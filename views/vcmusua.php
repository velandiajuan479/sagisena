<div class="conte">
    <?php echo titulo2("<i class= '". $icono . "'></i> Carga Masiva de Aspirantes", 2); ?>
        <div>
            <form action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="plantilla_aspirantes">Descripcion</label>
                        <input type="file" class="form-control" id="plantilla_aspirantes" name="plantilla_aspirantes" accept=".csv, .xlsx, .xls" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6" style="margin: auto;">
                        <input class="btn btn-primary" type="submit" value="Cargar" name="cargar">
                        <input type="hidden" name="opera" value="save">
                        <input type="hidden" name="idpag" id="idpag" value="">
                    </div>
                </div>
            </form>
        </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <a href="https://senacda.com/sagipr/EXCEL/fcapre.xlsx"
            class="btn btn-primary" target="_blank">Descargar Plantilla
        </a>
    </div>
    <div class="form-group col-md-12" style="margin: auto;">
        <img src="./imgasp.png">
        </a>
    </div>
</div>
