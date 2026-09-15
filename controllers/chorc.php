<?php
session_start();
require_once("models/mhorc.php");
require_once("models/mhdt.php");
require_once("models/memp.php");
require_once("models/mccm.php");

// Inicializar variables
$idnorad = isset($_REQUEST["idnorad"]) ? $_REQUEST["idnorad"] : NULL;

// Inicializar objetos
$mhorc = new Mhorc();
$mhdt = new Mhdt();
$memp = new Memp();
$mccm = new Mccm();

// Inicializar variables
$datOne = [];
$datOneEmp = [];
$datOneHt = [];
$idemp_actual = null;


$mhdt->setIdnorad($idnorad);
$dthdt = $mhdt->getOne();

if($dthdt){
    $mccm->setCodpro($dthdt[0]["codpro"]);
    $dtpro = $mccm->getOne();
    //var_dump($dtpro);
}

// Manejar el cambio de empresa (tanto para registros existentes como nuevos)
if(isset($_POST['cambiar_empresa']) && !empty($_POST['idemp'])) {
    $idemp_actual = $_POST['idemp'];
    
    // Si es un registro existente
    if($idnorad) {
        // Cargar datos actuales primero
        $mhdt->setIdnorad($idnorad);
        $datos_actuales = $mhdt->getOne();
        
        if(!empty($datos_actuales[0])) {
            // Guardar TODOS los datos actuales importantes, no solo las fechas
            $datos_guardar = $datos_actuales[0];
            
            // Actualizar solo el campo de empresa
            $mhdt->setIdemp($idemp_actual);
            
            // Mantener los datos existentes al actualizar
            $mhdt->setFeclini($datos_guardar['feclini'] ?? null);
            $mhdt->setFeclin($datos_guardar['feclin'] ?? null);
            $mhdt->setCupo($datos_guardar['cupo'] ?? null);
            $mhdt->setJornada($datos_guardar['jornada'] ?? null);
            
            // Realizar la actualización
            $mhdt->edit();
            
            // Recargar los datos actualizados
            $datOneHt = $mhdt->getOne();
            
            // Asegurarse de que los datos mostrados incluyan la información actualizada
            if (!empty($datOneHt[0])) {
                // Mantener los datos guardados en la visualización
                $datOneHt[0] = array_merge($datOneHt[0], $datos_guardar);
                // Asegurar que el idemp sea el correcto
                $datOneHt[0]['idemp'] = $idemp_actual;
                // Asegurar que las fechas se mantengan
                $datOneHt[0]['feclini'] = $datos_guardar['feclini'];
                $datOneHt[0]['feclin'] = $datos_guardar['feclin'];
            } else {
                // Si no se cargaron los datos actualizados, usar los guardados
                $datOneHt = $datos_actuales;
                $datOneHt[0]['idemp'] = $idemp_actual;
            }
        }
    }
    
    // Cargar datos de la empresa seleccionada
    $memp->setIdemp($idemp_actual);
    $datOneEmp = $memp->getOne();
}
// Si no se está cambiando la empresa pero hay un idnorad, cargar datos normales
else if($idnorad) {
    // Cargar datos de la hoja de trabajo
    $mhdt->setIdnorad($idnorad);
    $datOneHt = $mhdt->getOne();
    
    // Cargar datos de la empresa si existe idemp
    if(!empty($datOneHt[0]['idemp'])) {
        $memp->setIdemp($datOneHt[0]['idemp']);
        $datOneEmp = $memp->getOne();
    }
}

// Asignar datos a $datOne si hay datos de hoja de trabajo
if(!empty($datOneHt) && !empty($datOneHt[0])) {
    $datOne = $datOneHt;
    
    // Forzar la carga de las fechas desde la hoja de trabajo
    if (isset($datOne[0]['feclini']) && isset($datOne[0]['feclin'])) {
        // Asegurarse de que las fechas estén en el formato correcto
        $feclini = new DateTime($datOne[0]['feclini']);
        $feclin = new DateTime($datOne[0]['feclin']);
        
        // Actualizar los valores en el array
        $datOne[0]['feclini'] = $feclini->format('Y-m-d');
        $datOne[0]['feclin'] = $feclin->format('Y-m-d');
    }
}

// Si no hay idnorad pero se ha seleccionado una empresa (caso de nuevo registro)
if(empty($idnorad) && !empty($_POST['idemp']) && empty($datOneEmp)) {
    $memp->setIdemp($_POST['idemp']);
    $datOneEmp = $memp->getOne();
}

