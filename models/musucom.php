<?php 
class Musucom{
    
    
    //Atributos
    private $idusu;
    private $idcom;
    private $feccom;

    //Metodos GET.
    function getIdusu(){
        return $this->idusu;
    }
    
    function getIdcom(){
        return $this->idcom;
    }
    function getFecha(){
        return $this->feccom;
    }

    
    //Metodo SET.
    function setIdusu($idusu){
        $this->idusu = $idusu;
    }

    function setIdcom($idcom){
        $this->idcom = $idcom;
    }
    function setFecha($feccom){
        $this->idcom = $feccom;
    }


    //Metodo getALL
    public function getALL(){
        try {
            $sql = "SELECT uc.idusu, u.nomusu, u.codusu, uc.idcom, c.nomcom, c.codcom, uc.feccom FROM usucom AS uc INNER JOIN usuario AS u ON uc.idusu=u.idusu INNER JOIN compromiso AS c ON uc.idcom=c.idcom";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $resul = $conexion->prepare($sql);
            $resul->execute();
            $res = $resul->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    public function getOne(){
        try {
            $sql = "SELECT uc.idusu, u.nomusu, u.codusu, uc.idcom, c.nomcom, c.codcom, uc.feccom FROM usucom AS uc INNER JOIN usuario AS u ON uc.idusu=u.idusu INNER JOIN compromiso AS c ON uc.idcom=c.idcom WHERE uc.idcom = :idcom AND uc.idusu = :idusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $resul = $conexion->prepare($sql);
            $idcom = $this->getIdcom();
            $idusu = $this->getIdusu();
            $resul->bindParam(':idcom', $idcom, ':idusu', $idusu);
            $resul->execute();
            $res = $resul->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    public function save(){
        $sql = "INSERT INTO usucom (idusu, idcom, feccom) VALUES (:idusu, :idcom, :feccom)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $resul = $conexion->prepare($sql);
        $idusu = $this->getIdusu();
        $resul->bindParam(':idusu', $idusu);
        $idcom = $this->getIdcom();
        $resul->bindParam(':idcom', $idcom);
        $feccom = $this->getFecha();
        $resul->bindParam(':feccom', $feccom);
        $resul->execute();
    }

    // public function edit(){
    //     $sql = "UPDATE usucom SET idcom = :idcom, feccom = :feccom WHERE idusu = :idusu";
    //     $modelo = new conexion();
    //     $conexion = $modelo->get_conexion();
    //     $resul = $conexion->prepare($sql);
    //     $idusu = $this->getIdusu();
    //     $resul->bindParam(':idusu', $idusu);
    //     $idcom = $this->getIdcom();
    //     $resul->bindParam(':idcom', $idcom);
    //     $feccom = $this->getFecha();
    //     $resul->bindParam(':feccom', $feccom);
    //     $resul->execute();
    // }

    // public function delete(){
    //     $sql = "DELETE FROM usucom WHERE idusu = :idusu AND idcom = :idcom";
    //     $modelo = new conexion();
    //     $conexion = $modelo->get_conexion();
    //     $resul = $conexion->prepare($sql);
    //     $idusu = $this->getIdusu();
    //     $resul->bindParam(':idusu', $idusu);
    //     $idcom = $this->getIdcom();
    //     $resul->bindParam(':idcom', $idcom);
    //     $resul->execute();
    // }

    public function getAllUsu(){
        try {
            $sql = "SELECT idusu, nomusu, codusu FROM usuario";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $resul = $conexion->prepare($sql);
            $resul->execute();
            $res = $resul->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    public function getAllCom(){
        $sql = "SELECT idcom, nomcom, codcom FROM compromiso";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $resul = $conexion->prepare($sql);
        $resul->execute();
        $res = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $res; 
    }
}
?>