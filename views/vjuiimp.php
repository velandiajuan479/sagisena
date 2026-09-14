<?php
// Incluir el controlador para que las variables estén disponibles
require_once 'controllers/cjui.php';

// ============================================================================
// VISTA DE IMPORTACIÓN DE JUICIOS
// ============================================================================

// Verificar si hay datos procesados para mostrar vista previa
$hay_vista_previa = isset($_SESSION['procesamiento_exitoso']) && $_SESSION['procesamiento_exitoso'];
$hay_error = isset($_SESSION['error_procesamiento']);
?>

<!-- ============================================================================
     INTERFAZ DE IMPORTACIÓN DE JUICIOS
     ============================================================================ -->
     <div style="margin-top: 20px;"></div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background-color: #28a745; color: white; border: 2px solid #28a745;">
                    <h4 class="mb-0" style="color: white;">
                        <i class="fa-solid fa-upload"></i> 
                        Importación de Juicios Evaluativos
                    </h4>
                </div>
                <div class="card-body" style="background-color: white;">
                    
                    <?php if ($hay_vista_previa): ?>
                        <!-- PASO 1 COMPLETADO: VISTA PREVIA DE DATOS -->
                        <div id="paso1_completado" class="import-step">
                            <div class="alert" style="background-color: #d4edda; color: #155724; border: 2px solid #c3e6cb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                <h5 style="color: #155724;"><i class="fa-solid fa-check-circle"></i> ¡Conversión Excel a CSV Exitosa!</h5>
                                <p><strong>Archivo procesado:</strong> <?php echo htmlspecialchars($_SESSION['file_name'] ?? 'N/A'); ?></p>
                                <p><strong>Total de filas:</strong> <?php echo count($_SESSION['excel_data'] ?? []); ?></p>
                                <p><strong>Ficha destino:</strong> <?php echo htmlspecialchars($_SESSION['idfic_import'] ?? 'N/A'); ?></p>
                            </div>
                            
                            <!-- MARGEN SUPERIOR AGREGADO -->
                            <div style="margin-top: 40px;"></div>
                            
                            <!-- PREVIEW DE DATOS -->
                            <div class="card">
                                <div class="card-header" style="background-color: #28a745; color: white; border: 2px solid #28a745;">
                                    <h6 style="color: white;"><i class="fa-solid fa-eye"></i> Vista Previa de Datos (Primeras 10 filas)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead style="background-color: #f8f9fa;">
                                                <tr>
                                                    <?php 
                                                    if(isset($_SESSION['excel_data'][0])): 
                                                        foreach($_SESSION['excel_data'][0] as $index => $header): ?>
                                                            <th style="color: #333333;">Columna <?php echo $index + 1; ?></th>
                                                        <?php endforeach; 
                                                    endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $previewRows = array_slice($_SESSION['excel_data'] ?? [], 0, 10);
                                                foreach($previewRows as $row): ?>
                                                    <tr>
                                                        <?php foreach($row as $cell): ?>
                                                            <td style="color: #333333;"><?php echo htmlspecialchars(substr($cell, 0, 50)) . (strlen($cell) > 50 ? '...' : ''); ?></td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p style="color: #666666; margin-top: 0.5rem;">
                                        <small>Mostrando <?php echo count($previewRows); ?> de <?php echo count($_SESSION['excel_data'] ?? []); ?> filas totales</small>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- FORMULARIO PARA PASO 2 -->
                            <div class="mt-4">
                                <h5 style="color: #00af00;"><i class="fa-solid fa-database"></i> Paso 2: Confirmar e Importar a Base de Datos</h5>
                                <div class="alert" style="background-color: #fff3cd; color: #856404; border: 2px solid #ffeaa7; padding: 15px; border-radius: 8px;">
                                    <h6 style="color: #856404;"><i class="fa-solid fa-exclamation-triangle"></i> Confirmación de Importación</h6>
                                    <p class="mb-0" style="color: #856404;">¿Estás seguro de que quieres importar estos datos a la base de datos? Esta acción no se puede deshacer.</p>
                                </div>
                                
                                <form action="juicios.php?pg=2117&opera=import_step2" method="post" id="formConfirmacion">
