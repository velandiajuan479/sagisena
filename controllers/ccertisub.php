<?php
require_once 'models/mcertisub.php';

// Instanciar modelo
$mcertisubd = new Mcertisubd();

// Obtener ID del subdirector desde sesión
$subdirectorId = $_SESSION["idusu"];

// Inicializar variables
$mensaje = '';
$tipoMensaje = '';

// Manejar operaciones
$opera = $_REQUEST['opera'] ?? null;

// Procesar filtros mejorados
$filtros = [];
if ($_POST) {
    $filtros = [
        'programa' => $_POST['filtro_programa'] ?? '',
        'estudiante' => $_POST['filtro_estudiante'] ?? '',
        'tipo_certificacion' => $_POST['filtro_tipo'] ?? '',
        'tipo_programa' => $_POST['filtro_tipo_programa'] ?? '',
        'coordinador' => $_POST['filtro_coordinador'] ?? '',
        'fecha_desde' => $_POST['filtro_fecha_desde'] ?? '',
        'fecha_hasta' => $_POST['filtro_fecha_hasta'] ?? ''
    ];
    
    // Procesar filtro de prioridad
    if (!empty($_POST['filtro_prioridad'])) {
        switch ($_POST['filtro_prioridad']) {
            case 'atrasada':
                $filtros['dias_minimo'] = 8; // Más de 7 días
                break;
            case 'urgente':
                $filtros['dias_minimo'] = 4; // Entre 3 y 7 días
                $filtros['dias_maximo'] = 7;
                break;
            case 'normal':
                $filtros['dias_maximo'] = 3; // Menos de 3 días
                break;
        }
    }
    
    // Filtrar valores vacíos
    $filtros = array_filter($filtros);
}

