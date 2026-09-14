<?php
    class Matse{
        //atributos
        private $idact;
        private $idses;
        private $nomact;
        private $desact;
        private $duract;
        private $tipact;
        private $ordact;
        
        //metodos get
        public function getIdact(){
            return $this->idact;
        }

        public function getIdses(){
            return $this->idses;
        }
        public function getNomact(){
            return $this->nomact;
        }
        public function getDesact(){
            return $this->desact;
        }
        public function getDuract(){
            return $this->duract;
        }  
        public function getTipact(){
            return $this->tipact;
        }
        public function getOrdact(){
            return $this->ordact;
        }
        //metodos SET
        public function setIdact($idact){
            $this->idact =$idact;
        }

        public function setIdses($idses){
            $this->idses =$idses;}
        public function setNomact($nomact){
            $this->nomact =$nomact;
        }
        public function setDesact($desact){
            $this->desact =$desact;
        }
        public function setDuract($duract){
            $this->duract =$duract;
        }
        public function setTipact($tipact){
            $this->tipact =$tipact;
        }
        public function setOrdact($ordact){
            $this->ordact =$ordact;
        }
        //metodos publicos
        public function getAll(){
            $res = NULL;
            $sql = "SELECT * FROM actividad";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res= $result->fetchall(PDO::FETCH_ASSOC);
            return $res;
        }
        public function getOne(){
            $res = NULL;
            $sql = "SELECT * FROM actividad WHERE idact=:idact";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idact = $this->getIdact();
            $result->bindParam(":idact", $idact);
            $result->execute(); 
            $res= $result->fetchall(PDO::FETCH_ASSOC);
            return $res;
        }
        public function getSesion(){
            $sql = "SELECT idses FROM sesion ORDER BY idses";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result-> fetchall(PDO::FETCH_ASSOC);
            return $res;
        }
        public function save(){
            $sql = "INSERT INTO actividad ( nomact, idses, desact, duract, tipact, ordact) VALUES ( :nomact, :idses,:desact,:duract, :tipact, :ordact)";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion(); 
            $result = $conexion->prepare($sql);
            $nomact = $this->getNomact();
            $result->bindParam(":nomact", $nomact);
            $idses = $this->getIdses();
            $result->bindParam(":idses", $idses);
            $desact = $this->getDesact();
            $result->bindParam(":desact", $desact);
            $duract = $this->getDuract();
            $result->bindParam(":duract", $duract);
            $tipact = $this->getTipact();
            $result->bindParam(":tipact", $tipact);
            $ordact = $this->getOrdact();
            $result->bindParam(":ordact", $ordact);
            $result->execute();
        }
        public function edit(){
            $sql = "UPDATE actividad SET nomact=:nomact, idses=:idses, desact=:desact,duract=:duract, tipact=:tipact, ordact=:ordact WHERE idact=:idact";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idact = $this->getIdact();
            $result->bindParam(":idact", $idact);
            $nomact = $this->getNomact();
            $result->bindParam(":nomact", $nomact);
            $desact = $this->getDesact();
            $result->bindParam(":idses", $idses);
            $idses = $this->getIdses();
            $result->bindParam(":desact", $desact);
            $duract = $this->getDuract();
            $result->bindParam(":duract", $duract);
            $tipact = $this->getTipact();
            $result->bindParam(":tipact", $tipact);
            $ordact = $this->getOrdact();
            $result->bindParam(":ordact", $ordact);
            $result->execute();
        }
    
        public function del(){
            $sql = "DELETE FROM actividad WHERE idact=:idact";
            $modelo = new Conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idact = $this->getIdact();
            $result->bindParam(":idact", $idact);
            $result->execute();
        }
    }
?>