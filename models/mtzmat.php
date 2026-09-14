<?php
class Mtzmat {
    // ===== 1. ATRIBUTOS =====
    private $idtrm;    // PK (BIGINT 15)
    private $idpas;    // FK a paso.idpas (BIGINT 15)
    private $iduxf;    // FK a registro.iduxf (BIGINT 20)
    private $fecreg;   // DATETIME
    private $obstrm;   // TEXT

    // ===== 2. MÉTODOS GET/SET =====
    public function getIdtrm() { return $this->idtrm; }
    public function setIdtrm($idtrm) { $this->idtrm = $idtrm; }

    public function getIdpas() { return $this->idpas; }
    public function setIdpas($idpas) { $this->idpas = $idpas; }

    public function getIduxf() { return $this->iduxf; }
    public function setIduxf($iduxf) { $this->iduxf = $iduxf; }

    public function getFecreg() { return $this->fecreg; }
    public function setFecreg($fecreg) { $this->fecreg = $fecreg; }

    public function getObstrm() { return $this->obstrm; }
    public function setObstrm($obstrm) { $this->obstrm = $obstrm; }

    // ===== 3. CONEXIÓN =====
    private function getConnexion() {
        $modelo = new conexion();
        return $modelo->get_conexion();
    }

    // ===== 4. MÉTODOS CRUD =====
    // --- Obtener todos los registros con JOINs ---
    public function getAll() {
        
            $sql = "SELECT t.*, p.descpas AS nombre_paso, r.fecreg AS fecha_registro 
                FROM trazamat t
                LEFT JOIN paso p ON t.idpas = p.idpas
                LEFT JOIN registro r ON t.iduxf = r.iduxf";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);        
        
    }

    // --- Obtener un registro por ID ---
    public function getOne($idtrm) {
        $sql = "SELECT t.*, p.descpas AS nombre_paso, r.fecreg AS fecha_registro 
                FROM trazamat t
                LEFT JOIN paso p ON t.idpas = p.idpas
                LEFT JOIN registro r ON t.iduxf = r.iduxf
                WHERE t.idtrm = :idtrm";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idtrm', $idtrm, PDO::PARAM_INT);
        $result->execute();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    // --- Guardar nuevo registro ---
    public function save() {
        $sql = "INSERT INTO trazamat (idpas, iduxf, fecreg, obstrm) 
                VALUES (:idpas, :iduxf, :fecreg, :obstrm)";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idpas', $this->idpas, PDO::PARAM_INT);
        $result->bindParam(':iduxf', $this->iduxf, PDO::PARAM_INT);
        $result->bindParam(':fecreg', $this->fecreg);
        $result->bindParam(':obstrm', $this->obstrm);
        return $result->execute();
    }

    // --- Actualizar registro ---
    public function edit() {
        $sql = "UPDATE trazamat 
                SET idpas = :idpas, iduxf = :iduxf, fecreg = :fecreg, obstrm = :obstrm 
                WHERE idtrm = :idtrm";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idtrm', $this->idtrm, PDO::PARAM_INT);
        $result->bindParam(':idpas', $this->idpas, PDO::PARAM_INT);
        $result->bindParam(':iduxf', $this->iduxf, PDO::PARAM_INT);
        $result->bindParam(':fecreg', $this->fecreg);
        $result->bindParam(':obstrm', $this->obstrm);
        return $result->execute();
    }

    // --- Eliminar registro ---
    public function del($idtrm) {
        $sql = "DELETE FROM trazamat WHERE idtrm = :idtrm";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idtrm', $idtrm, PDO::PARAM_INT);
        return $result->execute();
    }

    // ===== 5. MÉTODOS AUXILIARES =====
    // --- Obtener todos los pasos (para selects) ---
    public function getAllPas() {
        $sql = "SELECT idpas, descpas FROM paso";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Obtener todos los registros (para selects) ---
    public function getAllUxf() {
        $sql = "SELECT iduxf, fecreg FROM registro";
        $conexion = $this->getConnexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>