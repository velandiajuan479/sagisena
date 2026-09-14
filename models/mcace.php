<?php
class Mcace{
    private $idfic;
    private $idusu;
    private $nomusu;
    private $actfic;

    public function getActfic() {
        return $this->actfic;
    }

    public function getIdfic() {
        return $this->idfic;
    }

    public function getIdusu() {
        return $this->idusu;
    }

    public function getNomusu() {
        return $this->nomusu;
    }

    public function setActfic($actfic) {
        $this->actfic = $actfic;
    }
        
    public function setIdfic($idfic) {
        $this->idfic = $idfic;
    }

    public function setIdusu($idusu) {
        $this->idusu = $idusu;
    }

    public function setNomusu($nomusu) {
        $this->nomusu = $nomusu;
    }

    public function getAll() {
        $res = NULL;
        $sql = "SELECT uf.idfic, uf.idusu, u.nomusu, uf.actfic FROM usufic uf INNER JOIN  usuario u ON uf.idusu = u.idusu";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne() {
        $res = NULL;
        $sql = "SELECT uf.idfic, uf.idusu, u.nomusu, uf.actfic FROM usufic uf INNER JOIN  usuario u ON uf.idusu = u.idusu WHERE uf.idusu = u.idusu";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idusu", $this->idusu);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function save() {
        $sql = "INSERT INTO usufic (idfic, idusu, actfic) VALUES (:idfic, :idusu, 1)";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion(); 
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $this->idfic);
        $result->bindParam(":idusu", $this->idusu);
        $result->execute();
    }



    public function edit() {
        $sql = "UPDATE usufic SET actfic = :actfic WHERE idfic = :idfic AND idusu = :idusu";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $actfic = 1;
        $result->bindParam(":actfic", $actfic);
        $result->bindParam(":idfic", $this->idfic);
        $result->bindParam(":idusu", $this->idusu);
        $result->execute();
    }


    public function del() {
        $sql = "DELETE FROM usufic WHERE idfic = :idfic AND idusu = :idusu";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $this->idfic);
        $result->bindParam(":idusu", $this->idusu); 
        $result->execute();
    }


    public function getAprendicesPorFicha() {
        $res = null;
        $sql = "SELECT u.idusu, u.nomusu 
                FROM usufic uf
                JOIN usuario u ON u.idusu = uf.idusu
                WHERE uf.idfic = :idfic AND uf.actfic = 1";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $this->idfic);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}
?>