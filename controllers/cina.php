<?php
require_once('models/mina.php');
require_once('models/macta.php');

$idusu = $_POST['idusu'] ?? null;
$comina = $_POST['comina'] ?? null;
$idval = $_POST['idval'] ?? null;
$ope = $_REQUEST['ope'] ?? null;
$arc = $_FILES['arc']['name'] ?? null;

$mina = new Mina();

$pg = 1311;
$datAllgi = null;
$datAsis = null;
$datInasi = null;
$datNove = null;

date_default_timezone_set('America/Bogota');
$fec = date("Y-m-d");
$fechor = date("Y-m-d H:i:s");
$feccom = date('Ymdhis');

// Obtener idfic desde POST, REQUEST o mantener en sesión
if (!empty($_POST['idfic1'])) {
    $_SESSION['idfic'] = $_POST['idfic1'];
} elseif (!empty($_POST['idfic2'])) {
    $_SESSION['idfic'] = $_POST['idfic2'];
} elseif (!empty($_REQUEST['idfic'])) {
    $_SESSION['idfic'] = $_REQUEST['idfic'];
}

$idfic = $_SESSION['idfic'] ?? null;

// Obtener datos de gráfica
$gaf = $mina->getGraphic($idfic);

// Guardar inasistencia
if ($ope === "save" && isset($_POST['idusu'], $_POST['horasina'], $_POST['chkgd'], $_POST['comina'])) {
    $idusu = $_POST['idusu'];
    $horasina = $_POST['horasina'];
    $chkgd = $_POST['chkgd'];
    $comina = $_POST['comina'];
    $fecina = $_POST['fecina'] ?? $fec;
    $fecreg = $_POST['fecreg'] ?? $fechor;

    foreach ($idusu as $i => $id) {
        if (in_array($id, $chkgd)) {
            $mina->setIdusu($id);
            $mina->setFecina($fecina);
            $mina->setFecreg($fecreg);
            $mina->setHorasina($horasina[$i]);
            $mina->setIdfic($idfic);
            $mina->setComina($comina[$i] ?? null);
            $mina->ins();
        }
    }

    $pg = $_GET['pg'] ?? 1311;
    header("Location: home.php?pg=$pg&idfic=$idfic&updated=1");
    exit;
}


// Guardar acta de deserción
if ($ope === "InAct" && $idfic) {
    $conact = $_POST['conact'] ?? null;
    $coorusu = $_POST['coorusu'] ?? null;
    $idusu = $_POST['idusu'] ?? null;

    $macta = new Macta();
    $nac = $macta->getNacGen($idfic);

    if ($nac) {
        $macta->setNac($nac['can']);
        $macta->setTipact(201);
        $macta->setFecact($fechor);
        $macta->setConact($conact);
        $macta->setIdusu($_SESSION["idusu"]);
        $macta->setCoorusu($coorusu);
        $macta->save();

        $idact = $macta->getOneIdact();
        if ($idact) {
            $macta->setIdact($idact['idact']);
            $macta->setDesacde("Enviar esta acta a Coordinación Misional y Coordinación académica");
            $macta->setFecacde($fechor);
            $macta->saveAcDe();

            $macta->setFecasi($fechor);
            $macta->saveAsis();
            $macta->setIdusu($coorusu);
            $macta->setFecasi(null);
            $macta->saveAsis();

            $macta->setIdusu($idusu);
            $macta->setIdfic($idfic);
            $macta->setFecregdec($fechor);
            $macta->setObsdec("Deserción solicitada");
            $macta->saveDes();
        }
    }
}


// Guardar foto

$datNove = [];

if ($idfic) {
    $datAllgi = $mina->selAll($idfic);         
    $datAsis = $mina->selAsistieron($idfic);   
    $datInasi = $mina->selInasistHoy($idfic);
    $datNove = $mina->selNove($idfic);
    $dfi = $mina->getFicha();               
} else {
    $dfi = $mina->getFicha();
}


