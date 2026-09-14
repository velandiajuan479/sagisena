<?php
require_once '../models/conexion.php';
require_once '../models/mrppses.php';

$mrppses = new Mrppses();
$dat = $mrppses->getAll();
$datC = $mrppses->getCen();
$datInst = $mrppses->getInstructor();
$datSes = $mrppses->getDatSes();
$html = null;
$html = "";

require_once 'stlrep.php';

$html .= '<table>';
$html .= '<tbody>';
if ($datC) {
    foreach ($datC as $dtc) {
        $html .= '<tr><td colspan="6">';
        $html .= '<img src="https://sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="logoSena">';
        $html .= '<h1>' . $dtc['nomcen'] . '</h1>';
        $html .= '<h5>' . $dtc['dircen'] . '</h5>';
        $html .= '</td></tr>';
    }
}
$html .= ' <tr class="title">';
$html .= ' <td colspan="6"><b>PLAN DE SESIÓN</b></td>';
$html .= ' </tr>';
$html .= '<tr class="title">';
$html .= '<td colspan="6"><b>DATOS GENERALES</b></td>';
$html .= '</tr>';
$html .= '<tr>';
if ($dat) {
    foreach ($dat as $dt) {
        $html .= '<td colspan="2" class="title"><b>Fecha</b></td>';
        $html .= ' <td colspan="1">' . $dt["fecses"] . '</td>';
        $html .= '<td colspan="2" class="title"><b>Sesión N°</b></td>';
        $html .= '<td colspan="1">' . $dt["ndeses"] . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" class="title"><b>Ficha</b></td>';
        $html .= '<td colspan="1">' . $dt["idfic"] . '</td>';
        $html .= '<td colspan="2" class="title"><b>Programa de Formación</b></td>';
        $html .= '<td colspan="1">' . $dt["nomfic"] . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        if ($datInst) {
            foreach ($datInst as $dti) {
        $html .= ' <td colspan="2" class="title"><b>Instructor</b></td>';
        $html .= '<td colspan="4">'. $dti["nomusu"].'</td>';
        $html .= '</tr>';
            }
        }
        $html .= '<tr>';
        $html .= ' <td colspan="2" class="title"><b>Actividad de Proyecto</b></td>';
        $html .= '<td colspan="4">0</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" class="title"><b>Competencia</b></td>';
        $html .= '<td colspan="1">' . $dt["descom"] . '</td>';
        $html .= ' <td colspan="2" class="title"><b>Resultado</b></td>';
        $html .= '<td colspan="1">' . $dt["nomres"] . '</td>';
        $html .= ' </tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" class="title"><b>Objetivo</b></td>';
        $html .= '<td colspan="4">CONOCER MI EQUIPO</td>';
        $html .= '</tr>';
        $html .= ' <tr>';
        $html .= '<td colspan="6" class="title">Secuencia Didáctica</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="4"></td>';
        $html .= '<td colspan="1" class="title"><b>Duración Total (hrs)</b></td>';
        $html .= '<td colspan="1"><b>0,0</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="title"><b>Momento</b></td>';
        $html .= '<td class="title"><b>Fecha</b></td>';
        $html .= '<td class="title"><b>Actividad</b></td>';
        $html .= '<td class="title"><b>Descripción</b></td>';
        $html .= '<td colspan="2" class="title">Tiempo</td>';
        $html .= '</tr>';
        if ($datSes) {
            foreach ($datSes as $dtS) {
        $html .= '<tr>';
        $html .= '<td>' . $dtS["tipact"] . '</td>';
        $html .= '<td>' . $dtS["fecses"] . '</td>';
        $html .= '<td>' . $dtS["nomact"] . '</td>';
        $html .= '<td>' . $dtS["desact"] . '</td>';
        $html .= '<td colspan="2">' . $dtS["duract"] . '</td>';
        $html .= '</tr>';
            }
        }
        $html .= '<tr>';
        $html .= '<td colspan="6" class="title"><b>Observaciones de la sesión</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="6"></td>';
        $html .= '</tr>';
    }
}
$html .= '</tbody>';
$html .= '</table>';
$html .= '</body>';

$html .= '</html>';
echo $html;
echo "<script>window.print()</script>";
