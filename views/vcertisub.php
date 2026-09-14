<?php require_once 'controllers/ccertisub.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Gestión de Firmas - Certificaciones", 2); ?>
    
    <!-- Mostrar mensajes -->
    <?php mostrarAlerta($mensaje, $tipoMensaje); ?> 
    
    <!-- Panel de estadísticas mejorado -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h3><?= $datEstadisticas['total_esperando_firma'] ?? 0; ?></h3>
                    <p><i class="fa fa-pen"></i> Esperando Firma</p>
                    <?php if (($datEstadisticas['promedio_dias_espera'] ?? 0) > 0): ?>
                        <small>Promedio: <?= round($datEstadisticas['promedio_dias_espera'], 1); ?> días</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h3><?= $datEstadisticas['atrasadas'] ?? 0; ?></h3>
                    <p><i class="fa fa-clock-o"></i> Atrasadas</p>
                    <small>>7 días espera</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h3><?= $datEstadisticas['urgentes'] ?? 0; ?></h3>
                    <p><i class="fa fa-exclamation-triangle"></i> Urgentes</p>
                    <small>3-7 días espera</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h3><?= $datEstadisticas['firmadas_hoy'] ?? 0; ?></h3>
                    <p><i class="fa fa-check"></i> Hoy</p>
                    <small><?= date('d/m/Y'); ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h3><?= $datEstadisticas['firmadas_semana'] ?? 0; ?></h3>
                    <p><i class="fa fa-calendar"></i> Semana</p>
                    <small>Sem. <?= date('W'); ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-secondary text-white">
                <div class="card-body">
                    <h3><?= $datEstadisticas['firmadas_mes'] ?? 0; ?></h3>
                    <p><i class="fa fa-archive"></i> Mes</p>
                    <small><?= date('M Y'); ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas adicionales por tipo de programa -->
    <?php if (($datEstadisticas['total_esperando_firma'] ?? 0) > 0): ?>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6><i class="fa fa-pie-chart"></i> Distribución por Tipo de Programa</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-2">
                                    <i class="fa fa-cogs fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0"><?= $datEstadisticas['tecnicos_pendientes'] ?? 0; ?></h4>
                                    <small class="text-muted">Técnicos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-2">
                                    <i class="fa fa-laptop fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0"><?= $datEstadisticas['tecnologos_pendientes'] ?? 0; ?></h4>
                                    <small class="text-muted">Tecnólogos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-2">
                                    <i class="fa fa-book fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0"><?= $datEstadisticas['cursos_pendientes'] ?? 0; ?></h4>
                                    <small class="text-muted">Cursos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-2">
                                    <i class="fa fa-industry fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0"><?= $datEstadisticas['etapa_productiva'] ?? 0; ?></h4>
                                    <small class="text-muted">Etapa Productiva</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filtros avanzados mejorados -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fa fa-filter"></i> Filtros Avanzados de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form action="home.php?pg=<?=$pg;?>" method="POST">
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Programa</label>
                        <input type="text" name="filtro_programa" class="form-control form-control-sm" 
                               placeholder="Nombre/Ficha" 
                               value="<?= htmlspecialchars($_POST['filtro_programa'] ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Estudiante</label>
                        <input type="text" name="filtro_estudiante" class="form-control form-control-sm" 
                               placeholder="Nombre/Doc" 
                               value="<?= htmlspecialchars($_POST['filtro_estudiante'] ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo Programa</label>
                        <select name="filtro_tipo_programa" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <option value="tecnico" <?= ($_POST['filtro_tipo_programa'] ?? '') == 'tecnico' ? 'selected' : ''; ?>>
                                Técnico
                            </option>
                            <option value="tecnologo" <?= ($_POST['filtro_tipo_programa'] ?? '') == 'tecnologo' ? 'selected' : ''; ?>>
                                Tecnólogo
                            </option>
                            <option value="curso" <?= ($_POST['filtro_tipo_programa'] ?? '') == 'curso' ? 'selected' : ''; ?>>
                                Curso
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo Certificación</label>
                        <select name="filtro_tipo" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            <option value="certificacion_inicio" <?= ($_POST['filtro_tipo'] ?? '') == 'certificacion_inicio' ? 'selected' : ''; ?>>
                                Inicio
                            </option>
                            <option value="certificacion_parcial" <?= ($_POST['filtro_tipo'] ?? '') == 'certificacion_parcial' ? 'selected' : ''; ?>>
                                Parcial
                            </option>
                            <option value="certificacion_etapa_productiva" <?= ($_POST['filtro_tipo'] ?? '') == 'certificacion_etapa_productiva' ? 'selected' : ''; ?>>
                                Etapa Productiva
                            </option>
                            <option value="certificacion_tecnica_completa" <?= ($_POST['filtro_tipo'] ?? '') == 'certificacion_tecnica_completa' ? 'selected' : ''; ?>>
                                Técnica Completa
                            </option>
                            <option value="certificacion_tecnologica_completa" <?= ($_POST['filtro_tipo'] ?? '') == 'certificacion_tecnologica_completa' ? 'selected' : ''; ?>>
                                Tecnológica Completa
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Coordinador</label>
                        <input type="text" name="filtro_coordinador" class="form-control form-control-sm" 
                               placeholder="Nombre" 
                               value="<?= htmlspecialchars($_POST['filtro_coordinador'] ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prioridad</label>
                        <select name="filtro_prioridad" class="form-select form-select-sm">
                            <option value="">Todas</option>
                            <option value="atrasada" <?= ($_POST['filtro_prioridad'] ?? '') == 'atrasada' ? 'selected' : ''; ?>>
                                Atrasadas (>7 días)
                            </option>
                            <option value="urgente" <?= ($_POST['filtro_prioridad'] ?? '') == 'urgente' ? 'selected' : ''; ?>>
                                Urgentes (3-7 días)
                            </option>
                            <option value="normal" <?= ($_POST['filtro_prioridad'] ?? '') == 'normal' ? 'selected' : ''; ?>>
                                Normales (<3 días)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label class="form-label">Fecha Aprobación Desde</label>
                        <input type="date" name="filtro_fecha_desde" class="form-control form-control-sm" 
                               value="<?= $_POST['filtro_fecha_desde'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Fecha Aprobación Hasta</label>
                        <input type="date" name="filtro_fecha_hasta" class="form-control form-control-sm" 
                               value="<?= $_POST['filtro_fecha_hasta'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-1">
                            <button type="submit" name="opera" value="filtrar" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i> Filtrar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-1">
                            <a href="home.php?pg=<?=$pg;?>" class="btn btn-secondary btn-sm">
                                <i class="fa fa-refresh"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Solicitudes esperando firma con información mejorada -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fa fa-pen"></i> Solicitudes Esperando Firma del Subdirector</h5>
            <div>
                <?php if (!empty($datSolicitudesPendientes)): ?>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-success btn-sm" onclick="mostrarFirmaMasiva()">
                            <i class="fa fa-check-square-o"></i> Firma Masiva
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" onclick="filtrarPorPrioridad('atrasada')">
                            <i class="fa fa-clock-o"></i> Solo Atrasadas
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="exportarSolicitudes()">
                            <i class="fa fa-download"></i> Exportar
                        </button>
                    </div>
                    <div class="mt-1">
                        <small class="text-muted">
                            <?= count($datSolicitudesPendientes); ?> solicitud(es) • 
                            <?= ($datEstadisticas['solicitud_mas_antigua'] ? 
                                 'Más antigua: ' . formatearFechaCorta($datEstadisticas['solicitud_mas_antigua']) : 
                                 'Sin solicitudes antiguas'); ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($datSolicitudesPendientes)): ?>
                <div class="alert alert-success text-center py-5">
                    <i class="fa fa-check-circle fa-5x mb-3 text-success"></i>
                    <h3>¡Excelente trabajo!</h3>
                    <p class="lead">No hay solicitudes pendientes de firma.</p>
                    <hr>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4 class="text-info"><?= $datEstadisticas['firmadas_hoy'] ?? 0; ?></h4>
                            <small>Firmadas hoy</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-success"><?= $datEstadisticas['firmadas_semana'] ?? 0; ?></h4>
                            <small>Esta semana</small>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-primary"><?= $datEstadisticas['firmadas_mes'] ?? 0; ?></h4>
                            <small>Este mes</small>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" title="Seleccionar todas">
                                </th>
                                <th width="80">Prioridad</th>
                                <th width="150">Solicitud</th>
                                <th width="200">Estudiante</th>
                                <th width="200">Programa</th>
                                <th width="100">Progreso</th>
                                <th width="120">Coordinador</th>
                                <th width="80">Espera</th>
                                <th width="120">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datSolicitudesPendientes as $solicitud): 
                                $diasEspera = $solicitud['dias_espera'] ?? calcularDiasEspera($solicitud['fecha_respuesta_coordinacion']);
                                $clasePrioridad = getClasePrioridad($diasEspera);
                                $textoPrioridad = getTextoPrioridad($diasEspera);
                                $claseFilaTipo = ($diasEspera > 7) ? 'table-danger' : (($diasEspera > 3) ? 'table-warning' : '');
                            ?>
                                <tr class="<?= $claseFilaTipo; ?>">
                                    <td>
                                        <input type="checkbox" name="solicitudes_check[]" value="<?= $solicitud['idsolicitud']; ?>" 
                                               class="solicitud-checkbox">
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $clasePrioridad; ?> fs-6" title="<?= $diasEspera; ?> días de espera">
                                            <?= $textoPrioridad; ?>
                                        </span>
                                        <?php if ($diasEspera > 7): ?>
                                            <br><i class="fa fa-warning text-danger" title="Solicitud crítica"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-primary">#<?= $solicitud['idsolicitud']; ?></strong>
                                            <br>
                                            <span class="badge bg-<?= getBadgeColorTipoCertificacion($solicitud['tipo_certificacion']); ?>">
                                                <?= formatearTipoCertificacionCorto($solicitud['tipo_certificacion']); ?>
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-calendar-plus-o"></i> <?= formatearFechaCorta($solicitud['fecha_solicitud']); ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($solicitud['nomusu']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-id-card-o"></i> <?= htmlspecialchars($solicitud['ndocusu']); ?>
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-envelope"></i> <?= htmlspecialchars(substr($solicitud['emausu'], 0, 20)); ?>...
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-info">
                                                <?= htmlspecialchars(substr($solicitud['nombre_programa'], 0, 30)); ?>
                                                <?= strlen($solicitud['nombre_programa']) > 30 ? '...' : ''; ?>
                                            </strong>
                                            <br>
                                            <span class="badge bg-<?= getBadgeColorTipoPrograma($solicitud['tipo_programa'] ?? ''); ?>">
                                                <?= strtoupper($solicitud['tipo_programa'] ?? 'N/A'); ?>
                                            </span>
                                            <small class="text-muted ms-1">Ficha: <?= $solicitud['codigo_ficha']; ?></small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if (isset($solicitud['trimestre_actual']) && $solicitud['trimestre_actual'] > 0): ?>
                                            <div class="mb-1">
                                                <small><strong>Trimestre:</strong> <?= $solicitud['trimestre_actual']; ?></small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-<?= ($solicitud['porcentaje_avance'] >= 75) ? 'success' : (($solicitud['porcentaje_avance'] >= 50) ? 'warning' : 'info'); ?>" 
                                                     role="progressbar" 
                                                     style="width: <?= min($solicitud['porcentaje_avance'], 100); ?>%" 
                                                     title="<?= round($solicitud['porcentaje_avance'], 1); ?>% completado">
                                                </div>
                                            </div>
                                            <small class="text-muted"><?= round($solicitud['porcentaje_avance'], 1); ?>%</small>
                                        <?php else: ?>
                                            <small class="text-muted">Sin datos</small>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($solicitud['bitacoras_completas']) && $solicitud['total_bitacoras'] > 0): ?>
                                            <br>
                                            <small class="text-success">
                                                <i class="fa fa-book"></i> <?= $solicitud['bitacoras_completas']; ?>/<?= $solicitud['total_bitacoras']; ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="text-success"><?= htmlspecialchars($solicitud['nombre_coordinador']); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-check-circle"></i> <?= formatearFechaCorta($solicitud['fecha_respuesta_coordinacion']); ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-<?= $clasePrioridad; ?> fs-5 mb-1">
                                                <?= $diasEspera; ?>
                                            </span>
                                            <small class="text-muted">día<?= $diasEspera != 1 ? 's' : ''; ?></small>
                                            
                                            <?php if ($diasEspera > 7): ?>
                                                <div class="mt-1">
                                                    <i class="fa fa-warning text-danger" title="Atención: Más de 7 días"></i>
                                                </div>
                                            <?php elseif ($diasEspera > 3): ?>
                                                <div class="mt-1">
                                                    <i class="fa fa-clock-o text-warning" title="Urgente: Más de 3 días"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical" role="group">
                                            <button type="button" class="btn btn-info btn-sm mb-1" 
                                                    onclick="verDetalleSolicitud(<?= $solicitud['idsolicitud']; ?>)"
                                                    title="Ver información completa">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-success btn-sm mb-1" 
                                                    onclick="firmarSolicitud(<?= $solicitud['idsolicitud']; ?>)"
                                                    title="Firmar y completar certificación">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="rechazarSolicitudSubdirector(<?= $solicitud['idsolicitud']; ?>)"
                                                    title="Rechazar solicitud">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Resumen por coordinador -->
    <?php if (!empty($datResumenCoordinadores)): ?>
    <div class="row mt-4">
        <div class="col-md-8">
            <!-- Historial reciente -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fa fa-history"></i> Últimas Firmas Realizadas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($datHistorialFirmas)): ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No hay firmas registradas aún.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Estudiante</th>
                                        <th>Tipo</th>
                                        <th>Coordinador</th>
                                        <th>Fecha Firma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($datHistorialFirmas, 0, 10) as $firma): ?>
                                        <tr>
                                            <td>#<?= $firma['idsolicitud']; ?></td>
                                            <td>
                                                <?= htmlspecialchars($firma['nomusu']); ?>
                                                <br><small class="text-muted"><?= $firma['ndocusu']; ?></small>
                                            </td>
                                            <td>
                                                <small><?= ucfirst(str_replace('_', ' ', $firma['tipo_certificacion'])); ?></small>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($firma['nombre_coordinador']); ?></small>
                                            </td>
                                            <td>
                                                <small><?= formatearFecha($firma['fecha_firma_subdirector']); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Resumen por coordinador -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fa fa-users"></i> Resumen por Coordinador</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($datResumenCoordinadores)): ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No hay datos de coordinadores.
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($datResumenCoordinadores as $resumen): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($resumen['coordinador']); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            Más antigua: <?= formatearFechaCorta($resumen['solicitud_mas_antigua']); ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary"><?= $resumen['total_esperando']; ?></span>
                                        <br>
                                        <small class="text-muted"><?= round($resumen['dias_promedio_espera'], 1); ?> días</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal para ver detalle de solicitud -->
