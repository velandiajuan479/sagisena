<?php
require_once '../models/conexion.php';
require_once '../models/mrphg.php';
ini_set('memory_limit', '512M');
// require_once '../vendor/autoload.php';

$idhor = isset($_GET['idhor']) ? $_GET['idhor'] : NULL;
$idare = isset($_GET['idare']) ? $_GET['idare'] : NULL;

$ancho = 750;
$alto = 500;
$alto2 = 300;
$html = "";

$mrphg = new Mrphg();
$mrphg->setIdhor($idhor);
$datcent = $mrphg->getCent();
$mrphg->setIdare($idare);
$datfic = $mrphg->getAll();
$ddi = $mrphg->getDia();


function pintit($ddi){
    $dias = "";
    if($ddi){ foreach ($ddi as $d) {
      $dias .= "<th>".$d['nomval']."</th>";
    }}
    return $dias;
}

function urlimg($url)
{
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
    return $imagenBase64;
}

require_once ('stlrep.php');

$html .= '<body>';
    $html .= '<table>';
        $html .= '<tbody>';
            $html .= '<tr>';
                $html .= '<td>';
                    $html .= '<img src="https://sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="logoSena">';
                    if($datcent){ foreach ($datcent as $dtc){
                        $html .= '<h1>' . $dtc['nomcen'] . '</h1>';
                        $html .= '<h5>' . $dtc['dircen'] . '</h5>';
                    }}
                $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th>';
                $html .= '<h1>REPORTE DE HORARIOS GENERALES</h1>';
                $html .= '</th>';
            $html .= '</tr>';
        $html .= '</tbody>';
    $html .= '</table>';

    $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th colspan="3">PROGRAMA DE FORMACION</th>';
        $html .= '<th colspan="7">HORARIO</th>';
            $html .= '</tr>';
            $html .= '<tr>';
                $html .= '<th>Ficha</th>';
                $html .= '<th>Programa</th>';
                $html .= '<th>Jornada</th>';
                $html .= pintit($ddi);
            $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
            if($datfic){ foreach ($datfic as $dtf) {
                $html .= '<tr>';
                    $html .= '<td class="dato">' . $dtf['idfic'] .'</td>';
                    $html .= '<td class="dato">' . $dtf['nomfic'] . '</td>';
                    $html .= '<td class="dato">' . $dtf['nomval'] . '</td>';
                    if($ddi){ foreach ($ddi as $d) {
                        $html .= "<td>";
                          $mrphg->setIdfic($dtf["idfic"]);
                          $mrphg->setIddia($d["idval"]);
                          $dat = $mrphg->getIns(); 
                          if($dat){ foreach ($dat as $dti) {
                            $html .= $dti["nomusu"]."<BR>".$dti['idaul'] . " " .$dti['nomaul'];
                          }}
                        $html .= "</td>";
                      }}
                $html .= '</tr>';
            }}
        $html .= '</tbody>';
    $html .= '</table>';
$html .= '</body>';
$html .= '</html>';
    echo $html;
    echo "<script>window.print();</script>";
?>