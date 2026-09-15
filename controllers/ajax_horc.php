<?php
// Endpoint AJAX dedicado para horarios — solo devuelve JSON
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar conexión y modelos necesarios
require_once(__DIR__ . '/../models/conexion.php');
require_once(__DIR__ . '/../models/mhorc.php');

// ManejoError está definida en optimg.php, pero para no cargar todo ese archivo,
// la definimos aquí si aún no existe.
if (!function_exists('ManejoError')) {
    function ManejoError($e) {
        error_log('Error: ' . $e->getMessage());
    }
}

header('Content-Type: application/json');

// Verificar sesión activa
if (!isset($_SESSION['idusu'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión expirada. Por favor inicie sesión nuevamente.']);
    exit;
}

$opera = isset($_POST['opera']) ? $_POST['opera'] : '';

if ($opera === 'save_horario') {
    if (isset($_POST['datos']) && !empty($_POST['idnorad'])) {
        $datos_json   = json_decode($_POST['datos'], true);
        $idnorad_save = $_POST['idnorad'];
        $idusu_actual = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : 1;

        if (is_array($datos_json)) {
            $saved  = 0;
            $errors = [];

            foreach ($datos_json as $dato) {
                $fecha       = isset($dato['fecha'])       ? trim($dato['fecha'])       : null;
                $hora_inicio = isset($dato['hora_inicio']) ? trim($dato['hora_inicio']) : null;
                $hora_fin    = isset($dato['hora_fin'])    ? trim($dato['hora_fin'])    : null;

                if (!$fecha || !$hora_inicio || !$hora_fin) {
                    $errors[] = "Dato incompleto: $fecha";
                    continue; // saltar este item, continuar con los demás
                }

                // Verificar si ya existe un horario para esta fecha y actualizar o insertar
                $mhorc_check = new Mhorc();
                $mhorc_check->setFechaEspecifica($fecha);
                $mhorc_check->setIdnorad($idnorad_save);
                $existe = $mhorc_check->getByFecha();

                if (empty($existe)) {
                    $mhorc_ins = new Mhorc();
                    $mhorc_ins->setFechaEspecifica($fecha);
                    $mhorc_ins->setHinihor($hora_inicio);
                    $mhorc_ins->setHfinhor($hora_fin);
                    $mhorc_ins->setIdnorad($idnorad_save);
                    $mhorc_ins->setIdusu($idusu_actual);
                    if ($mhorc_ins->saveConFecha()) {
                        $saved++;
                    } else {
                        $errors[] = "Error al insertar: $fecha";
                    }
                } else {
                    $mhorc_upd = new Mhorc();
                    $mhorc_upd->setIdhor($existe[0]['idhor']);
                    $mhorc_upd->setFechaEspecifica($fecha);
                    $mhorc_upd->setHinihor($hora_inicio);
                    $mhorc_upd->setHfinhor($hora_fin);
                    $mhorc_upd->setIdnorad($idnorad_save);
                    $mhorc_upd->setIdusu($idusu_actual);
                    if ($mhorc_upd->editConFecha()) {
                        $saved++;
                    } else {
                        $errors[] = "Error al actualizar: $fecha";
                    }
                }
            }

            echo json_encode([
                'success' => $saved > 0,
                'message' => "Guardadas: $saved" . (count($errors) > 0 ? " | Errores: " . implode(', ', $errors) : ''),
                'saved'   => $saved,
                'total'   => count($datos_json)
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos (datos o idnorad)']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Operación no reconocida']);
}
exit;
