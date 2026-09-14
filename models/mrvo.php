<?php
class Mrvo{


	private $fidjor;
	private $fidcen;
//metodos get
	public function getFidjor(){
		return $this->fidjor;
	}
	public function getFidcen(){
		return $this->fidcen;
	}

//metodos set
	public function setFidjor($fidjor){
		$this->fidjor=$fidjor;
	}
	public function setFidcen($fidcen){
		$this->fidcen = $fidcen;
	}

	//metodos generales
	public function selAll(){
		$jornada = $this->getFidjor();
		$idcen = $this->getFidcen();
		$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, f.idfic, f.nomfic, c.nomcen, u.actusu, u.fotcan, v.nomval FROM usuario AS u LEFT JOIN usupef AS up ON u.idusu=up.idusu INNER JOIN usufic AS uf ON u.idusu=uf.idusu INNER JOIN ficha AS f ON uf.idfic=f.idfic INNER JOIN centro AS c ON f.idcen=c.idcen INNER JOIN valor AS v ON f.jornada=v.idval WHERE f.jornada=:jornada AND c.idcen=:idcen AND up.idper=3;";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":jornada", $jornada);
		$result->bindParam(":idcen", $idcen);
		$result->execute();
		$res = $result ->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function nvoCan($canusu){
		$sql = "SELECT count(idusu) AS nvo FROM voto WHERE canusu=:canusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":canusu", $canusu);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getJor(){
		$sql = "SELECT idval, nomval FROM valor WHERE iddom=1";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getCen(){
		$sql = "SELECT idcen, nomcen FROM centro";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function canPxF($jornada){
		$sql = "SELECT count(v.idusu) AS nvo FROM voto AS v INNER JOIN usufic AS uf ON v.idusu=uf.idusu INNER JOIN ficha AS f ON uf.idfic=f.idfic WHERE f.jornada=:jornada";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":jornada", $jornada);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
}

	

}
?>