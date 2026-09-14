<?php require_once __DIR__ . '/../controllers/ccalendacad.php'; ?>

<div class="conte">
    <div class="tit">
        <h1>
            <i class="fa-solid fa-calendar-days"></i> Calendario Académico
            <i class="fa-solid fa-circle-plus" id="mas" onclick="ocul(1,1);" style="margin-left: 20px;color: #ffffff;text-shadow: 0px 0px 5px #00af00, 0px 0px 5px #00af00, 0px 0px 5px #00af00;"></i>
            <i class="fa-solid fa-circle-minus" id="menos" onclick="ocul(1);" style="margin-left: 20px;color: #ffffff;text-shadow: 0px 0px 5px #00af00, 0px 0px 5px #00af00, 0px 0px 5px #00af00;"></i>
        </h1>
        <hr class="lintit">
    </div>

    <!-- Formulario de nuevo/editar evento -->
    <div id="frmins" class="inser" style="display: none;">
        <form id="formEvento" action="home.php?pg=1599" method="POST" class="form-calendario">
            <input type="hidden" name="id" id="evento_id">
            <input type="hidden" name="ope" id="evento_ope" value="save">
            <input type="hidden" name="anio" id="evento_anio">
            <input type="hidden" name="trimestre" id="evento_trimestre">
            
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="titulo" class="form-label">Título del evento *</label>
                    <input type="text" name="titulo" id="evento_titulo" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="fecha_inicio" class="form-label">Fecha inicio *</label>
                    <input type="date" name="fecha_inicio" id="evento_fecha_inicio" class="form-control" required>
                </div>
            </div>
            
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="fecha_fin" class="form-label">Fecha fin *</label>
                    <input type="date" name="fecha_fin" id="evento_fecha_fin" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="tipo_evento" class="form-label">Tipo de evento *</label>
                    <select name="tipo_evento" id="evento_tipo_evento" class="form-select" required>
                        <?php foreach ($tipos_eventos as $key => $value): ?>
                            <option value="<?= $key ?>"><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <br>
                <input type="submit" class="btn btn-primary" value="Guardar">
                <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Limpiar</button>
            </div>
        </form>
    </div>

    <!-- Panel de estadísticas -->
    <div class="row mb-4 p-2 g-3 justify-content-center">
        <div class="col-4 col-md-3">
            <div class="card card-cal-aca text-center bg-success text-white">
                <div class="card-body text-center">
                    <h5><i class="fa-solid fa-calendar"></i></h5>
                    <h6>Total Eventos</h6>
                    <h4><?= $estadisticas['total_eventos'] ?></h4>
                </div>
            </div>
        </div>
        <div class="col-4 col-md-3">
            <div class="card card-cal-aca text-center bg-success text-white">
                <div class="card-body text-center">
                    <h5><i class="fa-solid fa-graduation-cap"></i></h5>
                    <h6>Trimestre Actual</h6>
                    <h4><?= $estadisticas['trimestre_actual'] ?></h4>
                    <small><?= $estadisticas['mes_actual'] ?></small>
                </div>
            </div>
        </div>
        <?php if ($estadisticas['aplica_calculo']): ?>
            <!-- Solo mostrar tipo de usuario si aplica (Instructores/Administradores/Funcionarios) -->
            <div class="col-4 col-md-3">
                <div class="card card-cal-aca text-center bg-success text-white">
                    <div class="card-body text-center">
                        <h5><i class="fa-solid fa-id-badge"></i></h5>
                        <h6>Tipo de Usuario</h6>
                        <h4><?= $estadisticas['tipo_usuario'] ?></h4>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Segunda fila de estadísticas - CONDICIONAL según tipo de usuario -->
    <?php if ($estadisticas['aplica_calculo']): ?>
        <?php if ($estadisticas['tipo_usuario'] == 'CONTRATISTA'): ?>
            <!-- Estadísticas para CONTRATISTA (Instructores/Administradores/Funcionarios con fechas) -->
            <div class="row mb-4 p-2 g-3 justify-content-center">
                <div class="col-4 col-md-3">
                    <div class="card card-cal-aca text-center bg-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-user-graduate"></i></h5>
                            <h6>Horas Requeridas</h6>
                            <h4><?= $estadisticas['horas_requeridas'] ?></h4>
                            <small>Fijas mensuales</small>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <div class="card card-cal-aca text-center bg-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-check-circle"></i></h5>
                            <h6>Horas Registradas</h6>
                            <h4><?= $estadisticas['horas_registradas'] ?></h4>
                            <small>En horarios</small>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <div class="card card-cal-aca text-center <?= $estadisticas['horas_faltantes'] > 0 ? 'bg-warning' : 'bg-success' ?> text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-<?= $estadisticas['horas_faltantes'] > 0 ? 'exclamation-triangle' : 'check-double' ?>"></i></h5>
                            <h6>Horas Faltantes</h6>
                            <h4><?= $estadisticas['horas_faltantes'] ?></h4>
                            <small><?= $estadisticas['porcentaje_cumplimiento'] ?>% cumplido</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Estadísticas para PLANTA (Instructores/Administradores/Funcionarios sin fechas) -->
            <div class="row mb-4 p-2 g-3 justify-content-center">
                <div class="col-4 col-md-3">
                    <div class="card card-cal-aca text-center bg-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-clock"></i></h5>
                            <h6>Horas Requeridas</h6>
                            <h4><?= round($estadisticas['horas_requeridas'] ?? 0) ?></h4>
                            <small>Mensual (días laborables × 6.2)</small>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <div class="card card-cal-aca text-center bg-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-check-circle"></i></h5>
                            <h6>Horas Registradas</h6>
                            <h4><?= $estadisticas['horas_registradas'] ?></h4>
                            <small>En horarios</small>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-3">
                    <div class="card card-cal-aca text-center <?= $estadisticas['horas_faltantes'] > 0 ? 'bg-warning' : 'bg-success' ?> text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-<?= $estadisticas['horas_faltantes'] > 0 ? 'exclamation-triangle' : 'check-double' ?>"></i></h5>
                            <h6>Horas Faltantes</h6>
                            <h4><?= $estadisticas['horas_faltantes'] ?></h4>
                            <small><?= $estadisticas['porcentaje_cumplimiento'] ?>% cumplido</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tercera fila: Horas Complementarias para PLANTA -->
            <div class="row mb-4 p-2 g-3 justify-content-center">
                <div class="col-6 col-md-4">
                    <div class="card card-cal-aca text-center bg-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fa-solid fa-user-tie"></i></h5>
                            <h6>Horas Complementarias</h6>
                            <h4><?= round($estadisticas['horas_complementarias'] ?? 0) ?></h4>
                            <small>Mensual (días laborables × 2.4)</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>



    <!-- Filtros y controles -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="home.php" class="filtro-estadisticas-form">
                <input type="hidden" name="pg" value="1599">
                <select name="anio" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($anios_disponibles as $anio): ?>
                        <option value="<?= $anio ?>" <?= ($_REQUEST['anio'] ?? date('Y')) == $anio ? 'selected' : '' ?>>
                            Año <?= $anio ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- Tabla de eventos -->
    <?php if (!empty($eventos)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Duración</th>
                        <th>Tipo</th>
                        <th>Trimestre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $e): ?>
                        <?php
                            $inicio = new DateTime($e['fecha_inicio']);
                            $fin = new DateTime($e['fecha_fin']);
                            $duracion = $inicio->diff($fin)->days + 1;
                        ?>
                        <tr class="table-row-white">
                            <td>
                                <strong><?= htmlspecialchars($e['titulo']) ?></strong>
                                <?php if ($e['descripcion']): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($e['descripcion']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $inicio->format('d/m/Y') ?></td>
                            <td><?= $fin->format('d/m/Y') ?></td>
                            <td>
                                <span class="badge-text"><?= $duracion ?> días</span>
                            </td>
                            <td>
                                <span class="badge-text">
                                    <?= ucfirst(str_replace('_', ' ', $e['tipo_evento'])) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($e['trimestre'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Vista de tarjetas para móvil -->
        <div class="mobile-cards">
            <?php foreach ($eventos as $e): ?>
                <?php
                    $inicio = new DateTime($e['fecha_inicio']);
                    $fin = new DateTime($e['fecha_fin']);
                    $duracion = $inicio->diff($fin)->days + 1;
                ?>
                <div class="evento-card">
                    <div class="evento-header">
                        <div class="evento-title">
                            <?= htmlspecialchars($e['titulo']) ?>
                        </div>
                        <div class="evento-meta">
                            <div class="evento-meta-item">
                                <strong>Trimestre:</strong> <?= htmlspecialchars($e['trimestre']) ?>
                            </div>
                            <div class="evento-meta-item">
                                <strong>Duración:</strong> <span class="duracion-badge"><?= $duracion ?> días</span>
                            </div>
                            <div class="evento-meta-item">
                                <strong>Tipo:</strong> <span class="tipo-badge"><?= htmlspecialchars($e['tipo_evento'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="evento-details">
                        <div class="evento-detail">
                            <div class="evento-detail-label">Fecha de Inicio</div>
                            <div class="evento-detail-value"><?= $inicio->format('d/m/Y') ?></div>
                        </div>
                        <div class="evento-detail">
                            <div class="evento-detail-label">Fecha de Fin</div>
                            <div class="evento-detail-value"><?= $fin->format('d/m/Y') ?></div>
                        </div>
                    </div>
                    
                    <?php if ($e['descripcion']): ?>
                        <div class="evento-descripcion">
                            <strong>Descripción:</strong><br>
                            <?= htmlspecialchars($e['descripcion']) ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
            <h5>No hay eventos registrados para el año <?= $_REQUEST['anio'] ?? date('Y') ?></h5>
            <p>Haz clic en el botón "+" del título para agregar un nuevo evento</p>
        </div>
    <?php endif; ?>
</div>

<script>
// Función para editar evento
function editarEvento(evento) {
    // Mostrar formulario
    ocul(1, 1);
    
    // Llenar datos del evento
    document.getElementById('evento_id').value = evento.id;
    document.getElementById('evento_ope').value = 'edit';
    document.getElementById('evento_titulo').value = evento.titulo;
    document.getElementById('evento_fecha_inicio').value = evento.fecha_inicio;
    document.getElementById('evento_fecha_fin').value = evento.fecha_fin;
    document.getElementById('evento_tipo_evento').value = evento.tipo_evento;
    
    // Calcular y establecer el año y trimestre automáticamente
    const fechaInicio = new Date(evento.fecha_inicio);
    document.getElementById('evento_anio').value = fechaInicio.getFullYear();
    
    // Calcular trimestre basado en la fecha
    const mes = fechaInicio.getMonth() + 1;
    let trimestre = '';
    if (mes >= 2 && mes <= 4) trimestre = 'Primer Trimestre';
    else if (mes >= 5 && mes <= 7) trimestre = 'Segundo Trimestre';
    else if (mes >= 8 && mes <= 10) trimestre = 'Tercer Trimestre';
    else if (mes >= 11 || mes == 1) trimestre = 'Cuarto Trimestre';
    document.getElementById('evento_trimestre').value = trimestre;
    
    // Cambiar texto del botón
    document.querySelector('input[type="submit"]').value = 'Actualizar';
}

// Función para nuevo evento (llamada cuando se hace clic en el botón +)
function nuevoEvento() {
    // Limpiar formulario
    limpiarFormulario();
    // Mostrar formulario
    ocul(1, 1);
}

// Validación de fechas
document.getElementById('formEvento').addEventListener('submit', function(e) {
    const fechaInicio = new Date(document.getElementById('evento_fecha_inicio').value);
    const fechaFin = new Date(document.getElementById('evento_fecha_fin').value);
    
    if (fechaFin < fechaInicio) {
        e.preventDefault();
        alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
        return false;
    }
});

// Auto-calcular trimestre y año basado en la fecha de inicio
document.getElementById('evento_fecha_inicio').addEventListener('change', function() {
    const fecha = new Date(this.value);
    const mes = fecha.getMonth() + 1;
    let trimestre = '';
    
    if (mes >= 2 && mes <= 4) trimestre = 'Primer Trimestre';
    else if (mes >= 5 && mes <= 7) trimestre = 'Segundo Trimestre';
    else if (mes >= 8 && mes <= 10) trimestre = 'Tercer Trimestre';
    else if (mes >= 11 || mes == 1) trimestre = 'Cuarto Trimestre';
    
    // Establecer trimestre y año automáticamente
    document.getElementById('evento_trimestre').value = trimestre;
    document.getElementById('evento_anio').value = fecha.getFullYear();
});

// Auto-calcular año cuando cambie la fecha de fin
document.getElementById('evento_fecha_fin').addEventListener('change', function() {
    const fecha = new Date(this.value);
    document.getElementById('evento_anio').value = fecha.getFullYear();
});

// Si hay un evento para editar, mostrar el formulario automáticamente
<?php if ($evento_editar): ?>
document.addEventListener('DOMContentLoaded', function() {
    editarEvento(<?= json_encode($evento_editar) ?>);
});
<?php endif; ?>

// Función para limpiar el formulario
function limpiarFormulario() {
    document.getElementById('formEvento').reset();
    document.getElementById('evento_id').value = '';
    document.getElementById('evento_ope').value = 'save';
    document.querySelector('input[type="submit"]').value = 'Guardar';
    document.getElementById('evento_anio').value = '';
    document.getElementById('evento_trimestre').value = '';
}

// Interceptar el clic en el botón + para limpiar el formulario
document.addEventListener('DOMContentLoaded', function() {
    // Buscar el botón + (icono fa-circle-plus)
    const botonMas = document.querySelector('#mas');
    if (botonMas) {
        botonMas.addEventListener('click', function() {
            // Limpiar formulario antes de mostrar
            limpiarFormulario();
        });
    }
});
</script>

<style>
/* Estilos personalizados para las tarjetas de estadísticas */
.card-cal-aca {
    border: 2px solid #00af00;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transition: transform 0.3s ease-in-out;
    height: 140px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.card-cal-aca:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.card-cal-aca .card-body {
    padding: 1rem;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.card-cal-aca h5 {
    font-size: 2.2rem;
    margin-bottom: 0.75rem;
    color: #ffffff;
}

.card-cal-aca h6 {
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-cal-aca h4 {
    font-size: 2.4rem;
    font-weight: 900;
    margin-bottom: 0.5rem;
    color: #ffffff;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.card-cal-aca small {
    font-size: 0.9rem;
    opacity: 0.95;
    color: #ffffff;
    font-weight: 600;
}

/* Asegurar que las tarjetas no se salgan del contenedor */
.row.g-3 {
    margin: 0;
    justify-content: center;
}

.col-4.col-md-3 {
    padding: 0.5rem;
    display: flex;
    justify-content: center;
}

/* Estilos para el título personalizado */
.tit {
    background: transparent;
    color: #00af00;
    padding: 0;
    margin-bottom: 2rem;
    box-shadow: none;
}

.tit h1 {
    margin: 0;
    font-size: 3.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    color: #00af00;
}

.tit h1 i {
    margin-right: 0.5rem;
    color: #00af00;
}

.lintit {
    border: none;
    height: 2px;
    background: #00af00;
    margin: 0.5rem 0 0 0;
}

/* Ajustar el verde del lado izquierdo para que sea más sutil */
.bg-success {
    background-color: #00af00 !important;
}

/* Estilos para el alert informativo */
.alert-success {
    border: 3px solid #00af00;
    background-color: #f0f9f4;
    color: #0f5132;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert-success h6 {
    color: #00af00;
    font-weight: bold;
    font-size: 1.3rem;
    text-align: center;
}

.alert-success p {
    font-size: 1.1rem;
    line-height: 1.6;
    text-align: justify;
}

/* Mejorar el centrado del contenido principal */
.conte {
    text-align: left;
}

/* Centrar mejor los filtros */
.filtro-estadisticas-form {
    display: flex;
    justify-content: center;
    align-items: center;
}

.filtro-estadisticas-form select {
    font-size: 1.1rem;
    padding: 0.75rem;
    border-radius: 10px;
    border: 2px solid #00af00;
    background-color: #ffffff;
    color: #00af00;
    font-weight: 600;
}

/* Mejorar la tabla */
.table {
    font-size: 1.1rem;
    background-color: white;
}

.table th {
    font-size: 1.2rem;
    font-weight: 700;
    text-align: center;
    background: #00af00;
    color: white;
    border: none;
    padding: 1rem;
}

.table td {
    text-align: center;
    vertical-align: middle;
    padding: 0.75rem;
    font-size: 1.05rem;
    background-color: white;
    color: black;
}

/* Estilos para las filas de la tabla */
.table-row-white {
    background-color: white !important;
    color: black !important;
}

.table-row-white td {
    background-color: white !important;
    color: black !important;
    border-color: #dee2e6;
}

.table-row-white:hover {
    background-color: #f8f9fa !important;
}

.table-row-white:hover td {
    background-color: #f8f9fa !important;
}

/* Mejorar los badges */
.badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    background-color: #00af00 !important;
    color: white !important;
}

.badge-text {
    font-size: 0.9rem;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-weight: 600;
    background-color: transparent !important;
    color: black !important;
    border: none;
}

/* Mejorar los botones de acción */
.btn-group .btn {
    font-size: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
}

/* Estilos para el formulario */
.form-calendario {
    background: #ffffff;
    padding: 2rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    text-align: left;
}

.form-calendario .form-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    text-align: left;
}

.form-calendario .form-control,
.form-calendario .form-select {
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    background-color: #ffffff;
    color: #495057;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    text-align: left;
}

.form-calendario .form-control:focus,
.form-calendario .form-select:focus {
    border-color: #00af00;
    box-shadow: 0 0 0 0.2rem rgba(0, 175, 0, 0.25);
    background-color: #ffffff;
    color: #495057;
}

.form-calendario .btn {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.15s ease-in-out;
    margin-right: 0.5rem;
    text-align: left;
}

.form-calendario .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.form-calendario .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.form-calendario .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.form-calendario .btn-secondary:hover {
    background-color: #545b62;
    border-color: #545b62;
}

/* Alinear los grupos de formulario a la izquierda */
.form-calendario .form-group {
    text-align: left;
}

.form-calendario .row {
    justify-content: flex-start;
}

/* Mejorar el mensaje cuando no hay eventos */
.alert-info {
    background: #f0f9f4;
    border: 3px solid #00af00;
    border-radius: 15px;
    color: #0f5132;
    font-size: 1.1rem;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert-info h5 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #00af00;
}

.alert-info p {
    font-size: 1.1rem;
    font-weight: 600;
}

/* Ocultar tarjetas en desktop por defecto */
.mobile-cards {
    display: none;
}

/* Responsive design mejorado */
@media (max-width: 768px) {
    /* Título responsive */
    .tit h1 {
        font-size: 2.2rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .tit h1 i {
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
    
    /* Botones de acción más grandes para móvil */
    .tit h1 i[id="mas"],
    .tit h1 i[id="menos"] {
        font-size: 2rem;
        padding: 0.5rem;
        margin: 0.5rem;
        cursor: pointer;
        border-radius: 50%;
        background-color: #00af00;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Tarjetas de estadísticas */
    .col-4.col-md-3 {
        padding: 0.25rem;
        margin-bottom: 1rem;
    }
    
    .card-cal-aca {
        height: 120px;
        margin-bottom: 1rem;
    }
    
    .card-cal-aca h5 {
        font-size: 1.5rem;
    }
    
    .card-cal-aca h4 {
        font-size: 1.8rem;
    }
    
    .card-cal-aca h6 {
        font-size: 0.9rem;
    }
    
    /* Formulario responsive */
    .form-calendario {
        padding: 1rem;
        margin: 1rem 0;
    }
    
    .form-calendario .form-control,
    .form-calendario .form-select {
        font-size: 1rem;
        padding: 0.75rem;
        min-height: 48px; /* Tamaño mínimo para tocar */
    }
    
    .form-calendario .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        min-height: 48px;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    /* Ocultar tabla en móvil */
    .table {
        display: none;
    }
    
    /* Diseño de tarjetas para móvil */
    .mobile-cards {
        display: block;
    }
    
    .evento-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        min-height: 200px;
    }
    
    .evento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .evento-header {
        border-bottom: 2px solid #00af00;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .evento-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #00af00;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .evento-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .evento-meta-item {
        font-size: 0.8rem;
        color: #6c757d;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
    
    .evento-meta-item strong {
        color: #495057;
    }
    
    .evento-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .evento-detail {
        display: flex;
        flex-direction: column;
    }
    
    .evento-detail-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .evento-detail-value {
        font-size: 1.1rem;
        color: #495057;
        font-weight: 600;
    }
    
    .evento-descripcion {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        color: #495057;
        line-height: 1.5;
    }
    
    
    .duracion-badge {
        background: #00af00;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .tipo-badge {
        background: #6c757d;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    /* Badges más pequeños en móvil */
    .badge-text {
        font-size: 0.8rem;
        padding: 0.2rem 0.4rem;
    }
    
    /* Alertas responsive */
    .alert-info {
        font-size: 1rem;
        padding: 1rem;
    }
    
    .alert-info h5 {
        font-size: 1.2rem;
    }
    
    .alert-info p {
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    /* Título extra pequeño */
    .tit h1 {
        font-size: 1.8rem;
    }
    
    /* Tarjetas más compactas */
    .card-cal-aca {
        height: 100px;
    }
    
    .card-cal-aca h5 {
        font-size: 1.2rem;
    }
    
    .card-cal-aca h4 {
        font-size: 1.5rem;
    }
    
    .card-cal-aca h6 {
        font-size: 0.8rem;
    }
    
    /* Formulario más compacto */
    .form-calendario {
        padding: 0.75rem;
    }
    
    .form-calendario .form-control,
    .form-calendario .form-select {
        font-size: 0.95rem;
        padding: 0.6rem;
    }
    
    /* Tabla más compacta */
    .table {
        font-size: 0.8rem;
        min-width: 500px;
    }
    
    .table th {
        font-size: 0.9rem;
        padding: 0.5rem 0.25rem;
    }
    
    .table td {
        font-size: 0.75rem;
        padding: 0.4rem 0.2rem;
    }
    
    /* Botones más pequeños pero táctiles */
    .tit h1 i[id="mas"],
    .tit h1 i[id="menos"] {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
    }
}

/* Mejoras adicionales para tablets */
@media (min-width: 769px) and (max-width: 1024px) {
    .tit h1 {
        font-size: 2.8rem;
    }
    
    .card-cal-aca {
        height: 130px;
    }
    
    .table {
        font-size: 1rem;
    }
}

/* Mejoras para pantallas muy pequeñas (menos de 400px) */
@media (max-width: 400px) {
    .tit h1 {
        font-size: 1.5rem;
    }
    
    .card-cal-aca {
        height: 90px;
    }
    
    .card-cal-aca h5 {
        font-size: 1rem;
    }
    
    .card-cal-aca h4 {
        font-size: 1.3rem;
    }
    
    .card-cal-aca h6 {
        font-size: 0.75rem;
    }
    
    .table {
        min-width: 450px;
        font-size: 0.75rem;
    }
    
    .table th {
        font-size: 0.8rem;
        padding: 0.4rem 0.2rem;
    }
    
    .table td {
        font-size: 0.7rem;
        padding: 0.3rem 0.1rem;
    }
}
</style>

