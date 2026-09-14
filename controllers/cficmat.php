<?php 
require_once("models/mficmat.php");

$mficmat = new Mficmat();

$idfic = isset($_REQUEST["idfic"]) ? $_REQUEST["idfic"]:NULL;
$nomfic = isset($_POST["nomfic"]) ? $_POST["nomfic"]:NULL;
$codpro = '1';
$idusu = NULL;
$jornada = isset($_POST["jornada"]) ? $_POST["jornada"]:NULL;
$idcen = isset($_POST["idcen"]) ? $_POST["idcen"]:NULL;
$mun = isset($_POST["ubiest"]) ? $_POST["ubiest"]:NULL;
$finific = isset($_POST["finific"]) ? $_POST["finific"]:NULL;
$ffinfic = isset($_POST["ffinfic"]) ? $_POST["ffinfic"]:NULL;

$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"]:NULL;
$datOne = NULL;

//echo $idfic." - ".$nomfic." - ".$codpro." - ".$idusu." - ".$jornada." - ".$idcen." - ".$mun." - ".$finific." - ".$ffinfic." - ".$opera;


$mficmat->setIdfic($idfic);
if(($opera=="save" || $opera=="save1") AND $mun){
	$mficmat->setNomfic($nomfic);
	$mficmat->setCodpro($codpro);
	$mficmat->setIdusu($idusu);
	$mficmat->setJornada($jornada);
	$mficmat->setIdcen($idcen);
	$mficmat->setMun($mun);
	$mficmat->setFinific($finific);
	$mficmat->setFfinfic($ffinfic);
	if($opera=="save"){
		$mficmat->save();
	}else{
		$mficmat->edit();
	}
	
}

if($opera=="eli" && $idfic){
	$mficmat->del();
	$idfic = NULL;

	echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}

if($opera=="edi" && $idfic){
	$datOne = $mficmat->getOne();
}

$datDep = $mficmat->getAllDep();
$datJor = $mficmat->getAllJor();
$datCen = $mficmat->getALLCen();
$datAll = $mficmat->getAll();
?>