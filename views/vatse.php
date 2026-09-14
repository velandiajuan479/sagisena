<?php require_once 'controllers/catse.php';?>

<?php echo titulo2("<i class='".$icono."'></i> Actividades",0); ?>
<div class="conte">
    <form id="frm1" action="home.php?pg=<?= $pg; ?>" method="POST" enctype="multipart/form-data"> 
        <div class="row">
            <div class="form-group col-md-4">
                <label for="nomact">Nombre actividad</label>
                <input type="text" class="form-control form-control" name="nomact" id="nomact" value="<?php if($dtOne && $dtOne[0]['nomact']) echo $dtOne[0]['nomact']; ?>" required>
            </div>
            
            <div class="form-group col-md-4">
                <label for="idses">Sesion</label>
                
                <select name="idses" id="idses" class="form-select">
                <?php if ($dses) {foreach ($dses as $de){?>
                    <option value="<?= $de['idses']; ?>" <?php if ($dat && $idses==$de['idses']) echo " selected "; ?> > <?=$de['idses']; ?></option>
                <?php }} ?>
      </select>
            </div>
            <div class="form-group col-md-4">
                <label for="desact">Descripción</label>
                <input type="text" class="form-control form-control" name="desact" id="desact" value="<?php if($dtOne && $dtOne[0]['desact']) echo $dtOne[0]['desact']; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="duract">Duración</label>
                <input type="number " class="form-control form-control" name="duract" id="duract" value="<?php if($dtOne && $dtOne[0]['duract']) echo $dtOne[0]['duract']; ?>" required>
            </div>
            <div class="form-group col-md-3">
                <label for="tipact">Tipo actividad</label>
                <input type="number" class="form-control form-control" name="tipact" id="tipact" value="<?php if($dtOne && $dtOne[0]['tipact']) echo $dtOne[0]['tipact']; ?>" required>
            </div> 
            <div class="form-group col-md-3">
                <label for="tipact">Orden de actividad</label>
                <input type="number" class="form-control form-control" name="ordact" id="ordact" value="<?php if($dtOne && $dtOne[0]['ordact']) echo $dtOne[0]['ordact']; ?>" required>
            </div> 
            <div class="form-group col-md-3">
			    <br>
			    <input type="hidden" name="ope" value="save">
			    <input type="hidden" name="idact" value="<?php if($dtOne && $dtOne[0]['idact']) echo $dtOne[0]['idact']; ?>" required>
			    <input type="submit" class="btn btn-primary" value="Enviar">
		    </div>
        </div>
    </form>
</div>
<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Numero actividad</th>
            <th>Sesion</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Duración</th>
            <th>Tipo</th>
            <th>Orden</th>

        </tr>
    </thead>
    <tbody>
        <?php if($dat){ foreach ($dat as $dt){ ?>
            <tr>
                <td><?=$dt["idact"];?></td>
                <td><?=$dt["idses"];?></td>
                <td> <?=$dt["nomact"];?></td>
                <td> <?=$dt["desact"];?></td>
                <td> <?=$dt["duract"];?>m</td>
                <td> <?=$dt["tipact"];?></td>
                <td> <?=$dt["ordact"];?></td>
                <td>
                        <a href="home.php?pg=1508&ope=del&idact=<?=$dt["idact"];?>" onclick="return eli();" title="Eliminar"><i class="fa-solid fa-trash-can fa-2x"></i></a>
                        <a href="home.php?pg=1508&ope=edi&idact=<?=$dt["idact"];?>" title="Editar"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
                </td>
            </tr>
        <?php }}?>
    </tbody>
    <tfoot>
            <th>Numero actividad</th>>
            <th>Sesion</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Duración</th>
            <th>Tipo</th>
            <th>Orden</th>
    </tfoot>
</table>