<?php
// =============================
// Parámetros configurables (deben estar al inicio)
// =============================
if (!defined('CONTRATISTA_MAX_HORAS')) {
    define('CONTRATISTA_MAX_HORAS', 180);
}
if (!defined('FACTOR_FORMACION')) {
    define('FACTOR_FORMACION', 6.4);
}
if (!defined('FACTOR_OTROS')) {
    define('FACTOR_OTROS', 2.1);
}
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir dependencias con rutas absolutas
include_once __DIR__ . '/../models/conexion.php';
include_once __DIR__ . '/../models/mhor.php';

$idhor = $_REQUEST['idhor']   ?? null;
$idfic = $_REQUEST['idfic']   ?? null;
$idaul = $_REQUEST['idaul']   ?? null;
$idusu = $_REQUEST['idusu']   ?? null;
$iddia = $_REQUEST['iddia']   ?? null;
$ope   = $_REQUEST['ope']     ?? $_POST['ope'] ?? null;

// Para crear un nuevo horario, limpiar el instructor solo si viene de URL (GET) y no es una edición
// No limpiar si viene del formulario (POST) ya que el usuario puede haber seleccionado un instructor
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $ope !== 'edi' && $ope !== 'edit') {
    $idusu = null;
}

$idare = $_REQUEST['idare'] ?? null;

// Nuevos campos para actividades "Otros"
$es_otros      = $_REQUEST['es_otros']      ?? null;
$actividad     = $_REQUEST['actividad']     ?? null;
$horas_otros   = $_REQUEST['horas_otros']   ?? null;
// Capturar es_formacion_directa (puede venir como array si hay checkbox + hidden)
$es_formacion_directa_raw = $_REQUEST['es_formacion_directa'] ?? 1;
if (is_array($es_formacion_directa_raw)) {
    // Si viene como array, tomar el valor más alto (el checkbox prevalece)
    $es_formacion_directa = max($es_formacion_directa_raw);
} else {
    $es_formacion_directa = $es_formacion_directa_raw;
}

// Nuevos campos para horarios "Transversales"
$es_transversal      = $_REQUEST['es_transversal']      ?? $_POST['es_transversal'] ?? null;
$nombre_transversal  = $_REQUEST['nombre_transversal']  ?? $_POST['nombre_transversal'] ?? null;

// Nuevos campos para fechas de lapsos de tiempo
$fecha_inicio = $_REQUEST['fecha_inicio'] ?? $_POST['fecha_inicio'] ?? null;
$fecha_fin    = $_REQUEST['fecha_fin']   ?? $_POST['fecha_fin'] ?? null;

// Configurar zona horaria de Colombia
date_default_timezone_set('America/Bogota');

// ==========================================
// SISTEMA DE PERMISOS POR PERFILES - INICIO
// ==========================================

/**
 * Obtiene el perfil del usuario actual desde la tabla usupef
 * @param int $idusu ID del usuario
 * @return array|null Datos del perfil o null si no existe
 */
function obtenerPerfilUsuario($idusu) {
    try {
        $sql = "SELECT up.idper, p.nomper 
                FROM usupef up 
                INNER JOIN perfil p ON up.idper = p.idper 
                WHERE up.idusu = :idusu 
                LIMIT 1";
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        $perfil = $result->fetch(PDO::FETCH_ASSOC);
        
        return $perfil ?: null;
    } catch (Exception $e) {
        error_log("Error obteniendo perfil del usuario: " . $e->getMessage());
        return null;
    }
}

/**
 * Valida si el usuario tiene permisos para realizar una acción específica
 * @param int $idusu ID del usuario
 * @param string $accion Acción a validar ('crear', 'editar', 'eliminar', 'ver', 'imprimir')
 * @return bool True si tiene permisos, false si no
 */
function validarPermisosUsuario($idusu, $accion) {
    $perfil = obtenerPerfilUsuario($idusu);
    
    if (!$perfil) {
        return true; // Si no se puede obtener perfil, permitir acceso (comportamiento por defecto)
    }
    
    $idper = $perfil['idper'];
    
    // Solo restringir al perfil 4, todos los demás tienen permisos completos
    if ($idper == 4) {
        // Perfil restringido - solo visualización e impresión
        $permisos_restrictivos = [
            'ver' => true,
            'imprimir' => true,
            'crear' => false,
            'editar' => false,
            'eliminar' => false
        ];
        
        return isset($permisos_restrictivos[$accion]) && $permisos_restrictivos[$accion] === true;
    }
    
    // Para todos los demás perfiles, permitir todas las acciones
    return true;
}

/**
 * Obtiene la ficha asignada al usuario (para perfiles restringidos)
 * @param int $idusu ID del usuario
 * @return int|null ID de la ficha asignada o null si no tiene
 */
function obtenerFichaAsignadaUsuario($idusu) {
    try {
        $sql = "SELECT idfic FROM usufic WHERE idusu = :idusu AND actfic = '1' LIMIT 1";
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        $ficha = $result->fetch(PDO::FETCH_ASSOC);
        
        return $ficha ? $ficha['idfic'] : null;
    } catch (Exception $e) {
        error_log("Error obteniendo ficha asignada: " . $e->getMessage());
        return null;
    }
}

/**
 * Valida si el usuario puede acceder a una ficha específica
 * @param int $idusu ID del usuario
 * @param int $idfic ID de la ficha
 * @return bool True si puede acceder, false si no
 */
function validarAccesoFicha($idusu, $idfic) {
    $perfil = obtenerPerfilUsuario($idusu);
    
    if (!$perfil) {
        return false;
    }
    
    $idper = $perfil['idper'];
    
    // Perfiles restringidos solo pueden ver su ficha asignada
    if ($idper == 4) {
        $fichaAsignada = obtenerFichaAsignadaUsuario($idusu);
        return $fichaAsignada == $idfic;
    }
    
    // Otros perfiles pueden acceder a cualquier ficha (por ahora)
    return true;
}

/**
 * Aplica restricciones de interfaz según el perfil del usuario
 * @param int $idusu ID del usuario
 * @return array Configuración de restricciones para la interfaz
 */
function obtenerRestriccionesInterfaz($idusu) {
    $perfil = obtenerPerfilUsuario($idusu);
    
    // Si no se puede obtener perfil, dar permisos completos por defecto
    if (!$perfil) {
        return [
            'mostrar_botones_crear' => true,
            'mostrar_botones_editar' => true,
            'mostrar_botones_eliminar' => true,
            'mostrar_boton_imprimir' => true,
            'solo_ficha_asignada' => false,
            'mensaje_restriccion' => ''
        ];
    }
    
    $idper = $perfil['idper'];
    
    // Solo restringir al perfil 4
    if ($idper == 4) {
        return [
            'mostrar_botones_crear' => false,
            'mostrar_botones_editar' => false,
            'mostrar_botones_eliminar' => false,
            'mostrar_boton_imprimir' => true,
            'solo_ficha_asignada' => true,
            'mensaje_restriccion' => 'Tu perfil solo permite visualizar e imprimir horarios de tu ficha asignada.'
        ];
    }
    
    // Para todos los demás perfiles, dar permisos completos
    return [
        'mostrar_botones_crear' => true,
        'mostrar_botones_editar' => true,
        'mostrar_botones_eliminar' => true,
        'mostrar_boton_imprimir' => true,
        'solo_ficha_asignada' => false,
        'mensaje_restriccion' => ''
    ];
}

// ==========================================
// SISTEMA DE PERMISOS POR PERFILES - FIN
// ==========================================

/**
 * Genera una URL de redirección dinámica basada en el entorno actual
 * @param string $pagina Página a la que redirigir (ej: 'home.php?pg=1506')
 * @return string URL completa para redirección
 */
function generarUrlRedireccion($pagina) {
    $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Obtener la ruta base del proyecto (sin el directorio controllers)
    $script_name = $_SERVER['SCRIPT_NAME']; // /sagi/controllers/chor.php
    $ruta_base = dirname(dirname($script_name)); // /sagi (sube dos niveles desde controllers/chor.php)
    
    // Limpiar barras dobles y asegurar formato correcto
    $url = $protocolo . '://' . $host . $ruta_base . '/' . ltrim($pagina, '/');
    $url = str_replace('//', '/', $url);
    $url = str_replace(':/', '://', $url);
    
    return $url;
}

/**
 * Obtiene el área del usuario actual desde la sesión
 * @return int ID del área
 */
function obtenerAreaUsuarioActual() {
    // Si hay un área guardada en la sesión, la usamos
    if (isset($_SESSION['idare']) && !empty($_SESSION['idare'])) {
        return $_SESSION['idare'];
    }
    
    // Si no, obtenemos la primera área disponible como por defecto
    try {
        $sql = 'SELECT idare FROM area ORDER BY idare LIMIT 1';
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $area = $result->fetch(PDO::FETCH_ASSOC);
        
        return $area ? $area['idare'] : 1; // Si no hay áreas, usar 1 por defecto
    } catch (Exception $e) {
        error_log("Error obteniendo área del usuario: " . $e->getMessage());
        return 1; // Área por defecto en caso de error
    }
}

/**
 * Crea o obtiene una ficha temporal para un instructor específico
 * Verifica conflictos de horarios para determinar si reutilizar o crear nueva ficha
 * VERSIÓN ROBUSTA: Garantiza que la ficha siempre se cree correctamente
 * MANTIENE: La funcionalidad de limpieza automática de fichas temporales vacías
 * @param string $idInstructor ID del instructor
 * @param string $dia Día de la semana (iddia)
 * @param string $fechaEspecifica Fecha específica del horario (Y-m-d)
 * @return string ID de la ficha temporal
 * @throws Exception Si no se puede crear la ficha
 */
function crearObtenerFichaTemporal($idInstructor, $dia = null, $fechaEspecifica = null) {
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
    
    try {
        // Iniciar transacción para garantizar consistencia
        $conexion->beginTransaction();
                
        // 1. Obtener datos completos del instructor
        $sqlInstructor = 'SELECT idusu, nomusu, ndocusu, fecini, fecfin FROM usuario WHERE idusu = :id_instructor';
        $resultInstructor = $conexion->prepare($sqlInstructor);
        $resultInstructor->bindParam(':id_instructor', $idInstructor);
        $resultInstructor->execute();
        $instructor = $resultInstructor->fetch(PDO::FETCH_ASSOC);
        
        if (!$instructor) {
            throw new Exception("Instructor con ID $idInstructor no encontrado");
        }
        
        $cedulaInstructor = $instructor['ndocusu'];
        $nombreInstructor = $instructor['nomusu'];
        $nomficTemporal = "Actividades Especiales - {$nombreInstructor} ({$cedulaInstructor})";
        
        
        // 2. Buscar todas las fichas existentes para este instructor
        $sqlFichasExistentes = 'SELECT idfic, nomfic FROM ficha WHERE idfic = :cedula OR idfic LIKE :patron_cedula ORDER BY idfic';
        $resultFichas = $conexion->prepare($sqlFichasExistentes);
        $patronCedula = $cedulaInstructor . '-%';
        $resultFichas->bindParam(':cedula', $cedulaInstructor);
        $resultFichas->bindParam(':patron_cedula', $patronCedula);
        $resultFichas->execute();
        $fichasExistentes = $resultFichas->fetchAll(PDO::FETCH_ASSOC);
        
        
        // 3. Si hay día y fecha específica, verificar conflictos
        if ($dia && $fechaEspecifica && !empty($fichasExistentes)) {
            
                foreach ($fichasExistentes as $ficha) {
                $idficExistente = $ficha['idfic'];
                
                // Verificar conflictos de horario más específicos
                $sqlConflicto = 'SELECT COUNT(*) as total FROM horario 
                                 WHERE idfic = :idfic 
                                 AND iddia = :dia 
                                 AND (
                                     fecha_especifica = :fecha_especifica 
                                     OR (fecha_inicio <= :fecha_especifica AND fecha_fin >= :fecha_especifica)
                                 )
                                 AND es_otros = 1'; // Solo verificar conflictos con "Otros"
                
                $resultConflicto = $conexion->prepare($sqlConflicto);
                $resultConflicto->bindParam(':idfic', $idficExistente);
                $resultConflicto->bindParam(':dia', $dia);
                $resultConflicto->bindParam(':fecha_especifica', $fechaEspecifica);
                $resultConflicto->execute();
                $conflictos = $resultConflicto->fetch(PDO::FETCH_ASSOC)['total'];
                
                if ($conflictos == 0) {
                    
                    // Verificar que la ficha realmente existe y tiene el nomfic correcto
                    if (empty($ficha['nomfic']) || $ficha['nomfic'] !== $nomficTemporal) {
                        $sqlUpdateNomfic = 'UPDATE ficha SET nomfic = :nomfic WHERE idfic = :idfic';
                        $resultUpdate = $conexion->prepare($sqlUpdateNomfic);
                        $resultUpdate->bindParam(':nomfic', $nomficTemporal);
                        $resultUpdate->bindParam(':idfic', $idficExistente);
                        $resultUpdate->execute();
                    }
                    
                    $conexion->commit();
                    return $idficExistente;
                } else {
                    $conexion->rollback();
                    return false;
                }
            }
            
        } else if (!empty($fichasExistentes)) {
            // Sin información de día/fecha, priorizar la ficha base
            foreach ($fichasExistentes as $ficha) {
                if ($ficha['idfic'] === $cedulaInstructor) {
                    
                    // Verificar y corregir nomfic si es necesario
                    if (empty($ficha['nomfic']) || $ficha['nomfic'] !== $nomficTemporal) {
                        $sqlUpdateNomfic = 'UPDATE ficha SET nomfic = :nomfic WHERE idfic = :idfic';
                        $resultUpdate = $conexion->prepare($sqlUpdateNomfic);
                        $resultUpdate->bindParam(':nomfic', $nomficTemporal);
                        $resultUpdate->bindParam(':idfic', $cedulaInstructor);
                        $resultUpdate->execute();
                    }
                    
                    $conexion->commit();
                    return $cedulaInstructor;
                }
            }
            
            // Si no hay ficha base, usar la primera disponible
            $fichaReutilizar = $fichasExistentes[0]['idfic'];
            
            // Verificar y corregir nomfic si es necesario
            if (empty($fichasExistentes[0]['nomfic']) || $fichasExistentes[0]['nomfic'] !== $nomficTemporal) {
                $sqlUpdateNomfic = 'UPDATE ficha SET nomfic = :nomfic WHERE idfic = :idfic';
                $resultUpdate = $conexion->prepare($sqlUpdateNomfic);
                $resultUpdate->bindParam(':nomfic', $nomficTemporal);
                $resultUpdate->bindParam(':idfic', $fichaReutilizar);
                $resultUpdate->execute();
            }
            
            $conexion->commit();
            return $fichaReutilizar;
        }
        
        // 4. Crear nueva ficha temporal
        
        // Encontrar el siguiente ID disponible
        $idficTemporal = $cedulaInstructor; // Comenzar con la cédula base
        $contador = 1;
        
        // Verificar disponibilidad del ID
        while (true) {
            $sqlVerificar = 'SELECT idfic FROM ficha WHERE idfic = :idfic';
            $resultVerificar = $conexion->prepare($sqlVerificar);
            $resultVerificar->bindParam(':idfic', $idficTemporal);
            $resultVerificar->execute();
            $fichaExistente = $resultVerificar->fetch(PDO::FETCH_ASSOC);
            
            if (!$fichaExistente) {
                break; // ID disponible
            }
            
                $contador++;
                $idficTemporal = $cedulaInstructor . '-' . $contador;
            
            // Prevenir bucle infinito
            if ($contador > 100) {
                throw new Exception("No se pudo encontrar un ID disponible para la ficha temporal (intentos: $contador)");
            }
        }
        
        // 5. Insertar la nueva ficha con validación
        $sqlInsert = 'INSERT INTO ficha(idfic, nomfic, codpro, jornada, idcen, mun, finific, ffinfic) 
                      VALUES (:idfic, :nomfic, :codpro, :jornada, :idcen, :mun, :finific, :ffinfic)';
        
        $resultInsert = $conexion->prepare($sqlInsert);
        $resultInsert->bindParam(':idfic', $idficTemporal);
        $resultInsert->bindParam(':nomfic', $nomficTemporal);
        
        // Valores por defecto
        $codpro = 228118;
        $jornada = 1; // Cambiar de 1055 (Madrugada) a 1 (Mañana)
        $idcen = 951310;
        $mun = '25175';
        $finific = date('Y-m-d');
        $ffinfic = date('Y-m-d', strtotime('+2 years'));
        
        $resultInsert->bindParam(':codpro', $codpro);
        $resultInsert->bindParam(':jornada', $jornada);
        $resultInsert->bindParam(':idcen', $idcen);
        $resultInsert->bindParam(':mun', $mun);
        $resultInsert->bindParam(':finific', $finific);
        $resultInsert->bindParam(':ffinfic', $ffinfic);
        
        $insertResult = $resultInsert->execute();
        
        if (!$insertResult) {
            $errorInfo = $resultInsert->errorInfo();
            throw new Exception("Error en INSERT: " . implode(' - ', $errorInfo));
        }
        
        // 6. Verificar que la ficha se creó correctamente
        $sqlVerificarCreacion = 'SELECT idfic, nomfic FROM ficha WHERE idfic = :idfic';
        $resultVerificarCreacion = $conexion->prepare($sqlVerificarCreacion);
        $resultVerificarCreacion->bindParam(':idfic', $idficTemporal);
        $resultVerificarCreacion->execute();
        $fichaCreada = $resultVerificarCreacion->fetch(PDO::FETCH_ASSOC);
        
        if (!$fichaCreada) {
            throw new Exception("CRÍTICO: La ficha $idficTemporal no se pudo verificar después de la inserción");
        }
        
        if ($fichaCreada['nomfic'] !== $nomficTemporal) {
            error_log("DEBUG: [ROBUSTO] ⚠️ ADVERTENCIA: nomfic no coincide. Esperado: '$nomficTemporal', Obtenido: '{$fichaCreada['nomfic']}'");
        }
        
        // Confirmar transacción
        $conexion->commit();
                
        return $idficTemporal;
        
    } catch (Exception $e) {
        // Rollback en caso de error
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }
        
        $errorMsg = "ERROR CRÍTICO en crearObtenerFichaTemporal para instructor $idInstructor: " . $e->getMessage();
        // Registro de error
        error_log($errorMsg);
        
        // Re-lanzar la excepción para que el proceso padre la maneje
        throw new Exception($errorMsg);
    }
}

