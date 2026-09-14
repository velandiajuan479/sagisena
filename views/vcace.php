<?php require_once 'controllers/ccace.php'; ?>

<?php echo titulo2("<i class='fa-solid fa-users'></i> Calificar Criterios de Evaluacion", 0); ?>

<div class="conte">
<form id="frm1" action="home.php?pg=<?= $pg ?>" method="POST"> 
        <input type="hidden" name="idfic" value="<?= htmlspecialchars($idfic) ?>">
        <input type="hidden" name="ope" value="save">

        <div class="row">
            <div class="form-group col-md-6">
                <label for="idusu">Seleccionar Aprendiz</label>
                <select name="idusu" id="idusu" class="form-control" required>
                    <option value="">Seleccione un aprendiz...</option>
                    <?php foreach ($aprendices as $apr) { ?>
                        <option value="<?= htmlspecialchars($apr['idusu']) ?>"><?= htmlspecialchars($apr['nomusu']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Agregar Aprendiz</button>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($aprendices)) : ?>
    <table class="table table-striped mt-4" style="width:100%">
        <thead>
            <tr>
                <th>Id Usuario</th>
                <th>Nombre del Aprendiz</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aprendices as $apr) : ?>
                <tr>
                    <td><?= htmlspecialchars($apr['idusu']) ?></td>
                    <td><?= htmlspecialchars($apr['nomusu']) ?></td>
                    <td>
                        <a href="home.php?pg=<?= $pg ?>&idfic=<?= urlencode($idfic) ?>&idusu=<?= urlencode($apr['idusu']) ?>&ope=del" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('¿Está seguro de eliminar a este aprendiz de la ficha?');" 
                           title="Eliminar Aprendiz">
                            <i class="fa-solid fa-trash-can"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="mt-4">No hay aprendices asignados a esta ficha.</p>
<?php endif; ?>