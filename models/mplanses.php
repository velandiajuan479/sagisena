<?php
require_once('conexion.php');

class Mplanses {
    private $idplan;
    private $idfic;
    private $idres;
    private $fas;
    private $actapr;
    private $tmp;
    private $cont;
    private $matfor;
    private $numses;
    private $titulo_sesion;
    private $fecha_programada;
    private $estado;
    private $conexion;

    public function __construct() {
        $this->conexion = new conexion();
    }

    // Getters y Setters
    public function getIdplan() { return $this->idplan; }
    public function setIdplan($idplan) { $this->idplan = $idplan; }

    public function getIdfic() { return $this->idfic; }
    public function setIdfic($idfic) { $this->idfic = $idfic; }

    public function getIdres() { return $this->idres; }
    public function setIdres($idres) { $this->idres = $idres; }

    public function getFas() { return $this->fas; }
    public function setFas($fas) { $this->fas = $fas; }

    public function getActapr() { return $this->actapr; }
    public function setActapr($actapr) { $this->actapr = $actapr; }

    public function getTmp() { return $this->tmp; }
    public function setTmp($tmp) { $this->tmp = $tmp; }

    public function getCont() { return $this->cont; }
    public function setCont($cont) { $this->cont = $cont; }

    public function getMatfor() { return $this->matfor; }
    public function setMatfor($matfor) { $this->matfor = $matfor; }

    public function getNumses() { return $this->numses; }
    public function setNumses($numses) { $this->numses = $numses; }

    public function getTitulo_sesion() { return $this->titulo_sesion; }
    public function setTitulo_sesion($titulo_sesion) { $this->titulo_sesion = $titulo_sesion; }

