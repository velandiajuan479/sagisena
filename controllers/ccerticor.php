<?php
require_once 'models/mcerticor.php';

// Instanciar modelo
$mcerticor = new Mcerticor();

// Variables para mensajes
$mensaje = '';
$tipoMensaje = '';

// OPERACIONES - PROCESAR ANTES DE CARGAR DATOS
$opera = $_REQUEST['opera'] ?? null;

// 1. OBTENER DETALLE DE SOLICITUD (AJAX)
if ($opera === 'obtener_detalle' && isset($_GET['id'])) {
    $idsolicitud = (int)$_GET['id'];
    
    try {
        error_log("Obteniendo detalle para solicitud ID: " . $idsolicitud);
        
        // Obtener detalle de la solicitud
        $detalleSolicitud = $mcerticor->getDetalleSolicitudConPrograma($idsolicitud);
        
        if (!$detalleSolicitud) {
            error_log("No se encontró la solicitud con ID: " . $idsolicitud);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Solicitud no encontrada con ID: ' . $idsolicitud
            ]);
            exit;
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'solicitud' => $detalleSolicitud
        ]);
        exit;
        
    } catch (Exception $e) {
        error_log("Error al obtener detalle: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error del servidor: ' . $e->getMessage()
        ]);
        exit;
    }
}

// 2. PROCESAR SOLICITUD INDIVIDUAL - VERSIÓN CORREGIDA
if ($opera === 'procesar_solicitud' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idsolicitud = (int)($_POST['idsolicitud'] ?? 0);
    $accion = $_POST['accion'] ?? '';
    $observaciones = trim($_POST['observaciones'] ?? '');
    
    error_log("=== INICIANDO PROCESAMIENTO SOLICITUD ===");
    error_log("ID Solicitud: " . $idsolicitud);
    error_log("Acción: " . $accion);
    error_log("Observaciones: " . $observaciones);
    
    if (empty($idsolicitud) || empty($accion)) {
        $mensaje = 'Error: Faltan datos obligatorios para procesar la solicitud.';
        $tipoMensaje = 'error';
        error_log("Error: Datos incompletos");
    } else {
        try {
            // Verificar que la solicitud existe
            $solicitudDetalle = $mcerticor->getDetalleSolicitudConPrograma($idsolicitud);
            
            if (!$solicitudDetalle) {
                $mensaje = 'Error: La solicitud no existe.';
                $tipoMensaje = 'error';
                error_log("Error: Solicitud no encontrada");
            } elseif ($solicitudDetalle['estado'] !== 'pendiente') {
                $mensaje = 'Error: La solicitud ya fue procesada anteriormente (Estado actual: ' . $solicitudDetalle['estado'] . ').';
                $tipoMensaje = 'warning';
                error_log("Error: Solicitud ya procesada - Estado: " . $solicitudDetalle['estado']);
            } else {
                // PROCESAR LA SOLICITUD - LÓGICA CORREGIDA
                $nuevoEstado = '';
                $observacionesCompletas = '';
                
                if ($accion === 'aprobar') {
                    $nuevoEstado = 'aprobada_coordinacion';
                    $observacionesCompletas = "✅ SOLICITUD APROBADA POR COORDINACIÓN\n";
                    $observacionesCompletas .= "📅 Fecha: " . date('d/m/Y H:i:s') . "\n";
                    $observacionesCompletas .= "🎓 Programa: " . ($solicitudDetalle['nombre_programa'] ?? 'N/A') . "\n";
                    if (!empty($observaciones)) {
                        $observacionesCompletas .= "📝 Observaciones: " . $observaciones;
                    }
                    
                } elseif ($accion === 'rechazar') {
                    $nuevoEstado = 'rechazada';
                    $observacionesCompletas = "❌ SOLICITUD RECHAZADA POR COORDINACIÓN\n";
                    $observacionesCompletas .= "📅 Fecha: " . date('d/m/Y H:i:s') . "\n";
                    $observacionesCompletas .= "🎓 Programa: " . ($solicitudDetalle['nombre_programa'] ?? 'N/A') . "\n";
                    if (!empty($observaciones)) {
                        $observacionesCompletas .= "📝 Motivo: " . $observaciones;
                    } else {
                        $observacionesCompletas .= "📝 Motivo: No cumple con los requisitos establecidos.";
                    }
                    
                } else {
                    $mensaje = 'Error: Acción no válida.';
                    $tipoMensaje = 'error';
                    error_log("Error: Acción no válida: " . $accion);
                }
                
                if (!empty($nuevoEstado)) {
                    error_log("Actualizando estado a: " . $nuevoEstado);
                    
                    // MÉTODO DE ACTUALIZACIÓN CORREGIDO
                    $resultado = $mcerticor->actualizarEstadoSolicitudCorregido(
                        $idsolicitud, 
                        $nuevoEstado, 
                        $observacionesCompletas
                    );
                    
                    if ($resultado) {
                        $accionTexto = ($accion === 'aprobar') ? 'aprobada' : 'rechazada';
                        $mensaje = "✅ La solicitud #$idsolicitud ha sido $accionTexto exitosamente.";
                        $tipoMensaje = 'success';
                        error_log("✅ Solicitud procesada correctamente");
                    } else {
                        $mensaje = '❌ Error al actualizar el estado en la base de datos.';
                        $tipoMensaje = 'error';
                        error_log("❌ Error en actualización BD");
                    }
                }
            }
            
        } catch (Exception $e) {
            error_log("❌ Exception: " . $e->getMessage());
            $mensaje = '❌ Error del sistema: ' . $e->getMessage();
            $tipoMensaje = 'error';
        }
    }
    
    error_log("=== FIN PROCESAMIENTO ===");
}

