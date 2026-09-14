<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/msersop.php');
$msersop = new Msersop();

$idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : NULL;
$idsop = isset($_REQUEST['idsop']) ? $_REQUEST['idsop'] : NULL;

$dtOne = NULL;

$msersop->setIdusu($idusu);
$msersop->setIdsop($idsop);

if (isset($_GET['grafico'])) {
    header('Content-Type: application/json');
    $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
    switch ($_GET['grafico']) {
        case 'por_falla':
            echo json_encode($msersop->getSoporteFinalizadoPorFallaPer($filter));
            break;
        case 'por_cargo':
            echo json_encode($msersop->getSoportePorCargoPer($filter));
            break;
        case 'por_usuario':
            echo json_encode($msersop->getSoportePorUsuarioPer($filter));
            break;
        default:
            echo json_encode([]);
    }
    exit;
}

if ($ope=="del" && $idsop) $msersop->del();
if ($ope=="edit" && $idsop){
    $dtOne = $msersop->getOne();
}else{
    $dtOne=NULL;
}

$dtRppr=$msersop->getReporteEstPer();
?>