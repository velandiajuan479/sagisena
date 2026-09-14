<?php
require_once __DIR__ . '/../models/conexion.php';
require_once __DIR__ . '/../models/mcce.php';

$ope   = $_REQUEST['ope'] ?? null;
$idfic = $_REQUEST['idfic'] ?? null;
$idins = $_REQUEST['idins'] ?? null;
$idcri = $_REQUEST['idcri'] ?? null;
$pg    = $_REQUEST['pg'] ?? '1547';

$nomcris = $_POST['nomcri'] ?? [];
$vscums  = $_POST['vscum'] ?? [];
$vpars   = $_POST['vpar'] ?? [];
$idcris  = $_POST['idcri'] ?? [];
$vncum   = $_POST['vncum'] ?? [];

$calificacion = $_POST['calificacion'] ?? null;
$idusu = $_POST['idusu'] ?? null;
$calif_ind = $_POST['calificacion_ind'] ?? null;

$mcce = new Mcce();
$error_message = null;
$success_message = null;
$form_data = [];
$nomfic = $mcce->getNombreFicha($idfic);

if ($ope === "calificarGlobal" && $idcri) {
    $mcce = new Mcce();
    $mcce->setIdcri($idcri);
    $criterio = $mcce->getOne();

    if (empty($criterio)) {
        $error_message = "No se encontró el criterio seleccionado.";
    } else {
        $crit = $criterio[0];
        $valor = 0;

        $calif_global = $_POST['calif_global'] ?? null;

        if ($calif_global === 'cumple') {
            $valor = (int)$crit['vscum'];
        } elseif ($calif_global === 'parcial') {
            $valor = (int)$crit['vpar'];
        } elseif ($calif_global === 'nocumple') {
            $valor = 0;
        } else {
            $error_message = "Debe seleccionar una calificación global.";
        }

        if (!isset($error_message)) {
            $conexion = (new Conexion())->get_conexion();

            // Obtener todos los aprendices de la ficha
            $mcceFicha = new Mcce();
            $mcceFicha->setIdins($idins);
            $aprendices = $mcceFicha->getAprendicesByFicha();

            foreach ($aprendices as $apr) {
                $idusuGlobal = $apr['idusu'];

                // Verificar si ya existe calificación
                $sqlCheck = "SELECT COUNT(*) as count FROM calcri WHERE idcri = :idcri AND idusu = :idusu";
                $stmtCheck = $conexion->prepare($sqlCheck);
                $stmtCheck->bindParam(':idcri', $idcri);
                $stmtCheck->bindParam(':idusu', $idusuGlobal);
                $stmtCheck->execute();
                $count = $stmtCheck->fetch(PDO::FETCH_ASSOC)['count'];

                try {
                    if ($count > 0) {
                        $sqlUpdate = "UPDATE calcri SET calif = :calif WHERE idcri = :idcri AND idusu = :idusu";
                        $stmtUpdate = $conexion->prepare($sqlUpdate);
                        $stmtUpdate->bindParam(':calif', $valor);
                        $stmtUpdate->bindParam(':idcri', $idcri);
                        $stmtUpdate->bindParam(':idusu', $idusuGlobal);
                        $stmtUpdate->execute();
                    } else {
                        $sqlInsert = "INSERT INTO calcri (idcri, idusu, calif) VALUES (:idcri, :idusu, :calif)";
                        $stmtInsert = $conexion->prepare($sqlInsert);
                        $stmtInsert->bindParam(':idcri', $idcri);
                        $stmtInsert->bindParam(':idusu', $idusuGlobal);
                        $stmtInsert->bindParam(':calif', $valor);
                        $stmtInsert->execute();
                    }
                } catch (PDOException $e) {
                    error_log("Error al guardar calificación global: " . $e->getMessage());
                    $error_message = "Error al guardar la calificación global.";
                    break;
                }
            }

            if (!isset($error_message)) {
                $success_message = "Calificación global registrada correctamente.";
            }
        }
    }
}

