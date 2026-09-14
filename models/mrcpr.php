<?php 
class Mrcpr{
    
    
    //Atributos
    // private $idusu;
    // private $idcom;
    // private $fecuxc;

    // //Metodos GET.
    // function getIdusu(){
    //     return $this->idusu;
    // }
    
    // function getIdcom(){
    //     return $this->idcom;
    // }
    // function getFecha(){
    //     return $this->fecuxc;
    // }

    
    // //Metodo SET.
    // function setIdusu($idusu){
    //     $this->idusu = $idusu;
    // }

    // function setIdcom($idcom){
    //     $this->idcom = $idcom;
    // }
    // function setFecha($fecuxc){
    //     $this->idcom = $fecuxc;
    // }


    //Metodo getALL
    public function getALL(){
        try {
            $sql = "SELECT uc.idusu, u.nomusu, u.ndocusu, uc.idcom, c.p1com, uc.fecuxc FROM usucom AS uc INNER JOIN usuario AS u ON uc.idusu=u.idusu INNER JOIN compromiso AS c ON uc.idcom=c.idcom";

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

    // public function getOne(){
    //     try {
    //         $sql = "SELECT uc.idusu, u.nomusu, u.ndocusu, uc.idcom, c.p1com, c.codcom, uc.fecuxc FROM usucom AS uc INNER JOIN usuario AS u ON uc.idusu=u.idusu INNER JOIN compromiso AS c ON uc.idcom=c.idcom WHERE uc.idcom = :idcom AND uc.idusu = :idusu";
    //         $modelo = new conexion();
    //         $conexion = $modelo->get_conexion();
    //         $resul = $conexion->prepare($sql);
    //         $idcom = $this->getIdcom();
    //         $idusu = $this->getIdusu();
    //         $resul->bindParam(':idcom', $idcom, ':idusu', $idusu);
    //         $resul->execute();
    //         $res = $resul->fetchAll(PDO::FETCH_ASSOC);
    //         return $res;
    //     } catch (Exception $e) {
    //         ManejoError($e);
    //     }
    // }

    // public function save(){
    //     $sql = "INSERT INTO usucom (idusu, idcom, fecuxc) VALUES (:idusu, :idcom, :fecuxc)";
    //     $modelo = new conexion();
    //     $conexion = $modelo->get_conexion();
    //     $resul = $conexion->prepare($sql);
    //     $idusu = $this->getIdusu();
    //     $resul->bindParam(':idusu', $idusu);
    //     $idcom = $this->getIdcom();
    //     $resul->bindParam(':idcom', $idcom);
    //     $fecuxc = $this->getFecha();
    //     $resul->bindParam(':fecuxc', $fecuxc);
    //     $resul->execute();
    // }

    // public function edit(){
    //     $sql = "UPDATE usucom SET idcom = :idcom, fecuxc = :fecuxc WHERE idusu = :idusu";
    //     $modelo = new conexion();
    //     $conexion = $modelo->get_conexion();
    //     $resul = $conexion->prepare($sql);
    //     $idusu = $this->getIdusu();
    //     $resul->bindParam(':idusu', $idusu);
    //     $idcom = $this->getIdcom();
    //     $resul->bindParam(':idcom', $idcom);
    //     $fecuxc = $this->getFecha();
    //     $resul->bindParam(':fecuxc', $fecuxc);
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
            $sql = "SELECT idusu, nomusu, ndocusu FROM usuario";
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
        $sql = "SELECT idcom, p1com FROM compromiso";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $resul = $conexion->prepare($sql);
        $resul->execute();
        $res = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $res; 
    }
}
?>