<?php require_once "controllers/cmcon.php"; ?>
<?php require_once "controllers/cmod.php"; ?>
<?php require_once "controllers/ccon.php"; ?>
<link rel="stylesheet" type="text/css" href="css/style.css">

<?php if (!$id) { ?>
	<div class="row bx-main-sld animate__animated animate__fadeIn animate__delay-02s">
		<div class="recuadro-verde col col-md-8 col-xl">
			<link rel="stylesheet" href="./css/slick.min.css"/>
			<script src="./js/slick.min.js"></script>

			<div class="wrap">
				<div class="slider">
					<?php foreach ($datAct as $dt): ?>
						<div class="item">
							<div class="row">
								<div class="col">
									<h4><?= $dt['nommod']; ?></h4>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<?php if (file_exists($dt['imgmod'])) { ?>
										<img src="<?= $dt['imgmod']; ?>" width="300px">
									<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<?php if($dt['desmod']){?>
										<p><?= $dt['desmod']; ?></p>
									<?php } else {
										echo "<p class='default-des'>Sin descripción añadida</p>";
									}?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if (!empty($datOne)): ?>

					<div class="row bx-wrp-des-sg">
						<div class="col bx-wrp-des-sg-col">
							<p><?php echo nl2br($datOne[0]['descof']); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="col bx-carnet">
			<form class="m-tb-40 form-carnet" action="index.php?pg=101" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="col"><i class="fa-solid fa-address-card logo-carnet"></i></div>
					<div class="col-10">
						<p class="texto-azul">Genera tu <b>Carnet</b> de forma rápida y sencilla.</p>
					</div>
				</div>
				<input required type="number" class="form-control form-control-sm" id="id" maxlength="100" name="id"
					style="font-size: 15px;" onchange="this.form.submit();" autofocus placeholder="Número de documento" />
				<input type="hidden" name="ope" value="save">
			</form>
		</div>
	</div>

<?php } elseif ($ndocusu) { ?>
	<div class="row">
		<div class="form-group col-md-6 frob" id="go1">
			<form class="m-tb-40" name="myForm" action="index.php?pg=202" method="POST" enctype="multipart/form-data">
				<div class="row">
					<br>
					<div class="form-group col-md-12" id="go1" style="text-align: center;">
						<big><span class="tnom"
								style="text-shadow: 0px 0px 4px #000, 0px 0px 4px #000, 0px 0px 4px #000, 0px 0px 4px #000, 0px 0px 4px #000;">
								<?php if (!$rdtT) {
									$txtsty = "opacity: 1;";
									$ajuimg = "70px"; ?>
									SALIDA
								<?php } else {
									$txtsty = "opacity: 0;";
									$ajuimg = "150px"; ?>
									<br><br><br><br>
									<span style="font-size:34px;">FINALIZACIÓN DEL RECORRIDO</span>
									<script type="text/javascript">
										setInterval(recargar, 5000);
										function recargar() {
											// document.myForm.submit()
											window.location.href = 'index.php?pg=202';
										}
									</script>
								<?php } ?>
							</span></big>
						<?php if ($msjerr) { ?>
							<br>
							<div class="boxven parpadea"><?= $msjerr; ?></div>
						<?php } ?>
					</div>
					<div class="form-group col-md-12" id="go1" style="text-align: center;">
						<span class="tnom">
							<input type="hidden" name="fotusu" id="idusu" value="<?= $image_url; ?>">
							<input type="hidden" name="idusu" value="<?= $val[0]['idusu']; ?>">
							<?= $val[0]['nomusu'] . " " . $val[0]['apeusu']; ?><br>
							C.C. <?php if ($ndocusu)
								echo number_format($ndocusu, 0, ',', '.'); ?>
							<?php if ($val[0]['noint']) { ?>
								No. Int <?= $val[0]['noint']; ?>
							<?php } ?>
						</span>
					</div>

					<div class="form-group col-md-12" id="go1" style="text-align: center;">
						<span class="tnom">
							Placa <?= $dtrut[0]['placa']; ?>
						</span>
						<input type="hidden" id="placa" name="placa" value="<?= $dtrut[0]['placa']; ?>">
					</div>
					<div class="form-group col-md-12" id="go1" style="text-align: center;">
						<label for="fechos" style="font-size: 15px;">Fecha y Hora</label>
						<br>
						<big><big>
								<?= $fechos; ?>
							</big></big>
						<input type="hidden" name="orimin" value="<?= $mrt[0]['idrut']; ?>">
						<input type="hidden" name="ope" value="edi">
					</div>
				</div>

				<div class="row agrrut" style="<?= $txtsty; ?>">
					<div class="form-group col-md-12 btnpin" id="go1">
						<big>
							<?= strtoupper($dtrut[0]['nomrut']); ?>
						</big>
					</div>
					<?php if ($mrt) {
						$con = round(12 / count($mrt), 0);
						if ($con < 4)
							$con = 4;
						foreach ($mrt as $mt) { ?>
							<div class="form-group col-md-<?= $con; ?> btnpin" id="go1">
								<input type="radio" name="btnrut" value="<?= $mt['idlug']; ?>" <?php if ($rdtT and $rdtT[0]['orimin'] == $mt['idlug'])
									  echo "checked"; ?> 			<?php if (!$rdtT)
														 echo "checked"; ?>
									style="width: 25px;height: 25px;" <?php if ($rdtT)
										echo "disabled"; ?> />
								<br><?= $mt['nomlug']; ?>

							</div>
						<?php }
					} ?>
					<?php if ($rdtT) { ?>
						<input type="hidden" name="btnrut" value="<?= $rdtT[0]['orimin']; ?>">
					<?php } ?>
				</div>
				<div class="row">
					<?php if (!$rdtT) { ?>
						<button type="submit" id="btnReg" class="btn btn-primary" style="font-size: 20px;display: none;">2.
							Registrar</button>
						<script src="js/foto.js"></script>
					<?php } ?>
					<?php if ($rdtT) { ?>
						<script>
							document.getElementById("btnReg").style.display = "inline-block";
						</script>
					<?php } ?>
				</div>
			</form>
		</div>
		<div class="form-group col-md-4" id="go1" style="text-align: center;margin-top: <?= $ajuimg; ?>">
			<!-- <?php //if($val && $val[0]['fotcan']){ ?>
				<img src="<?= $val[0]['fotcan']; ?>" class="fotusi">
			<?php //}else{ ?>
				<img src="img/user.jpg" class="fotusi">
			<?php //} ?> -->
			<?php if ($val && file_exists($val[0]['fotcan'])) { ?>
				<img src="<?= $val[0]['fotcan']; ?>" class="fotusi">
			<?php } elseif ($val && file_exists($val['ndocusu'])) { ?>
				<img src="img/<?= $val['ndocusu']; ?>_.jpg" class="fotusi">
			<?php } elseif (file_exists("img/" . $dtA['idusu'] . "_.jpg")) { ?>
				<img src="img/<?= $val['idusu']; ?>_.jpg" class="fotusi">
			<?php } else { ?>
				<img src="img/user.jpg" class="fotusi">
			<?php } ?>
		</div>
	</div>

<?php } ?>


<div class="apk">
	<a href="https://senacda.com/sagi/apk/Sena%20CDA_1_1.0.apk">
		<img src="img/apkdown.png">
	</a>
</div>

<script>
	$('.slider').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		arrows: true,
		dots: false,
		centerMode: true,
		variableWidth: true,
		infinite: true,
		focusOnSelect: true,
		cssEase: 'linear',
		touchMove: true,
		prevArrow: '<button class="slick-prev"> < </button>',
		nextArrow: '<button class="slick-next"> > </button>',

		//         responsive: [                        
		//             {
		//               breakpoint: 576,
		//               settings: {
		//                 centerMode: false,
		//                 variableWidth: false,
		//               }
		//             },
		//         ]
	});


	// var imgs = $('.slider img');
	// imgs.each(function () {
	// 	var item = $(this).closest('.item');
	// 	item.css({
	// 		'background-image': 'url(' + $(this).attr('src') + ')',
	// 		'background-position': 'center',
	// 		'-webkit-background-size': 'cover',
	// 		'background-size': 'cover',
	// 	});
	// 	$(this).hide();
	// });</script>