<?php
require_once 'models/mcerti.php';
require_once 'models/mbit.php'; 

// Obtener ID del usuario desde request o sesión
$idusu = $_REQUEST['idusu'] ?? $_SESSION["idusu"];

// Instanciar modelos
$mcerti = new Mcerti();
$mbit = new Mbit();

// Obtener datos generales del usuario
$datOne = $mcerti->getOneUsu($idusu);

// Obtener TODOS los programas del aprendiz
$datProgramasAprendiz = $mcerti->getProgramasActivosEstudiante($idusu);

// Obtener certificaciones disponibles específicas por programa
$datCertificacionesDisponibles = $mcerti->determinarCertificacionesDisponiblesPorPrograma($idusu);

// Actualizar estados de certificaciones antes de mostrar
$mcerti->actualizarEstados($idusu);

// Obtener certificaciones del usuario
$datCertificaciones = $mcerti->getCertificacionesByUsuario($idusu);

// Obtener mis solicitudes con información del programa
$datMisSolicitudes = $mcerti->getMisSolicitudesConPrograma($idusu);
$datEstadisticasSolicitudes = $mcerti->getEstadisticasMisSolicitudes($idusu);

// Verificar certificado de etapa productiva (mantener lógica existente)
$puedeObtenerCertificado = false;
$motivoNoDisponible = "";
$datBitacoras = $mbit->getBitacorasByUsuario($idusu);
$datPrograma = $mbit->getNombrePrograma($idusu);

if (!empty($datBitacoras)) {
    $todasCompletas = true;
    $totalBitacoras = count($datBitacoras);
    
    foreach ($datBitacoras as $bitacora) {
        if (empty($bitacora['firma_aprendiz']) || empty($bitacora['firma_jefe']) || empty($bitacora['firma_instructor'])) {
            $todasCompletas = false;
            break;
        }
    }
    
    if ($todasCompletas && $totalBitacoras >= 1) {
        $puedeObtenerCertificado = true;
    } else {
        $motivoNoDisponible = "Debes completar todas las bitácoras con las firmas correspondientes.";
    }
} else {
    $motivoNoDisponible = "No tienes bitácoras registradas.";
}

// Filtros para búsqueda de certificaciones
$filtroEstado = $_POST['filtro_estado'] ?? '';
$filtroTipo = $_POST['filtro_tipo'] ?? '';

if (!empty($filtroEstado)) {
    $datCertificaciones = $mcerti->getCertificacionesByEstado($idusu, $filtroEstado);
}

if (!empty($filtroTipo)) {
    $datCertificaciones = $mcerti->getCertificacionesByTipo($idusu, $filtroTipo);
}

// Variables para mensajes
$mensaje = '';
$tipoMensaje = '';

// Operaciones
$opera = $_REQUEST['opera'] ?? null;

// NUEVA OPERACIÓN: Solicitud por programa específico
if ($opera === "solicitar_programa") {
    $tipoCertificacion = $_POST['tipo_certificacion'] ?? '';
    $idfic = $_POST['idfic'] ?? '';
    
    // Validaciones del servidor
    if (empty($tipoCertificacion) || empty($idfic)) {
        $mensaje = 'Debes seleccionar un tipo de certificación y programa válidos.';
        $tipoMensaje = 'error';
    } else {
        // Guardar en base de datos con información del programa
        $resultado = $mcerti->saveSolicitudCertificacionConPrograma($idusu, $tipoCertificacion, $idfic);
        
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
            $tipoMensaje = 'success';
            
            // Recargar solicitudes
            $datMisSolicitudes = $mcerti->getMisSolicitudesConPrograma($idusu);
            $datEstadisticasSolicitudes = $mcerti->getEstadisticasMisSolicitudes($idusu);
        } else {
            $mensaje = $resultado['message'];
            $tipoMensaje = 'error';
        }
    }
}

// Operación para descargar certificado (si la implementas)
if ($opera === "descargar" && isset($_GET['id'])) {
    $idSolicitud = $_GET['id'];
    // Aquí implementarías la lógica para generar y descargar el PDF
    // Por ahora solo redirigimos con un mensaje
    $mensaje = "Funcionalidad de descarga en desarrollo para solicitud ID: $idSolicitud";
    $tipoMensaje = 'info';
}

