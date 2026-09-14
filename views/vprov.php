<?php require_once 'controllers/cpvs.php';
$vis = isset($_REQUEST['vis']) ? $_REQUEST['vis'] : NULL;
if ($vis == "OK") {
}
?>
<div class="conte">
	<?php echo titulo2("<i class='" . $icono . "'></i> Propuesta Voceros", 2); ?>
	<?php ?>
	<div class="Insertar">
		<div style="float: left;">
			<?php if (!$dus[0]['fotcan']) {
				?><img src="image/usuario.png" style="width: 90%;">
				<?php
			} else { ?>
				<img src="<?= $dus[0]['fotcan'] ?>" style="width: 200px;margin: 0 0 10px 10px;"></a>
				<?php
			}
			?>
		</div>
		<div class="row" style="float: left;width: 83%; margin: 0 0 10px 10px;">
			<ul class="list-group col-md-9" style="float: right; width: 100%">
				<li class="list-group-item" style="text-align: right;">
					<h3 style="font-size: 40px;"><strong>
							<?= $dus[0]['noca']; ?>
						</strong></h3>
				</li>
				<li class="list-group-item">
					<h3>
						<?= $dus[0]['nomusu']; ?>
					</h3>
				</li>
				<li class="list-group-item">
					<?= $dus[0]['nomcen']; ?>
				</li>
				<li class="list-group-item">
					<?= $dus[0]['idfic']; ?> -
					<?= $dus[0]['nomfic']; ?>
				</li>
				<li class="list-group-item">Jornada:
					<?= $dus[0]['nomval']; ?>
				</li>
			</ul>
		</div>
	</div>
</div>

<div style="float: left;width: 100%;padding: 0px 10px 100px 10px;">
	<form name="frm1" action="home.php?pg=<?= $pg; ?>" method="POST">
		<div class="row">
			<?php
			if ($dvpr) {
				$n = 0;
				foreach ($dvpr as $dv) {
					if (!$dv['parval']) {
						?>
						<div class="form-group col-md-12">
							<label for="texpro">
								<?= $dv['nomval']; ?>
							</label>
							<textarea name="texpro[]" id="texpro" class="form-control" required <?php if ($vis and $vis == "OK")
								echo "readonly" ?>><?php if ($datOne)
								echo $datOne[$n]['texpro']; ?></textarea>
							<input type="hidden" name="idval[]" value="<?= $dv['idval']; ?>">
							<input type="hidden" name="npro[]" value="<?php if ($datOne)
								echo $datOne[$n]['npro']; ?>">
						</div>
						<?php
						$n++;
					} else {
						?>
						<div class="form-group col-md-12">
							<br>
							<h3><?= $dv['nomval']; ?></h3>
						</div>
						<?php
						$nr = explode(";", $dv['parval']);
						for ($o = 0; $o < count($nr); $o++) {
							?>
							<div class="form-group col-md-12">
								<label for="texpro">
									<?= $nr[$o]; ?>
								</label>
								<textarea name="texpro[]" id="texpro" class="form-control" style="height: 350px;" required <?php if ($vis and $vis == "OK")
									echo "readonly" ?>><?php if ($datOne)
									echo $datOne[$n]['texpro']; ?></textarea>
								<input type="hidden" name="idval[]" value="<?= $dv['idval']; ?>">
								<input type="hidden" name="npro[]" value="<?php if ($datOne)
									echo $datOne[$n]['npro']; ?>">
							</div>
							<?php
							$n++;
						}
					}
				}
			}
			?>
			<?php
			echo dbche("Condiciones", $dcon, $idusu, 2, $vis);
			echo dbche("Manifiesto", $dman, $idusu, 4, $vis);
			?>
			<div class="form-group col-md-6">
				<br>
				<input type="submit" class="btn btn-primary" value="<?php if($datOne) echo "Actualizar"; else echo "Registrar"; ?>">
				<input type="hidden" name="opera" value="Insertar">
				<input type="hidden" name="idusu" value="<?php if($dus) echo $dus[0]['idusu']; ?>">
			</div>
		</div>
	</form>
</div>