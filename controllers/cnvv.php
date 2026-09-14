<?php
require_once ('models/mnvv.php');

$mnvv = new Mnvv();
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
$actusu = isset($_POST['actusu']) ? $_POST['actusu'] : NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic'] : NULL;
$fidfic = isset($_POST['fidfic']) ? $_POST['fidfic'] : NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;


if($ope == "save" || $ope == "edit") {
    $mnvv->setIdfic($idfic);
    $mnvv->setActusu($actusu);
    $mnvv->setIdusu($idusu);
    
    if($ope == "edit") {
        $mnvv->edit();
    } else {
        $mnvv->save();
    }
}

if($ope == "act" && $idusu && $actusu) {
    $mnvv->setActusu($actusu);
    $mnvv->editAct();
}

// se obtinen todas las fichas
$sqlFichas = "SELECT DISTINCT uf.idfic, f.nomfic, v.nomval 
              FROM usufic uf
              JOIN ficha f ON uf.idfic = f.idfic
              JOIN valor v ON f.jornada = v.idval
              WHERE uf.actfic = 1
              ORDER BY uf.idfic";
$modelo = new conexion();
$conexion = $modelo->get_conexion();
$result = $conexion->prepare($sqlFichas);
$result->execute();
$fichas = $result->fetchall(PDO::FETCH_ASSOC);

// se obtiene los aprendices y las estadisticas de acuerdo a la ficha seleccionada
$dat = [];
$gaf = ['total_personas' => 0, 'votaron' => 0, 'no_votaron' => 0];

if($fidfic) {
    $dat = $mnvv->getAprendicesPorFicha($fidfic);
    $gaf = $mnvv->getEstadisticasPorFicha($fidfic);
}
?>