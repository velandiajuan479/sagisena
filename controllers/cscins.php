<?php 
include("models/mscins.php");

$idses = isset($_REQUEST['idses']) ? $_REQUEST['idses']:NULL;
$idage = isset($_POST['idage']) ? $_POST['idage']:NULL;
$fecses = isset($_POST['fecses']) ? $_POST['fecses']:NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;

$mscins=new Mscins();
$mscins->setIdses($idses);
if ($ope=="save") {
	$mscins->setIdage($idage);
	$mscins->setFecses($fecses);
	if($idses) $mscins->edit();
	else $mscins->save();
}

$m=2;
if ($ope=="del" && $idses) $mscins->del();
if ($ope=="edit" && $idses){
	$dtOne = $mscins->getOne();
	$m=1;
}else{ 
	$dtOne=NULL;
}

$dat=$mscins->getAll();
$dage=$mscins->getAge();
?>