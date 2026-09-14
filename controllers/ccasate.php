<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/mcasate.php');
$mcasate = new Mcasate();

$idsop = isset($_REQUEST['idsop']) ? $_REQUEST['idsop'] : NULL;
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

if (isset($_POST['ajax']) && $_POST['ajax'] == 'asignar_usuario') {
    $idsop = $_POST['idsop'];
    $idusu = $_SESSION['idusu'];

    $mcasate->setIdsop($idsop);
    $mcasate->setIdusu($idusu);
    $mcasate->asignarSoporteAUsuario();

    echo json_encode(['success' => true, 'idusu' => $idusu]);
    exit;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $pg = isset($_GET['pg']) ? $_GET['pg'] : 'default value';
    echo ("<script>window.location.href = 'home.php?pg=".$pg."'</script>");
}

$dat=$mcasate->getAll();
?>