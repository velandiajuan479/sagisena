<?php

$pg = isset($_REQUEST["pg"]) ? $_REQUEST["pg"] : NULL;
if (!$pg or $pg == 1324) {
	$_SESSION["idper"] = 5;
	$_SESSION["pefnom"] = "Datos Básicos";
}
if (!$pg) {
	$_SESSION["pefnom"] = "Módulos";
}
?>
<div class="banusu">
	<div class="banint">
		<?php
		$nomcompleto = $_SESSION['nomusu'];
		$partes = explode(" ", $nomcompleto);
		$prinom = $partes[0];		
		if (count($partes) <=3) $priape = $partes[1];							
		else $priape = $partes[2];								
		echo $prinom." ".$priape;
		?>		
		<br>
		<small>
			<?= $_SESSION['pefnom']; ?>
		</small>
	</div>
	<?php $pg = isset($_REQUEST["pg"]) ? $_REQUEST["pg"] : NULL; ?>
	<div class="banint" style="margin-top: 5px; color: #29B265;">

		<a href="home.php?pg=1324" style="color: #29B265;">
			<i class="fa-solid fa-credit-card fa-2x" style="color: #ffffff;" title="Datos Personales"></i>
		</a>
	</div>
	<div class="banint" style="margin-top: 5px; color: #29B265;">
		<a href="views/vsal.php" style="color: #29B265;">
			<i class="fa-solid fa-power-off fa-2x" style="color: #ffffff;" title="Salir"></i>
		</a>
	</div>
</div>