switch ($opera) {
    case 'firmar':
        $idsolicitud = $_POST['idsolicitud'] ?? null;
        $observacionesFinales = trim($_POST['observaciones_finales'] ?? '');
        
        if ($idsolicitud) {
            $resultado = $mcertisubd->firmarSolicitud($idsolicitud, $subdirectorId, $observacionesFinales);
            
            if ($resultado['success']) {
                $mensaje = 'Solicitud firmada exitosamente. Certificación generada con número: ' . $resultado['numero_certificacion'];
                $tipoMensaje = 'success';
                
                // Log de la operación exitosa
                logActividad($subdirectorId, 'FIRMA_INDIVIDUAL', "Solicitud ID: $idsolicitud, Certificado: " . $resultado['numero_certificacion']);
                
                // Opcional: Enviar notificación automática
                // notificarSolicitudCompletada($idsolicitud, $resultado['numero_certificacion']);
            } else {
                $mensaje = $resultado['message'];
                $tipoMensaje = 'error';
                
                // Log del error
                logActividad($subdirectorId, 'ERROR_FIRMA', "Solicitud ID: $idsolicitud - Error: " . $resultado['message']);
            }
        } else {
            $mensaje = 'ID de solicitud no válido.';
            $tipoMensaje = 'error';
        }
        break;
        
    case 'rechazar_subdirector':
        $idsolicitud = $_POST['idsolicitud'] ?? null;
        $motivoRechazo = trim($_POST['motivo_rechazo'] ?? '');
        
        if ($idsolicitud && !empty($motivoRechazo)) {
            $resultado = $mcertisubd->rechazarSolicitudSubdirector($idsolicitud, $subdirectorId, $motivoRechazo);
            
            if ($resultado) {
                $mensaje = 'Solicitud rechazada exitosamente. Se han notificado el coordinador y estudiante.';
                $tipoMensaje = 'warning';
                
                // Log del rechazo
                logActividad($subdirectorId, 'RECHAZO_SUBDIRECTOR', "Solicitud ID: $idsolicitud - Motivo: " . substr($motivoRechazo, 0, 100));
                
                // Opcional: Enviar notificación automática
                // notificarSolicitudRechazada($idsolicitud, $motivoRechazo);
            } else {
                $mensaje = 'Error al rechazar la solicitud.';
                $tipoMensaje = 'error';
            }
        } else {
            $mensaje = 'Debes proporcionar un motivo válido para el rechazo.';
            $tipoMensaje = 'error';
        }
        break;
        
    case 'firmar_masivo':
        $solicitudesIds = $_POST['solicitudes_ids'] ?? [];
        $observacionesGenerales = trim($_POST['observaciones_generales'] ?? '');
        
        if (!empty($solicitudesIds) && is_array($solicitudesIds)) {
            $resultado = $mcertisubd->firmarSolicitudesMasivas($solicitudesIds, $subdirectorId, $observacionesGenerales);
            
            if ($resultado['success']) {
                $mensaje = "Firma masiva completada exitosamente:<br>";
                $mensaje .= "✅ <strong>{$resultado['firmadas']}</strong> de {$resultado['total']} solicitudes firmadas.<br>";
                
                if (!empty($resultado['certificaciones_generadas'])) {
                    $mensaje .= "📋 Certificaciones generadas: " . count($resultado['certificaciones_generadas']);
                }
                
                $tipoMensaje = 'success';
                
                // Log de la operación masiva
                logActividad($subdirectorId, 'FIRMA_MASIVA', "Total: {$resultado['total']}, Exitosas: {$resultado['firmadas']}, Errores: " . count($resultado['errores']));
                
                if (!empty($resultado['errores'])) {
                    $mensaje .= "<br><br><strong>Errores encontrados:</strong><br>";
                    foreach (array_slice($resultado['errores'], 0, 3) as $error) {
                        $mensaje .= "❌ " . htmlspecialchars($error) . "<br>";
                    }
                    if (count($resultado['errores']) > 3) {
                        $mensaje .= "... y " . (count($resultado['errores']) - 3) . " error(es) más.";
                    }
                }
            } else {
                $mensaje = "Error en firma masiva:<br>" . implode('<br>', $resultado['errores']);
                $tipoMensaje = 'error';
            }
        } else {
            $mensaje = 'Debes seleccionar al menos una solicitud para firmar masivamente.';
            $tipoMensaje = 'error';
        }
        break;
        
    case 'detalle':
        $idsolicitud = $_REQUEST['idsolicitud'] ?? null;
        
        if ($idsolicitud) {
            $datDetalle = $mcertisubd->getDetalleSolicitudParaFirma($idsolicitud);
            
            if ($datDetalle) {
                // Calcular días de espera y enriquecer información
                $fechaAprobacion = new DateTime($datDetalle['fecha_respuesta_coordinacion']);
                $fechaActual = new DateTime();
                $diasEspera = $fechaActual->diff($fechaAprobacion)->days;
                
                $datDetalle['dias_espera'] = $diasEspera;
                $datDetalle['es_urgente'] = $diasEspera > 3;
                $datDetalle['es_atrasada'] = $diasEspera > 7;
                $datDetalle['prioridad_texto'] = getTextoPrioridad($diasEspera);
                $datDetalle['prioridad_clase'] = getClasePrioridad($diasEspera);
                
                // Agregar información adicional si está disponible
                if (isset($datDetalle['trimestre_actual'])) {
                    $datDetalle['progreso_descripcion'] = generarDescripcionProgreso($datDetalle);
                }
                
                // Retornar JSON para modal
                header('Content-Type: application/json');
                echo json_encode([
                    'solicitud' => $datDetalle,
                    'success' => true
                ]);
                exit;
            }
        }
        
        // Si llegamos aquí, hubo error
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Solicitud no encontrada']);
        exit;
        
    case 'exportar':
        // Exportar solicitudes pendientes
        try {
            $solicitudes = $mcertisubd->getSolicitudesParaFirma($filtros);
            
            if (!empty($solicitudes)) {
                $nombreArchivo = generarExportacionSolicitudes($solicitudes);
                
                if ($nombreArchivo) {
                    // Forzar descarga
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
                    header('Content-Length: ' . filesize('temp/exports/' . $nombreArchivo));
                    readfile('temp/exports/' . $nombreArchivo);
                    
                    logActividad($subdirectorId, 'EXPORTACION', "Archivo: $nombreArchivo, Registros: " . count($solicitudes));
                    exit;
                } else {
                    $mensaje = 'Error al generar el archivo de exportación.';
                    $tipoMensaje = 'error';
                }
            } else {
                $mensaje = 'No hay solicitudes para exportar con los filtros seleccionados.';
                $tipoMensaje = 'warning';
            }
        } catch (Exception $e) {
            $mensaje = 'Error en la exportación: ' . $e->getMessage();
            $tipoMensaje = 'error';
        }
        break;
        
    case 'estadisticas_avanzadas':
        // Retornar estadísticas en tiempo real (AJAX)
        $estadisticas = $mcertisubd->getEstadisticasAvanzadas();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'estadisticas' => $estadisticas,
            'timestamp' => time()
        ]);
        exit;
}

// Obtener datos para la vista con las mejoras
$datSolicitudesPendientes = $mcertisubd->getSolicitudesParaFirma($filtros);
$datEstadisticas = $mcertisubd->getEstadisticasAvanzadas();
$datHistorialFirmas = $mcertisubd->getHistorialFirmas($subdirectorId, 50); // Últimas 50 firmas
$datResumenCoordinadores = $mcertisubd->getResumenPorCoordinador();

