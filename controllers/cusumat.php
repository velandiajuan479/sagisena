<?php

require_once("models/musumat.php");

$musumat = new Musumat();

// Captura de datos del formulario
$idusu    = isset($_REQUEST['idusu']) ? $_POST['idusu'] : NULL;
$ndocusu  = isset($_POST['ndocusu']) ? $_POST['ndocusu'] : NULL;
$nomusu   = isset($_POST['nomusu']) ? $_POST['nomusu'] : NULL;
$pasusu   = isset($_POST['pasusu']) ? $_POST['pasusu'] : NULL;
$emausu   = isset($_POST['emausu']) ? $_POST['emausu'] : NULL;
$opera    = isset($_POST['opera']) ? $_POST['opera'] : NULL;

$datOne = NULL;

// Seteo del ID de usuario
$musumat->setIdusu($idusu);

// Operación guardar o editar
if ($opera == "save") {
    $musumat->setNdocusu($ndocusu);
    $musumat->setNomusu($nomusu);
    $musumat->setPasusu($pasusu);
    $musumat->setEmausu($emausu);

    if (!$idusu) {  // Si no hay ID, es un nuevo registro
        $musumat->save();
    } else {        // Si hay ID, es una edición
        $musumat->edit();
    }
}

// Operación eliminar
if ($opera == "eli" && $idusu) {
    $musumat->del();
}

// Operación editar (obtener datos de un solo usuario)
if ($opera == "edi" && $idusu) {
    $datOne = $musumat->getOne();
}

// Cargar datos
$usuFi  = $musumat->getAllusuf();
$usuAll = $musumat->getAll();

?>