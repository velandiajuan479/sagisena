<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/mdtsop.php';

$mdtsop = new Mdtsop;

$idsop = isset($_REQUEST['idsop']) ? $_REQUEST['idsop'] : NULL;

$iddet = isset($_REQUEST['iddet']) ? $_REQUEST['iddet'] : NULL;
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
$detest = isset($_POST['detest']) ? $_POST['detest'] : NULL;
$detcom = isset($_POST['detcom']) ? $_POST['detcom'] : NULL;
$foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : NULL;
$detevi = NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

/*if($foto){
    if (!is_dir('fdetevi')) mkdir('fdetevi', 0755, true);
    $filename = opti($_FILES['foto'], $idsop, 'fdetevi', 'fdetevi');
    $detevi = $filename ? 'fdetevi/' . $filename : null;
}*/

$dtOne = NULL;

$mdtsop->setIddet($iddet);
$mdtsop->setIdsop($idsop);

if($ope=="save"){
    $mdtsop->setIdusu($idusu);
    $mdtsop->setDetest($detest);
    $mdtsop->setDetcom($detcom);

    if(!$iddet){
        $mdtsop->setDetevi(null);
        $iddet = $mdtsop->save();
        $mdtsop->setIddet($iddet);
        if($foto){
            $filename = opti($_FILES['foto'], $iddet, 'fdetevi', 'fdetevi');
            $detevi = $filename ? 'fdetevi/' . $filename : null;
            $mdtsop->setDetevi($detevi);
            $mdtsop->updateDetevi();
        }
    } else {
        if($foto){
            $filename = opti($_FILES['foto'], $iddet, 'fdetevi', 'fdetevi');
            $detevi = $filename ? 'fdetevi/' . $filename : null;
            $mdtsop->setDetevi($detevi);
            $mdtsop->updateDetevi();
        }
        $mdtsop->edit();
    }

    if($detest == 1074){
        $mdtsop->finalizarSoporte();
    }
}

if ($ope=="del" && $iddet) $mdtsop->del();
if ($ope=="edit" && $iddet){
    $dtOne = $mdtsop->getOne();
}else{
    $dtOne=NULL;
}

$ultimoEstadoRow = $mdtsop->getUltimoEstado();
$estadoFinalizado = ($ultimoEstadoRow && $ultimoEstadoRow['detest'] == 1074) ? true : false;

if ($_SERVER['REQUEST_METHOD']=='POST' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    $pg = isset($_GET['pg']) ? $_GET['pg'] : 'default value';
    echo ("<script>window.location.href = 'home.php?pg=".$pg."&idsop=".$idsop."'</script>");
}

$dat = $mdtsop->getAll();
$datOne = $mdtsop->getOne();
$dtSelsop = $mdtsop->getSelSop();
$dtEst = $mdtsop->getEstado();
?>