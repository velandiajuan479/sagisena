<?php
// Controlador del reporte por ficha (vrphfc)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/mrphfc.php';
require_once __DIR__ . '/../models/mhor.php';

date_default_timezone_set('America/Bogota');

$idfic = $_REQUEST['idfic'] ?? null;

$mrphfc = new Mrphfc();
$mhor   = new Mhor();

// Datos de cabecera
$mrphfc->setIdfic($idfic);
$dtOneHeader = $mrphfc->getOne();

// Obtener trimestre actual desde eventos de calendario (tipo 'trimestre')
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
        // Fallback mensual si no hay eventos
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

// Municipìo desde ficha
$__municipio = $mrphfc->getMunicipioPorFicha($idfic);

// Cargar horarios del rango
$dtOne = $mrphfc->getHorariosFichaEnRango($idfic, $__iniTr, $__finTr);

// Renderizar vista
require_once __DIR__ . '/../views/vrphfc.php';
<?php

<?php 
include("models/mrphfc.php");
$mrphfc=new Mrphfc();
$aul = $mrphfc->getAllaul();

$idhor = isset($_REQUEST['idhor']) ? $_REQUEST['idhor']:NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic']:NULL;

$idaul = isset($_REQUEST['idaul']) ? $_REQUEST['idaul']:NULL;
$iddia = isset($_REQUEST['iddia']) ? $_REQUEST['iddia']:NULL;
$jornada = isset($_REQUEST['jornada']) ? $_REQUEST['jornada']:NULL;

$idusu = isset($_POST['idusu']) ? $_POST['idusu']:NULL;
//$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;
$mrphfc->setIdfic($idfic);
$mrphfc->setIdaul($idaul);
$mrphfc->setIddia($iddia);
$mrphfc->setJornada($jornada);

$dtOne = $mrphfc->getOne();
$dat=$mrphfc->getAll();

?>