/**
 * Verifica si una ficha es temporal
 * @param string $idfic ID de la ficha
 * @return bool True si es temporal, false si no
 */
function esFichaTemporal($idfic) {
    // Verificar si es una ficha temporal (empieza con cédula o tiene formato cédula-número)
    // Las fichas temporales son las que tienen formato de cédula o cédula-número
    return preg_match('/^\d{6,12}(-\d+)?$/', $idfic) && strlen($idfic) >= 6;
}

/**
 * Elimina fichas temporales que no tienen horarios asociados
 */
function limpiarFichasTemporalesVacias() {
    try {
        
        // Primero, obtener todas las fichas temporales (solo las de actividades especiales)
        $sqlBuscar = 'SELECT f.idfic, f.nomfic FROM ficha f 
                      WHERE (f.idfic REGEXP "^[0-9]{6,12}(-[0-9]+)?$" OR f.idfic LIKE "999%")
                      AND f.nomfic LIKE "Actividades Especiales%"';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $resultBuscar = $conexion->prepare($sqlBuscar);
        $resultBuscar->execute();
        $fichasTemporales = $resultBuscar->fetchAll(PDO::FETCH_ASSOC);
        
        $fichasEliminadas = 0;
        
        foreach ($fichasTemporales as $ficha) {
            $idfic = $ficha['idfic'];
            
            // Verificar si esta ficha tiene horarios asociados
            $sqlVerificar = 'SELECT COUNT(*) as total FROM horario WHERE idfic = :idfic';
            $resultVerificar = $conexion->prepare($sqlVerificar);
            $resultVerificar->bindParam(':idfic', $idfic);
            $resultVerificar->execute();
            $horarios = $resultVerificar->fetch(PDO::FETCH_ASSOC);
            
            if ($horarios['total'] == 0) {
                // No tiene horarios, eliminar la ficha
                $sqlEliminar = 'DELETE FROM ficha WHERE idfic = :idfic';
                $resultEliminar = $conexion->prepare($sqlEliminar);
                $resultEliminar->bindParam(':idfic', $idfic);
                $resultEliminar->execute();
                
                $fichasEliminadas++;
            } else {
                $conexion->rollback();
                return false;
            }
        }
        
        
        return $fichasEliminadas;
        
    } catch (Exception $e) {
        return 0;
    }
}

