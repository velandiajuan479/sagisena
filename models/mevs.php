<?php
class Mevs{
    private $idses;
    private $idage;
    private $fecses;
    private $idva;
    private $idusu;
    private $comeva;
    private $valeva;

    function getIdses(){
        return $this->idses;
    }
    function getIdpage(){
        return $this->idage;
    }
    function getFecses(){
        return $this->fecses;
    }
    function getIdeva(){
        return $this->ideva;
    }
    function getIdusu(){
        return $this->idusu;
    }
    function getComeva(){
        return $this->comeva;
    }
    function getValeva(){
        return $this->valeva;
    }

    //metodo Set

    function setIdses($idses){
        $this->idses = $idses;
    }
    function setIdpage($idage){
        $this->idpage = $idpage;
    }
    function setFecses($fecses){
        $this->fecses = $fecses;
    }
    function setIdeva($ideva){
        $this->ideva = $ideva;
    }
    function setIdusu($idusu){
        $this->idusu = $idusu;
    }
    function setComeva($comeva){
        $this->comeva = $comeva;
    }
    function setValeva($valeva){
        $this->valeva = $valeva;
    }
    public function getAll(){
        $resultado = NULL;
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $sql="SELECT * FROM prv";
        $result = $conexion->prepare($sql);
        $result->execute();
        $resultado=$result->fetchall(PDO::FETCH_ASSOC);
        return $resultado; 
    }
    public function getOne(){
        $resultadp = NULL;
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT * FROM ses WHERE idses=:idses";
        $result = $conexion->prepare($sql);
        $id = $this->getIdses();
        $result->bindParam(":idses", $idprv);
        $result->execute();
        $resultado=$result->fetchall(PDO::FETCH_ASSOC);
        return $resultado;
    }
    public function save(){
        $modelo =new Conexion();
        $conexion = $modelo->get_conexion();
        $sql = "INSERT INTO ses(idses, idage, fecses, ideva, idusu, comeva, valeva) VALUES (:idses, :idage, :fecses, :ideva, :idusu, :comeva, :valeva)";
        $result = $conexion->prepare($sql);
        $idses = $this->getIdses();
        $result->bindParama(":idses", $idsees);
        $idage = $this->getIdpage();
        $result->bindParama(":idage", $idage);
        $fecses = $this->getFecses();
        $result->bindParama(":fecses", $fecses);
        $ideva = $this->getIdeva();
        $result->bindParama(":ideva", $ideva);
        $idusu = $this->getIdusu();
        $result->bindParama(":idusu", $idusu);
        $Comeva = $this->getComeva();
        $result->bindParama(":comeva", $comeva);
        $valeva = $this->getValeva();
        $result->bindParama(":valeva", $valeva); 
    }
    public function edit(){
        $modelo =new Conexion();
        $conexion = $modelo->get_conexion();
        $sql = "UPDATE ses SET idage=:idage, fecses=:fecses, ideva=:ideva, idusu=:idusu, comeva=:comeva, valeva=:valeva WHERE idses=:idses";
        $result = $conexion->prepare($sql);
        $idses=$this->getIdses();
        $result->bindParam(":idses", $idses);
        $idage=$this->getIdpage();
        $result->bindParam(":idage", $idage);
        $fecses=$this->getFecses();
        $result->bindParam(":fecses", $fecses);
        $ideva=$this->getIdeva();
        $result->bindParam(":ideva", $ideva);
        $idusu=$this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $comeva=$this->getComeva();
        $result->bindParam(":comeva", $comeva);
        $valeva=$this->getValeva();
        $result->bindParam(":valeva", $valeva);
        $result->execute();
    }
    public function del(){
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion(); 
        $sql = "DELETE FROM ses WHERE idses=:idses";
        $result = $conexion->prepare($sql);
        $idses = $this->getIdses();
        $result->bindParam(":idses", $idses);
        $result->execute;
    }
}
?>