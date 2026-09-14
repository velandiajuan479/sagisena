<?php
require_once 'models/mcpr.php';

$mcpr = new Mcpr();
$mensaje = '';
$detalle = null;

$idcom = isset($_POST['idcom']) ? trim(filter_var($_POST['idcom'], FILTER_SANITIZE_STRING)) : (isset($_GET['idcom']) ? $_GET['idcom'] : null);
$desdtc = isset($_POST['desdtc']) ? trim(filter_var($_POST['desdtc'], FILTER_SANITIZE_STRING)) : null;
$detfech = isset($_POST['detfech']) ? trim(filter_var($_POST['detfech'], FILTER_SANITIZE_STRING)) : null;
$action = isset($_POST['action']) ? trim(filter_var($_POST['action'], FILTER_SANITIZE_STRING)) : null;

if ($action) {
    if ($action == 'Crear' && $idcom && $desdtc && $detfech) {
        $idcom_new = $mcpr->save($idcom, $desdtc, $detfech);
        if ($idcom_new) {
            $mensaje = "Compromiso creado exitosamente.";
            $detalle = $mcpr->getOne($idcom_new);
        } else {
            $mensaje = "Error al crear compromiso.";
        }
    } elseif ($action == 'Actualizar' && $idcom && $desdtc && $detfech) {
        if ($mcpr->edit($idcom, $idcom, $desdtc, $detfech)) {
            $mensaje = "Compromiso actualizado exitosamente.";
            $detalle = $mcpr->getOne($idcom);
        } else {
            $mensaje = "Error al actualizar compromiso.";
        }
    }
}

// Cargar detalle si se pasa idcom por GET (para edición o visualización)
if (isset($_GET['idcom']) && !$action) {
    $detalle = $mcpr->getOne($_GET['idcom']);
    if (!$detalle) {
        $mensaje = "Compromiso no encontrado.";
    }
}
?>