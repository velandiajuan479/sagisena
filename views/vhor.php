<?php 
// Configurar zona horaria de Colombia
date_default_timezone_set('America/Bogota');
require_once 'controllers/chor.php'; 
// Asegurar configuración del footer (versión/actualización)
require_once __DIR__ . '/../controllers/ccof.php';
// Si $val no viene con datos válidos, intentar cargar configuración por defecto (idcof=1)
if ((!isset($val) || !is_array($val) || empty($val) || !isset($val[0]['versoft'])) ) {
    require_once __DIR__ . '/../models/mcof.php';
    $mcofFooter = new Mcof();
    $mcofFooter->setIdcof(1);
    $valFallback = $mcofFooter->getOne();
    if (is_array($valFallback) && !empty($valFallback) && isset($valFallback[0]['versoft'])) {
        $val = $valFallback;
    }
}

// ==========================================
// VALIDACIÓN DE PERMISOS POR PERFILES - INICIO
// ==========================================

// Obtener ID del usuario actual
$idusu_actual = $_SESSION["idusu"] ?? null;

// Validar que el usuario esté autenticado
if (!$idusu_actual) {
    echo '<div class="alert alert-danger">Usuario no autenticado.</div>';
    exit;
}

// Obtener perfil del usuario
$perfil_usuario = obtenerPerfilUsuario($idusu_actual);
if (!$perfil_usuario) {
    echo '<div class="alert alert-danger">No se pudo obtener el perfil del usuario.</div>';
    exit;
}

// Obtener restricciones de interfaz
$restricciones = obtenerRestriccionesInterfaz($idusu_actual);

// Para perfiles restringidos (idper=4), obtener ficha asignada
$ficha_asignada = null;
if ($perfil_usuario['idper'] == 4) {
    // Obtener ficha asignada al usuario
    $ficha_asignada = obtenerFichaAsignadaUsuario($idusu_actual);
    
    if (!$ficha_asignada) {
        echo '<div class="alert alert-warning">No tienes ficha asignada. Contacta al administrador.</div>';
        exit;
    }
    
    // Filtrar datos para mostrar solo la ficha asignada
    if (isset($dft) && is_array($dft)) {
        $dft = array_filter($dft, function($ficha) use ($ficha_asignada) {
            return $ficha['idfic'] == $ficha_asignada;
        });
    }
    
    // Filtrar instructores para mostrar solo los de la ficha asignada
    if (isset($instructores_tabla) && is_array($instructores_tabla)) {
        // Para perfil 4, no mostrar tabla de instructores
        $instructores_tabla = [];
    }
}

// ==========================================
// VALIDACIÓN DE PERMISOS POR PERFILES - FIN
// ==========================================
?>
<?php
// Nota: El footer espera que $val sea un arreglo [0] con claves 'versoft', 'actsoft', etc.
// Si $val viene con otro tipo (int, null, etc.) desde algún controlador, lo limpiamos para evitar warnings.
if (isset($val) && (!is_array($val) || !isset($val[0]) || !is_array($val[0]))) {
    $val = [];
}
?>
<?php
// Asegurar que la función pintit exista aunque el controlador termine antes
if (!function_exists('pintit')) {
    function pintit($ddi)
    {
        if ($ddi) {
            foreach ($ddi as $d) {
                echo '<th>'.$d['nomval'].'</th>';
            }
        }
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
/**
 * Obtiene la fecha actual en zona horaria de Colombia
 * @return DateTime Fecha actual en Colombia
 */
function obtenerFechaColombia() {
    // Configurar zona horaria de Colombia
    $zonaColombia = new DateTimeZone('America/Bogota');
    $fechaColombia = new DateTime('now', $zonaColombia);
    
    return $fechaColombia;
}

/**
 * Calcula la semana ISO de una fecha específica (sincronizada con input type="week")
 * @param DateTime $fecha Fecha para calcular la semana
 * @return string Semana en formato YYYY-WW
 */
function calcularSemanaISO($fecha = null) {
    if (!$fecha) {
        $fecha = obtenerFechaColombia();
    }
    
    // Usar el método estándar de PHP que está sincronizado con input type="week"
    $timestamp = $fecha->getTimestamp();
    $semana = date('W', $timestamp);
    $año = date('Y', $timestamp);
    
    // Manejar casos especiales de cambio de año según estándar ISO 8601
    if ($semana == 1 && $fecha->format('n') == 12) {
        // Si es diciembre pero la semana es 1, pertenece al año siguiente
        $año++;
    } elseif ($semana >= 52 && $fecha->format('n') == 1) {
        // Si es enero pero la semana es 52+, pertenece al año anterior
        $año--;
    }
    
    return sprintf('%04d-%02d', $año, $semana);
}

/**
 * Formatea una semana para mostrar en formato legible
 * @param string $semanaSelector Semana en formato YYYY-WW
 * @return string Semana formateada
 */
function formatearSemanaPHP($semanaSelector) {
    if (!$semanaSelector) {
        // Si no hay selector, calcular y formatear la semana actual
        $semanaActual = calcularSemanaISO();
        return formatearSemanaPHP($semanaActual);
    }
    
    try {
        // Parsear el selector de semana (formato: YYYY-WW o YYYY-WWW)
        if (preg_match('/^(\d{4})-W?(\d{2,3})$/', $semanaSelector, $matches)) {
            $año = (int)$matches[1];
            $semana = (int)$matches[2];
            
            // Configurar zona horaria de Colombia
            $zonaColombia = new DateTimeZone('America/Bogota');
            
            // Calcular el 4 de enero del año (según estándar ISO 8601)
            $cuatroEnero = new DateTime("$año-01-04", $zonaColombia);
            $diaSemanaCuatroEnero = (int)$cuatroEnero->format('N'); // 1=Lunes, 7=Domingo
            
            // Calcular el primer día de la semana 1 (lunes)
            $primerDiaSemana1 = clone $cuatroEnero;
            $diasHastaLunes = $diaSemanaCuatroEnero - 1;
            $primerDiaSemana1->sub(new DateInterval("P{$diasHastaLunes}D"));
            
            // Calcular el lunes de la semana específica
            $lunesSemana = clone $primerDiaSemana1;
            $lunesSemana->add(new DateInterval('P' . (($semana - 1) * 7) . 'D'));
            
            // Calcular el domingo de la semana
            $domingoSemana = clone $lunesSemana;
            $domingoSemana->add(new DateInterval('P6D'));
            
            // Formatear fechas usando zona horaria de Colombia
            $inicioFormateado = $lunesSemana->format('d/m/Y');
            $finFormateado = $domingoSemana->format('d/m/Y');
            
            return "Semana $semana ($inicioFormateado - $finFormateado)";
        }
    } catch (Exception $e) {
        error_log("Error formateando semana PHP: " . $e->getMessage());
    }
    
    // Si no se puede parsear, calcular la semana actual
    $semanaActual = calcularSemanaISO();
    return formatearSemanaPHP($semanaActual);
}
?>

<!-- Estilos necesarios para los dropdowns(PASAR A CSS) -->
<style>
.dropdown-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999;
}

.dropdown-item-custom {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.dropdown-item-custom:hover {
    background-color: #f5f5f5;
}
</style>


<!-- Inserción de horarios -->
<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Horarios", 1); ?>

    <?php if ($restricciones['mostrar_botones_crear']): ?>
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST">
            <div class="row justify-content-end">
                <!-- Búsqueda de Ficha -->
                <div class="form-group col-md-6">
                    <label for="search_idfic">Buscar Ficha</label>
                    <div class="position-relative">
                        <input type="text" 
                               id="search_idfic" 
                               class="form-control search-input" 
                               placeholder="Escriba para buscar ficha..."
                               autocomplete="off">
                        <input type="hidden" name="idfic" id="idfic" value="<?= $idfic ?? '' ?>">
                        
                        <div id="dropdown_idfic" class="dropdown-results" style="display: none;">
                            <?php if ($dfi): ?>
                                <?php foreach ($dfi as $de): ?>
                                    <div class="dropdown-item-custom" 
                                         data-value="<?= $de['idfic']; ?>"
                                         data-text="<?= $de['idfic']; ?> - <?= $de['nomfic']; ?> <?= $de['nomval']; ?>">
                                        <strong><?= $de['idfic']; ?></strong> - <?= $de['nomfic']; ?> 
                                        <small class="text-muted">(<?= $de['nomval']; ?>)</small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Selección de Días -->
                <div class="form-group col-md-6">
                    <label for="iddia">Día</label><br>
                    <?php if (!empty($ddi)): ?>
                        <?php foreach ($ddi as $de): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="iddia[]" 
                                       id="dia_<?= $de['idval'] ?>"
                                       value="<?= $de['idval'] ?>"
                                       <?= (!empty($dat) && $iddia == $de['idval']) ? 'checked' : '' ?> />
                                <label class="form-check-label" for="dia_<?= $de['idval'] ?>">
                                    <?= htmlspecialchars($de['nomval']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Checkbox para rango por evento -->
                <div class="form-group col-12">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="rango_por_evento" 
                               id="rango_por_evento" 
                               value="1"
                               onchange="toggleRangoPorEvento()">
                        <label class="form-check-label" for="rango_por_evento">
                            <strong><i class="fa-solid fa-calendar-alt me-2" style="color: #00af00;"></i>Rango por evento del calendario académico</strong>
                        </label>
                        <small class="form-text text-muted d-block mt-1">
                            Marque esta opción para seleccionar automáticamente el rango de fechas desde el calendario académico
                        </small>
                    </div>
                </div>

                <!-- Campos de rango por evento (inicialmente ocultos) -->
                <div id="campos_rango_evento" class="col-12" style="display: none;">
                    <div class="card mt-3">
                        <div class="card-header text-white" style="background-color: #00af00;">
                            <h6 class="mb-0">Selección de Evento del Calendario</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Tipo de Evento -->
                                <div class="form-group col-md-6">
                                    <label for="tipo_evento">Tipo de Evento</label>
                                    <select name="tipo_evento" id="tipo_evento" class="form-select" onchange="cargarEventosPorTipo()">
                                        <option value="">Seleccione el tipo de evento...</option>
                                        <?php if (!empty($tiposEventos)): ?>
                                            <?php foreach ($tiposEventos as $tipo): ?>
                                                <option value="<?= $tipo ?>"><?= ucfirst(str_replace('_', ' ', $tipo)) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted">Seleccione el tipo de evento del calendario</small>
                                </div>

                                <!-- Evento Específico -->
                                <div class="form-group col-md-6">
                                    <label for="evento_calendario">Evento del Calendario</label>
                                    <select name="evento_calendario" id="evento_calendario" class="form-select" onchange="seleccionarEvento()">
                                        <option value="">Primero seleccione el tipo de evento...</option>
                                    </select>
                                    <small class="form-text text-muted">Seleccione el evento específico</small>
                                </div>

                                <!-- Información del evento seleccionado -->
                                <div class="form-group col-12">
                                    <div id="info_evento" class="alert alert-info" style="display: none; border-color: #00af00; background-color: rgba(0, 175, 0, 0.1);">
                                        <small>
                                            <i class="fa-solid fa-info-circle me-1" style="color: #00af00;"></i>
                                            <strong>Evento seleccionado:</strong> 
                                            <span id="titulo_evento">-</span>
                                            <br>
                                            <strong>Período:</strong> 
                                            <span id="fecha_inicio_evento">-</span>
                                            <br>
                                            <strong>Trimestre:</strong> 
                                            <span id="trimestre_evento">-</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fecha de Inicio -->
                <div class="form-group col-md-6" id="campo_fecha_inicio">
                    <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="fecha_inicio" 
                           id="fecha_inicio" 
                           class="form-control" 
                           value="<?= obtenerFechaColombia()->format('Y-m-d') ?>"
                           required>
                    <small class="form-text text-muted">Fecha desde cuando inicia el horario (puede ser anterior a hoy)</small>
                </div>

                <!-- Fecha de Fin -->
                <div class="form-group col-md-6" id="campo_fecha_fin">
                    <label for="fecha_fin">Fecha de Fin <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="fecha_fin" 
                           id="fecha_fin" 
                           class="form-control" 
                           value="<?= obtenerFechaColombia()->modify('+1 month')->format('Y-m-d') ?>"
                           required>
                    <small class="form-text text-muted">Fecha hasta cuando es válido el horario (puede ser anterior a hoy)</small>
                </div>

                <!-- Fecha Específica para Horario Individual -->
                <div class="form-group col-md-6" id="campo_fecha_especifica" style="display: none;">
                    <label for="fecha_especifica">Fecha Específica <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="fecha_especifica" 
                           id="fecha_especifica" 
                           class="form-control">
                    <small class="form-text text-muted">Seleccione la fecha específica para el horario individual</small>
                </div>

                <!-- Modo de Horario Individual -->
                <div class="form-group col-12">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="modo_individual" 
                               name="modo_individual"
                               onchange="toggleModoIndividual()">
                        <label class="form-check-label" for="modo_individual">
                            <strong>Modo Individual:</strong> Agregar horario para una fecha específica únicamente
                        </label>
                    </div>
                    <small class="form-text text-muted">Marque esta opción si desea agregar el horario solo para una fecha específica en lugar de todo el rango</small>
                </div>

                <!-- Información del lapso -->
                <div class="form-group col-12">
                    <div class="alert alert-info" id="info_lapso" style="display: none;">
                        <small>
                            <i class="fa-solid fa-info-circle me-1"></i>
                            <strong>Lapso seleccionado:</strong> 
                            <span id="formatoInicio">-</span> al <span id="formatoFin">-</span>
                            (<span id="duracion_lapso">-</span> días)
                        </small>
                    </div>
                </div>

                <!-- Búsqueda de Instructor -->
                <div class="form-group col-md-6">
                    <label for="search_idusu">Buscar Instructor</label>
                    <div class="position-relative">
                        <input type="text" 
                               id="search_idusu" 
                               class="form-control search-input" 
                               placeholder="Escriba para buscar instructor..."
                               autocomplete="off">
                        <input type="hidden" name="idusu" id="idusu" value="<?= $idusu ?? '' ?>">
                        
                        <div id="dropdown_idusu" class="dropdown-results" style="display: none;">
                            <?php if ($din): ?>
                                <?php foreach ($din as $de): ?>
                                    <div class="dropdown-item-custom" 
                                         data-value="<?= $de['idusu']; ?>"
                                         data-text="<?= strtoupper($de['nomusu']) . ' - ' . $de['ndocusu']; ?>">
                                        <strong><?= strtoupper($de['nomusu']); ?></strong> 
                                        <small class="text-muted">(<?= $de['ndocusu']; ?>)</small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda de Aula -->
                <div class="form-group col-md-6">
                    <label for="search_idaul">Buscar Aula</label>
                    <div class="position-relative">
                        <input type="text" 
                               id="search_idaul" 
                               class="form-control search-input" 
                               placeholder="Escriba para buscar aula..."
                               autocomplete="off">
                        <input type="hidden" name="idaul" id="idaul" value="<?= $idaul ?? '' ?>">
                        
                        <div id="dropdown_idaul" class="dropdown-results" style="display: none;">
                            <?php if ($dau): ?>
                                <?php foreach ($dau as $de): ?>
                                    <div class="dropdown-item-custom" 
                                         data-value="<?= $de['idaul']; ?>"
                                         data-text="<?= $de['idaul'] . '. ' . $de['nomaul']; ?>">
                                        <strong><?= $de['idaul']; ?></strong> - <?= $de['nomaul']; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Checkbox para actividades "Otros" -->
                <div class="form-group col-12">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="es_otros" 
                               id="es_otros" 
                               value="1"
                               onchange="toggleOtrosFields()">
                        <label class="form-check-label" for="es_otros">
                            <strong><i class="fa-solid fa-calendar-plus me-2"></i>Actividades especiales (Otros)</strong>
                        </label>
                        <small class="form-text text-muted d-block mt-1">
                            Marque esta opción para agregar actividades como proyecto Senova, horas de alistamiento, horas de confraternidad, etc.
                        </small>
                    </div>
                </div>

                <!-- Checkbox para horarios "Transversales" -->
                <div class="form-group col-12">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="es_transversal" 
                               id="es_transversal" 
                               value="1"
                               onchange="toggleTransversalFields()">
                        <label class="form-check-label" for="es_transversal">
                            <strong><i class="fa-solid fa-book-open me-2"></i>Horarios Transversales</strong>
                        </label>
                        <small class="form-text text-muted d-block mt-1">
                            Marque esta opción para agregar horarios de materias transversales como inglés, ética, etc.
                        </small>
                    </div>
                </div>

                <!-- Campos adicionales para "Otros" (inicialmente ocultos) -->
                <div id="campos_otros" class="col-12" style="display: none;">
                    <div class="card mt-3">
                        <div class="card-header text-white" style="background-color: #00af00;">
                            <h6 class="mb-0">Configuración de Actividad Especial</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Actividad -->
                                <div class="form-group col-md-6">
                                    <label for="actividad">Actividad <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="actividad" 
                                           id="actividad" 
                                           class="form-control" 
                                           placeholder="Ej: Reunión de área, Capacitación docente..."
                                           maxlength="100">
                                    <small class="form-text text-muted">Descripción de la actividad especial</small>
                                </div>

                                <!-- Horas -->
                                <div class="form-group col-md-6">
                                    <label for="horas_otros">Horas de duración <span class="text-danger">*</span></label>
                                    <select name="horas_otros" id="horas_otros" class="form-select">
                                        <option value="">Seleccione las horas...</option>
                                        <option value="1">1 hora</option>
                                        <option value="2">2 horas</option>
                                        <option value="3">3 horas</option>
                                        <option value="4">4 horas</option>
                                        <option value="5">5 horas</option>
                                        <option value="6">6 horas</option>
                                        <option value="7">7 horas</option>
                                        <option value="8">8 horas</option>
                                        <option value="9">9 horas</option>
                                        <option value="10">10 horas</option>
                                        <option value="11">11 horas</option>
                                        <option value="12">12 horas</option>
                                        <option value="13">13 horas</option>
                                        <option value="14">14 horas</option>
                                        <option value="15">15 horas</option>
                                        <option value="16">16 horas</option>
                                    </select>
                                    <small class="form-text text-muted">Duración de la actividad especial</small>
                                </div>
                            </div>
                            
                            <!-- Checkbox para horas directas a formación -->
                            <div class="row mt-3">
                                <div class="form-group col-12">
                                    <!-- Campo oculto para asegurar que siempre se envíe un valor -->
                                    <input type="hidden" name="es_formacion_directa" value="1">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="es_formacion_directa" 
                                               id="es_formacion_directa"
                                               value="2">
                                        <label class="form-check-label" for="es_formacion_directa">
                                            <strong>Contar como horas directas a formación</strong>
                                        </label>
                                        <small class="form-text text-muted d-block mt-1">
                                            Si se marca, estas horas se sumarán como <strong>horas de formación</strong> en lugar de <strong>horas otros</strong>. 
                                            Útil para actividades especiales que contribuyen directamente a la formación de los aprendices.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campos adicionales para "Transversales" (inicialmente ocultos) -->
                <div id="campos_transversales" class="col-12" style="display: none;">
                    <div class="card mt-3">
                        <div class="card-header text-white" style="background-color: #00af00;">
                            <h6 class="mb-0">Configuración de Horario Transversal</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Nombre de la Transversal -->
                                <div class="form-group col-md-12">
                                    <label for="nombre_transversal">Nombre de la Transversal <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="nombre_transversal" 
                                           id="nombre_transversal" 
                                           class="form-control" 
                                           placeholder="Ej: Inglés, Ética, Emprendimiento..."
                                           maxlength="100">
                                    <small class="form-text text-muted">Nombre de la materia transversal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de enviar formulario -->
                <div class="form-group col-md-12 text-end">
                    <br>
                    <input type="hidden" name="ope" value="save">
                    <input type="hidden" name="id"
                        value="<?php if ($dtOne && $dtOne[0]['idhor']) {
                            echo $dtOne[0]['idhor'];
                        } ?>" required>
                    
                    <!-- Campos ocultos para rango por evento -->
                    <input type="hidden" name="evento_seleccionado_id" id="evento_seleccionado_id" value="">
                    <input type="hidden" name="evento_seleccionado_titulo" id="evento_seleccionado_titulo" value="">
                    <input type="hidden" name="evento_seleccionado_fecha_inicio" id="evento_seleccionado_fecha_inicio" value="">
                    <input type="hidden" name="evento_seleccionado_fecha_fin" id="evento_seleccionado_fecha_fin" value="">
                    
                    <!-- Campos ocultos para edición -->
                    <?php if (isset($dtOne) && $dtOne): ?>
                    <input type="hidden" name="es_otros_edit" value="<?= $dtOne[0]['es_otros'] ?? '0' ?>">
                    <input type="hidden" name="actividad_edit" value="<?= htmlspecialchars($dtOne[0]['actividad'] ?? '') ?>">
                    <input type="hidden" name="horas_otros_edit" value="<?= $dtOne[0]['horas_otros'] ?? '' ?>">
                    <input type="hidden" name="es_formacion_directa_edit" value="<?= $dtOne[0]['es_formacion_directa'] ?? '1' ?>">
                    <input type="hidden" name="es_transversal_edit" value="<?= $dtOne[0]['es_transversal'] ?? '0' ?>">
                    <input type="hidden" name="nombre_transversal_edit" value="<?= htmlspecialchars($dtOne[0]['nombre_transversal'] ?? '') ?>">
                    <input type="hidden" name="fecha_inicio_edit" value="<?= $dtOne[0]['fecha_inicio'] ?? '' ?>">
                    <input type="hidden" name="fecha_fin_edit" value="<?= $dtOne[0]['fecha_fin'] ?? '' ?>">
                    <?php endif; ?>
                    
                    <input type="submit" class="btn btn-primary" value="Enviar" style="background-color: #00af00; border-color: #00af00;">
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>
<!-- Fin de la inser -->

<!-- JavaScript para la funcionalidad de búsqueda -->
<script>
// Parámetros configurables sincronizados con backend
window.CONTRATISTA_MAX_HORAS = <?= defined('CONTRATISTA_MAX_HORAS') ? (int)CONTRATISTA_MAX_HORAS : 180 ?>;
window.FACTOR_FORMACION = <?= defined('FACTOR_FORMACION') ? json_encode(FACTOR_FORMACION) : '6.4' ?>;
window.FACTOR_OTROS = <?= defined('FACTOR_OTROS') ? json_encode(FACTOR_OTROS) : '2.1' ?>;
async function actualizarResumenMesSeleccionado(idInstructor, mes, año) {
    try {
        // Evitar condiciones de carrera si el usuario cambió de instructor mientras cargaba
        if (window.currentInstructorId && idInstructor !== window.currentInstructorId) return;
        if (!idInstructor) return;
        const fd = new FormData();
        fd.append('ope', 'get_horarios_por_mes');
        fd.append('id_instructor', idInstructor);
        fd.append('mes', mes);
        fd.append('año', año);
        const resp = await fetch('controllers/chor.php', { method: 'POST', body: fd });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const data = await resp.json();
        if (data && data.success) {
            // Actualizar la tabla de detalle con los datos del mes seleccionado
            if (data.detalle && data.detalle.length > 0) {
                await actualizarTablaDetalleMes(data.detalle, mes, data.año);
            } else {
                // Si no hay datos, mostrar mensaje
                await actualizarTablaDetalleMes([], mes, data.año);
            }
        } else {
            // Limpiar tabla de detalle
            await actualizarTablaDetalleMes([], mes, año);
        }
    } catch (e) {
        console.error('Error cargando datos del mes seleccionado:', e);
        // Limpiar tabla de detalle en caso de error
        await actualizarTablaDetalleMes([], mes, año);
    }
}

// Función para actualizar la tabla de detalle con datos del mes seleccionado
async function actualizarTablaDetalleMes(detalle, mes, año) {
    // Actualizar el título de la tabla
    const tituloTabla = document.querySelector('#contenidoModal .card .card-header h6');
    if (tituloTabla) {
        tituloTabla.textContent = `Detalle de Horarios y Actividades - ${obtenerNombreMes(mes)} ${año}`;
    }
    
    const tablaContainer = document.querySelector('#contenidoModal .card .card-body .table-responsive');
    if (!tablaContainer) return;
    
    // Calcular resumen de horas (igual que en el resumen inicial)
    let totalHorasFormacion = 0;
    let totalHorasOtros = 0;
    let totalGeneral = 0;
    
    if (detalle && detalle.length > 0) {
        // Procesar fichas de formación (horarios normales y transversales)
        let fichasHorarios = {};
        
        // Usar la misma lógica que "Ver detalle por rango"
        detalle.forEach(item => {
            if (!item.es_otros) {
                // Es horario normal o transversal - SIEMPRE suma a formación
                const dias = item.dia.split(',').map(d => parseInt(d.trim()));
                const diasUnicos = [...new Set(dias)];
                
                // Calcular horas por día según la jornada
                const mapeoJornada = {
                    'Mañana': 6,
                    'Tarde': 5,
                    'Noche': 4
                };
                const horasPorDia = mapeoJornada[item.jornada] || 6;
                const totalHoras = diasUnicos.length * horasPorDia;
                
                totalHorasFormacion += totalHoras;
                
            } else {
                // Es actividad "otros" - verificar es_formacion_directa
                const dias = item.dia.split(',').map(d => parseInt(d.trim()));
                const diasUnicos = [...new Set(dias)];
                const horasPorDia = item.horas / item.dias_count;
                const totalHoras = diasUnicos.length * horasPorDia;
                
                if (item.es_formacion_directa == 2) {
                    // Actividad "otros" con formación directa: contar como formación
                    totalHorasFormacion += totalHoras;
                    
                } else {
                    // Actividad "otros" tradicional: contar como otros
                    totalHorasOtros += totalHoras;
                    
                }
            }
        });
        
        totalGeneral = totalHorasFormacion + totalHorasOtros;
    }
    
    // Calcular horas esperadas dinámicamente para instructores de planta
    let horasEsperadasMensual = 160; // Valor por defecto para contratistas
    let horasFormacionDirecta = 0;
    let horasOtros = 0;
    
    // Calcular horas esperadas según tipo de contrato
    if (window.instructorActual && window.instructorActual.tipo_contrato === 'planta') {
        // Para instructores de planta, calcular dinámicamente
        const diasLaborales = await calcularDiasLaborales(mes, año);
        horasFormacionDirecta = diasLaborales * 6.4;
        horasOtros = diasLaborales * 2.1;
        horasEsperadasMensual = horasFormacionDirecta + horasOtros;
    } else {
        // Para contratistas, usar tope configurable
        horasEsperadasMensual = window.CONTRATISTA_MAX_HORAS || 180;
        horasFormacionDirecta = 0; // Los contratistas no tienen desglose de formación/otros
        horasOtros = 0;
    }
    
    const horasDisponibles = Math.max(0, horasEsperadasMensual - totalGeneral);
    
    // Actualizar el resumen de horas en la interfaz con formato x/y
    // Buscar explícitamente el card cuyo header sea "Resumen de Horas (...)"
    let resumenCard;
    const headers = Array.from(document.querySelectorAll('#contenidoModal h6'));
    const headerResumen = headers.find(h => h.textContent.trim().startsWith('Resumen de Horas'));
    if (headerResumen) {
        const resumenCardEl = headerResumen.closest('.card');
        resumenCard = resumenCardEl ? resumenCardEl.querySelector('.card-body') : null;
    }

    if (resumenCard) {
        const esContratista = window.instructorActual && window.instructorActual.tipo_contrato === 'contratista';
        resumenCard.innerHTML = `
            <h6>Resumen de Horas (${obtenerNombreMes(mes)})</h6>
            <p><strong>Horas directas a formación:</strong> ${esContratista ? totalHorasFormacion : `${totalHorasFormacion}/${horasFormacionDirecta.toFixed(1)}`} hrs</p>
            <p><strong>Horas otros:</strong> ${esContratista ? totalHorasOtros : `${totalHorasOtros}/${horasOtros.toFixed(1)}`} hrs</p>
            <p><strong>Total asignado:</strong> ${esContratista ? `${totalGeneral}/${(window.CONTRATISTA_MAX_HORAS||180).toFixed? (window.CONTRATISTA_MAX_HORAS||180).toFixed(1) : (window.CONTRATISTA_MAX_HORAS||180)}` : `${totalGeneral}/${horasEsperadasMensual.toFixed(1)}`} hrs</p>
            <p><strong>Horas disponibles:</strong> ${esContratista ? ((window.CONTRATISTA_MAX_HORAS||180) - totalGeneral).toFixed(1) : horasDisponibles.toFixed(1)} hrs</p>
        `;
        
    } else {
        console.error('No se encontró el card de Resumen de Horas para actualizar.');
    }
    
    // Actualizar la gráfica de barras
    const progressContainer = document.querySelector('#contenidoModal .progress.mt-3');
    if (progressContainer) {
        // Calcular porcentajes
        const porcentajeFormacion = (totalHorasFormacion / horasEsperadasMensual) * 100;
        const porcentajeOtros = (totalHorasOtros / horasEsperadasMensual) * 100;
        const porcentajeDisponibles = (horasDisponibles / horasEsperadasMensual) * 100;
        
        progressContainer.innerHTML = `
            <div class="progress-bar" style="background-color: #00af00; width: ${porcentajeFormacion}%" title="Horas directas a formación">
                ${totalHorasFormacion} hrs
            </div>
            <div class="progress-bar bg-primary" style="width: ${porcentajeOtros}%" title="Otros">
                ${totalHorasOtros} hrs
            </div>
            <div class="progress-bar bg-secondary" style="width: ${porcentajeDisponibles}%" title="Disponibles">
                ${horasDisponibles} hrs
            </div>
        `;
        
    } else {
        console.error('No se encontró el contenedor de la gráfica');
    }
    
    // Generar contenido de la tabla
    let contenido = `
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Ficha/Actividad</th>
                    <th>Días del mes</th>
                    <th>Jornada</th>
                    <th>Horas</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    if (detalle && detalle.length > 0) {
        detalle.forEach(item => {
            const claseFila = item.es_transversal ? 'table-info' : 
                             item.es_otros ? 'table-warning' : 'table-success';
            
            // Calcular horas por día según la jornada
            let horasPorDia = 0;
            if (item.es_otros) {
                // Para actividades "otros", usar las horas específicas
                horasPorDia = item.horas / item.dias_count;
            } else {
                // Para horarios normales, usar el mapeo de jornada
                const mapeoJornada = {
                    'Mañana': 6,
                    'Tarde': 5,
                    'Noche': 4
                };
                horasPorDia = mapeoJornada[item.jornada] || 6; // Default: Mañana
            }
            
            contenido += `
                <tr class="${claseFila}">
                    <td><strong>${item.ficha}</strong></td>
                    <td>${item.dia}</td>
                    <td>${item.jornada}</td>
                    <td>${horasPorDia} hrs/día</td>
                    <td><strong>${item.horas} hrs</strong></td>
                </tr>
            `;
        });
        
        // Fila de totales
        contenido += `
            <tr class="table-dark">
                <td><strong>TOTAL GENERAL</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>${totalGeneral} hrs</strong></td>
            </tr>
        `;
    } else {
        contenido += `
            <tr>
                <td colspan="5" class="text-center text-muted">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    No hay horarios registrados para ${obtenerNombreMes(mes)} ${año}
                </td>
            </tr>
        `;
    }
    
    contenido += `
            </tbody>
        </table>
    `;
    
    tablaContainer.innerHTML = contenido;
}

document.addEventListener('DOMContentLoaded', function() {
    // Mostrar mensajes de resultado si existen
    <?php if (isset($mensajesResultado) && !empty($mensajesResultado)): ?>
        <?php if (isset($mensajesResultado['errores'])): ?>
            const errores = <?= json_encode($mensajesResultado['errores']) ?>;
            let mensajeCompleto = "";
            errores.forEach((error, index) => {
                mensajeCompleto += `${error}\n`;
            });
            alert(mensajeCompleto);
        <?php endif; ?>
        
        <?php if (isset($mensajesResultado['exito'])): ?>
            setTimeout(function() {
                alert("<?= $mensajesResultado['exito'] ?>");
            }, <?= isset($mensajesResultado['errores']) ? '100' : '0' ?>);
        <?php endif; ?>
    <?php endif; ?>

    // Inicializar búsquedas interactivas
    initSearchDropdown('idfic', 'Ficha');
    initSearchDropdown('idusu', 'Instructor');
    initSearchDropdown('idaul', 'Aula');

    // Validación del formulario
    document.querySelector('#frmins').addEventListener('submit', function(e) {
        // Si es edición, verificar que se haya seleccionado transversal
        const esEdicion = document.querySelector('input[name="ope"]').value === 'edit';
        const esTransversal = document.getElementById('es_transversal').checked;
        
        if (esEdicion && !esTransversal) {
            e.preventDefault();
            alert('Para editar un transversal, debe marcar la opción "Transversales".');
            return;
        }
        
        const checked = document.querySelectorAll('input[name="iddia[]"]:checked').length;
        const fichaSeleccionada = document.getElementById('idfic').value;
        const aulaSeleccionada = document.getElementById('idaul').value;
        const instructorSeleccionado = document.getElementById('idusu').value;
        const esOtros = document.getElementById('es_otros').checked;
        const esTransversalCheck = document.getElementById('es_transversal').checked;

        if (!checked) {
            e.preventDefault();
            alert('Debes seleccionar al menos un día.');
            return;
        }

        // Validar ficha: requerida para horarios normales y transversales, opcional para "otros"
        if (!esOtros && !fichaSeleccionada) {
            e.preventDefault();
            alert('Debes seleccionar una ficha.');
            return;
        }

        // Validar instructor (requerido para horarios normales y "otros", opcional para transversales)
        if (!esTransversalCheck && !instructorSeleccionado) {
            e.preventDefault();
            alert('Debes seleccionar un instructor.');
            return;
        }

        // Validar aula (requerida para horarios normales y transversales, opcional para "otros")
        if (!esOtros && !aulaSeleccionada) {
            e.preventDefault();
            alert('Debes seleccionar un aula.');
            return;
        }

        // Validar campos específicos de "Otros"
        if (esOtros) {
            const actividad = document.getElementById('actividad').value;
            const horasOtros = document.getElementById('horas_otros').value;
            
            if (!actividad.trim()) {
                e.preventDefault();
                alert('Debes ingresar el nombre de la actividad especial.');
                return;
            }
            
            if (!horasOtros) {
                e.preventDefault();
                alert('Debes seleccionar las horas de duración de la actividad.');
                return;
            }
        }

        // Validar campos específicos de "Transversales"
        if (esTransversalCheck) {
            const nombreTransversal = document.getElementById('nombre_transversal').value;
            
            if (!nombreTransversal.trim()) {
                e.preventDefault();
                alert('Debes ingresar el nombre de la transversal.');
                return;
            }
        }

        // Validar campos de fechas
        const rangoPorEvento = document.getElementById('rango_por_evento').checked;
        let fechaInicio = document.getElementById('fecha_inicio').value;
        let fechaFin = document.getElementById('fecha_fin').value;
        
        
        // Verificar si está en modo individual
        const modoIndividual = document.getElementById('modo_individual').checked;
        
        if (modoIndividual) {
            // Modo individual: usar fecha específica
            const fechaEspecifica = document.getElementById('fecha_especifica').value;
            if (!fechaEspecifica) {
                e.preventDefault();
                alert('Debes seleccionar una fecha específica para el horario individual.');
                return;
            }
            
            // Establecer la fecha específica como fecha de inicio y fin para el procesamiento
            fechaInicio = fechaEspecifica;
            fechaFin = fechaEspecifica;
            
            // Habilitar temporalmente los campos de fecha para que se envíen en el formulario
            document.getElementById('fecha_inicio').disabled = false;
            document.getElementById('fecha_fin').disabled = false;
            document.getElementById('fecha_inicio').value = fechaEspecifica;
            document.getElementById('fecha_fin').value = fechaEspecifica;
            
        } else if (rangoPorEvento) {
            // Si es rango por evento, verificar que se haya seleccionado un evento
            const eventoSeleccionado = document.getElementById('evento_calendario').value;
            if (!eventoSeleccionado) {
                e.preventDefault();
                alert('Debes seleccionar un evento del calendario académico.');
                return;
            }
            
            // Usar las fechas del evento seleccionado
            fechaInicio = document.getElementById('evento_seleccionado_fecha_inicio').value;
            fechaFin = document.getElementById('evento_seleccionado_fecha_fin').value;
            
            // Habilitar temporalmente los campos de fecha para que se envíen en el formulario
            document.getElementById('fecha_inicio').disabled = false;
            document.getElementById('fecha_fin').disabled = false;
        }
        
        if (!fechaInicio || !fechaFin) {
            e.preventDefault();
            alert('Debes seleccionar tanto la fecha de inicio como la fecha de fin.');
            return;
        }
        
        // Solo validar que fecha de fin sea posterior a fecha de inicio si NO está en modo individual
        if (!modoIndividual && fechaInicio >= fechaFin) {
            e.preventDefault();
            alert('La fecha de fin debe ser posterior a la fecha de inicio.');
            return;
        }
        
        // Verificar que el rango de fechas no sea muy largo (máximo 1 año) solo si NO está en modo individual
        if (!modoIndividual) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            const diasDiferencia = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24)) + 1; // +1 para incluir ambos días
            
            if (diasDiferencia > 365) {
                e.preventDefault();
                alert('El rango de fechas no puede ser mayor a 1 año.');
                return;
            }
        }

        // Confirmación antes de enviar
        const diasSeleccionados = document.querySelectorAll('input[name="iddia[]"]:checked');
        const nombresDias = Array.from(diasSeleccionados).map(cb => cb.nextElementSibling.textContent).join(', ');
        
        // Asegurar que las fechas estén correctamente configuradas antes del envío
        if (rangoPorEvento) {
            // Si es rango por evento, asegurar que las fechas del evento se usen
            const fechaInicioEvento = document.getElementById('evento_seleccionado_fecha_inicio').value;
            const fechaFinEvento = document.getElementById('evento_seleccionado_fecha_fin').value;
            
            // Actualizar los campos de fecha con los valores del evento
            document.getElementById('fecha_inicio').value = fechaInicioEvento;
            document.getElementById('fecha_fin').value = fechaFinEvento;
            
        }
        
        let mensajeConfirmacion;
        if (esOtros) {
            const actividad = document.getElementById('actividad').value;
            mensajeConfirmacion = `¿Estás seguro de programar la actividad "${actividad}" para los días: ${nombresDias}?`;
        } else if (esTransversalCheck) {
            const transversal = document.getElementById('nombre_transversal').value;
            if (esEdicion) {
                mensajeConfirmacion = `¿Estás seguro de actualizar la transversal "${transversal}" para los días: ${nombresDias}?`;
            } else {
                mensajeConfirmacion = `¿Estás seguro de programar la transversal "${transversal}" para los días: ${nombresDias}?`;
            }
        } else {
            mensajeConfirmacion = `¿Estás seguro de asignar este horario para los días: ${nombresDias}?`;
        }
        
        if (!confirm(mensajeConfirmacion)) {
            e.preventDefault();
        }
    });

    // Cargar valores preseleccionados
    loadPreselectedValues();
    
    // Inicializar funcionalidad de fechas
    initFechaFuncionality();
});

/**
 * Inicializa la funcionalidad de búsqueda para un dropdown específico
 */
function initSearchDropdown(fieldName, displayName) {
    const searchInput = document.getElementById(`search_${fieldName}`);
    const hiddenInput = document.getElementById(fieldName);
    const dropdown = document.getElementById(`dropdown_${fieldName}`);
    const items = dropdown.querySelectorAll('.dropdown-item-custom');
    
    let highlightedIndex = -1;
    let allItems = Array.from(items);

    // Evento de escritura en el input
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        highlightedIndex = -1;
        
        if (query === '') {
            showAllItems(items);
            dropdown.style.display = 'block';
            hiddenInput.value = '';
            return;
        }

        filterItems(items, query);
        dropdown.style.display = 'block';
    });

    // Navegación con teclado
    searchInput.addEventListener('keydown', function(e) {
        const visibleItems = Array.from(dropdown.querySelectorAll('.dropdown-item-custom')).filter(
            item => item.style.display !== 'none'
        );

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                highlightedIndex = Math.min(highlightedIndex + 1, visibleItems.length - 1);
                highlightItem(visibleItems, highlightedIndex);
                break;
            case 'ArrowUp':
                e.preventDefault();
                highlightedIndex = Math.max(highlightedIndex - 1, -1);
                highlightItem(visibleItems, highlightedIndex);
                break;
            case 'Enter':
                e.preventDefault();
                if (highlightedIndex >= 0 && visibleItems[highlightedIndex]) {
                    selectItem(visibleItems[highlightedIndex], searchInput, hiddenInput, dropdown);
                }
                break;
            case 'Escape':
                dropdown.style.display = 'none';
                highlightedIndex = -1;
                break;
        }
    });

    // Click en items del dropdown
    items.forEach(item => {
        item.addEventListener('click', function() {
            selectItem(this, searchInput, hiddenInput, dropdown);
        });
    });

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
            highlightedIndex = -1;
        }
    });

    // Mostrar todas las opciones al hacer click o focus
    searchInput.addEventListener('click', function() {
        showAllItems(items);
        dropdown.style.display = 'block';
    });

    searchInput.addEventListener('focus', function() {
        showAllItems(items);
        dropdown.style.display = 'block';
    });
}

/**
 * Muestra todos los items sin filtros
 */
function showAllItems(items) {
    items.forEach(item => {
        item.style.display = 'block';
    });
    const dropdown = items[0]?.parentElement;
    removeNoResultsMessage(dropdown);
}

/**
 * Filtra los items basándose en la consulta de búsqueda
 */
function filterItems(items, query) {
    let hasVisibleItems = false;
    
    items.forEach(item => {
        const text = item.getAttribute('data-text').toLowerCase();
        const isMatch = text.includes(query);
        
        item.style.display = isMatch ? 'block' : 'none';
        if (isMatch) hasVisibleItems = true;
    });

    // Mostrar mensaje si no hay resultados
    const dropdown = items[0]?.parentElement;
    if (!hasVisibleItems && dropdown) {
        showNoResultsMessage(dropdown, query);
    } else {
        removeNoResultsMessage(dropdown);
    }
}

/**
 * Resalta un item específico
 */
function highlightItem(visibleItems, index) {
    // Remover highlight previo
    visibleItems.forEach(item => item.classList.remove('highlighted'));
    
    // Agregar highlight al item actual
    if (index >= 0 && visibleItems[index]) {
        visibleItems[index].classList.add('highlighted');
        visibleItems[index].scrollIntoView({ block: 'nearest' });
    }
}

/**
 * Selecciona un item del dropdown
 */
function selectItem(item, searchInput, hiddenInput, dropdown) {
    const value = item.getAttribute('data-value');
    const text = item.getAttribute('data-text');
    
    searchInput.value = text;
    hiddenInput.value = value;
    dropdown.style.display = 'none';
}

/**
 * Muestra mensaje de "no hay resultados"
 */
function showNoResultsMessage(dropdown, query) {
    removeNoResultsMessage(dropdown);
    
    const noResultsDiv = document.createElement('div');
    noResultsDiv.className = 'dropdown-item-custom no-results-message';
    noResultsDiv.style.color = '#6c757d';
    noResultsDiv.style.fontStyle = 'italic';
    noResultsDiv.innerHTML = `No se encontraron resultados para "${query}"`;
    
    dropdown.appendChild(noResultsDiv);
}

/**
 * Remueve el mensaje de "no hay resultados"
 */
function removeNoResultsMessage(dropdown) {
    const existingMessage = dropdown?.querySelector('.no-results-message');
    if (existingMessage) {
        existingMessage.remove();
    }
}

/**
 * Carga valores preseleccionados al cargar la página
 */
function loadPreselectedValues() {
    // Cargar Ficha preseleccionada
    const idficValue = document.getElementById('idfic').value;
    if (idficValue) {
        const fichaItem = document.querySelector(`#dropdown_idfic [data-value="${idficValue}"]`);
        if (fichaItem) {
            document.getElementById('search_idfic').value = fichaItem.getAttribute('data-text');
        }
    }

    // Cargar Instructor preseleccionado solo si estamos editando
    const idusuValue = document.getElementById('idusu').value;
    const isEditing = document.querySelector('input[name="ope"]') && document.querySelector('input[name="ope"]').value === 'edi';
    
    if (idusuValue && isEditing) {
        const instructorItem = document.querySelector(`#dropdown_idusu [data-value="${idusuValue}"]`);
        if (instructorItem) {
            document.getElementById('search_idusu').value = instructorItem.getAttribute('data-text');
        }
    }

    // Cargar Aula preseleccionada solo si estamos editando
    const idaulValue = document.getElementById('idaul').value;
    if (idaulValue && isEditing) {
        const aulaItem = document.querySelector(`#dropdown_idaul [data-value="${idaulValue}"]`);
        if (aulaItem) {
            document.getElementById('search_idaul').value = aulaItem.getAttribute('data-text');
        }
    }

    // Cargar campos de "Otros" si están preseleccionados
    <?php if (isset($dtOne) && $dtOne && !empty($dtOne[0]['es_otros']) && $dtOne[0]['es_otros'] == 1): ?>
    document.getElementById('es_otros').checked = true;
    toggleOtrosFields();
    document.getElementById('actividad').value = '<?= htmlspecialchars($dtOne[0]['actividad'] ?? '') ?>';
    document.getElementById('horas_otros').value = '<?= $dtOne[0]['horas_otros'] ?? '' ?>';

    // Cargar el estado del checkbox de formación directa
    <?php if (isset($dtOne[0]['es_formacion_directa']) && $dtOne[0]['es_formacion_directa'] == 2): ?>
    document.getElementById('es_formacion_directa').checked = true;
    <?php endif; ?>
    <?php endif; ?>

    // Cargar campos de "Transversales" si están preseleccionados
    <?php if (isset($dtOne) && $dtOne && !empty($dtOne[0]['es_transversal']) && $dtOne[0]['es_transversal'] == 1): ?>
    // Solo cargar si no es una edición (para evitar duplicar la carga)
    if (document.querySelector('input[name="ope"]').value !== 'edit') {
        document.getElementById('es_transversal').checked = true;
        toggleTransversalFields();
        document.getElementById('nombre_transversal').value = '<?= htmlspecialchars($dtOne[0]['nombre_transversal'] ?? '') ?>';
    }
    <?php endif; ?>

    // Cargar campos de fechas si están preseleccionados
    <?php if (isset($dtOne) && $dtOne): ?>
    if (document.querySelector('input[name="ope"]').value !== 'edit') {
        const fechaInicio = '<?= $dtOne[0]['fecha_inicio'] ?? '' ?>';
        const fechaFin = '<?= $dtOne[0]['fecha_fin'] ?? '' ?>';
        
        if (fechaInicio) {
            document.getElementById('fecha_inicio').value = fechaInicio;
        }
        if (fechaFin) {
            document.getElementById('fecha_fin').value = fechaFin;
        }
        
        // Actualizar información del lapso
        actualizarInfoLapso();
    }
    <?php endif; ?>
    
    // Verificar si hay un evento seleccionado y cargar rango por evento
    const eventoSeleccionadoId = document.getElementById('evento_seleccionado_id').value;
    if (eventoSeleccionadoId) {
        document.getElementById('rango_por_evento').checked = true;
        toggleRangoPorEvento();
    }
}

/**
 * Función de utilidad para limpiar un campo de búsqueda
 */
function clearSearchField(fieldName) {
    document.getElementById(`search_${fieldName}`).value = '';
    document.getElementById(fieldName).value = '';
    document.getElementById(`dropdown_${fieldName}`).style.display = 'none';
}

/**
 * Formatea una fecha en zona horaria local sin problemas de UTC
 */
function formatearFechaLocal(fechaString) {
    // Separar año, mes y día
    const [año, mes, dia] = fechaString.split('-');
    
    // Crear array de nombres de meses en español
    const meses = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];
    
    // Formatear la fecha
    const diaFormateado = parseInt(dia).toString().padStart(2, '0');
    const mesFormateado = meses[parseInt(mes) - 1];
    const añoFormateado = año;
    
    return `${diaFormateado} de ${mesFormateado} de ${añoFormateado}`;
}

