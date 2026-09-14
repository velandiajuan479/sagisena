<?php
class Mccm {
    // Atributos
    private $codpro;
    private $nompro;
    private $despro; 
    private $verpro;
    private $horlpro;
    private $tippro;

    // Getters
    function getCodpro() {
        return $this->codpro;
    }
    function getNompro() {
        return $this->nompro;
    }
    function getDespro() {
        return $this->despro;
    }
    function getVerpro() {
        return $this->verpro;
    }
    function getHorlpro() {
        return $this->horlpro;
    }
    function getTippro() {
        return $this->tippro;
    }

    // Setters
    function setCodpro($codpro) {
        $this->codpro = $codpro;
    }
    function setNompro($nompro) {
        $this->nompro = $nompro;
    }
    function setDespro($despro) {
        $this->despro = $despro;
    }
    function setVerpro($verpro) {
        $this->verpro = $verpro;
    }
    function setHorlpro($horlpro) {
        $this->horlpro = $horlpro;
    }
    function setTippro($tippro) {
        $this->tippro = $tippro;
    }
    function getAll(){
        try{
            $sql = "SELECT codpro, nompro, despro, verpro, horlpro, tippro FROM programa";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(Exception $e){
            ManejoError($e);
        }    
    }
    function getOne(){
        try{
            $sql = "SELECT codpro, nompro, despro, verpro, horlpro, tippro  FROM programa WHERE codpro = :codpro";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $codpro = $this->getCodpro();
            $result->bindParam(":codpro", $codpro);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(Exception $e){
            ManejoError($e);
        }    
    }
    function save(){
        try{
            $sql = "INSERT INTO programa (codpro, nompro, despro, verpro, horlpro, tippro) 
                    VALUES (:codpro, :nompro, :despro, :verpro, :horlpro, :tippro)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            
            $codpro = $this->getCodpro();
            $nompro = $this->getNompro();
            $despro = $this->getDespro();
            $verpro = $this->getVerpro();
            $horlpro = $this->getHorlpro();
            $tippro = $this->getTippro();

            $result->bindParam(":codpro", $codpro);
            $result->bindParam(":nompro", $nompro);
            $result->bindParam(":despro", $despro);
            $result->bindParam(":verpro", $verpro);
            $result->bindParam(":horlpro", $horlpro);
            $result->bindParam(":tippro", $tippro);
            
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }    
    }


    function edit(){
        try{
            $sql = "UPDATE programa SET nompro = :nompro, despro = :despro, verpro = :verpro, horlpro = :horlpro, tippro = :tippro
                    WHERE codpro = :codpro";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            
            $codpro = $this->getCodpro();
            $nompro = $this->getNompro();
            $despro = $this->getDespro();
            $verpro = $this->getVerpro();
            $horlpro = $this->getHorlpro();
            $tippro = $this->getTippro();
            
            $result->bindParam(":codpro", $codpro);
            $result->bindParam(":nompro", $nompro);
            $result->bindParam(":despro", $despro);
            $result->bindParam(":verpro", $verpro);
            $result->bindParam(":horlpro", $horlpro);
            $result->bindParam(":tippro", $tippro);
            
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }    
    }
    function del(){
        try{
            $sql = "DELETE FROM programa WHERE codpro = :codpro";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            
            $codpro = $this->getCodpro();
            $result->bindParam(":codpro", $codpro);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }    
    }
}
?>