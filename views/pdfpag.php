<?php
require_once ("../models/seguridad.php");
include ('../models/conexion.php');
include ('../models/mpag.php');

ini_set('memory_limit', '4096M');
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;

$mpag = new Mpag();

$datPdf = $mpag->getPdfPag();

$anctbl = 750;
$html = '';
$logo = "..\img/favicon.png";
$imagenBase64 = "data:image/png;base64,".base64_encode(file_get_contents($logo));
$html .= '<table style="width:'.$anctbl.'px;">';
    $html .= '<tr>';
        $html .= '<th style="text-align: center; ">';
            $html .= '<img src="'.$imagenBase64.'" style="width: 150px;"><br>';
            $html .= 'Cl. 25 #11-135, Chía, Cundinamarca<br>';
            $html .= 'Tel. 18844545<br>';
        $html .= '</th>';
    $html .= '</tr>';
$html .= '</table><br>';

    
    $html .= '<table style="width:'.$anctbl.'px;border-top: 1px solid #117f09;border-bottom: 1px solid #117f09;margin: 0px;">';
        $html .= '<tr>';
            $html .= '<th>ID.pag</th>';
            $html .= '<th>Nom.pag</th>';
            $html .= '<th>Activos</th>';
            $html .= '<th style="text-align: right;">modulo</th>'; 
        $html .= '</tr>';
        if ($datPdf) {
            foreach ($datPdf as $pd) {  
        $html .= '<tr>';
            $html .= '<td style="text-align: center;" >';
                $html .= $pd['idpag'];
            $html .= '</td>';
            $html .= '<td style="text-align: center;">';
                $html .= $pd['nompag'];
            $html .= '</td>';
            $html .= '<td style="text-align: center;">';
                if($pd['mospas']==1){
                    $html .= 'Si';
                }else{
                    $html .= 'No';
                }
            $html .= '</td>';
                $html .= '<td style="text-align: right;">';
                $html .= $pd['nommod'];
            $html .= '</td>';
        $html .= '</tr>';
        } }
    $html .= '</table>';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="text-align: center;">¡Muchas gracias por preferirnos.!';
    $html .= '';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</table>';

if($pdf==123456){
    $dompdf = new Dompdf();
    $paper_size = array(0,0,612,792);
    
    $dompdf->loadHtml($html); 
    $dompdf->setPaper($paper_size);
    //$dompdf->setPaper('Letter', 'landscape');
    $dompdf->render(); 
    $dompdf->stream("Factura Valor.pdf");
}else{
    echo $html; 
    echo "<script type='text/javascript'>window.print();</script>";
}
?>