<?php
// Inicializar variables si no están definidas
if (!isset($icono)) {
    $icono = 'fa-solid fa-calendar-alt';
}
?>

<!-- Modal para Planes de Sesión -->
<div class="modal fade" id="modalPlanesSesion" tabindex="-1" aria-labelledby="modalPlanesSesionLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down" style="max-width: 95vw; width: 95vw;">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalPlanesSesionLabel">
                    <i class="fa fa-calendar-alt me-2"></i>Gestión de Planes de Sesión
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Información del Resultado de Aprendizaje -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="fa fa-info-circle"></i> Información del Resultado de Aprendizaje</h6>
                            <div id="info-resultado">
                                <p><strong>Resultado:</strong> <span id="nomres"></span></p>
                                <p><strong>Número de Sesiones:</strong> <span id="ndeses"></span></p>
                                <p><strong>Competencia:</strong> <span id="descom"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row mb-4" id="estadisticas-container" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h6><i class="fa fa-chart-bar"></i> Estadísticas</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Sesiones Creadas:</strong> <span id="total-sesiones"></span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Total Actividades:</strong> <span id="total-actividades"></span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Tiempo Total:</strong> <span id="tiempo-total"></span> min
                                </div>
                                <div class="col-md-3">
                                    <strong>Estado:</strong> <span id="estado-general"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Sesiones Disponibles -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h6><i class="fa fa-calendar-check"></i> Sesiones del Resultado</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Total Sesiones:</strong> <span id="total-sesiones-disponibles">0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Creadas:</strong> <span id="sesiones-creadas">0</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>Pendientes:</strong> <span id="sesiones-pendientes">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selector de Sesión Mejorado -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="selector-sesion" class="form-label">Seleccionar Sesión:</label>
                        <select id="selector-sesion" class="form-select">
                            <option value="">Seleccione una sesión...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Acción Rápida:</label>
                        <button type="button" class="btn btn-primary w-100" id="btn-siguiente-sesion">
                            <i class="fa fa-plus"></i> Siguiente Sesión
                            </button>
                    </div>
                </div>

                <!-- Formulario de Sesión -->
                <div id="formulario-sesion" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titulo-sesion" class="form-label">Título de la Sesión:</label>
                            <input type="text" id="titulo-sesion" class="form-control" placeholder="Ej: Sesión 1 - Introducción a...">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha-programada" class="form-label">Fecha Programada:</label>
                            <input type="date" id="fecha-programada" class="form-control">
                        </div>
                    </div>
                    
                    <!-- Estado de la Sesión Actual -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-info-circle"></i> Estado de la Sesión</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Estado actual: <strong id="estado-sesion-actual">PENDIENTE</strong></span>
                        <div>
                            <!-- Botones de estado comentados temporalmente
                            <button class="btn btn-sm btn-warning" id="btn-marcar-en-curso" style="display: none;">
                                <i class="fa fa-play"></i> En Curso
                            </button>
                            <button class="btn btn-sm btn-success" id="btn-marcar-completada" style="display: none;">
                                <i class="fa fa-check"></i> Completada
                            </button>
                            <button class="btn btn-sm btn-primary" id="btn-completar-toda-sesion" style="display: none;">
                                <i class="fa fa-check-double"></i> Completar Toda la Sesión
                            </button>
                            -->
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Nueva Sesión -->
                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" id="btn-nueva-sesion">
                                <i class="fa fa-plus"></i> Nueva Sesión
                            </button>
                        </div>
                    </div>

                    <!-- Actividades por Fase -->
                    <div class="accordion" id="accordionFases">
                        <!-- Fase INICIO -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingInicio">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInicio" aria-expanded="true">
                                    <i class="fa fa-play-circle me-2" style="color: #00af00;"></i>Fase INICIO
                                </button>
                            </h2>
                            <div id="collapseInicio" class="accordion-collapse collapse show" data-bs-parent="#accordionFases">
                                <div class="accordion-body">
                                    <!-- Contenedor de actividades -->
                                    <div id="actividades-inicio" class="fase-actividades">
                                        <!-- Las actividades se agregarán dinámicamente -->
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="fase-actions text-end mt-3">
                                        <button type="button" class="btn btn-success" onclick="agregarActividad('inicio')">
                                            <i class="fa fa-plus me-1"></i> Agregar Actividad
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fase DESARROLLO -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingDesarrollo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDesarrollo">
                                    <i class="fa fa-cogs me-2" style="color: #00af00;"></i>Fase DESARROLLO
                                </button>
                            </h2>
                            <div id="collapseDesarrollo" class="accordion-collapse collapse" data-bs-parent="#accordionFases">
                                <div class="accordion-body">
                                    <!-- Contenedor de actividades -->
                                    <div id="actividades-desarrollo" class="fase-actividades">
                                        <!-- Las actividades se agregarán dinámicamente -->
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="fase-actions text-end mt-3">
                                        <button type="button" class="btn btn-success" onclick="agregarActividad('desarrollo')">
                                            <i class="fa fa-plus me-1"></i> Agregar Actividad
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fase CIERRE -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCierre">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCierre">
                                    <i class="fa fa-flag-checkered me-2" style="color: #00af00;"></i>Fase CIERRE
                                </button>
                            </h2>
                            <div id="collapseCierre" class="accordion-collapse collapse" data-bs-parent="#accordionFases">
                                <div class="accordion-body">
                                    <!-- Contenedor de actividades -->
                                    <div id="actividades-cierre" class="fase-actividades">
                                        <!-- Las actividades se agregarán dinámicamente -->
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="fase-actions text-end mt-3">
                                        <button type="button" class="btn btn-success" onclick="agregarActividad('cierre')">
                                            <i class="fa fa-plus me-1"></i> Agregar Actividad
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Tiempo -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-clock"></i> Resumen de Tiempo</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>INICIO:</strong> <span id="tiempo-inicio">0</span> min
                                    </div>
                                    <div class="col-md-3">
                                        <strong>DESARROLLO:</strong> <span id="tiempo-desarrollo">0</span> min
                                    </div>
                                    <div class="col-md-3">
                                        <strong>CIERRE:</strong> <span id="tiempo-cierre">0</span> min
                                    </div>
                                    <div class="col-md-3">
                                        <strong>TOTAL:</strong> <span id="tiempo-total-sesion">0</span> min
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de Acción del Formulario -->
                    <div class="form-group mt-4 d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-success" id="btn-guardar-sesion" style="display: none;">
                            <i class="fa fa-save"></i> Guardar Sesión
                        </button>
                        <button type="button" class="btn btn-danger" id="btn-eliminar-sesion" style="display: none;">
                            <i class="fa fa-trash"></i> Eliminar Sesión
                        </button>
                    </div>
                </div>

                <!-- Lista de Sesiones Existentes -->
                <div id="lista-sesiones" style="display: none;">
                    <h6><i class="fa fa-list"></i> Sesiones Existentes</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sesión</th>
                                    <th>Título</th>
                                    <th>Fecha</th>
                                    <th>Actividades</th>
                                    <th>Tiempo Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-sesiones">
                                <!-- Las sesiones se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let idresActual = null;
