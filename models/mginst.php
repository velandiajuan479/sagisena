<?php

class Mginst {
    

    public function getInstructores() {
        $sql = "SELECT idusu, nomusu FROM usuario WHERE idper = 7"; 
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function convertirASeguimiento($idInstructor) {
        $sql = "UPDATE usuario SET idper = 32 WHERE idusu = :id";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $idInstructor);
        return $stmt->execute();
    }

    public function getInstructoresSeguimiento() {
        $sql = "SELECT idusu, nomusu FROM usuario WHERE idper = 32"; 
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFichas() {
        $sql = "SELECT idfic, nomfic FROM ficha"; 
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProgramasUnicos() {
        $sql = "SELECT MIN(idfic) AS idfic, nomfic FROM ficha GROUP BY nomfic"; 
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function asignarFicha($idInstructor, $idFicha) {
        $sql = "INSERT INTO insseg (idusu, idficha) VALUES (:instructor_id, :ficha_id)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':instructor_id', $idInstructor);
        $stmt->bindParam(':ficha_id', $idFicha);
        return $stmt->execute();
    }

    public function getInsseg(){
        $sql = "SELECT insseg.idusu, insseg.idficha, usuario.nomusu, ficha.nomfic 
                FROM insseg 
                JOIN usuario ON insseg.idusu = usuario.idusu 
                JOIN ficha ON insseg.idficha = ficha.idfic";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarInsSeg($idficha) {
        $sql = "DELETE FROM insseg WHERE idficha = :idficha";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idficha', $idficha);
        return $stmt->execute();
    }

    public function existeAsignacion($idFicha) {
        $sql = "SELECT COUNT(*) FROM insseg WHERE idficha = :ficha_id";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':ficha_id', $idFicha);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getFichasPorPrograma($nomPrograma) {
        $sql = "SELECT idfic, nomfic FROM ficha WHERE nomfic = :nomPrograma";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nomPrograma', $nomPrograma);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    
}

?>