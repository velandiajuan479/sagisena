<?php
class Mhorc {

    private $iddia;
    private $hinihor;
    private $hfinhor;

    private $idnorad;

    // Getters
    public function getIdnorad() {
        return $this->idnorad;
    }

    public function getIddia() {
        return $this->iddia;
    }

    public function getHinihor() {
        return $this->hinihor;
    }

    public function getHfinhor() {
        return $this->hfinhor;
    }

    // Setters
    public function setIdnorad($idnorad) {
        $this->idnorad = $idnorad;
    }

    public function setIddia($iddia) {
        $this->iddia = $iddia;
    }

    public function setHinihor($hinihor) {
        $this->hinihor = $hinihor;
    }

    public function setHfinhor($hfinhor) {
        $this->hfinhor = $hfinhor;
    }

    // Instructores de la hoja de trabajo
    public function getInstructoresByHoja($idnorad) {
        try {
            $sql = "SELECT u.idusu, u.nomusu, u.ndocusu, u.emausu, u.telcan
                    FROM hdtxusu h
                    INNER JOIN usuario u ON h.idusu = u.idusu
                    WHERE h.idnorad = :idnorad
                    ORDER BY u.nomusu ASC";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return [];
        }
    }

    public function getAllInstructores() {
        try {
            $sql = "SELECT u.idusu, u.nomusu, u.ndocusu, u.emausu, u.telcan
                    FROM usuario u
                    INNER JOIN usupef up ON u.idusu = up.idusu
                    WHERE up.idper = 7 AND (u.actusu = 1 OR u.actusu IS NULL)
                    ORDER BY u.nomusu ASC";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return [];
        }
    }

    public function asignarInstructor($idnorad, $idusu) {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            
            // Primero eliminar cualquier instructor existente para esta hoja de trabajo (solo uno permitido)
            $deleteSql = "DELETE FROM hdtxusu WHERE idnorad = :idnorad";
            $deleteStmt = $conexion->prepare($deleteSql);
            $deleteStmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $deleteStmt->execute();

            // Insertar el nuevo instructor
            $sql = "INSERT INTO hdtxusu (idnorad, idusu) VALUES (:idnorad, :idusu)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt->bindParam(':idusu', $idusu, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return false;
        }
    }

    public function eliminarInstructor($idnorad, $idusu) {
        try {
            $sql = "DELETE FROM hdtxusu WHERE idnorad = :idnorad AND idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt->bindParam(':idusu', $idusu, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return false;
        }
    }

    public function updateComplementarios($idnorad, $idfic, $codslem, $convht) {
        try {
            $sql = "UPDATE hojatra SET idfic = :idfic, codslem = :codslem, convht = :convht WHERE idnorad = :idnorad";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt->bindParam(':idfic', $idfic);
            $stmt->bindParam(':codslem', $codslem);
            $stmt->bindParam(':convht', $convht);
            return $stmt->execute();
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return false;
        }
    }
 
    public function deleteHorarios($idnorad) {
        try {
            $sql = "DELETE FROM horario WHERE idnorad = :idnorad";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return false;
        }
    }

    public function saveHorario() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            // Usar fecha_inicio como campo para la fecha (iddia contiene la fecha)
            // Permitir múltiples horarios por idnorad usando INSERT directo
            $sql = "INSERT INTO horario (idnorad, fecha_inicio, hinihor, hfinhor) VALUES (:idnorad, :fecha_inicio, :hinihor, :hfinhor)";
            $result = $conexion->prepare($sql);

            $result->bindParam(':idnorad', $this->idnorad, PDO::PARAM_INT);
            $result->bindParam(':fecha_inicio', $this->iddia); // iddia contiene la fecha
            $result->bindParam(':hinihor', $this->hinihor);
            $result->bindParam(':hfinhor', $this->hfinhor);

            return $result->execute();
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return false;
        }
    }

    public function getAllVal($iddom) {
        $sql = "SELECT idval, nomval FROM valor WHERE iddom=:iddom";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':iddom', $iddom);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    // Sección de Horarios
    public function getHorario() {
        $sql = "SELECT * FROM horario WHERE fecha_inicio=:fecha_inicio and hinihor=:hinihor and hfinhor=:hfinhor";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $fecha_inicio = $this->getIddia();
        $result->bindParam(':fecha_inicio', $fecha_inicio);
        $hinihor = $this->getHinihor();
        $result->bindParam(':hinihor', $hinihor);
        $hfinhor = $this->getHfinhor();
        $result->bindParam(':hfinhor', $hfinhor);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function save() {
        try {
            $modelo = new conexion();
        $conexion = $modelo->get_conexion();
            $sql = "INSERT INTO horario (iddia, hinihor, hfinhor) VALUES (:iddia, :hinihor, :hfinhor)";
            $result = $conexion->prepare($sql);

            $result->bindParam(':iddia', $this->iddia);
            $result->bindParam(':hinihor', $this->hinihor);
            $result->bindParam(':hfinhor', $this->hfinhor);

            $result->execute();
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    public function edit() {
        $sql = "UPDATE horario SET iddia=:iddia, hinihor=:hinihor, hfinhor=:hfinhor WHERE id=:iddia";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $hinihor = $this->getHinihor();
        $result->bindParam(':hinihor', $hinihor);
        $hfinhor = $this->getHfinhor();
        $result->bindParam(':hfinhor', $hfinhor);
        $result->execute();
    }

    public function getAll() {
        $sql = "SELECT idhor, idnorad, fecha_inicio, hinihor, hfinhor FROM horario WHERE idnorad=:idnorad ORDER BY fecha_inicio, hinihor";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();    
        $result = $conexion->prepare($sql);
        $idnorad = $this->getIdnorad();
        $result->bindParam(':idnorad', $idnorad);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne() {
        $sql = "SELECT * FROM horario WHERE id=:iddia";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    // Generar horarios automáticos entre fecha inicial y final (Lunes a Viernes)
    public function generarHorarioAutomatico($idnorad, $fechaInicio, $fechaFin, $horaInicio, $horaFin) {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            
            // Eliminar horarios existentes para esta hoja de trabajo
            $deleteSql = "DELETE FROM horario WHERE idnorad = :idnorad";
            $deleteStmt = $conexion->prepare($deleteSql);
            $deleteStmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $deleteStmt->execute();
            
            // Iterar por cada día entre las fechas
            $fechaActual = new DateTime($fechaInicio);
            $fechaFinal = new DateTime($fechaFin);
            
            while ($fechaActual <= $fechaFinal) {
                // Obtener día de la semana (0 = Domingo, 6 = Sábado)
                $diaSemana = $fechaActual->format('N'); // 1 = Lunes, 7 = Domingo
                
                // Solo guardar de Lunes (1) a Viernes (5)
                if ($diaSemana >= 1 && $diaSemana <= 5) {
                    $sql = "INSERT INTO horario (idnorad, fecha_inicio, hinihor, hfinhor) 
                            VALUES (:idnorad, :fecha_inicio, :hinihor, :hfinhor)";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
                    $fechaFormateada = $fechaActual->format('Y-m-d');
                    $stmt->bindParam(':fecha_inicio', $fechaFormateada);
                    $stmt->bindParam(':hinihor', $horaInicio);
                    $stmt->bindParam(':hfinhor', $horaFin);
                    $stmt->execute();
                }
                
                // Avanzar un día
                $fechaActual->modify('+1 day');
            }
            
            return true;
        } catch (Exception $e) {
            if(function_exists('ManejoError')) {
                ManejoError($e);
            }
            return false;
        }
    }
}