<?php
class Mele {
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

  public function getAll() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT e.idele, e.idusu, e.nomele, e.nidele, e.marele, e.tipele, v.nomval, e.noplasena, e.desele, e.preele, f.rutfot, u.idusu, u.nomusu, u.ndocusu FROM elemento AS e INNER JOIN valor AS v ON e.tipele=v.idval INNER JOIN usuario AS u ON e.idusu=u.idusu LEFT JOIN foto AS f ON e.idele=f.idele";
      if ($_SESSION["idper"] != 6 || $_SESSION["idper"] != 9 || $_SESSION['idper'] != 29)
        $sql .= " WHERE e.idusu='" . $_SESSION["idusu"] . "'";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function getOne() {
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT e.idele, e.idusu, e.nomele, e.nidele, e.marele, e.tipele, v.nomval, e.noplasena, e.desele, e.preele, f.rutfot FROM elemento AS e INNER JOIN valor AS v ON e.tipele=v.idval LEFT JOIN foto AS f ON e.idele=f.idele WHERE e.idele=:idele";
    $result = $conexion->prepare($sql);
    $idele = $this->getIdele();
    $result->bindParam(":idele", $idele);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function getElem() {
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT idele, idusu, nomele, nidele, marele, tipele, noplasena, desele, preele FROM elemento WHERE idele=:idele";
    $result = $conexion->prepare($sql);
    $idele = $this->getIdele();
    $result->bindParam(":idele", $idele);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function getEle() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idval, nomval, iddom, parval, act FROM valor WHERE iddom=12";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function getOneId() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idele FROM elemento WHERE idusu=:idusu and nomele=:nomele and nidele=:nidele and marele=:marele and tipele=:tipele";
      $result = $conexion->prepare($sql);
      $idusu = $this->getIdusu();
      $result->bindParam(":idusu", $idusu);
      $nomele = $this->getNomele();
      $result->bindParam(":nomele", $nomele);
      $nidele = $this->getNidele();
      $result->bindParam(":nidele", $nidele);
      $marele = $this->getMarele();
      $result->bindParam(":marele", $marele);
      $tipele = $this->getTipele();
      $result->bindParam(":tipele", $tipele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function selectEle() {
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT COUNT(*) as sum FROM elemento WHERE nidele=:nidele";
    $result = $conexion->prepare($sql);
    $nidele = $this->getNidele();
    $result->bindParam(":nidele", $nidele);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
  }

  public function savePre() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "INSERT INTO elemento(idusu, nomele, nidele, marele, tipele, noplasena, desele, preele) VALUES (:idusu, :nomele, :nidele, :marele, :tipele, :noplasena, :desele, :preele)";
      $result = $conexion->prepare($sql);
      $idusu = $this->getIdusu();
      $result->bindParam(":idusu", $idusu);
      $nomele = $this->getNomele();
      $result->bindParam(":nomele", $nomele);
      $nidele = $this->getNidele();
      $result->bindParam(":nidele", $nidele);
      $marele = $this->getMarele();
      $result->bindParam(":marele", $marele);
      $tipele = $this->getTipele();
      $result->bindParam(":tipele", $tipele);
      $noplasena = $this->getNoplasena();
      $result->bindParam(":noplasena", $noplasena);
      $desele = $this->getDesele();
      $result->bindParam(":desele", $desele);
      $preele = $this->getPreele();
      $result->bindParam(":preele", $preele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function save() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "INSERT INTO elemento(idusu, nomele, nidele, marele, tipele, noplasena, desele) VALUES (:idusu, :nomele, :nidele, :marele, :tipele, :noplasena, :desele)";
      $result = $conexion->prepare($sql);
      $idusu = $this->getIdusu();
      $result->bindParam(":idusu", $idusu);
      $nomele = $this->getNomele();
      $result->bindParam(":nomele", $nomele);
      $nidele = $this->getNidele();
      $result->bindParam(":nidele", $nidele);
      $marele = $this->getMarele();
      $result->bindParam(":marele", $marele);
      $tipele = $this->getTipele();
      $result->bindParam(":tipele", $tipele);
      $noplasena = $this->getNoplasena();
      $result->bindParam(":noplasena", $noplasena);
      $desele = $this->getDesele();
      $result->bindParam(":desele", $desele);
      $result->execute();
      $lastId = $conexion->lastInsertId();
      return $lastId;
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function editPre() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "UPDATE elemento SET preele=:preele WHERE idele=:idele";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(":idele", $idele);
      $preele = $this->getPreele();
      $result->bindParam(":preele", $preele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function edit() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "UPDATE elemento SET nomele=:nomele, nidele=:nidele, marele=:marele, tipele=:tipele, noplasena=:noplasena, desele=:desele WHERE idele=:idele";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(":idele", $idele);
      $nomele = $this->getNomele();
      $result->bindParam(":nomele", $nomele);
      $nidele = $this->getNidele();
      $result->bindParam(":nidele", $nidele);
      $marele = $this->getMarele();
      $result->bindParam(":marele", $marele);
      $tipele = $this->getTipele();
      $result->bindParam(":tipele", $tipele);
      $noplasena = $this->getNoplasena();
      $result->bindParam(":noplasena", $noplasena);
      $desele = $this->getDesele();
      $result->bindParam(":desele", $desele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }

  public function del() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "DELETE FROM elemento WHERE idele=:idele";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(":idele", $idele);
      $result->execute();
    } catch (Exception $e) {
      ManejoError($e);
    }
  }
}
