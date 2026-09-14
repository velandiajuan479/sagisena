<?php
class Mregm {
    private $iduxf;
    private $idusu;
    private $idfic;
    private $fecreg;

    function getIduxf() { return $this->iduxf; }
    function getIdusu() { return $this->idusu; }
    function getIdfic() { return $this->idfic; }
    function getFecreg() { return $this->fecreg; }

    function setIduxf($iduxf) { $this->iduxf = $iduxf; }
    function setIdusu($idusu) { $this->idusu = $idusu; }
    function setIdfic($idfic) { $this->idfic = $idfic; }
    function setFecreg($fecreg) { $this->fecreg = $fecreg; }

    public function getAll() {
        $sql = "SELECT r.idfic, f.nomfic, f.codpro, f.jornada, v.nomval, f.mun, m.nomubi AS nommun, m.depubi, d.nomubi AS nomdep, COUNT(r.idusu) AS can FROM registro AS r INNER JOIN ficha AS f ON r.idfic=f.idfic INNER JOIN valor as v ON f.jornada=v.idval INNER JOIN ubica AS m ON f.mun=m.codubi LEFT JOIN ubica AS d ON m.depubi=d.codubi GROUP BY r.idfic, f.nomfic, f.codpro, f.jornada, v.nomval, f.mun, m.nomubi, m.depubi, d.nomubi ORDER BY r.idfic";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTot() {
        $sql = "SELECT COUNT(r.idusu) AS can FROM registro AS r INNER JOIN ficha AS f ON r.idfic=f.idfic INNER JOIN valor as v ON f.jornada=v.idval INNER JOIN ubica AS m ON f.mun=m.codubi LEFT JOIN ubica AS d ON m.depubi=d.codubi";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

	public function getOne(){
		$sql = "SELECT r.iduxf, r.idusu, u.ndocusu, u.nomusu, r.idfic, f.nomfic, r.fecreg FROM registro AS r INNER JOIN usuario AS u ON r.idusu=u.idusu INNER JOIN ficha AS f ON r.idfic=f.idfic WHERE r.iduxf=:iduxf";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$iduxf = $this->getIduxf();
		$result->bindParam(':iduxf',$iduxf);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
    public function getOneFil() {
    try {
        $sql = "SELECT iduxf FROM registro WHERE idfic = :idfic AND idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idfic = $this->getIdfic();
        $idusu = $this->getIdusu();
        $result->bindParam(':idfic', $idfic);
        $result->bindParam(':idusu', $idusu);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "<br><br>Ya existe un registro con ese usuario.";
    }
}

	public function save(){
		$sql = "INSERT INTO registro (idusu, idfic, fecreg) VALUES (:idusu, :idfic, :fecreg)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu = $this->getIdusu();
		$result->bindParam(':idusu',$idusu);
		$idfic = $this->getIdfic();
		$result->bindParam(':idfic',$idfic);
		$fecreg = $this->getFecreg();
		$result->bindParam(':fecreg',$fecreg);
		$result->execute();
	}

	public function saveUF(){
		$sql = "UPDATE usufic SET actfic='2' WHERE idusu=:idusu;";
		$sql .= "INSERT INTO usufic(idusu, idfic, actfic) VALUES (:idusu, :idfic, :actfic);";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu = $this->getIdusu();
		$result->bindParam(':idusu',$idusu);
		$idfic = $this->getIdfic();
		$result->bindParam(':idfic',$idfic);
		$actfic = 1;
		$result->bindParam(':actfic',$actfic);
		$result->execute();
	}

	public function getOneUF() {
	    try {
	        $sql = "SELECT COUNT(idusu) AS ct FROM usufic WHERE idusu=:idusu AND idfic=:idfic";
	        $modelo = new conexion();
	        $conexion = $modelo->get_conexion();
	        $result = $conexion->prepare($sql);
	        $idfic = $this->getIdfic();
	        $idusu = $this->getIdusu();
	        $result->bindParam(':idfic', $idfic);
	        $result->bindParam(':idusu', $idusu);
	        $result->execute();
	        return $result->fetchAll(PDO::FETCH_ASSOC);
	    } catch (PDOException $e) {
	        echo "Error: " . $e->getMessage() . "<br><br>Ya existe un registro con ese usuario.";
	    }
	}

	public function edit(){
		$sql = "UPDATE registro SET idusu=:idusu, idfic=:idfic, fecreg=:fecreg WHERE iduxf=:iduxf";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$iduxf = $this->getIduxf();
		$result->bindParam(':iduxf',$iduxf);
		$idusu = $this->getIdusu();
		$result->bindParam(':idusu',$idusu);
		$idfic = $this->getIdfic();
		$result->bindParam(':idfic',$idfic);
		$fecreg = $this->getFecreg();
		$result->bindParam(':fecreg',$fecreg);
		$result->execute();
	}

	public function del(){
		$sql = "DELETE FROM registro WHERE iduxf=:iduxf";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$iduxf = $this->getIduxf();
		$result->bindParam(':iduxf',$iduxf);
		$result->execute();
	}

	public function getAllUsu(){
		$sql = "SELECT idusu, ndocusu, nomusu FROM usuario";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getAllFic(){
		$sql = "SELECT idfic, nomfic FROM ficha";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

    
}
?>
