<?php
require_once('models/matse.php'); 

$idact = isset($_REQUEST['idact']) ? $_REQUEST['idact']:NULL;
$idses = isset($_REQUEST['idses']) ? $_REQUEST['idses']:NULL;
$nomact = isset($_POST['nomact']) ? $_POST['nomact']:NULL;
$desact = isset($_POST['desact']) ? $_POST['desact']:NULL;
$duract = isset($_POST['duract']) ? $_POST['duract']:NULL;
$tipact = isset($_POST['tipact']) ? $_POST['tipact']:NULL;
$ordact = isset($_POST['ordact']) ? $_POST['ordact']:NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;


$matse=new Matse();
$matse->setIdact($idact);
if ($ope=="save") {
	$matse->setIdact($idact);
	$matse->setNomact($nomact);
	$matse->setIdses($idses);
	$matse->setDesact($desact);
	$matse->setDuract($duract);
	$matse->setTipact($tipact);
	$matse->setOrdact($ordact);
	if($idact) $matse->edit();
	else $matse->save();
} 

$m=2;
if ($ope=="del" && $idact) {$matse->del();}
if ($ope=="edi" && $idact){
	$dtOne = $matse->getOne();
	$m=1;
}else{ 
	$dtOne=NULL;
}

$dat=$matse->getAll();
$dses = $matse->getSesion();
?>