let idficActual = null;
let ndesesActual = 0;
let sesionActual = null;
let contadorActividades = 0;

// Función para abrir el modal de planes de sesión
function abrirModalPlanesSesion(idres, nomres, ndeses) {
    idresActual = idres;
    idficActual = getUrlParameter('idfic');
    ndesesActual = ndeses;
    
    // Actualizar información del resultado
    document.getElementById('nomres').textContent = nomres;
    document.getElementById('ndeses').textContent = ndeses;
    
    // Limpiar formulario
    limpiarFormulario();
    
    // Cargar sesiones existentes
    cargarSesionesExistentes();
    
    // Mostrar modal
    $('#modalPlanesSesion').modal('show');
}

// Función para cargar sesiones existentes
function cargarSesionesExistentes() {
    fetch(`controllers/cplanses.php?opera=get_planes_resultado&idres=${idresActual}&idfic=${idficActual}`)
        .then(response => response.json())
        .then(data => {
            console.log('=== DEBUG cargarSesionesExistentes ===');
            console.log('Datos recibidos:', data);
            
            if (data.success) {
                // Actualizar información del resultado de aprendizaje
                if (data.info_resultado) {
                    console.log('Info resultado recibida:', data.info_resultado);
                    document.getElementById('nomres').textContent = data.info_resultado.nomres || 'No disponible';
                    document.getElementById('ndeses').textContent = data.info_resultado.ndeses || 0;
                    document.getElementById('descom').textContent = data.info_resultado.descom || 'No disponible';
                    console.log('Información del resultado actualizada');
                } else {
                    console.warn('No se recibió información del resultado');
                    document.getElementById('nomres').textContent = 'No disponible';
                    document.getElementById('ndeses').textContent = '0';
                    document.getElementById('descom').textContent = 'No disponible';
                }
                
                // Actualizar estadísticas
                if (data.estadisticas) {
                    document.getElementById('total-sesiones').textContent = data.estadisticas.total_sesiones || 0;
                    document.getElementById('total-actividades').textContent = data.estadisticas.total_actividades || 0;
                    document.getElementById('tiempo-total').textContent = data.estadisticas.tiempo_total_minutos || 0;
                    document.getElementById('estado-general').textContent = 'Activo';
                    document.getElementById('estadisticas-container').style.display = 'block';
                }
                
                // Poblar selector de sesiones
                poblarSelectorSesiones(data.planes);
                
                // Mostrar lista de sesiones
                mostrarListaSesiones(data.planes);
            } else {
                console.error('Error en la respuesta del servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al cargar sesiones:', error);
        });
}

// Función para poblar el selector de sesiones con indicadores visuales
function poblarSelectorSesiones(planes) {
    const selector = document.getElementById('selector-sesion');
    selector.innerHTML = '<option value="">Seleccione una sesión...</option>';
    
    let sesionesCreadas = 0;
    
    // Agregar opciones para todas las sesiones posibles
    for (let i = 1; i <= ndesesActual; i++) {
        const planExistente = planes.find(p => p.numses == i);
        let texto, icono, clase;
        
        if (planExistente) {
            sesionesCreadas++;
            icono = '✅';
            clase = 'text-success';
            texto = `Sesión ${i} - ${planExistente.titulo_sesion || 'Sin título'}`;
        } else {
            icono = '⭕';
            clase = 'text-muted';
            texto = `Sesión ${i} - Nueva`;
        }
        
        selector.innerHTML += `<option value="${i}" class="${clase}">${icono} ${texto}</option>`;
    }
    
    // Actualizar información de sesiones
    actualizarInformacionSesiones(sesionesCreadas);
}

// Función para cambiar estado de sesión
function cambiarEstado(numses, nuevoEstado) {
    if (!confirm(`¿Estás seguro de cambiar el estado de la sesión ${numses} a ${nuevoEstado}?`)) {
        return;
    }
    
    const idfic = idficActual;
    const idres = idresActual;
    
    $.ajax({
        url: 'controllers/cplanses.php',
        type: 'POST',
        data: {
            opera: 'actualizar_estado',
            idfic: idfic,
            idres: idres,
            numses: numses,
            estado: nuevoEstado
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Estado actualizado',
                    text: `La sesión ${numses} ahora está en estado ${nuevoEstado}`,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Recargar la lista de sesiones
                cargarSesionesExistentes();
                
                // Si estamos editando esta sesión, actualizar el estado en el formulario
                if (parseInt(document.getElementById('numses').value) === numses) {
                    actualizarEstadoFormulario(nuevoEstado);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al actualizar el estado'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error de conexión al actualizar el estado'
            });
        }
    });
}

