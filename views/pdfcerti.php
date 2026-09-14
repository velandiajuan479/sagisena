<?php

ini_set('memory_limit', '512M');
require_once("../models/mbit.php");
require_once("../models/conexion.php");
require_once('../vendor/autoload.php');

use Dompdf\Dompdf;

// Obtener parámetros
$pdf = isset($_GET['pdf']) ? $_GET['pdf'] : NULL;
$idusu = isset($_GET['idusu']) ? $_GET['idusu'] : NULL;

// Instanciar modelo
$mbit = new Mbit();

// Obtener datos del usuario y bitácoras
$datUsuario = $mbit->getOneUsu($idusu);
$datPrograma = $mbit->getNombrePrograma($idusu);
$datBitacoras = $mbit->getBitacorasByUsuario($idusu);
$datActividades = $mbit->getActividadesByUsuario($idusu);

// Funciones para manejo de imágenes
function imagenes($imgn) {
    if (file_exists($imgn)) {
        $imgnBase64 = "data:image/png;base64," . base64_encode(file_get_contents($imgn));
        return $imgnBase64;
    }
    return null;
}

function urlimg($url) {
    if (file_exists($url)) {
        $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
        return $imagenBase64;
    }
    return null;
}

// Configuración de fecha
date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$fecha2 = date('YmdHis');
$ancho = 750;

// Calcular tiempo total de prácticas
$tiempoTotalPracticas = 0;
$fechaInicioPracticas = null;
$fechaFinPracticas = null;
$empresaActual = '';
$nitEmpresa = '';

if (!empty($datBitacoras)) {
    // Ordenar bitácoras por fecha de inicio
    usort($datBitacoras, function($a, $b) {
        return strtotime($a['fecha_inicio']) - strtotime($b['fecha_inicio']);
    });
    
    $fechaInicioPracticas = $datBitacoras[0]['fecha_inicio'];
    $fechaFinPracticas = end($datBitacoras)['fecha_fin'];
    $empresaActual = $datBitacoras[0]['nombre_empresa'];
    $nitEmpresa = $datBitacoras[0]['nit'];
    
    // Calcular días totales
    $inicio = new DateTime($fechaInicioPracticas);
    $fin = new DateTime($fechaFinPracticas);
    $tiempoTotalPracticas = $inicio->diff($fin)->days;
}

// Generar HTML del certificado
$html = '';
$html .= '<html>';
$html .= '<head>';
$html .= '<style>
    body { font-family: Arial, sans-serif;  }
