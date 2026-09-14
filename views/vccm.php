<?php require_once("controllers/cccm.php"); ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Cursos Complementarios",1); ?>
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="codpro">Código</label>
                    <input type="text" name="codpro" id="codpro" class="form-control" 
                    value="<?php if($datOne) echo $datOne[0]['codpro']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="nompro">Nombre</label>
                    <input type="text" name="nompro" id="nompro" class="form-control" 
                    value="<?php if($datOne) echo $datOne[0]['nompro']; ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="verpro">Versión</label>
                    <input type="text" name="verpro" id="verpro" class="form-control" 
                    value="<?php if($datOne) echo $datOne[0]['verpro']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="despro">Descripción</label>
                    <input type="text" name="despro" id="despro" class="form-control" 
                    value="<?php if($datOne) echo $datOne[0]['despro']; ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="horlpro">Horas</label>
                    <input type="number" name="horlpro" id="horlpro" class="form-control" 
                    value="<?php if($datOne) echo $datOne[0]['horlpro']; ?>" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="tippro">Tipo</label>
                    <select name="tippro" id="tippro" class="form-control" required>
                    <option value="1083" <?php if($datOne && $datOne[0]['tippro']=="1083") echo "selected"; ?>>Complementario</option>
                    </select>
                </div>
                <div class="form-group col-md-6" style="margin:auto;">
                    <br>
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="opera" value="save">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Curso Complementario</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php require_once("controllers/cccm.php"); ?>
        <?php if($datAll){ foreach($datAll as $dta){ ?> 
            <tr>
                <td>
                    <big><strong><?= ($dta['codpro']); ?> - <?= ($dta['nompro']); ?></strong></big><br>
                    Versión: <?= ($dta['verpro']); ?><br>
                    Duración: <?= ($dta['horlpro']); ?> horas<br>
                    Tipo: <?= ($dta['tippro']); ?><br>
                    Descripción: <?= ($dta['despro']); ?><br>
                </td>
                <td>
                    <a href="home.php?pg=<?=$pg;?>&codpro=<?= ($dta['codpro']); ?>&opera=edi" title="Editar">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </a>
                    <a href="home.php?pg=<?=$pg;?>&codpro=<?=($dta['codpro']); ?>&opera=eli" title="Eliminar" onclick="return eli(this);">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                    </a>
                </td>
            </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Curso Complementario</th>
            <th></th>
        </tr>
    </tfoot>
</table>