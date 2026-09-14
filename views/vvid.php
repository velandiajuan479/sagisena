<?php
include "controllers/cvid.php";
?>
<div class="row opaco">
	<h3 id="titleHelp" data-nompag="<?= $datOne[0]['nompag'] ?>">Ayuda - <i class='<?= $datOne[0]['icopag'] ?>'></i> <?= $datOne[0]['nompag'] ?></h3>
	<div class="form-group col col-lg-8 frob container-vid-help" id="go1">
		<?php if ($vid) { ?>
			<video controls preload="auto" class="vid-help">
				<source src="videos/v<?= $vid; ?>.mp4" type="video/mp4">
				Su navegador no soporta la visualización de video.
			</video>
		<?php } else {
			echo "Video no encontrado";
		} ?>
		<div>
			<button title="Descargar Manual General de Usuario" class="btn-download-manual"><a
					href="./Manuales/ManualSagi.pdf"><i class="fa-solid fa-file-pdf"></i></a></button>
			<button title="Descargar Manual de usuario" class="btn-download-manual"><a
					href="./Manuales/m<?= $vid; ?>.pdf"><i
						class="fa-solid fa-file-arrow-down"></i></a></button>
		</div>
	</div>
	<div class="form-group col algizq" id="go1">
		<p class="txtract"><?php if($datOne[0]['despag']) echo $datOne[0]['despag']; else echo "Sin transcripción añadida"?></p>
	</div>

</div>
<script>
	document.addEventListener("DOMContentLoaded", () => {	
		const pagname = document.getElementById('titleHelp').dataset.nompag;
		const text = `Ayuda - ${pagname}`
		document.title = text.trim();
	});
</script>