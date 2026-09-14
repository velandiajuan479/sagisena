<?php
require_once("models/mhcm.php");

$mhcm = new Mhcm();

$datAll = $mhcm->getAll();

$idnorad = isset($_GET["idnorad"]) ? $_GET["idnorad"] : NULL;

if ($idnorad) {
    $mhcm->setIdnorad($idnorad);
    $dtaEdit = $mhcm->getOne();
    if ($dtaEdit) {
        $dtaEdit = $dtaEdit[0]; 
    }
}

$success = isset($_GET["success"]) ? $_GET["success"] : NULL;
?>
