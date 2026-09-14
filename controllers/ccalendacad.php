<?php
require_once("models/mcalendacad.php");

class ccalendacad {
    private $mcalendacad;
    
    public function __construct() {
        $this->mcalendacad = new mcalendacad();
    }

    // Procesar todas las operaciones
    public function procesarOperaciones() {
        $ope = $_REQUEST['ope'] ?? null;
        $id = $_REQUEST['id'] ?? null;
        $anio = $_REQUEST['anio'] ?? date('Y');

        switch ($ope) {
            case 'save':
                $this->guardarEvento();
                break;
            case 'edit':
                $this->editarEvento();
                break;
            case 'del':
                $this->eliminarEvento($id);
                break;
            case 'edi':
                return $this->obtenerEventoParaEditar($id);
            default:
                return $this->obtenerEventos($anio);
        }
    }

    // Guardar nuevo evento
    private function guardarEvento() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->mcalendacad->setTitulo($_POST['titulo'] ?? '');
            $this->mcalendacad->setDescripcion($_POST['descripcion'] ?? '');
            $this->mcalendacad->setFechaInicio($_POST['fecha_inicio'] ?? '');
            $this->mcalendacad->setFechaFin($_POST['fecha_fin'] ?? '');
            $this->mcalendacad->setTipoEvento($_POST['tipo_evento'] ?? 'evento');
            $this->mcalendacad->setAnio($_POST['anio'] ?? date('Y'));
            $this->mcalendacad->setTrimestre($_POST['trimestre'] ?? '');

            if ($this->mcalendacad->save()) {
                 echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'El evento fue guardado correctamente.',
                            confirmButtonColor: '#198754'
                        }).then(() => {
                            window.location.href = 'home.php?pg=1599'; 
                        });
                    </script>";
                    exit; 
            } else {
                  echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: 'No se pudo guardar el evento.',
                                confirmButtonColor: '#d33'
                            }).then(() => {
                                window.location.href = 'home.php?pg=1599';
                            });
                        </script>";
                        exit;
            }
        }
    }

    // Editar evento existente
    private function editarEvento() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->mcalendacad->setId($_POST['id'] ?? 0);
            $this->mcalendacad->setTitulo($_POST['titulo'] ?? '');
            $this->mcalendacad->setDescripcion($_POST['descripcion'] ?? '');
            $this->mcalendacad->setFechaInicio($_POST['fecha_inicio'] ?? '');
            $this->mcalendacad->setFechaFin($_POST['fecha_fin'] ?? '');
            $this->mcalendacad->setTipoEvento($_POST['tipo_evento'] ?? 'evento');
            $this->mcalendacad->setAnio($_POST['anio'] ?? date('Y'));
            $this->mcalendacad->setTrimestre($_POST['trimestre'] ?? '');

            if ($this->mcalendacad->edit()) {
                 echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El evento fue actualizado correctamente.',
                        confirmButtonColor: '#198754'
                    }).then(() => {
                        window.location.href = 'home.php?pg=1599'; 
                    });
                </script>";
                exit; 

            } else {
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'No se pudo actualizar el evento.',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.location.href = 'home.php?pg=1599';
                });
            </script>";
            exit;
            }
        }
    }

    // Eliminar evento
    private function eliminarEvento($id) {
        if ($id) {
            $this->mcalendacad->setId($id);
            if ($this->mcalendacad->del()) {
                 echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El evento fue eliminado correctamente.',
                        confirmButtonColor: '#198754'
                    }).then(() => {
                        window.location.href = 'home.php?pg=1599'; 
                    });
                </script>";
                exit; 
            } else {
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'No se pudo eliminar el evento.',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.location.href = 'home.php?pg=1599';
                });
            </script>";
            exit;
            }
        }
    }

    // Cargar calendario 2025 del SENA


    // Obtener evento para editar
    private function obtenerEventoParaEditar($id) {
        if ($id) {
            $this->mcalendacad->setId($id);
            return $this->mcalendacad->getOne();
        }
        return null;
    }

    // Obtener eventos por año
    private function obtenerEventos($anio) {
        return $this->mcalendacad->getByAnio($anio);
    }

    // Obtener estadísticas
    public function obtenerEstadisticas($anio = 2025, $idusu = null) {
        return $this->mcalendacad->getEstadisticas($anio, $idusu);
    }

    // Obtener estadísticas completas con horas registradas
    public function obtenerEstadisticasCompletas($anio = 2025, $idusu = null) {
        return $this->mcalendacad->getEstadisticasCompletas($anio, $idusu);
    }

    // Obtener años disponibles
    public function obtenerAniosDisponibles() {
        $sql = "SELECT DISTINCT anio FROM calendario_academico ORDER BY anio DESC";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obtener tipos de eventos disponibles
    public function obtenerTiposEventos() {
        return [
            'trimestre' => 'Trimestre',
            'alistamiento' => 'Alistamiento',
            'receso' => 'Receso',
            'festivo' => 'Festivo',
            'examen' => 'Examen',
            'inicio_clases' => 'Inicio de Clases',
            'balance' => 'Balance',
            'evento' => 'Otro Evento'
        ];
    }

    // Obtener trimestres disponibles
    public function obtenerTrimestres() {
        return [
            'Primer Trimestre' => 'Primer Trimestre',
            'Segundo Trimestre' => 'Segundo Trimestre',
            'Tercer Trimestre' => 'Tercer Trimestre',
            'Cuarto Trimestre' => 'Cuarto Trimestre',
            'Preparación' => 'Preparación',
            'Receso' => 'Receso',
            'Cierre' => 'Cierre',
            'Otro' => 'Otro'
        ];
    }
}

// Inicializar y procesar operaciones
$ccalendacad = new ccalendacad();
$eventos = $ccalendacad->procesarOperaciones();
$evento_editar = null;

// Si es modo edición, obtener el evento
if (isset($_REQUEST['ope']) && $_REQUEST['ope'] == 'edi' && isset($_REQUEST['id'])) {
    $evento_editar = $ccalendacad->obtenerEventoParaEditar($_REQUEST['id']);
}

// Obtener datos adicionales
$anios_disponibles = $ccalendacad->obtenerAniosDisponibles();
$tipos_eventos = $ccalendacad->obtenerTiposEventos();
$trimestres = $ccalendacad->obtenerTrimestres();

// Pasar el ID del usuario logueado para calcular estadísticas personalizadas
$idusu_sesion = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : null;
$estadisticas = $ccalendacad->obtenerEstadisticasCompletas($_REQUEST['anio'] ?? 2025, $idusu_sesion);
?>
