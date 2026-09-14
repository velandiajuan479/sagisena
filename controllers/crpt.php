<?php
require_once 'models/mrpt.php';

$idfic = $_REQUEST['idfic'] ?? null;
$trimestre = $_REQUEST['trimestre'] ?? null;

$mrpt = new Mrpt();
$mrpt->setIdfic($idfic);

$dtPrg = [];
$dtComRes = [];
$dtFechas = [];

if ($idfic) {
    $dtPrg = $mrpt->getFichaPrograma();
    $dtFechas = $mrpt->getFechasFicha();
    $dtComRes = $mrpt->getCompetenciasResultados($trimestre); // <-- ahora con trimestre
} else {
    $error_message = "Por favor ingrese una ficha válida.";
}
?>