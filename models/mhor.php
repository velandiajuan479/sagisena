<?php

class Mhor
{
    public function __construct() {
        // Configurar zona horaria de Colombia para toda la clase
        date_default_timezone_set('America/Bogota');
    }
    
    //atributos
    private $idhor;
    private $idfic;
    private $idaul;
    private $idusu;
    private $iddia;
    private $idare;

    // Nuevos atributos para actividades "Otros"
    private $es_otros;
    private $actividad;
    private $horas_otros;
    private $es_formacion_directa; // 1=otros, 2=formación directa

    // Nuevos atributos para horarios "Transversales"
    private $es_transversal;
    private $nombre_transversal;

    // Nuevos atributos para fechas de lapsos de tiempo
    private $fecha_inicio;
    private $fecha_fin;
    private $horas_mes_actual;

    //metodos get
    public function getIdhor()
    {
        return $this->idhor;
    }
    public function getIdfic()
    {
        return $this->idfic;
    }
    public function getIdaul()
    {
        return $this->idaul;
    }
    public function getIdusu()
    {
        return $this->idusu;
    }
    public function getIddia()
    {
        return $this->iddia;
    }
    public function getIdare()
    {
        return $this->idare;
    }

    // Nuevos métodos GET para actividades "Otros"
    public function getEsOtros()
    {
        return $this->es_otros;
    }

    public function getActividad()
    {
        return $this->actividad;
    }

    public function getHorasOtros()
    {
        return $this->horas_otros;
    }

    public function getEsFormacionDirecta()
    {
        return $this->es_formacion_directa;
    }

    // Nuevos métodos GET para horarios "Transversales"
    public function getEsTransversal()
    {
        return $this->es_transversal;
    }

    public function getNombreTransversal()
    {
        return $this->nombre_transversal;
    }

    // Nuevos métodos GET para fechas de lapsos de tiempo
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    public function getHorasMesActual()
    {
        return $this->horas_mes_actual;
    }

    //metodos SET
    public function setIdhor($idhor)
    {
        $this->idhor = $idhor;
    }
    public function setIdfic($idfic)
    {
        $this->idfic = $idfic;
    }
    public function setIdaul($idaul)
    {
        $this->idaul = $idaul;
    }
    public function setIdusu($idusu)
    {
        $this->idusu = $idusu;
    }
    public function setIddia($iddia)
    {
        $this->iddia = $iddia;
    }
    public function setIdare($idare)
    {
        $this->idare = $idare;
    }

    // Nuevos métodos SET para actividades "Otros"
    public function setEsOtros($es_otros)
    {
        $this->es_otros = $es_otros;
    }

    public function setActividad($actividad)
    {
        $this->actividad = $actividad;
    }

    public function setHorasOtros($horas_otros)
    {
        $this->horas_otros = $horas_otros;
    }

    public function setEsFormacionDirecta($es_formacion_directa)
    {
        $this->es_formacion_directa = $es_formacion_directa;
    }

    // Nuevos métodos SET para horarios "Transversales"
    public function setEsTransversal($es_transversal)
    {
        $this->es_transversal = $es_transversal;
    }

    public function setNombreTransversal($nombre_transversal)
    {
        $this->nombre_transversal = $nombre_transversal;
    }

