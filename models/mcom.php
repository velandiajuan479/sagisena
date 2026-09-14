<?php 
class Mcom{
	//atributos competencia
	private $idcom;
    private $descom;
    private $vercom;
    private $horcom;
	private $idval;

	//atributos proxcom
	private $codpro;

	//atributos resultado
	private $idres;
	private $nomres;
	private $ndeses;

	//metodos get
	public function getIdcom(){
		return $this->idcom;
	}
	public function getDescom(){
		return $this->descom;
	}
	public function getVercom(){
		return $this->vercom;
	}
	public function getHorcom(){
		return $this->horcom;
	}
	public function getIdval(){
		return $this->idval;
	}

	public function getCodpro(){
		return $this->codpro;
	}

	public function getIdres(){
		return $this->idres;
	}
	public function getNomres(){
		return $this->nomres;
	}
	public function getNdeses(){
		return $this->ndeses;
	}

	//metodos SET
	public function setIdcom($idcom){
		$this->idcom = $idcom;
	}
	public function setDescom($descom){
		$this->descom = $descom;
	}
	public function setVercom($vercom){
		$this->vercom = $vercom;
	}
	public function setHorcom($horcom){
		$this->horcom = $horcom;
	}
	public function setIdval($idval){
		$this->idval = $idval;
	}

	public function setCodpro($codpro){
		$this->codpro = $codpro;
	}

	public function setIdres($idres){
		$this->idres = $idres;
	}
	public function setNomres($nomres){
		$this->nomres = $nomres;
	}
	public function setNdeses($ndeses){
		$this->ndeses = $ndeses;
	}

	//Métodos públicos Competencia
	public function getAll(){
		$res = NULL;
		$sql = "SELECT c.idcom, c.descom, c.vercom, c.horcom, c.idval, v.nomval FROM competencia AS c INNER JOIN valor as v ON c.idval=v.idval INNER JOIN proxcom AS p ON c.idcom=p.idcom WHERE p.codpro=:codpro";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$codpro = $this->getCodpro();
		$result->bindParam(":codpro", $codpro);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getOne(){
		$res = NULL;
		$sql = "SELECT c.idcom, c.descom, c.vercom, c.horcom, c.idval, v.nomval FROM competencia AS c INNER JOIN valor as v ON c.idval=v.idval WHERE c.idcom=:idcom";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getTipComp(){
		$sql = "SELECT idval, nomval, parval FROM valor WHERE act=1 AND iddom=15 ORDER BY parval";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function save(){
		$sql = "INSERT INTO competencia (idcom, descom, vercom, horcom, idval) 
        VALUES (:idcom, :descom, :vercom, :horcom, :idval)";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		$descom = $this->getDescom();
		$result->bindParam(":descom", $descom);
		$vercom = $this->getVercom();
		$result->bindParam(":vercom", $vercom);
        $horcom = $this->getHorcom();
		$result->bindParam(":horcom", $horcom);
		$idval = $this->getIdval();
		$result->bindParam(":idval", $idval);
		/*echo $sql. "<br>Insertar<br>'".$idcom."','".$descom."','".$vercom."','".$horcom."','".$idval."'";
		die();*/
		$result->execute();
	}
	public function edit(){
		$sql = "UPDATE competencia SET descom=:descom, vercom=:vercom, horcom=:horcom, idval=:idval WHERE idcom=:idcom";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
        $idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		$descom = $this->getDescom();
		$result->bindParam(":descom", $descom);
		$vercom = $this->getVercom();
		$result->bindParam(":vercom", $vercom);
        $horcom = $this->getHorcom();
		$result->bindParam(":horcom", $horcom);
		$idval = $this->getIdval();
		$result->bindParam(":idval", $idval);
		/*echo $sql. "'<br>Actualizando<br>'".$idcom."','".$descom."','".$vercom."','".$horcom."','".$idval."'";
		die();*/
		$result->execute();
	}

	public function del(){
		$sql = "DELETE FROM competencia WHERE idcom=:idcom";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		$result->execute();
	}


	//Métodos públicos ProxCom
	public function savePxC(){
		$sql = "INSERT INTO proxcom(codpro, idcom) VALUES (:codpro, :idcom)";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$codpro = $this->getCodpro();
		$result->bindParam(":codpro", $codpro);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		/* echo $sql. "<br><br>'".$codpro."','".$idcom."'";
		die(); */
		$result->execute();
	}

	public function delPxC(){
		$sql = "DELETE FROM proxcom WHERE codpro=:codpro AND idcom=:idcom";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$codpro = $this->getCodpro();
		$result->bindParam(":codpro", $codpro);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		/*echo $sql."<br>'".$codpro."','".$idcom."'";
		die();*/
		$result->execute();
	}

	//Métodos públicos Resultados
	public function getAllRs(){
		$res = NULL;
		$sql = "SELECT idres, nomres, idcom, ndeses FROM resultado WHERE idcom=:idcom";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getOneRs(){
		$res = NULL;
		$sql = "SELECT idres, nomres, idcom, ndeses FROM resultado WHERE idres=:idres";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idres = $this->getIdres();
		$result->bindParam(":idres", $idres);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function saveRs(){
		$sql = "INSERT INTO resultado (idres, nomres, idcom, ndeses) 
        VALUES (:idres, :nomres, :idcom, :ndeses)";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idres = $this->getIdres();
		$result->bindParam(":idres", $idres);
		$nomres = $this->getNomres();
		$result->bindParam(":nomres", $nomres);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
        $ndeses = $this->getNdeses();
		$result->bindParam(":ndeses", $ndeses);
		// echo $sql. "'<br><br>'".$idres."','".$nomres."','".$idcom."','".$ndeses."'";
		// die();
		$result->execute();
	}
	public function editRs(){
		$sql = "UPDATE resultado SET nomres=:nomres, idcom=:idcom, ndeses=:ndeses WHERE idres=:idres";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
        $idres = $this->getIdres();
		$result->bindParam(":idres", $idres);
		$nomres = $this->getNomres();
		$result->bindParam(":nomres", $nomres);
		$idcom = $this->getIdcom();
		$result->bindParam(":idcom", $idcom);
        $ndeses = $this->getNdeses();
		$result->bindParam(":ndeses", $ndeses);
		$result->execute();
	}

	public function delRs(){
		$sql = "DELETE FROM resultado WHERE idres=:idres";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idres = $this->getIdres();
		$result->bindParam(":idres", $idres);
		$result->execute();
	}
}
?>