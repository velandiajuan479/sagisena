<?php
// MODELO PARA COORDINADOR - GESTION DE CERTIFICACIONES

class McertiCor {
    
    // Obtener todas las solicitudes con filtros
    public function getAllSolicitudesConFiltros($filtroEstado = '', $filtroFicha = '', $filtroPrograma = '', $filtroEstudiante = '') {
        try {
            $sql = "SELECT s.*, u.nomusu, u.ndocusu, u.emausu, 
                           p.nomfic as nombre_programa, f.numficha, f.nomficha,
                           coord.nomusu as nombre_coordinador
                    FROM soliciCerti s
                    INNER JOIN usuario u ON s.idusu = u.idusu
                    LEFT JOIN programa p ON u.idprograma = p.idprograma
                    LEFT JOIN ficha f ON u.idficha = f.idficha
                    LEFT JOIN usuario coord ON s.respondido_por = coord.idusu
                    WHERE 1=1";
            
            $params = [];
            
            // Aplicar filtros
            if (!empty($filtroEstado)) {
                $sql .= " AND s.estado = :estado";
                $params[':estado'] = $filtroEstado;
            }
            
            if (!empty($filtroFicha)) {
                $sql .= " AND f.numficha LIKE :ficha";
                $params[':ficha'] = "%$filtroFicha%";
            }
            
            if (!empty($filtroPrograma)) {
                $sql .= " AND p.nomfic LIKE :programa";
                $params[':programa'] = "%$filtroPrograma%";
            }
            
            if (!empty($filtroEstudiante)) {
                $sql .= " AND (u.nomusu LIKE :estudiante OR u.ndocusu LIKE :estudiante2)";
                $params[':estudiante'] = "%$filtroEstudiante%";
                $params[':estudiante2'] = "%$filtroEstudiante%";
            }
            
            $sql .= " ORDER BY s.fecha_solicitud DESC";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener solicitudes: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener estadísticas de solicitudes
    public function getEstadisticasSolicitudes() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_solicitudes,
                        SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                        SUM(CASE WHEN estado = 'aprobada' THEN 1 ELSE 0 END) as aprobadas,
                        SUM(CASE WHEN estado = 'rechazada' THEN 1 ELSE 0 END) as rechazadas
                    FROM soliciCerti";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [];
        }
    }
    
