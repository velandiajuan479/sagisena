<?php
require_once('models/mrvo.php');

$mrvo = new Mrvo();

$fidcen = isset($_POST['fidcen']) ? $_POST['fidcen']:NULL;
$fidjor = isset($_POST['fidjor']) ? $_POST['fidjor']:NULL;

$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;


$djor = $mrvo->getJor();
$dcen = $mrvo->getCen();
$dat=null;
if($fidcen && $fidjor){
    $mrvo->setFidjor($fidjor);
    $mrvo->setFidcen($fidcen);
    $dat = $mrvo->selAll();
}

?>