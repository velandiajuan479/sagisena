<?php
require_once 'models/mcins.php';
require_once 'vendor/autoload.php'; // Para PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

// ------------------------------------------------------------
// 1️⃣ Captura de variables del request (tanto GET como POST)
// ------------------------------------------------------------
$idins  = $_REQUEST['idins']  ?? null;
$idres  = $_REQUEST['idres']  ?? null;
$idfic  = $_REQUEST['idfic']  ?? null;
$ope    = $_REQUEST['ope']    ?? null;
$nomins = $_POST['nomins']    ?? null; // Solo de POST para save
$pg     = $_REQUEST['pg']     ?? null; // Asumiendo default

$mensajeExito = null;
$error_message = null;

// ------------------------------------------------------------
// 2️⃣ Instanciar modelo
// ------------------------------------------------------------
$mcins = new Mcins();
$mcins->setIdins($idins);
$mcins->setIdres($idres);
$mcins->setIdfic($idfic);
$nomfic = $mcins->getNombreFicha($idfic);

// ------------------------------------------------------------
// 3️⃣ Procesar upload Excel (solo si POST y archivo válido)
// ------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['EXCEL']) && $_FILES['EXCEL']['error'] === UPLOAD_ERR_OK) {
    $archivoExcel = $_FILES['EXCEL']['tmp_name'];

    $spreadsheet = IOFactory::load($archivoExcel);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    $modelo = new Conexion(); // Asumiendo que existe esta clase
    $conexion = $modelo->get_conexion();
    $conexion->beginTransaction();

    try {
        // 🔹 1. Nombre del instrumento (celda A1)
        $nomins = trim($rows[0][0] ?? '');
        if (empty($nomins)) {
            throw new Exception("El nombre del instrumento en la celda A1 está vacío.");
        }

        // 🔹 2. Insertar instrumento
        $stmtIns = $conexion->prepare("
            INSERT INTO inseva (nomins, idres, idfic)
            VALUES (:nomins, :idres, :idfic)
        ");
        $stmtIns->bindParam(':nomins', $nomins);
        $stmtIns->bindParam(':idres', $idres);
        $stmtIns->bindParam(':idfic', $idfic);
        $stmtIns->execute();

        $idins = $conexion->lastInsertId();

        // 🔹 3. Leer criterios del Excel (resto igual)
        $criterios = [];
        $total_vscum = 0;

        foreach ($rows as $index => $row) {
            if ($index < 2) continue; // Omitir título y encabezado
            $nomcri = trim($row[0] ?? '');
            $vscum  = intval($row[1] ?? 0);
            $vpar   = intval($row[2] ?? 0);
            $vncum  = intval($row[3] ?? 0);

            if (empty($nomcri)) continue;

            $criterios[] = [
                'nomcri' => $nomcri,
                'vscum'  => $vscum,
                'vpar'   => $vpar,
                'vncum'  => $vncum
            ];

            $total_vscum += $vscum;
        }

        // 🔹 4. Validar suma de “Si Cumple”
        if ($total_vscum !== 100) {
            throw new Exception("⚠️ La suma de 'Si Cumple' debe ser 100. Actualmente suma: $total_vscum");
        }

        // 🔹 5. Insertar criterios (resto igual)
        $stmtCri = $conexion->prepare("
            INSERT INTO criterio (nomcri, vscum, vpar, vncum, idins)
            VALUES (:nomcri, :vscum, :vpar, :vncum, :idins)
        ");
        foreach ($criterios as $c) {
            $stmtCri->bindParam(':nomcri', $c['nomcri']);
            $stmtCri->bindParam(':vscum',  $c['vscum']);
            $stmtCri->bindParam(':vpar',   $c['vpar']);
            $stmtCri->bindParam(':vncum',  $c['vncum']);
            $stmtCri->bindParam(':idins',  $idins);
            $stmtCri->execute();
        }

        $conexion->commit();
        $mensajeExito = "✅ Instrumento '$nomins' importado correctamente.";

    } catch (Exception $e) {
        $conexion->rollBack();
        $error_message = "❌ Error al importar: " . $e->getMessage();
    }
}

// ------------------------------------------------------------
// 4️⃣ Guardar o editar manualmente (solo si POST y ope=save)
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ope == "save") {
    $mcins->setNomins($nomins);

    if ($idins) {
        // Editar
        $mcins->edit();
        $mensajeExito = "Instrumento editado correctamente.";
    } else {
        // Crear nuevo
        if ($mcins->existsResultado()) {
            $mcins->save();
            $mensajeExito = "Instrumento creado correctamente.";
        } else {
            $error_message = "Error: El resultado no existe.";
        }
    }
}

// ------------------------------------------------------------
// 5️⃣ Eliminar instrumento (solo si GET/POST y ope=del)
// ------------------------------------------------------------
if ($ope == "del" && $idins) {
    $mcins->setIdins($idins);
    $mcins->del();
    $mensajeExito = "Instrumento eliminado correctamente.";
}

// ------------------------------------------------------------
// 6️⃣ Obtener datos para edición o listado
// ------------------------------------------------------------
$dtOne = null;
if ($ope == "edi" && $idins) {
    $mcins->setIdins($idins); // Asegurar que esté seteado
    $dtOne = $mcins->getOne();
}

// Listar todos los instrumentos del resultado
$dat = ($idres && !$error_message) ? $mcins->getByResultado() : [];

// ------------------------------------------------------------
// 7️⃣ Cargar vista (sin lógica aquí)
// ------------------------------------------------------------
require_once 'views/vcins.php';
?>
