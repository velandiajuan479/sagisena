<?php
require_once '../models/mcav.php';

$fidfic = isset($_GET['fidfic']) ? $_GET['fidfic'] : null;

if(!$fidfic) {
    header("HTTP/1.1 400 Bad Request");
    exit("Parámetro fidfic requerido");
}

$mcav = new Mcav();
$mcav->setIdusu(null); 

// obteniene informacion de la ficha
$fichaInfo = $mcav->getFichaInfo($fidfic);
if(!$fichaInfo) {
    header("HTTP/1.1 404 Not Found");
    exit("Ficha no encontrada");
}

// obtiene candidatos
$candidatos = $mcav->getCandidatosForExcel($fidfic);

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=voceros_ficha_{$fichaInfo['idfic']}_".date('YmdHis').".csv");

// Abre output stream
$output = fopen('php://output', 'w');

fputcsv($output, ['INFORMACIÓN DE CANDIDATOS VOCEROS']);
fputcsv($output, ["FICHA: {$fichaInfo['idfic']} - {$fichaInfo['nomfic']}"]);
fputcsv($output, ["CENTRO: {$fichaInfo['nomcen']}"]);
fputcsv($output, ["JORNADA: {$fichaInfo['nomval']}"]);
fputcsv($output, []); 

fputcsv($output, [
    'No.',
    'Nombre Completo',
    'Documento',
    'Email',
    'Teléfono',
    'N° Candidato'
]);

// datos de los candidatos
foreach($candidatos as $c) {
    fputcsv($output, [
        $c['noca'],
        $c['nomusu'],
        $c['ndocusu'],
        $c['emausu'],
        $c['telcan'],
        $c['noca']
    ]);
}

fclose($output);
?>