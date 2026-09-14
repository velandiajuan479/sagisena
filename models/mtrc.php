<?php
class Mtrc {
    // Atributos
    private $idtrht;
    private $idnorad;
    private $idpas;
    private $idusu;
    private $fectrac;
    //Getters
    public function getidtrht() {
        return $this->idtrht;
    }

    public function getIdnorad() {
        return $this->idnorad;
    }

    public function getIdpas() {
        return $this->idpas;
    }

    public function getIdusu() {
        return $this->idusu;
    }

    public function getFectrac() {
        return $this->fectrac;
    }
    //Setters
    public function setidtrht($idtrht) {
        $this->idtrht = $idtrht;
        return $this;
    }

    public function setIdnorad($idnorad) {
        $this->idnorad = $idnorad;
        return $this;
    }

    public function setIdpas($idpas) {
        $this->idpas = $idpas;
        return $this;
    }

    public function setIdusu($idusu) {
        $this->idusu = $idusu;
        return $this;
    }

    public function setFectrac($fectrac) {
        $this->fectrac = $fectrac;
        return $this;
    }
    // Métodos CRUD
    public function getAll() {
        $sql = "SELECT 
                t.idtnt AS idtrht,
                h.codpro AS trabajo,
                CONCAT('USR-', u.idusu) AS usuario,
                p.descpas AS paso,
                DATE_FORMAT(t.fectrac, '%d-%m-%Y') AS fecha,
                CONCAT('RAD-', h.idnorad) AS radicado,
                CASE 
                    WHEN p.idpas IS NULL THEN 'Finalizado'
                    ELSE 'En proceso'
                END AS estado
            FROM trazacom t
            INNER JOIN hojatra h ON t.idnorad = h.idnorad
            INNER JOIN paso p ON t.idpas = p.idpas
            INNER JOIN usuario u ON t.idusu = u.idusu
            ORDER BY t.fectrac DESC";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>