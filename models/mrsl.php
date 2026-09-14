<?php 
class Mrsl{
	//atributos
	private $idres;
    private $nomres;
    private $idcom;
    private $ndeses;
	//metodos get
	public function getIdres(){
		return $this->idres;
	}
	public function getNomres(){
		return $this->nomres;
	}
	public function getIdcom(){
		return $this->idcom;
	}
	public function getNdeses(){
		return $this->ndeses;
	}
	//metodos SET
	public function setIdcom($idres){
		$this->idres = $idres;
	}
	public function setDescom($nomres){
		$this->nomres = $nomres;
	}
	public function setVercom($idcom){
		$this->idcom = $idcom;
	}
	public function setHorcom($ndeses){
		$this->ndeses = $ndeses;
	}
	public function setIdres($idres) {
        $this->idres = $idres;
    }
	//metodos publicos
	public function getAll(){
		$res = NULL;
		$sql = "SELECT * FROM resultado";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getOne(){
		$res = NULL;
		$sql = "SELECT * FROM resultado WHERE idres = :idres";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idres = $this->getIdres();
		$result->bindParam(":idres", $idres);
		$result->execute();
		$res= $result->fetchall(PDO::FETCH_ASSOC);
		return $res;
	}
	public function save() {
		$sql = "INSERT INTO resultado (nomres, idcom, ndeses) VALUES (:nomres, :idcom, :ndeses)";
		$modelo = new Conexion(); 
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":nomres", $this->nomres);
		$result->bindParam(":idcom", $this->idcom);
		$result->bindParam(":ndeses", $this->ndses);
		$result->execute();
	  }
	  public function edit() {
		$sql = "UPDATE resultado SET nomres=:nomres, idcom=:idcom, ndeses=:ndeses WHERE idres=:idres";
		$modelo = new Conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":idres", $this->idres);
		$result->bindParam(":nomres", $this->nomres);
		$result->bindParam(":idcom", $this->idcom);
		$result->bindParam(":ndeses", $this->ndses);
		$result->execute();
	  }

	public function del(){
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