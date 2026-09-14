<?php
require_once("models/mflu.php");

$mflu = new Mflu();

$idflu = isset($_REQUEST["idflu"]) ? $_REQUEST["idflu"] : NULL;
$nomflu = isset($_POST["nomflu"]) ? $_POST["nomflu"] : NULL;
$fluacti = isset($_POST["fluacti"]) ? $_POST["fluacti"] : NULL;
$act = isset($_GET["act"]) ? $_GET["act"] : NULL;
$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"] : NULL;

$datOne = NULL;
//echo $idflu." - ".$nomflu." - ".$fluacti." - ".$opera;

$mflu->setIdflu($idflu);
if ($opera == "save") {
    $mflu->setNomflu($nomflu);
    $mflu->setFluacti($fluacti);

    if (!$idflu) {
        $mflu->save();  
    } else {
        $mflu->edit();  
    }

    $idflu = NULL;
}

if ($opera=="ediact" && $idflu && $act) {
    $mflu->setFluacti($act);
    $mflu->editAct();  
    $idflu = NULL;
}

if ($opera == "eli" && $idflu) {
    $mflu->del();  
    $idflu = NULL;

    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}

if ($opera == "edi" && $idflu) {
    $datOne = $mflu->getOne();  
}

$datAll = $mflu->getAll();
?>