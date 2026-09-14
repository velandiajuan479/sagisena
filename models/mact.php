<?php
class Mact{

private $idusu; 
private $ndocusu;
private $nomusu;
private $idper; 
private $idfic;
private $pasusu;
private $idcen; 
private $actusu;
private $opera; 

function getIdusu(){
	return $this->idusu;
}
function getNdocusu(){
	return $this->ndocusu;
}
function getNomusu(){
	return $this->nomusu;
}
function getIdper(){
	return $this->idper;
}
function getIdfic(){
	return $this->idfic;
}
function getPasusu(){
	return $this->pasusu;
}
function getIdcen(){
	return $this->idcen;
}
function getActusu(){
	return $this->actusu;
}
function getOpera(){
	return $this->opera;
}
//metodos set----------------------------------------------------------
function setIdusu($idusu){
	 $this->idusu = $idusu;
}
function setNdocusu($ndocusu){
	 $this->ndocusu = $ndocusu;
}
function setNomusu($nomusu){
	 $this->nomusu = $nomusu;
}
function setIdper($idper){
	 $this->idper = $idper;
}
function setIdfic($idfic){
	 $this->idfic = $idfic;
}
function setPasusu($pasusu){
	 $this->pasusu = $pasusu;
}
function setIdcen($idcen){
	 $this->idcen = $idcen;
}
function setActusu($actusu){
	 $this->actusu = $actusu;
}
function setOpera($opera){
	 $this->opera = $opera;
}

public function selAll(){
    $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, u.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN ficha AS f ON u.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->execute();
    $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
}
public function selOne(){           
    $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.idper, p.nomper, u.idfic, f.nomfic, v.nomval, u.pasusu, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan FROM usuario AS u INNER JOIN perfil AS p ON u.idper=p.idper INNER JOIN centro AS c ON u.idcen=c.idcen LEFT JOIN ficha AS f ON u.idfic=f.idfic LEFT JOIN valor AS v ON f.jornada=v.idval WHERE idusu=:idusu";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idusu = $this->getIdusu();
    $result->bindParam(":idusu",$idusu);
    $result->execute();
    $res = $result->fetchall(PDO::FETCH_ASSOC);
    return $res;
}
public function ins(){
    try{
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "INSERT INTO usuario (ndocusu, nomusu, idper, idfic, pasusu, idcen, actusu) VALUES(:ndocusu,:nomusu,:idper,:idfic,:pasusu,:idcen,:actusu)";
        $result = $conexion->prepare($sql);
        $ndocusu = $this->getNdocusu();
        $result->bindParam("ndocusu",$ndocusu);
        $nomusu = $this->getNomusu();
        $result->bindParam("nomusu",$nomusu);
        $idper = $this->getIdper();
        $result->bindParam("idper",$idper);
        $idfic = $this->getIdfic();
        $result->bindParam("idfic",$idfic);
        $pasusu = $this->getPasusu();
        $result->bindParam("pasusu",$pasusu);
        $idcen = $this->getIdcen();
        $result->bindParam("idcen",$idcen);
        $actusu = $this->getActusu();
        $result->bindParam("actusu",$actusu);
        $result->execute();    
    }catch(Exception $e){
        ManejoError($e);
    }
}
public function upd($idusu,$ndocusu,$nomusu,$idper,$idfic,$pasusu,$idcen,$actusu){
    try{
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "UPDATE  usuario  SET ndocusu=:ndocusu,nomusu=:nomusu,idper=:idper,idfic=:idfic,";
            if($pasusu) $sql .= "pasusu=:pasusu,";
        $sql .= "idcen=:idcen,actusu=:actusu WHERE idusu=:idusu";
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $ndocusu = $this->getNdocusu();
        $result->bindParam(":ndocusu",$ndocusu);
        $nomusu = $this->getNomusu();
        $result->bindParam(":nomusu",$nomusu);
        $idper = $this->getIdper();
        $result->bindParam(":idper",$idper);
        $idfic = $this->getIdfic();
        $result->bindParam(":idfic",$idfic);
        $pasusu = $this->getPasusu();
            if($pasusu) $result->bindParam(":pasusu",$pasusu);
        $idcen = $this->getIdcen();
        $result->bindParam(":idcen",$idcen);
        $actusu = $this->getActusu();
        $result->bindParam(":actusu",$actusu);
        $result->execute();
    }catch(Exception $e){
        ManejoError($e);
    }
}
public function del($idusu){
    try{
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "DELETE FROM  usuario  WHERE idusu=:idusu";
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu",$idusu);
        $result->execute();
    }catch(Exception $e){
        ManejoError($e);
    }
}
public function getCentro(){
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT idcen, nomcen FROM centro";
    $result = $conexion->prepare($sql);
    $result->execute();
    $res = $result->fetchall(PDO::FETCH_ASSOC);
	return $res;
}
public function getPerfil(){
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT idper, nomper FROM perfil";
    $result = $conexion->prepare($sql);
    $result->execute();
    $res = $result->fetchall(PDO::FETCH_ASSOC);
    return $res;
}
public function getFicha(){
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT f.idfic, f.nomfic, v.nomval FROM ficha AS f INNER JOIN valor AS v ON f.jornada=v.idval";
    $result = $conexion->prepare($sql);
    $result->execute();
    $res = $result->fetchall(PDO::FETCH_ASSOC);
return $res;
}
}
?>