// Función para actualizar el estado en el formulario
function actualizarEstadoFormulario(estado) {
    const estadoElement = document.getElementById('estado-sesion-actual');
    // Botones de estado comentados temporalmente
    // const btnEnCurso = document.getElementById('btn-marcar-en-curso');
    // const btnCompletada = document.getElementById('btn-marcar-completada');
    // const btnCompletarTodaSesion = document.getElementById('btn-completar-toda-sesion');
    
    if (estadoElement) {
        estadoElement.textContent = estado;
        estadoElement.className = `badge bg-${getColorEstado(estado)}`;
    }
    
    // Mostrar/ocultar botones según el estado - COMENTADO TEMPORALMENTE
    /*
    if (btnEnCurso && btnCompletada && btnCompletarTodaSesion) {
        btnEnCurso.style.display = estado === 'PENDIENTE' ? 'inline-block' : 'none';
        btnCompletada.style.display = (estado === 'PENDIENTE' || estado === 'EN_CURSO') ? 'inline-block' : 'none';
        btnCompletarTodaSesion.style.display = (estado === 'PENDIENTE' || estado === 'EN_CURSO') ? 'inline-block' : 'none';
    }
    */
}

// Función para completar toda la sesión
function completarTodaSesion() {
    const numses = document.getElementById('numses').value;
    const idfic = document.getElementById('idfic').value;
    const idres = document.getElementById('idres').value;
    
    if (!numses || !idfic || !idres) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se puede completar la sesión. Faltan datos requeridos.'
        });
        return;
    }
    
    Swal.fire({
        title: '¿Completar toda la sesión?',
        text: `¿Estás seguro de marcar como COMPLETADA toda la sesión ${numses}? Esto cambiará el estado de todas las actividades de todas las fases.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#00af00',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, completar toda la sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Completando sesión...',
                text: 'Por favor espera mientras se actualiza el estado de todas las actividades.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: 'controllers/cplanses.php',
                type: 'POST',
                data: {
                    opera: 'completar_toda_sesion',
                    idfic: idfic,
                    idres: idres,
                    numses: numses
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Sesión completada!',
                            text: `La sesión ${numses} y todas sus actividades han sido marcadas como COMPLETADAS.`,
                            timer: 3000,
                            showConfirmButton: false
                        });
                        
                        // Actualizar el estado en el formulario
                        actualizarEstadoFormulario('COMPLETADA');
                        
                        // Recargar la lista de sesiones
                        cargarSesionesExistentes();
                        
                        // Actualizar información de sesiones
                        actualizarInformacionSesiones();
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Error al completar la sesión'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de conexión al completar la sesión'
                    });
                }
            });
        }
    });
}

// Función para actualizar información de sesiones
function actualizarInformacionSesiones(sesionesCreadas) {
    const totalSesiones = ndesesActual;
    const sesionesPendientes = totalSesiones - sesionesCreadas;
    
    document.getElementById('total-sesiones-disponibles').textContent = totalSesiones;
    document.getElementById('sesiones-creadas').textContent = sesionesCreadas;
    document.getElementById('sesiones-pendientes').textContent = sesionesPendientes;
    
    // Actualizar botón de siguiente sesión
    const btnSiguiente = document.getElementById('btn-siguiente-sesion');
    if (sesionesPendientes > 0) {
        const siguienteSesion = sesionesCreadas + 1;
        btnSiguiente.innerHTML = `<i class="fa fa-plus"></i> Crear Sesión ${siguienteSesion}`;
        btnSiguiente.disabled = false;
        btnSiguiente.className = 'btn btn-primary w-100';
    } else {
        btnSiguiente.innerHTML = '<i class="fa fa-check"></i> Todas Creadas';
        btnSiguiente.disabled = true;
        btnSiguiente.className = 'btn btn-success w-100';
    }
}

// Función para mostrar lista de sesiones
function mostrarListaSesiones(planes) {
    console.log('=== DEBUG mostrarListaSesiones ===');
    console.log('Planes recibidos:', planes);
    console.log('Cantidad de planes:', planes.length);
    
    const tbody = document.getElementById('tabla-sesiones');
    if (!tbody) {
        console.error('No se encontró el elemento tbody con id "tabla-sesiones"');
        return;
    }
    
    tbody.innerHTML = '';
    
    if (planes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay sesiones creadas</td></tr>';
        return;
    }
    
    planes.forEach((plan, index) => {
        console.log(`Procesando plan ${index}:`, plan);
        console.log(`Estado del plan: "${plan.estado}"`);
        
        const actividades = plan.actividades ? plan.actividades.split('||').length : 0;
        const tiempoTotal = calcularTiempoTotalActividades(plan.actividades);
        
        // Debug para botones de estado
        console.log(`Estado: "${plan.estado}"`);
        console.log(`¿Es PENDIENTE? ${plan.estado === 'PENDIENTE'}`);
        console.log(`¿Es EN_CURSO? ${plan.estado === 'EN_CURSO'}`);
        console.log(`¿Es COMPLETADA? ${plan.estado === 'COMPLETADA'}`);
        
        // Generar botones de estado de forma directa con estilos forzados
        let botonesEstado = '';
        if (plan.estado === 'PENDIENTE') {
            botonesEstado = '<button class="btn btn-sm btn-success" onclick="cambiarEstado(' + plan.numses + ', \'COMPLETADA\')" title="Marcar como Completada" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: auto !important; height: auto !important;"><i class="fa fa-check-circle text-success"></i></button>';
            console.log('Generando botón para PENDIENTE');
        } else if (plan.estado === 'EN_CURSO') {
            botonesEstado = '<button class="btn btn-sm btn-success" onclick="cambiarEstado(' + plan.numses + ', \'COMPLETADA\')" title="Marcar como Completada" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: auto !important; height: auto !important;"><i class="fa fa-check-circle text-success"></i></button>' +
                          '<button class="btn btn-sm btn-warning" onclick="cambiarEstado(' + plan.numses + ', \'PENDIENTE\')" title="Volver a Pendiente" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: auto !important; height: auto !important;"><i class="fa fa-clock text-warning"></i></button>';
            console.log('Generando botones para EN_CURSO');
        } else if (plan.estado === 'COMPLETADA') {
            botonesEstado = '<button class="btn btn-sm btn-warning" onclick="cambiarEstado(' + plan.numses + ', \'EN_CURSO\')" title="Marcar como En Curso" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: auto !important; height: auto !important;"><i class="fa fa-play text-warning"></i></button>' +
                          '<button class="btn btn-sm btn-secondary" onclick="cambiarEstado(' + plan.numses + ', \'PENDIENTE\')" title="Volver a Pendiente" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important; width: auto !important; height: auto !important;"><i class="fa fa-clock text-warning"></i></button>';
            console.log('Generando botones para COMPLETADA');
        }
        
        console.log('Botones generados:', botonesEstado);
        
        // Crear fila usando inserción directa SIN template literals
        const fila = document.createElement('tr');
        fila.innerHTML = 
            '<td>Sesión ' + plan.numses + '</td>' +
            '<td>' + (plan.titulo_sesion || 'Sin título') + '</td>' +
            '<td>' + (plan.fecha_programada || 'No programada') + '</td>' +
            '<td>' + actividades + ' actividades</td>' +
            '<td>' + tiempoTotal + ' min</td>' +
            '<td><span class="badge bg-' + getColorEstado(plan.estado) + '">' + plan.estado + '</span></td>' +
            '<td>' +
                '<div class="btn-group btn-group-sm" role="group">' +
                    '<button class="btn btn-sm btn-primary" onclick="editarSesion(' + plan.numses + ')" title="Editar Sesión">' +
                        '<i class="fa fa-edit"></i>' +
                    '</button>' +
                    botonesEstado +
                    '<button class="btn btn-sm btn-danger" onclick="eliminarSesion(' + plan.numses + ')" title="Eliminar Sesión">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</div>' +
            '</td>';
        
        tbody.appendChild(fila);
        console.log('Fila agregada al DOM:', fila);
        console.log('Contenido de la fila:', fila.innerHTML);
    });
    
    document.getElementById('lista-sesiones').style.display = 'block';
}

// Función para calcular tiempo total de actividades
function calcularTiempoTotalActividades(actividadesStr) {
    if (!actividadesStr) return 0;
    
    let total = 0;
    const actividades = actividadesStr.split('||');
    actividades.forEach(actividad => {
        const partes = actividad.split('|');
        if (partes.length >= 3) {
            total += parseInt(partes[2]) || 0;
        }
    });
    return total;
}

// Función para obtener color del estado
function getColorEstado(estado) {
    switch(estado) {
        case 'COMPLETADA': return 'success';
        case 'EN_CURSO': return 'warning';
        case 'PENDIENTE': return 'secondary';
        default: return 'secondary';
    }
}

// Event listener para el selector de sesiones
document.getElementById('selector-sesion').addEventListener('change', function() {
    const numses = this.value;
    if (numses) {
        sesionActual = numses;
        cargarSesion(numses);
    } else {
        limpiarFormulario();
    }
});

// Función para cargar una sesión específica
function cargarSesion(numses) {
    fetch(`controllers/cplanses.php?opera=get_plan_sesion&idres=${idresActual}&idfic=${idficActual}&numses=${numses}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.planes.length > 0) {
                    // Cargar datos existentes
                    const primerPlan = data.planes[0];
                    document.getElementById('titulo-sesion').value = primerPlan.titulo_sesion || '';
                    document.getElementById('fecha-programada').value = primerPlan.fecha_programada || '';
                    
                    // Actualizar estado en el formulario
                    actualizarEstadoFormulario(primerPlan.estado);
                    
                    // Cargar actividades por fase
                    cargarActividadesPorFase(data.planes);
                } else {
                    // Nueva sesión
                    limpiarActividades();
                }
                
                document.getElementById('formulario-sesion').style.display = 'block';
                document.getElementById('btn-guardar-sesion').style.display = 'inline-block';
                document.getElementById('btn-eliminar-sesion').style.display = 'inline-block';
            }
        })
        .catch(error => {
            console.error('Error al cargar sesión:', error);
        });
}

