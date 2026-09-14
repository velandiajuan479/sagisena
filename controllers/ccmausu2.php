<?php
require_once("models/musumat.php");
require_once("models/mficmat.php");
require_once("models/mregm.php");
require_once("models/mval.php");
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Ccmausu2
{
    private $modUsu;
    private $modFic;
    private $modReg;
    private $modVal;
    private $mensaje = '';
    private $tipoMensaje = '';
    private $detalles = [];
    private $estadosPermitidos = ['matriculado', 'inscrito', 'en formacion', 'INDUCCION'];
    private $fecInicial;
    private $fecFinal;
    private $jornadaPrograma;
    private $jornadas = [];

    public function __construct()
    {
        $this->modUsu = new Musumat();
        $this->modFic = new Mficmat();
        $this->modReg = new Mregm();
        $this->modVal = new Mval();
        $this->procesarSolicitud();
    }

    /**
     * Procesa las solicitudes HTTP
     */
    private function procesarSolicitud(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['opera'])) {
            if (isset($_POST['fini']) && isset($_POST['ffin']) && isset($_POST['jorpro'])) {
                $this->fecInicial = date('Y-m-d', strtotime($_POST['fini']));
                $this->fecFinal = date('Y-m-d', strtotime($_POST['ffin']));
                $this->jornadaPrograma = htmlspecialchars($_POST['jorpro']);
            } else {
                throw new Exception("Las fechas son requeridas");
            }

            $this->ejecutarOperacion($_POST['opera']);
        }
    }

    /**
     * Ejecuta la operación solicitada
     */
    private function ejecutarOperacion(string $operacion): void
    {
        try {
            switch ($operacion) {
                case 'carga_masiva':
                    $this->procesarCargaMasiva();
                    break;

                default:
                    throw new Exception("Operación no válida");
            }
        } catch (Exception $e) {
            $this->establecerMensaje("Error: " . $e->getMessage(), 'error');
        }
    }

    /**
     * Procesa la carga masiva de usuarios
     */
    private function procesarCargaMasiva(): void
    {
        if (!isset($_FILES['archivo_excel']) || $_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("No se ha seleccionado un archivo válido");
        }

        $archivo = $_FILES['archivo_excel'];
        $resultado = $this->procesarArchivoExcel($archivo['tmp_name'], $archivo['name']);

        $this->mensaje = $resultado['mensaje'];
        $this->detalles = $resultado['detalles'];

        // Determinar si fue exitoso
        if ($resultado['exitosos'] > 0 && $resultado['errores'] == 0) {
            $this->tipoMensaje = 'success';
            $this->mensaje = "¡Archivo procesado exitosamente! {$resultado['exitosos']} usuarios cargados correctamente.";
        } else {
            $this->tipoMensaje = ($resultado['exitosos'] > 0) ? 'warning' : 'error';
        }
    }

    /**
     * Procesa archivo Excel para carga masiva
     */
    private function procesarArchivoExcel(string $rutaTemporal, string $nombreArchivo): array
    {
        $resultado = [
            'exitosos' => 0,
            'errores' => 0,
            'omitidos' => 0,
            'detalles' => [],
            'mensaje' => '',
            'codigoFicha' => '',
            'nombrePrograma' => '',
            'codigoPrograma' => '',
            'jornadaPrograma' => '',
        ];

        try {
            if (empty($this->fecInicial) || empty($this->fecFinal)) {
                throw new Exception("Las fechas inicial y final son requeridas");
            }

            $this->validarArchivo($rutaTemporal, $nombreArchivo);
            $spreadsheet = IOFactory::load($rutaTemporal);
            $worksheet = $spreadsheet->getActiveSheet();

            $codigoFicha = trim($worksheet->getCell('C2')->getCalculatedValue());
            $codigoFicha = trim(substr($codigoFicha,0,strpos($codigoFicha, "-")));
            $nombrePrograma = trim($worksheet->getCell('C2')->getCalculatedValue());
            $nombrePrograma = trim(substr($nombrePrograma,strpos($nombrePrograma, "-")+1,strlen($nombrePrograma)));

            $resultado['codigoFicha'] = $codigoFicha;
            $resultado['nombrePrograma'] = $nombrePrograma;
            $resultado['codigoPrograma'] = 1;
            $resultado['jornadaPrograma'] = $this->jornadaPrograma;

            $this->validarEstructuraArchivo($worksheet, $codigoFicha);
            $idFicha = (int)$codigoFicha;

            if ($idFicha > 0) {
                $this->modFic->setIdfic($idFicha);

                if (!$this->modFic->getFicCount()) {
                    $this->modFic->setNomfic($nombrePrograma);
                    $this->modFic->setCodpro(1);
                    $this->modFic->setJornada($this->jornadaPrograma);
                    $idCentro = 951310;
                    $this->modFic->setIdcen($idCentro);
                    $this->modFic->setMun(25175);
                    $this->modFic->setFinific($this->fecInicial);
                    $this->modFic->setFfinfic($this->fecFinal);
                    $this->modFic->save();
                }

                $resultado = $this->procesarFilasArchivo($worksheet, $idFicha, $resultado);
            } else {
                $resultado['mensaje'] = "Error: " . "Código de ficha inválido";
                $resultado['detalles'][] = $resultado['mensaje'];
            }
        } catch (Exception $e) {
            $resultado['mensaje'] = "Error: " . $e->getMessage();
            $resultado['detalles'][] = $resultado['mensaje'];
        }

        return $resultado;
    }

    /**
     * Procesa las filas del archivo Excel
     */
    private function procesarFilasArchivo($worksheet, int $idFicha, array $resultado): array
    {
        $filaMaxima = $worksheet->getHighestRow();

        for ($fila = 6; $fila <= $filaMaxima; $fila++) {
            try {
                $datosUsuario = $this->extraerDatosFila($worksheet, $fila);

                if ($this->filaVacia($datosUsuario)) continue;

                $this->validarDatosFila($datosUsuario, $fila);

                if (!$this->validarEstado($datosUsuario['estado'])) {
                    $resultado['omitidos']++;
                    $resultado['detalles'][] = "Fila {$fila}: Estado '{$datosUsuario['estado']}' no permitido";
                    continue;
                }

                $idUsuario = $this->procesarUsuario($datosUsuario);

                $this->modReg->setIdusu($idUsuario);
                $this->modReg->setIdfic($idFicha);

                $registroExistente = $this->modReg->getOneFil();

                if (empty($registroExistente)) {
                    $this->modReg->setFecreg($this->fecInicial);
                    $this->modReg->save();

                    $dtOUF = $this->modReg->getOneUF();
                    if($dtOUF && $dtOUF[0]['ct']==0)
                        $this->modReg->saveUF();

                    $resultado['exitosos']++;
                    $resultado['detalles'][] = "Fila {$fila} - Usuario registrado correctamente en la ficha";
                } else {
                    $resultado['omitidos']++;
                    $resultado['detalles'][] = "Fila {$fila} - Usuario ya registrado en la ficha (omitido)";
                }
            } catch (Exception $e) {
                $resultado['errores']++;
                $resultado['detalles'][] = "Fila {$fila}: " . $e->getMessage();
            }
        }

        $resultado['mensaje'] = "Procesamiento completado: {$resultado['exitosos']} exitosos, {$resultado['errores']} errores, {$resultado['omitidos']} omitidos";
        return $resultado;
    }

    // Métodos de validación y utilidad para el procesamiento de archivos
    private function validarArchivo(string $rutaTemporal, string $nombreArchivo): void
    {
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        if (!in_array($extension, ['xlsx', 'xls'])) {
            throw new Exception("Formato no permitido. Solo archivos Excel (.xlsx, .xls)");
        }

        if (filesize($rutaTemporal) > 10 * 1024 * 1024) {
            throw new Exception("Archivo excede 10MB");
        }
    }

    private function validarEstructuraArchivo($worksheet, string $codigoFicha): void
    {
        if (empty($codigoFicha)) {
            throw new Exception("Código de ficha no encontrado en celda B3");
        }

        $headers = [
            'A2' => 'Ficha de Caracterización:',
            'A3' => 'Estado:',
            'B5' => 'Número de Documento',
            'C5' => 'Nombre',
            'D5' => 'Apellidos',
            'G5' => 'Estado'
        ];

        foreach ($headers as $celda => $textoEsperado) {
            $valor = $worksheet->getCell($celda)->getCalculatedValue();
            if (stripos($valor, $textoEsperado) === false) {
                throw new Exception("Estructura incorrecta en celda {$celda}");
            }
        }
    }

    private function extraerDatosFila($worksheet, int $fila): array
    {
        $identificacion = trim($worksheet->getCell("B{$fila}")->getCalculatedValue());
        $tipodoc = trim($worksheet->getCell("A{$fila}")->getCalculatedValue());
        $nombre = trim($worksheet->getCell("C{$fila}")->getCalculatedValue())." ".trim($worksheet->getCell("D{$fila}")->getCalculatedValue());
        $estado = strtolower(trim($worksheet->getCell("G{$fila}")->getCalculatedValue()));

        // Procesar identificación formato "TIPO - NUMERO"
        $partes = preg_split('/\s*-\s*/', $identificacion, 2);

        return [
            'identificacion_completa' => $identificacion,
            'tipo_documento' => isset($tipodoc) ? trim($tipodoc) : '',
            'numero_documento' => isset($identificacion) ? trim($identificacion) : '',
            'nombre' => $nombre,
            'estado' => $estado
        ];
    }

    private function filaVacia(array $datos): bool
    {
        return empty($datos['numero_documento']) && empty($datos['nombre']);
    }

    private function validarDatosFila(array $datos, int $fila): void
    {
        if (empty($datos['identificacion_completa']) || empty($datos['nombre'])) {
            throw new Exception("Campos obligatorios vacíos");
        }
        if (!$this->validarDocumento($datos['identificacion_completa'])) {
            throw new Exception("Número de documento inválido: {$datos['numero_documento']}");
        }
        if (!$this->validarNombre($datos['nombre'])) {
            throw new Exception("Nombre inválido");
        }
        /*if (count(explode('-', $datos['identificacion_completa'])) < 2) {
            throw new Exception("Formato de identificación inválido");
        } */
    }

    private function validarEstado(string $estado): bool
    {
        return in_array(strtolower(trim($estado)), array_map('strtolower', $this->estadosPermitidos));
    }

    private function procesarUsuario(array $datos): int
    {
        $ndoc = $this->sanitizarTexto($datos['numero_documento']);
        $uname = $this->sanitizarTexto($datos['nombre']);

        if ($this->modUsu->existeDocumento($ndoc)) {
            $usuario = $this->modUsu->getOneByDoc($ndoc);
            return $usuario['idusu'];
        }

        $this->modUsu->setNdocusu($ndoc);
        $this->modUsu->setNomusu($uname);
        $this->modUsu->setIdcen(951310);
        $this->modUsu->setActusu(1);
        $this->modUsu->setPasusu(sha1(md5($ndoc))); // Generar contraseña por defecto
        $this->modUsu->save();

        $resultado = $this->modUsu->getOneByDoc($ndoc);

        return $resultado['idusu'];
    }

    private function sanitizarTexto(string $texto): string
    {
        return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Obtiene los datos para la vista
     */
    public function obtenerDatosVista(): array
    {
        $this->modVal->setIddom(1);
        $jornadasData = $this->modVal->getByDom();
        if (is_array($jornadasData)) {
            foreach ($jornadasData as $jornada) {
                $this->jornadas[] = $jornada;
            }
        }

        return [
            'mensaje' => $this->mensaje ?? '',
            'tipoMensaje' => $this->tipoMensaje ?? '',
            'detalles' => $this->detalles ?? [],
            'jornadas' => $this->jornadas ?? [],
        ];
    }

    private function establecerMensaje(string $mensaje, string $tipo): void
    {
        $this->mensaje = $mensaje;
        $this->tipoMensaje = $tipo;
    }

    // Métodos de validación
    public function validarDocumento(string $documento): bool
    {
        return preg_match('/^[0-9]{7,15}$/', trim($documento));
    }

    public function validarNombre(string $nombre): bool
    {
        $nombre = trim($nombre);
        return !empty($nombre) && strlen($nombre) <= 125 && preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $nombre);
    }

    public function validarTipoDocumento(string $tipo): bool
    {
        $tiposValidos = ['CC', 'TI', 'CE', 'PAS'];
        return in_array(strtoupper(trim($tipo)), $tiposValidos);
    }
}

// Inicializar controlador
$controlador2 = new Ccmausu2();
$datosVista2 = $controlador2->obtenerDatosVista();

// Extraer variables para la vista
extract($datosVista2);