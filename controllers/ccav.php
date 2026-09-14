<?php
require_once 'models/mcav.php';

$mcav = new Mcav();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:NULL;
$telcan = isset($_POST['telcan']) ? $_POST['telcan']:NULL;
$emausu = isset($_POST['emausu']) ? $_POST['emausu']:NULL;
$noca = isset($_POST['noca']) ? $_POST['noca']:NULL;
$fotcan = isset($_POST['fotcan']) ? $_POST['fotcan']:NULL;
$arch = isset($_FILES['arch']['name']) ? $_FILES['arch']['name']:NULL;

$fidcen = isset($_REQUEST['fidcen']) ? $_REQUEST['fidcen']:951310;
$fidfic = isset($_REQUEST['fidfic']) ? $_REQUEST['fidfic']:NULL;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
$exportar = isset($_GET['exportar']) ? $_GET['exportar']:NULL;
$datOne = NULL;



// Obtener datos
if($fidfic) {
    $dat = $mcav->selByFicha($fidfic);
    
    if(empty($dat)) {
        error_log("No se encontraron candidatos vocero para ficha: ".$fidfic);
    }
}


if($arch){
    $fotcan = opti($_FILES['arch'], $idusu, "fcan", "can");
}

// Actualizar
if($opera=="Actualizar"){
    if($idusu){
        $mcav->upd2();
    }
    $idusu="";
}

// Limpiar votaciones
if($opera=="Limpiar"){
    $mcav->limpiaVot();
    echo "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Proceso completado',
            text: 'Se han iniciado los valores de votaciones desde cero.',
            confirmButtonText: 'Aceptar'
        });
        </script>
        ";
}

// Obtener datos
$dcen = $mcav->getCen();
$dfichas = $mcav->getFichasByCentro($fidcen);

if($fidfic) {
    $dat = $mcav->selByFicha($fidfic);
}

if($idusu){
    $datOne = $mcav->selOne($idusu);
}



?>