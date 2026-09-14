<?php require_once("controllers/cemp.php"); ?>
<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i> Empresas",1); ?>
	<div class="inser">
		<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
			<div class="row">
				<div class="form-group col-md-6">
					<label for="numdocemp">NIT</label>
					<input type="text" name="numdocemp" id="numdocemp" maxlength="20" class="form-control" value="<?php if($datOne) echo $datOne[0]['numdocemp']; ?>" required>
				</div>

				<div class="form-group col-md-6">
					<label for="nomemp">Nombre</label>
					<input type="text" name="nomemp" id="nomemp" maxlength="50" class="form-control" value="<?php if($datOne) echo $datOne[0]['nomemp']; ?>" required>
				</div>

				<div class="form-group col-md-6">
					<label for="diremp">Direcci&oacute;n</label>
					<input type="text" name="diremp" id="diremp" maxlength="50" class="form-control" value="<?php if($datOne) echo $datOne[0]['diremp']; ?>" required>
				</div>

				<div class = "form-group col-md-3">
                    <label for="mun">Departamento</label>
                    <select class="form-control form-select" onchange="recCiudad(this.value);">
                    	<option value="0">Seleccione un departamento para cambiarlo</option>
                    <?php if($datUbi){ foreach($datUbi AS $ub){ ?>
                        <option value="<?=$ub['codubi'];?>"><?=$ub['nomubi'];?></option>
                    <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="ubiest">Municipio</label>
                    <div id="reloadMun">
                    	<?php if($datOne){ ?>
                    		<input type="text"class="form-control" disabled value="<?=$datOne[0]['nommun'];?> - <?=$datOne[0]['nomdep'];?>">
                    	<?php }else{ ?>
                    		<input type="text"class="form-control" disabled value="Primero selecione un departamento">
                    	<?php } ?>
                        <input type="hidden" name="ubiest" id="ubiest" maxlength="255" class="form-control" value="<?php if($datOne) echo $datOne[0]['codubi']; ?>">
                    </div>
                </div>



				<div class="form-group col-md-6">
					<label for="nomconemp">Nombre Contacto</label>
					<input type="text" name="nomconemp" id="nomconemp" maxlength="50" class="form-control" value="<?php if($datOne) echo $datOne[0]['nomconemp']; ?>" required>
				</div>

				<div class="form-group col-md-6">
					<label for="telemp">Tel&eacute;fono</label>
					<input type="number" name="telemp" id="telemp" min="1111111111" max="9999999999" class="form-control" value="<?php if($datOne) echo $datOne[0]['telemp']; ?>">
				</div>

				<div class="form-group col-md-6" style="margin:auto;">
					<input class="btn btn-primary" type="submit" value="Enviar">
					<input type="hidden" name="opera" value="save">
					<input type="hidden" name="idemp" id="idemp" value="<?php if($datOne) echo $datOne[0]['idemp'];?>">
				</div>
			</div>
		</form>
	</div>
</div>

<table id="example" class="table table-striped" style="width:100%">
	<thead>
		<tr>
			<th>Empresa</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php require_once("controllers/cemp.php");?>
		<?php if($datAll){ foreach($datAll as $du){ ?>
			<tr>
				<td>
					<big><strong><?=$du['idemp'];?> - <?=$du['nomemp'];?></strong></big><br>
					Contacto: <?=$du['nomconemp'];?><br>
					Teléfono: <?=$du['telemp'];?><br>
					Dirección: <?=$du['diremp'];?> <?=$du['nommun'];?> - <?=$du['nomdep'];?>
				</td>
				<td>
					<a href="home.php?pg=<?=$pg;?>&idemp=<?=$du['idemp'];?>&opera=edi" title="Editar">
						<i class="fa-solid fa-pen-to-square fa-2x"></i>
					</a>

					<a href="home.php?pg=<?=$pg;?>&idemp=<?=$du['idemp'];?>&opera=eli" title="Eliminar" onclick="return eli(this);">
						<i class="fa-solid fa-trash-can fa-2x"></i>
					</a>
				</td>
			</tr>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Empresa</th>
			<th></th>
		</tr>
	</tfoot>
</table>