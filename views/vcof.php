<?php require_once 'controllers/ccof.php';?>

<div class="conte">
    <div class="inser">
        <form name="frm1" action="home.php?pg=<?=$pg ?>" method="POST" enctype="multipart/form-data" id="frmins">
            <div class="row">
                
                <div class="form-group col-md-6" id="go1">
                    <label for="nomcof">Título</label>
                    <input type="text" name="titcof" id="titcof" class="form-control" value="<?=isset($val) ? $val[0]['titcof'] : ''; ?>">
                </div>
                <div class="form-group col-md-6" id="go1">
                    <label for="foocof">Pie de Página</label>
                    <input type="text" name="foocof" id="foocof" class="form-control" value="<?=isset($val) ? $val[0]['foocof'] : ''; ?>">
                </div>
                <div class="form-group col-md-6" id="go1">
                    <label for="arcimg">Logo</label>
                    <input name="arcimg" accept="image/png,image/jpeg,image/gif" class="form-control" type="file" >
                </div>
                  <div class="form-group col-md-1" id="go1" style="background:green; width:100px;">
                    <img src="<?= isset($val) ? $val[0]['logcof'] : ''; ?>" style="width:100%;">
                </div> 
<!---------------------------------------------------------------------Boton actualizar---------------------------------------------------------------------------------------->
                <div class ="form-group col-md-3">
                    <br>
                    <input type="submit" class="btn btn-primary" value="Guardar" />
                    <input type="hidden" id="idcof" name="idcof" value="<?= isset($val) ? $val[0]['idcof'] : ''; ?>">
                    <input type="hidden" name="ope" value="saveCF" />
                </div>
            </div>
        </form>
    </div>
</div>