<input type="hidden" name="idfic_import" value="<?php echo htmlspecialchars($_SESSION['idfic_import'] ?? ''); ?>">
                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <button type="submit" class="btn btn-confirm-import" id="btnConfirmar">
                                                <div class="btn-content">
                                                    <div class="btn-icon">
                                                        <i class="fa-solid fa-database"></i>
                                                    </div>
                                                    <div class="btn-text">
                                                        <span class="btn-title">Confirmar e Importar</span>
                                                        <span class="btn-subtitle">a Base de Datos</span>
                                                    </div>
                                                    <div class="btn-arrow">
                                                        <i class="fa-solid fa-arrow-right"></i>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                        <div class="col-md-5">
                                            <a href="home.php?pg=2117" class="btn btn-cancel-import" id="btnCancelar">
                                                <div class="btn-content">
                                                    <div class="btn-icon">
                                                        <i class="fa-solid fa-times"></i>
                                                    </div>
                                                    <div class="btn-text">
                                                        <span class="btn-title">Cancelar</span>
                                                        <span class="btn-subtitle">Importación</span>
                                                    </div>
                                                    <div class="btn-arrow">
                                                        <i class="fa-solid fa-arrow-left"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                        
                    <?php elseif ($hay_error): ?>
                        <!-- ERROR EN PROCESAMIENTO -->
                        <div class="alert" style="background-color: #f8d7da; border: 2px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px;">
                            <h5 style="color: #721c24;"><i class="fa-solid fa-exclamation-triangle"></i> Error en el Procesamiento</h5>
                            <p><?php echo htmlspecialchars($_SESSION['error_procesamiento']); ?></p>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="juicios.php?pg=2117" class="btn" 
                               style="background-color: #28a745; color: white; border: 2px solid #28a745; padding: 15px 30px; border-radius: 8px; font-size: 16px; text-decoration: none;">
                                <i class="fa-solid fa-arrow-left"></i> Volver e Intentar de Nuevo
                            </a>
                        </div>
                        
                        <?php unset($_SESSION['error_procesamiento']); ?>
                        
                    <?php else: ?>
                        <!-- PASO 1: SELECCIÓN DE ARCHIVO -->
                        <div id="paso1" class="import-step">
                            <h5 style="color: #28a745;"><i class="fa-solid fa-file-excel"></i> Paso 1: Seleccionar Archivo Excel</h5>
                            <div class="alert" style="background-color: #28a745; color: white; border: 2px solid #28a745; padding: 15px; border-radius: 8px;">
                                <h6 style="color: white;"><i class="fa-solid fa-info-circle"></i> Instrucciones de Importación</h6>
                                <ul style="color: white; margin-bottom: 0;">
                                    <li>Selecciona un archivo Excel (.xlsx) con los juicios evaluativos</li>
                                    <li>El archivo debe contener las columnas: Estudiante, Documento, Competencia, Calificación, etc.</li>
                                    <li>Los datos se procesarán y se mostrará una vista previa antes de la importación final</li>
                                </ul>
                            </div>

                            <form action="juicios.php?pg=2117&opera=import" method="post" id="formImportacion" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="excel_file" style="color: #28a745; font-weight: bold;">
                                                <i class="fa-solid fa-file-upload"></i> Archivo Excel:
                                            </label>
                                            <input type="file" name="excel_file" id="excel_file" 
                                                   class="form-control" accept=".xlsx,.xls" required
                                                   style="border: 2px solid #28a745; border-radius: 8px;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="idfic_import" style="color: #28a745; font-weight: bold;">
                                                <i class="fa-solid fa-list"></i> Ficha de Caracterización:
                                            </label>
                                            <select name="idfic_import" id="idfic_import" class="form-control" required
                                                    style="border: 2px solid #28a745; border-radius: 8px;">
                                                <option value="">Seleccione una ficha destino</option>
                                                <?php if (isset($datFic) && is_array($datFic)): ?>
                                                    <?php foreach ($datFic as $ficha): ?>
                                                        <option value="<?= $ficha['idfic'] ?>">
                                                            <?= htmlspecialchars($ficha['nomfic']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn" 
                                                style="background-color: #00af00; color: white; border: 2px solid #00af00; padding: 12px 30px; border-radius: 8px; font-size: 16px;">
                                            <i class="fa-solid fa-upload"></i> Subir y Procesar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Botón de Regreso -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="home.php?pg=2117" class="btn" style="background-color: #00af00; border-color: #00af00; color: white;">
                                <i class="fa-solid fa-arrow-left"></i> Volver al Módulo de Juicios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.import-step {
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-control:focus {
    border-color: #00af00;
    box-shadow: 0 0 0 0.2rem rgba(0, 175, 0, 0.25);
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.alert {
    margin-bottom: 25px;
}

.card {
    border: 2px solid #00af00;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: 2px solid #00af00;
    border-radius: 10px 10px 0 0;
}

.table th {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.table td {
    border-color: #dee2e6;
}

/* ============================================================================
   ESTILOS MODERNOS PARA BOTONES DE CONFIRMACIÓN
   ============================================================================ */

.btn-confirm-import, .btn-cancel-import {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    font-weight: 600;
    text-decoration: none;
    display: block;
    width: 100%;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    margin-bottom: 15px;
}

.btn-confirm-import {
    background: #00af00;
    color: white;
}

.btn-cancel-import {
    background: #00af00;
    color: white;
}

.btn-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 2;
}

.btn-icon {
    font-size: 20px;
    margin-right: 12px;
    transition: transform 0.3s ease;
}

.btn-text {
    flex: 1;
    text-align: left;
}

.btn-title {
    display: block;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.2;
}

.btn-subtitle {
    display: block;
    font-size: 12px;
    font-weight: 500;
    opacity: 0.9;
    margin-top: 2px;
}

.btn-arrow {
    font-size: 16px;
    transition: transform 0.3s ease;
}

/* Efectos hover */
.btn-confirm-import:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0, 175, 0, 0.4);
    color: white;
}

.btn-cancel-import:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0, 175, 0, 0.4);
    color: white;
}

