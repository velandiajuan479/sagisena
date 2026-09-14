<?php require_once ('controllers/ccele.php'); ?>

<div class="conte">
	<?php echo titulo2("<i class='" . $icono . "'></i> Carga masiva - Elementos",2); ?>
	<form name="frm1" action="home.php?pg=<?=$pag;?>" method="POST" enctype="multipart/form-data">
		<div class="row">
			<div class="form-group col-md-6">
				<label for="arch">Cargar Archivo</label>
				<input type="file" name="arch" id="arch" class="form-control" accept=".xls,.xlsx">
			</div>
			<div class="form-group col-md-6">
				<input type="submit" class="btn btn-primary" name="arch" id="arch" value="Cargar" style="margin: 10px 0;">
			</div>
		</div>
	</form>
</div>