if (isset($_POST['ope']) && $_POST['ope'] === 'cgImg') {
    $idusu = $_POST['idusu'] ?? null;

    if (!$idusu || !isset($_FILES['arc']) || $_FILES['arc']['error'] !== 0) {
        echo "Falta el ID de usuario o el archivo no se subió correctamente.";
        exit;
    }

    $nombreArchivo = "Ina" . $idusu . date("YmdHis");
    $rutaDestino = "img/inasi";

    if (!file_exists($rutaDestino)) {
        mkdir($rutaDestino, 0777, true);
    }

    $nombreGuardado = opti($_FILES['arc'], $nombreArchivo, $rutaDestino, "");

    if ($nombreGuardado != '') {
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();

        $sql = "INSERT INTO imagen_usuario (idusu, nomimg) VALUES (:idusu, :nomimg)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idusu", $idusu);
        $stmt->bindParam(":nomimg", $nombreGuardado);

        if ($stmt->execute()) {
            header("Location: home.php?pg=$pg&msg=ok");
            exit;
        } else {
            echo "Error al guardar el nombre de la imagen en la base de datos.";
        }
    } else {
        echo "Error al subir o procesar la imagen.";
    }
}

// PARA CAMBIAR ESTADO DEL USUARIO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ope']) && $_POST['ope'] === "infor") {

	$idusu = $_POST['idusu'] ?? null;
	$actusu = $_POST['actusu'] ?? null;

	if ($idusu && $actusu) {
		$modelo = new Mina();
		$ok = $modelo->camactusu($idusu, $actusu);

		if ($ok) {
			echo '<script>alert("Estado actualizado correctamente."); window.location.href="home.php?pg=' . $_GET['pg'] . '";</script>';
		} else {
			echo '<script>alert("No se pudo actualizar el estado.");</script>';
		}
	} else {
		echo '<script>alert("Faltan datos para actualizar el estado.");</script>';
	}
}

