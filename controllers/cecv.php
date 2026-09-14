<?php

require_once 'models/mecv.php';

$mecv = new Mecv();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:NULL;
$ndocusu = isset($_POST['ndocusu']) ? $_POST['ndocusu']:NULL;
$nomusu = isset($_POST['nomusu']) ? $_POST['nomusu']:NULL;
$idper = isset($_POST['idper']) ? $_POST['idper']:NULL;
$idfic = isset($_REQUEST['idfic']) ? $_REQUEST['idfic']:NULL;
$pasusu = isset($_POST['pasusu']) ? $_POST['pasusu']:NULL;
$idcen = isset($_POST['idcen']) ? $_POST['idcen']:NULL;
$actusu = isset($_POST['actusu']) ? $_POST['actusu']:NULL;
$emausu = isset($_POST['emausu']) ? $_POST['emausu']:NULL;
$telcan = isset($_POST['telcan']) ? $_POST['telcan']:NULL;
$noca = isset($_POST['noca']) ? $_POST['noca']:NULL;
$fotcan = isset($_POST['fotcan']) ? $_POST['fotcan']:NULL;


$idficfil = isset($_REQUEST['idficfil']) ? $_REQUEST['idficfil']:NULL;
$nodocfil = isset($_REQUEST['nodocfil']) ? $_REQUEST['nodocfil']:80546098;

$foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name']:NULL;

$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
if($foto){
    $fotcan = opti($_FILES['foto'], $idusu, 'fcan', "");
}

$ips = isset($_POST['inico']) ? $_POST['inico']:NULL;
$fps = isset($_POST['finco']) ? $_POST['finco']:NULL;

$datOne = NULL;

if($pg<>1324){
	$pg = 1117;
}else{
	$idusu = $_SESSION["idusu"];
}


$mecv->setIdusu($idusu);
//Insertar
if($opera=="save"){
	$mecv->setNdocusu($ndocusu);
	$mecv->setNomusu($nomusu);
	$mecv->setIdper($idper);
	$mecv->setPasusu($pasusu);
	$mecv->setEmausu($emausu);
	$mecv->setIdcen($idcen);
	$mecv->setActusu($actusu);
	$mecv->setFotcan($fotcan);
	$mecv->setTelcan($telcan);
	$mecv->setNoca($noca);
	if(!$idusu){
		$mecv->save();
	}else{
		$mecv->edi();
	}
	
}
//Actualizar
if($opera=="edit"){
	$datOne=$mecv->getOne();
}
if($opera=="CamCon"){
	if($ips AND $fps){
		// $apr = $musu->selApren();
		// foreach ($apr as $ap) {
		// 	$pas = $ips.$ap['ndocusu'].$fps;
		// 	//echo $pas."<br>";
			$mecv->updPasc($ips,$fps);
		// }
	}
	$idusu="";
}
//Actualizacion
if($opera=="InUP"){
    // se elimina los perfiles secundarios antes de todo
    if($idusu) $mecv->delUxP();
    
    // solo se asignan nuevos perfiles si se ajusta
    if($idper) {
        foreach($idper AS $idx){
            if($idx){
                $mecv->setIdper($idx);
                $mecv->insUxP();
            }
        }
    }
    // se actualiza el perfil principal si es diferente
    $perfilPrincipal = isset($_POST['perfil_principal']) ? $_POST['perfil_principal'] : null;
    if($perfilPrincipal) {
        $mecv->setIdper($perfilPrincipal);
        $mecv->edi(); // se actualiza el perfil principal en la tabla usuario
    }
}

// Eliminar candidato vocero 
if($opera=="remove_vocero" && $idusu){
    try {
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $conexion->beginTransaction();
        
        $sqlDelete = "DELETE FROM usupef WHERE idusu = :idusu AND idper = 13";
        $resultDelete = $conexion->prepare($sqlDelete);
        $resultDelete->bindParam(":idusu", $idusu);
        $resultDelete->execute();
        
        $sqlCheckMain = "SELECT idper FROM usuario WHERE idusu = :idusu AND idper = 13";
        $resultCheckMain = $conexion->prepare($sqlCheckMain);
        $resultCheckMain->bindParam(":idusu", $idusu);
        $resultCheckMain->execute();
        
        if($resultCheckMain->rowCount() > 0) {
            $sqlUpdate = "UPDATE usuario SET idper = 8, noca = NULL WHERE idusu = :idusu";
            $resultUpdate = $conexion->prepare($sqlUpdate);
            $resultUpdate->bindParam(":idusu", $idusu);
            $resultUpdate->execute();
        }
        
        $conexion->commit();
        
        echo "<script>
			Swal.fire({
				icon: 'success',
				title: 'Candidato eliminado correctamente',
				text: 'Se ha cambiado a aprendiz',
				confirmButtonText: 'Aceptar'
			}).then(() => {
				window.location.href = 'home.php?pg=$pg&idficfil=".$_REQUEST['idficfil']."';
			});
			</script>";
			exit;
    } catch(PDOException $e) {
        $conexion->rollBack();
        echo "<script>
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: '".addslashes($e->getMessage())."',
				confirmButtonText: 'Aceptar'
			}).then(() => {
				window.location.href = 'home.php?pg=$pg&idficfil=".$_REQUEST['idficfil']."';
			});
			</script>";
    	exit;
    }
}