if ($ope === "updateCriterio" && $idusu && $calificacion && $idcri) {
    $mcce = new Mcce();
    $mcce->setIdcri($idcri);
    $criterio = $mcce->getOne();

    if (empty($criterio)) {
        $error_message = "No se encontró el criterio seleccionado.";
    } else {
        $crit = $criterio[0];
        $valor = 0;

        if ($calificacion === 'cumple') {
            $valor = (int)$crit['vscum'];
        } elseif ($calificacion === 'parcial') {
            $valor = (int)$crit['vpar'];
        } elseif ($calificacion === 'nocumple') {
            $valor = 0;
        }

        $conexion = (new Conexion())->get_conexion();

        // Verificar si ya existe la calificación
        $sqlCheck = "SELECT COUNT(*) as count FROM calcri WHERE idcri = :idcri AND idusu = :idusu";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->bindParam(':idcri', $idcri);
        $stmtCheck->bindParam(':idusu', $idusu);
        $stmtCheck->execute();
        $count = $stmtCheck->fetch(PDO::FETCH_ASSOC)['count'];

        try {
            if ($count > 0) {
                // Actualizar
                $sqlUpdate = "UPDATE calcri SET calif = :calif WHERE idcri = :idcri AND idusu = :idusu";
                $stmtUpdate = $conexion->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':calif', $valor);
                $stmtUpdate->bindParam(':idcri', $idcri);
                $stmtUpdate->bindParam(':idusu', $idusu);
                $stmtUpdate->execute();
            } else {
                // Insertar nuevo
                $sqlInsert = "INSERT INTO calcri (idcri, idusu, calif) VALUES (:idcri, :idusu, :calif)";
                $stmtInsert = $conexion->prepare($sqlInsert);
                $stmtInsert->bindParam(':idcri', $idcri);
                $stmtInsert->bindParam(':idusu', $idusu);
                $stmtInsert->bindParam(':calif', $valor);
                $stmtInsert->execute();
            }

            $success_message = "Calificación registrada correctamente.";
        } catch (PDOException $e) {
            error_log("Error al guardar calificación: " . $e->getMessage());
            $error_message = "Error al guardar la calificación.";
        }
    }
}


    if (empty($criterios)) {
     
    } else {
        $conexion = (new Conexion())->get_conexion();

        foreach ($criterios as $crit) {
            $valor = 0;
            if ($calificacion === 'cumple') {
                $valor = (int)$crit['vscum'];
            } elseif ($calificacion === 'parcial') {
                $valor = (int)$crit['vpar'];
            } elseif ($calificacion === 'nocumple') {
                $valor = 0;
            }

            // Verificar si ya existe la calificación para este criterio y usuario
            $sqlCheck = "SELECT COUNT(*) as count FROM calcri WHERE idcri = :idcri AND idusu = :idusu";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':idcri', $crit['idcri']);
            $stmtCheck->bindParam(':idusu', $idusu);
            $stmtCheck->execute();
            $count = $stmtCheck->fetch(PDO::FETCH_ASSOC)['count'];

            try {
                if ($count > 0) {
                    // Actualizar registro existente
                    $sqlUpdate = "UPDATE calcri SET calif = :calif WHERE idcri = :idcri AND idusu = :idusu";
                    $stmtUpdate = $conexion->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':calif', $valor);
                    $stmtUpdate->bindParam(':idcri', $crit['idcri']);
                    $stmtUpdate->bindParam(':idusu', $idusu);
                    $stmtUpdate->execute();
                } else {
                    // Insertar nuevo registro
                    $sqlInsert = "INSERT INTO calcri (idcri, idusu, calif) VALUES (:idcri, :idusu, :calif)";
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->bindParam(':idcri', $crit['idcri']);
                    $stmtInsert->bindParam(':idusu', $idusu);
                    $stmtInsert->bindParam(':calif', $valor);
                    $stmtInsert->execute();
                }
            } catch (PDOException $e) {
                error_log("Error al guardar calificación global: " . $e->getMessage());
                $error_message = "Error al guardar la calificación global.";
                break;
            }
        }

        if (!isset($error_message)) {
            $success_message = "Calificación global registrada correctamente.";
        }
    }



