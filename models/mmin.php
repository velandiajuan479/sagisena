<?php
class Mmin{ 
	private $idusu;
	private $nummin;
	private $fechos;
	private $tipmin;
	private $hij;
	private $fhlle;
	private $obs;
	private $ideles;

	function getIdusu() { return $this->idusu; }
	function getNummin() { return $this->nummin; }
	function getFechos() { return $this->fechos; }
	function getTipmin() { return $this->tipmin; }
	function getHij() { return $this->hij; }
	function getObs() { return $this->obs; }
	function getFhlle() { return $this->fhlle; }
	function getIdeles() { return $this->ideles; }

	function setIdusu($idusu) { $this->idusu = $idusu; }
	function setNummin($nummin) { $this->nummin = $nummin; }
	function setFechos($fechos) { $this->fechos = $fechos; }
	function setTipmin($tipmin) { $this->tipmin = $tipmin; }
	function setHij($hij) { $this->hij = $hij; }
	function setFhlle($fhlle) { $this->fhlle = $fhlle; }
	function setObs($obs) { $this->obs = $obs; }
	function setIdeles($ideles) { $this->ideles = $ideles; }

	/* public function getAll() {
		$sql = "SELECT m.nummin, COALESCE(u.nomusu, 'Invitado') AS nomusu, COALESCE(u.ndocusu, m.idusu) AS ndocusu, p.nomper, uf.idfic, f.nomfic, 
		v.nomval, m.fechos, m.fhlle, m.obs, m.tipmin, m.hij, m.ideles, TIMEDIFF(m.fhlle, m.fechos) as tiempo FROM minuta AS m LEFT JOIN usuario 
		AS u ON m.idusu=u.idusu LEFT JOIN perfil AS p ON u.idper=p.idper LEFT JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON 
		uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE DATE(m.fechos) = CURRENT_DATE() 
		ORDER BY m.fechos DESC;";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = NULL;
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	} */

	public function getTodo($datemin, $datemax) {
		$res = NULL;
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$idusu = $this->getIdusu();
		$sql = "SELECT m.nummin, COALESCE(u.nomusu, 'Invitado') AS nomusu, COALESCE(u.ndocusu, m.idusu) AS ndocusu, p.nomper, uf.idfic, f.nomfic, v.nomval, m.fechos, m.fhlle, m.obs, m.tipmin, m.hij, m.ideles, TIMEDIFF(m.fhlle, m.fechos) as tiempo FROM minuta AS m LEFT JOIN usuario AS u ON m.idusu=u.idusu LEFT JOIN perfil AS p ON u.idper=p.idper LEFT JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE DATE(m.fechos)>=:datemin";
		if($datemax) $sql .= " AND DATE(m.fechos)<=:datemax AND tipmin IN ('F','S','I')";
		if($idusu) $sql .= " AND u.ndocusu LIKE CONCAT('%', :idusu, '%')";
		$sql .= " ORDER BY m.fechos DESC;";
		$result = $conexion->prepare($sql);
		$result->bindParam(':datemin',$datemin);
		if($datemax) $result->bindParam(':datemax',$datemax);
		if($idusu) $result->bindParam(":idusu",$idusu);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getTodoP() {
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql = "SELECT m.nummin, m.idusu, m.fechos, m.tipmin, m.hij, m.fhlle, m.obs, m.ideles, u.nomusu, u.ndocusu, u.idper, u.telcan, p.nomper, e.nomele, f.idfic, f.nomfic, v.nomval FROM minuta AS m INNER JOIN usuario AS u ON u.idusu=m.idusu INNER JOIN perfil AS p ON p.idper=u.idper INNER JOIN elemento AS e ON e.idele=m.ideles LEFT JOIN usufic AS uf ON uf.idusu=u.idusu LEFT JOIN ficha AS f ON f.idfic=uf.idfic LEFT JOIN valor AS v ON v.idval=f.jornada WHERE tipmin IN ('A')";
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getTodoA() {
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql = "SELECT m.nummin, m.idusu, m.fechos, m.tipmin, m.hij, m.fhlle, m.obs, m.ideles, u.nomusu, u.ndocusu, u.idper, u.telcan, p.nomper, v.nomval, v.parval FROM minuta AS m INNER JOIN usuario AS u ON u.idusu=m.idusu INNER JOIN perfil AS p ON p.idper=u.idper INNER JOIN valor AS v ON v.idval=m.ideles WHERE tipmin IN ('N')";
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function selOneEle($idele) {
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql = "SELECT e.idele, e.idusu, e.nomele, e.nidele, e.marele, e.tipele, v.nomval, e.noplasena, e.desele, e.preele, f.rutfot FROM elemento AS e INNER JOIN valor AS v ON e.tipele=v.idval LEFT JOIN foto AS f ON e.idele=f.idele WHERE e.idele=:idele";
		$result = $conexion->prepare($sql);
		$result->bindParam(":idele", $idele);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
  }
}
?>