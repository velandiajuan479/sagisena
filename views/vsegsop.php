<?php require_once 'controllers/cdtsop.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Seguimiento Soporte", 0); ?>
    <?php if ($datOne) {
        foreach ($datOne as $dtO) { ?>
                <div>
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <th colspan='8' style="font-size:30px;font-weight: bold; text-align: center;">
                                N° Soporte <?= $dtO['idsop']; ?></th>
                        </tr>
                        <tr>
                            <th>Nombre Persona</th>
                            <td><?= $dtO['nomper']; ?></td>
                            <th>Cargo Persona</th>
                            <td><?= $dtO['nom_carper']; ?></td>
                            <th>Falla Reportada</th>
                            <td><?= $dtO['nom_falrep']; ?></td>
                        </tr>
                        <tr>
                            <th>Fecha de Inicio Servicio</th>
                            <td><?= $dtO['fecserini']; ?></td>
                            <th>Fecha de Fin Servicio</th>
                            <td><?= $dtO['fecserfin']; ?></td>
                            <th>Descripcion</th>
                            <td><?= $dtO['desser']; ?></td>
                        </tr>
                    </table>
                </div>
        <?php }
    } ?>
    <br>
    <?php if(!$estadoFinalizado && isset($_SESSION['idper']) && ($_SESSION['idper'] == 27 || $_SESSION['idper'] == 38)) { ?>
                <div class="inser">
                    <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="idusu">Nombre de quien atiende</label>
                                <select name="idusu" id="idusu" class="form-control form-select">
                                    <?php if ($dtSelsop) {
                                        foreach ($dtSelsop as $dtsp) { ?>
                                                <option value="<?= $dtsp['idusu']; ?>" <?php if ($dtOne && $dtOne[0]['nomusu'] == $dtsp['idusu'])
                                                    echo "selected"; ?>><?= $dtsp['nomusu']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="detest">Estado</label>
                                <select name="detest" id="detest" class="form-control form-select">
                                    <?php if ($dtEst) {
                                        foreach ($dtEst as $dtE) { ?>
                                                <option value="<?= $dtE['idval']; ?>" <?php if ($dtOne && $dtOne[0]['detest'] == $dtE['idval'])
                                                    echo "selected"; ?>><?= $dtE['nomval']; ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="detcom">Comentario</label>
                                <textarea name="detcom" id="detcom" class="form-control"><?php if ($dtOne && $dtOne[0]['detcom'])
                                    echo $dtOne[0]['detcom']; ?></textarea>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="detevi">Foto Evidencia</label>
                                <?php if ($dtOne and $dtOne[0]['detevi']) { ?>
                                        <input type="hidden" name="detevi" id="detevi" value="<?= $dtOne[0]['detevi']; ?>">
                                <?php } ?>
                                <input type="file" name="foto" id="detevi" class="form-control" accept="image/png, image/jpeg">
                            </div>
                            <div class="form-group col-md-6">
                                <br>
                                <input class="btn btn-success" type="submit" value="Enviar">
                                <input type="hidden" name="ope" value="save">
                                <input type="hidden" name="idsop" value="<?= $idsop; ?>">
                                <input type="hidden" name="iddet" id="iddet" value="<?php if ($dtOne)
                                    echo $dtOne[0]['iddet']; ?>">
                            </div>
                        </div>
                    </form>
                </div>
    <?php } elseif ($estadoFinalizado) { ?>
            <div class="alert alert-info" style="margin-top:20px;">
                <strong>¡Atención!</strong> Este soporte ha finalizado y ya no se pueden agregar más registros.
            </div>
    <?php } ?>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Progreso Soporte</th>
                <th>Evidencia Servicio</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dat) {
                foreach ($dat as $dt) { ?>
                        <tr>
                            <td>
                                <strong>
                                    <?= $dt["idsop"]; ?> - <?= $dt["nomusu"]; ?>
                                </strong>
                                <br>
                                <?= $dt["nom_falrep"]; ?> - <?= $dt["fecseg"]; ?><br>
                                <?= $dt["nom_carper"]; ?> - <?= $dt["nomper"]; ?> - <?= $dt["nom_detest"]; ?><br> 
                                <?= $dt["detcom"]; ?>
                            </td>
                            <td class="img-cell">
                                <?php if (file_exists($dt['detevi'])) { ?>
                                    <img src="<?= $dt['detevi']; ?>" alt="Evidencia" class="zoom-img" width="100%"; />
                                <?php } ?>
                            </td>
                            <td>
                                <?php if(isset($_SESSION['idper']) && ($_SESSION['idper'] == 38)) { ?>
                                    <a href="home.php?pg=<?=$pg;?>&idsop=<?=$idsop;?>&iddet=<?=$dt['iddet'];?>&ope=del" title="Eliminar">
                                        <i class="fa-solid fa-trash-can fa-2x"></i>
                                    </a>
                                <?php } ?>
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
</div>
