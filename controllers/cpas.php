<?php
require_once("models/mpas.php");


$mpas = new Mpas();  

$idpas = isset($_REQUEST["idpas"]) ? $_REQUEST["idpas"] : NULL;
$idflu = isset($_POST["idflu"]) ? $_POST["idflu"] : NULL;
$descpas = isset($_POST["descpas"]) ? $_POST["descpas"] : NULL;
$idper = isset($_POST["idper"]) ? $_POST["idper"] : NULL;
$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"] : NULL;
$datOne = NULL;

$mpas->setIdpas($idpas);
if ($opera == "save") {
    $mpas->setIdflu($idflu);
    $mpas->setDescpas($descpas);
    $mpas->setIdper($idper);
    if (!$idpas) {
        $mpas->save();  
    } else {
        $mpas->edit();  
    }
    $idpas = NULL;
}

if ($opera == "eli" && $idpas) {
    $mpas->del();  
    $idpas = NULL;

    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}

if ($opera == "edi" && $idpas) {
    $datOne = $mpas->getOne();  
}

$datFlu = $mpas->getAllFlu();
$datPef = $mpas->getAllPef();
$datAll = $mpas->getAll();  
?>