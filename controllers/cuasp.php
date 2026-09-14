<?php
require_once("muasp.php");
class Cuasp{
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $db = 'usuario'; // <-- Cambia esto por el nombre real de tu base de datos.
        $user = 'root';
        $pass = '';
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public function insertarAspirante($nombre, $cedula, $email, $tipoId) {
        $sql = "INSERT INTO aspirantes (nombre, cedula, email, tipoId)
                VALUES (:nombre, :cedula, :email, :tipoId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':cedula' => $cedula,
            ':email' => $email,
            ':tipoId' => $tipoId,
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM aspirantes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>