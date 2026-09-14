<?php
require_once("models/seguridad.php");
$snomcc = isset($_SESSION["nomcc"]) ? $_SESSION["nomcc"] : NULL;
$ano = date("Y");
?>
<!DOCTYPE html>
<html class="menu">
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description"
		content="Sistema de Gestion Administrativa Integral SAGI automatiza y moderniza la creación y aprobación de cursos, gestión de horarios y agendas, entradas y salidas, inasistencias y otras funciones que simplifican el llenado de formatos, mejorando la precisión y reduciendo tiempos. Gestiona roles (instructores, administración educativa y aprobadores) con seguridad y control, mientras garantizas trazabilidad en cada etapa del proceso">
	<meta name="robots" content="index, follow">
	<link rel="shortcut icon" href="img/favicon.png">

	<!-- jQuery primero -->
	<script src="./js//jquery-3.5.1.js"></script>
	<script src="./js/jquery-ui.js"></script>
	<script src="./js/jquery-ui.min.js"></script>

	<!-- Bootstrap 5 JS -->
	<script src="./js/bootstrap.bundle.min.js"></script>

	<!-- DataTables CSS (con Bootstrap 5) -->
	<link href="./css/dataTables.bootstrap5.min.css" rel="stylesheet">

	<!-- Bootstrap 5 CSS -->
	<link href="./css/bootstrap.min.css" rel="stylesheet">

	<!-- DataTables JS -->
	<script src="./js/jquery.dataTables.min.js"></script>
	<script src="./js/dataTables.bootstrap5.min.js"></script>

	<!-- FontAwesome -->
	<link rel="stylesheet" href="./font/fontawesome/css/all.min.css">

	<script src="js/java.js"></script>

	<!-- DataTables exportación -->
	<script src="./js/dataTables.buttons.min.js"></script>
	<script src="./js/jszip.min.js"></script>
	<script src="./js/pdfmake.min.js"></script>
	<script src="./js/vfs_fonts.js"></script>
	<script src="./js/buttons.html5.min.js"></script>

	<link rel="stylesheet" href="./css/animate.min.css" />

	<link rel="stylesheet" href="css/style2.css">
	<link rel="stylesheet" href="css/menu.css">
	<title>SagiCDA <?= $ano; ?></title>
</head>

<body>
	<header>
		<?php
		$pg = isset($_REQUEST['pg']) ? $_REQUEST['pg'] : NULL;
		require_once('models/conexion.php');
		require_once("views/header.php");
		require_once('controllers/ccof.php');
		?>
	</header>
	<section class="contenido">
		<?php require_once('views/vpmod.php'); ?>
	</section>
	<footer>
	</footer>
</body>
<script type="text/javascript" src="js/valida.js"></script>

</html>