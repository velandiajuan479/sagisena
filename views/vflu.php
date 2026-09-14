<?php require_once("controllers/cflu.php"); ?>
<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i> Flujo",1); ?>
	<div class="inser">
		<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
			<div class="row">
				<div class="form-group col-md-4">
					<label for="nomare">Nombre del Flujo</label>
					<input type="text" name="nomflu" id="nomflu" class="form-control" value="<?php if($datOne) echo $datOne[0]['nomflu']; ?>" required>
				</div>
				<div class="form-group col-md-4">
					<label for="fluacti">Estado</label>
					<select name="fluacti" id="fluacti" class="form-control form-select" required>
						<option value="1" <?php if($datOne && $datOne[0]['fluacti']==1) echo 'selected'; ?>>Activo</option>
						<option value="2" <?php if($datOne && $datOne[0]['fluacti']!=1) echo 'selected'; ?>>Inactivo</option>
					</select>
				</div>
				<div class="form-group col-md-4">
					<br>
					<input type="submit" value="Guardar" class="btn btn-primary">
					<input type="hidden" name="opera" value="save">
					<input type="hidden" name="idflu" value="<?php if($datOne) echo $datOne[0]['idflu']; ?>">
				</div>
			</div>
		</form>
	</div>
</div>

<table id="example" class="table table-striped" style="width:100%">
	<thead>
		<tr>
			<th>Id</th>
			<th>Flujo</th>
			<th>Estado</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if($datAll){ foreach($datAll as $f){ ?>
			<tr>
				<td><strong><?=$f['idflu'];?></strong></td>
				<td><?=htmlspecialchars($f['nomflu']); ?></td>
				<td>
					<?php if($f['fluacti']==1){
						echo '<a href="home.php?pg='.$pg.'&idflu='.$f['idflu'].'&opera=ediact&act=2" title="Desactivar"><i class="fa-solid fa-circle-check fa-2x" style="color: #0000ff;"></i></a>';
					}else{
						echo '<a href="home.php?pg='.$pg.'&idflu='.$f['idflu'].'&opera=ediact&act=1" title="Activar"><i class="fa-solid fa-circle-xmark fa-2x" style="color: #ff0000;"></i></a>';
					} ?>
				</td>
				<td>
					<a href="home.php?pg=<?=$pg;?>&idflu=<?=$f['idflu'];?>&opera=edi" title="Editar">
						<i class="fa-solid fa-pen-to-square fa-2x"></i>
					</a>

					<a href="home.php?pg=<?=$pg;?>&idflu=<?=$f['idflu'];?>&opera=eli" title="Eliminar" onclick="return eliminar();">
						<i class="fa-solid fa-trash-can fa-2x"></i>
					</a>
				</td>
			</tr>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Id</th>
			<th>Flujo</th>
			<th>Estado</th>
			<th></th>
		</tr>
	</tfoot>
</table>