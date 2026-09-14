<?php
// Incluir el controlador para que las variables estén disponibles
require_once 'controllers/cjui.php';

// ============================================================================
// VISTA DE VISUALIZACIÓN DE JUICIOS
// ============================================================================

// Verificar si hay resultados de importación para mostrar
$hay_resultados_importacion = isset($_SESSION['resultado_importacion']);
?>

<!-- ============================================================================
     INTERFAZ MODERNA Y AMIGABLE DE VISUALIZACIÓN DE JUICIOS
     ============================================================================ -->

<div class="container-fluid">
    <!-- HEADER PRINCIPAL CON TÍTULO Y DESCRIPCIÓN -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="text-center">
                <h2 class="display-4" style="color: #00af00; font-weight: 700; margin-bottom: 10px;">
                    <i class="fa-solid fa-chart-line"></i> Dashboard de Juicios
                </h2>
                <p class="lead" style="color: #6c757d; font-size: 1.2rem;">
                    Visualiza y gestiona todos los juicios evaluativos importados en el sistema
                </p>
            </div>
        </div>
    </div>

    <?php if ($hay_resultados_importacion): ?>
        <!-- SECCIÓN DE RESULTADOS DE IMPORTACIÓN - DISEÑO MEJORADO -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header text-center" style="background: linear-gradient(135deg, #00af00 0%, #20c997 100%); color: white; padding: 30px; border: none;">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="display-1" style="font-size: 4rem; margin: 0;">
                                    <i class="fa-solid fa-trophy"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h1 class="display-5" style="margin: 0; font-weight: 700;">¡Importación Completada!</h1>
                                <p class="mb-0 mt-2" style="font-size: 1.1rem; opacity: 0.9;">
                                    La importación de juicios se ha procesado exitosamente
                                </p>
                            </div>
                            <div class="col-md-2">
                                <div class="display-1" style="font-size: 4rem; margin: 0;">
                                    <i class="fa-solid fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body" style="padding: 40px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <!-- ESTADÍSTICAS PRINCIPALES - DISEÑO PASTILLA -->
                        <div class="row mb-4 mt-3">
                          <div class="col-12">
                            <h2 class="text-center mb-3" style="color: #495057; font-weight: 700; font-size: 1.6rem;">
                              RESULTADOS DE LA IMPORTACIÓN
                            </h2>
                          </div>
                        </div>

                        <?php
                        // Extraer valores de la nueva estructura
                        $insertados = 0;
                        $actualizados = 0;
                        $omitidos = 0;
                        
                        if (is_array($_SESSION['resultado_importacion'])) {
                            if (isset($_SESSION['resultado_importacion']['juicios']['insertados'])) {
                                $insertados = intval($_SESSION['resultado_importacion']['juicios']['insertados']);
                            } elseif (isset($_SESSION['resultado_importacion']['insertados'])) {
                                $insertados = intval($_SESSION['resultado_importacion']['insertados']);
                            }
                            
                            if (isset($_SESSION['resultado_importacion']['juicios']['actualizados'])) {
                                $actualizados = intval($_SESSION['resultado_importacion']['juicios']['actualizados']);
                            } elseif (isset($_SESSION['resultado_importacion']['actualizados'])) {
                                $actualizados = intval($_SESSION['resultado_importacion']['actualizados']);
                            }
                            
                            if (isset($_SESSION['resultado_importacion']['juicios']['omitidos'])) {
                                $omitidos = intval($_SESSION['resultado_importacion']['juicios']['omitidos']);
                            } elseif (isset($_SESSION['resultado_importacion']['omitidos'])) {
                                $omitidos = intval($_SESSION['resultado_importacion']['omitidos']);
                            }
                        }
                        
                        // Calcular total procesado
                        $total_procesado = $insertados + $actualizados + $omitidos;
                        $hay_omitidos = $omitidos > 0;
                        ?>

                        <div class="row justify-content-center status-wrap">
                          <!-- TARJETA PRINCIPAL -->
                          <div class="<?php echo $hay_omitidos ? 'col-md-3' : 'col-md-6'; ?>">
                            <div class="status-card status-success">
                              <h4 class="status-title">
                                <span class="icon-badge success"><i class="fa-solid fa-square-check"></i></span>
                                <?php echo $hay_omitidos ? 'INSERTADOS' : 'PROCESADOS'; ?>
                              </h4>
                              <div
                                class="status-number"
                                data-target="<?php echo $hay_omitidos ? $insertados : $total_procesado; ?>"
                                data-format="plain">0</div>
                              <p class="status-sub"><?php echo $hay_omitidos ? 'Nuevos registros' : 'Total procesados'; ?></p>
                            </div>
                          </div>

                          <?php if ($hay_omitidos): ?>
                          <!-- TARJETA ACTUALIZADOS -->
                          <div class="col-md-3">
                            <div class="status-card status-info">
                              <h4 class="status-title info">
                                <span class="icon-badge info"><i class="fa-solid fa-pen-to-square"></i></span>
                                ACTUALIZADOS
                              </h4>
                              <div
                                class="status-number info"
                                data-target="<?php echo $actualizados; ?>"
                                data-format="plain">0</div>
                              <p class="status-sub">Registros actualizados</p>
                            </div>
                          </div>

                          <!-- TARJETA OMITIDOS -->
                          <div class="col-md-3">
                            <div class="status-card status-muted">
                              <h4 class="status-title muted">
                                <span class="icon-badge muted"><i class="fa-solid fa-forward"></i></span>
                                OMITIDOS
                              </h4>
                              <div
                                class="status-number muted"
                                data-target="<?php echo $omitidos; ?>"
                                data-format="plain">0</div>
                              <p class="status-sub">Filas saltadas</p>
                            </div>
                          </div>
                          <?php endif; ?>
                        </div>

                        
                        <!-- DETALLES DE REGISTROS OMITIDOS -->
                        <?php if ($hay_omitidos): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm" style="border-radius: 15px; background: white;">
                                        <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; border: none; border-radius: 15px 15px 0 0;">
                                            <h5 class="mb-0" style="font-weight: 600;">
                                                <i class="fa-solid fa-exclamation-triangle"></i> Detalles de Registros Omitidos
                                            </h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h5 class="mb-3" style="color: #856404; font-weight: 700; font-size: 1.2rem; letter-spacing: 0.3px;">
                                                        <i class="fa-solid fa-info-circle me-2"></i> Información de Omitidos
                                                    </h5>
                                                    <div class="mb-3">
                                                        <span style="font-size: 1.1rem; font-weight: 600; color: #495057;">Total omitidos:</span>
                                                        <span style="font-size: 1.3rem; font-weight: 700; color: #dc3545; margin-left: 8px;"><?php echo number_format($omitidos); ?> registros</span>
                                                    </div>
                                                    <p class="mb-4" style="font-size: 14px; color: #6c757d; font-weight: 500; line-height: 1.4;">Los registros se omitieron por motivos técnicos o de validación.</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <div class="mt-4 p-4" style="background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%); border-radius: 15px; border: 1px solid #ffeaa7; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.1);">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <h5 class="mb-3" style="color: #856404; font-weight: 700; font-size: 1.1rem; letter-spacing: 0.3px;">
                                                                <i class="fa-solid fa-list-ul me-2"></i>
                                                                Registros Omitidos Específicos
                                                            </h5>
                                                            <div class="bg-white p-3 rounded-3" style="border: 1px solid #ffeaa7;">
                                                                <?php 
                                                                if (isset($_SESSION['resultado_importacion']['duplicados_detallados']) && !empty($_SESSION['resultado_importacion']['duplicados_detallados'])):
                                                                    foreach (array_slice($_SESSION['resultado_importacion']['duplicados_detallados'], 0, 5) as $error): ?>
                                                                        <div class="d-flex align-items-start mb-3 p-2" style="background-color: #fff8e1; border-radius: 8px; border-left: 3px solid #ffc107;">
                                                                            <i class="fa-solid fa-exclamation-triangle text-warning me-3 mt-1" style="font-size: 14px;"></i>
                                                                            <span style="font-size: 14px; line-height: 1.5; color: #856404; font-weight: 500;"><?php echo htmlspecialchars($error); ?></span>
                                                                        </div>
                                                                    <?php endforeach;
                                                                    if (count($_SESSION['resultado_importacion']['duplicados_detallados']) > 5): ?>
                                                                        <div class="text-center p-3" style="background-color: #f8f9fa; border-radius: 8px; border: 1px dashed #dee2e6;">
                                                                            <i class="fa-solid fa-ellipsis-h text-muted me-2"></i>
                                                                            <span style="font-size: 14px; color: #6c757d; font-weight: 500;">... y <?php echo count($_SESSION['resultado_importacion']['duplicados_detallados']) - 5; ?> registros más</span>
                                                                        </div>
                                                                    <?php endif;
                                                                else: ?>
                                                                    <div class="text-center p-4" style="background-color: #e3f2fd; border-radius: 8px; border: 1px solid #bbdefb;">
                                                                        <i class="fa-solid fa-info-circle text-info me-2" style="font-size: 16px;"></i>
                                                                        <span style="font-size: 14px; color: #1976d2; font-weight: 500;">No hay detalles específicos disponibles</span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="h-100 p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px; border: 1px solid #dee2e6;">
                                                                <h6 class="mb-3" style="color: #495057; font-weight: 600; font-size: 1rem; letter-spacing: 0.2px;">
                                                                    <i class="fa-solid fa-lightbulb text-warning me-2"></i> 
                                                                    ¿Por qué se omiten registros?
                                                                </h6>
                                                                <ul class="mb-0" style="padding-left: 0; list-style: none;">
                                                                    <li class="mb-3 d-flex align-items-start">
                                                                        <i class="fa-solid fa-circle text-warning me-3 mt-1" style="font-size: 8px;"></i>
                                                                        <span style="font-size: 13px; line-height: 1.4; color: #6c757d; font-weight: 500;">Datos incompletos o malformateados</span>
                                                                    </li>
                                                                    <li class="mb-3 d-flex align-items-start">
                                                                        <i class="fa-solid fa-circle text-warning me-3 mt-1" style="font-size: 8px;"></i>
                                                                        <span style="font-size: 13px; line-height: 1.4; color: #6c757d; font-weight: 500;">Errores de validación en campos críticos</span>
                                                                    </li>
                                                                    <li class="mb-3 d-flex align-items-start">
                                                                        <i class="fa-solid fa-circle text-warning me-3 mt-1" style="font-size: 8px;"></i>
                                                                        <span style="font-size: 13px; line-height: 1.4; color: #6c757d; font-weight: 500;">Problemas de conexión con la base de datos</span>
                                                                    </li>
                                                                    <li class="mb-0 d-flex align-items-start">
                                                                        <i class="fa-solid fa-circle text-warning me-3 mt-1" style="font-size: 8px;"></i>
                                                                        <span style="font-size: 13px; line-height: 1.4; color: #6c757d; font-weight: 500;">Registros duplicados detectados</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- LIMPIAR VARIABLES DE SESIÓN DESPUÉS DE MOSTRAR -->
                        <?php 
                        unset($_SESSION['resultado_importacion']);
                        unset($_SESSION['idfic_importado']);
                        ?>
                        
                        <hr style="border: 2px solid #00af00; margin: 40px 0; border-radius: 10px;">
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTÓN DE REGRESO - COLORES SIMPLIFICADOS -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="home.php?pg=2117" class="btn btn-lg" 
               style="background: #00af00; color: white; border: none; border-radius: 25px; padding: 15px 35px; font-weight: 600; font-size: 1.1rem; text-decoration: none; display: inline-block;">
                <i class="fa-solid fa-arrow-left me-2"></i> 
                Volver al Módulo de Juicios
            </a>
        </div>
    </div>
</div>

<!-- SCRIPT DE ANIMACIÓN DE CONTEO -->
<script>
(function () {
  // Formateo opcional
  function formatValue(val, format) {
    if (format === 'thousands') {
      // miles por punto, sin decimales
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    return String(val);
  }

  // Animación con requestAnimationFrame
  function animateCounter(el, opts) {
    const target = Number(el.dataset.target || 0);
    const format = el.dataset.format || 'plain';
    const duration = opts?.duration ?? 1200; // ms
    const easing = opts?.easing || ((t) => 1 - Math.pow(1 - t, 3)); // easeOutCubic

    let start = null;
    const startVal = 0;

    function step(ts) {
      if (!start) start = ts;
      const elapsed = ts - start;
      const p = Math.min(1, elapsed / duration);
      const eased = easing(p);
      const current = Math.floor(startVal + (target - startVal) * eased);
      el.textContent = formatValue(current, format);
      if (p < 1) {
        requestAnimationFrame(step);
      } else {
        // asegura valor final exacto
        el.textContent = formatValue(target, format);
      }
    }
    requestAnimationFrame(step);
  }

  // Dispara cuando se vea en pantalla (una sola vez)
  const counters = Array.from(document.querySelectorAll('.status-number'));
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });
    counters.forEach((el) => io.observe(el));
  } else {
    // Fallback: anima inmediatamente
    counters.forEach((el) => animateCounter(el));
  }
})();
</script>

