<?php require_once 'controllers/ccusu.php'; ?>

<div class="conte">
	<?php echo titulo2("Carga masiva - Usuarios","fas-solid fa-sile","home.php", $pg,400); ?>
	<form name="frm1" action="home.php?pg=<?=$pag;?>" method="POST" enctype="multipart/form-data">
		<div class="row">
			<div class="form-group col-md-6">
				<label for="inic">Contraseña Inicio</label>
				<input type="text" name="inic" id="inic" class="form-control">
			</div>
			<div class="form-group col-md-6">
				<label for="finc">Contraseña Fin</label>
				<input type="text" name="finc" id="finc" class="form-control">
			</div>
			<div class="form-group col-md-6">
				<label for="arch">Cargar Archivo</label>
				<input type="file" name="arch" id="arch" class="form-control" accept=".xls,.xlsx">
			</div>
			<div class="form-group col-md-6">
				<br>
				<input type="submit" class="btn btn-primary" name="arch" id="arch">
			</div>
		</div>
	</form>
</div>