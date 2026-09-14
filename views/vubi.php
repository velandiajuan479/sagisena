<?php
include "controllers/cubi.php";
?>
<div class="conte"> 
    <?php echo titulo2("<i class='" . $icono . "'></i> Ubicación", 1); ?> 
<div id="ins">
<form name="frm1" action="#" method="POST">
	<div class="row">
		<div class="form-group col-md-4">
			<label for="nomubi">Nombre</label>
			<input type="text" class="form-control form-control" name="nomubi" id="nomubi" value="<?php if($dtOne && $dtOne[0]['nomubi']) echo $dtOne[0]['nomubi']; ?>" required>
		</div>
		<div class="form-group col-md-4">
			<label for="nomubi">Codigo</label>
			<input type="number" class="form-control form-control" name="codubi" id="codubi" value="<?php if($dtOne && $dtOne[0]['codubi']) echo $dtOne[0]['codubi']; ?>" required>
		</div>
		
		<div class="form-group col-md-4">
			<label for="depubi">Departamento</label>
			<select class="form-control form-select" id="depubi" name="depubi">
				<option value="0">Nuevo Departamento</option>
				<?php if($dtDtp){ foreach($dtDtp as $dtD){ ?>
				<option value="<?=$dtD['codubi'];?>" <?php if($dtOne && $dtOne[0]['depubi']==$dtD['codubi']) echo "selected"; ?>><?=$dtD['nomubi'];?></option>
				<?php }} ?>
			</select>
		</div>

		<div class="form-group col-md-4">
			<br>
			<input type="hidden" name="ope" value="save">
			<input type="hidden" name="codubi" value="<?php if($dtOne && $dtOne[0]['codubi']) echo $dtOne[0]['codubi']; ?>" required>
			<input type="submit" class="btn btn-primary" value="Enviar">
		</div>
	</div>
</form>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
			<th>Código</th>
			<th>Municipio - Dpto.</th>
			<th></th>
		</tr>
    </thead>
	<tbody>
		<?php if($dat){ foreach ($dat as $dt) { ?>
			<tr>
				<td><?=$dt["cm"];?></td>
				<td><?=$dt["nm"]." ".$dt["nd"] ;?></td>
				<td>
					<a href="home.php?pg=<?=$pg;?>&ope=del&codubi=<?=$dt['cm'];?>" onclick="return eli();" title="Eliminar"><i class="fa-solid fa-trash-can fa-2x"></i></a>
					<a href="home.php?pg=<?=$pg;?>&ope=edi&codubi=<?=$dt['cm'];?>" title="Editar"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
				</td>
			</tr>
		<?php }} ?>
	</tbody>

    <tfoot>
        <tr>
			<th>Código</th>
			<th>Ubicación</th>
			<th></th>
		</tr>
    </tfoot>
</table>
</div>