if($opera=="make_vocero" && $idusu){
    try {
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $conexion->beginTransaction(); 
        
        // Verifica que el usuario pertenezca a la ficha
        $sqlCheckFicha = "SELECT 1 FROM usufic WHERE idusu = :idusu AND idfic = :idfic";
        $resultCheckFicha = $conexion->prepare($sqlCheckFicha);
        $resultCheckFicha->bindParam(":idusu", $idusu);
        $resultCheckFicha->bindParam(":idfic", $_REQUEST['idficfil']);
        $resultCheckFicha->execute();
        
        if($resultCheckFicha->rowCount() == 0) {
            throw new Exception("El usuario no pertenece a esta ficha");
        }
        
        // Verifica si ya es candidato vocero
        $sqlCheck = "SELECT 1 FROM usupef WHERE idusu = :idusu AND idper = 13";
        $resultCheck = $conexion->prepare($sqlCheck);
        $resultCheck->bindParam(":idusu", $idusu);
        $resultCheck->execute();
        
        if($resultCheck->rowCount() == 0) {
            // se actuali el perfil principal a vocero 
            $sqlUpdatePerfil = "UPDATE usuario SET idper = 13 WHERE idusu = :idusu";
            $resultUpdatePerfil = $conexion->prepare($sqlUpdatePerfil);
            $resultUpdatePerfil->bindParam(":idusu", $idusu);
            $resultUpdatePerfil->execute();
            
            // se inserta la relacion en usupef 
            $sqlInsert = "INSERT INTO usupef (idusu, idper) VALUES (:idusu, 13)";
            $resultInsert = $conexion->prepare($sqlInsert);
            $resultInsert->bindParam(":idusu", $idusu);
            $resultInsert->execute();
            
            //se da el numero de candidato
            $sqlMax = "SELECT MAX(noca) as max_noca FROM usuario u 
                      INNER JOIN usufic uf ON u.idusu = uf.idusu 
                      WHERE uf.idfic = :idfic 
                      AND u.idusu IN (SELECT idusu FROM usupef WHERE idper = 13)";
            $resultMax = $conexion->prepare($sqlMax);
            $resultMax->bindParam(":idfic", $_REQUEST['idficfil']);
            $resultMax->execute();
            $max_noca = $resultMax->fetch(PDO::FETCH_ASSOC)['max_noca'];
            $nuevo_noca = $max_noca ? $max_noca + 1 : 1;
            
            $sqlUpdateNoca = "UPDATE usuario SET noca = :noca WHERE idusu = :idusu";
            $resultUpdateNoca = $conexion->prepare($sqlUpdateNoca);
            $resultUpdateNoca->bindParam(":noca", $nuevo_noca);
            $resultUpdateNoca->bindParam(":idusu", $idusu);
            $resultUpdateNoca->execute();
        }
        
        $conexion->commit();
        
        echo "<script>
		Swal.fire({
			icon: 'success',
			title: 'Usuario asignado como candidato a vocero correctamente',
			confirmButtonText: 'Aceptar'
		}).then(() => {
			window.location.href = 'home.php?pg=$pg&idficfil=".$_REQUEST['idficfil']."';
		});
		</script>";
		exit;
        
    } catch(PDOException $e) {
        $conexion->rollBack(); 
        echo "<script>
		Swal.fire({
			icon: 'error',
			title: 'Error en la base de datos',
			text: '".addslashes($e->getMessage())."',
			confirmButtonText: 'Aceptar'
		}).then(() => {
			window.location.href = 'home.php?pg=$pg&idficfil=".$_REQUEST['idficfil']."';
		});
		</script>";
        exit;
    } catch(Exception $e) {
        $conexion->rollBack(); 
       echo "<script>
			Swal.fire({
				icon: 'warning',
				title: 'Atención',
				text: '".addslashes($e->getMessage())."',
				confirmButtonText: 'Aceptar'
			}).then(() => {
				window.location.href = 'home.php?pg=$pg&idficfil=".$_REQUEST['idficfil']."';
			});
			</script>";
		exit;
    }
}

if($opera=="InUF"){
	if($idfic AND $idusu){
		$mecv->ediUxF();
		$dtuxf = $mecv->getFicUsuSec($idusu,$idfic);
		if(!$dtuxf) $mecv->insUxF($idusu, $idfic);
	}
}
//Eliminar
if($opera=="Eliminar"&& $idusu)$mecv->del();

if($opera=="duxf"){
	if($idfic AND $idusu) $mecvu->delUxPF($idusu,$idfic);
}

//mostrar aprendices de la ficha seleccionada
$dat = NULL;
if($idficfil) {
    if($pg == 1117) { 
        $dat = $mecv->getAprendicesPorFicha($idficfil);
    } else {
        $dat = $mecv->getAll($idficfil);
    }
}
$dce = $mecv->getCentro();
$dfi = $mecv->getFicha();
$dpe = $mecv->getPerfil();
$tfichas = $mecv->getTficha();
if($idusu){
	$datOne = $mecv->getOne();
}


