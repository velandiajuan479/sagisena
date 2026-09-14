<?php
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/mcac.php';

$idfic = $_GET['idfic'] ?? $_POST['idfic'] ?? null;

$aprendices = [];
$criterios = [];
$mensaje = null;

$mcac = new Mcac();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calificaciones'])) {
    $calificaciones = $_POST['calificaciones']; // Estructura: [idusu][idcri] => valor

    // Validación mínima (opcional)
    if (!empty($calificaciones)) {
        $exito = $mcac->guardarCalificaciones($calificaciones);
        $mensaje = $exito ? "✅ Calificaciones guardadas correctamente." : "❌ Error al guardar.";
    }
}

if ($idfic) {
    $mcac->setIdfic($idfic);
    $aprendices = $mcac->getAprendicesByFicha();
    $criterios = $mcac->getCriteriosByFicha();
}
?>