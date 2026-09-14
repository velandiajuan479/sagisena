<?php
require_once("models/conexion.php");

class mcalendacad {
    private $id;
    private $titulo;
    private $descripcion;
    private $fecha_inicio;
    private $fecha_fin;
    private $tipo_evento;
    private $anio;
    private $trimestre;

    // Getters
    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getDescripcion() { return $this->descripcion; }
    public function getFechaInicio() { return $this->fecha_inicio; }
    public function getFechaFin() { return $this->fecha_fin; }
    public function getTipoEvento() { return $this->tipo_evento; }
    public function getAnio() { return $this->anio; }
    public function getTrimestre() { return $this->trimestre; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setFechaInicio($fecha_inicio) { $this->fecha_inicio = $fecha_inicio; }
    public function setFechaFin($fecha_fin) { $this->fecha_fin = $fecha_fin; }
    public function setTipoEvento($tipo_evento) { $this->tipo_evento = $tipo_evento; }
    public function setAnio($anio) { $this->anio = $anio; }
    public function setTrimestre($trimestre) { $this->trimestre = $trimestre; }

    // Obtener todos los eventos
    public function getAll() {
        $sql = "SELECT * FROM calendario_academico ORDER BY fecha_inicio ASC";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener eventos por año
    public function getByAnio($anio) {
        $sql = "SELECT * FROM calendario_academico WHERE YEAR(fecha_inicio) = :anio ORDER BY fecha_inicio ASC";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':anio', $anio, PDO::PARAM_INT);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un evento específico
    public function getOne() {
        $sql = "SELECT * FROM calendario_academico WHERE id = :id";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id', $this->id, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar evento
    public function save() {
        $sql = "INSERT INTO calendario_academico (titulo, descripcion, fecha_inicio, fecha_fin, tipo_evento, anio, trimestre) 
                VALUES (:titulo, :descripcion, :fecha_inicio, :fecha_fin, :tipo_evento, :anio, :trimestre)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':titulo', $this->titulo, PDO::PARAM_STR);
        $result->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $result->bindParam(':fecha_inicio', $this->fecha_inicio, PDO::PARAM_STR);
        $result->bindParam(':fecha_fin', $this->fecha_fin, PDO::PARAM_STR);
        $result->bindParam(':tipo_evento', $this->tipo_evento, PDO::PARAM_STR);
        $result->bindParam(':anio', $this->anio, PDO::PARAM_INT);
        $result->bindParam(':trimestre', $this->trimestre, PDO::PARAM_STR);
        return $result->execute();
    }

    // Actualizar evento
    public function edit() {
        $sql = "UPDATE calendario_academico SET 
                titulo = :titulo, 
                descripcion = :descripcion, 
                fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, 
                tipo_evento = :tipo_evento, 
                anio = :anio, 
                trimestre = :trimestre 
                WHERE id = :id";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id', $this->id, PDO::PARAM_INT);
        $result->bindParam(':titulo', $this->titulo, PDO::PARAM_STR);
        $result->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $result->bindParam(':fecha_inicio', $this->fecha_inicio, PDO::PARAM_STR);
        $result->bindParam(':fecha_fin', $this->fecha_fin, PDO::PARAM_STR);
        $result->bindParam(':tipo_evento', $this->tipo_evento, PDO::PARAM_STR);
        $result->bindParam(':anio', $this->anio, PDO::PARAM_INT);
        $result->bindParam(':trimestre', $this->trimestre, PDO::PARAM_STR);
        return $result->execute();
    }

    // Eliminar evento
    public function del() {
        $sql = "DELETE FROM calendario_academico WHERE id = :id";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $result->execute();
    }












    // Calcular días hábiles entre dos fechas
    public function calcularDiasHabiles($fecha_inicio, $fecha_fin) {
        $inicio = new DateTime($fecha_inicio);
        $fin = new DateTime($fecha_fin);
        $dias_habiles = 0;
        
        while ($inicio <= $fin) {
            $dia_semana = $inicio->format('N'); // 1 (lunes) a 7 (domingo)
            if ($dia_semana <= 6) { // Lunes a sábado
                $dias_habiles++;
            }
            $inicio->add(new DateInterval('P1D'));
        }
        
        return $dias_habiles;
    }

    // Calcular días laborables entre dos fechas (excluyendo sábados, domingos y festivos)
    private function calcularDiasLaborablesEntreFechas($fecha_inicio, $fecha_fin, $festivos) {
        $inicio = new DateTime($fecha_inicio);
        $fin = new DateTime($fecha_fin);
        $dias_laborables = 0;
        
        // Preparar array de festivos para comparación rápida
        $festivos_formato = [];
        foreach ($festivos as $f) {
            $festivos_formato[] = (new DateTime($f['fecha_inicio']))->format('Y-m-d');
        }
        
        while ($inicio <= $fin) {
            $dia_semana = $inicio->format('N'); // 1 (lunes) a 7 (domingo)
            $fecha_formato = $inicio->format('Y-m-d');
            
            // Si no es sábado (6) ni domingo (7) y no es un día festivo
            if ($dia_semana < 6 && !in_array($fecha_formato, $festivos_formato)) {
                $dias_laborables++;
            }
            $inicio->add(new DateInterval('P1D'));
        }
        
        return $dias_laborables;
    }

    // Nueva función para calcular días laborables en un mes, descontando fines de semana y festivos
    private function calcularDiasLaborablesMes($mes, $anio, $festivos) {
        $dias_laborables = 0;
        $dias_en_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        
        $festivos_formato = [];
        foreach ($festivos as $f) {
            $festivos_formato[] = (new DateTime($f['fecha_inicio']))->format('Y-m-d');
        }

        for ($dia = 1; $dia <= $dias_en_mes; $dia++) {
            $fecha_actual = new DateTime("$anio-$mes-$dia");
            $dia_semana = $fecha_actual->format('N'); // 1 (lunes) a 7 (domingo)
            $fecha_formato = $fecha_actual->format('Y-m-d');

            // Si no es sábado (6) ni domingo (7) y no es un día festivo
            if ($dia_semana < 6 && !in_array($fecha_formato, $festivos_formato)) {
                $dias_laborables++;
            }
        }
        return $dias_laborables;
    }

    // Detectar tipo de usuario según fechas de contrato y perfil
    public function getTipoUsuario($idusu) {
        $sql = "SELECT u.fecini, u.fecfin, u.idper FROM usuario u WHERE u.idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idusu', $idusu, PDO::PARAM_INT);
        $result->execute();
        $usuario = $result->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            return null; // Usuario no encontrado
        }
        
        // Perfiles EXCLUIDOS (Aprendices y Votación)
        // 3: Candidato Votación, 4: Aprendiz, 8: Aprendiz E/S, 13: Candidato Vocero,
        // 19: Perfil Nulo Votaciones, 22: Aprendiz HyA, 23: Aprendiz EP, 39: Aprendiz Llamados
        $perfiles_excluidos = [3, 4, 8, 13, 19, 22, 23, 39];
        
        // Verificar si el perfil del usuario está excluido
        if (in_array($usuario['idper'], $perfiles_excluidos)) {
            return null; // No aplica para aprendices ni votación
        }
        
        // Para perfiles válidos (Administradores, Instructores, Directivos, Funcionarios):
        // Si tiene ambas fechas (fecini Y fecfin) es CONTRATISTA
        if ($usuario['fecini'] && $usuario['fecfin']) {
            return 'CONTRATISTA';
        }
        
        // Si NO tiene fechas es PLANTA
        return 'PLANTA';
    }

    // Obtener estadísticas del calendario
    public function getEstadisticas($anio = 2025, $idusu = null) {
        $eventos = $this->getByAnio($anio);
        $mes_actual = date('n'); // Mes actual (1-12)
        $anio_actual = date('Y');
        
        // Detectar tipo de usuario (puede retornar 'PLANTA', 'CONTRATISTA' o null)
        $tipo_usuario = null;
        if ($idusu) {
            $tipo_usuario = $this->getTipoUsuario($idusu);
        }
        
        $estadisticas = [
            'total_eventos' => count($eventos),
            'trimestre_actual' => $this->obtenerTrimestreActual($mes_actual),
            'mes_actual' => $this->obtenerMesActual($mes_actual),
            'dias_formacion' => 0,
            'dias_festivos_total' => 0,
            'dias_festivos_mes_actual' => 0,
            'dias_trabajo' => 0,
            'tipo_usuario' => $tipo_usuario, // Puede ser PLANTA, CONTRATISTA o null
            'aplica_calculo' => ($tipo_usuario !== null), // Indica si aplica cálculo de horas
            'horas_formacion_planta' => 0,
            'horas_alistamiento_eventos' => 0,
            'dias_alistamiento' => 0
        ];

        $festivos_mes_actual_arr = []; // Para almacenar los festivos del mes actual

        foreach ($eventos as $evento) {
            $dias_evento = $this->calcularDiasHabiles($evento['fecha_inicio'], $evento['fecha_fin']);
            
            switch ($evento['tipo_evento']) {
                case 'trimestre':
                    $estadisticas['dias_formacion'] += $dias_evento;
                    $estadisticas['dias_trabajo'] += $dias_evento;
                    break;
                case 'alistamiento':
                    $estadisticas['dias_alistamiento'] += $dias_evento;
                    // Las horas de alistamiento se calculan más adelante específicamente para planta
                    $estadisticas['dias_trabajo'] += $dias_evento;
                    break;
                case 'festivo':
                    $estadisticas['dias_festivos_total'] += $dias_evento;
                    $fecha_evento_inicio = new DateTime($evento['fecha_inicio']);
                    $fecha_evento_fin = new DateTime($evento['fecha_fin']);
                    
                    // Recorrer todos los días del evento festivo
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($fecha_evento_inicio, $interval, $fecha_evento_fin->modify('+1 day'));
                    
                    foreach ($period as $dt) {
                        if ($dt->format('n') == $mes_actual && $dt->format('Y') == $anio_actual) {
                            $estadisticas['dias_festivos_mes_actual']++;
                            $festivos_mes_actual_arr[] = ['fecha_inicio' => $dt->format('Y-m-d')];
                        }
                    }
                    break;
                case 'receso':
                    break;
                case 'examen':
                    $estadisticas['dias_trabajo'] += $dias_evento;
                    break;
                case 'inicio_clases':
                    $estadisticas['dias_trabajo'] += $dias_evento;
                    break;
                case 'balance':
                    $estadisticas['dias_trabajo'] += $dias_evento;
                    break;
                default:
                    $estadisticas['dias_trabajo'] += $dias_evento;
                    break;
            }
        }

        // Calcular días laborables reales del mes actual
        $dias_laborables_mes_actual = $this->calcularDiasLaborablesMes($mes_actual, $anio_actual, $festivos_mes_actual_arr);

        // Obtener todos los festivos del año para el cálculo de alistamiento
        $festivos_anio = [];
        foreach ($eventos as $evento) {
            if ($evento['tipo_evento'] == 'festivo') {
                $fecha_evento_inicio = new DateTime($evento['fecha_inicio']);
                $fecha_evento_fin = new DateTime($evento['fecha_fin']);
                
                // Agregar todos los días del evento festivo
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($fecha_evento_inicio, $interval, $fecha_evento_fin->modify('+1 day'));
                
                foreach ($period as $dt) {
                    $festivos_anio[] = ['fecha_inicio' => $dt->format('Y-m-d')];
                }
            }
        }

        // Calcular horas adicionales de eventos de alistamiento SOLO para planta (días laborables * 2.4)
        // Excluyendo sábados, domingos y festivos
        $horas_alistamiento_eventos_planta = 0;
        foreach ($eventos as $evento) {
            if ($evento['tipo_evento'] == 'alistamiento') {
                $dias_alistamiento_laborables = $this->calcularDiasLaborablesEntreFechas(
                    $evento['fecha_inicio'], 
                    $evento['fecha_fin'], 
                    $festivos_anio
                );
                $horas_alistamiento_eventos_planta += ($dias_alistamiento_laborables * 2.4);
            }
        }

        // Cálculos según tipo de usuario
        if ($tipo_usuario === null) {
            // Usuario es Aprendiz o Votación - No aplica cálculo de horas
            $estadisticas['horas_formacion_contrato'] = null;
            $estadisticas['horas_formacion_planta'] = null;
            $estadisticas['horas_alistamiento_planta'] = null;
            $estadisticas['horas_alistamiento_contratacion'] = null;
            $estadisticas['horas_directas'] = null;
            $estadisticas['horas_complementarias'] = null;
        } elseif ($tipo_usuario == 'CONTRATISTA') {
            // Para CONTRATISTA (Instructores/Administradores/Funcionarios con fechas): 160 horas fijas
            $estadisticas['horas_formacion_contrato'] = 160;
            $estadisticas['horas_formacion_planta'] = null;
            $estadisticas['horas_alistamiento_planta'] = null;
            $estadisticas['horas_alistamiento_contratacion'] = null;
            $estadisticas['horas_directas'] = null;
            $estadisticas['horas_complementarias'] = null;
        } else {
            // Para PLANTA (Instructores/Administradores/Funcionarios sin fechas): Cálculo basado en días laborables
            // Días laborables del mes actual (excluyendo sábados, domingos y festivos)
            
            // Horas Directas de Formación: días laborables × 6.2
            $estadisticas['horas_directas'] = round($dias_laborables_mes_actual * 6.2, 1);
            
            // Horas Complementarias: días laborables × 2.4
            $estadisticas['horas_complementarias'] = round($dias_laborables_mes_actual * 2.4, 1);
            
            // Mantener compatibilidad con código anterior
            $estadisticas['horas_formacion_planta'] = $estadisticas['horas_directas'];
            $estadisticas['horas_formacion_contrato'] = null;
            $estadisticas['horas_alistamiento_planta'] = $estadisticas['horas_complementarias'];
            $estadisticas['horas_alistamiento_contratacion'] = null;
        }

        return $estadisticas;
    }

    // Obtener trimestre actual basado en el mes
    private function obtenerTrimestreActual($mes) {
        if ($mes >= 2 && $mes <= 4) return 'Primer Trimestre';
        if ($mes >= 5 && $mes <= 7) return 'Segundo Trimestre';
        if ($mes >= 8 && $mes <= 10) return 'Tercer Trimestre';
        if ($mes >= 11 || $mes == 1) return 'Cuarto Trimestre';
        return 'Fuera de trimestre';
    }

    // Obtener mes actual en español colombiano
    private function obtenerMesActual($mes) {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero', 
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        return $meses[$mes] ?? 'Mes desconocido';
    }

    // Calcular horas registradas del usuario en horarios
    public function getHorasRegistradas($idusu, $anio = null) {
        if (!$idusu) return 0;
        
        $anio_actual = $anio ?: date('Y');
        
        $sql = "SELECT h.fecha_especifica, f.jornada 
                FROM horario h 
                INNER JOIN ficha f ON h.idfic = f.idfic 
                WHERE h.idusu = :idusu 
                AND YEAR(h.fecha_especifica) = :anio
                AND h.fecha_especifica IS NOT NULL";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idusu', $idusu, PDO::PARAM_INT);
        $result->bindParam(':anio', $anio_actual, PDO::PARAM_INT);
        $result->execute();
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        
        $total_horas = 0;
        
        foreach ($horarios as $horario) {
            $jornada = $horario['jornada'];
            
            // Calcular horas según jornada
            switch ($jornada) {
                case 1: // Mañana
                    $total_horas += 6;
                    break;
                case 2: // Tarde
                    $total_horas += 5;
                    break;
                case 3: // Noche
                    $total_horas += 4;
                    break;
                default:
                    $total_horas += 6; // Por defecto mañana
                    break;
            }
        }
        
        return $total_horas;
    }

    // Obtener estadísticas con horas registradas vs requeridas
    public function getEstadisticasCompletas($anio = 2025, $idusu = null) {
        // Obtener estadísticas base
        $estadisticas = $this->getEstadisticas($anio, $idusu);
        
        if ($idusu && $estadisticas['aplica_calculo']) {
            // Calcular horas registradas del usuario
            $horas_registradas = $this->getHorasRegistradas($idusu, $anio);
            $estadisticas['horas_registradas'] = $horas_registradas;
            
            // Calcular horas requeridas según tipo de usuario
            if ($estadisticas['tipo_usuario'] == 'CONTRATISTA') {
                $horas_requeridas = 160;
                $estadisticas['horas_requeridas'] = $horas_requeridas;
                $estadisticas['horas_faltantes'] = max(0, $horas_requeridas - $horas_registradas);
                $estadisticas['porcentaje_cumplimiento'] = $horas_requeridas > 0 ? round(($horas_registradas / $horas_requeridas) * 100, 1) : 0;
            } else {
                // Para PLANTA: usar las horas calculadas del mes actual
                $horas_requeridas = $estadisticas['horas_directas'] ?? 0;
                $estadisticas['horas_requeridas'] = $horas_requeridas;
                $estadisticas['horas_faltantes'] = max(0, $horas_requeridas - $horas_registradas);
                $estadisticas['porcentaje_cumplimiento'] = $horas_requeridas > 0 ? round(($horas_registradas / $horas_requeridas) * 100, 1) : 0;
            }
        } else {
            $estadisticas['horas_registradas'] = 0;
            $estadisticas['horas_requeridas'] = 0;
            $estadisticas['horas_faltantes'] = 0;
            $estadisticas['porcentaje_cumplimiento'] = 0;
        }
        
        return $estadisticas;
    }
}
?>
