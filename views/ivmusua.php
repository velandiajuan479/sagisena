<?php
require_once 'controllers/AspiranteController.php';

$controller = new AspiranteController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->cargarMasivo();
} else {
    $controller->mostrarFormulario();
}
?>