<?php
require_once __DIR__ . '/../models/maps.php'; 
require_once __DIR__ . '/../models/conexion.php';

$maps = new Maps();


if (isset($_GET['action']) && $_GET['action'] === 'get_empleados') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $id_dependencia = $_GET['id_dependencia'] ?? null;
        $documento = $_GET['documento'] ?? '';
        
        error_log("Búsqueda de empleados. Dependencia: $id_dependencia, Documento: $documento");

        if (!$id_dependencia) {
            error_log("Error: Dependencia no proporcionada");
            echo json_encode(['success' => false, 'error' => 'Dependencia no proporcionada']);
            exit;
        }

        $empleados = $maps->obtenerEmpleadosPorDependencia($id_dependencia);
        error_log("Total empleados encontrados antes de filtrar: " . count($empleados));

        if (!empty($documento)) {
            $empleados = array_filter($empleados, function($emp) use ($documento) {
                $match = strpos($emp['ndocusu'], $documento) !== false;
                if (!$match) {
                    error_log("Documento no coincide: {$emp['ndocusu']} vs $documento");
                }
                return $match;
            });
            error_log("Total empleados después de filtrar: " . count($empleados));
        }

        echo json_encode([
            'success' => true,
            'count' => count($empleados),
            'data' => array_values($empleados)
        ]);
    } catch (Throwable $e) {
        error_log("Error grave en get_empleados: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Error al obtener empleados: ' . $e->getMessage()]);
    }
    exit;
}

// Obtener ultima observacion del paz y salvo
if (isset($_GET['action']) && $_GET['action'] === 'get_observaciones') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $documento = $_GET['documento'] ?? '';
        if (empty($documento)) {
            echo json_encode(['success' => false, 'error' => 'Documento no proporcionado']);
            exit;
        }

        $empleado = $maps->obtenerDatosEmpleadoPorDocumento($documento);
        if (!$empleado || empty($empleado[0]['idusu'])) {
            echo json_encode(['success' => false, 'error' => 'Empleado no encontrado o sin ID']);
            exit;
        }

        $idusu = $empleado[0]['idusu'];
        $observacion = $maps->obtenerUltimaObservacion($idusu);

        echo json_encode([
            'success' => true,
            'data' => $observacion[0] ?? null
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Excepción del servidor: ' . $e->getMessage()]);
    }
    exit;
}

// Buscar usuario por documento

if (isset($_GET['action']) && $_GET['action'] === 'buscar_usuario') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $documento = $_GET['documento'] ?? '';
        $empleado = $maps->obtenerDatosEmpleadoPorDocumento($documento);
        $empleado = is_array($empleado) && count($empleado) > 0 ? $empleado[0] : null;

        echo json_encode([
            'success' => true,
            'data' => $empleado
        ]);
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}


// Calificar usuario(guardar paz y salvo)
if (isset($_GET['action']) && $_GET['action'] === 'calificar_usuario' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $idusu = $input['idusu'] ?? null;
        $calificacion = $input['calificacion'] ?? null;
        $observacion = $input['observacion'] ?? '';

        if (!$idusu || $calificacion === null) {
            throw new Exception("Datos incompletos");
        }

        $maps->gestionarPazSalvo($idusu, null, null, $observacion, $calificacion);

        echo json_encode(['success' => true]);
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Envio por formulario tradicional
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'])) {
    $empleado = $maps->obtenerDatosEmpleadoPorDocumento($_POST['documento']);
    $_SESSION['empleado_seleccionado'] = $empleado[0] ?? [
        'ndocusu' => $_POST['documento'],
        'nombre' => $_POST['nombre'] ?? 'N/A'
    ];
    header('Location: vpys.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id'])) {
    $empleado = $maps->obtenerDatosEmpleado($_POST['usuario_id']);
    $_SESSION['empleado_seleccionado'] = $empleado[0] ?? null;
    header('Location: vpys.php');
    exit;
}

$dependencias = $maps->obtenerDependencias();
