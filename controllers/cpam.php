<?php
  include("models/mmin.php");
  include("models/mres.php");
  include("models/mval.php");
  include("models/mpam.php");

  $mres = new Mres();
  $mmin = new Mmin();
  $mval = new Mval();
  $mpam = new Mpam();

  $idval = isset($_REQUEST['idval']) ? $_REQUEST['idval'] : NULL;
  $fecha = date("YmdHms");
  $idusu = $_SESSION['idusu'];
  $fechos = date('Y-m-d H:i:s');
  $nummin = isset($_REQUEST['nummin']) ? $_REQUEST['nummin'] : NULL;
  $nomval = isset($_REQUEST['nomval']) ? $_REQUEST['nomval'] : NULL;
  $parval = isset($_REQUEST['parval']) ? $_REQUEST['parval'] : NULL;
  $ambi = isset($_REQUEST['nomval']) ? $_REQUEST['nomval'] : NULL;

  $ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;
  $datOne = NULL;

  $mmin->setIdusu($idusu);
  $mval->setIdval($idval);
  if ($ope == "save") {
    $parval = $mpam->getParval($parval);
    $mres->setIdusu($idusu);
    $mres->setIdeles($idval);
    $mres->setFechos($fechos);
    $mpam->setAct(2);
    $mpam->setIdval($idval);
    $rti = $mres->getVuelta();
    $mres->setTipmin('N');
    $res = $mres->save();
    $mpam->save();
    echo '<script type="text/javascript"> window.location.href ="home.php?pg='.$pg.'";</script>';
  }

  if ($ope == "edit" && $idval && $nummin) {
    $datOne = $mpam->getOne();
    $valo = $mpam->selOneAmb($idval);
    $mres->setIdusu($idusu);
    $mres->setIdeles($idval);
    $mres->setFechos($fechos);
    $mpam->setAct(1);
    $mpam->setIdval($idval);
    $rti = $mres->getVuelta();
    $minu = $mres->getOneP($nummin);
    $mres->updHij($fechos, $rti[0]['nummin']);
    $mpam->save();
    echo '<script type="text/javascript"> window.location.href ="home.php?pg='.$pg.'";</script>';
  }

  if ($ope == "editnov" && $nomval) {
    $mpam->setNomval($nomval);
    $mpam->edit();
  }

  $datAll = $mmin->getTodoA();
  $datOne = $mpam->getOne();
  $datAmb = $mpam->getOneDom();
?>