// Función para cargar actividades por fase
function cargarActividadesPorFase(planes) {
    limpiarActividades();
    
    const actividadesPorFase = {
        'INICIO': [],
        'DESARROLLO': [],
        'CIERRE': []
    };
    
    planes.forEach(plan => {
        if (actividadesPorFase[plan.fas]) {
            actividadesPorFase[plan.fas].push(plan);
        }
    });
    
    // Cargar actividades de cada fase
    Object.keys(actividadesPorFase).forEach(fase => {
        const faseLower = fase.toLowerCase();
        actividadesPorFase[fase].forEach(actividad => {
            agregarActividad(faseLower, actividad);
        });
    });
    
    actualizarResumenTiempo();
}

// Función para agregar actividad
function agregarActividad(fase, datosActividad = null) {
    contadorActividades++;
    const containerId = `actividades-${fase}`;
    const container = document.getElementById(containerId);
    
    const actividadHtml = `
        <div class="actividad-item mb-3 p-3 border rounded" data-contador="${contadorActividades}">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Actividad de Aprendizaje:</label>
                    <input type="text" class="form-control actividad-actapr" 
                           value="${datosActividad ? datosActividad.actapr : ''}" 
                           placeholder="Descripción de la actividad">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tiempo (min):</label>
                    <input type="number" class="form-control actividad-tmp" 
                           value="${datosActividad ? datosActividad.tmp : ''}" 
                           min="1" onchange="actualizarResumenTiempo()">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Acciones:</label>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-sm btn-danger btn-eliminar-actividad" onclick="eliminarActividad(${contadorActividades})" title="Eliminar actividad">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Contenido:</label>
                    <textarea class="form-control actividad-cont" rows="2" 
                              placeholder="Contenido específico de la actividad">${datosActividad ? datosActividad.cont : ''}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Material de Formación:</label>
                    <textarea class="form-control actividad-matfor" rows="2" 
                              placeholder="Materiales necesarios">${datosActividad ? datosActividad.matfor : ''}</textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', actividadHtml);
    actualizarResumenTiempo();
}

// Función para eliminar actividad
function eliminarActividad(contador) {
    const actividad = document.querySelector(`[data-contador="${contador}"]`);
    if (actividad) {
        actividad.remove();
        actualizarResumenTiempo();
    }
}

// Función para actualizar resumen de tiempo
function actualizarResumenTiempo() {
    let tiempoInicio = 0;
    let tiempoDesarrollo = 0;
    let tiempoCierre = 0;
    
    // Calcular tiempo de INICIO
    document.querySelectorAll('#actividades-inicio .actividad-tmp').forEach(input => {
        tiempoInicio += parseInt(input.value) || 0;
    });
    
    // Calcular tiempo de DESARROLLO
    document.querySelectorAll('#actividades-desarrollo .actividad-tmp').forEach(input => {
        tiempoDesarrollo += parseInt(input.value) || 0;
    });
    
    // Calcular tiempo de CIERRE
    document.querySelectorAll('#actividades-cierre .actividad-tmp').forEach(input => {
        tiempoCierre += parseInt(input.value) || 0;
    });
    
    const tiempoTotal = tiempoInicio + tiempoDesarrollo + tiempoCierre;
    
    document.getElementById('tiempo-inicio').textContent = tiempoInicio;
    document.getElementById('tiempo-desarrollo').textContent = tiempoDesarrollo;
    document.getElementById('tiempo-cierre').textContent = tiempoCierre;
    document.getElementById('tiempo-total-sesion').textContent = tiempoTotal;
}

// Función para limpiar actividades
function limpiarActividades() {
    document.getElementById('actividades-inicio').innerHTML = '';
    document.getElementById('actividades-desarrollo').innerHTML = '';
    document.getElementById('actividades-cierre').innerHTML = '';
    contadorActividades = 0;
    actualizarResumenTiempo();
}

// Función para limpiar formulario
function limpiarFormulario() {
    document.getElementById('titulo-sesion').value = '';
    document.getElementById('fecha-programada').value = '';
    document.getElementById('selector-sesion').value = '';
    
    // Resetear estado a PENDIENTE
    actualizarEstadoFormulario('PENDIENTE');
    
    limpiarActividades();
    document.getElementById('formulario-sesion').style.display = 'none';
    document.getElementById('btn-guardar-sesion').style.display = 'none';
    document.getElementById('btn-eliminar-sesion').style.display = 'none';
    sesionActual = null;
}

// Event listeners con delegación de eventos para elementos dinámicos
$(document).ready(function() {
    // Event listener para nueva sesión
    $(document).on('click', '#btn-nueva-sesion', function() {
        // Encontrar la siguiente sesión disponible
        let siguienteSesion = 1;
        const opciones = document.querySelectorAll('#selector-sesion option');
        for (let i = 1; i <= ndesesActual; i++) {
            if (!opciones[i]) break;
            if (opciones[i].textContent.includes('Nueva')) {
                siguienteSesion = i;
                break;
            }
        }
        
        document.getElementById('selector-sesion').value = siguienteSesion;
        sesionActual = siguienteSesion;
        limpiarActividades();
        document.getElementById('formulario-sesion').style.display = 'block';
        document.getElementById('btn-guardar-sesion').style.display = 'inline-block';
        document.getElementById('btn-eliminar-sesion').style.display = 'none';
        
        // Limpiar campos del formulario
        document.getElementById('titulo-sesion').value = '';
        document.getElementById('fecha-programada').value = '';
    });

    // Event listener para siguiente sesión (botón inteligente)
    $(document).on('click', '#btn-siguiente-sesion', function() {
        // Encontrar la siguiente sesión disponible
        let siguienteSesion = 1;
        const opciones = document.querySelectorAll('#selector-sesion option');
        for (let i = 1; i <= ndesesActual; i++) {
            if (!opciones[i]) break;
            if (opciones[i].textContent.includes('Nueva')) {
                siguienteSesion = i;
                break;
            }
        }
        
        // Seleccionar la sesión en el dropdown
        document.getElementById('selector-sesion').value = siguienteSesion;
        
        // Disparar el evento change del selector
        $('#selector-sesion').trigger('change');
    });

    // Event listener para guardar sesión
    $(document).on('click', '#btn-guardar-sesion', function() {
        guardarSesion();
    });

    // Event listener para eliminar sesión
    $(document).on('click', '#btn-eliminar-sesion', function() {
        if (sesionActual && confirm('¿Está seguro de eliminar esta sesión?')) {
            eliminarSesion(sesionActual);
        }
    });

    // Event listener para selector de sesión
    $(document).on('change', '#selector-sesion', function() {
        const numses = $(this).val();
        if (numses) {
            sesionActual = parseInt(numses);
            cargarSesion(numses);
        }
    });

    // Event listeners para botones de estado - COMENTADOS TEMPORALMENTE
    /*
    $(document).on('click', '#btn-marcar-en-curso', function() {
        const numses = document.getElementById('numses').value;
        cambiarEstado(parseInt(numses), 'EN_CURSO');
    });

    $(document).on('click', '#btn-marcar-completada', function() {
        const numses = document.getElementById('numses').value;
        cambiarEstado(parseInt(numses), 'COMPLETADA');
    });

    $(document).on('click', '#btn-completar-toda-sesion', function() {
        completarTodaSesion();
    });
    */

    // Event listeners para actualizar tiempo en tiempo real
    $(document).on('input change', '.actividad-tmp', function() {
        actualizarTiempoTotal();
    });

    $(document).on('click', '.btn-eliminar-actividad', function() {
        // Actualizar tiempo después de eliminar actividad
        setTimeout(actualizarTiempoTotal, 100);
    });
});

// Función para guardar sesión
function guardarSesion() {
    const tituloSesion = document.getElementById('titulo-sesion').value;
    const fechaProgramada = document.getElementById('fecha-programada').value;
    
    if (!tituloSesion) {
        alert('Debe ingresar un título para la sesión');
        return;
    }
    
    // Recopilar actividades
    const actividades = [];
    
    // Actividades de INICIO
    document.querySelectorAll('#actividades-inicio .actividad-item').forEach(item => {
        const actapr = item.querySelector('.actividad-actapr').value;
        const tmp = item.querySelector('.actividad-tmp').value;
        const cont = item.querySelector('.actividad-cont').value;
        const matfor = item.querySelector('.actividad-matfor').value;
        
        if (actapr && tmp) {
            actividades.push({
                fas: 'INICIO',
                actapr: actapr,
                tmp: parseInt(tmp),
                cont: cont,
                matfor: matfor
            });
        }
    });
    
    // Actividades de DESARROLLO
    document.querySelectorAll('#actividades-desarrollo .actividad-item').forEach(item => {
        const actapr = item.querySelector('.actividad-actapr').value;
        const tmp = item.querySelector('.actividad-tmp').value;
        const cont = item.querySelector('.actividad-cont').value;
        const matfor = item.querySelector('.actividad-matfor').value;
        
        if (actapr && tmp) {
            actividades.push({
                fas: 'DESARROLLO',
                actapr: actapr,
                tmp: parseInt(tmp),
                cont: cont,
                matfor: matfor
            });
        }
    });
    
    // Actividades de CIERRE
    document.querySelectorAll('#actividades-cierre .actividad-item').forEach(item => {
        const actapr = item.querySelector('.actividad-actapr').value;
        const tmp = item.querySelector('.actividad-tmp').value;
        const cont = item.querySelector('.actividad-cont').value;
        const matfor = item.querySelector('.actividad-matfor').value;
        
        if (actapr && tmp) {
            actividades.push({
                fas: 'CIERRE',
                actapr: actapr,
                tmp: parseInt(tmp),
                cont: cont,
                matfor: matfor
            });
        }
    });
    
    if (actividades.length === 0) {
        alert('Debe agregar al menos una actividad');
        return;
    }
    
    // Preparar datos para envío
    const formData = new FormData();
    formData.append('opera', 'save');
    formData.append('idfic', idficActual);
    formData.append('idres', idresActual);
    formData.append('numses', sesionActual);
    formData.append('titulo_sesion', tituloSesion);
    formData.append('fecha_programada', fechaProgramada);
    
    // Agregar actividades
    actividades.forEach((actividad, index) => {
        const fase = actividad.fas.toLowerCase();
        formData.append(`actividades_${fase}[${index}][actapr]`, actividad.actapr);
        formData.append(`actividades_${fase}[${index}][tmp]`, actividad.tmp);
        formData.append(`actividades_${fase}[${index}][cont]`, actividad.cont);
        formData.append(`actividades_${fase}[${index}][matfor]`, actividad.matfor);
    });
    
    // Enviar datos
    fetch('controllers/cplanses.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            const mensaje = `Sesión ${sesionActual} guardada correctamente`;
            
            // Usar SweetAlert si está disponible, sino alert normal
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: mensaje,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert(mensaje);
            }
            
            // Recargar sesiones y actualizar información
            cargarSesionesExistentes();
            limpiarFormulario();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar la sesión');
    });
}

// Función para editar sesión
function editarSesion(numses) {
    document.getElementById('selector-sesion').value = numses;
    sesionActual = numses;
    cargarSesion(numses);
}

// Función para eliminar sesión
function eliminarSesion(numses) {
    if (confirm(`¿Está seguro de eliminar la sesión ${numses}?`)) {
        fetch(`controllers/cplanses.php?opera=delete_plan_sesion&idres=${idresActual}&idfic=${idficActual}&numses=${numses}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sesión eliminada correctamente');
                    cargarSesionesExistentes();
                    limpiarFormulario();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la sesión');
            });
    }
}

