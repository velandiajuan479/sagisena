<?php
require_once 'controllers/csolsop.php';
?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Enviar Solicitud Soporte", 1); ?>
        <div class="inser container-form-pages">
            <form class="form-default-pages" id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-md-4">
                        <div class="upload-container">
                            <label for="evisop" class="upload-label fw-semibold">Foto Evidencia</label>
                            <div class="upload-wrapper">
                                <div class="upload-dropzone">
                                    <?php if ($dtOne and $dtOne[0]['evisop']) { ?>
                                        <input type="hidden" name="evisop" id="evisop" value="<?= $dtOne[0]['evisop']; ?>">
                                    <?php } ?>
                                    <input type="file" class="upload-input" name="foto" id="evisop"
                                        accept="image/png, image/jpeg" />
                                    <label for="fileInput" class="upload-preview">
                                        <i class="fa-solid fa-cloud-arrow-up upload-info-icon"></i>
                                    </label>
                                    <p class="upload-hint" id="fileName">Arrastre y suelte o haga clic para cargar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row gap-4 justify-content-between">
                            <div class="form-group col-md-3">
                                <label for="nomper">Nombres de quien solicita</label>
                                <input list="usuarios" name="nomper" id="nomper" maxlength="70" class="form-control"
                                    value="<?php if ($dtOne)
                                        echo $dtOne[0]['nomper']; ?>" placeholder="Escriba o seleccione un nombre">
                                <datalist id="usuarios">
                                    <?php
                                    if ($dtNP) {
                                        foreach ($dtNP as $dt) {
                                            echo "<option value=\"{$dt['nomusu']}\"></option>";
                                        }
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="falrep">Falla Reportada</label>
                                <select name="falrep" id="falrep" class="form-control form-select">
                                    <?php if ($dtFalla) {
                                        foreach ($dtFalla as $dtFll) { ?>
                                                <option value="<?= $dtFll['idval']; ?>" <?php if ($dtOne && $dtOne[0]['falrep'] == $dtFll['idval'])
                                                    echo "selected"; ?>><?= $dtFll['nomval']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="carper">Cargo</label>
                                <select name="carper" id="carper" class="form-control form-select">
                                    <?php if ($dtCargo) {
                                        foreach ($dtCargo as $dtCr) { ?>
                                                <option value="<?= $dtCr['idval']; ?>" <?php if ($dtOne && $dtOne[0]['carper'] == $dtCr['idval'])
                                                    echo "selected"; ?>><?= $dtCr['nomval']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="desser">Descripción del Problema Presentado</label>
                                <textarea name="desser" id="desser" class="form-control"><?php if ($dtOne && $dtOne[0]['desser'])
                                    echo $dtOne[0]['desser']; ?></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <br>
                                <input class="btn btn-success" type="submit" value="Enviar">
                                <input type="hidden" name="ope" value="save">
                                <input type="hidden" name="idsop" id="idsop" value="<?php if ($dtOne)
                                    echo $dtOne[0]['idsop']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Servicio Soporte</th>
            <th>Evidencia servicio</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($dat) {
            foreach ($dat as $dt) { ?>
                    <tr>
                        <td>
                            <strong>
                                <?= $dt["nomper"]; ?> - <?= $dt["nom_carper"]; ?><br>
                            </strong>
                                Falla Reportada: <?= $dt["nom_falrep"]; ?><br>
                                Fecha de inicio: <?= $dt["fecserini"]; ?><br>
                                Fecha de fin: <?= $dt['fecserfin']; ?><br>
                                Descripcion: <?= $dt["desser"]; ?><br>
                            <strong>
                                <?= $dt["nom_ultimo_estado"]; ?>
                            </strong>
                        </td>
                        <td class="img-cell">
                            <?php if (file_exists($dt['evisop'])) { ?>
                                    <img src="<?= $dt['evisop']; ?>" alt="Evidencia" class="zoom-img" width="100%">
                            <?php } ?>
                        </td>
                        <td>
                            <a href="home.php?pg=2204&idsop=<?= $dt["idsop"]; ?>" title="Seguimiento">
                                <i class="fa-solid fa-magnifying-glass fa-2x"></i>
                            </a>
                            <a href="home.php?pg=<?=$pg;?>&idsop=<?=$dt['idsop'];?>&ope=del" title="Eliminar">
                                <i class="fa-solid fa-trash-can fa-2x"></i>
                            </a>
                        </td>
                    </tr>
            <?php }
        } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Servicio Soporte</th>
            <th>Evidencia Soporte</th>
            <th></th>
        </tr>
    </tfoot>
</table>