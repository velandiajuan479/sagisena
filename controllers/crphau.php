<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/mrphau.php';
require_once __DIR__ . '/../models/mhor.php';

date_default_timezone_set('America/Bogota');

$idaul = $_REQUEST['idaul'] ?? null;

$mrphau = new Mrphau();
$mhor   = new Mhor();

$mrphau->setIdaul($idaul);
$dtOneHeader = $mrphau->getOne();

// Trimestre actual por eventos
$anioActual = (int)date('Y');
$eventosTr  = $mhor->getEventosPorTipo('trimestre', $anioActual);
$hoy        = new DateTime('now', new DateTimeZone('America/Bogota'));

$__trActual = null;
foreach ($eventosTr as $ev) {
    if (!empty($ev['fecha_inicio']) && !empty($ev['fecha_fin'])) {
        $ini = new DateTime($ev['fecha_inicio']);
        $fin = new DateTime($ev['fecha_fin']);
        if ($hoy >= $ini && $hoy <= $fin) {
            $__trActual = [
                'nombre' => $ev['trimestre'] ?: 'Trimestre',
                'año' => (int)$ev['anio'],
                'fecha_inicio' => $ev['fecha_inicio'],
                'fecha_fin' => $ev['fecha_fin']
            ];
            break;
        }
    }
}

if (!$__trActual) {
    if (!empty($eventosTr)) {
        $ultimo = end($eventosTr);
        $__trActual = [
            'nombre' => $ultimo['trimestre'] ?: 'Trimestre',
            'año' => (int)$ultimo['anio'],
            'fecha_inicio' => $ultimo['fecha_inicio'],
            'fecha_fin' => $ultimo['fecha_fin']
        ];
    } else {
        $__trActual = [
            'nombre' => 'Trimestre',
            'año' => $anioActual,
            'fecha_inicio' => date('Y-m-01'),
            'fecha_fin' => date('Y-m-t')
        ];
    }
}

$__iniTr = $__trActual['fecha_inicio'];
$__finTr = $__trActual['fecha_fin'];

// Horarios del aula en el rango
$dtOne = $mrphau->getHorariosAulaEnRango($idaul, $__iniTr, $__finTr);

require_once __DIR__ . '/../views/vrphau.php';
<?php

<?php 
include("models/mrphau.php");
$mrphau=new Mrphau();
$aul = $mrphau->getAllaul();

$idhor = isset($_REQUEST['idhor']) ? $_REQUEST['idhor']:NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic']:NULL;
$idaul = isset($_REQUEST['idaul']) ? $_REQUEST['idaul']:NULL;
$idusu = isset($_POST['idusu']) ? $_POST['idusu']:NULL;
$iddia = isset($_REQUEST['iddia']) ? $_REQUEST['iddia'] : NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;

//$mrphau->setIddia($iddia);
$mrphau->setIdaul($idaul);
$mrphau->setIdhor($idhor);

if ($ope=="save") {
	$mrphau->setIdfic($idfic);
	$mrphau->setIdaul($idaul);
	$mrphau->setIdusu($idusu);
	$mrphau->setIddia($iddia);
	if($idhor) $mrphau->edit();
	else $mrphau->save();
}
$dtOne = $mrphau->getOne();

$dat=$mrphau->getAll();

?>