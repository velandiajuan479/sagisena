<?php
require_once 'models/mfpro.php';

$mfpro = new Mfpro();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:$_SESSION['idusu'];
$npro = isset($_REQUEST['npro']) ? $_REQUEST['npro']:NULL;
$texpro = isset($_POST['texpro']) ? $_POST['texpro']:NULL;
$idval = isset($_POST['idval']) ? $_POST['idval']:NULL;


$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$datOne = NULL;

$pg = 1118;

/*
echo $idusu."-".$opera."<br>";
var_dump($npro);
echo "<br>";
var_dump($texpro);
echo "<br>";
var_dump($idval);
*/

//mostrar todos los datos

if($idusu){
	$mfpro->setIdusu($idusu);
	$mfpro->setIddom(3);
	$datOne = $mfpro->selOne();
	$dus = $mfpro->getUsu();
}
$dcon = $mfpro->getVal(2);
$dvpr = $mfpro->getVal(3);
$dman = $mfpro->getVal(4);

function dbche($tit,$vec){
	$mfpro = new Mfpro();
	$datOne = $mfpro->selOne();
	$html = '<div class="form-group col-md-12">';
		$html .= '<br>';
		$html .= '<h6>'.$tit.'</h6>';
	$html .= '</div>';
	if($vec){
		$i=0;
		foreach($vec AS $dv){
			$html .= '<div class="form-group col-md-10">';
				$html .= ' <span>'.$dv['nomval'].'</span>';
			$html .= '</div>';
			$html .= '<div class="form-group col-md-2">';
				$html .= '<select name="texpro[]" id="texpro" class="form-select">';
					$html .= '<option';
					if($datOne AND $datOne[$i]['texpro']=="No") $html .= " selected ";
					$html .= '>No</option>';
					$html .= '<option';
					if($datOne AND $datOne[$i]['texpro']=="Si") $html .= " selected ";
					$html .= '>Si</option>';
				$html .= '</select>';
				$html .= '<input type="hidden" name="idval[]" value="'.$dv['idval'].'">';
				$html .= '<input type="hidden" name="npro[]" value="';
					if($datOne) $html .= $datOne[$i]['npro'];
				$html .= '">';
			$html .= '</div>';
			$i++;
		}
	} 
	return $html;
}
function tbol($tit,$vec){
	$mfpro = new Mfpro();
	$datOne = $mfpro->selOne();
	$html = '<tr> <td style="text-align: center;border: 1px solid #000;" colspan="3">';
		$html .= ' <strong>'.$tit.'</strong>';
	$html .= '</td></tr> ';
	if($vec){
		$i=0;
		foreach($vec AS $dv){
			$html .= '<tr><td style="border: 1px solid #000;" colspan="2">';
				$html .= $dv['nomval'];
			$html .= '</td>';
			$html .= '<td style="border: 1px solid #000;">';
					if($datOne AND $datOne[$i]['texpro']=="No"){$html .= "No";}
					else if($datOne AND $datOne[$i]['texpro']=="Si"){$html .= "Si";}
			$html .= '</td></tr>';
			$i++;
		}
	} 
	return $html;
}

?>