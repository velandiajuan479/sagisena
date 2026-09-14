<?php
require_once '../models/conexion.php';
require_once '../models/mcer.php';

$idusu = isset($_GET['idusu']) ? $_GET['idusu'] : null;

date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d') . " de " . $mes[date('m') - 1] . " de " . date('Y');
$fecha2 = date('YmdHis');

$mcer = new Mcer();
$mcer->setIdusu($idusu);
$datOne = $mcer->selOne();

function urlimg($url)
{
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
    return $imagenBase64;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificado</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        img { width: 80px; }
        .firma { margin-top: 50px; }
    </style>
</head>
<body onload="window.print()">
    <img src="<?php echo urlimg('../image/sena.png'); ?>"><br><br>

    <h2>El Servicio Nacional de Aprendizaje SENA</h2>
    <p><strong>Hace constar que</strong></p>

    <p>
        <?php if($datOne) echo $datOne[0]['nomusu']; ?><br>
        Con CÉDULA DE CIUDADANÍA No. 
        <?php if($datOne) echo $datOne[0]['ndocusu']; ?>
    </p>

    <p><strong>Participó en la Jornada electoral</strong><br><br>
    <strong>Votaciones SENA</strong><br><br>
    <?php 
    if ($datOne && $datOne[0]['idfic']) {
        echo 'Ficha ' . $datOne[0]['idfic'] . '<br>';
        echo 'Jornada ' . $datOne[0]['nomval'];
    }
    ?>
    </p>

    <p><small>En testimonio de lo anterior se firma en Chía a la fecha <?php echo $fecha; ?></small></p>

    <div class="firma">
        <div style="float:left; width:45%;">
            JAVIER RICARDO JIMÉNEZ RINCÓN<br>
            Subdirector 
            <?php if($datOne) echo $datOne[0]['nomcen']; ?><br>
            REGIONAL CUNDINAMARCA
        </div>
        <div style="float:right; width:45%;">
            <?php echo $fecha2; ?><br>
            No. Y FECHA DE REGISTRO
        </div>
        <div style="clear: both;"></div>
    </div>

    <p>Para verificar la validez de este certificado consultar la página <br>
    www.websolution.com.co/sagi</p>
</body>
</html>
