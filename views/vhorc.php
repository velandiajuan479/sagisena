<?php 
require_once("controllers/chorc.php"); 
require_once("controllers/chdt.php");
require_once("controllers/cemp.php");
?>
<div class="conte">
    <?php echo titulo2("<i class='fas fa-calendar-alt'></i> Programación de Horarios", 2); ?>
    <div class="inser">
            <div class="row">   
                <?php if($datOne){ ?>
                <div class="form-group col-md-12">
                    <h2>Editando Horario No. <?=$datOne[0]['idnorad'];?></h2>
                </div>
                <?php } ?>
                <div class="form-group col-md-6">
                    <label for="codpro">Programa</label>
                    <select name="codpro" id="codpro" class="form-control form-select" required>
                        <?php if($datPr){ foreach($datPr AS $dt){ ?>
                            <option value="<?=$dt["codpro"]; ?>" <?php if($datOne && $datOne[0]['codpro']==$dt['codpro']) echo 'selected'; ?>>
                                <?=$dt["codpro"]." - ".$dt["nompro"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="idemp">Empresa</label>
                    <form method="post" id="formEmpresa" style="display: inline;">
                        <input type="hidden" name="cambiar_empresa" value="1">
                        <?php if(isset($_REQUEST['idnorad'])): ?>
                            <input type="hidden" name="idnorad" value="<?=htmlspecialchars($_REQUEST['idnorad'])?>">
                        <?php endif; ?>
                        <?php
                        // Obtener el ID de la empresa seleccionada
                        $idemp_selected = '';
                        if(isset($_POST['idemp'])) {
                            $idemp_selected = $_POST['idemp'];
                        } elseif(isset($datOne[0]['idemp'])) {
                            $idemp_selected = $datOne[0]['idemp'];
                        }
                        ?>
                        <select name="idemp" id="idemp" class="form-control form-select" onchange="this.form.submit()" required>
                            <?php if($datEm){ 
                                foreach($datEm as $dt): 
                                    $selected = ($dt['idemp'] == $idemp_selected) ? 'selected' : '';
                            ?>
                                <option value="<?=htmlspecialchars($dt["idemp"]); ?>" <?=$selected?>>
                                    <?=htmlspecialchars($dt["nomemp"]); ?>
                                </option>
                            <?php 
                                endforeach;
                            } 
                            ?>
                        </select>
                    </form>
                </div>
                <div class="form-group col-md-6">
                    <label for="codproesp">Programa Especial</label>
                    <select name="codproesp" id="codproesp" class="form-control form-select" required>
                        <?php if($datPe){ foreach($datPe AS $dt){ ?> 
                            <option value="<?=$dt["idval"]; ?>" <?php if($datOne && $datOne[0]['codproesp']==$dt['idval']) echo 'selected'; ?>>
                                <?=$dt["nomval"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label for="feclini">Fecha Inicial</label>
                    <input type="date" name="feclini" id="feclini" class="form-control" value="<?php 
                        if(!empty($datOneHt[0]['feclini'])) {
                            echo $datOneHt[0]['feclini'];
                        } elseif($datOne && $datOne[0]['feclini']) { 
                            echo $datOne[0]['feclini']; 
                        } else { 
                            echo date('Y-m-d'); 
                        } 
                    ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="feclin">Fecha Final</label>
                    <input type="date" name="feclin" id="feclin" class="form-control" value="<?php 
                        if(!empty($datOneHt[0]['feclin'])) {
                            echo $datOneHt[0]['feclin'];
                        } elseif($datOne && $datOne[0]['feclin']) { 
                            echo $datOne[0]['feclin']; 
                        } else { 
                            echo date('Y-m-d'); 
                        } 
                    ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="cupo">Cupo</label>
                    <input type="number" min="1" name="cupo" id="cupo" class="form-control" value="<?php if($datOne && $datOne[0]['cupo']) echo $datOne[0]['cupo']; ?>" required>
                </div>

                <div class="form-group col-md-3">
                    <label for="jornada">Jornada</label>
                    <select name="jornada" id="jornada" class="form-control form-select" required>
                        <?php if($datJo){ foreach($datJo AS $dt){ ?>
                            <option value="<?=$dt["idval"]?>" <?php if($datOne && $datOne[0]['jornada']==$dt['idval']) echo 'selected'; ?>>
                                <?=$dt["nomval"]?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                                                
                <div class="form-group col-md-3">
                    <label for="idfic">Cod. Ficha</label>
                    <input type="number" name="idfic" id="idfic" class="form-control" value="<?php if($datOne && $datOne[0]['idfic']) echo $datOne[0]['idfic']; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="codslem">Cod. Solicitud Empresa</label>
                    <input type="text" name="codslem" id="codslem" class="form-control" value="<?php if($datOne && $datOne[0]['codslem']) echo $datOne[0]['codslem']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="convht">Convenio</label>
                    <input type="text" name="convht" id="convht" class="form-control" value="<?php if($datOne && $datOne[0]['convht']) echo $datOne[0]['convht']; ?>">
                </div>
                <div class="form-group col-md-3">
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
<div class="card">
    <div class="card-body">
    <?php 
    // Verificar si hay datos de empresa para mostrar
    if(!empty($datOneEmp) && is_array($datOneEmp) && !empty($datOneEmp[0])): 
        $empresa = $datOneEmp[0];
    ?>
        <h4 class="card-title">
            Datos de la Empresa 
        </h4>
        <div class="row">
            <?php if(isset($empresa['numdocemp'])): ?>
            <div class="col-md-2">
                <div class="fw-bold">NIT</div>
                <div class="form-control d-inline-block"><?=htmlspecialchars($empresa['numdocemp'])?></div>
            </div>
            <?php endif; ?>
            <?php if(isset($empresa['nomemp'])): ?>
            <div class="col-md-5">
                <div class="fw-bold">Empresa</div>
                <div class="form-control d-inline-block"><?=htmlspecialchars($empresa['nomemp'])?></div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($empresa['diremp'])): ?>
            <div class="col-md-5">
                <div class="fw-bold">Dirección</div>
                <div class="form-control d-inline-block">
                    <?=htmlspecialchars($empresa['diremp'])?> 
                    <?=htmlspecialchars($empresa['nommun'])?> 
                    <?=htmlspecialchars($empresa['nomdep'])?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($empresa['nomconemp'])): ?>
            <div class="col-md-7">
                <div class="fw-bold">Contacto</div>
                <div class="form-control d-inline-block"><?=htmlspecialchars($empresa['nomconemp'])?></div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($empresa['telemp'])): ?>
            <div class="col-md-5">
                <div class="fw-bold">Teléfono</div>
                <div class="form-control d-inline-block"><?=htmlspecialchars($empresa['telemp'])?></div>
            </div>
            <?php endif; ?>
        </div>
        <p class="card-text small"></p>
    </div>
</div>
<?php endif; ?>
<br>
<!-- PROGRAMA -->
<div class="card">
    <div class="card-body">
    <?php 
    // Verificar si hay datos de empresa para mostrar
    if(!empty($datOneEmp) && is_array($datOneEmp) && !empty($datOneEmp[0])): 
        $empresa = $datOneEmp[0];
    ?>
        <h4 class="card-title">
            Programa
        </h4>
        <div class="row">
            <?php if(isset($empresa['numdocemp'])): ?>
            <div class="col-md-8">
                <div class="fw-bold">Nombre</div>
                <div class="form-control d-inline-block"><?=htmlspecialchars($empresa['numdocemp'])?></div>
            </div>
            <?php endif; ?>
            <?php if(isset($empresa['nomemp'])): ?>
            <div class="col-md-2">
                <div class="fw-bold">Versión</div>
                <div class="form-control d-inline-block"><?=htmlspecialchars($empresa['nomemp'])?></div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($empresa['diremp'])): ?>
            <div class="col-md-2">
                <div class="fw-bold">Horas</div>
                <div class="form-control d-inline-block">
                    <?=htmlspecialchars($empresa['diremp'])?> 
                    <?=htmlspecialchars($empresa['nommun'])?> 
                    <?=htmlspecialchars($empresa['nomdep'])?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <p class="card-text small"></p>
    </div>
</div>
<?php endif; ?>
<br>

<!-- INSERCION DE HORARIO -->
<div class="card">
    <div class="card-body">
    <?php 
    // Verificar si hay datos de empresa para mostrar
    if(!empty($datOneEmp) && is_array($datOneEmp) && !empty($datOneEmp[0])): 
        $empresa = $datOneEmp[0];
    ?>
        <h4 class="card-title">
            Agregar Horario
        </h4>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="convht">Fecha</label>
                <input type="date" name="convht" id="convht" class="form-control" value="<?php if($datOne && $datOne[0]['convht']) echo $datOne[0]['convht']; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="convht">Hora Inicial</label>
                <select name="convht" id="convht" class="form-control form-select">
                    <?php for($r=0;$r<24;$r++){ ?>
                        <option value="<?=$r;?>" <?php if($r==7) echo "selected" ;?>><?php if($r<10) echo "0" ;?><?=$r;?>:00</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="convht">Hora Final</label>
                <select name="convht" id="convht" class="form-control form-select">
                    <?php for($r=0;$r<24;$r++){ ?>
                        <option value="<?=$r;?>" <?php if($r==12) echo "selected" ;?>><?php if($r<10) echo "0" ;?><?=$r;?>:00</option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-1">
                <div class="fw-bold">Horas</div>
                <div class="form-control d-inline-block">5</div>
            </div>
            <div class="form-group col-md-2">
                <input type="submit" class="btn btn-primary" value="Registrar">
            </div>
        </div>
        <p class="card-text small"></p>
    </div>
</div>
<?php endif; ?>
<br>

<!-- HORARIO -->
<div class="card" >
    <div class="card-body">
    <h4 class="card-title">Horario de la Hoja de Trabajo</h4>
        <br>
<div class="table-responsive">
    <table class="table table-bordered table-hover" style="width: 100%; max-width: 900px;">
        <thead class="table-success" style="color: black;">
            <tr>
                <th style="width: 25%;">Día</th>
                <th style="width: 20%;">Fecha</th>
                <th style="width: 15%;">Hora Inicio</th>
                <th style="width: 15%;">Hora Terminación</th>
                <th style="width: 15%;">Horas Totales</th>
                <th style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody id="horario_body">
            <?php
            if(!empty($datOneHt) && is_array($datOneHt) && !empty($datOneHt[0]) && 
               !empty($datOneHt[0]['feclini']) && !empty($datOneHt[0]['feclin'])) {
                $dias_espanol = [
                    'Monday' => 'LUNES',
                    'Tuesday' => 'MARTES',
                    'Wednesday' => 'MIÉRCOLES',
                    'Thursday' => 'JUEVES',
                    'Friday' => 'VIERNES',
                    'Saturday' => 'SÁBADO',
                    'Sunday' => 'DOMINGO'
                ];
                
                try {
                    $fecha_inicio = new DateTime($datOneHt[0]['feclini']);
                    $fecha_fin = new DateTime($datOneHt[0]['feclin']);
                    $intervalo = new DateInterval('P1D');
                    $periodo = new DatePeriod($fecha_inicio, $intervalo, $fecha_fin->modify('+1 day'));
                } catch (Exception $e) {
                    echo '<tr><td colspan="6" class="text-danger">Error al procesar las fechas del horario.</td></tr>';
                    return;
                }

                // Agrupar fechas por día de la semana
                $fechas_por_dia = [];
                foreach($periodo as $fecha) {
                    $dia_ingles = $fecha->format('l');
                    $fechas_por_dia[$dia_ingles][] = clone $fecha;
                }
                
                // Obtener horarios ocupados para validación de conflictos
                $horarios_ocupados = [];
                if(isset($datHorariosOcupados) && is_array($datHorariosOcupados)) {
                    foreach($datHorariosOcupados as $hor) {
                        $fecha_hor = isset($hor['fecha_especifica']) ? $hor['fecha_especifica'] : null;
                        if($fecha_hor) {
                            $horarios_ocupados[$fecha_hor] = [
                                'hinihor' => isset($hor['hinihor']) ? $hor['hinihor'] : null,
                                'hfinhor' => isset($hor['hfinhor']) ? $hor['hfinhor'] : null,
                                'idfic' => isset($hor['idfic']) ? $hor['idfic'] : null
                            ];
                        }
                    }
                }
                
                foreach($dias_espanol as $dia_ingles => $dia_espanol) {
                    if (!isset($fechas_por_dia[$dia_ingles])) continue;
                    echo '<tr class="table-primary"><td colspan="6"><strong>'.$dia_espanol.'</strong></td></tr>';
                    foreach($fechas_por_dia[$dia_ingles] as $fecha) {
                        $fecha_formateada = $fecha->format('d/m/Y');
                        $fecha_sql = $fecha->format('Y-m-d');
                        $unique_id = $fecha->format('Ymd');
                        
                        // Verificar si el día está ocupado por otra ficha
                        $esta_ocupado = false;
                        $ficha_ocupante = null;
                        if(isset($horarios_ocupados[$fecha_sql])) {
                            $esta_ocupado = true;
                            $ficha_ocupante = $horarios_ocupados[$fecha_sql]['idfic'];
                        }
                        
                        // Determinar clases y atributos según estado
                        $row_class = $esta_ocupado ? 'table-danger' : '';
                        $disabled_attr = $esta_ocupado ? 'disabled' : '';
                        $estado_text = $esta_ocupado ? 'Bloqueado' : 'Disponible';
                        $estado_class = $esta_ocupado ? 'badge bg-danger' : 'badge bg-success';
                        
                        // Valores por defecto según el Excel (07:00 - 17:00 = 10 horas)
                        $hora_inicio_defecto = '07:00';
                        $hora_fin_defecto = '17:00';
                        $horas_totales = 10;
                        ?>
                        <tr id="row-<?php echo $unique_id; ?>" class="<?php echo $row_class; ?>">
                            <td style="padding-left:1em; font-weight:bold;"><?php echo $dia_espanol; ?></td>
                            <td>
                                <?php echo $fecha_formateada; ?>
                                <input type="hidden" name="fecha[]" value="<?php echo $fecha_sql; ?>">
                            </td>
                            <td>
                                <input type="time" name="hora_inicio[]" id="hini_<?php echo $unique_id; ?>" 
                                       class="form-control" value="<?php echo $hora_inicio_defecto; ?>" 
                                       style="display: inline-block; width: 100%;" 
                                       data-id="<?php echo $unique_id; ?>" step="60" 
                                       <?php echo $disabled_attr; ?>
                                       onchange="calcularHoras('<?php echo $unique_id; ?>')">
                            </td>
                            <td>
                                <input type="time" name="hora_fin[]" id="hfin_<?php echo $unique_id; ?>" 
                                       class="form-control" value="<?php echo $hora_fin_defecto; ?>" 
                                       style="display: inline-block; width: 100%;" 
                                       data-id="<?php echo $unique_id; ?>" step="60" 
                                       <?php echo $disabled_attr; ?>
                                       onchange="calcularHoras('<?php echo $unique_id; ?>')">
                            </td>
                            <td>
                                <input type="text" name="horas_totales[]" id="htot_<?php echo $unique_id; ?>" 
                                       class="form-control" value="<?php echo $horas_totales; ?>" 
                                       style="display: inline-block; width: 100%; text-align: center; font-weight: bold;" 
                                       readonly>
                                <input type="hidden" name="fecha_especifica[]" value="<?php echo $fecha_sql; ?>">
                            </td>
                            <td style="text-align: center;">
                                <span class="<?php echo $estado_class; ?>"><?php echo $estado_text; ?></span>
                                <?php if($esta_ocupado): ?>
                                    <br><small class="text-muted">Ficha: <?php echo htmlspecialchars($ficha_ocupante); ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <div class="text-center mt-3">
        <button type="button" onclick="guardar()" class="btn btn-primary">
            <i class="fas fa-save"></i> Guardar Horario
        </button>
        <input type="hidden" name="opera" value="save">
        <input type="hidden" name="idnorad" value="<?php if($datOne && $datOne[0]['idnorad']) echo $datOne[0]['idnorad']; ?>">
    </div>
</div>
 </div>
 </div>

<script>
function calcularHoras(id) {
    var hini = document.getElementById('hini_' + id).value;
    var hfin = document.getElementById('hfin_' + id).value;
    
    if(hini && hfin) {
        var inicio = new Date('2000-01-01 ' + hini);
        var fin = new Date('2000-01-01 ' + hfin);
        
        if(fin > inicio) {
            var diffMs = fin - inicio;
            var diffHrs = diffMs / (1000 * 60 * 60);
            document.getElementById('htot_' + id).value = diffHrs.toFixed(0);
        } else {
            document.getElementById('htot_' + id).value = '0';
        }
    }
}

function guardar() {
    // Validar que todos los campos estén completos
    var fechas = document.querySelectorAll('input[name="fecha_especifica[]"]');
    var horasInicio = document.querySelectorAll('input[name="hora_inicio[]"]:not([disabled])');
    var horasFin = document.querySelectorAll('input[name="hora_fin[]"]:not([disabled])');
    
    for(var i = 0; i < horasInicio.length; i++) {
        if(!horasInicio[i].value || !horasFin[i].value) {
            alert('Por favor complete todas las horas de inicio y fin para los días disponibles.');
            return false;
        }
    }
    
    // Recopilar datos
    var datos = [];
    for(var i = 0; i < fechas.length; i++) {
        var hiniInput = document.getElementById('hini_' + fechas[i].value.replace(/-/g, ''));
        var hfinInput = document.getElementById('hfin_' + fechas[i].value.replace(/-/g, ''));
        
        if(hiniInput && !hiniInput.disabled) {
            datos.push({
                fecha: fechas[i].value,
                hora_inicio: hiniInput.value + ':00',
                hora_fin: hfinInput.value + ':00',
                horas_totales: document.getElementById('htot_' + fechas[i].value.replace(/-/g, '')).value
            });
        }
    }
    
    // Enviar datos al servidor
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?ctl=horc&opera=save', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    var params = 'opera=save_horario&idnorad=<?php echo isset($datOne[0]["idnorad"]) ? $datOne[0]["idnorad"] : ""; ?>&' + 
                 'datos=' + encodeURIComponent(JSON.stringify(datos));
    
    xhr.onload = function() {
        if(xhr.status === 200) {
            alert('Horario guardado exitosamente');
            location.reload();
        } else {
            alert('Error al guardar el horario');
        }
    };
    
    xhr.send(params);
}
</script>


        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AgreUsuIns" title="Agregar Instructor">
          <i class="fa-solid fa-user-plus fa-2x" style="color: #ffffff;"></i>
        </button>

        <?php include("views/vhtxus.php"); ?>

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
