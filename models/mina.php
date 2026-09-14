<?php
class Mina
{
	private $idina;
	private $idusu;
	private $fecina;
	private $fecreg;
	private $horasina;
	private $idfic;
	private $comina;
	private $parval;
	private $idval;
	private $tipllam;
	private $obsllam;
	private $fecllam;

	// Métodos Get

	function getIdina(){
		return $this->idina;
	}
	function getIdusu(){
		return $this->idusu;
	}
	function getFecina(){
		return $this->fecina;
	}
	function getFecreg(){
		return $this->fecreg;
	}
	function getHorasina(){
		return $this->horasina;
	}
	function getIdfic(){
		return $this->idfic;
	}
	function getComina(){
		return $this->comina;
	}
	function getParval(){
		return $this->parval;
	}
	function getIdval(){
		return $this->idval;
	}
	function getTipllam(){
		return $this->tipllam;
	}
	function getObsllam(){
		return $this->obsllam;
	}
	function getFecllam(){
		return $this->fecllam;
	}

	// Métodos SET

	function setIdina($idina){
		$this->idina=$idina;
	}
	function setIdusu($idusu){
		$this->idusu=$idusu;
	}function setFecina($fecina){
		$this->fecina=$fecina;
	}
	function setFecreg($fecreg){
		$this->fecreg=$fecreg;
	}
	function setHorasina($horasina){
		$this->horasina=$horasina;
	}
	function setIdfic($idfic){
		$this->idfic=$idfic;
	}
	function setComina($comina) {
    	$this->comina = $comina;
	}

	function setParval($parval){
		$this->parval=$parval;
	}
	function setIdval($idval){
		$this->idval=$idval;
	}function setTipllam($tipllam){
		$this->tipllam=$tipllam;
	}
	function setObsllam($obsllam){
		$this->obsllam=$obsllam;
	}
	function setFecllam($fecllam){
		$this->fecllam=$fecllam;
	}

	//Funciones de consulta
	