// Función auxiliar para obtener parámetros de URL
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}



// Función para actualizar tiempo total
function actualizarTiempoTotal() {
    let tiempoInicio = 0;
    let tiempoDesarrollo = 0;
    let tiempoCierre = 0;
    
    // Calcular tiempo de INICIO
    document.querySelectorAll('#actividades-inicio .actividad-tmp').forEach(input => {
        tiempoInicio += parseInt(input.value) || 0;
    });
    
    // Calcular tiempo de DESARROLLO
    document.querySelectorAll('#actividades-desarrollo .actividad-tmp').forEach(input => {
        tiempoDesarrollo += parseInt(input.value) || 0;
    });
    
    // Calcular tiempo de CIERRE
    document.querySelectorAll('#actividades-cierre .actividad-tmp').forEach(input => {
        tiempoCierre += parseInt(input.value) || 0;
    });
    
    const tiempoTotal = tiempoInicio + tiempoDesarrollo + tiempoCierre;
    
    // Actualizar badges
    document.getElementById('tiempo-inicio').textContent = tiempoInicio;
    document.getElementById('tiempo-desarrollo').textContent = tiempoDesarrollo;
    document.getElementById('tiempo-cierre').textContent = tiempoCierre;
    document.getElementById('tiempo-total-sesion').textContent = tiempoTotal;
}
</script>

