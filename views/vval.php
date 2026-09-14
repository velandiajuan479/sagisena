<?php require_once ('controllers/cval.php');?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Valor"); ?> 
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-grup col-md-6">
                    <label for="nomval">Nombre Valor</label>
                    <input type="text" name="nomval" id="nomval" class="form-control" required value="<?php if($datOne) echo $datOne[0]['nomval'];?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="iddom">Dominio</label>
                    <select name="iddom" id="iddom" class="form-select" value="<?php if($datOne) echo $datOne[0]['iddom'];?>">
                    <?php if ($datDom) {
                        foreach ($datDom as $ddo) { ?>
                            <option value="<?= $ddo['iddom']; ?>" <?php if ($datOne && $ddo['iddom'] == $datOne[0]['iddom']) echo "selected"; ?>>
                                <?= $ddo['nomdom']; ?>
                            </option>
                    <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="parval">Parametros</label>
                    <input type="text" name="parval" id="parval" class="form-control" value="<?php if($datOne) echo $datOne[0]['parval'];?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="act">Activo</label>
                    <select name="act" id="act" class="form-select" value="<?php if($datOne) echo $datOne[0]['iddom'];?>">
                        <option value="1" <?php if($datOne && $datOne[0]['act']==1) echo " selected "; ?>>Si</option>
                        <option value="2" <?php if($datOne && $datOne[0]['act']==2) echo " selected "; ?>>No</option>
                    </select> 
                </div>
                <div class="form-group col-md-6">
                    <br>
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idval" id="idval" value="<?php if($datOne) echo $datOne[0]['idval'];?>">
                </div>
            </div>
        </form>
    </div>
</div>
<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>id</th>
            <th>Valor</th>
            <th>Dominio</th>
            <th>Activo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($datAll){
                foreach($datAll AS $dta){ ?>
                    <tr>
                        <td><?=$dta['idval'];?></td>
                        <td><?=$dta['nomval'];?>
                            <?php if($dta['parval']){ ?>
                                <small><small><?=$dta['parval'];?></small></small>
                            <?php } ?></td>
                        <td>
                        <?php if($datDom){
                                    foreach ($datDom as $dtd){
                                        if ($dtd['iddom']==$dta['iddom']){
                                            echo $dtd['nomdom'];
                            }}}?>
                        </td>
                        <td>
                        <?php if($dta['act']==1){ ?>
                            <a href="home.php?pg=<?=$pg;?>&idval=<?=$dta['idval'];?>&opera=acti&act=2">
                                <i class="fa fa-solid fa-circle-check fa-2x"></i>
                            </a>
                        <?php }else{ ?>
                            <a href="home.php?pg=<?=$pg;?>&idval=<?=$dta['idval'];?>&opera=acti&act=1">
                                <i class="fa fa-solid fa-circle-xmark fa-2x" style="color: #ff0000;"></i>
                            </a>
                        <?php } ?>
                        </td>
                        <td style="text-align:right;">
                            <a href="home.php?pg=<?=$pg;?>&idval=<?=$dta['idval'];?>&opera=eli" title="Eliminar" onclick="return eli(this);">
                            <i class="fa-solid fa-trash-can fa-2x" ></i></a>
                            <a href="home.php?pg=<?=$pg;?>&idval=<?=$dta['idval'];?>&opera=edi" title="Editar">
                            <i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        </td>
                    </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
    <tr>
            <th>id</th>
            <th>Valor</th>
            <th>Dominio</th>
            <th>Activo</th>
            <th></th>
        </tr>
    </tfoot>
</table>
<br><br><br><br>
