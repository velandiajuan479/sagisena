<?php
 class Mscins{
    private $idses;
    private $idage;
    private $fecses;

// METODOS GET
    function getidses(){
        return $this->idses;
    }
    function getidage(){
        return $this->idage;
    }
    function getfecses(){
        return $this->fecses;
    }

//  METODOS SET
    function setidses($idses){
        $this->idses=$idses;
    }
    function setidage($idage){
        $this->idage=$idage;
    }
    function setfecses($fecses){
        $this->fecses=$fecses;
    }

   
    public function getAll(){
        $sql ="SELECT * FROM sesion";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute(); 
        $res= $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
       
    }    
    public function getOne(){
        $sql = "SELECT * FROM sesion WHERE idses=:idses";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idses = $this->getIdses();
        $result->bindParam(":idses",$idses);
        $result->execute(); 
        $res= $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function save(){
        $sql = "INSERT INTO sesion (idses, idage, fecses) VALUES (:idses, :idage, :fecses)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idses = $this->getIdses();
        $result->bindParam(":idses",$idses);
        $idage = $this->getIdage();
        $result->bindParam(":idage",$idage);
        $fecses = $this->getFecses();
        $result->bindParam(":fecses",$fecses);

        $result->execute();
    }

	public function edit(){
		$sql = "UPDATE sesion SET idage=:idage, fecses=:fecses WHERE idses=:idses";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idses = $this->getIdses();
		$result->bindParam(":idses", $idses);
		$idage = $this->getIdage();
		$result->bindParam(":idage", $idage);
		$fecses = $this->getFecses();
		$result->bindParam(":fecses", $fecses);
        $result->execute();
	}   

    public function del(){
        $sql = "DELETE FROM sesion WHERE idses=:idses";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idses = $this->getIdses();
        $result->bindParam(":idses",$idses);
        $result->execute(); 
        
    }
    public function getAge(){
        $sql ="SELECT idage FROM agenda";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute(); 
        $res= $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
 }
?>
