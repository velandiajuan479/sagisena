<?php
class Mhorc {

    private $iddia;
    private $hinihor;
    private $hfinhor;

    // Getters
    

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

    public function setIddia($iddia) {
        $this->iddia = $iddia;
    }

    public function setHinihor($hinihor) {
        $this->hinihor = $hinihor;
    }

    public function setHfinhor($hfinhor) {
        $this->hfinhor = $hfinhor;
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