    // Nuevos métodos SET para fechas de lapsos de tiempo
    public function setFechaInicio($fecha_inicio)
    {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFechaFin($fecha_fin)
    {
        $this->fecha_fin = $fecha_fin;
    }

    public function setHorasMesActual($horas_mes_actual)
    {
        $this->horas_mes_actual = $horas_mes_actual;
    }

    /**
     * Obtiene el inicio de la semana actual (lunes) o de una semana específica
     */
    private function getInicioSemana($semanaSelector = null)
    {
        if ($semanaSelector) {
            // Si se especifica una semana, calcular esa semana
            $resultado = $this->getInicioSemanaEspecifica($semanaSelector);
            return $resultado;
        }
        
        // Semana actual por defecto en zona horaria de Colombia
        $zonaColombia = new DateTimeZone('America/Bogota');
        $hoy = new DateTime('now', $zonaColombia);
        $diaSemana = $hoy->format('N'); // 1 (lunes) a 7 (domingo)
        
        if ($diaSemana == 1) {
            // Si hoy es lunes, la semana empieza hoy
            $inicioSemana = clone $hoy;
        } else {
            // Si no, retroceder al lunes anterior
            $diasRetroceder = $diaSemana - 1;
            $inicioSemana = clone $hoy;
            $inicioSemana->sub(new DateInterval("P{$diasRetroceder}D"));
        }
        
        $resultado = $inicioSemana->format('Y-m-d');
        return $resultado;
    }

    /**
     * Obtiene el fin de la semana actual (domingo)
     */
    private function getFinSemana($semanaSelector = null)
    {
        if ($semanaSelector) {
            // Si se especifica una semana, calcular esa semana
            $inicio = $this->getInicioSemanaEspecifica($semanaSelector);
            $fechaInicio = new DateTime($inicio);
            $fechaInicio->add(new DateInterval('P6D')); // Agregar 6 días para llegar al domingo
            $resultado = $fechaInicio->format('Y-m-d');
            return $resultado;
        }
        
        // Semana actual por defecto en zona horaria de Colombia
        $zonaColombia = new DateTimeZone('America/Bogota');
        $hoy = new DateTime('now', $zonaColombia);
        $diaSemana = $hoy->format('N'); // 1 (lunes) a 7 (domingo)
        
        if ($diaSemana == 7) {
            // Si hoy es domingo, la semana termina hoy
            $finSemana = clone $hoy;
        } else {
            // Si no, avanzar al domingo siguiente
            $diasAvanzar = 7 - $diaSemana;
            $finSemana = clone $hoy;
            $finSemana->add(new DateInterval("P{$diasAvanzar}D"));
        }
        
        $resultado = $finSemana->format('Y-m-d');
        return $resultado;
    }

    /**
     * Obtiene el inicio de una semana específica basada en el selector
     * @param string $semanaSelector Formato: YYYY-WW (ej: 2024-34)
     * @return string Fecha de inicio en formato Y-m-d
     */
    private function getInicioSemanaEspecifica($semanaSelector)
    {
        try {
                    // Parsear el selector de semana (formato: YYYY-WW o YYYY-WWW)
        if (preg_match('/^(\d{4})-W?(\d{2,3})$/', $semanaSelector, $matches)) {
                $año = (int)$matches[1];
                $semana = (int)$matches[2];
                
                // Calcular el 4 de enero del año (según estándar ISO 8601)
                $zonaColombia = new DateTimeZone('America/Bogota');
                $cuatroEnero = new DateTime("$año-01-04", $zonaColombia);
                $diaSemanaCuatroEnero = (int)$cuatroEnero->format('N'); // 1=Lunes, 7=Domingo
                
                // Calcular el primer día de la semana 1 (lunes)
                $primerDiaSemana1 = clone $cuatroEnero;
                $diasHastaLunes = $diaSemanaCuatroEnero - 1;
                $primerDiaSemana1->sub(new DateInterval("P{$diasHastaLunes}D"));
                
                // Calcular el lunes de la semana específica
                $lunesSemana = clone $primerDiaSemana1;
                $lunesSemana->add(new DateInterval('P' . (($semana - 1) * 7) . 'D'));
                
                $resultado = $lunesSemana->format('Y-m-d');
                return $resultado;
            }
        } catch (Exception $e) {
            error_log("Error en getInicioSemanaEspecifica: " . $e->getMessage());
            throw $e; // Re-lanzar el error
        }
        
        // Fallback: semana actual
        return $this->getInicioSemana();
    }

    //metodos publicos
    public function getAll($semanaSelector = null)
    {
        $res = null;

        // SQL CORREGIDO: Filtro simple y directo
        // Solo mostrar horarios que tengan fecha_especifica en la semana seleccionada
        // Y que el día de la semana coincida con la columna donde se va a mostrar
        $sql = 'SELECT h.idhor, h.idfic, h.idaul, a.nomaul, a.codubi, a.idcen, h.idusu, 
                       u.ndocusu, u.nomusu, u.colfon, u.coltex, h.iddia,
                       h.es_otros, h.actividad, h.horas_otros,
                       h.es_transversal, h.nombre_transversal,
                       h.fecha_inicio, h.fecha_fin,
                       h.fecha_especifica
                FROM horario AS h 
                LEFT JOIN usuario AS u ON h.idusu = u.idusu 
                LEFT JOIN aula AS a ON h.idaul = a.idaul 
                WHERE h.idfic = :idfic 
                AND h.iddia = :iddia
                AND h.fecha_especifica IS NOT NULL
                AND h.fecha_especifica BETWEEN :semana_inicio AND :semana_fin';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);

        $idfic = $this->getIdfic();
        $result->bindParam(':idfic', $idfic);

        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);

        // Calcular semana (actual o especificada)
        $semanaInicio = $this->getInicioSemana($semanaSelector);
        $semanaFin = $this->getFinSemana($semanaSelector);
        
        $result->bindParam(':semana_inicio', $semanaInicio);
        $result->bindParam(':semana_fin', $semanaFin);

        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);