/**
 * Inicializa la funcionalidad de fechas para el formulario de horarios
 * 
 * Esta función configura los event listeners para los campos de fechas:
 * - Actualiza automáticamente la fecha mínima de fin cuando cambia la fecha de inicio
 * - Valida que la fecha de fin no sea anterior a la fecha de inicio
 * - Actualiza la información del lapso en tiempo real
 * - Mantiene la consistencia entre los campos de fechas
 */
function initFechaFuncionality() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    
    // Evento para fecha de inicio: actualiza validaciones y muestra información
    fechaInicio.addEventListener('change', function() {
        // Actualizar fecha mínima de fin para evitar fechas inválidas
        fechaFin.min = this.value;
        
        // Si la fecha de fin es anterior a la nueva fecha de inicio, actualizarla
        if (fechaFin.value && fechaFin.value < this.value) {
            fechaFin.value = this.value;
        }
        
        // Actualizar la información del lapso mostrada al usuario
        actualizarInfoLapso();
    });
    
    // Evento para fecha de fin: actualiza información del lapso
    fechaFin.addEventListener('change', function() {
        actualizarInfoLapso();
    });
    
    // Evento para fecha específica: actualiza información del lapso
    const fechaEspecifica = document.getElementById('fecha_especifica');
    if (fechaEspecifica) {
        fechaEspecifica.addEventListener('change', function() {
            actualizarInfoLapso();
        });
    }
    
    // Actualizar información del lapso al cargar la página
    actualizarInfoLapso();
}

/**
 * Alterna entre modo individual y modo rango
 */
function toggleModoIndividual() {
    const modoIndividual = document.getElementById('modo_individual').checked;
    const campoFechaEspecifica = document.getElementById('campo_fecha_especifica');
    const campoFechaInicio = document.getElementById('campo_fecha_inicio');
    const campoFechaFin = document.getElementById('campo_fecha_fin');
    
    if (modoIndividual) {
        // Mostrar campo de fecha específica y ocultar campos de rango
        campoFechaEspecifica.style.display = 'block';
        campoFechaInicio.style.display = 'none';
        campoFechaFin.style.display = 'none';
        
        // Hacer requerido el campo de fecha específica
        document.getElementById('fecha_especifica').required = true;
        document.getElementById('fecha_inicio').required = false;
        document.getElementById('fecha_fin').required = false;
        
        // Establecer la fecha específica como la fecha de inicio por defecto
        const fechaInicio = document.getElementById('fecha_inicio').value;
        if (fechaInicio) {
            document.getElementById('fecha_especifica').value = fechaInicio;
        }
        
        // Actualizar la información del lapso
        actualizarInfoLapso();
    } else {
        // Ocultar campo de fecha específica y mostrar campos de rango
        campoFechaEspecifica.style.display = 'none';
        campoFechaInicio.style.display = 'block';
        campoFechaFin.style.display = 'block';
        
        // Hacer requeridos los campos de rango
        document.getElementById('fecha_especifica').required = false;
        document.getElementById('fecha_inicio').required = true;
        document.getElementById('fecha_fin').required = true;
        
        // Actualizar la información del lapso
        actualizarInfoLapso();
    }
}

/**
 * Actualiza la información del lapso seleccionado
 */
function actualizarInfoLapso() {
    const modoIndividual = document.getElementById('modo_individual').checked;
    const infoLapso = document.getElementById('info_lapso');
    const formatoInicio = document.getElementById('formatoInicio');
    const formatoFin = document.getElementById('formatoFin');
    const duracionLapso = document.getElementById('duracion_lapso');
    
    if (modoIndividual) {
        // Modo individual: mostrar fecha específica
        const fechaEspecifica = document.getElementById('fecha_especifica').value;
        
        if (fechaEspecifica) {
            const fechaFormateada = formatearFechaLocal(fechaEspecifica);
            
            // Actualizar elementos para modo individual
            formatoInicio.textContent = fechaFormateada;
            formatoFin.textContent = fechaFormateada;
            duracionLapso.textContent = '1 día';
            
            // Mostrar información con color verde para modo individual
            infoLapso.style.display = 'block';
            infoLapso.className = 'alert alert-success';
        } else {
            // Ocultar información si no hay fecha específica
            infoLapso.style.display = 'none';
        }
    } else {
        // Modo rango: mostrar rango de fechas
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        if (fechaInicio && fechaFin) {
            // Crear fechas usando solo la fecha (sin tiempo) para evitar problemas de UTC
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            
            // Asegurar que las fechas se interpreten como locales
            inicio.setHours(0, 0, 0, 0);
            fin.setHours(0, 0, 0, 0);
            
            const diasDiferencia = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24)) + 1; // +1 para incluir ambos días
            
            // Formatear fechas para mostrar usando la fecha original del input
            const textoInicio = formatearFechaLocal(fechaInicio);
            const textoFin = formatearFechaLocal(fechaFin);
            
            // Actualizar el contenido de los elementos
            formatoInicio.textContent = textoInicio;
            formatoFin.textContent = textoFin;
            duracionLapso.textContent = `${diasDiferencia} días`;
            
            infoLapso.style.display = 'block';
            
            // Cambiar color según la duración
            if (diasDiferencia <= 30) {
                infoLapso.className = 'alert alert-success';
            } else if (diasDiferencia <= 90) {
                infoLapso.className = 'alert alert-info';
            } else if (diasDiferencia <= 180) {
                infoLapso.className = 'alert alert-warning';
            } else {
                infoLapso.className = 'alert alert-danger';
            }
            
        } else {
            infoLapso.style.display = 'none';
        }
    }
}

