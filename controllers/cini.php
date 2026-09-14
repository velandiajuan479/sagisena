<?php

require_once 'models/mcen.php';
date_default_timezone_set('America/Bogota');
$mcen = new mcen();
$dtfe = $mcen->selOne(951310);
//var_dump($dtfe);

function erroraute(){
	$error = isset($_GET['error']) ? $_GET['error']:NULL;
	if($error=="ok"){
		$txt = '<div class="alert alert-warning cen" style="padding-top: 10px;" role="alert">';
			$txt .= 'Datos incorrectos.';
		$txt .= '</div>';
		echo $txt;
	}elseif($error=="ok2"){
		$txt = '<div class="alert alert-warning cen" style="padding-top: 10px;" role="alert">';
			$txt .= 'Usted no tiene permisos para ingresar por fechas de votaciones';
		$txt .= '</div>';
		echo $txt;
	}
	
}



// Calculo de fechas
if (strtotime($dtfe[0]["fivotcen"])> time()){
	list($fe, $hor) = explode(' ', $dtfe[0]["fivotcen"]);
	list($anio, $mes, $dia) = explode("-",$fe); 
	list($hh, $mm, $ss) = explode(":",$hor); 
}else if (strtotime($dtfe[0]["fivotcen"])<= time() AND strtotime($dtfe[0]["ffvotcen"])>time()) {
	list($fe, $hor) = explode(' ', $dtfe[0]["ffvotcen"]);
	list($anio, $mes, $dia) = explode("-",$fe); 
	list($hh, $mm, $ss) = explode(":",$hor);
}else{
	$anio=$ano+1;
	$mes="12"; 
	$dia="30"; 
	$hh="00"; 
	$mm="00"; 
	$ss="00"; 
}

$config['day']=$dia;
$config['month']=$mes;
$config['year']=$anio;
$config['hour']=$hh;
$config['minute']=$mm;
$config['second']=$ss;

//echo $config['year'];

$now = time();
$target = mktime(
	$config['hour'], 
	$config['minute'], 
	$config['second'], 
	$config['month'], 
	$config['day'], 
	$config['year']
);
$diferencia = $target - $now;
$date = array();
$date['secs'] = $diferencia % 60;
$date['mins'] = floor($diferencia/60)%60;
$date['hours'] = floor($diferencia/60/60)%24;
$date['days'] = floor($diferencia/60/60/24)%7;
$date['weeks']	= floor($diferencia/60/60/24/7);
//echo $date['days'];


foreach ($date as $i => $d) {
	$d1 = $d%10;
	$d2 = ($d-$d1) / 10;
	$date[$i] = array(
		(int)$d2,
		(int)$d1,
		(int)$d
	);
}

?>