    public function getFecha_programada() { return $this->fecha_programada; }
    public function setFecha_programada($fecha_programada) { $this->fecha_programada = $fecha_programada; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    // Métodos de base de datos
    public function save() {
        $db = $this->conexion->get_conexion();
        $sql = "INSERT INTO planses (idfic, idres, fas, actapr, tmp, cont, matfor, numses, titulo_sesion, fecha_programada, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $this->idfic,
            $this->idres,
            $this->fas,
            $this->actapr,
            $this->tmp,
            $this->cont,
            $this->matfor,
            $this->numses,
            $this->titulo_sesion,
            $this->fecha_programada,
            $this->estado
        ]);
    }

    public function update() {
        $db = $this->conexion->get_conexion();
        $sql = "UPDATE planses SET 
                idfic = ?, idres = ?, fas = ?, actapr = ?, tmp = ?, cont = ?, 
                matfor = ?, numses = ?, titulo_sesion = ?, fecha_programada = ?, estado = ?
                WHERE idplan = ?";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $this->idfic,
            $this->idres,
            $this->fas,
            $this->actapr,
            $this->tmp,
            $this->cont,
            $this->matfor,
            $this->numses,
            $this->titulo_sesion,
            $this->fecha_programada,
            $this->estado,
            $this->idplan
        ]);
    }

    public function delete() {
        $db = $this->conexion->get_conexion();
        $sql = "DELETE FROM planses WHERE idplan = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$this->idplan]);
    }

    public function getOne() {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT * FROM planses WHERE idplan = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$this->idplan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener planes por resultado de aprendizaje y ficha
    public function getPlanesPorResultado($idres, $idfic) {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT * FROM planses 
                WHERE idres = ? AND idfic = ? 
                ORDER BY numses, 
                CASE fas 
                    WHEN 'INICIO' THEN 1 
                    WHEN 'DESARROLLO' THEN 2 
                    WHEN 'CIERRE' THEN 3 
                END";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idres, $idfic]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener planes agrupados por sesión
    public function getPlanesAgrupadosPorSesion($idres, $idfic) {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT numses, titulo_sesion, fecha_programada, estado,
                GROUP_CONCAT(
                    CONCAT(fas, '|', actapr, '|', tmp, '|', COALESCE(cont, ''), '|', COALESCE(matfor, ''))
                    ORDER BY CASE fas 
                        WHEN 'INICIO' THEN 1 
                        WHEN 'DESARROLLO' THEN 2 
                        WHEN 'CIERRE' THEN 3 
                    END
                    SEPARATOR '||'
                ) as actividades
                FROM planses 
                WHERE idres = ? AND idfic = ? 
                GROUP BY numses, titulo_sesion, fecha_programada, estado
                ORDER BY numses";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idres, $idfic]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener información del resultado de aprendizaje
    public function getInfoResultado($idres) {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT r.idres, r.nomres, r.ndeses, c.descom 
                FROM resultado r 
                LEFT JOIN competencia c ON r.idcom = c.idcom 
                WHERE r.idres = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idres]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si existe plan para una sesión específica
    public function existePlanSesion($idres, $idfic, $numses) {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT COUNT(*) as total FROM planses 
                WHERE idres = ? AND idfic = ? AND numses = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idres, $idfic, $numses]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    // Obtener planes por sesión específica
    public function getPlanesPorSesion($idres, $idfic, $numses) {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT * FROM planses 
                WHERE idres = ? AND idfic = ? AND numses = ?
                ORDER BY CASE fas 
                    WHEN 'INICIO' THEN 1 
                    WHEN 'DESARROLLO' THEN 2 
                    WHEN 'CIERRE' THEN 3 
                END";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idres, $idfic, $numses]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar todos los planes de una sesión específica
    public function deletePlanesSesion($idres, $idfic, $numses) {
        $db = $this->conexion->get_conexion();
        $sql = "DELETE FROM planses WHERE idres = ? AND idfic = ? AND numses = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$idres, $idfic, $numses]);
    }

    // Guardar múltiples actividades de una sesión
    public function saveActividadesSesion($idres, $idfic, $numses, $titulo_sesion, $fecha_programada, $actividades) {
        $db = $this->conexion->get_conexion();
        
        try {
            $db->beginTransaction();
            
            // Eliminar actividades existentes de esta sesión
            $this->deletePlanesSesion($idres, $idfic, $numses);
            
            // Insertar nuevas actividades
            $sql = "INSERT INTO planses (idfic, idres, fas, actapr, tmp, cont, matfor, numses, titulo_sesion, fecha_programada, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDIENTE')";
            
            $stmt = $db->prepare($sql);
            
            foreach ($actividades as $actividad) {
                $stmt->execute([
                    $idfic,
                    $idres,
                    $actividad['fas'],
                    $actividad['actapr'],
                    $actividad['tmp'],
                    $actividad['cont'],
                    $actividad['matfor'],
                    $numses,
                    $titulo_sesion,
                    $fecha_programada
                ]);
            }
            
            $db->commit();
            return true;
            
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    // Obtener estadísticas de planes
    public function getEstadisticasPlanes($idres, $idfic) {
        $db = $this->conexion->get_conexion();
        $sql = "SELECT 
                    COUNT(DISTINCT numses) as total_sesiones,
                    COUNT(*) as total_actividades,
                    SUM(tmp) as tiempo_total_minutos,
                    SUM(CASE WHEN estado = 'COMPLETADA' THEN 1 ELSE 0 END) as sesiones_completadas,
                    SUM(CASE WHEN estado = 'EN_CURSO' THEN 1 ELSE 0 END) as sesiones_en_curso,
                    SUM(CASE WHEN estado = 'PENDIENTE' THEN 1 ELSE 0 END) as sesiones_pendientes
                FROM planses 
                WHERE idres = ? AND idfic = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idres, $idfic]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar estado de una sesión
    public function actualizarEstadoSesion($idres, $idfic, $numses, $estado) {
        $db = $this->conexion->get_conexion();
        $sql = "UPDATE planses 
                SET estado = ?, fecha_modificacion = NOW() 
                WHERE idres = ? AND idfic = ? AND numses = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$estado, $idres, $idfic, $numses]);
    }

    // Completar toda la sesión (todas las actividades de todas las fases)
    public function completarTodaSesion($idres, $idfic, $numses) {
        $db = $this->conexion->get_conexion();
        
        try {
            $db->beginTransaction();
            
            // Actualizar el estado de todas las actividades de la sesión a COMPLETADA
            $sql = "UPDATE planses 
                    SET estado = 'COMPLETADA', fecha_modificacion = NOW() 
                    WHERE idres = ? AND idfic = ? AND numses = ?";
            $stmt = $db->prepare($sql);
            $resultado = $stmt->execute([$idres, $idfic, $numses]);
            
            if ($resultado) {
                $db->commit();
                return true;
            } else {
                $db->rollBack();
                return false;
            }
            
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
?>

