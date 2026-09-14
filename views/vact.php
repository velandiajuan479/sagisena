<?php
//include '../model/mcer.php';
ini_set('memory_limit', '512M');
require_once("models/mpdfact.php");
require_once('dompdf/autoload.inc.php');
// require_once "vro.php";
use Dompdf\Dompdf;

// --------CONTROLADOR----------

$mrvo = new Mdpfact();
$datOne = NULL;

//mostrar todos los datos
$dat = $mrvo->selAll();
$djor = $mrvo->getJor();
// $dcen = $mrvo->getCen();

// ------------------


$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
$idusu = isset($_GET['idusu']) ? $_GET['idusu']:NULL;

date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$fecha2 = date('YmdHis');
$ancho = 750;
$año = date('Y');
$fcan = date('Y')+1;
$html = null;

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
						$html .= '<img src="image/sena.png" width="80px">';
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
						$html .= '<strong>1.&nbsp</strong>Acta de votaciones Sena.';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="border: 1px solid #000;"colspan="3">';
						$html .= '<strong>OBJETIVOS</strong><br>';
						$html .= '&nbsp&nbsp&nbsp-&nbsp&nbsp&nbspConocer los resultados de las votaciones para representante de aprendices Sena año '.$año.'.';
					$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td style="border: 1px solid #000;"colspan="3" >';
						$html .= '<strong>DESARROLLO</strong><br>';
						$html .= 'Se dan a conocer los resultados de votaciones para representante de aprendices sena del año '.$año.' en el cual se observan la cantidad de votos de cada candidato que se presento y se conoce el ganador el cual asumira el puesto de representante en el periodo '.$año.' - '.$fcan.'.<br><br>';
						$html .= '<tr>';
					$html .= '<td colspan="3" style="border: 1px solid #000; text-align: center; ">';
						$html .= '<strong>RESULTADOS</strong>';
					

					if($dat){
						foreach($djor AS $dj){
					$html .= '<table width="'.$ancho.'px" cellpadding="5px" background-color: #d1cfcf cellspacing="0">';
					$html .= '<tr style="width: 150px; border: 1px solid #000;" colspan="2">';
					$html .= '<th style="background-color: #d1cfcf; border: 1px solid #000;" colspan="2"; background-color: #f3eeee;>';
					$html .= 'Candidatos';
					$html .= '</th>';
					$html .= '<th style="background-color: #f3eeee; width: 150px; border: 1px solid #000;" colspan="2";>';
					$html .= 'No. Votos';
					$html .= '<br>';
					$html .= '<br>';
					// foreach ($dat as $d) {
					// 	if($d["jornada"] == $dj["idval"]){
					// 		$nvo = $mrvo->nvoCan($d['idusu']);
					// 		$nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo']:0;
					// 		$html .= '<style="text-align: center; font-size:14px"> ( '.$nvo.'  )  ';
					// 		$html .= $d['nomusu'].'<br><br>';
					// 	}
					// }
					$html .= '</th>';
					$html .= '<th style="background-color: #dbf1d9; width: 150px; border: 1px solid #000;" colspan="2"; background-color: #f3eeee;>';
					$html .= 'Votaciones Jornada '.$dj["nomval"];
					$html .= '</th>';
					$html .= '</tr>';
					$html .= '<tr style="width: 150px; border: 1px solid #000;" colspan="2">';
					$html .= '</tr>';
					$html .= '</table>';
						}
					}
						/*
						if($dat){
							foreach($djor AS $dj){
							$html .= '<BR><table width="'.$ancho.'px" cellpadding="5px" cellspacing="0">';
								$html .= '<tr>';
									$html .= '<th colspan="2" style="background-color: #d1cfcf;">';
										$html .= 'Votaciones Jornada '.$dj["nomval"];
									$html .= '</th>';
								$html .= '</tr>';
								$html .= '<tr>';
									$html .= '<th style="width: 150px;background-color: #f3eeee;">';
										$html .= 'No. Votos';
									$html .= '</th>';
									$html .= '<th style="width: 600px;background-color: #f3eeee;">';
										$html .= 'Candidatos';
									$html .= '</th>';
								$html .= '</tr>';
								foreach ($dat as $d) {
									$html .= '<tr>';
									if($d["jornada"] == $dj["idval"]){
										$nvo = $mrvo->nvoCan($d['idusu']);
										$nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo']:0;
										$html .= '<td style="text-align: center;">'.$nvo.'</td>';
										$html .= '<td>'. $d['nomusu'].'</td>';
									}
									$html .= '</tr>';
								}
							$html .= '</table>';
							}			
							
						}*/
						$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
					$html .= '<td colspan="3" style="border: 1px solid #000; text-align: center;">';
					$html .= '<strong>CONCLUSIONES</strong><br>';
					$html .= '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
					$html .= '</td>';
				$html .= '</tr>';
				
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
	// echo "<script type='text/javascript'>window.print();</script>";
}
?>
