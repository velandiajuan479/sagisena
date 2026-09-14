<?php 
    require_once ('controllers/cage.php'); 
    $idper = $_SESSION['idper'] ?? null;
    require_once('models/mplaneacion.php');
    $mplane = new Mplaneacion();
    $datosPlaneacion = $mplane->getAll($idfic);
?>

<?php echo titulo2("<i class='" . $icono . "'></i> Agenda", 2); ?>

<div class="main-content">
<!-- Datos de Programa -->
<?php if($dtPrg){ foreach ($dtPrg as $dtP){ ?>
    <div>
        <table class="table table-striped" style="width:100%">
            <tr>
                <th colspan='8' style="font-size:30px;font-weight: bold; text-align: center;">FICHA <?=$dtAge[0]['idfic']?> - <?=$dtAge[0]['nomfic']?></th>
            </tr>
            <tr>
                <th>Programa</th>
                <td colspan='7'><?=$dtP['codpro'];?> - <?=$dtP['nompro'];?> Versión: <?=$dtP['verpro'];?></td>
            </tr>
            <tr>
                <th>Horas Lectiva</th>
                <td><?=$dtP['horlpro'];?></td>
                <th>Créditos Lectiva</th>
                <td><?=$dtP['crelpro'];?></td>
                <th>Horas Productiva</th>
                <td><?=$dtP['horppro'];?></td>
                <th>Créditos Productiva</th>
                <td><?=$dtP['creppro'];?></td>
            </tr>
            <tr>
                <th>Tipo Formación</th>
                <td><?=$dtP['nomval'];?></td>
                <th>Red de Conocimiento</th>
<td colspan="2" style="word-wrap: break-word; white-space: normal; line-height: 1.4; max-width: 400px;">
    <div style="display: inline-block; vertical-align: top; width: calc(100% - 30px);"><?=$dtP['redcon'];?></div>
    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#modalPlaneacionFull" style="color: green; margin-left: 8px; vertical-align: top; float: right;">
        <i class="fa fa-eye fa-lg"></i>
    </button>
</td>
                <th>Área</th>
                <td><?=$dtP['nomare'];?></td>
            </tr>
        </table>
    </div>
<?php }} ?>

<a href="home.php?pg=1548&idfic=<?= $idfic ?>"><i class="fa-solid fa-square-poll-vertical" style="color: green; font-size: 30px;"></i></a> 
<a href="home.php?pg=1513&idfic=<?= $idfic ?>"><i class="fa-solid fa-square-poll-horizontal" style="color: green; font-size: 30px;"></i></a> 

<!-- Filtros y búsqueda -->
<div class="row mb-3">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
            <input type="text" id="filtro_busqueda_agenda" class="form-control" placeholder="Buscar por actividad, instructor o competencia...">
        </div>
    </div>
    <div class="col-md-2">
        <select id="filtro_fase_agenda" class="form-select">
            <option value="">Todas las fases</option>
            <option value="ANALISIS">Análisis</option>
            <option value="DESARROLLO">Desarrollo</option>
            <option value="CIERRE">Cierre</option>
        </select>
    </div>
    <div class="col-md-2">
        <select id="filtro_instructor_agenda" class="form-select">
            <option value="">Todos los instructores</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary" onclick="limpiarFiltrosAgenda()">
            <i class="fa fa-times"></i> Limpiar Filtros
        </button>
    </div>
    <!-- <div class="col-md-2">
        <button class="btn btn-outline-primary" onclick="exportarAgenda()">
            <i class="fa fa-download"></i> Exportar
        </button>
    </div> -->
</div>

