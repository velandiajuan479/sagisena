<?php

require_once("models/mtrc.php");

// Limpieza de parámetros
$idtrht = $_REQUEST["idtrht"] ?? NULL;
$idnorad = $_POST["idnorad"] ?? NULL;
$idpas = $_POST["idpas"] ?? NULL;
$idusu = $_POST["idusu"] ?? NULL;
$fectrac = $_POST["fectrac"] ?? NULL;
$opera = htmlspecialchars($_REQUEST["opera"] ?? '', ENT_QUOTES, 'UTF-8');

// Instancia del modelo
$mtrc = new Mtrc(); 
$trazas = $mtrc->getAll();

// Opcional: Validar/transformar datos si es necesario
foreach ($trazas as &$traza) {
    $traza['idnorad'] = $traza['idnorad'] ?? 'N/A';
    $traza['idpas'] = $traza['idpas'] ?? 'N/A';
    $traza['idusu'] = $traza['idusu'] ?? 'N/A';
}
?>