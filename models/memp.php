<?php
class Memp{
    //atributos
    private $idemp;
    private $numdocemp;
    private $nomemp;
    private $diremp;
    private $codubi;
    private $nomconemp;
    private $telemp;
    private $usuapr;
    private $fecapr;

    //getters
    function getIdemp(){
        return $this->idemp;
    }
    function getNumdocemp(){
        return $this->numdocemp;    
    }
    function getNomemp(){
        return $this->nomemp;    
    }
    function getDiremp(){
        return $this->diremp;    
    }
    function getCodubi(){
        return $this->codubi;    
    }
    function getNomconemp(){
        return $this->nomconemp;    
    }
    function getTelemp(){
        return $this->telemp;    
    }

    function getUsuapr(){
        return $this->usuapr;    
    }
    function getFecapr(){
        return $this->fecapr;    
    }

    //setters
    function setIdemp($idemp){
        $this->idemp = $idemp;
    }
    function setNumdocemp($numdocemp){
        $this->numdocemp = $numdocemp;
    }
    function setNomemp($nomemp){
        $this->nomemp = $nomemp;
    }
    function setDiremp($diremp){
        $this->diremp = $diremp;
    }
    function setCodubi($codubi){
        $this->codubi = $codubi;
    }
    function setNomconemp($nomconemp){
        $this->nomconemp = $nomconemp;
    }
    function setTelemp($telemp){
        $this->telemp = $telemp;
    }

    function setUsuapr($usuapr){
        $this->usuapr = $usuapr;    
    }
    function setFecapr($fecapr){
        $this->fecapr = $fecapr;    
    }

    //metodos
    function getAll(){
        try{
            $sql = "SELECT e.idemp, e.numdocemp, e.nomemp, e.diremp, e.codubi, u.nomubi AS nommun, u.depubi, d.nomubi AS nomdep, e.nomconemp, e.telemp FROM empresa AS e INNER JOIN ubica AS u ON e.codubi=u.codubi LEFT JOIN ubica AS d ON u.depubi=d.codubi";
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
            $sql = "SELECT e.idemp, e.numdocemp, e.nomemp, e.diremp, e.codubi, u.nomubi AS nommun, u.depubi, d.nomubi AS nomdep, e.nomconemp, e.telemp FROM empresa AS e INNER JOIN ubica AS u ON e.codubi=u.codubi LEFT JOIN ubica AS d ON u.depubi=d.codubi WHERE idemp=:idemp";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idemp = $this->getIdemp();
            $result->bindParam(":idemp",$idemp);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(Exception $e){
            ManejoError($e);
        }    
    }

    function save(){
        try{
            $sql = "INSERT INTO empresa (numdocemp, nomemp, diremp, codubi, nomconemp, telemp, usucre, feccre) VALUES
            (:numdocemp, :nomemp, :diremp, :codubi, :nomconemp, :telemp, :usucre, :feccre)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $numdocemp = $this->getNumdocemp();
            $result->bindParam(":numdocemp",$numdocemp);
            $nomemp = $this->getNomemp();
            $result->bindParam(":nomemp",$nomemp);
            $diremp = $this->getDiremp();
            $result->bindParam(":diremp",$diremp);
            $codubi = $this->getCodubi();
            $result->bindParam(":codubi",$codubi);
            $nomconemp = $this->getNomconemp();
            $result->bindParam(":nomconemp",$nomconemp);
            $telemp = $this->getTelemp();
            $result->bindParam(":telemp",$telemp);

            $usucre = isset($_SESSION["idusu"]) ? $_SESSION["idusu"]:NULL;
            $result->bindParam(":usucre",$usucre);
            $feccre = date('Y-m-d H:i:s');
            $result->bindParam(":feccre",$feccre);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }    
    }

    function edit(){
        try{
            $sql = "UPDATE empresa SET numdocemp=:numdocemp, nomemp=:nomemp, diremp=:diremp, codubi=:codubi, nomconemp=:nomconemp, telemp=:telemp WHERE idemp=:idemp";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idemp = $this->getIdemp();
            $result->bindParam(":idemp",$idemp);
            $numdocemp = $this->getNumdocemp();
            $result->bindParam(":numdocemp",$numdocemp);
            $nomemp = $this->getNomemp();
            $result->bindParam(":nomemp",$nomemp);
            $diremp = $this->getDiremp();
            $result->bindParam(":diremp",$diremp);
            $codubi = $this->getCodubi();
            $result->bindParam(":codubi",$codubi);
            $nomconemp = $this->getNomconemp();
            $result->bindParam(":nomconemp",$nomconemp);
            $telemp = $this->getTelemp();
            $result->bindParam(":telemp",$telemp);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }    
    }

    function del(){
        try{
            $sql = "DELETE FROM empresa WHERE idemp=:idemp";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idemp = $this->getIdemp();
            $result->bindParam(":idemp",$idemp);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }    
    }

    function getAllDep(){
        try{
            $sql = "SELECT codubi, nomubi FROM ubica WHERE depubi=0";
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
}
?>