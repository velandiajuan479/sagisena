<?php
require_once 'models/mcep.php';

$idusu = $_GET['idusu'] ?? null;
$idficha = $_GET['idficha'] ?? null;

$mcep = new Mcep();
$aprendices = $mcep->getAprendicesByFicha($idficha);

// Agregar resumen de bitácoras
$aprendices_resumen = [];
if ($aprendices) {
    foreach ($aprendices as $ap) {
        $resumen_bitacoras = $mcep->getResumenBitacorasAprendiz($ap['idusu'], $idficha);
        $ap['resumen_bitacoras'] = $resumen_bitacoras;
        $aprendices_resumen[] = $ap;
    }
}
?>

<div class="container-fluid mt-4">
    <h2 class="mb-3">Aprendices - Ficha <?= htmlspecialchars($idficha) ?></h2>
    <a href="javascript:history.back()" class="btn btn-secondary mb-3">Volver</a>

    <?php if ($aprendices_resumen): ?>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead class="table-success">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Evaluadas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($aprendices_resumen as $resumenA): ?>
                        <tr>
                            <td><?= $resumenA['ndocusu'] ?></td>
                            <td><?= $resumenA['nomusu'] ?></td>
                            <td><?= $resumenA['telcan'] ?></td>
                            <td>
                                <?php
                                    $aprobadas = $resumenA['resumen_bitacoras']['aprobado'] ?? 0;
                                    $rechazadas = $resumenA['resumen_bitacoras']['rechazado'] ?? 0;
                                    $pendientes = $resumenA['resumen_bitacoras']['pendiente'] ?? 0;
                                ?>
                                <?php if ($aprobadas == 0 && $rechazadas == 0 && $pendientes == 0): ?>
                                    <span class="text-muted">No hay bitácoras registradas</span>
                                <?php else: ?>
                                    <span class="text-success me-3" title="Aprobadas">
                                        <i class="fa-solid fa-circle-check fa-lg"></i> <?= $aprobadas ?>
                                    </span>
                                    <span class="text-danger me-3" title="Rechazadas">
                                        <i class="fa-solid fa-circle-xmark fa-lg"></i> <?= $rechazadas ?>
                                    </span>
                                    <span class="text-secondary" title="Pendientes">
                                        <i class="fa-solid fa-circle-minus fa-lg"></i> <?= $pendientes ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-success">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Evaluadas</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">
            No hay aprendices con bitácoras pendientes en esta ficha.
        </div>
    <?php endif; ?>
</div>



