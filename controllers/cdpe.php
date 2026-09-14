<?php
require_once 'models/mdpe.php';
require_once 'models/mvvc.php';
require_once 'models/mvot.php';

if (!isset($_SESSION['idusu']) || !isset($_SESSION['idper'])) {
    header("Location: index.php?error=sesion");
    exit();
}

$mdpe = new Mdpe();
$mvvc = new Mvvc();
$mvot = new Mvot();

$idusu = $_SESSION['idusu'];
$mdpe->setIdusu($idusu);
$mvvc->setIdusu($idusu);
$mvot->setIdusu($idusu);

$datOne = $mdpe->selOne();

// verifica votos independientemente
$yaVotoVocero = $mvvc->getOne();      
$yaVotoRepresentante = $mvot->getOne();


?>