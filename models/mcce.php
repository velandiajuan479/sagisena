<?php
class Mcce {
    private $idcri, $nomcri, $vscum, $vpar, $vncum, $idins, $idfic, $idusu, $actfic;

    // Getters
    public function getIdfic() { return $this->idfic; }
    public function getIdcri() { return $this->idcri; }
    public function getNomcri() { return $this->nomcri; }
    public function getIdins() { return $this->idins; }
    public function getVscum() { return $this->vscum; }
    public function getVpar()  { return $this->vpar; }
    public function getVncum() { return $this->vncum; }

    // Setters
    public function setIdfic($idfic) { $this->idfic = $idfic; }
    public function setVscum($vscum) { $this->vscum = $vscum; }
    public function setVpar($vpar)   { $this->vpar = $vpar; }
    public function setVncum($vncum) { $this->vncum = $vncum; }
    public function setIdcri($idcri) { $this->idcri = $idcri; }
    public function setNomcri($nomcri) { $this->nomcri = $nomcri; }
    public function setIdins($idins) { $this->idins = $idins; }

    // Métodos CRUD
    public function getAll() {
        $sql = "SELECT c.idcri, c.nomcri, c.idins, c.vscum, c.vpar, c.vncum, i.idfic 
                FROM criterio c 
                INNER JOIN inseva i ON c.idins = i.idins 
                INNER JOIN ficha f ON i.idfic = f.idfic";
        $conexion = (new conexion())->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne() {
        $sql = "SELECT c.idcri, c.nomcri, c.idins, c.vscum, c.vpar, c.vncum, i.idfic 
                FROM criterio c 
                INNER JOIN inseva i ON c.idins = i.idins 
                INNER JOIN ficha f ON i.idfic = f.idfic 
                WHERE c.idcri = :idcri";
        $conexion = (new conexion())->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idcri", $this->idcri);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save() {
        try {
            $conexion = (new conexion())->get_conexion(); 
           $sql = "INSERT INTO criterio (nomcri, vscum, vpar, vncum, idins, idusu) 
                   VALUES (:nomcri, :vscum, :vpar, :vncum, :idins, :idusu)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":nomcri", $this->nomcri);
            $stmt->bindParam(":vscum", $this->vscum);
            $stmt->bindParam(":vpar", $this->vpar);
            $stmt->bindParam(":idins", $this->idins);
            $stmt->bindParam(":vncum", $this->vncum);
            $stmt->bindParam(":idusu", $this->idusu);
            
            $result = $stmt->execute();
            
            if ($result) {
                $this->idcri = $conexion->lastInsertId();
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error en Mcce::save(): " . $e->getMessage());
            return false;
        }
    }

    public function edit() {
        try {
            $sql = "UPDATE criterio SET 
                    nomcri = :nomcri, 
                    vscum = :vscum, 
                    vpar = :vpar 
                    WHERE idcri = :idcri";
            
            $conexion = (new Conexion())->get_conexion();
            $stmt = $conexion->prepare($sql);
            
            $stmt->bindParam(":idcri", $this->idcri);
            $stmt->bindParam(":nomcri", $this->nomcri);
            $stmt->bindParam(":vscum", $this->vscum);
            $stmt->bindParam(":vpar", $this->vpar);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Mcce::edit(): " . $e->getMessage());
            return false;
        }
    }

    public function del() {
        try {
            $sql = "DELETE FROM criterio WHERE idcri = :idcri";
            $conexion = (new conexion())->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":idcri", $this->idcri);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error en Mcce::del(): " . $e->getMessage());
            return false;
        }
    }

    public function getByInstrumento() {
        try {
            $sql = "SELECT c.idcri, c.nomcri, c.vscum, c.vpar, c.vncum, i.idfic 
                    FROM criterio c 
                    INNER JOIN inseva i ON c.idins = i.idins 
                    WHERE c.idins = :idins";
            $conexion = (new conexion())->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":idins", $this->idins);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Mcce::getByInstrumento(): " . $e->getMessage());
            return [];
        }
    }

    public function obtenerCriterios($idins) {
        try {
            $conexion = (new conexion())->get_conexion();
            $sql = "SELECT idcri, nomcri FROM criterio WHERE idins = :idins";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":idins", $idins);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Mcce::obtenerCriterios(): " . $e->getMessage());
            return [];
        }
    }
        public function getValorTotal() {
            try {
                $conexion = (new conexion())->get_conexion();
                $sql = "SELECT SUM(vscum) as total FROM criterio WHERE idins = :idins";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(":idins", $this->idins);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)($result['total'] ?? 0);
            } catch (PDOException $e) {
                error_log("Error en Mcce::getTotalVscumByInstrumento(): " . $e->getMessage());
                return 0;
            }
        }

public function getAprendicesByFicha($idfic = null) {
    try {
        $conexion = (new conexion())->get_conexion();
        $sql = "SELECT u.idusu, u.nomusu
                FROM usufic uf
                INNER JOIN usuario u ON uf.idusu = u.idusu
                WHERE uf.idfic = :idfic
                  AND uf.actfic = 1";

        $stmt = $conexion->prepare($sql);
        $idfic = $idfic ?? $this->idfic; // usa el atributo si no se envía como parámetro
        $stmt->bindParam(":idfic", $idfic);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en Mcce::getAprendicesByFicha(): " . $e->getMessage());
        return [];
    }
}
    public function getNombreFicha($idfic) {
    $sql = "SELECT nomfic FROM ficha WHERE idfic = :idfic";
    $modelo = new Conexion();
    $conexion = $modelo->get_conexion();
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":idfic", $idfic, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn(); // Devuelve el nombre de la ficha
}
}
?>