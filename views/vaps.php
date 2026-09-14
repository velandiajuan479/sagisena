<?php
require_once 'controllers/caps.php';
echo titulo2("<i class='fas fa-id-card'></i> Aprobador Paz y Salvo", 2);
?>

<div class="card border-success p-3 mb-4">
    <h5 class="text-success mb-3">Buscar por dependencia</h5>

    <form id="form-buscar">
        <div class="row g-2 align-items-end">
            <div class="col-md-8">
                <label for="dependencia" class="form-label">Seleccione dependencia:</label>
                <select class="form-control form-control-sm border-success" id="dependencia" required>
                    <option value="">-- Seleccione una dependencia --</option>
                    <?php
                    $dependencias = $maps->obtenerDependencias();
                    foreach ($dependencias as $dep) {
                        echo "<option value='{$dep['id_dependencia']}'>{$dep['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 d-grid">
                <button type="submit" style="
                    width: 80px;
                    height: 27px;
                    background-color: #00AF00;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    font-size: 12px;
                    margin-top: 24px;
                ">Buscar</button>
            </div>
        </div>
    </form>
</div>

<!-- resultados -->
<div id="resultados-container">
    <!-- seccion de empleados pendienes por aprobar -->
    <div class="card border-success mb-4">
        <div class="card-header" style="background-color: #00AF00; color: white;">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Pendientes por aprobar</h5>
        </div>
        <div class="card-body p-0">
            <div id="empleados-pendientes">
                <div class="list-group list-group-flush"></div>
            </div>
        </div>
    </div>
    
    <!-- seccion de empleados que ya estan aprobados -->
    <div class="card border-success mb-4">
        <div class="card-header" style="background-color: #00AF00; color: white;">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Paz y salvo aprobado</h5>
        </div>
        <div class="card-body p-0">
            <div id="empleados-aprobados">
                <div class="list-group list-group-flush"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('form-buscar').addEventListener('submit', function (e) {
    e.preventDefault();
    
    const idDependencia = document.getElementById('dependencia').value;
    
    if (!idDependencia) {
        alert('Por favor seleccione una dependencia');
        return;
    }
    
    document.getElementById('empleados-pendientes').innerHTML = `
        <div class="list-group-item text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2">Buscando empleados...</p>
        </div>
    `;
    
    document.getElementById('empleados-aprobados').innerHTML = `
        <div class="list-group-item text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2">Cargando aprobados...</p>
        </div>
    `;
    
    // obteniene empleados de la dependencia
    fetch(`controllers/caps.php?action=get_empleados&id_dependencia=${idDependencia}`)
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            document.getElementById('empleados-pendientes').innerHTML = `
                <div class="list-group-item">
                    <div class="alert alert-danger mb-0">Error: ${data.error}</div>
                </div>
            `;
            return;
        }
        
        if (data.count === 0) {
            document.getElementById('empleados-pendientes').innerHTML = `
                <div class="list-group-item text-center">
                    <p class="text-muted mb-0">No se encontraron empleados para esta dependencia</p>
                </div>
            `;
            document.getElementById('empleados-aprobados').innerHTML = '';
            return;
        }

        // filtra empleados que no sean idper 34(emp_planta)
        const empleadosFiltrados = data.data.filter(emp => emp.idper != 34);

        if (empleadosFiltrados.length === 0) {
            document.getElementById('empleados-pendientes').innerHTML = `
                <div class="list-group-item text-center">
                    <p class="text-muted mb-0">No hay empleados pendientes por aprobar</p>
                </div>
            `;
        } else {
            // carga empleados pendientes
            let htmlPendientes = '';
            empleadosFiltrados.forEach(emp => {
                htmlPendientes += `
                    <div class="list-group-item d-flex align-items-center">
                        <span class="me-3"><strong>${emp.nombre}</strong></span>
                        <span class="me-3">Documento: ${emp.ndocusu}</span>
                        <span class="me-3">Perfil: ${emp.nomper || 'Sin perfil'}</span>
                        
                        <div class="form-check form-check-inline ms-auto">
                            <input class="form-check-input" type="checkbox" 
                                style="background-color: #00AF00; border-color: #00AF00;"
                                onchange="manejarCheck(this, ${emp.idusu}, '${emp.nombre.replace(/'/g, "\\'")}', 1)">
                            <label class="form-check-label" style="color: #00AF00; font-weight: bold;">Aprobar</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" 
                                style="background-color: #dc3545; border-color: #dc3545;"
                                onchange="manejarCheck(this, ${emp.idusu}, '${emp.nombre.replace(/'/g, "\\'")}', 0)">
                            <label class="form-check-label" style="color: #dc3545; font-weight: bold;">Rechazar</label>
                        </div>
                    </div>
                `;
            });
            
            document.getElementById('empleados-pendientes').innerHTML = `<div class="list-group list-group-flush">${htmlPendientes}</div>`;
        }
        
        // obteniene empleados ya aprobados
        return fetch(`controllers/caps.php?action=get_aprobados&id_dependencia=${idDependencia}`);
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            document.getElementById('empleados-aprobados').innerHTML = `
                <div class="list-group-item">
                    <div class="alert alert-danger mb-0">Error al cargar aprobados: ${data.error}</div>
                </div>
            `;
            return;
        }
        
        if (data.count === 0) {
            document.getElementById('empleados-aprobados').innerHTML = `
                <div class="list-group-item text-center">
                    <p class="text-muted mb-0">No hay empleados aprobados aún</p>
                </div>
            `;
            return;
        }
        
        // filtra solo los que aprobaron 
        const aprobadosFiltrados = data.data.filter(emp => emp.calificacion == 1);
        
        if (aprobadosFiltrados.length === 0) {
            document.getElementById('empleados-aprobados').innerHTML = `
                <div class="list-group-item text-center">
                    <p class="text-muted mb-0">No hay empleados con paz y salvo aprobado</p>
                </div>
            `;
            return;
        }
        
        // carga empleados aprobados
        let htmlAprobados = '';
        aprobadosFiltrados.forEach(emp => {
            const fecha = new Date(emp.fechayhora).toLocaleString();
                
            htmlAprobados += `
                <div class="list-group-item d-flex align-items-center">
                    <span class="me-3"><strong>${emp.nombre}</strong></span>
                    <span class="me-3">Documento: ${emp.ndocusu}</span>
                    <span class="me-3 badge bg-success">Aprobó</span>
                    <span class="me-3">Observación: ${emp.observacion || 'Ninguna'}</span>
                    <span>Fecha: ${fecha}</span>
                </div>
            `;
        });
        
        document.getElementById('empleados-aprobados').innerHTML = `<div class="list-group list-group-flush">${htmlAprobados}</div>`;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('empleados-pendientes').innerHTML = `
            <div class="list-group-item">
                <div class="alert alert-danger mb-0">Error al cargar los datos: ${error.message}</div>
            </div>
        `;
    });
});

