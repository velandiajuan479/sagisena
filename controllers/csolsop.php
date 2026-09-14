<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/msolsop.php');
$msolsop = new Msolsop();

$idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : NULL;
$nomusu = isset($_SESSION['nomusu']) ? $_SESSION['nomusu'] : NULL;

$idsop = isset($_REQUEST['idsop']) ? $_REQUEST['idsop'] : NULL;
$falrep = isset($_POST['falrep']) ? $_POST['falrep'] : NULL;
$carper = isset($_POST['carper']) ? $_POST['carper'] : NULL;
$fecser = isset($_POST['fecser']) ? $_POST['fecser'] : NULL;
$nomper = isset($_POST['nomper']) ? $_POST['nomper'] : NULL;
$desser = isset($_POST['desser']) ? $_POST['desser'] : NULL;
$foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : NULL;
$evisop = NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

if($foto){
    if (!is_dir('fevisop')) mkdir('fevisop', 0755, true);
    $filename = opti($_FILES['foto'], $idsop, 'fevisop', 'fevisop');
    $evisop = $filename ? 'fevisop/' . $filename : null;
}

$dtOne = NULL;
$msolsop->setIdusu($idusu);
$msolsop->setIdsop($idsop);

if($ope=="save"){
    $msolsop->setIdusu2($idusu);
    $msolsop->setFalrep($falrep);
    $msolsop->setCarper($carper);
    $msolsop->setFecser($fecser);
    $msolsop->setNomper($nomper);
    $msolsop->setDesser($desser);
    if(!$idsop){
        $msolsop->setEvisop(null);
        $idsop = $msolsop->save();
        $msolsop->setIdsop($idsop);
        if($foto){
            $filename = opti($_FILES['foto'], $idsop, 'fevisop', 'fevisop');
            $evisop = $filename ? 'fevisop/' . $filename : null;
            $msolsop->setEvisop($evisop);
            $msolsop->updateEvisop();
        }
    } else {
        if($foto){
            $filename = opti($_FILES['foto'], $idsop, 'fevisop', 'fevisop');
            $evisop = $filename ? 'fevisop/' . $filename : null;
            $msolsop->setEvisop($evisop);
        }
        $msolsop->edit();
    }
}

if ($ope=="del" && $idsop) $msolsop->del();
if ($ope=="edit" && $idsop){
    $dtOne = $msolsop->getOne();
}else{
    $dtOne=NULL;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $pg = isset($_GET['pg']) ? $_GET['pg'] : 'default value';
    echo ("<script>window.location.href = 'home.php?pg=".$pg."'</script>");
}

$dat=$msolsop->getReporteEstPer();
$dtNP=$msolsop->getNom();
$dtCargo=$msolsop->getCargoPer();
$dtFalla=$msolsop->getFallaRep();
?>