/**
 * Función para mostrar/ocultar los campos de "Otros"
 */
function toggleOtrosFields() {
    const checkbox = document.getElementById('es_otros');
    const camposOtros = document.getElementById('campos_otros');
    const camposTransversales = document.getElementById('campos_transversales');
    const searchIdfic = document.getElementById('search_idfic');
    
    if (checkbox.checked) {
        // Mostrar campos de "Otros"
        camposOtros.style.display = 'block';
        
        // Desactivar y ocultar campos de "Transversales"
        document.getElementById('es_transversal').checked = false;
        camposTransversales.style.display = 'none';
        
        // HACER EL CAMPO DE FICHA OPCIONAL PARA ACTIVIDADES ESPECIALES
        if (searchIdfic) {
            searchIdfic.placeholder = 'Opcional: Busque una ficha o deje vacío para actividad especial...';
            searchIdfic.style.borderColor = '#28a745'; // Verde para indicar que es opcional
        }
        
        // Scroll suave hacia los nuevos campos
        setTimeout(() => {
            camposOtros.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 100);
        
    } else {
        // Ocultar campos de "Otros"
        camposOtros.style.display = 'none';
        
        // RESTAURAR CAMPO DE FICHA COMO REQUERIDO
        if (searchIdfic) {
            searchIdfic.placeholder = 'Escriba para buscar ficha...';
            searchIdfic.style.borderColor = ''; // Restaurar color normal
        }
        
        // Limpiar campos de "Otros"
        document.getElementById('actividad').value = '';
        document.getElementById('horas_otros').value = '';
        document.getElementById('es_formacion_directa').checked = false;
    }
}

/**
 * Función para mostrar/ocultar los campos de "Transversales"
 */
function toggleTransversalFields() {
    const checkbox = document.getElementById('es_transversal');
    const camposTransversales = document.getElementById('campos_transversales');
    const camposOtros = document.getElementById('campos_otros');
    
    if (checkbox.checked) {
        // Mostrar campos de "Transversales"
        camposTransversales.style.display = 'block';
        
        // Desactivar y ocultar campos de "Otros"
        document.getElementById('es_otros').checked = false;
        camposOtros.style.display = 'none';
        
        // Solo hacer scroll si no es una edición
        if (document.querySelector('input[name="ope"]').value !== 'edit') {
            setTimeout(() => {
                camposTransversales.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'nearest' 
                });
            }, 100);
        }
        
    } else {
        // Ocultar campos de "Transversales"
        camposTransversales.style.display = 'none';
        
        // Solo limpiar campos si no es una edición
        if (document.querySelector('input[name="ope"]').value !== 'edit') {
            document.getElementById('nombre_transversal').value = '';
        }
    }
}

/**
 * Función para mostrar/ocultar los campos de rango por evento
 */
function toggleRangoPorEvento() {
    const checkbox = document.getElementById('rango_por_evento');
    const camposRangoEvento = document.getElementById('campos_rango_evento');
    const campoFechaInicio = document.getElementById('campo_fecha_inicio');
    const campoFechaFin = document.getElementById('campo_fecha_fin');
    
    if (checkbox.checked) {
        // Mostrar campos de rango por evento
        camposRangoEvento.style.display = 'block';
        
        // Ocultar campos de fecha manual pero NO deshabilitarlos
        campoFechaInicio.style.display = 'none';
        campoFechaFin.style.display = 'none';
        
        // NO desactivar campos de fecha manual para que se envíen en el formulario
        // document.getElementById('fecha_inicio').disabled = true;
        // document.getElementById('fecha_fin').disabled = true;
        
        // Desactivar otros checkboxes
        document.getElementById('es_otros').checked = false;
        document.getElementById('es_transversal').checked = false;
        toggleOtrosFields();
        toggleTransversalFields();
        
        // Scroll suave hacia los nuevos campos
        setTimeout(() => {
            camposRangoEvento.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 100);
        
    } else {
        // Ocultar campos de rango por evento
        camposRangoEvento.style.display = 'none';
        
        // Mostrar campos de fecha manual
        campoFechaInicio.style.display = 'block';
        campoFechaFin.style.display = 'block';
        
        // Asegurar que los campos de fecha estén habilitados
        document.getElementById('fecha_inicio').disabled = false;
        document.getElementById('fecha_fin').disabled = false;
        
        // Limpiar campos de evento
        document.getElementById('tipo_evento').value = '';
        document.getElementById('evento_calendario').value = '';
        document.getElementById('info_evento').style.display = 'none';
        
        // Limpiar campos ocultos
        document.getElementById('evento_seleccionado_id').value = '';
        document.getElementById('evento_seleccionado_titulo').value = '';
        document.getElementById('evento_seleccionado_fecha_inicio').value = '';
        document.getElementById('evento_seleccionado_fecha_fin').value = '';
        
        // Limpiar campos de fecha
        document.getElementById('fecha_inicio').value = '';
        document.getElementById('fecha_fin').value = '';
        
        // Actualizar información del lapso
        actualizarInfoLapso();
    }
}

/**
 * Carga los eventos según el tipo seleccionado
 */
function cargarEventosPorTipo() {
    const tipoEvento = document.getElementById('tipo_evento').value;
    const selectEvento = document.getElementById('evento_calendario');
    
    if (!tipoEvento) {
        selectEvento.innerHTML = '<option value="">Primero seleccione el tipo de evento...</option>';
        return;
    }
    
    // Limpiar eventos anteriores
    selectEvento.innerHTML = '<option value="">Cargando eventos...</option>';
    
    // Crear FormData para enviar la consulta
    const formData = new FormData();
    formData.append('ope', 'get_eventos_por_tipo');
    formData.append('tipo_evento', tipoEvento);
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.eventos) {
            selectEvento.innerHTML = '<option value="">Seleccione un evento...</option>';
            
            data.eventos.forEach(evento => {
                const option = document.createElement('option');
                option.value = evento.id;
                
                // Formatear fechas para mostrar mes y día (evitar problemas de UTC)
                const fechaInicio = new Date(evento.fecha_inicio + 'T00:00:00');
                const fechaFin = new Date(evento.fecha_fin + 'T00:00:00');
                
                const mesInicio = fechaInicio.toLocaleDateString('es-ES', { month: 'short' });
                const diaInicio = fechaInicio.getDate();
                const mesFin = fechaFin.toLocaleDateString('es-ES', { month: 'short' });
                const diaFin = fechaFin.getDate();
                
                // Crear texto del option con rango de fechas
                let textoOption = `${evento.titulo} (${evento.anio})`;
                
                // Si las fechas son del mismo mes, mostrar solo una vez
                if (fechaInicio.getMonth() === fechaFin.getMonth()) {
                    textoOption += ` - ${diaInicio} al ${diaFin} ${mesInicio}`;
                } else {
                    // Si son meses diferentes, mostrar ambos
                    textoOption += ` - ${diaInicio} ${mesInicio} al ${diaFin} ${mesFin}`;
                }
                
                textoOption += ` - ${evento.trimestre}`;
                
                option.textContent = textoOption;
                option.setAttribute('data-fecha-inicio', evento.fecha_inicio);
                option.setAttribute('data-fecha-fin', evento.fecha_fin);
                option.setAttribute('data-titulo', evento.titulo);
                option.setAttribute('data-trimestre', evento.trimestre);
                selectEvento.appendChild(option);
            });
        } else {
            selectEvento.innerHTML = '<option value="">No se encontraron eventos</option>';
        }
    })
    .catch(error => {
        console.error('Error cargando eventos:', error);
        selectEvento.innerHTML = '<option value="">Error al cargar eventos</option>';
    });
}

/*
 * ====================================================================================
 * (Esto es para eliminar TODOS los registros de horarios, usar solo en PRODUCCIÓN)
 * ====================================================================================
 */
/*
function confirmarEliminacionTotal() {
    const confirmacion = confirm(
        '¿Estás seguro de que quieres eliminar TODOS los horarios del sistema?\n\n' +
        'Esta acción eliminará todos los horarios y reseteará los contadores.\n' +
        'NO SE PUEDE DESHACER.\n\n' +
        '¿Continuar?'
    );
    
    if (confirmacion) {
        ejecutarEliminacionTotal();
    }
}

function ejecutarEliminacionTotal() {
    // Mostrar indicador de carga
    const boton = document.querySelector('button[onclick="confirmarEliminacionTotal()"]');
    const textoOriginal = boton.innerHTML;
    
    boton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>PROCESANDO...';
    boton.disabled = true;
    
    // Crear FormData para enviar la solicitud
    const formData = new FormData();
    formData.append('ope', 'eliminar_todos_horarios');
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ OPERACIÓN COMPLETADA\n\n' + data.message);
            // Recargar la página para mostrar el estado actualizado
            location.reload();
        } else {
            alert('❌ ERROR EN LA OPERACIÓN\n\n' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ ERROR DE CONEXIÓN\n\nNo se pudo completar la operación. Verifica la conexión e intenta nuevamente.');
    })
    .finally(() => {
        // Restaurar el botón
        boton.innerHTML = textoOriginal;
        boton.disabled = false;
    });
}
*/

/**
 * Selecciona un evento y llena automáticamente las fechas
 */
function seleccionarEvento() {
    const selectEvento = document.getElementById('evento_calendario');
    if (!selectEvento) {
        console.error('Elemento evento_calendario no encontrado');
        return;
    }
    
    const eventoId = selectEvento.value;
    
    const infoEvento = document.getElementById('info_evento');
    if (!infoEvento) {
        console.error('Elemento info_evento no encontrado');
        return;
    }
    
    if (!eventoId) {
        infoEvento.style.display = 'none';
        return;
    }
    
    const option = selectEvento.options[selectEvento.selectedIndex];
    const fechaInicio = option.getAttribute('data-fecha-inicio');
    const fechaFin = option.getAttribute('data-fecha-fin');
    const titulo = option.getAttribute('data-titulo');
    const trimestre = option.getAttribute('data-trimestre');
    
    // Llenar campos ocultos (con validación)
    const eventoSeleccionadoId = document.getElementById('evento_seleccionado_id');
    const eventoSeleccionadoTitulo = document.getElementById('evento_seleccionado_titulo');
    const eventoSeleccionadoFechaInicio = document.getElementById('evento_seleccionado_fecha_inicio');
    const eventoSeleccionadoFechaFin = document.getElementById('evento_seleccionado_fecha_fin');
    
    if (eventoSeleccionadoId) eventoSeleccionadoId.value = eventoId;
    if (eventoSeleccionadoTitulo) eventoSeleccionadoTitulo.value = titulo;
    if (eventoSeleccionadoFechaInicio) eventoSeleccionadoFechaInicio.value = fechaInicio;
    if (eventoSeleccionadoFechaFin) eventoSeleccionadoFechaFin.value = fechaFin;
    
    // ACTUALIZAR CAMPOS VISIBLES para que se muestre en el "Lapso seleccionado"
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');
    
    if (fechaInicioInput) fechaInicioInput.value = fechaInicio;
    if (fechaFinInput) fechaFinInput.value = fechaFin;
    
    // ACTUALIZAR EL LAPSO SELECCIONADO para mostrar las fechas del evento
    if (typeof actualizarInfoLapso === 'function') {
        actualizarInfoLapso();
    }
    
    // Mostrar información del evento (con validación)
    const tituloEvento = document.getElementById('titulo_evento');
    if (tituloEvento) {
        tituloEvento.textContent = titulo;
    }
    
    // Formatear fechas para mostrar de manera más clara (evitando problemas UTC)
    // Separar las fechas manualmente para evitar problemas de zona horaria
    const [añoInicio, mesInicioNum, diaInicioNum] = fechaInicio.split('-').map(Number);
    const [añoFin, mesFinNum, diaFinNum] = fechaFin.split('-').map(Number);
    
    const meses = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];
    
    const mesInicio = meses[mesInicioNum - 1];
    const mesFin = meses[mesFinNum - 1];
    const diaInicio = diaInicioNum;
    const diaFin = diaFinNum;
    
    // Crear texto del rango de fechas
    let textoRango;
    if (mesInicioNum === mesFinNum) {
        // Mismo mes
        textoRango = `${diaInicio} al ${diaFin} de ${mesInicio}`;
    } else {
        // Meses diferentes
        textoRango = `${diaInicio} de ${mesInicio} al ${diaFin} de ${mesFin}`;
    }
    
    // Actualizar información del evento (con validación)
    const fechaInicioEvento = document.getElementById('fecha_inicio_evento');
    const fechaFinEvento = document.getElementById('fecha_fin_evento');
    const trimestreEvento = document.getElementById('trimestre_evento');
    
    if (fechaInicioEvento) fechaInicioEvento.textContent = textoRango;
    if (fechaFinEvento) fechaFinEvento.textContent = ''; // Ya no necesitamos mostrar fecha fin por separado
    if (trimestreEvento) trimestreEvento.textContent = trimestre;
    
    // Mostrar la información del evento
    if (infoEvento) infoEvento.style.display = 'block';
    
    // Llenar campos de fecha (aunque estén ocultos, para el envío del formulario) - con validación
    const fechaInicioField = document.getElementById('fecha_inicio');
    const fechaFinField = document.getElementById('fecha_fin');
    
    if (fechaInicioField) fechaInicioField.value = fechaInicio;
    if (fechaFinField) fechaFinField.value = fechaFin;
    
    // Asegurar que los campos estén habilitados para el envío del formulario - con validación
    if (fechaInicioField) fechaInicioField.disabled = false;
    if (fechaFinField) fechaFinField.disabled = false;
    
    // Actualizar información del lapso - con validación
    if (typeof actualizarInfoLapso === 'function') {
        actualizarInfoLapso();
    }
}



/**
 * Función para abrir el modal de edición de transversales
 */
function abrirModalEditarTransversal(idhor, nombreTransversal, idaul, idusu, dia, ficha) {
    // Cargar datos en el modal
    document.getElementById('idhor_edit').value = idhor;
    document.getElementById('nombre_transversal_edit').value = nombreTransversal;
    document.getElementById('ficha_edit').value = ficha;
    document.getElementById('dia_edit').value = dia;
    
    // Seleccionar instructor si existe (manejar valores null/undefined)
    if (idusu && idusu !== 'null' && idusu !== 'undefined' && idusu !== '') {
        document.getElementById('instructor_edit').value = idusu;
    } else {
        document.getElementById('instructor_edit').value = '';
    }
    
    // Seleccionar aula si existe (manejar valores null/undefined)
    if (idaul && idaul !== 'null' && idaul !== 'undefined' && idaul !== '') {
        document.getElementById('aula_edit').value = idaul;
    } else {
        document.getElementById('aula_edit').value = '';
    }
    
    // Abrir el modal
    const modal = new bootstrap.Modal(document.getElementById('modalEditarTransversal'));
    modal.show();
}

/**
 * Función para guardar la edición del transversal
 */
function guardarEdicionTransversal() {
    // Validar formulario
    const nombreTransversal = document.getElementById('nombre_transversal_edit').value.trim();
    const idaul = document.getElementById('aula_edit').value;
    const idusu = document.getElementById('instructor_edit').value;
    
    if (!nombreTransversal) {
        alert('Debe ingresar el nombre de la transversal.');
        return;
    }
    
    if (!idaul) {
        alert('Debe seleccionar un aula.');
        return;
    }
    
    // Verificar si es edición grupal
    const idhorElement = document.getElementById('idhor_edit');
    const esEdicionGrupal = idhorElement.getAttribute('data-edicion-grupal') === 'true';
    const fechaInicio = idhorElement.getAttribute('data-fecha-inicio');
    const fechaFin = idhorElement.getAttribute('data-fecha-fin');
    const idaulOriginal = idhorElement.getAttribute('data-idaul-original');
    const idusuOriginal = idhorElement.getAttribute('data-idusu-original');
    
    // Verificar que el elemento existe y tiene los atributos necesarios
    if (!idhorElement) {
        alert('Error: Elemento de edición no encontrado');
        return;
    }
    
    // Verificar que los atributos estén presentes si es edición grupal
    if (esEdicionGrupal && (!fechaInicio || !fechaFin)) {
        console.error('❌ Edición grupal detectada pero faltan fechas:', { fechaInicio, fechaFin });
        alert('Error: Fechas de edición grupal no encontradas. Intente nuevamente.');
        return;
    }

    let mensajeConfirmacion = '¿Está seguro de guardar los cambios en este horario transversal?';
    if (esEdicionGrupal) {
        mensajeConfirmacion = `¿Está seguro de guardar los cambios en TODO el grupo de horarios transversales relacionados?\n\nEsto afectará a todos los horarios desde ${fechaInicio} hasta ${fechaFin} que mantengan la misma aula e instructor originales.\n\nHorarios que hayan sido editados individualmente NO serán afectados.`;
    }
    
    // Confirmar antes de guardar
    if (!confirm(mensajeConfirmacion)) {
        return;
    }
    
    // Crear FormData con todos los campos necesarios
    const formData = new FormData();
    const operacion = esEdicionGrupal ? 'edit_grupo_transversal' : 'edit';
    formData.append('ope', operacion);
    formData.append('idhor', idhorElement.value);
    formData.append('nombre_transversal', nombreTransversal);
    formData.append('idaul', idaul);
    formData.append('idusu', idusu || ''); // Enviar string vacío si no hay instructor
    formData.append('es_transversal', '1');
    
    // Si es edición grupal, agregar las fechas y valores originales
    if (esEdicionGrupal) {
        formData.append('fecha_inicio', fechaInicio);
        formData.append('fecha_fin', fechaFin);
        formData.append('idaul_original', idaulOriginal);
        formData.append('idusu_original', idusuOriginal);
    }
    
    // Enviar datos via AJAX
    
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        return response.text();
    })
    .then(data => {  
        // Intentar parsear como JSON para ver si es una respuesta estructurada
        try {
            const jsonData = JSON.parse(data);
            
        } catch (e) {}
        
        // Verificar si la respuesta contiene algún mensaje de error
        if (data.includes('Error') || data.includes('error')) {
            console.error('❌ Error detectado en la respuesta');
            throw new Error('Error en el servidor');
        }
        
        // Limpiar atributos de edición grupal solo después de una edición exitosa
        if (esEdicionGrupal) {
            limpiarAtributosEdicionGrupal();
        }
        
        // Cerrar modal
        document.getElementById('modalEditarTransversal').classList.remove('visible');
        document.getElementById('overlayEliminacion').style.display = 'none';
        
        // Mostrar mensaje de éxito
        const mensajeExito = esEdicionGrupal ? 
            'Grupo de horarios transversales actualizado correctamente.' : 
            'Horario transversal actualizado correctamente.';
        alert(mensajeExito);
        
        // Recargar la página para mostrar los cambios
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el horario transversal. Intente nuevamente.');
    });
}
</script>


<!-- Filtro por área y semana -->
<form action="home.php?pg=<?= $pg; ?>" method="POST" id="formFiltros">
    <div class="row">
        <?php if ($perfil_usuario['idper'] != 4): // Solo mostrar filtro de área para perfiles que no sean restringidos ?>
        <div class="form-group col-md-4">
            <label for="idare">Filtro por área</label>

            <?php
              $idare = $_POST['idare'] ?? ($areasFavoritas[0]['idare'] ?? null);
?>

                <select name="idare" id="idare" class="form-select" onchange="this.form.submit()">
                    <?php if ($dar): ?>
                    <?php foreach ($dar as $de): ?>
                    <option value="<?= $de['idare']; ?>" <?= ($idare == $de['idare']) ? 'selected' : ''; ?>>
                        <?= $de['nomare']; ?>
                    </option>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </select>

            <?php if (!isset($_POST['idare']) && !empty($areasFavoritas)): ?>
            <script>
            window.addEventListener('DOMContentLoaded', function() {
                document.getElementById('idare').dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            });
            </script>
            <?php endif; ?>

            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="marcar_favorito" value="1" id="marcar_favorito"
                    <?= !empty($areasFavoritas['idare']) && $areasFavoritas['idare'] == $idare ? 'checked' : ''; ?>>
                <label class="form-check-label" for="marcar_favorito">Marcar área favorita</label>
            </div>

            <script>
            document.getElementById('marcar_favorito')?.addEventListener('change', function() {
                this.closest('form').submit();
            });
            </script>
        </div>
        <?php endif; ?>

        <div class="form-group <?= $perfil_usuario['idper'] == 4 ? 'col-md-8' : 'col-md-4'; ?>">
            <label for="semana_selector">Semana</label>
            <div class="input-group">
                <input type="week" 
                       id="semana_selector" 
                       name="semana_selector" 
                       class="form-control" 
                       value="<?= $_POST['semana_selector'] ?? calcularSemanaISO() ?>"
                       onchange="cambiarSemana(this.value)">
                <button type="button" class="btn btn-outline-secondary" onclick="semanaActual()">
                    <i class="fa-solid fa-calendar-day"></i> Hoy
                </button>
            </div>
            <small class="form-text text-muted">Selecciona la semana para ver los horarios</small>
        </div>

        <?php if ($perfil_usuario['idper'] != 4): // Solo mostrar botón de imprimir general para perfiles que no sean restringidos ?>
        <div class="form-group col-md-4" style="text-align: end;">
            <br>
            <a href="views/vrphg.php?idare=<?= $idare; ?>&fic=2773071" title="Imprimir" target="_blank">
                <i class="fa-solid fa-print fa-2x"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</form>
<!--Fin Filtro por área y semana -->





<!-- Información de la semana seleccionada -->
<?php 
// Lógica simplificada para el selector de semana
if (!isset($_POST['semana_selector'])) {
    // Carga inicial: siempre mostrar la semana actual
    $semanaSelector = calcularSemanaISO();
} else {
    // Usar la semana seleccionada manualmente
    $semanaSelector = $_POST['semana_selector'];
}

$verHoy = isset($_POST['ver_hoy']) && $_POST['ver_hoy'] === '1';

if ($verHoy) {
    // Botón "Hoy": mostrar la semana actual
    $semanaSelector = calcularSemanaISO(); // Siempre semana actual
    $semanaFormateada = formatearSemanaPHP($semanaSelector);
    $fechaHoy = date('d/m/Y');
    $mensaje = "Mostrando <strong>semana actual</strong> donde está hoy ($fechaHoy): <strong>$semanaFormateada</strong>";
    $color = "rgba(0, 175, 0, 0.1)";
    $colorTexto = "#00af00";
    $colorBorde = "rgba(0, 175, 0, 0.3)";
    $icono = "fa-calendar-week";
} else {
    // Sin botón "Hoy": mostrar la semana correspondiente
    $semanaFormateada = formatearSemanaPHP($semanaSelector);
    
    if (!isset($_POST['semana_selector'])) {
        $mensaje = "Mostrando <strong>semana actual</strong>: <strong>$semanaFormateada</strong>";
    } else {
        $mensaje = "Semana seleccionada: <strong>$semanaFormateada</strong>";
    }
    
    $color = "rgba(0, 175, 0, 0.1)";
    $colorTexto = "#00af00";
    $colorBorde = "rgba(0, 175, 0, 0.3)";
    $icono = "fa-calendar-week";
}
?>
<div class="alert mb-3" style="background-color: <?= $color ?>; color: <?= $colorTexto ?>; border: 1px solid <?= $colorBorde ?>;">
    <i class="fa-solid <?= $icono ?> me-2"></i>
    <?= $mensaje ?>
</div>