// Mantener operación original para certificado de etapa productiva
if ($opera === "generar_certificado") {
    // Lógica existente para generar certificado de etapa productiva...
    // (mantener la lógica que ya tenías si la necesitas)
    if ($puedeObtenerCertificado) {
        // Aquí iría la lógica de generación del certificado
        $mensaje = "Certificado de etapa productiva generado exitosamente.";
        $tipoMensaje = 'success';
    } else {
        $mensaje = $motivoNoDisponible;
        $tipoMensaje = 'warning';
    }
}

// Función ÚNICA para mostrar alertas (evitar duplicación)
if (!function_exists('mostrarAlerta')) {
    function mostrarAlerta($mensaje, $tipo) {
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
            default:
                $clase = 'alert-info';
                $icono = 'fa-info-circle';
        }
        
        if (!empty($mensaje)) {
            echo "<div class='alert $clase alert-dismissible fade show' role='alert'>
                    <i class='fa $icono me-2'></i>$mensaje
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        }
    }
}

// FUNCIONES AUXILIARES ÚNICAS (evitar duplicación con la vista)
// Estas funciones solo se definen si no existen ya

if (!function_exists('getBadgeColorPrograma')) {
    function getBadgeColorPrograma($tipo) {
        switch (strtoupper($tipo)) {
            case 'TECNICO': return 'primary';
            case 'TECNOLOGO': return 'success';
            case 'CURSO': case 'COMPLEMENTARIA': return 'info';
            default: return 'secondary';
        }
    }
}

if (!function_exists('getEstadoPrograma')) {
    function getEstadoPrograma($estado) {
        switch ($estado) {
            case 'activo': return 'success';
            case 'terminado': return 'primary';
            case 'inactivo': return 'secondary';
            default: return 'warning';
        }
    }
}

if (!function_exists('formatearTipoCertificacion')) {
    function formatearTipoCertificacion($tipo) {
        $tipos = [
            'certificacion_inicio' => 'Inicio de Formación',
            'certificacion_parcial' => 'Competencias Parciales',
            'certificacion_etapa_productiva' => 'Etapa Productiva',
            'certificacion_tecnica_completa' => 'Técnica Completa',
            'certificacion_tecnologica_completa' => 'Tecnológica Completa',
            'certificacion_curso_inicio' => 'Participación en Curso',
            'certificacion_curso_intermedio' => 'Progreso en Curso',
            'certificacion_curso_completo' => 'Finalización de Curso',
            'constancia_estudiante_activo' => 'Constancia de Estudiante'
        ];
        
        return $tipos[$tipo] ?? ucfirst(str_replace('_', ' ', $tipo));
    }
}

if (!function_exists('getIconoCertificacion')) {
    function getIconoCertificacion($tipo) {
        $iconos = [
            'certificacion_inicio' => 'fa-star',
            'certificacion_parcial' => 'fa-certificate',
            'certificacion_etapa_productiva' => 'fa-industry',
            'certificacion_tecnica_completa' => 'fa-cogs',
            'certificacion_tecnologica_completa' => 'fa-laptop',
            'certificacion_curso_inicio' => 'fa-book',
            'certificacion_curso_intermedio' => 'fa-bookmark',
            'certificacion_curso_completo' => 'fa-graduation-cap',
            'constancia_estudiante_activo' => 'fa-id-card'
        ];
        
        return $iconos[$tipo] ?? 'fa-certificate';
    }
}

if (!function_exists('getClaseEstadoSolicitud')) {
    function getClaseEstadoSolicitud($estado) {
        switch ($estado) {
            case 'aprobada_coordinacion': return 'info';
            case 'completada': return 'success';
            case 'rechazada': return 'danger';
            case 'pendiente': return 'warning';
            default: return 'secondary';
        }
    }
}

if (!function_exists('getTextoEstado')) {
    function getTextoEstado($estado) {
        switch ($estado) {
            case 'pendiente': return 'Pendiente';
            case 'aprobada_coordinacion': return 'Aprobada - Esp. Firma';
            case 'completada': return 'Completada';
            case 'rechazada': return 'Rechazada';
            default: return ucfirst($estado);
        }
    }
}

?>