<!-- Tabla de Agenda con estilos mejorados -->
<div class="table-container">
<div class="table-responsive" style="overflow-x: visible;">
    <table id="tabla_agenda_importada" class="table table-striped table-hover" style="width:100%; background-color: white; border: 2px solid #00af00;">
        <thead style="background-color: #00af00; color: white;">
            <tr>
                <th style="width: 20%; border: 1px solid #00af00; padding: 12px; color: white;">Actividad</th>
                <th style="width: 12%; border: 1px solid #00af00; padding: 12px; color: white;">Instructor</th>
                <th style="width: 25%; border: 1px solid #00af00; padding: 12px; color: white;">Competencias</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">L</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">M</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">M</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">J</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">V</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">S</th>
                <th style="width: 3%; border: 1px solid #00af00; padding: 12px; color: white;">D</th>
                <th style="width: 8%; border: 1px solid #00af00; padding: 12px; color: white;">Fecha Inicio</th>
                <th style="width: 8%; border: 1px solid #00af00; padding: 12px; color: white;">Fecha Final</th>
                <th style="width: 5%; border: 1px solid #00af00; padding: 12px; color: white;">Horas</th>
                <th class="col-acciones" style="width: 5%; border: 1px solid #00af00; padding: 12px; color: white;">Acciones</th>
                <tr>
           <?php if (!empty($dtAge)): ?>
    <?php foreach ($dtAge as $dta): ?>
        <tr>
            <td><?= htmlspecialchars($dta['idusu'] ?? '') ?></td>
            <td><?= htmlspecialchars($dta['descom'] ?? '') ?></td>
            <td style="position: relative; padding-bottom: 30px;">
                <div><?= htmlspecialchars($dta['nomres'] ?? '') ?></div>

                <?php if (isset($idper) && in_array($idper, [21, 7])): ?>
                    <!-- Ícono de agregar instrumento -->
                    <a href="home.php?pg=1545&idres=<?= urlencode($dta['idres'] ?? '') ?>&idfic=<?= urlencode($idfic ?? '') ?>"
                       style="position: absolute; bottom: 0; left: 5px; 
                              width: 24px; height: 24px; display: flex; 
                              align-items: center; justify-content: center; 
                              text-decoration: none;">
                        <i class="fa fa-plus" style="color: green; font-size: 22px;"></i>
                    </a>
                <?php endif; ?>

                <?php if (!empty($dta['instrumentos'])): ?>
                    <div style="position: absolute; bottom: 0; left: 35px; display: flex; gap: 5px;">
                        <?php if (is_array($dta['instrumentos'])): ?>
                            <?php foreach ($dta['instrumentos'] as $instrumento): ?>
                                <?php
                                    $nomIns = htmlspecialchars($instrumento['nomins'] ?? '');
                                    $idIns  = urlencode($instrumento['idins'] ?? '');
                                    $link = (isset($idper) && in_array($idper, [21, 7]))
                                        ? "home.php?pg=1547&idins={$idIns}&idfic=" . urlencode($idfic ?? '')
                                        : "#";
                                ?>
                                <a href="<?= $link ?>" title="<?= $nomIns ?>" 
                                   style="width: 24px; height: 24px; display: flex; 
                                          align-items: center; justify-content: center; 
                                          text-decoration: none;">
                                    <i class="fa fa-book" style="color: green; font-size: 22px;"></i>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php for ($i = 0; $i < intval($dta['instrumentos']); $i++): ?>
                                <a href="#" title="Instrumento <?= $i + 1 ?>" 
                                   style="width: 24px; height: 24px; display: flex; 
                                          align-items: center; justify-content: center; 
                                          text-decoration: none;">
                                    <i class="fa fa-book" style="color: green; font-size: 22px;"></i>
                                </a>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>

            </tr>
        </thead>
        <tbody id="tbody_agenda_importada" style="background-color: white;">
            <!-- Los datos se cargarán dinámicamente -->
            <?php if(isset($dtAge) && !empty($dtAge)): ?>
                <!-- Fallback: mostrar datos originales si JavaScript falla -->
                <tr>
                    <td colspan="14" class="text-center text-muted">
                        <i class="fa fa-spinner fa-spin"></i> Cargando datos...
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="14" class="text-center text-muted">No hay datos de agenda disponibles</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="row mt-3">
    <div class="col-md-6">
        <div class="d-flex align-items-center">
            <label for="registros_por_pagina_agenda" class="form-label me-2 mb-0">Mostrar:</label>
            <select id="registros_por_pagina_agenda" class="form-select form-select-sm" style="width: auto;">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <!-- <option value="25">25</option> -->
                <!-- <option value="50">50</option> -->
            </select>
            <span class="ms-3 text-muted">
                Mostrando <span id="inicio_registros_agenda">1</span> a <span id="fin_registros_agenda">10</span> de <span id="total_registros_agenda">0</span> registros
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <nav aria-label="Navegación de páginas">
            <ul class="pagination justify-content-end mb-0" id="paginacion_agenda">
                <!-- La paginación se generará dinámicamente -->
            </ul>
        </nav>
    </div>
