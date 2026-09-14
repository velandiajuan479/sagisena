<?php
class Mage {
    private $idage;
    private $idfic;
    private $idusu;
    private $idres;
    private $fchinc;
    private $fchfnl;
    private $idcom;
    private $idare;
    private $acti;

    public function getIdage() { return $this->idage; }
    public function getIdfic() { return $this->idfic; }
    public function getIdusu() { return $this->idusu; }
    public function getIdres() { return $this->idres; }
    public function getFchinc() { return $this->fchinc; }
    public function getFchfnl() { return $this->fchfnl; }
    public function getIdcom() { return $this->idcom; }
    public function getIdare() { return $this->idare; }
    public function getActi() { return $this->acti; }

    public function setActi($acti) { $this->acti = $acti; }
    public function setIdage($idage) { $this->idage = $idage; }
    public function setIdfic($idfic) { $this->idfic = $idfic; }
    public function setIdusu($idusu) { $this->idusu = $idusu; }
    public function setIdres($idres) { $this->idres = $idres; }
    public function setFchinc($fchinc) { $this->fchinc = $fchinc; }
    public function setFchfnl($fchfnl) { $this->fchfnl = $fchfnl; }
    public function setIdcom($idcom) { $this->idcom = $idcom; }
    public function setIdare($idare) { $this->idare = $idare; }

