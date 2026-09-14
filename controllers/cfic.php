<?php
    
    require_once(__DIR__ . '/../models/mfic.php');

    $mfic = new Mfic();
    $idfic = isset($_REQUEST['idfic']) ?$_REQUEST['idfic']:NULL;
    $nomfic = isset($_POST['nomfic']) ?$_POST['nomfic']:NULL;
    $jornada = isset($_POST['jornada']) ?$_POST['jornada']:NULL;
    $mun = isset($_POST['mun']) ?$_POST['mun']:NULL;
    $idcen = isset($_POST['idcen']) ?$_POST['idcen']:NULL;
    $finific= isset($_POST['finific']) ?$_POST['finific']:NULL;
    $ffinfic= isset($_POST['ffinfic']) ?$_POST['ffinfic']:NULL;
    $opera = isset($_REQUEST['opera']) ?$_REQUEST['opera']:NULL;
    $idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:$_SESSION["idusu"];
    $datOne = NULL;
    $pg = 1210;
    
    $idfic = trim(str_replace(" ","",$idfic));
    
$mfic->setIdfic($idfic);
    // Insertar 
    if($opera=="Insertar"){
        if($idfic AND $nomfic AND $jornada AND $idcen){
            $mfic->setIdfic($idfic);
            $mfic->setNomfic($nomfic);
            $mfic->setJornada($jornada);
            $mfic->setIdcen($idcen);
            $mfic->setMun($mun);
            $mfic->setFinific($finific);
            $mfic->setFfinfic($ffinfic);
            $mfic->ins();
            $idfic = NULL;
        }
    }
    // actualizar
    if($opera=="Actualizar"){
        if($idfic AND $nomfic AND $jornada AND $idcen){
            $mfic->setIdfic($idfic);
            $mfic->setNomfic($nomfic);
            $mfic->setJornada($jornada);
            $mfic->setIdcen($idcen);
            $mfic->setMun($mun);
            $mfic->setFinific($finific);
            $mfic->setFfinfic($ffinfic);
            $mfic->upd();
            $idfic = NULL;
        }
    }

    // Eliminar
    if($opera=="Eliminar"){
        if("$idfic"){
            $mfic->del();
        }
    }

    //mostrar datos
    $dat = $mfic->selAll();
    $dce = $mfic->getCen();
    $djo = $mfic->getJor();
    $dubi = $mfic->getUbica();
    if($idfic){
        $datOne = $mfic->selOne();
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ope']) && $_POST['ope'] === 'InAct') {
    $idfic = $_POST['idfic'];
    $idusu = $_POST['coorusu'];
    $numact = $_POST['numacta'];

    $idactdc = $mfic->insertarActaCierre($idusu, $idfic, $numact);
}

function modActCierreModal($fic, $nomfic, $feini, $fefin, $pg) {
    $html = '';
    $html .= '<div class="modal" id="CrearActModal' . $fic . '" tabindex="-1" role="dialog">';
    $html .= '<div class="modal-dialog">';
    $html .= '<form action="home.php?pg=' . $pg . '" method="POST">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header">';
    $html .= '<h3>Acta de Cierre</h3>';
    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    $html .= '</div>';
    $html .= '<div class="modal-body">';
    $html .= '<h5>Ficha: ' . $fic . " " . $nomfic . '</h5><br>';
    $html .= '<h5>Fechas: ' . $feini . " " . $fefin . '</h5><br>';

    $html .= '<div class="row">';
    $html .= '<div class="form-group col-md-12">';
    $html .= '<label for="conact">Conclusiones</label>';
    $html .= '<textarea name="conact" id="conact" class="form-control"></textarea>';
    $html .= '</div>';

    $html .= '<div class="form-group col-md-12">';
    $html .= '<label for="coorusu">Coordinador</label>';
    $html .= '<select name="coorusu" id="coorusu" class="form-select">';
    $html .= '<option value="1">Robin</option>';
    $html .= '</select>';
    $html .= '</div>';

    // === Actas por trimestres ===
    $inicio = new DateTime(explode(" ", $feini)[0]); 
    $fin = new DateTime(explode(" ", $fefin)[0]);
    $hoy = new DateTime();

    $html .= '<div class="form-group col-md-12">';
    $html .= '<label for="numacta">Seleccione Acta</label>';
    $html .= '<select name="numacta" id="numacta" class="form-select">';

    $actaNum = 1;
    $temp = clone $inicio;

    while ($temp < $fin) {
        $limite = (clone $temp)->add(new DateInterval('P3M'));

        // Detectar si es el último trimestre
        if ($limite >= $fin) {
            $texto = "Acta de Cierre (" . $temp->format("Y-m-d") . " a " . $fin->format("Y-m-d") . ")";

            // Solo disponible si ya pasó la fecha de fin de la ficha
            if ($hoy < $fin) {
                $html .= '<option value="cierre" disabled>' . $texto . ' (No disponible)</option>';
            } else {
                $html .= '<option value="cierre">' . $texto . '</option>';
            }
        } else {
            $texto = "Acta $actaNum (" . $temp->format("Y-m-d") . " a " . $limite->format("Y-m-d") . ")";

            // Solo disponible si ya pasó el trimestre
            if ($hoy < $limite) {
                $html .= '<option value="' . $actaNum . '" disabled>' . $texto . ' (No disponible)</option>';
            } else {
                $html .= '<option value="' . $actaNum . '">' . $texto . '</option>';
            }
        }

        $temp = $limite; 
        $actaNum++;
    }

    $html .= '</select>';
    $html .= '</div>'; // cierre select

    $html .= '</div>'; // cierre row
    $html .= '</div>'; // cierre body

    $html .= '<div class="modal-footer">';
    $html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
    $html .= '<input type="submit" class="btn btn-primary" value="Guardar">';
    $html .= '<input type="hidden" name="idfic" value="' . $fic . '">';
    $html .= '<input type="hidden" name="ope" value="InAct">';
    $html .= '</div>';

    $html .= '</div>'; 
    $html .= '</form>';
    $html .= '</div>'; 
    $html .= '</div>'; 

    return $html;
}




?>