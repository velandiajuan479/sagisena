<?php 
//require_once ('controllers/cage.php');

echo titulo2("<i class='".$icono."'></i> Carga Masiva");
?>

<!-- <div class="conte">
    <div class="inser"> -->
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-grup col-md-6">
                    <label for="nomdom">Archivo</label>
                    <input type="file" name="nomdom" id="nomdom" value="" class="form-control" required >
                </div>
                <div class="form-group col-md-6">
                    <br>
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="iddom" id="iddom" value="<?php if($datOne) echo $datOne[0]['iddom'];?>">
                </div>
            </div>
        </form>
    <!-- </div>
</div> -->