	public function selAll($idfic){
		$resultado = null;
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		
		$sql = 'SELECT u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan, 
					   c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora,
					   COALESCE(SUM(i.horasina), 0) AS horasina
				FROM usuario AS u
				INNER JOIN usufic AS f ON u.idusu = f.idusu
				INNER JOIN ficha AS c ON f.idfic = c.idfic
				INNER JOIN valor AS v ON c.jornada = v.idval
				LEFT JOIN inasistencia AS i ON u.idusu = i.idusu AND i.idfic = c.idfic
				WHERE f.idfic = :idfic
				  AND u.actusu = 1
				  AND u.idusu NOT IN (
					  SELECT m.idusu 
					  FROM minuta AS m 
					  INNER JOIN usufic AS f2 ON m.idusu = f2.idusu 
					  WHERE DATE(m.fechos) = CURRENT_DATE() AND f2.idfic = :idfic
				  )
				  AND u.idusu NOT IN (
					  SELECT idusu FROM inasistencia 
					  WHERE DATE(fecina) = CURRENT_DATE()
				  )
				GROUP BY u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan,
						 c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora';
		
		$result = $conexion->prepare($sql);
		$result->bindParam(':idfic', $idfic);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function selAsistieron($idfic) {
		$resultado = null;
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();

		$sql = 'SELECT u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan,
					c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora,
					m.fechos,
					COALESCE(SUM(i.horasina), 0) AS horasina
				FROM usuario AS u
				INNER JOIN minuta AS m ON u.idusu = m.idusu
				INNER JOIN usufic AS f ON u.idusu = f.idusu
				INNER JOIN ficha AS c ON f.idfic = c.idfic
				INNER JOIN valor AS v ON c.jornada = v.idval
				LEFT JOIN inasistencia AS i ON u.idusu = i.idusu AND i.idfic = c.idfic
				WHERE f.idfic = :idfic
				AND u.actusu = 1
				AND DATE(m.fechos) = CURRENT_DATE()
				GROUP BY u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan,
						c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora, m.fechos';

		$result = $conexion->prepare($sql);
		$result->bindParam(':idfic', $idfic);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
	}

	public function selInasistHoy($idfic){
		$resultado = null;
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();

		$sql = 'SELECT u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan, 
					c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora,
					COALESCE(SUM(i.horasina), 0) AS horasina
				FROM usuario AS u
				INNER JOIN usufic AS f ON u.idusu = f.idusu
				INNER JOIN ficha AS c ON f.idfic = c.idfic
				INNER JOIN valor AS v ON c.jornada = v.idval
				LEFT JOIN inasistencia AS i ON u.idusu = i.idusu AND i.idfic = c.idfic
				WHERE f.idfic = :idfic
				AND u.actusu = 1
				AND u.idusu IN (
					SELECT idusu FROM inasistencia
					WHERE DATE(fecina) = CURRENT_DATE()
					AND idfic = :idfic
				)
				GROUP BY u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan,
						c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora';

		$result = $conexion->prepare($sql);
		$result->bindParam(':idfic', $idfic);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function selNove($idfic){
		$resultado = null;
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();

		$sql = 'SELECT 
				u.idusu, 
				u.ndocusu, 
				u.nomusu, 
				u.emausu, 
				u.fotcan, 
				u.telcan, 
				u.idper,
				u.actusu,
				c.idfic, 
				c.nomfic, 
				c.jornada, 
				v.nomval, 
				v.nhora,
				COALESCE(SUM(i.horasina), 0) AS horasina
			FROM usuario AS u
			INNER JOIN usufic AS f ON u.idusu = f.idusu
			INNER JOIN ficha AS c ON f.idfic = c.idfic
			INNER JOIN valor AS v ON u.actusu = v.parval AND v.iddom = 10
			LEFT JOIN inasistencia AS i ON u.idusu = i.idusu AND i.idfic = c.idfic
			WHERE f.idfic = :idfic
			AND u.actusu IN (3, 4, 5, 6)
			GROUP BY 
				u.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan, u.idper, u.actusu,
				c.idfic, c.nomfic, c.jornada, v.nomval, v.nhora;
			';

		$result = $conexion->prepare($sql);
		$result->bindParam(':idfic', $idfic);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}


	public function getGraphic($idfic) {
    $res = [];

    try {
        $sql = "
            SELECT 
                SUM(CASE WHEN DATE(m.fechos) = CURRENT_DATE() THEN 1 ELSE 0 END) AS asistieron,
                SUM(CASE WHEN DATE(i.fecina) = CURRENT_DATE() THEN 1 ELSE 0 END) AS no_asistieron,
                SUM(CASE WHEN u.actusu IN (3, 4, 5, 6) THEN 1 ELSE 0 END) AS con_novedad,
                SUM(
                    CASE 
                        WHEN m.idusu IS NULL 
                         AND i.idusu IS NULL 
                         AND u.actusu NOT IN (3, 4, 5, 6) 
                        THEN 1 ELSE 0 
                    END
                ) AS pendientes,
                COUNT(DISTINCT u.idusu) AS total_personas
            FROM usufic AS f
            INNER JOIN usuario AS u ON u.idusu = f.idusu
            LEFT JOIN minuta AS m ON u.idusu = m.idusu AND DATE(m.fechos) = CURRENT_DATE()
            LEFT JOIN inasistencia AS i ON u.idusu = i.idusu AND DATE(i.fecina) = CURRENT_DATE()
            WHERE f.idfic = :idfic
        ";
		require_once(__DIR__ . '/../models/conexion.php');
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idfic', $idfic);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        ManejoError($e);
    }

    return $res;
}

	
	function getAll($idfic){
		$sql = "SELECT i.idusu, u.ndocusu, u.actusu, u.nomusu, f.idfic, f.nomfic, v.nomval, v.parval, sum(i.horasina) AS horasina FROM inasistencia AS i INNER JOIN usuario AS u ON i.idusu=u.idusu INNER JOIN ficha AS f ON i.idfic=f.idfic INNER JOIN valor AS v ON f.jornada=v.idval WHERE f.idfic='".$idfic."' AND u.actusu=1 GROUP BY i.idusu, u.ndocusu, u.nomusu, f.idfic, f.nomfic, v.nomval, v.parval";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getFicha(){
		$sql = "SELECT f.idfic, f.nomfic, v.nomval FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval WHERE f.idfic>1000 ORDER BY f.idfic, f.nomfic";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->execute();
		$res = $result-> fetchall(PDO::FETCH_ASSOC);
		return $res;
	}


	public function getHist($idusu){
		$sql = "SELECT horasina, fecina, comina FROM inasistencia WHERE idusu = :idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(':idusu', $idusu);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function getLlam($idusu){
		$sql = "SELECT tipllam, obsllam, fecllam FROM llamado WHERE idusu = :idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$result->bindParam(':idusu', $idusu);
		$result->execute();
		$res = $result->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	

	public function inasistencia(){
		$sql = "INSERT INTO inasistencia(horasina) VALUES (:horasina)";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();
		$result = $conexion->prepare($sql);
		$horasina=$this->getHorasina();
		$result->bindParam(":horasina", $horasina);
		$result->execute();
	}

	public function ins(){
		try{
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$sql = "INSERT INTO inasistencia(idusu, fecina, fecreg, horasina, idfic, comina)
					VALUES (:idusu, :fecina, :fecreg, :horasina, :idfic, :comina)";
			$result = $conexion->prepare($sql);

			$idusu = $this->getIdusu();
			$result->bindParam(":idusu", $idusu);

			$fecina = $this->getFecina();
			$result->bindParam(":fecina", $fecina);

			$fecreg = $this->getFecreg();
			$result->bindParam(":fecreg", $fecreg);

			$horasina = $this->getHorasina();
			$result->bindParam(":horasina", $horasina);

			$idfic = $this->getIdfic();
			$result->bindParam(":idfic", $idfic);

			$comina = $this->getComina();
			$result->bindParam(":comina", $comina);

			$result->execute();
		} catch(Exception $e){
			if(strpos($e->getMessage(), '1062'))
				echo '<script>alert("Ficha existente");</script>';
			else
				echo '<script>alert("Error de Inserción");</script>';
		}
	}


    public function del($idusu){
        try{
			$sql = "DELETE FROM inasistencia WHERE idusu=:idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu",$idusu);
            $result->execute();
        }catch(Exception $e){
            echo '<script>alert("No se puede eliminar porque existen relaciones");</script>';
        }
    }

	public function DesCanRet($idusu,$actusu){
		try{
			$sql = "UPDATE usuario SET actusu=:actusu WHERE idusu=:idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
			$result->bindParam(":actusu",$actusu);
            $result->bindParam(":idusu",$idusu);
            $result->execute();
		}catch(Exception $e){
            echo '<script>alert("No se puede eliminar porque existen relaciones");</script>';
        }

	}


public function camactusu($idusu, $actusu){
	try {
		$sql = "UPDATE usuario SET actusu = :actusu WHERE idusu = :idusu";
		$modelo = new conexion();
		$conexion = $modelo->get_conexion();

		$result = $conexion->prepare($sql);
		$result->bindParam(":actusu", $actusu, PDO::PARAM_INT);
		$result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
		$result->execute();

		return $result->rowCount() > 0;
	} catch (Exception $e) {
		echo '<script>alert("Error al cambiar el estado del usuario: ' . $e->getMessage() . '");</script>';
		return false;
	}
}

    public function guardarLlamado($idusu, $tipllam, $obsllam, $fecllam) {
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();

        $sql = "INSERT INTO llamado (idusu, tipllam, obsllam, fecllam) 
                VALUES (:idusu, :tipllam, :obsllam, :fecllam)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->bindParam(':tipllam', $tipllam);
        $stmt->bindParam(':obsllam', $obsllam);
        $stmt->bindParam(':fecllam', $fecllam);
        $stmt->execute();
    }


}