    // Actualizar estado de solicitud
    public function updateSolicitudEstado($idsolicitud, $nuevoEstado, $observaciones = '', $respondidoPor = null) {
        try {
            $sql = "UPDATE soliciCerti SET 
                    estado = :estado,
                    observaciones = :observaciones,
                    fecha_respuesta = NOW(),
                    respondido_por = :respondido_por
                    WHERE idsolicitud = :idsolicitud";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(':estado', $nuevoEstado);
            $stmt->bindParam(':observaciones', $observaciones);
            $stmt->bindParam(':respondido_por', $respondidoPor);
            $stmt->bindParam(':idsolicitud', $idsolicitud);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al actualizar solicitud: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener una solicitud específica
    public function getSolicitudById($idsolicitud) {
        try {
            $sql = "SELECT s.*, u.nomusu, u.ndocusu, u.emausu, u.telcan,
                           p.nomfic as nombre_programa, f.numficha, f.nomficha,
                           coord.nomusu as nombre_coordinador
                    FROM soliciCerti s
                    INNER JOIN usuario u ON s.idusu = u.idusu
                    LEFT JOIN programa p ON u.idprograma = p.idprograma
                    LEFT JOIN ficha f ON u.idficha = f.idficha
                    LEFT JOIN usuario coord ON s.respondido_por = coord.idusu
                    WHERE s.idsolicitud = :idsolicitud";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idsolicitud', $idsolicitud);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener solicitud: " . $e->getMessage());
            return null;
        }
    }
    
    // Obtener certificaciones de un estudiante específico
    public function getCertificacionesEstudiante($idusu) {
        try {
            $sql = "SELECT c.*, u.nomusu, u.ndocusu, p.nomfic as nombre_programa, f.numficha
                    FROM certificaciones c
                    INNER JOIN usuario u ON c.idaprendiz = u.idusu
                    LEFT JOIN programa p ON u.idprograma = p.idprograma
                    LEFT JOIN ficha f ON u.idficha = f.idficha
                    WHERE c.idaprendiz = :idusu
                    ORDER BY c.fecha_obtencion DESC";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idusu', $idusu);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener certificaciones del estudiante: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener todas las fichas para filtros
    public function getAllFichas() {
        try {
            $sql = "SELECT DISTINCT f.idficha, f.numficha, f.nomficha, p.nomfic
                    FROM ficha f
                    INNER JOIN programa p ON f.idprograma = p.idprograma
                    ORDER BY f.numficha";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener fichas: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener todos los programas para filtros
    public function getAllProgramas() {
        try {
            $sql = "SELECT DISTINCT idprograma, nomfic FROM programa ORDER BY nomfic";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener programas: " . $e->getMessage());
            return [];
        }
    }
    
    // Crear nueva certificación desde coordinación
    public function crearCertificacionDesdeCoordinacion($datCerti) {
        try {
            $sql = "INSERT INTO certificaciones (
                idaprendiz, nombre_certificacion, descripcion, fecha_obtencion, 
                fecha_vencimiento, entidad_emisora, numero_certificacion, 
                imagen_certificacion, archivo_certificacion, estado, tipo_certificacion
            ) VALUES (
                :idaprendiz, :nombre_certificacion, :descripcion, :fecha_obtencion,
                :fecha_vencimiento, :entidad_emisora, :numero_certificacion,
                :imagen_certificacion, :archivo_certificacion, :estado, :tipo_certificacion
            )";

            $modelo = new conexion(); 
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            
            foreach ($datCerti as $key => $value) {
                $result->bindValue(":$key", $value);
            }

            return $result->execute();
            
        } catch (Exception $e) {
            error_log("Error al crear certificación: " . $e->getMessage());
            return false;
        }
    }
    
    // Obtener resumen completo de certificaciones por programa/ficha
    public function getResumenCertificacionesPorPrograma() {
        try {
            $sql = "SELECT 
                        p.nomfic as programa,
                        f.numficha as ficha,
                        COUNT(DISTINCT u.idusu) as total_estudiantes,
                        COUNT(c.idcertificacion) as total_certificaciones,
                        SUM(CASE WHEN c.estado = 'activo' THEN 1 ELSE 0 END) as activas,
                        SUM(CASE WHEN c.estado = 'vencido' THEN 1 ELSE 0 END) as vencidas,
                        SUM(CASE WHEN c.estado = 'por_vencer' THEN 1 ELSE 0 END) as por_vencer
                    FROM programa p
                    LEFT JOIN ficha f ON p.idprograma = f.idprograma
                    LEFT JOIN usuario u ON f.idficha = u.idficha
                    LEFT JOIN certificaciones c ON u.idusu = c.idaprendiz
                    GROUP BY p.idprograma, f.idficha
                    ORDER BY p.nomfic, f.numficha";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener resumen por programa: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener estudiantes sin certificaciones
    public function getEstudiantesSinCertificaciones() {
        try {
            $sql = "SELECT u.idusu, u.nomusu, u.ndocusu, u.emausu,
                           p.nomfic as programa, f.numficha, f.nomficha
                    FROM usuario u
                    INNER JOIN programa p ON u.idprograma = p.idprograma
                    INNER JOIN ficha f ON u.idficha = f.idficha
                    LEFT JOIN certificaciones c ON u.idusu = c.idaprendiz
                    WHERE c.idcertificacion IS NULL
                    AND u.idperfil = 1  -- Asumiendo que 1 es el perfil de estudiante
                    ORDER BY p.nomfic, f.numficha, u.nomusu";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error al obtener estudiantes sin certificaciones: " . $e->getMessage());
            return [];
        }
    }
    
    // Validar archivo subido
    public function validarArchivo($archivo, $tiposPermitidos = ['pdf', 'jpg', 'jpeg', 'png']) {
        if (!isset($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            return ['valido' => false, 'error' => 'No se subió ningún archivo'];
        }
        
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $tiposPermitidos)) {
            return ['valido' => false, 'error' => 'Tipo de archivo no permitido'];
        }
        
        // Validar tamaño (máximo 10MB para coordinador)
        if ($archivo['size'] > 10 * 1024 * 1024) {
            return ['valido' => false, 'error' => 'Archivo muy grande (máximo 10MB)'];
        }
        
        return ['valido' => true, 'error' => ''];
    }
    
    // Subir archivo de certificación
    public function subirArchivoCertificacion($archivo, $directorio = 'uploads/certificaciones/') {
        $validacion = $this->validarArchivo($archivo);
        
        if (!$validacion['valido']) {
            return ['exito' => false, 'error' => $validacion['error']];
        }
        
        // Crear directorio si no existe
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }
        
        // Generar nombre único
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $nombreArchivo = 'coord_' . uniqid() . '_' . time() . '.' . $extension;
        $rutaCompleta = $directorio . $nombreArchivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            return ['exito' => true, 'ruta' => $rutaCompleta, 'nombre' => $nombreArchivo];
        } else {
            return ['exito' => false, 'error' => 'Error al mover el archivo'];
        }
    }
    
    // Eliminar certificación
    public function eliminarCertificacion($idcertificacion) {
        try {
            // Primero obtener la ruta del archivo para eliminarlo
            $sql = "SELECT imagen_certificacion, archivo_certificacion FROM certificaciones WHERE idcertificacion = :id";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $idcertificacion);
            $stmt->execute();
            $certificacion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Eliminar archivos físicos
            if ($certificacion) {
                if (!empty($certificacion['imagen_certificacion']) && file_exists($certificacion['imagen_certificacion'])) {
                    unlink($certificacion['imagen_certificacion']);
                }
                if (!empty($certificacion['archivo_certificacion']) && file_exists($certificacion['archivo_certificacion'])) {
                    unlink($certificacion['archivo_certificacion']);
                }
            }
            
            // Eliminar de la base de datos
            $sql = "DELETE FROM certificaciones WHERE idcertificacion = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $idcertificacion);
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Error al eliminar certificación: " . $e->getMessage());
            return false;
        }
    }
}
?>