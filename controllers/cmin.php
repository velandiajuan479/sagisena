<?php
// require_once 'models/conexion.php';
require_once 'models/mmin.php';
require_once 'models/musu.php';

$mmin = new Mmin();
$musu = new Musu();
date_default_timezone_set('America/Bogota');
$fecha = date("Y-m-d");
$idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu']:NULL;
$nummin = isset($_POST['nummin']) ? $_POST['nummin']:NULL;
$fechos = isset($_POST['fechos']) ? $_POST['fechos']:$fecha;
$tipmin = isset($_POST['tipmin']) ? $_POST['tipmin']:NULL;
$ndocusu = isset($_POST['ndocusu']) ? $_POST['ndocusu']:NULL;
$hij = isset($_POST['hij']) ? $_POST['hij']:NULL;
$fhlle = isset($_POST['fhlle']) ? $_POST['fhlle']:$fecha;
$obs = isset($_POST['obs']) ? $_POST['obs']:NULL;
$ideles = isset($_POST['ideles']) ? $_POST['ideles']:NULL;
$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;

$opecer = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;

$datOne = NULL; 

$pg = 1404;
//echo $idusu."-".$nummin."-".$fechos."-".$tipmin."-".$hij."-".$fhlle."-".$obs."-".$ideles."-";


if($fechos OR $ndocusu){
    $mmin -> setIdusu($ndocusu);
    $dat = $mmin->getTodo($fechos, $fhlle);
    $datP = $mmin->getTodoP($fechos, $fhlle);
}
/* $musu -> setActusu(1);
$datAllUsu = $musu -> getAllUsu(); */
// if($idusu){
// 	$datOne = $mmin->getOne($idusu);
// }
?>