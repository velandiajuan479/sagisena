<?php require_once 'controllers/cevb.php'; ?>
<div class="conte">
    <?= titulo2("<i class='$icono'></i> Evaluar Bitácoras", 2); ?>

    <!-- ================== FICHAS Y BITÁCORAS PENDIENTES ================== -->
    <h3>Fichas Asignadas</h3>
    <form method="GET" action="home.php" class="mt-3 row">
        <input type="hidden" name="pg" value="<?= $pg; ?>">
        <input type="hidden" name="opera" value="evaluar_bitacora">
        <div class="mb-3">
            <label for="idficha_eval" class="form-label">Selecciona una ficha:</label>
            <select name="idficha" id="idficha_eval" class="form-select" required onchange="this.form.submit()">
                <option value="" disabled selected>Elige una ficha</option>
                <?php foreach ($fichas as $ficha): ?>
                    <option value="<?= $ficha['idficha']; ?>" <?= (isset($_GET['idficha']) && $_GET['opera'] == 'evaluar_bitacora' && $_GET['idficha'] == $ficha['idficha']) ? 'selected' : '' ?>>
                        Ficha <?= $ficha['idficha']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if (!empty($aprendices)): ?>
    <h3>Aprendices de la Ficha Seleccionada</h3>
    <form method="POST" action="home.php?pg=<?= $pg; ?>">
        <input type="hidden" name="opera" value="evaluar_masivamente">

        <table class="table table-striped" id="tabla_bitacoras">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all" onclick="toggleAll(this)"></th>
                    <th>Número Bitácora</th>
                    <th>Nombre Aprendiz</th>
                    <th>Empresa</th>
                    <th>NIT</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aprendices as $bitacora): ?>
                    <tr>
                        <td><input type="checkbox" name="bitacoras_seleccionadas[]" value="<?= $bitacora['idbitacora']; ?>"></td>
                        <td><?= $bitacora['numero_bitacora']; ?></td>
                        <td><?= $bitacora['nomusu']; ?></td>
                        <td><?= $bitacora['nombre_empresa']; ?></td>
                        <td><?= $bitacora['nit']; ?></td>
                        <td><?= $bitacora['fecha_inicio']; ?></td>
                        <td><?= $bitacora['fecha_fin']; ?></td>
                        <td style="text-align:right;">
                            <i class="fa-solid fa-eye fa-2x" title="Ver actividades" style="cursor:pointer"
                               data-bs-toggle="modal" data-bs-target="#bitDet<?= $bitacora['idbitacora'] ?>"></i>
                            <?= modalBitacoraDetalles($bitacora['idbitacora'], $bitacora['numero_bitacora'], $pg); ?>
                            <!-- Ver Documento Bitacora -->
                            <i class="fa-solid fa-file-pdf fa-2x" title="Ver bitácora PDF" style="cursor:pointer; margin-right: 10px;"
                               onclick="window.open('views/pdfbit.php?idusu=<?= $bitacora['idusu']; ?>&idbitacora=<?= $bitacora['idbitacora'] ?>&pdf=ok', '_blank')"></i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4 p-3 border rounded bg-light">
            <h5>Evaluar Seleccionadas</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="estado_masivo">Estado:</label>
                    <select name="estado_masivo" id="estado_masivo" class="form-select">
                        <option value="">Seleccione</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>
                <div class="col-md-9 mb-3">
                    <label for="observacion_masiva">Observación común:</label>
                    <textarea name="observacion_masiva" id="observacion_masiva" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Evaluar Seleccionadas</button>
        </div>
    </form>

    <script>
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('input[name="bitacoras_seleccionadas[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }
    </script>
    <?php elseif (isset($_GET['idficha'])): ?>
        <div class="alert alert-warning mt-3">No hay aprendices asignados a esta ficha.</div>
    <?php endif; ?>

    <!-- ================== TABLA DE BITÁCORAS APROBADAS ================== -->
    <div class="container-fluid mt-4">
        <h3 class="mb-3">Bitácoras Aprobadas</h3>

        <?php if (!empty($aprendices_aprobados)): ?>
            <div class="table-responsive">
                <table id="tabla_aprobadas" class="table table-striped table-bordered" style="width:100%">
                    <thead class="table-success">
                        <tr>
                            <th>Número Bitácora</th>
                            <th>Documento</th>
                            <th>Aprendiz</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aprendices_aprobados as $ap): ?>
                            <tr>
                                <td><?= $ap['numero_bitacora'] ?></td>
                                <td><?= $ap['ndocusu'] ?></td>
                                <td><?= $ap['nomusu'] ?></td>
                                <td><?= $ap['fecha_inicio'] ?></td>
                                <td><?= $ap['fecha_fin'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-success">
                        <tr>
                            <th>Número Bitácora</th>
                            <th>Documento</th>
                            <th>Aprendiz</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">
                No hay bitácoras aprobadas en esta ficha.
            </div>
        <?php endif; ?>
    </div>

    <!-- ================== TABLA DE BITÁCORAS RECHAZADAS ================== -->
    <div class="container-fluid mt-4">
        <h3 class="mb-3 text-danger">Bitácoras Rechazadas</h3>

        <?php if (!empty($aprendices_rechazados)): ?>
            <div class="table-responsive">
                <table id="tabla_rechazadas" class="table table-striped table-bordered" style="width:100%">
                    <thead class="table-danger">
                        <tr>
                            <th>Número Bitácora</th>
                            <th>Documento</th>
                            <th>Aprendiz</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aprendices_rechazados as $ap): ?>
                            <tr>
                                <td><?= $ap['numero_bitacora'] ?></td>
                                <td><?= $ap['ndocusu'] ?></td>
                                <td><?= $ap['nomusu'] ?></td>
                                <td><?= $ap['fecha_inicio'] ?></td>
                                <td><?= $ap['fecha_fin'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-danger">
                        <tr>
                            <th>Número Bitácora</th>
                            <th>Documento</th>
                            <th>Aprendiz</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-3">
                No hay bitácoras rechazadas en esta ficha.
            </div>
        <?php endif; ?>
    </div>
</div>