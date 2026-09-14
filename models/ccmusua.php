<?php
class AspiranteModel {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $db = 'mi_base_de_datos';
        $user = 'root';
        $pass = ''; // En XAMPP suele estar vacío
//Aqui debemos hacer la conexion a la base de datos
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    public function insertarAspirante($id, $nombre, $cedula, $correo, $tipo_carrera, $horario) {
        $sql = "INSERT INTO aspirantes (id, nombre, cedula, correo, tipo_carrera, horario)
                VALUES (:id, :nombre, :cedula, :correo, :tipo_carrera, :horario)
                ON DUPLICATE KEY UPDATE 
                    nombre = VALUES(nombre),
                    cedula = VALUES(cedula),
                    correo = VALUES(correo),
                    tipo_carrera = VALUES(tipo_carrera),
                    horario = VALUES(horario)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':cedula' => $cedula,
            ':correo' => $correo,
            ':tipo_carrera' => $tipo_carrera,
            ':horario' => $horario,
        ]);
    }
}
?>