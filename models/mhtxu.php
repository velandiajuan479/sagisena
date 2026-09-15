<?php
class Mhtxu {
    // Método para obtener todos los instructores (usuarios con perfil instructor)
    public function getAllIns() {
        try {
            $sql = "SELECT u.idusu, u.nomusu
                    FROM usuario u
                    INNER JOIN usupef up ON u.idusu = up.idusu
                    WHERE up.idper = 7
                    ORDER BY u.nomusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch (Exception $e) {
            ManejoError($e);
        }
    }

    // Método para asignar un instructor a una hoja de trabajo
    public function asignarInstructorHoja($idnorad, $idusu) {
        try {
            $sql = "INSERT INTO hdtxusu (idnorad, idusu) VALUES (:idnorad, :idusu)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idnorad', $idnorad, PDO::PARAM_INT);
            $stmt->bindParam(':idusu', $idusu, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            ManejoError($e);
            return false;
        }
    }
}
?>