<!-- Tabla de horarios -->
<?php if ($dft) {?>
<table id='example' class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Ficha y Programa</th>
            <th></th>
            <?php pintit($ddi); ?>
        </tr>
    </thead>
    <tbody>
        <?php if ($dft) {
            foreach ($dft as $dt) { ?>
        <tr>
            <td>
                <?=$dt['idfic']; ?> - <?=$dt['nomfic']; ?><br>
                <small>
                    Jornada: <?=$dt['nomval']; ?>
                    <?php if ($perfil_usuario['idper'] != 4): // Ocultar área para perfil 4 ?>
                    Área: <?=$dt['nomare']; ?>
                    <?php endif; ?>
                    Cod. Programa: <?=$dt['codpro']; ?>
                </small>
            </td>
            <td>
                <?php if ($restricciones['mostrar_botones_crear'] || $restricciones['mostrar_botones_editar']): ?>
                <a href="home.php?pg=1509&idfic=<?=$dt['idfic']; ?>" title="Agenda Ficha <?=$dt['idfic']; ?>">
                    <i class="fa-regular fa-calendar-check fa-2x"></i>
                </a>
                <?php endif; ?>
                
                <?php if ($restricciones['mostrar_boton_imprimir']): ?>
                <a href="views/vrphfc.php?idfic=<?=$dt['idfic']; ?>" title="Imprimir horario x ficha" target="_blank">
                    <i class="fa-solid fa-print fa-2x"></i>
                </a>
                <?php endif; ?>
            </td>
            <?php
                      if ($ddi) {
                          foreach ($ddi as $d) {
                              //echo "<th>".$dt["idfic"]." - ".$d['idval']."</th>";

                              $mhor->setIdfic($dt['idfic']);
                              $mhor->setIddia($d['idval']);
                              
                              // Usar el selector de semana ya definido arriba
                              // La variable $verHoy ya está definida arriba, no redefinir
                              
                              if ($verHoy) {
                                  // Cuando se presiona "Hoy", usar la semana actual
                                  $semanaSelectorHoy = calcularSemanaISO();
                              } else {
                                  // Usar el selector de semana ya definido
                                  $semanaSelectorHoy = $semanaSelector;
                              }
                              
                              // Usar siempre el mismo filtro con el selector de semana correspondiente
                              $dat = $mhor->getAllUltraEstricto($semanaSelectorHoy);

                                                                    if ($dat) {
                                          foreach ($dat as $dti) {
                                              // Debug: Datos del horario
                                              echo "<!-- DEBUG Horario: ID=" . $dti['idhor'] . ", IDFIC=" . $dt['idfic'] . ", Jornada=" . $dt['nomval'] . ", IDUSU=" . ($dti['idusu'] ?? 'NULL') . ", IDAUL=" . ($dti['idaul'] ?? 'NULL') . " -->";
                                              
                                              // Usar los colores del instructor para ambos casos
                                              $colorFondo = !empty($dti['colfon']) ? '#'.$dti['colfon'] : '#f8f9fa';
                                              $colorTexto = !empty($dti['coltex']) ? '#'.$dti['coltex'] : '#212529';

                                      echo "<td class='position-relative' style='background: $colorFondo; color: $colorTexto;'>";

                                      // Mostrar nombre del instructor (si existe)
                                      if (!empty($dti['idusu']) && !empty($dti['nomusu'])) {
                                      echo $dti['ndocusu'].' '.$dti['nomusu'];
                                          if ($perfil_usuario['idper'] != 4) { // Ocultar botón de imprimir por instructor para perfil 4
                                          echo "<a href='views/vrphor.php?idusu=".$dti['idusu']."' title='Imprimir horario del instructor' target='_blank' style='color: #00af00; margin-left: 5px;'>";
                                          echo "<i class='fa-solid fa-print fa-lg'></i>";
                                          echo '</a>';
                                          }
                                      } else {
                                          // Para transversales sin instructor
                                          if (!empty($dti['es_transversal']) && ($dti['es_transversal'] == 1 || $dti['es_transversal'] === 1 || $dti['es_transversal'] === true || $dti['es_transversal'] === '1')) {
                                              echo "<em class='text-muted'>Sin instructor asignado</em>";
                                          } else {
                                              echo "<em class='text-muted'>Sin instructor</em>";
                                          }
                                      }

                                      // Si es actividad especial, mostrar información adicional
                                      if (!empty($dti['es_otros']) && ($dti['es_otros'] == 1 || $dti['es_otros'] === 1 || $dti['es_otros'] === true || $dti['es_otros'] === '1')) {
                                          echo "<br><small class='badge' style='background-color: #28a745; color: white;'>";
                                          echo "<i class='fa-solid fa-calendar-plus me-1'></i>";
                                          echo htmlspecialchars($dti['actividad']);
                                          echo "</small>";
                                          echo "<br><small class='text-muted'>";
                                          echo $dti['horas_otros']." hrs";
                                          echo "</small>";
                                      }

                                      // Si es transversal, mostrar información adicional
                                      if (!empty($dti['es_transversal']) && ($dti['es_transversal'] == 1 || $dti['es_transversal'] === 1 || $dti['es_transversal'] === true || $dti['es_transversal'] === '1')) {
                                          echo "<br><small class='badge' style='background-color: #00af00; color: white;'>";
                                          echo "<i class='fa-solid fa-book-open me-1'></i>";
                                          echo "Transversal: " . htmlspecialchars($dti['nombre_transversal']);
                                          echo "</small>";
                                      }

                                      // Mostrar fecha específica del horario (más útil para el usuario)
                                      if (!empty($dti['fecha_especifica'])) {
                                          $fechaEspecifica = date('d/m/Y', strtotime($dti['fecha_especifica']));
                                          $diaSemana = date('N', strtotime($dti['fecha_especifica']));
                                          
                                          // Mapear día de la semana a español
                                          $diasSemana = [
                                              1 => 'Lunes',
                                              2 => 'Martes', 
                                              3 => 'Miércoles',
                                              4 => 'Jueves',
                                              5 => 'Viernes',
                                              6 => 'Sábado',
                                              7 => 'Domingo'
                                          ];
                                          
                                          $diaSemanaEsp = $diasSemana[$diaSemana] ?? 'Día';
                                          
                                          echo "<br><small class='text-muted'>";
                                          echo "<i class='fa-solid fa-calendar-day me-1'></i>";
                                          echo "$diaSemanaEsp, $fechaEspecifica";
                                          echo "</small>";
                                      }

                                      echo '<BR>';
                                      if (!empty($dti['idaul']) && !empty($dti['nomaul'])) {
                                          echo $dti['nomaul'];
                                          if ($perfil_usuario['idper'] != 4) { // Ocultar botón de imprimir por aula para perfil 4
                                          echo "<a href='views/vrphau.php?idaul=".$dti['idaul']."' title='Imprimir horario del aula ".$dti['idaul']."' target='_blank' style='color: #00af00; margin-left: 5px;'>";
                                          echo "<i class='fa-solid fa-print fa-lg'></i>";
                                          echo '</a>';
                                          }
                                      } else {
                                          echo "<em class='text-muted'>Sin aula asignada</em>";
                                      }
                                      ?>

            <?php if ($_SESSION['idper'] == 21) { ?>
            <div class="position-absolute bottom-0 end-0">
                <button type="button" 
                        class="btn btn-link p-0" 
                        data-idhor="<?=$dti['idhor']?>"
                        data-iddia="<?=$dti['iddia']?>"
                        data-idfic="<?=$dt['idfic']?>"
                        data-idusu="<?=$dti['idusu']?>"
                        data-idaul="<?=$dti['idaul']?>"
                        data-nomfic='<?=json_encode($dt['nomfic'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                        data-nomusu='<?=json_encode($dti['nomusu'] ?? "Sin instructor", JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                        data-nomaul='<?=json_encode($dti['nomaul'] ?? "Sin aula", JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                        data-fecha-inicio="<?=$dti['fecha_inicio'] ?? ''?>"
                        data-fecha-fin="<?=$dti['fecha_fin'] ?? ''?>"
                        data-jornada='<?=json_encode($dt['nomval'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                        onclick="onClickEliminar(this)"
                        title="Eliminar">
                    <i class="fa-solid fa-trash-can fa-lg fa-2x"
                        style="text-shadow: 0px 0px 2px #fff, 0px 0px 2px #fff, 0px 0px 2px #fff;padding: 6px 0px; color: #00af00;"></i>
                </button>
            </div>
            
            <!-- Botón de editar para transversales -->
            <?php if (!empty($dti['es_transversal']) && ($dti['es_transversal'] == 1 || $dti['es_transversal'] === 1 || $dti['es_transversal'] === true || $dti['es_transversal'] === '1')) { ?>
            <button type="button" 
                    class="btn btn-link p-0 position-absolute bottom-0" 
                    style="right: 50px;"
                    data-idhor="<?=$dti['idhor']?>"
                    data-nombre-transversal='<?=json_encode($dti['nombre_transversal'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                    data-idaul="<?=$dti['idaul'] ?? ''?>"
                    data-idusu="<?=$dti['idusu'] ?? ''?>"
                    data-dia='<?=json_encode($d['nomval'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                    data-ficha='<?=json_encode($dt['idfic'] . ' - ' . $dt['nomfic'], JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)?>'
                    data-fecha-inicio="<?=$dti['fecha_inicio'] ?? ''?>"
                    data-fecha-fin="<?=$dti['fecha_fin'] ?? ''?>"
                    onclick="onClickEditarTransversal(this)"
                    title="Editar Transversal">
                <i class="fa-solid fa-edit fa-lg fa-2x"
                    style="text-shadow: 0px 0px 2px #fff, 0px 0px 2px #fff, 0px 0px 2px #fff;padding: 6px 0px; color: #00af00;"></i>
            </button>
            <?php } ?>
            <?php } ?>

            <?php
                                        echo '</td>';
                                  }
                              } else {
                                  echo '<td></td>';
                              }
                          }
                      }
                ?>
        </tr>
        <?php }
            } ?>
    </tbody>

    <tfoot>
        <tr>
            <th>Ficha y Programa</th>
            <th></th>
            <?php pintit($ddi); ?>
        </tr>
    </tfoot>
</table>
<?php }?>

<!-- Modal Personalizado de Opciones de Eliminación -->
<div id="modalEliminacion" class="modal-eliminacion" style="display: none;">
    <div class="modal-eliminacion-content">
        <div class="modal-eliminacion-header">
            <h5 class="modal-eliminacion-title">
                <i class="fa-solid fa-trash-can me-2" style="color: #00af00;"></i>
                Opciones de Eliminación
            </h5>
            <button type="button" class="modal-eliminacion-close" onclick="cerrarModalEliminacion()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="modal-eliminacion-body">
            <div class="info-horario">
                <i class="fa-solid fa-info-circle me-2" style="color: #00af00;"></i>
                <strong>Horario seleccionado:</strong><br>
                <span id="infoHorarioEliminar"></span>
            </div>
            
            <div id="infoFechas" class="info-fechas" style="display: none;">
                <i class="fa-solid fa-calendar-alt me-2" style="color: #00af00;"></i>
                <strong>Información de fechas:</strong><br>
                <span id="detalleFechas"></span>
            </div>
            
            <div class="opciones-eliminacion">
                <div class="opcion-eliminacion">
                    <div class="opcion-icono">
                        <i class="fa-solid fa-calendar-day fa-3x" style="color: #00af00;"></i>
                    </div>
                    <div class="opcion-contenido">
                        <h6>Eliminar Individual</h6>
                        <p>Elimina solo este horario específico</p>
                        <button type="button" class="btn-eliminar-individual" onclick="eliminarHorarioIndividual()">
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Eliminar Individual
                        </button>
                    </div>
                </div>
                
                <div class="opcion-eliminacion">
                    <div class="opcion-icono">
                        <i class="fa-solid fa-calendar-week fa-3x" style="color: #00af00;"></i>
                    </div>
                    <div class="opcion-contenido">
                        <h6>Eliminar Grupo</h6>
                        <p>Elimina todo el grupo de horarios relacionados</p>
                        <button type="button" class="btn-eliminar-grupo" onclick="eliminarGrupoHorarios()">
                            <i class="fa-solid fa-trash-can me-1"></i>
                            Eliminar Grupo
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="advertencia-eliminacion">
                <i class="fa-solid fa-exclamation-triangle me-2" style="color: #ffc107;"></i>
                <strong>Advertencia:</strong> Esta acción no se puede deshacer.
            </div>
        </div>
        
        <div class="modal-eliminacion-footer">
            <button type="button" class="btn-cancelar" onclick="cerrarModalEliminacion()">
                <i class="fa-solid fa-times me-1"></i>
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Modal Personalizado de Opciones de Edición para Transversales -->
<div id="modalEdicionTransversal" class="modal-eliminacion" style="display: none;">
    <div class="modal-eliminacion-content">
        <div class="modal-eliminacion-header">
            <h5 class="modal-eliminacion-title">
                <i class="fa-solid fa-edit me-2" style="color: #00af00;"></i>
                Opciones de Edición para Transversal
            </h5>
            <button type="button" class="modal-eliminacion-close" onclick="cerrarModalEdicionTransversal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="modal-eliminacion-body">
            <div class="info-horario">
                <i class="fa-solid fa-info-circle me-2" style="color: #00af00;"></i>
                <strong>Transversal seleccionada:</strong><br>
                <span id="infoTransversalEditar"></span>
            </div>
            
            <div id="infoFechasEdicion" class="info-fechas" style="display: none;">
                <i class="fa-solid fa-calendar-alt me-2" style="color: #00af00;"></i>
                <strong>Información de fechas:</strong><br>
                <span id="detalleFechasEdicion"></span>
            </div>
            
            <div class="opciones-eliminacion">
                <div class="opcion-eliminacion">
                    <div class="opcion-icono">
                        <i class="fa-solid fa-calendar-day fa-3x" style="color: #00af00;"></i>
                    </div>
                    <div class="opcion-contenido">
                        <h6>Editar Individual</h6>
                        <p>Modifica solo este horario específico</p>
                        <button type="button" class="btn-eliminar-individual" onclick="editarTransversalIndividual()">
                            <i class="fa-solid fa-edit me-1"></i>
                            Editar Individual
                        </button>
                    </div>
                </div>
                
                <div class="opcion-eliminacion">
                    <div class="opcion-icono">
                        <i class="fa-solid fa-calendar-week fa-3x" style="color: #00af00;"></i>
                    </div>
                    <div class="opcion-contenido">
                        <h6>Editar Grupo</h6>
                        <p>Modifica todo el grupo de horarios relacionados</p>
                        <button type="button" class="btn-editar-grupo" onclick="editarTransversalGrupo()">
                            <i class="fa-solid fa-edit me-1"></i>
                            Editar Grupo
                        </button>
                    </div>
                </div>
            </div>
            
            
        </div>
        
        <!-- Sin footer: solo se permite cerrar con la X del header -->
    </div>
</div>

<!-- Overlay para el modal -->
<div id="overlayEliminacion" class="overlay-eliminacion" style="display: none;" onclick="cerrarTodoOverlay()"></div>

<!-- Estilos CSS para el Modal Personalizado -->
<style>
.modal-eliminacion {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 9999 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
}

.modal-eliminacion.visible,
.modal-eliminacion[style*="display: flex"],
.modal-eliminacion[style*="display:block"] {
    display: flex !important;
}

.overlay-eliminacion {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9998;
}

.modal-eliminacion-content {
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    z-index: 9999;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-eliminacion-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-eliminacion-title {
    margin: 0;
    color: #333;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-eliminacion-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6c757d;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    transition: all 0.2s ease;
}

.modal-eliminacion-close:hover {
    background-color: #e9ecef;
    color: #495057;
}

.modal-eliminacion-body {
    padding: 25px;
}

.info-horario {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #90caf9;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    color: #1565c0;
}

.info-fechas {
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
    border: 1px solid #81c784;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 25px;
    color: #2e7d32;
}

.opciones-eliminacion {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

.opcion-eliminacion {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.opcion-eliminacion:hover {
    border-color: #00af00;
    box-shadow: 0 5px 15px rgba(0, 175, 0, 0.2);
    transform: translateY(-2px);
}

.opcion-icono {
    margin-bottom: 15px;
}

.opcion-contenido h6 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.opcion-contenido p {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 15px;
    line-height: 1.4;
}

.btn-eliminar-individual,
.btn-eliminar-grupo {
    background: linear-gradient(135deg, #00af00 0%, #008f00 100%);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    font-size: 0.9rem;
}

.btn-eliminar-individual:hover,
.btn-eliminar-grupo:hover {
    background: linear-gradient(135deg, #008f00 0%, #007000 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 175, 0, 0.3);
}

.btn-eliminar-grupo:disabled {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    cursor: not-allowed;
    opacity: 0.5;
}

.btn-eliminar-grupo:disabled:hover {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    transform: none;
    box-shadow: none;
}

/* Estilos para el botón de editar grupo */
.btn-editar-grupo {
    background: linear-gradient(135deg, #00af00 0%, #008f00 100%);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-editar-grupo:hover {
    background: linear-gradient(135deg, #008f00 0%, #007000 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 175, 0, 0.3);
}

.btn-editar-grupo:disabled {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    cursor: not-allowed;
    opacity: 0.5;
}

.btn-editar-grupo:disabled:hover {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    transform: none;
    box-shadow: none;
}

.advertencia-eliminacion {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 15px;
    color: #856404;
    text-align: center;
}

.modal-eliminacion-footer {
    background: #f8f9fa;
    padding: 20px;
    border-top: 1px solid #dee2e6;
    border-radius: 0 0 10px 10px;
    text-align: center;
}

.btn-cancelar {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    border: none;
    color: white;
    padding: 10px 25px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-cancelar:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .opciones-eliminacion {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .modal-eliminacion-content {
        width: 95%;
        margin: 10px;
    }
    
    .modal-eliminacion-body {
        padding: 20px;
    }
}

/* Animación de salida */
.modal-eliminacion.fade-out {
    animation: modalSlideOut 0.3s ease-in forwards;
}

@keyframes modalSlideOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
}
</style>

<!-- Inicio Horas por instructor -->
<?php if ($perfil_usuario['idper'] != 4): // Solo mostrar para perfiles que no sean restringidos ?>
<div class="container mt-4">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="4">Horas por instructor</th>
            </tr>
            <tr>
                <th>Instructor</th>
                <th>Horas Mensuales (<?= obtenerNombreMes(date('n')) ?>)</th>
                <th>Gráfica</th>
                <th>Ver detalle</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($instructores_tabla)): ?>
            <?php foreach ($instructores_tabla as $instructor): ?>
            <tr>
                <!-- Nombre del instructor y imprime el icono de imprimir -->
                <td>
                    <?= htmlspecialchars($instructor['nombre']) ?>
                    <?php if (strpos($instructor['id_instructor'], 'transversal_') !== 0 && $perfil_usuario['idper'] != 4): // Ocultar botón de imprimir por instructor para perfil 4 ?>
                    <a href="views/vrphor.php?idusu=<?= $instructor['id_instructor'] ?>" title="Imprimir horario del instructor" target="_blank" style="color: #00af00; margin-left: 10px;">
                        <i class="fa-solid fa-print fa-lg"></i>
                    </a>
                    <?php endif; ?>
                </td>
                <!-- Fin Nombre del instructor y imprime el icono de imprimir -->

                <!-- Horas mensuales -->
                <td>
                    <?php if (strpos($instructor['id_instructor'], 'transversal_') === 0): ?>
                        <span class="text-muted">N/A (Transversal)</span>
                    <?php else: ?>
                        <div>
                            <div class="fw-bold">
                                <span id="horas_mes_<?= htmlspecialchars($instructor['id_instructor']) ?>">
                                    <?= (int)$instructor['horas'] ?>/<?= (int)$instructor['max_horas'] ?>
                                </span>
                            </div>
                            <?php if (isset($instructor['horas_esperadas']) && $instructor['horas_esperadas'] && $instructor['tipo_contrato'] === 'planta'): ?>
                            <div class="small text-muted mt-1">
                                <div>Formación: <?= $instructor['horas_esperadas']['horas_formacion_directa'] ?>h</div>
                                <div>Otros: <?= $instructor['horas_esperadas']['horas_otros'] ?>h</div>
                                <div class="fw-bold text-dark">Total: <?= $instructor['horas_esperadas']['horas_totales'] ?>h</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </td>
                <!-- Fin Horas mensuales -->

                <!-- Gráfica -->
                <td>
                    <?php if (strpos($instructor['id_instructor'], 'transversal_') === 0): ?>
                        <span class="text-muted">N/A (Transversal)</span>
                    <?php else: ?>
                    <canvas id="grafico_<?= md5($instructor['nombre']) ?>" width="150" height="30" class="mx-auto d-block"></canvas>
                    <script>
                    // Crear gráfica inicial con datos disponibles y registrarla por instructor
                    window.graficosHoras = window.graficosHoras || {};
                    const ctx_<?= md5($instructor['nombre']) ?> = document.getElementById('grafico_<?= md5($instructor['nombre']) ?>').getContext('2d');
                    const chartInst_<?= md5($instructor['nombre']) ?> = new Chart(ctx_<?= md5($instructor['nombre']) ?>, {
                        type: 'bar',
                        data: {
                            labels: [''],
                            datasets: [{
                                    label: 'Horas asignadas',
                                    data: [<?= (int)$instructor['horas'] ?>],
                                    backgroundColor: '#00af00',
                                    borderWidth: 0,
                                    barThickness: 10
                                },
                                {
                                    label: 'Horas restantes',
                                    data: [<?= max(0, (int)$instructor['max_horas'] - (int)$instructor['horas']) ?>],
                                    backgroundColor: '#adb5bd',
                                    borderWidth: 0,
                                    barThickness: 10
                                }
                            ]
                        },
                        options: {
                            responsive: false,
                            indexAxis: 'y',
                            scales: {
                                x: {
                                    stacked: true,
                                    min: 0,
                                    max: <?= (int)$instructor['max_horas'] ?>,
                                    display: false
                                },
                                y: {
                                    stacked: true,
                                    display: false
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.raw + ' horas';
                                        }
                                    }
                                }
                            }
                        }
                    });
                    window.graficosHoras['<?= htmlspecialchars($instructor['id_instructor']) ?>'] = {
                        chart: chartInst_<?= md5($instructor['nombre']) ?>,
                        max: <?= (int)$instructor['max_horas'] ?>
                    };
                    </script>
                    <?php endif; ?>
                </td>
                <!-- Fin Gráfica -->

                <!-- Ver detalle -->
                <td>
                    <?php if (strpos($instructor['id_instructor'], 'transversal_') === 0): ?>
                        <span class="text-muted">N/A (Transversal)</span>
                    <?php else: ?>
                    <button class="btn btn-primary" style="font-size: 10px; padding: 3px 8px; background-color: #00af00; border-color: #00af00;" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalDetalle"
                            data-instructor='<?= json_encode($instructor, JSON_HEX_APOS | JSON_HEX_QUOT) ?>' 
                            onclick="abrirDetalle(this)">
                        Ver detalle
                    </button>
                    <?php endif; ?>
                </td>
                <!-- Fin Ver detalle -->
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4">No se encontraron datos de instructores para mostrar.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; // Fin de la condición para perfiles restringidos ?>
<!-- Fin Horas por instructor -->

<!-- Botón de Producción - Eliminar Todos los Horarios -->
<?php if ($_SESSION['idper'] == 21): // Solo para administradores ?>
<div class="container mt-4 mb-5 text-center">
    <!--
     * ====================================================================================
     * (Esto es para eliminar TODOS los registros de horarios, usar solo en PRODUCCIÓN)
     * ====================================================================================
     -->
    <!--
    <button type="button" 
            class="btn btn-danger btn-lg" 
            onclick="confirmarEliminacionTotal()"
            style="min-width: 250px;">
        <i class="fa-solid fa-trash-can me-2"></i>
        ELIMINAR TODOS LOS HORARIOS
    </button>
    <br>
    <small class="text-muted">Solo para producción</small>
    -->
</div>
<?php endif; ?>

<!-- Modal de detalle de horarios -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Horas - <span id="nombreInstructor"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Pestañas de navegación -->
                <ul class="nav nav-tabs" id="detalleTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="detalle-tab" data-bs-toggle="tab" data-bs-target="#detalle" type="button" role="tab" aria-controls="detalle" aria-selected="true">
                            <i class="fa-solid fa-calendar-day me-2"></i>Detalle Mensual
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="trimestre-tab" data-bs-toggle="tab" data-bs-target="#trimestre" type="button" role="tab" aria-controls="trimestre" aria-selected="false">
                            <i class="fa-solid fa-calendar-range me-2"></i>Por Rango
                        </button>
                    </li>
                    <!-- Pestaña: Contador de Horarios -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contador-tab" data-bs-toggle="tab" data-bs-target="#contenidoContador" type="button" role="tab" aria-controls="contenidoContador" aria-selected="false">
                            <i class="fa-solid fa-calculator me-2"></i>Horas Anuales
                        </button>
                    </li>
                </ul>
                
                <!-- Contenido de las pestañas -->
                <div class="tab-content" id="detalleTabsContent">
                    <!-- Pestaña Detalle Mensual -->
                    <div class="tab-pane fade show active" id="detalle" role="tabpanel" aria-labelledby="detalle-tab">
                        <div id="contenidoModal">
                            <!-- Aquí se carga el contenido del detalle mensual -->
                        </div>
                    </div>
                    
                    <!-- Pestaña Contador de Horarios eliminada - duplicada -->
                    
                    <!-- Pestaña Por Rango -->
                    <div class="tab-pane fade" id="trimestre" role="tabpanel" aria-labelledby="trimestre-tab">
                        <div id="contenidoRango">
                            <!-- Aquí se cargará el contenido del detalle por rango -->
                        </div>
                    </div>
                    <!-- Pestaña: Contador de Horarios -->
                    <div class="tab-pane fade" id="contenidoContador" role="tabpanel" aria-labelledby="contador-tab">
                        <!-- Aquí se cargará el contenido del contador de horarios -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de edición de transversales -->
<div id="modalEditarTransversal" class="modal-eliminacion" style="display: none;">
    <div class="modal-eliminacion-content">
        <div class="modal-eliminacion-header">
            <h5 class="modal-eliminacion-title">
                <i class="fa-solid fa-edit me-2" style="color: #00af00;"></i>
                Editar Horario Transversal
            </h5>
            <button type="button" class="modal-eliminacion-close" onclick="cerrarModalEditarTransversal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="modal-eliminacion-body">
            <form id="frmEditarTransversal">
                <input type="hidden" name="idhor_edit" id="idhor_edit">
                <input type="hidden" name="ope" value="edit">
                
                <div class="row">
                    <!-- Nombre de la Transversal -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="nombre_transversal_edit">Nombre de la Transversal <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nombre_transversal" 
                               id="nombre_transversal_edit" 
                               class="form-control" 
                               placeholder="Ej: Inglés, Ética, Emprendimiento..."
                               maxlength="100" required>
                        <small class="form-text text-muted">Nombre de la materia transversal</small>
                    </div>

                    <!-- Ficha -->
                    <div class="form-group col-md-6 mb-3">
                        <label for="ficha_edit">Ficha</label>
                        <input type="text" 
                               id="ficha_edit" 
                               class="form-control" 
                               readonly>
                    </div>

                    <!-- Día -->
                    <div class="form-group col-md-6 mb-3">
                        <label for="dia_edit">Día</label>
                        <input type="text" 
                               id="dia_edit" 
                               class="form-control" 
                               readonly>
                    </div>

                    <!-- Instructor -->
                    <div class="form-group col-md-6 mb-3">
                        <label for="instructor_edit">Instructor</label>
                        <select name="idusu" id="instructor_edit" class="form-select">
                            <option value="">Sin instructor</option>
                            <?php if ($din): ?>
                                <?php foreach ($din as $de): ?>
                                    <option value="<?= $de['idusu']; ?>">
                                        <?= strtoupper($de['nomusu']) . ' - ' . $de['ndocusu']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Aula -->
                    <div class="form-group col-md-6 mb-3">
                        <label for="aula_edit">Aula <span class="text-danger">*</span></label>
                        <select name="idaul" id="aula_edit" class="form-select" required>
                            <option value="">Seleccione un aula...</option>
                            <?php if ($dau): ?>
                                <?php foreach ($dau as $de): ?>
                                    <option value="<?= $de['idaul']; ?>">
                                        <?= $de['idaul'] . '. ' . $de['nomaul']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal-eliminacion-footer">
            <button type="button" class="btn-eliminar-individual" onclick="guardarEdicionTransversal()">
                <i class="fa-solid fa-save me-1"></i>
                Guardar Cambios
            </button>
        </div>
    </div>
</div>

<script>
let festivosCache = null;

async function obtenerFestivosColombia(año) {
    // Si ya tenemos los festivos en cache para este año, los devolvemos
    if (festivosCache && festivosCache.año === año) {
        return festivosCache.festivos;
    }
    
    try {
        const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${año}/CO`);
        const festivos = await response.json();
        
        // Convertir a formato simple de fechas
        const fechasFestivos = festivos.map(festivo => festivo.date);
        
        // Guardar en cache
        festivosCache = {
            año: año,
            festivos: fechasFestivos
        };
        
        return fechasFestivos;
    } catch (error) {
        console.warn('No se pudieron obtener los festivos de la API, usando fallback:', error);
        
        // Fallback con festivos fijos de 2024
        const festivosFallback = [
            '2024-01-01', '2024-01-08', '2024-03-25', '2024-03-28', '2024-03-29',
            '2024-05-01', '2024-05-13', '2024-06-03', '2024-06-10', '2024-07-01',
            '2024-07-20', '2024-08-07', '2024-08-19', '2024-10-14', '2024-11-04',
            '2024-11-11', '2024-12-08', '2024-12-25'
        ];
        
        return festivosFallback;
    }
}

// Abre el modal de detalle de horarios
function abrirDetalle(button) {
    try {
        const instructor = JSON.parse(button.dataset.instructor);
        // Limpiar el contenido del modal antes de cargar nuevos datos
        document.getElementById('contenidoModal').innerHTML = '';
        document.getElementById('nombreInstructor').textContent = '';
        
        // Guardar el instructor actual en variable global para acceso desde otras funciones
        window.instructorActual = instructor;
        window.currentInstructorId = instructor.id_instructor;
        
        mostrarDetalle(instructor.nombre, instructor.documento, instructor.horas, instructor.max_horas, instructor.tipo_contrato, instructor.detalle, instructor.horas_senova || 0);
    } catch (error) {
        console.error('Error al abrir detalle:', error);
        document.getElementById('contenidoModal').innerHTML = `
            <div class="alert alert-danger">
                Error al cargar los datos del instructor. Intente nuevamente.
            </div>
        `;
    }
}
// Fin Abre el modal de detalle de horarios

// Calcula los días laborales
async function calcularDiasLaborales(mes, año) {
    const festivos = await obtenerFestivosColombia(año);
    let diasLaborales = 0;
    let diasEnMes = new Date(año, mes, 0).getDate();
    
    for (let dia = 1; dia <= diasEnMes; dia++) {
        let fecha = new Date(año, mes - 1, dia);
        let diaSemana = fecha.getDay();
        let fechaStr = año + '-' + String(mes).padStart(2, '0') + '-' + String(dia).padStart(2, '0');
        
        // Si es de lunes (1) a viernes (5) y no es festivo
        if (diaSemana >= 1 && diaSemana <= 5 && !festivos.includes(fechaStr)) {
            diasLaborales++;
        }
    }
    
    return diasLaborales;
}

/**
 * Calcula los días laborales de un mes específico (versión síncrona)
 */
function calcularDiasLaboralesMes(mes, año) {
    // Usar festivos fijos para evitar problemas de async
    const festivos = [
        '01-01', '01-08', '03-25', '03-28', '03-29',
        '05-01', '05-13', '06-03', '06-10', '07-01',
        '07-20', '08-07', '08-19', '10-14', '11-04',
        '11-11', '12-08', '12-25'
    ];
    
    let diasLaborales = 0;
    let diasEnMes = new Date(año, mes, 0).getDate();
    
    for (let dia = 1; dia <= diasEnMes; dia++) {
        let fecha = new Date(año, mes - 1, dia);
        let diaSemana = fecha.getDay();
        let fechaStr = String(mes).padStart(2, '0') + '-' + String(dia).padStart(2, '0');
        
        // Si es de lunes (1) a viernes (5) y no es festivo
        if (diaSemana >= 1 && diaSemana <= 5 && !festivos.includes(fechaStr)) {
            diasLaborales++;
        }
    }
    
    return diasLaborales;
}
// Fin Calcula los días laborales

/**
 * Actualiza los cálculos dinámicos cuando se cambia el mes para instructores de planta
 */
async function actualizarCalculosDinamicos(mes, año) {
    try {
        const diasLaborales = await calcularDiasLaborales(mes, año);
        const horasFormacionDirecta = diasLaborales * 6.4;
        const horasOtros = diasLaborales * 2.1;
        const horasEsperadasMensual = horasFormacionDirecta + horasOtros;
        
        // Actualizar la información en el alert
        const alertElement = document.querySelector('.alert-success');
        if (alertElement) {
            const diasElement = alertElement.querySelector('p:nth-child(2)');
            const horasElement = alertElement.querySelector('p:nth-child(3)');
            
            if (diasElement) {
                diasElement.innerHTML = `Días laborales en ${obtenerNombreMes(mes)} ${año}: <strong>${diasLaborales} días</strong>`;
            }
            if (horasElement) {
                horasElement.innerHTML = `Horas esperadas mensual: <strong>${horasEsperadasMensual.toFixed(1)} horas</strong> (Planta: cálculo dinámico)`;
            }
        }
        
        // La sección de horas esperadas ya no es necesaria, está integrada en el resumen
        
        // Actualizar el título de la tabla de detalle
        const tablaTitulo = document.querySelector('.card-header h6');
        if (tablaTitulo) {
            tablaTitulo.textContent = `Detalle de Horarios y Actividades - ${obtenerNombreMes(mes)} ${año}`;
        }
        
        // La función actualizarTablaDetalleMes ya maneja la actualización del resumen
        // Solo necesitamos actualizar la información del alert y la tabla de detalle
        
    } catch (error) {
        console.error('Error actualizando cálculos dinámicos:', error);
    }
}

// Llenar el body del modal con el detalle de horarios
async function mostrarDetalle(nombre, documento, horas, maxHoras, tipoContrato, detalle, horasSenova = 0) {
    // Mostrar nombre del instructor
    document.getElementById('nombreInstructor').textContent = nombre;
    
    // Mostrar loading mientras carga
    document.getElementById('contenidoModal').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Calculando días laborales...</p>
        </div>
    `;

    
    let fechaActual = new Date();
    let mesActual = fechaActual.getMonth() + 1;
    let añoActual = fechaActual.getFullYear();
    
    try {
        let diasLaborales = await calcularDiasLaborales(mesActual, añoActual);
        
        
        let contenido = '';
        
        // Definir horas máximas según tipo de contrato
        const horasMaximas = tipoContrato === 'planta' ? 160 : (window.CONTRATISTA_MAX_HORAS || 180);
        
        if (tipoContrato === 'contratista') {
            // Para contratistas, las horas esperadas son fijas (180)
            const horasEsperadasContratista = (window.CONTRATISTA_MAX_HORAS || 180);
            
            contenido = `
                <div class="alert alert-info">
                    <h6>Instructor Contratista</h6>
                    <p>Máximo permitido mensual: <strong>${horasMaximas} horas</strong></p>
                </div>
                
                <!-- Selector de mes/año para contratistas -->
                <div class="row mb-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Mes</label>
                        <select id="selectorMesDetalle" class="form-select">
                            ${[1,2,3,4,5,6,7,8,9,10,11,12].map(m=>`<option value="${m}" ${m===mesActual?'selected':''}>${obtenerNombreMes(m)}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">Año</label>
                        <input id="selectorAnioDetalle" type="number" class="form-control" value="${añoActual}" min="2000" max="2100" />
                    </div>
                    <div class="col-12 col-md-5 d-grid d-md-block">
                        <button id="btnAplicarMesDetalle" class="btn w-100" style="background-color: #00af00; border-color: #00af00; color: white;" type="button">
                            <i class="fa-solid fa-rotate me-2"></i>Aplicar mes seleccionado
                        </button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Información Personal</h6>
                                <p><strong>Nombre:</strong> ${nombre}</p>
                                <p><strong>Documento:</strong> ${documento}</p>
                                <p><strong>Tipo de contrato:</strong> ${tipoContrato === 'planta' ? 'Planta' : 'Contratista'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Resumen de Horas (${obtenerNombreMes(mesActual)})</h6>
                                <p><strong>Horas directas a formación:</strong> 0 hrs</p>
                                <p><strong>Horas otros:</strong> 0 hrs</p>
                                <p><strong>Total asignado:</strong> ${horas}/${(window.CONTRATISTA_MAX_HORAS||180).toFixed ? (window.CONTRATISTA_MAX_HORAS||180).toFixed(1) : (window.CONTRATISTA_MAX_HORAS||180)} hrs</p>
                                <p><strong>Horas disponibles:</strong> ${((window.CONTRATISTA_MAX_HORAS||180) - horas).toFixed(1)} hrs</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3 align-items-end">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Detalle de Horarios y Actividades - ${obtenerNombreMes(mesActual)} ${añoActual}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <!-- La tabla será renderizada con datos REALES desde backend por actualizarTablaDetalleMes() -->
                                </div>
                                <div class="progress mt-3"></div>
                                <small class="text-muted">
                                    <span class="badge" style="background-color: #00af00;">Formación</span>
                                    <span class="badge bg-success">Otros/Formación</span>
                                    <span class="badge bg-warning text-dark">Otros</span>
                                    <span class="badge text-white" style="background-color: #007bff;">SENOVA</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Instructor de planta - cálculo dinámico basado en días laborales
            let horasFormacionDirecta = diasLaborales * 6.4;
            let horasOtros = diasLaborales * 2.1;
            let horasEsperadasMensual = horasFormacionDirecta + horasOtros;
            
            // Obtener datos reales del instructor para el mes actual desde el backend
            let totalHorasFormacion = 0;
            let totalHorasOtros = 0;
            let totalGeneral = 0;
            
            // Obtener datos reales del instructor para el mes actual
            try {
                const formData = new FormData();
                formData.append('ope', 'get_horarios_por_mes');
                formData.append('id_instructor', window.instructorActual?.id_instructor);
                formData.append('mes', mesActual);
                formData.append('año', añoActual);
                
                const response = await fetch('controllers/chor.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.resumen) {
                        // Usar los datos del resumen que ya están calculados
                        totalHorasFormacion = data.resumen.horas_formacion || 0;
                        totalHorasOtros = data.resumen.horas_otros || 0;
                        totalGeneral = data.resumen.total_horas || 0;
                    }
                }
            } catch (error) {
                console.error('Error obteniendo datos del instructor:', error);
            }
            
            // Para instructores de planta, usar el cálculo dinámico como máximo
            let horasComplementarias = Math.max(0, horasEsperadasMensual - totalGeneral);
            
            // Los valores están correctos: 18, 0, 18, 169
            
            contenido = `
                <div class="alert alert-success">
                    <h6>Instructor de Planta</h6>
                    <p>Días laborales en ${obtenerNombreMes(mesActual)} ${añoActual}: <strong>${diasLaborales} días</strong></p>
                    <p>Horas esperadas mensual: <strong>${horasEsperadasMensual.toFixed(1)} horas</strong> (Planta: cálculo dinámico)</p>
                    <small class="text-muted">*Cálculo basado en festivos oficiales de Colombia</small>
                </div>

                <div class="row g-2 align-items-end mb-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Seleccionar mes</label>
                        <select id="selectorMesDetalle" class="form-select">
                            ${[1,2,3,4,5,6,7,8,9,10,11,12].map(m=>`<option value="${m}" ${m===mesActual?'selected':''}>${obtenerNombreMes(m)}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">Año</label>
                        <input id="selectorAnioDetalle" type="number" class="form-control" value="${añoActual}" min="2000" max="2100" />
                    </div>
                    <div class="col-12 col-md-5 d-grid d-md-block">
                        <button id="btnAplicarMesDetalle" class="btn w-100" style="background-color: #00af00; border-color: #00af00; color: white;" type="button">
                            <i class="fa-solid fa-rotate me-2"></i>Aplicar mes seleccionado
                        </button>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Información Personal</h6>
                                <p><strong>Nombre:</strong> ${nombre}</p>
                                <p><strong>Documento:</strong> ${documento}</p>
                                <p><strong>Tipo de contrato:</strong> ${tipoContrato === 'planta' ? 'Planta' : 'Contratista'}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Resumen de Horas (${obtenerNombreMes(mesActual)})</h6>
                                <p><strong>Horas directas a formación:</strong> ${totalHorasFormacion}/${horasFormacionDirecta.toFixed(1)} hrs</p>
                                <p><strong>Horas otros:</strong> ${totalHorasOtros}/${horasOtros.toFixed(1)} hrs</p>
                                <p><strong>Total asignado:</strong> ${totalGeneral}/${horasEsperadasMensual.toFixed(1)} hrs</p>
                                <p><strong>Horas disponibles:</strong> ${horasComplementarias.toFixed(1)} hrs</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Detalle de Horarios y Actividades - ${obtenerNombreMes(mesActual)} ${añoActual}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <!-- La tabla será renderizada con datos REALES desde backend por actualizarTablaDetalleMes() -->
                                </div>
                                <div class="progress mt-3"></div>
                                <small class="text-muted">
                                    <span class="badge" style="background-color: #00af00;">Formación</span>
                                    <span class="badge bg-success">Otros/Formación</span>
                                    <span class="badge bg-warning text-dark">Otros</span>
                                    <span class="badge text-white" style="background-color: #007bff;">SENOVA</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('contenidoModal').innerHTML = contenido;

        // Cargar y renderizar inmediatamente el detalle REAL del mes actual desde backend
        if (window.instructorActual && window.instructorActual.id_instructor) {
            await actualizarResumenMesSeleccionado(window.instructorActual.id_instructor, mesActual, añoActual);
        }

        // Enlazar selector de mes/año para actualizar resumen mensual con datos del backend
        const btnAplicar = document.getElementById('btnAplicarMesDetalle');
        if (btnAplicar) {
            btnAplicar.addEventListener('click', async () => {
                const mesSel = parseInt(document.getElementById('selectorMesDetalle').value, 10);
                let anioSel = parseInt(document.getElementById('selectorAnioDetalle').value, 10);
                
                // Validar que el año sea válido, usar año actual si no lo es
                if (isNaN(anioSel) || anioSel < 2000 || anioSel > 2100) {
                    anioSel = new Date().getFullYear();
                    document.getElementById('selectorAnioDetalle').value = anioSel;
                }
                
                // Recalcular días laborales y horas esperadas para el mes seleccionado
                if (window.instructorActual?.tipo_contrato === 'planta') {
                    await actualizarCalculosDinamicos(mesSel, anioSel);
                }
                
                await actualizarResumenMesSeleccionado(window.instructorActual?.id_instructor, mesSel, anioSel);
            });
        }
        
    } catch (error) {
        console.error('Error al calcular días laborales:', error);
        
        // Fallback simple sin API
        let diasLaborales = 22; // Días laborales promedio por mes
        
        let contenido = `
            <div class="alert alert-warning">
                <h6>Modo Offline</h6>
                <p>No se pudo conectar a la API de festivos. Usando cálculo aproximado.</p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>Información Personal</h6>
                            <p><strong>Nombre:</strong> ${nombre}</p>
                            <p><strong>Documento:</strong> ${documento}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>Horas</h6>
                            <p><strong>Horas asignadas:</strong> ${horas}</p>
                            <p><strong>Horas máximas:</strong> ${maxHoras}</p>
                            <p><strong>Tipo:</strong> ${tipoContrato}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        if (detalle && detalle.length > 0) {
            contenido += `
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Horarios Asignados</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Día</th>
                                            <th>Ficha</th>
                                            <th>Jornada</th>
                                            <th>Aula</th>
                                            <th>Horas</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
            `;
            
            detalle.forEach(horario => {
                let tipoClase, tipoTexto;
                
                // Usar la nueva lógica basada en tipo_horas del backend
                if (horario.tipo_horas === 'formacion_directa') {
                    tipoClase = 'bg-success';
                    tipoTexto = 'Otros/Formación';
                } else if (horario.tipo_horas === 'otros') {
                    tipoClase = 'bg-warning text-dark';
                    tipoTexto = 'Otros';
                } else if (horario.es_senova) {
                    tipoClase = 'text-white';
                    tipoTexto = 'SENOVA';
                } else {
                    tipoClase = 'style="background-color: #00af00;"';
                    tipoTexto = 'Formación';
                }
                contenido += `
                    <tr>
                        <td>${horario.dia}</td>
                        <td>${horario.ficha}</td>
                        <td>${horario.jornada}</td>
                        <td>${horario.aula}</td>
                        <td>${horario.horas} hrs</td>
                        <td><span class="badge ${tipoClase}" ${tipoClase === 'text-white' ? 'style="background-color: #00af00;"' : ''}>${tipoTexto}</span></td>
                    </tr>
                `;
            });
            
            contenido += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('contenidoModal').innerHTML = contenido;
    }
}

function obtenerNombreMes(mes) {
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    return meses[mes - 1];
}

// Función para calcular días laborales en un mes (excluyendo fines de semana y festivos)
async function calcularDiasLaborales(mes, año) {
    // Validar parámetros
    if (!mes || !año || isNaN(mes) || isNaN(año)) {
        return 0;
    }
    
    try {
        // Lista de festivos fijos de Colombia
        const festivosFijos = [
            { mes: 1, dia: 1, nombre: 'Año Nuevo' },
            { mes: 1, dia: 6, nombre: 'Día de los Reyes Magos' },
            { mes: 3, dia: 25, nombre: 'Día de San José' },
            { mes: 4, dia: 13, nombre: 'Domingo de Ramos' }, // Variable
            { mes: 4, dia: 14, nombre: 'Lunes Santo' }, // Variable
            { mes: 4, dia: 15, nombre: 'Martes Santo' }, // Variable
            { mes: 4, dia: 16, nombre: 'Miércoles Santo' }, // Variable
            { mes: 4, dia: 17, nombre: 'Jueves Santo' }, // Variable
            { mes: 4, dia: 18, nombre: 'Viernes Santo' }, // Variable
            { mes: 4, dia: 19, nombre: 'Sábado Santo' }, // Variable
            { mes: 5, dia: 1, nombre: 'Día del Trabajo' },
            { mes: 5, dia: 12, nombre: 'Día de la Madre' },
            { mes: 5, dia: 26, nombre: 'Ascensión del Señor' }, // Variable
            { mes: 6, dia: 9, nombre: 'Corpus Christi' }, // Variable
            { mes: 6, dia: 16, nombre: 'Sagrado Corazón' }, // Variable
            { mes: 6, dia: 30, nombre: 'San Pedro y San Pablo' },
            { mes: 7, dia: 20, nombre: 'Día de la Independencia' },
            { mes: 8, dia: 7, nombre: 'Batalla de Boyacá' },
            { mes: 8, dia: 18, nombre: 'Asunción de la Virgen' },
            { mes: 10, dia: 13, nombre: 'Día de la Raza' },
            { mes: 11, dia: 3, nombre: 'Todos los Santos' },
            { mes: 11, dia: 17, nombre: 'Independencia de Cartagena' },
            { mes: 12, dia: 8, nombre: 'Inmaculada Concepción' },
            { mes: 12, dia: 25, nombre: 'Navidad' }
        ];
        
        // Filtrar festivos fijos del mes específico
        const festivosDelMesFijos = festivosFijos.filter(festivo => festivo.mes === mes);
        
        // Intentar obtener festivos de la API como respaldo
        let festivosAPI = [];
        try {
            const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${año}/CO`);
            if (response.ok) {
                const festivos = await response.json();
                festivosAPI = festivos.filter(festivo => {
                    const fecha = new Date(festivo.date);
                    return fecha.getMonth() === (mes - 1);
                });
            }
        } catch (apiError) {
            console.warn('Error obteniendo festivos de API, usando lista fija:', apiError);
        }
        
        // Usar festivos fijos como base, API como respaldo
        const festivosDelMes = festivosDelMesFijos.length > 0 ? festivosDelMesFijos : festivosAPI;
        
        
        // Crear array de fechas festivas solo del mes
        const fechasFestivas = festivosDelMes.map(festivo => {
            // Si es de la lista fija, usar el campo 'dia'
            if (festivo.dia) {
                return festivo.dia;
            }
            // Si es de la API, extraer el día de la fecha
            const fecha = new Date(festivo.date);
            return fecha.getDate();
        });
        
        // Obtener el número de días en el mes
        const diasEnMes = new Date(año, mes, 0).getDate();
        let diasLaborales = 0;
        
        // Contar días laborales (lunes a viernes, excluyendo festivos)
        for (let dia = 1; dia <= diasEnMes; dia++) {
            const fecha = new Date(año, mes - 1, dia);
            const diaSemana = fecha.getDay(); // 0 = domingo, 1 = lunes, ..., 6 = sábado
            
            // Si es día laboral (lunes a viernes) y no es festivo
            if (diaSemana >= 1 && diaSemana <= 5 && !fechasFestivas.includes(dia)) {
                diasLaborales++;
            }
        }
        return diasLaborales;
    } catch (error) {
        console.error('Error calculando días laborales:', error);
        // Fallback: calcular días laborales sin festivos
        const diasEnMes = new Date(año, mes, 0).getDate();
        let diasLaborales = 0;
        
        for (let dia = 1; dia <= diasEnMes; dia++) {
            const fecha = new Date(año, mes - 1, dia);
            const diaSemana = fecha.getDay();
            if (diaSemana >= 1 && diaSemana <= 5) {
                diasLaborales++;
            }
        }
        
        return diasLaborales;
    }
}

/**
 * Calcula los días específicos del mes actual
 */
// Obsoleto: cálculo de días específicos en cliente (removido)

// Obsoleto: obtener días del mes por día de semana (removido)



// Ejecutar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Guardar datos de instructores en variable global para acceso desde funciones
    window.instructoresData = <?= json_encode($instructores_tabla) ?>;
    
    // Agregar evento para cambiar entre pestañas
    document.getElementById('reconteo-tab')?.addEventListener('click', function() {
        // Cuando se hace clic en la pestaña de contador de horarios, cargar los datos
        if (window.instructorActual) {
            cargarContadorHorarios(window.instructorActual);
        }
    });
    
    // Agregar evento para la pestaña de rango
    document.getElementById('trimestre-tab')?.addEventListener('click', function() {
        // Cuando se hace clic en la pestaña de rango, cargar los datos
        if (window.instructorActual) {
            cargarDetallePorRango(window.instructorActual);
        }
    });

    // Actualizar horas mensuales por instructor usando la lógica de Por Rango (get_horarios_por_mes)
    try {
        const hoy = new Date();
        const mesActual = hoy.getMonth() + 1;
        const añoActual = hoy.getFullYear();
        if (Array.isArray(window.instructoresData)) {
            window.instructoresData.forEach((inst) => {
                if (!inst || !inst.id_instructor || String(inst.id_instructor).startsWith('transversal_')) return;
                const spanId = 'horas_mes_' + inst.id_instructor;
                const el = document.getElementById(spanId);
                if (!el) return;
                const fd = new FormData();
                fd.append('ope', 'get_horarios_por_mes');
                fd.append('id_instructor', inst.id_instructor);
                fd.append('mes', mesActual);
                fd.append('año', añoActual);
                fetch('controllers/chor.php', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.success) {
                            // Sumar horas del detalle (mismo criterio que Por Rango)
                            let totalFormacion = 0;
                            let totalOtros = 0;
                            (data.detalle || []).forEach(item => {
                                const horas = Number(item.horas) || 0;
                                if (item.tipo_horas === 'formacion' || item.tipo_horas === 'formacion_directa') {
                                    totalFormacion += horas;
                                } else {
                                    totalOtros += horas;
                                }
                            });
                            const total = totalFormacion + totalOtros;
                            el.textContent = total + '/' + (parseInt(inst.max_horas, 10) || 160);

                            // Actualizar gráfica si existe
                            if (window.graficosHoras && window.graficosHoras[inst.id_instructor]) {
                                const g = window.graficosHoras[inst.id_instructor];
                                const max = parseInt(inst.max_horas, 10) || g.max || 160;
                                const restantes = Math.max(0, max - total);
                                try {
                                    g.chart.data.datasets[0].data = [total];
                                    g.chart.data.datasets[1].data = [restantes];
                                    if (g.chart.options && g.chart.options.scales && g.chart.options.scales.x) {
                                        g.chart.options.scales.x.max = max;
                                    }
                                    g.chart.update();
                                } catch (e) {}
                            }
                        }
                    })
                    .catch(() => {});
            });
        }
    } catch (e) {}
});

/**
 * Carga el detalle por rango para un instructor específico
 * 
 * Esta función es el punto de entrada principal para mostrar el detalle por rango.
 * Se ejecuta cuando el usuario hace clic en la pestaña "Por Rango".
 * 
 * @param {Object} instructor - Objeto con información del instructor
 * @param {string} instructor.id_instructor - ID único del instructor
 * @param {string} instructor.nombre - Nombre completo del instructor
 * @param {string} instructor.tipo_contrato - Tipo de contrato (planta/contratista)
 */
function cargarDetallePorRango(instructor) {
    const contenidoRango = document.getElementById('contenidoRango');
    
    // Mostrar indicador de carga
    contenidoRango.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Cargando detalle por rango...</span>
            </div>
            <p class="mt-2">Cargando opciones de rango...</p>
        </div>
    `;
    
    // Cargar la interfaz de selección de rango
    mostrarSelectorRango(instructor);
}

/**
 * Muestra el selector de rango con opciones automáticas y manuales
 */
function mostrarSelectorRango(instructor) {
    const contenidoRango = document.getElementById('contenidoRango');
    
    let html = `
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="fa-solid fa-calendar-range me-2"></i>Detalle por Rango</h6>
                    <p class="mb-1"><strong>Instructor:</strong> ${instructor.nombre}</p>
                    <p class="mb-0">Seleccione un rango de fechas para ver las horas asignadas en ese período específico.</p>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fa-solid fa-calendar-alt me-2"></i>Selección de Rango</h6>
                    </div>
                    <div class="card-body">
                        <!-- Checkbox para rango automático -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="rango_automatico" 
                                   id="rango_automatico" 
                                   value="1"
                                   onchange="toggleRangoAutomatico()">
                            <label class="form-check-label" for="rango_automatico">
                                <strong><i class="fa-solid fa-calendar-alt me-2" style="color: #00af00;"></i>Rango por evento del calendario académico</strong>
                            </label>
                            <small class="form-text text-muted d-block mt-1">
                                Marque esta opción para seleccionar automáticamente el rango de fechas desde el calendario académico
                            </small>
                        </div>
                        
                        <!-- Campos de rango automático (inicialmente ocultos) -->
                        <div id="campos_rango_automatico" style="display: none;">
                            <div class="form-group mb-3">
                                <label for="tipo_evento_rango">Tipo de Evento</label>
                                <select id="tipo_evento_rango" class="form-select" onchange="cargarEventosPorTipoRango()">
                                    <option value="">Seleccione el tipo de evento...</option>
                                </select>
                                <small class="form-text text-muted">Seleccione el tipo de evento del calendario</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="evento_rango">Evento del Calendario</label>
                                <select id="evento_rango" class="form-select" onchange="seleccionarEventoRango()">
                                    <option value="">Primero seleccione el tipo de evento...</option>
                                </select>
                                <small class="form-text text-muted">Seleccione el evento específico</small>
                            </div>
                            
                            <div id="info_evento_rango" class="alert alert-success" style="display: none;">
                                <small>
                                    <i class="fa-solid fa-info-circle me-1"></i>
                                    <strong>Evento seleccionado:</strong> 
                                    <span id="titulo_evento_rango">-</span>
                                    <br>
                                    <strong>Período:</strong> 
                                    <span id="fecha_inicio_evento_rango">-</span>
                                    <br>
                                    <strong>Trimestre:</strong> 
                                    <span id="trimestre_evento_rango">-</span>
                                </small>
                            </div>
                        </div>
                        
                        <!-- Campos de rango manual -->
                        <div id="campos_rango_manual">
                            <div class="form-group mb-3">
                                <label for="fecha_inicio_rango">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="date" 
                                       id="fecha_inicio_rango" 
                                       class="form-control" 
                                       value="${new Date().toISOString().split('T')[0]}"
                                       onchange="actualizarInfoRango()">
                                <small class="form-text text-muted">Fecha desde cuando inicia el rango</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="fecha_fin_rango">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" 
                                       id="fecha_fin_rango" 
                                       class="form-control" 
                                       value="${new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}"
                                       onchange="actualizarInfoRango()">
                                <small class="form-text text-muted">Fecha hasta cuando es válido el rango</small>
                            </div>
                            
                            <div id="info_rango_manual" class="alert alert-info" style="display: none;">
                                <small>
                                    <i class="fa-solid fa-info-circle me-1"></i>
                                    <strong>Rango seleccionado:</strong> 
                                    <span id="formatoInicioRango">-</span> al <span id="formatoFinRango">-</span>
                                    (<span id="duracion_rango">-</span> días)
                                </small>
                            </div>
                        </div>
                        
                        <!-- Botón para calcular -->
                        <div class="text-center mt-3">
                            <button type="button" 
                                    class="btn btn-primary" 
                                    onclick="calcularHorasRango()"
                                    style="background-color: #00af00; border-color: #00af00;">
                                <i class="fa-solid fa-calculator me-2"></i>
                                Calcular Horas del Rango
                            </button>
                            

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fa-solid fa-chart-bar me-2"></i>Resumen del Rango</h6>
                    </div>
                    <div class="card-body">
                        <div id="resumen_rango">
                            <p class="text-muted text-center">
                                <i class="fa-solid fa-arrow-left me-2"></i>
                                Seleccione un rango y calcule las horas
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div id="detalle_rango" style="display: none;">
                    <!-- Aquí se mostrará el detalle detallado del rango -->
                </div>
            </div>
        </div>
    `;
    
    contenidoRango.innerHTML = html;
    
    // Cargar tipos de eventos para el rango automático
    cargarTiposEventosParaRango();
    
    // Actualizar información del rango manual
    actualizarInfoRango();
}

/**
 * Carga los tipos de eventos disponibles para el rango automático
 */
function cargarTiposEventosParaRango() {
    // Crear FormData para obtener los tipos de eventos
    const formData = new FormData();
    formData.append('ope', 'get_tipos_eventos');
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.tipos) {
            const selectTipo = document.getElementById('tipo_evento_rango');
            selectTipo.innerHTML = '<option value="">Seleccione el tipo de evento...</option>';
            
            data.tipos.forEach(tipo => {
                const option = document.createElement('option');
                option.value = tipo;
                option.textContent = tipo.charAt(0).toUpperCase() + tipo.slice(1).replace('_', ' ');
                selectTipo.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error cargando tipos de eventos:', error);
    });
}

/**
 * Alterna entre rango automático y manual
 */
function toggleRangoAutomatico() {
    const checkbox = document.getElementById('rango_automatico');
    const camposAutomatico = document.getElementById('campos_rango_automatico');
    const camposManual = document.getElementById('campos_rango_manual');
    
    if (checkbox.checked) {
        // Mostrar campos automáticos, ocultar manuales
        camposAutomatico.style.display = 'block';
        camposManual.style.display = 'none';
        
        // Limpiar campos manuales
        document.getElementById('fecha_inicio_rango').value = '';
        document.getElementById('fecha_fin_rango').value = '';
        document.getElementById('info_rango_manual').style.display = 'none';
        
        // Limpiar resumen
        document.getElementById('resumen_rango').innerHTML = `
            <p class="text-muted text-center">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Seleccione un evento del calendario
            </p>
        `;
        
        // Ocultar detalle
        document.getElementById('detalle_rango').style.display = 'none';
        
    } else {
        // Mostrar campos manuales, ocultar automáticos
        camposAutomatico.style.display = 'none';
        camposManual.style.display = 'block';
        
        // Limpiar campos automáticos
        document.getElementById('tipo_evento_rango').value = '';
        document.getElementById('evento_rango').value = '';
        document.getElementById('info_evento_rango').style.display = 'none';
        
        // Restaurar fechas por defecto
        const hoy = new Date();
        const en30Dias = new Date(hoy.getTime() + 30 * 24 * 60 * 60 * 1000);
        
        document.getElementById('fecha_inicio_rango').value = hoy.toISOString().split('T')[0];
        document.getElementById('fecha_fin_rango').value = en30Dias.toISOString().split('T')[0];
        
        // Actualizar información del rango manual
        actualizarInfoRango();
    }
}

/**
 * Actualiza la información del rango manual
 */
function actualizarInfoRango() {
    const fechaInicio = document.getElementById('fecha_inicio_rango').value;
    const fechaFin = document.getElementById('fecha_fin_rango').value;
    const infoRango = document.getElementById('info_rango_manual');
    const formatoInicio = document.getElementById('formatoInicioRango');
    const formatoFin = document.getElementById('formatoFinRango');
    const duracionRango = document.getElementById('duracion_rango');
    
    if (fechaInicio && fechaFin) {
        // Crear fechas usando solo la fecha (sin tiempo) para evitar problemas de UTC
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);
        
        // Asegurar que las fechas se interpreten como locales
        inicio.setHours(0, 0, 0, 0);
        fin.setHours(0, 0, 0, 0);
        
        const diasDiferencia = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24)) + 1; // +1 para incluir ambos días
        
        // Formatear fechas para mostrar usando la fecha original del input
        const textoInicio = formatearFechaLocal(fechaInicio);
        const textoFin = formatearFechaLocal(fechaFin);
        
        // Actualizar el contenido de los elementos
        formatoInicio.textContent = textoInicio;
        formatoFin.textContent = textoFin;
        duracionRango.textContent = `${diasDiferencia} días`;
        
        infoRango.style.display = 'block';
        
        // Cambiar color según la duración
        if (diasDiferencia <= 30) {
            infoRango.className = 'alert alert-success';
        } else if (diasDiferencia <= 90) {
            infoRango.className = 'alert alert-info';
        } else if (diasDiferencia <= 180) {
            infoRango.className = 'alert alert-warning';
        } else {
            infoRango.className = 'alert alert-danger';
        }
        
    } else {
        infoRango.style.display = 'none';
    }
}

/**
 * Carga los eventos según el tipo seleccionado para rango
 */
function cargarEventosPorTipoRango() {
    const tipoEvento = document.getElementById('tipo_evento_rango').value;
    const selectEvento = document.getElementById('evento_rango');
    
    if (!tipoEvento) {
        selectEvento.innerHTML = '<option value="">Primero seleccione el tipo de evento...</option>';
        return;
    }
    
    // Limpiar eventos anteriores
    selectEvento.innerHTML = '<option value="">Cargando eventos...</option>';
    
    // Crear FormData para enviar la consulta
    const formData = new FormData();
    formData.append('ope', 'get_eventos_por_tipo');
    formData.append('tipo_evento', tipoEvento);
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.eventos) {
            selectEvento.innerHTML = '<option value="">Seleccione un evento...</option>';
            
            data.eventos.forEach(evento => {
                const option = document.createElement('option');
                option.value = evento.id;
                
                // Formatear fechas para mostrar mes y día (evitar problemas de UTC)
                const fechaInicio = new Date(evento.fecha_inicio + 'T00:00:00');
                const fechaFin = new Date(evento.fecha_fin + 'T00:00:00');
                
                const mesInicio = fechaInicio.toLocaleDateString('es-ES', { month: 'short' });
                const diaInicio = fechaInicio.getDate();
                const mesFin = fechaFin.toLocaleDateString('es-ES', { month: 'short' });
                const diaFin = fechaFin.getDate();
                
                // Crear texto del option con rango de fechas
                let textoOption = `${evento.titulo} (${evento.anio})`;
                
                // Si las fechas son del mismo mes, mostrar solo una vez
                if (fechaInicio.getMonth() === fechaFin.getMonth()) {
                    textoOption += ` - ${diaInicio} al ${diaFin} ${mesInicio}`;
                } else {
                    // Si son meses diferentes, mostrar ambos
                    textoOption += ` - ${diaInicio} ${mesInicio} al ${diaFin} ${mesFin}`;
                }
                
                textoOption += ` - ${evento.trimestre}`;
                
                option.textContent = textoOption;
                option.setAttribute('data-fecha-inicio', evento.fecha_inicio);
                option.setAttribute('data-fecha-fin', evento.fecha_fin);
                option.setAttribute('data-titulo', evento.titulo);
                option.setAttribute('data-trimestre', evento.trimestre);
                selectEvento.appendChild(option);
            });
        } else {
            selectEvento.innerHTML = '<option value="">No se encontraron eventos</option>';
        }
    })
    .catch(error => {
        console.error('Error cargando eventos:', error);
        selectEvento.innerHTML = '<option value="">Error al cargar eventos</option>';
    });
}

/**
 * Selecciona un evento y llena automáticamente las fechas
 */
function seleccionarEventoRango() {
    const selectEvento = document.getElementById('evento_rango');
    const eventoId = selectEvento.value;
    
    if (!eventoId) {
        document.getElementById('info_evento_rango').style.display = 'none';
        return;
    }
    
    const option = selectEvento.options[selectEvento.selectedIndex];
    const fechaInicio = option.getAttribute('data-fecha-inicio');
    const fechaFin = option.getAttribute('data-fecha-fin');
    const titulo = option.getAttribute('data-titulo');
    const trimestre = option.getAttribute('data-trimestre');
    
    // Mostrar información del evento
    document.getElementById('titulo_evento_rango').textContent = titulo;
    
    // Formatear fechas para mostrar de manera más clara (evitando problemas UTC)
    // Separar las fechas manualmente para evitar problemas de zona horaria
    const [añoInicio, mesInicioNum, diaInicioNum] = fechaInicio.split('-').map(Number);
    const [añoFin, mesFinNum, diaFinNum] = fechaFin.split('-').map(Number);
    
    const meses = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];
    
    const mesInicio = meses[mesInicioNum - 1];
    const mesFin = meses[mesFinNum - 1];
    const diaInicio = diaInicioNum;
    const diaFin = diaFinNum;
    
    // Crear texto del rango de fechas
    let textoRango;
    if (mesInicioNum === mesFinNum) {
        // Mismo mes
        textoRango = `${diaInicio} al ${diaFin} de ${mesInicio}`;
    } else {
        // Meses diferentes
        textoRango = `${diaInicio} de ${mesInicio} al ${diaFin} de ${mesFin}`;
    }
    
    document.getElementById('fecha_inicio_evento_rango').textContent = textoRango;
    document.getElementById('trimestre_evento_rango').textContent = trimestre;
    document.getElementById('info_evento_rango').style.display = 'block';
}

/**
 * Calcula las horas del instructor para el rango seleccionado
 */
function calcularHorasRango() {
    const rangoAutomatico = document.getElementById('rango_automatico').checked;
    let fechaInicio, fechaFin;
    
    if (rangoAutomatico) {
        // Obtener fechas del evento seleccionado
        const selectEvento = document.getElementById('evento_rango');
        if (!selectEvento.value) {
            alert('Debe seleccionar un evento del calendario académico.');
            return;
        }
        
        const option = selectEvento.options[selectEvento.selectedIndex];
        fechaInicio = option.getAttribute('data-fecha-inicio');
        fechaFin = option.getAttribute('data-fecha-fin');
        
    } else {
        // Obtener fechas del rango manual
        fechaInicio = document.getElementById('fecha_inicio_rango').value;
        fechaFin = document.getElementById('fecha_fin_rango').value;
        
        if (!fechaInicio || !fechaFin) {
            alert('Debe seleccionar tanto la fecha de inicio como la fecha de fin.');
            return;
        }
        
        if (fechaInicio >= fechaFin) {
            alert('La fecha de fin debe ser posterior a la fecha de inicio.');
            return;
        }
    }
    
    // Calcular y mostrar las horas del rango
    calcularHorasRangoInstructor(window.instructorActual, fechaInicio, fechaFin);
}

/**
 * Calcula las horas del instructor para el rango seleccionado
 */
function calcularHorasRangoInstructor(instructor, fechaInicio, fechaFin) {

    
    const resumenRango = document.getElementById('resumen_rango');
    const detalleRango = document.getElementById('detalle_rango');
    
    // Mostrar indicador de carga
    resumenRango.innerHTML = `
        <div class="text-center">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Calculando...</span>
            </div>
            <p class="mt-2">Calculando horas del rango...</p>
        </div>
    `;
    
    // Crear FormData para obtener las horas del rango
    const formData = new FormData();
    formData.append('ope', 'get_horas_rango');
    formData.append('id_instructor', instructor.id_instructor);
    formData.append('fecha_inicio', fechaInicio);
    formData.append('fecha_fin', fechaFin);
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarResumenRango(data.resumen, fechaInicio, fechaFin);
            mostrarDetalleRango(data.detalle, fechaInicio, fechaFin);
        } else {
            resumenRango.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    ${data.message || 'No se pudieron calcular las horas del rango'}
                </div>
            `;
        }
    })
    .catch(error => {
        resumenRango.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                Error al calcular las horas del rango. Intente nuevamente.
                <br><small>Error: ${error.message}</small>
            </div>
        `;
    });
}

/**
 * Muestra el resumen del rango
 */
function mostrarResumenRango(resumen, fechaInicio, fechaFin) {
    const resumenRango = document.getElementById('resumen_rango');
    
    // Calcular días del período
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);
    const diasPeriodo = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24)) + 1;
    
    let html = `
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #00af00;">
                    <div class="card-body">
                        <h5 class="card-title">${resumen.total_horas}</h5>
                        <p class="card-text">Total Horas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">${resumen.horas_formacion}</h5>
                        <p class="card-text">Formación</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">${resumen.horas_otros}</h5>
                        <p class="card-text">Otros</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <h6><i class="fa-solid fa-calendar-range me-2"></i>Rango Seleccionado</h6>
            <p class="mb-1"><strong>Período:</strong> ${diasPeriodo} días</p>
            <p class="mb-0"><strong>Fechas:</strong> ${formatearFechaLocal(fechaInicio)} al ${formatearFechaLocal(fechaFin)}</p>
        </div>
    `;
    
    resumenRango.innerHTML = html;
}





/**
 * Muestra el detalle detallado del rango
 */
function mostrarDetalleRango(detalle, fechaInicio, fechaFin) {
    const detalleRango = document.getElementById('detalle_rango');
    
    let html = `
        <div class="card">
            <div class="card-header">
                <h6><i class="fa-solid fa-list me-2"></i>Detalle de Horarios del Rango</h6>
                <small class="text-muted">Horarios agrupados por ficha y jornada</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ficha/Actividad</th>
                                <th>Días</th>
                                <th>Jornada</th>
                                <th>Horas por Día</th>
                                <th>Total Horas</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    if (detalle && detalle.length > 0) {
        detalle.forEach(horario => {
            let tipoClase, tipoTexto;
            
            // Usar la nueva lógica basada en tipo_horas del backend
            if (horario.tipo_horas === 'formacion_directa') {
                tipoClase = 'bg-success';
                tipoTexto = 'Otros/Formación';
            } else if (horario.tipo_horas === 'otros') {
                tipoClase = 'bg-warning text-dark';
                tipoTexto = 'Otros';
            } else if (horario.es_transversal) {
                tipoClase = 'bg-info';
                tipoTexto = 'Transversal';
            } else {
                tipoClase = 'text-white';
                tipoTexto = 'Formación';
            }
            
            // Calcular horas por día
            let horasPorDia = 0;
            if (horario.es_otros) {
                horasPorDia = horario.horas / horario.dias_count;
            } else {
                // Para horarios normales, usar las horas de jornada estándar
                switch(horario.jornada) {
                    case 'Mañana': horasPorDia = 6; break;
                    case 'Tarde': horasPorDia = 5; break;
                    case 'Noche': horasPorDia = 4; break;
                    default: horasPorDia = 6;
                }
            }
            
            html += `
                <tr>
                    <td><strong>${horario.ficha}</strong></td>
                    <td>
                        <span class="badge bg-secondary me-1">${horario.dias_count} días</span>
                        <small class="text-muted">${horario.dia}</small>
                    </td>
                    <td><span class="badge bg-warning text-dark">${horario.jornada}</span></td>
                    <td>${horasPorDia} hrs</td>
                    <td><strong class="text-primary">${horario.horas} hrs</strong></td>
                    <td><span class="badge ${tipoClase}" ${tipoClase === 'text-white' ? 'style="background-color: #00af00;"' : ''}>${tipoTexto}</span></td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    <i class="fa-solid fa-inbox me-2"></i>
                    No se encontraron horarios en el rango seleccionado
                </td>
            </tr>
        `;
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    `;
    
    detalleRango.innerHTML = html;
    detalleRango.style.display = 'block';
}

/**
 * Calcula las horas del instructor para el trimestre seleccionado
 */
function calcularHorasTrimestre(instructor, fechaInicio, fechaFin, tituloEvento, trimestre) {
    const resumenTrimestre = document.getElementById('resumen_trimestre');
    const detalleTrimestre = document.getElementById('detalle_trimestre');
    
    // Mostrar indicador de carga
    resumenTrimestre.innerHTML = `
        <div class="text-center">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Calculando...</span>
            </div>
            <p class="mt-2">Calculando horas del trimestre...</p>
        </div>
    `;
    
    // Crear FormData para obtener las horas del trimestre
    const formData = new FormData();
    formData.append('ope', 'get_horas_trimestre');
    formData.append('id_instructor', instructor.id_instructor);
    formData.append('fecha_inicio', fechaInicio);
    formData.append('fecha_fin', fechaFin);
    
    fetch('controllers/chor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarResumenTrimestre(data.resumen, tituloEvento, trimestre);
            mostrarDetalleTrimestre(data.detalle, fechaInicio, fechaFin);
        } else {
            resumenTrimestre.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    ${data.message || 'No se pudieron calcular las horas del trimestre'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error calculando horas del trimestre:', error);
        resumenTrimestre.innerHTML = `
            <div class="alert alert-danger">
                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                Error al calcular las horas del trimestre. Intente nuevamente.
            </div>
        `;
    });
}

/**
 * Muestra el resumen del trimestre
 */
function mostrarResumenTrimestre(resumen, tituloEvento, trimestre) {
    const resumenTrimestre = document.getElementById('resumen_trimestre');
    
    let html = `
        <div class="row text-center">
            <div class="col-md-4">
                <div class="card text-white" style="background-color: #00af00;">
                    <div class="card-body">
                        <h5 class="card-title">${resumen.total_horas}</h5>
                        <p class="card-text">Total Horas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">${resumen.horas_formacion}</h5>
                        <p class="card-text">Formación</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">${resumen.horas_otros}</h5>
                        <p class="card-text">Otros</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <h6><i class="fa-solid fa-calendar-week me-2"></i>${trimestre}</h6>
            <p class="mb-1"><strong>Evento:</strong> ${tituloEvento}</p>
            <p class="mb-0"><strong>Período:</strong> ${resumen.dias_periodo} días</p>
        </div>
    `;
    
    resumenTrimestre.innerHTML = html;
}

/**
 * Muestra el detalle detallado del trimestre
 */
function mostrarDetalleTrimestre(detalle, fechaInicio, fechaFin) {
    const detalleTrimestre = document.getElementById('detalle_trimestre');
    
    let html = `
        <div class="card">
            <div class="card-header">
                <h6><i class="fa-solid fa-list me-2"></i>Detalle de Horarios del Trimestre</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ficha/Actividad</th>
                                <th>Día</th>
                                <th>Aula</th>
                                <th>Horas</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    if (detalle && detalle.length > 0) {
        detalle.forEach(horario => {
            let tipoClase, tipoTexto;
            
            // Usar la nueva lógica basada en tipo_horas del backend
            if (horario.tipo_horas === 'formacion_directa') {
                tipoClase = 'bg-success';
                tipoTexto = 'Otros/Formación';
            } else if (horario.tipo_horas === 'otros') {
                tipoClase = 'bg-warning text-dark';
                tipoTexto = 'Otros';
            } else if (horario.es_transversal) {
                tipoClase = 'bg-info';
                tipoTexto = 'Transversal';
            } else {
                tipoClase = 'text-white';
                tipoTexto = 'Formación';
            }
            
            html += `
                <tr>
                    <td><strong>${horario.ficha}</strong></td>
                    <td>${horario.dia}</td>
                    <td>${horario.aula || 'Sin aula'}</td>
                    <td><strong>${horario.horas} hrs</strong></td>
                    <td><span class="badge ${tipoClase}" ${tipoClase === 'text-white' ? 'style="background-color: #00af00;"' : ''}>${tipoTexto}</span></td>
                </tr>
            `;
        });
    } else {
        html += `
            <tr>
                <td colspan="5" class="text-center text-muted">
                    No hay horarios asignados en este trimestre
                </td>
            </tr>
        `;
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    detalleTrimestre.innerHTML = html;
    detalleTrimestre.style.display = 'block';
}

/**
 * Carga el reconteo anual para un instructor específico
 * 
 * Esta función es el punto de entrada principal para mostrar el reconteo anual.
 * Se ejecuta cuando el usuario hace clic en la pestaña "Reconteo Anual".
 * 
 * @param {Object} instructor - Objeto con información del instructor
 * @param {string} instructor.id_instructor - ID único del instructor
 * @param {string} instructor.nombre - Nombre completo del instructor
 * @param {string} instructor.tipo_contrato - Tipo de contrato (planta/contratista)
 */
// Función cargarContadorHorarios eliminada - se creará desde cero

// Función calcularContadorHorariosLocal eliminada - se creará desde cero

// Función calcularHorasMesLocal eliminada - se creará desde cero



/**
 * Obtiene las horas de una jornada específica (misma lógica que el backend)
 */
// Función obtenerHorasJornada eliminada - se creará desde cero

// Función duplicada removida - ya existe más arriba

/**
 * Verifica si un horario está activo en un mes específico
 * Evita que las horas se dupliquen en meses incorrectos
 */
function esHorarioActivoEnMes(horario, mes, año) {
    // Si el horario tiene fecha específica, usar esa fecha (OBLIGATORIO)
    if (horario.fecha_especifica) {
        const fechaHorario = new Date(horario.fecha_especifica);
        const mesHorario = fechaHorario.getMonth() + 1;
        const añoHorario = fechaHorario.getFullYear();
        return mesHorario === mes && añoHorario === año;
    }
    
    // Si NO tiene fecha específica, NO contar en ningún mes
    // Esto evita que horarios sin fecha se sumen incorrectamente
    return false;
}

/**
 * Filtra horarios por mes específico usando fecha_especifica
 */
function filtrarHorariosPorMes(horarios, mes, año) {
    return horarios.filter(horario => {
        if (!horario.fecha_especifica) return false;
        
        const fecha = new Date(horario.fecha_especifica);
        const mesHorario = fecha.getMonth() + 1;
        const añoHorario = fecha.getFullYear();
        
        return mesHorario === mes && añoHorario === año;
    });
}

/**
 * Calcula las horas de un mes usando la lógica de "ver detalle"
 */
function calcularHorasMesHorarios(horarios, mes, año) {
    let totalHoras = 0;
    
    horarios.forEach(horario => {
        if (horario.es_transversal || horario.es_otros == 0) {
            // Horario normal o transversal: usar jornada de la ficha
            const horasJornada = obtenerHorasJornada(horario.jornada);
            totalHoras += horasJornada;
        } else {
            // Actividad "otros": usar horas_otros
            const horasOtros = horario.horas_otros || 0;
            totalHoras += horasOtros;
        }
    });
    
    return totalHoras;
}

/**
 * Verifica la sincronización de un mes específico entre reconteo anual y ver detalle
 */
async function verificarSincronizacionMes(idInstructor, mes, año) {
    try {
        // Crear FormData para enviar la consulta
        const formData = new FormData();
        formData.append('ope', 'verificar_sincronizacion');
        formData.append('id_instructor', idInstructor);
        formData.append('mes', mes);
        formData.append('año', año);
        
        const response = await fetch('controllers/chor.php', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        
        const data = await response.json();
        
        if (data.success && data.resultado) {
            return data.resultado;
        }
        
        // Fallback si no hay respuesta
        return {
            sincronizado: false,
            diferencia: 0,
            horas_ver_detalle: 0
        };
        
    } catch (error) {
        console.warn('Error verificando sincronización del mes:', error);
        return {
            sincronizado: false,
            diferencia: 0,
            horas_ver_detalle: 0
        };
    }
}

/**
 * Verifica la sincronización general del conteo
 */
async function verificarSincronizacionConteo(idInstructor, mes, año) {
    try {
        // Crear FormData para enviar la consulta
        const formData = new FormData();
        formData.append('ope', 'verificar_sincronizacion');
        formData.append('id_instructor', idInstructor);
        formData.append('mes', mes);
        formData.append('año', año);
        
        const response = await fetch('controllers/chor.php', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        
        const data = await response.json();
        
        if (data.success && data.resultado) {
            return data.resultado;
        }
        
        // Fallback si no hay respuesta
        return {
            sincronizado: false,
            diferencia: 0,
            horas_ver_detalle: 0
        };
        
    } catch (error) {
        console.warn('Error verificando sincronización general:', error);
        return {
            sincronizado: false,
            diferencia: 0,
            horas_ver_detalle: 0
        };
    }
}

/**
 * Función de fallback para calcular reconteo anual si falla la conexión a la BD
 * Usa el método anterior de cálculo aproximado
 */
async function calcularReconteoAnualFallback(instructor) {
    const añoActual = new Date().getFullYear();
    const mesActual = new Date().getMonth() + 1;
    
    // Horas esperadas por año según tipo de contrato
    const horasEsperadasAnual = instructor.tipo_contrato === 'contratista' ? 2160 : 1920;
    
    let horasAcumuladas = 0;
    let mesesCompletados = [];
    
    for (let mes = 1; mes <= 12; mes++) {
        try {
            const diasLaborales = await calcularDiasLaborales(mes, añoActual);
            const horasMes = calcularHorasMes(instructor, mes, añoActual, diasLaborales);
            
            mesesCompletados.push({
                mes: mes,
                nombreMes: obtenerNombreMes(mes),
                diasLaborales: diasLaborales,
                horas: horasMes,
                completado: mes < mesActual,
                futuro: mes > mesActual,
                tieneHoras: horasMes > 0
            });
            
            if (mes <= mesActual) {
                horasAcumuladas += horasMes;
            }
        } catch (error) {
            console.warn(`Error calculando mes ${mes} (fallback):`, error);
        }
    }
    
    const mesesConHoras = mesesCompletados.filter(m => m.tieneHoras && m.mes <= mesActual).length;
    const promedioMensual = mesesConHoras > 0 ? horasAcumuladas / mesesConHoras : 0;
    const proyeccionAnual = promedioMensual * 12;
    const horasRestantes = Math.max(0, horasEsperadasAnual - horasAcumuladas);
    
    return {
        instructor: instructor,
        año: añoActual,
        mesActual: mesActual,
        horasEsperadasAnual: horasEsperadasAnual,
        horasAcumuladas: horasAcumuladas,
        horasRestantes: horasRestantes,
        proyeccionAnual: proyeccionAnual,
        mesesCompletados: mesesCompletados,
        porcentajeCompletado: (horasAcumuladas / horasEsperadasAnual) * 100,
        promedioMensual: promedioMensual,
        mesesConHoras: mesesConHoras,
        modo: 'fallback'
    };
}

/**
 * Calcula las horas para un mes específico (método de fallback)
 * Esta función se usa solo cuando no se pueden obtener datos reales de la BD
 */
function calcularHorasMes(instructor, mes, año, diasLaborales) {
    // Si no hay horarios asignados al instructor, las horas son 0
    if (!instructor.horas || instructor.horas === 0) {
        return 0;
    }
    
    // Para meses completados, usar las horas reales asignadas
    // Para el mes actual, usar las horas hasta la fecha
    const mesActual = new Date().getMonth() + 1;
    
    if (mes < mesActual) {
        // Meses completados: usar horas reales
        return instructor.horas || 0;
    } else if (mes === mesActual) {
        // Mes actual: calcular proporción basada en días transcurridos
        const diaActual = new Date().getDate();
        const diasEnMes = new Date(año, mes, 0).getDate();
        const factorMes = diasLaborales / diasEnMes;
        
        return Math.round((instructor.horas || 0) * factorMes);
    } else {
        // Meses futuros: 0 horas (no asignadas aún)
        return 0;
    }
}


    /**
     * Cambia la semana seleccionada y recarga los horarios
     * @param {string} semana - Valor del selector de semana (formato: YYYY-WW)
     */
    function cambiarSemana(semana) {
        
        // Agregar el selector de semana al formulario
        const form = document.getElementById('formFiltros');
        const inputSemana = document.createElement('input');
        inputSemana.type = 'hidden';
        inputSemana.name = 'semana_selector';
        inputSemana.value = semana;
        form.appendChild(inputSemana);
        
        // Enviar el formulario para recargar con la nueva semana
        form.submit();
    }

    /**
     * Regresa a la semana actual
     */
    function semanaActual() {
        // Obtener fecha actual en zona horaria local (Colombia)
        const fechaActual = new Date();
        const semanaISO = getWeekNumber(fechaActual.toISOString().slice(0, 10));
        
        // Agregar parámetro para ver solo horarios de hoy
        const form = document.getElementById('formFiltros');
        
        // Limpiar parámetros anteriores
        const inputsExistentes = form.querySelectorAll('input[name="ver_hoy"], input[name="semana_selector"]');
        inputsExistentes.forEach(input => input.remove());
        
        // Agregar parámetro para ver hoy
        const inputVerHoy = document.createElement('input');
        inputVerHoy.type = 'hidden';
        inputVerHoy.name = 'ver_hoy';
        inputVerHoy.value = '1';
        form.appendChild(inputVerHoy);
        
        // Agregar selector de semana actual
        const inputSemana = document.createElement('input');
        inputSemana.type = 'hidden';
        inputSemana.name = 'semana_selector';
        inputSemana.value = semanaISO;
        form.appendChild(inputSemana);
        
        // Enviar el formulario
        form.submit();
    }

    /**
     * Obtiene el número de semana ISO para una fecha (sincronizado con PHP)
     * @param {string} fecha - Fecha en formato YYYY-MM-DD
     * @returns {string} Semana en formato YYYY-WW (sin la W, igual que PHP)
     */
    function getWeekNumber(fecha) {
        const date = new Date(fecha);
        date.setHours(0, 0, 0, 0);
        
        // Jueves en la semana actual
        date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
        
        // 4 de enero en la semana actual
        const week1 = new Date(date.getFullYear(), 0, 4);
        
        // Ajustar para que el 4 de enero esté en la semana 1
        if (date < week1) {
            return (date.getFullYear() - 1) + '-' + 
                   String(Math.ceil((((date - new Date(date.getFullYear() - 1, 0, 4)) / 86400000) + 1) / 7)).padStart(2, '0');
        } else {
            return date.getFullYear() + '-' + 
                   String(Math.ceil((((date - week1) / 86400000) + 1) / 7)).padStart(2, '0');
        }
    }

    /**
     * Formatea una semana para mostrar en formato legible
     * @param {string} semana - Semana en formato YYYY-WW
     * @returns {string} Semana formateada (ej: "Semana 34 (19-25 agosto)")
     */
    function formatearSemana(semana) {
        if (!semana) return 'Semana actual';
        
        try {
            const [año, semanaNum] = semana.split('-W');
            const fechaInicio = getDateOfISOWeek(parseInt(semanaNum), parseInt(año));
            const fechaFin = new Date(fechaInicio);
            fechaFin.setDate(fechaFin.getDate() + 6);
            
            const inicioFormateado = formatearFechaLocal(fechaInicio.toISOString().slice(0, 10));
            const finFormateado = formatearFechaLocal(fechaFin.toISOString().slice(0, 10));
            
            return `Semana ${semanaNum} (${inicioFormateado} - ${finFormateado})`;
        } catch (error) {
            return semana;
        }
    }

    /**
     * Obtiene la fecha del lunes de una semana ISO
     * @param {number} week - Número de semana
     * @param {number} year - Año
     * @returns {Date} Fecha del lunes
     */
    function getDateOfISOWeek(week, year) {
        const simple = new Date(year, 0, 1 + (week - 1) * 7);
        const dayOfWeek = simple.getDay();
        const ISOweekStart = simple;
        
        if (dayOfWeek <= 4) {
            ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);
        } else {
            ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());
        }
        
        return ISOweekStart;
    }

    /**
     * Función de prueba para verificar la conexión con el backend
     */
    async function probarConexionBackend() {
        try {
            const formData = new FormData();
            formData.append('ope', 'get_horas_mensuales');
            formData.append('id_instructor', '1'); // ID de prueba
            formData.append('año', new Date().getFullYear());
            
            const response = await fetch('controllers/chor.php', {
                method: 'POST',
                body: formData
            });
            
            
            
            if (response.ok) {
                const data = await response.json();
                
                return true;
            } else {
                
                return false;
            }
            
        } catch (error) {
            console.error('❌ Error en prueba de conexión:', error);
            return false;
        }
    }

// Función recalcularHorasInstructor eliminada - se creará desde cero

    // Ejecutar prueba de conexión al cargar la página (desactivado)
    // document.addEventListener('DOMContentLoaded', function() {
    //     setTimeout(() => {
    //         probarConexionBackend();
    //     }, 1000);
    //     
    //     // Agregar listener para cerrar modal con Escape
    //     document.addEventListener('keydown', function(e) {
    //         if (e.key === 'Escape') {
    //             cerrarModalEliminacion();
    //         }
    //     });
    // });

    // Solo mantener el listener para Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalEliminacion();
        }
    });

    /**
     * Obtiene el nombre del día de la semana en español
     */
    function obtenerDiaSemana(fecha) {
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return dias[fecha.getDay()];
    }

    // Endpoint AJAX del controlador (ruta absoluta para evitar envoltura del layout)
    const CHOR_ENDPOINT = '/controllers/chor.php';

    // Variables globales para la eliminación
    let horarioSeleccionado = null;

    /**
     * Muestra el modal personalizado con opciones de eliminación
     */
    function mostrarOpcionesEliminacion(idhor, iddia, idfic, idusu, idaul, nomfic, nomusu, nomaul, fecha_inicio, fecha_fin, jornada) {
        // Guardar información del horario seleccionado
        horarioSeleccionado = {
            idhor: idhor,
            iddia: iddia,
            idfic: idfic,
            idusu: idusu,
            idaul: idaul,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin
        };
        
        // Mostrar información del horario
        const infoHorario = document.getElementById('infoHorarioEliminar');
        infoHorario.innerHTML = `
            <strong>Ficha:</strong> ${horarioSeleccionado.idfic} - ${nomfic}<br>
            <strong>Jornada:</strong> ${jornada}<br>
            <strong>Instructor:</strong> ${nomusu}<br>
            <strong>Aula:</strong> ${nomaul}
        `;

        // Mostrar información de fechas si están disponibles
        const infoFechas = document.getElementById('infoFechas');
        const detalleFechas = document.getElementById('detalleFechas');
        const btnEliminarGrupo = document.querySelector('.btn-eliminar-grupo');
        
        if (fecha_inicio && fecha_fin) {
            infoFechas.style.display = 'block';
            detalleFechas.innerHTML = `
                <strong>Fecha de inicio:</strong> ${fecha_inicio}<br>
                <strong>Fecha de fin:</strong> ${fecha_fin}
            `;
            btnEliminarGrupo.disabled = false;
            btnEliminarGrupo.style.opacity = '1';
            btnEliminarGrupo.style.cursor = 'pointer';
        } else {
            infoFechas.style.display = 'none';
            btnEliminarGrupo.disabled = true;
            btnEliminarGrupo.style.opacity = '0.5';
            btnEliminarGrupo.style.cursor = 'not-allowed';
            btnEliminarGrupo.title = 'No disponible: este horario no tiene fechas de inicio y fin definidas';
        }

        // Mostrar el modal y overlay
        const modal = document.getElementById('modalEliminacion');
        const overlay = document.getElementById('overlayEliminacion');
        
        modal.classList.add('visible');
        overlay.style.display = 'block';
        
        // Agregar clase para animación de entrada
        modal.classList.remove('fade-out');
    }

    /**
     * Cierra el modal de eliminación
     */
    function cerrarModalEliminacion() {
        const modal = document.getElementById('modalEliminacion');
        const overlay = document.getElementById('overlayEliminacion');
        
        // Agregar clase para animación de salida
        modal.classList.add('fade-out');
        
        // Ocultar después de la animación
        setTimeout(() => {
            modal.classList.remove('visible');
            overlay.style.display = 'none';
            modal.classList.remove('fade-out');
        }, 300);
    }

    /**
     * Elimina solo el horario individual
     */
    async function eliminarHorarioIndividual() {
        if (!horarioSeleccionado) return;

        if (confirm('¿Está seguro de que desea eliminar solo este horario específico?')) {
            try {
                // Construir parámetros para la eliminación
                const params = new URLSearchParams();
                params.set('ope', 'del');
                params.set('idhor', horarioSeleccionado.idhor);
                params.set('iddia', horarioSeleccionado.iddia);
                
                // Construir URL absoluta al controlador
                const controllerUrl = new URL('controllers/chor.php', window.location.href).toString();
                const response = await fetch(controllerUrl, {
                    method: 'GET',
                    headers: { 
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                // Construir URL con parámetros
                const url = `${controllerUrl}?${params.toString()}`;
                const responseWithParams = await fetch(url, {
                    method: 'GET',
                    headers: { 
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                const data = await responseWithParams.json();
                
                if (data.success) {
                    alert(`✅ ${data.message}\n\nSe eliminó ${data.cantidad} horario(s) exitosamente.`);
                    // Recargar la página para mostrar los cambios
                    window.location.reload();
                } else {
                    alert(`❌ Error: ${data.message || 'No se pudo eliminar el horario'}`);
                }
            } catch (error) {
                console.error('Error eliminando horario individual:', error);
                alert('❌ Error al eliminar el horario. Por favor, intente nuevamente.');
            }
        }
    }

    /**
     * Cuenta cuántos horarios coinciden con los criterios del grupo
     * @returns {Promise<number>} Cantidad de horarios que se eliminarán
     */
    async function contarHorariosGrupo() {
        if (!horarioSeleccionado) { return 0; }


        try {
            // Crear FormData para enviar la consulta
            const formData = new FormData();
            formData.append('ope', 'contar_horarios_grupo');
            formData.append('idfic', horarioSeleccionado.idfic);
            formData.append('iddia', horarioSeleccionado.iddia);
            formData.append('idusu', horarioSeleccionado.idusu);
            formData.append('idaul', horarioSeleccionado.idaul);
            formData.append('fecha_inicio', horarioSeleccionado.fecha_inicio);
            formData.append('fecha_fin', horarioSeleccionado.fecha_fin);

            // Construir URL absoluta al controlador y enviar cookies de sesión
            const controllerUrl = new URL('controllers/chor.php', window.location.href).toString();
            const response = await fetch(controllerUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store',
                body: formData
            });

            const textoPlano = await response.clone().text().catch(() => null);

            // Intentar parsear JSON; si falla, mostrar texto para diagnóstico
            let data;
            try {
                data = await response.json();
            } catch (e) {
                console.error('❌ No es JSON. Texto recibido:', (textoPlano || '').slice(0, 300));
                throw e;
            }
            
            
            if (data.success) {
                
                return data.cantidad || 0;
            } else {
                console.error('❌ Error del servidor:', data.message);
                return 0;
            }

        } catch (error) {
            console.error('❌ Error contando horarios del grupo:', error);
            return 0;
        }
    }

    /**
     * Elimina todo el grupo de horarios relacionados
     */
    async function eliminarGrupoHorarios() {
        if (!horarioSeleccionado) return;

        // Verificar que las fechas estén disponibles
        if (!horarioSeleccionado.fecha_inicio || !horarioSeleccionado.fecha_fin) {
            alert('No se puede eliminar en grupo: este horario no tiene fechas de inicio y fin definidas.');
            return;
        }

        // Contar cuántos horarios se van a eliminar
        const cantidadHorarios = await contarHorariosGrupo();
        
        if (confirm('¿Está seguro de que desea eliminar TODO el grupo de horarios relacionados?\n\nEste grupo eliminará ' + cantidadHorarios + ' horarios.')) {
            try {
                // Construir parámetros para la eliminación del grupo
                const params = new URLSearchParams();
                params.set('ope', 'del_grupo');
                params.set('idfic', horarioSeleccionado.idfic);
                params.set('iddia', horarioSeleccionado.iddia);
                if (horarioSeleccionado.idusu !== '') params.set('idusu', horarioSeleccionado.idusu);
                if (horarioSeleccionado.idaul !== '') params.set('idaul', horarioSeleccionado.idaul);
                params.set('fecha_inicio', horarioSeleccionado.fecha_inicio);
                params.set('fecha_fin', horarioSeleccionado.fecha_fin);
                
                // Construir URL absoluta al controlador
                const controllerUrl = new URL('controllers/chor.php', window.location.href).toString();
                const url = `${controllerUrl}?${params.toString()}`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    credentials: 'same-origin',
                    cache: 'no-store'
                });

                const data = await response.json();
                
                if (data.success) {
                    alert(`✅ ${data.message}\n\nSe eliminaron ${data.cantidad} horario(s) exitosamente.`);
                    // Recargar la página para mostrar los cambios
                    window.location.reload();
                } else {
                    alert(`❌ Error: ${data.message || 'No se pudo eliminar el grupo de horarios'}`);
                }
            } catch (error) {
                console.error('Error eliminando grupo de horarios:', error);
                alert('❌ Error al eliminar el grupo de horarios. Por favor, intente nuevamente.');
            }
        }
    }

    function onClickEliminar(btn) {
        try {
            const idhor = parseInt(btn.getAttribute('data-idhor'));
            const iddia = parseInt(btn.getAttribute('data-iddia'));
            const idfic = btn.getAttribute('data-idfic');
            
            // Manejar valores vacíos correctamente para idusu e idaul
            let idusu = btn.getAttribute('data-idusu');
            let idaul = btn.getAttribute('data-idaul');
            
            // Convertir a número solo si no está vacío
            if (idusu && idusu !== '') {
                idusu = parseInt(idusu);
            } else {
                idusu = ''; // Mantener como string vacío para el backend
            }
            
            if (idaul && idaul !== '') {
                idaul = parseInt(idaul);
            } else {
                idaul = ''; // Mantener como string vacío para el backend
            }
            
            const nomfic = JSON.parse(btn.getAttribute('data-nomfic') || '""');
            const nomusu = JSON.parse(btn.getAttribute('data-nomusu') || '"Sin instructor"');
            const nomaul = JSON.parse(btn.getAttribute('data-nomaul') || '"Sin aula"');
            const fecha_inicio = btn.getAttribute('data-fecha-inicio') || '';
            const fecha_fin = btn.getAttribute('data-fecha-fin') || '';
            const jornada = JSON.parse(btn.getAttribute('data-jornada') || '""');
            
            
            
            // Llamar a la función existente con parámetros seguros
            mostrarOpcionesEliminacion(idhor, iddia, idfic, idusu, idaul, nomfic, nomusu, nomaul, fecha_inicio, fecha_fin, jornada);
        } catch (e) {
            console.error('Error preparando datos para eliminar:', e);
        }
    }

    /**
     * Función para manejar el clic en editar transversal
     */
    function onClickEditarTransversal(btn) {
        try {
            const idhor = parseInt(btn.getAttribute('data-idhor'));
            const nombreTransversal = JSON.parse(btn.getAttribute('data-nombre-transversal') || '""');
            const idaul = btn.getAttribute('data-idaul') || '';
            const idusu = btn.getAttribute('data-idusu') || '';
            const dia = JSON.parse(btn.getAttribute('data-dia') || '""');
            const ficha = JSON.parse(btn.getAttribute('data-ficha') || '""');
            const fecha_inicio = btn.getAttribute('data-fecha-inicio') || '';
            const fecha_fin = btn.getAttribute('data-fecha-fin') || '';
            
            // Llamar a la función para mostrar opciones de edición
            mostrarOpcionesEdicionTransversal(idhor, nombreTransversal, idaul, idusu, dia, ficha, fecha_inicio, fecha_fin);
        } catch (e) {
            console.error('Error preparando datos para editar transversal:', e);
        }
    }

    /**
     * Muestra el modal de opciones de edición para transversales
     */
    function mostrarOpcionesEdicionTransversal(idhor, nombreTransversal, idaul, idusu, dia, ficha, fecha_inicio, fecha_fin) {

        
        // Guardar información del transversal seleccionado
        transversalSeleccionado = {
            idhor: idhor,
            nombreTransversal: nombreTransversal,
            idaul: idaul,
            idusu: idusu,
            dia: dia,
            ficha: ficha,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin
        };
        
    
        
        // Mostrar información del transversal
        const infoTransversal = document.getElementById('infoTransversalEditar');
        infoTransversal.innerHTML = `
            <strong>Transversal:</strong> ${nombreTransversal}<br>
            <strong>Ficha:</strong> ${ficha}<br>
            <strong>Día:</strong> ${dia}
        `;
        
        // Mostrar información de fechas si están disponibles
        const infoFechas = document.getElementById('infoFechasEdicion');
        const detalleFechas = document.getElementById('detalleFechasEdicion');
        const btnEditarGrupo = document.querySelector('#modalEdicionTransversal .btn-editar-grupo');
        
        if (fecha_inicio && fecha_fin) {
            infoFechas.style.display = 'block';
            detalleFechas.innerHTML = `
                <strong>Fecha de inicio:</strong> ${fecha_inicio}<br>
                <strong>Fecha de fin:</strong> ${fecha_fin}
            `;
            btnEditarGrupo.disabled = false;
            btnEditarGrupo.style.opacity = '1';
            btnEditarGrupo.style.cursor = 'pointer';

        } else {
            infoFechas.style.display = 'none';
            btnEditarGrupo.disabled = true;
            btnEditarGrupo.style.opacity = '0.5';
            btnEditarGrupo.style.cursor = 'not-allowed';
        }
        
        // Mostrar el modal y overlay
        const modal = document.getElementById('modalEdicionTransversal');
        const overlay = document.getElementById('overlayEliminacion');
        
        modal.classList.add('visible');
        overlay.style.display = 'block';
    }

    /**
     * Cierra el modal de edición de transversales
     */
    function cerrarModalEdicionTransversal() {
        const modalOpciones = document.getElementById('modalEdicionTransversal');
        const overlay = document.getElementById('overlayEliminacion');
        
        if (modalOpciones) {
            modalOpciones.classList.remove('visible');
        }
        
        if (overlay) {
            overlay.style.display = 'none';
        }
        
    }

    /**
     * Edita solo el horario transversal individual
     */
    function editarTransversalIndividual() {
        if (!transversalSeleccionado) return;
        
        // Cargar datos en el modal de edición existente
        document.getElementById('idhor_edit').value = transversalSeleccionado.idhor;
        document.getElementById('nombre_transversal_edit').value = transversalSeleccionado.nombreTransversal;
        document.getElementById('ficha_edit').value = transversalSeleccionado.ficha;
        document.getElementById('dia_edit').value = transversalSeleccionado.dia;
        
        // Seleccionar instructor si existe
        if (transversalSeleccionado.idusu && transversalSeleccionado.idusu !== '') {
            document.getElementById('instructor_edit').value = transversalSeleccionado.idusu;
        } else {
            document.getElementById('instructor_edit').value = '';
        }
        
        // Seleccionar aula si existe
        if (transversalSeleccionado.idaul && transversalSeleccionado.idaul !== '') {
            document.getElementById('aula_edit').value = transversalSeleccionado.idaul;
        } else {
            document.getElementById('aula_edit').value = '';
        }
        
        // Cerrar modal de opciones y abrir modal de edición
        cerrarModalEdicionTransversal();
        
        const modalEdicion = document.getElementById('modalEditarTransversal');
        const overlay = document.getElementById('overlayEliminacion');
        
        modalEdicion.classList.add('visible');
        overlay.style.display = 'block';
    }

    /**
     * Edita todo el grupo de horarios transversales relacionados
     */
    function editarTransversalGrupo() {
        if (!transversalSeleccionado) {
            return;
        }
        
        // Verificar que las fechas estén disponibles
        if (!transversalSeleccionado.fecha_inicio || !transversalSeleccionado.fecha_fin) {

            alert('No se puede editar en grupo: este transversal no tiene fechas de inicio y fin definidas.');
            return;
        }
        
        // Cargar datos en el modal de edición existente
        document.getElementById('idhor_edit').value = transversalSeleccionado.idhor;
        document.getElementById('nombre_transversal_edit').value = transversalSeleccionado.nombreTransversal;
        document.getElementById('ficha_edit').value = transversalSeleccionado.ficha;
        document.getElementById('dia_edit').value = transversalSeleccionado.dia;
        
        // Seleccionar instructor si existe
        if (transversalSeleccionado.idusu && transversalSeleccionado.idusu !== '') {
            document.getElementById('instructor_edit').value = transversalSeleccionado.idusu;
        } else {
            document.getElementById('instructor_edit').value = '';
        }
        
        // Seleccionar aula si existe
        if (transversalSeleccionado.idaul && transversalSeleccionado.idaul !== '') {
            document.getElementById('aula_edit').value = transversalSeleccionado.idaul;
        } else {
            document.getElementById('aula_edit').value = '';
        }
        
        // Marcar que es edición grupal
        const idhorElement = document.getElementById('idhor_edit');
        idhorElement.setAttribute('data-edicion-grupal', 'true');
        idhorElement.setAttribute('data-fecha-inicio', transversalSeleccionado.fecha_inicio);
        idhorElement.setAttribute('data-fecha-fin', transversalSeleccionado.fecha_fin);
        
        // Guardar valores originales del grupo para edición precisa
        idhorElement.setAttribute('data-idaul-original', transversalSeleccionado.idaul || '');
        idhorElement.setAttribute('data-idusu-original', transversalSeleccionado.idusu || '');
        
        // Cerrar modal de opciones y abrir modal de edición
        cerrarModalEdicionTransversal();
        
        const modalEdicion = document.getElementById('modalEditarTransversal');
        const overlay = document.getElementById('overlayEliminacion');
        
        modalEdicion.classList.add('visible');
        overlay.style.display = 'block';
        
    }

    // Variable global para almacenar el transversal seleccionado
    let transversalSeleccionado = null;

    // Agregar evento al overlay para cerrar modales al hacer clic fuera
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('overlayEliminacion');
        if (overlay) {
            overlay.addEventListener('click', cerrarTodoOverlay);
        }

        // Cerrar como la "X" si el usuario hace clic FUERA del cuadro (fallback adicional)
        document.addEventListener('mousedown', function(e) {
            const modalMenu = document.getElementById('modalEdicionTransversal');
            const modalEdicion = document.getElementById('modalEditarTransversal');
            const modalEliminar = document.getElementById('modalEliminacion');
            const contentSelector = '.modal-eliminacion-content';

            // Si el menú de opciones está visible y el clic fue fuera de su contenido
            if (modalMenu && modalMenu.classList.contains('visible')) {
                const content = modalMenu.querySelector(contentSelector);
                if (content && !content.contains(e.target)) {
                    cerrarModalEdicionTransversal();
                    return;
                }
            }

            // Si el modal de edición está visible y el clic fue fuera de su contenido
            if (modalEdicion && modalEdicion.classList.contains('visible')) {
                const content = modalEdicion.querySelector(contentSelector);
                if (content && !content.contains(e.target)) {
                    cerrarModalEditarTransversal();
                    return;
                }
            }

            // Si el modal de eliminación está visible y el clic fue fuera de su contenido
            if (modalEliminar && modalEliminar.classList.contains('visible')) {
                const content = modalEliminar.querySelector(contentSelector);
                if (content && !content.contains(e.target)) {
                    cerrarModalEliminacion();
                    return;
                }
            }
        });
    });

    function cerrarTodoOverlay() {
        const modalMenu = document.getElementById('modalEdicionTransversal');
        const modalEdicion = document.getElementById('modalEditarTransversal');
        const modalEliminar = document.getElementById('modalEliminacion');

        if (modalMenu && modalMenu.classList.contains('visible')) {
            cerrarModalEdicionTransversal(); // mismo comportamiento que la X
            return;
        }
        if (modalEdicion && modalEdicion.classList.contains('visible')) {
            cerrarModalEditarTransversal(); // mismo comportamiento que la X
            return;
        }
        if (modalEliminar && modalEliminar.classList.contains('visible')) {
            cerrarModalEliminacion(); // mismo comportamiento que la X
            return;
        }
    }

    /**
     * Limpia los atributos de edición grupal cuando se cierra el modal
     */
    function limpiarAtributosEdicionGrupal() {
        const idhorElement = document.getElementById('idhor_edit');
        if (idhorElement) {
            const hadGrupal = idhorElement.hasAttribute('data-edicion-grupal');
            const hadInicio = idhorElement.getAttribute('data-fecha-inicio');
            const hadFin = idhorElement.getAttribute('data-fecha-fin');
            const hadAulaOriginal = idhorElement.getAttribute('data-idaul-original');
            const hadInstructorOriginal = idhorElement.getAttribute('data-idusu-original');
            
            idhorElement.removeAttribute('data-edicion-grupal');
            idhorElement.removeAttribute('data-fecha-inicio');
            idhorElement.removeAttribute('data-fecha-fin');
            idhorElement.removeAttribute('data-idaul-original');
            idhorElement.removeAttribute('data-idusu-original');
            
        } else {
            console.error('❌ Elemento idhor_edit no encontrado en limpiarAtributosEdicionGrupal');
        }
    }

    /**
     * Cierra el modal de edición de transversales
     */
    function cerrarModalEditarTransversal() {
        const modal = document.getElementById('modalEditarTransversal');
        const overlay = document.getElementById('overlayEliminacion');
        
        if (modal) {
            modal.classList.remove('visible');
        }
        
        if (overlay) {
            overlay.style.display = 'none';
        }
        
        limpiarAtributosEdicionGrupal();
    }

    /**
     * Cancela la edición de transversales
     */
    function cancelarEdicionTransversal() {
        cerrarModalEdicionTransversal();
        limpiarAtributosEdicionGrupal();
    }

    /**
     * Función de prueba para verificar que el contador de horarios esté funcionando
     * Muestra información detallada de los cálculos para debugging
     */
    async function probarContadorHorarios() {
        if (!window.instructorActual) {
            alert('No hay instructor seleccionado');
            return;
        }
        
        const instructor = window.instructorActual;
        const añoActual = new Date().getFullYear();
        const mesActual = new Date().getMonth() + 1;
        
        
        // Crear ventana de prueba
        const ventanaPrueba = window.open('', '_blank', 'width=1000,height=700');
        ventanaPrueba.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Prueba Contador Horarios - ${instructor.nombre}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container mt-3">
                    <h4><i class="fa-solid fa-bug me-2"></i>Prueba de Horas Anuales</h4>
                    <div class="alert alert-info">
                        <strong>Instructor:</strong> ${instructor.nombre}<br>
                        <strong>ID:</strong> ${instructor.id_instructor}<br>
                        <strong>Año:</strong> ${añoActual}
                    </div>
                    <div id="resultados-prueba">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Calculando...</span>
                            </div>
                            <p class="mt-2">Calculando horas para cada mes...</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        `);
        
        try {
            let resultados = [];
            let totalAnual = 0;
            let debugInfo = [];
            
            // Probar cada mes del año
            for (let mes = 1; mes <= 12; mes++) {
                try {
                    // Primero hacer debugging para ver qué datos se obtienen
                    const debugFormData = new FormData();
                    debugFormData.append('ope', 'debug_horarios_instructor');
                    debugFormData.append('id_instructor', instructor.id_instructor);
                    debugFormData.append('mes', mes);
                    debugFormData.append('año', añoActual);
                    
                    const debugResponse = await fetch('controllers/chor.php', {
                        method: 'POST',
                        body: debugFormData
                    });
                    
                    let debugData = null;
                    if (debugResponse.ok) {
                        debugData = await debugResponse.json();
                        debugInfo.push({
                            mes: mes,
                            debug: debugData
                        });
                    }
                    
                    // Luego obtener las horas calculadas
                    const horasFormData = new FormData();
                    horasFormData.append('ope', 'get_horas_mes_instructor');
                    horasFormData.append('id_instructor', instructor.id_instructor);
                    horasFormData.append('mes', mes);
                    horasFormData.append('año', añoActual);
                    
                    const horasResponse = await fetch('controllers/chor.php', {
                        method: 'POST',
                        body: horasFormData
                    });
                    
                    if (!horasResponse.ok) {
                        throw new Error(`HTTP ${horasResponse.status}: ${horasResponse.statusText}`);
                    }
                    
                    const horasData = await horasResponse.json();
                    
                    if (horasData.success) {
                        const horas = horasData.horas || 0;
                        totalAnual += horas;
                        
                        resultados.push({
                            mes: mes,
                            nombreMes: obtenerNombreMes(mes),
                            horas: horas,
                            status: 'success',
                            message: `✅ ${horas} horas calculadas`,
                            debug: debugData
                        });
                        
                    } else {
                        resultados.push({
                            mes: mes,
                            nombreMes: obtenerNombreMes(mes),
                            horas: 0,
                            status: 'error',
                            message: `❌ ${horasData.message || 'Error desconocido'}`,
                            debug: debugData
                        });
                        
                    }
                    
                } catch (error) {
                    resultados.push({
                        mes: mes,
                        nombreMes: obtenerNombreMes(mes),
                        horas: 0,
                        status: 'error',
                        message: `❌ ${error.message}`,
                        debug: null
                    });
                }
            }
            
            // Mostrar resultados en la ventana de prueba
            const resultadosHTML = resultados.map(resultado => {
                let debugInfo = '';
                if (resultado.debug && resultado.debug.success) {
                    debugInfo = `
                        <small class="text-muted">
                            <br>📊 Horarios en BD: ${resultado.debug.total}
                            <br>🏷️ Jornadas: ${resultado.debug.jornadas ? resultado.debug.jornadas.join(', ') : 'N/A'}
                        </small>
                    `;
                }
                
                return `
                    <tr class="table-${resultado.status === 'success' ? 'success' : 'danger'}">
                        <td><strong>${resultado.nombreMes}</strong></td>
                        <td>${resultado.horas}</td>
                        <td>${resultado.message}${debugInfo}</td>
                    </tr>
                `;
            }).join('');
            
            ventanaPrueba.document.getElementById('resultados-prueba').innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">${totalAnual}</h5>
                                <p class="card-text">Total Horas Anuales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">${Math.round(totalAnual / 12)}</h5>
                                <p class="card-text">Promedio Mensual</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Horas</th>
                                <th>Estado + Debug</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${resultadosHTML}
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fa-solid fa-info-circle me-2"></i>Resumen de la Prueba</h6>
                    <p class="mb-1"><strong>Total de horas calculadas:</strong> ${totalAnual}</p>
                    <p class="mb-1"><strong>Meses con éxito:</strong> ${resultados.filter(r => r.status === 'success').length}/12</p>
                    <p class="mb-0"><strong>Meses con error:</strong> ${resultados.filter(r => r.status === 'error').length}/12</p>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fa-solid fa-exclamation-triangle me-2"></i>Información de Debugging</h6>
                    <p class="mb-1">Esta ventana muestra información detallada de la base de datos para cada mes.</p>
                    <p class="mb-0">Si algún mes muestra 0 horas, revisa la información de debugging para identificar el problema.</p>
                </div>
            `;
            
        } catch (error) {
            ventanaPrueba.document.getElementById('resultados-prueba').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Error ejecutando la prueba: ${error.message}
                </div>
            `;
        }
    }

    /**
     * Función de prueba para verificar la base de datos
     */
    async function probarBaseDatos() {
        if (!window.instructorActual) {
            alert('No hay instructor seleccionado');
            return;
        }
        
        const instructor = window.instructorActual;
       
        try {  
            // Mostrar información básica del instructor
            let mensaje = `Información del Instructor:\n\n`;
            mensaje += `ID: ${instructor.id_instructor}\n`;
            mensaje += `Nombre: ${instructor.nombre}\n`;
            mensaje += `Documento: ${instructor.documento}\n`;
            mensaje += `Horas: ${instructor.horas}\n`;
            mensaje += `Tipo Contrato: ${instructor.tipo_contrato}\n`;
            mensaje += `Total Horarios: ${instructor.detalle ? instructor.detalle.length : 0}\n\n`;
            
            if (instructor.detalle && instructor.detalle.length > 0) {
                mensaje += `Primeros 3 horarios del detalle:\n`;
                instructor.detalle.slice(0, 3).forEach((horario, index) => {
                    mensaje += `${index + 1}. Ficha: ${horario.ficha}, Jornada: ${horario.jornada}\n`;
                });
            } else {
                mensaje += `No hay horarios en el detalle del instructor.\n`;
            }
            
            alert(mensaje);
            
            // Ahora probar la base de datos
            const formData = new FormData();
            formData.append('ope', 'test_horarios_instructor');
            formData.append('id_instructor', instructor.id_instructor);
            
            const response = await fetch('controllers/chor.php', {
                method: 'POST',
                body: formData
            });
            
            
            if (response.ok) {
                const data = await response.json();
                
                // Mostrar información de la base de datos
                let mensajeBD = `Base de Datos:\n\n`;
                mensajeBD += `Total horarios: ${data.estadisticas.total}\n`;
                mensajeBD += `Con fecha específica: ${data.estadisticas.con_fecha}\n`;
                mensajeBD += `Sin fecha específica: ${data.estadisticas.sin_fecha}\n\n`;
                
                if (data.ejemplos.length > 0) {
                    mensajeBD += `Ejemplos de horarios:\n`;
                    data.ejemplos.forEach((ejemplo, index) => {
                        mensajeBD += `${index + 1}. ID: ${ejemplo.idhor}, Ficha: ${ejemplo.idfic}, Fecha: ${ejemplo.fecha_especifica}\n`;
                    });
                }
                
                alert(mensajeBD);
            } else {
                alert('Error al probar la base de datos. Intente nuevamente.');
            }
        } catch (error) {
            alert('Error al probar la base de datos: ' + error.message);
        }
    }

    /**
     * Carga el contador de horarios para un instructor específico
     * Usa la misma lógica que "Por Rango" pero muestra mes a mes
     */
    async function cargarContadorHorarios(instructor) {
        const contenidoContador = document.getElementById('contenidoContador');
        
        // Mostrar indicador de carga
        contenidoContador.innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando contador de horarios...</span>
                </div>
                <p class="mt-2">Calculando contador de horarios mes a mes...</p>
            </div>
        `;
        
        try {
            // Calcular el contador mes a mes usando la misma lógica que Por Rango
            const contadorHorarios = await calcularContadorHorariosMesAMes(instructor);
            
             // Generar y mostrar el HTML del contador
             contenidoContador.innerHTML = await generarHTMLContadorHorarios(contadorHorarios);
            
        } catch (error) {
            contenidoContador.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Error al cargar el contador de horarios. Intente nuevamente.
                    <br><small>Error: ${error.message}</small>
                </div>
            `;
        }
    }

    /**
     * Calcula el contador de horarios mes a mes usando la misma lógica que "Por Rango"
     * Agrupa por ficha y jornada, suma horas según la lógica establecida
     */
    async function calcularContadorHorariosMesAMes(instructor) {
        const año = new Date().getFullYear();
        const resumenMensual = [];
        let totalAnual = 0;
        
        // Calcular horas para cada mes usando la misma lógica que Por Rango
        for (let mes = 1; mes <= 12; mes++) {
            // Reutilizar la lógica de "Por rango" para cada mes
            const resultadoMes = await calcularHorasMesConLogicaRango(instructor, mes, año);
            const horasMes = resultadoMes.horas || 0;
            const detalleMes = Array.isArray(resultadoMes.detalle) ? resultadoMes.detalle : [];
            const desgloseMes = resultadoMes.desglose || {};
            
            // Obtener información del mes
            const fechaMes = new Date(año, mes - 1, 1);
            const nombreMes = fechaMes.toLocaleDateString('es-ES', { month: 'long' });
            const mesActual = new Date().getMonth() + 1;
            const añoActual = new Date().getFullYear();
            
            // Preparar resumen estilo: chip solo para horas + días como texto pequeño
            // Usar horas por día (no suma), igual a Por Rango
            const detalleResumenHtml = detalleMes.map(item => {
                const diasArr = (item.dia || '')
                    .split(',')
                    .map(d => parseInt(String(d).trim(), 10))
                    .filter(n => !isNaN(n));
                const diasUnicos = [...new Set(diasArr)].sort((a, b) => a - b);
                const diasTexto = diasUnicos.join(',');
                let horasPorDia = 0;
                if (item.es_otros) {
                    const total = Number(item.horas) || 0;
                    const count = Number(item.dias_count) || Math.max(1, diasUnicos.length);
                    horasPorDia = count > 0 ? total / count : 0;
                } else {
                    const mapeo = { 'Mañana': 6, 'Tarde': 5, 'Noche': 4 };
                    horasPorDia = mapeo[item.jornada] || 6;
                }
                const horasHtml = `<span class=\"chip-horas\">${horasPorDia}hrs</span>`;
                const diasHtml = diasTexto? `<span class=\"dias-implicados\">(${diasTexto})</span>` : '';
                return `${horasHtml}${diasHtml}`;
            }).join(' ');

            const resumenMes = {
                mes: mes,
                nombreMes: nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1),
                horas: horasMes,
                desglose: desgloseMes,
                detalleResumenHtml: detalleResumenHtml,
                completado: mes < mesActual || (mes === mesActual && año < añoActual),
                futuro: mes > mesActual || (mes === mesActual && año > añoActual)
            };
            
            resumenMensual.push(resumenMes);
            
            // Sumar TODOS los meses
            totalAnual += horasMes;
        }
        
        const contador = {
            instructor: instructor,
            año: año,
            totalAnual: totalAnual,
            resumenMensual: resumenMensual,
            totalHorarios: instructor.detalle ? instructor.detalle.length : 0
        };
        
        return contador;
    }

    /**
     * Calcula las horas de un mes reutilizando la lógica de "Por Rango"
     * Usa la misma función que se usa en "Ver detalle por rango"
     */
    async function calcularHorasMesConLogicaRango(instructor, mes, año) {
        try {
            // Calcular fechas de inicio y fin del mes
            const fechaInicio = `${año}-${mes.toString().padStart(2, '0')}-01`;
            const fechaFin = new Date(año, mes, 0).toISOString().split('T')[0]; // Último día del mes
            
            // Reutilizar la lógica de "Por rango" llamando al backend
            const formData = new FormData();
            formData.append('ope', 'get_horas_rango');
            formData.append('id_instructor', instructor.id_instructor);
            formData.append('fecha_inicio', fechaInicio);
            formData.append('fecha_fin', fechaFin);
            
            const response = await fetch('controllers/chor.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error obteniendo horas del rango');
            }
            
            // Extraer total y detalle (mismo formato ya usado en Por Rango)
            const totalHoras = data.resumen?.total_horas || 0;
            const detalle = Array.isArray(data.detalle) ? data.detalle : [];
            
            return {
                horas: totalHoras,
                detalle: detalle,
                desglose: {}
            };
            
        } catch (error) {
            return {
                horas: 0,
                desglose: {}
            };
        }
    }

    /**
     * Calcula las horas de un mes usando la misma lógica que "Por Rango"
     * Agrupa por ficha y jornada, suma horas según la lógica establecida
     */
    async function calcularHorasMesPorRango(instructor, mes, año) {
        try {
            // Obtener TODOS los horarios del instructor desde la base de datos
            
            const formData = new FormData();
            formData.append('ope', 'get_horarios_instructor_completo');
            formData.append('id_instructor', instructor.id_instructor);
            
            const response = await fetch('controllers/chor.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error obteniendo horarios');
            }
            
            const todosLosHorarios = data.horarios || [];
            
            if (todosLosHorarios.length === 0) {
                  return 0;
            }
            
            // Filtrar horarios por mes usando fecha_especifica
            const horariosDelMes = todosLosHorarios.filter(horario => {
                if (!horario.fecha_especifica) {
                    return false;
                }
                
                try {
                    const fechaHorario = new Date(horario.fecha_especifica);
                    const mesHorario = fechaHorario.getMonth() + 1;
                    const añoHorario = fechaHorario.getFullYear();
                    
                    const coincide = mesHorario === mes && añoHorario === año;
                    
                    return coincide;
                } catch (e) {
                    console.warn('❌ Error parseando fecha:', horario.fecha_especifica, e);
                    return false;
                }
            });
            
            if (horariosDelMes.length === 0) {
                return 0;
            }
            
            // Misma lógica que Por Rango: agrupar por ficha y jornada
            const horariosPorFichaJornada = {};
            const desgloseJornadas = {}; // Para el desglose detallado
            
            horariosDelMes.forEach(horario => {
                // Usar nombre_jornada en lugar de jornada (ID)
                const jornada = horario.nombre_jornada || 'Mañana';
                const idfic = horario.ficha || horario.idfic;
                const clave = `${idfic}_${jornada}`;
                
                if (!horariosPorFichaJornada[clave]) {
                    horariosPorFichaJornada[clave] = {
                        ficha: idfic,
                        jornada: jornada,
                        count: 0,
                        es_otros: horario.es_otros || false,
                        horas_otros: horario.horas_otros || 0,
                        es_transversal: horario.es_transversal || false,
                        es_formacion_directa: horario.es_formacion_directa || 1
                    };
                }
                
                horariosPorFichaJornada[clave].count++;
                
                // Acumular el desglose por jornada
                if (!desgloseJornadas[jornada]) {
                    desgloseJornadas[jornada] = 0;
                }
                desgloseJornadas[jornada]++;
            });

            // Calcular horas totales usando la misma lógica que "Ver detalle por rango"
            let totalHoras = 0;
            let totalHorasFormacion = 0;
            let totalHorasOtros = 0;
            
            Object.values(horariosPorFichaJornada).forEach(grupo => {
                if (grupo.es_otros) {
                    // Actividad "otros": verificar es_formacion_directa
                    const horasOtros = grupo.horas_otros || 0;
                    const horasTotal = horasOtros * grupo.count;
                    
                    if (grupo.es_formacion_directa == 2) {
                        // Actividad "otros" con formación directa: contar como formación
                        totalHorasFormacion += horasTotal;
                    } else {
                        // Actividad "otros" tradicional: contar como otros
                        totalHorasOtros += horasTotal;
                   }
                } else {
                    // Horario normal o transversal: SIEMPRE suma a formación
                    const horasJornada = obtenerHorasJornadaPorRango(grupo.jornada);
                    const horasTotal = horasJornada * grupo.count;
                    totalHorasFormacion += horasTotal;
                }
            });
            
            // Total general: formación + otros
            totalHoras = totalHorasFormacion + totalHorasOtros;
            
            // Retornar objeto con horas y desglose
            return {
                horas: totalHoras,
                desglose: desgloseJornadas
            };
            
        } catch (error) {            
            // Fallback: usar el detalle local si falla la BD
            const detalle = instructor.detalle || [];
            
            if (detalle.length === 0) {
                return 0;
            }
            
            // Filtrar horarios por mes usando fecha_especifica
            const horariosDelMes = detalle.filter(horario => {
                if (!horario.fecha_especifica) {
                    return false;
                }
                
                try {
                    const fechaHorario = new Date(horario.fecha_especifica);
                    const mesHorario = fechaHorario.getMonth() + 1;
                    const añoHorario = fechaHorario.getFullYear();
                    
                    const coincide = mesHorario === mes && añoHorario === año;
                    
                    return coincide;
                } catch (e) {
                    console.warn('❌ Error parseando fecha:', horario.fecha_especifica, e);
                    return false;
                }
            });
            
            
            if (horariosDelMes.length === 0) {
                return 0;
            }
            
            // Misma lógica que Por Rango: agrupar por ficha y jornada
            const horariosPorFichaJornada = {};
            
            horariosDelMes.forEach(horario => {
                const jornada = horario.jornada || 'Mañana';
                const idfic = horario.ficha || horario.idfic;
                const clave = `${idfic}_${jornada}`;
                
                if (!horariosPorFichaJornada[clave]) {
                    horariosPorFichaJornada[clave] = {
                        ficha: idfic,
                        jornada: jornada,
                        count: 0,
                        es_otros: horario.es_otros || false,
                        horas_otros: horario.horas_otros || 0,
                        es_transversal: horario.es_transversal || false
                    };
                }
                
                horariosPorFichaJornada[clave].count++;
            });
            
            
            // Calcular horas totales usando la misma lógica que Por Rango
            let totalHoras = 0;
            
            Object.values(horariosPorFichaJornada).forEach(grupo => {
                if (grupo.es_otros) {
                    // Actividad "otros": multiplicar horas_otros por el número de días
                    const horasOtros = grupo.horas_otros || 0;
                    const horasTotal = horasOtros * grupo.count;
                    totalHoras += horasTotal;
                } else {
                    // Horario normal o transversal: multiplicar horas de jornada por el número de días
                    const horasJornada = obtenerHorasJornadaPorRango(grupo.jornada);
                    const horasTotal = horasJornada * grupo.count;
                    totalHoras += horasTotal;
                }
            });
            // Retornar objeto con horas y desglose
            return {
                horas: totalHoras,
                desglose: desgloseJornadas
            };
        }
    }

    /**
     * Obtiene las horas de una jornada específica (misma lógica que el backend)
     * Mapeo de jornadas a horas según la lógica implementada
     */
    function obtenerHorasJornadaPorRango(jornada) {
        // Mapeo de jornadas a horas según la lógica implementada
        // Ahora funciona con nombres de jornada (como viene del backend)
        const jornadas = {
            'Mañana': 6,   // Mañana: 6 horas por día
            'Tarde': 5,    // Tarde: 5 horas por día
            'Noche': 4     // Noche: 4 horas por día
        };
        
        // También mantener compatibilidad con IDs numéricos por si acaso
        const jornadasId = {
            1042: 6,  // Mañana: 6 horas por día
            1043: 5,  // Tarde: 5 horas por día
            1044: 4   // Noche: 4 horas por día
        };
        
        // Primero intentar con nombre, luego con ID
        if (jornadas[jornada] !== undefined) {
            return jornadas[jornada];
        } else if (jornadasId[jornada] !== undefined) {
            return jornadasId[jornada];
        }
        
        // Default a mañana (6 horas) si no se reconoce
        console.warn(`⚠️ Jornada no reconocida: "${jornada}", usando default 6 horas`);
        return 6;
    }

    /**
     * Genera el HTML para mostrar el contador de horarios mes a mes
     */
     async function generarHTMLContadorHorarios(contador) {
         const { instructor, año, totalAnual, resumenMensual, totalHorarios } = contador;
        
        let html = `
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6><i class="fa-solid fa-calculator me-2"></i>Horas Anuales ${año}</h6>
                        <p class="mb-1"><strong>Instructor:</strong> ${instructor.nombre}</p>
                        <p class="mb-0"><strong>Total anual (todos los meses):</strong> ${totalAnual} hrs</p>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">${totalAnual}</h5>
                            <p class="card-text">Horas Anuales</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info">${Math.round(totalAnual / 12)}</h5>
                            <p class="card-text">Promedio Mensual</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Desglose Mensual de Horarios - Año ${año}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                <th>Mes</th>
                                <th>Desglose Mes (horas(días))</th>
                                <th>Total Horas</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
        `;
        
        for (const mes of resumenMensual) {
            let estadoClase, estadoTexto, estadoIcono, estadoColor;
            
            // Calcular horas esperadas para el mes
            let horasEsperadas = (window.CONTRATISTA_MAX_HORAS || 180); // Tope configurable para contratistas
            if (window.instructorActual && window.instructorActual.tipo_contrato === 'planta') {
                // Para instructores de planta, calcular dinámicamente
                // Obtener el número del mes desde el nombre del mes
                const nombreMes = mes.nombreMes;
                const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                              'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                const numeroMes = meses.indexOf(nombreMes) + 1;
                
                if (numeroMes > 0) {
                    const diasLaborales = await calcularDiasLaborales(numeroMes, año);
                    
                    if (diasLaborales > 0) {
                        const horasFormacionDirecta = diasLaborales * 6.4;
                        const horasOtros = diasLaborales * 2.1;
                        horasEsperadas = horasFormacionDirecta + horasOtros;
                    }
                }
            }
            
            if (mes.futuro) {
                // Meses futuros
                if (mes.horas === 0) {
                    // Mes futuro sin horas
                    estadoClase = 'table-light';
                    estadoTexto = 'Sin asignar';
                    estadoIcono = 'fa-calendar-plus';
                    estadoColor = 'bg-secondary';
                } else {
                    // Mes futuro con horas
                    estadoClase = 'table-success';
                    estadoTexto = 'Asignado';
                    estadoIcono = 'fa-check-circle';
                    estadoColor = 'bg-success';
                }
            } else if (mes.horas === 0) {
                // Meses sin horas asignadas
                estadoClase = 'table-warning';
                estadoTexto = 'Sin horas';
                estadoIcono = 'fa-exclamation-triangle';
                estadoColor = 'bg-warning';
            } else if (mes.completado) {
                // Meses completados con horas
                estadoClase = 'table-success';
                estadoTexto = 'Completado';
                estadoIcono = 'fa-check-circle';
                estadoColor = 'bg-success';
            } else {
                // Mes actual
                estadoClase = 'table-info';
                estadoTexto = 'En curso';
                estadoIcono = 'fa-play-circle';
                estadoColor = 'bg-info';
            }
            
            // Generar el desglose detallado de horarios
            const desgloseHorarios = (mes.detalleResumen && mes.detalleResumen.length > 0) ? mes.detalleResumen : (mes.horas > 0 ? 'Con horarios' : 'Sin horarios');
            
            html += `
                <tr class="${estadoClase}">
                    <td><strong>${mes.nombreMes}</strong></td>
                    <td><small class="chips-horas-dias">${mes.detalleResumenHtml || desgloseHorarios}</small></td>
                    <td><strong>${mes.horas}/${horasEsperadas.toFixed(1)} hrs</strong></td>
                    <td>
                        <span class="badge ${estadoColor}">
                            <i class="fa-solid ${estadoIcono} me-1"></i>${estadoTexto}
                        </span>
                    </td>
                </tr>
            `;
        }
        
        html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        return html;
    }

    // Event listener para la pestaña del contador de horarios
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar event listener para la pestaña del contador
        const contadorTab = document.getElementById('contador-tab');
        if (contadorTab) {
            contadorTab.addEventListener('click', function() {
                if (window.instructorActual) {
                    cargarContadorHorarios(window.instructorActual);
                }
            });
        }
    });
