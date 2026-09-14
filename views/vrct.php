<?php require_once('controllers/crct.php'); ?>

<?php if($act==true){ ?>
    <form action="index.php?pg=183" method="POST">
        <div class="row">
			<div class="form-group col-md-4 frob" id="go1">
				<div style="width: 200px;height: 20px;"></div>
			</div>
			<div class="form-group col-md-5 frob" id="go1" style="text-align: center;">
				<img src="img/logoSenaB.png" style="image-rendering: inherit;">
				<h4 style="color: #ffffff;">CAMBIAR CONTRASE&Ntilde;A</h4>
			</div>
			<div class="form-group col-md-4 frob" id="go1">
				<div style="width: 200px;height: 20px;"></div>
			</div>
			<div class="form-group col-md-5 frob" id="go1" style="text-align: center;">
				<label for="id" style="text-shadow: 0px 0px 8px #000;font-size: 15px;">Contrase&ntilde;a</label>
				<input required="" type="password" class="form-control form-control-sm" id="pas1" maxlength="100" name="pas1" style="font-size: 15px;">
			</div>
			<div class="form-group col-md-4 frob" id="go1">
				<div style="width: 200px;height: 20px;"></div>
			</div>
			<div class="form-group col-md-5 frob" id="go1" style="text-align: center;">
				<label for="id" style="text-shadow: 0px 0px 8px #000;font-size: 15px;">Repita Contrase&ntilde;a</label>
				<input required="" type="password" class="form-control form-control-sm" id="pas2" maxlength="100" name="pas2" style="font-size: 15px;">
			</div>
			<div class="form-group col-md-4 frob" id="go1">
				<div style="width: 200px;height: 20px;"></div>
			</div>
			<div class="form-group col-md-5 frob" id="go1" style="text-align: center;">
				<input type="hidden" name="idusu" value="<?=$datAll['idusu'];?>">
        	    <input type="hidden" name="ope" value="AcTuAl">
        		<input type="submit" style="cursor: pointer;background-color: #fff;color: #117f09;padding: 15px 100px;border-radius: 15px;font-weight: bold;" onclick="return validadat();" value="Cambiar">
			</div>
		</div>
    </form>
<?php } ?>