<?php
class Maps {
    public function obtenerDatosEmpleado($id_empleado) {
        try {
            $sql = "SELECT u.idusu, u.ndocusu, u.nomusu AS nombre, 
                    u.emausu AS email, u.telcan AS telefono, 
                    p.nomper AS perfil, d.nombre AS dependencia
                    FROM usuario u
                    LEFT JOIN perfil p ON u.idper = p.idper
                    LEFT JOIN usu_dep ud ON u.idusu = ud.idusu
                    LEFT JOIN dependencias d ON ud.id_dependencia = d.id_dependencia
                    WHERE u.idusu = :id_empleado";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':id_empleado', $id_empleado);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            ManejoError($e);
        }
    }

    public function obtenerDependencias() {
        try {
            $sql = "SELECT id_dependencia, nombre, descripcion FROM dependencias";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            ManejoError($e);
        }
    }

    public function obtenerEmpleadosPorDependencia($id_dependencia) {
        try {
            $sql = "SELECT u.idusu, u.ndocusu, u.nomusu AS nombre, 
                    u.emausu AS email, ud.activo, u.telcan AS telefono, u.idper,
                    p.nomper, ps.calificacion
                    FROM usu_dep ud
                    INNER JOIN usuario u ON ud.idusu = u.idusu
                    LEFT JOIN perfil p ON u.idper = p.idper
                    LEFT JOIN (
                        SELECT d.idusu, d.calificacion 
                        FROM detallesps d 
                        INNER JOIN (
                            SELECT idusu, MAX(fechayhora) as ultima_fecha 
                            FROM detallesps 
                            GROUP BY idusu
                        ) ult ON d.idusu = ult.idusu AND d.fechayhora = ult.ultima_fecha
                    ) ps ON u.idusu = ps.idusu
                    WHERE ud.id_dependencia = :id_dependencia
                    AND ud.activo = 1
                    AND u.idper != 34
                    AND (ps.calificacion IS NULL OR ps.calificacion = 0)
                    ORDER BY u.nomusu";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':id_dependencia', $id_dependencia, PDO::PARAM_INT);
            $result->execute();
            
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            error_log("Usuarios pendientes encontrados para dependencia $id_dependencia: " . count($res));
            
            return $res;
        } catch(Exception $e) {
            error_log("Error en obtenerEmpleadosPorDependencia: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerListaPazYSalvos($id_dependencia) {
        try {
            $sql = "SELECT u.idusu, u.ndocusu, u.nomusu AS nombre, 
                    p.idpaz, p.fecha, d.iddetalle, d.calificacion, 
                    d.observacion, d.fechayhora
                    FROM usu_dep ud
                    INNER JOIN usuario u ON ud.idusu = u.idusu
                    LEFT JOIN pazysalvo p ON u.idusu = p.idusu
                    LEFT JOIN detallesps d ON (p.idpaz = d.idpaz AND d.idusu = u.idusu)
                    WHERE ud.id_dependencia = :id_dependencia
                    AND u.idper != 34
                    ORDER BY u.nomusu, d.fechayhora DESC";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':id_dependencia', $id_dependencia);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            ManejoError($e);
        }
    }

    public function gestionarPazSalvo($idusu, $idpaz, $iddetalle, $observacion, $calificacion) {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $conexion->beginTransaction();

            if ($idpaz) {
                $sql = "SELECT idusu FROM pazysalvo WHERE idpaz = :idpaz";
                $result = $conexion->prepare($sql);
                $result->bindParam(':idpaz', $idpaz);
                $result->execute();
                $res = $result->fetchAll(PDO::FETCH_ASSOC);

                if (!$res || $res[0]['idusu'] != $idusu) {
                    throw new Exception("El paz y salvo no pertenece al usuario especificado", 400);
                }
            } else {
                $sql = "INSERT INTO pazysalvo (idusu) VALUES (:idusu)";
                $result = $conexion->prepare($sql);
                $result->bindParam(':idusu', $idusu);
                $result->execute();
                $idpaz = $conexion->lastInsertId();
            }

            $sql = "INSERT INTO detallesps (idpaz, idusu, calificacion, observacion) 
                    VALUES (:idpaz, :idusu, :calificacion, :observacion)";
            $result = $conexion->prepare($sql);
            $result->bindParam(':idpaz', $idpaz);
            $result->bindParam(':idusu', $idusu);
            $result->bindParam(':calificacion', $calificacion);
            $result->bindParam(':observacion', $observacion);
            $result->execute();
            $iddetalle = $conexion->lastInsertId();

            $conexion->commit();

            return [
                'idpaz' => $idpaz,
                'iddetalle' => $iddetalle,
                'fechayhora' => date('Y-m-d H:i:s')
            ];
        } catch(Exception $e) {
            if (isset($conexion)) {
                $conexion->rollBack();
            }
            ManejoError($e);
        }
    }

    public function obtenerHistorialDetallesPorUsuario($idusu) {
        try {
            $sql = "SELECT iddetalle, idpaz, fechayhora, observacion, calificacion
                    FROM detallesps
                    WHERE idusu = :idusu
                    ORDER BY fechayhora DESC";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idusu', $idusu);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            ManejoError($e);
        }
    }

    public function obtenerUltimaObservacion($idusu) {
        try {
            $sql = "SELECT d.observacion, d.calificacion
                    FROM pazysalvo p
                    INNER JOIN detallesps d ON p.idpaz = d.idpaz
                    WHERE p.idusu = :idusu
                    ORDER BY d.fechayhora DESC
                    LIMIT 1";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(':idusu', $idusu);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        } catch(Exception $e) {
            ManejoError($e);
        }
    }
    
}
