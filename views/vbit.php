<?php require_once 'controllers/cbit.php'; 
?>
<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Bitácora"); ?>

    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Datos del aprendiz -->
                <div class="section-aprendiz p-3 border rounded mb-4 col-md-12 row">
                    <h3 class="mt-2 mb-3">Datos del Aprendiz</h3>

                    <div class="form-group col-md-6">
                        <label>Nombre del Aprendiz</label>
                        <input type="text" name="nombre_aprendiz" class="form-control" value="<?= $datOne[0]['nomusu'];?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tipo documento</label>
                        <select name="tipo_documento" class="form-select">
                            <option value="CC">Cédula</option>
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="CE">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Número Documento</label>
                        <input type="text" name="numero_id" class="form-control" value="<?= $datOne[0]['ndocusu'] ; ?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Teléfono</label>
                        <input type="text" name="telefono_aprendiz" class="form-control" value="<?= $datOne[0]['telcan'] ; ?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Correo institucional</label>
                        <input type="email" name="correo_institucional" class="form-control" value="<?= $datOne[0]['emausu'];?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Correo personal</label>
                        <input type="email" name="correo_personal" class="form-control" value="<?= $datOne[0]['emausu'];?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Número de grupo</label>
                        <input type="text" name="numero_grupo" class="form-control" value="<?= $datFicha[0]['idfic'] ?? ''; ?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Programa de formación</label>
                        <input type="text" name="programa_formacion" class="form-control" value="<?= $datPrograma[0]['nomfic'] ?? ''; ?>" disabled>
                    </div>
                </div>

                <!-- Empresa/Organización -->
                <div class="section-empresa p-3 border rounded mb-4 col-md-12 row">
                    <h3 class="mt-2 mb-3">Empresa / Organización</h3>

                    <div class="form-group col-md-6">
                        <label>Nombre empresa</label>
                        <input type="text" name="nombre_empresa" class="form-control" 
                            value="<?= $datOneBit['nombre_empresa'] ?? '' ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>NIT</label>
                        <input type="text" name="nit" class="form-control" 
                            value="<?= $datOneBit['nit'] ?? '' ?>" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Bitácora N°</label>
                        <?php if (!empty($datOneBit['numero_bitacora'])): ?>
                            <input type="text" name="numero_bitacora_mostrar" class="form-control"
                                value="<?= $datOneBit['numero_bitacora'] ?>" readonly>
                            <input type="hidden" name="numero_bitacora" value="<?= $datOneBit['numero_bitacora'] ?>">
                        <?php elseif (!empty($proximoNumero)): ?>
                            <input type="text" name="numero_bitacora_mostrar" class="form-control"
                                value="<?= $proximoNumero ?>" disabled>
                            <input type="hidden" name="numero_bitacora" value="<?= $proximoNumero ?>">
                        <?php else: ?>
                            <input type="text" class="form-control is-invalid" value="Límite alcanzado" disabled>
                        <?php endif; ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha inicio del período a reportar</label>
                        <input type="date" name="fecha_inicio" class="form-control" 
                            value="<?= $datOneBit['fecha_inicio'] ?? $fechaInicioValor ?>" readonly>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Fecha fin del período a reportar</label>
                        <input type="date" name="fecha_fin" class="form-control" 
                            value="<?= $datOneBit['fecha_fin'] ?? $fechaFinValor ?>" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nivel de ARL</label>
                        <select name="nvlarl" class="form-control">
                            <option value="">-- Seleccione --</option>
                            <?php
                            $niveles = ['1', '2', '3', '4', '5'];
                            $valorActual = $datOneBit['nvlarl'] ?? '';
                            foreach ($niveles as $nivel): ?>
                                <option value="<?= htmlspecialchars($nivel) ?>" 
                                    <?= $nivel == $valorActual ? 'selected' : '' ?>>
                                    Nivel <?= $nivel ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- Ente Coformador / Instructor -->
                <div class="section-coformador p-3 border rounded mb-4 col-md-12 row">
                    <h3 class="mt-2 mb-3">Ente Coformador e Instructor</h3>

                    <input type="hidden" name="idjefe" value="<?= $datJefes[0]['idusu']; ?>">

                    <div class="form-group col-md-6">
                        <label>Nombre Jefe Inmediato</label>
                        <input type="text" name="nombre_coformador" class="form-control" value="<?= $datJefes[0]['nomusu']?? '' ?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Cargo</label>
                        <input type="text" name="cargo_coformador" class="form-control" value="<?= $datCargo[0]['nomper']?? '' ?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Teléfono Jefe Inmediato</label>
                        <input type="text" name="telefono_coformador" class="form-control" value="<?= $datJefes[0]['telcan']?? '' ?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Correo Jefe Inmediato</label>
                        <input type="email" name="correo_coformador" class="form-control" value="<?= $datJefes[0]['emausu']?? '' ?>" disabled>
                    </div>

                    <input type="hidden" name="idinstructor" value="<?= $datInst[0]['idusu']?? '' ?>">
                    <div class="form-group col-md-6">
                        <label>Nombre Instructor</label>
                        <input type="text" name="nombre_instructor" class="form-control" value="<?= $datInst[0]['nomusu']?? ''?>" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Correo Instructor</label>
                        <input type="email" name="correo_instructor" class="form-control" value="<?= $datInst[0]['emausu']?? '' ?>" disabled> 
                    </div>
                </div>

                <h3 class="mt-2 mb-3">Alternativa Subalternativa y Actividades</h3>
                <div class="form-group col-md-6">
                    <label>Alternativa Etapa Productiva</label>
                    <select name="idaltep" class="form-select" required>
                        <option value="">Seleccione una alternativa</option>
                        <?php foreach($datDominios as $dominio): ?>
                            <option value="<?= $dominio['iddom']; ?>"<?php if($datOneBit && $datOneBit['idaltep'] == $dominio['iddom']) echo ' selected'; ?>>
                                <?= $dominio['nomdom']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Subalternativa Etapa Productiva</label>
                    <select name="idsubaltep" class="form-select" required>
                        <option value="">Seleccione una subalternativa</option>
                        <?php foreach($datValores as $valor): ?>
                            <option value="<?= $valor['idval']; ?>"<?php if($datOneBit && $datOneBit['idsubaltep'] == $valor['idval']) echo ' selected'; ?>>
                                <?= $valor['nomval']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Actividades dinámicas: mínimo 2, el usuario puede agregar más -->
                <div id="actividades-container">
                    <?php for ($i = 0; $i < 2; $i++): ?>
                        <div class="actividad-item mb-3 border rounded p-2 row">
                            <div class="form-group col-md-6">
                                <label>Fecha inicio actividad</label>
                                <input type="date" name="fecha_inicio_act[]" class="form-control" 
                                    min="<?= $fechaInicioPeriodo ?>" max="<?= $fechaFinPeriodo ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fecha fin actividad</label>
                                <input type="date" name="fecha_fin_act[]" class="form-control"
                                    min="<?= $fechaInicioPeriodo ?>" max="<?= $fechaFinPeriodo ?>">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Descripción Actividad</label>
                                <input type="text" name="actividad_descripcion[]" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Evidencia cumplimiento</label>
                                <input type="text" name="actividad_evidencia[]" class="form-control">
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-secondary" onclick="agregarActividad()">Agregar otra actividad</button>
                </div>
                <script>
                    const fechaInicioPeriodo = '<?= $fechaInicioPeriodo ?>';
                    const fechaFinPeriodo = '<?= $fechaFinPeriodo ?>';
                    console.log("Fecha inicio periodo:", fechaInicioPeriodo);
                    console.log("Fecha fin periodo:", fechaFinPeriodo);
                    function agregarActividad() {
                        const container = document.getElementById('actividades-container');
                        const div = document.createElement('div');
                        div.className = 'actividad-item mb-3 border rounded p-2 row'; 

                        div.innerHTML = `
                            <div class="form-group col-md-6">
                                <label>Fecha inicio actividad</label>
                                <input type="date" name="fecha_inicio_act[]" class="form-control"
                                    min="${fechaInicioPeriodo}" max="${fechaFinPeriodo}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Fecha fin actividad</label>
                                <input type="date" name="fecha_fin_act[]" class="form-control"
                                    min="${fechaInicioPeriodo}" max="${fechaFinPeriodo}">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Descripción Actividad</label>
                                <input type="text" name="actividad_descripcion[]" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Evidencia cumplimiento</label>
                                <input type="text" name="actividad_evidencia[]" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentNode.parentNode.remove()">Eliminar</button>
                            </div>
                        `;
                        container.appendChild(div);
                    }
                </script>
                <div class="form-group col-md-12">
                    <label>Tipo de evidencia</label>
                    <select class="form-select" name="tipo_evidencia" id="tipo_evidencia" onchange="mostrarCampoEvidencia()">
                        <option value="">Seleccione una opción</option>
                        <option value="archivo">Subir archivo</option>
                        <option value="link">Ingresar enlace</option>
                    </select>
                </div>

                <div class="form-group col-md-12" id="campo_archivo" style="display:none;">
                    <label>Archivo evidencia (PDF, imagen, etc.)</label>
                    <input type="file" name="archivo_evidencia" class="form-control" accept="application/pdf,image/*">
                </div>

                <div class="form-group col-md-12" id="campo_link" style="display:none;">
                    <label>Enlace evidencia</label>
                    <input type="url" name="link_evidencia" class="form-control" placeholder="https://...">
                </div>

                <script>
                function mostrarCampoEvidencia() {
                    const tipo = document.getElementById('tipo_evidencia').value;
                    document.getElementById('campo_archivo').style.display = (tipo === 'archivo') ? 'block' : 'none';
                    document.getElementById('campo_link').style.display = (tipo === 'link') ? 'block' : 'none';
                }
                </script>
                <!-- Botón de envío -->
                <div class="form-group col-md-12">
                    <br>
                    <input type="submit" class="btn btn-primary" value="<?= $datOneBit ? "Actualizar" : "Registrar"; ?>">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idbitacora" value="<?= isset($datOneBit['idbitacora']) ? $datOneBit['idbitacora'] : ''; ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<h3>Ver Bitácoras</h3>

