<?php
require_once("models/mdoc.php");

$mdoc = new Mdoc();
$iddoc = isset($_REQUEST["iddoc"]) ? $_REQUEST["iddoc"] : NULL;
$radicado = isset($_POST["radicado"]) ? $_POST["radicado"] : NULL;
$rut = isset($_POST["rut"]) ? $_POST["rut"] : NULL;
$ruta_archivo = NULL;

$opera = isset($_REQUEST["opera"]) ? $_REQUEST["opera"] : NULL;
$datOne = NULL;

// Asignar iddoc al modelo
$mdoc->setIddoc($iddoc);

if($opera == "save") {
    $mdoc->setNumeroRadicado($radicado);
    $mdoc->setRut($rut);

    // Manejo de archivo solo si se sube uno nuevo
    if(isset($_FILES["archivo"]) && $_FILES["archivo"]["error"] == 0) {
        $nombreArchivo = uniqid() . "_" . $_FILES["archivo"]["name"];
        $ruta_archivo = "uploads/" . $nombreArchivo;
        move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta_archivo);
        $mdoc->setRutaArchivo($ruta_archivo);
    } else if($iddoc && $opera == "save") {
        // Si es edición y no se sube archivo, mantener el anterior
        $docActual = $mdoc->getOne();
        if($docActual && isset($docActual[0]["ruta_archivo"])) {
            $mdoc->setRutaArchivo($docActual[0]["ruta_archivo"]);
        }
    }

    if(!$iddoc) $mdoc->save(); else $mdoc->edit();
    $iddoc = NULL;
}

if($opera == "eli" && $iddoc) {
    $mdoc->del();
    $iddoc = NULL;
}

if($opera == "edi" && $iddoc) $datOne = $mdoc->getOne();

$datAll = $mdoc->getAll();
?>