<div class="modal fade" id="modalDetalleSolicitud" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-info-circle"></i> Detalle de Solicitud para Firma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoDetalleSolicitud">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <p>Cargando...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btnFirmarDesdeDetalle">
                    <i class="fa fa-pen"></i> Firmar Ahora
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para firmar solicitud individual -->
<div class="modal fade" id="modalFirmar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-pen"></i> Firmar Certificación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="home.php?pg=<?=$pg;?>">
                <div class="modal-body">
                    <input type="hidden" name="opera" value="firmar">
                    <input type="hidden" name="idsolicitud" id="idSolicitudFirmar">
                    
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        <strong>¿Confirmas la firma de esta certificación?</strong>
                        <br><br>
                        Al firmar se completará el proceso y se generará automáticamente la certificación para el estudiante.
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacionesFinales" class="form-label">
                            <strong>Observaciones Finales</strong> (opcional)
                        </label>
                        <textarea class="form-control" name="observaciones_finales" id="observacionesFinales" rows="3"
                                  placeholder="Comentarios adicionales sobre la certificación..."></textarea>
                        <small class="form-text text-muted">
                            Estas observaciones aparecerán en el certificado final.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-pen"></i> Confirmar Firma
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para rechazar desde subdirección -->
<div class="modal fade" id="modalRechazarSubdirector" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-times"></i> Rechazar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="home.php?pg=<?=$pg;?>">
                <div class="modal-body">
                    <input type="hidden" name="opera" value="rechazar_subdirector">
                    <input type="hidden" name="idsolicitud" id="idSolicitudRechazarSubd">
                    
                    <div class="alert alert-warning">
                        <i class="fa fa-warning"></i> 
                        <strong>¿Estás seguro de rechazar esta solicitud?</strong>
                        <br><br>
                        Este rechazo será definitivo y se notificará tanto al estudiante como al coordinador que la aprobó.
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivoRechazoSubd" class="form-label">
                            <strong>Motivo del rechazo</strong> <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="motivo_rechazo" id="motivoRechazoSubd" rows="4"
                                  placeholder="Explica detalladamente el motivo del rechazo desde subdirección..." required></textarea>
                        <small class="form-text text-muted">
                            Este motivo será visible para el coordinador y el estudiante.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times"></i> Confirmar Rechazo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para firma masiva -->
