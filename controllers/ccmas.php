<?php
include("models/mage.php");
include("vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\IOFactory;

$mage = new Mage();

if (isset($_POST['btnCmas']) && isset($_FILES['EXCEL_CMAS']) && $_FILES['EXCEL_CMAS']['name'] != '') {

    $dat = $_FILES['EXCEL_CMAS']['tmp_name'];

    $inputFileType = IOFactory::identify($dat);
    $objReader = IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($dat);

    $lastSheetIndex = $objPHPExcel->getSheetCount() - 1;
    $sheet = $objPHPExcel->getSheet($lastSheetIndex);
    $highestRow = $sheet->getHighestRow();

    $fase_actual = '';
    $actividad_actual = '';
    $competencia_actual = '';

    for ($row = 2; $row <= $highestRow; $row++) {
        $fase = trim($sheet->getCell("A" . $row)->getValue());
        $actividad = trim($sheet->getCell("B" . $row)->getValue());
        $competencia = trim($sheet->getCell("C" . $row)->getValue());
        $resultado = trim($sheet->getCell("D" . $row)->getValue());
        $duracion = trim($sheet->getCell("F" . $row)->getValue());
        $instructor = trim($sheet->getCell("K" . $row)->getValue());
        $fecha_inicio = trim($sheet->getCell("H" . $row)->getValue());
        $fecha_fin = trim($sheet->getCell("I" . $row)->getValue());

        if (!empty($fase)) $fase_actual = $fase;
        if (!empty($actividad)) $actividad_actual = $actividad;
        if (!empty($competencia)) $competencia_actual = $competencia;

        if (empty($competencia_actual) && empty($resultado)) continue;

        $mage->setIdfic(null);
        $mage->setFchinc($fecha_inicio ?: date("Y-m-d"));
        $mage->setFchfnl($fecha_fin ?: date("Y-m-d"));
        $mage->setCompetencia($competencia_actual);
        $mage->setResultado($resultado);
        $mage->setActividad($actividad_actual ?: 'Sin actividad');
        $mage->setDuracion($duracion ?: 0);
        $mage->setInstructor($instructor ?: 'Sin asignar');

        $mage->save();
    }

    $lastSheetName = $objPHPExcel->getSheet($lastSheetIndex)->getTitle();
    echo "<div class='alert alert-success'>Cargue completado correctamente desde la hoja: <strong>$lastSheetName</strong>.</div>";
}
?>
