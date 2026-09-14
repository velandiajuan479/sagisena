
<?php
class Mdpc{
	//idusu, ndocusu, nomusu, idper, pasusu, emausu, idcen, actusu, fotcan, telcan, noca 
	private $idusu;
	private $ndocusu;
	private $nomusu;
	private $idper;
	private $pasusu;
	private $emausu;
	private $idcen;
	private $actusu;
	private $fotcan;
	private $telcan;
	private $noca;
	private $canusu;

	public function getIdusu(){
		return $this->idusu;
	}
	public function getNdocusu(){
		return $this->ndocusu;
	}
	public function getNomusu(){
		return $this->nomusu;
	}
	public function getIdper(){
		return $this->idper;
	}
	public function getPasusu(){
		return $this->pasusu;
	}
	public function getEmausu(){
		return $this->emausu;
	}
	public function getIdcen(){
		return $this->idcen;
		
	}
	public function getActusu(){
		return $this->actusu;
	}
	public function getFotcan(){
		return $this->fotcan;
	}
	public function getTelcan(){
		return $this->telcan;
	}
	public function getNoca(){
		return $this->noca;
	}
	public function getCanusu(){
		return $this->canusu;
	}


	public function setIdusu($idusu){
		$this->idusu = $idusu;
	}
	public function setNdocusu($ndocusu){
		$this->ndocusu = $ndocusu;
	}
	public function setNomusu($nomusu){
		$this->nomusu = $nomusu;
	}
	public function setIdper($idper){
		$this->idper = $idper;
	}
	public function setPasusu($pasusu){
		$this->pasusu = $pasusu ;
	}
	public function setEmausu($emausu){
		$this->emausu = $emausu ;
	}
	public function setIdcen($idcen){
		$this->idcen = $idcen;	
	}
	public function setActusu($actusu){
		$this->actusu = $actusu;
	}
	public function setFotcan($fotcan){
		$this->fotcan = $fotcan;
	}
	public function setTelcan($telcan){
		$this->telcan = $telcan;
	}
	public function setNoca($noca){
		$this->noca =$noca ;
	}
	
	public function set($canusu){
		$this-> canusu=$canusu;
	}
	public function selAll($jornada, $idcen){
		$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, f.idfic, f.nomfic, c.nomcen, u.actusu, u.fotcan, v.nomval, u.emausu, u.noca FROM usuario AS u LEFT JOIN usupef AS up ON u.idusu=up.idusu INNER JOIN usufic AS uf ON u.idusu=uf.idusu INNER JOIN ficha AS f ON uf.idfic=f.idfic INNER JOIN centro AS c ON f.idcen=c.idcen INNER JOIN valor AS v ON f.jornada=v.idval WHERE f.jornada=:jornada AND c.idcen=:idcen AND up.idper=3;";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":jornada", $jornada);
		$result->bindParam(":idcen", $idcen);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	
	
	public function selOne(){
		$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen INNER JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function upd2(){
		$sql = "UPDATE usuario SET emausu=:emausu, telcan=:telcan, noca=:noca, fotcan=:fotcan WHERE idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$emausu=$this->getEmausu();
		$result->bindParam(":emausu",$emausu);
		$telcan=$this->getTelcan();
		$result->bindParam(":telcan",$telcan);
		$noca=$this->getNoca();
		$result->bindParam(":noca",$noca);
		$fotcan=$this->getFotcan();
		$result->bindParam(":fotcan",$fotcan);
		$result->execute();
	}

	public function limpiaVot(){
		$sql = "DELETE FROM voto";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
	}

	public function nvoCan($canusu){
		$sql = "SELECT count(idusu) AS nvo FROM voto WHERE canusu=:canusu";
		// $sql .= " WHERE u.idper IN (3,4)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		// $canusu=$this->getCanusu();
		$result->bindParam(":canusu", $canusu);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getJor(){
		
		$sql = "SELECT idval, nomval FROM valor WHERE iddom=1";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getCen(){
		$sql = "SELECT idcen, nomcen FROM centro";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getLstVt(){
		$sql = "SELECT u.nomusu, t.nomval AS tdu, u.ndocusu, g.nomval AS gus, c.idfic, f.nomfic, j.nomval AS jornada, n.nomcen, u.emausu, u.telcan, v.dtvot FROM voto AS v INNER JOIN usuario AS u ON v.idusu=u.idusu INNER JOIN valor AS t ON u.tdousu=t.idval INNER JOIN valor AS g ON u.genusu=g.idval INNER JOIN usufic AS c ON u.idusu=c.idusu AND c.actfic=1 INNER JOIN ficha AS f ON c.idfic=f.idfic INNER JOIN valor AS j ON f.jornada=j.idval INNER JOIN centro as n ON f.idcen=n.idcen ORDER BY j.nomval, c.idfic, u.nomusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
	}

}
?>