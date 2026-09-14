<?php 
require_once("models/mpasmat.php"); 

$mpasmat = new Mpasmat();

$idpas = isset($_REQUEST["idpas"]) ? $_REQUEST["idpas"]:NULL; 
$idflu = isset($_POST["idflu"]) ? $_POST["idflu"]:NULL; 
$descpas = isset($_POST["descpas"]) ? $_POST["descpas"]:NULL; 
$idper = isset($_POST["idper"]) ? $_POST["idper"]:NULL; 

$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"]:NULL;
$datOne = NULL;

$mpasmat -> setIdpas($idpas);
if($opera == "save"){
    $mpasmat -> setIdpas($idpas);
    $mpasmat -> setIdflu($idflu);
    $mpasmat -> setDescpas($descpas);
    $mpasmat -> setIdper($idper);
if (!$idpas) {
    $mpasmat -> save();
} else {
    $mpasmat -> setIdpas($idpas);
    $mpasmat -> edit();
  }
}
if ($opera == "eli" && $idpas) {
    $mpasmat -> setIdpas($idpas);
    $mpasmat -> del();
}
if ($opera == "edi" && $idpas) {
    $mpasmat->setIdpas($idpas);
    $res = $mpasmat->getOne();
    $datOne = $res ? $res[0] : null;
}

$datFlu = $mpasmat -> getAllFlu();
$datPer = $mpasmat -> getAllPer();
$datAll = $mpasmat -> getAll();



?>