<?php require_once("controllers/cpas.php"); ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Paso",1); ?>
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="descpas">Descripción</label>
                    <input type="text" name="descpas" id="descpas" class="form-control"
                        value="<?php if ($datOne) echo $datOne[0]['descpas']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="idflu">Flujo</label>
                    <select name="idflu" id="idflu" class="form-control form-select">
                    <?php if($datFlu){ foreach($datFlu AS $dt){ ?>
                        <option value="<?=$dt['idflu'];?>" <?php if($datOne && $datOne[0]['idflu']==$dt['idflu']) echo "selected"; ?>><?=$dt['nomflu'];?></option>
                    <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="idper">Perfil</label>
                    <select name="idper" id="idper" class="form-control form-select">
                    <?php if($datPef){ foreach($datPef AS $dt){ ?>
                        <option value="<?=$dt['idper'];?>" <?php if ($datOne && $datOne[0]['idper']==$dt['idper']) echo "selected"; ?>><?=$dt['nomper'];?></option>
                    <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <br>
                    <input type="submit" value="Guardar" class="btn btn-primary">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idpas"
                        value="<?php if ($datOne) echo $datOne[0]['idpas']; ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Paso</th>
            <th>Flujo</th>
            <th>Perfil</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($datAll) { foreach ($datAll as $p) { ?>
            <tr>
                <td>
                    <?=$p['idpas'];?> - <?=htmlspecialchars($p['descpas']); ?>
                </td>
                <td><?=$p['nomflu'];?></td>
                <td><?=$p['nomper'];?></td>
                <td>
                    <a href="home.php?pg=<?=$pg;?>&idpas=<?=$p['idpas']; ?>&opera=edi" title="Editar">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </a>
                    <a href="home.php?pg=<?=$pg;?>&idpas=<?=$p['idpas']; ?>&opera=eli" title="Eliminar"
                        onclick="return eli(this);">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                    </a>
                </td>
            </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Paso</th>
            <th>Flujo</th>
            <th>Perfil</th>
            <th></th>
        </tr>
    </tfoot>
</table>