    function getAll() {
        $sql = "SELECT * FROM agenda";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    function getOne() {
        $sql = "SELECT idage, idfic, idusu, idres, fchinc, fchfnl, acti FROM agenda WHERE idage = :idage";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idage", $this->idage);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    function save() {
        $sql = "INSERT INTO agenda (idfic, idusu, idres, fchinc, fchfnl, acti) VALUES (:idfic, :idusu, :idres, :fchinc, :fchfnl, :acti)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $this->idfic);
        $result->bindParam(":idusu", $this->idusu);
        $result->bindParam(":idres", $this->idres);
        $result->bindParam(":fchinc", $this->fchinc);
        $result->bindParam(":fchfnl", $this->fchfnl);
        $result->bindParam(":acti", $this->acti);
        $result->execute();
    }

    function edit() {
        $sql = "UPDATE agenda SET idfic = :idfic, idusu = :idusu, idres = :idres, fchinc = :fchinc, fchfnl = :fchfnl, acti = :acti WHERE idage = :idage";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idage", $this->idage);
        $result->bindParam(":idfic", $this->idfic);
        $result->bindParam(":idusu", $this->idusu);
        $result->bindParam(":idres", $this->idres);
        $result->bindParam(":fchinc", $this->fchinc);
        $result->bindParam(":fchfnl", $this->fchfnl);
        $result->bindParam(":acti", $this->acti);
        $result->execute();
    }

    function getAllAge() {
        $sql = "SELECT a.idage, a.idfic, a.fas, a.actpro, a.idcom, a.idres, a.horcom, a.idins, a.hor, 
                       a.lun, a.mar, a.mie, a.jue, a.vie, a.sab, a.fecini, a.fecfin,
                       f.nomfic, f.codpro, f.idusu, f.jornada, f.finific, f.ffinfic,
                       c.descom, r.nomres, r.ndeses, u.nomusu, a.acti
                FROM agenda AS a
                INNER JOIN ficha AS f ON a.idfic = f.idfic
                LEFT JOIN competencia AS c ON a.idcom = c.idcom
                LEFT JOIN resultado AS r ON a.idres = r.idres
                LEFT JOIN usuario AS u ON a.idins = u.idusu
                WHERE a.idfic = :idfic AND a.fas IS NOT NULL
                ORDER BY a.fas, a.actpro, a.idcom, a.idres";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(":idfic", $this->idfic);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

public function getInstrumentosByResultado($idres) {
    $sql = "SELECT idins, nomins FROM inseva WHERE idres = :idres";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idres', $idres);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Muy importante que sea FETCH_ASSOC
}

// Método mejorado para obtener agenda completa - funciona con y sin agendas
function getAllAgeCompleta() {
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    
    // 1. Primero obtenemos las competencias y resultados del programa
    $sql_competencias = "SELECT 
                            c.idcom, c.descom, c.horcom,
                            r.idres, r.nomres, r.ndeses
                         FROM ficha f
                         INNER JOIN programa p ON f.codpro = p.codpro 
                         LEFT JOIN proxcom pc ON p.codpro = pc.codpro 
                         LEFT JOIN competencia c ON c.idcom = pc.idcom 
                         LEFT JOIN resultado r ON r.idcom = c.idcom 
                         WHERE f.idfic = :idfic
                         ORDER BY c.idcom, r.idres";
    
    $result = $conexion->prepare($sql_competencias);
    $result->bindParam(":idfic", $this->idfic);
    $result->execute();
    $competencias = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. Para cada competencia, obtenemos los horarios disponibles
    $datos_completos = [];
    foreach ($competencias as $comp) {
        $sql_horarios = "SELECT 
                            h.iddia, h.fecha_especifica, h.hinihor, h.hfinhor,
                            u.idusu, u.nomusu as instructor,
                            CASE h.iddia 
                                WHEN 1042 THEN 'L'
                                WHEN 1043 THEN 'M' 
                                WHEN 1044 THEN 'M'
                                WHEN 1045 THEN 'J'
                                WHEN 1046 THEN 'V'
                                WHEN 1047 THEN 'S'
                                WHEN 1048 THEN 'D'
                                ELSE ''
                            END as dia_semana
                         FROM horario h
                         LEFT JOIN usuario u ON h.idusu = u.idusu
                         WHERE h.idfic = :idfic AND h.idusu > 0
                         ORDER BY h.fecha_especifica";
        
        $result = $conexion->prepare($sql_horarios);
        $result->bindParam(":idfic", $this->idfic);
        $result->execute();
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. Combinamos competencia con sus horarios
        if (!empty($horarios)) {
            foreach ($horarios as $horario) {
                $datos_completos[] = array_merge($comp, $horario);
            }
        } else {
            // Si no hay horarios, agregamos la competencia sin horarios
            $datos_completos[] = $comp;
        }
    }
    
    return $datos_completos;
}

// Método para obtener horarios por competencia y resultado (adaptado para nueva estructura)
function getHorariosPorCompetencia($idcom, $idres) {
    $sql = "SELECT 
                a.lun, a.mar, a.mie, a.jue, a.vie, a.sab,
                a.hor as horario_texto,
                a.fecini, a.fecfin,
                u.idusu, u.nomusu as instructor
            FROM agenda a
            LEFT JOIN usuario u ON a.idins = u.idusu
            WHERE a.idfic = :idfic 
            AND a.idcom = :idcom
            AND a.idres = :idres
            ORDER BY a.fecini, a.fecfin";
    
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->bindParam(":idfic", $this->idfic);
    $result->bindParam(":idres", $idres);
    $result->bindParam(":idcom", $idcom);
    $result->execute();
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

// Método para obtener fases y actividades por competencia (adaptado para nueva estructura)
function getFasesYActividades($idcom) {
    $sql = "SELECT DISTINCT a.fas as fase, a.actpro as actividad, a.horcom as duracion
            FROM agenda a
            WHERE a.idcom = :idcom AND a.fas IS NOT NULL
            ORDER BY a.fas, a.actpro";
    
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->bindParam(":idcom", $idcom);
    $result->execute();
    return $result->fetchAll(PDO::FETCH_ASSOC);
}


// verifica si ya existe una agenda para idres e idfic
function verAge($idres, $idfic) {
    $sql = "SELECT idage, acti FROM agenda WHERE idres = :idres AND idfic = :idfic";
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->bindParam(":idres", $idres);
    $result->bindParam(":idfic", $idfic);
    $result->execute();
    return $result->fetch(PDO::FETCH_ASSOC);
}

}
?>