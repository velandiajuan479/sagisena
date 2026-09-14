<?php
require_once("conexion.php");
class Mdtsop {
    private $iddet;
    private $idsop;
    private $idusu;
    private $detest;
    private $detcom;
    private $detevi;

    public function getIddet() { return $this->iddet; }
    public function getIdsop() { return $this->idsop; }
    public function getIdusu() { return $this->idusu; }
    public function getDetest() { return $this->detest; }
    public function getDetcom() { return $this->detcom; }
    public function getDetevi() { return $this->detevi; }

    public function setIddet($iddet) { $this->iddet = $iddet; }
    public function setIdsop($idsop) { $this->idsop = $idsop; }
    public function setIdusu($idusu) { $this->idusu = $idusu; }
    public function setDetest($detest) { $this->detest = $detest; }
    public function setDetcom($detcom) { $this->detcom = $detcom; }
    public function setDetevi($detevi) { $this->detevi = $detevi; }

    public function getAll(){
        $sql = "SELECT d.iddet, d.idsop, d.idusu, u.nomusu, d.detevi, d.detcom, d.fecseg, s1.falrep, v1.nomval AS nom_falrep, s1.carper,v2.nomval AS nom_carper, s1.nomper, d.detest, v3.nomval AS nom_detest 
        FROM detalle_soporte AS d
        INNER JOIN usuario AS u ON d.idusu = u.idusu INNER JOIN soporte AS s1 ON d.idsop = s1.idsop
        INNER JOIN valor AS v1 ON s1.falrep = v1.idval INNER JOIN valor AS v2 ON s1.carper = v2.idval
        INNER JOIN valor AS v3 ON d.detest = v3.idval WHERE d.idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $result->bindParam(":idsop",$idsop);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne(){
        $sql = "SELECT s.idsop, s.idusu, u.nomusu, s.falrep, v1.nomval 
        AS nom_falrep, s.carper, v2.nomval 
        AS nom_carper, s.fecserini, s.fecserfin, s.nomper, s.desser, s.evisop 
        FROM soporte AS s INNER JOIN usuario AS u ON s.idusu = u.idusu 
        INNER JOIN valor AS v1 ON s.falrep = v1.idval 
        INNER JOIN valor AS v2 ON s.carper = v2.idval WHERE idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $result->bindParam(":idsop",$idsop);
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

    public function save() {
        $sql = "INSERT INTO detalle_soporte (idsop, idusu, detest, detcom, detevi, fecseg) VALUES (:idsop, :idusu, :detest, :detcom, :detevi, NOW())";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idsop", $this->idsop);
        $stmt->bindParam(":idusu", $this->idusu);
        $stmt->bindParam(":detest", $this->detest);
        $stmt->bindParam(":detcom", $this->detcom);
        $stmt->bindParam(":detevi", $this->detevi);
        $stmt->execute();
        return $conexion->lastInsertId();
    }

    public function updateDetevi(){
        $sql = "UPDATE detalle_soporte SET detevi = :detevi WHERE iddet = :iddet";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddet = $this->getIddet();
        $detevi = $this->getDetevi();
        $result->bindParam(":detevi", $detevi);
        $result->bindParam(":iddet", $iddet);
        $result->execute();
    }

    public function getEstado(){
        $sql = "SELECT idval, nomval, act FROM valor WHERE iddom=9";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getUltimoEstado(){
        $sql = "SELECT detest FROM detalle_soporte WHERE idsop = :idsop ORDER BY iddet DESC LIMIT 1";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idsop", $this->idsop);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function finalizarSoporte() {
        $sql = "UPDATE soporte SET fecserfin = NOW() WHERE idsop = :idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idsop", $this->idsop);
        $stmt->execute();
    }

    public function del(){
        $sql = "DELETE FROM detalle_soporte WHERE iddet=:iddet";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddet = $this->getIddet();
        $result->bindParam(":iddet",$iddet);
        $result->execute();
    }
}
?>