<?php
require_once("conexion.php");
class Mcasate{

    private $idsop;
    private $idusu;
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

    public function getAll(){
        $sql = "SELECT s.idsop, s.idusu, s.idusu2, u.nomusu, s.falrep, v1.nomval AS nom_falrep, 
            s.carper, v2.nomval AS nom_carper, s.fecserini, s.fecserfin, s.nomper, 
            s.desser, s.evisop, ds.detest AS ultimo_estado_id, v3.nomval AS nom_ultimo_estado 
        FROM soporte AS s
        LEFT JOIN usuario AS u ON s.idusu = u.idusu
        LEFT JOIN valor AS v1 ON s.falrep = v1.idval
        LEFT JOIN valor AS v2 ON s.carper = v2.idval
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
        WHERE s.idusu IS NULL";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function asignarSoporteAUsuario() {
        $sql = "UPDATE soporte SET idusu = :idusu WHERE idsop = :idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $this->idusu);
        $stmt->bindParam(':idsop', $this->idsop);
        $stmt->execute();
    }

    public function del(){
        $sql = "DELETE FROM soporte WHERE idsop=:idsop";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idsop = $this->getIdsop();
        $result->bindParam(":idsop",$idsop);
        $result->execute();
    }
}
?>