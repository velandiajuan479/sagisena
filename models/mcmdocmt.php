<?php
class Mcmdocmt{
    //Atributos
// iddcma
// nomdcma
// iduxf
// iddocp
// rutdcma
// fecdcma
// aprdcma

    private $iddcma;
    private $nomdcma;
    private $iduxf;
    private $iddocp;
    private $rutdcma;
    private $fecdcma;
    private $aprdcma;

    //GET--------------------------
    function getIddcma(){
        return $this->iddcma;
    }
    function getNomdcma(){
        return $this->nomdcma;
    }
    function getIduxf(){
        return $this->iduxf;
    }
    function getIddocp(){
        return $this->iddocp;
    }
    function getRutdcma(){
        return $this->rutdcma;
    }
    function getFecdcma(){
        return $this->fecdcma;
    }
    function getAprdcma(){
        return $this->aprdcma;
    }
    //SET------------------------
    function setIddcma($iddcma){
        $this->iddcma = $iddcma;
    }
    function setNomdcma($nomdcma){
        $this->nomdcma = $nomdcma;
    }
    function setIduxf($iduxf){
        $this->iduxf = $iduxf;
    }
    function setIddocp($iddocp){
        $this->iddocp = $iddocp;
    }
    function setRutdcma($rutdcma){
        $this->rutdcma = $rutdcma;
    }
    function setFecdcma($fecdcma){
        $this->fecdcma = $fecdcma;
    }
    function setAprdcma($aprdcma){
        $this->aprdcma = $aprdcma;
    }

    //Metodos
    function getAll(){
        try{
            $sql = "SELECT m.iddcma, m.nomdcma, m.iduxf, m.iddocp, d.nomdocp, d.tipdocp, r.idusu, r.idfic, r.fecreg, m.rutdcma, m.fecdcma, m.aprdcma FROM docmat AS m
                INNER JOIN docped AS d ON m.iddocp=d.iddocp
                INNER JOIN registro AS r  ON m.iduxf=r.iduxf";
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
            $sql = "SELECT m.iddcma, m.nomdcma, m.iduxf, m.iddocp, d.nomdocp, d.tipdocp, r.idusu, r.idfic, r.fecreg, m.rutdcma, m.fecdcma, m.aprdcma FROM docmat AS m
                INNER JOIN docped AS d ON m.iddocp=d.iddocp
                INNER JOIN registro AS r  ON m.iduxf=r.iduxf WHERE m.iddcma=:iddcma";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $iddcma = $this->getIddcma();
            $result->bindParam(":iddcma",$iddcma);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            ManejoError($e);
        }
    }

    function save(){
        try{
            $sql = "INSERT INTO docmat (nomdcma, iduxf, iddocp, rutdcma, fecdcma, aprdcma)
                    VALUES (:nomdcma, :iduxf, :iddocp, :rutdcma, :fecdcma, :aprdcma)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $nomdcma = $this->getNomdcma();
            $result->bindParam(":nomdcma",$nomdcma);
            $iduxf = $this->getIduxf();
            $result->bindParam(":iduxf",$iduxf);
            $iddocp = $this->getIddocp();
            $result->bindParam(":iddocp",$iddocp);
            $rutdcma = $this->getRutdcma();
            $result->bindParam(":rutdcma",$rutdcma);
            $fecdcma = $this->getFecdcma();
            $result->bindParam(":fecdcma",$fecdcma);
            $aprdcma = $this->getAprdcma();
            $result->bindParam(":aprdcma",$aprdcma);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }
    }

        function edit(){
        try{
            $sql = "UPDATE docmat SET nomdcma=:nomdcma, iduxf=:iduxf, iddocp=:iddocp,
                rutdcma=:rutdcma, fecdcma=:fecdcma, aprdcma=:aprdcma WHERE iddcma =:iddcma";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $iddcma = $this->getIddcma();
            $result->bindParam(":iddcma",$iddcma);
            $nomdcma = $this->getNomdcma();
            $result->bindParam(":nomdcma",$nomdcma);
            $iduxf = $this->getIduxf();
            $result->bindParam(":iduxf",$iduxf);
            $iddocp = $this->getIddocp();
            $result->bindParam(":iddocp",$iddocp);
            $rutdcma = $this->getRutdcma();
            $result->bindParam(":rutdcma",$rutdcma);
            $fecdcma = $this->getFecdcma();
            $result->bindParam(":fecdcma",$fecdcma);
            $aprdcma = $this->getAprdcma();
            $result->bindParam(":aprdcma",$aprdcma);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }
    }

    function del(){
        try{
            $sql = "DELETE FROM docmat WHERE iddcma=:iddcma";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $iddcma = $this->getIddcma();
            $result->bindParam(":iddcma",$iddcma);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }
    }

    function getDoctipos(){
        try{
            $sql = "SELECT iddocp, nomdocp FROM docped";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            error_log("Error en Mcmdocmt::getDoctipos: " . $e->getMessage());
            return []; // Retornar un array vacío en caso de error
        }
    }

}
?>