<?php require_once('controllers/ccar.php'); ?>
<script src="js/JsBarcode.all.min.js"></script>
<div class="row">
	<div class="col bx-carnet-dt"">

		<section>
			<div class=" borcar">
		<div class="logo">
			<img src="img/logoSena.png">
		</div>
		<div class="fot">
			<?php if ($datOne && file_exists($datOne[0]['fotcan'])) { ?>
				<img src="<?= $datOne[0]['fotcan']; ?>">
			<?php } else { ?>
				<img src="img/user.jpg">
			<?php } ?>
		</div>
		<div class="tipct">
			<?php if ($datOne)
				echo $datOne[0]['nomper'];
			else
				echo "Visitante" ?>
			</div>
			<hr>
			<div class="txtnom">
			<?= $nom; ?>
			<br>
			<?= $ape; ?>
		</div>
		<div class="txt">
			    <?= ($datOne && isset($datOne[0]['tipdoc']) ? $datOne[0]['tipdoc'] : 'N/A'); ?>
    <?= number_format($ndocusu, 0, ',', '.'); ?>
		</div>
		<div class="txt">
			<?php echo "RH: " . ($datOne && isset($datOne[0]['rh']) ? $datOne[0]['rh'] : "N/A"); ?>
		</div>
		<?php if($datOne && isset($datOne[0]['tipdoc'])){ ?>
			<div class="coba">
				<svg id="c1"></svg>
			</div>
		<?php } ?>
		<hr class="tbar">
		<div class="txt" style="padding-top: 2px;font-size: 12px;">
			<span class="txt1">Regional Cundinamarca</span>
			<br>
			<span class="txt2">Centro de Desarrollo Agroempresarial</span>
		</div>
	</div>

	<!-- <div class="borcar ajubc">
		<div class="txtint">
			Este carné identifica a quien lo porta únicamente para el cumplimiento de sus funciones y para la obtención de los servicios que el SENA presta a sus funcionarios y/o contratistas.<br>
			Se solicita a las autoridades civiles y militares prestarle toda la colaboración para su desempeño.<br><br>
			FIRMA AUTORIZADA

		</div>
		<br>
		Válido hasta: 16/12/2023
	</div> -->
	
	<div class="borcar ajubc">
		<div class="txtint">
			<?php echo $text; ?>
		</div>
		<?php if ($datOne): ?>
			<?php $perfil = $datOne[0]['idper']; ?>

			<?php if ($perfil == 4 || $perfil == 8): ?>
				<strong>Ficha:</strong> <?= $datOne[0]['idfic'] . " " . $datOne[0]['nomfic'] . " " . $datOne[0]['nomval'] ?><br>
				<strong>Fecha inicial: </strong><?= $datOne[0]['finific'] ?><br>
				<strong>Fecha Finalización: </strong><?= $datOne[0]['ffinfic'] ?><br>

			<?php elseif ($perfil == 12): ?>
				<strong>Fecha inicial: </strong><?= $datOne[0]['fecini'] ?><br>
				<strong>Fecha Finalización: </strong><?= $datOne[0]['fecfin'] ?><br>

			<?php else :?>
				<br>
				<strong style="padding-top: 5px;">Cargo: </strong><?= $datOne[0]['nomper'] ?><br>
			<?php endif; ?>
		<?php endif; ?>


		<script type="text/javascript">
			JsBarcode("#c1", "<?= $ndocusu; ?>", {
			format: "codabar",
			lineColor: "#000",
			width: 2,
			height: 30,
			displayValue: false
		});
	</script>
	</section>
	<div class="col bx-carnet">
		<form class="m-tb-40 form-carnet" action="index.php?pg=101" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col"><i class="fa-solid fa-address-card logo-carnet"></i></div>
				<div class="col-10">
					<p class="texto-azul">Genera tu <b>Carnet</b> de forma rápida y sencilla.</p>
				</div>
			</div>
			<input required type="number" class="form-control form-control-sm" id="inp-num_doc" maxlength="100" name="id"
				style="font-size: 15px;" autofocus placeholder="Número de documento" />
			<input type="hidden" name="ope" value="save">
		</form>
	</div>
</div>
<div class="text-end mt-3">
	<a href="index.php" class="btn btn-sm">
		<i class="fa-solid fa-arrow-left"></i> Volver
	</a>
</div>
</div>

<script>
	let timer;
	const inputCarnet = document.getElementById('inp-num_doc');
	const form = document.querySelector('.form-carnet');

	if (inputCarnet) {
		inputCarnet.addEventListener('input', () => {
			clearTimeout(timer); // reinicia el temporizador
			timer = setTimeout(() => {
				if (inputCarnet.value.trim() !== "") {
					form.submit();
				}
			}, 2000);
		});
	}

</script>