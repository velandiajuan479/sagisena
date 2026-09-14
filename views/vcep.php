<?php require_once 'controllers/ccep.php';
?>

<?php echo titulo2("<i class='$icono'></i> Coordinacion Etapa Productiva",2); ?>

<h3>Instructores</h3>
<?php if ($instructores): ?>
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre Instructor</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Fichas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($instructores as $ins): ?>
                <tr>
                    <td><?= $ins['ndocusu']; ?></td>
                    <td><?= $ins['nomusu']; ?></td>
                    <td><?= $ins['telcan']; ?></td>
                    <td><?= $ins['emausu']; ?></td>
                    <td>
                        <?php foreach ($ins['fichas'] as $idficha): ?>
                            <a href="home.php?pg=2303&idficha=<?= $idficha ?>&idusu=<?= $ins['idusu'] ?>" 
                            class="badge bg-success text-decoration-none m-1">
                                <?= $idficha ?>
                            </a> 
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Documento</th>
                <th>Nombre Instructor</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Fichas</th>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <p>No hay instructores registrados.</p>
<?php endif; ?>           