</div>
</div> <!-- Cerrar table-container -->

<!-- Estilos mejorados para la tabla de agenda -->
<style>
/* Estilos base para la tabla */
.table {
    table-layout: fixed;
    width: 100% !important;
}

.table th, .table td {
    word-wrap: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
}

/* Mejoras en la tabla principal */
#tabla_agenda_importada th {
    background-color: #00af00 !important;
    color: white !important;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: none;
}

#tabla_agenda_importada td {
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

/* Estilos para badges */
.badge {
    font-size: 0.9rem;
    padding: 0.7rem 1.2rem;
    font-weight: 600;
    text-align: center;
}

/* Estilos para botones de acción */
.btn-outline-primary, .btn-outline-info, .btn[style*="background-color: #00af00"] {
    border-width: 2px;
    margin: 0 0.25rem;
    transition: all 0.2s ease;
}

/* Tu tabla de agenda */
#tabla_agenda_importada {
  table-layout: fixed;          /* evita "bailes" por textos largos */
  width: 100%;
}

#tabla_agenda_importada th,
#tabla_agenda_importada td {
  vertical-align: middle !important; /* centra verticalmente todo */
}

/* Columna Acciones */
#tabla_agenda_importada th:last-child,
#tabla_agenda_importada td:last-child {
  width: 110px !important;          /* ajusta a lo que necesites */
  white-space: nowrap !important;    /* no partir botones */
  text-align: center !important;
  overflow: visible;
}

/* Contenedor de los botones dentro de la celda */
#tabla_agenda_importada td:last-child .btn-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: .5rem;                        /* espacio entre botones */
}

/* Evitar floats/absolutos en botones de esa columna */
#tabla_agenda_importada td:last-child .btn-wrap .btn {
  float: none !important;
  position: static !important;
}

/* Columna de Competencias - permitir múltiples líneas */
#tabla_agenda_importada td:nth-child(3) { /* Competencias */
  white-space: normal !important;
  text-overflow: initial !important;
  overflow: visible !important;
  min-width: 300px;
  padding: 0.75rem;
}

/* Estilos para el texto de Resultado de Aprendizaje */
#tabla_agenda_importada td:nth-child(3) div:last-child {
  border-top: 1px solid #e9ecef;
  padding-top: 0.5rem;
  margin-top: 0.5rem;
}

/* Centrar badges de Horas */
#tabla_agenda_importada td:nth-child(13) { /* Horas */
  text-align: center;
  vertical-align: middle;
}

.btn-outline-primary:hover, .btn-outline-info:hover, .btn[style*="background-color: #00af00"]:hover {
    transform: scale(1.1);
    background-color: #008000 !important;
    box-shadow: 0 2px 8px rgba(0, 175, 0, 0.3);
}

/* Separadores de fase */
.fase-separator {
    background-color: #f8f9fa !important;
    border-top: 3px solid #00af00 !important;
    font-weight: bold !important;
    font-size: 16px !important;
    color: #00af00 !important;
    text-align: center !important;
    padding: 15px !important;
}

/* Evitar scroll en la tabla */
.table-responsive {
    overflow-x: visible !important;
    overflow-y: visible !important;
    min-height: 160vh;
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Layout para mantener footer abajo */
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Espaciado adicional para la tabla */
#tabla_agenda_importada {
    margin-bottom: 30px;
    flex: 1;
    min-height: 0;
}

