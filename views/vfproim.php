<?php
///include 'models/mfpro.php';
ini_set('memory_limit', '512M');
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
require_once 'controllers/cfpro.php'; 
$ta=700;

$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;

$html='';
$html.='<div>';
$html.='<table style="margin: 0 auto;" width="'.$ta.'px">';
$html.='<tr>';
$html.='<td style="text-align: center;">';
$html.='<img src="image/sena.png" width="80px">';
$html.='<br><br>';
$html.='</td>';
$html.='</tr>';
$html.='</table>';


$html.='<table style="margin: 0 auto;" width="'.$ta.'px" cellpadding="5px" cellspacing="0">';
$html.='<tr>';
$html.='<td style="text-align: center;border: 1px solid #000;" colspan="3">';
$html.='<strong>FORMATO DE PROPUESTA</strong>';
$html.='</td>';
$html.='</tr>';
$html.='<tr>';
$html.='<td style="border: 1px solid #000;">';
$html.='<strong>NOMBRE DEL CANDIDATO:</strong> ';
$html.='</td>';
$html.='<td style="border: 1px solid #000;" colspan="2">';
$html.=$dus[0]['nomusu'];
$html.='</td>';
$html.='</tr>';
$html.='<tr>';
$html.='<td style="border: 1px solid #000;">';
$html.='<strong>FICHA DEL CANDIDATO:</strong><br>';
$html.=$dus[0]['nomfic'].$dus[0]['idfic'];
$html.='</td>';
$html.='<td style="border: 1px solid #000;">';
$html.='<strong>CENTRO DE FORMACION:</strong><br>';
            $html.=$dus[0]['nomcen'];
            $html.='</td>';
            $html.='<td style="border: 1px solid #000;">';
			$html.='<strong>JORNADA:</strong><br>';
            $html.= $dus[0]['nomval'];
            $html.='</td>';
            $html.='</tr>';
            if($dvpr){
				$n=0;
				foreach($dvpr AS $dv){
					if(!$dv['parval']){
                        
                        $html.='<tr>';
                        $html.='<td style="border: 1px solid #000;" colspan="2">';
                        $html.=$dv['nomval'];
                        $html.='</td>';
                        $html.='<td style="border: 1px solid #000;">';
                        if($datOne) echo $datOne[$n]['texpro'];
                        $html.='</td>';
                        $html.='</tr>';
                        $html.=$n++;
                        }else{
	
                            $html.='<tr>';
                            $html.='<td style="border: 1px solid #000;" colspan="3">';
                            $html.='<strong>'.$dv["nomval"].'</strong>  ';
                            $html.='</td>  ';
                            $html.='</tr>';
                            $html.='<tr>';
            
                            $html.=$nr = explode(";",$dv['parval']);
                            for($o=0;$o<count($nr);$o++){
                
            
                                $html.='<td style="width: 33%;border: 1px solid #000;">';
                                $html.='<strong>'.$nr[$o].'</strong><br>';
                                if($datOne) echo $datOne[$n]['texpro'];
                                $html.='</td>';
            
                                $html.=$n++;
								$html.='}</tr>';
        
                                }
                                }
                                }
                                }
				
                
                                echo tbol("Condiciones",$dcon,$idusu,2);
                                echo tbol("Manifiesto",$dman,$idusu,4);
                                $html.='</table>';
                                $html.='<br> <br> <br>';
                                $html.='</div>';


if($pdf=="ok"){
	$dompdf = new DOMPDF();
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