// Manejo temprano del conteo de horarios para garantizar respuesta JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'contar_horarios_grupo') {
    $idfic_post = $_POST['idfic'] ?? null;
    $iddia_post = $_POST['iddia'] ?? null;
    $idusu_post = $_POST['idusu'] ?? '';
    $idaul_post = $_POST['idaul'] ?? '';
    $fecha_inicio_post = $_POST['fecha_inicio'] ?? null;
    $fecha_fin_post = $_POST['fecha_fin'] ?? null;
    
    header('Content-Type: application/json; charset=UTF-8');
    
    // Validación de parámetros; solo idfic, iddia, fecha_inicio y fecha_fin son obligatorios
    // idusu e idaul pueden estar vacíos (para horarios sin instructor o sin aula)
    if (!$idfic_post || !$iddia_post || !$fecha_inicio_post || !$fecha_fin_post) {
        echo json_encode([
            'success' => true,
            'cantidad' => 0,
            'message' => 'Parámetros obligatorios incompletos: idfic, iddia, fecha_inicio, fecha_fin'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $mhorTmp = new Mhor();
    $cantidadTmp = $mhorTmp->contarHorariosGrupo($idfic_post, $iddia_post, $idusu_post, $idaul_post, $fecha_inicio_post, $fecha_fin_post);
    echo json_encode(['success' => true, 'cantidad' => $cantidadTmp], JSON_UNESCAPED_UNICODE);
    exit;
}

/*
 * ====================================================================================
 * (Esto es para eliminar TODOS los registros de horarios, usar solo en PRODUCCIÓN)
 * ====================================================================================
 */
/*
// Manejo temprano para eliminar todos los horarios (solo producción)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'eliminar_todos_horarios') {
    // Verificar que sea un administrador
    if (!isset($_SESSION['idper']) || $_SESSION['idper'] != 21) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => 'Acceso denegado. Solo administradores pueden realizar esta acción.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    header('Content-Type: application/json; charset=UTF-8');
    
    try {
        // Crear instancia del modelo
        $mhorTmp = new Mhor();
        
        // Obtener conexión
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        
        try {
            // 1. Eliminar todos los horarios
            $sqlEliminar = "DELETE FROM horario";
            $stmtEliminar = $conexion->prepare($sqlEliminar);
            $stmtEliminar->execute();
            $horariosEliminados = $stmtEliminar->rowCount();
            
            // 2. Resetear el autoincrement
            $sqlReset = "ALTER TABLE horario AUTO_INCREMENT = 1";
            $stmtReset = $conexion->prepare($sqlReset);
            $stmtReset->execute();
            
            // 3. Limpiar otras tablas relacionadas si existen
            // Limpiar horas mensuales
            $sqlHorasMensuales = "DELETE FROM horas_mensuales";
            $stmtHorasMensuales = $conexion->prepare($sqlHorasMensuales);
            $stmtHorasMensuales->execute();
            $horasMensualesEliminadas = $stmtHorasMensuales->rowCount();
            
            // Resetear autoincrement de horas mensuales
            $sqlResetHoras = "ALTER TABLE horas_mensuales AUTO_INCREMENT = 1";
            $stmtResetHoras = $conexion->prepare($sqlResetHoras);
            $stmtResetHoras->execute();
            
            echo json_encode([
                'success' => true,
                'message' => "Operación completada exitosamente. Todos los horarios han sido eliminados y los contadores reseteados."
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            // Registro de error
            error_log("Error durante eliminación total: " . $e->getMessage());
            throw $e;
        }
        
    } catch (Exception $e) {
        // Registro de error
        error_log("Error eliminando todos los horarios: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error durante la operación: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
*/

// Manejo temprano para obtener eventos por tipo del calendario académico
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'get_eventos_por_tipo') {
    $tipoEvento = $_POST['tipo_evento'] ?? null;
    
    header('Content-Type: application/json; charset=UTF-8');
    
    if (!$tipoEvento) {
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de evento no especificado'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $mhorTmp = new Mhor();
    $eventos = $mhorTmp->getEventosPorTipo($tipoEvento);
    
    echo json_encode([
        'success' => true,
        'eventos' => $eventos
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Manejo temprano para obtener tipos de eventos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'get_tipos_eventos') {
    header('Content-Type: application/json; charset=UTF-8');
    
    try {
        $mhorTmp = new Mhor();
        $tipos = $mhorTmp->getTiposEventos();
        
        echo json_encode([
            'success' => true,
            'tipos' => $tipos
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtener tipos de eventos: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Endpoint de prueba para verificar conexión
// Eliminado: endpoint de prueba 'test_conexion'

// Endpoint de prueba para verificar la función del modelo
// Eliminado: endpoint de prueba 'test_modelo'

// Endpoint de prueba para crear ficha temporal
// Eliminado: endpoint de prueba 'test_ficha_temporal'

// Endpoint de prueba simple para verificar la función
// Eliminado: endpoint de prueba 'test_simple'

// Endpoint para verificar estructura de la tabla ficha
// Eliminado: endpoint de prueba 'test_estructura'

// Endpoint para probar el nuevo sistema de fichas con cédula
// Eliminado: endpoint de prueba 'test_cedula'

// Endpoint para probar la limpieza de fichas temporales
// Eliminado: endpoint de prueba 'test_limpieza'

// Manejo temprano para obtener horas del rango
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'get_horas_rango') {
    
    $idInstructor = $_POST['id_instructor'] ?? null;
    $fechaInicio = $_POST['fecha_inicio'] ?? null;
    $fechaFin = $_POST['fecha_fin'] ?? null;
        
    header('Content-Type: application/json; charset=UTF-8');
    
    if (!$idInstructor || !$fechaInicio || !$fechaFin) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan parámetros requeridos: id_instructor, fecha_inicio, fecha_fin'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $mhorTmp = new Mhor();
        $resultado = calcularHorasRango($mhorTmp, $idInstructor, $fechaInicio, $fechaFin);
        
        echo json_encode([
            'success' => true,
            'resumen' => $resultado['resumen'],
            'detalle' => $resultado['detalle']
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        error_log("DEBUG get_horas_rango: Error en cálculo: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error al calcular horas del rango: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$mhor = new Mhor();
$mhor->setIdhor($idhor);
$mhor->setIdare($idare);

if ($ope == 'save') {
    // ==========================================
    // VALIDACIÓN DE PERMISOS - CREAR HORARIOS
    // ==========================================
    $idusu_actual = $_SESSION["idusu"] ?? null;
    if (!$idusu_actual) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autenticado.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Validar permisos para crear horarios
    if (!validarPermisosUsuario($idusu_actual, 'crear')) {
        echo json_encode([
            'success' => false,
            'message' => 'No tienes permisos para crear horarios.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Para perfiles restringidos, validar acceso a la ficha
    if ($idfic && !validarAccesoFicha($idusu_actual, $idfic)) {
        echo json_encode([
            'success' => false,
            'message' => 'No tienes acceso a esta ficha.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if (empty($iddia)) {
        return;
    }

    $errores   = [];
    $guardados = 0;
    $totalHorariosCreados = 0;

    // Procesar cada día seleccionado
    foreach ((array)$iddia as $dia) {
        $mhor = new Mhor();
        $mhor->setIdfic($idfic);
        $mhor->setIddia($dia);
        
        // Calcular fecha específica para este día para validaciones
        $fecha = null;
        $modo_individual = isset($_POST['modo_individual']) && $_POST['modo_individual'] == 'on';
        
        if ($modo_individual) {
            // Modo individual: usar la fecha específica directamente
            $fecha_especifica = $_POST['fecha_especifica'] ?? null;
            if ($fecha_especifica && $dia) {
                // Verificar que la fecha específica corresponda al día de la semana seleccionado
                $fechaObj = new DateTime($fecha_especifica);
                $diaSemana = $fechaObj->format('N'); // 1=Lunes, 2=Martes, etc.
                
                $diasSemana = [
                    1042 => 1,  // Lunes
                    1043 => 2,  // Martes
                    1044 => 3,  // Miércoles
                    1045 => 4,  // Jueves
                    1046 => 5,  // Viernes
                    1047 => 6,  // Sábado
                    1048 => 7,  // Domingo
                ];
                
                $diaEsperado = $diasSemana[$dia] ?? 1;
                
                if ($diaSemana == $diaEsperado) {
                    $fecha = $fecha_especifica;
                } else {
                    $errores[] = "La fecha específica seleccionada no corresponde al día de la semana seleccionado.";
                    continue;
                }
            }
        } else {
            // Modo rango: usar la lógica original
            if ($fecha_inicio && $fecha_fin && $dia) {
                $diasEspecificos = generarDiasDelHorario($fecha_inicio, $fecha_fin, $dia);
                $fecha = !empty($diasEspecificos) ? $diasEspecificos[0] : null;
            }
        }

        // Configurar campos según el tipo de horario
        if ($es_otros) {
            // Configuración para actividades "Otros"
            $mhor->setEsOtros(true);
            $mhor->setActividad(trim($actividad));
            $mhor->setHorasOtros($horas_otros);
            // Para actividades "otros": si se marcó el checkbox, usar 2; si no, usar 1
            $valorFormacionDirecta = ($es_formacion_directa == '2') ? 2 : 1;
            $mhor->setEsFormacionDirecta($valorFormacionDirecta);
            error_log("DEBUG: es_formacion_directa recibido: '$es_formacion_directa', valor asignado: $valorFormacionDirecta");
            
            // Para actividades "otros", el aula es opcional
            $mhor->setIdaul(!empty($idaul) ? $idaul : null);
            
            // El instructor sigue siendo requerido
            $mhor->setIdusu($idusu);
            
            // SISTEMA DE FICHAS TEMPORALES: Si no hay ficha seleccionada, crear/obtener ficha temporal
            error_log("DEBUG: Verificando ficha para actividad 'otros'. idfic recibido: '$idfic', idusu: '$idusu'");
            
            if (empty($idfic)) {
                // Solo crear ficha temporal si hay un instructor válido
                if (!empty($idusu)) {
                    error_log("DEBUG: idfic está vacío, creando ficha temporal para instructor $idusu");
                    try {
                        // Calcular fecha específica para verificar conflictos
                        $fechaEspecifica = null;
                        if ($fecha_inicio && $fecha_fin && $dia) {
                            // Obtener la primera fecha específica del rango para este día
                            $diasEspecificos = generarDiasDelHorario($fecha_inicio, $fecha_fin, $dia);
                            $fechaEspecifica = !empty($diasEspecificos) ? $diasEspecificos[0] : null;
                        }
                        
                        $idficTemporal = crearObtenerFichaTemporal($idusu, $dia, $fechaEspecifica);
                        $mhor->setIdfic($idficTemporal);
                        error_log("DEBUG: Ficha temporal asignada al modelo: $idficTemporal para instructor $idusu");
                        
                        // Verificar que se asignó correctamente
                        $idficVerificado = $mhor->getIdfic();
                        error_log("DEBUG: Verificación - ID de ficha en el modelo: '$idficVerificado'");
                        
                    } catch (Exception $e) {
                        error_log("ERROR: Error creando ficha temporal: " . $e->getMessage());
                        $errores[] = "Error creando ficha temporal: " . $e->getMessage();
                        continue; // Saltar al siguiente día
                    }
                } else {
                    // Si no hay instructor, no se puede crear ficha temporal
                    $errores[] = "Para actividades especiales se requiere seleccionar un instructor.";
                    continue;
                }
            } else {
                error_log("DEBUG: Usando ficha existente: $idfic");
                $mhor->setIdfic($idfic);
            }
            
            // Campos de transversales en false
            $mhor->setEsTransversal(false);
            $mhor->setNombreTransversal(null);
        } elseif ($es_transversal) {
            // Configuración para horarios "Transversales"
            $mhor->setEsOtros(false);
            $mhor->setActividad(null);
            $mhor->setHorasOtros(null);
            
            // Para transversales, el aula es obligatoria
            $mhor->setIdaul($idaul);
            
            // El instructor es opcional para transversales
            $mhor->setIdusu(!empty($idusu) ? $idusu : null);
            
            // Campos de transversales
            $mhor->setEsTransversal(true);
            $mhor->setNombreTransversal(trim($nombre_transversal));
            $mhor->setEsFormacionDirecta(1); // Los transversales siempre son formación (valor por defecto)
        } else {
            // Configuración para horarios normales
            $mhor->setEsOtros(false);
            $mhor->setActividad(null);
            $mhor->setHorasOtros(null);
            $mhor->setEsFormacionDirecta(1); // Los horarios normales siempre son formación (valor por defecto)
            $mhor->setIdaul($idaul); // Aula requerida para horarios normales
            $mhor->setIdusu($idusu);
            
            // Campos de transversales en false
            $mhor->setEsTransversal(false);
            $mhor->setNombreTransversal(null);
        }

        // Configurar fechas para todos los tipos de horario
        if ($fecha_inicio && $fecha_fin) {
            $mhor->setFechaInicio($fecha_inicio);
            $mhor->setFechaFin($fecha_fin);
            // Obsoleto: cálculo previo de horas del mes en cliente/controlador eliminado.
        }

        // Obtener nombre del día para mostrar en errores
        $nombreDia = $mhor->obtenerNombreDia($dia);

        // Verificar si ya existe horario para esta ficha en este día (mismo mes)
        $idhorExistente = $mhor->verificarHorarioExistente($idfic, $dia, $fecha);
        if ($idhorExistente) {
            $errores[] = "Ya existe un horario para esta ficha el día $nombreDia en este mes.";
            continue; // Saltar al siguiente día
        }

        // Verificar conflicto de instructor solo si se seleccionó uno
        if (!empty($idusu)) {
            $hayConflicto = $mhor->verificarConflictoInstructor($idusu, $idfic, $dia, $fecha);
            error_log("DEBUG: Verificando conflicto instructor - idusu: $idusu, idfic: $idfic, dia: $dia, fecha: $fecha, hayConflicto: " . ($hayConflicto ? 'true' : 'false'));
            
            if ($hayConflicto) {
                $errores[] = "Este instructor ya está asignado en este mismo horario con otra ficha el día $nombreDia.";
                continue;
            }
        }

        // Verificar conflicto de aula solo si se seleccionó una
        if (!empty($idaul) && $mhor->verificarConflictoAula($idaul, $idfic, $dia, $fecha)) {
            $tipoActividad = $es_otros ? "esta actividad especial" : ($es_transversal ? "otra transversal" : "otra ficha");
            $errores[] = "Esta aula ya está ocupada en este mismo horario con $tipoActividad el día $nombreDia.";
            continue;
        }

        // Si no hay errores, generar registros individuales por día
        try {
            // Obtener el ID de ficha correcto (puede ser temporal)
            $idficFinal = $mhor->getIdfic();
            
            // Generar registros individuales para cada día del horario
            if ($modo_individual) {
                // Modo individual: crear solo un registro para la fecha específica
                try {
                    $diasGenerados = generarRegistroIndividual($idficFinal, $idaul, $idusu, $dia, $es_transversal, $nombre_transversal, $es_otros, $actividad, $horas_otros, $es_formacion_directa, $fecha);
                    
                    if (!empty($diasGenerados)) {
                        $guardados++;
                        $totalHorariosCreados += count($diasGenerados);
                        
                        // Calcular y guardar horas mensuales solo si hay instructor
                        if ($idusu) {
                            // Modo individual: recalcular solo el mes de la fecha específica
                            $mes = date('n', strtotime($fecha));
                            $año = date('Y', strtotime($fecha));
                            $horasMes = calcularHorasMesParaInstructor($idusu, $mes, $año, $idficFinal, $dia);
                            if ($horasMes > 0) {
                                guardarHorasMensuales($idusu, $mes, $año, $horasMes);
                            }
                        }
                    } else {
                        error_log("No se generó registro individual para el día $dia");
                    }
                    
                } catch (Exception $e) {
                    error_log("Error generando registro individual: " . $e->getMessage());
                    $errores[] = "Error al generar horario individual: " . $e->getMessage();
                }
            } else if ($fecha_inicio && $fecha_fin) {
                try {
                    // Generar registros individuales para cada día del horario
                    $diasGenerados = generarRegistrosIndividuales($idficFinal, $idaul, $idusu, $dia, $es_transversal, $nombre_transversal, $es_otros, $actividad, $horas_otros, $es_formacion_directa, $fecha_inicio, $fecha_fin);
                    
                    if (!empty($diasGenerados)) {
                        $guardados++;
                        $totalHorariosCreados += count($diasGenerados);
                        
                        // Calcular y guardar horas mensuales solo si hay instructor
                        if ($idusu) {
                            // Modo rango: recalcular todos los meses afectados
                            $mesesAfectados = obtenerMesesEntreFechas($fecha_inicio, $fecha_fin);
                            foreach ($mesesAfectados as $mesInfo) {
                                $horasMes = calcularHorasMesParaInstructor($idusu, $mesInfo['mes'], $mesInfo['año'], $idficFinal, $dia);
                                if ($horasMes > 0) {
                                    guardarHorasMensuales($idusu, $mesInfo['mes'], $mesInfo['año'], $horasMes);
                                }
                            }
                        }
                    } else {
                        error_log("No se generaron registros individuales para el día $dia");
                    }
                    
                } catch (Exception $e) {
                    error_log("Error generando registros individuales: " . $e->getMessage());
                    $errores[] = "Error al generar horarios para el día $nombreDia: " . $e->getMessage();
                }
            } else {
                $errores[] = "Se requieren fechas de inicio y fin para generar el horario.";
            }
            
        } catch (Exception $e) {
            $errores[] = "Error al generar horarios para el día $nombreDia: " . $e->getMessage();
            // Agregar información de debugging
            error_log("Error en generación de horarios: " . $e->getMessage());
            error_log("Datos: idfic=$idfic, idaul=$idaul, idusu=$idusu, iddia=$dia, es_otros=$es_otros, es_transversal=$es_transversal");
        }
    }

    // Preparar mensajes para mostrar en JavaScript
    $mensajesResultado = [];

    if (!empty($errores)) {
        $mensajesResultado['errores'] = $errores;
    }

    if ($guardados > 0) {
        $mensaje = $es_otros ? 
            "Se guardaron $totalHorariosCreados actividad(es) especial(es) correctamente." :
            ($es_transversal ? 
                "Se guardaron $totalHorariosCreados horario(s) transversal(es) correctamente." :
                "Se guardaron $totalHorariosCreados horario(s) correctamente.");
        $mensajesResultado['exito'] = $mensaje;
        
        // Limpiar fichas temporales vacías SOLO después de guardar actividades especiales
        if ($es_otros) {
            limpiarFichasTemporalesVacias();
        }
    }
} elseif ($ope == 'get_horas_mensuales') {
    // Endpoint para obtener horas mensuales de un instructor
    $id_instructor = $_REQUEST['id_instructor'] ?? null;
    $año = $_REQUEST['año'] ?? date('Y'); // Ya en zona horaria de Colombia
    
    if (!$id_instructor) {
        echo json_encode(['success' => false, 'message' => 'ID de instructor requerido']);
        exit;
    }
    
    try {
        // Obtener horas mensuales desde la tabla horas_mensuales
        $sql = 'SELECT mes, año, horas_totales 
                FROM horas_mensuales 
                WHERE id_instructor = :id_instructor AND año = :año 
                ORDER BY mes ASC';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $id_instructor);
        $result->bindParam(':año', $año);
        $result->execute();
        
        $horasMensuales = $result->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true, 
            'horas_mensuales' => $horasMensuales,
            'total_registros' => count($horasMensuales)
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'eliminar_dia') {
    // Endpoint para eliminar un día específico del horario
    $id_horario = $_REQUEST['id_horario'] ?? null;
    $fecha_especifica = $_REQUEST['fecha_especifica'] ?? null;
    
    if (!$id_horario || !$fecha_especifica) {
        echo json_encode(['success' => false, 'message' => 'ID de horario y fecha requeridos']);
        exit;
    }
    
    try {
        $success = eliminarDiaHorario($id_horario, $fecha_especifica);
        
        if ($success) {
            echo json_encode([
                'success' => true, 
                'message' => "Día $fecha_especifica eliminado del horario"
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Error al eliminar el día del horario"
            ]);
        }
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'recalcular_horas') {
    // Endpoint para recalcular horas mensuales de un instructor
    $id_instructor = $_REQUEST['id_instructor'] ?? null;
    $año = $_REQUEST['año'] ?? date('Y'); // Ya en zona horaria de Colombia
    
    if (!$id_instructor) {
        echo json_encode(['success' => false, 'message' => 'ID de instructor requerido']);
        exit;
    }
    
    try {
        $success = recalcularHorasMensualesInstructor($id_instructor, $año);
        
        if ($success) {
            echo json_encode([
                'success' => true, 
                'message' => "Horas mensuales recalculadas para instructor $id_instructor, año $año"
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "Error al recalcular horas para instructor $id_instructor"
            ]);
        }
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'verificar_sincronizacion') {
    // Endpoint para verificar la sincronización entre "ver detalle" y reconteo anual
    $id_instructor = $_REQUEST['id_instructor'] ?? null;
    $mes = $_REQUEST['mes'] ?? date('n'); // Mes actual
    $año = $_REQUEST['año'] ?? date('Y'); // Año actual
    
    if (!$id_instructor) {
        echo json_encode(['success' => false, 'message' => 'ID de instructor requerido']);
        exit;
    }
    
    try {
        $resultado = verificarSincronizacionConteo($id_instructor, $mes, $año);
        
        if ($resultado) {
            echo json_encode([
                'success' => true, 
                'resultado' => $resultado,
                'message' => $resultado['sincronizado'] ? 
                    'Lógica sincronizada correctamente' : 
                    'Diferencia detectada en el conteo'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al verificar sincronización'
            ]);
        }
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'get_horarios_por_mes') {
    // Endpoint para obtener horarios del instructor en un MES usando la MISMA lógica que "Por Rango"
    $id_instructor = $_REQUEST['id_instructor'] ?? null;
    $mes = $_REQUEST['mes'] ?? date('n');
    $año = $_REQUEST['año'] ?? date('Y');
    
    if (!$id_instructor) {
        echo json_encode(['success' => false, 'message' => 'ID de instructor requerido']);
        exit;
    }
    
    try {
        $fecha_inicio = sprintf('%04d-%02d-01', (int)$año, (int)$mes);
        $fecha_fin = date('Y-m-t', strtotime($fecha_inicio));
        $mhorTmp = new Mhor();
        $resultado = calcularHorasRango($mhorTmp, $id_instructor, $fecha_inicio, $fecha_fin);
        
        echo json_encode([
            'success' => true, 
            'resumen' => $resultado['resumen'] ?? [],
            'detalle' => $resultado['detalle'] ?? [],
            'total_registros' => isset($resultado['detalle']) ? count($resultado['detalle']) : 0,
            'mes' => (int)$mes,
            'año' => (int)$año,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ], JSON_UNESCAPED_UNICODE);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
} elseif ($ope == 'get_total_horarios_instructor') {
    // Endpoint para obtener el total de horarios de un instructor
    $id_instructor = $_REQUEST['id_instructor'] ?? null;
    
    if (!$id_instructor) {
        echo json_encode(['success' => false, 'message' => 'ID de instructor requerido']);
        exit;
    }
    
    try {
        // Obtener total de horarios del instructor
        $sql = 'SELECT COUNT(*) as total
                FROM horario h
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $id_instructor);
        $result->execute();
        
        $total = $result->fetch(PDO::FETCH_ASSOC)['total'];
        
        echo json_encode([
            'success' => true, 
            'total' => (int)$total
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'debug_filtro') {
    // Endpoint para debugging del filtro de horarios
    $idfic = $_REQUEST['idfic'] ?? null;
    $iddia = $_REQUEST['iddia'] ?? null;
    $semana_selector = $_REQUEST['semana_selector'] ?? null;
    
    if (!$idfic || !$iddia) {
        echo json_encode(['success' => false, 'message' => 'Ficha y día son requeridos para debugging']);
        exit;
    }
    
    try {
        $mhor = new Mhor();
        $mhor->setIdfic($idfic);
        $mhor->setIddia($iddia);
        
        $debug = $mhor->debugFiltroHorarios($semana_selector);
        
        echo json_encode([
            'success' => true, 
            'debug' => $debug
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error en debugging: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'probar_filtro') {
    // Endpoint para probar el filtro corregido
    $idfic = $_REQUEST['idfic'] ?? null;
    $iddia = $_REQUEST['iddia'] ?? null;
    $semana_selector = $_REQUEST['semana_selector'] ?? null;
    
    if (!$idfic || !$iddia) {
        echo json_encode(['success' => false, 'message' => 'Ficha y día son requeridos para probar el filtro']);
        exit;
    }
    
    try {
        $mhor = new Mhor();
        $mhor->setIdfic($idfic);
        $mhor->setIddia($iddia);
        
        $resultado = $mhor->probarFiltro($semana_selector);
        
        echo json_encode([
            'success' => true, 
            'resultado' => $resultado
        ]);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error probando filtro: ' . $e->getMessage()]);
        exit;
    }
    
} elseif ($ope == 'edit') {
    // ==========================================
    // VALIDACIÓN DE PERMISOS - EDITAR HORARIOS
    // ==========================================
    $idusu_actual = $_SESSION["idusu"] ?? null;
    if (!$idusu_actual) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autenticado.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Validar permisos para editar horarios
    if (!validarPermisosUsuario($idusu_actual, 'editar')) {
        echo json_encode([
            'success' => false,
            'message' => 'No tienes permisos para editar horarios.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Lógica para editar horarios
    $idhor_edit = $_REQUEST['idhor'] ?? null;
    $nombre_transversal = $_REQUEST['nombre_transversal'] ?? null;
    $idaul = $_REQUEST['idaul'] ?? null;
    $idusu = $_REQUEST['idusu'] ?? null;
    $es_transversal = $_REQUEST['es_transversal'] ?? null;

    if (!$idhor_edit) {
        $errores[] = "ID de horario no válido para edición.";
    } elseif (!$nombre_transversal || !$idaul) {
        $errores[] = "Nombre de transversal y aula son requeridos.";
    } else {
        try {
            $mhor = new Mhor();
            $mhor->setIdhor($idhor_edit);
            
            // Para edición, necesitamos obtener el día actual del horario
            $horarioActual = $mhor->getOne();
            if ($horarioActual && !empty($horarioActual[0]['iddia'])) {
                $mhor->setIddia($horarioActual[0]['iddia']);
                $mhor->setIdfic($horarioActual[0]['idfic']);
                
                // Configurar campos para transversal
                $mhor->setEsOtros(false);
                $mhor->setActividad(null);
                $mhor->setHorasOtros(null);
                $mhor->setIdaul($idaul);
                
                // Manejar instructor (puede ser null o vacío)
                if (!empty($idusu) && $idusu !== 'null' && $idusu !== 'undefined') {
                    $mhor->setIdusu($idusu);
                } else {
                    $mhor->setIdusu(null);
                }
                
                $mhor->setEsTransversal(true);
                $mhor->setNombreTransversal(trim($nombre_transversal));
                
                $mhor->edit();
                
                // Respuesta de éxito
                echo json_encode(['success' => true, 'message' => 'Horario transversal actualizado correctamente.']);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo encontrar el horario a editar.']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
            exit;
        }
    }
    
    if (!empty($errores)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
        exit;
    }
} elseif ($ope == 'edit_grupo_transversal') {
    // Lógica para editar grupo de horarios transversales
    $idhor_edit = $_POST['idhor'] ?? null;
    $nombre_transversal = $_POST['nombre_transversal'] ?? null;
    $idaul = $_POST['idaul'] ?? null;
    $idusu = $_POST['idusu'] ?? null;
    $es_transversal = $_POST['es_transversal'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $idaul_original = $_POST['idaul_original'] ?? null;
    $idusu_original = $_POST['idusu_original'] ?? null;


    if (!$idhor_edit || !$nombre_transversal || !$idaul || !$fecha_inicio || !$fecha_fin) {
        $errores = [];
        if (!$idhor_edit) $errores[] = 'ID de horario faltante';
        if (!$nombre_transversal) $errores[] = 'Nombre de transversal faltante';
        if (!$idaul) $errores[] = 'Aula faltante';
        if (!$fecha_inicio) $errores[] = 'Fecha de inicio faltante';
        if (!$fecha_fin) $errores[] = 'Fecha de fin faltante';
        
        echo json_encode(['success' => false, 'message' => 'Campos faltantes: ' . implode(', ', $errores)]);
        exit;
    }

    try {
        // Obtener el horario base para extraer información
        $mhor = new Mhor();
        $mhor->setIdhor($idhor_edit);
        $horarioBase = $mhor->getOne();
        
        if (!$horarioBase || empty($horarioBase[0])) {
            echo json_encode(['success' => false, 'message' => 'No se pudo encontrar el horario base.']);
            exit;
        }
        
        $horarioInfo = $horarioBase[0];
        $idfic = $horarioInfo['idfic'];
        $iddia = $horarioInfo['iddia'];
        
        // Actualizar solo los horarios que realmente pertenecen al grupo original
        // Los criterios de agrupación son: misma ficha, mismo día, mismas fechas, 
        // mismo aula original, mismo instructor original y que sea transversal
        $sql = 'UPDATE horario SET 
                nombre_transversal = :nombre_transversal,
                idaul = :idaul,
                idusu = :idusu
                WHERE idfic = :idfic 
                AND iddia = :iddia 
                AND fecha_inicio = :fecha_inicio 
                AND fecha_fin = :fecha_fin
                AND es_transversal = 1';
        
        // Agregar criterios adicionales para ser más preciso
        if ($idaul_original !== null && $idaul_original !== '') {
            $sql .= ' AND idaul = :idaul_original';
        }
        if ($idusu_original !== null && $idusu_original !== '') {
            $sql .= ' AND idusu = :idusu_original';
        } else {
            // Si no hay instructor original, buscar horarios sin instructor
            $sql .= ' AND (idusu IS NULL OR idusu = "")';
        }
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        
        // Vincular solo los parámetros que están en la consulta SQL
        $result->bindParam(':nombre_transversal', $nombre_transversal);
        $result->bindParam(':idaul', $idaul);
        $result->bindParam(':idusu', $idusu);
        $result->bindParam(':idfic', $idfic);
        $result->bindParam(':iddia', $iddia);
        $result->bindParam(':fecha_inicio', $fecha_inicio);
        $result->bindParam(':fecha_fin', $fecha_fin);
        
        // Vincular parámetros adicionales para criterios precisos
        if ($idaul_original !== null && $idaul_original !== '') {
            $result->bindParam(':idaul_original', $idaul_original);
        }
        if ($idusu_original !== null && $idusu_original !== '') {
            $result->bindParam(':idusu_original', $idusu_original);
        }
        
        $result->execute();
        
        $filasAfectadas = $result->rowCount();
        
        if ($filasAfectadas > 0) {
            echo json_encode([
                'success' => true, 
                'message' => "Grupo de horarios transversales actualizado correctamente. Se afectaron $filasAfectadas horarios."
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron horarios para actualizar en el grupo.']);
        }
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar grupo: ' . $e->getMessage()]);
        exit;
    }
}

$m = 2;
$mhor->setIddia($iddia);

/**
 * Calcula las horas del mes actual para un horario
 */
// Obsoleto: calcularHorasMesActual eliminado. Usar get_horarios_por_mes / calcularHorasMesInstructorRango

/**
 * Calcula las horas mensuales para un instructor específico
 * Esta función usa la nueva lógica de días específicos y considera todos los tipos de horario
 * Sincronizada con la lógica de "ver detalle"
 */
function calcularHorasMesParaInstructor($idInstructor, $mes, $año, $idfic, $iddia) {
    try {
        // Obtener todos los horarios del instructor en el mes especificado
        // Usamos fecha_especifica para obtener días individuales según la nueva implementación
        $sql = 'SELECT h.*, f.jornada, v.nomval as nombre_jornada
                FROM horario h
                INNER JOIN ficha f ON h.idfic = f.idfic
                INNER JOIN valor v ON f.jornada = v.idval
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL
                AND MONTH(h.fecha_especifica) = :mes 
                AND YEAR(h.fecha_especifica) = :año';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $idInstructor);
        $result->bindParam(':mes', $mes);
        $result->bindParam(':año', $año);
        $result->execute();
        
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("Horarios encontrados para instructor $idInstructor, mes $mes, año $año: " . count($horarios));
        
        $horasTotales = 0;
        
        foreach ($horarios as $horario) {
            // Verificar que no sea festivo
            if (esFechaFestiva($horario['fecha_especifica'], $año)) {
                error_log("Horario ID {$horario['idhor']}: fecha {$horario['fecha_especifica']} es festivo, se omite");
                continue;
            }
            
            // Calcular horas por horario según el tipo (considerando el nuevo campo es_formacion_directa)
            if ($horario['es_transversal'] || $horario['es_otros'] == 0) {
                // Horario normal o transversal: usar jornada de la ficha - SIEMPRE FORMACIÓN
                $jornada = $horario['nombre_jornada'] ?? 'Mañana';
                $horasJornada = obtenerHorasJornada($jornada);
                $horasTotales += $horasJornada;
                
                error_log("Horario ID {$horario['idhor']}: jornada $jornada = $horasJornada horas (normal/transversal → FORMACIÓN)");
            } elseif ($horario['es_otros'] == 1 && isset($horario['es_formacion_directa']) && $horario['es_formacion_directa'] == 2) {
                // Actividad "otros" con formación directa: usar horas_otros - CUENTA COMO FORMACIÓN
                $horasOtros = $horario['horas_otros'] ?? 0;
                $horasTotales += $horasOtros;
                
                error_log("Horario ID {$horario['idhor']}: otros formación directa = $horasOtros horas → FORMACIÓN");
            } else {
                // Actividad "otros" tradicional: usar horas_otros - NO CUENTA PARA FORMACIÓN
                // NOTA: Estas horas NO se suman a $horasTotales porque no son formación
                $horasOtros = $horario['horas_otros'] ?? 0;
                
                error_log("Horario ID {$horario['idhor']}: otros tradicional = $horasOtros horas → NO CUENTA PARA FORMACIÓN");
            }
        }
        
        return $horasTotales;
        
    } catch (Exception $e) {
        error_log("Error calculando horas del instructor $idInstructor, mes $mes: " . $e->getMessage());
        return 0;
    }
}

/**
 * Obtiene las horas de una jornada específica
 * Según la lógica de negocio implementada en "ver detalle"
 */
function obtenerHorasJornada($jornada) {
    // Mapeo de jornadas a horas según la lógica de negocio
    $jornadas = [
        1042 => 6,  // Mañana: 6 horas
        1043 => 5,  // Tarde: 5 horas
        1044 => 4,  // Noche: 4 horas
        'Mañana' => 6,
        'Tarde' => 5,
        'Noche' => 4
    ];
    
    $horas = $jornadas[$jornada] ?? 6; // Default: Mañana (6 horas)
    error_log("DEBUG obtenerHorasJornada: jornada '$jornada' = $horas horas");
    return $horas;
}

// Cache para festivos por año
$festivosCache = [];

/**
 * Obtiene festivos de Colombia con cache local
 * @param int $año Año para obtener festivos
 * @return array Array de fechas festivas
 */
function obtenerFestivosConCache($año) {
    global $festivosCache;
    
    // Si ya está en cache, devolverlo
    if (isset($festivosCache[$año])) {
        return $festivosCache[$año];
    }
    
    // Verificar si existe archivo de cache local
    $cacheFile = __DIR__ . "/../cache/festivos_{$año}.json";
    $cacheDir = dirname($cacheFile);
    
    // Crear directorio de cache si no existe
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    // Verificar si el archivo de cache existe y es reciente (menos de 24 horas)
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
        $festivos = json_decode(file_get_contents($cacheFile), true);
        if (is_array($festivos)) {
            $festivosCache[$año] = $festivos;
            return $festivos;
        }
    }
    
    // Si no hay cache válido, obtener de la API
    $festivos = obtenerFestivosColombia($año);
    
    if (is_array($festivos) && !empty($festivos)) {
        // Guardar en cache local
        file_put_contents($cacheFile, json_encode($festivos, JSON_UNESCAPED_UNICODE));
        $festivosCache[$año] = $festivos;
        error_log("Festivos de $año obtenidos de API y guardados en cache");
    } else {
        // Si falla la API, usar festivos fijos como fallback
        $festivosFijos = [
            '01-01', '01-08', '03-25', '03-28', '03-29',
            '05-01', '05-13', '06-03', '06-10', '07-01',
            '07-20', '08-07', '08-15', '08-19', '10-14', '11-04',
            '11-11', '12-08', '12-25'
        ];
        
        $festivos = [];
        foreach ($festivosFijos as $festivo) {
            $festivos[] = $año . '-' . $festivo;
        }
        
        $festivosCache[$año] = $festivos;
        error_log("Usando festivos fijos para $año (API no disponible)");
    }
    
    return $festivosCache[$año];
}

/**
 * Verifica si una fecha es festiva en Colombia
 * @param string $fecha Fecha en formato Y-m-d
 * @param int $año Año para obtener festivos
 * @return bool True si es festivo, false si no
 */
function esFechaFestiva($fecha, $año) {
    try {
        $festivos = obtenerFestivosConCache($año);
        return in_array($fecha, $festivos);
    } catch (Exception $e) {
        error_log("Error verificando festivo para fecha $fecha: " . $e->getMessage());
        return false;
    }
}

/**
 * Filtra fechas excluyendo festivos y fines de semana
 * @param array $fechas Array de fechas en formato Y-m-d
 * @param int $año Año para verificar festivos
 * @return array Array de fechas laborales
 */
function filtrarFechasLaborales($fechas, $año) {
    $fechasLaborales = [];
    
    foreach ($fechas as $fecha) {
        $fechaObj = new DateTime($fecha);
        $diaSemana = $fechaObj->format('N'); // 1=Lunes, 7=Domingo
        
        // Solo incluir si es de lunes a viernes y no es festivo
        if ($diaSemana >= 1 && $diaSemana <= 5 && !esFechaFestiva($fecha, $año)) {
            $fechasLaborales[] = $fecha;
        }
    }
    
    return $fechasLaborales;
}

/**
 * Obtiene el nombre de una jornada a partir de su ID o nombre
 * Convierte IDs numéricos a nombres legibles
 */
function obtenerNombreJornada($jornada) {
    // Mapeo de IDs a nombres de jornada
    $nombresJornada = [
        1042 => 'Mañana',
        1043 => 'Tarde',
        1044 => 'Noche',
        'Mañana' => 'Mañana',
        'Tarde' => 'Tarde',
        'Noche' => 'Noche'
    ];
    
    $nombre = $nombresJornada[$jornada] ?? 'Mañana'; // Default: Mañana
    return $nombre;
}

/**
 * Guarda las horas mensuales en la tabla horas_mensuales
 */
function guardarHorasMensuales($idInstructor, $mes, $año, $horas) {
    try {
        // Primero verificar si ya existe un registro para este instructor/mes/año
        $sqlCheck = 'SELECT id, horas_totales FROM horas_mensuales 
                     WHERE id_instructor = :id_instructor AND mes = :mes AND año = :año';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        
        $resultCheck = $conexion->prepare($sqlCheck);
        $resultCheck->bindParam(':id_instructor', $idInstructor);
        $resultCheck->bindParam(':mes', $mes);
        $resultCheck->bindParam(':año', $año);
        $resultCheck->execute();
        
        $existingRecord = $resultCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($existingRecord) {
            // Actualizar registro existente
            $sql = 'UPDATE horas_mensuales 
                    SET horas_totales = :horas_totales, 
                        fecha_actualizacion = CURRENT_TIMESTAMP
                    WHERE id = :id';
            
            $result = $conexion->prepare($sql);
            $result->bindParam(':horas_totales', $horas);
            $result->bindParam(':id', $existingRecord['id']);
            
            
        } else {
            // Insertar nuevo registro
            $sql = 'INSERT INTO horas_mensuales (id_instructor, mes, año, horas_totales) 
                    VALUES (:id_instructor, :mes, :año, :horas_totales)';
            
            $result = $conexion->prepare($sql);
            $result->bindParam(':id_instructor', $idInstructor);
            $result->bindParam(':mes', $mes);
            $result->bindParam(':año', $año);
            $result->bindParam(':horas_totales', $horas);
            
        }
        
        $success = $result->execute();
        
        return $success;
        
    } catch (Exception $e) {
        error_log("Error guardando horas mensuales: " . $e->getMessage());
        return false;
    }
}

/**
 * Genera un registro individual para una fecha específica
 * Esta función crea un solo registro para el horario individual
 */
function generarRegistroIndividual($idfic, $idaul, $idusu, $iddia, $es_transversal, $nombre_transversal, $es_otros, $actividad, $horas_otros, $es_formacion_directa, $fecha_especifica) {
    try {
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        
        // Verificar si la columna existe
        $checkColumn = $conexion->prepare("SHOW COLUMNS FROM horario LIKE 'es_formacion_directa'");
        $checkColumn->execute();
        $columnExists = $checkColumn->rowCount() > 0;
        
        if ($columnExists) {
            // Si existe el campo, usar la consulta completa
            $sql = 'INSERT INTO horario (idfic, idaul, idusu, iddia, es_transversal, nombre_transversal, 
                                       es_otros, actividad, horas_otros, es_formacion_directa, fecha_especifica) 
                    VALUES (:idfic, :idaul, :idusu, :iddia, :es_transversal, :nombre_transversal, 
                            :es_otros, :actividad, :horas_otros, :es_formacion_directa, :fecha_especifica)';
        } else {
            // Si no existe el campo, usar consulta sin él
            $sql = 'INSERT INTO horario (idfic, idaul, idusu, iddia, es_transversal, nombre_transversal, 
                                       es_otros, actividad, horas_otros, fecha_especifica) 
                    VALUES (:idfic, :idaul, :idusu, :iddia, :es_transversal, :nombre_transversal, 
                            :es_otros, :actividad, :horas_otros, :fecha_especifica)';
        }
        
        $result = $conexion->prepare($sql);
        $result->bindParam(':idfic', $idfic);
        $result->bindParam(':idaul', $idaul);
        $result->bindParam(':idusu', $idusu);
        $result->bindParam(':iddia', $iddia);
        $result->bindParam(':es_transversal', $es_transversal);
        $result->bindParam(':nombre_transversal', $nombre_transversal);
        $result->bindParam(':es_otros', $es_otros);
        $result->bindParam(':actividad', $actividad);
        $result->bindParam(':horas_otros', $horas_otros);
        $result->bindParam(':fecha_especifica', $fecha_especifica);
        
        if ($columnExists) {
            $result->bindParam(':es_formacion_directa', $es_formacion_directa);
        }
        
        $success = $result->execute();
        
        if ($success) {
            error_log("Registro individual creado exitosamente para fecha: $fecha_especifica");
            return [$fecha_especifica];
        } else {
            error_log("Error creando registro individual para fecha: $fecha_especifica");
            return [];
        }
        
    } catch (Exception $e) {
        error_log("Error en generarRegistroIndividual: " . $e->getMessage());
        return [];
    }
}

/**
 * Genera registros individuales por día para un horario
 * Esta función crea un registro por cada día específico del horario
 */
function generarRegistrosIndividuales($idfic, $idaul, $idusu, $iddia, $es_transversal, $nombre_transversal, $es_otros, $actividad, $horas_otros, $es_formacion_directa, $fecha_inicio, $fecha_fin) {
    try {
        // Obtener los días específicos que corresponden al día de la semana
        $diasEspecificos = generarDiasDelHorario($fecha_inicio, $fecha_fin, $iddia);
                
        $registrosGenerados = [];
        
        foreach ($diasEspecificos as $fecha) {
            try {
                // Crear registro individual para este día
                // Verificar si el campo es_formacion_directa existe en la tabla
                $modelo = new Conexion();
                $conexion = $modelo->get_conexion();
                
                // Verificar si la columna existe
                $checkColumn = $conexion->prepare("SHOW COLUMNS FROM horario LIKE 'es_formacion_directa'");
                $checkColumn->execute();
                $columnExists = $checkColumn->rowCount() > 0;
                
                if ($columnExists) {
                    // Si existe el campo, usar la consulta completa
                    $sql = 'INSERT INTO horario (idfic, idaul, idusu, iddia, es_transversal, nombre_transversal, 
                                               es_otros, actividad, horas_otros, es_formacion_directa, fecha_inicio, fecha_fin, fecha_especifica) 
                            VALUES (:idfic, :idaul, :idusu, :iddia, :es_transversal, :nombre_transversal, 
                                    :es_otros, :actividad, :horas_otros, :es_formacion_directa, :fecha_inicio, :fecha_fin, :fecha_especifica)';
                } else {
                    // Si no existe el campo, usar consulta sin él
                $sql = 'INSERT INTO horario (idfic, idaul, idusu, iddia, es_transversal, nombre_transversal, 
                                           es_otros, actividad, horas_otros, fecha_inicio, fecha_fin, fecha_especifica) 
                        VALUES (:idfic, :idaul, :idusu, :iddia, :es_transversal, :nombre_transversal, 
                                :es_otros, :actividad, :horas_otros, :fecha_inicio, :fecha_fin, :fecha_especifica)';
                }
                
                $result = $conexion->prepare($sql);
                
                $result->bindParam(':idfic', $idfic);
                $result->bindParam(':idaul', $idaul);
                $result->bindParam(':idusu', $idusu);
                $result->bindParam(':iddia', $iddia);
                $result->bindParam(':es_transversal', $es_transversal);
                $result->bindParam(':nombre_transversal', $nombre_transversal);
                $result->bindParam(':es_otros', $es_otros);
                $result->bindParam(':actividad', $actividad);
                $result->bindParam(':horas_otros', $horas_otros);
                
                // Solo vincular es_formacion_directa si la columna existe
                if ($columnExists) {
                    // Asegurar que el valor no sea null
                    $valorFormacionDirecta = ($es_formacion_directa == 2) ? 2 : 1;
                    $result->bindParam(':es_formacion_directa', $valorFormacionDirecta);
                }
                
                $result->bindParam(':fecha_inicio', $fecha_inicio);
                $result->bindParam(':fecha_fin', $fecha_fin);
                $result->bindParam(':fecha_especifica', $fecha);
                
                if ($result->execute()) {
                    $registrosGenerados[] = $fecha;
                    error_log("Registro generado para fecha: $fecha");
                } else {
                    error_log("Error generando registro para fecha: $fecha");
                }
                
            } catch (Exception $e) {
                error_log("Error generando registro para fecha $fecha: " . $e->getMessage());
            }
        }
        
        return $registrosGenerados;
        
    } catch (Exception $e) {
        error_log("Error en generarRegistrosIndividuales: " . $e->getMessage());
        return [];
    }
}

/**
 * Genera los días específicos para un horario basado en el día de la semana
 */
function generarDiasDelHorario($fecha_inicio, $fecha_fin, $iddia) {
    $dias = [];
    
    try {
        // Configurar zona horaria de Colombia
        $zonaColombia = new DateTimeZone('America/Bogota');
        
        $inicio = new DateTime($fecha_inicio, $zonaColombia);
        $fin = new DateTime($fecha_fin, $zonaColombia);
        
        // Mapeo de días de la semana (CORREGIDO para format('N'))
        $diasSemana = [
            1042 => 1,  // Lunes
            1043 => 2,  // Martes
            1044 => 3,  // Miércoles
            1045 => 4,  // Jueves
            1046 => 5,  // Viernes
            1047 => 6,  // sbaado
            1048 => 7,  // domingo 
        ];
        
        $diaSemana = $diasSemana[$iddia] ?? 1; // Default a lunes si no se encuentra
        
        $fechaActual = clone $inicio;
        
        while ($fechaActual <= $fin) {
            // Si la fecha actual corresponde al día de la semana
            if ($fechaActual->format('N') == $diaSemana) {
                $dias[] = $fechaActual->format('Y-m-d');
                error_log("Día agregado: " . $fechaActual->format('Y-m-d') . " (" . $fechaActual->format('l') . ")");
            }
            
            $fechaActual->add(new DateInterval('P1D'));
        }
        
        return $dias;
        
    } catch (Exception $e) {
        error_log("Error generando días del horario: " . $e->getMessage());
        return [];
    }
}

/**
 * Recalcula todas las horas mensuales de un instructor para un año específico
 * Usa la nueva lógica de días específicos y está sincronizada con "ver detalle"
 * Útil para corregir datos o recalcular después de cambios
 */
function recalcularHorasMensualesInstructor($idInstructor, $año) {
    try {
        // Obtener todos los horarios del instructor para el año
        // Ahora usamos fecha_especifica para obtener días individuales
        $sql = 'SELECT h.*, f.jornada, v.nomval as nombre_jornada
                FROM horario h
                INNER JOIN ficha f ON h.idfic = f.idfic
                INNER JOIN valor v ON f.jornada = v.idval
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL
                AND YEAR(h.fecha_especifica) = :año
                ORDER BY h.fecha_especifica';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $idInstructor);
        $result->bindParam(':año', $año);
        $result->execute();
        
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($horarios)) {
            error_log("No se encontraron horarios para instructor $idInstructor en año $año");
            return false;
        }
        
        // Calcular horas para cada mes del año usando la nueva lógica
        for ($mes = 1; $mes <= 12; $mes++) {
            $horasMes = calcularHorasMesParaInstructor($idInstructor, $mes, $año, null, null);
            
            if ($horasMes > 0) {
                $success = guardarHorasMensuales($idInstructor, $mes, $año, $horasMes);
                if ($success) {
                    error_log("Mes $mes/$año: $horasMes horas guardadas para instructor $idInstructor (nueva lógica)");
                } else {
                    error_log("Error guardando mes $mes/$año para instructor $idInstructor");
                }
            } else {
                error_log("Mes $mes/$año: 0 horas para instructor $idInstructor");
            }
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Error en recálculo de horas mensuales: " . $e->getMessage());
        return false;
    }
}

/**
 * Función de prueba para verificar la sincronización de la lógica de conteo
 * Compara el conteo de "ver detalle" con el reconteo anual
 */
function verificarSincronizacionConteo($idInstructor, $mes, $año) {
    try {
        
        // Obtener conteo usando la nueva lógica (reconteo anual)
        $horasReconteo = calcularHorasMesParaInstructor($idInstructor, $mes, $año, null, null);
        
        // Obtener conteo usando la lógica de "ver detalle" (días específicos)
        $horasVerDetalle = calcularHorasVerDetalle($idInstructor, $mes, $año);
        
        $diferencia = abs($horasReconteo - $horasVerDetalle);
        $sincronizado = $diferencia == 0;
        
        error_log("Diferencia: $diferencia horas");
        error_log("¿Sincronizado?: " . ($sincronizado ? 'SÍ' : 'NO'));
        error_log("=== FIN VERIFICACIÓN ===");
        
        return [
            'sincronizado' => $sincronizado,
            'horas_reconteo' => $horasReconteo,
            'horas_ver_detalle' => $horasVerDetalle,
            'diferencia' => $diferencia
        ];
        
    } catch (Exception $e) {
        error_log("Error en verificación de sincronización: " . $e->getMessage());
        return false;
    }
}

/**
 * Calcula horas usando la lógica de "ver detalle" para comparación
 */
function calcularHorasVerDetalle($idInstructor, $mes, $año) {
    try {
        // Obtener horarios del instructor en el mes especificado
        $sql = 'SELECT h.*, f.jornada, v.nomval as nombre_jornada
                FROM horario h
                INNER JOIN ficha f ON h.idfic = f.idfic
                INNER JOIN valor v ON f.jornada = v.idval
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL
                AND MONTH(h.fecha_especifica) = :mes 
                AND YEAR(h.fecha_especifica) = :año';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $idInstructor);
        $result->bindParam(':mes', $mes);
        $result->bindParam(':año', $año);
        $result->execute();
        
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        
        $horasTotales = 0;
        
        foreach ($horarios as $horario) {
            // Usar la misma lógica que "ver detalle" considerando el nuevo campo es_formacion_directa
            if ($horario['es_transversal'] || $horario['es_otros'] == 0) {
                // Horario normal o transversal: usar jornada de la ficha - SIEMPRE FORMACIÓN
                $horasJornada = obtenerHorasJornada($horario['jornada']);
                $horasTotales += $horasJornada;
            } elseif ($horario['es_otros'] == 1 && isset($horario['es_formacion_directa']) && $horario['es_formacion_directa'] == 2) {
                // Actividad "otros" con formación directa: usar horas_otros - CUENTA COMO FORMACIÓN
                $horasOtros = $horario['horas_otros'] ?? 0;
                $horasTotales += $horasOtros;
            } else {
                // Actividad "otros" tradicional: NO CUENTA PARA FORMACIÓN
                // NOTA: No se suma a $horasTotales porque esta función calcula solo horas de formación
                $horasOtros = $horario['horas_otros'] ?? 0;
                // NO SUMAR: $horasTotales += $horasOtros;
            }
        }
        
        return $horasTotales;
        
    } catch (Exception $e) {
        error_log("Error calculando horas ver detalle: " . $e->getMessage());
        return 0;
    }
}

/**
 * Elimina un día específico del horario
 * Permite eliminar días individuales sin afectar el resto del horario
 */
function eliminarDiaHorario($idHorario, $fechaEspecifica) {
    try {
        error_log("Eliminando día $fechaEspecifica del horario $idHorario");
        
        // Buscar el registro específico con esa fecha
        $sql = 'SELECT idhor, idusu, fecha_especifica FROM horario 
                WHERE idhor = :id_horario AND fecha_especifica = :fecha_especifica';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_horario', $idHorario);
        $result->bindParam(':fecha_especifica', $fechaEspecifica);
        $result->execute();
        
        $horario = $result->fetch(PDO::FETCH_ASSOC);
        
        if (!$horario) {
            error_log("No se encontró el horario $idHorario para la fecha $fechaEspecifica");
            return false;
        }
        
        // Eliminar el registro específico
        $sqlDelete = 'DELETE FROM horario WHERE idhor = :id_horario AND fecha_especifica = :fecha_especifica';
        
        $resultDelete = $conexion->prepare($sqlDelete);
        $resultDelete->bindParam(':id_horario', $idHorario);
        $resultDelete->bindParam(':fecha_especifica', $fechaEspecifica);
        
        $success = $resultDelete->execute();
        
        if ($success) {
            error_log("Día $fechaEspecifica eliminado exitosamente del horario $idHorario");
            
            // Recalcular horas mensuales para el instructor usando la nueva lógica
            $idInstructor = $horario['idusu'];
            $mes = date('n', strtotime($fechaEspecifica));
            $año = date('Y', strtotime($fechaEspecifica));
            
            // Recalcular horas del mes afectado usando la nueva lógica de días específicos
            $horasMes = calcularHorasMesParaInstructor($idInstructor, $mes, $año, null, null);
            guardarHorasMensuales($idInstructor, $mes, $año, $horasMes);
            
            error_log("Horas recalculadas para instructor $idInstructor, mes $mes/$año: $horasMes (después de eliminar día)");
            
            return true;
        } else {
            error_log("Error eliminando día $fechaEspecifica del horario $idHorario");
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Error en eliminarDiaHorario: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtiene los meses entre dos fechas
 */
function obtenerMesesEntreFechas($fechaInicio, $fechaFin) {
    $meses = [];
    
    $inicio = new DateTime($fechaInicio);
    $fin = new DateTime($fechaFin);
    
    // Asegurar que fin sea el último día del mes
    $fin->modify('last day of this month');
    
    $periodo = new DatePeriod($inicio, new DateInterval('P1M'), $fin);
    
    foreach ($periodo as $fecha) {
        $meses[] = [
            'mes' => (int)$fecha->format('n'),
            'año' => (int)$fecha->format('Y')
        ];
    }
    
    return $meses;
}

if ($ope == 'del' && $idhor && $iddia) {
    // ==========================================
    // VALIDACIÓN DE PERMISOS - ELIMINAR HORARIOS
    // ==========================================
    $idusu_actual = $_SESSION["idusu"] ?? null;
    if (!$idusu_actual) {
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "Usuario no autenticado.";
        }
        exit;
    }
    
    // Validar permisos para eliminar horarios
    if (!validarPermisosUsuario($idusu_actual, 'eliminar')) {
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos para eliminar horarios.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "No tienes permisos para eliminar horarios.";
        }
        exit;
    }
    
    // Verificar si es una petición AJAX
    $isAjax = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    
    // Obtener información del horario antes de eliminarlo para verificar si es ficha temporal
    $horarioInfo = $mhor->getOne();
    $idficEliminado = null;
    
    if ($horarioInfo && !empty($horarioInfo[0])) {
        $idficEliminado = $horarioInfo[0]['idfic'];
    }
    
    $mhor->del();
    
    // Limpiar fichas temporales vacías después de eliminar horario
    limpiarFichasTemporalesVacias();
    
    if ($isAjax) {
        // Retornar JSON para peticiones AJAX
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => true,
            'message' => 'Horario eliminado exitosamente',
            'cantidad' => 1
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Redirigir SIEMPRE a la página de horarios principal para peticiones normales
        $redirect_url = generarUrlRedireccion('home.php?pg=1506');
        header("Location: " . $redirect_url);
        exit;
    }
}

if ($ope == 'del_grupo' && $idfic && $iddia && isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
    // ==========================================
    // VALIDACIÓN DE PERMISOS - ELIMINAR GRUPO
    // ==========================================
    $idusu_actual = $_SESSION["idusu"] ?? null;
    if (!$idusu_actual) {
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "Usuario no autenticado.";
        }
        exit;
    }
    
    // Validar permisos para eliminar horarios
    if (!validarPermisosUsuario($idusu_actual, 'eliminar')) {
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos para eliminar horarios.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "No tienes permisos para eliminar horarios.";
        }
        exit;
    }
    
    // Para perfiles restringidos, validar acceso a la ficha
    if (!validarAccesoFicha($idusu_actual, $idfic)) {
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'No tienes acceso a esta ficha.'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo "No tienes acceso a esta ficha.";
        }
        exit;
    }
    
    // Verificar si es una petición AJAX
    $isAjax = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    
    // idusu e idaul pueden estar vacíos (para horarios sin instructor o sin aula)
    $idusu = $_GET['idusu'] ?? '';
    $idaul = $_GET['idaul'] ?? '';
    
    // Contar horarios antes de eliminar
    $cantidadAntes = $mhor->contarHorariosGrupo($idfic, $iddia, $idusu, $idaul, $_GET['fecha_inicio'], $_GET['fecha_fin']);
    
    // Eliminar el grupo y obtener la cantidad real de horarios eliminados
    $cantidadEliminados = $mhor->delGrupo($idfic, $iddia, $idusu, $idaul, $_GET['fecha_inicio'], $_GET['fecha_fin']);
    
    // Si delGrupo retorna 0 pero había horarios, usar el conteo previo
    if ($cantidadEliminados == 0 && $cantidadAntes > 0) {
        $cantidadEliminados = $cantidadAntes;
    }
    
    // Limpiar fichas temporales vacías después de eliminar grupo
    limpiarFichasTemporalesVacias();
    
    if ($isAjax) {
        // Retornar JSON para peticiones AJAX
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => true,
            'message' => 'Grupo de horarios eliminado exitosamente',
            'cantidad' => $cantidadEliminados
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Redirigir SIEMPRE a la página de horarios principal para peticiones normales
        $redirect_url = generarUrlRedireccion('home.php?pg=1506');
        header("Location: " . $redirect_url);
        exit;
    }
}


if ($ope == 'edi' && $idhor) {
    $dtOne = $mhor->getOne();
    $m     = 1;
} else {
    $dtOne = null;
}
$dfi = $mhor->getFicha();
$ddi = $mhor->getDia();
$din = $mhor->getInst();
$dau = $mhor->getAula();
$dar = $mhor->getArea();

// Para perfiles restringidos (idper=4), filtrar por ficha asignada
$idusu_actual = $_SESSION["idusu"] ?? null;
$perfil_usuario = obtenerPerfilUsuario($idusu_actual);
$ficha_asignada = null;

if ($perfil_usuario && $perfil_usuario['idper'] == 4) {
    $ficha_asignada = obtenerFichaAsignadaUsuario($idusu_actual);
    if ($ficha_asignada) {
        // Filtrar datos para mostrar solo la ficha asignada
        $mhor->setIdfic($ficha_asignada);
        
        // Obtener el área de la ficha para establecerla en el modelo
        try {
            $sql = "SELECT a.idare FROM ficha f 
                    LEFT JOIN programa p ON f.codpro = p.codpro 
                    LEFT JOIN area a ON p.idare = a.idare 
                    WHERE f.idfic = :idfic";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idfic", $ficha_asignada);
            $result->execute();
            $area_data = $result->fetch(PDO::FETCH_ASSOC);
            
            if ($area_data && $area_data['idare']) {
                $mhor->setIdare($area_data['idare']);
            }
        } catch (Exception $e) {
            error_log("Error obteniendo área para perfil 4: " . $e->getMessage());
        }
    }
}

// Restaurar filtro de fichas activas por fecha actual
$dft = $mhor->getFiltro(date('Y-m-d'));

// Obtener horarios con fecha_especifica para el contador de horarios
$horarios_con_fecha = [];
try {
    $sql = 'SELECT h.idhor, h.idfic, h.idaul, h.idusu, h.iddia, h.fecha_especifica, h.es_otros, h.actividad, h.horas_otros, h.es_transversal, h.nombre_transversal,
                   f.nomfic, f.jornada, v.nomval as nombre_jornada, a.nomare, p.codpro,
                   u.ndocusu, u.nomusu, u.fecini, u.fecfin
            FROM horario h
            INNER JOIN ficha f ON h.idfic = f.idfic
            INNER JOIN valor v ON f.jornada = v.idval
            LEFT JOIN programa p ON f.codpro = p.codpro
            LEFT JOIN area a ON p.idare = a.idare
            LEFT JOIN usuario u ON h.idusu = u.idusu
            WHERE h.fecha_especifica IS NOT NULL
            ORDER BY h.fecha_especifica ASC';
    
    $modelo = new Conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->execute();
    $horarios_con_fecha = $result->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Horarios con fecha_especifica obtenidos: " . count($horarios_con_fecha));
} catch (Exception $e) {
    error_log("Error obteniendo horarios con fecha_especifica: " . $e->getMessage());
}

// Indexar fechas específicas por grupo (idfic-iddia-idaul-idusu) para el mes/año actual
$fechasPorGrupoMes = [];
try {
    $mesActualIdx = date('n');
    $añoActualIdx = date('Y');
    foreach ($horarios_con_fecha as $hcf) {
        if (empty($hcf['fecha_especifica'])) { continue; }
        $mesH = (int)date('n', strtotime($hcf['fecha_especifica']));
        $añoH = (int)date('Y', strtotime($hcf['fecha_especifica']));
        if ($mesH != (int)$mesActualIdx || $añoH != (int)$añoActualIdx) { continue; }
        $clave = ($hcf['idfic'] ?? '') . '_' . ($hcf['iddia'] ?? '') . '_' . (($hcf['idaul'] ?? '') ?: 'NULL') . '_' . (($hcf['idusu'] ?? '') ?: 'NULL');
        if (!isset($fechasPorGrupoMes[$clave])) { $fechasPorGrupoMes[$clave] = []; }
        $fechasPorGrupoMes[$clave][] = $hcf['fecha_especifica'];
    }
} catch (Exception $e) {
    error_log('WARNING indexando fechas por grupo: ' . $e->getMessage());
}

function pintit($ddi)
{
    if ($ddi) {
        foreach ($ddi as $d) {
            echo '<th>'.$d['nomval'].'</th>';
        }
    }
}

$filfav = new Mfilfav();
$idusu  = $_SESSION['idusu'] ?? null;

// Si no hay usuario en sesión, usar un valor por defecto o manejar el error
if (!$idusu) {
    error_log("WARNING: No hay usuario en sesión para operaciones de favoritos");
    $idusu = 0; // Valor por defecto
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idare'])) {
        $idarea = $_POST['idare'];

        if (isset($_POST['marcar_favorito']) && $_POST['marcar_favorito'] == '1') {
            $filfav->guardarFiltroFavorito($idusu, $idarea);
        }
    }
}

$areasFavoritas = $filfav->obtenerAreasFavoritas($idusu);

$instructores_horas   = [];
$instructores_info    = [];
$instructores_detalle = [];
$instructores_senova  = [];
$instructores_otros   = [];

// Función alternativa usando cURL para obtener festivos
function obtenerFestivosColombiaCurl($año) {
    if (!function_exists('curl_init')) {
        return false; // cURL no disponible
    }
    
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://date.nager.at/api/v3/PublicHolidays/{$año}/CO");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response !== false && $httpCode === 200) {
            $festivos = json_decode($response, true);
            if (is_array($festivos)) {
                $fechasFestivos = [];
                foreach ($festivos as $festivo) {
                    if (isset($festivo['date'])) {
                        $fechasFestivos[] = $festivo['date'];
                    }
                }
                return $fechasFestivos;
            }
        }
    } catch (Exception $e) {
        error_log("Error cURL al obtener festivos: " . $e->getMessage());
    }
    
    return false;
}

// Función para obtener festivos de Colombia usando API
function obtenerFestivosColombia($año) {
    // Primero intentar con file_get_contents
    try {
        // Configurar contexto para evitar warnings
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // 5 segundos de timeout
                'ignore_errors' => true
            ]
        ]);
        
        // Intentar obtener festivos desde la API
        $url = "https://date.nager.at/api/v3/PublicHolidays/{$año}/CO";
        $response = @file_get_contents($url, false, $context);
        
        if ($response !== false) {
            $festivos = json_decode($response, true);
            if (is_array($festivos)) {
                // Extraer solo las fechas
                $fechasFestivos = [];
                foreach ($festivos as $festivo) {
                    if (isset($festivo['date'])) {
                        $fechasFestivos[] = $festivo['date'];
                    }
                }
                return $fechasFestivos;
            }
        }
    } catch (Exception $e) {
        // Log del error pero no mostrar al usuario
        error_log("Error al obtener festivos con file_get_contents: " . $e->getMessage());
    }
    
    // Si file_get_contents falló, intentar con cURL
    $festivosCurl = obtenerFestivosColombiaCurl($año);
    if ($festivosCurl !== false) {
        return $festivosCurl;
    }
    
    // Si ambos fallaron, usar fallback con festivos fijos de Colombia
    $festivos = [
        '01-01', '01-08', '03-25', '03-28', '03-29',
        '05-01', '05-13', '06-03', '06-10', '07-01',
        '07-20', '08-07', '08-19', '10-14', '11-04',
        '11-11', '12-08', '12-25'
    ];
    
    $festivosCompletos = [];
    foreach ($festivos as $festivo) {
        $festivosCompletos[] = $año . '-' . $festivo;
    }
    
    return $festivosCompletos;
}

/**
 * Calcula los días laborales de un mes específico (omitiendo festivos y fines de semana)
 * @param int $mes Mes (1-12)
 * @param int $año Año
 * @return int Número de días laborales
 */
function calcularDiasLaboralesMes($mes, $año) {
    try {
        // Obtener festivos del año
        $festivos = obtenerFestivosConCache($año);
        
        // Crear objeto DateTime para el primer día del mes
        $fechaInicio = new DateTime("$año-$mes-01");
        $diasEnMes = $fechaInicio->format('t'); // Número de días en el mes
        
        $diasLaborales = 0;
        
        // Iterar por cada día del mes
        for ($dia = 1; $dia <= $diasEnMes; $dia++) {
            $fecha = new DateTime("$año-$mes-$dia");
            $diaSemana = $fecha->format('N'); // 1=Lunes, 7=Domingo
            $fechaStr = $fecha->format('Y-m-d');
            
            // Solo contar si es de lunes a viernes y no es festivo
            if ($diaSemana >= 1 && $diaSemana <= 5 && !in_array($fechaStr, $festivos)) {
                $diasLaborales++;
            }
        }
        
        return $diasLaborales;
        
    } catch (Exception $e) {
        error_log("Error calculando días laborales para $mes/$año: " . $e->getMessage());
        return 22; // Fallback: promedio de días laborales por mes
    }
}

/**
 * Calcula las horas esperadas para un mes basado en días laborales
 * @param int $mes Mes (1-12)
 * @param int $año Año
 * @return array Array con horas de formación directa, otros y total
 */
function calcularHorasEsperadasMes($mes, $año) {
    $diasLaborales = calcularDiasLaboralesMes($mes, $año);
    
    $horasFormacionDirecta = $diasLaborales * FACTOR_FORMACION;
    $horasOtros = $diasLaborales * FACTOR_OTROS;
    $horasTotales = $horasFormacionDirecta + $horasOtros;
    
    return [
        'dias_laborales' => $diasLaborales,
        'horas_formacion_directa' => round($horasFormacionDirecta, 1),
        'horas_otros' => round($horasOtros, 1),
        'horas_totales' => round($horasTotales, 1)
    ];
}

/**
 * Obtiene el nombre del mes en español
 * @param int $mes Mes (1-12)
 * @return string Nombre del mes
 */
function obtenerNombreMes($mes) {
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    
    return $meses[$mes] ?? 'Mes';
}

/**
 * Determina el tipo de contrato del usuario y el máximo de horas
 *
 * Regla:
 * - Si tiene registro activo en usu_dep con numero_contrato: contratista (max 180)
 * - En caso contrario, fallback a lógica anterior: si usuario.fecini y usuario.fecfin son NULL => planta, else contratista
 * - Para planta, calcula horas esperadas dinámicamente con calcularHorasEsperadasMes()
 *
 * @param int|string $idUsuario
 * @return array ['tipo_contrato' => 'planta'|'contratista', 'max_horas' => int, 'horas_esperadas' => array|null]
 */
function obtenerTipoContratoYMaxHoras($idUsuario) {
    try {
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();

        // Regla solicitada: si viene con fecha es contratista; si es NULL es planta
        $sqlUsu = 'SELECT fecini, fecfin FROM usuario WHERE idusu = :idusu LIMIT 1';
        $stmtUsu = $conexion->prepare($sqlUsu);
        $stmtUsu->bindParam(':idusu', $idUsuario);
        $stmtUsu->execute();
        $usu = $stmtUsu->fetch(PDO::FETCH_ASSOC);

        $fecini = $usu['fecini'] ?? null;
        $fecfin = $usu['fecfin'] ?? null;
        // Regla: contratista solo si fecini Y fecfin tienen valor; en cualquier otro caso es planta
        $esPlanta = (is_null($fecini) || is_null($fecfin));

        if ($esPlanta) {
            $mesActual = date('n');
            $añoActual = date('Y');
            $horasEsperadas = calcularHorasEsperadasMes($mesActual, $añoActual);
            $maxHoras = $horasEsperadas['horas_totales'];
            return [
                'tipo_contrato' => 'planta',
                'max_horas' => $maxHoras,
                'horas_esperadas' => $horasEsperadas
            ];
        }

        // Si tiene cualquier fecha en usuario, considerarlo contratista
        return [
            'tipo_contrato' => 'contratista',
            'max_horas' => CONTRATISTA_MAX_HORAS,
            'horas_esperadas' => null
        ];
    } catch (Exception $e) {
        error_log('ERROR obtenerTipoContratoYMaxHoras: ' . $e->getMessage());
        // Fallback seguro: contratista 180
        return [
            'tipo_contrato' => 'contratista',
            'max_horas' => CONTRATISTA_MAX_HORAS,
            'horas_esperadas' => null
        ];
    }
}

if ($dft && $ddi) {
    // Nota: cálculo de días laborales eliminado; se usa detalle real del backend por rango
    

    

    
    foreach ($dft as $dt) {
        $valor_nomval      = $dt['nomval'] ?? '';
        $max_horas_jornada = 0;
        $horas_por_dia     = 0;

        if ($valor_nomval == 'Tarde') {
            $max_horas_jornada = 25;
            $horas_por_dia     = 5;
        } elseif ($valor_nomval == 'Mañana') {
            $max_horas_jornada = 30;
            $horas_por_dia     = 6;
        } elseif ($valor_nomval == 'Noche') {
            $max_horas_jornada = 20;
            $horas_por_dia     = 4;
        }

        foreach ($ddi as $d) {
            $mhor->setIdfic($dt['idfic']);
            $mhor->setIddia($d['idval']);
            $dat = $mhor->getAll();

            if ($dat) {
                foreach ($dat as $dti) {
                    // Debug: ver qué campos tiene $dti
                    error_log("Campos disponibles en dti: " . implode(', ', array_keys($dti)));
                    error_log("dti completo: " . print_r($dti, true));
                    
                    $id_instructor = $dti['idusu'];

                    // Para transversales sin instructor, usar un ID temporal
                    if (empty($id_instructor) && !empty($dti['es_transversal']) && ($dti['es_transversal'] == 1 || $dti['es_transversal'] === 1 || $dti['es_transversal'] === true || $dti['es_transversal'] === '1')) {
                        $id_instructor = 'transversal_' . $dti['idhor']; // ID temporal para transversales sin instructor
                    }

                    if (!isset($instructores_horas[$id_instructor])) {
                        $instructores_horas[$id_instructor] = 0;
                        
                        if (strpos($id_instructor, 'transversal_') === 0) {
                            // Es un transversal sin instructor
                            $instructores_info[$id_instructor] = [
                                'nombre'        => 'Transversal sin instructor',
                                'documento'     => 'N/A',
                                'max_horas'     => 0, // No aplica para transversales sin instructor
                                'tipo_contrato' => 'transversal',
                            ];
                        } else {
                            // Es un instructor normal
                            // Determinar tipo de contrato de forma robusta (usu_dep -> usuario)
                            $contrato = obtenerTipoContratoYMaxHoras($id_instructor);
                            $tipoContrato = $contrato['tipo_contrato'];
                            $maxHoras = $contrato['max_horas'];
                            $horasEsperadas = $contrato['horas_esperadas'];
                            
                            $instructores_info[$id_instructor] = [
                                'nombre'        => $dti['ndocusu'] . ' - ' . $dti['nomusu'],
                                'documento'     => $dti['ndocusu'],
                                'max_horas'     => $maxHoras,
                                'tipo_contrato' => $tipoContrato,
                                'horas_esperadas' => $horasEsperadas, // Solo para instructores de planta
                            ];
                        }
                        
                        $instructores_detalle[$id_instructor] = [];
                        $instructores_senova[$id_instructor]  = 0;
                        $instructores_otros[$id_instructor]   = 0;
                    }

                    // Determinar si es SENOVA pero tambien hay que mirar como ajustarlo para que funcione
                    $es_senova = (strpos(strtoupper($dt['nomfic']), 'SENOVA') !== false) ||
                                 (strpos(strtoupper($dt['nomare']), 'SENOVA') !== false) ||
                                 (strpos(strtoupper($dt['codpro']), 'SENOVA') !== false);

                    // Determinar si es actividad "otros"
                    $es_otros = !empty($dti['es_otros']) && ($dti['es_otros'] == 1 || $dti['es_otros'] === '1');
                    
                    // Determinar si es transversal
                    $es_transversal = !empty($dti['es_transversal']) && ($dti['es_transversal'] == 1 || $dti['es_transversal'] === '1');
                    
                    // Determinar si es formación directa (para actividades "otros")
                    $es_formacion_directa = isset($dti['es_formacion_directa']) ? $dti['es_formacion_directa'] : 1;
                    
                    // Agregar detalle del horario
                    $instructores_detalle[$id_instructor][] = [
                        'ficha'        => $dt['idfic'],
                        'nombre_ficha' => $dt['nomfic'],
                        'jornada'      => $dt['nomval'],
                        'dia'          => $d['nomval'],
                        'aula'         => $dti['nomaul'],
                        'horas'        => $es_otros ? ($dti['horas_otros'] ?? $horas_por_dia) : $horas_por_dia,
                        'area'         => $dt['nomare'],
                        'codpro'       => $dt['codpro'],
                        'es_senova'    => $es_senova,
                        'es_otros'     => $es_otros,
                        'es_transversal' => $es_transversal,
                        'actividad'    => $dti['actividad'] ?? null,
                        'horas_otros'  => $dti['horas_otros'] ?? null,
                        'nombre_transversal' => $dti['nombre_transversal'] ?? null,
                        'fecha_especifica' => $dti['fecha_especifica'] ?? null,
                        'es_formacion_directa' => $es_formacion_directa, // Agregar para mostrar en detalle
                        'tipo_horas' => ($es_otros && $es_formacion_directa == 2) ? 'formacion_directa' : 
                                       ($es_otros ? 'otros' : 'formacion_normal') // Clasificación para mostrar
                    ];

                    // Contar horas SENOVA (solo para horarios normales, no para "otros")
                    if ($es_senova && !$es_otros) {
                        $instructores_senova[$id_instructor] += $horas_por_dia;
                    }

                    // Calcular días reales del mes actual usando fechas específicas existentes
                    $claveGrupo = ($dt['idfic'] ?? '') . '_' . ($d['idval'] ?? '') . '_' . (($dti['idaul'] ?? '') ?: 'NULL') . '_' . (($id_instructor ?? '') ?: 'NULL');
                    $fechasGrupo = $fechasPorGrupoMes[$claveGrupo] ?? [];
                    // Extraer días únicos (números) para detalle
                    $diasUnicos = [];
                    foreach ($fechasGrupo as $fesp) {
                        $diasUnicos[] = (int)date('j', strtotime($fesp));
                    }
                    $diasUnicos = array_values(array_unique($diasUnicos));
                    sort($diasUnicos);
                    $totalDias = count($diasUnicos);
                    

                    
                    // Contar horas según el tipo y configuración de formación directa
                    if (!$es_otros) {
                        // Horarios normales y transversales: siempre cuentan como formación
                        $horasCalculadas = $horas_por_dia * $totalDias;
                        $instructores_horas[$id_instructor] += $horasCalculadas;
                        error_log("DEBUG Conteo: Instructor $id_instructor - Horario normal/transversal: +$horasCalculadas horas formación");
                    } elseif ($es_otros && $es_formacion_directa == 2) {
                        // Actividades "otros" con formación directa: cuentan como FORMACIÓN
                        $horas_otros = !empty($dti['horas_otros']) ? $dti['horas_otros'] : $horas_por_dia;
                        $horasCalculadas = $horas_otros * $totalDias;
                        $instructores_horas[$id_instructor] += $horasCalculadas; // ← CAMBIO: suma a formación, no a otros
                        error_log("DEBUG Conteo: Instructor $id_instructor - Actividad especial FORMACIÓN DIRECTA: +$horasCalculadas horas formación");
                    } else {
                        // Actividades "otros" tradicionales: cuentan como otros
                        $horas_otros = !empty($dti['horas_otros']) ? $dti['horas_otros'] : $horas_por_dia;
                        $horasCalculadas = $horas_otros * $totalDias;
                        $instructores_otros[$id_instructor] += $horasCalculadas;
                        error_log("DEBUG Conteo: Instructor $id_instructor - Actividad especial OTROS: +$horasCalculadas horas otros");
                    }

                    // Nota: Los transversales se cuentan igual que los horarios normales
                    // ya que usan las horas de la jornada de la ficha
                    // Los transversales sin instructor también se cuentan normalmente
                }
            }
        }
    }
}

// Obtener eventos del calendario académico para el checkbox de rango por evento
$tiposEventos = $mhor->getTiposEventos();
$eventosCalendario = $mhor->getEventosPorTipo();

$instructores_tabla = [];

foreach ($instructores_horas as $id_instructor => $horas) {
    $horas_otros = $instructores_otros[$id_instructor] ?? 0;
    $horas_totales = $horas + $horas_otros;
    

    
    $instructores_tabla[] = [
        'id_instructor' => $id_instructor,
        'nombre'        => $instructores_info[$id_instructor]['nombre'],
        'documento'     => $instructores_info[$id_instructor]['documento'],
        'horas'         => $horas_totales, // Horas totales (formación + otros)
        'max_horas'     => $instructores_info[$id_instructor]['max_horas'],
        'tipo_contrato' => $instructores_info[$id_instructor]['tipo_contrato'],
        'horas_senova'  => $instructores_senova[$id_instructor]   ?? 0,
        'horas_otros'   => $horas_otros,
        'detalle'       => $instructores_detalle[$id_instructor]  ?? [],
        'horas_esperadas' => $instructores_info[$id_instructor]['horas_esperadas'] ?? null,
    ];
}

// Incluir instructores con historial en meses anteriores (para habilitar "Ver detalle" aunque el mes actual no tenga horarios)
try {
    $anioActual = date('Y');
    $modelo = new Conexion();
    $conexion = $modelo->get_conexion();

    // Instructores con fecha_especifica en el año
    $sqlHist = 'SELECT DISTINCT h.idusu 
                FROM horario h 
                WHERE h.idusu IS NOT NULL AND h.idusu <> ""
                AND (
                    (h.fecha_especifica IS NOT NULL AND YEAR(h.fecha_especifica) = :anio)
                    OR (h.fecha_especifica IS NULL AND (
                        (h.fecha_inicio IS NOT NULL AND YEAR(h.fecha_inicio) <= :anio) AND
                        (h.fecha_fin IS NOT NULL AND YEAR(h.fecha_fin) >= :anio)
                    ))
                )';
    $stmtHist = $conexion->prepare($sqlHist);
    $stmtHist->bindParam(':anio', $anioActual);
    $stmtHist->execute();
    $idsHist = $stmtHist->fetchAll(PDO::FETCH_COLUMN, 0);

    if (!empty($idsHist)) {
        // Obtener info de usuarios de la lista
        $idsFaltantes = [];
        foreach ($idsHist as $idu) {
            if (!isset($instructores_info[$idu])) {
                $idsFaltantes[] = $idu;
            }
        }

        if (!empty($idsFaltantes)) {
            // Construir placeholders
            $placeholders = implode(',', array_fill(0, count($idsFaltantes), '?'));
            $sqlUsu = 'SELECT idusu, ndocusu, nomusu, fecini, fecfin FROM usuario WHERE idusu IN (' . $placeholders . ')';
            $stmtUsu = $conexion->prepare($sqlUsu);
            foreach ($idsFaltantes as $idx => $val) {
                $stmtUsu->bindValue($idx + 1, $val);
            }
            $stmtUsu->execute();
            $usuarios = $stmtUsu->fetchAll(PDO::FETCH_ASSOC);

            foreach ($usuarios as $u) {
                $id = $u['idusu'];
                // Determinar tipo de contrato de forma robusta (usu_dep -> usuario)
                $contrato = obtenerTipoContratoYMaxHoras($id);
                $tipoContrato = $contrato['tipo_contrato'];
                $maxHoras = $contrato['max_horas'];
                $horasEsperadas = $contrato['horas_esperadas'];
                
                $instructores_info[$id] = [
                    'nombre'        => $u['ndocusu'] . ' - ' . $u['nomusu'],
                    'documento'     => $u['ndocusu'],
                    'max_horas'     => $maxHoras,
                    'tipo_contrato' => $tipoContrato,
                    'horas_esperadas' => $horasEsperadas, // Solo para instructores de planta
                ];
                $instructores_senova[$id] = 0;
                $instructores_otros[$id] = 0;
                $instructores_detalle[$id] = [];

                $instructores_tabla[] = [
                    'id_instructor' => $id,
                    'nombre'        => $instructores_info[$id]['nombre'],
                    'documento'     => $instructores_info[$id]['documento'],
                    'horas'         => 0,
                    'max_horas'     => $instructores_info[$id]['max_horas'],
                    'tipo_contrato' => $instructores_info[$id]['tipo_contrato'],
                    'horas_senova'  => 0,
                    'horas_otros'   => 0,
                    'detalle'       => [],
                    'horas_esperadas' => $instructores_info[$id]['horas_esperadas'] ?? null,
                ];
            }
        }
    }
} catch (Exception $e) {
    error_log('WARNING incluir historial instructores: ' . $e->getMessage());
}

/**
 * Calcula las horas del instructor para un rango específico
 * @param Mhor $mhor Instancia del modelo de horarios
 * @param string $idInstructor ID del instructor
 * @param string $fechaInicio Fecha de inicio del rango (YYYY-MM-DD)
 * @param string $fechaFin Fecha de fin del rango (YYYY-MM-DD)
 * @return array Array con resumen y detalle de horas del rango
 */
function calcularHorasRango($mhor, $idInstructor, $fechaInicio, $fechaFin) {
    try {
        error_log("DEBUG calcularHorasRango: Iniciando cálculo para instructor $idInstructor, rango $fechaInicio a $fechaFin");
        
        // Convertir fechas a objetos DateTime
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);
        
        // Calcular días del período
        $diasPeriodo = $fin->diff($inicio)->days + 1;
        error_log("DEBUG: Días del período: $diasPeriodo");
        
        // Obtener horarios del instructor en el rango de fechas
        $horarios = obtenerHorariosInstructorEnRango($mhor, $idInstructor, $fechaInicio, $fechaFin);
        error_log("DEBUG: Horarios obtenidos: " . count($horarios));
        
        // Calcular resumen de horas
        $resumen = calcularResumenHorasRango($horarios, $diasPeriodo);
        error_log("DEBUG: Resumen calculado: " . json_encode($resumen));
        
        // Preparar detalle para mostrar
        $detalle = prepararDetalleHorariosRango($horarios);
        error_log("DEBUG: Detalle preparado: " . count($detalle) . " elementos");
        
        return [
            'resumen' => $resumen,
            'detalle' => $detalle
        ];
        
    } catch (Exception $e) {
        error_log("Error calculando horas del rango: " . $e->getMessage());
        throw new Exception("Error al calcular horas del rango: " . $e->getMessage());
    }
}

/**
 * Obtiene los horarios de un instructor en un rango de fechas específico
 * @param Mhor $mhor Instancia del modelo de horarios
 * @param string $idInstructor ID del instructor
 * @param string $fechaInicio Fecha de inicio
 * @param string $fechaFin Fecha de fin
 * @return array Array de horarios
 */
function obtenerHorariosInstructorEnRango($mhor, $idInstructor, $fechaInicio, $fechaFin) {
    try {
        // Crear nueva instancia del modelo para evitar conflictos
        $mhorTemp = new Mhor();
        
        // Obtener todos los horarios del instructor usando la nueva función específica
        $horarios = $mhorTemp->getHorariosPorInstructor($idInstructor);
        
        
        // Filtrar por rango de fechas y agregar información adicional
        $horariosFiltrados = [];
        
        foreach ($horarios as $horario) {
            
            // Verificar si el horario está activo en el rango de fechas
            if (isset($horario['fecha_especifica']) && !empty($horario['fecha_especifica'])) {
                $fechaHorario = new DateTime($horario['fecha_especifica']);
                $inicio = new DateTime($fechaInicio);
                $fin = new DateTime($fechaFin);
                
                
                if ($fechaHorario >= $inicio && $fechaHorario <= $fin) {
                    // Verificar que no sea festivo
                    $año = $fechaHorario->format('Y');
                    if (!esFechaFestiva($horario['fecha_especifica'], $año)) {
                        // Agregar información adicional necesaria para el cálculo
                        // El campo 'jornada' ya contiene el ID numérico de la consulta SQL
                        // No sobrescribir, solo agregar el nombre si no existe
                        if (!isset($horario['jornada_nombre'])) {
                            $horario['jornada_nombre'] = $horario['nombre_jornada'] ?? 'Mañana';
                        }
                        $horariosFiltrados[] = $horario;
                    }
                    
                }
            }
        }
        
        return $horariosFiltrados;
        
    } catch (Exception $e) {
        error_log("Error obteniendo horarios del instructor: " . $e->getMessage());
        return [];
    }
}

/**
 * Calcula el resumen de horas para el rango
 * @param array $horarios Array de horarios filtrados
 * @param int $diasPeriodo Días totales del período
 * @return array Array con el resumen de horas
 */
function calcularResumenHorasRango($horarios, $diasPeriodo) {
    error_log("DEBUG calcularResumenHorasRango: Procesando " . count($horarios) . " horarios");
    
    $totalHoras = 0;
    $horasFormacion = 0;
    $horasOtros = 0;
    
    // Agrupar horarios por ficha y jornada para aplicar la lógica de reconteo
    $horariosAgrupados = [];
    
    foreach ($horarios as $horario) {
        $jornadaNombre = $horario['nombre_jornada'] ?? 'Mañana';
        // Incluir horas_otros en la clave para agrupar correctamente horarios "otros" con diferentes cantidades de horas
        $horasOtros = $horario['horas_otros'] ?? 0;
        $clave = $horario['idfic'] . '_' . $jornadaNombre . '_' . $horasOtros;
        
        if (!isset($horariosAgrupados[$clave])) {
            $horariosAgrupados[$clave] = [
                'idfic' => $horario['idfic'],
                'nomfic' => $horario['nomfic'],
                'jornada' => $jornadaNombre,
                'es_otros' => $horario['es_otros'],
                'horas_otros' => $horario['horas_otros'],
                'es_transversal' => $horario['es_transversal'],
                'es_formacion_directa' => $horario['es_formacion_directa'] ?? 1, // AGREGAR CAMPO FALTANTE
                'count' => 0
            ];
        }
        
        $horariosAgrupados[$clave]['count']++;
    }
    
    error_log("DEBUG: Horarios agrupados por ficha y jornada: " . count($horariosAgrupados));
    
    // Calcular horas usando la misma lógica que "Detalle Mensual"
    foreach ($horariosAgrupados as $clave => $grupo) {
        error_log("DEBUG: Procesando grupo $clave: " . json_encode($grupo));
        
        if (!empty($grupo['es_otros']) && $grupo['es_otros'] == 1) {
            // Actividad "otros": verificar si es formación directa
            $horasOtrosGrupo = $grupo['horas_otros'] ?? 0;
            $horasTotal = $horasOtrosGrupo * $grupo['count'];
            
            if (isset($grupo['es_formacion_directa']) && $grupo['es_formacion_directa'] == 2) {
                // Actividad "otros" con formación directa: contar como formación
                $horasFormacion += $horasTotal;
                error_log("DEBUG: Grupo 'otros formación directa' - $grupo[count] días × $horasOtrosGrupo horas = $horasTotal horas");
            } else {
                // Actividad "otros" tradicional: contar como otros
            $horasOtros += $horasTotal;
                error_log("DEBUG: Grupo 'otros tradicional' - $grupo[count] días × $horasOtrosGrupo horas = $horasTotal horas");
            }
        } else {
            // Horario normal o transversal: multiplicar horas de jornada por el número de días
            $jornada = $grupo['jornada'];
            $horasJornada = obtenerHorasJornada($jornada);
            $horasTotal = $horasJornada * $grupo['count'];
            $horasFormacion += $horasTotal;
            error_log("DEBUG: Grupo normal/transversal - jornada: $jornada, $grupo[count] días × $horasJornada horas = $horasTotal horas");
        }
        
        $totalHoras += $horasTotal;
        error_log("DEBUG: Total acumulado: $totalHoras");
    }
    
    $resumen = [
        'total_horas' => $totalHoras,
        'horas_formacion' => $horasFormacion,
        'horas_otros' => $horasOtros,
        'dias_periodo' => $diasPeriodo
    ];
    
    error_log("DEBUG: Resumen final: " . json_encode($resumen));
    return $resumen;
}

/**
 * Prepara el detalle de horarios para mostrar en la interfaz
 * @param array $horarios Array de horarios filtrados
 * @return array Array con el detalle formateado
 */
function prepararDetalleHorariosRango($horarios) {
    error_log("DEBUG prepararDetalleHorariosRango: Preparando detalle para " . count($horarios) . " horarios");
    
    $detalle = [];
    
    // Agrupar horarios por ficha y jornada para mostrar correctamente
    $horariosAgrupados = [];
    
    foreach ($horarios as $horario) {
        $jornadaNombre = $horario['nombre_jornada'] ?? 'Mañana';
        // Incluir horas_otros en la clave para agrupar correctamente horarios "otros" con diferentes cantidades de horas
        $horasOtros = $horario['horas_otros'] ?? 0;
        $clave = $horario['idfic'] . '_' . $jornadaNombre . '_' . $horasOtros;
        
        if (!isset($horariosAgrupados[$clave])) {
            $horariosAgrupados[$clave] = [
                'idfic' => $horario['idfic'],
                'nomfic' => $horario['nomfic'],
                'jornada' => $jornadaNombre,
                'es_otros' => $horario['es_otros'],
                'horas_otros' => $horario['horas_otros'],
                'es_transversal' => $horario['es_transversal'],
                'es_formacion_directa' => $horario['es_formacion_directa'] ?? 1, // AGREGAR CAMPO FALTANTE
                'count' => 0,
                'fechas' => []
            ];
        }
        
        $horariosAgrupados[$clave]['count']++;
        
        // Agregar fecha específica si no está ya incluida
        if (!empty($horario['fecha_especifica'])) {
            $fecha = new DateTime($horario['fecha_especifica']);
            $dia = $fecha->format('j'); // Solo el día del mes (21, 22, 23)
            if (!in_array($dia, $horariosAgrupados[$clave]['fechas'])) {
                $horariosAgrupados[$clave]['fechas'][] = $dia;
            }
        }
    }
    
    error_log("DEBUG: Horarios agrupados para detalle: " . count($horariosAgrupados));
    
    // Crear detalle agrupado por ficha y jornada
    foreach ($horariosAgrupados as $clave => $grupo) {
        error_log("DEBUG: Preparando detalle para grupo $clave: " . json_encode($grupo));
        
        // Calcular horas según el tipo de horario y número de días
        $horas = 0;
        $tipoHoras = 'formacion'; // Por defecto es formación
        
        if (!empty($grupo['es_otros']) && $grupo['es_otros'] == 1) {
            // Es actividad especial - verificar si es formación directa
            $horas = (!empty($grupo['horas_otros']) ? $grupo['horas_otros'] : 0) * $grupo['count'];
            
            if (isset($grupo['es_formacion_directa']) && $grupo['es_formacion_directa'] == 2) {
                $tipoHoras = 'formacion_directa'; // Formación directa
                error_log("DEBUG prepararDetalle: Actividad especial FORMACIÓN DIRECTA - $horas horas");
        } else {
                $tipoHoras = 'otros'; // Otros tradicional
                error_log("DEBUG prepararDetalle: Actividad especial OTROS - $horas horas");
            }
        } else {
            // Horario normal o transversal - siempre formación
            $jornada = $grupo['jornada'];
            $horasJornada = obtenerHorasJornada($jornada);
            $horas = $horasJornada * $grupo['count'];
            $tipoHoras = 'formacion';
            error_log("DEBUG prepararDetalle: Horario normal/transversal - $horas horas FORMACIÓN");
        }
        
        // Obtener nombre de jornada (Mañana, Tarde, Noche)
        $nombreJornada = obtenerNombreJornada($grupo['jornada']);
        
        // Ordenar las fechas numéricamente
        sort($horariosAgrupados[$clave]['fechas'], SORT_NUMERIC);
        $fechasTexto = implode(', ', $horariosAgrupados[$clave]['fechas']);
        
        $detalleItem = [
            'ficha' => $grupo['idfic'] . ' - ' . ($grupo['nomfic'] ?? ''),
            'dia' => $fechasTexto, // Mostrar días específicos (21, 22, 23)
            'jornada' => $nombreJornada, // Usar nombre de jornada
            'horas' => $horas,
            'es_transversal' => !empty($grupo['es_transversal']) && $grupo['es_transversal'] == 1,
            'es_otros' => !empty($grupo['es_otros']) && $grupo['es_otros'] == 1,
            'es_formacion_directa' => $grupo['es_formacion_directa'] ?? 1, // AGREGAR CAMPO
            'tipo_horas' => $tipoHoras, // AGREGAR CLASIFICACIÓN: 'formacion', 'formacion_directa', 'otros'
            'dias_count' => $grupo['count'] // Número de días para este grupo
        ];
        
        $detalle[] = $detalleItem;
        error_log("DEBUG: Detalle preparado para grupo $clave: " . json_encode($detalleItem));
    }
    
    error_log("DEBUG: Total elementos de detalle preparados: " . count($detalle));
    return $detalle;
}

/**
 * Calcula las horas mensuales para un instructor específico usando la lógica de agrupación por ficha y jornada
 * Esta función implementa la misma lógica que "Por Rango" para garantizar consistencia
 * @param string $idInstructor ID del instructor
 * @param int $mes Mes (1-12)
 * @param int $año Año
 * @return int Total de horas del mes
 */
function calcularHorasMesInstructorRango($idInstructor, $mes, $año) {
    try {
        error_log("DEBUG calcularHorasMesInstructorRango: Calculando horas para instructor $idInstructor, mes $mes, año $año");
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();

        // 1) Horarios con fecha_especifica en el mes
        $sqlFE = 'SELECT h.idhor, h.idfic, h.idusu, h.fecha_especifica, h.es_otros, h.horas_otros, h.es_transversal, h.es_formacion_directa,
                       f.jornada, v.nomval as nombre_jornada
                FROM horario h
                  LEFT JOIN ficha f ON h.idfic = f.idfic
                  LEFT JOIN valor v ON f.jornada = v.idval
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL
                AND MONTH(h.fecha_especifica) = :mes 
                  AND YEAR(h.fecha_especifica) = :año';
        $stmtFE = $conexion->prepare($sqlFE);
        $stmtFE->bindParam(':id_instructor', $idInstructor);
        $stmtFE->bindParam(':mes', $mes);
        $stmtFE->bindParam(':año', $año);
        $stmtFE->execute();
        $horarios = $stmtFE->fetchAll(PDO::FETCH_ASSOC);

        // 2) Incluir horarios legacy (sin fecha_especifica) cuyo rango traslape el mes seleccionado
        $primerDiaMes = sprintf('%04d-%02d-01', (int)$año, (int)$mes);
        $ultimoDiaMes = date('Y-m-t', strtotime($primerDiaMes));

        $sqlLegacy = 'SELECT h.idhor, h.idfic, h.idusu, h.iddia, h.fecha_inicio, h.fecha_fin,
                             h.es_otros, h.horas_otros, h.es_transversal, h.es_formacion_directa,
                             f.jornada, v.nomval as nombre_jornada, f.finific as ficha_inicio, f.ffinfic as ficha_fin
                      FROM horario h
                      LEFT JOIN ficha f ON h.idfic = f.idfic
                      LEFT JOIN valor v ON f.jornada = v.idval
                      WHERE h.idusu = :id_instructor
                      AND h.fecha_especifica IS NULL
                      AND h.fecha_inicio <= :fin_mes
                      AND h.fecha_fin >= :inicio_mes';
        $stmtLegacy = $conexion->prepare($sqlLegacy);
        $stmtLegacy->bindParam(':id_instructor', $idInstructor);
        $stmtLegacy->bindParam(':inicio_mes', $primerDiaMes);
        $stmtLegacy->bindParam(':fin_mes', $ultimoDiaMes);
        $stmtLegacy->execute();
        $legacy = $stmtLegacy->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($legacy)) {
            $mapDia = [
                1042 => 1,
                1043 => 2,
                1044 => 3,
                1045 => 4,
                1046 => 5,
                1047 => 6,
                1048 => 7,
            ];
            foreach ($legacy as $h) {
                $inicio = max(strtotime($primerDiaMes), strtotime($h['fecha_inicio']));
                $fin = min(strtotime($ultimoDiaMes), strtotime($h['fecha_fin']));
                if (!empty($h['ficha_inicio'])) {
                    $inicio = max($inicio, strtotime($h['ficha_inicio']));
                }
                if (!empty($h['ficha_fin'])) {
                    $fin = min($fin, strtotime($h['ficha_fin']));
                }
                if ($inicio > $fin) { continue; }
                $diaN = $mapDia[$h['iddia']] ?? null;
                if ($diaN === null) { continue; }
                for ($t = $inicio; $t <= $fin; $t = strtotime('+1 day', $t)) {
                    if ((int)date('N', $t) === (int)$diaN) {
                        $fechaEspecifica = date('Y-m-d', $t);
                        
                        // Verificar que no sea festivo
                        if (!esFechaFestiva($fechaEspecifica, $año)) {
                            $horarios[] = [
                                'idhor' => $h['idhor'],
                                'idfic' => $h['idfic'],
                                'idusu' => $h['idusu'],
                                'fecha_especifica' => $fechaEspecifica,
                                'es_otros' => $h['es_otros'],
                                'horas_otros' => $h['horas_otros'],
                                'es_transversal' => $h['es_transversal'],
                                'es_formacion_directa' => $h['es_formacion_directa'] ?? 1,
                                'jornada' => $h['jornada'],
                                'nombre_jornada' => $h['nombre_jornada'],
                            ];
                        }
                    }
                }
            }
        }

        // 3) Incluir horarios muy legacy (sin fecha_especifica y sin rango) activos por ficha y día
        $sqlMuyLegacy = 'SELECT h.idhor, h.idfic, h.idusu, h.iddia, h.es_otros, h.horas_otros, h.es_transversal, h.es_formacion_directa,
                                 f.jornada, v.nomval as nombre_jornada, f.finific as ficha_inicio, f.ffinfic as ficha_fin
                         FROM horario h
                         LEFT JOIN ficha f ON h.idfic = f.idfic
                         LEFT JOIN valor v ON f.jornada = v.idval
                         WHERE h.idusu = :id_instructor
                         AND h.fecha_especifica IS NULL
                         AND (h.fecha_inicio IS NULL OR h.fecha_inicio = "")
                         AND (h.fecha_fin IS NULL OR h.fecha_fin = "")';
        $stmtMuyLegacy = $conexion->prepare($sqlMuyLegacy);
        $stmtMuyLegacy->bindParam(':id_instructor', $idInstructor);
        $stmtMuyLegacy->execute();
        $muyLegacy = $stmtMuyLegacy->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($muyLegacy)) {
            $mapDia = [
                1042 => 1,
                1043 => 2,
                1044 => 3,
                1045 => 4,
                1046 => 5,
                1047 => 6,
                1048 => 7,
            ];
            foreach ($muyLegacy as $h) {
                $inicio = strtotime($primerDiaMes);
                $fin = strtotime($ultimoDiaMes);
                if (!empty($h['ficha_inicio'])) {
                    $inicio = max($inicio, strtotime($h['ficha_inicio']));
                }
                if (!empty($h['ficha_fin'])) {
                    $fin = min($fin, strtotime($h['ficha_fin']));
                }
                if ($inicio > $fin) { continue; }
                $diaN = $mapDia[$h['iddia']] ?? null;
                if ($diaN === null) { continue; }
                for ($t = $inicio; $t <= $fin; $t = strtotime('+1 day', $t)) {
                    if ((int)date('N', $t) === (int)$diaN) {
                        $fechaEspecifica = date('Y-m-d', $t);
                        
                        // Verificar que no sea festivo
                        if (!esFechaFestiva($fechaEspecifica, $año)) {
                            $horarios[] = [
                                'idhor' => $h['idhor'],
                                'idfic' => $h['idfic'],
                                'idusu' => $h['idusu'],
                                'fecha_especifica' => $fechaEspecifica,
                                'es_otros' => $h['es_otros'],
                                'horas_otros' => $h['horas_otros'],
                                'es_transversal' => $h['es_transversal'],
                                'es_formacion_directa' => $h['es_formacion_directa'] ?? 1,
                                'jornada' => $h['jornada'],
                                'nombre_jornada' => $h['nombre_jornada'],
                            ];
                        }
                    }
                }
            }
        }

        error_log("DEBUG: Horarios combinados (incluye legacy) para mes $mes: " . count($horarios));
        
        if (empty($horarios)) {
            error_log("DEBUG: No se encontraron horarios para instructor $idInstructor en mes $mes/$año");
            error_log("DEBUG: Verificar que existan horarios con fecha_especifica para este instructor");
            return 0;
        }
        
        // Mostrar los primeros 3 horarios para debugging
        error_log("DEBUG: Primeros 3 horarios obtenidos:");
        for ($i = 0; $i < min(3, count($horarios)); $i++) {
            error_log("DEBUG: Horario $i: " . json_encode($horarios[$i]));
        }
        
        // Agrupar horarios por ficha y jornada (misma lógica que "Por Rango")
        $horariosAgrupados = [];
        
        foreach ($horarios as $horario) {
            $jornadaNombre = $horario['nombre_jornada'] ?? 'Mañana';
            // Incluir horas_otros en la clave para agrupar correctamente horarios "otros" con diferentes cantidades de horas
            $horasOtros = $horario['horas_otros'] ?? 0;
            $clave = $horario['idfic'] . '_' . $jornadaNombre . '_' . $horasOtros;
            
            if (!isset($horariosAgrupados[$clave])) {
                $horariosAgrupados[$clave] = [
                    'idfic' => $horario['idfic'],
                    'jornada' => $jornadaNombre,
                    'count' => 0,
                    'es_otros' => $horario['es_otros'],
                    'horas_otros' => $horario['horas_otros'],
                    'es_transversal' => $horario['es_transversal'],
                    'es_formacion_directa' => $horario['es_formacion_directa'] ?? 1
                ];
            }
            
            $horariosAgrupados[$clave]['count']++;
        }
        
        error_log("DEBUG: Horarios agrupados por ficha y jornada: " . count($horariosAgrupados));
        
        // Mostrar cada grupo para debugging
        foreach ($horariosAgrupados as $clave => $grupo) {
            error_log("DEBUG: Grupo $clave: " . json_encode($grupo));
        }
        
        // Calcular horas usando la misma lógica que "Por Rango"
        $totalHoras = 0;
        
        foreach ($horariosAgrupados as $clave => $grupo) {
            error_log("DEBUG: Procesando grupo $clave: " . json_encode($grupo));
            
            if (!empty($grupo['es_otros']) && $grupo['es_otros'] == 1) {
                // Actividad "otros": verificar si es formación directa
                $horasOtros = $grupo['horas_otros'] ?? 0;
                $horasTotal = $horasOtros * $grupo['count'];
                
                if (isset($grupo['es_formacion_directa']) && $grupo['es_formacion_directa'] == 2) {
                    // Formación directa: SÍ suma a total de horas de formación
                $totalHoras += $horasTotal;
                    error_log("DEBUG: Grupo 'otros FORMACIÓN DIRECTA' - $grupo[count] días × $horasOtros horas = $horasTotal horas → SUMA A FORMACIÓN");
                } else {
                    // Otros tradicional: NO suma a total de horas de formación
                    error_log("DEBUG: Grupo 'otros tradicional' - $grupo[count] días × $horasOtros horas = $horasTotal horas → NO SUMA A FORMACIÓN");
                }
            } else {
                // Horario normal o transversal: multiplicar horas de jornada por el número de días
                $jornada = $grupo['jornada'];
                $horasJornada = obtenerHorasJornada($jornada);
                $horasTotal = $horasJornada * $grupo['count'];
                $totalHoras += $horasTotal;
                error_log("DEBUG: Grupo normal/transversal - jornada: $jornada, $grupo[count] días × $horasJornada horas = $horasTotal horas → SUMA A FORMACIÓN");
            }
        }
        
        error_log("DEBUG: Total horas calculadas para instructor $idInstructor, mes $mes/$año: $totalHoras");
        return $totalHoras;
        
    } catch (Exception $e) {
        error_log("Error calculando horas del instructor $idInstructor, mes $mes: " . $e->getMessage());
        return 0;
    }
}

// Manejo temprano para obtener horas mensuales del instructor (nueva lógica)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'get_horas_mes_instructor') {
    $idInstructor = $_POST['id_instructor'] ?? null;
    $mes = $_POST['mes'] ?? null;
    $año = $_POST['año'] ?? null;
    
    header('Content-Type: application/json; charset=UTF-8');
    
    if (!$idInstructor || !$mes || !$año) {
        echo json_encode([
            'success' => false,
            'message' => 'Parámetros incompletos: id_instructor, mes, año'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $horas = calcularHorasMesInstructorRango($idInstructor, $mes, $año);
        echo json_encode([
            'success' => true,
            'horas' => $horas,
            'instructor' => $idInstructor,
            'mes' => $mes,
            'año' => $año
        ], JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error calculando horas: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

/**
 * Función de prueba para verificar la obtención de datos de la base de datos
 * Muestra exactamente qué horarios se están obteniendo para debugging
 * @param string $idInstructor ID del instructor
 * @param int $mes Mes (1-12)
 * @param int $año Año
 * @return array Información detallada de debugging
 */
function debugHorariosInstructor($idInstructor, $mes, $año) {
    try {
        error_log("=== DEBUG HORARIOS INSTRUCTOR ===");
        error_log("Instructor: $idInstructor, Mes: $mes, Año: $año");
        
        // Consulta SQL para obtener horarios del instructor
        $sql = 'SELECT h.idhor, h.idfic, h.idusu, h.fecha_especifica, h.es_otros, h.horas_otros, h.es_transversal, h.es_formacion_directa,
                       f.jornada, v.nomval as nombre_jornada
                FROM horario h
                INNER JOIN ficha f ON h.idfic = f.idfic
                INNER JOIN valor v ON f.jornada = v.idval
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL
                AND MONTH(h.fecha_especifica) = :mes 
                AND YEAR(h.fecha_especifica) = :año
                ORDER BY h.fecha_especifica';
        
        error_log("SQL ejecutado: " . $sql);
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $idInstructor);
        $result->bindParam(':mes', $mes);
        $result->bindParam(':año', $año);
        $result->execute();
        
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        error_log("Total horarios encontrados: " . count($horarios));
        
        if (empty($horarios)) {
            error_log("No se encontraron horarios para este instructor/mes/año");
            return [
                'success' => false,
                'message' => 'No se encontraron horarios',
                'total' => 0,
                'horarios' => []
            ];
        }
        
        // Mostrar cada horario encontrado
        foreach ($horarios as $index => $horario) {
            error_log("Horario $index: " . json_encode($horario));
        }
        
        // Verificar si hay problemas con la jornada
        $jornadasEncontradas = [];
        foreach ($horarios as $horario) {
            $jornada = $horario['jornada'] ?? 'NULL';
            $nombreJornada = $horario['nombre_jornada'] ?? 'NULL';
            $jornadasEncontradas[] = "ID: $jornada, Nombre: $nombreJornada";
        }
        
        error_log("Jornadas encontradas: " . implode(', ', $jornadasEncontradas));
        
        return [
            'success' => true,
            'message' => 'Horarios obtenidos correctamente',
            'total' => count($horarios),
            'horarios' => $horarios,
            'jornadas' => $jornadasEncontradas
        ];
        
    } catch (Exception $e) {
        error_log("Error en debugHorariosInstructor: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'total' => 0,
            'horarios' => []
        ];
    }
}

// Manejo temprano para debugging de horarios del instructor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'debug_horarios_instructor') {
    $idInstructor = $_POST['id_instructor'] ?? null;
    $mes = $_POST['mes'] ?? null;
    $año = $_POST['año'] ?? null;
    
    header('Content-Type: application/json; charset=UTF-8');
    
    if (!$idInstructor || !$mes || !$año) {
        echo json_encode([
            'success' => false,
            'message' => 'Parámetros incompletos: id_instructor, mes, año'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        $debugInfo = debugHorariosInstructor($idInstructor, $mes, $año);
        echo json_encode($debugInfo, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en debugging: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Manejo temprano para prueba simple de horarios del instructor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope === 'test_horarios_instructor') {
    $idInstructor = $_POST['id_instructor'] ?? null;
    
    header('Content-Type: application/json; charset=UTF-8');
    
    if (!$idInstructor) {
        echo json_encode([
            'success' => false,
            'message' => 'ID de instructor requerido'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    try {
        // Consulta simple para ver si hay horarios para este instructor
        $sql = 'SELECT COUNT(*) as total, 
                       COUNT(CASE WHEN fecha_especifica IS NOT NULL THEN 1 END) as con_fecha,
                       COUNT(CASE WHEN fecha_especifica IS NULL THEN 1 END) as sin_fecha
                FROM horario 
                WHERE idusu = :id_instructor';
        
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $idInstructor);
        $result->execute();
        
        $stats = $result->fetch(PDO::FETCH_ASSOC);
        
        // Obtener algunos ejemplos de horarios
        $sqlEjemplos = 'SELECT idhor, idfic, fecha_especifica, es_otros, horas_otros, es_transversal
                        FROM horario 
                        WHERE idusu = :id_instructor 
                        AND fecha_especifica IS NOT NULL
                        ORDER BY fecha_especifica 
                        LIMIT 5';
        
        $resultEjemplos = $conexion->prepare($sqlEjemplos);
        $resultEjemplos->bindParam(':id_instructor', $idInstructor);
        $resultEjemplos->execute();
        
        $ejemplos = $resultEjemplos->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'instructor_id' => $idInstructor,
            'estadisticas' => $stats,
            'ejemplos' => $ejemplos,
            'sql_ejecutada' => $sql
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error en prueba: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// Endpoint para probar festivos reales de Colombia
// Eliminado: endpoint de prueba 'test_festivos'

// Endpoint de prueba simple para verificar que el sistema funciona
// Eliminado: endpoint de prueba 'test_sistema'

// Endpoint de prueba para verificar mapeo de jornadas
// Eliminado: endpoint de prueba 'test_mapeo_jornadas'

// Manejo temprano para obtener TODOS los horarios del instructor desde la base de datos
// Eliminado: endpoint de prueba 'get_horarios_instructor_completo'
