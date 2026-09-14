<?php require_once("controllers/ctzmat.php"); ?>
<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Trazabilidad de matricula",1); ?>
    <!-- Formulario -->
    <div class="inser">
        <form id="frmins" action="home.php?pg=tzmat" method="POST">
            <div class="row">
                <!-- Campo: Paso -->
                <div class="form-group col-md-4">
                    <label for="idpas">Paso:</label>
                    <select name="idpas" id="idpas" class="form-control form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($datPas ?? [] as $dt): ?>
                            <option value="<?= htmlspecialchars($dt["idpas"]) ?>" <?= (isset($datOne) && $datOne["idpas"] == $dt["idpas"]) ? "selected" : "" ?>>
                                <?= htmlspecialchars($dt["descpas"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Campo: Registro -->
                <div class="form-group col-md-4">
                    <label for="iduxf">Registro:</label>
                    <select name="iduxf" id="iduxf" class="form-control form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($datUxf ?? [] as $dt): ?>
                            <option value="<?= htmlspecialchars($dt["iduxf"]) ?>" <?= (isset($datOne) && $datOne["iduxf"] == $dt["iduxf"]) ? "selected" : "" ?>>
                                #<?= htmlspecialchars($dt["iduxf"]) ?> (<?= date("d/m/Y", strtotime($dt["fecreg"])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Campo: Fecha -->
                <div class="form-group col-md-4">
                    <label for="fecreg">Fecha:</label>
                    <input type="datetime-local" name="fecreg" id="fecreg" class="form-control" 
                           value="<?= isset($datOne) ? str_replace(' ', 'T', htmlspecialchars($datOne["fecreg"])) : '' ?>" required>
                </div>

                <!-- Campo: Observación -->
                <div class="form-group col-md-12">
                    <label for="obstrm">Observación:</label>
                    <textarea name="obstrm" id="obstrm" class="form-control"><?= htmlspecialchars($datOne["obstrm"] ?? '') ?></textarea>
                </div>

                <!-- Botones -->
                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="<?= isset($datOne) ? 'Actualizar' : 'Guardar' ?>">
                    <?php if (isset($datOne)): ?>
                        <a href="home.php?pg=tzmat" class="btn btn-secondary">Cancelar</a>
                        <input type="hidden" name="opera" value="edit">
                        <input type="hidden" name="idtrm" value="<?= htmlspecialchars($datOne["idtrm"]) ?>">
                    <?php else: ?>
                        <input type="hidden" name="opera" value="save">
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Mensajes de error -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Tabla de registros -->
    <h3 class="mt-4">Registros de Trazabilidad</h3>
    <?php if (!empty($datAll)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paso</th>
                        <th>Registro</th>
                        <th>Fecha</th>
                        <th>Observación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datAll as $dt): ?>
                        <tr>
                            <td><?= htmlspecialchars($dt["idtrm"]) ?></td>
                            <td><?= htmlspecialchars($dt["nombre_paso"] ?? "N/A") ?></td>
                            <td>#<?= htmlspecialchars($dt["iduxf"]) ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($dt["fecreg"])) ?></td>
                            <td><?= htmlspecialchars($dt["obstrm"]) ?></td>
                            <td>
                                <a href="home.php?pg=tzmat&idtrm=<?= htmlspecialchars($dt['idtrm']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="home.php?pg=tzmat" method="POST" style="display: inline;">
                                    <input type="hidden" name="opera" value="del">
                                    <input type="hidden" name="idtrm" value="<?= htmlspecialchars($dt['idtrm']) ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirmarEliminacion()">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No hay registros de trazabilidad.</div>
    <?php endif; ?>
</div>

<script>
    function confirmarEliminacion() {
        return confirm("¿Estás seguro de eliminar este registro?");
    }
</script>