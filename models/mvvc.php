<?php
class Mvvc{
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

    // mtodo para obtener la ficha del usuario actual
    public function getFichaUsuario($idusu) {
        $sql = "SELECT uf.idfic FROM usufic uf WHERE uf.idusu = :idusu AND uf.actfic = '1' LIMIT 1";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['idfic'] : null;
    }

    public function getVocerosMismaFicha($idfic, $idusu_actual = null) {
        if (!$idfic) {
            return []; // Retorna array vacío si no hay ficha
        }

        $sql = "SELECT DISTINCT u.idusu, u.nomusu, u.fotcan, u.noca, f.nomfic, uf.idfic
                FROM usuario u
                INNER JOIN usupef up ON u.idusu = up.idusu
                LEFT JOIN usufic uf ON u.idusu = uf.idusu
                LEFT JOIN ficha f ON uf.idfic = f.idfic
                WHERE up.idper = 13
                AND u.actusu = '1'
                AND uf.idfic = :idfic
                AND u.idusu != :idusu_actual
                ORDER BY u.noca";
        
        $conexion = (new conexion())->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idfic", $idfic);
        $stmt->bindParam(":idusu_actual", $idusu_actual);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getVotoUsuario() {
        $sql = "SELECT v.*, u.nomusu, u.fotcan, f.nomfic 
                FROM voto v 
                LEFT JOIN usuario u ON v.canusu = u.idusu 
                LEFT JOIN usufic uf ON u.idusu = uf.idusu 
                LEFT JOIN ficha f ON uf.idfic = f.idfic 
                WHERE v.idusu = :idusu 
                ORDER BY v.dtvot DESC LIMIT 1";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
    // ins

    public function getOne() {
        $sql = "SELECT COUNT(*) AS co FROM voto 
                WHERE idusu = :idusu AND tipo_voto = 'vocero'";
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
                error_log("Usuario ya votó por vocero: ".$this->idusu);
                return false;
            }
            
            $sql = "INSERT INTO voto (idusu, canusu, dtvot, tipo_voto) 
                    VALUES (:idusu, :canusu, :dtvot, 'vocero')";
            
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $this->idusu, PDO::PARAM_INT);
            $result->bindParam(":canusu", $this->canusu, PDO::PARAM_INT);
            $result->bindParam(":dtvot", $this->dtvot);
            
            return $result->execute();
            
        } catch (PDOException $e) {
            error_log("Error en voto vocero: ".$e->getMessage());
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