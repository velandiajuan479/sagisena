<?php
include("models/mprg.php");

$mprg=new Mprg();

$codpro = isset($_REQUEST['codpro']) ? $_REQUEST['codpro']:NULL;
$nompro = isset($_POST['nompro']) ? $_POST['nompro']:NULL;
$despro = isset($_POST['despro']) ? $_POST['despro']:NULL;
$verpro = isset($_POST['verpro']) ? $_POST['verpro']:NULL;
$horlpro = isset($_POST['horlpro']) ? $_POST['horlpro']:NULL;
$horppro = isset($_POST['horppro']) ? $_POST['horppro']:NULL;
$crelpro = isset($_POST['crelpro']) ? $_POST['crelpro']:NULL;
$creppro = isset($_POST['creppro']) ? $_POST['creppro']:NULL;
$tippro = isset($_POST['tippro']) ? $_POST['tippro']:NULL;
$just = isset($_POST['just']) ? $_POST['just']:NULL;
$redcon = isset($_POST['redcon']) ? $_POST['redcon']:NULL;
$reqing = isset($_POST['reqing']) ? $_POST['reqing']:NULL;
$reqcer = isset($_POST['reqcer']) ? $_POST['reqcer']:NULL;
$idare = isset($_POST['idare']) ? $_POST['idare']:NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;
$ope2 = isset($_POST['ope2']) ? $_POST['ope2']:NULL;

$mprg->setCodpro($codpro);
if($ope=="save"){
    $mprg->setNompro($nompro);
    $mprg->setDespro($despro);
    $mprg->setVerpro($verpro);
    $mprg->setHorlpro($horlpro);
    $mprg->setHorppro($horppro);
    $mprg->setCrelpro($crelpro);
    $mprg->setCreppro($creppro);
    $mprg->setTippro($tippro);
    $mprg->setJust($just);
    $mprg->setRedcon($redcon);
    $mprg->setReqing($reqing);
    $mprg->setReqcer($reqcer);
    $mprg->setIdare($idare);
	if($codpro && $ope2=="edit") $mprg->edit();
	else $mprg->save();
}


if ($ope=="del" && $codpro){
    $mprg->del();

    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
} 

if ($ope=="edi" && $codpro){
	$dtOne = $mprg->getOne();
}else{ 
	$dtOne=NULL;
}

$dat=$mprg->getAll();
$dtArea=$mprg->getArea();
$dtTippr=$mprg->getTipPr();
?>