// Manejar guardado de horario (DEBE IR DESPUÉS de inicializar variables pero ANTES del HTML)
if(isset($_POST['opera']) && $_POST['opera'] == 'save_horario') {
    if(isset($_POST['datos']) && !empty($_POST['idnorad'])) {
        $datos_json = json_decode($_POST['datos'], true);
        $idnorad_save = $_POST['idnorad'];
        
        // Obtener el ID del usuario actual (instructor) desde la sesión
        $idusu_actual = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : 1;
        
        if(is_array($datos_json)) {
            $exito = true;
            foreach($datos_json as $dato) {
                $fecha = isset($dato['fecha']) ? $dato['fecha'] : null;
                $hora_inicio = isset($dato['hora_inicio']) ? $dato['hora_inicio'] : null;
                $hora_fin = isset($dato['hora_fin']) ? $dato['hora_fin'] : null;
                
                if($fecha && $hora_inicio && $hora_fin) {
                    // Validar que las horas estén en formato militar (HH:MM:SS)
                    // y que sean horas completas (sin minutos diferentes de 00)
                    $inicio_parts = explode(':', $hora_inicio);
                    $fin_parts = explode(':', $hora_fin);
                    
                    // Verificar formato correcto
                    if(count($inicio_parts) != 3 || count($fin_parts) != 3) {
                        echo json_encode(['success' => false, 'message' => 'Formato de hora inválido. Use formato militar HH:MM:SS']);
                        exit;
                    }
                    
                    // Verificar que los minutos sean 00 (horas completas)
                    if($inicio_parts[1] != '00' || $fin_parts[1] != '00') {
                        echo json_encode(['success' => false, 'message' => 'Las horas deben ser completas (ej: 07:00:00, 17:00:00)']);
                        exit;
                    }
                    
                    // Calcular horas totales
                    $inicio_dt = new DateTime('2000-01-01 ' . $hora_inicio);
                    $fin_dt = new DateTime('2000-01-01 ' . $hora_fin);
                    $diff = $inicio_dt->diff($fin_dt);
                    $horas_totales = $diff->h + ($diff->i / 60);
                    
                    // Validar que las horas totales sean enteras y estén entre 1 y 24
                    if($horas_totales < 1 || $horas_totales > 24 || $horas_totales != floor($horas_totales)) {
                        echo json_encode(['success' => false, 'message' => 'Las horas totales deben ser enteras entre 1 y 24']);
                        exit;
                    }
                    
                    // Verificar si ya existe un horario para esta fecha
                    $mhorc_check = new Mhorc();
                    $mhorc_check->setFechaEspecifica($fecha);
                    $mhorc_check->setIdnorad($idnorad_save);
                    $existe = $mhorc_check->getByFecha();
                    
                    if(empty($existe)) {
                        // Insertar nuevo horario
                        $mhorc_ins = new Mhorc();
                        $mhorc_ins->setFechaEspecifica($fecha);
                        $mhorc_ins->setHinihor($hora_inicio);
                        $mhorc_ins->setHfinhor($hora_fin);
                        $mhorc_ins->setIdnorad($idnorad_save);
                        $mhorc_ins->setIdusu($idusu_actual);
                        $mhorc_ins->saveConFecha();
                    } else {
                        // Actualizar horario existente
                        $mhorc_upd = new Mhorc();
                        $mhorc_upd->setIdhor($existe[0]['idhor']);
                        $mhorc_upd->setFechaEspecifica($fecha);
                        $mhorc_upd->setHinihor($hora_inicio);
                        $mhorc_upd->setHfinhor($hora_fin);
                        $mhorc_upd->setIdnorad($idnorad_save);
                        $mhorc_upd->setIdusu($idusu_actual);
                        $mhorc_upd->editConFecha();
                    }
                }
            }
            
            if($exito) {
                echo json_encode(['success' => true, 'message' => 'Horario guardado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar algunos horarios']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        }
        exit;
    }
}

// Obtener horarios ocupados para otras fichas en el rango de fechas
if(!empty($datOneHt[0]['feclini']) && !empty($datOneHt[0]['feclin'])) {
    $mhorc->setFeclini($datOneHt[0]['feclini']);
    $mhorc->setFeclin($datOneHt[0]['feclin']);
    // Excluir los horarios de esta ficha, si no hay idnorad usar 0
    $idnorad_excluir = !empty($idnorad) ? $idnorad : 0;
    $mhorc->setIdnorad($idnorad_excluir);
    $datHorariosOcupados = $mhorc->getHorariosOcupados();
} else {
    $datHorariosOcupados = [];
}

// Obtener datos para los selectores (programas, empresas, programas especiales, jornadas)
$datPr = $mhdt->getAllPro();
$datEm = $mhdt->getAllEmp();
$datPe = $mhdt->getAllVal(25);
$datJo = $mhdt->getAllVal(1);

// Variables por defecto para la vista del horario
$hora_inicio_defecto = "07:00";
$hora_fin_defecto = "17:00";
$horas_totales = "10";
$disabled_attr = "";
$esta_ocupado = false;
$estado_class = "";
$estado_text = "";
$ficha_ocupante = "";
$nombre_instructor_ocupante = "";
$instructor_ocupante = "";
$fecha_sql = "";

// Obtener instructores asignados a la hoja de trabajo
$instructoresAsignados = [];
if(!empty($idnorad)) {
    try {
        $sql = "SELECT u.idusu, u.nomusu
                FROM hdtxusu h
                INNER JOIN usuario u ON h.idusu = u.idusu
                WHERE h.idnorad = :idnorad
                ORDER BY u.nomusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
        $stmt->execute();
        $instructoresAsignados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Si hay error, se mantiene el array vacío
        $instructoresAsignados = [];
    }
}

// Manejar agregado de instructor
if(isset($_POST['opera']) && $_POST['opera'] == 'AgrIns' && !empty($idnorad) && !empty($_POST['idinstructor'])) {
    $idinstructor = $_POST['idinstructor'];
    try {
        // Verificar si ya existe el instructor
        $sql_check = "SELECT COUNT(*) as existe FROM hdtxusu WHERE idnorad = :idnorad AND idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
        $stmt_check->bindParam(':idusu', $idinstructor, PDO::PARAM_INT);
        $stmt_check->execute();
        $resultado = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if($resultado['existe'] == 0) {
            // Insertar instructor
            $sql_insert = "INSERT INTO hdtxusu (idnorad, idusu) VALUES (:idnorad, :idusu)";
            $stmt_insert = $conexion->prepare($sql_insert);
            $stmt_insert->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt_insert->bindParam(':idusu', $idinstructor, PDO::PARAM_INT);
            $stmt_insert->execute();
            
            // Recargar instructores asignados
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt->execute();
            $instructoresAsignados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        // Si hay error, se mantiene el array actual
    }
}

?>
