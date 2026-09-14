<?php
require_once('models/mvvc.php');


if (!isset($_SESSION['idusu']) || !isset($_SESSION['idper'])) {
    header("Location: index.php?error=sesion");
    exit();
}

$mvvc = new Mvvc();
$mvvc->setIdusu($_SESSION['idusu']);

// Perfiles permitidos 
$perfilesPermitidos = [3, 4, 8, 13, 22, 2];
if (!in_array($_SESSION['idper'], $perfilesPermitidos)) {
    header("Location: index.php?error=permiso");
    exit();
}


$yaVoto = $mvvc->getOne();
if ($yaVoto && $yaVoto[0]['co'] > 0) {
    echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'YA EJERCISTE TU DERECHO AL VOTO PARA VOCERO',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'home.php?pg=1200';
            });
          </script>";
    exit();
}

// obtiene todos los voceros 
$idfic = $mvvc->getFichaUsuario($_SESSION['idusu']);
$dat = $mvvc->getVocerosMismaFicha($idfic, $_SESSION['idusu']); 

// si no hay candidatos disponibles
if (empty($dat)) {
    die("<script>
            Swal.fire({
                icon: 'info',
                title: 'No hay candidatos disponibles para votar en tu ficha',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'home.php?pg=1200';
            });
         </script>");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opera']) && $_POST['opera'] === 'save') {
    if (!empty($_POST['canusu'])) {
        $mvvc->setCanusu($_POST['canusu']);
        $mvvc->setDtvot(date("Y-m-d H:i:s"));
        
        if ($mvvc->save()) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'VOTO PARA VOCERO REGISTRADO CON ÉXITO',
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
                        title: 'ERROR: Ya has votado por vocero o hubo un problema',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = 'home.php?pg=1202';
                    });
                  </script>";
            exit();
        }
    }
}
?>