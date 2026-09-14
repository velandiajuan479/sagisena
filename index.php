<?php
date_default_timezone_set('America/Bogota');
$ano = date("Y");
session_start();
 ?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>SENA CDA - Chía</title>
	<link rel="shortcut icon" href="img/favicon.png">


	<!-- jQuery primero -->
	<script src="./js/jquery-3.5.1.min.js"></script>

	<!-- jQuery UI CSS y JS -->
	<link rel="stylesheet" href="./css/jquery-ui.css">
	<script src="./js/jquery-ui.min.js"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="./font/fontawesome/css/all.min.css">
	<script src="./font/fontawesome/js/all.min.js"></script>

	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="./css/dataTables.bootstrap5.min.css">

	<link rel="stylesheet" href="./css/animate.min.css" />

	<!-- Custom Styles -->
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/carne.css">

	<!-- Bootstrap JS -->
	<script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

	<!-- Alerts Mensajes Pantalla -->
	<script src="js/sweetalert2.all.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/sweetalert2.min.css">

	<!-- DataTables JS -->
	<script src="./js/jquery.dataTables.min.js"></script>
	<script src="./js/dataTables.bootstrap5.min.js"></script>

	<!-- Tus scripts personalizados -->
	<script src="./js/valida.js"></script>
	<script src="./js/java.js"></script>

	<!-- <script src="http://www.littlewebthings.com/projects/countdown/demo/js/jquery.lwtCountdown-1.0.js"></script> -->
</head>


<body>
	<header class="cabecera-sena">
		<?php
		require_once('models/conexion.php');
		require_once('controllers/optimg.php');
		require_once('controllers/ccof.php');
		$pg = isset($_GET['pg']) ? $_GET['pg'] : NULL;
		$id = isset($_GET['id']) ? $_GET['id'] : NULL;
		$nu = 2;
		$alto = "0px";
		require_once 'controllers/titulo.php';
		$dmd = new conexion();
		$datmd = $dmd->getOneModV();

		if (isset($_SESSION['idusu']) && $pg !== "205") {
			header("Location: mod.php");
			exit;
		}


		?>
		<div class="cabecera-contenido">
			<section class="cabecera-contendo__bx-logs">
				<img class="logo-sena" src="<?php if ($val)
					echo "img/" . $val[0]['logcof'] ?>" style="image-rendering: inherit;">
				<?php if ($datmd[0]['actmod'] == 8) { //1){ ?>
					<div id="btnini" class="btninp">
						<a href="index.php?pg=102" style="color: #fff;text-decoration: none;">
							<i class="fa-solid fa-users"></i> Candidatos
						</a>
					</div>
				<?php } ?>
				<div class="texto-sena">
					<?php if ($val) {
						$partes = explode(" ", $val[0]['titcof']); ?>
						<h3>
							<span class="palabra1"><?= $partes[0] ?></span>
							<span class="palabra2"><?= $partes[1] ?></span>
						</h3>
					<?php } ?>
				</div>
			</section>
			<!-- Carrusel -->
			<div class="carrusel-sena">
				<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<img src="img/index1.jpg" class="d-block w-100" alt="Imagen 1">
						</div>
						<div class="carousel-item">
							<img src="img/index2.jpg" class="d-block w-100" alt="Imagen 2">
						</div>
						<div class="carousel-item">
							<img src="img/index3.jpg" class="d-block w-100" alt="Imagen 3">
						</div>
					</div>
				</div>
			</div>
			<?php if ($pg != 205) { ?>
				<div id="btnini" class="btninp" style="cursor: pointer;" onclick="ingreso(1);">
					<i class="fa-solid fa-user"></i> Sesión
				</div>
			<?php } ?>
			<div class="veen" id="venflo">
				<div class="wrapper">
					<div id="btnini" onclick="ingreso(0);">
						<br><i class="fa-solid fa-xmark fa-2x" title="Cerrar"></i><br>
					</div>
					<?php
					require_once "views/vini.php";
					require_once "views/vreg.php";
					require_once "views/volv.php";
					?>
				</div>
			</div>
		</div>
	</header>
	<section class="container">
		<?php
		if (!$pg or $pg == 100) {
			require_once "views/vmcon.php";
		} else if ($pg == 101) {
			require_once "views/vcar.php";
			/*}else if($pg==102){
																											 include ("views/vmcan.php"); */
		} else if ($pg == 103) {
			include("views/vini.php");
		} else if ($pg == 205) {
			include("views/vvid.php");
		} else if ($pg == 183) {
			include("views/vrct.php");
		} else if ($pg == 1325) {
			include("views/vres2.php");
		}
		?>
		<script type="text/javascript">ingreso(0);</script>
	</section>
	<?php require_once "views/vfooter.php"; ?>
</body>

</html>