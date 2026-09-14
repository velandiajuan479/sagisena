<?php require_once ("models/mdpdr.php"); 

$mdpdr = new Mdpdr();

$iddocp = isset($_REQUEST["iddocp"]) ? $_REQUEST["iddocp"]:NULL;
$nomdocp = isset($_POST["nomdocp"]) ? $_POST["nomdocp"]:NULL;
$tipdocp = isset($_POST["tipdocp"]) ? $_POST["tipdocp"]:NULL;

$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"]:NULL;
$datOne = NULL;

//echo $iddocp. " - " .$nomdocp. " - " .$tipdocp. " - " .$opera;

$mdpdr->setIddocp($iddocp);

if($opera=="save"){
    $mdpdr->setNomdocp($nomdocp);
    $mdpdr->setTipdocp($tipdocp);
    if(!$iddocp) $mdpdr->save(); else $mdpdr->edi();
    $iddocp = NULL;
}

if($opera=="eli" && $iddocp){
    $mdpdr->eli();
    $iddocp = NULL;
}



if($opera=="edi" && $iddocp) $datOne = $mdpdr->getOne();

$datAll = $mdpdr->getAll();
$tipdocdom = $mdpdr->getTipDocDom();
?>