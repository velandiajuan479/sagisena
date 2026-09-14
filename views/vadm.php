<?php require_once ('controllers/cadm.php'); ?>

<?php echo titulo2("<i class='".$icono."'></i> Administración Dependencias",2); ?>

<!-- mostrar los empleados-->
<div id="empleados-global-container" style="display: none; margin-bottom: 20px;">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users me-2"></i>Empleados por Dependencia</h4>
            <button id="close-empleados-btn" class="btn btn-sm btn-light">
                <i class="fas fa-times"></i> Cerrar
            </button>
        </div>
        <div class="card-body" id="empleados-global-content">
        </div>
    </div>
</div>

<!-- Mensajes de exito yerror -->
<?php if(isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['mensaje'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Lista de dependencias -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-building me-2"></i>Listado de Dependencias</h4>
    </div>
    
    <div class="card-body">
        <?php if(!isset($dependencias)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>Error: Variable dependencias no está definida
            </div>
        <?php elseif(empty($dependencias)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>No hay dependencias registradas
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dependencias as $dep): ?>
                        <tr>
                            <td><?= htmlspecialchars($dep['id_dependencia']) ?></td>
                            <td><?= htmlspecialchars($dep['nombre']) ?></td>
                            <td><?= !empty($dep['descripcion']) ? htmlspecialchars($dep['descripcion']) : '<em class="text-muted">NULL</em>' ?></td>
                            <td class="text-center">
                                <button class="add-btn" data-id="<?= htmlspecialchars($dep['id_dependencia']) ?>" title="Ver empleados">
                                    +
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <small class="text-muted">
                    Total de dependencias: <?= count($dependencias) ?>
                </small>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const empleadosContainer = document.getElementById('empleados-global-container');
    const empleadosContent = document.getElementById('empleados-global-content');
    const closeBtn = document.getElementById('close-empleados-btn');
    closeBtn.addEventListener('click', function() {
        empleadosContainer.style.display = 'none';
    });
    
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.getAttribute('data-id');

            empleadosContainer.style.display = 'block';
            empleadosContent.innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Cargando empleados...</p>
                </div>`;
            
            try {
                const response = await fetch('controllers/cadm.php?action=get_empleados&id_dependencia=' + id, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
               
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Respuesta completa:', text);
                    throw new Error(`Respuesta no es JSON: ${text.substring(0, 100)}...`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || 'Error al cargar empleados');
                }

                console.log('Datos recibidos:', data); 
                
                
                if (data.count === 0) {
                    empleadosContent.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay empleados registrados en esta dependencia
                        </div>`;
                } else {
                    let html = `
                        <h5 class="mb-3">
                            <i class="fas fa-building me-2"></i>
                            Dependencia: ${this.closest('tr').querySelector('td:nth-child(2)').textContent}
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Perfil</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    data.data.forEach(empleado => {
                        const estado = empleado.activo == 1 ? 
                            '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Activo</span>' : 
                            '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Inactivo</span>';
                        let perfil = empleado.nomper 
                        ? `<span class="badge ${empleado.idper == 34 ? 'bg-primary' : 'bg-warning'}">
                            <i class="${empleado.idper == 34 ? 'fas fa-user-tie' : 'fas fa-file-signature'} me-1"></i>
                            ${empleado.nomper}
                        </span>`
                        : '<span class="badge bg-secondary"><i class="fas fa-question-circle me-1"></i> Perfil no definido</span>';
                        html += `
                        <tr>
                            <td>
                                <a href="home.php?pg=2401&documento=${empleado.ndocusu || ''}&nombre=${encodeURIComponent(empleado.nombre || '')}&id_dependencia=${id}&desde=vadm"
                                class="nombre-link" 
                                title="Seleccionar este empleado">
                                    ${empleado.ndocusu || 'N/A'}
                                </a>
                            </td>
                            <td>${empleado.nombre || 'N/A'}</td>
                            <td>${empleado.email || 'N/A'}</td>
                            <td>${empleado.telefono || 'N/A'}</td>
                            <td>${perfil}</td>
                            <td>${estado}</td>
                        </tr>`;
                    }); 
                    html += `</tbody></table></div>`;
                    empleadosContent.innerHTML = html;
                }
            } catch(error) {
                console.error('Error completo:', error);
                empleadosContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${error.message}
                        <br><small>Revisa la consola para más detalles</small>
                    </div>`;
            }
            empleadosContainer.scrollIntoView({ behavior: 'smooth' });
        });
    });
});
</script>

<style>
.add-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem 1rem;
    font-size: 2.5rem;
    font-weight: bold;
    color: #28a745; 
    transition: all 0.2s ease;
}

.add-btn:hover {
    color: #218838; 
    transform: scale(1.2); 
}

.table td {
    vertical-align: middle;
}

#close-empleados-btn {
    transition: all 0.2s ease;
}

#close-empleados-btn:hover {
    transform: scale(1.1);
}

.card-header.bg-success {
    background-color: #28a745 !important;
}

.nombre-link {
    color: #212529;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    padding: 0.2rem 0;
    display: block;
}

.nombre-link:hover {
    color: #218838;
    text-decoration: underline;
    transform: translateX(2px);
}

.badge.bg-primary {
    background-color:#218838 !important;
}

.badge.bg-warning.text-dark {
    background-color: #28a745 !important;
    color: #212529 !important;
}


.table-sm th, .table-sm td {
    padding: 0.75rem 0.5rem;
    vertical-align: middle;
}
</style>