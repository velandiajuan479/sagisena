<?php
require_once("conexion.php");
class Msersop{

    private $idsop;
    private $idusu;
    private $idusu2;
    private $falrep;
    private $carper;
    private $fecser;
    private $nomper;
    private $desser;
    private $evisop;

    public function getIdsop(){
        return $this->idsop;
    }
    public function getIdusu(){
        return $this->idusu;
    }
    public function getIdusu2(){
        return $this->idusu2;
    }
    public function getFalrep(){
        return $this->falrep;
    }
    public function getCarper(){
        return $this->carper;
    }
    public function getFecser(){
        return $this->fecser;
    }
    public function getNomper(){
        return $this->nomper;
    }
    public function getDesser(){
        return $this->desser;
    }
    public function getEvisop(){
        return $this->evisop;
    }

    public function setIdsop($idsop){
        $this->idsop = $idsop;
    }
    public function setIdusu($idusu){
        $this->idusu = $idusu;
    }
    public function setIdusu2($idusu2){
        $this->idusu2 = $idusu2;
    }
    public function setFalrep($falrep){
        $this->falrep = $falrep;
    }
    public function setCarper($carper){
        $this->carper = $carper;
    }
    public function setFecser($fecser){
        $this->fecser = $fecser;
    }
    public function setNomper($nomper){
        $this->nomper = $nomper;
    }
    public function setDesser($desser){
        $this->desser = $desser;
    }
    public function setEvisop($evisop){
        $this->evisop = $evisop;
    }

