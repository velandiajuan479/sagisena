<?php
class Mare{
	//Atributos
	private $idare;
	private $nomare;
	private $idusu;

	//GET
	function getidare(){
		return $this->idare;
	}
	function getNomare(){
		return $this->nomare;
	}
	function getIdusu(){
		return $this->idusu;
	}

	//SET
	function setidare($idare){
		$this->idare = $idare;
	}
	function setNomare($nomare){
		$this->nomare = $nomare;
	}
	function setIdusu($idusu){
		$this->idusu = $idusu;
	}

	//Metodos
	public function getAll(){
		try{
			$sql = "SELECT a.idare, a.nomare, a.idusu, u.nomusu FROM area AS a INNER JOIN usuario AS u ON a.idusu=u.idusu";
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

	public function getOne(){
		try{
			$sql = "SELECT a.idare, a.nomare, a.idusu, u.nomusu FROM area AS a INNER JOIN usuario AS u ON a.idusu=u.idusu WHERE idare=:idare";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idare = $this->getidare();
			$result->bindParam(":idare",$idare);
			$result->execute();
			$res = $result->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	public function save(){
		try{
			$sql = "INSERT INTO area (nomare, idusu) VALUES (:nomare, :idusu)";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$nomare = $this->getNomare();
			$result->bindParam(":nomare",$nomare);
			$idusu = $this->getIdusu();
			$result->bindParam(":idusu",$idusu);
			$result->execute();
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	public function edit(){
		try{
			$sql = "UPDATE area SET nomare=:nomare, idusu=:idusu WHERE idare=:idare";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idare = $this->getidare();
			$result->bindParam(":idare",$idare);
			$nomare = $this->getNomare();
			$result->bindParam(":nomare",$nomare);
			$idusu = $this->getIdusu();
			$result->bindParam(":idusu",$idusu);
			$result->execute();
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	public function del(){
		try{
			$sql = "DELETE FROM area WHERE idare=:idare";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idare = $this->getidare();
			$result->bindParam(":idare",$idare);
			$result->execute();
			if ($result->rowCount() > 0) {
				return true;
			} else return false;
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	public function getAllusu(){
		try{
			$sql = "SELECT idusu, nomusu FROM usuario";
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