/* Contenedor flexible para tabla y paginación */
.table-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.main-content {
    flex: 1;
    min-height: 80vh;
    padding-bottom: 60px;
    padding-top: 30px;
    display: flex;
    flex-direction: column;
}

.footer {
    margin-top: auto;
}

/* Responsive para tablets */
@media (max-width: 1024px) {
    .table {
        font-size: 12px;
    }
    
    .table th, .table td {
        padding: 4px 2px;
    }
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .table {
        font-size: 10px;
    }
    
    .table th, .table td {
        padding: 3px 1px;
    }
    
    /* Botones más pequeños en móvil */
    .table a[style*="width: 24px"] {
        width: 18px !important;
        height: 18px !important;
    }
    
    .table a[style*="width: 32px"] {
        width: 24px !important;
        height: 24px !important;
    }
    
    /* Iconos más pequeños */
    .table i[style*="font-size: 22px"] {
        font-size: 16px !important;
    }
    
    .table i[style*="font-size: 18px"] {
        font-size: 12px !important;
    }
    
    .table i[style*="font-size: 16px"] {
        font-size: 12px !important;
    }
}

/* Pantallas muy pequeñas */
@media (max-width: 480px) {
    .table {
        font-size: 8px;
    }
    
    .table th, .table td {
        padding: 2px 1px;
    }
    
    /* Ocultar columnas menos importantes en pantallas muy pequeñas */
    .table th:nth-child(11), .table td:nth-child(11),
    .table th:nth-child(12), .table td:nth-child(12) {
        display: none;
    }
}

/* Estilos para filtros */
.input-group-text {
    background-color: #00af00;
    color: white;
    border-color: #00af00;
}

.form-control:focus, .form-select:focus {
    border-color: #00af00;
    box-shadow: 0 0 0 0.2rem rgba(0, 175, 0, 0.25);
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    border-color: #007bff;
}
</style>

