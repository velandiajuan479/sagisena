<?php
require_once 'models/mbit.php';

// =======================
// Sesión y datos del usuario
// =======================
$idusu = $_REQUEST['idusu'] ?? $_SESSION["idusu"];
$ndocusu = $_SESSION["idusu"];

// =======================
// Instancia del modelo
// =======================
$mbit = new Mbit();

// =======================
// Datos generales del usuario
// =======================
$datOne       = $mbit->getOneUsu($idusu);
$datFicha     = $mbit->getFichaUsuario($idusu);
$datPrograma  = $mbit->getNombrePrograma($idusu);
$datDominios  = $mbit->getNombresDominios();
$datValores   = $mbit->getValoresPorDominio();
$datJefes     = $mbit->getJefe();
$datInst      = $mbit->getInstructor($idusu);
$datCargo     = $mbit->getPerfil();
$ultimoNumero = $mbit->getUltimoNumeroBitacora($idusu);
$proximoNumero = ($ultimoNumero < 12) ? $ultimoNumero + 1 : null;

// =======================
// Bitácoras
// =======================
$bitacorasAMostrar = $mbit->getBitacorasByUsuario($idusu);
// =======================
// Datos del formulario
// =======================
$numero_bitacora = $_POST['numero_bitacora'] ?? null;
$nombre_empresa   = $_POST['nombre_empresa'] ?? null;
$nit              = $_POST['nit'] ?? null;
$fecha_inicio     = $_POST['fecha_inicio'] ?? null;
$fecha_fin        = $_POST['fecha_fin'] ?? null;
$fecha_entrega    = $_POST['fecha_entrega'] ?? null;
$firma_aprendiz   = $_POST['firma_aprendiz'] ?? null;
$idaltep          = $_POST['idaltep'] ?? null;
$idsubaltep       = $_POST['idsubaltep'] ?? null;
$idaprendiz       = $_POST['idaprendiz'] ?? null;
$idjefe           = $_POST['idjefe'] ?? null;
$idinstructor     = $_POST['idinstructor'] ?? null;
$idbitacora       = $_REQUEST['idbitacora'] ?? null;
$nvlalr          = $_POST['nvlarl'] ?? null;
$id_aprendiz      = $idusu; // Aprendiz autenticado

// =======================
// Datos Fecha Bitacora
// =======================

if (!empty($datOneBit)) {
    $fechaInicioValor = $datOneBit['fecha_inicio'];
    $fechaFinValor = $datOneBit['fecha_fin'];
} else {
    $ultimaBitacora = $mbit->getUltimaBitacoraUsuario($idusu);

    if ($ultimaBitacora) {
        $fechaInicioValor = date('Y-m-d', strtotime($ultimaBitacora['fecha_fin'] . ' +1 day'));
        $fechaFinValor = date('Y-m-d', strtotime($fechaInicioValor . ' +14 days'));
    } else {
        // Aquí llamas al nuevo método
        $ficha = $mbit->getFechaFinFichaPorUsuario($idusu);
        
        if ($ficha && !empty($ficha['ffinfic'])) {
            $fechaInicioValor = date('Y-m-d', strtotime($ficha['ffinfic'] . ' +1 day'));
        } else {
            // En caso de que tampoco tenga ficha, fallback a hoy
            $fechaInicioValor = date('Y-m-d');
        }

        $fechaFinValor = date('Y-m-d', strtotime($fechaInicioValor . ' +14 days'));
    }
}

$fechaInicioPeriodo = $fechaInicioValor;
$fechaFinPeriodo = $fechaFinValor;

// =======================
// Actividades múltiples
// =======================
$actividades_descripcion   = $_POST['actividad_descripcion'] ?? [];
$actividades_fecha_inicio  = $_POST['fecha_inicio_act'] ?? [];
$actividades_fecha_fin     = $_POST['fecha_fin_act'] ?? [];
$actividades_evidencia     = $_POST['actividad_evidencia'] ?? [];
// =======================
// Operación solicitada
// =======================
$opera = $_REQUEST['opera'] ?? null;
$mbit->setIdbitacora($idbitacora);

$fecha_entrega = date('Y-m-d'); // Asigna automáticamente el día actual

