<?php
require_once("conexion.php");

class Mhdt{
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
	private $convht;

	
	public function __construct($con = null) {
		
	}

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
	public function getConvht() {
        return $this->convht;
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
	public function setConvht($convht) {
        $this->convht = $convht;
        return $this; 
    }

//Métodos
	public function getAll(){
	    try {
	        $sql = "SELECT h.idnorad, h.codpro, p.nompro, h.idemp, e.nomemp, h.codproesp, s.nomval AS proesp, h.codslem, h.feclini, h.feclin, h.cupo, h.jornada, j.nomval AS jorn, h.idfic, h.convht FROM hojatra AS h  INNER JOIN programa AS p ON h.codpro=p.codpro  INNER JOIN empresa AS e ON h.idemp=e.idemp INNER JOIN valor AS s ON h.codproesp=s.idval INNER JOIN valor AS j ON h.jornada=j.idval ORDER BY h.idnorad DESC";        
	        $modelo = new conexion();
	        $conexion = $modelo->get_conexion();
	        $result = $conexion->prepare($sql);
	        $result->execute();
	        $res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
	    } catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener datos");</script>';
	        }
	        return [];
	    }
	}

	public function getOne(){
		try {
	    	$sql = "SELECT h.idnorad, h.codpro, p.nompro, h.idemp, e.nomemp, h.codproesp, s.nomval AS proesp, h.codslem, h.feclini, h.feclin, h.cupo, h.jornada, j.nomval AS jorn, h.idfic, h.convht FROM hojatra AS h  INNER JOIN programa AS p ON h.codpro=p.codpro  INNER JOIN empresa AS e ON h.idemp=e.idemp INNER JOIN valor AS s ON h.codproesp=s.idval INNER JOIN valor AS j ON h.jornada=j.idval WHERE h.idnorad=:idnorad";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idnorad = $this->getIdnorad();
			$result->bindParam(':idnorad',$idnorad);
			$result->execute();
			$res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener registro");</script>';
	        }
	        return [];
	    }
	}

	public function getOneLast(){
		try {
	    	$sql = "SELECT idnorad FROM hojatra WHERE codpro=:codpro AND 
            idemp=:idemp AND codproesp=:codproesp AND feclini=:feclini AND feclin=:feclin AND cupo=:cupo AND jornada=:jornada ORDER BY idnorad DESC LIMIT 1";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$codpro = $this->getCodpro();
			$result->bindParam(':codpro',$codpro);
			$idemp = $this->getIdemp();
			$result->bindParam(':idemp',$idemp);
			$codproesp = $this->getCodproesp();
			$result->bindParam(':codproesp',$codproesp);
			$feclini = $this->getFeclini();
			$result->bindParam(':feclini',$feclini);
			$feclin = $this->getFeclin();
			$result->bindParam(':feclin',$feclin);
			$cupo = $this->getCupo();
			$result->bindParam(':cupo',$cupo);
			$jornada = $this->getJornada();
			$result->bindParam(':jornada',$jornada);
			$result->execute();
			$res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener último registro");</script>';
	        }
	        return [];
	    }
	}

	public function save(){
		try {
			$sql = "INSERT INTO hojatra (codpro, idemp, codproesp, feclini, feclin, cupo, jornada) 
	            VALUES (:codpro, :idemp, :codproesp, :feclini, :feclin, :cupo, :jornada)";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$codpro = $this->getCodpro();
			$result->bindParam(':codpro',$codpro);
			$idemp = $this->getIdemp();
			$result->bindParam(':idemp',$idemp);
			$codproesp = $this->getCodproesp();
			$result->bindParam(':codproesp',$codproesp);
			$feclini = $this->getFeclini();
			$result->bindParam(':feclini',$feclini);
			$feclin = $this->getFeclin();
			$result->bindParam(':feclin',$feclin);
			$cupo = $this->getCupo();
			$result->bindParam(':cupo',$cupo);
			$jornada = $this->getJornada();
			$result->bindParam(':jornada',$jornada);
	    	$result->execute();
	    } catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al guardar registro");</script>';
	        }
	    }
	}

	public function saveHxU(){
		try {
			$sql = "INSERT INTO hdtxusu (idnorad, idusu) 
	            VALUES (:idnorad, :idusu)";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idnorad = $this->getIdnorad();
			$result->bindParam(':idnorad',$idnorad);
			$idusu = $_SESSION["idusu"];
			$result->bindParam(':idusu',$idusu);
	    	$result->execute();
	    } catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al asociar usuario");</script>';
	        }
	    }
	}

	public function edit(){
		try {
			$sql = "UPDATE hojatra SET 
	            codpro=:codpro, 
	            idemp=:idemp, 
	            codproesp=:codproesp, 
	            codslem=:codslem, 
	            feclini=:feclini, 
	            feclin=:feclin, 
	            cupo=:cupo, 
	            jornada=:jornada, 
	            idfic=:idfic, 
	            convht=:convht 
	            WHERE idnorad=:idnorad";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idnorad = $this->getIdnorad();
			$result->bindParam(':idnorad',$idnorad);
			$codpro = $this->getCodpro();
			$result->bindParam(':codpro',$codpro);
			$idemp = $this->getIdemp();
			$result->bindParam(':idemp',$idemp);
			$codproesp = $this->getCodproesp();
			$result->bindParam(':codproesp',$codproesp);
			$codslem = $this->getCodslem();
			$result->bindParam(':codslem',$codslem);
			$feclini = $this->getFeclini();
			$result->bindParam(':feclini',$feclini);
			$feclin = $this->getFeclin();
			$result->bindParam(':feclin',$feclin);
			$cupo = $this->getCupo();
			$result->bindParam(':cupo',$cupo);
			$jornada = $this->getJornada();
			$result->bindParam(':jornada',$jornada);
			$idfic = $this->getIdfic();
			$result->bindParam(':idfic',$idfic);
			$convht = $this->getConvht();
	    	$result->bindParam(':convht', $convht);
			$result->execute();
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al actualizar registro");</script>';
	        }
	    }
	}

	public function del(){
		try {
			// Primero eliminar registros relacionados en hdtxusu
			$sql = "DELETE FROM hdtxusu WHERE idnorad=:idnorad";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$idnorad = $this->getIdnorad();
			$result->bindParam(':idnorad',$idnorad);
			$result->execute();
			
			// Luego eliminar la hoja de trabajo
			$sql = "DELETE FROM hojatra WHERE idnorad=:idnorad";
			$result = $conexion->prepare($sql);
			$result->bindParam(':idnorad',$idnorad);
			$result->execute();
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al eliminar registro");</script>';
	        }
	    }
	}

	public function getAllPro(){
		try {
			$sql = "SELECT * FROM programa WHERE tippro = 1083 ORDER BY codpro ASC, nompro ASC";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$result->execute();
			$res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener programas");</script>';
	        }
	        return [];
	    }
	}

	public function getAllFic(){
		try {
			$sql = "SELECT * FROM ficha ORDER BY idfic DESC";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$result->execute();
			$res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener fichas");</script>';
	        }
	        return [];
	    }
	}

	public function getAllEmp(){
		try {
			$sql = "SELECT idemp, nomemp FROM empresa ORDER BY nomemp ASC";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$result->execute();
			$res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener empresas");</script>';
	        }
	        return [];
	    }
	}

	public function getAllVal($iddom){
		try {
			$sql = "SELECT idval, nomval FROM valor WHERE act=1 AND iddom=:iddom
				ORDER BY nomval";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);
			$result->bindParam(':iddom',$iddom);
			$result->execute();
			$res = $result->fetchall(PDO::FETCH_ASSOC);
			return $res;
		} catch (Exception $e) {
	        if(function_exists('ManejoError')) {
	            ManejoError($e);
	        } else {
	            echo '<script>alert("Error al obtener valores");</script>';
	        }
	        return [];
	    }
	}
}
?>