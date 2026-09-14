<?php require_once 'controllers/cusu.php'; ?>

<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i> Usuarios"); ?> 
	
	<div class="inser">
		<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="form-group col-md-6">
					<label for="ndocusu">No. Documento</label>
					<input type="number" name="ndocusu" id="ndocusu" class="form-control" required value="<?php if($datOne) echo $datOne[0]['ndocusu']; ?>">
				</div>
				<div class="form-group col-md-6">
					<label for="nomusu">Nombre</label>
					<input type="text" name="nomusu" id="nomusu" maxlength="70" class="form-control" required value="<?php if($datOne) echo $datOne[0]['nomusu']; ?>">
				</div>
				<div class="form-group col-md-6">
					<label for="idper">Perfil</label>
					<select name="idper" id="idper" class="form-select">
						<?php
						if($dpe){
							foreach ($dpe as $de) {
						?>
								<option value="<?=$de['idper'];?>" 
									<?php if($datOne && $datOne[0]['idper']==$de['idper']) echo " selected "; ?>
								><?=$de['nomper'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="rhusu">RH</label>
					<select name="rhusu" id="rhusu" class="form-select">
						<?php
						if($rhs){
							foreach ($rhs as $de) {
						?>
								<option value="<?=$de['idval'];?>" 
									<?php if($datOne && $datOne[0]['rhusu']==$de['idval']) echo " selected "; ?>
								><?=$de['nomval'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="tdousu">Tipo de Documento</label>
					<select name="tdousu" id="tdousu" class="form-select">
						<?php
						if($tdos){
							foreach ($tdos as $de) {
						?>
								<option value="<?=$de['idval'];?>" 
									<?php if($datOne && $datOne[0]['tdousu']==$de['idval']) echo " selected "; ?>
								><?=$de['nomval'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="genusu">Género</label>
					<select name="genusu" id="genusu" class="form-select">
						<?php
						if($gen){
							foreach ($gen as $de) {
						?>
								<option value="<?=$de['idval'];?>" 
									<?php if($datOne && $datOne[0]['genusu']==$de['idval']) echo " selected "; ?>
								><?=$de['nomval'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-6">
                    <label for="fotcan">Foto</label>
                    <?php if($datOne AND $datOne[0]['fotcan']){ ?>
                        <input type="hidden" name="fotcan" id="fotcan" value="<?=$datOne[0]['fotcan'];?>">
                    <?php } ?>
                    <input type="file" name="foto" id="fotcan" class="form-control" accept="image/png, image/jpeg">
                </div>
				<div class="form-group col-md-6">
					<label for="pasusu">Contraseña</label>
					<input type="password" name="pasusu" id="pasusu" maxlength="70" class="form-control" <?php if(!$datOne) echo "required"; ?>>
				</div>
				<div class="form-group col-md-6">
					<label for="nomusu">Email</label>
					<input type="text" name="emausu" id="emausu" maxlength="70" class="form-control" value="<?php if($datOne) echo $datOne[0]['emausu']; ?>">
				</div>
				<div class="form-group col-md-6">
					<label for="nomusu">Teléfono</label>
					<input type="number" name="telcan" id="telcan" max="9999999999999" class="form-control" value="<?php if($datOne) echo $datOne[0]['telcan']; ?>">
				</div>
				<div class="form-group col-md-6">
					<label for="noca">No. Candidato</label>
					<input type="text" name="noca" id="noca" maxlength="3" class="form-control" value="<?php if($datOne) echo $datOne[0]['noca']; ?>">
				</div>
				<div class="form-group col-md-6">
					<label for="idcen">Centro de Formación</label>
					<select name="idcen" id="idcen" class="form-select">
						<?php
						if($dce){
							foreach ($dce as $de) {
						?>
								<option value="<?=$de['idcen'];?>"
									<?php if($datOne && $datOne[0]['idcen']==$de['idcen']) echo " selected "; ?>
								><?=$de['nomcen'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="actusu">¿Activo?</label>
					<select name="actusu" id="actusu" class="form-select">
						<option value="1"
							<?php if($datOne && $datOne[0]['actusu']==1) echo " selected "; ?>
						>Si</option>
						<option value="2"
							<?php if($datOne && $datOne[0]['actusu']==2) echo " selected "; ?>
						>No</option>
						<option value="3"
							<?php if($datOne && $datOne[0]['actusu']==3) echo " selected "; ?>
						>desertó</option>
					</select>
				</div>
				<div class="form-group col-md-6">
					<br>
					<input type="submit" class="btn btn-primary" value="<?php if($datOne) echo "Actualizar"; else echo "Registrar"; ?>">
					<input type="hidden" name="opera" value="save">
					<input type="hidden" name="idusu" value="<?php if($datOne) echo $datOne[0]['idusu']; ?>">
				</div>
			</div>
		</form>
	</div>
</div>

<br><br>
<h3>Ver usuarios</h3>

<form name="frm1" action="home.php?pg=<?=$pg;?>" method="POST">
	<div class="row">
		<div class="form-group col-md-6">
			<label for="txt">Ingrese No. de Documento o ficha a buscar</label>
			<input type="text" name="nodocfil" id="txt" class="form-control" onchange="this.form.submit();">
		</div>
		<div class="form-group col-md-6">
			<label for="combobox">Seleccione Ficha a buscar</label>
			<select name="idficfil" id="combobox" class="form-select" onchange="this.form.submit();">
				<option value="0"></option>
				<?php
				if($dfi){
					foreach ($dfi as $de) {
				?>
						<option value="<?=$de['idfic'];?>"
							<?php if($datOne && $datOne[0]['idfic']==$de['idfic']) echo " selected "; ?>
						><?=$de['idfic'];?> - <?=$de['nomfic'];?> <?=$de['nomval'];?></option>
				<?php }} ?>
			</select>
		</div>
	</div>
</form>


<?php
$idusuanterior=NULL;
	if($dat){
?>
	<table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Perfil</th>
                <th>Foto</th>
                <th>Usuario</th>
                <th>E-mail</th>
                <th>No. Telefónico</th>
                <th>Activo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
				foreach($dat AS $d){
					if($idusuanterior != $d['idusu']){
			?>
	            <tr>
	                <td>
	                	<?=$d['nomper'];?>
	                </td>
	                <td>
	                	<?php if(file_exists($d['fotcan'])){ ?>
	                		<img src="<?=$d['fotcan'];?>" width="70px"; />
	                	<?php }else{ ?>
	                		<img src="img/user.jpg" width="70px"; />
	                	<?php } ?>
	                </td>
	                <td>
	                	<?=$d['nomusu'];?><br>
	                	<small>
		                	<strong>No. Documento:</strong> <?=$d['ndocusu'];?><br>
		                	<?php 
							$musu -> setIdusu($d['idusu']);
							$total = $musu -> getTficha();
							$datosfic = $musu -> getNomFics();
								foreach($datosfic as $dc){?>
									<strong>Ficha:</strong> <?=$dc['idfic'];?><strong> Nombre:</strong> <?=$dc['nomfic'];?> <br>
								<?php } ?>
		                		
		                	<?php } ?>
		                	<?=$d['nomcen'];?>
		                </small>
	                </td>
	                <td>
	                    <?=$d['emausu'];?>
	                </td>
	                <td>
	                    <?=$d['telcan'];?>
	                </td>
	                <td>
	                	<?php if($d['actusu']==1){ ?>
		                	<i class= "fa fa-solid fa-circle-check fa-2x"></i>
		                <?php }else{ ?>
		                	<i class="fa fa-solid fa-circle-xmark fa-2x" style="color:#f00;"></i>
		                <?php } ?>
	                </td>
	                <td style="text-align:right;">
						<i class="fa-solid fa-address-card fa-2x" title="Perfiles de Usuario" data-bs-toggle="modal" data-bs-target="#muxp<?=$d['idusu'];?>"></i>
						<?php echo modmulsel($d['idusu'], $d['nomusu'],$pg); ?>
						<i class="fa-solid fa-credit-card fa-2x" title="Fichas de Ususario" data-bs-toggle="modal" data-bs-target="#muxf<?=$d['idusu'];?>"></i>
						<?php echo modsimsel($d['idusu'], $d['nomusu'],$pg); ?>
						<a href="home.php?pg=<?=$pg;?>&idusu=<?=$d['idusu'];?>&opera=edit" title="Actualizar">
	                		<i class="fa-solid fa-pen-to-square fa-2x"></i>
	                	</a>
						<a href="home.php?pg=<?=$pg;?>&idusu=<?=$d['idusu'];?>&opera=Eliminar" title="Eliminar" onclick="return eli(this);">
	                		<i class="fa-solid fa-trash-can fa-2x"></i>
	                	</a>
							<?php if(isset($_GET['msg']) && $_GET['msg'] == 'eliminado'): ?>
                <script>
                    Swal.fire('¡Eliminado!', 'El registro se eliminó correctamente.', 'success');
                </script>
                <?php endif; ?>


	                </td>
	            </tr>
	            <?php 
				$idusuanterior = $d['idusu'];
				} ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Perfil</th>
                <th>Foto</th>
                <th>Usuario</th>
                <th>E-mail</th>
                <th>No. Telefónico</th>
                <th>Activo</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
<?php } ?>


<br><br>
<h3>Cambiar contraseñas masivas</h3>
<div style="float: left;width: 100%;padding: 0px 10px 100px 10px;">
	<div class="inser">
		<form name="frm5" action="home.php?pg=<?=$pg;?>" method="POST">
			<div class="row">
				<div class="form-group col-md-6">
					<label for="inico">Inicio Contraseña</label>
					<input type="text" name="inico" id="inico" maxlength="5" class="form-control">
				</div>
				<div class="form-group col-md-6">
					<label for="finco">Fin contraseña</label>
					<input type="text" name="finco" id="finco" maxlength="5" class="form-control">
				</div>
				<div class="form-group col-md-6">
					<br>
					<input type="submit" class="btn btn-primary" onclick="return eliminar();" value="Cambiar contraseñas">
					<input type="hidden" name="opera" value="CamCon">
				</div>
			</div>
		</form>
	</div>
</div>