</style>';
$html .= '</head>';
    $html .= '<body>';
        $html .= '<table width="'.$ancho.'px">';
            $html .= '<tr>';
                $html .= '<td style="text-align: center;">';
                    $html .= '<img src="' . urlimg('../image/sena.png') . '" width="80px">';
                    $html .= '<br><br>';
                $html .= '</td>';
            $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="'.$ancho.'px" cellpadding="5px" cellspacing="0">';
            $html .= '<tr>';
                $html .= '<td style="text-align: center;" colspan="3">';
                    $html .= '<strong>REGIONAL CUNDINAMARCA</strong><br><br>';
                    $html .= '<strong>EL CENTRO DE DESARROLLO AGROEMPRESARIAL</strong><br><br>';
                    $html .= '<strong>HACE CONSTAR</strong>';
                $html .= '</td>';
            $html .= '</tr>';
            
            $html .= '<tr>';
                $html .= '<td colspan="3" style="text-align: justify; padding: 20px;">';
                    $html .= 'Que <strong>' . strtoupper($datUsuario[0]['nomusu']) . '</strong> identificada(o) con Cédula de Ciudadanía No. <strong>' . $datUsuario[0]['ndocusu'] . '</strong> ';
                    $html .= 'se encuentra cursando el programa de <strong>' . strtoupper($datPrograma[0]['nomfic']) . '</strong>, ';
                    $html .= 'el cual inició el <strong>' . date('d', strtotime($fechaInicioPracticas)) . ' de ' . $mes[date('m', strtotime($fechaInicioPracticas))-1] . ' de ' . date('Y', strtotime($fechaInicioPracticas)) . '</strong> ';
                    
                    if ($fechaFinPracticas) {
                        $html .= 'y finalizará el <strong>' . date('d', strtotime($fechaFinPracticas)) . ' de ' . $mes[date('m', strtotime($fechaFinPracticas))-1] . ' de ' . date('Y', strtotime($fechaFinPracticas)) . '</strong>, ';
                    }
                    
                    $html .= 'en modalidad de Etapa Productiva en <strong>' . strtoupper($empresaActual) .'</strong>.';
                    $html .= '<br><br>';
                    $html .= 'Durante este período ha completado <strong>' . $tiempoTotalPracticas . ' días</strong> de prácticas profesionales.';
                $html .= '</td>';
            $html .= '</tr>';
        $html .= '</table>';
        
        // Tabla de actividades realizadas
        if (!empty($datActividades)) {
            $html .= '<br>';
            $html .= '<table width="'.$ancho.'px" cellpadding="5px" cellspacing="0" style="border-collapse: collapse;">';
                $html .= '<tr>';
                    $html .= '<td colspan="3" style="text-align: center; border: 1px solid #000; background-color: #d1cfcf;">';
                        $html .= '<strong>ACTIVIDADES REALIZADAS DURANTE LA ETAPA PRODUCTIVA</strong>';
                    $html .= '</td>';
                $html .= '</tr>';
                
                $html .= '<tr>';
                    $html .= '<th style="border: 1px solid #000; background-color: #d1cfcf; text-align: center; width: 60%;">DESCRIPCIÓN DE ACTIVIDAD</th>';
                    $html .= '<th style="border: 1px solid #000; background-color: #d1cfcf; text-align: center; width: 20%;">FECHA INICIO</th>';
                    $html .= '<th style="border: 1px solid #000; background-color: #d1cfcf; text-align: center; width: 20%;">FECHA FIN</th>';
                $html .= '</tr>';
                
                foreach ($datActividades as $actividad) {
                    $html .= '<tr>';
                        $html .= '<td style="border: 1px solid #000; background-color: #f3eeee; padding: 8px;">';
                            $html .= $actividad['descripcion_actividad'];
                        $html .= '</td>';
                        $html .= '<td style="border: 1px solid #000; background-color: #f3eeee; text-align: center; padding: 8px;">';
                            $html .= date('d/m/Y', strtotime($actividad['fecha_inicio']));
                        $html .= '</td>';
                        $html .= '<td style="border: 1px solid #000; background-color: #f3eeee; text-align: center; padding: 8px;">';
                            $html .= date('d/m/Y', strtotime($actividad['fecha_fin']));
                        $html .= '</td>';
                    $html .= '</tr>';
                }
            $html .= '</table>';
        }
        
        // Información final
        $html .= '<br><br>';
        $html .= '<table width="'.$ancho.'px" cellpadding="5px" cellspacing="0">';
            $html .= '<tr>';
                $html .= '<td style="text-align: justify;">';
                    $html .= 'Se expide en MOSQUERA a los ' . date('d') . ' días del mes de ' . $mes[date('m')-1] . ' de ' . date('Y') . '.';
                $html .= '</td>';
            $html .= '</tr>';
            
            $html .= '<tr>';
                $html .= '<td style="text-align: center; padding-top: 80px;">';
                    $html .= '<br><br>';
                    $html .= '____________________________________<br>';
                    $html .= '<strong>NELSON OCTAVIO GOMEZ BOTERO</strong><br>';
                    $html .= 'SUBDIRECTOR (A)<br>';
                    $html .= '<strong>CENTRO DE DESARROLLO AGROEMPRESARIAL</strong><br>';
                    $html .= 'Ministerio de la Protección Social<br>';
                    $html .= 'SERVICIO NACIONAL DE APRENDIZAJE<br>';
                    $html .= 'NIT 899999034-1 / Ley 119 de 1994';
                $html .= '</td>';
            $html .= '</tr>';
            
            $html .= '<tr>';
                $html .= '<td style="text-align: center; padding-top: 20px; border-top: 1px solid #000; margin-top: 20px;">';
                    $html .= 'CL. 25 #11-135 &nbsp;&nbsp;&nbsp; CHÍA COLOMBIA<br>';
                    $html .= '<strong>' . strtoupper($datUsuario[0]['nomusu']) . '</strong><br>';
                    $html .= '<strong>' . strtoupper($datPrograma[0]['nomfic']) . '</strong><br>';
                    $html .= 'Página 1 de 1';
                $html .= '</td>';
            $html .= '</tr>';
        $html .= '</table>';
    $html .= '</body>';
$html .= '</html>';


// Generar PDF o mostrar HTML
if ($pdf == "ok") {
    $dompdf = new Dompdf();
    $paper_size = array(0, 0, 612, 792);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper_size);
    $dompdf->render();
    $dompdf->stream("Certificado_Practicas_" . $datUsuario[0]['nomusu'] . "_" . $fecha2 . ".pdf");
} else {
    echo $html;
    echo "<script type='text/javascript'>window.print();</script>";
}
?>