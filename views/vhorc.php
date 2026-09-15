<?php 
require_once("controllers/chorc.php"); 
require_once("controllers/chdt.php");
require_once("controllers/cemp.php");
require_once("models/mhorc.php");

// Asegurar que $pg esté definido
if(!isset($pg)) {
    $pg = isset($_GET['pg']) ? $_GET['pg'] : '';
}
?>
<div class="conte">
    <?php echo titulo2("<i class='fas fa-calendar-alt'></i> Programación de Horarios", 2); ?>
    <div class="inser">
        <form action="home.php?pg=<?=$pg;?>&idnorad=<?=htmlspecialchars($idnorad ?? '');?>" method="POST">
            <div class="row">   
                <?php if($datOne){ ?>
                <div class="form-group col-md-12 mb-3">
                    <h2>Editando Horario No. <?=$datOne[0]['idnorad'];?></h2>
                </div>
                <?php } ?>

                <!-- Programa (Definido en Hoja de Trabajo: Bloqueado) -->
                <div class="form-group col-md-6 mb-3">
                    <label for="codpro">Programa <i class="fa-solid fa-lock text-muted ms-1" title="Definido en la Hoja de Trabajo"></i></label>
                    <select id="codpro" class="form-control form-select bg-light" disabled>
                        <?php if($datPr){ foreach($datPr AS $dt){ ?>
                            <option value="<?=$dt["codpro"]; ?>" <?php if($datOne && $datOne[0]['codpro']==$dt['codpro']) echo 'selected'; ?>>
                                <?=$dt["codpro"]." - ".$dt["nompro"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                    <input type="hidden" name="codpro" value="<?=$datOne[0]['codpro'] ?? ''?>">
                </div>

                <!-- Empresa (Definida en Hoja de Trabajo: Bloqueada) -->
                <div class="form-group col-md-6 mb-3">
                    <label for="idemp">Empresa <i class="fa-solid fa-lock text-muted ms-1" title="Definida en la Hoja de Trabajo"></i></label>
                    <select id="idemp" class="form-control form-select bg-light" disabled>
                        <?php if($datEm){ 
                            foreach($datEm as $dt): 
                                $selected = ($datOne && $datOne[0]['idemp'] == $dt['idemp']) ? 'selected' : '';
                        ?>
                            <option value="<?=htmlspecialchars($dt["idemp"]); ?>" <?=$selected?>>
                                <?=htmlspecialchars($dt["nomemp"]); ?>
                            </option>
                        <?php 
                            endforeach;
                        } 
                        ?>
                    </select>
                    <input type="hidden" name="idemp" value="<?=$datOne[0]['idemp'] ?? ''?>">
                </div>

                <!-- Programa Especial (Definido en Hoja de Trabajo: Bloqueado) -->
                <div class="form-group col-md-6 mb-3">
                    <label for="codproesp">Programa Especial <i class="fa-solid fa-lock text-muted ms-1" title="Definido en la Hoja de Trabajo"></i></label>
                    <select id="codproesp" class="form-control form-select bg-light" disabled>
                        <?php if($datPe){ foreach($datPe AS $dt){ ?> 
                            <option value="<?=$dt["idval"]; ?>" <?php if($datOne && $datOne[0]['codproesp']==$dt['idval']) echo 'selected'; ?>>
                                <?=$dt["nomval"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                    <input type="hidden" name="codproesp" value="<?=$datOne[0]['codproesp'] ?? ''?>">
                </div>

                <!-- Fecha Inicial (Definida en Hoja de Trabajo: Bloqueada) -->
                <div class="form-group col-md-3 mb-3">
                    <label for="feclini">Fecha Inicial <i class="fa-solid fa-lock text-muted ms-1" title="Definida en la Hoja de Trabajo"></i></label>
                    <input type="date" id="feclini" class="form-control bg-light" value="<?php 
                        if(!empty($datOneHt[0]['feclini'])) {
                            echo $datOneHt[0]['feclini'];
                        } elseif($datOne && $datOne[0]['feclini']) { 
                            echo $datOne[0]['feclini']; 
                        } else { 
                            echo date('Y-m-d'); 
                        } 
                    ?>" readonly style="background-color: #e9ecef;">
                    <input type="hidden" name="feclini" value="<?=$datOne[0]['feclini'] ?? ''?>">
                </div>

                <!-- Fecha Final (Definida en Hoja de Trabajo: Bloqueada) -->
                <div class="form-group col-md-3 mb-3">
                    <label for="feclin">Fecha Final <i class="fa-solid fa-lock text-muted ms-1" title="Definida en la Hoja de Trabajo"></i></label>
                    <input type="date" id="feclin" class="form-control bg-light" value="<?php 
                        if(!empty($datOneHt[0]['feclin'])) {
                            echo $datOneHt[0]['feclin'];
                        } elseif($datOne && $datOne[0]['feclin']) { 
                            echo $datOne[0]['feclin']; 
                        } else { 
                            echo date('Y-m-d'); 
                        } 
                    ?>" readonly style="background-color: #e9ecef;">
                    <input type="hidden" name="feclin" value="<?=$datOne[0]['feclin'] ?? ''?>">
                </div>

                <!-- Cupo (Definido en Hoja de Trabajo: Bloqueado) -->
                <div class="form-group col-md-3 mb-3">
                    <label for="cupo">Cupo <i class="fa-solid fa-lock text-muted ms-1" title="Definido en la Hoja de Trabajo"></i></label>
                    <input type="number" id="cupo" class="form-control bg-light" value="<?php if($datOne && $datOne[0]['cupo']) echo $datOne[0]['cupo']; ?>" readonly style="background-color: #e9ecef;">
                    <input type="hidden" name="cupo" value="<?=$datOne[0]['cupo'] ?? ''?>">
                </div>

                <!-- Jornada (Definida en Hoja de Trabajo: Bloqueada) -->
                <div class="form-group col-md-3 mb-3">
                    <label for="jornada">Jornada <i class="fa-solid fa-lock text-muted ms-1" title="Definida en la Hoja de Trabajo"></i></label>
                    <select id="jornada" class="form-control form-select bg-light" disabled>
                        <?php if($datJo){ foreach($datJo AS $dt){ ?>
                            <option value="<?=$dt["idval"]?>" <?php if($datOne && $datOne[0]['jornada']==$dt['idval']) echo 'selected'; ?>>
                                <?=$dt["nomval"]?>
                            </option>
                        <?php }} ?>
                    </select>
                    <input type="hidden" name="jornada" value="<?=$datOne[0]['jornada'] ?? ''?>">
                </div>
                                                
                <!-- Cod. Ficha (Editable: No pertenece a la Hoja de Trabajo inicial) -->
                <div class="form-group col-md-3 mb-3">
                    <label for="idfic" class="fw-semibold">Cod. Ficha</label>
                    <input type="number" name="idfic" id="idfic" class="form-control" value="<?php if($datOne && $datOne[0]['idfic']) echo $datOne[0]['idfic']; ?>" placeholder="Opcional">
                </div>

                <!-- Cod. Solicitud Empresa (Editable: No pertenece a la Hoja de Trabajo inicial) -->
                <div class="form-group col-md-3 mb-3">
                    <label for="codslem" class="fw-semibold">Cod. Solicitud Empresa</label>
                    <input type="text" name="codslem" id="codslem" class="form-control" value="<?php if($datOne && $datOne[0]['codslem']) echo $datOne[0]['codslem']; ?>" placeholder="Opcional">
                </div>

                <!-- Convenio (Editable: No pertenece a la Hoja de Trabajo inicial) -->
                <div class="form-group col-md-6 mb-3">
                    <label for="convht" class="fw-semibold">Convenio</label>
                    <input type="text" name="convht" id="convht" class="form-control" value="<?php if($datOne && $datOne[0]['convht']) echo $datOne[0]['convht']; ?>" placeholder="Opcional">
                </div>

                <div class="form-group col-md-12 mt-2">
                    <input type="submit" class="btn btn-primary" value="<?=isset($datOne) ? 'Actualizar' : 'Guardar'?>">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idnorad" value="<?php if($datOne && $datOne[0]['idnorad']) echo $datOne[0]['idnorad']; ?>">
                </div>
            </div>
        </form>
    </div>
</div>
<br>

<!-- DATOS DE LA EMPRESA -->
<?php if(!empty($datOneEmp) && is_array($datOneEmp) && !empty($datOneEmp[0])): 
    $empresa = $datOneEmp[0];
?>
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="card-title text-secondary">
            <i class="fa-solid fa-building me-2 text-primary"></i>Datos de la Empresa 
        </h4>
        <div class="row g-3">
            <?php if(isset($empresa['numdocemp'])): ?>
            <div class="col-md-2">
                <div class="fw-bold text-muted">NIT</div>
                <div class="form-control bg-light"><?=htmlspecialchars($empresa['numdocemp'])?></div>
            </div>
            <?php endif; ?>
            <?php if(isset($empresa['nomemp'])): ?>
            <div class="col-md-5">
                <div class="fw-bold text-muted">Empresa</div>
                <div class="form-control bg-light"><?=htmlspecialchars($empresa['nomemp'])?></div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($empresa['diremp'])): ?>
            <div class="col-md-5">
                <div class="fw-bold text-muted">Dirección</div>
                <div class="form-control bg-light">
                    <?=htmlspecialchars($empresa['diremp'])?> 
                    <?=htmlspecialchars($empresa['nommun'] ?? '')?> 
                    <?=htmlspecialchars($empresa['nomdep'] ?? '')?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($empresa['nomconemp'])): ?>
            <div class="col-md-7">
                <div class="fw-bold text-muted">Contacto</div>
                <div class="form-control bg-light"><?=htmlspecialchars($empresa['nomconemp'])?></div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($empresa['telemp'])): ?>
            <div class="col-md-5">
                <div class="fw-bold text-muted">Teléfono</div>
                <div class="form-control bg-light"><?=htmlspecialchars($empresa['telemp'])?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- PROGRAMA -->
<?php if(!empty($dtpro) && is_array($dtpro) && !empty($dtpro[0])): 
    $proInfo = $dtpro[0];
?>
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="card-title text-secondary">
            <i class="fa-solid fa-graduation-cap me-2 text-primary"></i>Programa de Formación
        </h4>
        <div class="row g-3">
            <div class="col-md-8">
                <div class="fw-bold text-muted">Nombre del Programa</div>
                <div class="form-control bg-light"><?=htmlspecialchars(($proInfo['codpro'] ?? '') . ' - ' . ($proInfo['nompro'] ?? ''))?></div>
            </div>
            <div class="col-md-2">
                <div class="fw-bold text-muted">Versión</div>
                <div class="form-control bg-light"><?=htmlspecialchars($proInfo['verpro'] ?? 'N/A')?></div>
            </div>
            <div class="col-md-2">
                <div class="fw-bold text-muted">Duración (Horas)</div>
                <div class="form-control bg-light"><?=htmlspecialchars($proInfo['horlpro'] ?? 'N/A')?></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- DATOS DEL INSTRUCTOR -->
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h4 class="card-title text-secondary mb-3">
            <i class="fa-solid fa-chalkboard-user me-2 text-primary"></i>Instructor Asignado
        </h4>

        <?php if(!empty($datInsHdt)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono</th>
                            <th class="text-center" style="width: 80px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datInsHdt as $inst): ?>
                            <tr>
                                <td class="fw-semibold"><?=htmlspecialchars($inst['nomusu'])?></td>
                                <td><?=htmlspecialchars($inst['ndocusu'] ?? 'N/A')?></td>
                                <td><?=htmlspecialchars($inst['emausu'] ?? 'N/A')?></td>
                                <td><?=htmlspecialchars($inst['telcan'] ?? 'N/A')?></td>
                                <td class="text-center">
                                    <a href="home.php?pg=<?=$pg?>&idnorad=<?=$idnorad?>&opera=EliIns&idusu=<?=$inst['idusu']?>" 
                                       class="btn btn-outline-danger btn-sm" 
                                       title="Retirar Instructor" 
                                       onclick="return confirm('¿Está seguro de retirar este instructor de la hoja de trabajo?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2 fa-lg"></i>
                <div>
                    No hay ningún instructor asignado a esta hoja de trabajo. Haga clic en <strong>"Asignar Instructor"</strong> para asociar uno.
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Botón para asignar instructor -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AgreUsuIns" title="Asignar Instructor">
            <i class="fa-solid fa-user-plus me-1"></i> Asignar Instructor
        </button>
    </div>
</div>

<!-- INSERCION DE HORARIO -->
<div class="card">
    <div class="card-body">
        <h4 class="card-title">
            Generar Horario Automático
        </h4>
        <p class="text-muted mb-3">Genera automáticamente los horarios de Lunes a Viernes entre las fechas del curso</p>
        <form id="formGenerarHorario" method="POST" action="home.php?pg=<?=$pg?>&idnorad=<?=$idnorad?>&opera=generarHorario">
            <div class="row">
                <?php
                $fechaInicio = isset($datOneHt[0]['feclini']) ? $datOneHt[0]['feclini'] : '';
                $fechaFin = isset($datOneHt[0]['feclin']) ? $datOneHt[0]['feclin'] : '';
                ?>
                <div class="form-group col-md-3 mb-3">
                    <label for="fecha_inicio_gen">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio_gen" name="fecha_inicio_gen" class="form-control" 
                           min="<?php echo $fechaInicio; ?>" max="<?php echo $fechaFin; ?>" value="<?php echo $fechaInicio; ?>" required readonly>
                </div>
                <div class="form-group col-md-3 mb-3">
                    <label for="fecha_fin_gen">Fecha Fin</label>
                    <input type="date" id="fecha_fin_gen" name="fecha_fin_gen" class="form-control" 
                           min="<?php echo $fechaInicio; ?>" max="<?php echo $fechaFin; ?>" value="<?php echo $fechaFin; ?>" required readonly>
                </div>
                <div class="form-group col-md-2 mb-3">
                    <label for="hora_inicio_gen">Hora Inicio</label>
                    <input type="time" id="hora_inicio_gen" name="hora_inicio_gen" class="form-control" step="60" required>
                </div>
                <div class="form-group col-md-2 mb-3">
                    <label for="hora_fin_gen">Hora Fin</label>
                    <input type="time" id="hora_fin_gen" name="hora_fin_gen" class="form-control" step="60" required>
                </div>
                <div class="form-group col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success" onclick="return validarHorarioGen()">Generar Horario</button>
                </div>
            </div>
        </form>
        <hr class="my-4">
        <h5 class="card-title">Agregar Horario Manual</h5>
        <form id="formAgregarHorario">
            <div class="row">
                <div class="form-group col-md-3 mb-3">
                    <label for="fecha_nueva">Fecha</label>
                    <input type="date" id="fecha_nueva" name="fecha_nueva" class="form-control" 
                           min="<?php echo $fechaInicio; ?>" max="<?php echo $fechaFin; ?>" required>
                </div>
                <div class="form-group col-md-3 mb-3">
                    <label for="hora_inicio_nueva">Hora Inicio</label>
                    <input type="time" id="hora_inicio_nueva" name="hora_inicio_nueva" class="form-control" step="60" required>
                </div>
                <div class="form-group col-md-3 mb-3">
                    <label for="hora_fin_nueva">Hora Fin</label>
                    <input type="time" id="hora_fin_nueva" name="hora_fin_nueva" class="form-control" step="60" required>
                </div>
                <div class="form-group col-md-3 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary" onclick="agregarHorario()">Agregar Horario</button>
                </div>
            </div>
        </form>
    </div>
</div>
<br>

<!-- HORARIO -->
<div class="card" >
    <div class="card-body">
    <h4 class="card-title">Horario de la Hoja de Trabajo</h4>
        <br>
        <!-- Botón para imprimir/descargar horario -->
        <div class="mb-3">
            <a href="views/vrphg.php?idare=<?=$idnorad?>&fic=2773071" title="Imprimir Horario" target="_blank" class="btn btn-info">
                <i class="fa-solid fa-print me-2"></i> Imprimir Horario
            </a>
        </div>
<div class="table-responsive">
    <table class="table table-bordered table-hover" style="width: 100%; max-width: 800px;">
        <thead class="table-success" style="color: black;">
            <tr>
                <th style="width: 60%;">Fecha</th>
                <th style="width: 20%;">Hora Inicio</th>
                <th style="width: 20%;">Hora Terminación</th>
            </tr>
        </thead>
        <tbody id="horario_body">
            <?php
            if(!empty($datOneHt) && is_array($datOneHt) && !empty($datOneHt[0]) &&
               !empty($datOneHt[0]['feclini']) && !empty($datOneHt[0]['feclin'])) {
                
                // Obtener duración del curso en horas
                $duracionCurso = isset($dtpro[0]['horlpro']) ? floatval($dtpro[0]['horlpro']) : 0;
                
                // Obtener horarios ya guardados para esta hoja de trabajo
                $mhorc_model = new Mhorc();
                $mhorc_model->setIdnorad($idnorad);
                $horariosGuardados = $mhorc_model->getAll();
                
                // Convertir horarios guardados a un array indexado por fecha
                $horariosPorFecha = [];
                $totalHorasAsignadas = 0;
                if(!empty($horariosGuardados)) {
                    foreach($horariosGuardados as $h) {
                        $fecha = isset($h['fecha_inicio']) ? $h['fecha_inicio'] : null;
                        if($fecha) {
                            if(!isset($horariosPorFecha[$fecha])) {
                                $horariosPorFecha[$fecha] = [];
                            }
                            $horariosPorFecha[$fecha][] = $h;
                            
                            // Calcular horas asignadas
                            if(isset($h['hinihor']) && isset($h['hfinhor'])) {
                                $inicio = strtotime($h['hinihor']);
                                $fin = strtotime($h['hfinhor']);
                                if($inicio && $fin) {
                                    $totalHorasAsignadas += ($fin - $inicio) / 3600;
                                }
                            }
                        }
                    }
                }
                
                // Mostrar solo las fechas que tienen horarios asignados
                if(!empty($horariosPorFecha)) {
                    // Ordenar fechas
                    ksort($horariosPorFecha);
                    
                    foreach($horariosPorFecha as $fecha => $horarios) {
                        $fechaObj = DateTime::createFromFormat('Y-m-d', $fecha);
                        if($fechaObj) {
                            $diaSemana = $fechaObj->format('l');
                            $dias_espanol = [
                                'Monday' => 'Lunes',
                                'Tuesday' => 'Martes',
                                'Wednesday' => 'Miércoles',
                                'Thursday' => 'Jueves',
                                'Friday' => 'Viernes',
                                'Saturday' => 'Sábado',
                                'Sunday' => 'Domingo'
                            ];
                            $diaEspanol = isset($dias_espanol[$diaSemana]) ? $dias_espanol[$diaSemana] : $diaSemana;
                            $fechaFormateada = $fechaObj->format('d/m/Y');
                            $unique_id = $fechaObj->format('Ymd');
                            
                            echo '<tr class="table-primary"><td colspan="3"><strong>'.$diaEspanol.' '.$fechaFormateada.'</strong></td></tr>';
                            
                            foreach($horarios as $h) {
                                $horaInicio = isset($h['hinihor']) ? $h['hinihor'] : '';
                                $horaFin = isset($h['hfinhor']) ? $h['hfinhor'] : '';
                                ?>
                                <tr id="row-<?php echo $unique_id; ?>">
                                    <td style="padding-left:2em;"><?php echo $fechaFormateada; ?>
                                        <input type="hidden" name="fecha[]" value="<?php echo $fecha; ?>">
                                        <input type="hidden" name="idhorario[]" value="<?php echo isset($h['idhor']) ? $h['idhor'] : ''; ?>">
                                    </td>
                                    <td>
                                        <input type="time" name="hora_inicio[]" class="form-control" value="<?php echo $horaInicio; ?>" style="display: inline-block;" data-id="<?php echo $unique_id; ?>" step="60">
                                    </td>
                                    <td>
                                        <input type="time" name="hora_fin[]" class="form-control" value="<?php echo $horaFin; ?>" style="display: inline-block;" data-id="<?php echo $unique_id; ?>" step="60">
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                } else {
                    echo '<tr><td colspan="3" class="text-center text-muted">No hay horarios asignados. Use la sección "Agregar Horario" para crear uno.</td></tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <div class="text-center mt-3">
        <button type="button" onclick="guardarHorario()" class="btn btn-primary">Guardar Horario</button>
    </div>
</div>

<script>
function guardarHorario() {
    // Crear un formulario dinámico para enviar los datos
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'home.php?pg=<?=$pg?>&idnorad=<?=$idnorad?>&opera=saveHorario';
    
    // Agregar campos de fecha, hora inicio y hora fin
    const fechas = document.querySelectorAll('input[name="fecha[]"]');
    const horasInicio = document.querySelectorAll('input[name="hora_inicio[]"]');
    const horasFin = document.querySelectorAll('input[name="hora_fin[]"]');
    
    let tieneDatos = false;
    
    fechas.forEach((campo, index) => {
        const hiddenFecha = document.createElement('input');
        hiddenFecha.type = 'hidden';
        hiddenFecha.name = 'fecha[]';
        hiddenFecha.value = campo.value;
        form.appendChild(hiddenFecha);
        
        if (horasInicio[index]) {
            const hiddenInicio = document.createElement('input');
            hiddenInicio.type = 'hidden';
            hiddenInicio.name = 'hora_inicio[]';
            hiddenInicio.value = horasInicio[index].value;
            form.appendChild(hiddenInicio);
        }
        
        if (horasFin[index]) {
            const hiddenFin = document.createElement('input');
            hiddenFin.type = 'hidden';
            hiddenFin.name = 'hora_fin[]';
            hiddenFin.value = horasFin[index].value;
            form.appendChild(hiddenFin);
        }
        
        if (campo.value && horasInicio[index] && horasFin[index]) {
            tieneDatos = true;
        }
    });
    
    if (!tieneDatos) {
        alert('No hay horarios para guardar. Agregue al menos un horario.');
        return;
    }
    
    document.body.appendChild(form);
    form.submit();
}

function agregarHorario() {
    const fecha = document.getElementById('fecha_nueva').value;
    const horaInicio = document.getElementById('hora_inicio_nueva').value;
    const horaFin = document.getElementById('hora_fin_nueva').value;
    
    if (!fecha || !horaInicio || !horaFin) {
        alert('Por favor complete todos los campos del horario');
        return;
    }
    
    // Validar que la hora de fin sea mayor a la hora de inicio
    if (horaFin <= horaInicio) {
        alert('La hora de finalización debe ser mayor a la hora de inicio');
        return;
    }
    
    const fechaObj = new Date(fecha);
    const diaSemana = fechaObj.toLocaleDateString('es-ES', { weekday: 'long' });
    const diaEspanol = diaSemana.charAt(0).toUpperCase() + diaSemana.slice(1);
    const fechaFormateada = fecha.split('-').reverse().join('/');
    const unique_id = fechaObj.toISOString().split('T')[0].replace(/-/g, '') + '_' + Date.now();
    
    const tbody = document.getElementById('horario_body');
    
    // Si es el primer horario, quitar el mensaje de "no hay horarios"
    const noHayHorariosMsg = tbody.querySelector('tr td[colspan="3"]');
    if (noHayHorariosMsg) {
        noHayHorariosMsg.parentElement.remove();
    }
    
    // Buscar si ya existe un encabezado para esta fecha
    let headerRow = null;
    const rows = tbody.querySelectorAll('tr');
    for (let i = 0; i < rows.length; i++) {
        if (rows[i].classList.contains('table-primary')) {
            const strongElem = rows[i].querySelector('strong');
            if (strongElem && strongElem.textContent.includes(diaEspanol) && strongElem.textContent.includes(fechaFormateada)) {
                headerRow = rows[i];
                break;
            }
        }
    }
    
    // Crear la nueva fila de horario
    const newRow = document.createElement('tr');
    newRow.id = 'row-' + unique_id;
    newRow.innerHTML = `
        <td style="padding-left:2em;">${fechaFormateada}
            <input type="hidden" name="fecha[]" value="${fecha}">
            <input type="hidden" name="idhorario[]" value="">
        </td>
        <td>
            <input type="time" name="hora_inicio[]" class="form-control" value="${horaInicio}" style="display: inline-block;" data-id="${unique_id}" step="60">
        </td>
        <td>
            <input type="time" name="hora_fin[]" class="form-control" value="${horaFin}" style="display: inline-block;" data-id="${unique_id}" step="60">
        </td>
    `;
    
    // Insertar después del encabezado de fecha si existe, o al final
    if (headerRow) {
        headerRow.parentNode.insertBefore(newRow, headerRow.nextSibling);
    } else {
        // Crear nuevo encabezado de fecha
        const headerNew = document.createElement('tr');
        headerNew.classList.add('table-primary');
        headerNew.innerHTML = '<td colspan="3"><strong>' + diaEspanol + ' ' + fechaFormateada + '</strong></td>';
        tbody.appendChild(headerNew);
        tbody.appendChild(newRow);
    }
    
    // Limpiar formulario
    document.getElementById('fecha_nueva').value = '';
    document.getElementById('hora_inicio_nueva').value = '';
    document.getElementById('hora_fin_nueva').value = '';
}

function validarHorarioGen() {
    const horaInicio = document.getElementById('hora_inicio_gen').value;
    const horaFin = document.getElementById('hora_fin_gen').value;
    
    if (!horaInicio || !horaFin) {
        alert('Por favor complete las horas de inicio y fin');
        return false;
    }
    
    if (horaFin <= horaInicio) {
        alert('La hora de finalización debe ser mayor a la hora de inicio');
        return false;
    }
    
    return confirm('¿Está seguro de generar el horario automático? Esto reemplazará todos los horarios existentes.');
}
</script>

</div> <!-- Cierre de conte -->

<!-- Modal Asignar Instructor -->
<div class="modal fade" id="AgreUsuIns" tabindex="-1" aria-labelledby="modalInstructorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="home.php?pg=<?=$pg?>&idnorad=<?=$idnorad?>&opera=AgrIns" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalInstructorLabel">Asignar Instructor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if(!empty($instructores)): ?>
            <div class="mb-3">
              <label for="idinstructor" class="form-label">Seleccione un instructor:</label>
              <select name="idinstructor" id="idinstructor" class="form-select" required>
                <option value="">-- Seleccione --</option>
                <?php foreach($instructores as $inst): ?>
                  <option value="<?=$inst['idusu']?>"><?=htmlspecialchars($inst['nomusu'])?> - <?=$inst['ndocusu']?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php else: ?>
            <div class="alert alert-warning">No hay instructores disponibles para asignar.</div>
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <?php if(!empty($instructores)): ?>
            <button type="submit" class="btn btn-primary">Asignar</button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#CargaAsp" title="Cargar Aspirantes">
  <i class="fa-solid fa-upload fa-2x" style="color: #ffffff;"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="CargaAsp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cargar Aspirantes</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="file" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Cargar</button>
      </div>
    </div>
  </div>
</div>
