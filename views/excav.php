<?php
require_once '../models/mcav.php';

$fidfic = isset($_GET['fidfic']) ? $_GET['fidfic'] : null;

if(!$fidfic) {
    header("HTTP/1.1 400 Bad Request");
    exit("Parámetro fidfic requerido");
}

$mcav = new Mcav();
$mcav->setIdusu(null); 

// obtiene informaci on de la ficha
$fichaInfo = $mcav->getFichaInfo($fidfic);
if(!$fichaInfo) {
    header("HTTP/1.1 404 Not Found");
    exit("Ficha no encontrada");
}

// obtenienen candidatos
$candidatos = $mcav->getCandidatosForExcel($fidfic);


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=voceros_ficha_{$fichaInfo['idfic']}_".date('YmdHis').".xls");

echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
echo "<table border='1'>";

echo "<tr><th colspan='6' style='background-color: #117f09; color: white;'>INFORMACIÓN DE CANDIDATOS VOCEROS</th></tr>";
echo "<tr><th colspan='6'>FICHA: {$fichaInfo['idfic']} - {$fichaInfo['nomfic']}</th></tr>";
echo "<tr><th colspan='6'>CENTRO: {$fichaInfo['nomcen']}</th></tr>";
echo "<tr><th colspan='6'>JORNADA: {$fichaInfo['nomval']}</th></tr>";
echo "<tr><th colspan='6'>&nbsp;</th></tr>";

echo "<tr style='background-color: #d9ead3;'>";
echo "<th>No.</th>";
echo "<th>Nombre Completo</th>";
echo "<th>Documento</th>";
echo "<th>Email</th>";
echo "<th>Teléfono</th>";
echo "<th>N° Candidato</th>";
echo "</tr>";

// datos de los candidatos
foreach($candidatos as $c) {
    echo "<tr>";
    echo "<td>{$c['noca']}</td>";
    echo "<td>{$c['nomusu']}</td>";
    echo "<td>{$c['ndocusu']}</td>";
    echo "<td>{$c['emausu']}</td>";
    echo "<td>{$c['telcan']}</td>";
    echo "<td>{$c['noca']}</td>";
    echo "</tr>";
}

echo "</table>";
echo "</html>";
?>