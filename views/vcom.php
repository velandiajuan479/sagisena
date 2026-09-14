<?php require_once 'controllers/ccom.php'; ?>

<div class="conte">
    <!-- Datos de Programa -->
    <?php if($dtPrg){ foreach ($dtPrg as $dtP){ ?>
        <div style="margin-top: 100px;margin-bottom: 120px;">

            <table class="table table-striped" style="width:100%">
                <tr>
                    <th colspan='8' style="font-size:30px;font-weight: bold; text-align: center;"><?=$dtP['codpro'];?> - <?=$dtP['nompro'];?> Versión: <?=$dtP['verpro'];?></th>
                </tr>
                <tr>
                    <th>Horas Lectiva</th>
                    <td><?=$dtP['horlpro'];?></td>
                    <th>Créditos Lectiva</th>
                    <td><?=$dtP['crelpro'];?></td>
                    <th>Horas Productiva</th>
                    <td><?=$dtP['horppro'];?></td>
                    <th>Créditos Productiva</th>
                    <td><?=$dtP['creppro'];?></td>
                </tr>
                <tr>
                    <th>Tipo Formación</th>
                    <td><?=$dtP['nomval'];?></td>
                    <th>Red de Conocimiento</th>
                    <td colspan="3"><?=$dtP['redcon'];?></td>
                    <th>Área</th>
                    <td><?=$dtP['nomare'];?></td>
                </tr>
            </table>
        </div>
    <?php }} ?>


    <?php echo titulo2("<i class='".$icono."'></i> Competencia", 1); ?>

    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="idcom">Código</label>
                    <input type="number" name="idcom" id="idcom" class="form-control" required value="<?php if($datOne && $datOne[0]['idcom']) echo $datOne[0]['idcom']; ?>" <?php if($datOne && $datOne[0]['idcom']) echo "readonly"; ?>>
                </div>
                <div class="form-group col-md-9">
                    <label for="descom">Nombre</label>
                    <input type="text" name="descom" id="descom" class="form-control" required value="<?php if($datOne && $datOne[0]['descom']) echo $datOne[0]['descom']; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="vercom">Versión</label>
                    <input type="number" name="vercom" id="vercom" class="form-control" min="1" max="99" required value="<?php if($datOne && $datOne[0]['vercom']) echo $datOne[0]['vercom']; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="horcom">Horas</label>
                    <input type="number" name="horcom" id="horcom" class="form-control" min="0" max="9999" required value="<?php if($datOne && $datOne[0]['horcom']) echo $datOne[0]['horcom']; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="idval">Tipo de competencia</label>
                    <select name="idval" id="idval" class="form-select">
                        <?php if ($datVal) { foreach ($datVal as $ddo) { ?>
                            <option value="<?= $ddo['idval']; ?>" <?= isset($datOne) && isset($datOne[0]['idval']) && $ddo['idval'] == $datOne[0]['idval'] ? "selected" : ""; ?>><?= $ddo['nomval']; ?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <br>
                    <input type="hidden" name="ope" value="save">
                    <?php if($datOne && $datOne[0]['idcom']){ 
                        echo '<input type="hidden" name="idcom" value="'.$datOne[0]['idcom'].'">';
                        echo '<input type="hidden" name="ope2" value="edit">'; 
                    } ?>
                    <input type="hidden" name="codpro" value="<?=$codpro;?>">
                    <input type="submit" class="btn btn-primary" value="Enviar">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Competencia</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($datAll) {
            foreach ($datAll as $dt) { ?>
                <tr>
                    <td>
                        <?php
                            $mcom->setIdcom($dt['idcom']);
                            $dtRs = $mcom->getAllRs();
                            $can = count($dtRs);
                        ?>
                        <big><?=$dt['idcom'];?> - <?=$dt['descom'];?></big>
                        <br>
                        <small>
                            <strong>Versión:</strong> <?=$dt['vercom'];?>
                            <strong>Horas:</strong> <?=$dt['horcom'];?>
                            <strong>Tipo:</strong> <?=$dt['nomval'];?>
                            <strong>No. Resultados:</strong> <?=$can;?>
                        </small>

                        <?php if($dtRs){ ?>
                            <br><br>
                        <table class="table" style="width:100%">
                            <tr>
                                <th>Resultados</th>
                                <th>No. Sesiones</th>
                                <th></th>
                            </tr>
                            <?php foreach ($dtRs AS $dtR){ ?>
                                <tr>
                                    <td><?=$dtR['idres'];?> <?=$dtR['nomres'];?></td>
                                    <td><?=$dtR['ndeses'];?></td>
                                    <td style="text-align: right;"><a href="home.php?pg=<?=$pg;?>&idres=<?=$dtR['idres'];?>&codpro=<?=$codpro;?>&ope=delRS" title="Eliminar" onclick="return eliminar();">
                                    <i class="fa-solid fa-trash-can fa-2x"></i>
                            </a></td>
                                </tr>
                            <?php } ?>
                        </table>
                        <?php } ?>
                    </td>
                    <td style="text-align:right;">
                        <a href="home.php?pg=<?=$pg;?>&idcom=<?=$dt['idcom'];?>&codpro=<?=$codpro;?>&ope=edi" title="Editar">
                                <i class="fa-solid fa-pen-to-square fa-2x"></i>
                        </a>
                        <?php if($can==0){ ?>
                            <a href="home.php?pg=<?=$pg;?>&idcom=<?=$dt['idcom'];?>&codpro=<?=$codpro;?>&ope=del" title="Eliminar" onclick="return eliminar();">
                                    <i class="fa-solid fa-trash-can fa-2x"></i>
                            </a>
                        <?php } ?>

                        <a href="#"  title="Adicionar resultado" data-bs-toggle="modal" data-bs-target="#myModal<?=$dt['idcom'];?>">
                                <i class="fa-solid fa-plus fa-2x"></i>
                        </a>
                        <?php modal($dt['idcom'], $dt['descom'], $pg, $codpro); ?>
                    </td>
                </tr>
            <?php } 
        } else {
            echo "<tr><td colspan='3'>No se encontraron competencias.</td></tr>";
        } ?>
    </tbody>
    <thead>
        <tr>
            <th>Competencia</th>
            <th></th>
        </tr>
    </thead>
</table>