<!-- Modal -->
<div class="modal fade" id="modalPlaneacionFull" tabindex="-1" aria-labelledby="modalPlaneacionFullLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalPlaneacionFullLabel">
          <i class="fa fa-folder-open me-2"></i>Datos de Planeación
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-3">
        <div class="table-responsive">
          <table id="tablaPlaneacionFull" 
                 class="table table-bordered table-striped table-hover"
                 style="width:100%; font-size: 13px; table-layout: fixed; word-wrap: break-word;">
            <thead class="bg-light">
              <tr>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Fase Proyecto</th>
                <th style="width: 10%; font-size: 11px; padding: 8px 4px;">Actividad Proyecto</th>
                <th style="width: 12%; font-size: 11px; padding: 8px 4px;">Competencia</th>
                <th style="width: 12%; font-size: 11px; padding: 8px 4px;">Resultados aprendizaje</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Saberes conceptos</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Actividades de aprendizaje</th>
                <th style="width: 5%; font-size: 11px; padding: 8px 4px;">Duración (h)</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Estrategia didáctica</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Criterios evaluación</th>
                <th style="width: 6%; font-size: 11px; padding: 8px 4px;">Ambiente</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Recursos</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Evidencias</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Observaciones</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Responsable</th>
                <th style="width: 6%; font-size: 11px; padding: 8px 4px;">Fecha</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Metodología</th>
                <th style="width: 8%; font-size: 11px; padding: 8px 4px;">Material</th>
                <th style="width: 6%; font-size: 11px; padding: 8px 4px;">Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php if($datosPlaneacion): ?>
                <?php foreach($datosPlaneacion as $fila): ?>
                  <tr>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['fp']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['ap']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['comp']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['ra']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['sab']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['aa']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3; text-align: center;"><?= $fila['dh'] ? $fila['dh'] . 'h' : '-' ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['ed']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['ce']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['amb']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['rec']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['evid']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['obs']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['responsable']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['fecha']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['metodologia']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['material']) ?></td>
                    <td style="padding: 6px 4px; font-size: 11px; line-height: 1.3;"><?= nl2br($fila['estado']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="18" class="text-center">No hay registros de planeación para esta ficha</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Incluir modal de planes de sesión -->
<?php require_once('views/vplanses.php'); ?>

</div> <!-- Cerrar main-content -->

<script>
// Variables globales para paginación
let datosAgenda = [];
let datosAgendaFiltrados = [];
let paginaActualAgenda = 1;
let registrosPorPaginaAgenda = 10;

// Función simple para cargar datos de agenda
function cargarDatosAgenda() {
    console.log('Cargando datos de agenda...');
    
    // Verificar si hay datos
    const datos = <?= json_encode($dtAge ?? []) ?>;
    console.log('Datos recibidos:', datos);
    
    if (!datos || datos.length === 0) {
        document.getElementById('tbody_agenda_importada').innerHTML = 
            '<tr><td colspan="14" class="text-center text-muted">No hay datos de agenda disponibles</td></tr>';
        return;
    }
    
    // Guardar datos globalmente
    datosAgenda = datos;
    datosAgendaFiltrados = [...datos];
    paginaActualAgenda = 1;
    
    console.log('Datos guardados globalmente:', datosAgenda.length);
    console.log('Registros por página:', registrosPorPaginaAgenda);
    
    // Mostrar mensaje de carga
    document.getElementById('tbody_agenda_importada').innerHTML = 
        '<tr><td colspan="14" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Cargando datos...</td></tr>';
    
    // Simular carga y mostrar datos paginados
    setTimeout(() => {
        mostrarDatosPaginadosAgenda();
        actualizarInfoPaginacionAgenda();
        generarPaginacionAgenda();
    }, 500);
}

// Función para mostrar datos paginados de agenda
function mostrarDatosPaginadosAgenda() {
    console.log('=== INICIANDO mostrarDatosPaginadosAgenda ===');
    console.log('Datos filtrados:', datosAgendaFiltrados);
    console.log('Página actual:', paginaActualAgenda);
    console.log('Registros por página:', registrosPorPaginaAgenda);
    
    const inicio = (paginaActualAgenda - 1) * registrosPorPaginaAgenda;
    const fin = inicio + registrosPorPaginaAgenda;
    const datosPagina = datosAgendaFiltrados.slice(inicio, fin);
    
    console.log('Datos de la página:', datosPagina);
    
    let html = '';
    let fase_actual = '';
    
    datosPagina.forEach((dta, index) => {
        // Mostrar separador de fase si cambió
        const fase = dta.fas || '';
        if (fase && fase !== fase_actual) {
            fase_actual = fase;
            html += `
                <tr class="fase-separator">
                    <td colspan="14">
                        <i class="fa fa-folder-open" style="margin-right: 8px;"></i>
                        FASE: ${fase.toUpperCase()}
                    </td>
                </tr>
            `;
        }
        
        // Actividad
        const actividad = dta.actpro || '';
        const actividadTexto = actividad; // Mostrar texto completo sin truncar
        
        // Instructor
        const instructor = dta.instructor_principal || dta.nomusu || 'Sin asignar';
        
        // Competencias
        const competencia = dta.descom || '';
        const resultado = dta.nomres || '';
        
        // Debug para verificar datos
        console.log(`Datos para ${index}:`, {
            competencia: competencia,
            resultado: resultado,
            descom: dta.descom,
            nomres: dta.nomres
        });
        
        // Debug para verificar si se está generando el HTML
        console.log(`HTML generado para competencia: ${competencia}`);
        console.log(`HTML generado para resultado: ${resultado}`);
        
        // Días de la semana
        const dias = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
        let diasHtml = '';
        dias.forEach(dia => {
            const clases_dia = dta.dias_clase && dta.dias_clase[dia] ? dta.dias_clase[dia] : [];
            if (clases_dia.length > 0) {
                diasHtml += `<td style="text-align: center; vertical-align: middle; padding: 8px 4px;">
                    <span style="color: #00af00; font-weight: bold; font-size: 18px;">X</span>
                </td>`;
            } else {
                diasHtml += `<td style="text-align: center; vertical-align: middle; padding: 8px 4px;">
                    <span style="color: #ccc; font-size: 14px;">-</span>
                </td>`;
            }
        });
        
        // Fechas
        const fechaInicio = dta.fecha_inicio_agenda || dta.fecini || '-';
        const fechaFin = dta.fecha_fin_agenda || dta.fecfin || '-';
        
        // Horas
        const duracion = dta.horcom || '';
        const horas = duracion ? `${duracion}h` : '-';
        
        html += `
            <tr>
                <td style="vertical-align: top; padding: 8px;">
                    <div>
                        <strong style="font-size: 12px; line-height: 1.3; color: #000; font-weight: bold;">${actividadTexto}</strong>
                    </div>
                </td>
                <td style="vertical-align: middle;">${instructor}</td>
                <td style="vertical-align: top; padding: 8px;">
                    <div>
                        <div style="margin-bottom: 8px;">
                            <strong style="color: #000; font-size: 12px; font-weight: bold;">COMPETENCIA:</strong><br>
                            <span style="font-size: 11px; line-height: 1.3; color: #333; font-weight: normal;">${competencia}</span>
                        </div>
                        <div style="margin-top: 8px;">
                            <strong style="color: #000; font-size: 12px; font-weight: bold;">RESULTADO DE APRENDIZAJE:</strong><br>
                            <span style="font-size: 11px; line-height: 1.3; color: #333; font-weight: normal;">${resultado}</span>
                        </div>
                    </div>
                </td>
                ${diasHtml}
                <td style="vertical-align: middle; text-align: center;">${fechaInicio}</td>
                <td style="vertical-align: middle; text-align: center;">${fechaFin}</td>
                <td style="vertical-align: middle; text-align: center;">
                    <strong style="font-size: 12px; color: #00af00;">${horas}</strong>
                </td>
                <td style="vertical-align: middle; text-align: center;">
                    <div class="btn-wrap">
                        <a href="home.php?pg=1545&idres=${dta.idres}&idfic=<?= $idfic ?>" 
                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border-radius: 4px; background-color: #f8f9fa; margin-right: 5px;"
                           title="Editar">
                            <i class="fa-solid fa-pen-to-square" style="color: #00af00; font-size: 16px;"></i>
                        </a>
                        <a href="#" 
                           onclick="abrirModalPlanesSesion(${dta.idres}, '${resultado.replace(/'/g, "\\'")}', ${dta.ndeses || 1})"
                           style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border-radius: 4px; background-color: #f8f9fa;"
                           title="Gestionar Planes de Sesión">
                            <i class="fa fa-calendar-alt" style="color: #00af00; font-size: 16px;"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    
    console.log('HTML generado:', html);
    console.log('Insertando HTML en tbody_agenda_importada');
    document.getElementById('tbody_agenda_importada').innerHTML = html;
    console.log('HTML insertado correctamente');
}

// Función para limpiar filtros
function limpiarFiltrosAgenda() {
    document.getElementById('filtro_busqueda_agenda').value = '';
    document.getElementById('filtro_fase_agenda').value = '';
    document.getElementById('filtro_instructor_agenda').value = '';
    cargarDatosAgenda();
}

// Función para exportar agenda (comentada)
/*
function exportarAgenda() {
    alert('Función de exportación en desarrollo');
}
*/

// Función para actualizar información de paginación
function actualizarInfoPaginacionAgenda() {
    const totalRegistros = datosAgendaFiltrados.length;
    const inicio = (paginaActualAgenda - 1) * registrosPorPaginaAgenda + 1;
    const fin = Math.min(paginaActualAgenda * registrosPorPaginaAgenda, totalRegistros);
    
    // Actualizar los elementos individuales
    const inicioElement = document.getElementById('inicio_registros_agenda');
    const finElement = document.getElementById('fin_registros_agenda');
    const totalElement = document.getElementById('total_registros_agenda');
    
    if (inicioElement) inicioElement.textContent = inicio;
    if (finElement) finElement.textContent = fin;
    if (totalElement) totalElement.textContent = totalRegistros;
    
    console.log(`Paginación actualizada: ${inicio} a ${fin} de ${totalRegistros} registros`);
}

// Función para generar controles de paginación
function generarPaginacionAgenda() {
    const totalRegistros = datosAgendaFiltrados.length;
    const totalPaginas = Math.ceil(totalRegistros / registrosPorPaginaAgenda);
    
    const paginacion = document.getElementById('paginacion_agenda');
    if (!paginacion) return;
    
    let html = '';
    
    // Botón Anterior
    html += `<li class="page-item ${paginaActualAgenda === 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="cambiarPaginaAgenda(${paginaActualAgenda - 1})">Anterior</a>
    </li>`;
    
    // Números de página
    const inicioPagina = Math.max(1, paginaActualAgenda - 2);
    const finPagina = Math.min(totalPaginas, paginaActualAgenda + 2);
    
    for (let i = inicioPagina; i <= finPagina; i++) {
        html += `<li class="page-item ${i === paginaActualAgenda ? 'active' : ''}">
            <a class="page-link" href="#" onclick="cambiarPaginaAgenda(${i})">${i}</a>
        </li>`;
    }
    
    // Botón Siguiente
    html += `<li class="page-item ${paginaActualAgenda === totalPaginas ? 'disabled' : ''}">
        <a class="page-link" href="#" onclick="cambiarPaginaAgenda(${paginaActualAgenda + 1})">Siguiente</a>
    </li>`;
    
    paginacion.innerHTML = html;
}

// Función para cambiar de página
function cambiarPaginaAgenda(nuevaPagina) {
    const totalPaginas = Math.ceil(datosAgendaFiltrados.length / registrosPorPaginaAgenda);
    
    if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;
    
    paginaActualAgenda = nuevaPagina;
    mostrarDatosPaginadosAgenda();
    actualizarInfoPaginacionAgenda();
    generarPaginacionAgenda();
}

// Función para aplicar filtros
function aplicarFiltrosAgenda() {
    const busqueda = document.getElementById('filtro_busqueda_agenda').value.toLowerCase();
    const fase = document.getElementById('filtro_fase_agenda').value;
    const instructor = document.getElementById('filtro_instructor_agenda').value;
    
    datosAgendaFiltrados = datosAgenda.filter(dta => {
        const cumpleBusqueda = !busqueda || 
            (dta.actpro && dta.actpro.toLowerCase().includes(busqueda)) ||
            (dta.nomusu && dta.nomusu.toLowerCase().includes(busqueda));
        
        const cumpleFase = !fase || dta.fas === fase;
        const cumpleInstructor = !instructor || dta.nomusu === instructor;
        
        return cumpleBusqueda && cumpleFase && cumpleInstructor;
    });
    
    paginaActualAgenda = 1;
    mostrarDatosPaginadosAgenda();
    actualizarInfoPaginacionAgenda();
    generarPaginacionAgenda();
}

// Función para limpiar filtros
function limpiarFiltrosAgenda() {
    document.getElementById('filtro_busqueda_agenda').value = '';
    document.getElementById('filtro_fase_agenda').value = '';
    document.getElementById('filtro_instructor_agenda').value = '';
    
    datosAgendaFiltrados = [...datosAgenda];
    paginaActualAgenda = 1;
    mostrarDatosPaginadosAgenda();
    actualizarInfoPaginacionAgenda();
    generarPaginacionAgenda();
}

// Cargar datos cuando el documento esté listo
$(document).ready(function() {
    cargarDatosAgenda();
    
    // Event listener para cambio de registros por página
    $('#registros_por_pagina_agenda').on('change', function() {
        registrosPorPaginaAgenda = parseInt($(this).val());
        paginaActualAgenda = 1;
        mostrarDatosPaginadosAgenda();
        actualizarInfoPaginacionAgenda();
        generarPaginacionAgenda();
    });
});
</script>

