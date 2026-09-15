<?php
require_once(__DIR__ . "/conexion.php");

/**
 * Función para manejar errores de base de datos
 * Solo se define si no existe ya
 */
if (!function_exists('ManejoError')) {
    function ManejoError($e) {
        error_log("Error en Mhorc: " . $e->getMessage());
        // No imprimir nada para evitar romper respuestas JSON
    }
}

class Mhorc {

    private $idhor;
    private $iddia;
    private $hinihor;
    private $hfinhor;
    private $idnorad;
    private $fecha_especifica;
    private $feclini;
    private $feclin;

    // Getters
    
    public function getIdhor() {
        return $this->idhor;
    }

    public function getIddia() {
        return $this->iddia;
    }

    public function getHinihor() {
        return $this->hinihor;
    }

    public function getHfinhor() {
        return $this->hfinhor;
    }

    public function getIdnorad() {
        return $this->idnorad;
    }

    public function getFechaEspecifica() {
        return $this->fecha_especifica;
    }

    public function getFeclini() {
        return $this->feclini;
    }

    public function getFeclin() {
        return $this->feclin;
    }


    // Setters

    public function setIdhor($idhor) {
        $this->idhor = $idhor;
    }

    public function setIddia($iddia) {
        $this->iddia = $iddia;
    }

    public function setHinihor($hinihor) {
        $this->hinihor = $hinihor;
    }

    public function setHfinhor($hfinhor) {
        $this->hfinhor = $hfinhor;
    }

    public function setIdnorad($idnorad) {
        $this->idnorad = $idnorad;
    }

    public function setFechaEspecifica($fecha_especifica) {
        $this->fecha_especifica = $fecha_especifica;
    }

    public function setFeclini($feclini) {
        $this->feclini = $feclini;
    }

    public function setFeclin($feclin) {
        $this->feclin = $feclin;
    }


 
    public function del() {
        $sql = "DELETE FROM horario WHERE id=:iddia AND hinihor=:hinihor AND hfinhor=:hfinhor";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $hinihor = $this->getHinihor();
        $result->bindParam(':hinihor', $hinihor);
        $hfinhor = $this->getHfinhor();
        $result->bindParam(':hfinhor', $hfinhor);
        $result->execute();
    }

    public function getAllVal($iddom) {
        $sql = "SELECT idval, nomval FROM valor WHERE iddom=:iddom";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':iddom', $iddom);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    // Sección de Horarios
    public function getHorario() {
        $sql = "SELECT * FROM horarios WHERE id=:iddia and hinihor=:hinihor and hfinhor=:hfinhor";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $hinihor = $this->getHinihor();
        $result->bindParam(':hinihor', $hinihor);
        $hfinhor = $this->getHfinhor();
        $result->bindParam(':hfinhor', $hfinhor);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function save() {
        try {
            $modelo = new conexion();
        $conexion = $modelo->get_conexion();
            $sql = "INSERT INTO horario (iddia, hinihor, hfinhor) VALUES (:iddia, :hinihor, :hfinhor)";
            $result = $conexion->prepare($sql);

            $result->bindParam(':iddia', $this->iddia);
            $result->bindParam(':hinihor', $this->hinihor);
            $result->bindParam(':hfinhor', $this->hfinhor);

            $result->execute();
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    public function edit() {
        $sql = "UPDATE horario SET iddia=:iddia, hinihor=:hinihor, hfinhor=:hfinhor WHERE id=:iddia";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $hinihor = $this->getHinihor();
        $result->bindParam(':hinihor', $hinihor);
        $hfinhor = $this->getHfinhor();
        $result->bindParam(':hfinhor', $hfinhor);
        $result->execute();
    }

    public function getAll() {
        $sql = "SELECT * FROM horario WHERE idnorad=:idnorad";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();    
        $result = $conexion->prepare($sql);
        $idnorad = $this->getIdnorad();
        $result->bindParam(':idnorad', $idnorad);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne() {
        $sql = "SELECT * FROM horario WHERE id=:iddia";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $iddia = $this->getIddia();
        $result->bindParam(':iddia', $iddia);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    // Obtener horario por fecha específica
    public function getByFecha() {
        $sql = "SELECT * FROM horario WHERE fecha_especifica=:fecha_especifica AND idnorad=:idnorad";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $fecha_especifica = $this->getFechaEspecifica();
        $idnorad = $this->getIdnorad();
        $result->bindParam(':fecha_especifica', $fecha_especifica);
        $result->bindParam(':idnorad', $idnorad);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    // Guardar horario con fecha específica
    public function saveConFecha() {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $sql = "INSERT INTO horario (idnorad, fecha_especifica, hinihor, hfinhor, iddia, idusu) 
                    VALUES (:idnorad, :fecha_especifica, :hinihor, :hfinhor, :iddia, :idusu)";
            $result = $conexion->prepare($sql);

            $idnorad = $this->getIdnorad();
            $fecha_especifica = $this->getFechaEspecifica();
            $hinihor = $this->getHinihor();
            $hfinhor = $this->getHfinhor();
            $iddia = 1044; // Default para lunes (ajustar según corresponda)
            $idusu = 1; // Usuario actual (debería obtenerse de la sesión)

            $result->bindParam(':idnorad', $idnorad);
            $result->bindParam(':fecha_especifica', $fecha_especifica);
            $result->bindParam(':hinihor', $hinihor);
            $result->bindParam(':hfinhor', $hfinhor);
            $result->bindParam(':iddia', $iddia);
            $result->bindParam(':idusu', $idusu);

            $result->execute();
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    // Editar horario con fecha específica
    public function editConFecha() {
        $sql = "UPDATE horario SET fecha_especifica=:fecha_especifica, hinihor=:hinihor, hfinhor=:hfinhor, idnorad=:idnorad 
                WHERE idhor=:idhor";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        
        $idhor = $this->getIdhor();
        $fecha_especifica = $this->getFechaEspecifica();
        $hinihor = $this->getHinihor();
        $hfinhor = $this->getHfinhor();
        $idnorad = $this->getIdnorad();
        
        $result->bindParam(':idhor', $idhor);
        $result->bindParam(':fecha_especifica', $fecha_especifica);
        $result->bindParam(':hinihor', $hinihor);
        $result->bindParam(':hfinhor', $hfinhor);
        $result->bindParam(':idnorad', $idnorad);
        
        $result->execute();
    }

    // Obtener horarios ocupados por otras fichas en un rango de fechas
    public function getHorariosOcupados() {
        $sql = "SELECT h.fecha_especifica, h.hinihor, h.hfinhor, ht.idfic 
                FROM horario h
                INNER JOIN hojatra ht ON h.idnorad = ht.idnorad
                WHERE h.fecha_especifica BETWEEN :feclini AND :feclin 
                AND h.idnorad != :idnorad_excluir
                ORDER BY h.fecha_especifica";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        
        $feclini = $this->getFeclini();
        $feclin = $this->getFeclin();
        $idnorad_excluir = $this->getIdnorad();
        
        $result->bindParam(':feclini', $feclini);
        $result->bindParam(':feclin', $feclin);
        $result->bindParam(':idnorad_excluir', $idnorad_excluir);
        
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

}