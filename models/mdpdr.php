<?php
class Mdpdr{


    //SELECT `iddocp`, `nomdocp`, `tipdocp` FROM `docped`
    //ATRIBUTOS
    private $iddocp;
    private $nomdocp;
    private $tipdocp;

    //METODOS GET
    function getIddocp(){
        return $this->iddocp;
    }
    function getNomdocp(){
        return $this->nomdocp;
    }
    function getTipdocp(){
        return $this->tipdocp;
    }

    //METODOS SET
    function setIddocp($iddocp){
        $this->iddocp = $iddocp;
    }
    function setNomdocp($nomdocp){
        $this->nomdocp = $nomdocp;
    }
    function setTipdocp($tipdocp){
        $this->tipdocp = $tipdocp;
    }

    //METODOS GENERALES
    public function getAll(){
        try{
            $sql = "SELECT iddocp, nomdocp, tipdocp, act FROM docped";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>Comuniquese con su administrador";
        }
        }

        public function getTipDocDom(){
        try{
            $sql = "SELECT idval, nomval, act FROM valor WHERE iddom=11;";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>Comuniquese con su administrador";
        }
        }
    
   

    public function getOneFil(){
        try{
        $sql = "SELECT iddocp FROM docped WHERE nomdocp=:nomdocp AND tipdocp=:tipdocp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $nomdocp = $this->getNomdocp();
        $result->bindParam(':nomdocp', $nomdocp);
        $tipdocp = $this->getTipdocp();
        $result->bindParam(':tipdocp', $tipdocp);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>Comuniquese con su administrador";
        }
    }

        public function getOne(){
        try{
        $sql = "SELECT iddocp, nomdocp, tipdocp, act FROM docped WHERE iddocp=:iddocp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddocp = $this->getIddocp();
        $result->bindParam(':iddocp', $iddocp);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>Comuniquese con su administrador";
        }
    }

        public function save(){
            try{
                $sql = "INSERT INTO docped(nomdocp, tipdocp) VALUES (:nomdocp, :tipdocp)";
                $modelo = new conexion();
                $conexion = $modelo->get_conexion();
                $result = $conexion->prepare($sql);
                $nomdocp = $this->getNomdocp();
                $tipdocp = $this->getTipdocp();
                $result->bindParam(':nomdocp', $nomdocp);
                $result->bindParam(':tipdocp', $tipdocp);
                $result->execute();
            }catch(Exception $e){
                echo "Error: ".$e."<br><br>Comuniquese con su administrador";
            }
        }

    public function edi(){
        try{
        $sql = "UPDATE docped SET nomdocp=:nomdocp, tipdocp=:tipdocp WHERE iddocp=:iddocp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddocp = $this->getIddocp();
        $result->bindParam(':iddocp', $iddocp);
        $nomdocp = $this->getNomdocp();
        $result->bindParam(':nomdocp', $nomdocp);
        $tipdocp = $this->getTipdocp();
        $result->bindParam(':tipdocp', $tipdocp);
        $result->execute();
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>Comuniquese con su administrador";
        }
    }

    public function eli(){
        try{
        $sql = "DELETE FROM docped WHERE iddocp=:iddocp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddocp = $this->getIddocp();
        $result->bindParam(':iddocp', $iddocp);
        $result->execute();
        }catch(Exception $e){
            echo "Error: ".$e."<br><br>Comuniquese con su administrador";
        }
    }

}
?>