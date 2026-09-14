<?php
 include '../models/conexion.php';
include '../models/mfpro.php';
ini_set('memory_limit', '512M');
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;

$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
$idusu =isset($_GET['idusu']) ? $_GET['idusu']:NULL;

date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$fecha2 = date('YmdHis');
$mfpro = new mfpro();
$mfpro->setIdusu($idusu);
$mfpro->setIddom(3);
$dus = $mfpro->getUsu();
$dcon = $mfpro->getVal(2);
$dvpr = $mfpro->getVal(3);
$dman = $mfpro->getVal(4);
$datOne = $mfpro->selOne();
$tb = $mfpro->elic("Condiciones",$dcon,$idusu,2);
$th = $mfpro->elic("Manifiesto",$dman,$idusu,4);

function urlimg($url){
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
    return $imagenBase64;
}
$ancho = 750;

$html='';
$html.='<div>';
$html.='<table style="margin: 0 auto;" width="'.$ancho.'px">';
    $html.='<tr>';
        $html.='<td style="text-align: center;">';
            $html.='<img src="'.urlimg('../image/sena.png').'" width="80px">';
            $html.='<br><br>';
        $html.='</td>';
    $html.='</tr>';
$html.='</table>';
$html.='<table style="margin: 0 auto;" width="'.$ancho.'px" cellpadding="5px" cellspacing="0">';
    $html.='<tr>';
        $html.='<td style="text-align: center;" colspan="3">';
            $html.='<strong>FORMATO DE PROPUESTA</strong>';
        $html.='</td>';
    $html.='</tr>';
$html.='<tr>';
    $html.='<td >';
            $html.='<strong>NOMBRE DEL CANDIDATO:</strong> ';
    $html.='</td>';
    $html.='<td colspan="2">';
    $html.=$dus[0]['nomusu'];
    $html.='</td>';
$html.='</tr>';
$html.='<tr>';
    $html.='<td>';
            $html.='<strong>FICHA DEL CANDIDATO:</strong><br>';
            $html.=$dus[0]['nomfic'].$dus[0]['idfic'];
    $html.='</td>';
    $html.='<td>';
            $html.='<strong>CENTRO DE FORMACION:</strong><br>';
            $html.=$dus[0]['nomcen'];
    $html.='</td>';
    $html.='<td>';
			$html.='<strong>JORNADA:</strong><br>';
            $html.= $dus[0]['nomval'];
    $html.='</td>';
$html.='</tr>';
            if($dvpr){
                $n=0;
				foreach($dvpr AS $dv){
					if(!$dv['parval']){
                        $html.='<tr>';
                        $html.='<td colspan="2">';
                            $html.=$dv['nomval'];
                        $html.='</td>';
                        $html.='<td>';
                            if($datOne) $html.=$datOne[$n]['texpro'];
                        $html.='</td>';
                        $html.='</tr>';
                        $n++;
                        }else{
	
                            $html.='<tr>';
                            $html.='<td style="text-align:center;" colspan="3">';
                                $html.='<strong>'.$dv["nomval"].'</strong>  ';
                            $html.='</td>  ';
                            $html.='</tr>';
                            $html.='<tr>';  
                            $html.='<td colspan="3">';
                                $html.='<strong>'.$dv["parval"].'</strong><br>  ';         
                            $nr = explode(';',$dv['parval']);
                            for($o=0;$o<count($nr);$o++){
                
            
                        
                                if($datOne) $html.=$datOne[$n]['texpro'];
                                

                                $n++;
                                }
								$html.='</tr>';
        
                                }
                                }
                                }
                                $html.=$tb;
                                $html.=$th;
                                $html.='</table>';
                                $html.='<br> <br> <br>';
                                $html.='</div>';
                                $html.='</body>';

if($pdf=="ok"){
	$dompdf = new Dompdf();
	$paper_size = array(0,0, 612,792);
	$dompdf->loadHtml($html);
	$dompdf->setPaper($paper_size);
	$dompdf->render();
	$dompdf->stream("Certi_".$fecha2.".pdf");
}else{
	echo $html;
	echo "<script>window.print();</script>";
}
?>