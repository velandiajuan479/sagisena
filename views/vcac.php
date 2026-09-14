<?php 
require_once 'controllers/ccac.php';
?>

<?php echo titulo2("<i class='".$icono."'></i> Calificar Criterio de Evaluación", 0); ?>

<?php if (!empty($aprendices)) : ?>
    <form action="home.php?pg=<?= $pg; ?>" method="POST">
        <input type="hidden" name="idfic" value="<?= htmlspecialchars($idfic) ?>">

        <div class="table-responsive ms-3 mt-4">
            <table class="table table-bordered text-center align-middle w-auto">
                <thead class="table-light">
                    <tr>
                        <th class="text-start">Aprendiz</th>
                        <?php foreach ($criterios as $cri): ?>
                            <th><?= htmlspecialchars($cri['nomcri']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($aprendices as $apr): ?>
                        <tr>
                            <td class="text-start">
                                <i class="fa-solid fa-user text-success me-2"></i>
                                <?= htmlspecialchars($apr['nomusu']) ?>
                            </td>
                            <?php foreach ($criterios as $cri): ?>
                                <td class="text-start">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                            name="calificaciones[<?= $apr['idusu'] ?>][<?= $cri['idcri'] ?>]" 
                                            value="<?= $cri['vscum'] ?>" id="c-<?= $apr['idusu'] ?>-<?= $cri['idcri'] ?>" checked>
                                        <label class="form-check-label small" for="c-<?= $apr['idusu'] ?>-<?= $cri['idcri'] ?>">
                                            ✅ Cumplió
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                            name="calificaciones[<?= $apr['idusu'] ?>][<?= $cri['idcri'] ?>]" 
                                            value="<?= $cri['vpar'] ?>" id="p-<?= $apr['idusu'] ?>-<?= $cri['idcri'] ?>">
                                        <label class="form-check-label small" for="p-<?= $apr['idusu'] ?>-<?= $cri['idcri'] ?>">
                                            ⚠️ Parcialmente
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                            name="calificaciones[<?= $apr['idusu'] ?>][<?= $cri['idcri'] ?>]" 
                                            value="0" id="n-<?= $apr['idusu'] ?>-<?= $cri['idcri'] ?>">
                                        <label class="form-check-label small" for="n-<?= $apr['idusu'] ?>-<?= $cri['idcri'] ?>">
                                            ❌ No cumplió
                                        </label>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end me-3 mb-4">
            <button type="submit" class="btn btn-primary">
                Guardar Calificaciones
            </button>
        </div>
    </form>
<?php else: ?>
    <p class="text-muted fs-5 ms-3">No se encontraron aprendices para esta ficha.</p>
<?php endif; ?> 