<div class="modal fade" id="modalFirmaMasiva" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-check-square-o"></i> Firma Masiva de Certificaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="home.php?pg=<?=$pg;?>" id="formFirmaMasiva">
                <div class="modal-body">
                    <input type="hidden" name="opera" value="firmar_masivo">
                    
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        <strong>Firma Masiva de Solicitudes</strong>
                        <br>
                        Se firmarán todas las solicitudes seleccionadas. Esta acción no se puede deshacer.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Solicitudes Seleccionadas:</strong></label>
                        <div id="listaSolicitudesSeleccionadas" class="border p-3 rounded bg-light">
                            <!-- Se llena via JavaScript -->
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacionesGenerales" class="form-label">
                            <strong>Observaciones Generales</strong> (opcional)
                        </label>
                        <textarea class="form-control" name="observaciones_generales" id="observacionesGenerales" rows="3"
                                  placeholder="Comentarios que se aplicarán a todas las certificaciones..."></textarea>
                    </div>
                    
                    <div id="solicitudesIdsContainer">
                        <!-- Los IDs se agregan aquí via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check-square-o"></i> Firmar Todas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript mejorado con funciones adicionales

// Función para filtrar por prioridad rápidamente
function filtrarPorPrioridad(prioridad) {
    document.querySelector('[name="filtro_prioridad"]').value = prioridad;
    document.querySelector('[name="opera"][value="filtrar"]').click();
}

