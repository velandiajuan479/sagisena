<?php 
require_once 'models/mdec.php';
require_once 'models/musu.php';
$mdec = new Mdec();
$musu = new Musu();
$idusu = isset($_REQUEST['idusu'])? $_REQUEST['idusu']:NULL;
$iddec = isset($_POST['iddec'])? $_POST['iddec']:NULL;
$idfic = isset($_POST['idfic'])? $_POST['idfic']:NULL;
$fecregdec = isset($_POST['fecregdec'])? $_POST['fecregdec']:NULL;
$obsdec = isset($_REQUEST['obsdec'])? $_REQUEST['obsdec']:NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;
$datAllPer = NULL;
if ($idusu) {
    $mdec->setIdusu($idusu);
    $musu->setIdusu($idusu);
    $datAllPer = $musu->getOne();
}

$pg=1315;
$datusu = $musu->getOne();

if($ope == "save" && $datTP){
    $mdec -> setIdusu($idusu);
    if($idusu)$mdec -> del();
    foreach($datTP as $dTp){
        $c = isset($_POST['c'.$dTp["idusu"]]) ? $_POST['c'.$dTp["idusu"]]:NULL;
    if($c and $idusu){
        $mdec -> setIdusu($dTp["idusu"]);
        $mdec -> setValdes($c);
        $mdec -> save();
       }
    }
}

$datOne = NULL;
$datAll = NULL;




if($iddec) $datAll = $mdec->getAll();
?>