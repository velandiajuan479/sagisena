<?php require_once 'controllers/ccen.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Centro"); ?>

    <div class="Insertar">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class = "form-group col-md-6">
                    <label for="idcen">NIT del centro</label>
                    <input type="text" name="idcen" id="idcen" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['idcen']; ?>">
                </div>
                <div class = "form-group col-md-6">
                    <label for="nomcen">Nombre del centro</label>
                    <input type="text" name="nomcen" id="nomcen" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['nomcen']; ?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="dircen">Dirección</label>
                    <input type="text" name="dircen" id="dircen" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['dircen']; ?>">
                </div>


                <div class = "form-group col-md-6">
                    <label for="telcen">Teléfono</label>
                    <input type="number" name="telcen" id="telcen" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['telcen']; ?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="imgcen" class="form-label">Imagen</label>
                    <input type="file" name="foto" id="imgcen" class="form-control" vaccept="image/png,image/jpeg,image/gif" <?php if(!$datOne) echo "required";?>>
                </div>

                <div class = "form-group col-md-6">
                    <label for="descen">Descripción</label>
                    <input type="text" name="descen" id="descen" maxlength="70" class="form-control" value="<?php if($datOne) echo $datOne[0]['descen']; ?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="fiicancen">Fecha Inicial Inscripción Candidato</label>
                    <?php if($datOne){
                        $fec = date("Y-m-d\Th:i",strtotime($datOne[0]['fiicancen'])); 
                    }else $fec = null; ?>
                    <input type="datetime-local" name="fiicancen" id="fiicancen" maxlength="70" class="form-control" required value="<?=$fec;?>">
                </div>
                <div class = "form-group col-md-6">
                    <label for="fficancen">Fecha Final Inscripción Candidato</label>
                    <?php if($datOne){
                        $fec = date("Y-m-d\Th:i",strtotime($datOne[0]['fficancen'])); 
                    }else $fec = null; ?>
                    <input type="datetime-local" name="fficancen" id="fficancen" maxlength="70" class="form-control" required value="<?=$fec;?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="fiprocen">Fecha Inicial Registro Propuestas</label>
                    <?php if($datOne){
                        $fec = date("Y-m-d\Th:i",strtotime($datOne[0]['fiprocen'])); 
                    }else $fec = null; ?>
                    <input type="datetime-local" name="fiprocen" id="fiprocen" maxlength="70" class="form-control" required value="<?=$fec;?>">
                </div>
                <div class = "form-group col-md-6">
                    <label for="ffprocen">Fecha Final Registro Propuestas</label>
                    <?php if($datOne){
                        $fec = date("Y-m-d\Th:i",strtotime($datOne[0]['ffprocen'])); 
                    }else $fec = null; ?>
                    <input type="datetime-local" name="ffprocen" id="ffprocen" maxlength="70" class="form-control" required value="<?=$fec;?>">
                </div>

                <div class = "form-group col-md-6">
                    <label for="fivotcen">Fecha Inicial Votación</label>
                    <?php if($datOne){
                        $fec = date("Y-m-d\Th:i",strtotime($datOne[0]['fivotcen'])); 
                    }else $fec = null; ?>
                    <input type="datetime-local" name="fivotcen" id="fivotcen" maxlength="70" class="form-control" required value="<?=$fec;?>">
                </div>
                <div class = "form-group col-md-6">
                    <label for="ffvotcen">Fecha Final Votación</label>
                    <?php if($datOne){
                        $fec = date("Y-m-d\Th:i",strtotime($datOne[0]['ffvotcen'])); 
                    }else $fec = null; ?>
                    <input type="datetime-local" name="ffvotcen" id="ffvotcen" maxlength="70" class="form-control" required value="<?=$fec;?>">
                </div>

                <div class = "form-group col-md-6">
                    <br>
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idcen" id="idcen" value="<?php if($datOne) echo $datOne[0]['idcen'];?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Centro</th>
            <th>Fechas</th>
            <th></th>
        </tr>
    </thead>
        <tbody>
        <?php
            if($datAll){
                foreach($datAll AS $dta){ ?>
                <tr>
                    <td>
                        <?php if($dta['imgcen']){ ?>
                            <img src="<?=$dta['imgcen'];?>" width="100px">
                        <?php } ?>
                    </td>
                    <td width="60%">
                        <strong><?=$dta['idcen'];?> - <?=$dta['nomcen'];?></strong>
                        <br>
                        <small>
                            <strong>Dirección:</strong> <?=$dta['dircen'];?><br>
                            <strong>Teléfono:</strong> <?=$dta['telcen'];?> <br>
                            <?=$dta['descen'];?>
                        </small>
                    </td>
                    <td>
                        <strong>Inscripción Candidato</strong><br>
                        <small>
                            <?=$dta['fiicancen'];?> - <?=$dta['fficancen'];?>
                        </small>
                        <br><br>
                        <strong>Registro Propuestas</strong><br>
                        <small>
                            <?=$dta['fiprocen'];?> - <?=$dta['ffprocen'];?>
                        </small>
                        <br><br>
                        <strong>Votaciones</strong><br>
                        <small>
                            <?=$dta['fivotcen'];?> - <?=$dta['ffvotcen'];?>
                        </small>
                        <br><br>
                    </td>
                    <td>
                    <?php 
                        $datCrl = $mcen->getCrl();
                        if($datCrl){ if($datCrl[0]["con"]==0){
                    ?>
                    <a href="home.php?pg=<?=$pg;?>&idcen=<?=$dta['idcen'];?>&ope=edi" title="Editar">
                            <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </a>
                    <?php }} ?>
                        <a href="home.php?pg=<?=$pg;?>&idcen=<?=$dta['idcen'];?>&opera=eli" title="Eliminar" onclick="return eli(this);">
                            <i class="fa-solid fa-trash-can fa-2x"></i>
                        </a>

                    </td>
                </tr>
        <?php
                }
            }
        ?>
        </tbody>
        <tfoot>
            <th>Imagen</th>
            <th>Centro</th>
            <th>Fechas</th>
            <th></th>
        </tfoot>
    </table>
    <br><br><br><br><br>