<?php require_once("controllers/ctrc.php"); ?>

<div class="card mb-4 border-success">
    <div class="card-header card-header-verde">
        <h5 class="mb-0">Estado Actual</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped" width="style: 100%;">
                <thead>
                    <tr>
                        <th>Trabajo</th>
                        <th>Usuario</th>
                        <th>Paso</th>
                        <th>Fecha</th>
                        <th>Radicado</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($trazas as $traza): ?>
                    <tr>
                        <td><?= htmlspecialchars($traza['trabajo']) ?></td>
                        <td><?= htmlspecialchars($traza['usuario']) ?></td>
                        <td><?= htmlspecialchars($traza['paso']) ?></td>
                        <td><?= htmlspecialchars($traza['fecha']) ?></td>
                        <td><?= htmlspecialchars($traza['radicado']) ?></td>
                        <td>
                            <span class="badge <?= $traza['estado'] == 'En proceso' ? 'badge-proceso' : 'badge-finalizado' ?>">
                                <?= htmlspecialchars($traza['estado']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>