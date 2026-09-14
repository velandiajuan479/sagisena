<?php

// Definición de constantes para la conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sagi');

/**
 * Clase Mflumat
 * Modelo para la gestión de flujos y pasos de matrícula (SAGI)
 */
class Mflumat {
    private $db; // Objeto de conexión a la base de datos (PDO)

    public function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error de conexión a la base de datos (Mflumat): ' . $e->getMessage());
        }
    }

    // --- Métodos para la tabla 'sagi_flujo' (Gestión de Flujos) ---

    public function getAllFlujos() {
        $stmt = $this->db->query("SELECT idflu, nomflu, fluacti FROM sagi_flujo ORDER BY nomflu");
        return $stmt->fetchAll();
    }

    public function getFlujoById($idFlu) {
        $stmt = $this->db->prepare("SELECT idflu, nomflu, fluacti FROM sagi_flujo WHERE idflu = :idflu");
        $stmt->bindParam(':idflu', $idFlu, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createFlujo($nombreFlujo, $activo) {
        $stmt = $this->db->prepare("INSERT INTO sagi_flujo (nomflu, fluacti) VALUES (:nomflu, :fluacti)");
        $stmt->bindParam(':nomflu', $nombreFlujo, PDO::PARAM_STR);
        $stmt->bindParam(':fluacti', $activo, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateFlujo($idFlu, $nombreFlujo, $activo) {
        $stmt = $this->db->prepare("UPDATE sagi_flujo SET nomflu = :nomflu, fluacti = :fluacti WHERE idflu = :idflu");
        $stmt->bindParam(':nomflu', $nombreFlujo, PDO::PARAM_STR);
        $stmt->bindParam(':fluacti', $activo, PDO::PARAM_INT);
        $stmt->bindParam(':idflu', $idFlu, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteFlujo($idFlu) {
        // Elimina primero los pasos asociados (buena práctica para integridad referencial)
        $this->db->prepare("DELETE FROM sagi_paso WHERE idflu = :idflu_pasos")->execute([':idflu_pasos' => $idFlu]);
        $stmt = $this->db->prepare("DELETE FROM sagi_flujo WHERE idflu = :idflu");
        $stmt->bindParam(':idflu', $idFlu, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // --- Métodos para la tabla 'sagi_paso' (Gestión de Pasos de Flujo) ---

    public function getPasosByFlujoId($idFlu) {
        $stmt = $this->db->prepare("SELECT idpas, idflu, descpas FROM sagi_paso WHERE idflu = :idflu ORDER BY idpas");
        $stmt->bindParam(':idflu', $idFlu, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPasoById($idPaso) {
        $stmt = $this->db->prepare("SELECT idpas, idflu, descpas FROM sagi_paso WHERE idpas = :idpas");
        $stmt->bindParam(':idpas', $idPaso, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createPaso($idFlu, $descripcionPaso) {
        $stmt = $this->db->prepare("INSERT INTO sagi_paso (idflu, descpas) VALUES (:idflu, :descpas)");
        $stmt->bindParam(':idflu', $idFlu, PDO::PARAM_INT);
        $stmt->bindParam(':descpas', $descripcionPaso, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updatePaso($idPaso, $descripcionPaso) {
        $stmt = $this->db->prepare("UPDATE sagi_paso SET descpas = :descpas WHERE idpas = :idpas");
        $stmt->bindParam(':descpas', $descripcionPaso, PDO::PARAM_STR);
        $stmt->bindParam(':idpas', $idPaso, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deletePaso($idPaso) {
        $stmt = $this->db->prepare("DELETE FROM sagi_paso WHERE idpas = :idpas");
        $stmt->bindParam(':idpas', $idPaso, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>