<?php
require_once ('models/mpag.php');

$mpag = new Mpag();

$idpag = isset($_REQUEST['idpag']) ? $_REQUEST['idpag']:NULL;
$nompag = isset($_POST['nompag']) ? $_POST['nompag']:NULL;
$rutpag = isset($_POST['rutpag']) ? $_POST['rutpag']:NULL;
$mospas = isset($_REQUEST['mospas']) ? $_REQUEST['mospas']:NULL;
$ordpag = isset($_POST['ordpag']) ? $_POST['ordpag']:NULL;
$icopag = isset($_POST['icopag']) ? $_POST['icopag']:NULL;
$idmod = isset($_POST['idmod']) ? $_POST['idmod']:NULL;
$despag = isset($_POST['despag']) ? $_POST['despag']:NULL;

$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$datOne = NULL;

$mpag->setIdpag($idpag);
if($opera=="save"){
    $mpag->setNompag($nompag);
    $mpag->setRutpag($rutpag);
    $mpag->setMospas($mospas);
    $mpag->setOrdpag($ordpag);
    $mpag->setIcopag($icopag);
    $mpag->setIdmod($idmod);
    $mpag->setDespag($despag);
    if(!$idpag) $mpag->save();else $mpag->edit();
}

if($idpag && $opera=="acti"){
    $mpag->setMospas($mospas);
    $mpag->editMospas();
}

if($opera=="eli" && $idpag){
    $mpag->del();

    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}
if($opera=="edi" && $idpag) $datOne = $mpag->getOne($idpag);

$datAll = $mpag->getAll();
$datMod = $mpag->getAllMod();
$datPdf = $mpag->getPdfPag();
?>