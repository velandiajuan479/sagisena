<?php
  ini_set('memory:limit', '512M');

  require_once '../models/conexion.php';
  require_once '../models/mpresp.php';

  use Dompdf\Dompdf;

  $mpre = new Mpresp();

  $idaul = isset($_REQUEST['idaul']) ? $_REQUEST['idaul'] : NULL;
  
  $mpre->setIdaul($idaul);
  $dtPreEsp = $mpre->getPresEsp();

  date_default_timezone_set('America/Bogota');
  $mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  $fecha = date('d') . " de " . $mes[date('m') - 1] . " de " . date('Y');
  
  $width = 740;

  $html = '';
  if (!empty($dtPreEsp)) {
    $html .= '<!DOCTYPE html>
              <html lang="en">
              <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Prestamo '.$dtPreEsp[0]['nomaul'].'</title>
              </head>
              <body>
                <style>
                  table {
                    border-collapse: collapse;
                  }

                  table tr {
                    font-size: 10px;
                  }

                  table tr td {
                    text-align: center;
                  }

                  table tr th, table tr td {
                    border: 1px solid #000;
                    padding: 3px 0;
                    text-transform: uppercase;
                  }
                </style>
                <table width="'.$width.'px">
                  <tr>
                    <td style="text-align: center;">
                      <img src="../image/sena.png" width="40px">
                    </td>
                    <th>
                      <strong>LISTADO DE SOLICITUDES DE '.$dtPreEsp[0]['nomaul'].'</strong>
                    </th>
                  </tr>
                </table>
                <table width="'.$width.'px">
                  <thead>
                    <tr>
                      <th>Para</th>
                      <th>Fecha</th>
                      <th>Estado</th>
                      <th>Cumplido</th>
                      <th>Administrador</th>
                    </tr>
                  </thead>
                  <tbody>';
        foreach ($dtPreEsp as $dpe) {
          $html .= '<tr>
                      <td>
                        '.$dpe['unomusu'].' - '.$dpe['unomper'].'<br>
                        <small>'.$dpe['undocusu'].'</small>
                      </td>
                      <td>
                        '.$dpe['fecfin'].'<br>
                        Desde '.$dpe['horini'].'<br>
                        Hasta '.$dpe['horfin'].'
                      </td>
                      <td>';
            switch ($dpe['apresp']) {
              case $dpe['apresp'] == 0:
                $est = 'No Aprobado';
                break;
              case $dpe['apresp'] == 1:
                $est = 'Aprobado';
                break;
              case $dpe['apresp'] == 2:
                $est = 'En proceso';
                break;
            }
              $html .= $est.'
                      </td>
                      <td>';
                    switch ($dpe['incpre']) {
                      case $dpe['incpre'] == 0:
                        $inc = 'Incumplido';
                        break;
                      case $dpe['incpre'] == 1 && empty($dpe['whoapr']):
                        $inc = 'En proceso';
                        break;
                      case $dpe['incpre'] == 1 && isset($dpe['whoapr']):
                        $inc = 'Cumplido';
                        break;
                    }
              $html .= $inc.'
                      </td>';
            $who = isset($dpe['whoapr']) ? $dpe['uwnomusu'] : 'En proceso';
            $html .= '<td>'.$who.'</td>
                    </tr>';
        }
        $html .= '</tbody>
                </table>
              </body>
              </html>';
    } else {
      $html .= '<h1>No hay solicitudes realizadas para esta aula</h1>';
    }
  echo $html;
  echo "<script type='text/javascript'> window.print(); </script>";