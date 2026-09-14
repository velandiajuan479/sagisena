<?php
require_once 'models/mginst.php';

$usuario_id = $_REQUEST['idusu'] ?? $_SESSION["idusu"];
$ndocusu    = $_SESSION["idusu"];

$mginst = new Mginst();

$instructores           = $mginst->getInstructores();
$instructoresSeguimiento = $mginst->getInstructoresSeguimiento();
$fichas                 = $mginst->getFichas();
$programas = $mginst->getProgramasUnicos();
$insseg             = $mginst->getInsseg();

$opera = $_REQUEST['opera'] ?? null;
$instructor_id = $_POST['instructor_id'] ?? null;

if ($opera === 'convertir' && !empty($instructor_id)) {
    if ($mginst->convertirASeguimiento($instructor_id)) {
        echo "<script>alert('Instructor convertido exitosamente'); window.location.href='home.php?pg={$pg}';</script>";
        exit;
    } else {
        echo "<script>alert('Error al convertir el instructor');</script>";
    }
}

if ($opera === 'asignar_ficha') {
    $asignar_instructor_id = $_POST['asignar_instructor_id'] ?? null;
    $ficha_id = $_POST['ficha_id'] ?? null;
    $programa_completo = $_POST['programa_completo'] ?? null;

    if (!empty($asignar_instructor_id) && !empty($programa_completo)) {
        // Asignar todas las fichas del programa seleccionado
        $fichasDelPrograma = $mginst->getFichasPorPrograma($programa_completo);

        $contador = 0;
        foreach ($fichasDelPrograma as $ficha) {
            if (!$mginst->existeAsignacion($ficha['idfic'])) {
                $mginst->asignarFicha($asignar_instructor_id, $ficha['idfic']);
                $contador++;
            }
        }

        echo "<script>alert('Se asignaron {$contador} fichas del programa a este instructor'); window.location.href='home.php?pg={$pg}';</script>";
        exit;
    }

    // Asignar ficha individual
    if (!empty($asignar_instructor_id) && !empty($ficha_id)) {
        if ($mginst->existeAsignacion($ficha_id)) {
            echo "<script>alert('Esta ficha ya está asignada a un instructor');</script>";
            exit;
        }

        if ($mginst->asignarFicha($asignar_instructor_id, $ficha_id)) {
            echo "<script>alert('Ficha asignada exitosamente'); window.location.href='home.php?pg={$pg}';</script>";
            exit;
        } else {
            echo "<script>alert('Error al asignar la ficha');</script>";
        }
    } else {
        echo "<script>alert('Por favor, seleccione un instructor y una ficha o programa');</script>";
    }
}



if($opera === 'eliminar') {
    $idusu = $_REQUEST['idusu'] ?? null;
    if ($idusu) {
        $mginst->eliminarInsSeg( $_REQUEST['idficha']);
        echo "<script>alert('Instructor eliminado exitosamente'); window.location.href='home.php?pg={$pg}';</script>";
        exit;
    } else {
        echo "<script>alert('Error al eliminar el instructor');</script>";
    }
}

?>