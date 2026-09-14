<?php

/**
 * Vista para Carga Masiva de Usuarios SAGI
 * 
 * Interfaz de usuario moderna y responsiva para carga masiva de usuarios
 * Incluye validaciones client-side y mejor experiencia de usuario
 * 
 * @author Felipe Gutierrez- Aprendiz SENA
 * @version 2.0.0
 * @since 2025
 * @package SAGI
 */

require_once("controllers/ccmausu.php")
?>

<!-- Estilos CSS personalizados -->
<style>
    .carga-masiva-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin: 20px 0;
    }
    
    .upload-zone {
        border: 2px dashedrgb(13, 207, 23);
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        background: rgba(0,123,255,0.05);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .upload-zone:hover {
        border-color:rgb(17, 224, 28);
        background: rgba(0,123,255,0.1);
        transform: translateY(-2px);
    }
    
    .upload-zone.dragover {
        border-color: #28a745;
        background: rgba(40,167,69,0.1);
    }
    
    .file-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        display: none;
    }
    
    .progress-container {
        display: none;
        margin-top: 20px;
    }
    
    .btn-action {
        min-width: 140px;
        height: 45px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .alert-custom {
        border-radius: 10px;
        border-left: 4px solid;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .detalles-procesamiento {
        max-height: 400px;
        overflow-y: auto;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
    }
    
    .stats-container {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        flex-wrap: wrap;
    }
    
    .stat-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex: 1;
        min-width: 120px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 1.8em;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9em;
    }
</style>

<!-- Mensajes del sistema -->
<?php if (!empty($mensaje)): ?>
    <div class="alert alert-<?= $tipoMensaje; ?> alert-dismissible fade show alert-custom" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-<?= $tipoMensaje === 'success' ? 'check-circle' : ($tipoMensaje === 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2" style="font-size: 1.2em;"></i>
            <div class="flex-grow-1">
                <strong><?= $mensaje; ?></strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        
        <!-- Estadísticas de procesamiento -->
        <?php if (!empty($detalles) && $tipoMensaje !== 'danger'): ?>
            <hr>
            <div class="stats-container">
                <?php
                $exitosos = count(array_filter($detalles, function($d) { return strpos($d, 'procesado exitosamente') !== false || strpos($d, 'Procesado correctamente') !== false; }));
                $errores = count(array_filter($detalles, function($d) { return strpos($d, 'Error') !== false; }));
                $omitidos = count(array_filter($detalles, function($d) { return strpos($d, 'omitido') !== false || strpos($d, 'no permitido') !== false; }));
                $duplicados = count(array_filter($detalles, function($d) { return strpos($d, 'duplicado') !== false; }));
                ?>
                
                <?php if ($exitosos > 0): ?>
                    <div class="stat-card">
                        <div class="stat-number text-success"><?= $exitosos; ?></div>
                        <div class="stat-label">Exitosos</div>
                    </div>
                <?php endif; ?>
                
                <?php if ($errores > 0): ?>
                    <div class="stat-card">
                        <div class="stat-number text-danger"><?= $errores; ?></div>
                        <div class="stat-label">Errores</div>
                    </div>
                <?php endif; ?>
                
                <?php if ($omitidos > 0): ?>
                    <div class="stat-card">
                        <div class="stat-number text-warning"><?= $omitidos; ?></div>
                        <div class="stat-label">Omitidos</div>
                    </div>
                <?php endif; ?>
                
                <?php if ($duplicados > 0): ?>
                    <div class="stat-card">
                        <div class="stat-number text-info"><?= $duplicados; ?></div>
                        <div class="stat-label">Duplicados</div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Botón para mostrar/ocultar detalles -->
            <div class="mt-3">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleDetalles()">
                    <i class="fas fa-list-ul"></i> <span id="detalles-text">Mostrar Detalles</span>
                </button>
            </div>
            
            <!-- Detalles del procesamiento (inicialmente ocultos) -->
            <div id="detalles-procesamiento" class="detalles-procesamiento" style="display: none;">
                <strong><i class="fas fa-terminal"></i> Log detallado del procesamiento:</strong>
                <div class="mt-2">
                    <?php foreach ($detalles as $index => $detalle): ?>
                        <div class="log-line <?= strpos($detalle, 'Error') !== false ? 'text-danger' : (strpos($detalle, 'exitosamente') !== false ? 'text-success' : 'text-muted'); ?>">
                            <small class="text-muted">[<?= str_pad($index + 1, 3, '0', STR_PAD_LEFT); ?>]</small> 
                            <?= htmlspecialchars($detalle); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Contenedor principal de carga masiva -->
<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Carga Masiva de Usuarios SAGI", 2); ?>
    
    <div class="carga-masiva-container">
        <form id="form-carga-masiva" action="home.php?pg=<?=$pg;?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Zona de carga de archivo -->
                <div class="col-md-8">
                    <label class="form-label fw-bold">
                        <i class="fas fa-file-excel text-success"></i> Archivo Excel de Usuarios *
                    </label>
                    
                    <div class="upload-zone" onclick="document.getElementById('archivo_excel').click()">
                        <div id="upload-content">
                            <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 3em; margin-bottom: 15px;"></i>
                            <h5 class="text-primary">Seleccionar archivo Excel</h5>
                            <p class="text-muted mb-2">Haz clic aquí o arrastra tu archivo</p>
                            <small class="text-muted">
                                <strong>Formatos permitidos:</strong> .xlsx, .xls<br>
                                <strong>Tamaño máximo:</strong> 15MB
                            </small>
                        </div>
                        
                        <input type="file" name="archivo_excel" id="archivo_excel" 
                               style="display: none;" accept=".xlsx,.xls" required>
                    </div>
                    
                    <!-- Información del archivo seleccionado -->
                    <div id="file-info" class="file-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-excel text-success me-2" style="font-size: 1.5em;"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold" id="file-name"></div>
                                <small class="text-muted" id="file-size"></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Barra de progreso -->
                    <div id="progress-container" class="progress-container">
                        <div class="progress">
                            <div id="upload-progress" class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-1">Procesando archivo...</small>
                    </div>
                </div>
                
                <!-- Panel de acciones -->
                <div class="col-md-4">
                    <div class="d-grid gap-3">
                        <!-- Botón de carga masiva -->
                        <div>
                            <button type="submit" class="btn btn-primary btn-action w-100" id="btn-cargar">
                                <i class="fas fa-upload me-2"></i>
                                <span id="btn-text">Procesar Usuarios</span>
                            </button>
                            <input type="hidden" name="operacion" value="carga_masiva_usuarios">
                        </div>
                        
                        <!-- Botón de validación -->
                        <div>
                            <button type="button" class="btn btn-outline-info btn-action w-100" onclick="validarPlantilla()">
                                <i class="fas fa-check-circle me-2"></i>
                                Validar Plantilla
                            </button>
                        </div>
                        
                        <!-- Descargar plantilla -->
                        <div>
                            <a href="EXCEL/fcmausu.xls" class="btn btn-secondary btn-action w-100" download>
                                <i class="fas fa-download me-2"></i>
                                Descargar Plantilla
                            </a>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="mt-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-info-circle text-info mb-2" style="font-size: 2em;"></i>
                                    <h6 class="card-title">Estados Permitidos</h6>
                                    <p class="card-text small">
                                        <?= implode(' • ', $estadosPermitidos ?? ['matriculado', 'inscrito', 'activo']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript para funcionalidad interactiva -->
<script>
// Variables globales
const maxFileSize = <?= $maxTamanoArchivo ?? 15728640; ?>; // 15MB por defecto
const allowedExtensions = <?= json_encode($extensionesPermitidas ?? ['xlsx', 'xls']); ?>;

// Elementos del DOM
const fileInput = document.getElementById('archivo_excel');
const uploadZone = document.querySelector('.upload-zone');
const fileInfo = document.getElementById('file-info');
const uploadContent = document.getElementById('upload-content');
const progressContainer = document.getElementById('progress-container');
const form = document.getElementById('form-carga-masiva');
const btnCargar = document.getElementById('btn-cargar');
const btnText = document.getElementById('btn-text');

// Event listeners para drag & drop
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadZone.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

['dragenter', 'dragover'].forEach(eventName => {
    uploadZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadZone.addEventListener(eventName, unhighlight, false);
});

uploadZone.addEventListener('drop', handleDrop, false);

// Event listener para selección de archivo
fileInput.addEventListener('change', function(e) {
    handleFiles(e.target.files);
});

// Event listener para envío del formulario
form.addEventListener('submit', function(e) {
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Por favor selecciona un archivo Excel');
        return;
    }
    
    showProgress();
});

// Funciones para drag & drop
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    uploadZone.classList.add('dragover');
}

function unhighlight(e) {
    uploadZone.classList.remove('dragover');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
}

// Función para manejar archivos seleccionados
function handleFiles(files) {
    if (files.length === 0) return;
    
    const file = files[0];
    
    // Validar extensión
    const extension = file.name.split('.').pop().toLowerCase();
    if (!allowedExtensions.includes(extension)) {
        alert(Formato no permitido. Solo se aceptan archivos: ${allowedExtensions.join(', ')}'});
        return;
    }
    
    // Validar tamaño
    if (file.size > maxFileSize) {
        const maxSizeMB = Math.round(maxFileSize / (1024 * 1024));
        alert(El archivo excede el tamaño máximo permitido (${maxSizeMB}MB));
        return;
    }
    
    // Asignar archivo al input
    const dt = new DataTransfer();
    dt.items.add(file);
    fileInput.files = dt.files;
    
    // Mostrar información del archivo
    showFileInfo(file);
}

// Función para mostrar información del archivo
function showFileInfo(file) {
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = formatFileSize(file.size);
    
    uploadContent.style.display = 'none';
    fileInfo.style.display = 'block';
    
    uploadZone.style.borderColor = '#28a745';
    uploadZone.style.backgroundColor = 'rgba(40,167,69,0.1)';
}

// Función para limpiar archivo seleccionado
function clearFile() {
    fileInput.value = '';
    fileInfo.style.display = 'none';
    uploadContent.style.display = 'block';
    
    uploadZone.style.borderColor = '#007bff';
    uploadZone.style.backgroundColor = 'rgba(0,123,255,0.05)';
}

// Función para mostrar progreso
function showProgress() {
    btnCargar.disabled = true;
    btnText.textContent = 'Procesando...';
    progressContainer.style.display = 'block';
    
    // Simular progreso
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        
        document.getElementById('upload-progress').style.width = progress + '%';
        
        if (progress >= 90) {
            clearInterval(interval);
        }
    }, 200);
}

// Función para validar plantilla
function validarPlantilla() {
    if (!fileInput.files.length) {
        alert('Por favor selecciona un archivo Excel para validar');
        return;
    }
    
    // Cambiar operación y enviar formulario
    const operacionInput = document.querySelector('input[name="operacion"]');
    const operacionOriginal = operacionInput.value;
    operacionInput.value = 'validar_plantilla';
    
    // Crear formulario temporal para validación
    const tempForm = form.cloneNode(true);
    tempForm.style.display = 'none';
    document.body.appendChild(tempForm);
    tempForm.submit();
    
    // Restaurar operación original
    operacionInput.value = operacionOriginal;
}

// Función para mostrar/ocultar detalles
function toggleDetalles() {
    const detalles = document.getElementById('detalles-procesamiento');
    const text = document.getElementById('detalles-text');
    
    if (detalles.style.display === 'none') {
        detalles.style.display = 'block';
        text.textContent = 'Ocultar Detalles';
    } else {
        detalles.style.display = 'none';
        text.textContent = 'Mostrar Detalles';
    }
}

// Función para formatear tamaño de archivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Auto-ocultar alertas después de 10 segundos (solo para success)
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 10000);
    });
});
</script>