<?php

require_once(__DIR__ . '/../models/mflumat.php');

/**
 * Controlador para la gestión de flujos y pasos de matrícula (SAGI)
 */
class Cflumat {
    private $mflumatModelo;

    public function __construct() {
        $this->mflumatModelo = new Mflumat();
    }

    // Listar todos los flujos
    public function index() {
        $flujos = $this->mflumatModelo->getAllFlujos();
        return ['flujos' => $flujos, 'current_action' => 'list_flows'];
    }

    // Listar pasos de un flujo
    public function listSteps($idFlu) {
        $flujo = $this->mflumatModelo->getFlujoById($idFlu);
        $pasos = $this->mflumatModelo->getPasosByFlujoId($idFlu);
        return ['flujo' => $flujo, 'pasos' => $pasos, 'current_action' => 'list_steps'];
    }

    // Crear flujo
    public function createFlow() {
        $data = ['current_action' => 'create_flow', 'form_errors' => []];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreFlujo = $_POST['nombre_flujo'] ?? '';
            $activo = isset($_POST['activo']) ? 1 : 0;
            if (empty($nombreFlujo)) {
                $data['form_errors'][] = 'El nombre del flujo es obligatorio.';
            }
            if (empty($data['form_errors'])) {
                if ($this->mflumatModelo->createFlujo($nombreFlujo, $activo)) {
                    header('Location: ?action=list_flows&status=flow_created');
                    exit();
                } else {
                    $data['form_errors'][] = 'Error al crear el flujo.';
                }
            }
        }
        return $data;
    }

    // Editar flujo
    public function editFlow($idFlu) {
        $flujo = $this->mflumatModelo->getFlujoById($idFlu);
        if (!$flujo) {
            header('Location: ?action=list_flows&status=flow_not_found');
            exit();
        }
        $data = ['current_action' => 'edit_flow', 'flujo' => $flujo, 'form_errors' => []];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombreFlujo = $_POST['nombre_flujo'] ?? '';
            $activo = isset($_POST['activo']) ? 1 : 0;
            if (empty($nombreFlujo)) {
                $data['form_errors'][] = 'El nombre del flujo es obligatorio.';
            }
            if (empty($data['form_errors'])) {
                if ($this->mflumatModelo->updateFlujo($idFlu, $nombreFlujo, $activo)) {
                    header('Location: ?action=list_flows&status=flow_updated');
                    exit();
                } else {
                    $data['form_errors'][] = 'Error al actualizar el flujo.';
                }
            }
        }
        return $data;
    }

    // Eliminar flujo
    public function deleteFlow($idFlu) {
        if ($this->mflumatModelo->deleteFlujo($idFlu)) {
            header('Location: ?action=list_flows&status=flow_deleted');
            exit();
        } else {
            header('Location: ?action=list_flows&status=delete_error');
            exit();
        }
    }

    // Crear paso
    public function createStep($idFlu) {
        $flujo = $this->mflumatModelo->getFlujoById($idFlu);
        if (!$flujo) {
            header('Location: ?action=list_flows&status=flow_not_found');
            exit();
        }
        $data = ['current_action' => 'create_step', 'flujo' => $flujo, 'form_errors' => []];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcionPaso = $_POST['descripcion_paso'] ?? '';
            if (empty($descripcionPaso)) {
                $data['form_errors'][] = 'La descripción del paso es obligatoria.';
            }
            if (empty($data['form_errors'])) {
                if ($this->mflumatModelo->createPaso($idFlu, $descripcionPaso)) {
                    header('Location: ?action=list_steps&idflu=' . $idFlu . '&status=step_created');
                    exit();
                } else {
                    $data['form_errors'][] = 'Error al crear el paso.';
                }
            }
        }
        return $data;
    }

    // Editar paso
    public function editStep($idPaso) {
        $paso = $this->mflumatModelo->getPasoById($idPaso);
        if (!$paso) {
            header('Location: ?action=list_flows&status=step_not_found');
            exit();
        }
        $flujo = $this->mflumatModelo->getFlujoById($paso['idflu']);
        $data = ['current_action' => 'edit_step', 'paso' => $paso, 'flujo' => $flujo, 'form_errors' => []];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcionPaso = $_POST['descripcion_paso'] ?? '';
            if (empty($descripcionPaso)) {
                $data['form_errors'][] = 'La descripción del paso es obligatoria.';
            }
            if (empty($data['form_errors'])) {
                if ($this->mflumatModelo->updatePaso($idPaso, $descripcionPaso)) {
                    header('Location: ?action=list_steps&idflu=' . $paso['idflu'] . '&status=step_updated');
                    exit();
                } else {
                    $data['form_errors'][] = 'Error al actualizar el paso.';
                }
            }
        }
        return $data;
    }

    // Eliminar paso
    public function deleteStep($idPaso) {
        $paso = $this->mflumatModelo->getPasoById($idPaso);
        if (!$paso) {
            header('Location: ?action=list_flows&status=step_not_found');
            exit();
        }
        $idFlu = $paso['idflu'];
        if ($this->mflumatModelo->deletePaso($idPaso)) {
            header('Location: ?action=list_steps&idflu=' . $idFlu . '&status=step_deleted');
            exit();
        } else {
            header('Location: ?action=list_steps&idflu=' . $idFlu . '&status=delete_error');
            exit();
        }
    }
}

// --- Enrutamiento ---
$cflumat = new Cflumat();
$action = $_GET['action'] ?? 'index';
$data = [];

switch ($action) {
    case 'list_flows':
        $data = $cflumat->index();
        break;
    case 'list_steps':
        $idFlu = $_GET['idflu'] ?? null;
        if ($idFlu) {
            $data = $cflumat->listSteps($idFlu);
        } else {
            header('Location: ?action=list_flows');
            exit();
        }
        break;
    case 'create_flow':
        $data = $cflumat->createFlow();
        break;
    case 'edit_flow':
        $idFlu = $_GET['idflu'] ?? null;
        if ($idFlu) {
            $data = $cflumat->editFlow($idFlu);
        } else {
            header('Location: ?action=list_flows');
            exit();
        }
        break;
    case 'delete_flow':
        $idFlu = $_GET['idflu'] ?? null;
        if ($idFlu) {
            $cflumat->deleteFlow($idFlu);
        } else {
            header('Location: ?action=list_flows');
            exit();
        }
        break;
    case 'create_step':
        $idFlu = $_GET['idflu'] ?? null;
        if ($idFlu) {
            $data = $cflumat->createStep($idFlu);
        } else {
            header('Location: ?action=list_flows');
            exit();
        }
        break;
    case 'edit_step':
        $idPaso = $_GET['idpaso'] ?? null;
        if ($idPaso) {
            $data = $cflumat->editStep($idPaso);
        } else {
            header('Location: ?action=list_flows');
            exit();
        }
        break;
    case 'delete_step':
        $idPaso = $_GET['idpaso'] ?? null;
        if ($idPaso) {
            $cflumat->deleteStep($idPaso);
        } else {
            header('Location: ?action=list_flows');
            exit();
        }
        break;
    default:
        $data = $cflumat->index();
        break;
}

extract($data);
include(__DIR__ . '/../views/vflumat.php');
?>
