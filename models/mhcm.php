<?php
class Mhcm{
	//Atributos
	
	private $idnorad;
	private $codpro;
	private $idemp;
	private $codproesp;
	private $codslem;
	private $feclini;
	private $feclin;
	private $cupo;
	private $jornada;
	private $idfic;

//Getters
	function getIdnorad(){
		return $this->idnorad;
	}
	function getCodpro(){
		return $this->codpro;
	}
	function getIdemp(){
		return $this->idemp;
	}
	function getCodproesp(){
		return $this->codproesp;
	}
	function getCodslem(){
		return $this->codslem;
	}
	function getFeclini(){
		return $this->feclini;
	}
	function getFeclin(){
		return $this->feclin;
	}
	function getCupo(){
		return $this->cupo;
	}
	function getJornada(){
		return $this->jornada;
	}
	function getIdfic(){
		return $this->idfic;
	}

//Setters
	function setIdnorad($idnorad){
		$this->idnorad = $idnorad;
	}
	function setCodpro($codpro){
		$this->codpro = $codpro;
	}
	function setIdemp($idemp){
		$this->idemp = $idemp;
	}
	function setCodproesp($codproesp){
		$this->codproesp = $codproesp;
	}
	function setCodslem($codslem){
		$this->codslem = $codslem;
	}
	function setFeclini($feclini){
		$this->feclini = $feclini;
	}
	function setFeclin($feclin){
		$this->feclin = $feclin;
	}
	function setCupo($cupo){
		$this->cupo = $cupo;
	}
	function setJornada($jornada){
		$this->jornada = $jornada;
	}
	function setIdfic($idfic){
		$this->idfic = $idfic;
	}

//Métodos
	public function getAll(){
		$sql = "SELECT h.idnorad, h.codpro, p.nompro, h.idemp, e.nomemp, h.codproesp, s.nomval AS proesp, h.codslem, h.feclini, h.feclin, h.cupo, h.jornada, j.nomval AS jorn, h.idfic, f.nomfic FROM hojatra AS h INNER JOIN programa AS p ON h.codpro=p.codpro INNER JOIN empresa AS e ON h.idemp=e.idemp INNER JOIN valor AS s ON h.codproesp=s.idval INNER JOIN valor AS j ON h.jornada=j.idval INNER JOIN ficha AS f ON h.idfic=f.idfic";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getOne(){
		$sql = "SELECT h.idnorad, h.codpro, p.nompro, h.idemp, e.nomemp, h.codproesp, s.nomval AS proesp, h.codslem, h.feclini, h.feclin, h.cupo, h.jornada, j.nomval AS jorn, h.idfic, f.nomfic FROM hojatra AS h INNER JOIN programa AS p ON h.codpro=p.codpro INNER JOIN empresa AS e ON h.idemp=e.idemp INNER JOIN valor AS s ON h.codproesp=s.idval INNER JOIN valor AS j ON h.jornada=j.idval INNER JOIN ficha AS f ON h.idfic=f.idfic WHERE h.idnorad=:idnorad";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idnorad = $this->getIdnorad();
		$result->bindParam(':idnorad',$idnorad);
		$result->execute();
		$res = $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getAllPro(){
		$sql = "SELECT * FROM programa";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getAllFic(){
		$sql = "SELECT * FROM ficha";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getAllEmp(){
		$sql = "SELECT idemp, nomemp FROM empresa";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
}
?>