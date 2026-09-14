<?php require_once 'controllers/ccon.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Configuración"); ?>

    <div class="Insertar">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class = "form-group col-md-6">
                    <label for="titcof">Nombre del software</label>
                    <input type="text" name="titcof" id="titcof" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['titcof']; ?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="descof">Titulo Empresa</label>
                    <input type="text" name="descof" id="descof" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['descof']; ?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="foocof">Pie de página</label>
                    <input type="text" name="foocof" id="foocof" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['foocof']; ?>">
                </div>
                <div class = "form-group col-md-6">
                    <label for="logcof" class="form-label">Imagen</label>
                    <input type="file" name="foto" id="logcof" class="form-control" accept="image/png,image/jpeg,image/gif" <?php if(!$datOne) echo "required";?>>
                </div>
                <div class = "form-group col-md-6">
                    <br>
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="ope" value="save">
                    <input type="hidden" name="idcof" id="idcof" value="<?php if($datOne) echo $datOne[0]['idcof'];?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Datos</th>
            <th></th>
        </tr>
    </thead>
        <tbody>
        <?php
            if($datOne){
                foreach($datOne AS $dta){ ?>
                <tr>
                    <td>
                        <?php if($dta['logcof']){ ?>
                            <img src="<?=$dta['logcof'];?>" width="100px">
                        <?php } ?>
                    </td>
                    <td width="60%">
                        <strong><?=$dta['idcof'];?> - <?=$dta['titcof'];?></strong>
                        <br>
                        <small>
                            <strong>Nombre empresa:</strong> <?=$dta['descof'];?><br>
                            <strong>Pie de página:</strong> <?=$dta['foocof'];?>
                        </small>
                    </td>
                    <td>
                        <a href="home.php?pg=<?=$pg;?>&idcof=<?=$dta['idcof'];?>&ope=edi" title="Editar">
                                <i class="fa-solid fa-pen-to-square fa-2x"></i>
                        </a>
                    </td>
                </tr>
        <?php
                }
            }
        ?>
        </tbody>
        <tfoot>
            <th>Imagen</th>
            <th>Datos</th>
            <th></th>
        </tfoot>
    </table>
    <br><br><br><br><br>