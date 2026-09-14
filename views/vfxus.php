<?php require_once("controllers/cfxus.php"); ?>
<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i>Fichas por Usuario",1);?>
	<div class="inser">
		
		<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
			
			<!--
			<form id="frmins" action="index.php" method="post">!
				-->
			<div class="row">
				<!-- Usuario -->
				<div class="form-group col-md-6">
					<label for="idusu">Usuario</label>
					<select name="idusu" id="idusu" class="form-control form-select">
						<?php if($datUsu){ foreach($datUsu as $dt){ ?>
							<option value="<?=$dt["idusu"];?>"<?php if($datOne && $datOne[0]['idusu']==$dt["idusu"]) echo "selected"; ?>><?=$dt["ndocusu"]." - ".$dt["nomusu"];?></option>
						<?php }} ?>
					</select>
				</div>

				<!-- Ficha -->
				<div class="form-group col-md-6">
					<label for="idfic">Ficha</label>
					<select name="idfic" id="idfic" class="form-control form-select">
						<?php if($datFic){ foreach($datFic as $dt){ ?>
							<option value="<?=$dt["idfic"];?>"><?=$dt["nomfic"];?></option>
						<?php }} ?>
					</select>
				</div>

				<!-- Activo -->
				<div class="form-group col-md-6">
					<label for="actfic">¿Activo?</label>
					<select name="actfic" id="actfic" class="form-control form-select">
						<option value="1">Sí</option>
						<option value="0">No</option>
					</select>
				</div>

				<!-- Botones -->
				<div class="form-group col-md-6">
					<br>
					<input type="submit" class="btn btn-primary" value="Guardar">
					<input type="hidden" name="opera" value="save">
					<input type="hidden" name="idusu" value="">
				</div>
			</div>			
		</form>		
	</div>
</div>

<!-- Tabla de registros -->
<table id="example" class="table table-striped" style="width: 100%">
	<thead>
		<tr>
			<th>Ficha</th>
			<th>Usuario</th>
			<th>Estado</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php if($datAll){ foreach($datAll as $dt){ ?>
			<tr>
				<td><?=$dt["idfic"];?> - <?=$dt["nomfic"];?></td>
				<td><?=$dt["nomusu"];?></td>
				<td>
					<?php if($dt["actfic"] == 1){ ?>
						<span class="badge bg-success">Activo</span>
					<?php } else { ?>
						<span class="badge bg-secondary">Inactivo</span>
					<?php } ?>
				</td>
				<td>
					<!--botones de editar/eliminar -->
					<a href="home.php?pg=<?=$pg;?>$opera=edi&idusu=<?=$dt['idusu'];?>" title="Editar">
						<i class="fa-solid fa-pen-to-square fa-2x "></i>
					</a>
					<a href="home.php?pg=<?=$pg;?>$opera=eli&idusu=<?=$dt['idusu'];?>" title="Eliminar">
						<i class="fa-solid fa-trash-can fa-2x "></i>
					</a>
				</td>
			</tr>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Ficha</th>
			<th>Usuario</th>
			<th>Estado</th>
			<th>Acciones</th>
		</tr>
	</tfoot>	
</table>