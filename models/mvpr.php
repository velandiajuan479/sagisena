<?php
class Mvpr{
// atributos
	private $npro;
	private $idusu;
	private $texpro;
	private $idval;
    private $nomval;
    private $iddom;
    private $parval;
    private $act;

// get
	public function getNpro(){
		return $this->npro;
	}
	public function getIdusu(){
		return $this->idusu;
	}
	public function getTexpro(){
		return $this->texpro;
	}
	public function getIdval(){
		return $this->idval;
	}
	public function getNomval(){
		return $this->nomval;
	}
	public function getIddom(){
		return $this->iddom;
	}
	public function getParval(){
		return $this->parval;
	}
	public function getAct(){
		return $this->act;
	}



// set
	public function setNpro($npro)
	{
		$this->npro = $npro;
	}
	public function setIdusu($idusu)
	{
		$this->idusu = $idusu;
	}
	public function setTexpro($texpro)
	{
		$this->texpro = $texpro;
	}
	public function setIdval($idval)
	{
		$this->idval = $idval;
	}
	public function setNomval($nomval){
		$this->nomval=$nomval;
	}
	public function setIddom($iddom){
		$this->iddom=$iddom;
	}
	public function setParval($parval){
		$this->parval=$parval;
	}
	public function setAct($act){
		$this->act=$act;
	}

	public function selAll(){
		$sql = "SELECT p.npro,p.idusu,p.texpro,v.idval,v.nomval,v.iddom,v.parval,v.act,p.idval AS id FROM propuesta AS p INNER JOIN valor AS v ON p.idval=v.idval";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
        $res = $result-> fetchall(PDO::FETCH_ASSOC);
	    return $res;
	}
	public function selOne($iddom){
		$sql = "SELECT p.npro,p.idusu,p.texpro AS texpro,v.idval,v.nomval,v.iddom,v.parval,v.act,p.idval AS id FROM propuesta AS p INNER JOIN valor AS v ON p.idval=v.idval WHERE p.idusu=:idusu AND v.iddom=:iddom";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu = $this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->bindParam(":iddom",$iddom);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
	    return $res;
	}
	public function ins(){
		$sql = "INSERT INTO propuesta(idusu, texpro, idval) VALUES (:idusu, :texpro, :idval)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu = $this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$texpro = $this->getTexpro();
		$result->bindParam(":texpro",$texpro);
		$idval = $this->getIdval();
		$result->bindParam(":idval",$idval);
		$result->execute();
	}
	public function upd(){
		$sql = "UPDATE propuesta SET idusu=:idusu,texpro=:texpro,idval=:idval WHERE npro=:npro";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		// echo "<br>".$sql."<br>'".$npro."','".$idusu."','".$texpro."','".$idval."'";
		$result = $conexion->prepare($sql);
		// echo "<br>".$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)."<br>";
		$npro = $this->getNpro();
		$result->bindParam(":npro",$npro);
		$idusu = $this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$texpro = $this->getTexpro();
		$result->bindParam(":texpro",$texpro);
		$idval = $this->getIdval();
		$result->bindParam(":idval",$idval);
		$result->execute();
	}
	public function del(){
		$sql = "DELETE FROM propuesta WHERE npro=:npro";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$npro = $this->getNpro();
		$result->bindParam(":npro",$npro);
		$result->execute();

	}

	public function getUsu(){
		$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen INNER JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE u.idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu = $this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res=$result->fetchall(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getVal($iddom){
		$sql = "SELECT idval, nomval, parval, act FROM valor WHERE iddom=:iddom";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":iddom",$iddom);
		$result->execute();
		$res=$result->fetchall(PDO::FETCH_ASSOC);
        return $res;
	}
}
?>