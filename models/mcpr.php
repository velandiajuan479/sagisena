<?php
require_once 'models/conexion.php';

class Mcpr {
    private $idcom;
    private $idval;
    private $p1com;
    private $p2com;

    // Métodos Get
    public function getIdcom() { return $this->idcom; }
    public function getIdval() { return $this->idval; }
    public function getP1com() { return $this->p1com; }
    public function getP2com() { return $this->p2com; }

    // Métodos Set
    public function setIdcom($idcom) { $this->idcom = $idcom; }
    public function setIdval($idval) { $this->idval = $idval; }
    public function setP1com($p1com) { $this->p1com = $p1com; }
    public function setP2com($p2com) { $this->p2com = $p2com; }

    public function getAll() {
        try {
            $sql = "SELECT c.idcom, c.p1com, c.p2com, d.desdtc, d.detfech 
                    FROM compromiso c 
                    LEFT JOIN detcom d ON c.idcom = d.idcom";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getOne($idcom) {
        try {
            $sql = "SELECT c.idcom, c.p1com, c.p2com, d.desdtc, d.detfech 
                    FROM compromiso c 
                    LEFT JOIN detcom d ON c.idcom = d.idcom 
                    WHERE c.idcom = :idcom LIMIT 1";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idcom', $idcom, PDO::PARAM_INT);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en getOne: " . $e->getMessage());
            return null;
        }
    }

    public function save($com_type, $desdtc, $detfech) {
        try {
            $sql = "INSERT INTO compromiso (idcom, idval, p1com, p2com) VALUES (NULL, :idval, :p1com, :p2com)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idval = 1;
            $p1com = ($com_type == 'p1com') ? 'MI COMPROMISO COMO APRENDIZ SENA' : null;
            $p2com = ($com_type == 'p2com') ? 'TRATAMIENTO DE DATOS MENOR DE EDAD' : null;
            $result->bindParam(':idval', $idval);
            $result->bindParam(':p1com', $p1com);
            $result->bindParam(':p2com', $p2com);
            $result->execute();
            $idcom = $conexion->lastInsertId();

            $sql_detcom = "INSERT INTO detcom (iddtc, idcom, desdtc, detfech) VALUES (NULL, :idcom, :desdtc, :detfech)";
            $result_detcom = $conexion->prepare($sql_detcom);
            $result_detcom->bindParam(':idcom', $idcom);
            $result_detcom->bindParam(':desdtc', $desdtc);
            $result_detcom->bindParam(':detfech', $detfech);
            $result_detcom->execute();

            return $idcom;
        } catch (Exception $e) {
            error_log("Error en save: " . $e->getMessage());
            return null;
        }
    }

    public function edit($idcom, $com_type, $desdtc, $detfech) {
        try {
            $sql = "UPDATE compromiso c 
                    SET c.idval = :idval, c.p1com = :p1com, c.p2com = :p2com 
                    WHERE c.idcom = :idcom";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idval = 1;
            $p1com = ($com_type == 'p1com') ? 'MI COMPROMISO COMO APRENDIZ SENA' : null;
            $p2com = ($com_type == 'p2com') ? 'TRATAMIENTO DE DATOS MENOR DE EDAD' : null;
            $result->bindParam(':idcom', $idcom);
            $result->bindParam(':idval', $idval);
            $result->bindParam(':p1com', $p1com);
            $result->bindParam(':p2com', $p2com);
            $result->execute();

            $sql_detcom = "UPDATE detcom d 
                           SET d.desdtc = :desdtc, d.detfech = :detfech 
                           WHERE d.idcom = :idcom";
            $result_detcom = $conexion->prepare($sql_detcom);
            $result_detcom->bindParam(':idcom', $idcom);
            $result_detcom->bindParam(':desdtc', $desdtc);
            $result_detcom->bindParam(':detfech', $detfech);
            $result_detcom->execute();

            return $result->rowCount() > 0 || $result_detcom->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error en edit: " . $e->getMessage());
            return false;
        }
    }

    public function del($idcom) {
        try {
            $sql = "DELETE c, d FROM compromiso c 
                    LEFT JOIN detcom d ON c.idcom = d.idcom 
                    WHERE c.idcom = :idcom";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idcom', $idcom);
            $result->execute();
            return $result->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error en del: " . $e->getMessage());
            return false;
        }
    }
}
?>