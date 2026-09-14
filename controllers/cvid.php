<?php
require_once 'models/mpag.php';

$mpag = new Mpag();
$datOne = NULL;
$vid = isset($_GET['vid']) ? $_GET['vid']:NULL;

$mpag->setIdpag($vid); 
if($vid)
    $datOne = $mpag->getOne();
?>