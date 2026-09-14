<?php
require_once("models/mhorc.php");
require_once("models/mhdt.php");
require_once("models/memp.php");
require_once("models/mccm.php");

// Inicializar variables
$idnorad = isset($_REQUEST["idnorad"]) ? $_REQUEST["idnorad"] : NULL;
$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera'] : NULL;

// Inicializar objetos
$mhorc = new Mhorc();
$mhdt = new Mhdt();
$memp = new Memp();
$mccm = new Mccm();

// Inicializar variables de datos
$datOne = [];
$datOneEmp = [];
$datOneHt = [];
$dtpro = [];
$datInsHdt = [];
$instructores = [];
$idemp_actual = null;

// Operación: Asignar Instructor
if ($opera == "AgrIns" && $idnorad) {
    $idinstructor = isset($_POST['idinstructor']) ? $_POST['idinstructor'] : NULL;
    if ($idinstructor) {
        $mhorc->asignarInstructor($idnorad, $idinstructor);
        echo "<script>alert('Instructor asignado exitosamente'); window.location.href='home.php?pg=2002&idnorad=" . $idnorad . "';</script>";
        exit;
    }
}

// Operación: Eliminar Instructor
if ($opera == "EliIns" && $idnorad) {
    $idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : NULL;
    if ($idusu) {
        $mhorc->eliminarInstructor($idnorad, $idusu);
        echo "<script>alert('Instructor retirado exitosamente'); window.location.href='home.php?pg=2002&idnorad=" . $idnorad . "';</script>";
        exit;
    }
}

// Operación: Guardar / Actualizar datos complementarios desde vhorc
if ($opera == "save" && $idnorad) {
    $idfic = isset($_POST["idfic"]) ? trim($_POST["idfic"]) : NULL;
    $codslem = isset($_POST["codslem"]) ? trim($_POST["codslem"]) : NULL;
    $convht = isset($_POST["convht"]) ? trim($_POST["convht"]) : NULL;

    $mhorc->updateComplementarios($idnorad, $idfic, $codslem, $convht);
    echo "<script>alert('Datos complementarios actualizados exitosamente'); window.location.href='home.php?pg=2002&idnorad=" . $idnorad . "';</script>";
    exit;
}

// Cargar datos si existe idnorad
if ($idnorad) {
    $mhdt->setIdnorad($idnorad);
    $datOneHt = $mhdt->getOne();

    if (!empty($datOneHt[0])) {
        $datOne = $datOneHt;

        // Cargar empresa asociada a la hoja de trabajo
        if (!empty($datOneHt[0]['idemp'])) {
            $memp->setIdemp($datOneHt[0]['idemp']);
            $datOneEmp = $memp->getOne();
        }

        // Cargar programa asociado a la hoja de trabajo
        if (!empty($datOneHt[0]['codpro'])) {
            $mccm->setCodpro($datOneHt[0]['codpro']);
            $dtpro = $mccm->getOne();
        }

        // Normalizar fechas para visualización
        if (isset($datOne[0]['feclini']) && isset($datOne[0]['feclin'])) {
            try {
                $feclini = new DateTime($datOne[0]['feclini']);
                $feclin = new DateTime($datOne[0]['feclin']);
                $datOne[0]['feclini'] = $feclini->format('Y-m-d');
                $datOne[0]['feclin'] = $feclin->format('Y-m-d');
            } catch (Exception $e) {
                // Mantener formato original si falla
            }
        }
    }

    // Cargar instructores asignados a esta hoja de trabajo
    $datInsHdt = $mhorc->getInstructoresByHoja($idnorad);
}

// Cargar catálogo de todos los instructores disponibles para asignación
$instructores = $mhorc->getAllInstructores();
?>
