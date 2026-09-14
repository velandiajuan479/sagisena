<?php 
require_once("models/mregm.php"); 

$mregm = new Mregm();

$iduxf = isset($_REQUEST['iduxf']) ? $_REQUEST['iduxf'] : NULL;
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic'] : NULL;
$fecreg = isset($_POST['fecreg']) ? $_POST['fecreg'] : NULL;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera'] : NULL;
$datOne = NULL;

if ($opera == "save") {
    $mregm->setIdusu($idusu);
    $mregm->setIdfic($idfic);
    $mregm->setFecreg($fecreg);
    if (!$iduxf) {
        $mregm->save();
    } else {
        $mregm->setIduxf($iduxf);
        $mregm->edit();
    }
}

if ($opera == "eli" && $iduxf) {
    $mregm->setIduxf($iduxf);
    $mregm->del();  
}

if ($opera == "edit" && $iduxf) {
    $mregm->setIduxf($iduxf);
    $datOne = $mregm->getOne();
    if ($datOne && count($datOne) > 0) {
        $idusu = $datOne[0]['idusu'];
        $idfic = $datOne[0]['idfic'];
        $fecreg = $datOne[0]['fecreg'];
    }
}

$datUsu = $mregm->getAllUsu();
$datFic = $mregm->getAllFic();
$datAll = $mregm->getAll();
$datAllTot = $mregm->getAllTot();
?>
