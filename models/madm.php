<?php
require_once 'conexion.php';

class Madm {

    public function obtenerDependencias() {
        try {
            $sql = "SELECT id_dependencia, nombre, descripcion FROM dependencias";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();

            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            throw new Exception("Error al obtener dependencias: " . $e->getMessage(), 500);
        }
    }

    public function obtenerEmpleadosPorDependencia($id_dependencia) {
        try {
            $sql = "SELECT u.idusu, u.ndocusu, u.nomusu AS nombre, 
                    u.emausu AS email, ud.activo, u.telcan AS telefono, u.idper,
                    p.nomper
                    FROM usu_dep ud
                    INNER JOIN usuario u ON ud.idusu = u.idusu
                    LEFT JOIN perfil p ON u.idper = p.idper
                    WHERE ud.id_dependencia = :id_dependencia
                    ORDER BY u.nomusu";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':id_dependencia', $id_dependencia, PDO::PARAM_INT);
            $result->execute();

            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            throw new Exception("Error al obtener empleados: " . $e->getMessage(), 500);
        }
    }
}