    public function getAll($idper = null, $idusu = null){
        $sql = "SELECT s.idsop, s.idusu, s.idusu2, u.nomusu, s.falrep, v1.nomval AS nom_falrep, s.carper, v2.nomval AS nom_carper, s.fecserini, s.fecserfin, s.nomper, s.desser, s.evisop, ds.detest AS ultimo_estado_id, v3.nomval AS nom_ultimo_estado FROM soporte AS s
        LEFT JOIN usuario AS u ON s.idusu = u.idusu
        LEFT JOIN valor AS v1 ON s.falrep = v1.idval
        LEFT JOIN valor AS v2 ON s.carper = v2.idval
        LEFT JOIN (
            SELECT d1.idsop, d1.detest FROM detalle_soporte d1
                INNER JOIN (
                    SELECT idsop, MAX(fecseg) AS max_fecha FROM detalle_soporte GROUP BY idsop
                ) d2 ON d1.idsop = d2.idsop AND d1.fecseg = d2.max_fecha
            ) ds ON s.idsop = ds.idsop
        LEFT JOIN valor AS v3 ON ds.detest = v3.idval ";
        if ($idper == 27) {
            $sql .= " WHERE s.idusu = :idusu ";
        }
        $sql .= ";";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($idper == 27) {
            $result->bindParam(":idusu", $idusu);
        }
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne(){
        $sql = "SELECT s.idsop, s.idusu, u.nomusu, s.falrep, v1.nomval AS nom_falrep, s.carper, v2.nomval AS nom_carper, s.fecserini, s.fecserfin, s.nomper, s.desser, s.evisop, ds.detest AS ultimo_estado_id, v3.nomval AS nom_ultimo_estado FROM soporte AS s
        INNER JOIN usuario AS u ON s.idusu = u.idusu
        INNER JOIN valor AS v1 ON s.falrep = v1.idval
        INNER JOIN valor AS v2 ON s.carper = v2.idval
            LEFT JOIN (
                SELECT d1.idsop, d1.detest 
                FROM detalle_soporte d1
                INNER JOIN (
                    SELECT idsop, MAX(fecseg) AS max_fecha 
                    FROM detalle_soporte 
                    GROUP BY idsop
                ) d2 ON d1.idsop = d2.idsop AND d1.fecseg = d2.max_fecha
            ) ds ON s.idsop = ds.idsop
        LEFT JOIN valor AS v3 ON ds.detest = v3.idval WHERE idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $result->bindParam(":idsop",$idsop);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getReporteEstPer(){
        $sql = "SELECT s.idsop, s.idusu, u.nomusu, s.falrep, v1.nomval AS nom_falrep, s.carper, v2.nomval AS nom_carper, s.fecserini, s.fecserfin, s.nomper, s.desser, s.evisop, ds.detest AS ultimo_estado_id, v3.nomval AS nom_ultimo_estado FROM soporte AS s
        INNER JOIN usuario AS u ON s.idusu = u.idusu
        INNER JOIN valor AS v1 ON s.falrep = v1.idval
        INNER JOIN valor AS v2 ON s.carper = v2.idval
            LEFT JOIN (
                SELECT d1.idsop, d1.detest 
                FROM detalle_soporte d1
                INNER JOIN (
                    SELECT idsop, MAX(fecseg) AS max_fecha 
                    FROM detalle_soporte 
                    GROUP BY idsop
                ) d2 ON d1.idsop = d2.idsop AND d1.fecseg = d2.max_fecha
            ) ds ON s.idsop = ds.idsop
        LEFT JOIN valor AS v3 ON ds.detest = v3.idval WHERE (idper=27 OR idper=38) AND s.idusu=:idusu;";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getSoporteFinalizadoPorFalla($filter = null, $idper = null, $idusu = null) {
        $whereDate = "";
        if ($filter === 'day') {
            $whereDate = " AND DATE(s.fecserini) = CURDATE() ";
        } elseif ($filter === 'month') {
            $whereDate = " AND MONTH(s.fecserini) = MONTH(CURDATE()) AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        } elseif ($filter === 'year') {
            $whereDate = " AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        }
        $whereUser = "";
        $joinUser = "";
        if ($idper !== null) {
            $joinUser = " INNER JOIN usuario u ON s.idusu = u.idusu ";
            $whereUser .= " AND u.idper = :idper ";
        }
        if ($idusu !== null) {
            if ($joinUser === "") {
                $joinUser = " INNER JOIN usuario u ON s.idusu = u.idusu ";
            }
            $whereUser .= " AND s.idusu = :idusu ";
        }
        $sql = "SELECT v.nomval AS falla, COUNT(*) AS total FROM soporte s
            INNER JOIN valor v ON s.falrep = v.idval
            INNER JOIN (SELECT d1.idsop, d1.detest FROM detalle_soporte d1
                INNER JOIN (SELECT idsop, MAX(fecseg) AS max_fecha FROM detalle_soporte GROUP BY idsop) d2 ON d1.idsop = d2.idsop AND d1.fecseg = d2.max_fecha
            ) ds ON s.idsop = ds.idsop
            $joinUser
            WHERE ds.detest = 1074 " . $whereDate . $whereUser . " GROUP BY s.falrep";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($idper !== null) {
            $result->bindParam(":idper", $idper);
        }
        if ($idusu !== null) {
            $result->bindParam(":idusu", $idusu);
        }
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoporteFinalizadoPorFallaPer($filter = null) {
        $whereDate = "";
        if ($filter === 'day') {
            $whereDate = " AND DATE(s.fecserini) = CURDATE() ";
        } elseif ($filter === 'month') {
            $whereDate = " AND MONTH(s.fecserini) = MONTH(CURDATE()) AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        } elseif ($filter === 'year') {
            $whereDate = " AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        }
        $sql = "SELECT v.nomval AS falla, COUNT(*) AS total FROM soporte s
            INNER JOIN valor v ON s.falrep = v.idval
                INNER JOIN (SELECT d1.idsop, d1.detest FROM detalle_soporte d1
                    INNER JOIN (SELECT idsop, MAX(fecseg) AS max_fecha FROM detalle_soporte GROUP BY idsop) d2 ON d1.idsop = d2.idsop AND d1.fecseg = d2.max_fecha
                ) ds ON s.idsop = ds.idsop
            WHERE ds.detest = 1074 AND s.idusu =:idusu " . $whereDate . "
            GROUP BY s.falrep";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoportePorCargo($filter = null, $idper = null, $idusu = null) {
        $whereDate = "";
        if ($filter === 'day') {
            $whereDate = " AND DATE(s.fecserini) = CURDATE() ";
        } elseif ($filter === 'month') {
            $whereDate = " AND MONTH(s.fecserini) = MONTH(CURDATE()) AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        } elseif ($filter === 'year') {
            $whereDate = " AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        }
        $whereUser = "";
        $joinUser = "";
        if ($idper !== null) {
            $joinUser = " INNER JOIN usuario u ON s.idusu = u.idusu ";
            $whereUser .= " AND u.idper = :idper ";
        }
        if ($idusu !== null) {
            if ($joinUser === "") {
                $joinUser = " INNER JOIN usuario u ON s.idusu = u.idusu ";
            }
            $whereUser .= " AND s.idusu = :idusu ";
        }
        $sql = "SELECT v.nomval AS cargo, COUNT(*) AS total FROM soporte s 
                INNER JOIN valor v ON s.carper = v.idval
                $joinUser
                WHERE 1=1 " . $whereDate . $whereUser . " GROUP BY s.carper";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($idper !== null) {
            $result->bindParam(":idper", $idper);
        }
        if ($idusu !== null) {
            $result->bindParam(":idusu", $idusu);
        }
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSoportePorCargoPer($filter = null) {
        $whereDate = "";
        if ($filter === 'day') {
            $whereDate = " AND DATE(s.fecserini) = CURDATE() ";
        } elseif ($filter === 'month') {
            $whereDate = " AND MONTH(s.fecserini) = MONTH(CURDATE()) AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        } elseif ($filter === 'year') {
            $whereDate = " AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        }
        $sql = "SELECT v.nomval AS cargo, COUNT(*) AS total FROM soporte s INNER JOIN valor v ON s.carper = v.idval WHERE s.idusu =:idusu " . $whereDate . " GROUP BY s.carper";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSoportePorUsuario($filter = null, $idper = null, $idusu = null) {
        $whereDate = "";
        if ($filter === 'day') {
            $whereDate = " AND DATE(s.fecserini) = CURDATE() ";
        } elseif ($filter === 'month') {
            $whereDate = " AND MONTH(s.fecserini) = MONTH(CURDATE()) AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        } elseif ($filter === 'year') {
            $whereDate = " AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        }
        $whereUser = "";
        $joinUser = "";
        if ($idper !== null) {
            $joinUser = " INNER JOIN usuario u ON s.idusu = u.idusu ";
            $whereUser .= " AND u.idper = :idper ";
        }
        if ($idusu !== null) {
            if ($joinUser === "") {
                $joinUser = " INNER JOIN usuario u ON s.idusu = u.idusu ";
            }
            $whereUser .= " AND s.idusu = :idusu ";
        }
        $sql = "SELECT u.nomusu AS usuario, COUNT(*) AS total FROM soporte s 
                INNER JOIN usuario u ON s.idusu = u.idusu
                $joinUser
                WHERE 1=1 " . $whereDate . $whereUser . " GROUP BY s.idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($idper !== null) {
            $result->bindParam(":idper", $idper);
        }
        if ($idusu !== null) {
            $result->bindParam(":idusu", $idusu);
        }
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSoportePorUsuarioPer($filter = null) {
        $whereDate = "";
        if ($filter === 'day') {
            $whereDate = " AND DATE(s.fecserini) = CURDATE() ";
        } elseif ($filter === 'month') {
            $whereDate = " AND MONTH(s.fecserini) = MONTH(CURDATE()) AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        } elseif ($filter === 'year') {
            $whereDate = " AND YEAR(s.fecserini) = YEAR(CURDATE()) ";
        }
        $sql = "SELECT u.nomusu AS usuario, COUNT(*) AS total FROM soporte s INNER JOIN usuario u ON s.idusu = u.idusu WHERE s.idusu =:idusu " . $whereDate . " GROUP BY s.idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCargoPer(){
        $sql = "SELECT idval, nomval, act FROM valor WHERE iddom=7";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getFallaRep(){
        $sql = "SELECT idval, nomval, act FROM valor WHERE iddom=6";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getSelSop($idcen = null){
        $sql = "SELECT idusu, nomusu FROM usuario WHERE (idper=27 OR idper=38)";
        if ($idcen !== null) {
            $sql .= " AND idcen = :idcen";
        }
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($idcen !== null) {
            $result->bindParam(":idcen", $idcen);
        }
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function buscarUsuarios($term) {
        try {
            $sql = "SELECT idusu, nomusu FROM usuario WHERE nomusu LIKE :term AND actusu = 1 ORDER BY nomusu ASC LIMIT 10";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $term = '%'.trim($term).'%';
            $result->bindValue(":term", $term, PDO::PARAM_STR);
            if(!$result->execute()) {
                throw new Exception("Error en la ejecución de la consulta");
            }
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en buscarUsuarios: " . $e->getMessage());
            return array();
        }
    }

    public function save(){
        $sql = "INSERT INTO soporte (idusu, idusu2, falrep, carper, fecserini, nomper, desser, evisop) 
            VALUES (:idusu, :idusu2, :falrep, :carper, NOW(), :nomper, :desser, :evisop)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $idusu2 = $this->getIdusu2();
        $result->bindParam(":idusu2",$idusu2);
        $falrep = $this->getFalrep();
        $result->bindParam(":falrep",$falrep);
        $carper= $this->getCarper();
        $result->bindParam(":carper",$carper);
        $nomper = $this->getNomper();
        $result->bindParam(":nomper",$nomper);
        $desser= $this->getDesser();
        $result->bindParam(":desser",$desser);
        $evisop= $this->getEvisop();
        $result->bindParam(":evisop",$evisop);
        $result->execute();
        return $conexion->lastInsertId();
    }

    public function updateEvisop(){
        $sql = "UPDATE soporte SET evisop = :evisop WHERE idsop = :idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $evisop = $this->getEvisop();
        $result->bindParam(":evisop", $evisop);
        $result->bindParam(":idsop", $idsop);
        $result->execute();
    }

    public function edit(){
        $sql = "UPDATE soporte SET idusu=:idusu, falrep=:falrep, carper=:carper, fecserini=:fecserini, nomper=:nomper, desser=:desser, evisop=:evisop WHERE idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $result->bindParam(":idsop",$idsop);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $falrep = $this->getFalrep();
        $result->bindParam(":falrep",$falrep);
        $carper= $this->getCarper();
        $result->bindParam(":carper",$carper);
        $fecser= $this->getFecser();
        $result->bindParam(":fecser",$fecser);
        $nomper = $this->getNomper();
        $result->bindParam(":nomper",$nomper);
        $desser= $this->getDesser();
        $result->bindParam(":desser",$desser);
        $evisop= $this->getEvisop();
        $result->bindParam(":evisop",$evisop);
        $result->execute();
    }

    public function del(){
        // eliminar los registros relacionados con detalle_soporte
        $sql1 = "DELETE FROM detalle_soporte WHERE idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result1 = $conexion->prepare($sql1);
        $idsop = $this->getIdsop();
        $result1->bindParam(":idsop",$idsop);
        $result1->execute();
        
        // eliminar el registro principal soporte
        $sql2 = "DELETE FROM soporte WHERE idsop=:idsop";
        $result2 = $conexion->prepare($sql2);
        $result2->bindParam(":idsop",$idsop);
        $result2->execute();
    }
}
?>