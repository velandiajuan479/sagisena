<?php
class  Mfot {
	private $idfoto;
	private $idele;
	private $rutfot;

	public function getIdfoto(){
		return $this->idfoto;
	}
	public function getIdele(){
		return $this->idele;
	}
	public function getRutfot(){
		return $this->rutfot;
	}

	public function setIdfoto($idfoto){
		$this->idfoto = $idfoto;
	}
	public function setIdele($idele){
		$this->idele = $idele;
	}
	public function setRutfot($rutfot){
		$this->rutfot = $rutfot;
	}

	public function saveFoto(){
        $sql = "INSERT INTO foto (idele, rutfot) VALUES  (:idele, :rutfot)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idele = $this->getIdele();
		$result->bindParam(":idele",$idele);
		$rutfot = $this->getRutfot();
		$result->bindParam(":rutfot",$rutfot);
		$result->execute();
    }
	public function editFoto(){
        $sql = "UPDATE foto SET rutfot=:rutfot WHERE idele=:idele";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idele = $this->getIdele();
		$result->bindParam(":idele",$idele);
		$rutfot = $this->getRutfot();
		$result->bindParam(":rutfot",$rutfot);
		$result->execute();
    }

	public function delFot(){
        $sql = "DELETE FROM foto WHERE idele=:idele";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idele = $this->getIdele();
		$result->bindParam(":idele",$idele);
		$result->execute();
    }

}