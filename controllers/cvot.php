<?php
    require_once ('models/mvot.php');

    $mvot = new Mvot();
   
    $idusu = isset($_SESSION['idusu']) ? $_SESSION['idusu'] :NULL;
    $idval= isset($_REQUEST['idval']) ? $_REQUEST['idval']:NULL;
    $canusu= isset($_POST['canusu']) ? $_POST['canusu']:NULL;
    $dtvot= date("Y-m-d H:i:s");

    $opera=isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;
    $pg = 1203;

    $mvot->setIdusu($idusu);
    

$yaVoto = $mvot->getOne();
if ($yaVoto && $yaVoto[0]['co'] > 0) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Ya ejerciste tu derecho al voto para representante',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = 'home.php?pg=1200';
        });
    </script>";
    exit();
}

if ($opera == "save") {
    $mvot->setCanusu($_POST['canusu']);
    $mvot->setDtvot(date("Y-m-d H:i:s"));
    
    if ($mvot->save()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Voto para representante registrado con éxito',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'home.php?pg=1200';
            });
        </script>";
        exit();
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ya has votado por representante o hubo un problema',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'home.php?pg=1203';
            });
        </script>";
        exit();
    }
}

$jorn = $mvot->getOneJor();
if($jorn && $jorn[0]['jornada']) $jorn = $jorn[0]['jornada'];
else $jorn=1;
$dat=$mvot->getAll($jorn);

?>