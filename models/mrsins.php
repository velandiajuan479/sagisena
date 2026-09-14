<?php
require_once 'conexion.php';

class Mrsins {
    private $conexion;

    public function __construct() {
        $modelo = new Conexion();
        $this->conexion = $modelo->get_conexion();
    }

    // Obtener instructores que aparecen en resxins
    public function getInstructores() {
        try {
            $sql = "SELECT DISTINCT u.idusu, u.nomusu
                    FROM resxins rx
                    INNER JOIN usuario u ON rx.idusu = u.idusu
                    ORDER BY u.nomusu";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Para desarrollo puedes registrar $e->getMessage()
            return [];
        }
    }

    // Obtener resultados por id de instructor (si $idusu es null devuelve todos)
    public function getResultadosPorInstructorId($idusu = null) {
        try {
            $sql = "SELECT r.idres, r.nomres, c.idcom, c.descom, u.idusu, u.nomusu
                    FROM resxins rx
                    LEFT JOIN resultado r ON rx.idres = r.idres
                    LEFT JOIN competencia c ON r.idcom = c.idcom
                    LEFT JOIN usuario u ON rx.idusu = u.idusu";
            if ($idusu) {
                $sql .= " WHERE u.idusu = :idusu";
            }
            $sql .= " ORDER BY c.descom, r.nomres";

            $stmt = $this->conexion->prepare($sql);
            if ($idusu) {
                $stmt->bindValue(':idusu', $idusu, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>


