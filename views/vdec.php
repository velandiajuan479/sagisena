<?php  require_once ('controllers/cdec.php'); ?>

<?php echo titulo2("<i class='" . $icono . "'></i> Generar Desercion"); ?>
<div class="row">
    <div class="row">
        <div class="form-group col-lg-6 info">
    <?php if ($datAllPer) { foreach($datAllPer AS $dus){ ?>
        <div class="form-group col-lg-6 info">
                <?php if($dus && file_exists($dus['fotcan'])){ ?>
				<img src="<?=$dus['fotcan'];?>" style="width: 140px;">
			<?php }else{ ?>
				<img src="img/user.jpg" style="width: 140px;">
			<?php } ?>
        </div>
        </div>
        <div class="form-group col-lg-6 info">
            <div class="form-group col-lg-6 info">
                <?php if ($dus['nomusu']){ ?>
                <BIG><strong for="persona">Nombres y Apellidos: </strong></BIG><br>  <?= $dus['nomusu']; ?>  
                <?php } ?>
            </div>
            <div class="form-group col-lg-6 info">
                <?php if ($dus['ndocusu']){ ?>
                <BIG><strong for="persona">Documento: </strong></BIG><br> <?= $dus['ndocusu']; ?>   
                <?php } ?>
            </div>
            <div class="form-group col-lg-6 info">
                <?php if ($dus['telcan']){ ?>
                <BIG><strong for="persona">Teléfono: </strong></BIG><br> <?= $dus['telcan']; ?>   
                <?php } ?>
            </div>
            <div class="form-group col-lg-6 info">
                <?php if ($dus['emausu']){ ?>
                <BIG><strong for="persona">E-Mail: </strong></BIG><br> <?= $dus['emausu']; ?>  
                <?php } ?>
            </div>
            </div>
            <div class="form-group col-md-12">
                    <label for="rutfot">Cargar Evidencias:</label>
                    <input class="form-control" type="file" id="rutfot" name="rutfot" accept="image/png,image/jpeg,image/gif" <?php if(!$datOne) echo "required";?>>
                </div>
            <div class="form-group col-lg-12 col-md-12 info">
                <label for="obsdec">Observaciones:</label><br>
                <textarea name="obsdec" id="obsdec" class="form-control" style="height: 191px;"></textarea>
            </div>
            <div class="form-group col-md-6">
                    <input class="btn btn-primary" type="submit" value="Cargar otra evidencia">
                    <input type="hidden" name="opera" value="">
                    <input type="hidden" name="idele" id="idele" value="<?php if($datOne) echo $datOne[0]['idele'];?>">
                </div>
            <div class="form-group col-md-6">
                    <input class="btn btn-primary" type="submit" value="Guardar" style="float: right;">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idele" id="idele" value="<?php if($datOne) echo $datOne[0]['idele'];?>">
                </div>
            
    <?php }}else{ ?>
        <div class="form-group col-lg- info">
            <BIG><p>No se ha seleccionado usuario.</p></BIG>
        </div>
    <?php } ?>
</div>