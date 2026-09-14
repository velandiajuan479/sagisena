<?php

class Mcertisubd {
    
    // Obtener solicitudes aprobadas por coordinación (esperando firma del subdirector)
    public function getSolicitudesParaFirma($filtros = []) {
        try {
            error_log("=== OBTENIENDO SOLICITUDES PARA FIRMA ===");
            
            $sql = "SELECT 
                        s.id as idsolicitud, 
                        s.idusu, 
                        s.tipo_certificacion, 
                        s.motivo, 
                        s.fecha_solicitud, 
                        s.estado, 
                        s.respuesta as respuesta_coordinacion,
                        s.fecha_respuesta as fecha_respuesta_coordinacion,
                        u.nomusu, 
                        u.ndocusu, 
                        COALESCE(u.emausu, 'N/A') as emausu, 
                        COALESCE(u.telcan, 'N/A') as telcan,
                        COALESCE(f.nomfic, 'Sin programa') as nombre_programa,
                        COALESCE(f.idfic, 'N/A') as codigo_ficha,
                        TIMESTAMPDIFF(DAY, s.fecha_respuesta, NOW()) as dias_espera
                    FROM solicicerti s
                    INNER JOIN usuario u ON s.idusu = u.idusu
                    LEFT JOIN ficha f ON s.idfic = f.idfic
                    WHERE s.estado = 'aprobada_coordinacion'";
            
            $params = [];
            
            if (!empty($filtros['programa'])) {
                $sql .= " AND (f.nomfic LIKE :programa OR f.idfic LIKE :programa)";
                $params[':programa'] = '%' . $filtros['programa'] . '%';
            }
            
            if (!empty($filtros['estudiante'])) {
                $sql .= " AND (u.nomusu LIKE :estudiante OR u.ndocusu LIKE :estudiante)";
                $params[':estudiante'] = '%' . $filtros['estudiante'] . '%';
            }
            
            $sql .= " ORDER BY s.fecha_respuesta ASC";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("✅ Solicitudes encontradas: " . count($resultados));
            
            return $resultados;
            
        } catch (Exception $e) {
            error_log("❌ Error: " . $e->getMessage());
            return [];
        }
    }
    // Firmar certificación
    public function firmarCertificacion($idsolicitud, $firmaSubdirector, $observaciones = '', $subdirectorId = null) {
        try {
            // Convertir 'SI'/'NO' a 1/0 si viene como string
            $firmaValor = ($firmaSubdirector === 'SI' || $firmaSubdirector === 1) ? 1 : 0;
            
            $sql = "UPDATE solicicerti SET 
                        firma_subdirector = :firma_subdirector,
                        fecha_firma_subdirector = NOW(),
                        observaciones_finales = :observaciones  -- CORREGIDO
                    WHERE id = :idsolicitud AND estado = 'aprobada_coordinacion'";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindValue(':firma_subdirector', $firmaValor, PDO::PARAM_INT);
            $stmt->bindValue(':observaciones', $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(':idsolicitud', $idsolicitud, PDO::PARAM_INT);
            
            $resultado = $stmt->execute();
            
            if ($resultado && $stmt->rowCount() > 0) {
                $this->registrarLogFirma($idsolicitud, $subdirectorId, 'FIRMA_CERTIFICACION');
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("❌ Error: " . $e->getMessage());
            return false;
        }
    }


    // Registrar log de firma
    private function registrarLogFirma($idsolicitud, $subdirectorId, $tipoFirma) {
        try {
            $sql = "INSERT INTO log_firmas_certificaciones 
                    (idsolicitud, idusuario_firma, tipo_firma, fecha_firma) 
                    VALUES (:idsolicitud, :idusuario, :tipo_firma, NOW())";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindValue(':idsolicitud', $idsolicitud, PDO::PARAM_INT);
            $stmt->bindValue(':idusuario', $subdirectorId, PDO::PARAM_INT);
            $stmt->bindValue(':tipo_firma', $tipoFirma, PDO::PARAM_STR);
            $stmt->execute();
            
            error_log("Log de firma registrado correctamente");
            
        } catch (Exception $e) {
            error_log("Error al registrar log de firma: " . $e->getMessage());
            // No lanzar excepción, solo registrar el error
        }
    }
    
    // Firmar solicitud (aprobar final)
    public function firmarSolicitud($idsolicitud, $subdirectorId, $observacionesFinales = '') {
        try {
            error_log("=== FIRMANDO SOLICITUD ===");
            error_log("ID: $idsolicitud | Subdirector: $subdirectorId");
            
            $sql = "UPDATE solicicerti SET 
                        estado = 'completada',
                        firma_subdirector = 1,  -- CORREGIDO: era 'SI'
                        fecha_firma_subdirector = NOW(),
                        observaciones_finales = :observaciones  -- CORREGIDO: campo correcto
                    WHERE id = :idsolicitud AND estado = 'aprobada_coordinacion'";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindValue(':observaciones', $observacionesFinales, PDO::PARAM_STR);
            $stmt->bindValue(':idsolicitud', $idsolicitud, PDO::PARAM_INT);
            
            $resultado = $stmt->execute();
            $filasAfectadas = $stmt->rowCount();
            
            error_log("Filas afectadas: " . $filasAfectadas);
            
            if ($resultado && $filasAfectadas > 0) {
                $this->registrarLogFirma($idsolicitud, $subdirectorId, 'FIRMA_FINAL');
                error_log("✅ Solicitud firmada exitosamente");
                return ['success' => true, 'message' => 'Solicitud firmada exitosamente'];
            } else {
                // Verificar estado actual
                $sqlVerificar = "SELECT estado FROM solicicerti WHERE id = :idsolicitud";
                $stmtV = $conexion->prepare($sqlVerificar);
                $stmtV->bindValue(':idsolicitud', $idsolicitud, PDO::PARAM_INT);
                $stmtV->execute();
                $estadoActual = $stmtV->fetchColumn();
                
                error_log("❌ Estado actual: " . $estadoActual);
                return ['success' => false, 'message' => 'Estado actual: ' . $estadoActual];
            }
            
        } catch (Exception $e) {
            error_log("❌ Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    // Rechazar solicitud desde subdirección
    public function rechazarSolicitudSubdirector($idsolicitud, $subdirectorId, $motivoRechazo) {
        try {
            error_log("=== RECHAZANDO SOLICITUD DESDE SUBDIRECCIÓN ===");
            error_log("ID: $idsolicitud | Motivo: $motivoRechazo");
            
            $sql = "UPDATE solicicerti SET 
                        estado = 'rechazada',
                        fecha_firma_subdirector = NOW(),
                        observaciones_subdirector = :motivo
                    WHERE id = :idsolicitud AND estado = 'aprobada_coordinacion'";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindValue(':motivo', $motivoRechazo, PDO::PARAM_STR);
            $stmt->bindValue(':idsolicitud', $idsolicitud, PDO::PARAM_INT);
            
            $resultado = $stmt->execute();
            
            if ($resultado && $stmt->rowCount() > 0) {
                $this->registrarLogFirma($idsolicitud, $subdirectorId, 'RECHAZO_SUBDIRECTOR');
                error_log("✅ Solicitud rechazada correctamente");
                return true;
            }
            
            error_log("❌ No se rechazó ninguna solicitud");
            return false;
            
        } catch (Exception $e) {
            error_log("❌ Error al rechazar solicitud: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener estadísticas para el subdirector
    public function getEstadisticasSubdirector() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_esperando_firma,
                        SUM(CASE WHEN DATEDIFF(NOW(), fecha_respuesta_coordinacion) > 7 THEN 1 ELSE 0 END) as atrasadas,
                        SUM(CASE WHEN DATEDIFF(NOW(), fecha_respuesta_coordinacion) > 3 THEN 1 ELSE 0 END) as urgentes
                    FROM solicicerti 
                    WHERE estado = 'aprobada_coordinacion'";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $esperando = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Obtener estadísticas de solicitudes firmadas
            $sql2 = "SELECT 
                        COUNT(*) as total_firmadas,
                        COUNT(CASE WHEN DATE(fecha_firma_subdirector) = CURDATE() THEN 1 END) as firmadas_hoy,
                        COUNT(CASE WHEN WEEK(fecha_firma_subdirector) = WEEK(NOW()) 
                                    AND YEAR(fecha_firma_subdirector) = YEAR(NOW()) THEN 1 END) as firmadas_semana
                     FROM solicicerti 
                     WHERE firma_subdirector = 'SI' OR estado = 'completada'";
            
            $stmt2 = $conexion->prepare($sql2);
            $stmt2->execute();
            $firmadas = $stmt2->fetch(PDO::FETCH_ASSOC);
            
            $resultado = array_merge($esperando ?: [], $firmadas ?: []);
            
            error_log("Estadísticas subdirector: " . json_encode($resultado));
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [
                'total_esperando_firma' => 0,
                'atrasadas' => 0,
                'urgentes' => 0,
                'total_firmadas' => 0,
                'firmadas_hoy' => 0,
                'firmadas_semana' => 0
            ];
        }
    }
    
    // Obtener detalle de una solicitud específica
    public function getDetalleSolicitudParaFirma($idsolicitud) {
        try {
            $sql = "SELECT 
                        s.*,
                        u.nomusu, 
                        u.ndocusu, 
                        COALESCE(u.emausu, 'N/A') as emausu, 
                        COALESCE(u.telcan, 'N/A') as telcan,
                        COALESCE(f.nomfic, 'Sin programa') as nombre_programa,
                        COALESCE(f.idfic, 'N/A') as codigo_ficha,
                        TIMESTAMPDIFF(DAY, s.fecha_respuesta, NOW()) as dias_espera
                    FROM solicicerti s
                    INNER JOIN usuario u ON s.idusu = u.idusu
                    LEFT JOIN ficha f ON s.idfic = f.idfic
                    WHERE s.id = :idsolicitud";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':idsolicitud', $idsolicitud, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }
    // Obtener historial de firmas del subdirector
    public function getHistorialFirmas($subdirectorId = null, $limite = 50) {
        try {
            $sql = "SELECT 
                        s.id as idsolicitud,
                        s.tipo_certificacion,
                        s.fecha_firma_subdirector,
                        s.observaciones_finales,  -- CORREGIDO
                        u.nomusu,
                        u.ndocusu,
                        COALESCE(f.nomfic, 'Sin programa') as nombre_programa
                    FROM solicicerti s
                    INNER JOIN usuario u ON s.idusu = u.idusu
                    LEFT JOIN ficha f ON s.idfic = f.idfic
                    WHERE (s.firma_subdirector = 1 OR s.estado = 'completada')  -- CORREGIDO
                    AND s.fecha_firma_subdirector IS NOT NULL
                    ORDER BY s.fecha_firma_subdirector DESC 
                    LIMIT :limite";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return [];
        }
    }

    
    // Obtener resumen por coordinador
    public function getResumenPorCoordinador() {
        try {
            $sql = "SELECT 
                        'Coordinador General' as coordinador,
                        COUNT(*) as total_esperando,
                        AVG(DATEDIFF(NOW(), s.fecha_respuesta_coordinacion)) as dias_promedio_espera,
                        MIN(s.fecha_respuesta_coordinacion) as solicitud_mas_antigua
                    FROM solicicerti s
                    WHERE s.estado = 'aprobada_coordinacion'
                    HAVING COUNT(*) > 0";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Resumen por coordinador: " . count($resultados) . " registros");
            
            return $resultados;
            
        } catch (Exception $e) {
            error_log("Error al obtener resumen por coordinador: " . $e->getMessage());
            return [];
        }
    }
    
    // Firmar múltiples solicitudes (firma masiva)
    public function firmarSolicitudesMasivas($solicitudesIds, $subdirectorId, $observacionesGenerales = '') {
        try {
            error_log("=== FIRMA MASIVA INICIADA ===");
            error_log("Total solicitudes: " . count($solicitudesIds));
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $conexion->beginTransaction();
            
            $firmadas = 0;
            $errores = [];
            
            foreach ($solicitudesIds as $idsolicitud) {
                $resultado = $this->firmarSolicitud($idsolicitud, $subdirectorId, $observacionesGenerales);
                if ($resultado['success']) {
                    $firmadas++;
                    error_log("✅ Solicitud $idsolicitud firmada");
                } else {
                    $errores[] = "ID {$idsolicitud}: " . $resultado['message'];
                    error_log("❌ Error en solicitud $idsolicitud: " . $resultado['message']);
                }
            }
            
            $conexion->commit();
            
            error_log("=== FIRMA MASIVA COMPLETADA ===");
            error_log("Firmadas: $firmadas / " . count($solicitudesIds));
            
            return [
                'success' => $firmadas > 0,
                'firmadas' => $firmadas,
                'total' => count($solicitudesIds),
                'errores' => $errores
            ];
            
        } catch (Exception $e) {
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            error_log("❌ Error en firma masiva: " . $e->getMessage());
            return [
                'success' => false,
                'firmadas' => 0,
                'total' => count($solicitudesIds),
                'errores' => ['Error del sistema: ' . $e->getMessage()]
            ];
        }
    }

    // Obtener estadísticas avanzadas para el subdirector
    public function getEstadisticasAvanzadas() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_solicitudes,
                        COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes,
                        COUNT(CASE WHEN estado = 'aprobada_coordinacion' THEN 1 END) as esperando_firma,
                        COUNT(CASE WHEN estado = 'completada' THEN 1 END) as completadas,
                        COUNT(CASE WHEN estado = 'rechazada' THEN 1 END) as rechazadas,
                        COUNT(CASE WHEN firma_subdirector = 1 OR estado = 'completada' THEN 1 END) as firmadas_total,  -- CORREGIDO
                        SUM(CASE WHEN DATEDIFF(NOW(), fecha_respuesta_coordinacion) > 7 
                            AND estado = 'aprobada_coordinacion' THEN 1 ELSE 0 END) as atrasadas,
                        SUM(CASE WHEN DATEDIFF(NOW(), fecha_respuesta_coordinacion) > 3 
                            AND estado = 'aprobada_coordinacion' THEN 1 ELSE 0 END) as urgentes,
                        COUNT(CASE WHEN DATE(fecha_firma_subdirector) = CURDATE() THEN 1 END) as firmadas_hoy,
                        COUNT(CASE WHEN WEEK(fecha_firma_subdirector) = WEEK(NOW()) 
                                    AND YEAR(fecha_firma_subdirector) = YEAR(NOW()) THEN 1 END) as firmadas_semana,
                        AVG(CASE 
                            WHEN fecha_firma_subdirector IS NOT NULL 
                                AND fecha_respuesta_coordinacion IS NOT NULL
                            THEN TIMESTAMPDIFF(HOUR, fecha_respuesta_coordinacion, fecha_firma_subdirector) 
                        END) as tiempo_promedio_firma_horas
                    FROM solicicerti";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_solicitudes' => (int)($resultado['total_solicitudes'] ?? 0),
                'pendientes' => (int)($resultado['pendientes'] ?? 0),
                'esperando_firma' => (int)($resultado['esperando_firma'] ?? 0),
                'total_esperando_firma' => (int)($resultado['esperando_firma'] ?? 0),
                'completadas' => (int)($resultado['completadas'] ?? 0),
                'rechazadas' => (int)($resultado['rechazadas'] ?? 0),
                'firmadas_total' => (int)($resultado['firmadas_total'] ?? 0),
                'total_firmadas' => (int)($resultado['firmadas_total'] ?? 0),
                'atrasadas' => (int)($resultado['atrasadas'] ?? 0),
                'urgentes' => (int)($resultado['urgentes'] ?? 0),
                'firmadas_hoy' => (int)($resultado['firmadas_hoy'] ?? 0),
                'firmadas_semana' => (int)($resultado['firmadas_semana'] ?? 0),
                'tiempo_promedio_firma_horas' => round((float)($resultado['tiempo_promedio_firma_horas'] ?? 0), 2)
            ];
            
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return [
                'total_solicitudes' => 0,
                'pendientes' => 0,
                'esperando_firma' => 0,
                'total_esperando_firma' => 0,
                'completadas' => 0,
                'rechazadas' => 0,
                'firmadas_total' => 0,
                'total_firmadas' => 0,
                'atrasadas' => 0,
                'urgentes' => 0,
                'firmadas_hoy' => 0,
                'firmadas_semana' => 0,
                'tiempo_promedio_firma_horas' => 0
            ];
        }
    }

}

?>