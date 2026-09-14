<?php
class Mpasmat{
	//Atributos
	private $idpas;
	private $idflu;
	private $descpas;
	private $idper;

	//Métodos Get
	function getIdpas(){
		return $this->idpas;
	}
	function getIdflu(){
		return $this->idflu;
	}
	function getDescpas(){
		return $this->descpas;
	}
	function getIdper(){
		return $this->idper;
	}

	//Métodos Set
	function setIdpas($idpas){
		$this->idpas = $idpas;
	}
	function setIdflu($idflu){
		$this->idflu = $idflu;
	}
	function setDescpas($descpas){
		$this->descpas = $descpas;
	}
	function setIdper($idper){
		$this->idper = $idper;
	}

	//Métodos
	public function getAll(){
		$sql = "SELECT ps.idpas, f.idflu, ps.descpas, p.idper FROM paso AS ps INNER JOIN flujo AS f ON ps.idflu=f.idflu INNER JOIN perfil AS p ON ps.idper=p.idper";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result -> execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getOne(){
		try{
			$sql = "SELECT ps.idpas, f.idflu, ps.descpas, p.idper FROM paso AS ps INNER JOIN flujo AS f ON ps.idflu=f.idflu INNER JOIN perfil AS p ON ps.idper=p.idper WHERE ps.idpas=:idpas";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idpas = $this->getIdpas();
			$result->bindParam(':idpas',$idpas);
			$result->execute();
			$res = $result->fetchAll(PDO::FETCH_ASSOC);
			return $res;
	}catch(Exception $e){
		echo "Eror: ".$e;
		}
	}

	public function getOnefil(){
		try{
			$sql = "SELECT ps.idpas FROM paso WHERE descpas =: descpas AND idflu =: idflu  ";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idpas = $this->getIdpas();
			$result->bindParam(':idpas',$idpas);
			$idflu = $this->getIdflu();
			$result->bindParam(':idflu',$idpas);
			$result->execute();
			$res = $result->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}catch (Exception $e) {
			return null;
		}

	}

	public function save(){
		$sql = "INSERT INTO paso (idpas, idflu, descpas, idper) VALUES (:idpas, :idflu, :descpas, :idper)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idpas = $this->getIdpas();
		$result->bindParam(':idpas',$idpas);
		$idflu = $this->getIdflu();
		$result->bindParam(':idflu',$idflu);
		$descpas = $this->getDescpas();
		$result->bindParam(':descpas',$descpas);
		$idper = $this->getIdper();
		$result->bindParam(':idper',$idper);
		$result->execute();
	}

	public function edit(){
		$sql = "UPDATE paso SET idpas=:idpas, idflu=:idflu, descpas=:descpas WHERE idpas=:idpas";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idpas = $this->getIdpas();
		$result->bindParam(':idpas',$idpas);
		$idflu = $this->getIdflu();
		$result->bindParam(':idflu',$idflu);
		$descpas = $this->getDescpas();
		$result->bindParam(':descpas',$descpas);
		$idper = $this->getIdper();
		$result->bindParam(':idper',$idper);
		$result->execute();
	}

	public function del(){
		$sql = "DELETE FROM paso WHERE idpas=:idpas";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idpas = $this->getIdpas();
		$result->bindParam(':idpas',$idpas);
		$result->execute();
	}

	public function getAllFlu(){
		$sql = "SELECT idflu, nomflu FROM flujo";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getAllPer(){
		$sql = "SELECT idper, nomper FROM perfil WHERE idmod IN (8,9) ";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
}
?>