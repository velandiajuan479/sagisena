<?php
require_once("../models/mrcv.php");
require_once("../models/conexion.php");

$idfic = isset($_GET['idfic']) ? $_GET['idfic'] : null;

// obtener datos
$mrcv = new Mrcv();
$fichaActual = [];
$candidatos = [];
$totalVotos = 0;

if($idfic) {
    $fichas = $mrcv->getFichas();
    $fichaActual = current(array_filter($fichas, function($f) use ($idfic) {
        return $f['idfic'] == $idfic;
    }));
    
    $candidatos = $mrcv->getVocerosElectosPorFicha($idfic);
    
    foreach($candidatos as $c) {
        $totalVotos += $c['total_votos'];
    }
}

// configuracion de fecha
date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$ancho = 750;
$año = date('Y');
$fcan = date('Y')+1;

// Funcion para convertir imagenes a base64
function imagenes($imgn){
    if(file_exists($imgn)) {
        $imgnBase64 = "data:image/png;base64," . base64_encode(file_get_contents($imgn));
        return $imgnBase64;
    }
    return "data:image/png;base64," . base64_encode(file_get_contents('../image/usuario.png'));
}

function urlimg($url) {
    if(file_exists($url)) {
        $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
        return $imagenBase64;
    }
    return "data:image/png;base64," . base64_encode(file_get_contents('../image/sena.png'));
}

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        @media print {
            body { 
                font-family: Arial; 
                margin: 0; 
                padding: 20px; 
                background-color: white;
            }
            table { 
                width: 80%; 
                border-collapse: collapse; 
            }
            th, td { 
                border: 1px solid #000; 
                padding: 8px; 
                text-align: left; 
            }
            th { 
                background-color: #d1cfcf; 
            }
            .no-print { 
                display: none; 
            }
            .fixed-width {
                width: 750px;
                margin: 0 auto;
            }
            .foto-candidato {
                width: 100px;
                height: 100px;
                display: block;
                margin: 0 auto;
            }
            .text-center {
                text-align: center;
            }
            .bg-gray {
                background-color: #f3eeee;
            }
        }
    </style>
</head>
<body>';

$html .= '<table class="fixed-width header-table">
    <tr>
        <td class="text-center">
            <img src="' . urlimg('../image/sena.png') . '" width="80px">
            <br><br>
        </td>
    </tr>
</table>';

$html .= '<table class="fixed-width main-table">
    <tr>
        <td style="text-align: center;border: 1px solid #000;" colspan="3">
            <strong>ACTA No. 001</strong>
        </td>
    </tr>
    <tr>
        <th style="text-align: center;border: 1px solid #000;" colspan="3">
            ACTA DE VOTACIÓN DE VOCEROS SENA
        </th>
    </tr>
    <tr>
        <td style="border: 1px solid #000;">
            <strong>CIUDAD y FECHA</strong><br>
            Chía, '.$fecha.'
        </td>
        <td style="border: 1px solid #000;">
            <strong>HORA INICIO</strong><br>
            10:00 Hrs
        </td>
        <td style="border: 1px solid #000;">
            <strong>HORA FIN</strong><br>
            12:00 Hrs
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;">
            <strong>LUGAR Y/O ENLACE</strong><br>
            Bienestar
        </td>
        <td colspan="2" style="border: 1px solid #000;">
            <strong>DIRECCIÓN GENERAL / Regional / Centro</strong><br>
            Regional Cundinamarca, Centro
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid #000;">
            <strong>TEMA</strong><br>
            <strong>1.</strong>Acta de votaciones Sena.
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;" colspan="3">
            <strong>OBJETIVOS</strong><br>
            Conocer los resultados de las votaciones de vocero sena año '.$año.'.
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;" colspan="3">
            <strong>DESARROLLO</strong><br>
            Se dan a conocer los resultados de votaciones para vocero sena del año '.$año.' en el cual se observan la cantidad de votos de cada candidato que se presento y se conoce el ganador el cual asumira el puesto de vocero de la ficha  '.$idfic.'.<br><br>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border: 1px solid #000; text-align: center;">
            <strong>RESULTADOS VOTACIONES FICHA '.strtoupper($fichaActual['idfic']).' - '.strtoupper($fichaActual['nomfic']).'</strong>
        </td>
    </tr>
</table>';

// resultados
$html .= '<table class="fixed-width results-table">
    <tr>
        <th style="background-color: #d1cfcf; border: 1px solid #000;" width="30%">CANDIDATOS</th>
        <th style="background-color: #d1cfcf; border: 1px solid #000;" width="20%">N. VOTOS</th>
        <th style="background-color: #d1cfcf; border: 1px solid #000;" width="20%">PORCENTAJE DE VOTACIÓN</th>
    </tr>';

foreach($candidatos as $candidato) {
    $porcentaje = ($totalVotos > 0) ? floor(($candidato['total_votos'] / $totalVotos * 100)) : 0;
    $foto = (!empty($candidato['fotcan']) && file_exists($candidato['fotcan'])) ? $candidato['fotcan'] : '../image/usuario.png';
    
    $html .= '<tr>
        <td style="background-color: #f3eeee; border: 1px solid #000; text-align: center;">
            <br>'.$candidato['nomusu'].'<br><br>
            <img src="'.imagenes($foto).'" class="foto-candidato"><br>
        </td>
        <td style="background-color: #f3eeee; border: 1px solid #000; text-align: center;">
            '.$candidato['total_votos'].'
        </td>
        <td style="background-color: #f3eeee; border: 1px solid #000; text-align: center;">
            '.$porcentaje.'%
        </td>
    </tr>';
}

$html .= '</table>';

//ganador
if(count($candidatos) > 0) {
    usort($candidatos, function($a, $b) {
        return $b['total_votos'] - $a['total_votos'];
    });
    
    $ganador = $candidatos[0];
    $porcentajeGanador = ($totalVotos > 0) ? floor(($ganador['total_votos'] / $totalVotos * 100)) : 0;
    
    $html .= '<table class="fixed-width conclusions-table">
        <tr>
            <td style="border: 1px solid #000; text-align: center;" colspan="3">
                <strong>CONCLUSIONES</strong>
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; text-align: center;" colspan="3">
                <div style="margin-bottom: 10px;"><strong>GANADOR:</strong> '.$ganador['nomusu'].'</div>
                <div style="margin-bottom: 10px;"><strong>VOTOS OBTENIDOS:</strong> '.$ganador['total_votos'].'</div>
                <div style="margin-bottom: 10px;"><strong>PORCENTAJE DE VOTACIÓN:</strong> '.$porcentajeGanador.'%</div>
                <div style="margin-top: 15px;"><strong>TOTAL DE VOTOS EN LA FICHA:</strong> '.$totalVotos.'</div>
            </td>
        </tr>
    </table>';
}

$html .= '
<div class="no-print" style="margin-top: 20px; text-align: center;">
    <button onclick="window.print()">Imprimir PDF</button>
    <button onclick="window.close()">Cerrar</button>
</div>

<script>
    window.print();
</script>
</body>
</html>';

echo $html;
?>