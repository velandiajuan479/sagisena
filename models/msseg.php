<?php
class Msseg {
    private $idses;
    private $idage;
    private $fecses;
    private $ideva;
    private $idusu;
    private $comeva;
    private $valeva;
    private $idfic;
    private $idcom;
    private $descom;

    // Métodos Get
    function getIdses() { 
        return $this->idses;
    }
    function getIdage() { 
        return $this->idage;
    }
    function getFecses() { 
        return $this->fecses;
    }
    function getIdeva() { 
        return $this->ideva;
    }
    function getIdusu() { 
        return $this->idusu;
    }
    function getComeva() { 
        return $this->comeva;
    }
    function getValeva() { 
        return $this->valeva;
    }
    function getIdfic() { 
        return $this->idfic;
    }
    function getIdcom() { 
        return $this->idcom;
    }
     function getDescom() { 
        return $this->descom;
    }
    
    
    // Métodos Set
    function setIdses($idses) { 
        $this->idses = $idses;
    }
    function setIdage($idage) { 
        $this->idage = $idage;
    }
    function setFecses($fecses) { 
        $this->fecses = $fecses;
    }
    function setIdeva($ideva) { 
        $this->ideva = $ideva;
    }
    function setIdusu($idusu) { 
        $this->idusu = $idusu;
    }
    function setComeva($comeva) { 
        $this->comeva = $comeva;
    }
    function setValeva($valeva) { 
        $this->valeva = $valeva;
    }
    function setIdfic($idfic) { 
        $this->idfic = $idfic;
    } 
    function setIdcom($idcom) { 
        $this->idcom = $idcom;
    }
    function setDescom($descom) { 
        $this->descom = $descom;
    }

    public function getAll() {
        $sql = "SELECT * FROM prv";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una sesión específica
    public function getOne() {
        $sql = "SELECT * FROM ses WHERE idses=:idses";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $id = $this->getIdses();
        $result->bindParam(":idses", $id);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFic(){
        $sql = "SELECT f.idfic, f.nomfic, v.nomval, f.codpro, a.nomare FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval LEFT JOIN programa AS p ON f.codpro=p.codpro LEFT JOIN area AS a ON p.idare=a.idare WHERE f.idfic>1000 ORDER BY f.idfic, f.nomfic";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
    //public function getValeva(){
       // $sql = "SELECT f.idfic, f.nomfic, v.nomval, f.codpro, a.nomare FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval LEFT JOIN programa AS p ON f.codpro=p.codpro LEFT JOIN area AS a ON p.idare=a.idare WHERE f.idfic>1000 ORDER BY f.idfic, f.nomfic";
        //$modelo = new conexion();
        //$conexion = $modelo->get_conexion();
       // $result = $conexion->prepare($sql);
       // $result->execute();
        //$res = $result-> fetchall(PDO::FETCH_ASSOC);
       // return $res;
    //}
    public function getTipComp(){
        $sql = "SELECT idval, nomval, parval FROM valor WHERE act=1 AND iddom=15 ORDER BY parval";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res= $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
    public function save() {
        $sql = "INSERT INTO ses (idses, idage, fecses, ideva, idusu, comeva, valeva) VALUES (:idses, :idage, :fecses, :ideva, :idusu, :comeva, :valeva)";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idses", $this->idses);
        $result->bindParam(":idage", $this->idage);
        $result->bindParam(":fecses", $this->fecses);
        $result->bindParam(":ideva", $this->ideva);
        $result->bindParam(":idusu", $this->idusu);
        $result->bindParam(":comeva", $this->comeva);
        $result->bindParam(":valeva", $this->valeva);
        $result->execute();
    }

    public function edit() {
        $sql = "UPDATE ses SET idage=:idage, fecses=:fecses, ideva=:ideva, idusu=:idusu, comeva=:comeva, valeva=:valeva WHERE idses=:idses";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idses", $this->idses);
        $result->bindParam(":idage", $this->idage);
        $result->bindParam(":fecses", $this->fecses);
        $result->bindParam(":ideva", $this->ideva);
        $result->bindParam(":idusu", $this->idusu);
        $result->bindParam(":comeva", $this->comeva);
        $result->bindParam(":valeva", $this->valeva);
        $result->execute();
    }

    public function del() {
        $sql = "DELETE FROM ses WHERE idses=:idses";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idses", $this->idses);
        $result->execute();
    }

    // Obtener el seguimiento de evaluaciones por usuario
    public function getSegses($idusu) {
        $sql = "SELECT * FROM ses WHERE idusu=:idusu";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>