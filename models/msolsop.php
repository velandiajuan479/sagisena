<?php
require_once("conexion.php");
class Msolsop{

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

    public function getOne(){
        $sql = "SELECT s.idsop, s.idusu, s.idusu2, u.nomusu, s.falrep, v1.nomval AS nom_falrep, s.carper, v2.nomval AS nom_carper, s.fecserini, s.fecserfin, s.nomper, s.desser, s.evisop, ds.detest AS ultimo_estado_id, v3.nomval AS nom_ultimo_estado FROM soporte AS s
        INNER JOIN usuario AS u ON s.idusu = u.idusu
        INNER JOIN usuario AS u2 ON s.idusu2 = u2.idusu
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
        LEFT JOIN valor AS v3 ON ds.detest = v3.idval WHERE idsop=:idsop;";
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
        $sql = "SELECT s.idsop, s.idusu2, s.falrep, v1.nomval AS nom_falrep, s.carper, v2.nomval AS nom_carper, s.fecserini, s.fecserfin, s.nomper, s.desser, s.evisop, ds.detest AS ultimo_estado_id, v3.nomval AS nom_ultimo_estado FROM soporte AS s
            INNER JOIN usuario AS u ON s.idusu2 = u.idusu
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
            LEFT JOIN valor AS v3 ON ds.detest = v3.idval
        WHERE s.idusu2 = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
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

    public function getNom(){
        $sql = "SELECT idusu, nomusu FROM usuario WHERE idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function save(){
        $sql = "INSERT INTO soporte (idusu2, falrep, carper, fecserini, nomper, desser, evisop) VALUES (:idusu, :falrep, :carper, NOW(), :nomper, :desser, :evisop)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu2 = $this->getIdusu2();
        $result->bindParam(":idusu",$idusu2);
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
        $sql = "UPDATE soporte SET idusu2=:idusu2, falrep=:falrep, carper=:carper, fecserini=:fecserini, nomper=:nomper, desser=:desser, evisop=:evisop WHERE idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $result->bindParam(":idsop",$idsop);
        $idusu2 = $this->getIdusu2();
        $result->bindParam(":idusu2",$idusu2);
        $falrep = $this->getFalrep();
        $result->bindParam(":falrep",$falrep);
        $carper= $this->getCarper();
        $result->bindParam(":carper",$carper);
        $fecser= $this->getFecser();
        $result->bindParam(":fecserini",$fecser);
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