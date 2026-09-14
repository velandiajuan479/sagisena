<?php

class Musumat
{
    private $idusu;
    private $ndocusu;
    private $nomusu;
    private $idcen;
    private $pasusu;
    private $emausu;
    private $actusu;

    // GETTERS
    function getIdusu() { return $this->idusu; }
    function getNdocusu() { return $this->ndocusu; }
    function getNomusu() { return $this->nomusu; }
    function getIdcen() { return $this->idcen; }
    function getPasusu() { return $this->pasusu; }
    function getEmausu() { return $this->emausu; }
    function getActusu() { return $this->actusu; }

    // SETTERS
    function setIdusu($idusu) { $this->idusu = $idusu; }
    function setNdocusu($ndocusu) { $this->ndocusu = $ndocusu; }
    function setNomusu($nomusu) { $this->nomusu = $nomusu; }
    function setIdcen($idcen) { $this->idcen = $idcen; }
    function setPasusu($pasusu) { $this->pasusu = $pasusu; }
    function setEmausu($emausu) { $this->emausu = $emausu; }
    function setActusu($actusu) { $this->actusu = $actusu; }


    // MÉTODOS

    public function getAll()
    {
        try {
            $sql = "SELECT u.idusu, f.idfic, f.actfic, u.ndocusu, u.nomusu, u.pasusu, u.emausu 
                    FROM usuario AS u
                    INNER JOIN usufic AS f ON u.idusu = f.idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuniquese con su administrador";
        }
    }

    public function getOne()
    {
        try {
            $sql = "SELECT u.idusu, f.idfic, f.actfic, u.ndocusu, u.nomusu, u.pasusu, u.emausu 
                    FROM usuario AS u
                    INNER JOIN usufic AS f ON u.idusu = f.idusu 
                    WHERE u.idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $this->idusu);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuniquese con su administrador";
        }
    }

    public function getOneByDoc($doc)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE ndocusu = :doc";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":doc", $doc);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function existeDocumento(string $documento): bool
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM usuario WHERE ndocusu = ?";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$documento]);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado[0]['total'] > 0;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function save()
    {
        try {
            $sql = "INSERT INTO usuario (ndocusu, nomusu, idcen, pasusu, emausu, actusu, idper)
                    VALUES (:ndocusu, :nomusu, :idcen, :pasusu, :emausu, :actusu, '8')";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":ndocusu", $this->ndocusu);
            $result->bindParam(":nomusu", $this->nomusu);
            $result->bindParam(":idcen", $this->idcen);
            $result->bindParam(":pasusu", $this->pasusu);
            $result->bindParam(":emausu", $this->emausu);
            $result->bindParam(":actusu", $this->actusu);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuniquese con su administrador";
        }
    }

    function edit()
    {
        try {
            $sql = "UPDATE usuario 
                    SET ndocusu = :ndocusu, nomusu = :nomusu, pasusu = :pasusu, emausu = :emausu 
                    WHERE idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $this->idusu);
            $result->bindParam(":ndocusu", $this->ndocusu);
            $result->bindParam(":nomusu", $this->nomusu);
            $result->bindParam(":pasusu", $this->pasusu);
            $result->bindParam(":emausu", $this->emausu);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuniquese con su administrador";
        }
    }

    function del()
    {
        try {
            $sql = "DELETE FROM usuario WHERE idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $this->idusu);
            $result->execute();
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuniquese con su administrador";
        }
    }

    function getAllusuf()
    {
        try {
            $sql = "SELECT idusu, idfic, actfic FROM usufic";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Error: " . $e . "<br><br>Comuniquese con su administrador";
        }
    }
}