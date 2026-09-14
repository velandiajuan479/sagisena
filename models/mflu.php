<?php
class Mflu {
    
    private $idflu;
    private $nomflu;
    private $fluacti;

    // Getters
    function getIdflu() {
        return $this->idflu;
    }
    function getNomflu() {
        return $this->nomflu;
    }
    function getFluacti() {
        return $this->fluacti;
    }

    // Setters
    function setIdflu($idflu) {
        $this->idflu = $idflu;
    }
    function setNomflu($nomflu) {
        $this->nomflu = $nomflu;
    }
    function setFluacti($fluacti) {
        $this->fluacti = $fluacti;
    }

    
    public function getAll() {
        try {
            $sql = "SELECT idflu, nomflu, fluacti FROM flujo";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

   
    public function getOne() {
        try {
            $sql = "SELECT idflu, nomflu, fluacti FROM flujo WHERE idflu = :idflu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idflu = $this->getIdflu();
            $result->bindParam(":idflu", $idflu);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    
    public function save() {
        try {
            $sql = "INSERT INTO flujo (nomflu, fluacti) VALUES (:nomflu, :fluacti)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $nomflu = $this->getNomflu();
            $result->bindParam(":nomflu", $nomflu);
            $fluacti = $this->getFluacti();
            $result->bindParam(":fluacti", $fluacti);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    
    public function edit() {
        try {
            $sql = "UPDATE flujo SET nomflu = :nomflu, fluacti = :fluacti WHERE idflu = :idflu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idflu = $this->getIdflu();
            $result->bindParam(":idflu", $idflu);
            $nomflu = $this->getNomflu();
            $result->bindParam(":nomflu", $nomflu);
            $fluacti = $this->getFluacti();
            $result->bindParam(":fluacti", $fluacti);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    public function editAct(){
        try {
            $sql = "UPDATE flujo SET fluacti=:fluacti WHERE idflu=:idflu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idflu = $this->getIdflu();
            $result->bindParam(":idflu", $idflu);
            $fluacti = $this->getFluacti();
            $result->bindParam(":fluacti", $fluacti);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

   
    public function del() {
        try {
            $sql = "DELETE FROM flujo WHERE idflu = :idflu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idflu = $this->getIdflu();
            $result->bindParam(":idflu", $idflu);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }
}
?>



