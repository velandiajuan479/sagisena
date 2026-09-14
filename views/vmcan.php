<?php require_once 'controllers/cdpc.php'; ?>

<!-- Actualizar Candidato Inicio ------------------------------- -->
<div class="" style="width:90%">
    <?php echo titulo2("<i class='fa-solid fa-users' style='color:#000'></i><span style='color:#000'>CANDIDATOS</span>", 2); ?> 


<!-- Actualizar candidato Fin ------------------------------- -->

<!-- Filtro Inicio ------------------------------------------ -->

    <form name="frmfil" action="#'" method="POST">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="idfic" ><h4 style="color:white;">Jornada</h4></label>
                <select name="fidjor" id="idfic" class="form-select" onchange="this.form.submit();">
                    <?php
                    if($djor){
                        foreach ($djor as $dj) { 
                    ?>
                            <option value="<?=$dj['idval'];?>"
                                <?php if($fidjor==$dj['idval']) echo " selected "; ?>
                            ><?=$dj['nomval'];?></option>
                    <?php }} ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="idcen"><h4 style="color:white;">Centro de Formación</h4></label>
                <select name="fidcen" id="idcen" class="form-select" onchange="this.form.submit();">
                <?php
                    if($dcen){
                        foreach ($dcen as $dj) { 
                ?>
                            <option value="<?=$dj['idcen'];?>"
                                <?php if($fidcen==$dj['idcen']) echo " selected "; ?>><?=$dj['nomcen'];?></option>
                <?php
                        }
                    } 
                ?>
                </select>
            </div>
        </div>
    </form>

<!-- Filtro Fin --------------------------------------------- -->
<br><br>


<!-- Mostrar Candidato Inicio ------------------------------- -->
<div style="float: left;width: 100%;padding: 0px 10px 100px 10px;">
    <table id="example" class="table table-striped" style="width:100%; background-color: white;">
        <thead> 
            <tr>
                <th>Foto</th>
                <th>Candidato</th>  
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                if($dat){
                    foreach($dat AS $d){
                    	if($d['noca']<>999){
            ?>
	                        <tr>
	                            <td width="100px">
	                            <?php //if(file_exists("fcan/".$d['ndocusu'].".jpg")){
	                            if(file_exists($d['fotcan'])){
	                             ?>
	                                <img src="<?=$d['fotcan'];?>" style="width: 100%;">
	                            <?php }else{ ?>
	                                <img src="image/usuario.png" style="width: 100%;">
	                            <?php } ?>
	                            </td>
	                            <td >
	                                <?=$d['nomusu'];?><br>
	                                <small>
	                                <?php if($d['idfic']){ ?>
	                                <strong>Ficha: </strong> <?=$d['idfic'];?> <?=$d['nomfic'];?> <?=$d['nomval'];?> <br>
	                                <?php } ?>
	                                <?=$d['nomcen'];?><br>
	                                <strong>No. Candidato: </strong> <?=$d['noca'];?>
	                                </small>
	                            </td>
	                            <td>
	                                <a href="views/pfpro.php?&idusu=<?=$d['idusu'];?>" title="Ver Propuesta">
	                                    <i class="fa-solid fa-file-lines fa-2x"></i>
	                                </a>
	                            </td>
	                        </tr>
            <?php
            			}
                    }
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Foto</th>
                <th>Candidato</th>

                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
</div>
<!-- Mostrar candidato Fin -------------------------------------- -->