function modmulsel($id, $nom,$pg){
	$musu = new musu();
	$datmd = $musu->getMod();
	
	$html = '';
	$html .= '<div class="modal" id="muxp'.$id.'" tabindex="-1" role="dialog">';
		$html .= '<div class="modal-dialog">';
			$html .= '<form action="home.php?pg='.$pg.'" method="POST">';
				$html .= '<div class="modal-content">';
					$html .= '<div class="modal-header">';
						$html .= '<h3>Perfiles de Usuario</h3>';
						$html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
					$html .= '</div>';
					$html .= '<div class="modal-body">';
						$html .= '<h5>Usuario: '.$nom.'</h5>';
						$html .= '<div class="row">';
						//
							if($datmd){ foreach($datmd AS $dmd){
								$datpu = $musu->getPefus($dmd['idmod']);//por cada modulo se obtiene los perfiles que estan activos  
								$datup = $musu->getUsPe($dmd['idmod'],$id);// por cada modulo obtiene el perfil actual del usuario
								$html .= '<div class="form-group col-md-6">';
									$html .= '<label for="idper">'.$dmd['nommod'].'</label>';
									$html .= '<select name="idper[]" id="idper" class="form-select">';
										$html .= '<option value="0">Sin perfil</option>';
										if($datpu){ foreach($datpu AS $dp){
											$html .= '<option value="'.$dp['idper'].'" ';
											if(($datup AND $datup[0]['idper']==$dp['idper'])) $html .= 'selected';
											$html .= '>'.$dp['nomper'].'</option>';
										}}
									$html .= '</select>';
								$html .= '</div>';
							}}
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="modal-footer">';
						$html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
						$html .= '<input type="submit" class="btn btn-primary" value="Guardar">';
						$html .= '<input type="hidden" name="idusu" value="'.$id.'">';
						$html .= '<input type="hidden" name="opera" value="InUP">';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</form>';
		$html .= '</div>';
	$html .= '</div>';
	return $html;
}
function modsimsel($id, $nom,$pg){
	$musu = new musu();
	$datpu = $musu->getFicha();
	$datfr = $musu->getFicUsu($id);
	$html = '';
	$html .= '<div class="modal" id="muxf'.$id.'" tabindex="-1" role="dialog">';
		$html .= '<div class="modal-dialog">';
			$html .= '<form action="home.php?pg='.$pg.'" method="POST">';
				$html .= '<div class="modal-content">';
					$html .= '<div class="modal-header">';
						$html .= '<h3>Fichas de Usuario</h3>';
						$html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
					$html .= '</div>';
					$html .= '<div class="modal-body">';
						$html .= '<h5>Usuario: '.$nom.'</h5>';
						$html .= '<div class="row">';
								$html .= '<div class="form-group col-md-12">';
									$html .= '<label for="idfic">Ficha</label>';
									$html .= '<select name="idfic" id="idfic" class="form-select">';
										$html .= '<option value="0">Seleccione Ficha</option>';
										if($datpu){ foreach($datpu AS $dp){
											$html .= '<option value="'.$dp['idfic'].'" ';
											$html .= '>'.$dp['idfic']." - ".$dp['nomfic'].'</option>';
										}}
									$html .= '</select>';
								$html .= '</div>';
								$html .= '<label>Fichas Registradas</label><br>';
								$html .= '<div class="form-group col-md-12">';
										if($datfr){ foreach($datfr AS $dp){
											$html .= '<div class="row">';
												$html .= '<div class="form-group col-md-9">';
													$html .= $dp['idfic']." - ".$dp['nomfic'];
												$html .= '</div>';
												$html .= '<div class="form-group col-md-3" style="text-align: center;">';
													if($dp['actfic']==1) $ctx = "#ff0"; else $ctx = "#333"; 
													$html .= '<i class="fa-solid fa-star" style="color: '.$ctx.';text-shadow: 0px 0px 4px #000"></i>';
													$html .= '&nbsp;&nbsp;&nbsp;';
													$html .= '<a href="home.php?pg='.$pg.'&idfic='.$dp['idfic'].'&idusu='.$id.'&opera=duxf">';
														$html .= '<i class="fa-solid fa-trash"></i>';
													$html .= '</a>';
												$html .= '</div>';
											$html .= '</div>';
										}}else{
											$html .= '<label>Sin ficha registrada</label>';
										}
									
								$html .= '</div>';
						$html .= '</div>';
					$html .= '</div>';
					$html .= '<div class="modal-footer">';
						$html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
						$html .= '<input type="submit" class="btn btn-primary" value="Guardar">';
						$html .= '<input type="hidden" name="idusu" value="'.$id.'">';
						$html .= '<input type="hidden" name="opera" value="InUF">';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</form>';
		$html .= '</div>';
	$html .= '</div>';
	return $html;
}

?>