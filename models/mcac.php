<?php
class Mcac {
    private $idfic, $idusu, $actfic;

    // Getters
    public function getIdfic() { return $this->idfic; }

    // Setters
    public function setIdfic($idfic) { $this->idfic = $idfic; }

    // Obtener aprendices por ficha
    public function getAprendicesByFicha($idfic = null) {
        try {
            $conexion = (new conexion())->get_conexion();
            $sql = "SELECT u.idusu, u.nomusu
                    FROM usufic uf
                    INNER JOIN usuario u ON uf.idusu = u.idusu
                    WHERE uf.idfic = :idfic
                      AND uf.actfic = 1";

            $stmt = $conexion->prepare($sql);
            $idfic = $idfic ?? $this->idfic;
            $stmt->bindParam(":idfic", $idfic);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getAprendicesByFicha: " . $e->getMessage());
            return [];
        }
    }

    // Obtener criterios asociados a una ficha
public function getCriteriosByFicha($idfic = null) {
    try {
        $conexion = (new conexion())->get_conexion();
        $sql = "SELECT c.idcri, c.nomcri, c.vscum, c.vpar, c.vncum
                FROM criterio c
                INNER JOIN inseva i ON c.idins = i.idins
                WHERE i.idfic = :idfic";

        $stmt = $conexion->prepare($sql);
        $idfic = $idfic ?? $this->idfic;
        $stmt->bindParam(":idfic", $idfic);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en getCriteriosByFicha: " . $e->getMessage());
        return [];
    }
}


    // Guardar muchas calificaciones de una sola vez
    public function guardarCalificaciones($calificaciones) {
        try {
            $conexion = (new conexion())->get_conexion();

            $sql = "INSERT INTO calcri (idcri, idusu, calif) VALUES ";
            $valores = [];
            $params = [];

            foreach ($calificaciones as $idusu => $criterios) {
                foreach ($criterios as $idcri => $calif) {
                    $valores[] = "(?, ?, ?)";
                    $params[] = $idcri;
                    $params[] = $idusu;
                    $params[] = $calif;
                }
            }

            if (empty($valores)) return false;

            $sql .= implode(", ", $valores);
            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);

            return true;
        } catch (PDOException $e) {
            error_log("Error en guardarCalificaciones: " . $e->getMessage());
            return false;
        }
    }
}
?>
