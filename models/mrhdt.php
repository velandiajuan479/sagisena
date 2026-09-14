<?php

class Mrhdt {

    //conectar a la base de datos
    private function conectarBD() {
        $host = 'localhost';
        $dbname = 'sagilocal';
        $username = 'root';
        $password = '';
        try {
            $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }   

    // Método para obtener todos los datos
    public function getAll() {
        $sql = "SELECT h.idnorad, h.codpro, p.nompro, h.idemp, e.nomemp, h.codproesp, s.nomval AS proesp, h.codslem, h.feclini, h.feclin, h.cupo, h.jornada, j.nomval AS jorn, h.idfic, f.nomfic 
                FROM hojatra AS h 
                INNER JOIN programa AS p ON h.codpro=p.codpro
                INNER JOIN programa AS np ON np.nompro=h.nompro 
                INNER JOIN empresa AS e ON h.idemp=e.idemp 
                INNER JOIN valor AS s ON h.codproesp=s.idval 
                INNER JOIN valor AS j ON h.jornada=j.idval 
                INNER JOIN ficha AS f ON h.idfic=f.idfic";

        $conexion = $this->conectarBD();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un registro específico
    public function getOne($idnorad) {
        $sql = "SELECT idnorad, codpro, idemp, codproesp, codslem, feclini, feclin, cupo, jornada, idfic
                FROM hojatra
                WHERE idnorad = :idnorad";
        $conexion = $this->conectarBD();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>