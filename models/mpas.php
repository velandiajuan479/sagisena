<?php
class Mpas {

    private $idpas;
    private $idflu;
    private $descpas;
    private $idper;

    // Getters
    function getIdpas() {
        return $this->idpas;
    }
    function getIdflu() {
        return $this->idflu;
    }
    function getDescpas() {
        return $this->descpas;
    }
    function getIdper() {
        return $this->idper;
    }

    // Setters
    function setIdpas($idpas) {
        $this->idpas = $idpas;
    }
    function setIdflu($idflu) {
        $this->idflu = $idflu;
    }
    function setDescpas($descpas) {
        $this->descpas = $descpas;
    }
    function setIdper($idper) {
        $this->idper = $idper;
    }

  
    public function getAll() {
        try {
            $sql = "SELECT p.idpas, p.idflu, f.nomflu, p.descpas, p.idper, j.nomper FROM paso AS p INNER JOIN flujo as f ON p.idflu=f.idflu INNER JOIN perfil AS j ON p.idper=j.idper";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }


    public function getOne() {
        try {
            $sql = "SELECT p.idpas, p.idflu, f.nomflu, p.descpas, p.idper, j.nomper FROM paso AS p INNER JOIN flujo as f ON p.idflu=f.idflu INNER JOIN perfil AS j ON p.idper=j.idper WHERE p.idpas=:idpas";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idpas = $this->getIdpas();
            $result->bindParam(":idpas", $idpas);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }

    
    public function save() {
        try {
            $sql = "INSERT INTO paso (idflu, descpas, idper) VALUES (:idflu, :descpas, :idper)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idflu = $this->getIdflu();
            $descpas = $this->getDescpas();
            $idper = $this->getIdper();
            $result->bindParam(":idflu", $idflu);
            $result->bindParam(":descpas", $descpas);
            $result->bindParam(":idper", $idper);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }

    
    public function edit() {
        try {
            $sql = "UPDATE paso SET idflu = :idflu, descpas = :descpas, idper = :idper WHERE idpas = :idpas";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idpas = $this->getIdpas();
            $idflu = $this->getIdflu();
            $descpas = $this->getDescpas();
            $idper = $this->getIdper();
            $result->bindParam(":idpas", $idpas);
            $result->bindParam(":idflu", $idflu);
            $result->bindParam(":descpas", $descpas);
            $result->bindParam(":idper", $idper);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }

    
    public function del() {
        try {
            $sql = "DELETE FROM paso WHERE idpas = :idpas";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idpas = $this->getIdpas();
            $result->bindParam(":idpas", $idpas);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }

    public function getAllFlu(){
        try {
            $sql = "SELECT idflu, nomflu FROM flujo WHERE fluacti=1";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }

    public function getAllPef(){
        try {
            $sql = "SELECT idper, nomper FROM perfil WHERE idmod IN (8,9)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuníquese con su administrador.";
        }
    }
}
?>