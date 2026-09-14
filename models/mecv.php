<?php
class Mecv{

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
	private $idfic;
	private $finc;
	private $idval;
	private $inic;

	// METODOS GET-------------------------

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
	public function getIdfic($idfic){
		return $this->$idfic;
	}
	public function getFinc($finc){
		return $this->$finc;
	}
	public function getInic($inic){
		return $this->$inic;
	}

	// Metodos set-----------------------

	public function setIdusu($idusu){
		$this->idusu=$idusu;
	}
	public function setNdocusu($ndocusu){
		$this->ndocusu=$ndocusu;
	}
	public function setNomusu($nomusu){
		$this->nomusu=$nomusu;
	}
	public function setIdper($idper){
		$this->idper=$idper;
	}
	public function setPasusu($pasusu){
		$this->pasusu=$pasusu;
	}
	public function setEmausu($emausu){
		$this->emausu=$emausu;
	}
	public function setIdcen($idcen){
		$this->idcen=$idcen;
	}
	public function setActusu($actusu){
		$this->actusu=$actusu;
	}
	public function setFotcan($fotcan){
		$this->fotcan=$fotcan;
	}
	public function setTelcan($telcan){
		$this->telcan=$telcan;
	}
	public function setIdfic($idfic){
		$this->idfic=$idfic;
	}
	public function setFinc($finc){
		$this->finc=$finc;
	}
	public function setInic($inic){
		$this->inic=$inic;
	}
	public function setNoca($noca){
		$this->noca=$noca;
	}