// Función para mostrar estadísticas en tiempo real
function actualizarEstadisticas() {
    // Contar filas por tipo de prioridad
    const filas = document.querySelectorAll('tbody tr');
    let atrasadas = 0, urgentes = 0, normales = 0;
    
    filas.forEach(fila => {
        if (fila.classList.contains('table-danger')) atrasadas++;
        else if (fila.classList.contains('table-warning')) urgentes++;
        else normales++;
    });
    
    // Actualizar badges si existen
    console.log(`Estadísticas actuales: ${atrasadas} atrasadas, ${urgentes} urgentes, ${normales} normales`);
}

// Función para resaltar solicitudes críticas
function resaltarSolicitudesCriticas() {
    const filasAtrasadas = document.querySelectorAll('.table-danger');
    filasAtrasadas.forEach((fila, index) => {
        setTimeout(() => {
            fila.style.animation = 'pulse 2s infinite';
        }, index * 200);
    });
}

// Función mejorada para ver detalle con más información
function verDetalleSolicitud(idsolicitud) {
    solicitudActual = idsolicitud;
    
    $('#contenidoDetalleSolicitud').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando información detallada...</p>
        </div>
    `);
    
    $('#modalDetalleSolicitud').modal('show');
    
    $.ajax({
        url: 'home.php?pg=<?=$pg;?>',
        type: 'POST',
        data: {
            opera: 'detalle',
            idsolicitud: idsolicitud
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.solicitud) {
                let s = response.solicitud;
                
                // Generar HTML con información completa del programa
                let html = generarHTMLDetalleCompleto(s);
                $('#contenidoDetalleSolicitud').html(html);
                
                // Configurar botón de firma desde detalle
                $('#btnFirmarDesdeDetalle').off('click').on('click', function() {
                    $('#modalDetalleSolicitud').modal('hide');
                    setTimeout(() => firmarSolicitud(solicitudActual), 500);
                });
                
            } else {
                $('#contenidoDetalleSolicitud').html(`
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i> Error al cargar los datos de la solicitud
                    </div>
                `);
            }
        },
        error: function() {
            $('#contenidoDetalleSolicitud').html(`
                <div class="alert alert-danger">
                    <i class="fa fa-wifi"></i> Error de conexión con el servidor
                </div>
            `);
        }
    });
}

// Función para generar HTML detallado con información del programa
function generarHTMLDetalleCompleto(s) {
    return `
        <div class="row">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fa fa-user"></i> Información del Estudiante</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr><td><strong>Nombre:</strong></td><td>${s.nomusu}</td></tr>
                            <tr><td><strong>Documento:</strong></td><td>${s.ndocusu}</td></tr>
                            <tr><td><strong>Email:</strong></td><td><small>${s.emausu}</small></td></tr>
                            <tr><td><strong>Teléfono:</strong></td><td>${s.telcan || 'N/A'}</td></tr>
                            <tr><td><strong>Programa:</strong></td><td><strong class="text-info">${s.nombre_programa}</strong></td></tr>
                            <tr><td><strong>Ficha:</strong></td><td><code>${s.codigo_ficha}</code></td></tr>
                            <tr><td><strong>Tipo:</strong></td><td><span class="badge bg-success">${s.tipo_programa}</span></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        ${s.trimestre_actual ? `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fa fa-chart-line"></i> Progreso Académico del Estudiante</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h4 class="text-primary">${s.trimestre_actual}</h4>
                                    <small>Trimestre Actual</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h4 class="text-success">${s.porcentaje_avance}%</h4>
                                    <small>Avance General</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h4 class="text-info">${s.bitacoras_completas}/${s.total_bitacoras}</h4>
                                    <small>Bitácoras</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h4 class="text-warning">${calcularDiasFormacion(s.fecha_inicio_programa)}</h4>
                                    <small>Días Formación</small>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: ${Math.min(s.porcentaje_avance, 100)}%" 
                                     title="${s.porcentaje_avance}% completado">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ` : ''}
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fa fa-comment"></i> Motivo Original de la Solicitud</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 fst-italic">"${s.motivo}"</p>
                    </div>
                </div>
            </div>
        </div>
        
        ${s.respuesta_coordinacion ? `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fa fa-check"></i> Aprobación del Coordinador</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">${s.respuesta_coordinacion}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-user"></i> <strong>Coordinador:</strong> ${s.nombre_coordinador}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fa fa-calendar"></i> <strong>Fecha:</strong> ${formatearFechaJS(s.fecha_respuesta_coordinacion)}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ` : ''}
        
        ${s.dias_espera > 7 ? `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="fa fa-warning"></i> 
                        <strong>SOLICITUD CRÍTICA</strong> - Esta solicitud lleva ${s.dias_espera} días esperando firma. 
                        Se recomienda procesar con máxima prioridad para evitar retrasos en la entrega al estudiante.
                    </div>
                </div>
            </div>
        ` : (s.dias_espera > 3 ? `
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="fa fa-clock-o"></i> 
                        <strong>SOLICITUD URGENTE</strong> - Esta solicitud lleva ${s.dias_espera} días esperando firma.
                        Se recomienda procesar en los próximos días.
                    </div>
                </div>
            </div>
        ` : '')}
    `;
}

// Función auxiliar para calcular días de formación
function calcularDiasFormacion(fechaInicio) {
    if (!fechaInicio) return 'N/A';
    const inicio = new Date(fechaInicio);
    const ahora = new Date();
    const diferencia = Math.floor((ahora - inicio) / (1000 * 60 * 60 * 24));
    return diferencia > 0 ? diferencia : 0;
}

// Función auxiliar para formatear tipo de certificación
function formatearTipoCertificacion(tipo) {
    const tipos = {
        'certificacion_inicio': 'Inicio de Formación',
        'certificacion_parcial': 'Competencias Parciales', 
        'certificacion_etapa_productiva': 'Etapa Productiva',
        'certificacion_tecnica_completa': 'Técnica Completa',
        'certificacion_tecnologica_completa': 'Tecnológica Completa',
        'certificacion_curso_completo': 'Curso Completo',
        'constancia_estudiante_activo': 'Estudiante Activo'
    };
    return tipos[tipo] || tipo.replace('_', ' ');
}

// Mantener las funciones existentes de firma, rechazo, etc.
function firmarSolicitud(idsolicitud) {
    $('#idSolicitudFirmar').val(idsolicitud);
    $('#observacionesFinales').val('');
    $('#modalFirmar').modal('show');
}

function rechazarSolicitudSubdirector(idsolicitud) {
    $('#idSolicitudRechazarSubd').val(idsolicitud);
    $('#motivoRechazoSubd').val('');
    $('#modalRechazarSubdirector').modal('show');
}

function mostrarFirmaMasiva() {
    const checkboxes = document.querySelectorAll('.solicitud-checkbox:checked');
    
    if (checkboxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Selección requerida',
            text: 'Debes seleccionar al menos una solicitud para firmar masivamente.',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    
    // Verificar si hay solicitudes críticas seleccionadas
    let solicitudesCriticas = 0;
    checkboxes.forEach(function(checkbox) {
        const row = checkbox.closest('tr');
        if (row.classList.contains('table-danger')) {
            solicitudesCriticas++;
        }
    });
    
    // Limpiar contenedores
    $('#listaSolicitudesSeleccionadas').empty();
    $('#solicitudesIdsContainer').empty();
    
    let html = '<div class="list-group">';
    
    checkboxes.forEach(function(checkbox) {
        const row = checkbox.closest('tr');
        const id = checkbox.value;
        const estudiante = row.querySelector('td:nth-child(4) strong').textContent;
        const programa = row.querySelector('td:nth-child(5) strong').textContent;
        const tipo = row.querySelector('td:nth-child(3) .badge').textContent;
        const prioridad = row.querySelector('td:nth-child(2) .badge').textContent;
        const diasEspera = row.querySelector('td:nth-child(8) .badge').textContent;
        
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <h6 class="mb-1">#${id} - ${estudiante}</h6>
                    <p class="mb-1"><strong>Programa:</strong> ${programa.substring(0, 40)}${programa.length > 40 ? '...' : ''}</p>
                    <small class="text-muted">${tipo}</small>
                </div>
                <div class="text-end">
                    <span class="badge bg-${getClasePrioridadPorTexto(prioridad)} mb-1">${prioridad}</span><br>
                    <small class="text-muted">${diasEspera} días</small>
                </div>
            </div>
        `;
        
        // Agregar input hidden
        $('#solicitudesIdsContainer').append(`<input type="hidden" name="solicitudes_ids[]" value="${id}">`);
    });
    
    html += '</div>';
    
    if (solicitudesCriticas > 0) {
        html = `<div class="alert alert-warning mb-3">
                    <i class="fa fa-warning"></i> <strong>Atención:</strong> 
                    ${solicitudesCriticas} de las solicitudes seleccionadas están atrasadas (>7 días).
                    La firma masiva ayudará a regularizar estas situaciones críticas.
                </div>` + html;
    }
    
    $('#listaSolicitudesSeleccionadas').html(html);
    $('#modalFirmaMasiva').modal('show');
}

