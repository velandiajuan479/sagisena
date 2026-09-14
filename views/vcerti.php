<?php require_once 'controllers/ccerti.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Mis Certificaciones", 0); ?>

    <!-- Mostrar mensajes -->
    <?php mostrarAlerta($mensaje, $tipoMensaje); ?>

    <!-- Información del usuario -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fa fa-user"></i> Información del Aprendiz</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nombre:</strong> <?= $datOne[0]['nomusu'] ?? 'N/A'; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Documento:</strong> <?= $datOne[0]['ndocusu'] ?? 'N/A'; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> <?= $datOne[0]['emausu'] ?? 'N/A'; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Teléfono:</strong> <?= $datOne[0]['telcan'] ?? 'N/A'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6><i class="fa fa-graduation-cap"></i> Resumen General</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-primary"><?= count($datProgramasAprendiz); ?></h4>
                        <small>Programas Inscritos</small>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success"><?= $datEstadisticasSolicitudes['completadas'] ?? 0; ?></h5>
                            <small>Certificados</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-warning"><?= $datEstadisticasSolicitudes['pendientes'] ?? 0; ?></h5>
                            <small>Solicitudes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MIS PROGRAMAS Y CERTIFICACIONES DISPONIBLES -->
    <?php if (!empty($datProgramasAprendiz)): ?>
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5><i class="fa fa-list"></i> Mis Programas de Formación</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    <strong>Múltiples Programas:</strong> Puedes tener certificaciones de cada programa por separado. 
                    Selecciona un programa para ver las certificaciones disponibles según tu progreso actual.
                </div>
                
                <!-- Pestañas de programas -->
                <ul class="nav nav-tabs" id="programasTabs" role="tablist">
                    <?php foreach ($datProgramasAprendiz as $index => $programa): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?= $index === 0 ? 'active' : ''; ?>" 
                                    id="programa-<?= $programa['idfic']; ?>-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#programa-<?= $programa['idfic']; ?>" 
                                    type="button" role="tab">
                                <span class="badge bg-<?= getBadgeColorPrograma($programa['tipo_programa']); ?> me-1">
                                    <?= strtoupper($programa['tipo_programa']); ?>
                                </span>
                                <?= htmlspecialchars(substr($programa['nombre_programa'], 0, 20)); ?>
                                <?= strlen($programa['nombre_programa']) > 20 ? '...' : ''; ?>
                                <span class="badge bg-secondary ms-1"><?= round($programa['porcentaje_avance']); ?>%</span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content" id="programasTabsContent">
                    <?php foreach ($datProgramasAprendiz as $index => $programa): ?>
                        <div class="tab-pane fade <?= $index === 0 ? 'show active' : ''; ?>" 
                             id="programa-<?= $programa['idfic']; ?>" 
                             role="tabpanel">
                            
                            <!-- Información detallada del programa -->
                            <div class="card mt-3">
                                <div class="card-header bg-light">
                                    <h6><i class="fa fa-info-circle"></i> Detalles del Programa</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h5 class="text-primary"><?= htmlspecialchars($programa['nombre_programa']); ?></h5>
                                            <div class="row mt-3">
                                                <div class="col-md-3">
                                                    <strong>Tipo:</strong><br>
                                                    <span class="badge bg-<?= getBadgeColorPrograma($programa['tipo_programa']); ?>">
                                                        <?= strtoupper($programa['tipo_programa']); ?>
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Ficha:</strong><br>
                                                    <code><?= $programa['idfic']; ?></code>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Inicio:</strong><br>
                                                    <?= date('d/m/Y', strtotime($programa['fecha_inicio'])); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Estado:</strong><br>
                                                    <span class="badge bg-<?= getEstadoPrograma($programa['estado_programa']); ?>">
                                                        <?= ucfirst($programa['estado_programa']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <!-- Progreso visual -->
                                            <div class="text-center">
                                                                                                    <div class="position-relative d-inline-block">
                                                        <canvas width="120" height="120" id="progress-<?= $programa['idfic']; ?>"></canvas>
                                                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                                                            <h4 class="mb-0"><?= round($programa['porcentaje_avance']); ?>%</h4>
                                                            <small>Avance</small>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <small>
                                                            <strong>Trimestre:</strong> <?= $programa['trimestre_actual']; ?>/<?= $programa['duracion_trimestres']; ?><br>
                                                            <strong>Bitácoras:</strong> <?= $programa['bitacoras_completas']; ?>/<?= $programa['total_bitacoras']; ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificaciones disponibles para este programa -->
                            <?php 
                            $certificacionesPrograma = [];
                            foreach ($datCertificacionesDisponibles as $item) {
                                if ($item['programa']['idfic'] == $programa['idfic']) {
                                    $certificacionesPrograma = $item['certificaciones_disponibles'];
                                    break;
                                }
                            }
                            ?>

                            <?php if (!empty($certificacionesPrograma)): ?>
                                <div class="card mt-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6><i class="fa fa-certificate"></i> Certificaciones Disponibles</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php foreach ($certificacionesPrograma as $cert): ?>
                                                <div class="col-md-6 mb-3">
                                                    <div class="card border-<?= $cert['requisitos_cumplidos'] ? 'success' : 'warning'; ?> h-100">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fa <?= getIconoCertificacion($cert['tipo']); ?> fa-2x text-<?= $cert['requisitos_cumplidos'] ? 'success' : 'warning'; ?> me-3"></i>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="card-title mb-1"><?= $cert['nombre']; ?></h6>
                                                                    <small class="text-muted"><?= formatearTipoCertificacion($cert['tipo']); ?></small>
                                                                </div>
                                                            </div>
                                                            
                                                            <p class="card-text small"><?= $cert['descripcion']; ?></p>
                                                            
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <?php if ($cert['requisitos_cumplidos']): ?>
                                                                    <span class="badge bg-success">
                                                                        <i class="fa fa-check"></i> Disponible
                                                                    </span>
                                                                    <button type="button" 
                                                                            class="btn btn-success btn-sm btn-solicitar"
                                                                            onclick="solicitarCertificacionPrograma('<?= $cert['tipo']; ?>', '<?= htmlspecialchars($cert['nombre']); ?>', '<?= $programa['idfic']; ?>', '<?= htmlspecialchars($programa['nombre_programa']); ?>')">
                                                                        <i class="fa fa-paper-plane"></i> Solicitar
                                                                    </button>
                                                                
                                                                <?php else: ?>
                                                                    <span class="badge bg-warning">
                                                                        <i class="fa fa-clock-o"></i> Requisitos pendientes
                                                                    </span>
                                                                    <small class="text-muted">Proximamente</small>
                                                                <?php endif; ?>
                                                            </div>
                                                            <style>
                                                            .btn-solicitar {
                                                                position: relative;
                                                                top: 2px; 
                                                            }
                                                            </style>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                               
                            <?php else: ?>
                                <div class="alert alert-info mt-3">
                                    <i class="fa fa-info-circle"></i> No hay certificaciones disponibles para tu progreso actual en este programa.
                                </div>
                            <?php endif; ?>

                            <!-- Mis solicitudes para este programa -->
                            <?php 
                            $solicitudesPrograma = array_filter($datMisSolicitudes, function($sol) use ($programa) {
                                return $sol['idfic'] == $programa['idfic'];
                            });
                            ?>

                            <?php if (!empty($solicitudesPrograma)): ?>
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        <h6><i class="fa fa-list-alt"></i> Mis Solicitudes para este Programa</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Tipo</th>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($solicitudesPrograma as $solicitud): ?>
                                                        <tr>
                                                            <td><?= $solicitud['id']; ?></td>
                                                            <td>
                                                                <small><?= formatearTipoCertificacion($solicitud['tipo_certificacion']); ?></small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-<?= getClaseEstadoSolicitud($solicitud['estado']); ?>">
                                                                    <?= getTextoEstado($solicitud['estado']); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <small><?= date('d/m/Y', strtotime($solicitud['fecha_solicitud'])); ?></small>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-info" 
                                                                        onclick="verDetalleSolicitud(<?= $solicitud['id']; ?>)"
                                                                        title="Ver detalle">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                
                                                                <?php if ($solicitud['estado'] === 'completada'): ?>
                                                                    <button type="button" class="btn btn-sm btn-success ms-1" 
                                                                            onclick="descargarCertificado(<?= $solicitud['id']; ?>)"
                                                                            title="Descargar certificado">
                                                                        <i class="fa fa-download"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> No tienes programas de formación asignados actualmente.
            <br>Contacta con tu coordinador académico para más información.
        </div>
    <?php endif; ?>

    <!-- Resumen general de solicitudes -->
    <?php if (!empty($datMisSolicitudes)): ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fa fa-file-text-o"></i> Resumen General de Solicitudes</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-warning"><?= $datEstadisticasSolicitudes['pendientes'] ?? 0; ?> Pendientes</span>
                    <span class="badge bg-info"><?= $datEstadisticasSolicitudes['esperando_firma'] ?? 0; ?> Esp. Firma</span>
                    <span class="badge bg-success"><?= $datEstadisticasSolicitudes['completadas'] ?? 0; ?> Completadas</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Programa</th>
                                <th>Tipo Certificación</th>
                                <th>Estado</th>
                                <th>Fecha Solicitud</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datMisSolicitudes as $solicitud): ?>
                                <tr>
                                    <td><?= $solicitud['id']; ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($solicitud['nombre_programa'] ?? 'N/A'); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <span class="badge bg-secondary"><?= strtoupper($solicitud['tipo_programa'] ?? 'N/A'); ?></span>
                                            Ficha: <?= $solicitud['idfic']; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <strong><?= formatearTipoCertificacion($solicitud['tipo_certificacion']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= getClaseEstadoSolicitud($solicitud['estado']); ?>">
                                            <?= getTextoEstado($solicitud['estado']); ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" 
                                                onclick="verDetalleSolicitud(<?= $solicitud['id']; ?>)"
                                                title="Ver detalle">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($solicitud['estado'] === 'completada'): ?>
                                            <button type="button" class="btn btn-sm btn-success ms-1" 
                                                    onclick="descargarCertificado(<?= $solicitud['id']; ?>)"
                                                    title="Descargar certificado">
                                                <i class="fa fa-download"></i> Descargar
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Certificaciones obtenidas -->
    <?php if (!empty($datCertificaciones)): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-trophy"></i> Mis Certificaciones Obtenidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($datCertificaciones as $cert): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-success">
                                <?php if (!empty($cert['imagen_certificacion']) && file_exists($cert['imagen_certificacion'])): ?>
                                    <img src="<?= $cert['imagen_certificacion']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Certificación">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa fa-certificate fa-5x text-success"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-success"><?= htmlspecialchars($cert['nombre_certificacion']); ?></h5>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($cert['descripcion'] ?? ''); ?></p>
                                    
                                    <div class="mt-auto">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">Emisor</small>
                                                <br><strong><?= htmlspecialchars($cert['entidad_emisora'] ?? 'SENA'); ?></strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Fecha</small>
                                                <br><strong><?= date('d/m/Y', strtotime($cert['fecha_obtencion'])); ?></strong>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-<?= 
                                                $cert['estado'] == 'activo' ? 'success' : 
                                                ($cert['estado'] == 'por_vencer' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cert['estado'])); ?>
                                            </span>
                                            
                                            <div>
                                                <?php if (!empty($cert['archivo_certificacion']) && file_exists($cert['archivo_certificacion'])): ?>
                                                    <a href="<?= $cert['archivo_certificacion']; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Ver certificado">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?= $cert['archivo_certificacion']; ?>" download class="btn btn-sm btn-outline-success ms-1" title="Descargar certificado">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($cert['fecha_vencimiento'])): ?>
                                            <small class="text-muted d-block mt-2">
                                                Vence: <?= date('d/m/Y', strtotime($cert['fecha_vencimiento'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmación para solicitar certificación con programa -->
<div class="modal fade" id="modalConfirmarSolicitudPrograma" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fa fa-paper-plane"></i> Confirmar Solicitud de Certificación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="home.php?pg=<?=$pg;?>">
                <div class="modal-body">
                    <input type="hidden" name="opera" value="solicitar_programa">
                    <input type="hidden" name="tipo_certificacion" id="tipoSolicitudPrograma">
                    <input type="hidden" name="idfic" id="idficSolicitud">
                    
                    <div class="alert alert-info">
                        <i class="fa fa-robot"></i>
                        <strong>Sistema Automático</strong><br>
                        El sistema generará automáticamente la justificación basada en tu progreso académico actual.
                    </div>
                    
                    <div class="text-center">
                        <i class="fa fa-certificate fa-3x text-success mb-3"></i>
                        <h5>¿Deseas solicitar la certificación:</h5>
                        <h4 class="text-primary" id="nombreCertificacionPrograma"></h4>
                        <hr>
                        <p><strong>Para el programa:</strong></p>
                        <h5 class="text-info" id="nombreProgramaSolicitud"></h5>
                    </div>
                    
                    <hr>
                    
                    <p><strong>Proceso automático:</strong></p>
                    <ol class="small">
                        <li>El sistema analizará tu progreso académico específico</li>
                        <li>Generará automáticamente la justificación personalizada</li>
                        <li>Enviará la solicitud al coordinador del programa</li>
                        <li>Recibirás notificación del estado</li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-paper-plane"></i> Confirmar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal detalle de solicitud -->
<div class="modal fade" id="modalDetalleSolicitud" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-info-circle"></i> Detalle de mi Solicitud</h5>
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
        </div>
    </div>
</div>


<script>
// Función para solicitar certificación por programa
function solicitarCertificacionPrograma(tipo, nombre, idfic, nombrePrograma) {
// Verificar que el modal existe
    const modal = document.getElementById('modalConfirmarSolicitudPrograma');
    if (!modal) {
        console.error('Modal no encontrado');
        alert('Error: Modal de confirmación no disponible');
        return;
    }
    $('#tipoSolicitudPrograma').val(tipo);
    $('#idficSolicitud').val(idfic);
    $('#nombreCertificacionPrograma').text(nombre);
    $('#nombreProgramaSolicitud').text(nombrePrograma);
    $('#modalConfirmarSolicitudPrograma').modal('show');
}

// Función para descargar certificado
function descargarCertificado(idsolicitud) {
    window.open('home.php?pg=<?=$pg;?>&opera=descargar&id=' + idsolicitud, '_blank');
}

// Dibujar gráficos de progreso circulares
$(document).ready(function() {
    <?php foreach ($datProgramasAprendiz as $programa): ?>
        drawCircularProgress('progress-<?= $programa['idfic']; ?>', <?= $programa['porcentaje_avance']; ?>);
    <?php endforeach; ?>
});

function drawCircularProgress(canvasId, percentage) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const centerX = 60;
    const centerY = 60;
    const radius = 50;
    
    // Limpiar canvas
    ctx.clearRect(0, 0, 120, 120);
    
    // Fondo del círculo
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
    ctx.strokeStyle = '#e9ecef';
    ctx.lineWidth = 8;
    ctx.stroke();
    
    // Progreso
    ctx.beginPath();
    ctx.arc(centerX, centerY, radius, -Math.PI/2, (-Math.PI/2) + (2 * Math.PI * percentage / 100));
    ctx.strokeStyle = percentage >= 75 ? '#28a745' : (percentage >= 50 ? '#ffc107' : '#007bff');
    ctx.lineWidth = 8;
    ctx.lineCap = 'round';
    ctx.stroke();
}

// Funciones auxiliares para JavaScript
function getClaseEstadoJS(estado) {
    switch(estado) {
        case 'aprobada_coordinacion': return 'info';
        case 'completada': return 'success';
        case 'rechazada': return 'danger';
        case 'pendiente': return 'warning';
        default: return 'secondary';
    }
}

function getTextoEstadoJS(estado) {
    switch(estado) {
        case 'pendiente': return 'Pendiente';
        case 'aprobada_coordinacion': return 'Aprobada - Esperando Firma';
        case 'completada': return 'Completada';
        case 'rechazada': return 'Rechazada';
        default: return estado;
    }
}

// Función para ver detalle de solicitud
function verDetalleSolicitud(idsolicitud) {
    $('#contenidoDetalleSolicitud').html(`
        <div class="text-center">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p>Cargando...</p>
        </div>
    `);
    
    $('#modalDetalleSolicitud').modal('show');
    
    const solicitudes = <?= json_encode($datMisSolicitudes); ?>;
    const solicitud = solicitudes.find(s => s.id == idsolicitud);
    
    if (solicitud) {
        let html = `
            <div class="row">
                <div class="col-md-6">
                    <strong>ID Solicitud:</strong> ${solicitud.id}
                </div>
                <div class="col-md-6">
                    <strong>Tipo:</strong> ${solicitud.tipo_certificacion.replace('_', ' ')}
                </div>
                <div class="col-md-6">
                    <strong>Estado:</strong> 
                    <span class="badge bg-${getClaseEstadoJS(solicitud.estado)}">${getTextoEstadoJS(solicitud.estado)}</span>
                </div>
                <div class="col-md-6">
                    <strong>Fecha Solicitud:</strong> ${new Date(solicitud.fecha_solicitud).toLocaleDateString('es-ES')}
                </div>
                <div class="col-12 mt-3">
                    <strong>Justificación Automática:</strong><br>
                    <div class="alert alert-light border">
                        <i class="fa fa-robot text-primary"></i> 
                        <strong>Sistema Automático:</strong><br>
                        ${solicitud.motivo}
                    </div>
                </div>
            </div>
            
            ${solicitud.respuesta_coordinacion ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <strong>Respuesta de Coordinación:</strong><br>
                        <p class="border p-2 rounded bg-light">${solicitud.respuesta_coordinacion}</p>
                    </div>
                </div>
            ` : ''}
        `;
        
        $('#contenidoDetalleSolicitud').html(html);
    } else {
        $('#contenidoDetalleSolicitud').html('<div class="alert alert-danger">Error al cargar los datos</div>');
    }
}
 


</script>

<?php
function getBadgeColorPrograma($tipo) {
    switch (strtoupper($tipo)) {
        case 'TECNICO': return 'primary';
        case 'TECNOLOGO': return 'success';
        case 'CURSO': case 'COMPLEMENTARIA': return 'info';
        default: return 'secondary';
    }
}

function getEstadoPrograma($estado) {
    switch ($estado) {
        case 'activo': return 'success';
        case 'terminado': return 'primary';
        case 'inactivo': return 'secondary';
        default: return 'warning';
    }
}


function formatearTipoCertificacion($tipo) {
    $tipos = [
        'certificacion_inicio' => 'Inicio de Formación',
        'certificacion_parcial' => 'Competencias Parciales',
        'certificacion_etapa_productiva' => 'Etapa Productiva',
        'certificacion_tecnica_completa' => 'Técnica Completa',
        'certificacion_tecnologica_completa' => 'Tecnológica Completa',
        'certificacion_curso_inicio' => 'Participación en Curso',
        'certificacion_curso_intermedio' => 'Progreso en Curso',
        'certificacion_curso_completo' => 'Finalización de Curso',
        'constancia_estudiante_activo' => 'Constancia de Estudiante'
    ];
    
    return $tipos[$tipo] ?? ucfirst(str_replace('_', ' ', $tipo));
}

function getIconoCertificacion($tipo) {
    $iconos = [
        'certificacion_inicio' => 'fa-star',
        'certificacion_parcial' => 'fa-certificate',
        'certificacion_etapa_productiva' => 'fa-industry',
        'certificacion_tecnica_completa' => 'fa-cogs',
        'certificacion_tecnologica_completa' => 'fa-laptop',
        'certificacion_curso_inicio' => 'fa-book',
        'certificacion_curso_intermedio' => 'fa-bookmark',
        'certificacion_curso_completo' => 'fa-graduation-cap',
        'constancia_estudiante_activo' => 'fa-id-card'
    ];
    
    return $iconos[$tipo] ?? 'fa-certificate';
}

function getClaseEstadoSolicitud($estado) {
    switch ($estado) {
        case 'aprobada_coordinacion': return 'info';
        case 'completada': return 'success';
        case 'rechazada': return 'danger';
        case 'pendiente': return 'warning';
        default: return 'secondary';
    }
}

function getTextoEstado($estado) {
    switch ($estado) {
        case 'pendiente': return 'Pendiente';
        case 'aprobada_coordinacion': return 'Aprobada - Esp. Firma';
        case 'completada': return 'Completada';
        case 'rechazada': return 'Rechazada';
        default: return ucfirst($estado);
    }
}


?>

