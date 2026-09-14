<?php
  require_once('../models/conexion.php');
  require_once("../models/mpraul.php");

  $mpraul = new Mpraul();
  $fecha = date("Y-m-d");
  $hora = date("H:i:s");

  $fin = isset($_GET['fin']) ? $_GET['fin'] : NULL;
  $ffi = isset($_GET['ffi']) ? $_GET['ffi'] : NULL;
  $idusu = isset($_GET['idusu']) ? $_GET['idusu'] : NULL;
  $idaul = isset($_GET['idaul']) ? $_GET['idaul'] : NULL;

  $dtHis = $mpraul->getAll($fin, $ffi, $idusu, $idaul);
  $ancho = "730px";

  $html = '';
  $html .='<!DOCTYPE html>
            <html>
            <head>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <title>Reporte de Préstamos de Aulas</title>
              <style>
                body {
                  font-family: Arial;
                  font-size: 12px;
                }
                table thead tr th, table tbody tr td {
                  padding: 10px;
                }
              </style>
            </head>
            <body>
              <table style="width:'.$ancho.'" border="1" cellspacing="0px">
                <tr>
                  <td style="text-align: center;" rowspan="3">
                    <img src="../image/logocom.png" style="width: 80px;">
                  </td>
                  <th style="text-align: center;" rowspan="3">
                      SERVICIO NACIONAL DE APRENDIZAJE - SENA<BR>
                      CENTRO DE DESARROLLO AGROEMPRESARIAL CHÍA - CUNDINAMARCA<BR>
                      REGISTRO DE PRESTAMO DE AULAS
                  </th>
                  <th style="text-align: center;">ADMINISTRACIÓN</th>
                </tr>
                <tr>
                  <td><strong>Fecha:</strong> '.$fecha.'</td>
                </tr>
                <tr>
                  <td><strong>Hora:</strong> '.$hora.'</td>
                </tr>
              </table>
              <BR>
              <table style="width:'.$ancho.'" border="1" cellspacing="0px" cellpadding="4px">
                <thead>
                  <tr>
                    <th>Usuario</th>
                    <th>Aula</th>
                    <th>Fecha Recibido</th>
                    <th>Fecha Entrega</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>';
        if ($dtHis) { foreach ($dtHis as $d) {
          $html .='<tr>
                    <td>'.$d['ndocusu'].' - '.$d['nomusu'].'</td>
                    <td>'.$d['nomaul'].'</td>
                    <td>'.$d['fechin'].'</td>
                    <td>'.$d['fechfin'].'</td>
                    <td>'.$d['estado'].'</td>
                  </tr>';
        }}
        $html .='</tbody>
              </table>
            </body>
            </html>';
  echo $html;
  echo "<script>window.print();</script>";
?>