<?php 
if (!empty($bitacorasAMostrar)) {
?>
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Número</th>
                <th>Empresa</th>
                <th>Fecha Entrega</th>
                <th>Jefe</th>
                <th>Instructor</th>
                <th>Estado / Acciones</th> <!-- 🔹 Columna combinada -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bitacorasAMostrar as $bit) { ?>
                <tr>
                    <td><?= $bit['numero_bitacora'] ?></td>

                    <!-- Columna Empresa con todos los datos juntos -->
                    <td>
                        <strong><?= $bit['nombre_empresa'] ?></strong><br>
                        <small>NIT: <?= $bit['nit'] ?></small><br>
                        <small>Inicio: <?= $bit['fecha_inicio'] ?></small><br>
                        <small>Fin: <?= $bit['fecha_fin'] ?></small>
                        <small>Nivel ARL: <?= $bit['nvlarl'] ?? 'No especificado' ?></small>
                    </td>

                    <td><?= $bit['fecha_entrega'] ?></td>
                    <td><?= $bit['nombre_jefe'] ?? 'Sin asignar' ?></td>
                    <td><?= $bit['nombre_instructor'] ?? 'Sin asignar' ?></td>

                    <!-- 🔹 Estado y Acciones en una sola línea -->
                    <td>
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:nowrap; white-space:nowrap;">
                            <!-- Estado -->
                            <?php
                            $estado = $bit['estado'] ?? 'pendiente';
                            if ($estado === 'Aprobado') {
                                echo '<i class="fa-solid fa-circle-check fa-2x " title="Aprobado"></i>';
                            } elseif ($estado === 'Rechazado') {
                                echo '<i class="fa-solid fa-circle-xmark fa-2x text-danger" title="Rechazado"></i>';
                            } else {
                                echo '<i class="fa-solid fa-circle-minus fa-2x text-secondary" title="Pendiente"></i>';
                            }
                            ?>

                            <!-- Acciones -->
                            <i class="fa-solid fa-file-pdf fa-2x" title="Ver bitácora PDF" style="cursor:pointer;"
                            onclick="window.open('views/pdfbit.php?idusu=<?= $idusu; ?>&idbitacora=<?= $bit['idbitacora'] ?>&pdf=ok', '_blank')"></i>

                            <i class="fa-solid fa-eye fa-2x" title="Ver actividades" style="cursor:pointer"
                            data-bs-toggle="modal" data-bs-target="#bitDet<?= $bit['idbitacora'] ?>"></i>
                            <?= modalBitacoraDetalles($bit['idbitacora'], $bit['numero_bitacora'], $pg); ?>

                            <i class="fa-brands fa-readme fa-2x" title="Ver observación" style="cursor:pointer"
                            data-bs-toggle="modal" data-bs-target="#modalObs<?= $bit['idbitacora'] ?>"></i>
                            <?= modalVerObservacion($bit['idbitacora'], $bit['numero_bitacora'], $bit['observacion'] ?? 'Sin observación'); ?>

                            <i class="fa fa-download fa-2x" title="Descargar certificado PDF" style="cursor:pointer"
                            onclick="window.location.href='views/pdfcerti.php?idusu=<?= $idusu; ?>&pdf=ok'"></i>

                            <i class="fa-solid fa-pen-to-square fa-2x" title="Editar" style="cursor:pointer"
                            onclick="window.location.href='home.php?pg=<?= $pg ?>&idbitacora=<?= $bit['idbitacora'] ?>&opera=edit'"></i>

                            <i class="fa-solid fa-trash-can fa-2x text-danger" title="Eliminar" style="cursor:pointer"
                            onclick="if(confirm('¿Está seguro de eliminar esta bitácora?')){window.location.href='home.php?pg=<?= $pg ?>&idbitacora=<?= $bit['idbitacora'] ?>&opera=Eliminar';}"></i>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Número</th>
                <th>Empresa</th>
                <th>Fecha Entrega</th>
                <th>Jefe</th>
                <th>Instructor</th>
                <th>Estado / Acciones</th> <!-- 🔹 Footer ajustado -->
            </tr>
        </tfoot>
    </table>
<?php } else { ?>
    <div class="alert alert-info mt-3">
        No se encontraron bitácoras registradas.
    </div>
<?php } ?>
