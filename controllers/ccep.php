<?php
require_once 'models/mcep.php';

$mcep = new Mcep();
$instructoresBase = $mcep->getInstructores();

foreach ($instructoresBase as $inst) {
    $inst['fichas'] = $mcep->getFichasPorInstructor($inst['idusu']);
    $instructores[$inst['idusu']] = $inst;
}
$instructores = array_values($instructores);


$opera = $_REQUEST['opera'] ?? null;
