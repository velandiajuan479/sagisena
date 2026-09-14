<?php
require_once 'models/mhdt.php';
require_once 'models/mhorc.php';
require_once 'models/mchdt.php';
require_once 'vendor/autoload.php';

$icono = 'fa-solid fa-file-arrow-up';

use PhpOffice\PhpSpreadsheet\IOFactory;

class cchdt {
    public $mensaje = "";
    public $datosExcel = [];

    public function CargarExcel() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mchdt = new mchdt();
            $ruta = $mchdt->guardarArchivo($_FILES['archivo']['tmp_name'], $_FILES['archivo']['name']);
            if ($ruta) {
                try {
                    $spreadsheet = IOFactory::load($ruta);
                    $sheet = $spreadsheet->getActiveSheet();
                    $this->datosExcel = $sheet->toArray();
                    $this->mensaje = "<div class='alert alert-success col-4 mt-3'>Archivo subido correctamente.</div>";
                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), 'Unable to identify a reader for this file') !== false) {
                        $this->mensaje = "<div class='alert alert-danger col-4 mt-3'>El archivo seleccionado no es un archivo Excel válido.</div>";
                    }
                    $this->datosExcel = [];
                }
            }
        }
    }
}
?>