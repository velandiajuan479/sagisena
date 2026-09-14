<?php

require_once 'models/mdpc.php';

$mdpc = new Mdpc();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:NULL;
$telcan = isset($_POST['telcan']) ? $_POST['telcan']:NULL;
$emausu = isset($_POST['emausu']) ? $_POST['emausu']:NULL;
$noca = isset($_POST['noca']) ? $_POST['noca']:NULL;
$fotcan = isset($_POST['fotcan']) ? $_POST['fotcan']:NULL;
$arch = isset($_FILES['arch']['name']) ? $_FILES['arch']['name']:NULL;

$fidcen = isset($_REQUEST['fidcen']) ? $_REQUEST['fidcen']:951310;
$fidjor = isset($_REQUEST['fidjor']) ? $_REQUEST['fidjor']:1;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
$datOne = NULL;

if($arch){
	$fotcan = opti($_FILES['arch'], $idusu, "fcan", "can");
}

// $pg = 1206;
//echo $idusu."-".$idper."-".$opera;
//Actualizar
if($opera=="Actualizar"){
	if($idusu){
		$mdpc->upd2();
	}
	$idusu="";
}

//Limpiar votaciones
if($opera=="Limpiar"){
    $mdpc->limpiaVot();
    echo "<script>
        Swal.fire({
            title: 'Éxito',
            text: 'Se han iniciado los valores de votaciones desde cero.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>";
}


//mostrar todos los datos
	// $mdpc -> setFidcen($fidcen);
	// $mdpc -> setFidjor($fidjor);
	$dat = $mdpc->selAll($fidjor,$fidcen);

//$pg = 1108;
$djor = $mdpc->getJor();
$dcen = $mdpc->getCen();

if($idusu){
	$datOne = $mdpc->selOne($idusu);
}

?>