.btn-confirm-import:hover .btn-icon,
.btn-confirm-import:hover .btn-arrow {
    transform: scale(1.1);
}

.btn-cancel-import:hover .btn-icon,
.btn-cancel-import:hover .btn-arrow {
    transform: scale(1.1);
}

/* Efecto de pulsación al hacer clic */
.btn-confirm-import:active {
    transform: translateY(-2px) scale(0.98);
    transition: all 0.1s ease;
}

.btn-cancel-import:active {
    transform: translateY(-2px) scale(0.98);
    transition: all 0.1s ease;
}

/* Animación de carga para el botón de confirmar */
.btn-confirm-import.loading {
    pointer-events: none;
    opacity: 0.8;
}

.btn-confirm-import.loading .btn-arrow {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Efecto de brillo en hover */
.btn-confirm-import::before,
.btn-cancel-import::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
    z-index: 1;
}

.btn-confirm-import:hover::before,
.btn-cancel-import:hover::before {
    left: 100%;
}

/* Responsive */
@media (max-width: 768px) {
    /* Título responsive */
    .card-header h4 {
        font-size: 1.3rem;
        text-align: center;
    }
    
    /* Cards responsive */
    .card {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    /* Tabla de vista previa con scroll horizontal */
    .table-responsive {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        min-width: 600px;
        font-size: 0.9rem;
    }
    
    .table th, .table td {
        font-size: 0.85rem;
        padding: 0.5rem 0.25rem;
        white-space: nowrap;
    }
    
    /* Botones táctiles */
    .btn-confirm-import, .btn-cancel-import {
        padding: 15px;
        margin-bottom: 10px;
        min-height: 48px;
        width: 100%;
    }
    
    .btn-title {
        font-size: 14px;
    }
    
    .btn-subtitle {
        font-size: 12px;
    }
    
    .btn-icon {
        font-size: 20px;
        margin-right: 10px;
    }
    
    .btn-arrow {
        font-size: 16px;
    }
    
    /* Formularios táctiles */
    .form-control, .form-select {
        font-size: 1rem;
        padding: 0.75rem;
        min-height: 48px;
    }
    
    /* Alertas responsive */
    .alert {
        font-size: 0.95rem;
        padding: 0.75rem;
    }
    
    /* Contenedor principal */
    .container-fluid {
        padding: 0.5rem;
    }
}

@media (max-width: 576px) {
    /* Título más pequeño */
    .card-header h4 {
        font-size: 1.1rem;
    }
    
    /* Tabla más compacta */
    .table {
        min-width: 500px;
        font-size: 0.8rem;
    }
    
    .table th, .table td {
        font-size: 0.75rem;
        padding: 0.4rem 0.2rem;
    }
    
    /* Botones más compactos */
    .btn-confirm-import, .btn-cancel-import {
        padding: 12px;
        font-size: 0.95rem;
    }
    
    .btn-title {
        font-size: 13px;
    }
    
    .btn-subtitle {
        font-size: 11px;
    }
    
    .btn-icon {
        font-size: 18px;
        margin-right: 8px;
    }
    
    /* Cards más compactas */
    .card-body {
        padding: 0.75rem;
    }
}

@media (max-width: 400px) {
    /* Título extra pequeño */
    .card-header h4 {
        font-size: 1rem;
    }
    
    /* Tabla muy compacta */
    .table {
        min-width: 450px;
        font-size: 0.75rem;
    }
    
    .table th, .table td {
        font-size: 0.7rem;
        padding: 0.3rem 0.1rem;
    }
    
    /* Botones más compactos */
    .btn-confirm-import, .btn-cancel-import {
        padding: 10px;
        font-size: 0.9rem;
    }
    
    .btn-title {
        font-size: 12px;
    }
    
    .btn-subtitle {
        font-size: 10px;
    }
    
    .btn-icon {
        font-size: 16px;
        margin-right: 6px;
    }
}

/* Animación de entrada */
.btn-confirm-import, .btn-cancel-import {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-confirm-import {
    animation-delay: 0.1s;
}

.btn-cancel-import {
    animation-delay: 0.2s;
}
</style>

<script>
// Script para mejorar la experiencia de usuario en los botones
document.addEventListener('DOMContentLoaded', function() {
    const btnConfirmar = document.getElementById('btnConfirmar');
    const btnCancelar = document.getElementById('btnCancelar');
    
    if (btnConfirmar) {
        btnConfirmar.addEventListener('click', function(e) {
            // Agregar efecto de carga
            this.classList.add('loading');
            
            // Cambiar el texto del botón
            const btnText = this.querySelector('.btn-text');
            const originalTitle = btnText.querySelector('.btn-title').textContent;
            const originalSubtitle = btnText.querySelector('.btn-subtitle').textContent;
            
            btnText.querySelector('.btn-title').textContent = 'Procesando...';
            btnText.querySelector('.btn-subtitle').textContent = 'Por favor espera';
            
            // Permitir que el formulario se envíe normalmente
            // No deshabilitar el botón para que el envío funcione
        });
    }
    
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function(e) {
            // Continuar directamente con la cancelación
            return true;
        });
    }
    
    // Efecto de hover mejorado
    const buttons = document.querySelectorAll('.btn-confirm-import, .btn-cancel-import');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