// Aplicar filtros adicionales en PHP si es necesario
if (isset($filtros['dias_minimo']) || isset($filtros['dias_maximo'])) {
    $datSolicitudesPendientes = array_filter($datSolicitudesPendientes, function($solicitud) use ($filtros) {
        $diasEspera = calcularDiasEspera($solicitud['fecha_respuesta_coordinacion']);
        
        if (isset($filtros['dias_minimo']) && $diasEspera < $filtros['dias_minimo']) {
            return false;
        }
        
        if (isset($filtros['dias_maximo']) && $diasEspera > $filtros['dias_maximo']) {
            return false;
        }
        
        return true;
    });
}

// Funciones auxiliares mejoradas
function mostrarAlerta($mensaje, $tipo) {
    if (empty($mensaje)) return;
    
    $clase = '';
    $icono = '';
    
    switch ($tipo) {
        case 'success':
            $clase = 'alert-success';
            $icono = 'fa-check-circle';
            break;
        case 'error':
            $clase = 'alert-danger';
            $icono = 'fa-exclamation-triangle';
            break;
        case 'warning':
            $clase = 'alert-warning';
            $icono = 'fa-exclamation-circle';
            break;
        case 'info':
            $clase = 'alert-info';
            $icono = 'fa-info-circle';
            break;
        default:
            $clase = 'alert-info';
            $icono = 'fa-info-circle';
    }
    
    echo "<div class='alert $clase alert-dismissible fade show' role='alert'>
            <i class='fa $icono me-2'></i>$mensaje
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
}

function formatearFecha($fecha) {
    return $fecha ? date('d/m/Y H:i', strtotime($fecha)) : 'N/A';
}

function formatearFechaCorta($fecha) {
    return $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A';
}

function getClasePrioridad($diasEspera) {
    if ($diasEspera > 7) return 'danger';      // Crítica
    if ($diasEspera > 3) return 'warning';     // Urgente
    if ($diasEspera > 1) return 'info';        // Normal
    return 'success';                          // Reciente
}

function getTextoPrioridad($diasEspera) {
    if ($diasEspera > 7) return 'CRÍTICA';
    if ($diasEspera > 3) return 'URGENTE';
    if ($diasEspera > 1) return 'NORMAL';
    return 'RECIENTE';
}

function calcularDiasEspera($fechaAprobacion) {
    $fecha = new DateTime($fechaAprobacion);
    $ahora = new DateTime();
    return $ahora->diff($fecha)->days;
}

function generarDescripcionProgreso($datDetalle) {
    $descripcion = "Estudiante en ";
    
    if ($datDetalle['trimestre_actual'] > 0) {
        $descripcion .= "trimestre {$datDetalle['trimestre_actual']} ";
        
        if (isset($datDetalle['porcentaje_avance'])) {
            $descripcion .= "({$datDetalle['porcentaje_avance']}% completado) ";
        }
    }
    
    if ($datDetalle['total_bitacoras'] > 0) {
        $descripcion .= "con {$datDetalle['bitacoras_completas']}/{$datDetalle['total_bitacoras']} bitácoras completadas";
    }
    
    return $descripcion;
}

function generarExportacionSolicitudes($solicitudes) {
    try {
        $nombreArchivo = 'solicitudes_firma_' . date('Y-m-d_H-i-s') . '.csv';
        $rutaCompleta = 'temp/exports/' . $nombreArchivo;
        
        // Crear directorio si no existe
        if (!is_dir('temp/exports')) {
            mkdir('temp/exports', 0755, true);
        }
        
        $archivo = fopen($rutaCompleta, 'w');
        
        // Escribir BOM para UTF-8
        fwrite($archivo, "\xEF\xBB\xBF");
        
        // Encabezados
        fputcsv($archivo, [
            'ID Solicitud',
            'Estudiante',
            'Documento',
            'Email',
            'Programa',
            'Tipo Programa', 
            'Ficha',
            'Tipo Certificación',
            'Fecha Solicitud',
            'Coordinador Aprobó',
            'Fecha Aprobación',
            'Días Esperando',
            'Prioridad',
            'Trimestre Actual',
            'Porcentaje Avance',
            'Bitácoras Completas',
            'Total Bitácoras',
            'Motivo Original'
        ], ';');
        
        // Datos
        foreach ($solicitudes as $sol) {
            $diasEspera = calcularDiasEspera($sol['fecha_respuesta_coordinacion']);
            
            fputcsv($archivo, [
                $sol['idsolicitud'],
                $sol['nomusu'],
                $sol['ndocusu'],
                $sol['emausu'],
                $sol['nombre_programa'],
                $sol['tipo_programa'] ?? 'N/A',
                $sol['codigo_ficha'],
                formatearTipoCertificacion($sol['tipo_certificacion']),
                date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])),
                $sol['nombre_coordinador'],
                date('d/m/Y H:i', strtotime($sol['fecha_respuesta_coordinacion'])),
                $diasEspera,
                getTextoPrioridad($diasEspera),
                $sol['trimestre_actual'] ?? 'N/A',
                isset($sol['porcentaje_avance']) ? round($sol['porcentaje_avance'], 1) . '%' : 'N/A',
                $sol['bitacoras_completas'] ?? 'N/A',
                $sol['total_bitacoras'] ?? 'N/A',
                substr(strip_tags($sol['motivo']), 0, 200) . '...'
            ], ';');
        }
        
        fclose($archivo);
        return $nombreArchivo;
        
    } catch (Exception $e) {
        error_log("Error generando exportación: " . $e->getMessage());
        return false;
    }
}

