<?php
class Mficmat{
	//Atributos
	private $idfic;
	private $nomfic;
	private $codpro;
	private $idusu;
	private $jornada;
	private $idcen;
	private $mun;
	private $finific;
	private $ffinfic;

	//Metodos get
	function getIdfic(){
		return $this->idfic;
	}
	function getNomfic(){
		return $this->nomfic;
	}
	function getCodpro(){
		return $this->codpro;
	}
	function getIdusu(){
		return $this->idusu;
	}
	function getJornada(){
		return $this->jornada;
	}
	function getIdcen(){
	return $this->idcen;
	}
	function getMun(){
		return $this->mun;
	}
	function getFinific(){
		return $this->finific;
	}
	function getFfinfic(){
		return $this->ffinfic;
	}


	//Metodos Set
	function setIdfic($idfic){
		$this->idfic = $idfic;
	}
	function setNomfic($nomfic){
		$this->nomfic = $nomfic;
	}
	function setCodpro($codpro){
		$this->codpro = $codpro;
	}
	function setIdusu($idusu){
		$this->idusu = $idusu;
	}
	function setJornada($jornada){
		$this->jornada = $jornada;
	}
	function setIdcen($idcen){
		$this->idcen = $idcen;
	}
	function setMun($mun){
		$this->mun = $mun;
	}
	function setFinific($finific){
		$this->finific = $finific;
	}
	function setFfinfic($ffinfic){
		$this->ffinfic = $ffinfic;
	}


	//Metodos
	public function getAll(){
		try{
			$sql = "SELECT f.idfic, f.nomfic, f.codpro, f.idusu, f.jornada, v.nomval, f.idcen, c.nomcen, f.mun, m.nomubi AS nommun, m.depubi, d.nomubi AS nomdep, f.finific, f.ffinfic FROM ficha AS f INNER JOIN centro AS c ON f.idcen=c.idcen INNER JOIN valor as v ON f.jornada=v.idval INNER JOIN ubica AS m ON f.mun=m.codubi LEFT JOIN ubica AS d ON m.depubi=d.codubi WHERE MONTH(f.finific)>='".date('m')."' AND YEAR(f.finific)>='".date('Y')."'";
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
			$sql = "SELECT f.idfic, f.nomfic, f.codpro, f.idusu, f.jornada, v.nomval, f.idcen, c.nomcen, f.mun, m.nomubi AS nommun, m.depubi, d.nomubi AS nomdep, f.finific, f.ffinfic FROM ficha AS f INNER JOIN centro AS c ON f.idcen=c.idcen INNER JOIN valor as v ON f.jornada=v.idval INNER JOIN ubica AS m ON f.mun=m.codubi LEFT JOIN ubica AS d ON m.depubi=d.codubi WHERE f.idfic=:idfic";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idfic = $this->getIdfic();
			$result->bindParam(':idfic',$idfic);
			$result->execute();
			$res = $result->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}catch(Exception $e){
			ManejoError($e);
		}
	}


	public function getOneFil(){
		try{
			$sql = "SELECT idfic FROM ficha WHERE nomfic=:nomfic AND codpro=:codpro AND idusu=:idusu AND jornada=:jornada AND idcen=:idcen AND mun=:mun AND finific=:finific AND ffinfic=:ffinfic";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$nomfic = $this->getNomfic();
			$result->bindParam(':nomfic',$nomfic);
			$codpro = $this->getCodpro();
			$result->bindParam(':codpro',$codpro);
			$idusu = $this->getIdusu();
			$result->bindParam(':idusu',$idusu);
			$jornada = $this->getJornada();
			$result->bindParam(':jornada',$jornada);
			$idcen = $this->getIdcen();
			$result->bindParam(':idcen',$idcen);
			$mun = $this->getMun();
			$result->bindParam(':mun',$mun);
			$finific = $this->getFinific();
			$result->bindParam(':finific',$finific);
			$ffinfic = $this->getFfinfic();
			$result->bindParam(':ffinfic',$ffinfic);
			$result->execute();
			$res = $result->fetchAll(PDO::FETCH_ASSOC);
			return $res;
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	public function save(){
		try{
			$sql = "INSERT INTO ficha (idfic, nomfic, codpro, idusu, jornada, idcen, mun, finific, ffinfic) VALUES (:idfic, :nomfic, :codpro, :idusu, :jornada, :idcen, :mun, :finific, :ffinfic)";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idfic = $this->getIdfic();
			$result->bindParam(':idfic',$idfic);
			$nomfic = $this->getNomfic();
			$result->bindParam(':nomfic',$nomfic);
			$codpro = $this->getCodpro();
			$result->bindParam(':codpro',$codpro);
			$idusu = $this->getIdusu();
			$result->bindParam(':idusu',$idusu);
			$jornada = $this->getJornada();
			$result->bindParam(':jornada',$jornada);
			$idcen = $this->getIdcen();
			$result->bindParam(':idcen',$idcen);
			$mun = $this->getMun();
			$result->bindParam(':mun',$mun);
			$finific = $this->getFinific();
			$result->bindParam(':finific',$finific);
			$ffinfic = $this->getFfinfic();
			$result->bindParam(':ffinfic',$ffinfic);
			$result->execute();
		}catch(Exception $e){
			ManejoError($e);
		}
	}



	public function edit(){
		try{
			$sql = "UPDATE ficha SET nomfic=:nomfic, codpro=:codpro, idusu=:idusu, jornada=:jornada, idcen=:idcen, mun=:mun, finific=:finific, ffinfic=:ffinfic WHERE idfic=:idfic";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idfic = $this->getIdfic();
			$result->bindParam(':idfic',$idfic);
			$nomfic = $this->getNomfic();
			$result->bindParam(':nomfic',$nomfic);
			$codpro = $this->getCodpro();
			$result->bindParam(':codpro',$codpro);
			$idusu = $this->getIdusu();
			$result->bindParam(':idusu',$idusu);
			$jornada = $this->getJornada();
			$result->bindParam(':jornada',$jornada);
			$idcen = $this->getIdcen();
			$result->bindParam(':idcen',$idcen);
			$mun = $this->getMun();
			$result->bindParam(':mun',$mun);
			$finific = $this->getFinific();
			$result->bindParam(':finific',$finific);
			$ffinfic = $this->getFfinfic();
			$result->bindParam(':ffinfic',$ffinfic);
			$result->execute();
		}catch(Exception $e){
			ManejoError($e);
		}
	}



	public function del(){
		try{
			$sql = "DELETE FROM ficha WHERE idfic=:idfic";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idfic = $this->getIdfic();
			$result->bindParam(':idfic',$idfic);
			$result->execute();
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	public function getAllJor(){
		try{
			$sql = "SELECT idval, nomval FROM valor WHERE iddom=1 AND act=1;";
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

	public function getAllDep(){
		try{
			$sql = "SELECT codubi, nomubi FROM ubica WHERE depubi=0;";
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

	public function getAllCen(){
		try{
			$sql = "SELECT idcen, nomcen FROM centro";
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

	public function getFicCount()
	{
		try {
			$sql = "SELECT COUNT(*) as total FROM ficha WHERE idfic = ?";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$stmt = $conexion->prepare($sql);
			$stmt->execute([$this->getIdfic()]);
			$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $resultado[0]['total'] > 0;
		} catch (Exception $e) {
			echo "Error: " . $e;
		}
	}


}
?>