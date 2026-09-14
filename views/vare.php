<?php require_once("controllers/care.php"); ?>
<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i> Área",1); ?>
	<div class="inser">
		<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
			<div class="row">
				<div class="form-group col-md-4">
					<label for="nomare">Nombre</label>
					<input type="text" name="nomare" id="nomare" class="form-control" value="<?php if($datOne) echo $datOne[0]['nomare']; ?>" required>
				</div>
				<div class="form-group col-md-4">
					<label for="idusu">Coordinador</label>
					<select name="idusu" id="idusu" class="form-control form-select" required>
						<?php if($datUsu){ foreach($datUsu as $du){ ?>
							<option value="<?=$du['idusu'];?>" 
								<?php if($datOne && $datOne[0]['idusu']==$du['idusu']) echo "selected"; ?>
								><?=$du['nomusu'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-4">
					<br>
					<input type="submit" value="Enviar" class="btn btn-primary">
					<input type="hidden" name="opera" value="save">
					<input type="hidden" name="idare" value="<?=$idare;?>">
				</div>
			</div>
		</form>
	</div>
</div>

<table id="example" class="table table-striped" style="width:100%">
	<thead>
		<tr>
			<th>Área</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if($datAll){ foreach($datAll as $du){ ?>
			<tr>
				<td>
					<big><strong><?=$du['idare'];?> - <?=$du['nomare'];?></strong></big><br>
					Coordinador: <?=$du['nomusu'];?>
				</td>
				<td>
					<a href="home.php?pg=<?=$pg;?>&idare=<?=$du['idare'];?>&opera=edi" title="Editar">
						<i class="fa-solid fa-pen-to-square fa-2x"></i>
					</a>
					<a href="home.php?pg=<?=$pg;?>&idare=<?=$du['idare'];?>&opera=eli" onclick="return eli(this);" title="Eliminar">
						<i class="fa-solid fa-trash-can fa-2x"></i>
					</a>
				</td>
			</tr>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Área</th>
			<th></th>
		</tr>
	</tfoot>
</table>