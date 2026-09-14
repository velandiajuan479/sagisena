<?php require_once 'controllers/ccerticor.php'; ?>

<style>

/* Cards de Estadísticas SENA */
.sena-stats-container {
    margin-bottom: 2rem;
    width: 100%;
}

.sena-stats-container .row {
    margin-left: 0;
    margin-right: 0;
}



.sena-stat-card {
    background: white;
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 40  px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid #108b22ff;
    margin-bottom: 2rem;
}

.sena-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}

.sena-stat-card .card-body {
    padding: 1.5rem;
    text-align: center;
}

.sena-stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0.5rem 0;
    color: #1b365d;
}

.sena-stat-label {
    color: #6c757d;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sena-stat-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #2c5282;
}

/* Tabla Principal SENA */
.sena-table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.sena-table-header {
    background: #1b365d;
    color: white;
    padding: 1.5rem;
    border-bottom: none;
}

.sena-table-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.2rem;
}

.sena-table {
    margin: 0;
    font-size: 0.9rem;
}

.sena-table thead th {
    background-color: #f8f9fa;
    border: none;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.3px;
}

.sena-table tbody td {
    padding: 1rem 0.75rem;
    border: none;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.sena-table tbody tr:hover {
    background-color: rgba(27, 54, 93, 0.02);
}

/* Estados de Solicitudes */
.estado-pendiente {
    background-color: rgba(255, 193, 7, 0.1);
    border-left: 3px solid #ffc107;
}

.estado-aprobada {
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 3px solid #28a745;
}

.estado-rechazada {
    background-color: rgba(220, 53, 69, 0.1);
    border-left: 3px solid #dc3545;
}

/* Badges SENA */
.sena-badge {
    padding: 0.5rem 0.8rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.sena-badge-pendiente {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.sena-badge-aprobada {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #a3d977;
}

.sena-badge-rechazada {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f1aeb5;
}

.sena-badge-tecnico {
    background-color: #cce5ff;
    color: #0056b3;
    border: 1px solid #99ccff;
}

.sena-badge-tecnologo {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #b3d7dd;
}

/* Botones SENA */
.sena-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 400;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.sena-btn-primary {
    background-color: #1b365d;
    color: white;
}

.sena-btn-primary:hover {
    background-color: #2c5282;
    color: white;
}

.sena-btn-success {
    background-color: #28a745;
    color: white;
}

.sena-btn-success:hover {
    background-color: #218838;
    color: white;
}

.sena-btn-danger {
    background-color: #dc3545;
    color: white;
}

.sena-btn-danger:hover {
    background-color: #c82333;
    color: white;
}

.sena-btn-info {
    background-color: #17a2b8;
    color: white;
}

.sena-btn-info:hover {
    background-color: #138496;
    color: white;
}

/* Información del Estudiante */
.estudiante-info {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 6px;
    border-left: 3px solid #1b365d;
}

.estudiante-nombre {
    font-weight: 600;
    color: #1b365d;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.estudiante-documento {
    color: #6c757d;
    font-size: 0.85rem;
    font-family: 'Courier New', monospace;
}

/* Información del Programa */
.programa-info {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 6px;
    border-left: 3px solid #2c5282;
}

.programa-nombre {
    font-weight: 600;
    color: #2c5282;
    font-size: 0.9rem;
    line-height: 1.3;
    margin-bottom: 0.5rem;
}

.programa-detalles {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.ficha-codigo {
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Progreso */
.progreso-container {
    text-align: center;
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 6px;
}

.progreso-item {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
    font-weight: 600;
}

.progreso-trimestre {
    color: #1b365d;
}

.progreso-avance {
    color: #28a745;
}

.progreso-bitacoras {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Tipo de Certificación */
.certificacion-tipo {
    background: #e3f2fd;
    color: #1565c0;
    padding: 0.5rem;
    border-radius: 6px;
    text-align: center;
    font-size: 0.8rem;
    font-weight: 600;
    line-height: 1.2;
}

/* Modals SENA */
.sena-modal .modal-header {
    background: #1b365d;
    color: white;
    border-bottom: none;
}

.sena-modal .modal-footer {
    border-top: 1px solid #dee2e6;
    background: #f8f9fa;
}

/* Alerts SENA */
.sena-alert {
    border: none;
    border-radius: 6px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
}

.sena-alert-success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.sena-alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.sena-alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

.sena-alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

/* Responsive */
@media (max-width: 768px) {
    .sena-title {
        font-size: 1.5rem;
    }
    
    .sena-stat-number {
        font-size: 2rem;
    }
    
    .sena-table {
        font-size: 0.8rem;
    }
    
    .programa-detalles {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Loading States */
.sena-loading {
    opacity: 0.6;
    pointer-events: none;
}

.sena-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #1b365d;
    border-radius: 50%;
    animation: sena-spin 1s linear infinite;
}

@keyframes sena-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}


/* Botones de Acciones - Más Compactos */
.sena-btn-sm {
    padding: 0.2rem 0.5rem !important;
    font-size: 0.7rem !important;
    white-space: nowrap;
    min-width: auto !important;
}

.sena-btn-sm i {
    font-size: 0.75rem;
}

.acciones-container {
    display: flex;
    gap: 0.3rem;
    justify-content: flex-start;
    flex-wrap: nowrap;
    min-width: 200px;
}

/* Contenedor de tabla - Ancho completo */
.sena-table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    overflow-x: auto;
    margin-bottom: 2rem;
    width: 100%;
    max-width: 200%;
}

/* Tabla - Usar todo el ancho */
.sena-table {
    margin: 0;
    font-size: 0.85rem;
    width: 100%;
    table-layout: fixed; /* Importante para distribuir anchos */
}

/* Ajustar anchos proporcionales de columnas */
.sena-table thead th {
    background-color: #f8f9fa;
    border: none;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.3px;
}

/* Asegurar que el contenedor principal use todo el espacio */
.sena-container {
    width: 100%;
    max-width: 100%;
}

</style>

<div class="sena-container">
     <?php echo titulo2("<i class='".$icono."'></i> Gestión de Certificaciones - Coordinación", 0); ?>


    </div>

    <div class="container-fluid" style="padding-left: 50px; padding-right: 20px;">
        <!-- Mostrar mensajes -->
        <?php if (!empty($mensaje)): ?>
            <div class="sena-alert sena-alert-<?= $tipoMensaje === 'error' ? 'danger' : $tipoMensaje; ?>">
                <i class="fa fa-<?= $tipoMensaje === 'success' ? 'check-circle' : ($tipoMensaje === 'error' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
                <?= htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="row sena-stats-container">
            <div class="col-xl-3 col-md-6">
                <div class="card sena-stat-card">
                    <div class="card-body">
                        <div class="sena-stat-icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div class="sena-stat-number text-warning">
                            <?= $datEstadisticas['pendientes'] ?? 0; ?>
                        </div>
                        <div class="sena-stat-label">
                            Solicitudes Pendientes
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card sena-stat-card">
                    <div class="card-body">
                        <div class="sena-stat-icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="sena-stat-number text-success">
                            <?= $datEstadisticas['aprobadas'] ?? 0; ?>
                        </div>
                        <div class="sena-stat-label">
                            Aprobadas
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card sena-stat-card">
                    <div class="card-body">
                        <div class="sena-stat-icon">
                            <i class="fa fa-times-circle"></i>
                        </div>
                        <div class="sena-stat-number text-danger">
                            <?= $datEstadisticas['rechazadas'] ?? 0; ?>
                        </div>
                        <div class="sena-stat-label">
                            Rechazadas
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card sena-stat-card">
                    <div class="card-body">
                        <div class="sena-stat-icon">
                            <i class="fa fa-list-alt"></i>
                        </div>
                        <div class="sena-stat-number">
                            <?= $datEstadisticas['total'] ?? 0; ?>
                        </div>
                        <div class="sena-stat-label">
                            Total Solicitudes
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Solicitudes -->
        <div class="sena-table-container">
            <div class="sena-table-header">
                <h5>
                    <i class="fa fa-list me-2"></i>
                    Gestión de Solicitudes de Certificación
                </h5>
            </div>
            
            <?php if (!empty($datSolicitudes)): ?>
                <div class="table-responsive">
                    <table class="table sena-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 200px;">Estudiante</th>
                                <th style="width: 280px;">Programa</th>
                                <th style="width: 180px;">Certificación</th>
                                <th style="width: 120px;">Estado</th>
                                <th style="width: 110px;">Progreso</th>
                                <th style="width: 90px;">Fecha</th>
                                <th style="width: 260px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datSolicitudes as $solicitud): ?>
                                <tr class="<?= 
                                    ($solicitud['estado'] ?? '') === 'pendiente' ? 'estado-pendiente' : 
                                    (($solicitud['estado'] ?? '') === 'aprobada_coordinacion' ? 'estado-aprobada' : 
                                    (($solicitud['estado'] ?? '') === 'rechazada' ? 'estado-rechazada' : '')) 
                                ?>">
                                    <td>
                                        <code style="font-weight: bold; color: #1b365d;">
                                            <?= $solicitud['idsolicitud'] ?? 'N/A'; ?>
                                        </code>
                                    </td>
                                    
                                    <td>
                                        <div class="estudiante-info">
                                            <div class="estudiante-nombre">
                                                <?= htmlspecialchars($solicitud['nomusu'] ?? 'N/A'); ?>
                                            </div>
                                            <div class="estudiante-documento">
                                                CC: <?= htmlspecialchars($solicitud['ndocusu'] ?? 'N/A'); ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="programa-info">
                                            <div class="programa-nombre">
                                                <?= htmlspecialchars($solicitud['nombre_programa'] ?? 'Sin programa'); ?>
                                            </div>
                                            <div class="programa-detalles">
                                                <span class="sena-badge sena-badge-<?= strtolower($solicitud['tipo_programa'] ?? 'tecnico'); ?>">
                                                    <?= strtoupper($solicitud['tipo_programa'] ?? 'N/A'); ?>
                                                </span>
                                                <span class="ficha-codigo">
                                                    FICHA: <?= $solicitud['codigo_ficha'] ?? $solicitud['idfic'] ?? 'N/A'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="certificacion-tipo">
                                            <?= formatearTipoCertificacion($solicitud['tipo_certificacion'] ?? ''); ?>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="sena-badge sena-badge-<?= 
                                            ($solicitud['estado'] ?? '') === 'pendiente' ? 'pendiente' : 
                                            (($solicitud['estado'] ?? '') === 'aprobada_coordinacion' ? 'aprobada' : 'rechazada') 
                                        ?>">
                                            <?= getTextoEstado($solicitud['estado'] ?? ''); ?>
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div class="progreso-container">
                                            <div class="progreso-item progreso-trimestre">
                                                TRIM: <?= ($solicitud['trimestre_actual'] ?? 0); ?>/<?= ($solicitud['duracion_trimestres'] ?? 0) ?: 'N/A'; ?>
                                            </div>
                                            <div class="progreso-item progreso-avance">
                                                AVANCE: <?= round($solicitud['porcentaje_avance'] ?? 0); ?>%
                                            </div>
                                            <div class="progreso-item progreso-bitacoras">
                                                BIT: <?= ($solicitud['bitacoras_completas'] ?? 0); ?>/<?= ($solicitud['total_bitacoras'] ?? 0); ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <small style="font-family: 'Courier New', monospace; color: #6c757d;">
                                            <?= ($solicitud['fecha_solicitud'] ?? false) ? date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) : 'N/A'; ?>
                                        </small>
                                    </td>
                                    
                                    <td>
                                        <div class="acciones-container">
                                            <button type="button" 
                                                    class="btn sena-btn sena-btn-info sena-btn-sm" 
                                                    onclick="verDetalleSolicitud(<?= $solicitud['idsolicitud'] ?? 0; ?>)"
                                                    title="Ver detalle">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            
                                            <?php if (($solicitud['estado'] ?? '') === 'pendiente'): ?>
                                                <button type="button" 
                                                        class="btn sena-btn sena-btn-success sena-btn-sm" 
                                                        onclick="procesarSolicitud(<?= $solicitud['idsolicitud'] ?? 0; ?>, 'aprobar')"
                                                        title="Aprobar solicitud">
                                                    <i class="fa fa-check"></i> Aprobar
                                                </button>
                                                
                                                <button type="button" 
                                                        class="btn sena-btn sena-btn-danger sena-btn-sm" 
                                                        onclick="procesarSolicitud(<?= $solicitud['idsolicitud'] ?? 0; ?>, 'rechazar')"
                                                        title="Rechazar solicitud">
                                                    <i class="fa fa-times"></i> Rechazar
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Ya procesada</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay solicitudes disponibles</h5>
                    <p class="text-muted">No se encontraron solicitudes de certificación en el sistema</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de detalle de solicitud -->
<div class="modal fade sena-modal" id="modalDetalleSolicitud" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-info-circle me-2"></i>Detalle de Solicitud #<span id="idSolicitudModal">-</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleSolicitud">
                <div class="text-center py-4">
                    <div class="sena-loading">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">Cargando información...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de procesamiento de solicitud -->
<div class="modal fade sena-modal" id="modalProcesarSolicitud" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="headerProcesar">
                <h5 class="modal-title">
                    <i class="fa fa-cog me-2"></i>Procesar Solicitud
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="home.php?pg=<?= $pg; ?>" id="formProcesarSolicitud">
                <input type="hidden" name="opera" value="procesar_solicitud">
                <input type="hidden" name="idsolicitud" id="idsolicitudProcesar">
                <input type="hidden" name="accion" id="accionProcesar">
                
                <div class="modal-body">
                    <div class="sena-alert sena-alert-info" id="alertaInfo">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Información:</strong>
                        <span id="textoInfo">Cargando...</span>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label fw-bold">
                            <i class="fa fa-comment me-1"></i>Observaciones del Coordinador:
                        </label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="4"
                                  placeholder="Escriba las observaciones para el aprendiz..."></textarea>
                        <small class="form-text text-muted">
                            Estas observaciones serán visibles para el aprendiz y quedarán registradas en el sistema.
                        </small>
                    </div>
                    
                    <div id="informacionEstudiante" class="border rounded p-3" style="background-color: #f8f9fa;">
                        <!-- Se cargará dinámicamente -->
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn sena-btn sena-btn-primary" id="btnConfirmarProceso">
                        <i class="fa fa-check me-1"></i>Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript simplificado y funcional
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema SENA iniciado correctamente');
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Ver detalle de solicitud
function verDetalleSolicitud(idsolicitud) {
    if (!idsolicitud || idsolicitud === 0) {
        alert('Error: ID de solicitud no válido');
        return;
    }
    
    console.log('Viendo detalle de solicitud:', idsolicitud);
    
    document.getElementById('idSolicitudModal').textContent = idsolicitud;
    document.getElementById('contenidoDetalleSolicitud').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Obteniendo información de la solicitud...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('modalDetalleSolicitud'));
    modal.show();
    
    // Obtener detalles
    const currentUrl = new URL(window.location.href);
    const pg = currentUrl.searchParams.get('pg') || '<?= $pg ?>';
    const fetchUrl = `home.php?pg=${pg}&opera=obtener_detalle&id=${idsolicitud}`;
    
    fetch(fetchUrl)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarDetalleSolicitud(data.solicitud, data.validacion);
        } else {
            document.getElementById('contenidoDetalleSolicitud').innerHTML = 
                `<div class="sena-alert sena-alert-danger">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> ${data.message || 'Error desconocido'}
                </div>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('contenidoDetalleSolicitud').innerHTML = 
            `<div class="sena-alert sena-alert-danger">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <strong>Error de conexión:</strong> No se pudo cargar la información
            </div>`;
    });
}

// Mostrar detalle completo
function mostrarDetalleSolicitud(solicitud, validacion) {
    if (!solicitud) {
        document.getElementById('contenidoDetalleSolicitud').innerHTML = 
            '<div class="sena-alert sena-alert-danger">Error: No se recibieron datos de la solicitud</div>';
        return;
    }
    
    const fechaInicio = solicitud.fecha_inicio_programa ? 
        new Date(solicitud.fecha_inicio_programa).toLocaleDateString('es-ES') : 'No disponible';
    const fechaSolicitud = solicitud.fecha_solicitud ? 
        new Date(solicitud.fecha_solicitud).toLocaleDateString('es-ES') : 'No disponible';
    
    const html = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary"><i class="fa fa-user me-2"></i>Información del Estudiante</h6>
                <div class="estudiante-info mb-3">
                    <p class="mb-2"><strong>Nombre:</strong> ${solicitud.nomusu || 'N/A'}</p>
                    <p class="mb-2"><strong>Documento:</strong> ${solicitud.ndocusu || 'N/A'}</p>
                    <p class="mb-2"><strong>Email:</strong> ${solicitud.emausu || 'N/A'}</p>
                    <p class="mb-0"><strong>Teléfono:</strong> ${solicitud.telcan || 'N/A'}</p>
                </div>
                
                <h6 class="text-success"><i class="fa fa-graduation-cap me-2"></i>Información del Programa</h6>
                <div class="programa-info mb-3">
                    <p class="mb-2"><strong>Programa:</strong> ${solicitud.nombre_programa || 'Sin programa'}</p>
                    <p class="mb-2"><strong>Tipo:</strong> 
                        <span class="sena-badge sena-badge-${(solicitud.tipo_programa || 'tecnico').toLowerCase()}">${(solicitud.tipo_programa || 'N/A').toUpperCase()}</span>
                    </p>
                    <p class="mb-2"><strong>Ficha:</strong> ${solicitud.codigo_ficha || solicitud.idfic || 'N/A'}</p>
                    <p class="mb-0"><strong>Fecha Inicio:</strong> ${fechaInicio}</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-warning"><i class="fa fa-certificate me-2"></i>Información de la Certificación</h6>
                <div class="certificacion-tipo mb-3" style="text-align: left; padding: 1rem;">
                    <p class="mb-2"><strong>Tipo:</strong> ${formatearTipoCertificacion(solicitud.tipo_certificacion)}</p>
                    <p class="mb-2"><strong>Fecha Solicitud:</strong> ${fechaSolicitud}</p>
                    <p class="mb-0"><strong>Estado:</strong> 
                        <span class="sena-badge sena-badge-${getClaseEstado(solicitud.estado)}">${getTextoEstado(solicitud.estado)}</span>
                    </p>
                </div>
                
                <h6 class="text-info"><i class="fa fa-chart-bar me-2"></i>Progreso Académico</h6>
                <div class="progreso-container" style="text-align: left;">
                    <p class="mb-2"><strong>Trimestre:</strong> ${(validacion && validacion.analisis) ? validacion.analisis.trimestre_actual : 0}/${(validacion && validacion.analisis) ? validacion.analisis.duracion_total : 'N/A'}</p>
                    <p class="mb-2"><strong>Avance:</strong> ${(validacion && validacion.analisis) ? validacion.analisis.porcentaje_avance : 0}%</p>
                    <p class="mb-2"><strong>Días Formación:</strong> ${(validacion && validacion.analisis) ? validacion.analisis.dias_formacion : 0}</p>
                    <p class="mb-0"><strong>Bitácoras:</strong> ${(validacion && validacion.analisis) ? validacion.analisis.bitacoras_completas : 0}/${(validacion && validacion.analisis) ? validacion.analisis.total_bitacoras : 0}</p>
                </div>
            </div>
        </div>
        
        <h6 class="text-primary mt-3"><i class="fa fa-comment me-2"></i>Justificación del Estudiante</h6>
        <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
            <p class="mb-0">${solicitud.motivo || 'Sin motivo especificado'}</p>
        </div>
        
        ${solicitud.respuesta_coordinacion ? `
            <h6 class="text-secondary"><i class="fa fa-reply me-2"></i>Respuesta del Coordinador</h6>
            <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
                <p class="mb-2">${solicitud.respuesta_coordinacion}</p>
                <small class="text-muted">
                    <i class="fa fa-calendar me-1"></i>
                    Fecha: ${solicitud.fecha_respuesta_coordinacion ? 
                        new Date(solicitud.fecha_respuesta_coordinacion).toLocaleDateString('es-ES') : 
                        'No disponible'}
                </small>
            </div>
        ` : ''}
        
        ${solicitud.estado === 'pendiente' ? `
            <div class="text-center mt-4">
                <button type="button" class="btn sena-btn sena-btn-success me-2" onclick="procesarSolicitudDesdeModal(${solicitud.idsolicitud}, 'aprobar')">
                    <i class="fa fa-check me-1"></i>APROBAR SOLICITUD
                </button>
                <button type="button" class="btn sena-btn sena-btn-danger" onclick="procesarSolicitudDesdeModal(${solicitud.idsolicitud}, 'rechazar')">
                    <i class="fa fa-times me-1"></i>RECHAZAR SOLICITUD
                </button>
            </div>
        ` : ''}
    `;
    
    document.getElementById('contenidoDetalleSolicitud').innerHTML = html;
}

// Reemplazar la función de procesamiento en el script
function procesarSolicitud(idsolicitud, tipo) {
    if (!idsolicitud || !tipo) {
        alert('❌ Error: Datos inválidos');
        return;
    }
    
    console.log('Procesando solicitud:', idsolicitud, tipo);
    
    // Configurar modal
    document.getElementById('idsolicitudProcesar').value = idsolicitud;
    document.getElementById('accionProcesar').value = tipo;
    
    const esAprobacion = tipo === 'aprobar';
    const headerColor = esAprobacion ? 'bg-success' : 'bg-danger';
    const headerTexto = esAprobacion ? 'APROBAR' : 'RECHAZAR';
    const headerIcon = esAprobacion ? 'fa-check' : 'fa-times';
    
    // Configurar header
    const header = document.getElementById('headerProcesar');
    header.className = `modal-header text-white ${headerColor}`;
    header.innerHTML = `
        <h5 class="modal-title">
            <i class="fa ${headerIcon} me-2"></i>${headerTexto} Solicitud #${idsolicitud}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    `;
    
    // Configurar información
    document.getElementById('textoInfo').innerHTML = 
        `Se procederá a <strong>${headerTexto.toLowerCase()}</strong> la solicitud de certificación.`;
    
    // Configurar botón
    const btnClass = esAprobacion ? 'sena-btn-success' : 'sena-btn-danger';
    const btnConfirmar = document.getElementById('btnConfirmarProceso');
    btnConfirmar.className = `btn sena-btn ${btnClass}`;
    btnConfirmar.innerHTML = `<i class="fa ${headerIcon} me-1"></i>Confirmar ${headerTexto}`;
    
    // Limpiar y enfocar observaciones
    document.getElementById('observaciones').value = '';
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modalProcesarSolicitud'));
    modal.show();
    
    // Enfocar en el textarea después de mostrar el modal
    setTimeout(() => {
        document.getElementById('observaciones').focus();
    }, 500);
}

// Procesar desde modal de detalle
function procesarSolicitudDesdeModal(idsolicitud, tipo) {
    const modalDetalle = bootstrap.Modal.getInstance(document.getElementById('modalDetalleSolicitud'));
    if (modalDetalle) {
        modalDetalle.hide();
    }
    
    setTimeout(() => {
        procesarSolicitud(idsolicitud, tipo);
    }, 300);
}

// Funciones auxiliares
function getClaseEstado(estado) {
    switch ((estado || '').toLowerCase()) {
        case 'pendiente': return 'pendiente';
        case 'aprobada_coordinacion': return 'aprobada';
        case 'completada': return 'aprobada';
        case 'rechazada': return 'rechazada';
        default: return 'pendiente';
    }
}

function getTextoEstado(estado) {
    switch ((estado || '').toLowerCase()) {
        case 'pendiente': return 'Pendiente Revisión';
        case 'aprobada_coordinacion': return 'Aprobada - Esp. Firma';
        case 'completada': return 'Completada';
        case 'rechazada': return 'Rechazada';
        default: return 'Sin Estado';
    }
}

function formatearTipoCertificacion(tipo) {
    const tipos = {
        'certificacion_inicio': 'Inicio de Formación',
        'certificacion_parcial': 'Competencias Parciales',
        'certificacion_etapa_productiva': 'Etapa Productiva',
        'certificacion_tecnica_completa': 'Técnica Completa',
        'certificacion_tecnologica_completa': 'Tecnológica Completa',
        'certificacion_curso_inicio': 'Participación en Curso',
        'certificacion_curso_intermedio': 'Progreso en Curso',
        'certificacion_curso_completo': 'Finalización de Curso',
        'constancia_estudiante_activo': 'Constancia de Estudiante'
    };
    
    return tipos[tipo] || (tipo || 'Sin tipo').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

// Manejar envío del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formProcesarSolicitud');
    if (form) {
        form.addEventListener('submit', function(e) {
            const accion = document.getElementById('accionProcesar').value;
            const idsolicitud = document.getElementById('idsolicitudProcesar').value;
            
            if (!accion || !idsolicitud) {
                e.preventDefault();
                alert('Error: Faltan datos para procesar la solicitud');
                return;
            }
            
            const tipoTexto = accion === 'aprobar' ? 'aprobar' : 'rechazar';
            const confirmacion = confirm(`¿Está seguro de ${tipoTexto} la solicitud #${idsolicitud}?\n\nEsta acción quedará registrada en el sistema.`);
            
            if (!confirmacion) {
                e.preventDefault();
                return;
            }
            
            // Mostrar loading
            const submitBtn = e.target.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Procesando...';
                submitBtn.disabled = true;
            }
        });
    }
});

console.log('Sistema SENA de certificaciones cargado completamente');
</script>


?>