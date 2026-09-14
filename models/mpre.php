<?php
class Mpre{
  private $idele;
  private $idusu;
  private $nomele;
  private $nidele;
  private $marele;
  private $tipele;
  private $noplasena;
  private $desele;
  private $preele;
  private $idfot;
  private $rutfot;
  private $iddom;

  function getIdele() { return $this->idele; }
  function getIdusu() { return $this->idusu; }
  function getNomele() { return $this->nomele; }
  function getNidele() { return $this->nidele; }
  function getMarele() { return $this->marele; }
  function getTipele() { return $this->tipele; }
  function getNoplasena() { return $this->noplasena; }
  function getDesele() { return $this->desele; }
  function getPreele() { return $this->preele; }
  function getIdfot() { return $this->idfot; }
  function getRutfot() { return $this->rutfot; }
  function getIddom() { return $this->iddom; }
  // METODOS SET
  function setIdele($idele) { $this->idele = $idele; }
  function setIdusu($idusu) { $this->idusu = $idusu; }
  function setNomele($nomele) { $this->nomele = $nomele; }
  function setNidele($nidele) { $this->nidele = $nidele; }
  function setMarele($marele) { $this->marele = $marele; }
  function setTipele($tipele) { $this->tipele = $tipele; }
  function setNoplasena($noplasena) { $this->noplasena = $noplasena; }
  function setDesele($desele) { $this->desele = $desele; }
  function setPreele($preele) { $this->preele = $preele; }
  function setIdfot($idfot) { $this->idfot = $idfot; }
  function setRutfot($rutfot) { $this->rutfot = $rutfot; }
  function setIddom($iddom) { $this->iddom = $iddom; }

  public function getAll() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();   
      $sql = "SELECT e.idele, e.idusu, e.nomele, e.nidele, e.marele, e.tipele, v.nomval, e.noplasena, e.desele, e.preele, f.rutfot, u.idusu, u.nomusu, u.ndocusu FROM elemento AS e INNER JOIN valor AS v ON e.tipele=v.idval INNER JOIN usuario AS u ON e.idusu=u.idusu LEFT JOIN foto AS f ON e.idele=f.idele WHERE e.preele=4"; 
      if ($_SESSION["idper"]!=6 AND $_SESSION["idper"]!=9)
          $sql .= " WHERE e.idusu='".$_SESSION["idusu"]."'";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getAllpre() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();   
      $sql = "SELECT e.idele, e.idusu, e.nomele, e.nidele, e.marele, e.tipele, v.nomval, e.noplasena, e.desele, e.preele, f.rutfot, u.idusu, u.nomusu, u.ndocusu FROM elemento AS e INNER JOIN valor AS v ON e.tipele=v.idval INNER JOIN usuario AS u ON e.idusu=u.idusu LEFT JOIN foto AS f ON e.idele=f.idele WHERE e.preele=5";
      if ($_SESSION["idper"]!=6 AND $_SESSION["idper"]!=9) $sql .= " WHERE e.idusu='".$_SESSION["idusu"]."'";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getOne() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();    
      $sql = "SELECT e.idele, e.idusu, e.nomele, e.nidele, u.ndocusu, e.marele, e.tipele, v.nomval, e.noplasena, e.desele, e.preele FROM elemento AS e INNER JOIN valor AS v ON e.tipele=v.idval INNER JOIN usuario AS u ON u.idusu=e.idusu WHERE e.idele=:idele";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(":idele", $idele);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getEle() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idele, nomele, desele FROM elemento WHERE preele=5";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getElem($idele) {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idele, nomele, desele FROM elemento WHERE idele=:idele";
      $result = $conexion->prepare($sql);
      $result->bindParam(":idele",$idele);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getOneId() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idele FROM elemento WHERE idusu=:idusu AND nomele=:nomele AND nidele=:nidele AND marele=:marele AND tipele=:tipele";
      $result = $conexion->prepare($sql);
      $idusu = $this->getIdusu();
      $result->bindParam(":idusu",$idusu);
      $nomele = $this->getNomele();
      $result->bindParam(":nomele",$nomele);
      $nidele = $this->getNidele();
      $result->bindParam(":nidele",$nidele);
      $marele = $this->getMarele();
      $result->bindParam(":marele",$marele);
      $tipele = $this->getTipele();
      $result->bindParam(":tipele",$tipele);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getUsu($ndocusu) {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idusu, ndocusu, nomusu FROM usuario WHERE ndocusu=:ndocusu";
      $result = $conexion->prepare($sql);
      $result->bindParam(":ndocusu",$ndocusu);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function save($cact, $can) {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion(); 
      $sql = "UPDATE elemento SET desele=($cact)-($can) WHERE idele=:idele";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(":idele", $idele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function edit($cact, $can) {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();   
      $sql = "UPDATE elemento SET desele=($cact)+($can) WHERE idele=:idele"; 
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(":idele", $idele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }
}
?>