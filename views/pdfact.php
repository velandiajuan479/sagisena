<?php
//include '../model/mcer.php';
ini_set('memory_limit', '512M');
require_once("../models/mrvo.php");

require_once("../models/conexion.php");
require_once('../vendor/autoload.php');
// require_once "vro.php";
use Dompdf\Dompdf;

// --------CONTROLADOR----------

$mrvo = new Mrvo();
$datOne = NULL;

$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
/* $idusu = isset($_GET['idusu']) ? $_GET['idusu']:NULL;
 */
$fidcen = isset($_GET['fidcen']) ? $_GET['fidcen']:NULL;
$fidjor = isset($_GET['fidjor']) ? $_GET['fidjor']:NULL;

//mostrar todos los datos
$mrvo -> setFidcen($fidcen);
$mrvo -> setFidjor($fidjor);
$dat = $mrvo->selAll();
$djor = $mrvo->getJor();

function imagenes($imgn){
    $imgnBase64 = "data:image/png;base64," . base64_encode(file_get_contents($imgn));
    return $imgnBase64;
}
function urlimg($url)
{
	$imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
	return $imagenBase64;
}
// $dcen = $mrvo->getCen();

// ------------------



date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$fecha2 = date('YmdHis');
$ancho = 750;
$año = date('Y');
$fcan = date('Y')+1;
$html = null;
$totm = $mrvo->canPxF($fidjor);

// variables para identificar al ganador y calcular el total de los votos
$ganador = null;
$maxVotos = 0;
$totalVotosJornada = 0;

foreach ($dat as $d) {
    $nvo = $mrvo->nvoCan($d['idusu']);
    $nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo'] : 0;
    $totalVotosJornada += $nvo;
    
    if ($nvo > $maxVotos) {
        $maxVotos = $nvo;
        $ganador = $d;
    }
}



