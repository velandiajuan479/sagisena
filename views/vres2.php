<?php
include "controllers/cres2.php";
?>

<?php if($usuVen): ?>
<div id="overlayVencido" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; display: flex; align-items: center; justify-content: center; pointer-events: none;">
    <h1 style="margin: 0; font-size: 250px; font-weight: bold; color: #dc3545; text-shadow: 5px 5px 15px rgba(0,0,0,0.5); opacity: 0.8; text-align: center; line-height: 0.9;">VENCIDO</h1>
</div>
<?php endif; ?>

<?php if(!$ndocusu){ ?>
	<form class="m-tb-40" action="<?=$direc;?>" method="POST" enctype="multipart/form-data">
		<div class="row">
			<div class="form-group col-md-12 frob" id="go1" style="text-align: center;">
				<img src="img/logo.png" style="width: 150px;margin-top: 120px;">
			</div>
			<div class="form-group col-md-3 frob" id="go1" style="text-align: center;">
			</div>
			<div class="form-group col-md-6 frob" id="go1" style="text-align: center;">
				<br><br>
				<label for="ndocusu" style="font-size: 15px;color: #000;">Número de documento</label>
				<input type="text" class="form-control form-control-sm" id="ndocusu" maxlength="100" name="ndocusu" style="font-size: 15px;" required onchange="this.form.submit();" autofocus />
				<input type="hidden" name="ope" value="save">
			</div>
			<div class="form-group col-md-3 frob" id="go1" style="text-align: center;">
			</div>
		</div>
	</form>
<?php }elseif($ndocusu){ ?>
	<div class="row" style="margin-left: 4%; display: flex;align-items: center;">
		<div class="form-group <?php echo (!$dtUds) ? 'col-md-9' : 'col-md-12'; ?> frob ajuies" style="margin-top: 0px;" id="go1" >
			<form class="m-tb-40" name="myForm" action="<?=$direc;?>" method="POST" enctype="multipart/form-data">
				<div class="row">
					<div class="form-group col-md-12" id="go1" style="text-align: center;">
						<big><span class="tnom" style="text-shadow: 0px 0px 4px #000, 0px 0px 4px #000, 0px 0px 4px #000, 0px 0px 4px #000, 0px 0px 4px #000;font-size: 30px;color: #4eac31;">
						<?php if(!$rdtT){ $txtsty="opacity: 1;"; $ajuimg="70px"; ?>
							INGRESO
						<?php }else{ $txtsty="opacity: 0;"; $ajuimg="20px"; ?>
							<span style="font-size:34px;">SALIDA</span>
							
							<script type="text/javascript">
								setTimeout(function() {
									const form = document.forms['myForm'];
									if (form) {
										form.submit();
									}
								}, 1000);
							</script>
						<?php } ?>
						</span></big>
						<?php if($msjerr){ ?>
							<br>
							<div class="boxven parpadea"><?=$msjerr;?></div>
						<?php } ?>
					</div>
					<div class="form-group col-md-12" id="go1" style="text-align: center;">
						<span class="tnom">
							<?php if(!$dtUds){ ?>
								<input type="hidden" name="idusu" value="<?=$val[0]['idusu'];?>" >
								<?=$val[0]['nomusu'];?>
								<br>
    							C.C. <?php if($ndocusu) echo number_format($ndocusu, 0, ',', '.');?>
							<?php }else{ ?>
								USUARIO NO REGISTRADO
								<br><br>
        						<a href="index.php?pg=1325">
        							<button type="button" class="btn btn-primary" style="font-size: 20px;width: 100%;background-color: #00af00; margin-bottom: 2%;">Volver a Registrar</button>
        						</a>
							<?php } ?>
						</span>
					</div>
				
					<div class="form-group col-md-12" id="go1" style="text-align: center;color: #4eac31;">
						<label for="fechos" style="font-size: 15px;color: #4eac31;">Fecha y Hora</label>
						<br>
						<big><big>
							<?=$fechos;?>
						</big></big>
						<input type="hidden" name="ope" value="edi">
					</div>
				</div>
				<?php if(!$dtUds){ ?>
					<?php if(!$rdtT){ ?>
					<?php } ?>
				<?php }else{ ?>
					<div class="form-group col-md-6" id="go1" style="margin: 0 auto;">
						<a href="home.php?pg=1325">
							<button type="button" class="btn btn-primary" style="font-size: 20px;width: 100%;background-color: #00af00; margin-bottom: 2%;">Volver Al Inicio</button>
						</a>
					</div>
					<div class="form-group col-md-6" id="go1" style="margin: 0 auto;">
						<p style="font-size: 28px; color: #000; font-weight: bold; text-align: center;">Por favor acercarse a la biblioteca para crear el usuario. Gracias</p>
						<input type="hidden" name="dtUds" value="<?=$dtUds;?>">
					</div>
				<?php } ?>		
				<div class="row">
					<?php if($datEle){ foreach($datEle AS $dEl){ ?>
						<div class="form-group col-md-3" id="go1" style="margin: 0 auto;color: #000; left: 10px;">
							<?php if(file_exists($dEl['rutfot'])){ ?>
				                <img src="<?=$dEl['rutfot'];?>" class="fotele">
				            <?php } else{ ?>
				                <img src="img/user.jpg" class="fotele">
				            <?php } ?>
				            <input type="checkbox" name="ideles[]" checked style="width: 25px;height: 25px;margin-top: -25px;display: block;position: absolute;z-index: 1;" value="<?=$dEl['idele'];?>">
				            <br>
				            <strong><?=$dEl['nomele'];?></strong> 
				            <small><?=$dEl['nidele'];?></small>
						</div>
					<?php }} ?>
				</div>
			</form>
		</div>
		<?php if(!$dtUds){ ?>
			<div class="form-group col-md-3" id="go1" style="text-align: center;margin-top: <?=$ajuimg;?>">
				<?php if($val && file_exists($val[0]['fotcan'])){ ?>
					<img src="<?=$val[0]['fotcan'];?>" class="fotusi">
				<?php }else{ ?>
					<img src="img/user.jpg" class="fotusi">
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<?php if(!$dtUds && !$rdtT){ ?>
		<script type="text/javascript">
			setTimeout(function() {
				document.forms['myForm'].submit();
			}, 1000);
		</script>
	<?php } ?>
<?php } ?>