$tipo_evidencia = $_POST['tipo_evidencia'] ?? '';
$archivo_evidencia = null;
$link_evidencia = null;

if ($tipo_evidencia === 'archivo' && !empty($_FILES['archivo_evidencia']['name'])) {
    $archivo_evidencia = opti($_FILES['archivo_evidencia'], $idusu, 'evid', ''); // ya tienes esta función
} elseif ($tipo_evidencia === 'link' && !empty($_POST['link_evidencia'])) {
    $link_evidencia = trim($_POST['link_evidencia']);
}

// =======================
// GUARDAR o EDITAR Bitácora
// =======================
if ($opera === "save") {
    // Asignar valores al modelo
    $mbit->setIdAprendiz($id_aprendiz);
    $mbit->setIdJefe($idjefe);
    $mbit->setIdInstructor($idinstructor);
    $mbit->setNombreEmpresa($nombre_empresa);
    $mbit->setNit($nit);
    $mbit->setNumeroBitacora($numero_bitacora);
    $mbit->setFechaInicio($fecha_inicio);
    $mbit->setFechaFin($fecha_fin);
    $mbit->setFechaEntrega($fecha_entrega);
    $mbit->setIdaltep($idaltep);
    $mbit->setIdsubaltep($idsubaltep);
    $mbit->setArchivoEvidencia($archivo_evidencia);
    $mbit->setLinkEvidencia($link_evidencia);
    $mbit->setNvlarl($nvlalr);
    try {
        if (!$idbitacora) {
            // Crear nueva bitácora
            $resultado = $mbit->save();

            if ($resultado) {
                // Guardar actividades
                $ultimaBitacora = $mbit->getUltimaBitacoraUsuario($idusu);
                $idBitacoraCreada = $ultimaBitacora['idbitacora'];

                for ($i = 0; $i < count($actividades_descripcion); $i++) {
                    if (!empty($actividades_descripcion[$i])) {
                        $dataActividad = [
                            'idbitacora'            => $idBitacoraCreada,
                            'descripcion_actividad' => $actividades_descripcion[$i],
                            'fecha_inicio_act'      => $actividades_fecha_inicio[$i] ?? null,
                            'fecha_fin_act'         => $actividades_fecha_fin[$i] ?? null,
                            'evidencia_cumplimiento'=> $actividades_evidencia[$i] ?? '',
                            'archivo_evidencia'     => null 
                        ];
                        $mbit->saveActEp($dataActividad);
                    }
                }

                echo "<script>alert('Bitácora y actividades guardadas correctamente');</script>";
            }
        } else {
            // Editar bitácora existente
            $mbit->edit();
            echo "<script>alert('Bitácora actualizada correctamente');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al guardar: " . $e->getMessage() . "');</script>";
    }

    $pg = $_REQUEST['pg'] ?? '';
    echo "<script>window.location.href='home.php?pg={$pg}';</script>";
    exit;
}

// =======================
// Obtener una bitácora para editar
// =======================
$datOneBit = null;
if ($opera === "edit" && $idbitacora) {
    $datOneBit = $mbit->getOne($idbitacora);
}

// =======================
// Eliminar bitácora
// =======================
if ($opera === "Eliminar" && isset($_GET['idbitacora'])) {
    $mbit->delete($_GET['idbitacora']);
    exit;
}

if ($opera == 'editar_actividades') {
    $idbitacora = $_POST['idbitacora'];
    $estado_actual = $_POST['estado_actual']; 

    $idactividades = $_POST['idactividad'];
    $descripciones = $_POST['descripcion_actividad'];
    $fechas_inicio = $_POST['fecha_inicio_act'];
    $fechas_fin = $_POST['fecha_fin_act'];
    $evidencias = $_POST['evidencia_cumplimiento'];

    $mbit = new Mbit();

    for ($i = 0; $i < count($idactividades); $i++) {
        $datos = [
            'idactividad' => $idactividades[$i],
            'descripcion_actividad' => $descripciones[$i],
            'fecha_inicio_act' => $fechas_inicio[$i],
            'fecha_fin_act' => $fechas_fin[$i],
            'evidencia_cumplimiento' => $evidencias[$i],
        ];
        $mbit->updateActividad($datos);
    }

    if ($estado_actual === 'rechazado') {
        $mbit->actualizarEstadoBitacora($idbitacora, 'pendiente');
    }

    echo "<script>alert('Actividades actualizadas exitosamente'); location.href='home.php?pg=$pg';</script>";
    exit;
}



// =======================
// MODAL de actividades por bitácora
function modalBitacoraDetalles($idbitacora, $numero_bitacora, $pg): string {
    $mbit = new Mbit();
    $actividades = $mbit->getActividadesByBitacora($idbitacora);
    $html = '';

    $html .= '<div class="modal fade" id="bitDet'.$idbitacora.'" tabindex="-1" role="dialog">';
    $html .= '<div class="modal-dialog modal-lg">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header bg-success text-white">';
    $html .= '<h5 class="modal-title">Actividades de la Bitácora #'.$numero_bitacora.'</h5>';
    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>';
    $html .= '</div>';
    $html .= '<div class="modal-body">';

    if ($actividades && count($actividades) > 0) {
        $html .= '<form method="POST" action="home.php?pg='.$pg.'" id="formEditarActividades">';
        $html .= '<input type="hidden" name="opera" value="editar_actividades">';
        $html .= '<input type="hidden" name="idbitacora" value="'.$idbitacora.'">';
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-bordered">';
        $html .= '<thead><tr><th>Descripción</th><th>Inicio</th><th>Fin</th><th>Evidencia</th></tr></thead><tbody>';

        $bitacora = $mbit->getFechasBitacora($idbitacora);
        $fechaMin = $bitacora['fecha_inicio'];
        $fechaMax = $bitacora['fecha_fin'];
        $estadoActual = $bitacora['estado'];
        $html .= '<input type="hidden" name="estado_actual" value="'.$estadoActual.'">';

        foreach ($actividades as $act) {
            $html .= '<tr>';
            $html .= '<td><input type="text" name="descripcion_actividad[]" class="form-control" value="'.htmlspecialchars($act['descripcion_actividad']).'"></td>';
            $html .= '<td><input type="date" name="fecha_inicio_act[]" class="form-control" value="'.$act['fecha_inicio_act'].'" min="'.$fechaMin.'" max="'.$fechaMax.'"></td>';
            $html .= '<td><input type="date" name="fecha_fin_act[]" class="form-control" value="'.$act['fecha_fin_act'].'" min="'.$fechaMin.'" max="'.$fechaMax.'"></td>';
            $html .= '<td><input type="text" name="evidencia_cumplimiento[]" class="form-control" value="'.htmlspecialchars($act['evidencia_cumplimiento']).'"></td>';
            $html .= '<input type="hidden" name="idactividad[]" value="'.$act['idactividad'].'">';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '</div>';
        $html .= '</form>';
    } else {
        $html .= '<p class="text-muted">No hay actividades registradas para esta bitácora.</p>';
    }

    $html .= '</div>';
    $html .= '<div class="modal-footer">';
    if ($actividades && count($actividades) > 0) {
        $html .= '<button type="submit" class="btn btn-primary" form="formEditarActividades">Guardar cambios</button>';
    }
    $html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
    $html .= '</div></div></div></div>';

    return $html;
}


function modalVerObservacion($idbitacora, $numero_bitacora, $observacion) {
    $html = '';

    $html .= '<div class="modal fade" id="modalObs' . $idbitacora . '" tabindex="-1" role="dialog" aria-labelledby="modalObsLabel' . $idbitacora . '" aria-hidden="true">';
        $html .= '<div class="modal-dialog">';
            $html .= '<div class="modal-content">';
                $html .= '<div class="modal-header bg-success text-white">';
                    $html .= '<h5 class="modal-title" id="modalObsLabel' . $idbitacora . '">Observacion - Bitácora #' . $numero_bitacora . '</h5>';
                    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>';
                $html .= '</div>';

                $html .= '<div class="modal-body">';
                    $html .= '<p>' . (!empty($observacion) ? nl2br(htmlspecialchars($observacion)) : '<em>Sin observación registrada.</em>') . '</p>';
                $html .= '</div>';

                $html .= '<div class="modal-footer">';
                    $html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';

    return $html;
}

?>
