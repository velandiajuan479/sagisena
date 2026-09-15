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
                $dias_espanol = [
                    'Monday' => 'Lunes',
                    'Tuesday' => 'Martes',
                    'Wednesday' => 'Miércoles',
                    'Thursday' => 'Jueves',
                    'Friday' => 'Viernes',
                    'Saturday' => 'Sábado',
                    'Sunday' => 'Domingo'
                ];
                
                try {
                    $fecha_inicio = new DateTime($datOneHt[0]['feclini']);
                    $fecha_fin = new DateTime($datOneHt[0]['feclin']);
                    $intervalo = new DateInterval('P1D');
                    $periodo = new DatePeriod($fecha_inicio, $intervalo, $fecha_fin->modify('+1 day'));
                } catch (Exception $e) {
                    // Si hay un error al crear las fechas, no mostramos el horario
                    echo '<tr><td colspan="3" class="text-danger">Error al procesar las fechas del horario.</td></tr>';
                    return;
                }

                // Agrupar fechas por día de la semana
                $fechas_por_dia = [];
                foreach($periodo as $fecha) {
                    $dia_ingles = $fecha->format('l');
                    $fechas_por_dia[$dia_ingles][] = clone $fecha;
                }
                foreach($dias_espanol as $dia_ingles => $dia_espanol) {
                    if (!isset($fechas_por_dia[$dia_ingles])) continue;
                    echo '<tr class="table-primary"><td colspan="3"><strong>'.$dia_espanol.'</strong></td></tr>';
                    foreach($fechas_por_dia[$dia_ingles] as $fecha) {
                        $fecha_formateada = $fecha->format('d/m/Y');
                        $unique_id = $fecha->format('Ymd');
                        ?>
                        <tr id="row-<?php echo $unique_id; ?>">
                            <td style="padding-left:2em;"> <?php echo $fecha_formateada; ?>
                                <input type="hidden" name="fecha[]" value="<?php echo $fecha->format('Y-m-d'); ?>">
                            </td>
                            <td>
                                <input type="time" name="hora_inicio[]" class="form-control" value="" style="display: inline-block;" data-id="<?php echo $unique_id; ?>" step="60">
                            </td>
                            <td>
                                <input type="time" name="hora_fin[]" class="form-control" value="" style="display: inline-block;" data-id="<?php echo $unique_id; ?>" step="60">
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
                    <input onClick={guardar()} class="btn btn-primary" value="Guardar">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idnorad" value="<?php if($datOne && $datOne[0]['idnorad']) echo $datOne[0]['idnorad']; ?>">
                </div>
</div>
 </div>
 </div>


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
