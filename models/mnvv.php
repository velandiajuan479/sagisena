<?php
class Mnvv{
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
        $this->idfic = $idfic;
    }
    
    public function setIdusu($idusu){
        $this->idusu = $idusu;
    }
    
    public function setActusu($actusu){
        $this->actusu = $actusu;
    }

    // Metodo para obtener todas las fichas disponibles
    public function getFichas() {
        $sql = "SELECT DISTINCT f.idfic, f.nomfic, v.nomval 
                FROM ficha f 
                JOIN valor v ON f.jornada = v.idval 
                ORDER BY f.idfic";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchall(PDO::FETCH_ASSOC);
    }

    // Metodo para obtener aprendices por ficha especifica 
    public function getAprendicesPorFicha($idfic) {
    $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, 
                   uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, 
                   u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca,
                   CASE WHEN vo.idusu IS NOT NULL THEN 1 ELSE 0 END AS votado,
                   uf.actfic as estado_en_ficha
            FROM usufic AS uf
            INNER JOIN usuario AS u ON uf.idusu = u.idusu
            INNER JOIN perfil AS p ON u.idper = p.idper
            LEFT JOIN ficha AS f ON uf.idfic = f.idfic
            LEFT JOIN valor AS v ON f.jornada = v.idval
            LEFT JOIN centro AS c ON u.idcen = c.idcen
            LEFT JOIN voto AS vo ON u.idusu = vo.idusu
            WHERE uf.idfic = :idfic
            AND uf.actfic = 1
            AND (u.idper = 3 OR u.idper = 4 OR u.idper = 13 OR u.idper = 8)";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        return $result->fetchall(PDO::FETCH_ASSOC);
    }

    // Metodo para obtener estadisticas por ficha
    public function getEstadisticasPorFicha($idfic) {
        $sql = "SELECT 
                COUNT(*) AS total_personas,
                SUM(CASE WHEN vo.idusu IS NOT NULL THEN 1 ELSE 0 END) AS votaron,
                SUM(CASE WHEN vo.idusu IS NULL THEN 1 ELSE 0 END) AS no_votaron
                FROM usufic AS uf
                INNER JOIN usuario AS u ON uf.idusu = u.idusu
                LEFT JOIN voto AS vo ON u.idusu = vo.idusu
                WHERE uf.idfic = :idfic
                AND uf.actfic = 1
                AND (u.idper = 3 OR u.idper = 4 OR u.idper = 13 OR u.idper = 8)";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    // Metodo para obtener todos los aprendices 
    public function getAll($fidfic = null) {
        $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, 
                       uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, 
                       u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca,
                       CASE WHEN vo.idusu IS NOT NULL THEN 1 ELSE 0 END AS votado
                FROM usuario AS u 
                INNER JOIN perfil AS p ON u.idper = p.idper 
                LEFT JOIN usufic AS uf ON u.idusu = uf.idusu 
                LEFT JOIN ficha AS f ON uf.idfic = f.idfic 
                LEFT JOIN valor AS v ON f.jornada = v.idval 
                LEFT JOIN centro AS c ON u.idcen = c.idcen 
                LEFT JOIN voto AS vo ON u.idusu = vo.idusu 
                WHERE (u.idper = 3 OR u.idper = 4)";
        
        if($fidfic){
            $sql .= " AND uf.idfic = :fidfic";
        }
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        
        if($fidfic){
            $result->bindParam(":fidfic", $fidfic);
        }
        
        $result->execute();
        return $result->fetchall(PDO::FETCH_ASSOC);
    }

    public function editAct(){
        $sql = "UPDATE usuario SET actusu = :actusu WHERE idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $actusu = $this->getActusu();
        $result->bindParam(":actusu", $actusu);
        $result->execute();
    }
    
    public function edit(){
        try {
            $sql = "UPDATE usuario SET actusu = :actusu, idusu = :idusu, idfic = :idfic 
                    WHERE idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":actusu", $this->actusu);
            $result->bindParam(":idusu", $this->idusu);
            $result->bindParam(":idfic", $this->idfic);
            $result->execute();
        } catch(Exception $e) {
            ManejoError($e);
        }
    }
    
    public function save(){
        try {
            $sql = "INSERT INTO voto(idusu, dtvot) VALUES(:idusu, NOW())";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $this->idusu);
            $result->execute();
            return true;
        } catch(Exception $e) {
            error_log("Error al guardar voto: " . $e->getMessage());
            return false;
        }
    }
    
    public function getGraphic($fidfic = null){
        $sql = "SELECT 
                SUM(CASE WHEN vo.dtvot IS NOT NULL THEN 1 ELSE 0 END) AS votaron,
                SUM(CASE WHEN vo.dtvot IS NULL THEN 1 ELSE 0 END) AS no_votaron,
                COUNT(*) AS total_personas
                FROM usuario AS u 
                LEFT JOIN voto AS vo ON u.idusu = vo.idusu
                LEFT JOIN usufic AS uf ON u.idusu = uf.idusu
                WHERE (u.idper = 3 OR u.idper = 4)";
        
        if($fidfic){
            $sql .= " AND uf.idfic = :fidfic";
        }
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        
        if($fidfic){
            $result->bindParam(":fidfic", $fidfic);
        }
        
        $result->execute();
        return $result->fetchall(PDO::FETCH_ASSOC);
    }
    
    public function getVotU($idusu) {
        $sql = "SELECT idusu FROM voto WHERE idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        return (bool) $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>