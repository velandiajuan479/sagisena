<?php

require_once('models/mpro.php');

$mpro = new Mpro();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:$_SESSION['idusu'];
$npro = isset($_REQUEST['npro']) ? $_REQUEST['npro']:NULL;
$texpro = isset($_POST['texpro']) ? $_POST['texpro']:NULL;
$idval = isset($_POST['idval']) ? $_POST['idval']:NULL;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$datOne = NULL;

$dcon = $mpro->getVal(2);
$dvpr = $mpro->getVal(3);
$dman = $mpro->getVal(4);

$pg = 1207;

$mpro->setIdusu($idusu);

//Insertar
// if($opera=="Insertar"){
// 	if($npro AND $idusu AND $texpro AND $idval){
// 		for ($i=0;$i<count($texpro);$i++){
// 			$mpro->setTexpro($texpro[$i]);
// 			$mpro->setIdval($idval[$i]);
// 			$mpro->save();
// 		}
// 	}
// }
//Actualizar
if($opera=="Insertar"){
	$mpro->del();
	if($npro AND $idusu AND $texpro AND $idval){
		for ($i=0;$i<count($texpro);$i++){
			$mpro->setTexpro($texpro[$i]);
			$mpro->setIdval($idval[$i]);
			$mpro->save();
		}
	}
}
//Eliminar
if($opera=="Eliminar" && $npro)$mpro->del();

//mostrar todos los datos
if($idusu){
	$datOne = $mpro->getOne(3);
	$dus = $mpro->getUsu();
}


function dbche($tit,$vec,$idusu,$iddom){
	$mpro = new Mpro();
	$mpro->setIdusu($idusu);
	$datOne = $mpro->getOne($iddom);
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
				$html .= '<select name="texpro[]" id="texpro" class="form-select"';
				if($datOne AND $datOne[$i]['texpro']=="No") $html .= ' value="No"';
				elseif($datOne AND $datOne[$i]['texpro']=="Si") $html .= ' value="Si"';
				$html .=' >';
					$html .= '<option value="No"';
					if($datOne AND $datOne[$i]['texpro']=="No") $html .= ' selected';
					$html .= '>No</option>';
					$html .= '<option value="Si"';
					if($datOne AND $datOne[$i]['texpro']=="Si") $html .= ' selected';
					$html .= '>Si</option>';
				$html .= '</select>';
				$html .= '<input type="hidden" name="idval[]" value="'.$dv['idval'].'">';
				$html .= '<input type="hidden" name="npro[]" value="';
					if($datOne) 
				$html .= $datOne[$i]['npro'];
				$html .= '">';
			$html .= '</div>';
			$i++;
		}
	} 
	return $html;
}

?>