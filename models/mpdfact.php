<?php
require_once "conexion.php";

class Mdpfact{
	 //atributos
    private $idusu;
    private $ndocusu;
    private $nomusu;
    private $idper;
    private $jornada;
    private $emausu;
    private $idcen;
	private $nomcen;
	private $actusu;
	private $fotcan;
	private $telcan;
	private $noca;
	private $idfic;
	private $idval;
	private $canusu;

	// Metodos GET devuelven el dato
    
	function getIdusu(){
		return $this -> idusu;
	}
    
	function getNdocusu(){
		return $this -> ndocusu;
	}
    
	function getNomusu(){
		return $this -> nomusu;
	}
    
	function getIdper(){
		return $this -> idper;
	}
    
	function getJornada(){
		return $this -> jornada;
	}
    
	function getEmausu(){
		return $this -> emausu;
	}
    
	function getIdcen(){
		return $this -> idcen;
	}
    
	function getNomcen(){
		return $this -> nomcen;
	}
    
	function getActusu(){
		return $this -> actusu;
	}
    
	function getFotcan(){
		return $this -> fotcan;
	}
    
	function getTelcan(){
		return $this -> telcan;
	}
    
	function getNoca(){
		return $this -> noca;
	}
    
	function getIdfic(){
		return $this -> idfic;
	}
    
	function getIdval(){
		return $this -> idval;
	}
    
	function getCanusu(){
		return $this -> canusu;
	}
	// Metodos SET guardar el dato

	function setIdusu($idusu){
		$this->idusu = $idusu;
	}
	function setNdocusu($ndocusu){
		$this->ndocusu = $ndocusu;
	}
	function setNomusu($nomusu){
		$this->nomusu = $nomusu;
	}
	function setIdper($idper){
		$this->idper = $idper;
	}
	function setJornada($jornada){
		$this->jornada = $jornada;
	}
	function setEmausu($emausu){
		$this->emausu = $emausu;
	}
	function setIdcen($idcen){
		$this->idcen = $idcen;
	}
	function setNomcen($nomcen){
		$this->nomcen = $nomcen;
	}
	function setActusu($actusu){
		$this->actusu = $actusu;
	}
	function setFotcan($fotcan){
		$this->fotcan = $fotcan;
	}
	function setTelcan($telcan){
		$this->telcan = $telcan;
	}
	function setNoca($noca){
		$this->noca = $noca;
	}
	function setIdfic($idfic){
		$this->idfic = $idfic;
	}
	function setIdval($idval){
		$this->idval = $idval;
	}

	function setCanusu($canusu){
		$this->canusu = $canusu; 
	}

	function selAll(){
		try{
		$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, f.jornada, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper LEFT JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN usufic AS uf ON uf.idusu=u.idusu LEFT JOIN ficha as f ON f.idfic=uf.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE p.idper=3";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
		}catch(Exception $e){
			ManejoError($e);
		}
	}

    function getJor(){
		try{
		$sql = "SELECT idval, nomval FROM valor WHERE iddom=1";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
		}catch(Exception $e){
			ManejoError($e);
		}
	}

	function nvoCan($canusu){
		try {
		$sql = "SELECT count(idusu) AS nvo FROM voto WHERE canusu=:canusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":canusu", $canusu);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
		}catch(Exception $e){
			ManejoError($e);
		}
	}
	
    function prueba($num){
        echo $num."hola";
    }
	
	// public function getCen(){
	// 	$resultado = null;
	// 	$modelo = new conexion();
	// 	$conexion = $modelo->get_conexion();
	// 	$sql = "SELECT idcen, nomcen FROM centro";
	// 	$result = $conexion->prepare($sql);
	// 	$result->execute();
	// 	while($f=$result->fetch()){
	// 		$resultado[]=$f;
	// 	}
	// 	return $resultado;
	// }

}
?>