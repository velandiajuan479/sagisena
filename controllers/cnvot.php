<?php

require_once ('models/mnvot.php');

$mnvot = new Mnvot();
$idusu = isset($_POST['idusu']) ? $_POST['idusu']:NULL;
$actusu = isset($_POST['actusu']) ? $_POST['actusu']:NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic']:NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;

if($ope=="save" OR $ope=="edit"){
    $mnvot->setIdfic($idfic);
	$mnvot->setActusu($actusu);
	$mnvot->setIdusu($idusu);
    if($ope=="edit") $mnvot->edit();
	else $mnvot->save();
}
if($ope=="act" && $idusu && $actusu){
	$mnvot->setActusu($actusu);
	$mnvot->editAct();
}
//mostrar todos los datos
$dat = $mnvot->getAll();
$gaf = $mnvot->getGraphic();
// $votaciones = $mnvot->getVotacionUsuarios();

?>