<?php
class Mrpage {

    private $idage;
    private $idfic;
    private $idusu;
    private $idres;
    private $fchinc;
    private $fchfnl;
    private $idcen;
    private $dircen;
    private $iddia;

    // METODOS GET
    public function getIdage() {
        return $this->idage;
    }

    public function getIdfic() {
        return $this->idfic;
    }

    public function getIdusu() {
        return $this->idusu;
    }

    public function getIdres() {
        return $this->idres;
    }

    public function getFchinc() {
        return $this->fchinc;
    }

    public function getFchfnl() {
        return $this->fchfnl;
    }

    function getIdcen() {
        return $this->idcen;
    }

    function getDircen() {
        return $this->dircen;
    }
    function getIddia() {
        return $this->iddia;
    }

    // METODOS SET
    public function setIdage($idage) {
        $this->idage = $idage;
    }

    public function setIdfic($idfic) {
        $this->idfic = $idfic;
    }

    public function setIdusu($idusu) {
        $this->idusu = $idusu;
    }

    public function setIdres($idres) {
        $this->idres = $idres;
    }

    public function setFchinc($fchinc) {
        $this->fchinc = $fchinc;
    }

    public function setFchfnl($fchfnl) {
        $this->fchfnl = $fchfnl;
    }

    function setIdcen($idcen) {
        $this->idcen = $idcen;
    }

    function setDircen($dircen) {
        $this->dircen = $dircen;
    }
    function setIddia($iddia){
        $this->iddia =$iddia;
    }

    // METODOS GET PARA TRAER DATOS
    public function getAll(){
        $res = NULL;
        $sql = "SELECT idhor, idfic, idusu, iddia FROM horario WHERE idfic=:idfic AND iddia=:iddia";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idfic = $this->getIdfic();
        $result->bindParam(":idfic", $idfic);
        $iddia = $this->getIddia();
        $result->bindParam(":iddia", $iddia);
        $result->execute();
        $res= $result->fetchall(PDO::FETCH_ASSOC);
        return $res;        
    }
    public function getCent() {
        $res = NULL;
        $sql = "SELECT c.idcen, c.nomcen, c.dircen FROM centro AS c";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getAge($idfic = null) {
        $res = NULL;
        $sql = "SELECT ag.idage, ag.idfic, ag.idusu, ag.idres, ag.fchinc, ag.fchfnl, u.nomusu
                FROM agenda AS ag
                INNER JOIN usuario AS u ON ag.idusu = u.idusu";
        if ($idfic) {
            $sql .= " WHERE ag.idfic = :idfic";
        }
        $sql .= " ORDER BY ag.idage, ag.idfic, ag.idusu, ag.idres, ag.fchinc, ag.fchfnl ASC";
    
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($idfic) {
            $result->bindParam(':idfic', $idfic);
        }
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res; 
    }
    
    
public function getFic($fic = null) {
    $res = NULL;
    $sql = "SELECT f.idfic, f.nomfic, v.nomval, f.codpro, a.nomare, f.jornada, f.mun, a.idusu AS idinstructor
            FROM ficha AS f
            INNER JOIN valor AS v ON f.jornada = v.idval
            LEFT JOIN programa AS p ON f.codpro = p.codpro
            LEFT JOIN area AS a ON p.idare = a.idare
            WHERE v.iddom = '1'";
    if ($fic) {
        $sql .= " AND f.idfic = :fic";
    }
    $sql .= " ORDER BY f.idfic, f.nomfic";
    
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    if ($fic) {
        $result->bindParam(':fic', $fic);
    }
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}


    public function getRes($res = null) {
        $sql = "SELECT r.idres, r.nomres, r.idcom, co.descom, co.horcom
                FROM resultado AS r 
                INNER JOIN competencia AS co ON r.idcom = co.idcom ";
        if ($res) {
            $sql .= " AND r.idres = :res";
        }
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        if ($res) {
            $result->bindParam(':res', $res);
        }
        $sql .= " ORDER BY r.nomres, co.descom, co,horcom ASC";
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    } 
    //funcion para numero de aprendices 
    public function getNoApr($idfic) {
        $sql = "SELECT COUNT(idusu) AS can FROM usufic WHERE idfic = :idfic";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return [$res]; // Retorna un arreglo que contiene el resultado
    }
    public function getDia(){
    $sql = "SELECT idval, nomval FROM valor WHERE act=1 AND iddom=14 ORDER BY parval";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->execute();
    $res =  $result-> fetchall(PDO::FETCH_ASSOC);
    return $res;
    }
    
}

?>