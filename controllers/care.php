<?php
require_once("models/mare.php");

$mare = new Mare();
$idare = isset($_REQUEST["idare"]) ? $_REQUEST["idare"] : NULL;
$nomare = isset($_POST["nomare"]) ? $_POST["nomare"] : NULL;
$idusu = isset($_POST["idusu"]) ? $_POST["idusu"] : NULL;

$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"] : NULL;
$datOne = NULL;

//echo $idare." - ".$nomare." - ".$idusu." - ".$opera;
$mare->setIdare($idare);
if ($opera == "save") {
	$mare->setNomare($nomare);
	$mare->setIdusu($idusu);
	if (!$idare)
		$mare->save();
	else
		$mare->edit();
	$idare = NULL;
}

if ($opera == "eli" && $idare) {
	$mare->setidare($idare);
	if ($mare->del())
		echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
	else echo "<script>
					setTimeout(() => {
    					window.location.href = 'home.php?pg=$pg';
					}, 3000);
				</script>";
	$idare = NULL;
	exit;
}

if ($opera == "edi" && $idare)
	$datOne = $mare->getOne();

$datUsu = $mare->getAllusu();
$datAll = $mare->getAll();
?>