<style>
.actividad-item {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

.actividad-item:hover {
    background-color: #e9ecef;
}

.actividad-item .row {
    align-items: end;
}

.actividad-item .form-label {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.actividad-item .btn {
    margin-top: 0.25rem;
}

.accordion-button:not(.collapsed) {
    background-color: #d1e7dd;
    border-color: #badbcc;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
}

.badge {
    font-size: 0.8em;
}

/* Estilos para el selector de sesiones mejorado */
#selector-sesion option.text-success {
    color: #28a745 !important;
    font-weight: 600;
}

#selector-sesion option.text-muted {
    color: #6c757d !important;
}

/* Estilos para el botón de siguiente sesión */
#btn-siguiente-sesion {
    transition: all 0.3s ease;
}

#btn-siguiente-sesion:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

#btn-siguiente-sesion:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Estilos para botones de estado */
#btn-marcar-en-curso {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: white !important;
}

#btn-marcar-completada {
    background-color: #00af00 !important;
    border-color: #00af00 !important;
    color: white !important;
}

#btn-completar-toda-sesion {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
    font-weight: 600 !important;
}

#btn-completar-toda-sesion:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}

/* Estilos para badges de estado */
.badge.bg-success {
    background-color: #00af00 !important;
}

/* Estilos para botones de agregar actividad */
.fase-actions .btn[onclick*="agregarActividad"] {
    position: relative !important;
    z-index: 10 !important;
    margin-left: auto !important;
    flex-shrink: 0 !important;
}

