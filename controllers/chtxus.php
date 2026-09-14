<?php
require_once 'models/comp.php';
require_once 'models/mhtxus.php';

$con = (new conexion())->get_conexion();

$nomusu = isset($_GET['nomusu']) ? $_GET['nomusu'] : null;

$mhdt = new Mhdt($con);
$datAll = $mhdt->buscarNorad($nomusu);

// Métodos

?>