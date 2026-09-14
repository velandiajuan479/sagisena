<?php require_once("models/mccm.php"); 

$mccm = new Mccm();



$codpro = isset($_POST["codpro"]) ? $_POST["codpro"] : NULL;
$nompro = isset($_POST["nompro"]) ? $_POST["nompro"] : NULL;
$despro = isset($_POST["despro"]) ? $_POST["despro"] : NULL;
$verpro = isset($_POST["verpro"]) ? $_POST["verpro"] : NULL;
$horlpro = isset($_POST["horlpro"]) ? $_POST["horlpro"] : NULL;
$tippro = isset($_POST["tippro"]) ? $_POST["tippro"] : NULL;
$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"] : NULL;
$datOne = NULL;


$mccm->setcodpro($codpro); 
if($opera == "save"){
    $mccm->setCodpro($codpro);
    $mccm->setNompro($nompro);
    $mccm->setDespro($despro);
    $mccm->setVerpro($verpro);
    $mccm->setHorlpro($horlpro);
    $mccm->setTippro($tippro);
    $mccm->save();
} else if($opera == "edit"){
    $mccm->setCodpro($codpro);
    $mccm->setNompro($nompro);
    $mccm->setDespro($despro);
    $mccm->setVerpro($verpro);
    $mccm->setHorlpro($horlpro);
    $mccm->setTippro($tippro);
    $mccm->edit();
}
//if($opera == "save") {
    //  $mccm->setCodpro($codpro);
    //  $mccm->setNompro($nompro);
    //  $mccm->setDespro($despro);
    // $mccm->setVerpro($verpro);
    // $mccm->setHorlpro($horlpro);
    //$mccm->setTippro($tippro);
    //if(!$codpro) $mccm->save(); else $mccm->edit(); 
    //$idpro = NULL;
//}

if($opera == "eli" && $codpro){
    $mccm->del(); 
    $codpro = NULL;
    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}

if($opera == "edi" && $codpro){
    $datOne = $mccm->getOne(); 
}


$datAll = $mccm->getAll(); 
?>
b 