        return $res;
    }

    /**
     * Valida si un horario está realmente activo en una semana específica
     * Este método proporciona una validación adicional al filtro SQL
     * @param array $horario Array con los datos del horario
     * @param string $semanaInicio Fecha de inicio de la semana (Y-m-d)
     * @param string $semanaFin Fecha de fin de la semana (Y-m-d)
     * @return bool True si el horario está activo en esa semana
     */
    public function validarHorarioActivoEnSemana($horario, $semanaInicio, $semanaFin)
    {
        try {
            // Verificar que el horario tenga todas las fechas necesarias
            if (empty($horario['fecha_inicio']) || empty($horario['fecha_fin']) || empty($horario['fecha_especifica'])) {
                return false;
            }

            $fechaInicio = new DateTime($horario['fecha_inicio']);
            $fechaFin = new DateTime($horario['fecha_fin']);
            $fechaEspecifica = new DateTime($horario['fecha_especifica']);
            $semanaInicioDT = new DateTime($semanaInicio);
            $semanaFinDT = new DateTime($semanaFin);

            // Validación 1: La fecha_especifica debe estar dentro de la semana
            if ($fechaEspecifica < $semanaInicioDT || $fechaEspecifica > $semanaFinDT) {
                return false;
            }

            // Validación 2: El horario debe estar vigente durante la semana
            // fecha_inicio <= semana_fin (el horario debe haber comenzado antes del fin de la semana)
            if ($fechaInicio > $semanaFinDT) {
                return false;
            }

            // fecha_fin >= semana_inicio (el horario debe estar vigente al inicio de la semana)
            if ($fechaFin < $semanaInicioDT) {
                return false;
            }

            return true;

        } catch (Exception $e) {
            error_log("Error en validarHorarioActivoEnSemana: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene horarios con validación adicional de actividad en la semana
     * Este método combina el filtro SQL con validación PHP para mayor precisión
     * @param string $semanaSelector Selector de semana (YYYY-WW) o null para semana actual
     * @return array Array de horarios validados
     */
    public function getAllValidados($semanaSelector = null)
    {
        // Primero obtener horarios con el filtro SQL mejorado
        $horarios = $this->getAll($semanaSelector);
        
        if (empty($horarios)) {
            return [];
        }

        // Calcular fechas de la semana para validación
        $semanaInicio = $this->getInicioSemana($semanaSelector);
        $semanaFin = $this->getFinSemana($semanaSelector);

        $horariosValidados = [];
        $horariosRechazados = 0;

        foreach ($horarios as $horario) {
            if ($this->validarHorarioActivoEnSemana($horario, $semanaInicio, $semanaFin)) {
                $horariosValidados[] = $horario;
            } else {
                $horariosRechazados++;
            }
        }


        return $horariosValidados;
    }

    /**
     * Obtiene horarios con filtro ULTRA-ESTRICTO
     * Este método es para casos donde se necesita máxima precisión
     * Solo muestra horarios que estén 100% activos en la semana seleccionada
     * @param string $semanaSelector Selector de semana (YYYY-WW) o null para semana actual
     * @return array Array de horarios con filtro ultra-estricto
     */
    public function getAllUltraEstricto($semanaSelector = null)
    {
        $res = null;

        // SQL con filtro ULTRA-ESTRICTO
        // Solo horarios que estén 100% activos en la semana seleccionada
        // Incluye tanto horarios con rango (fecha_inicio/fecha_fin) como individuales (solo fecha_especifica)
        $sql = 'SELECT h.idhor, h.idfic, h.idaul, a.nomaul, a.codubi, a.idcen, h.idusu, 
                       u.ndocusu, u.nomusu, u.colfon, u.coltex, h.iddia,
                       h.es_otros, h.actividad, h.horas_otros,
                       h.es_transversal, h.nombre_transversal,
                       h.fecha_inicio, h.fecha_fin,
                       h.fecha_especifica
                FROM horario AS h 
                LEFT JOIN usuario AS u ON h.idusu = u.idusu 
                LEFT JOIN aula AS a ON h.idaul = a.idaul 
                WHERE h.idfic = :idfic 
                AND h.iddia = :iddia
                AND h.fecha_especifica IS NOT NULL
                AND h.fecha_especifica BETWEEN :semana_inicio AND :semana_fin
                AND (
                    (h.fecha_inicio IS NOT NULL AND h.fecha_fin IS NOT NULL) OR
                    (h.fecha_inicio IS NULL AND h.fecha_fin IS NULL)
                )';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);

        $idfic = $this->getIdfic();
        $result->bindParam(':idfic', $idfic);

        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);

        // Calcular semana (actual o especificada)
        $semanaInicio = $this->getInicioSemana($semanaSelector);
        $semanaFin = $this->getFinSemana($semanaSelector);
        
        $result->bindParam(':semana_inicio', $semanaInicio);
        $result->bindParam(':semana_fin', $semanaFin);

        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        
        return $res;
    }

    /**
     * Método de prueba para verificar que el filtro esté funcionando correctamente
     * Este método simula exactamente lo que hace la vista para identificar problemas
     * @param string $semanaSelector Selector de semana (YYYY-WW) o null para semana actual
     * @return array Información de prueba del filtro
     */
    public function probarFiltro($semanaSelector = null)
    {
        $resultado = [
            'semana_selector' => $semanaSelector,
            'fechas_semana' => [],
            'ficha' => $this->getIdfic(),
            'dia' => $this->getIddia(),
            'horarios_encontrados' => [],
            'resumen' => []
        ];

        try {
            // Calcular fechas de la semana
            $semanaInicio = $this->getInicioSemana($semanaSelector);
            $semanaFin = $this->getFinSemana($semanaSelector);
            $resultado['fechas_semana'] = [
                'inicio' => $semanaInicio,
                'fin' => $semanaFin
            ];

            // Obtener horarios con el filtro corregido
            $horarios = $this->getAll($semanaSelector);
            $resultado['horarios_encontrados'] = $horarios;

            // Analizar cada horario encontrado
            foreach ($horarios as $horario) {
                $fechaEspecifica = $horario['fecha_especifica'];
                $fechaInicio = $horario['fecha_inicio'];
                $fechaFin = $horario['fecha_fin'];
                
                // Verificar que la fecha_especifica esté en la semana
                $enSemana = ($fechaEspecifica >= $semanaInicio && $fechaEspecifica <= $semanaFin);
                
                // Verificar que el día de la semana coincida
                $fechaDT = new DateTime($fechaEspecifica);
                $diaSemana = $fechaDT->format('N'); // 1=Lunes, 2=Martes, etc.
                
                // Mapear ID del día a día de la semana
                $mapeoDias = [
                    1042 => 1, // Lunes
                    1043 => 2, // Martes
                    1044 => 3, // Miércoles
                    1045 => 4, // Jueves
                    1046 => 5, // Viernes
                    1047 => 6, // Sábado
                    1048 => 7  // Domingo
                ];
                
                $diaEsperado = $mapeoDias[$this->getIddia()] ?? 1;
                $diaCoincide = ($diaSemana == $diaEsperado);
                
                $resultado['resumen'][] = [
                    'id_horario' => $horario['idhor'],
                    'fecha_especifica' => $fechaEspecifica,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'dia_semana' => $diaSemana,
                    'dia_esperado' => $diaEsperado,
                    'en_semana' => $enSemana,
                    'dia_coincide' => $diaCoincide,
                    'valido' => $enSemana && $diaCoincide
                ];
            }

        } catch (Exception $e) {
            error_log("Error en probarFiltro: " . $e->getMessage());
            $resultado['error'] = $e->getMessage();
        }

        return $resultado;
    }


    /**
     * Obtiene todos los horarios de un instructor específico
     * Esta función es específica para el cálculo por rango
     * @param string $idInstructor ID del instructor
     * @return array Array de horarios del instructor
     */
    public function getHorariosPorInstructor($idInstructor)
    {
        try {
            $sql = 'SELECT h.idhor, h.idfic, h.idaul, h.idusu, h.iddia, h.fecha_inicio, h.fecha_fin, h.fecha_especifica,
                           h.es_otros, h.actividad, h.horas_otros, h.es_transversal, h.nombre_transversal, h.es_formacion_directa,
                           f.nomfic, f.jornada, vj.nomval as nombre_jornada, vd.nomval as nombre_dia, a.nomaul, u.ndocusu, u.nomusu
                    FROM horario AS h 
                    LEFT JOIN ficha AS f ON h.idfic = f.idfic
                    LEFT JOIN valor AS vj ON f.jornada = vj.idval
                    LEFT JOIN valor AS vd ON h.iddia = vd.idval
                    LEFT JOIN aula AS a ON h.idaul = a.idaul
                    LEFT JOIN usuario AS u ON h.idusu = u.idusu
                    WHERE h.idusu = :idInstructor
                    AND h.fecha_especifica IS NOT NULL
                    ORDER BY h.fecha_especifica ASC';

            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idInstructor', $idInstructor);
            $result->execute();
            
            $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
            
            return $horarios;
            
        } catch (Exception $e) {
            error_log("Error en getHorariosPorInstructor: " . $e->getMessage());
            return [];
        }
    }

    public function getOne()
    {
        $res      = null;
        $sql      = 'SELECT * FROM horario WHERE idhor=:idhor';
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $idhor    = $this->getIdhor();
        $result->bindParam(':idhor', $idhor);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function getFicha()
    {
        $sql      = 'SELECT f.idfic, f.nomfic, v.nomval, f.codpro, a.nomare FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval LEFT JOIN programa AS p ON f.codpro=p.codpro LEFT JOIN area AS a ON p.idare=a.idare WHERE f.idfic>1000';
        $modelo   = new conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function getDia()
    {
        $sql      = 'SELECT idval, nomval, parval FROM valor WHERE act=1 AND iddom=14 ORDER BY parval';
        $modelo   = new conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function getInst()
    {
        $sql      = 'SELECT p.idusu, p.ndocusu, p.nomusu, p.idper, p.emausu,  p.actusu FROM usuario AS p INNER JOIN usupef AS f ON p.idusu=f.idusu WHERE f.idper IN (7,6,12) ORDER BY p.nomusu';
        $modelo   = new conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function getAula()
    {
        $sql      = 'SELECT idaul, nomaul FROM aula ORDER BY idaul';
        $modelo   = new conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function getArea()
    {
        $sql      = 'SELECT idare, nomare, idusu FROM area';
        $modelo   = new conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function getFiltro($fec = null)
    {
        $sql = 'SELECT f.idfic, f.nomfic, v.nomval, f.codpro, a.idare, a.nomare FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval 
		LEFT JOIN programa AS p ON f.codpro=p.codpro LEFT JOIN area AS a ON p.idare=a.idare WHERE f.idfic>1000 AND a.idare=:idare';

        // Si hay una ficha específica establecida (para perfiles restringidos), filtrar por ella
        $idfic_especifica = $this->getIdfic();
        if ($idfic_especifica && $idfic_especifica > 1000) {
            $sql .= " AND f.idfic = :idfic_especifica";
        }

        if ($fec) {
            $sql .= " AND '".$fec."'<=f.ffinfic";
        }
        $modelo   = new conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $idare    = $this->getIdare();
        $result->bindParam(':idare', $idare);
        
        // Si hay ficha específica, agregar el parámetro
        if ($idfic_especifica && $idfic_especifica > 1000) {
            $result->bindParam(':idfic_especifica', $idfic_especifica);
        }
        
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);

        return $res;
    }
    public function save()
    {
        // SQL actualizado para incluir los nuevos campos de "Otros", "Transversales", fechas y formación directa
        $sql = 'INSERT INTO horario (idfic, idaul, idusu, iddia, es_otros, actividad, horas_otros, es_formacion_directa, es_transversal, nombre_transversal, fecha_inicio, fecha_fin, horas_mes_actual) 
                VALUES (:idfic, :idaul, :idusu, :iddia, :es_otros, :actividad, :horas_otros, :es_formacion_directa, :es_transversal, :nombre_transversal, :fecha_inicio, :fecha_fin, :horas_mes_actual)';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);

        $idfic = $this->getIdfic();
        $result->bindParam(':idfic', $idfic);

        $idaul = $this->getIdaul();
        $result->bindParam(':idaul', $idaul, PDO::PARAM_INT);

        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);

        $idusu = $this->getIdusu();
        $result->bindParam(':idusu', $idusu);

        // Vincular parámetros de "Otros"
        $es_otros = $this->getEsOtros() ? 1 : 0;
        $result->bindParam(':es_otros', $es_otros);

        $actividad = $this->getActividad();
        $result->bindParam(':actividad', $actividad);

        $horas_otros = $this->getHorasOtros();
        $result->bindParam(':horas_otros', $horas_otros);

        $es_formacion_directa = $this->getEsFormacionDirecta();
        // Asegurar que no sea null, usar 1 como valor por defecto
        if ($es_formacion_directa === null || $es_formacion_directa === '') {
            $es_formacion_directa = 1;
        }
        $result->bindParam(':es_formacion_directa', $es_formacion_directa);

        // Vincular nuevos parámetros de "Transversales"
        $es_transversal = $this->getEsTransversal() ? 1 : 0;
        $result->bindParam(':es_transversal', $es_transversal);

        $nombre_transversal = $this->getNombreTransversal();
        $result->bindParam(':nombre_transversal', $nombre_transversal);

        // Vincular nuevos parámetros de fechas
        $fecha_inicio = $this->getFechaInicio();
        $result->bindParam(':fecha_inicio', $fecha_inicio);

        $fecha_fin = $this->getFechaFin();
        $result->bindParam(':fecha_fin', $fecha_fin);

        $horas_mes_actual = $this->getHorasMesActual();
        $result->bindParam(':horas_mes_actual', $horas_mes_actual);

        $result->execute();
    }
    public function edit()
    {
        $sql = 'UPDATE horario SET idfic=:idfic, idaul=:idaul, idusu=:idusu, iddia=:iddia, 
                es_otros=:es_otros, actividad=:actividad, horas_otros=:horas_otros, es_formacion_directa=:es_formacion_directa,
                es_transversal=:es_transversal, nombre_transversal=:nombre_transversal 
                WHERE idhor=:idhor';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);

        $idhor = $this->getIdhor();
        $result->bindParam(':idhor', $idhor);

        $idfic = $this->getIdfic();
        $result->bindParam(':idfic', $idfic);

        $idaul = $this->getIdaul();
        $result->bindParam(':idaul', $idaul, PDO::PARAM_INT);

        $idusu = $this->getIdusu();
        $result->bindParam(':idusu', $idusu);

        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);

        // Campos de "Otros"
        $es_otros = $this->getEsOtros() ? 1 : 0;
        $result->bindParam(':es_otros', $es_otros);

        $actividad = $this->getActividad();
        $result->bindParam(':actividad', $actividad);

        $horas_otros = $this->getHorasOtros();
        $result->bindParam(':horas_otros', $horas_otros);

        $es_formacion_directa = $this->getEsFormacionDirecta();
        // Asegurar que no sea null, usar 1 como valor por defecto
        if ($es_formacion_directa === null || $es_formacion_directa === '') {
            $es_formacion_directa = 1;
        }
        $result->bindParam(':es_formacion_directa', $es_formacion_directa);

        // Nuevos campos de "Transversales"
        $es_transversal = $this->getEsTransversal() ? 1 : 0;
        $result->bindParam(':es_transversal', $es_transversal);

        $nombre_transversal = $this->getNombreTransversal();
        $result->bindParam(':nombre_transversal', $nombre_transversal);

        $result->execute();
    }

    public function del()
    {
        $sql      = 'DELETE FROM horario WHERE idhor=:idhor AND iddia=:iddia';
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $idhor    = $this->getIdhor();
        $result->bindParam(':idhor', $idhor);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $result->execute();
    }

    /**
     * Elimina un grupo completo de horarios (misma ficha, día, fechas, aula e instructor)
     * @param int $idfic ID de la ficha
     * @param int $iddia ID del día
     * @param int $idusu ID del instructor
     * @param int $idaul ID del aula
     * @param string $fecha_inicio Fecha de inicio del grupo
     * @param string $fecha_fin Fecha de fin del grupo
     */
    public function delGrupo($idfic, $iddia, $idusu, $idaul, $fecha_inicio, $fecha_fin)
    {
        // Construir SQL dinámicamente para manejar valores NULL correctamente
        $sql = 'DELETE FROM horario WHERE idfic=:idfic AND iddia=:iddia AND fecha_inicio=:fecha_inicio AND fecha_fin=:fecha_fin';
        $params = array();
        
        // Agregar condiciones para idusu (manejar NULL y valores vacíos)
        if ($idusu === null || $idusu === '') {
            $sql .= ' AND (idusu IS NULL OR idusu = "")';
        } else {
            $sql .= ' AND idusu=:idusu';
            $params[':idusu'] = $idusu;
        }
        
        // Agregar condiciones para idaul (manejar NULL y valores vacíos)
        if ($idaul === null || $idaul === '') {
            $sql .= ' AND (idaul IS NULL OR idaul = "")';
        } else {
            $sql .= ' AND idaul=:idaul';
            $params[':idaul'] = $idaul;
        }
        
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        
        // Bind de parámetros obligatorios
        $result->bindParam(':idfic', $idfic);
        $result->bindParam(':iddia', $iddia);
        $result->bindParam(':fecha_inicio', $fecha_inicio);
        $result->bindParam(':fecha_fin', $fecha_fin);
        
        // Bind de parámetros opcionales
        foreach ($params as $key => $value) {
            $result->bindValue($key, $value);
        }
        
        $result->execute();
        
        $filasAfectadas = $result->rowCount();
        
        return $filasAfectadas;
    }

    /**
     * Cuenta cuántos horarios coinciden con los criterios del grupo
     * @param int $idfic ID de la ficha
     * @param int $iddia ID del día
     * @param int $idusu ID del usuario/instructor
     * @param int $idaul ID del aula
     * @param string $fecha_inicio Fecha de inicio
     * @param string $fecha_fin Fecha de fin
     * @return int Cantidad de horarios que coinciden
     */
    public function contarHorariosGrupo($idfic, $iddia, $idusu, $idaul, $fecha_inicio, $fecha_fin)
    {
        
        // Construir SQL dinámicamente para manejar valores NULL correctamente
        $sql = 'SELECT COUNT(*) as total FROM horario WHERE idfic=:idfic AND iddia=:iddia AND fecha_inicio=:fecha_inicio AND fecha_fin=:fecha_fin';
        $params = array();
        
        // Agregar condiciones para idusu (manejar NULL y valores vacíos)
        if ($idusu === null || $idusu === '') {
            $sql .= ' AND (idusu IS NULL OR idusu = "")';
        } else {
            $sql .= ' AND idusu=:idusu';
            $params[':idusu'] = $idusu;
        }
        
        // Agregar condiciones para idaul (manejar NULL y valores vacíos)
        if ($idaul === null || $idaul === '') {
            $sql .= ' AND (idaul IS NULL OR idaul = "")';
        } else {
            $sql .= ' AND idaul=:idaul';
            $params[':idaul'] = $idaul;
        }
        
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        
        // Bind de parámetros obligatorios
        $result->bindParam(':idfic', $idfic);
        $result->bindParam(':iddia', $iddia);
        $result->bindParam(':fecha_inicio', $fecha_inicio);
        $result->bindParam(':fecha_fin', $fecha_fin);
        
        // Bind de parámetros opcionales
        foreach ($params as $key => $value) {
            $result->bindValue($key, $value);
        }
        
        $result->execute();
        
        $res = $result->fetch(PDO::FETCH_ASSOC);
        $total = (int)(isset($res['total']) ? $res['total'] : 0);
        
        return $total;
    }

    /**
     * Verifica si un horario pertenece a un grupo (tiene fecha_inicio y fecha_fin)
     * @return bool True si pertenece a un grupo, False si es individual
     */
    public function perteneceAGrupo()
    {
        $sql = 'SELECT fecha_inicio, fecha_fin FROM horario WHERE idhor=:idhor';
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $idhor    = $this->getIdhor();
        $result->bindParam(':idhor', $idhor);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        
        return $res && !empty($res['fecha_inicio']) && !empty($res['fecha_fin']);
    }

    public function getOneF()
    {
        $res      = null;
        $sql      = 'SELECT idage, idfic, idusu, idres, fchinc, fchfnl FROM agenda WHERE idfic=:idfic';
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $idfic    = $this->getIdfic();
        $result->bindParam(':idfic', $idfic);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);

        return $res;
    }



    /**
     * Verifica si ya existe un horario para la misma ficha en el mismo día
     * Esta función verifica que no se duplique el horario de una ficha en el mismo día
     */
    public function verificarHorarioExistente($idfic, $iddia, $fecha = null)
    {
        // Consulta para verificar si ya existe un horario para esta ficha en este día
        $sql = 'SELECT idhor FROM horario WHERE idfic = :idfic AND iddia = :iddia';
        
        // Agregar filtro de fecha específica si se proporciona
        if ($fecha) {
            // Solo verificar horarios con fecha_especifica exacta, no horarios de rango
            $sql .= " AND fecha_especifica = :fecha_especifica";
        }
        
        $sql .= ' LIMIT 1';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->bindParam(':idfic', $idfic);
        $result->bindParam(':iddia', $iddia);
        
        if ($fecha) {
            $result->bindParam(':fecha_especifica', $fecha);
        }
        
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);

        return $res ? $res['idhor'] : false;
    }

    /**
     * Verifica si el instructor ya está asignado en la misma franja horaria (día + jornada)
     * Un instructor no puede estar asignado dos veces en el mismo día y misma jornada
     */
    public function verificarConflictoInstructor($idusu, $idfic, $iddia, $fecha = null)
    {
        // Obtener la jornada de la ficha que se está intentando asignar
        $sqlJornada = 'SELECT jornada FROM ficha WHERE idfic = :idfic';
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $resultJornada = $conexion->prepare($sqlJornada);
        $resultJornada->bindParam(':idfic', $idfic);
        $resultJornada->execute();
        $fichaJornada = $resultJornada->fetch(PDO::FETCH_ASSOC);
        
        if (!$fichaJornada) {
            return false; // Si no se encuentra la ficha, no hay conflicto
        }
        
        $jornadaFicha = $fichaJornada['jornada'];
        
        // Verificar conflictos con horarios existentes del instructor
        $sql = 'SELECT COUNT(*) as total FROM horario h 
                INNER JOIN ficha f ON h.idfic = f.idfic 
                WHERE h.idusu = :idusu 
                AND h.iddia = :iddia 
                AND f.jornada = :jornada';

        // Agregar filtro de fecha específica si se proporciona
        if ($fecha) {
            // Solo verificar horarios con fecha_especifica exacta, no horarios de rango
            $sql .= " AND h.fecha_especifica = :fecha_especifica";
        }

        $result = $conexion->prepare($sql);
        $result->bindParam(':idusu', $idusu);
        $result->bindParam(':iddia', $iddia);
        $result->bindParam(':jornada', $jornadaFicha);
        
        if ($fecha) {
            $result->bindParam(':fecha_especifica', $fecha);
        }
        
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        
        $hayConflicto = $res['total'] > 0;
        error_log("DEBUG verificarConflictoInstructor: idusu=$idusu, iddia=$iddia, jornada=$jornadaFicha, fecha=$fecha, total_conflictos=" . $res['total'] . ", hayConflicto=" . ($hayConflicto ? 'true' : 'false'));

        return $hayConflicto;
    }

    /**
     * Verifica si el aula ya está ocupada en la misma franja horaria (día + jornada)
     * Un aula no puede ser usada por dos fichas en el mismo día y misma jornada
     */
    public function verificarConflictoAula($idaul, $idfic, $iddia, $fecha = null)
    {
        // Obtener la jornada de la ficha que se está intentando asignar
        $sqlJornada = 'SELECT jornada FROM ficha WHERE idfic = :idfic';
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $resultJornada = $conexion->prepare($sqlJornada);
        $resultJornada->bindParam(':idfic', $idfic);
        $resultJornada->execute();
        $fichaJornada = $resultJornada->fetch(PDO::FETCH_ASSOC);
        
        if (!$fichaJornada) {
            return false; // Si no se encuentra la ficha, no hay conflicto
        }
        
        $jornadaFicha = $fichaJornada['jornada'];
        
        // Verificar conflictos con horarios existentes del aula
        $sql = 'SELECT COUNT(*) as total FROM horario h 
                INNER JOIN ficha f ON h.idfic = f.idfic 
                WHERE h.idaul = :idaul 
                AND h.iddia = :iddia 
                AND f.jornada = :jornada';

        // Agregar filtro de fecha específica si se proporciona
        if ($fecha) {
            // Solo verificar horarios con fecha_especifica exacta, no horarios de rango
            $sql .= " AND h.fecha_especifica = :fecha_especifica";
        }

        $result = $conexion->prepare($sql);
        $result->bindParam(':idaul', $idaul);
        $result->bindParam(':iddia', $iddia);
        $result->bindParam(':jornada', $jornadaFicha);
        
        if ($fecha) {
            $result->bindParam(':fecha_especifica', $fecha);
        }
        
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);

        return $res['total'] > 0;
    }



    /**
     * Función auxiliar para obtener el nombre del día
     */
    public function obtenerNombreDia($iddia)
    {
        $sql = 'SELECT nomval FROM valor WHERE idval = :iddia AND iddom = 14 LIMIT 1';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->bindParam(':iddia', $iddia);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);

        return $res ? $res['nomval'] : 'Día ' . $iddia;
    }

    /**
     * Calcula y guarda las horas mensuales para un instructor
     */
    public function calcularYGuardarHorasMensuales($idInstructor, $mes, $año)
    {
        try {
            // Calcular horas totales del mes para este instructor
            $horasMes = $this->calcularHorasMesInstructor($idInstructor, $mes, $año);
            
            // Guardar o actualizar en la tabla horas_mensuales
            $sql = 'INSERT INTO horas_mensuales (id_instructor, mes, año, horas_totales) 
                    VALUES (:id_instructor, :mes, :año, :horas_totales)
                    ON DUPLICATE KEY UPDATE 
                    horas_totales = :horas_totales,
                    fecha_actualizacion = CURRENT_TIMESTAMP';
            
            $modelo   = new Conexion();
            $conexion = $modelo->get_conexion();
            $result   = $conexion->prepare($sql);
            
            $result->bindParam(':id_instructor', $idInstructor);
            $result->bindParam(':mes', $mes);
            $result->bindParam(':año', $año);
            $result->bindParam(':horas_totales', $horasMes);
            
            return $result->execute();
            
        } catch (Exception $e) {
            error_log("Error en calcularYGuardarHorasMensuales: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcula las horas de un mes específico para un instructor
     */
    private function calcularHorasMesInstructor($idInstructor, $mes, $año)
    {
        try {
            // Obtener horarios del instructor en el mes especificado
            $sql = 'SELECT h.*, f.jornada, v.nomval as nombre_jornada
                    FROM horario h
                    INNER JOIN ficha f ON h.idfic = f.idfic
                    INNER JOIN valor v ON f.jornada = v.idval
                    WHERE h.idusu = :id_instructor 
                    AND MONTH(h.fecha_inicio) <= :mes 
                    AND MONTH(h.fecha_fin) >= :mes
                    AND YEAR(h.fecha_inicio) = :año';
            
            $modelo   = new Conexion();
            $conexion = $modelo->get_conexion();
            $result   = $conexion->prepare($sql);
            
            $result->bindParam(':id_instructor', $idInstructor);
            $result->bindParam(':mes', $mes);
            $result->bindParam(':año', $año);
            
            $result->execute();
            $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $horasTotales = 0;
            
            foreach ($horarios as $horario) {
                // Calcular horas por horario según el tipo
                if ($horario['es_transversal'] || $horario['es_otros'] == 0) {
                    // Horario normal o transversal: usar jornada de la ficha
                    $horasJornada = $this->obtenerHorasJornada($horario['jornada']);
                    $horasTotales += $horasJornada;
                } else {
                    // Actividad "otros": usar horas_otros
                    $horasTotales += $horario['horas_otros'] ?? 0;
                }
            }
            
            return $horasTotales;
            
        } catch (Exception $e) {
            error_log("Error en calcularHorasMesInstructor: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene las horas de una jornada específica
     */
    private function obtenerHorasJornada($jornada)
    {
        // Mapeo de jornadas a horas (ajustar según tu sistema)
        $jornadas = [
            1042 => 8,  // Mañana
            1043 => 8,  // Tarde
            1044 => 8   // Noche
        ];
        
        return $jornadas[$jornada] ?? 8; // Default 8 horas
    }

    /**
     * Obtiene las horas mensuales guardadas para un instructor
     */
    public function obtenerHorasMensuales($idInstructor, $mes, $año)
    {
        try {
            $sql = 'SELECT horas_totales FROM horas_mensuales 
                    WHERE id_instructor = :id_instructor 
                    AND mes = :mes AND año = :año';
            
            $modelo   = new Conexion();
            $conexion = $modelo->get_conexion();
            $result   = $conexion->prepare($sql);
            
            $result->bindParam(':id_instructor', $idInstructor);
            $result->bindParam(':mes', $mes);
            $result->bindParam(':año', $año);
            
            $result->execute();
            $res = $result->fetch(PDO::FETCH_ASSOC);
            
            return $res ? $res['horas_totales'] : 0;
            
        } catch (Exception $e) {
            error_log("Error en obtenerHorasMensuales: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtiene horarios específicamente para la fecha de hoy
     * Este método es para cuando el usuario quiere ver solo los horarios de hoy
     * @return array Array de horarios para la fecha actual
     */
    public function getHorariosHoy()
    {
        $res = null;

        // SQL para horarios de HOY específicamente
        $sql = 'SELECT h.idhor, h.idfic, h.idaul, a.nomaul, a.codubi, a.idcen, h.idusu, 
                       u.ndocusu, u.nomusu, u.colfon, u.coltex, h.iddia,
                       h.es_otros, h.actividad, h.horas_otros,
                       h.es_transversal, h.nombre_transversal,
                       h.fecha_inicio, h.fecha_fin,
                       h.fecha_especifica
                FROM horario AS h 
                LEFT JOIN usuario AS u ON h.idusu = u.idusu 
                LEFT JOIN aula AS a ON h.idaul = a.idaul 
                WHERE h.idfic = :idfic 
                AND h.iddia = :iddia
                AND h.fecha_especifica = :fecha_hoy';

        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);

        $idfic = $this->getIdfic();
        $result->bindParam(':idfic', $idfic);

        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);

        // Fecha de hoy en zona horaria de Colombia
        $fechaHoy = date('Y-m-d');
        $result->bindParam(':fecha_hoy', $fechaHoy);
        
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);

        return $res;
    }

    /**
     * Obtiene todos los tipos de eventos disponibles del calendario académico
     * @return array Array con los tipos de eventos únicos
     */
    public function getTiposEventos() {
        try {
            $sql = "SELECT DISTINCT tipo_evento FROM calendario_academico ORDER BY tipo_evento";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $tipos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tipos[] = $row['tipo_evento'];
            }
            
            return $tipos;
        } catch (Exception $e) {
            error_log("Error en getTiposEventos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene eventos por tipo y año, ordenados por año más reciente
     * @param string $tipoEvento Tipo de evento a filtrar (opcional)
     * @param int $anio Año específico (opcional)
     * @return array Array con los eventos filtrados
     */
    public function getEventosPorTipo($tipoEvento = null, $anio = null) {
        try {
            $sql = "SELECT id, titulo, descripcion, fecha_inicio, fecha_fin, tipo_evento, anio, trimestre 
                    FROM calendario_academico 
                    WHERE 1=1";
            
            $params = [];
            
            if ($tipoEvento) {
                $sql .= " AND tipo_evento = ?";
                $params[] = $tipoEvento;
            }
            
            if ($anio) {
                $sql .= " AND anio = ?";
                $params[] = $anio;
            }
            
            $sql .= " ORDER BY anio DESC, fecha_inicio ASC";
            
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);
            
            $eventos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eventos[] = [
                    'id' => $row['id'],
                    'titulo' => $row['titulo'],
                    'descripcion' => $row['descripcion'],
                    'fecha_inicio' => $row['fecha_inicio'],
                    'fecha_fin' => $row['fecha_fin'],
                    'tipo_evento' => $row['tipo_evento'],
                    'anio' => $row['anio'],
                    'trimestre' => $row['trimestre']
                ];
            }
            
            return $eventos;
        } catch (Exception $e) {
            error_log("Error en getEventosPorTipo: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene un evento específico por ID
     * @param int $id ID del evento
     * @return array|null Datos del evento o null si no existe
     */
    public function getEventoPorId($id) {
        try {
            $sql = "SELECT id, titulo, descripcion, fecha_inicio, fecha_fin, tipo_evento, anio, trimestre 
                    FROM calendario_academico 
                    WHERE id = ?";
            
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id]);
            
            $evento = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($evento) {
                return [
                    'id' => $evento['id'],
                    'titulo' => $evento['titulo'],
                    'descripcion' => $evento['descripcion'],
                    'fecha_inicio' => $evento['fecha_inicio'],
                    'fecha_fin' => $evento['fecha_fin'],
                    'tipo_evento' => $evento['tipo_evento'],
                    'anio' => $evento['anio'],
                    'trimestre' => $evento['trimestre']
                ];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Error en getEventoPorId: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Formatea un tipo de evento para mostrar en la interfaz
     * @param string $tipoEvento Tipo de evento de la base de datos
     * @return string Tipo de evento formateado para mostrar
     */
    public function formatearTipoEvento($tipoEvento) {
        $formateos = [
            'inicio_clases' => 'Inicio de Clases',
            'receso' => 'Receso Académico',
            'examen' => 'Período de Exámenes',
            'festivo' => 'Festivo',
            'evento' => 'Evento Especial',
            'trimestre' => 'Trimestre Académico',
            'alistamiento' => 'Alistamiento',
            'balance' => 'Balance'
        ];
        
        return $formateos[$tipoEvento] ?? ucfirst(str_replace('_', ' ', $tipoEvento));
    }
}


class Mfilfav
{
    public function guardarFiltroFavorito($idusu, $idare)
    {
        $sql = 'INSERT INTO filhor (idusu, idare) 
                VALUES (:idusu, :idare) 
                ON DUPLICATE KEY UPDATE idare = :idare';
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->bindParam(':idusu', $idusu);
        $result->bindParam(':idare', $idare);

        return $result->execute();
    }

    public function obtenerAreasFavoritas($idusu)
    {
        $sql      = 'SELECT idare FROM filhor WHERE idusu = :idusu';
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $result   = $conexion->prepare($sql);
        $result->bindParam(':idusu', $idusu);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_ASSOC);

    }
}
