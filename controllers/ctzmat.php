<?php
// Incluir el modelo
require_once("models/mtzmat.php");

// Inicializar variables
$mtzmat = new Mtzmat();
$datAll = [];
$datOne = null;
$datPas = [];
$datUxf = [];
$error = "";

try {
    // Obtener datos para los selects
    $datPas = $mtzmat->getAllPas();
    $datUxf = $mtzmat->getAllUxf();
    
    // Procesar operaciones POST
    if ($_POST) {
        $opera = $_POST['opera'] ?? '';
        
        switch ($opera) {
            case 'save':
                // Validar datos requeridos
                if (empty($_POST['idpas']) || empty($_POST['iduxf']) || empty($_POST['fecreg'])) {
                    $error = "Todos los campos obligatorios deben ser completados.";
                    break;
                }
                
                // Setear datos en el modelo
                $mtzmat->setIdpas($_POST['idpas']);
                $mtzmat->setIduxf($_POST['iduxf']);
                $mtzmat->setFecreg($_POST['fecreg']);
                $mtzmat->setObstrm($_POST['obstrm'] ?? '');
                
                // Intentar guardar
                if ($mtzmat->save()) {
                    header("Location: home.php?pg=tzmat&msg=success");
                    exit;
                } else {
                    $error = "Error al guardar el registro.";
                }
                break;
                
            case 'edit':
                // Validar datos requeridos
                if (empty($_POST['idtrm']) || empty($_POST['idpas']) || empty($_POST['iduxf']) || empty($_POST['fecreg'])) {
                    $error = "Todos los campos obligatorios deben ser completados.";
                    break;
                }
                
                // Setear datos en el modelo
                $mtzmat->setIdtrm($_POST['idtrm']);
                $mtzmat->setIdpas($_POST['idpas']);
                $mtzmat->setIduxf($_POST['iduxf']);
                $mtzmat->setFecreg($_POST['fecreg']);
                $mtzmat->setObstrm($_POST['obstrm'] ?? '');
                
                // Intentar actualizar
                if ($mtzmat->edit()) {
                    header("Location: home.php?pg=tzmat&msg=updated");
                    exit;
                } else {
                    $error = "Error al actualizar el registro.";
                }
                break;
                
            case 'del':
                // Validar ID
                if (empty($_POST['idtrm'])) {
                    $error = "ID de registro no válido.";
                    break;
                }
                
                // Intentar eliminar
                if ($mtzmat->del($_POST['idtrm'])) {
                    header("Location: home.php?pg=tzmat&msg=deleted");
                    exit;
                } else {
                    $error = "Error al eliminar el registro.";
                }
                break;
        }
    }
    
    // Verificar si se está editando un registro específico
    if (isset($_GET['idtrm']) && !empty($_GET['idtrm'])) {
        $datOne = $mtzmat->getOne($_GET['idtrm']);
        if (!$datOne) {
            $error = "Registro no encontrado.";
        }
    }
    
    // Obtener todos los registros para mostrar en la tabla
    $datAll = $mtzmat->getAll();
    
} catch (Exception $e) {
    $error = "Error en el sistema: " . $e->getMessage();
}
?>