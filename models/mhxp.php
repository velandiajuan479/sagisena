<?php 
class Mhxp{
	//atributos
	private $idhor;
	private $idfic;
	private $idaul;
	private $idusu;
	private $iddia;
	//metodos get
	public function getIdhor(){
		return $this->idhor;
	}
	public function getIdfic(){
		return $this->idfic;
	}
	public function getIdaul(){
		return $this->idaul;
	}
	public function getIdusu(){
		return $this->idusu;
	}
	public function getIddia(){
		return $this->iddia;
	}
	//metodos SET
	public function setIdhor($idhor){
		$this->idhor =$idhor;
	}
	public function setIdfic($idfic){
		$this->idfic =$idfic;
	}
	public function setIdaul($idaul){
		$this->idaul =$idaul;
	}
	public function setIdusu($idusu){
		$this->idusu =$idusu;
	}
	public function setIddia($iddia){
		$this->iddia =$iddia;
	}
	//metodos publicos
	public function getAll(){
		$res = NULL;
		$sql = "SELECT h.idhor, h.idfic, h.idaul, a.nomaul, a.codubi, a.idcen, h.idusu, u.ndocusu, u.nomusu, h.iddia FROM horario AS h INNER JOIN usuario AS u ON h.idusu=u.idusu INNER JOIN aula AS a ON h.idaul=a.idaul WHERE h.idfic=:idfic AND h.iddia=:iddia AND h.idusu=:idusu";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idfic = $this->getIdfic();
		$result->bindParam(":idfic", $idfic);
		$iddia = $this->getIddia();
		$result->bindParam(":iddia", $iddia);
		$idusu = $this->getIdusu();
		$result->bindParam(":idusu", $idusu);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getOne(){
		$res = NULL;
		$sql = "SELECT * FROM horario WHERE idhor=:idhor";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idhor = $this->getIdhor();
		$result->bindParam(":idhor", $idhor);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	
	public function getFicha(){
		$sql = "SELECT DISTINCT f.idfic, f.nomfic, v.nomval, v.nhora, f.codpro, a.nomare FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval INNER JOIN horario AS h ON f.idfic=h.idfic LEFT JOIN programa AS p ON f.codpro=p.codpro LEFT JOIN area AS a ON p.idare=a.idare WHERE h.idusu=:idusu AND f.idfic>1000 ORDER BY f.idfic, f.nomfic";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu = $this->getIdusu();
		$result->bindParam(":idusu", $idusu);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getDia(){
		$sql = "SELECT idval, nomval, parval FROM valor WHERE act=1 AND iddom=14 ORDER BY parval";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getInst(){
		$sql = "SELECT p.idusu, p.ndocusu, p.nomusu, p.idper, p.emausu,  p.actusu FROM usuario AS p INNER JOIN usupef AS f ON p.idusu=f.idusu WHERE f.idper IN (7,6,12) ORDER BY p.nomusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getAula(){
		$sql = "SELECT idaul, nomaul FROM aula ORDER BY idaul";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
}
?>