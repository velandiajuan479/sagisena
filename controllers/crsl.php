<?php
include("models/mrsl.php");
$mresu = new Mrsl();

$idres = isset($_REQUEST['idres']) ? $_REQUEST['idres'] : NULL;
$nomres = isset($_POST['nomres']) ? $_POST['nomres'] : NULL;
$idcom = isset($_POST['idcom']) ? $_POST['idcom'] : NULL;
$ndses = isset($_POST['ndses']) ? $_POST['ndses'] : NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

$mresu->setIdres($idres);

if ($ope == "save") {
    if ($idres) {
        $datOne = $mresu->getOne();
    }
    $mresu->setNomres($nomres);
    $mresu->setIdcom($idcom);
    $mresu->setNdeses($ndses);

    if (!$idres) {
        $mresu->save();
    } else {
        $mresu->edit();
    }
}

if ($ope == "del" && $idres) {
    $mresu->del();
}

if ($ope == "edit" && $idres) {
    $datOne = $mresu->getOne();
} else {
    $datOne = NULL;
}

$datAll = $mresu->getAll();
if (isset($datAll) && is_array($datAll)) {
  
  foreach ($datAll as $resultado) {
    echo $resultado['nomres'] . "<br>";  
   
  }
}
?>
