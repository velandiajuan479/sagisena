<?php
include "../models/conexion.php";
include "../controllers/optimg.php";
include "../models/macta.php";
include "../models/musu.php";

date_default_timezone_set('America/Bogota');
$mes = ["","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE"];
$fecha = "CHÍA, ".$mes[intval(date('m'))]." ".date('d')." DE ".date('Y');
$hora = date("H:i a");
$idact = isset($_GET['idact']) ? $_GET['idact']:NULL;
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : (isset($_SESSION["idusu"]) ? $_SESSION["idusu"] : NULL);
session_start();


$macta = new Macta();
$macta->setIdact($idact);
$dt = $macta->getOne();
$dtAcDe = $macta->getOneAcDe();
$dtAsis = $macta->getOneAsis();
$dteDes = $macta->getOneDes();


$musu = new Musu();
$musu->setIdusu($dteDes['idusu']);
$dtusu = $musu->getOne();


$macta->setIdusu($dteDes['idusu']);
$macta->setIdfic($dtusu[0]['idfic']);
$dtIna = $macta->getInasis();
$dtTin = $macta->getTotIna();
$macta->setTit("Acta de Deserción");
$macta->setNac($dt['nac']);
$macta->setNcre("ACTA DE SOLICITUD DE DESERCIÓN DE LA ETAPA LECTIVA PARA LA FICHA No. ".strtoupper($dtusu[0]['idfic'])." PROGRAMA ".strtoupper($dtusu[0]['nomfic']).", JORNADA ".strtoupper($dtusu[0]['nomval']).", MUNICIPIO DE CHÍA");
$macta->setCife($fecha);
$macta->setHini($hora);
$macta->setHfin((date("H")+1).":".date("i a"));
$macta->setJgen("CHÍA, CUNDINAMARCA");
$macta->setDrcd("CUNDINAMARCA, CENTRO DE DESARROLLO AGROEMPRESARIAL");
$macta->setAgep("1. Revisión del estado de formación del aprendiz.<br>
2. Verificar las inasistencias del aprendiz.<br>
3. Realizar proceso de deserción.");

$macta->setObjr("Verificar el estado de formación del aprendiz  ".strtoupper($dtusu[0]['nomusu'])." con numero de documento  ".strtolower($dtusu[0]['ndocusu']).", jornada ".strtolower($dtusu[0]['nomval'])."");


	$desa = '';
$desa .= '<br>El día '.$mes[intval(date('m'))].' '.date('d').' DE '.date('Y').' en reunión de equipo ejecutor, el instructor '.strtoupper($_SESSION["nomusu"]).'  realizando una verificación de la ficha
  '.strtolower($dtusu[0]['idfic']).'   -  '.strtoupper($dtusu[0]['nomfic']).'del municipio de Chia.El aprendiz '.strtoupper($dtusu[0]['nomusu']).
'  con numero de documento CC: '.strtolower($dtusu[0]['ndocusu']).'  mencionado anteriormente no ha justificado sus inasistencias. Se realizo el llamado donde el aprendiz no contesto. Y me dice que no va a continuar el proceso de formación por motivos personales. Se le informo el proceso de retiro voluntario,
 pero no hace mención de algún interés de hacer dicho proceso. De acuerdo a eso se realizará el proceso de deserción. '.'<br>';

	$imagenes = $macta->obtenerImagenesUsuario($dteDes['idusu']);
if ($imagenes) {
    $desa .= '<br><strong>Se anexa las inasistencias de los instructores.</strong><br>';
    foreach ($imagenes as $img) {
        $ruta = '../img/inasi/' . $img['nomimg'];
        $desa .= '<div style="margin: 10px 0;">';
        $desa .= "<div style='text-align:center;'><img src='{$ruta}' style='max-width:600px; border:1px solid #000; padding:4px;'></div><br><br>";

        $desa .= '</div>';
    }
}

$desa .= '<table cellspacing="0" cellpadding="4" border="1" style="width: 100%;">';
		$desa .= '<tr>';
			$desa .= '<th>Fecha</th>';
			$desa .= '<th>No. Horas</th>';
		$desa .= '</tr>';
		if($dtIna){ foreach ($dtIna as $di) {
			$desa .= '<tr>';
				$desa .= '<td>'.$di["fecina"].'</td>';
				$desa .= '<td>'.$di["horasina"].'</td>';
			$desa .= '</tr>';
		}}
		$desa .= '<tr>';
			$desa .= '<th>TOTAL</th>';
			$desa .= '<th>'.$dtTin[0]["tot"].'</th>';
		$desa .= '</tr>';
	$desa .= '</table>';

	$desa .= '</br>';	
$macta->setDesr($desa);

$macta->setConc("De acuerdo con el grupo ejecutor se solicitará a coordinación el proceso de deserción para el anterior aprendiz, de acuerdo al debido proceso como se indica en el reglamento del aprendiz.");
$macta->setEaco($dtAcDe);
$macta->setAade($dtAsis);
echo $macta->getActa();
?>