<?php
class Mprele {
  private $idprele;
  private $idele;
  private $idusu;
  private $fhpre;
  private $fhent;
  private $estpre;
  private $whopre;

  function getIdprele() { return $this->idprele; }
  function getIdele() { return $this->idele; }
  function getIdusu() { return $this->idusu; }
  function getFhpre() { return $this->fhpre; }
  function getFhent() { return $this->fhent; }
  function getEstpre() { return $this->estpre; }
  function getWhopre() { return $this->whopre; }

  function setIdprele($idprele) { $this->idprele = $idprele; }
  function setIdele($idele) { $this->idele = $idele; }
  function setIdusu($idusu) { $this->idusu = $idusu; }
  function setFhpre($fhpre) { $this->fhpre = $fhpre; }
  function setFhent($fhent) { $this->fhent = $fhent; }
  function setEstpre($estpre) { $this->estpre = $estpre; }
  function setWhopre($whopre) { $this->whopre = $whopre; }

  public function getAll($idper, $idusu) {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT pr.idprele, pr.idele, ft.rutfot, e.nomele, e.nidele, e.marele, e.tipele, e.noplasena, e.desele, e.preele, pr.idusu, u.ndocusu AS docu, u.nomusu AS nomu, p.nomper AS peru, pr.fhpre, pr.estpre, pr.fhent, pr.whopre, w.ndocusu AS docw, w.nomusu AS nomw, wp.nomper AS perw, uf.idfic, f.nomfic, v.nomval AS jornada FROM presele AS pr INNER JOIN elemento AS e ON pr.idele=e.idele LEFT JOIN foto AS ft ON e.idele=ft.idele INNER JOIN usuario AS u ON pr.idusu=u.idusu LEFT JOIN usuario AS w ON pr.idusu=w.idusu LEFT JOIN usufic AS uf ON pr.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic INNER JOIN perfil AS p ON u.idper=p.idper LEFT JOIN valor AS v ON f.jornada=v.idval INNER JOIN perfil AS wp ON w.idper=wp.idper";
      if ($idper != 6 && $idper != 29) $sql .= " WHERE pr.idusu=:idusu";
      $result = $conexion->prepare($sql);
      if ($idper != 6 && $idper != 29) $result->bindParam(':idusu', $idusu);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }
  
  public function getOne() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT pr.idprele, pr.idele, ft.rutfot, e.nomele, e.nidele, e.marele, e.tipele, e.noplasena, e.desele, e.preele, pr.idusu, u.ndocusu AS docu, u.nomusu AS nomu, p.nomper AS peru, pr.fhpre, pr.estpre, pr.fhent, pr.whopre, w.ndocusu AS docw, w.nomusu AS nomw, wp.nomper AS perw, uf.idfic, f.nomfic, v.nomval AS jornada FROM presele AS pr INNER JOIN elemento AS e ON pr.idele=e.idele LEFT JOIN foto AS ft ON e.idele=ft.idele INNER JOIN usuario AS u ON pr.idusu=u.idusu LEFT JOIN usuario AS w ON pr.idusu=w.idusu LEFT JOIN usufic AS uf ON pr.idusu=uf.idusu LEFT JOIN ficha AS f ON uf.idfic=f.idfic INNER JOIN perfil AS p ON u.idper=p.idper LEFT JOIN valor AS v ON f.jornada=v.idval INNER JOIN perfil AS wp ON w.ipder=wp.idper WHERE pr.idprele=:idprele";
      $result = $conexion->prepare($sql);
      $idprele = $this->getIdprele();
      $result->bindParam(':idprele', $idprele);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }

  public function getUsu($ndocusu) {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idusu, ndocusu, nomusu FROM usuario WHERE ndocusu=:ndocusu";
      $result = $conexion->prepare($sql);
      $result->bindParam(":ndocusu", $ndocusu);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }

  public function getMin() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT nummin, obs FROM minuta WHERE ideles=:idele AND idusu=:idusu";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(':idele', $idele);
      $idusu = $this->getIdusu();
      $result->bindParam(':idusu', $idusu);
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }

  public function getEles() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idele, nomele, desele FROM elemento WHERE noplasena!=0";
      $result = $conexion->prepare($sql);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getElem() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT desele FROM elemento WHERE idele=:idele";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(':idele', $idele);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }
  
  public function save() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "INSERT INTO presele(idele, idusu, fhpre, estpre, whopre) VALUES(:idele, :idusu, :fhpre, :estpre, :whopre)";
      $result = $conexion->prepare($sql);
      $idele = $this->getIdele();
      $result->bindParam(':idele', $idele);
      $idusu = $this->getIdusu();
      $result->bindParam(':idusu', $idusu);
      $fhpre = $this->getFhpre();
      $result->bindParam(':fhpre', $fhpre);
      $estpre = $this->getEstpre();
      $result->bindParam(':estpre', $estpre);
      $whopre = $this->getWhopre();
      $result->bindParam(':whopre', $whopre);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }

  public function entEle() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "UPDATE presele SET fhent=:fhent, estpre=:estpre WHERE idprele=:idprele";
      $result = $conexion->prepare($sql);
      $idprele = $this->getIdprele();
      $result->bindParam(':idprele', $idprele);
      $fhent = $this->getFhent();
      $result->bindParam(':fhent', $fhent);
      $estpre = $this->getEstpre();
      $result->bindParam(':estpre', $estpre);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }
  
  public function edit() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "UPDATE presele SET idele=:idele, idusu=:idusu, fhpre=:fhpre, fhent=:fhent, estpre=:estpre, whopre=:whopre WHERE idprele=:idprele";
      $result = $conexion->prepare($sql);
      $idprele = $this->getIdprele();
      $result->bindParam(':idprele', $idprele);
      $idele = $this->getIdele();
      $result->bindParam(':idele', $idele);
      $idusu = $this->getIdusu();
      $result->bindParam(':idusu', $idusu);
      $fhpre = $this->getFhpre();
      $result->bindParam(':fhpre', $fhpre);
      $fhent = $this->getFhent();
      $result->bindParam(':fhent', $fhent);
      $estpre = $this->getEstpre();
      $result->bindParam(':estpre', $estpre);
      $whopre = $this->getWhopre();
      $result->bindParam(':whopre', $whopre);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }

  public function del() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "DELETE FROM presele WHERE idprele=:idprele";
      $result = $conexion->prepare($sql);
      $idprele = $this->getIdprele();
      $result->bindParam(':idprele', $idprele);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }
  }
}
?>