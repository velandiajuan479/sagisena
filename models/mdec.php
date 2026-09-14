<?php 
class Mdec{
    private $iddec;
    private $idusu;
    private $idfic;
    private $fecregdec;
    private $obsdec;

function getIddec(){
    return $this->iddec;
}
function getIdusu(){
    return $this->idusu;
}
function getIdfic(){
    return $this->idfic;
}
function getFecregdec(){
    return $this->fecregdec;
}
function getObsdec(){
    return $this->obsdec;
}

function setIddec($iddec){
    $this-> iddec = $iddec;
}
function setIdusu($idusu){
    $this-> idusu = $idusu;
}
function setIdfic($idfic){
    $this-> idfic = $idfic;
}
function setFecregdec($fecregdec){
    $this-> fecregdec = $fecregdec;
}
function setObsdec($obsdec){
    $this-> obsdec = $obsdec;
}

public function getAll(){
    try{
    $sql= "SELECT iddec, idusu,  idfic,  fecregdec, obsdec FROM desercion VALUE idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idsusu);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }catch(Exception $e){
        ManejoError($e);
    }
}
public function getOne(){
    $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, uf.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN usufic AS uf ON u.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE u.idusu=:idusu";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idusu=$this->getIdusu();
    $result->bindParam(":idusu",$idusu);
    $result->execute();
    $res=$result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}
public function ins(){
    try {
        $sql = "INSERT INTO desercion(iddec, idusu, idfic, fecregdec, obsdec) VALUES (:iddec, :idusu, :idfic, :fecregdec, :obsdec)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddec = $this->getIddec();
        $result->bindParam(":iddec",$iddec);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $idfic = $this->getIdfic();
        $result->bindParam(":idfic",$idfic);
        $fecregdec = $this->getFecregdec();
        $result->bindParam(":fecregdec",$fecregdec);
        $obsdec = $this->getObsdec();
        $result->bindParam(":obsdec",$obsdec);
        $result->execute();
    } catch (Exception $e) {
        ManejoError($e);
    }
}

public function del(){
    try {
        $sql = "DELETE FROM desercion WHERE idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
    } catch (Exception $e) {
        ManejoError($e);
    }
}


}
?>