.btn[onclick*="agregarActividad"]:hover {
    background-color: #008f00 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}

/* Asegurar que los contenedores de actividades tengan el layout correcto */
.accordion-body .d-flex {
    position: relative !important;
    z-index: 5 !important;
}

/* Estilos específicos para cada fase */
.fase-actividades {
    min-height: 50px !important;
    border: 1px dashed #dee2e6 !important;
    border-radius: 5px !important;
    padding: 15px !important;
    background-color: #f8f9fa !important;
    margin-bottom: 15px !important;
}

.fase-actions {
    border-top: 1px solid #dee2e6 !important;
    padding-top: 15px !important;
}

.fase-actions .btn {
    background-color: #00af00 !important;
    border-color: #00af00 !important;
    color: white !important;
    font-weight: 500 !important;
    padding: 8px 16px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.fase-actions .btn:hover {
    background-color: #008f00 !important;
    border-color: #008f00 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}

/* Estilos para la información de sesiones */
.alert-info h6 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.alert-info .row > div {
    text-align: center;
}

.alert-info strong {
    color: #0c5460;
}

/* Estilos para hacer la interfaz más ancha */
#modalPlanesSesion .modal-dialog {
    max-width: 95vw !important;
    width: 95vw !important;
}

#modalPlanesSesion .modal-content {
    height: 90vh;
    overflow-y: auto;
}

/* Optimizar el uso del espacio en las tablas */
.table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

/* Hacer que las columnas de la tabla tengan un ancho más apropiado */
#tabla-sesiones th:nth-child(1),
#tabla-sesiones td:nth-child(1) {
    width: 10%;
}