// Función auxiliar para obtener clase de prioridad por texto
function getClasePrioridadPorTexto(texto) {
    switch(texto) {
        case 'ATRASADA': return 'danger';
        case 'URGENTE': return 'warning';
        case 'NORMAL': return 'info';
        case 'RECIENTE': return 'success';
        default: return 'secondary';
    }
}

// Manejo mejorado de checkboxes con contadores
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.solicitud-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            actualizarContadorSeleccionadas();
        });
    }
    
    // Listener para checkboxes individuales
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('solicitud-checkbox')) {
            actualizarEstadoSelectAll();
            actualizarContadorSeleccionadas();
        }
    });
    
    // Resaltar solicitudes críticas al cargar
    setTimeout(resaltarSolicitudesCriticas, 1000);
    
    // Actualizar estadísticas
    setTimeout(actualizarEstadisticas, 500);
});

function actualizarContadorSeleccionadas() {
    const seleccionadas = document.querySelectorAll('.solicitud-checkbox:checked').length;
    const total = document.querySelectorAll('.solicitud-checkbox').length;
    
    // Actualizar texto en botón de firma masiva si existe
    const btnFirmaMasiva = document.querySelector('button[onclick="mostrarFirmaMasiva()"]');
    if (btnFirmaMasiva) {
        if (seleccionadas > 0) {
            btnFirmaMasiva.innerHTML = `<i class="fa fa-check-square-o"></i> Firmar ${seleccionadas} Seleccionadas`;
            btnFirmaMasiva.classList.remove('btn-success');
            btnFirmaMasiva.classList.add('btn-warning');
        } else {
            btnFirmaMasiva.innerHTML = `<i class="fa fa-check-square-o"></i> Firma Masiva`;
            btnFirmaMasiva.classList.remove('btn-warning');
            btnFirmaMasiva.classList.add('btn-success');
        }
    }
}