// ========================================
// GUARDAR CRITERIOS NUEVOS O EDITADOS
// ========================================
if ($ope === "save" && is_array($nomcris)) {
    if (count($nomcris) !== count($vscums) || count($nomcris) !== count($vpars)) {
        $error_message = "Datos incompletos recibidos.";
    } else {
        $mcce = new Mcce();
        $mcce->setIdins($idins);

        // Obtener la suma actual de vscum en DB
        $vscumDB = $mcce->getValorTotal();

        // Ajustar vscumDB para excluir valores de los criterios que se están editando
        foreach ($idcris as $idc) {
            if (!empty($idc)) {
                $mcceEdit = new Mcce();
                $mcceEdit->setIdcri($idc);
                $old = $mcceEdit->getOne();
                if (!empty($old)) {
                    $vscumDB -= (int)$old[0]['vscum'];
                }
            }
        }

        // Sumar los nuevos valores
        $totalNuevo = array_sum(array_map('intval', $vscums));
        $totalFinal = $vscumDB + $totalNuevo;

        if ($totalFinal > 100) {
            $error_message = "No se pueden agregar más criterios, la suma excede 100.";
        } elseif ($totalNuevo != 100) {
            $error_message = "La suma de los valores 'Si Cumple' debe ser exactamente 100. Actualmente es $totalNuevo.";
        } else {
            for ($i = 0; $i < count($nomcris); $i++) {
                $mcce = new Mcce();
                $mcce->setIdcri($idcris[$i] ?? null);
                $mcce->setNomcri(trim($nomcris[$i]));
                $mcce->setVscum((int)$vscums[$i]);
                $mcce->setVpar((int)$vpars[$i]);
                $mcce->setVncum(0);
                $mcce->setIdins($idins);
                $mcce->setIdfic($idfic);

                // Si idcri existe, editamos; si no, creamos nuevo
                $result = !empty($idcris[$i]) ? $mcce->edit() : $mcce->save();
                if (!$result) {
                    $error_message = "Error al guardar el criterio: " . htmlspecialchars($nomcris[$i]);
                    break;
                }
            }
        }

        // Conservar los datos en caso de error
        if (isset($error_message)) {
            for ($i = 0; $i < count($nomcris); $i++) {
                $form_data[] = [
                    'idcri' => $idcris[$i] ?? '',
                    'nomcri' => $nomcris[$i],
                    'vscum' => $vscums[$i],
                    'vpar' => $vpars[$i]
                ];
            }
        }
    }
}

// ========================================
// ELIMINAR CRITERIO
// ========================================
if ($ope === "del" && $idcri) {
    $mcce = new Mcce();
    $mcce->setIdcri($idcri);
    $success_message = $mcce->del() ? "Criterio eliminado correctamente." : "Error al eliminar el criterio.";
}

// ========================================
// CARGAR DATOS PARA EDITAR UN CRITERIO
// ========================================
if ($ope === "edi" && $idcri) {
    $mcce = new Mcce();
    $mcce->setIdcri($idcri);
    $crit = $mcce->getOne();

    if (!empty($crit)) {
        $idins = $crit[0]['idins'];
        $mcce->setIdins($idins);
        $dtOne = $mcce->getByInstrumento(); // Devuelve todos los criterios del instrumento
    } else {
        $dtOne = null;
        $error_message = "No se encontró el criterio seleccionado.";
    }
}
    

// ========================================
// CARGAR MODAL PARA CALIFICAR CRITERIO
if ($ope === "getEditForm" && $idcri) {
    $mcce = new Mcce();
    $mcce->setIdcri($idcri);
    $dato = $mcce->getOne();

    if (!empty($dato)) {
        $idfic = $dato[0]['idfic'] ?? null;
        $idins = $dato[0]['idins'] ?? null; // Se asegura el valor para pasar al modal

        if (!$idfic) {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se encontró la ficha.',
                confirmButtonText: 'Aceptar'
            });
        </script>";
        exit;
        }

        $mcceFicha = new Mcce();
        $mcceFicha->setIdfic($idfic);

        $aprendices = $mcceFicha->getAprendicesByFicha();

        if (empty($aprendices)) {
            echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'No se encontraron aprendices para esta ficha.',
                confirmButtonText: 'Aceptar'
            });
        </script>";
        exit;
        }

        modal(
            $dato[0]['idcri'],
            $dato[0]['nomcri'],
            $pg,
            $dato[0]['vscum'],
            $dato[0]['vpar'],
            $dato[0]['vncum'],
            $aprendices,
            '',
            '',
            $idins // SE AGREGA AQUI PARA PASARLO AL MODAL
        );
    } else {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el criterio.',
            confirmButtonText: 'Aceptar'
        });
    </script>";
    }

    exit;
}


