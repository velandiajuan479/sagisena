<?php require_once("models/mrcpr.php"); 

$mrcpr = new Mrcpr();

$idusu = isset($_POST['idusu']) ? $_POST['idusu']:NULL;
$idcom = isset($_POST['idcom']) ? $_POST['idcom']:NULL;
$fecuxc = isset($_POST['fecuxc']) ? $_POST['fecuxc']:NULL;

$opera = isset($_POST['opera']) ? $_POST['opera']:NULL;
$datOne = NULL;


// if($opera == "save"){
// 	$mrcpr -> setIdusu($idusu);
// 	$mrcpr -> setIdcom($idcom);
// 	$mrcpr -> setFecha($fecuxc);
// 	$mrcpr -> save();
// }

$datUsu = $mrcpr -> getAllUsu();
$datCom = $mrcpr -> getAllCom();
$datAll = $mrcpr -> getAll();

?>

<?php //require_once("models/mrcpr.php"); ?>
<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i> Compromisos de los Aprendices",1); ?>
	<div class="inser">
		<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
			<div class="row">
				<div class="form-group col-md-6">
					<label for="idusu">Usuario</label>
					<select name="idusu" id="idusu" class="form-control form-select">
						<?php if($datUsu){ foreach($datUsu as $dt){ ?>
							<option value="<?=$dt["idusu"];?>"><?=$dt["nomusu"]." - ".$dt["ndocusu"];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="idcom">Compromiso</label>
					<select name="idcom" id="idcom" class="form-control form-select">
						<?php if($datCom){ foreach($datCom as $dt){ ?>
							<option value="<?=$dt["idcom"];?>"><?=$dt["p1com"];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="fecuxc">Fecha</label>
					<input type="date" name="fecuxc" id="fecuxc" class="form-control" value="<?=$fecuxc;?>">
				</div>
				<div class="form-group col-md-6">
					<br>
					<input type="submit" class="btn btn-primary" value="Enviar">
					<input type="hidden" name="opera" value="save">
					<input type="hidden" name="idusu" value="<?=$idusu;?>">
					<input type="hidden" name="idcom" value="<?=$idcom;?>">
				</div>
			</div>			
		</form>		
	</div>
</div>

<table id="example" class="table table-striped" style="width: 100%">
	<thead>
		<tr>
			<th>Usuario</th>
			<th>Compromiso</th>
			<th>Fecha</th>
		</tr>
	</thead>
	<tbody>
		<?php if($datAll){ foreach($datAll as $dt){ ?>
			<tr>
				<td>
					<?=$dt["idusu"]." ".$dt["nomusu"];?>
				</td>
				<td><?=$dt["idcom"]." ".$dt["p1com"];?></td>
				<td>
					<a href="index.php?opera=&id title="">
						<i class="fa-solid fa-pen-to-square fa-2x"></i>
					</a>
					<a href="index.php?opera=&id title="">
						<i class="fa-solid fa-trash-can fa-2x"></i>
					</a>
				</td>
			</tr>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Usuario</th>
			<th>Compromiso</th>
			<th>Fecha</th>
		</tr>
	</tfoot>	
</table>