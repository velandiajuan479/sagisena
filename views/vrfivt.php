<?php
require_once '../models/conexion.php';
require_once '../models/mdpc.php';
ini_set('memory_limit', '4096M');
require_once '../vendor/autoload.php';

// Controladores
$mdpc = new Mdpc();
$dat = $mdpc->getLstVt();


//convierte imagen
function imageToDataUri($path) {
    if (!file_exists($path)) return '';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}
$logo = imageToDataUri(__DIR__ . '/../img/logoSena.png');

$html = "";
require_once ('stlrep.php');

$html .= '<body>';
    $html .= '<table>';
        $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<td colspan="12">';
                    $html .= '<img src="'.$logo.'" alt="Logo SENA">';
                $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th rowspan="2" colspan="10">';
                    $html .= 'Proceso de Gestion de Formacion Profesional Integral <br>Formato Control Participacion
                    Actividades de Bienestar Del Aprendiz';
                $html .= '</th>';
                $html .= '<th colspan="2" class="version">Version: 03</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th colspan="2" class="gpf">Codigo: GFPI-F-023</th>';
            $html .= '</tr>';

            $html .= '<tr>';
                $html .= '<th colspan="12" class="pri">CIUDAD Y FECHA:</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th colspan="12" class="pri">REGIONAL Y CENTRO DE FORMACIÓN: Cundinamarca - Centro de Desarrollo
                    Agroempresarial</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th colspan="12" class="pri">OBJETIVO ESTRATEGICO DEL PLAN:</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th colspan="12" class="pri">OBJETIVO OPERATIVO DEL PLAN:</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th colspan="12" class="pri">ACTIVIDAD A REALIZAR:</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th colspan="12" class="pri">PROPOSITO DE LA ACTIVIDAD:</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th class="dato">Nombre y Apellidos del Aprendiz</th>';
                $html .= '<th class="dato">Tipo de Documento</th>';
                $html .= '<th class="dato"># de Documento</th>';
                $html .= '<th class="dato">Genero</th>';
                $html .= '<th class="dato">Numero de Ficha</th>';
                $html .= '<th class="dato">Modalidad de Formación</th>';
                $html .= '<th class="dato">Programa de Formación</th>';
                $html .= '<th class="dato">Jornada de Formación</th>';
                $html .= '<th class="dato">Centro de Formación</th>';
                $html .= '<th class="dato">Correo</th>';
                $html .= '<th class="dato">Telefono</th>';
                $html .= '<th class="dato">Firma Aprendiz</th>';
            $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
            if($dat){ foreach ($dat as $dt) {
                $html .= '<tr>';
                    $html .= '<td>'.strtoupper($dt['nomusu']).'</td>';
                    $html .= '<td>'.$dt['tdu'].'</td>';
                    $html .= '<td>'.$dt['ndocusu'].'</td>';
                    $html .= '<td>'.$dt['gus'].'</td>';
                    $html .= '<td>'.$dt['idfic'].'</td>';
                    $jornada = ($dt['jornada'] == 'Virtual') ? 'Virtual' : 'Presencial';
                    $html .= '<td>'.$jornada.'</td>';
                    $html .= '<td>'.$dt['nomfic'].'</td>';
                    $html .= '<td>'.$dt['jornada'].'</td>';
                    $html .= '<td>'.$dt['nomcen'].'</td>';
                    $html .= '<td>'.strtolower($dt['emausu']).'</td>';
                    $html .= '<td>'.$dt['telcan'].'</td>';
                    $html .= '<td>'.$dt['dtvot'].'</td>';
                $html .= '</tr>';
            }}
        $html .= '</tbody>';
    $html .= '</table>';
$html .= '</body>';
$html .= '</html>';
/*use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte.pdf", ["Attachment" => false]);*/
echo $html;
?>