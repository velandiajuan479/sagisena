<?php
class Mpraul {
  private $idpres;
  private $idaul;
  private $idusu;
  private $jornada;
  private $estado;
  private $fechin;
  private $fechfin;

  function getIdpres() { return $this->idpres; }
  function getIdaul() { return $this->idaul; }
  function getIdusu() { return $this->idusu; }
  function getJornada() { return $this->jornada; }
  function getEstado() { return $this->estado; }
  function getFechin() { return $this->fechin; }
  function getFechfin() { return $this->fechfin; }

  function setIdpres($idpres) { $this->idpres = $idpres; }
  function setIdaul($idaul) { $this->idaul = $idaul; }
  function setIdusu($idusu) { $this->idusu = $idusu; }
  function setJornada($jornada) { $this->jornada = $jornada; }
  function setEstado($estado) { $this->estado = $estado; }
  function setFechin($fechin) { $this->fechin = $fechin; }
  function setFechfin($fechfin) { $this->fechfin = $fechfin; }

  public function getAll($fechin, $fechfin, $idusu, $idaul) {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT p.idpres, p.idaul, a.nomaul, p.idusu, u.ndocusu, u.nomusu, p.jornada, p.estado, p.fechin, p.fechfin FROM presaula AS p INNER JOIN usuario AS u ON p.idusu = u.idusu INNER JOIN aula AS a ON p.idaul=a.idaul WHERE 1";
      if ($fechin && $fechfin) $sql .= " AND p.fechin BETWEEN :fin AND :ffi";
      else if ($fechin) $sql .= " AND p.fechin>=:fin";
      else if ($fechfin) $sql .= " AND p.fechfin<=:ffi";
      if ($idusu) $sql .= " AND u.idusu=:idusu";
      if ($idaul) $sql .= " AND p.idaul=:idaul";
      $result = $conexion->prepare($sql);
      if ($fechin) {
        $fin = $fechin.' 00:00:00';
        $result->bindParam(':fin', $fin);
      }
      if ($fechfin) {
        $ffi = $fechfin.' 23:59:00';
        $result->bindParam(':ffi', $ffi);
      }
      if ($idusu) $result->bindParam(':idusu', $idusu[0]['idusu']);
      if ($idaul) $result->bindParam(':idaul', $idaul);
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
      $modelo = new Conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT m.idpres, m.idusu, m.idaul, m.jornada, m.fechin, m.fechfin FROM presaula AS m INNER JOIN usuario AS u ON m.idusu=u.idusu WHERE m.idpres=:idpres";
      $result = $conexion->prepare($sql);
      $idpres = $this->getIdpres();
      $result->bindParam(':idpres', $idpres);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getUser($documentNumber) {
    try {
      $res = NULL;
      $modelo = new Conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idusu, nomusu, ndocusu FROM usuario WHERE ndocusu=:ndocusu";
      $result = $conexion->prepare($sql);
      $result->bindParam(':ndocusu', $documentNumber);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getAllAula() {
    try {
      $res = NULL;
      $modelo = new Conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idaul, nomaul FROM aula WHERE tipaul=1";
      $result = $conexion->prepare($sql);
      $result->execute();
      return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getAulas() {
    try {
      $res = NULL;
      $modelo = new Conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT idaul, nomaul FROM aula";
      $result = $conexion->prepare($sql);
      $result->execute();
      return $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function getAulOcu() {
    try {
      $res = NULL;
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "SELECT p.idpres, p.idaul, p.idusu, u.ndocusu, u.nomusu, u.emausu, u.fotcan, u.telcan, p.jornada, p.estado, p.fechin, p.fechfin FROM presaula AS p INNER JOIN usuario AS u ON p.idusu=u.idusu WHERE p.estado='Ocupado' AND p.idaul=:idaul";
      $result = $conexion->prepare($sql);
      $idaul = $this->getIdaul();
      $result->bindParam(':idaul', $idaul);
      $result->execute();
      $res = $result->fetchall(PDO::FETCH_ASSOC);
      return $res;
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function save() {
    try {
      $modelo = new Conexion();
      $conexion = $modelo->get_conexion();
      $sql = "INSERT INTO presaula(idaul, idusu, jornada, estado, fechin) VALUES (:idaul, :idusu, '1', 'Ocupado', :fechin)";
      $result = $conexion->prepare($sql);
      $idusu = $this->getIdusu();
      $result->bindParam(":idusu", $idusu);
      $idaul = $this->getIdaul();
      $result->bindParam(":idaul", $idaul);
      $fechin = $this->getFechin();
      $result->bindParam(":fechin", $fechin);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function edit() {
    try {
      $modelo = new Conexion();
      $conexion = $modelo->get_conexion();
      $sql = "UPDATE presaula SET estado=:estado, fechfin=:fechfin WHERE idpres=:idpres";
      $result = $conexion->prepare($sql);
      $idpres = $this->getIdpres();
      $result->bindParam(":idpres", $idpres);
      $estado = $this->getEstado();
      $result->bindParam(":estado", $estado);
      $fechfin = $this->getFechfin();
      $result->bindParam(":fechfin", $fechfin);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function delSol() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "DELETE FROM presaula WHERE idaul=:idaul AND idusu=:idusu";
      $result = $conexion->prepare($sql);
      $idaul = $this->getIdaul();
      $result->bindParam(":idaul", $idaul);
      $idusu = $this->getIdusu();
      $result->bindParam(":idusu", $idusu);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }

  public function del() {
    try {
      $modelo = new conexion();
      $conexion = $modelo->get_conexion();
      $sql = "DELETE FROM presaula WHERE idpres=:idpres";
      $result = $conexion->prepare($sql);
      $idpres=$this->getIdpres();
      $result->bindParam(":idpres",$idpres);
      $result->execute();
    } catch (Exception $e) {
      echo "Error: $e";
    }
  }
}