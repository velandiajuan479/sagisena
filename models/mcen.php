<?php
class Mcen{
private $idcen;
private $nomcen;
private $dircen;
private $telcen;
private $imgcen;
private $descen;
private $fiicancen;
private $fficancen;
private $fiprocen;
private $ffprocen;
private $fivotcen;
private $ffvotcen;
// Metodo Get
public function getIdcen(){
    return $this->idcen;
}
public function getNomcen(){
    return $this->nomcen;
}
public function getDircen(){
    return $this->dircen;
}
public function getTelcen(){
    return $this->telcen;
}
public function getImgcen(){
    return $this->imgcen;
}
public function getDescen(){
    return $this->descen;
}
public function getFiicancen(){
    return $this->fiicancen;
}
public function getFficancen(){
    return $this->fficancen;
}
public function getFiprocen(){
    return $this->fiprocen;
}
public function getFfprocen(){
    return $this->ffprocen;
}
public function getFivotcen(){
    return $this->fivotcen;
}
public function getFfvotcen(){
    return $this->ffvotcen;
}

    // Metodos SET
public function setIdcen($idcen){
    $this->idcen=$idcen;
}
public function setNomcen($nomcen){
    $this->nomcen=$nomcen;
}
public function setDircen($dircen){
    $this->dircen=$dircen;
}
public function setTelcen($telcen){
    $this->telcen=$telcen;
}
public function setImgcen($imgcen){
    $this->imgcen=$imgcen;
}
public function setDescen($descen){
    $this->descen=$descen;
}
public function setFiicancen($fiicancen){
    $this->fiicancen=$fiicancen;
}
public function setFficancen($fficancen){
    $this->fficancen=$fficancen;
}
public function setFiprocen($fiprocen){
    $this->fiprocen=$fiprocen;
}
public function setFfprocen($ffprocen){
    $this->ffprocen=$ffprocen;
}
public function setFivotcen($fivotcen){
    $this->fivotcen=$fivotcen;
}
public function setFfvotcen($ffvotcen){
    $this->ffvotcen=$ffvotcen;
}

function getAll(){
    $sql = "SELECT idcen, nomcen, dircen, telcen, imgcen, descen, fiicancen, fficancen, fiprocen, ffprocen, fivotcen, ffvotcen FROM centro";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->execute();
    $res= $result->fetchall(PDO::FETCH_ASSOC);
    return $res;
}

function getOne(){
    $sql = "SELECT idcen, nomcen, dircen, telcen, imgcen, descen, fiicancen, fficancen, fiprocen, ffprocen, fivotcen, ffvotcen FROM centro WHERE idcen=:idcen";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idcen = $this->getIdcen();
    $result->bindParam(":idcen",$idcen);
    $result->execute(); 
    $res= $result->fetchall(PDO::FETCH_ASSOC);
    return $res;
}

function save(){
    $sql = "INSERT INTO centro(idcen, nomcen, dircen, telcen, imgcen, descen, fiicancen, fficancen, fiprocen, ffprocen, fivotcen, ffvotcen)VALUES(:idcen, :nomcen, :dircen, :telcen, :imgcen, :descen, :fiicancen, :fficancen, :fiprocen, :ffprocen, :fivotcen, :ffvotcen)";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);

    $idcen = $this->getIdcen();
    $result->bindParam(":idcen",$idcen);

    $nomcen = $this->getNomcen();
    $result->bindParam(":nomcen",$nomcen);

    $dircen = $this->getDircen();
    $result->bindParam(":dircen",$dircen);

    $telcen = $this->getTelcen();
    $result->bindParam(":telcen",$telcen);

    $imgcen = $this->getImgcen();
    $result->bindParam(":imgcen",$imgcen);

    $descen = $this->getDescen();
    $result->bindParam(":descen",$descen);

    $fiicancen = $this->getFiicancen();
    $result->bindParam(":fiicancen",$fiicancen);

    $fficancen = $this->getFficancen();
    $result->bindParam(":fficancen",$fficancen);

    $fiprocen = $this->getFiprocen();
    $result->bindParam(":fiprocen",$fiprocen);

    $ffprocen = $this->getFfprocen();
    $result->bindParam(":ffprocen",$ffprocen);

    $fivotcen = $this->getFivotcen();
    $result->bindParam(":fivotcen",$fivotcen);

    $ffvotcen = $this->getFfvotcen();
    $result->bindParam(":ffvotcen",$ffvotcen);

    $result->execute();
}

function edit(){
    $imgcen = $this->getImgcen();
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "UPDATE centro SET nomcen=:nomcen, dircen=:dircen, telcen=:telcen, ";
    if($imgcen){
        $sql .= "imgcen=:imgcen, ";
    }
    $sql .= "descen=:descen, fiicancen=:fiicancen, fficancen=:fficancen, fiprocen=:fiprocen, ffprocen=:ffprocen, fivotcen=:fivotcen, ffvotcen=:ffvotcen WHERE idcen=:idcen";
    $result = $conexion->prepare($sql);
    $idcen = $this->getIdcen();
    $result->bindParam(":idcen",$idcen);
    $nomcen = $this->getNomcen();
    $result->bindParam(":nomcen",$nomcen);
    $dircen = $this->getDircen();
    $result->bindParam(":dircen",$dircen);
    $telcen = $this->getTelcen();
    $result->bindParam(":telcen",$telcen);
    if($imgcen){
        $result->bindParam(":imgcen",$imgcen);
    }
    $descen = $this->getDescen();
    $result->bindParam(":descen",$descen);
    $fiicancen = $this->getFiicancen();
    $result->bindParam(":fiicancen",$fiicancen);
    $fficancen = $this->getFficancen();
    $result->bindParam(":fficancen",$fficancen);
    $fiprocen = $this->getFiprocen();
    $result->bindParam(":fiprocen",$fiprocen);
    $ffprocen = $this->getFfprocen();
    $result->bindParam(":ffprocen",$ffprocen);
    $fivotcen = $this->getFivotcen();
    $result->bindParam(":fivotcen",$fivotcen);
    $ffvotcen = $this->getFfvotcen();
    $result->bindParam(":ffvotcen",$ffvotcen);
    $result->execute();
}
function del(){
    $sql = "DELETE FROM centro WHERE idcen=:idcen";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idcen = $this->getIdcen();
    $result->bindParam(":idcen",$idcen);
    $result->execute();
}

function getCrl(){
    $sql = "SELECT COUNT(c.idcen) AS con FROM centro AS c INNER JOIN usuario AS u ON c.idcen=u.idcen INNER JOIN ficha AS f ON c.idcen=f.idcen WHERE c.idcen=:idcen";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idcen = $this->getIdcen();
    $result->bindParam(":idcen",$idcen);
    $result->execute();
    $res=$result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

}
?>