<?php
require_once 'models/mrcv.php';

$mrcv = new Mrcv();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : NULL;
$fidfic = isset($_REQUEST['fidfic']) ? $_REQUEST['fidfic'] : NULL;

// obtiene todas las fichas para el selector
$fichas = $mrcv->getFichas();

// obtiene informacion de la ficha seleccionada
$fichaActual = [];
$dat = [];
if($fidfic) {
    $fichaActual = current(array_filter($fichas, function($f) use ($fidfic) {
        return $f['idfic'] == $fidfic;
    }));
    
    // obteniene voceros elegidos de la ficha seleccionada
    $dat = $mrcv->getVocerosElectosPorFicha($fidfic);
}
?>