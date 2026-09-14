<?php 
include "models/mubi.php";

$codubi = isset($_REQUEST['codubi']) ? $_REQUEST['codubi']:NULL;
$nomubi = isset($_POST['nomubi']) ? $_POST['nomubi']:NULL;
$depubi = isset($_POST['depubi']) ? $_POST['depubi']:NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;

$mubi=new Mubi();
$mubi->setCodubi($codubi);
if ($ope=="save") {
	$mubi->setNomubi($nomubi);
	$mubi->setDepubi($depubi);
	if($codubi) $mubi->edit();
	else $mubi->save();
}

$m=2;
if ($ope=="del" && $codubi){
	$mubi->del();

	echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
} 
if ($ope=="edi" && $codubi){
	$dtOne = $mubi->getOne();
	$m=1;
}else{ 
	$dtOne=NULL;
}

$dat=$mubi->getAll();
$dtDtp =$mubi->getDep(0);
?>