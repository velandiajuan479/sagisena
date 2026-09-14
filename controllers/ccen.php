<?php
require_once('models/mcen.php');

$mcen = new Mcen();

$idcen = isset($_REQUEST['idcen'])? $_REQUEST['idcen']:NULL;
$nomcen = isset($_POST['nomcen'])? $_POST['nomcen']:NULL;
$dircen = isset($_POST['dircen'])? $_POST['dircen']:NULL;
$telcen = isset($_POST['telcen'])? $_POST['telcen']:NULL;
$imgcen = isset($_POST['imgcen'])? $_POST['imgcen']:NULL;
$descen = isset($_POST['descen'])? $_POST['descen']:NULL;
$fiicancen = isset($_POST['fiicancen'])? $_POST['fiicancen']:NULL;
$fficancen = isset($_POST['fficancen'])? $_POST['fficancen']:NULL;
$fiprocen = isset($_POST['fiprocen'])? $_POST['fiprocen']:NULL;
$ffprocen = isset($_POST['ffprocen'])? $_POST['ffprocen']:NULL;
$fivotcen = isset($_POST['fivotcen'])? $_POST['fivotcen']:NULL;
$ffvotcen = isset($_POST['ffvotcen'])? $_POST['ffvotcen']:NULL;

$opera = isset($_REQUEST['opera'])? $_REQUEST['opera']:NULL;
$ope = isset($_REQUEST['ope'])? $_REQUEST['ope']:NULL;
$datOne = NULL;
//echo $idcen." - ".$nomcen." - ".$dircen." - ".$telcen." - ".$imgcen." - ".$descen." - ".$fiicancen." - ".$fficancen." - ".$fiprocen." - ".$ffprocen." - ".$fivotcen." - ".$ffvotcen." - ".$opera;
$foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name']:NULL;
if($foto){
    $imgcen = opti($_FILES['foto'], $idcen, 'fotcen', "");
}

$mcen->setIdcen($idcen);
if($opera=="save"){
    if($nomcen){
        $mcen->setIdcen($idcen);
        $mcen->setNomcen($nomcen);
        $mcen->setDircen($dircen);
        $mcen->setTelcen($telcen);
        $mcen->setImgcen($imgcen);
        $mcen->setDescen($descen);
        $mcen->setFiicancen($fiicancen);
        $mcen->setFficancen($fficancen);
        $mcen->setFiprocen($fiprocen);
        $mcen->setFfprocen($ffprocen);
        $mcen->setFivotcen($fivotcen);
        $mcen->setFfvotcen($ffvotcen);
		if(!$idcen) $mcen->save();else $mcen->edit();
	}else echo '<script>err("Todos los datos son obligatorios.");</script>';
}

if($opera=="eli" && $idcen){
    $mcen->del();

    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}
if($ope=="edi" && $idcen) $datOne = $mcen->getOne();

//MOSTRAR TODOS LOS DATOS
$datAll = $mcen->getAll();
?>