// ========================================
// MOSTRAR TODOS LOS CRITERIOS EXISTENTES
// ========================================
$mcce = new Mcce();
$mcce->setIdins($idins);
$dat = $idins ? $mcce->getByInstrumento() : [];

function modal($idcri, $nomcri, $pg, $vscum, $vpar, $vncum, $aprendices, $calificacionGlobal = '', $calificacionIndividual = '', $idins = null) {
    $html = '';
    $html .= '<div class="modal fade" id="myModal' . $idcri . '" tabindex="-1" aria-hidden="true">';
        $html .= '<div class="modal-dialog modal-xl modal-dialog-scrollable">';
            $html .= '<div class="modal-content fs-4">';
                $html .= '<div class="modal-header">';
                    $html .= '<h3 class="modal-title fs-1">Calificar Criterio</h3>';
                    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                $html .= '</div>';

                // Formulario único
                $html .= '<form action="home.php?pg=' . $pg . '" method="POST">';
                    $html .= '<input type="hidden" name="idcri" value="' . $idcri . '">';
                    $html .= '<input type="hidden" name="idins" value="' . $idins . '">';

                    $html .= '<div class="modal-body border-top pt-4">';
                    
                        // Calificación individual
                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label for="calificacion" class="fs-4">Calificación Individual</label>';
                            $html .= '<select name="calificacion" class="form-control fs-4">';
                                $html .= '<option value="">Seleccione una opción</option>';
                                $html .= '<option value="cumple">Cumple</option>';
                                $html .= '<option value="parcial">Cumple parcialmente</option>';
                                $html .= '<option value="nocumple">No cumple</option>';
                            $html .= '</select>';
                        $html .= '</div>';

                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label for="idusu" class="fs-4">Seleccionar Aprendiz</label>';
                            $html .= '<select name="idusu" class="form-control fs-4">';
                                $html .= '<option value="">Seleccione un aprendiz</option>';
                                foreach ($aprendices as $apr) {
                                    $html .= '<option value="' . htmlspecialchars($apr['idusu']) . '">' . htmlspecialchars($apr['nomusu']) . '</option>';
                                }
                            $html .= '</select>';
                        $html .= '</div>';

                        // Botón calificar individual
                        $html .= '<div class="mb-4">';
                            $html .= '<input type="hidden" name="ope" value="updateCriterio">';
                            $html .= '<button type="submit" name="ope" value="updateCriterio" class="btn btn-primary fs-4">Guardar Individual</button>';
                        $html .= '</div>';

                        // Calificación global (debe ir al final)
                        $html .= '<hr>';
                        $html .= '<div class="form-group mb-3">';
                            $html .= '<label for="calif_global" class="fs-4">Calificación Global (Todos los Aprendices)</label>';
                            $html .= '<select name="calif_global" class="form-control fs-4">';
                                $html .= '<option value="">Seleccione una opción</option>';
                                $html .= '<option value="cumple">Cumple</option>';
                                $html .= '<option value="parcial">Cumple parcialmente</option>';
                                $html .= '<option value="nocumple">No cumple</option>';
                            $html .= '</select>';
                        $html .= '</div>';

                        // Botón calificar global
                        $html .= '<div class="modal-footer">';
                            $html .= '<button type="submit" name="ope" value="calificarGlobal" class="btn btn-success fs-4">Calificar Global</button>';
                        $html .= '</div>';

                    $html .= '</div>'; // modal-body
                $html .= '</form>';

            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';

    echo $html;
}
?>