function formatearTipoCertificacion($tipo) {
    $tipos = [
        'certificacion_inicio' => 'Inicio de Formación',
        'certificacion_parcial' => 'Competencias Parciales', 
        'certificacion_etapa_productiva' => 'Etapa Productiva',
        'certificacion_tecnica_completa' => 'Técnica Completa',
        'certificacion_tecnologica_completa' => 'Tecnológica Completa',
        'certificacion_curso_completo' => 'Finalización de Curso',
        'constancia_estudiante_activo' => 'Constancia de Estudiante'
    ];
    
    return $tipos[$tipo] ?? ucfirst(str_replace('_', ' ', $tipo));
}

function logActividad($usuarioId, $accion, $detalle = '') {
    try {
        $mensaje = date('Y-m-d H:i:s') . " - Usuario ID: $usuarioId - Acción: $accion";
        if (!empty($detalle)) {
            $mensaje .= " - Detalle: $detalle";
        }
        
        // Log en archivo específico del subdirector
        $logFile = 'logs/subdirector_' . date('Y-m') . '.log';
        
        // Crear directorio si no existe
        if (!is_dir('logs')) {
            mkdir('logs', 0755, true);
        }
        
        error_log($mensaje . PHP_EOL, 3, $logFile);
        
        // También log general del sistema
        error_log("SUBDIRECTOR - $mensaje");
        
    } catch (Exception $e) {
        error_log("Error en log de actividad: " . $e->getMessage());
    }
}

// Funciones de notificación (opcional - para implementar después)
function notificarSolicitudCompletada($idsolicitud, $numeroCertificacion) {
    // Implementar notificación por email/SMS al estudiante
    // Esta función se puede desarrollar más adelante
    logActividad($_SESSION["idusu"], 'NOTIFICACION_ENVIADA', "Solicitud: $idsolicitud, Certificado: $numeroCertificacion");
    return true;
}

function notificarSolicitudRechazada($idsolicitud, $motivo) {
    // Implementar notificación por email al coordinador y estudiante
    logActividad($_SESSION["idusu"], 'NOTIFICACION_RECHAZO', "Solicitud: $idsolicitud");
    return true;
}


// Log de acceso a la página
logActividad($subdirectorId, 'ACCESO_FIRMAS', 'Total pendientes: ' . count($datSolicitudesPendientes));

// Verificar alertas automáticas
$alertasAutomaticas = [];

if (($datEstadisticas['atrasadas'] ?? 0) > 10) {
    $alertasAutomaticas[] = [
        'tipo' => 'danger',
        'mensaje' => 'Tienes ' . $datEstadisticas['atrasadas'] . ' solicitudes críticas (>7 días). Se recomienda procesarlas inmediatamente.'
    ];
}

if (($datEstadisticas['urgentes'] ?? 0) > 20) {
    $alertasAutomaticas[] = [
        'tipo' => 'warning', 
        'mensaje' => 'Hay ' . $datEstadisticas['urgentes'] . ' solicitudes urgentes (3-7 días) esperando tu firma.'
    ];
}

if (($datEstadisticas['tiempo_promedio_firma_horas'] ?? 0) > 72) {
    $alertasAutomaticas[] = [
        'tipo' => 'info',
        'mensaje' => 'El tiempo promedio de firma es de ' . round($datEstadisticas['tiempo_promedio_firma_horas'], 1) . ' horas. Considera optimizar el proceso.'
    ];
}

// Mostrar alertas automáticas si las hay
if (!empty($alertasAutomaticas) && empty($mensaje)) {
    $alerta = $alertasAutomaticas[0]; // Mostrar la más importante
    $mensaje = $alerta['mensaje'];
    $tipoMensaje = $alerta['tipo'];
}

?>