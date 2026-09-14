<?php require_once 'controllers/cfic.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Evaluación Sesión",1); ?> 

    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class = "form-group col-md-6">
                    <label for="idses">Sesión</label>
                    <input type="text" name="idses" id="idses" class="form-control" maxlength="20" required value="<?php if($datOne) echo $datOne[0]['idses']; ?>" <?php if($datOne) echo " readonly "; ?>>
                </div>
                <div class="form-group col-6">
                    <label for="idage">Fecha de sesión</label>
                    <input type="date" name="idage" id="idage" class="form-control form-control-sm" required value="<?php if($datOne) echo $datOne[0]['idage']; ?>">
                </div>
                <div class = "form-group col-md-6">
                    <label for="idses">Valoración</label>
                    <select name="idses" id="idses" class="form-select">
                            <option selected>Despliegue el menu y seleccione la opcion deseada</option>
                            <option value="1" <?php if ($datOne && $datOne[0]['idses'] == 1) echo " selected "; ?>>Se vio</option>
                            <option value="2" <?php if ($datOne && $datOne[0]['idses'] == 2) echo " selected "; ?>>No se vio</option>
                            <option value="3" <?php if ($datOne && $datOne[0]['idses'] == 3) echo " selected "; ?>>Se vio parcialmente</option>
                    </select>
                </div>
                <div class = "form-group col-md-6">
                    <input type="submit" class="btn btn-primary" value="<?php if($datOne) echo "Actualizar"; else echo "Registrar"; ?>">
                    <input type="hidden" name="opera" value="<?php if($datOne) echo "Actualizar"; else echo "Insertar"; ?>">
                    <br><br><br>
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100% ">
    <thead>
        <tr>
            <th>Sesión</th>
            <th>Fecha de la sesion</th>
            <th>Resultado</th>
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
                    <td>
                        <?=$d['idses'];?> - <?=$d['idses'];?><br>
                        <small>
                            <strong>Jornada: </strong><?=$d['idage'];?>
                            <?php if($d['fecses']){ ?>
                                <strong>Municipio: </strong><?=$d['fecses'];?>
                            <?php } ?>
                            <br>
                            <strong>Centro: </strong><?=$d['idses']." ".$d['idage']; ?>
                            <br>
                            <strong>Inicio: </strong><?=$d['fecses'];?>
                            -
                            <strong>Fin: </strong><?=$d['fecses'];?>
                        </small>
                    </td>
                    <td style="text-align: center;">
                        <?php
                            $dtnApr = $mfic->selNoApr($d['idses']);
                            $dtnApr = $dtnApr[0]['can'];
                        ?>
                            <span style="font-size: 30px;font-weight: bold;"><?=$dtnApr;?></span>
                    </td>
                    <td></td>
                    <td style="text-align: right;">
                    <?php if($_SESSION["idses"]==1){ ?>
                    <a href="home.php?pg=<?=$pg;?>&idses=<?=$d['idses'];?>&ope=edi" title="Actualizar">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                    <?php } ?>
                    <a href="home.php?pg=<?=$pg;?>&idses=<?=$d['idses'];?>&opera=Eliminar" title="Eliminar" onclick="return eliminar();">
                        <i class="fa-solid fa-trash-can fa-2x"></i></a>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sesión</th>
            <th>Fecha de la sesion</th>
            <th>Resultado</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<br><br><br><br>