function actualizarEstadoSelectAll() {
    const totalCheckboxes = document.querySelectorAll('.solicitud-checkbox').length;
    const checkedCheckboxes = document.querySelectorAll('.solicitud-checkbox:checked').length;
    const selectAllCheckbox = document.getElementById('selectAll');
    
    if (selectAllCheckbox) {
        if (checkedCheckboxes === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes === totalCheckboxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
}

// Auto-refresh inteligente cada 2 minutos para verificar nuevas solicitudes
let ultimaActualizacion = Date.now();
setInterval(function() {
    const totalEsperando = <?= $datEstadisticas['total_esperando_firma'] ?? 0; ?>;
    const atrasadas = <?= $datEstadisticas['atrasadas'] ?? 0; ?>;
    
    if (totalEsperando > 0) {
        // Actualizar título de la página con información de urgencia
        if (atrasadas > 0) {
            document.title = `⚠️ (${atrasadas}) Firmas Críticas - SENA Subdirección`;
        } else {
            document.title = `📝 (${totalEsperando}) Firmas Pendientes - SENA Subdirección`;
        }
        
        console.log(`Estado actual: ${totalEsperando} solicitudes esperando firma, ${atrasadas} críticas`);
        
        // Mostrar notificación discreta cada 10 minutos si hay solicitudes críticas
        if (atrasadas > 0 && (Date.now() - ultimaActualizacion) > 600000) {
            mostrarNotificacionCritica(atrasadas);
            ultimaActualizacion = Date.now();
        }
    } else {
        document.title = '✅ Firmas al Día - SENA Subdirección';
    }
}, 120000); // cada 2 minutos

function mostrarNotificacionCritica(cantidad) {
    // Crear notificación discreta
    const notificacion = document.createElement('div');
    notificacion.className = 'alert alert-warning alert-dismissible position-fixed';
    notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 350px;';
    notificacion.innerHTML = `
        <i class="fa fa-warning"></i>
        <strong>Recordatorio:</strong> Tienes ${cantidad} solicitud${cantidad > 1 ? 'es' : ''} crítica${cantidad > 1 ? 's' : ''} (>7 días) esperando firma.
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(notificacion);
    
    // Auto-remove después de 10 segundos
    setTimeout(() => {
        if (notificacion.parentElement) {
            notificacion.remove();
        }
    }, 10000);
}

// Atajos de teclado mejorados
document.addEventListener('keydown', function(e) {
    // Ctrl + A = Seleccionar todas
    if (e.ctrlKey && e.key === 'a' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.checked = true;
            selectAll.dispatchEvent(new Event('change'));
        }
    }
    
    // Ctrl + F = Firma masiva (si hay selecciones)
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const checked = document.querySelectorAll('.solicitud-checkbox:checked');
        if (checked.length > 0) {
            mostrarFirmaMasiva();
        }
    }
    
    // Ctrl + U = Filtrar urgentes
    if (e.ctrlKey && e.key === 'u') {
        e.preventDefault();
        filtrarPorPrioridad('urgente');
    }
    
    // Ctrl + R = Refresh página
    if (e.ctrlKey && e.key === 'r') {
        e.preventDefault();
        window.location.reload();
    }
});

// Mantener funciones auxiliares existentes
function formatearFechaJS(fecha) {
    if (!fecha) return 'N/A';
    let d = new Date(fecha);
    return d.toLocaleDateString('es-ES') + ' ' + d.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});
}

function getClasePrioridadJS(dias) {
    if (dias > 7) return 'danger';
    if (dias > 3) return 'warning';
    if (dias > 1) return 'info';
    return 'success';
}

function exportarSolicitudes() {
    window.location.href = "home.php?pg=<?=$pg;?>&opera=exportar";
}

</script>


?>