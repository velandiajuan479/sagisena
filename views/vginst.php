<?php require_once 'controllers/cginst.php'; 
?>
<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Gestion Instructores"); ?>
     <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
            <div class="row align-items-end"></div>
                <div class="col">
                    <label for="instructor_id">Instructor:</label>
                    <select name="instructor_id" id="instructor_id" class="form-control" required>
                        <option value="">Seleccione un instructor</option>
                        <?php foreach ($instructores as $instructor): ?>
                            <option value="<?= $instructor['idusu'] ?>">
                                <?= $instructor['nomusu'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <form method="post" action="">
                    <input type="hidden" name="opera" value="convertir">
                    <div class="row mb-2">
                        <?php foreach ($instructores as $instructor): ?>
                            <input type="hidden" name="instructor_ids[]" value="<?= $instructor['idusu'] ?>">
                        <?php endforeach; ?>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary">
                                Convertir en instructor de seguimiento
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>

<h3>Asignar Fichas o Programa</h3>

<form id="frmAsignarFicha" action="home.php?pg=<?= $pg; ?>" method="POST">
    <input type="hidden" name="opera" value="asignar_ficha">

    <div class="row align-items-end">
        <!-- Instructor -->
        <div class="col">
            <label for="asignar_instructor_id">Instructor:</label>
            <select name="asignar_instructor_id" id="asignar_instructor_id" class="form-control">
                <option value="">Seleccione un instructor</option>
                <?php foreach ($instructoresSeguimiento as $instructor): ?>
                    <option value="<?= $instructor['idusu'] ?>">
                        <?= $instructor['nomusu'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Ficha individual -->
        <div class="col">
            <label for="ficha_id">Ficha (individual):</label>
            <select name="ficha_id" id="ficha_id" class="form-control">
                <option value="">Seleccione una ficha</option>
                <?php
                $fichasPorPrograma = [];
                foreach ($fichas as $ficha) {
                    $fichasPorPrograma[$ficha['nomfic']][] = $ficha;
                }

                foreach ($fichasPorPrograma as $nombrePrograma => $grupoFichas): ?>
                    <optgroup label="<?= htmlspecialchars($nombrePrograma) ?>">
                        <?php foreach ($grupoFichas as $ficha): ?>
                            <option value="<?= $ficha['idfic'] ?>">
                                <?= $ficha['nomfic'] ?> (Ficha: <?= $ficha['idfic'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- O asignar por programa completo -->
        <div class="col">
            <label for="programa_completo">Asignar todas las fichas del programa:</label>
            <select name="programa_completo" id="programa_completo" class="form-control">
                <option value="">Seleccione un programa</option>
                <?php foreach (array_keys($fichasPorPrograma) as $programa): ?>
                    <option value="<?= htmlspecialchars($programa) ?>"><?= htmlspecialchars($programa) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Botón Asignar -->
        <div class="col-auto ms-auto text-end">
            <button type="submit" class="btn btn-success">
                Asignar
            </button>
        </div>
    </div>
</form>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Instructor</th>
            <th>Ficha Asignada</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($insseg as $row): ?>
            <tr>
                <td><?= $row['nomusu']; ?></td>
                <td><?= $row['nomfic'] . " (Ficha: " . $row['idficha'] . ")" ?></td>
                <td>
                    <a href="home.php?pg=<?= $pg; ?>&idusu=<?= $row['idusu']; ?>&idficha=<?= $row['idficha']; ?>&opera=eliminar" title="Eliminar" onclick="return eliminar();">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Instructor</th>
            <th>Ficha Asignada</th>
            <th>Acciones</th>
        </tr>
    </tfoot>    
</table>