// if($dat){
// 	foreach($djor AS $dj){
// 		echo $dj["nomval"]."<br>";
// 		foreach ($dat as $d) {
// 			if($d["jornada"] == $dj["idval"]){
// 				$nvo = $mrvo->nvoCan($d['idusu']);
// 				$nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo']:0;
// 				echo $nvo."-".$d['nomusu']." - ".$d["nomcen"]."<br>";
// 			}	
// 		}
// 	}
// }

		$html .= '';
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
					$html .= '<td style="text-align: center;border: 1px solid #000;" colspan="3">';
						$html .= '<strong>ACTA No. 001</strong>';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<th style="text-align: center;border: 1px solid #000;" colspan="3">';
						$html .= 'ACTA DE VOTACIÓN DE REPRESENTANTES DE APRENDICES SENA';
					$html .= '</th>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="border: 1px solid #000;">';
						$html .= '<strong>CIUDAD y FECHA</strong><br>';
						$html .= 'Chía, '.$fecha;
					$html .= '</td>';
					$html .= '<td style="border: 1px solid #000;">';
						$html .= '<strong>HORA INICIO</strong><br>';
						$html .= '10:00 Hrs ';
					$html .= '</td>';
					$html .= '<td style="border: 1px solid #000;">';
						$html .= '<strong>HORA FIN</strong><br>';
						$html .= '12:00 Hrs';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="border: 1px solid #000;">';
						$html .= '<strong>LUGAR Y/O ENLACE</strong><br>';
						$html .= 'Bienestar';
					$html .= '</td>';
					$html .= '<td colspan="2" style="border: 1px solid #000;">';
						$html .= '<strong>DIRECCIÓN GENERAL / Regional / Centro</strong><br>';
						$html .= 'Regional Cundinamarca, Centro';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td colspan="3" style="border: 1px solid #000;">';
						$html .= '<strong>TEMA</strong><br>';
						$html .= '<strong>1.</strong>Acta de votaciones Sena.';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="border: 1px solid #000;"colspan="3">';
						$html .= '<strong>OBJETIVOS</strong><br>';
						$html .= 'Conocer los resultados de las votaciones para representante de aprendices Sena año '.$año.'.';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="border: 1px solid #000;"colspan="3" >';
						$html .= '<strong>DESARROLLO</strong><br>';
						$html .= 'Se dan a conocer los resultados de votaciones para representante de aprendices sena del año '.$año.' en el cual se observan la cantidad de votos de cada candidato que se presento y se conoce el ganador el cual asumira el puesto de representante en el periodo '.$año.' - '.$fcan.'.<br><br>';
						$html .= '<tr>';
					$html .= '<td colspan="3" style="border: 1px solid #000; text-align: center; ">';
					
					foreach($djor AS $dj){
						if($dj['idval'] == $fidjor){
						$html .= '<strong>RESULTADOS VOTACIONES JORNADA '.strtoupper($dj['nomval']).'</strong>';
						}
					}
					

					$html .= '<table width="'.$ancho.'px" cellpadding="5px" background-color="#d1cfcf" cellspacing="0">';
					$html .= '<tr style="width: 150px; border: 1px solid #000;">';
					$html .= '<th style="background-color: #d1cfcf; border: 1px solid #000;" background-color: #f3eeee; width="30%"> CANDIDATOS </th>';
					$html .= '<th style="background-color: #d1cfcf; border: 1px solid #000;" background-color: #f3eeee; width="20%"> N. Votos </th>';
					$html .= '<th style="background-color: #d1cfcf; border: 1px solid #000;" background-color: #f3eeee; width="20%"> Porcentaje de votacion </th>';
					$html .= '</tr>';
		
					if ($dat) {
						foreach ($dat as $d) {
							if ($d['fotcan']) {
								$foto = "../".$d['fotcan'];
							} else {
								$foto="../image/usuario.png";
							}
							$html .= '<tr style="width: 150px; border: 1px solid #000;">';
							$html .= '<td style="background-color: #f3eeee; width: 150px; border: 1px solid #000; text-align: center;">';
							$html .= '<br>';
							$nvo = $mrvo->nvoCan($d['idusu']);
							$nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo'] : 0;
							$html .= $d['nomusu'].'<br><br>';
							$html .='<img src="'.imagenes($foto).'" style="width: 150px;"><br>';
							$html .= '</td>';
							$html .= '<td style="background-color: #f3eeee; width: 150px; border: 1px solid #000; text-align: center;">';
							$html .= $nvo;
							$html .= '</td>';
							$html .= '<td style="background-color: #f3eeee; width: 150px; border: 1px solid #000; text-align: center;">';
							$porcentajeVotos = $totalVotosJornada != 0 ? floor(($nvo * 100) / $totalVotosJornada) : 0;
							$html .= $porcentajeVotos . '%';
							$html .= '</td>';
							$html .= '</tr>';
						}
					}
		
					
				
					$html .= '<tr>';
					$html .= '<td colspan="3" style="border: 1px solid #000; text-align: center;">';
					$html .= '<strong>CONCLUSIONES</strong><br>';
					$html .= '</td>';
					$html .= '</tr>';
		
				// seccion para mostrar el ganador de las votaciones
				if ($ganador) {
					// se pone floor() para obtener numeros enteras
					$porcentajeGanador = $totalVotosJornada != 0 ? floor(($maxVotos * 100) / $totalVotosJornada) : 0;
					
					$html .= '<tr>';
						$html .= '<td colspan="3" style="border: 1px solid #000; text-align: center;">';
						$html .= '<div style="margin-bottom: 10px;"><strong>GANADOR:</strong> '.$ganador['nomusu'].'</div>';
						$html .= '<div style="margin-bottom: 10px;"><strong>VOTOS OBTENIDOS:</strong> '.$maxVotos.'</div>';
						$html .= '<div style="margin-bottom: 10px;"><strong>PORCENTAJE DE VOTACIÓN:</strong> '.$porcentajeGanador.'%</div>';
						$html .= '<div style="margin-top: 15px;"><strong>TOTAL DE VOTOS EN LA JORNADA:</strong> '.$totalVotosJornada.'</div>';
												
							
						$html .= '</td>';
					$html .= '</tr>';
				}
				
			$html .= '</table>';
		$html .= '</body>';
		
		if($pdf=="ok"){
			$dompdf = new Dompdf();
			$paper_size = array(0,0, 612,792);
			$dompdf->loadHtml($html);
			$dompdf->setPaper($paper_size);
			$dompdf->render();
			$dompdf->stream("Acta_".$fecha2.".pdf");
		}else{
			echo $html;
			echo "<script type='text/javascript'>window.print();</script>";
		}
		?>