<?php
Class Mpam{
  private $idusu;
  private $idval;
  private $nomusu;
  private $parval;
  private $nomval; 
  private $fhlle;
  private $fechos;
  private $act;

  function getIdusu() { return $this->idusu; }
  function getIdval() { return $this->idval; }
  function getNomusu() { return $this->nomusu; }
  function getParval() { return $this->parval; }
  function getNomval() { return $this->nomval; }
  function getFhlle() { return $this->fhlle; }
  function getFechos() { return $this->fechos; }
  function getAct() { return $this->act; }

  function setIdusu($idusu) { $this->idusu = $idusu; }
  function setIdval($idval) { $this->idval = $idval; }
  function setNomusu($nomusu) { $this->nomusu = $nomusu; }
  function setParval($parval) { $this->parval = $parval; }
  function setNomval($nomval) { $this->nomval = $nomval; }
  function setFhlle($fhlle) { $this->fhlle = $idusu; }
  function setFechos($fechos) { $this->fechos = $fechos; }
  function setAct($act) { $this->act = $act; }

  public function getTodoA() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT m.nummin, m.idusu, m.fechos, m.tipmin, m.hij, m.fhlle, m.obs, m.ideles, v.iddom, v.parval, v.nomval, v.novam, u.nomusu, u.ndocusu FROM minuta AS m INNER JOIN valor AS v ON v.idval=m.ideles INNER JOIN usuario AS u ON u.idusu=m.idusu WHERE v.iddom=13 AND m.tipmin IN ('R','E');";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo 'Error: '.$e->getMessage();
    }
}

  public function getOne() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idval, nomval, iddom, parval, act, nhora, novam FROM valor WHERE idval=:idval";
      $result = $conexion->prepare($sql);
      $idval = $this->getIdval();
      $result->bindParam(":idval", $idval);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo 'Error: '.$e->getMessage();
    }
  }

  public function selOneAmb($idval) {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idval, nomval, iddom, parval, act, nhora, novam FROM valor WHERE idval=:idval";
      $result = $conexion->prepare($sql);
      $result->bindParam(":idval", $idval);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo 'Error: '.$e->getMessage();
    }
  }

  public function getOneDom() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idval, nomval, iddom, parval, act, nhora, novam FROM valor WHERE iddom=13 AND act=1";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo 'Error: '.$e->getMessage();
    }
  }

  public function save() {
    try{
      $sql = "UPDATE valor SET act=:act WHERE idval=:idval";
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();    
      $result = $conexion->prepare($sql);
      $idval = $this->getIdval();
      $result->bindParam(":idval", $idval);
      $act = $this->getAct();
      $result->bindParam(":act", $act);
      $result->execute();
    } catch(Exception $e) {
      ManejoError($e);
    }
  }
}
?>