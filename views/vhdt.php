<?php 
// ==========================================
// Controlador principal de la Hoja de Trabajo
// ==========================================
require_once("controllers/chdt.php"); 
?>

<!-- Contenedor Principal -->
<div class="container-fluid px-0">
    <!-- Título de la Sección -->
    <div class="mb-3">
        <?php echo titulo2("<i class='".$icono."'></i> Hoja de Trabajo", 1); ?>
    </div>

    <!-- ========================================== -->
    <!-- Formulario de Registro / Edición           -->
    <!-- ========================================== -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light py-3 border-0">
            <h5 class="card-title mb-0 fw-bold text-secondary">
                <i class="fa-solid <?= isset($datOne) ? 'fa-pen-to-square text-warning' : 'fa-circle-plus text-success'; ?> me-2"></i>
                <?= isset($datOne) ? 'Modificar Hoja de Trabajo No. ' . htmlspecialchars($datOne[0]['idnorad']) : 'Nueva Hoja de Trabajo'; ?>
            </h5>
        </div>
        <div class="card-body p-4">
            <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
                <div class="row g-3">
                    
                    <!-- Programa de Formación (Select Grande) -->
                    <div class="col-md-6">
                        <label for="codpro" class="form-label fw-semibold">Programa <span class="text-danger">*</span></label>
                        <select name="codpro" id="codpro" class="form-select form-select-lg" required>
                            <option value="">Seleccione un programa...</option>
                            <?php if($datPr){ foreach($datPr AS $dt){ ?>
                                <option value="<?=$dt["codpro"]; ?>" <?php if($datOne && $datOne[0]['codpro']==$dt['codpro']) echo 'selected'; ?>>
                                    <?=$dt["codpro"]." - ".$dt["nompro"]; ?>
                                </option>
                            <?php }} ?>
                        </select>
                    </div>

                    <!-- Empresa (Select Grande) -->
                    <div class="col-md-6">
                        <label for="idemp" class="form-label fw-semibold">Empresa <span class="text-danger">*</span></label>
                        <select name="idemp" id="idemp" class="form-select form-select-lg" required>
                            <option value="">Seleccione una empresa...</option>
                            <?php if($datEm){ foreach($datEm AS $dt){ ?>
                                <option value="<?=$dt["idemp"]; ?>" <?php if($datOne && $datOne[0]['idemp']==$dt['idemp']) echo 'selected'; ?>>
                                    <?=$dt["nomemp"]; ?>
                                </option>
                            <?php }} ?>
                        </select> 
                    </div>

                    <!-- Programa Especial (Select Grande) -->
                    <div class="col-md-6">
                        <label for="codproesp" class="form-label fw-semibold">Programa Especial <span class="text-danger">*</span></label>
                        <select name="codproesp" id="codproesp" class="form-select form-select-lg" required>
                            <option value="">Seleccione programa especial...</option>
                            <?php if($datPe){ foreach($datPe AS $dt){ ?> 
                                <option value="<?=$dt["idval"]; ?>" <?php if($datOne && $datOne[0]['codproesp']==$dt['idval']) echo 'selected'; ?>>
                                    <?=$dt["nomval"]; ?>
                                </option>
                            <?php }} ?>
                        </select>
                    </div>

                    <!-- Jornada (Select Grande) -->
                    <div class="col-md-6">
                        <label for="jornada" class="form-label fw-semibold">Jornada <span class="text-danger">*</span></label>
                        <select name="jornada" id="jornada" class="form-select form-select-lg" required>
                            <option value="">Seleccione jornada...</option>
                            <?php if($datJo){ foreach($datJo AS $dt){ ?>
                                <option value="<?=$dt["idval"]?>" <?php if($datOne && $datOne[0]['jornada']==$dt['idval']) echo 'selected'; ?>>
                                    <?=$dt["nomval"]?>
                                </option>
                            <?php }} ?>
                        </select>
                    </div>

                    <!-- Fecha Inicial -->
                    <div class="col-md-4">
                        <label for="feclini" class="form-label fw-semibold">Fecha Inicial <span class="text-danger">*</span></label>
                        <input type="date" name="feclini" id="feclini" onchange="nFfin(this.value);" class="form-control form-control-lg" value="<?php if($datOne && $datOne[0]['feclini']) echo $datOne[0]['feclini']; else echo $hoy; ?>" min="<?=$hoy; ?>" max="<?=$hmfin; ?>" required>
                    </div>

                    <!-- Fecha Final -->
                    <div class="col-md-4">
                        <label for="feclin" class="form-label fw-semibold">Fecha Final <span class="text-danger">*</span></label>
                        <input type="date" name="feclin" id="feclin" class="form-control form-control-lg" value="<?php if($datOne && $datOne[0]['feclin']) echo $datOne[0]['feclin']; else echo $hmes; ?>" min="<?=$hoy; ?>" max="<?=$hmfin; ?>" required>
                    </div>

                    <!-- Cupo -->
                    <div class="col-md-4">
                        <label for="cupo" class="form-label fw-semibold">Cupo <span class="text-danger">*</span></label>
                        <input type="number" min="1" name="cupo" id="cupo" class="form-control form-control-lg" value="<?php if($datOne && $datOne[0]['cupo']) echo $datOne[0]['cupo']; ?>" placeholder="Ej: 30" required>
                    </div>

                    <!-- Campos adicionales (visibles al editar) -->
                    <?php if(isset($datOne)){ ?>
                    <div class="col-md-4">
                        <label for="idfic" class="form-label fw-semibold">Código Ficha</label>
                        <input type="number" name="idfic" id="idfic" class="form-control form-control-lg" value="<?php if($datOne && $datOne[0]['idfic']) echo $datOne[0]['idfic']; ?>" placeholder="Opcional">
                    </div>
                    <div class="col-md-4">
                        <label for="codslem" class="form-label fw-semibold">Cód. Solicitud Empresa</label>
                        <input type="text" name="codslem" id="codslem" class="form-control form-control-lg" value="<?php if($datOne && $datOne[0]['codslem']) echo $datOne[0]['codslem']; ?>" placeholder="Opcional">
                    </div>
                    <div class="col-md-4">
                        <label for="convht" class="form-label fw-semibold">Convenio</label>
                        <input type="text" name="convht" id="convht" class="form-control form-control-lg" value="<?php if($datOne && $datOne[0]['convht']) echo $datOne[0]['convht']; ?>" placeholder="Opcional">
                    </div>
                    <?php } ?>

                    <!-- Botones de Acción -->
                    <div class="col-12 mt-4 d-flex align-items-center gap-2">
                        <button type="submit" class="btn <?= isset($datOne) ? 'btn-warning text-dark' : 'btn-success'; ?> btn-lg px-4 shadow-sm" title="<?= isset($datOne) ? 'Actualizar cambios de la hoja' : 'Guardar nueva hoja de trabajo'; ?>" data-bs-toggle="tooltip">
                            <i class="fa-solid <?= isset($datOne) ? 'fa-arrows-rotate' : 'fa-floppy-disk'; ?> me-2"></i>
                            <?= isset($datOne) ? 'Actualizar' : 'Guardar'; ?>
                        </button>
                        
                        <?php if(isset($datOne)){ ?>
                            <a href="home.php?pg=<?=$pg;?>" class="btn btn-outline-secondary btn-lg px-4" title="Cancelar edición" data-bs-toggle="tooltip">
                                <i class="fa-solid fa-xmark me-2"></i> Cancelar
                            </a>
                        <?php } ?>

                        <input type="hidden" name="opera" value="save">
                        <input type="hidden" name="idnorad" value="<?php if($datOne && $datOne[0]['idnorad']) echo $datOne[0]['idnorad']; ?>">
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- Tabla de Listado de Hojas de Trabajo       -->
    <!-- ========================================== -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="example" class="table table-hover table-striped align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Hoja de Trabajo</th>
                            <th>Jornada</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($datAll){ foreach ($datAll as $dta){ ?>
                            <tr>
                                <!-- Datos de la Hoja de Trabajo -->
                                <td>
                                    <span class="badge bg-success fs-6 mb-1">Hoja No. <?=$dta["idnorad"];?></span><br>
                                    <strong>Programa:</strong> <?=htmlspecialchars($dta["codpro"]);?> - <?=htmlspecialchars($dta["nompro"]);?><br>
                                    <strong>Empresa:</strong> <?=htmlspecialchars($dta["nomemp"]);?><br>
                                    <small class="text-muted"><i class="fa-regular fa-calendar-days me-1"></i><strong>Fechas:</strong> <?=$dta["feclini"];?> al <?=$dta["feclin"];?></small>
                                </td>
                                
                                <!-- Jornada -->
                                <td>
                                    <span class="badge bg-secondary"><?=htmlspecialchars($dta["jorn"]);?></span>
                                </td>
                                
                                <!-- Estado: Activo / Inactivo con cambio dinámico -->
                                <td class="text-center">
                                    <?php if(isset($dta['act']) && $dta['act'] == 1){ ?>
                                        <a href="home.php?pg=<?=$pg;?>&idnorad=<?=$dta['idnorad'];?>&opera=acti&act=2" 
                                           class="text-decoration-none" 
                                           title="Activo - Clic para desactivar" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-circle-check fa-2x text-success"></i>
                                        </a>
                                    <?php } else { ?>
                                        <a href="home.php?pg=<?=$pg;?>&idnorad=<?=$dta['idnorad'];?>&opera=acti&act=1" 
                                           class="text-decoration-none" 
                                           title="Inactivo - Clic para activar" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-circle-xmark fa-2x text-danger"></i>
                                        </a>
                                    <?php } ?>
                                </td>

                                <!-- Acciones con Tooltips Claros -->
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <!-- Editar -->
                                        <a href="home.php?pg=<?=$pg?>&opera=edi&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-warning" 
                                           title="Editar Hoja de Trabajo" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-pen-to-square fa-2x"></i>
                                        </a>
                                        
                                        <!-- Eliminar con confirmación -->
                                        <a href="home.php?pg=<?=$pg?>&opera=eli&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-danger" 
                                           title="Eliminar Hoja de Trabajo" 
                                           data-bs-toggle="tooltip" 
                                           onclick="return eliminar(this);">
                                            <i class="fa-solid fa-trash-can fa-2x"></i>
                                        </a>

                                        <!-- Horario Complementario -->
                                        <a href="home.php?pg=2002&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-primary" 
                                           title="Horario Complementario" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-calendar fa-2x"></i>
                                        </a>

                                        <!-- Instructores Asignados -->
                                        <a href="home.php?pg=2007&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-info" 
                                           title="Instructores Asignados" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-user-group fa-2x"></i>
                                        </a>    
                                        
                                        <!-- Imprimir Reporte -->
                                        <a href="home.php?pg=2001&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-secondary" 
                                           title="Imprimir Hoja de Trabajo" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-print fa-2x"></i>
                                        </a>  

                                        <!-- Notas / Seguimiento -->
                                        <a href="home.php?pg=2010&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-success" 
                                           title="Notas / Seguimiento" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-note-sticky fa-2x"></i>
                                        </a>  
                                         
                                        <!-- Bloqueo / Seguridad -->
                                        <a href="home.php?pg=2004&idnorad=<?=$dta["idnorad"]?>" 
                                           class="text-dark" 
                                           title="Bloqueo / Seguridad" 
                                           data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-lock fa-2x"></i>
                                        </a>   
                                    </div>
                                </td>
                                 
                            </tr>
                        <?php }} ?>
                    </tbody>
                    <thead class="table-light">
                        <tr>
                            <th>Hoja de Trabajo</th>
                            <th>Jornada</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Inicialización de Tooltips de Bootstrap -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>