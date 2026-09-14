<?php 
class Mrppses{
    private $idact;
    private $idses; 
    private $nomact; 
    private $desact; 
    private $duract;
    private $tipact;
    private $fecses;
    private $idfic;

    public function getIdact(){
        return $this->idact;
    }
    public function getIdses(){
        return $this->idses;
    }
    public function getNomact(){
        return $this->nomact;
    }
    public function getDesact(){
        return $this->desact;
    }
    public function getDuract(){
        return $this->duract;
    }
    public function getTipact(){
        return $this->tipact;
    }
    public function getFecses(){
        return $this->fecses;
    }
    public function getIdfic(){
        return $this->idfic;
    }
    public function setIdact($idact){
        $this->idact = $idact;
    }
    public function setIdses($idses){
        $this->idses = $idses;
    }
    public function setNomact($nomact){
        $this->nomact = $nomact;
    }
    public function setDesact($desact){
        $this->desact = $desact;
    }
    public function setDuract($duract){
        $this->duract = $duract;
    }
    public function setTipact($tipact){
        $this->tipact = $tipact;
    }
    public function setFecses($fecses){
        $this->fecses = $fecses;
    }
    public function setIdfic($idfic){
        $this->idfic = $idfic;
    }

    public function getAll(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT a.idact, a.idses, a.nomact, a.desact, a.duract, a.tipact, s.fecses, ag.idfic, f.codpro, f.nomfic, ag.idres, r.nomres, r.ndeses, c.idcom, c.descom FROM actividad AS a INNER JOIN sesion AS s ON a.idses = s.idses INNER JOIN agenda AS ag ON s.idage = ag.idage INNER JOIN ficha AS f ON ag.idfic = f.idfic INNER JOIN resultado AS r ON ag.idres = r.idres INNER JOIN competencia AS c ON r.idcom = c.idcom;";
        $result = $conexion->prepare($sql);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getCen(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT cen.nomcen, cen.dircen FROM centro AS cen;";
        $result = $conexion->prepare($sql);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getDatSes(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT ac.idact, ac.nomact, ac.desact, ac.tipact, ac.duract, s.idses, s.fecses, ag.idage, ag.idfic, ag.idres, ag.fchinc, ag.fchfnl, f.idfic, f.nomfic, r.idres, r.nomres, r.idcom, c.idcom,c.descom FROM actividad AS ac INNER JOIN sesion AS s ON ac.idses = s.idses INNER JOIN agenda AS ag ON s.idage = ag.idage INNER JOIN ficha AS f ON ag.idfic = f.idfic INNER JOIN resultado AS r ON ag.idres = r.idres INNER JOIN competencia AS c ON r.idcom = c.idcom;";
        $result = $conexion->prepare($sql);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getInstructor(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo -> get_conexion();
        $sql = "SELECT u.idusu, u.nomusu, ag.idfic, ag.idusu FROM usuario AS u INNER JOIN agenda AS ag ON ag.idusu = u.idusu;";
        $result = $conexion->prepare($sql);
        $result -> execute();
        $res = $result -> fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getOne(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT a.idact, a.idses, a.nomact, a.desact, a.duract, a.tipact, s.fecses FROM actividad AS a INNER JOIN sesion AS s ON a.idses = s.idses WHERE a.idact = :idact";
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}