<?php
    
require_once('models/mact.php');

$mact = new Mact();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:NULL;
$ndocusu = isset($_POST['ndocusu']) ? $_POST['ndocusu']:NULL;
$nomusu = isset($_POST['nomusu']) ? $_POST['nomusu']:NULL;
$idper = isset($_POST['idper']) ? $_POST['idper']:NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic']:NULL;
$pasusu = isset($_POST['pasusu']) ? $_POST['pasusu']:NULL;
$idcen = isset($_POST['idcen']) ? $_POST['idcen']:NULL;
$actusu = isset($_POST['actusu']) ? $_POST['actusu']:NULL;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;

$pg =1117;

$mact->getIdusu();

//Mostrar todos los datos
$dat = $mact->selALL();
$dce = $mact->getCentro();
$dfi = $mact->getFicha();
$dpe = $mact->getPerfil();


if($idusu){
    $datOne = $mact->selOne();
}

?>