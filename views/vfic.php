<?php require_once 'controllers/cfic.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Ficha",1); ?> 

    <div class="inser container-form-pages">
        <form class="form-default-pages" id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class = "form-group col-md-6">
                    <label for="idfic" class="fw-semibold">Ficha</label>
                    <input type="text" name="idfic" id="idfic" class="form-control form--input-default" maxlength="20" required value="<?php if($datOne) echo $datOne[0]['idfic']; ?>" <?php if($datOne) echo " readonly "; ?>>
                </div>
                <div class = "form-group col-md-6">
                    <label for="nomfic" class="fw-semibold">Nombre de la ficha</label>
                    <input type="text" name="nomfic" id="nomfic" maxlength="70" class="form-control form--input-default" required value="<?php if($datOne) echo $datOne[0]['nomfic']; ?>">
                </div>
                <div class = "form-group col-md-4">
                    <label for="jornada" class="fw-semibold">Jornada</label>
                    <select name="jornada" id="jornada" class="form-select form--input-default">
                        <?php
                            if($djo){
                                foreach($djo AS $dj){
                        ?>
                                    <option value="<?=$dj['idval'];?>" 
                                    <?php if($datOne AND $datOne[0]['jornada']==$dj['idval']) echo " selected "; ?>
                                    >
                                        <?=$dj['nomval'];?>
                                    </option>
                        <?php 
                                }
                            } 
                        ?>
                    </select>
                </div>
                <div class = "form-group col-md-4">
                    <label for="mun" class="fw-semibold">Departamento</label>
                    <select class="form-control form-select form--input-default" onchange="recCiudad(this.value);">
                    <?php if($dubi){ foreach($dubi AS $ub){ ?>
                        <option value="<?=$ub['codubi'];?>"><?=$ub['nomubi'];?></option>
                    <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="mun" class="fw-semibold">Municipio</label>
                    <div id="reloadMun">
                        <input type="text" name="mun" id="mun" maxlength="255" class="form-control form--input-default" value="<?php if($datOne) echo $datOne[0]['mun']; ?>">
                    </div>
                </div>
                <div class = "form-group col-md-6">
                    <label for="idcen" class="fw-semibold">Centro</label>
                    <select name="idcen" id="idcen" class="form-select form--input-default">
                        
                        <?php
                            if($dce){
                                foreach($dce AS $dj){
                        ?>
                                    <option value="<?=$dj['idcen'];?>" 
                                    <?php if($datOne AND $datOne[0]['idcen']==$dj['idcen']) echo " selected "; ?>
                                    >
                                        <?=$dj['nomcen'];?>
                                    </option>
                        <?php 
                                }
                            } 
                        ?>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label for="finific" class="fw-semibold">Inicio de ficha</label>
                    <input type="date" name="finific" id="finific" class="form-control form-control-sm form--input-default" required value="<?php if($datOne) echo $datOne[0]['finific']; ?>">
                </div>
                <div class="form-group col-6">
                    <label for="ffinfic" class="fw-semibold">Fin de ficha</label>
                    <input type="date" name="ffinfic" id="ffinfic" class="form-control form-control-sm form--input-default" required value="<?php if($datOne) echo $datOne[0]['ffinfic']; ?>">
                </div>
                <div class = "form-group col-md-6 d-flex align-items-center">
                    <input type="submit" class="btn btn-success" value="<?php if($datOne) echo "Actualizar"; else echo "Registrar"; ?>">
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
            <th>Ficha</th>
            <th>No. Apr.</th>
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
                        <?=$d['idfic'];?> - <?=$d['nomfic'];?><br>
                        <small>
                            <strong>Jornada: </strong><?=$d['nomval'];?>
                            <?php if($d['mun']){ ?>
                                <strong>Municipio: </strong><?=$d['mun'];?>
                            <?php } ?>
                            <br>
                            <strong>Centro: </strong><?=$d['idcen']." ".$d['nomcen']; ?>
                            <br>
                            <strong>Inicio: </strong><?=$d['finific'];?>
                            -
                            <strong>Fin: </strong><?=$d['ffinfic'];?>
                        </small>
                    </td>
                    <td style="text-align: center;">
                        <?php
                            $dtnApr = $mfic->selNoApr($d['idfic']);
                            $dtnApr = $dtnApr[0]['can'];
                        ?>
                            <span style="font-size: 30px;font-weight: bold;"><?=$dtnApr;?></span>
                    </td>
                    <td style="text-align: right;">
                       <?php
                            
                            $actas = $mfic->getAllActasFic($d['idfic']);
                            if ($actas && count($actas) > 0) {
                                foreach ($actas as $acta) {
                                    echo '<a href="views/pdffic.php?idactdc='.$acta['idactdc'].'&idfic='.$d['idfic'].'" target="_blank"
                                            title="Imprimir Acta #'.$acta['numact'].'">
                                            <i class="fa-solid fa-print fa-2x"></i>
                                        </a>';
                                }
                            }
                            echo '<i class="fa-solid fa-file-circle-plus fa-2x" 
                                title="Crear Actas" 
                                data-bs-toggle="modal" 
                                data-bs-target="#CrearActModal'.$d['idfic'].'"></i>';
                            echo modActCierreModal(
                                $d['idfic'], 
                                $d['nomfic'], 
                                $d['finific'], 
                                $d['ffinfic']." ".$d['nomval'], 
                                $pg
                            );

                                ?>
                        <a href="views/vrfic.php?fic=<?=$d['idfic'];?>" title="Imprimir" target="_blank">
                            <i class="fa-solid fa-print fa-2x"></i>
                        </a>
                        <?php if($_SESSION["idper"]==1 OR $_SESSION["idper"]==18){ ?>
                        <a href="home.php?pg=<?=$pg;?>&idfic=<?=$d['idfic'];?>&ope=edi" title="Actualizar">
                            <i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                        <?php } ?>
                        <?php 
        
        $res = $mfic->selNoApr($d['idfic']);
        $totalAprendices = (int)$res[0]['can'];

        if ($totalAprendices === 0) { 
    ?>
        <a href="home.php?pg=<?= $pg; ?>&idfic=<?= $d['idfic']; ?>&opera=Eliminar" 
           title="Eliminar" 
           onclick="return eli(this);">
            <i class="fa-solid fa-trash-can fa-2x"></i>
        </a>
    <?php } ?>
                    </td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Ficha</th>
            <th></th>
        </tr>
    </tfoot>
</table>
<br><br><br><br>