// Guardar llamado de atención
if (isset($_GET['pg']) && $_GET['pg'] == 'guardar_llamado') {
    $llamado = new Mina();

    $idusu = $_POST['idusu'] ?? null;
    $tipllam = $_POST['tipllam'] ?? null;
    $obsllam = $_POST['obsllam'] ?? null;

    if ($idusu && $tipllam) {
        date_default_timezone_set('America/Bogota');
        $fecllam = date('Y-m-d H:i:s');
        $llamado->guardarLlamado($idusu, $tipllam, $obsllam, $fecllam);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipllam']) && isset($_POST['idusu'])) {
    $llamado = new Mina();

    $idusu = $_POST['idusu'];
    $tipllam = $_POST['tipllam'];
    $obsllam = $_POST['obsllam'] ?? null;

    date_default_timezone_set('America/Bogota');
    $fecllam = date('Y-m-d H:i:s');
    $llamado->guardarLlamado($idusu, $tipllam, $obsllam, $fecllam);

}


if (isset($_GET['img']) && isset($_GET['idusu'])) {
    require_once(__DIR__ . '/../models/conexion.php');

    $img = $_GET['img'];
    $idusu = $_GET['idusu'];
    $pg = $_GET['pg'] ?? 'default_pg';

    $ruta = __DIR__ . "/../img/inasi/" . $img;

    // Eliminar archivo del servidor
    if (file_exists($ruta)) {
        unlink($ruta);
    }

    // Eliminar registro de la base de datos
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();

    $sql = "DELETE FROM imagen_usuario WHERE nomimg = :nomimg AND idusu = :idusu";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nomimg', $img);
    $stmt->bindParam(':idusu', $idusu);
    $stmt->execute();

    // Volver a home.php después de eliminar
    header("Location: ../home.php?pg=$pg&msg=img_deleted");
    exit;
}


function modActMod($id, $nom, $fic, $nomfic, $pg){
	$html = '';
	$html .= '<div class="modal" id="CrearActModal'.$id.'" tabindex="-1" role="dialog">';
		$html .= '<div class="modal-dialog">';
			$html .= '<form action="home.php?pg='.$pg.'" method="POST">';
				$html .= '<div class="modal-content">';
					$html .= '<div class="modal-header">';
						$html .= '<h3>Acta de Deserción</h3>';
						$html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
					$html .= '</div>';
					$html .= '<div class="modal-body">';
						$html .= '<h5>';
							$html .= 'Aprendiz a desertar: '.$nom.'<br><br>';
							$html .= 'Ficha: '.$fic." ".$nomfic;
						$html .= '</h5><br>';
						$html .= '<div class="row">';
							$html .= '<div class="form-group col-md-12">';
								$html .= '<label for="conact">Conclusiones</label>';
								$html .= '<textarea name="conact" id="conact" class="form-control">';
								$html .= '</textarea>';
							$html .= '</div>';
							$html .= '<div class="form-group col-md-12">';
								$html .= '<label for="coorusu">Coordinador</label>';
								$html .= '<select name="coorusu" id="coorusu" class="form-select">';
									$html .= '<option value="1">Robin</option>';
								$html .= '</select>';
							$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="modal-footer">';
						$html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
						$html .= '<input type="submit" class="btn btn-primary" value="Guardar">';
						$html .= '<input type="hidden" name="idusu" value="'.$id.'">';
						$html .= '<input type="hidden" name="idfic" value="'.$fic.'">';
						$html .= '<input type="hidden" name="ope" value="InAct">';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</form>';
		$html .= '</div>';
	$html .= '</div>';
	return $html;
}

function modllamado($id, $nom, $fic, $nomfic, $pg){
	$html = '';
	$html .= '<div class="modal" id="modllamado'.$id.'" tabindex="-1" role="dialog">';
		$html .= '<div class="modal-dialog">';
			$html .= '<form action="home.php?pg='.$pg.'" method="POST">';
				$html .= '<div class="modal-content">';
					$html .= '<div class="modal-header">';
						$html .= '<h3>Llamado de atención</h3>';
						$html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
					$html .= '</div>';
					$html .= '<div class="modal-body">';

						$html .= '<h5>';
							$html .= 'Aprendiz al que se le realiza el llamado: '.$nom.'<br><br>';
							$html .= 'Ficha: '.$fic." ".$nomfic;
						$html .= '</h5><br>';

						$html .= '<div class="form-group col-md-12">';
							$html .= '<label for="obsllam">Observaciones</label>';
							$html .= '<textarea name="obsllam" id="obsllam" class="form-control"></textarea>';
						$html .= '</div>';

						$html .= '<input type="hidden" name="idusu" value="'.$id.'">';

						$html .= '<br>';
						$html .= '<div class="d-flex gap-2">';

						$html .= '<button type="submit" name="tipllam" value="1" class="btn text-white border-0 px-3 py-2 rounded-pill d-inline-flex align-items-center justify-content-center" ';
							$html .= 'style="background-color: rgb(255, 193, 7);';
							$html .= 'font-size: 12px; ';
							$html .= 'box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); ';
							$html .= 'transition: all 0.3s ease; ';
							$html .= 'min-width: 160px;">';
							$html .= '<i class="fa-solid fa-comment-dots fa-sm me-2"></i>';
							$html .= '<span class="fw-semibold">Verbal</span>';
						$html .= '</button>';

						$html .= '<button type="submit" name="tipllam" value="2" class="btn text-white border-0 px-3 py-2 rounded-pill d-inline-flex align-items-center justify-content-center" ';
							$html .= 'style="background-color: rgb(220, 53, 69); ';
							$html .= 'font-size: 12px; ';
							$html .= 'box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); ';
							$html .= 'transition: all 0.3s ease; ';
							$html .= 'min-width: 160px;">';
							$html .= '<i class="fa-solid fa-file-lines fa-sm me-2"></i>';
							$html .= '<span class="fw-semibold">Escrito</span>';
						$html .= '</button>';

						$html .= '</div>';

					$html .= '</div>';
					$html .= '<div class="modal-footer">';
						$html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</form>';
		$html .= '</div>';
	$html .= '</div>';
	return $html;
}



function modnove($id, $nom, $fic, $nomfic, $pg){
	$html = '';
	$html .= '<div class="modal" id="modnove'.$id.'" tabindex="-1" role="dialog">';
		$html .= '<div class="modal-dialog">';
			$html .= '<form action="home.php?pg='.$pg.'" method="POST">';
				$html .= '<div class="modal-content">';
					$html .= '<div class="modal-header">';
						$html .= '<h3>Novedad</h3>';
						$html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
					$html .= '</div>';
					$html .= '<div class="modal-body">';
						$html .= '<h5>';
							$html .= 'Aprendiz al que se le hace la novedad: '.$nom.'<br><br>';
							$html .= 'Ficha: '.$fic." ".$nomfic;
						$html .= '</h5><br>';
						$html .= '<div class="mb-3">';
						$html .= '<label for="actusu" class="form-label">Estado de la novedad</label>';
							$html .= '<select class="form-select" name="actusu" id="actusu">';
								$html .= '<option value="3">Deserción</option>';
								$html .= '<option value="4">Cancelado</option>';
								$html .= '<option value="5">Traslado</option>';
								$html .= '<option value="6">Retiro Voluntario</option>';
							$html .= '</select>';
						$html .= '</div>';

					$html .= '</div>';
					$html .= '<div class="modal-footer">';
						$html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
						$html .= '<input type="submit" class="btn btn-primary" value="Guardar">';
						$html .= '<input type="hidden" name="idusu" value="'.$id.'">';
						$html .= '<input type="hidden" name="ope" value="infor">';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</form>';
		$html .= '</div>';
	$html .= '</div>';
	return $html;
}

$hist = $mina->getHist($idusu);
if (!is_array($hist)) {
    echo "<pre>getHist devolvió algo que no es array:</pre>";
    var_dump($hist);
}
$llam = $mina->getLlam($idusu);

function modhisina($idusu, $nom, $fic, $nomfic, $pg, $hist, $llam) {
	$html = '';
	$html .= '<div class="modal" id="modhisina'.$idusu.'" tabindex="-1" role="dialog">';
		$html .= '<div class="modal-dialog">';
		$html .= '<form action="home.php?pg='.$pg.'" method="POST">';
		$html .= '<div class="modal-content">';
		$html .= '<div class="modal-header">';
		$html .= '<h3>Historial</h3>';
		$html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
		$html .= '</div>';
		$html .= '<div class="modal-body">';
		$html .= '<h5>Historial de: '.$nom.'<br><br>Ficha: '.$fic." ".$nomfic.'</h5>';

		$html .= '<h3>Inasistencias</h3><br>';

		if (!empty($hist)) {
			$html .= '<table class="table table-striped" style="width:100%">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th></th>';
			$html .= '<th>Horas registradas</th>';
			$html .= '<th>Fecha</th>';
			$html .= '<th>Comentario</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ($hist as $h) {
				$comentario = (trim($h['comina']) !== '') ? $h['comina'] : 'Sin comentario';

				$html .= '<tr>';
				$html .= '<td></td>';
				$html .= '<td>'.$h['horasina'].'</td>';
				$html .= '<td>'.$h['fecina'].'</td>';
				$html .= '<td>'.$comentario.'</td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '<tfoot>';
			$html .= '<tr>';
			$html .= '<th></th>';
			$html .= '<th>Horas registradas</th>';
			$html .= '<th>Fecha</th>';
			$html .= '<th>Comentario</th>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
		} else {
			$html .= '<div class="alert alert-warning" role="alert">';
			$html .= 'No hay historial de inasistencias registrado.';
			$html .= '</div>';
		}


		$html .= '<h3>Llamados de Atención</h3><br>';

		if (!empty($llam)) {
			$html .= '<table class="table table-striped" style="width:100%">';
			$html .= '<thead>';
			$html .= '<tr>';
			$html .= '<th></th>';
			$html .= '<th>Tipo de llamado registrados</th>';
			$html .= '<th>Fecha</th>';
			$html .= '<th>Observaciones</th>';
			$html .= '</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';

			foreach ($llam as $l) {
				$tipo = ($l['tipllam'] == 1) ? 'Verbal' : (($l['tipllam'] == 2) ? 'Escrito' : 'Desconocido');
				$observacion = (trim($l['obsllam']) !== '') ? $l['obsllam'] : 'Sin observación';

				$html .= '<tr>';
				$html .= '<td></td>';
				$html .= '<td>'.$tipo.'</td>';
				$html .= '<td>'.$l['fecllam'].'</td>';
				$html .= '<td>'.$observacion.'</td>';
				$html .= '<td><i class="fa fa-solid fa-print fa-2x" style="font-size: 30px;" title="Imprimir"></i></td>';
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '<tfoot>';
			$html .= '<tr>';
			$html .= '<th></th>';
			$html .= '<th>Tipo de llamado registrados</th>';
			$html .= '<th>Fecha</th>';
			$html .= '<th>Observaciones</th>';
			$html .= '</tr>';
			$html .= '</tfoot>';
			$html .= '</table>';
		} else {
			$html .= '<div class="alert alert-info" role="alert">';
			$html .= 'No hay llamados de atención registrados.';
			$html .= '</div>';
		}



		$html .= '</div>';
		$html .= '<div class="modal-footer">';
		$html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</form>';
		$html .= '</div>';
	$html .= '</div>';
	return $html;
}

function modalCarImg($id, $nom, $pg){
    require_once("models/macta.php"); 

    $macta = new Macta();
    $imagenes = $macta->obtenerImagenesUsuario($id);

    $txt = '';
    $txt .= '<div class="modal fade" id="mdcgImg'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">';
      $txt .= '<div class="modal-dialog">';
        $txt .= '<div class="modal-content">';
          $txt .= '<div class="modal-header">';
            $txt .= '<h2 class="modal-title fs-5" id="exampleModalLabel">Registro de Inasistencia</h2>';
            $txt .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
          $txt .= '</div>';
          $txt .= '<form action="home.php?pg='.$pg.'" method="POST" enctype="multipart/form-data">';
	          $txt .= '<div class="modal-body">';
	          	$txt .= '<div class="row">';
	          		$txt .= '<div class="form-group col-md-12">';
			            $txt .= $nom.'<br><br>';
		            $txt .= '</div>';
		            $txt .= '<div class="form-group col-md-12">';
		            	$txt .= '<label for="arc">Cargar imagen de SENASOFIAPLUS</label>';
			            $txt .= '<input type="file" name="arc" id="arc" class="form-control">';
			            $txt .= '<input type="hidden" name="idusu" value="'.$id.'">';
			            $txt .= '<input type="hidden" name="ope" value="cgImg">';
		            $txt .= '</div>';
	            $txt .= '</div>';

                if (!empty($imagenes)) {
    $txt .= '<hr><strong>Imágenes cargadas:</strong><br><div class="row">';
    foreach ($imagenes as $img) {
        $url = 'img/inasi/'.$img['nomimg'];
        $txt .= '<div class="col-12" style="position:relative; margin-bottom:15px;">';
        
            $txt .= '<a href="'.$url.'" target="_blank" style="display:inline-block;">'.$url.'</a>';

    
			$txt .= '<a href="controllers/cina.php?img='.$img['nomimg'].'&idusu='.$id.'&pg='.$pg.'" ';
			$txt .= 'style="position:absolute; top:50%; right:0; transform:translateY(-50%); color:green; font-size:20px; font-weight:bold; text-decoration:none;" ';
			$txt .= 'onclick="return confirm(\'¿Eliminar esta imagen?\')">×</a>';

        $txt .= '</div>';
    }
    $txt .= '</div>';
}


	          $txt .= '</div>';
	          $txt .= '<div class="modal-footer">';
	            $txt .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>';
	            $txt .= '<button type="submit" class="btn btn-primary">Cargar Imagen</button>';
	          $txt .= '</div>';
	      $txt .= '</form>';
        $txt .= '</div>';
      $txt .= '</div>';
    $txt .= '</div>';
    return $txt;
}
?>