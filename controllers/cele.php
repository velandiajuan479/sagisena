<?php
  require_once ('models/mele.php');
  require_once ('models/mfot.php');
  require_once('models/mprele.php');

  $mele = new Mele();
  $mfot = new Mfot();

  $mprele = new Mprele();
  $dtAllPr = $mprele->getAll($_SESSION['idper'], $_SESSION['idusu']);

  $fecha = date("YmdHms");
  $idele = isset($_REQUEST['idele']) ? $_REQUEST['idele'] : NULL;
  $idusu = isset($_POST['idusu']) ? $_POST['idusu'] : $_SESSION["idusu"];
  $nomele = isset($_POST['nomele']) ? $_POST['nomele'] : NULL;
  $nidele = isset($_POST['nidele']) ? $_POST['nidele'] : NULL;
  $marele = isset($_POST['marele']) ? $_POST['marele'] : NULL;
  $tipele = isset($_POST['tipele']) ? $_POST['tipele'] : NULL;
  $noplasena = isset($_POST['noplasena']) ? $_POST['noplasena'] : NULL;
  $desele = isset($_POST['desele']) ? $_POST['desele'] : NULL;
  $preele = isset($_REQUEST['preele']) ? $_REQUEST['preele'] : NULL;
  $rutfot = isset($_POST['rutfot']) ? $_POST['rutfot'] : NULL;
  $foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : NULL;

  $opera = isset($_REQUEST['opera']) ? $_REQUEST['opera'] : NULL;
  $ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;
  $datOne = NULL;

  if ($foto) $rutfot = opti($_FILES['foto'], $idusu, 'fotele', $fecha);

  $mele->setIdele($idele);
  if ($opera=="save"){
    $mele->setIdusu($idusu);
    $mele->setNomele($nomele);
    $mele->setNidele($nidele);
    $mele->setMarele($marele);
    $mele->setTipele($tipele);
    $mele->setNoplasena($noplasena);
    $mele->setDesele($desele);
    $mele->setRutfot($rutfot);
    if (!$idele) {
      $idele = $mele->save();
      var_dump($idele);
      /* $ele = $mele->getOneId(); */
      if (isset($idele)) $mfot->setIdele($idele);
      else {
        echo 'Id de elemento no encontrado';
        exit();
      }
      if (!$rutfot) $rutfot = 'img/no-found.jpg';
      $mfot->setRutfot($rutfot);
      $mfot->saveFoto();
    } else {
      $mele->edit();
      if ($rutfot) {
        $mfot->setIdele($idele);
        $mfot->setRutfot($rutfot);
        $mfot->editFoto();
      }
    } 
  }

  if ($idele && $opera=="editPre") {
    $mele->setPreele($preele);
    $mele->editPre();
  }

  if ($opera=="eli" && $idele) {
    $mfot->setIdele($idele);
    $mfot->delFot();
    $mele->del();
  }

  if ($ope=="edi" && $idele) $datOne = $mele->getOne();

  $datAll = $mele->getAll();
  $datAllTe = $mele->getEle();
?>