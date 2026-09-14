<?php
class Mrpt {
    private $idfic;

    public function getIdfic() {
        return $this->idfic;
    }

    public function setIdfic($idfic) {
        $this->idfic = $idfic;
    }

    // 🔹 Traer información de la ficha y su programa
    public function getFichaPrograma() {
        $sql = "SELECT f.idfic, f.nomfic, p.codpro, p.nompro, p.verpro, 
                       p.horlpro, p.horppro, p.crelpro, p.creppro, p.redcon
                FROM ficha f
                JOIN programa p ON f.codpro = p.codpro
                WHERE f.idfic = :idfic";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idfic", $this->idfic);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Traer competencias y resultados (por ficha y trimestre opcional)
public function getCompetenciasResultados($trimestre = null) {
    $modelo = new Conexion();
    $conexion = $modelo->get_conexion();

    if (empty($trimestre)) {
        $sql = "SELECT DISTINCT c.idcom, c.descom, r.idres, r.nomres
                FROM ficha f
                JOIN programa p ON f.codpro = p.codpro
                JOIN proxcom pc ON p.codpro = pc.codpro
                JOIN competencia c ON pc.idcom = c.idcom
                JOIN resultado r ON c.idcom = r.idcom
                WHERE f.idfic = :idfic
                ORDER BY c.idcom, r.idres";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":idfic", $this->idfic);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $fch = $this->getFechasFicha();
    if (!$fch || empty($fch['finific'])) {
        return [];
    }

    $inicioFicha = new DateTime($fch['finific']);
    $inicio = clone $inicioFicha;
    $inicio->modify('+' . (($trimestre - 1) * 3) . ' months');
    $fin = clone $inicio;
    $fin->modify('+2 months')->modify('last day of this month');

    $sql = "SELECT DISTINCT c.idcom, c.descom, r.idres, r.nomres
            FROM ficha f
            JOIN programa p ON f.codpro = p.codpro
            JOIN proxcom pc ON p.codpro = pc.codpro
            JOIN competencia c ON pc.idcom = c.idcom
            JOIN resultado r ON c.idcom = r.idcom
            JOIN agenda a ON a.idfic = f.idfic AND a.idres = r.idres
            WHERE f.idfic = :idfic
              AND a.fchinc <= :fin
              AND a.fchfnl >= :inicio
            ORDER BY c.idcom, r.idres";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":idfic", $this->idfic);
    $stmt->bindValue(":inicio", $inicio->format('Y-m-d'));
    $stmt->bindValue(":fin", $fin->format('Y-m-d'));
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    // 🔹 Traer fechas de la ficha
public function getFechasFicha() {
    $sql = "SELECT finific, ffinfic 
            FROM ficha 
            WHERE idfic = :idfic";
    $modelo = new Conexion();
    $conexion = $modelo->get_conexion();
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":idfic", $this->idfic);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // solo 1 fila
}
}
?>
