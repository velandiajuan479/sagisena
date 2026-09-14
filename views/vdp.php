<?php require_once('controllers/cusu.php'); ?>

<div class="conte">
	<?php echo titulo2("<i class='" . $icono . "'></i> Datos Personales", 0); ?>
	<div class="inser container-form-pages">
		<form name="frm1" action="home.php?pg=<?= $pg; ?>" method="POST" enctype="multipart/form-data"
			class="form-default-pages my-5">
			<div class="row d-flex gap-5">
				<div class="form-group col-md-3 d-flex flex-column align-items-center">
					<div class="profile-pic-container">
						<?php
						$fotoPerfil = ($datOne && $datOne[0]['fotcan'])
							? $datOne[0]['fotcan']
							: 'image/user-default.webp';
						?>
						<img src="<?= $fotoPerfil ?>" alt="Foto de perfil" class="profile-pic" id="profileImage">

						<?php if (!$datOne[0]['fotcan']) { ?>
							<label for="fotoInput" class="edit-icon"
								title="Tenga en cuenta que la fotografía solo puede ser cargada en el sistema una única vez. Esta debe ser reciente, con fondo blanco, mostrar claramente el rostro y el pecho, y no debe incluir gorras ni ningún otro elemento que cubra el rostro.">
								<i class="fa-solid fa-pencil"></i>
							</label>
							<input type="file" id="fotoInput" name="foto" accept="image/png, image/jpeg" class="d-none">
						<?php } ?>
					</div>
				</div>
				<div class="col">
					<div class="container-inputs-default row justify-content-between">
						<div class="form-group col-md-12">
							<label for="nomusu" class="fw-semibold mb-2">Nombre Completo</label>
							<input type="text" name="nomusu" id="nomusu" maxlength="70"
								class="form-control form--input-default" required value="<?php if ($datOne)
									echo $datOne[0]['nomusu']; ?>">
						</div>
						<div class="form-group col-md-5">
							<label for="tdousu" class="fw-semibold mb-2">Tipo de Documento</label>
							<select name="tdousu" id="tdousu" class="form-select form--input-default">
								<?php
								if ($tdos) {
									foreach ($tdos as $de) {
										?>
										<option value="<?= $de['idval']; ?>" <?php if ($datOne && $datOne[0]['tdousu'] == $de['idval'])
											  echo " selected "; ?>><?= $de['nomval']; ?>
										</option>
									<?php }
								} ?>
							</select>
						</div>
						<div class="form-group col-md-7">
							<label for="ndocusu" class="fw-semibold mb-2">No. Documento</label>
							<input type="text" name="ndocusu" id="ndocusu" class="form-control form--input-default"
								required value="<?php if ($datOne)
									echo $datOne[0]['ndocusu']; ?>" readonly>
						</div>
						<div class="form-group col-md-5">
							<label for="rhusu" class="fw-semibold mb-2">RH</label>
							<select name="rhusu" id="rhusu" class="form-select form--input-default">
								<?php
								if ($rhs) {
									foreach ($rhs as $de) {
										?>
										<option value="<?= $de['idval']; ?>" <?php if ($datOne && $datOne[0]['rhusu'] == $de['idval'])
											  echo " selected "; ?>><?= $de['nomval']; ?>
										</option>
									<?php }
								} ?>
							</select>
						</div>
						<div class="form-group col">
							<label for="genusu" class="fw-semibold mb-2">Género</label>
							<select name="genusu" id="genusu" class="form-select form--input-default">
								<?php
								if ($gen) {
									foreach ($gen as $de) {
										?>
										<option value="<?= $de['idval']; ?>" <?php if ($datOne && $datOne[0]['genusu'] == $de['idval'])
											  echo " selected "; ?>><?= $de['nomval']; ?>
										</option>
									<?php }
								} ?>
							</select>
						</div>
						<div class="form-group col-md-12">
							<label for="nomusu" class="fw-semibold mb-2">Email</label>
							<input type="text" name="emausu" id="emausu" maxlength="70"
								class="form-control form--input-default" value="<?php if ($datOne)
									echo $datOne[0]['emausu']; ?>">
						</div>
						<div class="form-group col-md-5">
							<label for="nomusu" class="fw-semibold mb-2">Teléfono</label>
							<input type="number" name="telcan" id="telcan" max="9999999999999"
								class="form-control form--input-default" value="<?php if ($datOne)
									echo $datOne[0]['telcan']; ?>">
						</div>
						<div class="form-group col">
							<label for="pasusu" class="fw-semibold mb-2">Contraseña</label>
							<input type="password" name="pasusu" id="pasusu" maxlength="70"
								class="form-control form--input-default" <?php if (!$datOne)
									echo "required"; ?>
								placeholder="Actualiza tu contraseña">
						</div>

						<?php if ($datOne[0]['fotcan']) { ?>
							<div class="alert alert-info my-4" role="alert">
								<p class="m-0"><b>NOTA:</b> Si desea actualizar su fotografía, diríjase a la
								Biblioteca
								del Centro de Desarrollo Agroempresarial</p>
							</div>
						<?php } ?>
						<div class="form-group col">
							<input type="submit" class="btn btn-success" value="Actualizar">
							<input type="hidden" name="idusu" value="<?php if ($datOne)
								echo $datOne[0]['idusu']; ?>">
							<input type="hidden" name="opera" value="save">
							<input type="hidden" name="noca" value="<?php if ($datOne)
								echo $datOne[0]['noca']; ?>">
							<input type="hidden" name="actusu" value="1">
							<input type="hidden" name="idcen" value="<?php if ($datOne)
								echo $datOne[0]['idcen']; ?>">
							<input type="hidden" name="idper" value="<?php if ($datOne)
								echo $datOne[0]['idper']; ?>">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", () => {
		let h1 = document.querySelector(".title-page");
		if (h1) {
			document.title = h1.textContent.trim();
		}
	});
	document.getElementById('fotoInput').addEventListener('change', function (event) {
		const file = event.target.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function (e) {
				document.getElementById('profileImage').src = e.target.result;
			};
			reader.readAsDataURL(file);
		}
	});

</script>