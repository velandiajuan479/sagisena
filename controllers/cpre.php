<?php
	include('models/mpre.php');
	include("models/mres.php");
	include("models/mmin.php");
	date_default_timezone_set('America/Bogota');

	$mpre = new Mpre();
	$mres = new Mres();

	$mmin = new Mmin();
	$datAll = $mmin->getTodoP();

	$nummin = isset($_REQUEST['nummin']) ? $_REQUEST['nummin'] : NULL;
	$idele = isset($_REQUEST['idele']) ? $_REQUEST['idele']	:	NULL;
	$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : $_SESSION["idusu"];
	$desele = isset($_POST['desele']) ? $_POST['desele'] : NULL;
	$preele = isset($_POST['preele']) ? $_POST['preele'] : NULL;
	$can = isset($_POST['can']) ? $_POST['can']	:	NULL;
	$fechos = date('Y-m-d H:i:s');

	$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']	:	NULL;
	$datOne = NULL;

	$mpre->setIdele($idele);
	$elem = $mpre->getElem($idele);
	if ($opera == "save") {
		$ndoc = $mpre->getUsu($idusu);
		$mres->setIdusu($ndoc[0]['idusu']);
		$mres->setIdeles($idele);
		$mres->setFechos($fechos);
		/* $mres->setTipmin('I');
		$rti = $mres->getVuelta(); */
		$mres->setTipmin('A');
		$mres->setObs($can);
		$res = $mres->save();
		$mpre->save($elem[0]['desele'], $can);
		echo '<script type="text/javascript"> window.location.href ="home.php?pg='.$pg.'";</script>';
	}

	if ($opera == "edi") {
		$datOne = $mpre->getOne();
		$mres->setIdusu($idusu);
		$mres->setIdeles($idele);
		$mres->setFechos($fechos);
		/* $mres->setTipmin('A');
		$rti = $mres->getVuelta(); */
		$minu = $mres->getOneP($nummin);
		$mres->setTipmin('E');
		$mres->updHij($fechos, $rti[0]['nummin']);
		$mpre->edit($elem[0]['desele'], $minu[0]['obs']);
		echo '<script type="text/javascript"> window.location.href ="home.php?pg='.$pg.'";</script>';
	}

	$datEle = $mpre->getEle();
	$datAllpre = $mpre->getAllpre();
?>