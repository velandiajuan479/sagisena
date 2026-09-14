<?php
class Mubi{
	private $codubi;
	private $nomubi;
	private $depubi;

	function getCodubi(){
		return $this->codubi;
	}
	function getNomubi(){
		return $this->nomubi;
	}
	function getDepubi(){
		return $this->depubi;
	}

	function setCodubi($codubi){
		$this->codubi = $codubi;
	}
	function setNomubi($nomubi){
		$this->nomubi = $nomubi;
	}
	function setDepubi($depubi){
		$this->depubi = $depubi;
	}

	public function getAll(){
		$res = NULL;
		$sql = "SELECT d.codubi AS cd, d.nomubi AS nd
			, m.codubi AS cm,m.nomubi AS nm FROM
			 ubica AS m LEFT JOIN ubica AS d ON m.depubi
			 =d.codubi";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getOne(){
		$res = NULL;
		$sql = "SELECT * FROM ubica WHERE codubi=:codubi";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$codubi = $this->getCodubi();
		$result->bindParam(":codubi", $codubi);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function save(){
		$sql = "INSERT INTO ubica (nomubi, depubi) VALUES (:nomubi, :depubi)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$nomubi = $this->getNomubi();
		$result->bindParam(":nomubi", $nomubi);
		$depubi = $this->getDepubi();
		$result->bindParam(":depubi", $depubi);
		$result->execute();
	}
	public function edit(){
		$sql = "UPDATE ubica SET nomubi=:nomubi, depubi=:depubi WHERE codubi=:codubi";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$codubi = $this->getCodubi();
		$result->bindParam(":codubi", $codubi);
		$nomubi = $this->getNomubi();
		$result->bindParam(":nomubi", $nomubi);
		$depubi = $this->getDepubi();
		$result->bindParam(":depubi", $depubi);
		$result->execute();
	}

	public function del(){
		$sql = "DELETE FROM ubica WHERE codubi=:codubi";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$codubi = $this->getCodubi();
		$result->bindParam(":codubi", $codubi);
		$result->execute();
	}

	public function getDep($depubi){
		$resultado = NULL;
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql="SELECT * FROM ubica WHERE depubi=:depubi
			ORDER BY nomubi";
		$result = $conexion->prepare($sql);
		$result->bindParam(":depubi", $depubi);
		$result->execute();
		$resultado=$result->fetchall(PDO::FETCH_ASSOC);
		return $resultado;
	}
}
?>