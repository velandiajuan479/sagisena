<?php

require_once("controllers/ccmausu2.php");
?>

<?php if (!empty($mensaje) || !empty($tipoMensaje) || $pg == 2118): ?>
    <?php
    $estadoReal = 'info';

    if (!empty($mensaje)) {
        preg_match('/(\d+)\s+exitosos,\s*(\d+)\s+errores,\s*(\d+)\s+omitidos/', $mensaje, $matches);

        if (count($matches) >= 4) {
            $exitosos = (int)$matches[1];
            $errores = (int)$matches[2];
            $omitidos = (int)$matches[3];

            if ($errores > 0) {
                $estadoReal = 'danger';
            } elseif ($exitosos > 0 && $omitidos > 0) {
                $estadoReal = 'warning';
            } elseif ($exitosos > 0 && $omitidos == 0) {
                $estadoReal = 'success';
            } elseif ($exitosos == 0 && $errores == 0 && $omitidos > 0) {
                $estadoReal = 'info';
            }
        } else {
            $estadoReal = 'success';
        }
    }

    // Configuración de estilos por estado
    $configuracion = [
        'success' => [
            'fondo' => '#d4edda',
            'borde' => '#c3e6cb',
            'color' => '#155724',
            'icono' => 'fa-check-circle',
            'titulo' => '¡Carga Masiva Completada Exitosamente Nueva Plantilla!'
        ],
        'warning' => [
            'fondo' => '#fff3cd',
            'borde' => '#ffeaa7',
            'color' => '#856404',
            'icono' => 'fa-exclamation-triangle',
            'titulo' => 'Carga Masiva Completada con Advertencias Nueva Plantilla'
        ],
        'danger' => [
            'fondo' => '#f8d7da',
            'borde' => '#f5c6cb',
            'color' => '#721c24',
            'icono' => 'fa-times-circle',
            'titulo' => 'Error en el Procesamiento de Carga Masiva Nueva Plantilla'
        ],
        'info' => [
            'fondo' => '#e2e3e5',
            'borde' => '#d1d3d4',
            'color' => '#6c757d',
            'icono' => 'fa-info-circle',
            'titulo' => 'Procesamiento Completado - Sin Cambios Nueva Plantilla'
        ]
    ];

    $config = $configuracion[$estadoReal] ?? [
        'fondo' => '#f8f9fa',
        'borde' => '#dee2e6',
        'color' => '#495057',
        'icono' => 'fa-question-circle',
        'titulo' => 'Resultado de Carga Masiva Nueva Plantilla'
    ];

    echo titulo2("<i class='fa-solid fa-file-import'></i> Resultado de Carga Masiva Nueva Plantilla", 0);
    ?>

    <div class="conte">
        <div class="row" style="margin-bottom: 2rem;">
            <div class="col-md-12">
                <div style="text-align: center; padding: 2rem; background-color: <?php echo $config['fondo']; ?>; border: 1px solid <?php echo $config['borde']; ?>; border-radius: 8px;">

                    <div style="font-size: 5rem; margin-bottom: 1rem; color: <?php echo $config['color']; ?>;">
                        <i class="fa-solid <?php echo $config['icono']; ?>"></i>
                    </div>

                    <h3 style="color: <?php echo $config['color']; ?>; margin-bottom: 1rem; font-weight: bold;">
                        <?php echo $config['titulo']; ?>
                    </h3>

                    <p style="font-size: 1.8rem; color: <?php echo $config['color']; ?>; margin: 0; font-weight: 500;">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </p>

                    <?php if ($estadoReal === 'info'): ?>
                        <p style="font-size: 1.4rem; color: #6c757d; margin-top: 1rem; font-style: italic;">
                            Todos los registros fueron omitidos porque ya existían en el sistema
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Detalles del Procesamiento -->
        <?php if (!empty($detalles) && is_array($detalles)): ?>
            <div class="row">
                <div class="col-md-12">
                    <div style="background-color: #ffffff; border: 1px solid #ddd; border-radius: 8px; padding: 2rem;">
                        <h4 style="color: #333; margin-bottom: 2rem; text-align: center; font-weight: bold;">
                            <i class="fa-solid fa-list-check" style="margin-right: 1rem; color: #117f09;"></i>
                            Detalles del Procesamiento
                        </h4>

                        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 5px; padding: 1rem; background-color: #f8f9fa;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tbody>
                                    <?php
                                    $iconosDetalles = [
                                        ['palabras' => ['correctamente', 'exitoso'], 'icono' => 'fa-check-circle', 'color' => '#28a745'],
                                        ['palabras' => ['omitido', 'ya registrado'], 'icono' => 'fa-exclamation-circle', 'color' => '#ffc107'],
                                        ['palabras' => ['error', 'fallo'], 'icono' => 'fa-times-circle', 'color' => '#dc3545']
                                    ];

                                    foreach ($detalles as $detalle):
                                        $iconoDetalle = 'fa-info-circle';
                                        $colorDetalle = '#17a2b8';

                                        foreach ($iconosDetalles as $configIcono) {
                                            foreach ($configIcono['palabras'] as $palabra) {
                                                if (strpos(strtolower($detalle), $palabra) !== false) {
                                                    $iconoDetalle = $configIcono['icono'];
                                                    $colorDetalle = $configIcono['color'];
                                                    break 2;
                                                }
                                            }
                                        }
                                    ?>
                                        <tr style="border-bottom: 1px solid #dee2e6;">
                                            <td style="padding: 1rem 0; width: 40px; text-align: center; vertical-align: top;">
                                                <i class="fa-solid <?php echo $iconoDetalle; ?>" style="color: <?php echo $colorDetalle; ?>; font-size: 1.4rem;"></i>
                                            </td>
                                            <td style="padding: 1rem 0; font-size: 1.4rem; color: #495057; line-height: 1.4;">
                                                <?php echo htmlspecialchars($detalle); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row" style="margin-top: 3rem;">
            <div class="col-md-12" style="text-align: center;">
                <?php
                $botones = [
                    ['url' => 'home.php?pg=2103', 'color' => '#6c757d', 'icono' => 'fa-arrow-left', 'texto' => 'Regresar'],
                    ['url' => 'mod.php', 'color' => '#117f09', 'icono' => 'fa-home', 'texto' => 'Ir al Inicio']
                ];

                foreach ($botones as $boton): ?>
                    <a href="<?php echo $boton['url']; ?>" class="btn" style="background-color: <?php echo $boton['color']; ?>; color: white; margin-right: 1rem; padding: 1rem 2rem; font-size: 1.6rem; border-radius: 5px; text-decoration: none; display: inline-block;">
                        <i class="fa-solid <?php echo $boton['icono']; ?>" style="margin-right: 1rem;"></i><?php echo $boton['texto']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="modalCmausu2" tabindex="-1" aria-labelledby="modalCmausuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalCmausuLabel">Carga Masiva de Usuarios Nueva Plantilla</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="home.php?pg=2118" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <!-- Campo de carga de archivo -->
                        <div class="form-group col-md-12" style="margin:auto;">
                            <label for="archivo_excel">Archivo Excel *</label>
                            <input type="file" name="archivo_excel" id="archivo_excel"
                                class="form-control" accept=".xlsx,.xls" required>
                            <small class="text-muted">Formatos permitidos: Excel (.xlsx, .xls)</small>
                        </div>

                        <div class="mb-3" style="margin-top:10px;">
                            <label for="jorpro" class="form-label" style="color: #17AD0C">
                                <i class="fas fa-calendar-day me-2"></i>
                                Seleccionar Jornada
                            </label>
                            <select class="form-select border-success" id="jorpro" name="jorpro" required>
                                <?php foreach ($jornadas as $jornada): ?>
                                    <option value="<?php echo $jornada['idval']; ?>">
                                        <?php echo $jornada['nomval']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <?php
                    $fecha_inicial = date('Y-m-d');
                    $fecha_final = date('Y-m-d', strtotime('+2 years'));
                    ?>
                    <input type="hidden" name="pg" value="2118">
                    <input type="hidden" name="opera" value="carga_masiva">
                    <input type="hidden" name="fini" value="<?php echo $fecha_inicial; ?>">
                    <input type="hidden" name="ffin" value="<?php echo $fecha_final; ?>">
                </div>
                <div class="modal-footer">
                    <!-- <a href="arc/fcmausu.xls" class="btn btn-secondary" download>
                        <i class="fas fa-download"></i> Descargar Plantilla
                    </a> -->
                    <input type="submit" class="btn btn-primary" value="Cargar Archivo"></input>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('archivo_excel').addEventListener('change', function(e) {
        const archivo = e.target.files[0];
        if (archivo && archivo.size > 10 * 1024 * 1024) {
            alert('El archivo excede el tamaño máximo permitido (10MB)');
            e.target.value = '';
        }
    });
</script>