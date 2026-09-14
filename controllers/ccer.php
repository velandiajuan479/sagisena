<?php

require_once 'models/mcer.php';

$mcer = new Mcer();
$idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu']:NULL;
$ndocusu = isset($_POST['ndocusu']) ? $_POST['ndocusu']:NULL;
$nomusu = isset($_POST['nomusu']) ? $_POST['nomusu']:NULL;
$idper = isset($_POST['idper']) ? $_POST['idper']:NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic']:NULL;
$pasusu = isset($_POST['pasusu']) ? $_POST['pasusu']:NULL;
$idcen = isset($_POST['idcen']) ? $_POST['idcen']:NULL;
$actusu = isset($_POST['actusu']) ? $_POST['actusu']:NULL;
$opecer = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$datOne = NULL;

$pg = 1205;



//echo $idusu."-".$ndocusu."-".$nomusu."-".$idper."-".$idfic."-".$pasusu."-".$idcen."-".$actusu."-".$opera;


//Insertar
$mcer->setIdusu($idusu);
if($idusu){
	
}

//mostrar todos los datos
$dat = $mcer->selAll();
$dce = $mcer->getCentro();
$dfi = $mcer->getFicha();
$dpe = $mcer->getPerfil();

if($idusu){
	$datOne = $mcer->selOne();
}

?>