</script>

<style>
/* Chips de horas (gris más oscuro) y días como texto pequeño, estilo Por Rango */
.chips-horas-dias .chip-horas {
    display: inline-block;
    background-color: #4a4a4a; /* gris tirando a negro */
    border: 1px solid #2e2e2e; /* borde más oscuro */
    border-radius: 12px;
    padding: 1px 6px;
    margin: 1px 3px 1px 0;
    font-size: 10px; /* más pequeño */
    line-height: 1.1;
    color: #ffffff; /* contraste alto sobre gris oscuro */
}
.chips-horas-dias .dias-implicados {
    font-size: 9px; /* más pequeño */
    color: #666;
}
/* Estilos personalizados para mantener consistencia de colores verdes */

/* Badges de formación con color verde personalizado */
.badge.text-white[style*="background-color: #00af00"] {
    border: 1px solid #007a00;
    box-shadow: 0 2px 4px rgba(0, 175, 0, 0.2);
}

/* Tarjetas con fondo verde personalizado */
.card.text-white[style*="background-color: #00af00"] {
    border: 1px solid #007a00;
    box-shadow: 0 4px 8px rgba(0, 175, 0, 0.15);
}

/* Mejorar el contraste en elementos verdes */
.card.text-white[style*="background-color: #00af00"] .card-title,
.card.text-white[style*="background-color: #00af00"] .card-text {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

/* Header de sección de calendario con color verde */
.card-header[style*="background-color: #00af00"] {
    border-bottom: 1px solid #007a00;
    box-shadow: 0 2px 4px rgba(0, 175, 0, 0.1);
}

/* Asegurar que los badges verdes se vean bien */
.badge[style*="background-color: #00af00"] {
    color: white !important;
    font-weight: 600;
    border: 1px solid #007a00;
}

/* Efectos hover para elementos verdes */
.card.text-white[style*="background-color: #00af00"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 175, 0, 0.25);
    transition: all 0.3s ease;
}

.badge.text-white[style*="background-color: #00af00"]:hover {
    background-color: #007a00 !important;
    transition: all 0.2s ease;
}

/* ===== ESTILOS PARA CHECKBOXES VERDES ===== */

/* Checkboxes personalizados con color verde institucional */
.form-check-input:checked {
    background-color: #00af00 !important;
}

/* Checkboxes específicos del formulario de horarios */
#es_transversal:checked,
#es_otros:checked,
#es_formacion_directa:checked {
    background-color: #00af00 !important;
}

/* Checkboxes de días de la semana */
input[name="dia[]"]:checked {
    background-color: #00af00 !important;
}

/* Todos los checkboxes del formulario */
input[type="checkbox"]:checked {
    background-color: #00af00 !important;
}

/* Radio buttons también en verde */
input[type="radio"]:checked {
    background-color: #00af00 !important;
}
</style>