<?php

class Mcep {

    function getInstructores() {
        $sql = "SELECT DISTINCT idusu, ndocusu, nomusu, emausu, telcan FROM usuario WHERE idper = 32";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getFichasPorInstructor($idusu) {
        $sql = "SELECT DISTINCT idficha FROM insseg WHERE idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); 
    }

    function getAprendicesResumenPorFicha($idusu, $idficha) {
        $sql = "
            SELECT 
                u.ndocusu, u.nomusu, u.telcan,
                SUM(CASE WHEN b.estado = 'Aprobado' THEN 1 ELSE 0 END) AS aprobado,
                SUM(CASE WHEN b.estado = 'Rechazado' THEN 1 ELSE 0 END) AS rechazado,
                SUM(CASE WHEN b.estado = 'Pendiente' THEN 1 ELSE 0 END) AS pendiente
            FROM insseg i
            JOIN bitacora b ON i.idusu = b.idinstructor
            JOIN usufic uf ON b.idaprendiz = uf.idusu
            JOIN usuario u ON u.idusu = b.idaprendiz
            WHERE i.idusu = :idusu
            AND uf.idfic = :idficha
            GROUP BY u.idusu, u.ndocusu, u.nomusu, u.telcan
        ";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu, PDO::PARAM_INT);
        $stmt->bindParam(':idficha', $idficha, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAprendicesByFicha($idficha) {
        $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.telcan
            FROM usufic uf
            INNER JOIN usuario u ON uf.idusu = u.idusu
            WHERE uf.idfic = :idfic;
            ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idfic', $idficha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAprendicesByFichaResumen($idficha) {
        $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, u.telcan
                FROM usufic uf
                JOIN usuario u ON uf.idusu = u.idusu
                WHERE uf.idfic = :idficha";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idficha', $idficha);
        $stmt->execute();
        $aprendices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ahora añadir el resumen de bitácoras (aprobadas, rechazadas, pendientes) a cada aprendiz
        foreach ($aprendices as &$ap) {
            $ap['resumen_bitacoras'] = $this->getResumenBitacorasPorAprendiz($ap['idusu'], $idficha);
        }

        return $aprendices;
    }

    public function getResumenBitacorasPorAprendiz($idusu, $idficha) {
        $sql = "SELECT estado, COUNT(*) as total
                FROM bitacora b
                JOIN usufic uf ON b.idaprendiz = uf.idusu
                WHERE uf.idfic = :idficha AND b.idaprendiz = :idusu
                GROUP BY estado";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idficha', $idficha);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inicializar en 0
        $resumen = [
            'aprobado' => 0,
            'rechazado' => 0,
            'pendiente' => 0
        ];

        // Reemplazar con valores de la BD
        foreach ($resultados as $row) {
            $estado = strtolower($row['estado']);
            if (isset($resumen[$estado])) {
                $resumen[$estado] = $row['total'];
            }
        }

        return $resumen;
    }

    public function getResumenBitacorasAprendiz($idusu, $idficha) {
        $sql = "SELECT estado, COUNT(*) as total
                FROM bitacora b
                JOIN usufic uf ON b.idaprendiz = uf.idusu
                WHERE uf.idfic = :idficha AND b.idaprendiz = :idusu
                GROUP BY estado";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idficha', $idficha);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inicializar en 0
        $resumen = [
            'aprobado' => 0,
            'rechazado' => 0,
            'pendiente' => 0
        ];

        // Reemplazar con valores de la BD
        foreach ($resultados as $row) {
            $estado = strtolower($row['estado']);
            if (isset($resumen[$estado])) {
                $resumen[$estado] = $row['total'];
            }
        }

        return $resumen;
    }



}


?>