<?php
require_once 'models/mhdt.php';

$mhdt = new Mhdt();

$idnorad = isset($_REQUEST['idnorad']) ? $_REQUEST['idnorad'] : NULL;
$idusu   = isset($_POST['idinstructor']) ? $_POST['idinstructor'] : NULL;
$opera   = isset($_POST['opera']) ? $_POST['opera'] : NULL;

// Guardar instructor en hoja de trabajo
if ($opera == "AgrIns" && $idnorad && $idusu) {
    $mhdt->setIdnorad($idnorad);
    $mhdt->setIdusu($idusu);
    $mhdt->saveHxU(); // Asumiendo que esta función guarda la relación hoja-instructor
}

// Obtener instructores (usuarios con perfil instructor)
$datIns = $mhdt->getAllIns(); // Debes crear este método en el modelo

// Obtener datos de la hoja de trabajo actual
$datOneHt = $mhdt->getOne($idnorad);

// Obtener usuarios relacionados a la hoja de trabajo
$mhdt->setIdnorad($idnorad);
$usuarios = $mhdt->getByHoja(); // Si existe esta función

// Pasar instructores a la vista
$instructores = $datIns;

include 'views/vhtxus.php';
?>