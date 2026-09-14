<?php
class Mdcpr {
    private $iddtc;
    private $idcom;
    private $desdtc;
    private $detfech;

    // Métodos Get
    function getiddtc() { return $this->iddtc; }
    function getidcom() { return $this->idcom; }
    function getdesdtc() { return $this->desdtc; }
    function getdetfech() { return $this->detfech; }

    // Métodos Set
    function setiddtc($iddtc) { $this->iddtc = $iddtc; }
    function setidcom($idcom) { $this->idcom = $idcom; }
    function setdesdtc($desdtc) { $this->desdtc = $desdtc; }
    function setdetfech($detfech) { $this->detfech = $detfech; }

    // Obtener todos los detalles de compromiso
    public function getAllDtc() {
        $sql = "SELECT d.iddtc, d.idcom, d.desdtc, d.detfech 
                FROM detcom AS d 
                INNER JOIN compromiso AS c ON d.idcom = c.idcom";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un detalle específico
    public function getOne() {
        $sql = "SELECT d.iddtc, d.idcom, d.desdtc, d.detfech 
                FROM detcom AS d 
                WHERE d.iddtc = :iddtc";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':iddtc', $this->iddtc);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar nuevo detalle de compromiso
    public function save() {
        $sql = "INSERT INTO detcom (idcom, desdtc, detfech) 
                VALUES (:idcom, :desdtc, :detfech)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idcom', $this->idcom);
        $result->bindParam(':desdtc', $this->desdtc);
        $result->bindParam(':detfech', $this->detfech);
        $result->execute();
    }

    // Editar detalle de compromiso
    public function edit() {
        $sql = "UPDATE detcom 
                SET idcom = :idcom, desdtc = :desdtc, detfech = :detfech 
                WHERE iddtc = :iddtc";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':iddtc', $this->iddtc);
        $result->bindParam(':idcom', $this->idcom);
        $result->bindParam(':desdtc', $this->desdtc);
        $result->bindParam(':detfech', $this->detfech);
        $result->execute();
    }

    // Eliminar detalle de compromiso
    public function del() {
        $sql = "DELETE FROM detcom WHERE iddtc = :iddtc";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':iddtc', $this->iddtc);
        $result->execute();
    }
}
?>