<?php
require_once __DIR__ . '/../models/ccmusua.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class AspiranteController {
    private $model;

    public function __construct() {
        $this->model = new AspiranteModel();
    }

    public function mostrarFormulario($mensaje = '', $exito = false) {
        include __DIR__ . '/../views/vcmusua.php';
    }

    public function cargarMasivo() {
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
            try {
                $filePath = $_FILES['excel_file']['tmp_name'];
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                $headers = array_shift($rows);

                session_start();
                $_SESSION['excel_data'] = $rows;

                include __DIR__ . '/../views/vcmusua.php';
                return;
            } catch (Exception $e) {
                $mensaje = "❌ Error al procesar el archivo: " . $e->getMessage();
                $this->mostrarFormulario($mensaje, false);
                return;
            }
        } else {
            $mensaje = "❌ Error al subir el archivo.";
            $this->mostrarFormulario($mensaje, false);
        }
    }

    public function guardarMasivo() {
        session_start();
        $mensaje = '';
        $exito = false;

        if (isset($_SESSION['excel_data'])) {
            $rows = $_SESSION['excel_data'];
            unset($_SESSION['excel_data']);

            try {
                $max = min(35, count($rows));
                for ($i = 0; $i < $max; $i++) {
                    $row = $rows[$i];
                    // Ajusta los índices según tu archivo Excel
                    $tipo_ident = $row[1];
                    $numero_ident = $row[2];
                    $codigo_ficha = $row[3];
                    $tipo_poblacion = $row[4];
                    $tipo_org = $row[5];
                    $codigo_empresa = $row[6];

                    $this->model->insertarAspirante($tipo_ident, $numero_ident, $codigo_ficha, $tipo_poblacion, $tipo_org, $codigo_empresa);
                }
                $mensaje = "✅ Carga masiva realizada con éxito. Se cargaron $max aspirantes.";
                $exito = true;
            } catch (Exception $e) {
                $mensaje = "❌ Error al guardar los datos: " . $e->getMessage();
            }
        } else {
            $mensaje = "❌ No hay datos para guardar.";
        }

        $this->mostrarFormulario($mensaje, $exito);
    }
}