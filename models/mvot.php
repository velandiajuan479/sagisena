<?php
class Mvot{
    private $idusu;
    private $canusu;
    private $dtvot;

    // Metodos Set 
    public function getIdusu(){
        return $this->idusu;
    }
    public function getCanusu(){
        return $this->canusu;
    }
    public function getDtvot(){
        return $this->dtvot;
    }

    // Metodo Get
    public function setIdusu($idusu){
        $this->idusu=$idusu;
    }
    public function setCanusu($canusu){
        $this->canusu=$canusu;
    }
    public function setDtvot($dtvot){
        $this->dtvot=$dtvot;
    }
    // selAll
    public function getAll($idval=1){
        $sql="SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, u.noca, p.nomper, uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan FROM usuario AS u INNER JOIN usupef AS up ON u.idusu=up.idusu LEFT JOIN perfil AS p ON p.idper=up.idper INNER JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON v.idval=u.noca WHERE up.idper=3 AND f.jornada='$idval' ORDER BY u.noca, u.nomusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res=$result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    } 

    public function getOne() {
        $sql = "SELECT COUNT(*) AS co FROM voto 
                WHERE idusu = :idusu AND tipo_voto = 'representante'";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res['co'] > 0;
    }

    public function save() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            
            if ($this->getOne()) {
                error_log("Usuario ya votó por representante: ".$this->idusu);
                return false;
            }
            
            $sql = "INSERT INTO voto (idusu, canusu, dtvot, tipo_voto) 
                    VALUES (:idusu, :canusu, :dtvot, 'representante')";
            
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $this->idusu, PDO::PARAM_INT);
            $result->bindParam(":canusu", $this->canusu, PDO::PARAM_INT);
            $result->bindParam(":dtvot", $this->dtvot);
            
            return $result->execute();
            
        } catch (PDOException $e) {
            error_log("Error en voto representante: ".$e->getMessage());
            return false;
        }
    }

    public function getOneJor(){
        $sql = "SELECT f.jornada FROM usufic AS u INNER JOIN ficha AS f ON u.idfic=f.idfic WHERE u.actfic ='1' AND u.idusu=:idusu;";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu= $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        $res=$result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }


}
?>