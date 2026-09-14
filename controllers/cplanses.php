<?php
define('ROOT_PATH', dirname(__DIR__));

// Configuración de errores según el entorno
if (defined('DEBUG') && DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// La sesión puede no estar iniciada cuando se entra por AJAX
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ROOT_PATH . '/models/mplanses.php';

$mplanses = new Mplanses();

// Definir variable pg si no está definida
$pg = isset($_REQUEST['pg']) ? $_REQUEST['pg'] : '1549';

$idplan = isset($_REQUEST['idplan']) ? $_REQUEST['idplan'] : NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic'] : (isset($_REQUEST['idfic']) ? $_REQUEST['idfic'] : NULL);
$idres = isset($_POST['idres']) ? $_POST['idres'] : (isset($_REQUEST['idres']) ? $_REQUEST['idres'] : NULL);
$numses = isset($_POST['numses']) ? $_POST['numses'] : (isset($_REQUEST['numses']) ? $_REQUEST['numses'] : NULL);

$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera'] : NULL;
$datOne = NULL;

$mplanses->setIdplan($idplan);

// Guardar plan de sesión
if($opera == "save"){
    try {
        $idfic = $_POST['idfic'];
        $idres = $_POST['idres'];
        $numses = $_POST['numses'];
        $titulo_sesion = $_POST['titulo_sesion'];
        $fecha_programada = $_POST['fecha_programada'];
        
        // Obtener actividades del formulario
        $actividades = [];
        
        // Procesar actividades de INICIO
        if(isset($_POST['actividades_inicio'])) {
            foreach($_POST['actividades_inicio'] as $index => $actividad) {
                if(!empty($actividad['actapr']) && !empty($actividad['tmp'])) {
                    $actividades[] = [
                        'fas' => 'INICIO',
                        'actapr' => $actividad['actapr'],
                        'tmp' => intval($actividad['tmp']),
                        'cont' => $actividad['cont'] ?? '',
                        'matfor' => $actividad['matfor'] ?? ''
                    ];
                }
            }
        }
        
        // Procesar actividades de DESARROLLO
        if(isset($_POST['actividades_desarrollo'])) {
            foreach($_POST['actividades_desarrollo'] as $index => $actividad) {
                if(!empty($actividad['actapr']) && !empty($actividad['tmp'])) {
                    $actividades[] = [
                        'fas' => 'DESARROLLO',
                        'actapr' => $actividad['actapr'],
                        'tmp' => intval($actividad['tmp']),
                        'cont' => $actividad['cont'] ?? '',
                        'matfor' => $actividad['matfor'] ?? ''
                    ];
                }
            }
        }
        
        // Procesar actividades de CIERRE
        if(isset($_POST['actividades_cierre'])) {
            foreach($_POST['actividades_cierre'] as $index => $actividad) {
                if(!empty($actividad['actapr']) && !empty($actividad['tmp'])) {
                    $actividades[] = [
                        'fas' => 'CIERRE',
                        'actapr' => $actividad['actapr'],
                        'tmp' => intval($actividad['tmp']),
                        'cont' => $actividad['cont'] ?? '',
                        'matfor' => $actividad['matfor'] ?? ''
                    ];
                }
            }
        }
        
        if(empty($actividades)) {
            echo json_encode(['success' => false, 'message' => 'Debe agregar al menos una actividad']);
            exit;
        }
        
        $resultado = $mplanses->saveActividadesSesion($idres, $idfic, $numses, $titulo_sesion, $fecha_programada, $actividades);
        
        if($resultado) {
            echo json_encode(['success' => true, 'message' => 'Plan de sesión guardado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el plan de sesión']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Actualizar plan de sesión
if($opera == "update"){
    try {
        $idplan = $_POST['idplan'];
        $mplanses->setIdplan($idplan);
        
        $mplanses->setIdfic($_POST['idfic']);
        $mplanses->setIdres($_POST['idres']);
        $mplanses->setFas($_POST['fas']);
        $mplanses->setActapr($_POST['actapr']);
        $mplanses->setTmp($_POST['tmp']);
        $mplanses->setCont($_POST['cont']);
        $mplanses->setMatfor($_POST['matfor']);
        $mplanses->setNumses($_POST['numses']);
        $mplanses->setTitulo_sesion($_POST['titulo_sesion']);
        $mplanses->setFecha_programada($_POST['fecha_programada']);
        $mplanses->setEstado($_POST['estado']);
        
        $resultado = $mplanses->update();
        
        if($resultado) {
            echo json_encode(['success' => true, 'message' => 'Plan de sesión actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el plan de sesión']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Eliminar plan de sesión
if($opera == "delete"){
    try {
        $mplanses->setIdplan($idplan);
        $resultado = $mplanses->delete();
        
        if($resultado) {
            echo json_encode(['success' => true, 'message' => 'Plan de sesión eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el plan de sesión']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener planes por resultado de aprendizaje
if($opera == "get_planes_resultado"){
    try {
        $idres = $_REQUEST['idres'];
        $idfic = $_REQUEST['idfic'];
        
        $planes = $mplanses->getPlanesAgrupadosPorSesion($idres, $idfic);
        $info_resultado = $mplanses->getInfoResultado($idres);
        $estadisticas = $mplanses->getEstadisticasPlanes($idres, $idfic);
        
        echo json_encode([
            'success' => true, 
            'planes' => $planes,
            'info_resultado' => $info_resultado,
            'estadisticas' => $estadisticas
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener plan específico de una sesión
if($opera == "get_plan_sesion"){
    try {
        $idres = $_REQUEST['idres'];
        $idfic = $_REQUEST['idfic'];
        $numses = $_REQUEST['numses'];
        
        $planes = $mplanses->getPlanesPorSesion($idres, $idfic, $numses);
        $info_resultado = $mplanses->getInfoResultado($idres);
        
        echo json_encode([
            'success' => true, 
            'planes' => $planes,
            'info_resultado' => $info_resultado
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Verificar si existe plan para una sesión
if($opera == "existe_plan_sesion"){
    try {
        $idres = $_REQUEST['idres'];
        $idfic = $_REQUEST['idfic'];
        $numses = $_REQUEST['numses'];
        
        $existe = $mplanses->existePlanSesion($idres, $idfic, $numses);
        
        echo json_encode(['success' => true, 'existe' => $existe]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Eliminar plan de sesión específico
if($opera == "delete_plan_sesion"){
    try {
        $idres = $_REQUEST['idres'];
        $idfic = $_REQUEST['idfic'];
        $numses = $_REQUEST['numses'];
        
        $resultado = $mplanses->deletePlanesSesion($idres, $idfic, $numses);
        
        if($resultado) {
            echo json_encode(['success' => true, 'message' => 'Sesión eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la sesión']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Actualizar estado de sesión
if($opera == "actualizar_estado"){
    try {
        $idres = $_REQUEST['idres'];
        $idfic = $_REQUEST['idfic'];
        $numses = $_REQUEST['numses'];
        $estado = $_REQUEST['estado'];
        
        $resultado = $mplanses->actualizarEstadoSesion($idres, $idfic, $numses, $estado);
        
        if($resultado) {
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Completar toda la sesión (todas las actividades de todas las fases)
if($opera == "completar_toda_sesion"){
    try {
        $idres = $_REQUEST['idres'];
        $idfic = $_REQUEST['idfic'];
        $numses = $_REQUEST['numses'];
        
        $resultado = $mplanses->completarTodaSesion($idres, $idfic, $numses);
        
        if($resultado) {
            echo json_encode(['success' => true, 'message' => 'Toda la sesión ha sido completada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al completar la sesión']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener información de un plan específico
if($opera == "get_one" && $idplan){
    $datOne = $mplanses->getOne();
}

// Cargar vista cuando no hay operación específica
if (!$opera || in_array($opera, ['', 'view', 'list'])) {
    // Cargar la vista de planes de sesión
    require_once 'views/vplanses.php';
    exit;
}
?>