// CARGAR DATOS PARA LA VISTA
try {
    error_log("Cargando datos para la vista...");
    
    // Obtener estadísticas
    $datEstadisticas = $mcerticor->getEstadisticasSolicitudes();
    if (!$datEstadisticas) {
        $datEstadisticas = ['total' => 0, 'pendientes' => 0, 'aprobadas' => 0, 'completadas' => 0, 'rechazadas' => 0];
    }
    
    // Obtener solicitudes
    $datSolicitudes = $mcerticor->getAllSolicitudesDetalladas();
    if (!is_array($datSolicitudes)) {
        $datSolicitudes = [];
    }
    
    error_log("Datos cargados - Solicitudes: " . count($datSolicitudes));
    
} catch (Exception $e) {
    error_log("Error general: " . $e->getMessage());
    $mensaje = 'Error al cargar datos: ' . $e->getMessage();
    $tipoMensaje = 'error';
    $datEstadisticas = ['total' => 0, 'pendientes' => 0, 'aprobadas' => 0, 'completadas' => 0, 'rechazadas' => 0];
    $datSolicitudes = [];
}

// FUNCIONES AUXILIARES
function getTextoEstado($estado) {
    $estados = [
        'pendiente' => '🟡 Pendiente Revisión',
        'aprobada_coordinacion' => '✅ Aprobada - Esperando Firma', 
        'completada' => '🏁 Completada',
        'rechazada' => '❌ Rechazada'
    ];
    return $estados[$estado] ?? '⚪ ' . ucfirst($estado);
}

function formatearTipoCertificacion($tipo) {
    $tipos = [
        'certificacion_inicio' => '🎓 Inicio de Formación',
        'certificacion_parcial' => '📚 Competencias Parciales',
        'certificacion_etapa_productiva' => '🏢 Etapa Productiva', 
        'certificacion_tecnica_completa' => '🔧 Técnica Completa',
        'certificacion_tecnologica_completa' => '💻 Tecnológica Completa',
        'certificacion_curso_inicio' => '📖 Participación en Curso',
        'certificacion_curso_intermedio' => '📊 Progreso en Curso',
        'certificacion_curso_completo' => '🎉 Finalización de Curso',
        'constancia_estudiante_activo' => '👨‍🎓 Constancia de Estudiante'
    ];
    return $tipos[$tipo] ?? '📄 ' . ucfirst(str_replace('_', ' ', $tipo));
}
?>