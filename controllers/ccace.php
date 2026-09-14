<?php
require_once 'models/mcace.php';

$idfic  = isset($_REQUEST['idfic']) ? $_REQUEST['idfic'] : NULL;
$idusu  = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : NULL;
$nomusu = isset($_POST['nomusu']) ? $_POST['nomusu'] : NULL;
$ope    = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

$mcace = new Mcace();
$mcace->setIdfic($idfic);
$mcace->setIdusu($idusu);

if ($ope == "save") {
    $mcace->save();
}

if ($ope == "del" && $idfic && $idusu) {
    $mcace->del();
}

if ($idfic) {
    $mcace->setIdfic($idfic);
    $aprendices = $mcace->getAprendicesPorFicha();
} else {
    $aprendices = [];
}

# Pasar idfic a la vista
$idfic = $idfic;
?>
