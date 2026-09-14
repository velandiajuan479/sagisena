<?php

require_once('models/mvpr.php');

$mvpr = new Mvpr();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:$_SESSION['idusu'];
$npro = isset($_REQUEST['npro']) ? $_REQUEST['npro']:NULL;
$texpro = isset($_POST['texpro']) ? $_POST['texpro']:NULL;
$idval = isset($_POST['idval']) ? $_POST['idval']:NULL;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$datOne = NULL;

$dcon = $mvpr->getVal(2);
$dvpr = $mvpr->getVal(3);
$dman = $mvpr->getVal(4);

$pg = 1204;

/*
echo $idusu."-".$opera."<br>";
var_dump($npro);
echo "<br>";
var_dump($texpro);
echo "<br>";
var_dump($idval);
*/
$mvpr->setIdusu($idusu);

//Insertar
if($opera=="Insertar"){
	$mvpr->setIdusu($idusu);
	$mvpr->setTexpro($texpro);
	$mvpr->setIdval($idval);
	if(!$idusu) $mvpr->ins();	else $mvpr->upd();
}
//Actualizar
if($opera=="Actualizar" && $idusu) $datOne = $mvpr->selOne();
//Eliminar
if($opera=="Eliminar" && $idusu)$mvpr->del();
//mostrar todos los datos
if($idusu){
	$datOne = $mvpr->selOne(3);
	$dus = $mvpr->getUsu();
}


function dbche($tit,$vec,$idusu,$iddom){
	$mvpr = new Mvpr();
	$datOne = $mvpr->selOne($iddom);
	$html = '<div class="form-group col-md-12">';
		$html .= '<br>';
		$html .= '<h3>'.$tit.'</h3>';
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
					if($datOne AND $datOne[$i]['texpro']=="No") $html .= "disable selected ";
					$html .= '>No</option>';
					$html .= '<option';
					if($datOne AND $datOne[$i]['texpro']=="Si") $html .= "disable selected ";
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

?>