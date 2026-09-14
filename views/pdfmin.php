<?php
require_once("../models/seguridad.php");
require_once "../models/mmin.php";
include('../models/conexion.php');

ini_set('memory_limit', '4096M');
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;


// --------CONTROLADOR----------

$mmin = new Mmin();
$nummin = isset($_POST['nummin']) ? $_POST['nummin'] : NULL;
$tipmin = isset($_POST['tipmin']) ? $_POST['tipmin'] : NULL;
$hij = isset($_POST['hij']) ? $_POST['hij'] : NULL;
$obs = isset($_POST['obs']) ? $_POST['obs'] : NULL;
$ideles = isset($_POST['ideles']) ? $_POST['ideles'] : NULL;
$opecer = isset($_REQUEST['opera']) ? $_REQUEST['opera'] : NULL;
$pdf = isset($_GET['pdf']) ? $_GET['pdf'] : NULL;
$idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : NULL;

$fechos = isset($_REQUEST['fechos']) ? $_REQUEST['fechos'] : NULL;
$fhlle = isset($_REQUEST['fhlle']) ? $_REQUEST['fhlle'] : NULL;
$ndocusu = isset($_REQUEST['ndocusu']) ? $_REQUEST['ndocusu'] : NULL;
$datAll = NULL;
$dat = NULL;
//mostrar todos los datos

// $dcen = $mmin->getCen();
if ($fechos) {
    $idusu = $mmin->getIdusu();
    $dat = $mmin->getTodo($fechos, $fhlle);
}
if ($ndocusu) {
    $dat = $mmin->setIdusu($ndocusu);
}
// ------------------

date_default_timezone_set('America/Bogota');
$mes = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$fecha = date('d') . " de " . $mes[date('m') - 1] . " de " . date('Y');
$fecha2 = date('YmdHis');
$ancho = 750;
$año = date('Y');
$fcan = date('Y') + 1;

function urlimg($url)
{
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
    return $imagenBase64;
}

$html = '';
$html .= '<body>';
$html .= '<table style="width:' . $ancho . 'px;">';
$html .= '<tr>';
$html .= '<td style="text-align: center;">';
$html .= '<img src="' . urlimg('../image/sena.png') . '" width="80px">';
$html .= '<br><br>';
$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '<table width="' . $ancho . 'px" cellpadding="5px" cellspacing="0">';
$html .= '<tr>';
$html .= '<td colspan="3" style=" text-align: center; font-size:14px">';



$html .= '<table border="3" style="border-collapse: collapse; border-color: #000;width:' . $ancho . 'px;margin: 20px 0px;">';
$html .= '<thead>';

$html .= '<tr>';
$html .= '<th>Usuario</th>';
$html .= '<th>Perfil</th>';
$html .= '<th>Entrada</th>';
$html .= '<th>Salida</th>';
$html .= '<th>Tiempo</th>';
$html .= '<th>Estado</th>';
$html .= '<th>Elementos</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

if ($dat) {
    foreach ($dat as $d) {

        $html .= '<tr>';
        $html .= '<td style="font-size:14px">';
        $html .= $d['nomusu'];
        $html .= ' ';
        $html .= $d['ndocusu'];
        $html .= '<br>';
        $html .= '<br><small>';
        $html .= '</small>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= $d['nomper'];
        $html .= '</td>';
        $html .= '<td>';
        $html .= $d['fechos'];
        $html .= '</td>';
        $html .= '<td>';
        $html .= $d['fhlle'];
        $html .= '</td>';
        $html .= '<td>';
        $html .= $d['tiempo'];
        $html .= '</td>';
        $html .= '<td>';

        if ($d['tipmin'] == "I") {
            $html .= 'Entro';
        } else {
            $html .=  'Salio';
        }

        $html .= '</td>';
        $html .= '<td>';

        $eles = explode(";", $d['ideles']);
        if ($eles) {
            foreach ($eles as $ele) {
                $dtela = $mmin->selOneEle($ele);
                if ($dtela) {
                    $html .= "</br>" . $dtela[0]['nomele'] . "<small>" . $dtela[0]['nidele'] . " (" . $dtela[0]['nomval'] . ")</small><br>";
                }
            }
        }

        $html .= '</td>';
        $html .= '</tr>';
    }
}

$html .= '</tbody>';
$html .= '</table>';

if ($pdf == "ok") {
    $dompdf = new Dompdf();
    $paper_size = array(0, 0, 612, 792);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper_size);
    $dompdf->render();
    $dompdf->stream("Minuta.pdf");
    exit(); // Agregamos esta línea para salir del script después de generar el PDF
} else {
    // Resto del código para mostrar en la pantalla o imprimir
    echo $html;
    echo "<script>window.print();</script>";
}
