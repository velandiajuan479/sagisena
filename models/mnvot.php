<?php
class Mnvot{
    private $idfic;
	private $idusu;
	private $actusu;

    public function getIdfic(){
        return $this->idfic;
    }
	public function getIdusu(){
        return $this->idusu;
    }
	public function getActusu(){
        return $this->actusu;
    }
    public function setIdfic($idfic){
        $this->idfic= $idfic;
    }
	public function setIdusu($idusu){
        $this->idusu= $idusu;
    }
	public function setActusu($actusu){
        $this->actusu= $actusu;
    }

		

    public function getAll(){
        $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, f.idfic,
        f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, 
        u.telcan, u.noca FROM usuario AS u 
        INNER JOIN perfil AS p ON u.idper = p.idper 
        LEFT JOIN centro AS c ON u.idcen = c.idcen 
        LEFT JOIN ficha AS f ON u.idusu = f.idfic 
        LEFT JOIN valor AS v ON f.jornada = v.idval 
        LEFT JOIN voto AS vo ON u.idusu = vo.idusu 
        WHERE (u.idper = 4 OR u.idper = 3 OR u.idper = 13)";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

	function editAct(){
            $sql = "UPDATE usuario SET actusu= :actusu WHERE idusu= :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idusu = $this->getIdusu();
            $result->bindParam(":idusu",$idusu);
            $actusu = $this->getActusu();
            $result->bindParam(":actusu",$actusu);
            $result->execute();
       
    }
	function edit(){
        try{
            $sql = "UPDATE usuario SET actusu,:actusu,idusu,:idusu,idfic,:idfic";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $actusu = $this->getActusu();
            $result->bindParam(":actusu",$actusu);
			$idusu = $this->getIdusu();
            $result->bindParam(":idusu",$idusu);
            $idfic= $this->getIdfic();
            $result->bindParam(":idfic",$idfic);
            $actusu = $this->getActusu();
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }
    }
	function save(){
        try {
            //cambio en la tabla-usuario por la tabla-voto para que los votos queden en esta como destino
            $sql = "INSERT INTO voto(idusu, dtvot) VALUES(:idusu, NOW())";
            // inserte dtvot con now() para que quede registrado fecha y hora del voto 
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idusu = $this->getIdusu();
            $result->bindParam(":idusu", $idusu);        
            $result->execute();
            return true;
        } catch(Exception $e) {
            error_log("Error al guardar voto: " . $e->getMessage());
            return false;
        }
    }
	public function getGraphic(){
        $sql = "SELECT 
            SUM(CASE WHEN vo.dtvot IS NOT NULL THEN 1 ELSE 0 END) AS votaron,
            SUM(CASE WHEN vo.dtvot IS NULL THEN 1 ELSE 0 END) AS no_votaron,
            COUNT(*) AS total_personas
            FROM usuario AS u 
            LEFT JOIN voto AS vo ON u.idusu = vo.idusu
            WHERE u.idper = 4 OR u.idper = 3 OR u.idper = 13"; // amplie para que coincida com getall
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchall(PDO::FETCH_ASSOC);
    }
	
    public function getVotU($idusu) {
        $sql = "SELECT idusu FROM voto WHERE idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        // Si el usuario ha votado, devolvemos true, de lo contrario devolvemos false
        return $res ? true : false;
    }
    
}
?>