#tabla-sesiones th:nth-child(2),
#tabla-sesiones td:nth-child(2) {
    width: 25%;
}

#tabla-sesiones th:nth-child(3),
#tabla-sesiones td:nth-child(3) {
    width: 15%;
}

#tabla-sesiones th:nth-child(4),
#tabla-sesiones td:nth-child(4) {
    width: 12%;
}

#tabla-sesiones th:nth-child(5),
#tabla-sesiones td:nth-child(5) {
    width: 12%;
}

#tabla-sesiones th:nth-child(6),
#tabla-sesiones td:nth-child(6) {
    width: 10%;
}

#tabla-sesiones th:nth-child(7),
#tabla-sesiones td:nth-child(7) {
    width: 16%;
}

/* Mejorar el espaciado de los paneles de información */
.alert {
    margin-bottom: 1rem;
}

.alert .row > div {
    padding: 0.5rem;
}

/* Hacer que los botones de acción se vean mejor */
.btn-group-sm .btn {
    margin: 0 1px;
}

/* ===== COLORES DE VAGE.PHP APLICADOS A VPLANSES.PHP ===== */

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

/* Mejoras en la tabla principal con colores de vage.php */
#tabla-sesiones th {
    background-color: #00af00 !important;
    color: white !important;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #00af00 !important;
}

#tabla-sesiones td {
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

/* Estilos para badges con colores de vage.php */
.badge {
    font-size: 0.9rem;
    padding: 0.7rem 1.2rem;
    font-weight: 600;
    text-align: center;
}

/* Estilos para botones de acción con colores de vage.php */
.btn-outline-primary, .btn-outline-info, .btn[style*="background-color: #00af00"] {
    border-width: 2px;
    margin: 0 0.25rem;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover, .btn-outline-info:hover, .btn[style*="background-color: #00af00"]:hover {
    transform: scale(1.1);
    background-color: #008000 !important;
    box-shadow: 0 2px 8px rgba(0, 175, 0, 0.3);
}

/* Colores para botones de estado */
.btn-success {
    background-color: #00af00 !important;
    border-color: #00af00 !important;
}

.btn-success:hover {
    background-color: #008f00 !important;
    border-color: #008f00 !important;
}

.btn-warning {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #000 !important;
}

.btn-warning:hover {
    background-color: #e0a800 !important;
    border-color: #e0a800 !important;
}

/* Estilos para filtros con colores de vage.php */
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

/* Separadores de fase con colores de vage.php */
.fase-separator {
    background-color: #f8f9fa !important;
    border-top: 3px solid #00af00 !important;
    font-weight: bold !important;
    font-size: 16px !important;
    color: #00af00 !important;
    text-align: center !important;
    padding: 15px !important;
}

/* Estilos para alertas con colores de vage.php */
.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-info h6 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.alert-info .row > div {
    text-align: center;
}

.alert-info strong {
    color: #0c5460;
}

/* ===== FORZAR VISIBILIDAD DE BOTONES DE ESTADO ===== */
#tabla-sesiones .btn-group .btn {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    width: auto !important;
    height: auto !important;
    min-width: 32px !important;
    min-height: 32px !important;
    margin: 0 2px !important;
    padding: 4px 8px !important;
}

#tabla-sesiones .btn-group {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 4px !important;
}

#tabla-sesiones .btn-group .btn i {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    font-size: 14px !important;
}

/* Forzar que el contenedor de botones sea visible */
#tabla-sesiones td:last-child {
    overflow: visible !important;
    white-space: nowrap !important;
    text-align: center !important;
}

/* Debug: agregar borde rojo temporal para ver los botones */
#tabla-sesiones .btn-group .btn {
    border: 2px solid red !important;
    background-color: yellow !important;
}
</style>

