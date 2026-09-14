<?php
require_once '../models/conexion.php';
require_once '../models/mfic.php';

setlocale(LC_TIME, 'es_ES.UTF-8');
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    setlocale(LC_TIME, 'spanish');
}
date_default_timezone_set('America/Bogota');

// Inputs
$idact = isset($_GET['idact']) ? $_GET['idact'] : null;

$mfic = new Mfic();
$acta = $mfic->getActa($idact); // Info de la tabla acta
$fichas = $mfic->getFichasByActa($idact); // Info de la tabla acta_cierre
$fecha = strftime('%d de %B de %Y', strtotime($acta['fecact']));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta General</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 6px; }
        .header-table td { text-align: center; vertical-align: middle; }
        .logo { width: 80px; }
        .title { background: #d9d9d9; font-weight: bold; font-size: 16px; padding: 8px; border: 1px solid #000; text-align: center; }
        h2, h3 { margin: 5px 0; }
        .subtitulo { font-weight: bold; margin-top: 10px; }
        .firmas td { height: 60px; text-align: center; }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td style="width: 20%;"><img src="../assets/img/logo-sena.png" class="logo"></td>
        <td style="width: 60%;">
            <strong>CENTRO DE DESARROLLO AGROEMPRESARIAL CHÍA</strong><br>
            <h2>ACTA DE SEGUIMIENTO</h2>
        </td>
        <td style="width: 20%;"><strong>Fecha:</strong><br><?= $fecha ?></td>
    </tr>
</table>

<!-- Título -->
<div class="title">ACTA No. <?= str_pad($acta['nac'], 2, '0', STR_PAD_LEFT) ?></div>

<!-- Información básica -->
<table>
    <tr><td><strong>Nombre del comité o reunión:</strong><br><?= strtoupper($acta['conact']) ?></td></tr>
    <tr>
        <td>
            <strong>Ciudad y Fecha:</strong> CHÍA, <?= $fecha ?><br>
            <strong>Hora de inicio:</strong> 07:00 am<br>
            <strong>Hora de finalización:</strong> 12:59 pm<br>
            <strong>Lugar:</strong> CHÍA, Cundinamarca<br>
            <strong>Centro:</strong> CUNDINAMARCA / CENTRO DE DESARROLLO AGROEMPRESARIAL
        </td>
    </tr>
</table>

<!-- Fichas asociadas -->
<h3 class="subtitulo">Fichas asociadas a esta acta</h3>
<table>
    <thead>
        <tr>
            <th>Código Ficha</th>
            <th>Fecha de Registro</th>
            <th>Registrado por</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fichas as $f) { ?>
            <tr>
                <td><?= $f['idfic'] ?></td>
                <td><?= strftime('%d/%m/%Y %H:%M', strtotime($f['fecregdec'])) ?></td>
                <td><?= $f['nomusu'] ?? 'Usuario ID: '.$f['idusu'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Objetivo -->
<h3 class="subtitulo">Objetivo de la reunión</h3>
<p>Identificar novedades en las fichas relacionadas, validar trámites pendientes, socializar estrategias de retención y evaluar avances de la etapa lectiva.</p>

<!-- Desarrollo -->
<h3 class="subtitulo">Desarrollo de la reunión</h3>
<p><?= nl2br($acta['conact']) ?></p>

<!-- Compromisos -->
<h3 class="subtitulo">Establecimiento y aceptación de compromisos</h3>
<table>
    <thead>
        <tr><th>Actividad / Decisión</th><th>Fecha</th><th>Responsable</th><th>Firma</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>Entrega del acta a coordinación académica</td>
            <td><?= date('d/m/Y', strtotime($acta['fecact'] . ' +1 day')) ?></td>
            <td><?= $acta['coorusu'] ?></td>
            <td></td>
        </tr>
    </tbody>
</table>

<!-- Firmas -->
<h3 class="subtitulo">Asistentes</h3>
<table class="firmas">
    <tr><th>Nombre</th><th>Dependencia</th><th>Firma</th></tr>
    <tr><td>LEONARDO LUCHINI</td><td>Líder de ficha</td><td></td></tr>
    <tr><td>KATALINA RANGEL</td><td>Coordinadora académica</td><td></td></tr>
    <tr><td>ROBINSON ENRIQUE RINCÓN</td><td>Instructor</td><td></td></tr>
</table>

<script>window.print();</script>
</body>
</html>