function manejarCheck(checkbox, idusu, nombre, calificacion) {
    // desmarca el otro checkbox del mismo empleado
    const allCheckboxes = document.querySelectorAll(`input[onchange*="${idusu}"]`);
    allCheckboxes.forEach(cb => {
        if (cb !== checkbox) {
            cb.checked = false;
        }
    });

    const accion = calificacion === 1 ? 'aprobar' : 'rechazar';
    const observacion = prompt(`Ingrese observación para ${accion} a ${nombre}:`);
    
    if (observacion === null) {
        checkbox.checked = false;
        return;
    }
    
    if (observacion.trim() === '') {
        checkbox.checked = false;
        alert('Debe escribir una observación.');
        return;
    }

    guardarCalificacion(idusu, calificacion, observacion);
}

function guardarCalificacion(idusu, calificacion, observacion) {
    fetch('controllers/caps.php?action=calificar_usuario', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            idusu, 
            calificacion, 
            observacion: observacion.trim() 
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Calificación guardada correctamente');
            //recarga automaticamente
            document.getElementById('form-buscar').dispatchEvent(new Event('submit'));
        } else {
            alert('Error: ' + data.error);
            // desmarca el checkbox en caso de error
            document.querySelectorAll(`input[onchange*="${idusu}"]`).forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar: ' + error.message);
        // desmarca el checkbox en caso de error
        document.querySelectorAll(`input[onchange*="${idusu}"]`).forEach(checkbox => {
            checkbox.checked = false;
        });
    });
}
</script>