<!-- ESTILOS CSS MEJORADOS -->
<style>
  .status-wrap { gap: 20px; }
  .status-card {
    border-radius: 12px;
    padding: 22px 18px;
    text-align: center;
    box-shadow: 0 1px 2px rgba(0,0,0,.06);
  }
  .status-success { background: #e6f7e6; }   /* verde suave */
  .status-info    { background: #e3f2fd; }   /* azul suave */
  .status-muted   { background: #e5e7eb; }   /* gris suave */
  .status-title {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #006600;        /* verde oscuro legible */
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 6px 0;
  }
  .status-title.info  { color: #1a365d; }    /* azul título actualizados */
  .status-title.muted { color: #616973; }    /* gris título omitidos */
  .icon-badge {
    display: inline-flex;
    width: 22px; height: 22px;
    border-radius: 6px;
    align-items: center; justify-content: center;
    font-size: 12px; color: #fff;
  }
  .icon-badge.success { background: #00af00; }
  .icon-badge.info    { background: #17a2b8; }
  .icon-badge.muted   { background: #6c757d; }
  .status-number {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 6px 0;
    color: #006600;        /* verde número */
  }
  .status-number.info  { color: #1a365d; }    /* azul número */
  .status-number.muted { color: #606a76; }    /* gris número */
  .status-sub {
    margin: 0;
    font-weight: 600;
    color: #1f2937;        /* texto negro/gris oscuro */
    font-size: 14px;
  }

  /* Responsive: que las "pastillas" ocupen todo en móviles */
  @media (max-width: 767.98px){
    .status-wrap > div { width: 100%; }
  }

/* Efectos hover para botones */
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Efectos hover para inputs */
.form-control:focus, .form-select:focus {
    border-color: #00af00;
    box-shadow: 0 0 0 0.2rem rgba(0, 175, 0, 0.25);
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

/* Efectos hover para tarjetas */
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

/* Estilos para la tabla */
.table th {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Estilos para paginación */
.pagination .page-link {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #00af00;
    border-color: #00af00;
    color: white;
    transform: translateY(-1px);
}

/* Animaciones para elementos que aparecen */
.card, .alert, .btn {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive design */
@media (max-width: 768px) {
    /* Títulos responsive */
    .display-4 {
        font-size: 2rem;
    }
    
    .display-5 {
        font-size: 1.5rem;
    }
    
    /* Cards responsive */
    .card {
        margin-bottom: 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    /* Tabla con scroll horizontal */
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
    .btn-lg {
        padding: 10px 25px;
        font-size: 1rem;
        min-height: 48px;
    }
    
    .btn {
        min-height: 48px;
        margin-bottom: 0.5rem;
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
    
    /* Status cards responsive */
    .status-wrap {
        flex-direction: column;
        gap: 1rem;
    }
    
    .status-wrap > div {
        width: 100%;
    }
}

@media (max-width: 576px) {
    /* Títulos más pequeños */
    .display-4 {
        font-size: 1.8rem;
    }
    
    .display-5 {
        font-size: 1.3rem;
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
    .btn-lg {
        padding: 8px 20px;
        font-size: 0.95rem;
    }
    
    .btn {
        font-size: 0.95rem;
        padding: 0.6rem 1.2rem;
    }
    
    /* Cards más compactas */
    .card-body {
        padding: 0.75rem;
    }
    
    /* Status cards más compactas */
    .status-card {
        padding: 15px 12px;
    }
    
    .status-title {
        font-size: 0.9rem;
    }
    
    .status-number {
        font-size: 24px;
    }
    
    .status-sub {
        font-size: 12px;
    }
}

@media (max-width: 400px) {
    /* Títulos extra pequeños */
    .display-4 {
        font-size: 1.5rem;
    }
    
    .display-5 {
        font-size: 1.1rem;
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
    .btn-lg {
        padding: 6px 15px;
        font-size: 0.9rem;
    }
    
    .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    /* Status cards muy compactas */
    .status-card {
        padding: 12px 10px;
    }
    
    .status-title {
        font-size: 0.8rem;
    }
    
    .status-number {
        font-size: 20px;
    }
    
    .status-sub {
        font-size: 11px;
    }
}

/* Estilos específicos para la sección de omitidos */
/* Efectos hover para elementos de la lista de omitidos */
.d-flex:hover {
    transform: translateX(5px);
    transition: all 0.3s ease;
}

/* Estilos para el botón de detalles */
.btn-outline-warning:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

/* Mejoras en la tipografía */
h5, h6 {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    letter-spacing: 0.3px;
}

/* Espaciado mejorado para elementos de lista */
.list-unstyled li {
    margin-bottom: 12px;
    padding: 8px 0;
}

/* Sombras sutiles para profundidad */
.bg-light {
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
</style>
