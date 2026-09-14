<?php require_once("controllers/cpasmat.php"); ?>
<div class="conte">
    <?php echo titulo2("<i class='fa-solid fa-list'></i> Paso", 1); ?>
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="idcom">Flujo</label>
                    <select name="idflu" id="idflu" class="form-control form-select" required>
                        <?php if ($datFlu) { foreach ($datFlu as $flu) { ?>
                            <option value="<?= $flu['idflu']; ?>" 
                                <?php if ($datOne && $datOne['idflu'] == $flu['idflu']) echo "selected"; ?>>
                                <?= $flu['nomflu']; // Asumiendo que compromiso tiene un campo nomcom ?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="descpas">Descripción</label>
                    <input type="text" name="descpas" id="descpas" class="form-control" 
                        value="<?php if ($datOne) echo $datOne['descpas']; ?>" required>
                </div>
               <div class="form-group col-md-3">
                    <label for="idcom">Perfil</label>
                    <select name="idper" id="idper" class="form-control form-select" required>
                        <?php if ($datPer) { foreach ($datPer as $per) { ?>
                            <option value="<?= $per['idper']; ?>" 
                                <?php if ($datOne && $datOne['idper'] == $per['idper']) echo "selected"; ?>>
                                <?= $per['nomper']; // Asumiendo que compromiso tiene un campo nomcom ?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <br>
                    <input type="submit" value="Guardar" class="btn btn-primary">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idpas" value="<?= $idpas; ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Descripcion del Paso</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($datAll) { foreach ($datAll as $paso) { ?>
            <tr>
                <td>
                    <big><strong><?= $paso['idpas']; ?> - <?= $paso['descpas']; ?></strong></big><br>
                    Paso: <?= $paso['idpas']; ?><br>
                    Flujo: <?= $paso['idflu']; ?>
                    Perfil: <?= $paso['idper']; ?>
                </td>
                <td>
                    <a href="home.php?pg=<?= $pg; ?>&idpas=<?= $paso['idpas']; ?>&opera=edi" title="Editar">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </a>
                    <a href="home.php?pg=<?= $pg; ?>&idpas=<?= $paso['idpas']; ?>&opera=eli" title="Eliminar" onclick="return eliminar();">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                    </a>
                </td>
            </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>