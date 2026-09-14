<?php
class Mdoc {
    // Atributos
    private $iddoc;
    private $nomdoc;
    private $idnorad;

    // GET
    function getIddoc() {
        return $this->iddoc;
    }
    function getNomdoc() {
        return $this->nomdoc;
    }
    function getIdnorad() {
        return $this->idnorad;
    }

    // SET
    function setIddoc($iddoc) {
        $this->iddoc = $iddoc;
    }
    function setNomdoc($nomdoc) {
        $this->nomdoc = $nomdoc;
    }
    function setIdnorad($idnorad) {
        $this->idnorad = $idnorad;
    }

    // Métodos
    public function getAll() {
        try {
            $sql = "SELECT iddoc, nomdoc, idnorad FROM documento";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    public function getOne() {
        try {
            $sql = "SELECT iddoc, nomdoc, idnorad FROM documento WHERE iddoc=:iddoc";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $iddoc = $this->getIddoc();
            $result->bindParam(":iddoc", $iddoc);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    public function save() {
        try {
            $sql = "INSERT INTO documento (nomdoc, idnorad) VALUES (:nomdoc, :idnorad)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $nomdoc = $this->getNomdoc();
            $result->bindParam(":nomdoc", $nomdoc);
            $idnorad = $this->getIdnorad();
            $result->bindParam(":idnorad", $idnorad);
            $result->execute();
        } catch(Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    public function edit() {
        try {
            $sql = "UPDATE documento SET nomdoc=:nomdoc, idnorad=:idnorad WHERE iddoc=:iddoc";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $iddoc = $this->getIddoc();
            $result->bindParam(":iddoc", $iddoc);
            $nomdoc = $this->getNomdoc();
            $result->bindParam(":nomdoc", $nomdoc);
            $idnorad = $this->getIdnorad();
            $result->bindParam(":idnorad", $idnorad);
            $result->execute();
        } catch(Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }

    public function del() {
        try {
            $sql = "DELETE FROM documento WHERE iddoc=:iddoc";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $iddoc = $this->getIddoc();
            $result->bindParam(":iddoc", $iddoc);
            $result->execute();
        } catch(Exception $e) {
            echo "Error: ".$e."<br><br>Comuníquese con su administrador.";
        }
    }
}
?>