	public function getAll($idfic){
		$sql="SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE uf.idfic='".$idfic."' OR u.ndocusu='".$idfic."' OR u.nomusu='".$idfic."' OR u.nomusu LIKE '%".$idfic."%'";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getTficha(){
		$sql="SELECT idusu, COUNT(*) AS Total FROM usufic  WHERE idusu=:idusu GROUP BY idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getNomFics(){
		$sql = "SELECT u.idusu,u.idfic,f.nomfic FROM usufic as u INNER JOIN ficha as f ON u.idfic=f.idfic where u.idusu=:idusu ";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

    public function getAprendicesPorFicha($idfic) {
        $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, u.actusu, u.fotcan, 
                    (SELECT 1 FROM usupef WHERE idusu = u.idusu AND idper = 13 LIMIT 1) AS es_candidato
                FROM usuario AS u 
                INNER JOIN usufic AS uf ON u.idusu = uf.idusu 
                INNER JOIN perfil AS p ON u.idper = p.idper
                WHERE uf.idfic = :idfic AND u.idper IN (3, 4, 8, 22,13)"; 
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Metodo para contar candidatos vocero por ficha
    public function contarCandidatosVocero($idfic) {
        $sql = "SELECT COUNT(*) as total 
                FROM usupef up
                INNER JOIN usufic uf ON up.idusu = uf.idusu
                WHERE uf.idfic = :idfic AND up.idper = 13";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res['total'];
    }

    // Metodo para eliminar candidato vocero y cambiar a aprendiz
    public function eliminarCandidatoVocero($idusu) {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            
            // se elimina el perfil vocero del aprendiz
            $sqlDelete = "DELETE FROM usupef WHERE idusu = :idusu AND idper = 13";
            $resultDelete = $conexion->prepare($sqlDelete);
            $resultDelete->bindParam(":idusu", $idusu);
            $resultDelete->execute();
            
            // se Cambia perfil vocero a aprendiz 
            $sqlUpdate = "UPDATE usuario SET idper = 8, noca = NULL WHERE idusu = :idusu";
            $resultUpdate = $conexion->prepare($sqlUpdate);
            $resultUpdate->bindParam(":idusu", $idusu);
            $resultUpdate->execute();
            
            return true;
        } catch(PDOException $e) {
            error_log("Error al eliminar candidato vocero: " . $e->getMessage());
            return false;
        }
    }

    
	// public function selApren(){
	// 	$resultado = null;
	// 	$modelo = new conexion();
	// 	$conexion = $modelo->get_conexion();
	// 	$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper FROM usuario AS u WHERE idper IN (3,4) AND noca<>999";
	// 	$result = $conexion->prepare($sql);
	// 	$result->execute();
	// 	while($f=$result->fetch()){
	// 		$resultado[]=$f;
	// 	}
	// 	return $resultado;
	// }
	public function getOne(){
		$sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE u.idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
//ins($ndocusu, $nomusu, $idper, $idfic, $pasusu, $idcen, $actusu);
	public function save($dt=1){
		try{
			$sql = "INSERT INTO usuario(idper, ndocusu, nomusu, pasusu, idcen, actusu, emausu, telcan, noca, fotcan) VALUES (:idper, :ndocusu, :nomusu, :pasusu, :idcen, :actusu, :emausu, :telcan, :noca, :fotcan)";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);

			$idper=$this->getIdper();
			$result->bindParam(":idper",$idper);

			$ndocusu=$this->getNdocusu();
			$result->bindParam(":ndocusu",$ndocusu);

			$nomusu=$this->getNomusu();
			$result->bindParam(":nomusu",$nomusu);

			$pasusu=$this->getPasusu();
			$pasusu = sha1(md5($pasusu));
			$result->bindParam(":pasusu",$pasusu);

			$idcen=$this->getIdcen();
			$result->bindParam(":idcen",$idcen);

			$actusu=$this->getActusu();
			$result->bindParam(":actusu",$actusu);

			$emausu=$this->getEmausu();
			$result->bindParam(":emausu",$emausu);

			$telcan=$this->getTelcan();
			$result->bindParam(":telcan",$telcan);

			$noca=$this->getNoca();
			$result->bindParam(":noca",$noca);

			$fotcan=$this->getFotcan();
			$result->bindParam(":fotcan",$fotcan);
			$result->execute();
		}catch(Exception $e){
			if($dt==1) ManejoError($e);
			echo $e->getMessage();
		}
	}

	// public function selOneIdusu($ndocusu, $nomusu, $idper, $idcen, $actusu, $emausu, $telcan, $noca){
	// 	$resultado = null;
	// 	$modelo = new conexion();
	// 	$conexion = $modelo->get_conexion();
	// 	$sql = "SELECT idusu FROM usuario WHERE ndocusu=:ndocusu AND nomusu=:nomusu AND idper=:idper AND idcen=:idcen AND actusu=:actusu AND emausu=:emausu AND telcan=:telcan AND noca=:noca";
	// 	$result = $conexion->prepare($sql);
	// 	$result->bindParam(":ndocusu",$ndocusu);
	// 	$result->bindParam(":nomusu",$nomusu);
	// 	$result->bindParam(":idper",$idper);
	// 	$result->bindParam(":idcen",$idcen);
	// 	$result->bindParam(":actusu",$actusu);
	// 	$result->bindParam(":emausu",$emausu);
	// 	$result->bindParam(":telcan",$telcan);
	// 	$result->bindParam(":noca",$noca);
	// 	$result->execute();
	// 	while($f=$result->fetch()){
	// 		$resultado[]=$f;
	// 	}
	// 	return $resultado;
	// }
	public function edi(){
		try{
		$pasusu=$this->getPasusu();
		$sql = "UPDATE usuario SET ndocusu=:ndocusu,nomusu=:nomusu,idper=:idper,";
		if($pasusu) $sql .= "pasusu=:pasusu,";
		$sql .= "idcen=:idcen,actusu=:actusu, emausu=:emausu, telcan=:telcan, noca=:noca, fotcan=:fotcan WHERE idusu=:idusu";
		$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$result = $conexion->prepare($sql);

			$idusu=$this->getIdusu();
			$result->bindParam(":idusu",$idusu);

			$ndocusu=$this->getNdocusu();
			$result->bindParam(":ndocusu",$ndocusu);

			$nomusu=$this->getNomusu();
			$result->bindParam(":nomusu",$nomusu);

			$idper=$this->getIdper();
			$result->bindParam(":idper",$idper);
			if($pasusu){
				$pasusu = sha1(md5($pasusu));
				$result->bindParam(":pasusu",$pasusu);
			}
			$idcen=$this->getIdcen();
			$result->bindParam(":idcen",$idcen);

			$actusu=$this->getActusu();
			$result->bindParam(":actusu",$actusu);

			$emausu=$this->getEmausu();
			$result->bindParam(":emausu",$emausu);

			$telcan=$this->getTelcan();
			$result->bindParam(":telcan",$telcan);

			$noca=$this->getNoca();
			$result->bindParam(":noca",$noca);

			$fotcan=$this->getFotcan();
			$result->bindParam(":fotcan",$fotcan);
			
			$result->execute();
		}catch(Exception $e){
            if(strpos($e->getMessage(), '1062'))
                echo '';//'<script>alert("Ficha existente");</script>';
        }
	}

    

	public function updPasc($ips,$fps){
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql = "UPDATE usuario SET pasusu=sha(md5(concat('$ips',ndocusu,'$fps'))) WHERE idper NOT IN (1)";
		//echo $sql."<br>";
		$result = $conexion->prepare($sql);
		$result->execute();
	}
	public function updPascm($ips,$ndoc,$fps){
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql = "UPDATE usuario SET pasusu=sha(md5(concat('$ips','$ndoc','$fps'))) WHERE idper NOT IN (1)";
		//echo $sql."<br>";
		$result = $conexion->prepare($sql);
		$result->execute();
	}

	public function del(){
		$sql = "DELETE FROM usuario WHERE idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
	}

	public function getCentro(){
		$sql = "SELECT idcen, nomcen FROM centro";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getPerfil(){
		$sql = "SELECT idper, nomper FROM perfil";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getFicha(){
		$sql = "SELECT f.idfic, f.nomfic, v.nomval FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval WHERE f.idfic>1000 ORDER BY f.idfic, f.nomfic";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getMod(){
		$sql = "SELECT idmod, nommod, idper FROM modulo";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getUsPe($idmod, $idusu){
		$sql = "SELECT s.idper FROM usupef AS s INNER JOIN perfil AS p ON s.idper=p.idper WHERE p.idmod=:idmod AND s.idusu=:idusu;";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":idmod",$idmod);
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	
	public function getPefus($idmod){
		$sql = "SELECT idper, nomper FROM perfil WHERE idmod=:idmod";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":idmod",$idmod);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function insUxP(){
		$sql = "INSERT INTO usupef(idusu, idper) VALUES (:idusu, :idper)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$idper=$this->getIdper();
		$result->bindParam(":idper",$idper);
		$result->execute();
	}

	public function delUxP(){
		$sql = "DELETE FROM usupef WHERE idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
	}

	public function insUxF($idusu, $idfic){
		$sql = "INSERT INTO usufic(idusu, idfic, actfic) VALUES (:idusu, :idfic, 1)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":idusu",$idusu);
		$result->bindParam(":idfic",$idfic);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function ediUxF(){
		$sql = "UPDATE usufic SET actfic=2 WHERE idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$idusu=$this->getIdusu();
		$result->bindParam(":idusu",$idusu);
		$result->execute();
	}

	public function delUxPF($idusu,$idfic){
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$sql = "DELETE FROM usufic WHERE idusu=:idusu AND idfic=:idfic";
		$result = $conexion->prepare($sql);
		$result->bindParam(":idusu",$idusu);
		$result->bindParam(":idfic",$idfic);
		$result->execute();
	}
	
	public function getFicUsu($idusu){
		$sql = "SELECT f.idfic, f.nomfic, f.jornada, f.idcen, f.mun, u.idusu, u.actfic FROM ficha AS f INNER JOIN usufic AS u ON f.idfic=u.idfic WHERE u.idusu=:idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":idusu",$idusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getFicUsuSec($idusu,$idfic){
		$sql = "SELECT f.idfic FROM ficha AS f INNER JOIN usufic AS u ON f.idfic=u.idfic WHERE u.idusu=:idusu AND u.idfic=:idfic";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":idusu",$idusu);
		$result->bindParam(":idfic",$idfic);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function selectUsu(){
		$sql = "SELECT idusu, COUNT(*) as sum FROM usuario WHERE ndocusu=:ndocusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$ndocusu=$this->getNdocusu();
		$result->bindParam(":ndocusu",$ndocusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
	public function getUsu($ndocusu){
		$sql = "SELECT idusu, nomusu, ndocusu FROM usuario WHERE ndocusu=:ndocusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(":ndocusu",$ndocusu);
		$result->execute();
		$res=$result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}
}
?>