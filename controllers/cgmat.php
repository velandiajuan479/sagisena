<?php
require_once ("models/mgmat.php");

$mgmat = new Mgmat();

$datId = $mgmat -> getId();
$datIdusu = $mgmat -> getIdusu();
$datIdpag = $mgmat -> getIdpag();
$datIdcen = $mgmat -> getIdcen();
$datIdhor = $mgmat -> getIdhor();
$datIdasp = $mgmat -> getIdasp();
$datIdfic = $mgmat -> getIdfic();
$datCodpro = $mgmat -> getCodpro();
$datFechaCreacion = $mgmat -> getFechaCreacion();
$datFechaActualizacion = $mgmat -> getFechaActualizacion();
$datEstado = $mgmat -> getEstado();

?>