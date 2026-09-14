<?php require_once 'controllers/cdpc.php'; ?>

<!-- Actualizar Candidato Inicio ------------------------------- -->
<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> CANDIDATO ", 2); ?>
    <?php if ($idusu) { ?>

        <form method="POST" enctype="multipart/form-data">
            <div style="float: left;width:17%;">

                <?php if (!$datOne[0]['fotcan']) { ?>
                    <img src="image/usuario.png" style="width: 90%;">
                <?php } else { ?>
                    <img src="<?= $datOne[0]['fotcan'] ?>" style="width: 90%;" style="width: 90%;"></a>
                <?php } ?>
                <input type="file" name="arch" class="form-control" style="width: 90%;" accept="image/*">
            </div>
            <div class="row" style="float: left;width: 83%;">
                <div class="form-group col-md-12">
                    <ul class="list-group col-md-12" style="float: right; width: 100%">
                        <li class="list-group-item" style="text-align: right;">
                            <h3 style="font-size: 40px;">
                                <strong><?= $datOne[0]['noca']; ?></strong>
                            </h3>
                        </li>
                    </ul>
                </div>
                <div class="form-group col-md-6">
                    <label for="ndocusu">No. Documento</label>
                    <input type="number" name="ndocusu" id="ndocusu" class="form-control" disabled="disabled" required
                        value="<?php if ($datOne)
                            echo $datOne[0]['ndocusu']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="nomusu">Nombre</label>
                    <input type="text" name="nomusu" id="nomusu" maxlength="70" class="form-control" disabled="disabled"
                        required value="<?php if ($datOne)
                            echo $datOne[0]['nomusu']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="idfic">Ficha</label>
                    <input type="text" name="idfic" id="idfic" maxlength="70" class="form-control" disabled="disabled"
                        required value="<?php if ($datOne)
                            echo $datOne[0]['idfic'] . " " . $datOne[0]['nomfic']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="idcen">Centro de Formación</label>
                    <input type="text" name="idcen" id="idcen" maxlength="70" class="form-control" disabled="disabled"
                        required value="<?php if ($datOne)
                            echo $datOne[0]['idcen'] . " " . $datOne[0]['nomcen']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="telcan">No. Telefono</label>
                    <input type="number" name="telcan" id="telcan" class="form-control"
                        value="<?php if ($datOne)
                            echo $datOne[0]['telcan']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="telcan">Email</label>
                    <input type="text" name="Email" id="Email" class="form-control"
                        value="<?php if ($datOne)
                            echo $datOne[0]['emausu']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="noca">No. Candidato</label>
                    <input type="text" name="noca" id="noca" class="form-control" required
                        value="<?php if ($datOne)
                            echo $datOne[0]['noca']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <br>
                    <input type="submit" class="btn btn-success" value="Actualizar">
                    <input type="hidden" name="idusu" value="<?php if ($datOne)
                        echo $datOne[0]['idusu']; ?>">
                    <input type="hidden" name="opera" value="<?php if ($datOne)
                        echo "Actualizar";
                    else
                        echo "Insertar"; ?>">
                </div>

            </div>
        </form>

    <?php } ?>

</div>

<!-- Actualizar candidato Fin ------------------------------- -->

<!-- Filtro Inicio ------------------------------------------ -->
<div class="conte container-form-pages">
    <form class="form-default-pages" action="#'" method="POST">
        <div class="row">
            <div class="form-group col-md-4">
                <label for="idfic" class="fw-semibold">Jornada</label>
                <select name="fidjor" id="idfic" class="form-select  form--input-default" onchange="this.form.submit();">
                    <?php
                    if ($djor) {
                        foreach ($djor as $dj) {
                            ?>
                            <option value="<?= $dj['idval']; ?>" <?php if ($fidjor == $dj['idval'])
                                echo " selected "; ?>>
                                <?= $dj['nomval']; ?></option>
                        <?php }
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-8">
                <label for="idcen" class="fw-semibold">Centro de Formación</label>
                <select name="fidcen" id="idcen" class="form-select  form--input-default" onchange="this.form.submit();">
                    <?php
                    if ($dcen) {
                        foreach ($dcen as $dj) {
                            ?>
                            <option value="<?= $dj['idcen']; ?>" <?php if ($fidcen == $dj['idcen'])
                                echo " selected "; ?>>
                                <?= $dj['nomcen']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Filtro Fin --------------------------------------------- -->
<br><br>


<!-- Mostrar Candidato Inicio ------------------------------- -->
<div>
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Candidato</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($dat) {
                foreach ($dat as $d) {
                    ?>
                    <tr>
                        <td width="100px">
                            <?php //if(file_exists("fcan/".$d['ndocusu'].".jpg")){
                                    if (file_exists($d['fotcan'])) {
                                        ?>
                                <img src="<?= $d['fotcan']; ?>" style="width: 100%;">
                            <?php } else { ?>
                                <img src="image/usuario.png" style="width: 100%;">
                            <?php } ?>
                        </td>
                        <td>
                            <?= $d['nomusu']; ?><br>
                            <small>
                                <strong>No. Documento: </strong> <?= $d['ndocusu']; ?><br>
                                <?php if ($d['idfic']) { ?>
                                    <strong>Ficha: </strong> <?= $d['idfic']; ?>             <?= $d['nomfic']; ?>             <?= $d['nomval']; ?> <br>
                                <?php } ?>
                                <?= $d['nomcen']; ?><br>
                                <strong>E-mail: </strong> <?= $d['emausu']; ?><br>
                                <strong>No. Candidato: </strong> <?= $d['noca']; ?>
                            </small>
                        </td>
                        <td style="text-align:center">
                            <a href="home.php?pg=1207&idusu=<?= $d['idusu']; ?>" title="Editar Propuesta" target="_blank">
                                <i class="fa-solid fa-pen-to-square fa-2x"></i>
                            </a>
                        </td>
                        <td style="text-align:center">
                            <a href="home.php?pg=1204&idusu=<?= $d['idusu']; ?>" title="Ver Propuesta" target="_blank">
                                <i class="fa-solid fa-file-lines fa-2x"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Foto</th>
                <th>Candidato</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<div style="float: left;width: 100%;padding: 0px 10px 100px 10px;">
    <div class="inser">
        <form name="frm5" action="home.php?pg=<?= $pg; ?>" method="POST">
            <div class="row">
                <div class="form-group col-md-6">
                    <br>
                    <input type="submit" class="btn btn-success" onclick="return eliminar();"
                        value="Limpiar votaciones">
                    <input type="hidden" name="opera" value="Limpiar">
                </div>
            </div>
        </form>
    </div>
</div>
<script> ocul(0, 0);</script>
<!-- Mostrar candidato Fin -------------------------------------- -->