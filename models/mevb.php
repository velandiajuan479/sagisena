<?php

class Mevb{


    public function getFichasBitacoras($idusu) {
        $sql = "SELECT idficha FROM insseg WHERE idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAprendicesByFicha($idficha) {
        $sql = "
            SELECT u.idusu, u.ndocusu, u.nomusu, b.idbitacora, b.numero_bitacora, 
                b.nombre_empresa, b.nit, b.fecha_inicio, b.fecha_fin
            FROM usufic uf
            INNER JOIN usuario u ON uf.idusu = u.idusu
            INNER JOIN bitacora b ON b.idaprendiz = u.idusu
            WHERE uf.idfic = :idfic AND b.estado = 'pendiente'
        ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idfic', $idficha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAprendicesAprobadosByFicha($idficha) {
        $sql = "
            SELECT u.idusu, u.ndocusu, u.nomusu, b.idbitacora, b.numero_bitacora, 
                b.nombre_empresa, b.nit, b.fecha_inicio, b.fecha_fin
            FROM usufic uf
            INNER JOIN usuario u ON uf.idusu = u.idusu
            INNER JOIN bitacora b ON b.idaprendiz = u.idusu
            WHERE uf.idfic = :idfic AND b.estado = 'Aprobado'
        ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idfic', $idficha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAprendicesRechazadosByFicha($idficha) {
        $sql = "
            SELECT u.idusu, u.ndocusu, u.nomusu, b.idbitacora, b.numero_bitacora, 
                b.nombre_empresa, b.nit, b.fecha_inicio, b.fecha_fin
            FROM usufic uf
            INNER JOIN usuario u ON uf.idusu = u.idusu
            INNER JOIN bitacora b ON b.idaprendiz = u.idusu
            WHERE uf.idfic = :idfic AND b.estado = 'Rechazado'
        ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idfic', $idficha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBitacorasPendientesByUsuario($idusu) {
        $sql = "SELECT 
                    b.*, 
                    u_jefe.nomusu AS nombre_jefe, 
                    u_instructor.nomusu AS nombre_instructor,
                    u_instructor.emausu AS email_instructor
                FROM bitacora b
                LEFT JOIN usuario u_jefe 
                    ON b.idjefe = u_jefe.idusu
                LEFT JOIN usuario u_instructor 
                    ON b.idinstructor = u_instructor.idusu
                WHERE b.idaprendiz = :idusu AND b.estado = 'pendiente'
        ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOneUsu($idusu) {
        $sql = "SELECT idusu, nomusu, ndocusu, telcan, emausu FROM usuario WHERE idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFichaUsuario($idusu) {
        $sql = "SELECT uf.idfic FROM usufic uf INNER JOIN usuario u ON uf.idusu = u.idusu WHERE u.idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNombrePrograma($idusu) {
        $sql = "SELECT f.nomfic FROM ficha f INNER JOIN usufic uf ON f.idfic = uf.idfic WHERE uf.idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlternativaAndSubalternativa($idbitacora) {
        $sql = "
            SELECT 
                d.nomdom AS alternativa,
                v.nomval AS subalternativa
            FROM bitacora b
            LEFT JOIN dominio d ON b.idaltep = d.iddom
            LEFT JOIN valor v ON b.idsubaltep = v.idval
            WHERE b.idbitacora = :idbitacora
        ";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idbitacora', $idbitacora, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalBitacorasAprobadasPorFicha($idficha) {
        $sql = "SELECT COUNT(*) as total_aprobadas
                FROM bitacora b
                JOIN usufic uf ON b.idaprendiz = uf.idusu
                WHERE uf.idfic = :idficha AND b.estado = 'Aprobado'";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idficha', $idficha);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_aprobadas'] ?? 0;
    }


    public function getActividadesByBitacora($idbitacora) {
        $sql = "SELECT * FROM actividades WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idbitacora', $idbitacora);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstadoBitacoraMasiva($idbitacora, $estado) {
        $sql = "UPDATE bitacora SET estado = :estado WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idbitacora', $idbitacora);
        return $stmt->execute();
    }

    public function saverObservationMasiva($idbitacora, $observacion) {
        $sql = "UPDATE bitacora SET `observacion` = :observacion WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':observacion', $observacion);
        $stmt->bindParam(':idbitacora', $idbitacora);
        return $stmt->execute();
    }

}
?>