<?php
    require_once __DIR__ . '/../models/madm.php';
    $madm = new Madm();
    $dependencias = $madm->obtenerDependencias();

    function sendJsonResponse($data, $statusCode = 200) {
        // se limpis solo si es una respuesta AJAX
        if(ob_get_length()) ob_clean();
        
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE));
    }

    if (isset($_GET['action']) && $_GET['action'] == 'get_empleados') {
        try {
            if (!isset($_GET['id_dependencia'])) {
                throw new Exception('Parámetro id_dependencia faltante', 400);
            }
            
            $id_dependencia = filter_var($_GET['id_dependencia'], FILTER_VALIDATE_INT);
            if ($id_dependencia === false || $id_dependencia <= 0) {
                throw new Exception('ID de dependencia no válido', 400);
            }
            
            require_once __DIR__ . '/../models/madm.php';
            $madm = new Madm();
            $empleados = $madm->obtenerEmpleadosPorDependencia($id_dependencia);
            
            sendJsonResponse([
                'success' => true,
                'data' => $empleados,
                'count' => count($empleados)
            ]);
            
        } catch(Exception $e) {
            sendJsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }


?>