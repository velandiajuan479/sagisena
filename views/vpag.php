<?php require_once ('controllers/cpag.php');?>
<div class="conte"> 
    <?php echo titulo2("<i class='".$icono."'></i> Página",1); ?> 
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-group col-md-6">
                        <label for="nompag">Nombre</label>
                        <input type="text" name="nompag" id="nompag" maxlength="70" class="form-control" value="<?php if($datOne) echo $datOne[0]['nompag']; ?>" required>
                </div>
                <div class="form-group col-md-6">
                        <label for="rutpag">Ruta</label>
                        <input type="text" name="rutpag" id="rutpag" class="form-control" required value="<?php if($datOne) echo $datOne[0]['rutpag']; ?>">
                </div>
                <div class="form-group col-md-6">
                        <label for="mospas">Mostrar</label>
                        <select name="mospas" id="mospas" class="form-control">
                            <option value="1" <?php if ($datOne && $datOne[0]['mospas'] == 1) echo " selected "; ?>>Si</option>
                            <option value="2" <?php if ($datOne && $datOne[0]['mospas'] == 2) echo " selected "; ?>>No</option>
                        </select>
                </div>
                <div class="form-group col-md-6">
                        <label for="ordpag">Ordenar</label>
                        <input type="number" name="ordpag" id="ordpag" class="form-control" required value="<?php if($datOne) echo $datOne[0]['ordpag']; ?>">
                </div>
                <div class="form-group col-md-6">
                        <label for="icopag">Icono</label>
                        <input type="text" name="icopag" id="icopag" class="form-control" value="<?php if($datOne) echo $datOne[0]['icopag']; ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="idmod">Módulo</label>
                    <select name="idmod" id="idmod" class="form-control">
                    <?php if ($datMod) {
                        foreach ($datMod as $dt) { ?>
                            <option value="<?= $dt['idmod']; ?>" <?php if ($datOne && $datOne[0]['idmod'] == $dt['idmod']) echo " selected "; ?>>
                                <?= $dt['nommod']; ?>
                            </option>
                    <?php }
                    } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                        <label for="despag">Descripción</label>
                        <textarea name="despag" id="despag" rows="3" class="form-control"><?php if($datOne) echo $datOne[0]['despag'];?></textarea>
                </div>
                <div class="form-group col-md-6" style="margin:auto;">
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idpag" id="idpag" value="<?php if($datOne) echo $datOne[0]['idpag'];?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>  
            <th>Icono</th>
            <th>Página</th>
            <th>Mostrar</th>
            
            <th style="text-align: right;">
                <a href="views/pdfpag.php" title="PDF" target="_blank">
                    <i class="fa-solid fa-file-pdf fa-2x" style="color: #117f09;"></i>
                </a>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if ($datAll) { foreach ($datAll AS $dta) { ?>       
            <tr>
                <td><i class="<?=$dta['icopag'];?> fa-2x"></i></td>
                <td>
                    <strong><?=$dta['idpag'];?> - <?=$dta['nompag'];?></strong><br>
                    <small>
                        <strong>Ruta: </strong><?=$dta['rutpag'];?><br><strong>Nombre del icono: </strong>
                        <?=$dta['icopag'];?>&nbsp;<br>
                        <strong>Módulo: </strong>
                        <?=$dta['nommod'];?>
                        <?php if ($dta['despag']){?>
                            <br><strong>Descripción: </strong>
                            <?=$dta['despag'];?>
                        <?php }?>
                    </small>
                </td>
                <td>
                <?php if($dta['mospas']==1){ ?>
                        <a href="home.php?pg=<?=$pg;?>&idpag=<?=$dta['idpag'];?>&opera=acti&mospas=2">
                            <i class="fa fa-solid fa-circle-check fa-2x"></i>
                        </a>
                    <?php }else{ ?>
                        <a href="home.php?pg=<?=$pg;?>&idpag=<?=$dta['idpag'];?>&opera=acti&mospas=1">
                        <i class="fa fa-solid fa-circle-xmark fa-2x" style="color: #ff0000;"></i>
                        </a>
                    <?php } ?>
                </td>
                
                <td style="text-align:right;">
                <a href="home.php?pg=<?=$pg;?>&idpag=<?=$dta['idpag'];?>&opera=edi" title="Editar">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                </a>
                <a href="home.php?pg=<?=$pg;?>&idpag=<?=$dta['idpag'];?>&opera=eli" title="Eliminar" onclick="return eli(this);">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                </a>
                </td>
            </tr>
        <?php }} ?>      
    </tbody>
    <tfoot>
        <tr>
            <th>Icono</th>
            <th>Página</th>
            <th>Mostrar</th>
            
            <th></th>
        </tr>
    </tfoot>
</table>