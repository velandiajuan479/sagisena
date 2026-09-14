<?php
class Maula {
  private $idaul;
  private $nomaul;
  private $piso;
  private $bloqau;
  private $taula;
  private $esaula;
  private $desaula;
  private $tipaul;
  private $idcen;
  private $codubi;

  function getIdaul() { return $this->idaul; }
  function getNomaul() { return $this->nomaul; }
  function getPiso() { return $this->piso; }
  function getBloqau() { return $this->bloqau; }
  function getTaula() { return $this->taula; }
  function getEsaula() { return $this->esaula; }
  function getDesaula() { return $this->desaula; }
  function getTipaul() { return $this->tipaul; }
  function getIdcen() { return $this->idcen; }
  function getCodubi() { return $this->codubi; }

  function setIdaul($idaul) { $this->idaul = $idaul; }
  function setNomaul($nomaul) { $this->nomaul = $nomaul; }
  function setPiso($piso) { $this->piso = $piso; }
  function setBloqau($bloqau) { $this->bloqau = $bloqau; }
  function setTaula($taula) { $this->taula = $taula; }
  function setEsaula($esaula) { $this->esaula = $esaula; }
  function setDesaula($desaula) { $this->desaula = $desaula; }
  function setTipaul($tipaul) { $this->tipaul = $tipaul; }
  function setIdcen($idcen) { $this->idcen = $idcen; }
  function setCodubi($codubi) { $this->codubi = $codubi; }

  public function getAllPiso() {
    $sql = "SELECT DISTINCT piso FROM aula WHERE idcen='".$_SESSION["idcen"]."' AND codubi='".$_SESSION["codubi"]."' ORDER BY piso";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function getAllBloque() {
    $sql = "SELECT DISTINCT bloqau FROM aula WHERE idcen='".$_SESSION["idcen"]."' AND codubi='".$_SESSION["codubi"]."' AND piso=:piso AND bloqau>0 ORDER BY bloqau";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $piso = $this->getPiso();
    $result->bindParam(":piso", $piso);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function getAllAula() {
    $sql = "SELECT DISTINCT idaul, nomaul, taula FROM aula WHERE idcen='".$_SESSION["idcen"]."' AND codubi='".$_SESSION["codubi"]."' AND piso=:piso AND bloqau=:bloqau ORDER BY idaul DESC";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $piso = $this->getPiso();
    $result->bindParam(":piso", $piso);
    $bloqau = $this->getBloqau();
    $result->bindParam(":bloqau", $bloqau);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function getOne() {
    $sql = "SELECT idaul, nomaul, piso, bloqau, taula, esaula, desaula, tipaul, idcen, codubi FROM aula WHERE idaul=:idaul";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idaul = $this->getIdaul();
    $result->bindParam(":idaul", $idaul);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function save() {
    $sql = "INSERT INTO aula (nomaul, piso, bloqau, taula, esaula, desaula, tipaul, idcen, codubi) VALUES (:nomaul, :piso, :bloqau, :taula, :esaula, :desaula, :tipaul, :idcen, :codubi)";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $nomaul = $this->getNomaul();
    $result->bindParam(":nomaul", $nomaul);
    $piso = $this->getPiso();
    $result->bindParam(":piso", $piso);
    $bloqau = $this->getBloqau();
    $result->bindParam(":bloqau", $bloqau); 
    $taula = $this->getTaula();
    $result->bindParam(":taula", $taula);
    $esaula = $this->getEsaula();
    $result->bindParam(":esaula", $esaula);
    $desaula = $this->getDesaula();
    $result->bindParam(":desaula", $desaula);
    $tipaul = $this->getTipaul();
    $result->bindParam(":tipaul", $tipaul);
    $idcen = $this->getIdcen();
    $result->bindParam(":idcen", $idcen);
    $codubi = $this->getCodubi();
    $result->bindParam(":codubi", $codubi);
    $result->execute();
  }

  public function edit() {
    $sql = "UPDATE aula SET nomaul=:nomaul, piso=:piso, bloqau=:bloqau, taula=:taula, esaula=:esaula, desaula=:desaula, tipaul=:tipaul, idcen=:idcen, codubi=:codubi WHERE idaul=:idaul";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $nomaul = $this->getNomaul();
    $result->bindParam(":nomaul", $nomaul);
    $piso = $this->getPiso();
    $result->bindParam(":piso", $piso);
    $bloqau = $this->getBloqau();
    $result->bindParam(":bloqau", $bloqau); 
    $taula = $this->getTaula();
    $result->bindParam(":taula", $taula);
    $esaula = $this->getEsaula();
    $result->bindParam(":esaula", $esaula);
    $desaula = $this->getDesaula();
    $result->bindParam(":desaula", $desaula);
    $tipaul = $this->getTipaul();
    $result->bindParam(":tipaul", $tipaul);
    $idcen = $this->getIdcen();
    $result->bindParam(":idcen", $idcen);
    $codubi = $this->getCodubi();
    $result->bindParam(":codubi", $codubi);
    $idaul = $this->getIdaul();
    $result->bindParam(":idaul", $idaul); 
    $result->execute();
  }

  public function del() {
    $sql = "DELETE FROM aula WHERE idaul = :idaul";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $idaul = $this->getIdaul();
    $result->bindParam(":idaul", $idaul);
    $result->execute();
  }
}