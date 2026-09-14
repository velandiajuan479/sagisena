<?php
  require_once('models/mprele.php');
  require_once('models/mres.php');

  $mprele = new Mprele();
  $mres = new Mres();

  $date = date('Y-m-d H:i:s');
  $idprele = isset($_REQUEST['idprele']) ? $_REQUEST['idprele'] : NULL;
  $idele = isset($_REQUEST['idele']) ? $_REQUEST['idele'] : NULL;
  $docusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : NULL;
  $fhpre = isset($_POST['fhpre']) ? $_POST['fhpre'] : $date;
  $fhent = isset($_POST['fhent']) ? $_POST['fhent'] : NULL;
  $estpre = isset($_POST['estpre']) ? $_POST['estpre'] : NULL;
  $whopre = isset($_REQUEST['whopre']) ? $_REQUEST['whopre'] : NULL;
	$can = isset($_POST['can']) ? $_POST['can']	:	NULL;
  $idusu = isset($_GET['idusu']) ? $_GET['idusu'] : NULL;

  $ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;
  $dtOne = NULL;

  $mprele->setIdprele($idprele);
  if ($ope == 'save') {
    $idusu = $mprele->getUsu($docusu);
    $mprele->setIdusu($idusu[0]['idusu']);
    $mprele->setIdele($idele);
    $ele = $mprele->getElem();
    $mprele->setFhpre($fhpre);
    $mprele->setEstpre(1);
    $mprele->setWhopre($_SESSION['idusu']);
    if ($idprele) $mprele->edit();
    else $mprele->save();

		$mres->setIdusu($idusu[0]['idusu']);
		$mres->setIdeles($idele);
		$mres->setFechos($fhpre);
		$mres->setTipmin('A');
		$mres->setObs($can);
		$res = $mres->save();
		echo '<script type="text/javascript"> window.location.href ="home.php?pg='.$pg.'";</script>';
  }
  
  if ($ope == 'entEle' && $idprele && $idele && $idusu) {
    $mprele->setEstpre(2);
    $mprele->setFhent($date);
    $mprele->entEle();
    $mprele->setIdele($idele);
    $mprele->setIdusu($idusu);
    $min = $mprele->getMin();

    $mres->setNummin($min[0]['nummin']);
		$mres->setTipmin('E');
		$mres->setFhlle($fhent);
		$mres->updHij();
		echo '<script type="text/javascript"> window.location.href ="home.php?pg='.$pg.'";</script>';
  }
  if ($ope == 'del' && $idprele) $mprele->del();

  $dtAll = $mprele->getAll($_SESSION['idper'], $_SESSION['idusu']);
  $dtEle = $mprele->getEles();
?>