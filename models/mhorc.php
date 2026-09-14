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
            
            // Verificar si ya existe la asignación
            $checkSql = "SELECT COUNT(*) FROM hdtxusu WHERE idnorad = :idnorad AND idusu = :idusu";
            $checkStmt = $conexion->prepare($checkSql);
            $checkStmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $checkStmt->bindParam(':idusu', $idusu, PDO::PARAM_INT);
            $checkStmt->execute();
            if ($checkStmt->fetchColumn() > 0) {
                return true; // Ya está asignado
            }

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
 
    public function del() {
        $sql = "DELETE FROM horario WHERE id=:iddia AND hinihor=:hinihor AND hfinhor=:hfinhor";
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
        $sql = "SELECT * FROM horarios WHERE id=:iddia and hinihor=:hinihor and hfinhor=:hfinhor";
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
        $sql = "SELECT * FROM horario WHERE idnorad=:idnorad";
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
}