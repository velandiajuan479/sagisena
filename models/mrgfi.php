<?php
class MRFGI {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function obtFic() {
        $sql = "
            SELECT 
                f.idfic, 
                f.nomfic, 
                c.nomcen AS centro,
                f.jornada AS horario,
                p.nompro AS programa,

                (
                    SELECT COUNT(DISTINCT rx.idusu)
                    FROM resxins rx
                    INNER JOIN usuario u ON rx.idusu = u.idusu
                    INNER JOIN usufic uf ON u.idusu = uf.idusu
                    WHERE uf.idfic = f.idfic
                ) AS total_inscritos,

                (
                    SELECT COUNT(rx2.idusu)
                    FROM resxins rx2
                    INNER JOIN usuario u2 ON rx2.idusu = u2.idusu
                    INNER JOIN usufic uf2 ON u2.idusu = uf2.idusu
                    WHERE uf2.idfic = f.idfic
                ) AS cantidad

            FROM ficha f
            LEFT JOIN centro c ON f.idcen = c.idcen
            LEFT JOIN programa p ON f.codpro = p.codpro
            ORDER BY f.idfic DESC
        ";

        $conexion = $this->conn;
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function obtFicXId($idfic) {
        $sql = "SELECT * FROM ficha WHERE idfic = :idfic";
        $conexion = $this->conn;
        $result = $conexion->prepare($sql);
        $result->bindParam(':idfic', $idfic);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function actFicha($idfic, $nomfic, $codpro, $jornada, $idcen, $can) {
        $sql = "UPDATE ficha SET nomfic = :nomfic, codpro = :codpro, jornada = :jornada, idcen = :idcen, can = :can WHERE idfic = :idfic";
        $conexion = $this->conn;
        $result = $conexion->prepare($sql);
        $result->bindParam(':nomfic', $nomfic);
        $result->bindParam(':codpro', $codpro);
        $result->bindParam(':jornada', $jornada);
        $result->bindParam(':idcen', $idcen);
        $result->bindParam(':can', $can);
        $result->bindParam(':idfic', $idfic);
        return $result->execute();
    }
}
?>
