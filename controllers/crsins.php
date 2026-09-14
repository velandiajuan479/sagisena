<?php
// controllers/crsins.php
require_once 'models/mrsins.php';

$modelo = new Mrsins();

// lista de instructores (llenará el select)
$dtInstructores = $modelo->getInstructores();

// id del instructor seleccionado (viene por GET)
$idusu = isset($_GET['idusu']) && $_GET['idusu'] !== '' ? intval($_GET['idusu']) : null;

// resultados filtrados
$dtComRes = $modelo->getResultadosPorInstructorId($idusu);

// Opcional: otras variables que la vista usa (ej: $pg)
$pg = $_GET['pg'] ?? 'vrsins';

// llamar la vista (asegúrate de usar la ruta correcta)
require_once 'views/vrsins.php';
?>
