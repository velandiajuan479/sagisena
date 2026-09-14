<?php
require_once("models/memp.php"); 

	$memp = new Memp();
	$idemp = isset($_REQUEST["idemp"]) ? $_REQUEST["idemp"] : NULL;
	$numdocemp = isset($_POST["numdocemp"]) ? $_POST["numdocemp"] : NULL;
	$nomemp = isset($_POST["nomemp"]) ? $_POST["nomemp"] : NULL;
	$diremp = isset($_POST["diremp"]) ? $_POST["diremp"] : NULL;
	$codubi = isset($_POST["ubiest"]) ? $_POST["ubiest"] : NULL;
	$nomconemp = isset($_POST["nomconemp"]) ? $_POST["nomconemp"] : NULL;
	$telemp = isset($_POST["telemp"]) ? $_POST["telemp"] : NULL;

	$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"] : NULL;
	$datOne = NULL;

	$memp->setIdemp($idemp);
	if($opera == "save" && $codubi) {
		$memp->setNumdocemp($numdocemp);
		$memp->setNomemp($nomemp);
		$memp->setDiremp($diremp);
		$memp->setCodubi($codubi);
		$memp->setNomconemp($nomconemp);
		$memp->setTelemp($telemp);
		
		if(!$idemp) $memp->save(); else $memp->edit();
		$idemp = NULL;
	}

	if($opera == "eli" && $idemp) {
		$memp->del();
		$idemp = NULL;

		echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    	exit;
	}

	if($opera == "edi" && $idemp) {
		$datOne = $memp->getOne();
	}

	$datUbi = $memp->getAllDep();
	$datAll = $memp->getAll();
?>