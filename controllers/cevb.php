<?php
require_once 'models/mevb.php';

$mevb = new Mevb();

$idusu = $_SESSION["idusu"] ?? null;

if (!$idusu) {
    die("Usuario no autenticado.");
}

$fichas = $mevb->getFichasBitacoras($idusu);

$idficha_seleccionada = $_REQUEST['idficha'] ?? null;
$aprendices = [];
$aprendices_aprobados = [];
$aprendices_rechazados = [];

if ($idficha_seleccionada) {
    $aprendices = $mevb->getAprendicesByFicha($idficha_seleccionada);
    $aprendices_aprobados = $mevb->getAprendicesAprobadosByFicha($idficha_seleccionada);
    $aprendices_rechazados = $mevb->getAprendicesRechazadosByFicha($idficha_seleccionada);
}

$opera = $_REQUEST['opera'] ?? null;
$pg = $_GET['pg'] ?? 'evaluar_bitacora';

if ($opera === 'evaluar_masivamente') {
    $bitacoras = $_POST['bitacoras_seleccionadas'] ?? [];
    $estado = $_POST['estado_masivo'] ?? null;
    $observacion = trim($_POST['observacion_masiva'] ?? '');

    if (empty($bitacoras)) {
        $msg = 'bitacoras_vacias';
    } elseif (!in_array($estado, ['aprobado', 'rechazado'])) {
        $msg = 'estado_invalido';
    } elseif (empty($observacion)) {
        $msg = 'observacion_vacia';
    } else {
        $ok = true;
        foreach ($bitacoras as $idbitacora) {
            if (
                !$mevb->actualizarEstadoBitacoraMasiva($idbitacora, $estado) ||
                !$mevb->saverObservationMasiva($idbitacora, $observacion)
            ) {
                $ok = false;
                break;
            }
        }
        $msg = $ok ? 'bitacoras_actualizadas' : 'error_al_actualizar_masiva';
    }

    echo "<script>alert('$msg'); location.href='home.php?pg=$pg';</script>";
    exit;
}

function modalBitacoraDetalles($idbitacora, $numero_bitacora, $pg){
    $mevb = new mevb();
    $actividades = $mevb->getActividadesByBitacora($idbitacora);
    $html = '';

    $html .= '<div class="modal" id="bitDet'.$idbitacora.'" tabindex="-1" role="dialog">';
        $html .= '<div class="modal-dialog modal-lg">';
            $html .= '<div class="modal-content">';
                $html .= '<div class="modal-header bg-success text-white">';
                    $html .= '<h5 class="modal-title">Actividades de la Bitácora #'.$numero_bitacora.'</h5>';
                    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>';
                $html .= '</div>';
                $html .= '<div class="modal-body">';

                if ($actividades && count($actividades) > 0) {
                    $html .= '<div class="table-responsive">';
                        $html .= '<table class="table table-striped">';
                            $html .= '<thead><tr><th>Descripción</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Evidencia</th></tr></thead>';
                            $html .= '<tbody>';
                            foreach ($actividades as $act) {
                                $html .= '<tr>';
                                    $html .= '<td>'.$act['descripcion_actividad'].'</td>';
                                    $html .= '<td>'.$act['fecha_inicio_act'].'</td>';
                                    $html .= '<td>'.$act['fecha_fin_act'].'</td>';
                                    $html .= '<td>'.$act['evidencia_cumplimiento'].'</td>';
                                $html .= '</tr>';
                            }
                            $html .= '</tbody>';
                        $html .= '</table>';
                    $html .= '</div>';
                } else {
                    $html .= '<p class="text-muted">No hay actividades registradas para esta bitácora.</p>';
                }

                $html .= '</div>';
                $html .= '<div class="modal-footer">';
                    $html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';

    return $html;
}


