<?php 
class Mcins {
    private $idins;
    private $nomins;    
    private $idres; 
    private $idfic;

    // Getters
    public function getIdfic() { return $this->idfic; }
    public function getIdins() { return $this->idins; }
    public function getNomins() { return $this->nomins; }
    public function getIdres() { return $this->idres; }

    // Setters
    public function setIdfic($idfic) { $this->idfic = $idfic; }
    public function setIdins($idins) { $this->idins = $idins; }
    public function setNomins($nomins) { $this->nomins = $nomins; }
    public function setIdres($idres) { $this->idres = $idres; }

    public function getAll() {
        $sql = "SELECT idins, nomins, idres, idfic FROM inseva";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne() {
        $sql = "SELECT idins, nomins, idres, idfic FROM inseva WHERE idins = :idins";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idins", $this->idins);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC); // 🔹 Devuelve UNA sola fila
    }

    public function save(){
        $sql = "INSERT INTO inseva (nomins, idres, idfic) VALUES (:nomins,:idres,:idfic)";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion(); 
        $result = $conexion->prepare($sql);
        $nomins = $this->getNomins();
        $idres = $this->getIdres(); 
        $idfic = $this->getIdfic();
        $result->bindParam(":nomins", $nomins);
        $result->bindParam(":idres", $idres);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        return $conexion->lastInsertId();
    }

    public function edit() {
        $sql = "UPDATE inseva SET nomins=:nomins, idres=:idres, idfic=:idfic WHERE idins=:idins";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idins = $this->getIdins();
        $nomins = $this->getNomins();
        $idres = $this->getIdres();
        $idfic = $this->getIdfic();
        $result->bindParam(":idins", $idins);
        $result->bindParam(":nomins", $nomins);
        $result->bindParam(":idres", $idres);
        $result->bindParam(":idfic", $idfic);
        $result->execute();
        return $result->rowCount();
    }

    public function del() {
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();

        // Eliminar primero los criterios relacionados
        $sqlCriterios = "DELETE FROM criterio WHERE idins = :idins";
        $stmt1 = $conexion->prepare($sqlCriterios);
        $stmt1->bindParam(":idins", $this->idins);
        $stmt1->execute();

        // Luego eliminar el instrumento
        $sql = "DELETE FROM inseva WHERE idins = :idins";
        $stmt2 = $conexion->prepare($sql);
        $stmt2->bindParam(":idins", $this->idins);
        return $stmt2->execute();
    }

    public function getByResultado() {
        $sql = "SELECT idins, nomins, idres, idfic FROM inseva WHERE idres = :idres";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idres", $this->idres);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existsResultado() {
        $sql = "SELECT COUNT(*) FROM resultado WHERE idres = :idres";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idres", $this->idres);
        $result->execute();
        return $result->fetchColumn() > 0;
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
