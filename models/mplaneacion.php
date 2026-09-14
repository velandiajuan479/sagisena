<?php
class Mplaneacion {
    private $fp;    // Fase del Proyecto Formativo
    private $ap;    // Actividad del Proyecto Formativo
    private $comp;  // Competencia asociada
    private $ra;    // Resultados de aprendizaje esperados
    private $sab;   // Saberes, conceptos y principios
    private $aa;    // Actividades de aprendizaje
    private $dh;    // Duración en horas
    private $ed;    // Estrategia didáctica
    private $ce;    // Criterios de evaluación
    private $amb;   // Ambiente de aprendizaje
    private $rec;   // Recursos
    private $evid;  // Evidencias
    private $obs;   // Observaciones adicionales
    private $responsable;
    private $fecha;
    private $metodologia;
    private $material;
    private $estado;

    // ================================
    // GETTERS
    // ================================
    function getFp() { return $this->fp; }
    function getAp() { return $this->ap; }
    function getComp() { return $this->comp; }
    function getRa() { return $this->ra; }
    function getSab() { return $this->sab; }
    function getAa() { return $this->aa; }
    function getDh() { return $this->dh; }
    function getEd() { return $this->ed; }
    function getCe() { return $this->ce; }
    function getAmb() { return $this->amb; }
    function getRec() { return $this->rec; }
    function getEvid() { return $this->evid; }
    function getObs() { return $this->obs; }
    function getResponsable() { return $this->responsable; }
    function getFecha() { return $this->fecha; }
    function getMetodologia() { return $this->metodologia; }
    function getMaterial() { return $this->material; }
    function getEstado() { return $this->estado; }

    // ================================
    // SETTERS
    // ================================
    function setFp($fp) { $this->fp = $fp; }
    function setAp($ap) { $this->ap = $ap; }
    function setComp($comp) { $this->comp = $comp; }
    function setRa($ra) { $this->ra = $ra; }
    function setSab($sab) { $this->sab = $sab; }
    function setAa($aa) { $this->aa = $aa; }
    function setDh($dh) { $this->dh = $dh; }
    function setEd($ed) { $this->ed = $ed; }
    function setCe($ce) { $this->ce = $ce; }
    function setAmb($amb) { $this->amb = $amb; }
    function setRec($rec) { $this->rec = $rec; }
    function setEvid($evid) { $this->evid = $evid; }
    function setObs($obs) { $this->obs = $obs; }
    function setResponsable($responsable) { $this->responsable = $responsable; }
    function setFecha($fecha) { $this->fecha = $fecha; }
    function setMetodologia($metodologia) { $this->metodologia = $metodologia; }
    function setMaterial($material) { $this->material = $material; }
    function setEstado($estado) { $this->estado = $estado; }

    // ================================
    // MÉTODOS DE BASE DE DATOS
    // ================================
    public function getAll(){
        try{
            $sql = "SELECT * FROM planeacion_p";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchAll(PDO::FETCH_ASSOC);
            return $res;
        }catch(Exception $e){
            ManejoError($e);
        }
    }

    public function insertP(){
        try{
            $sql = "INSERT INTO planeacion_p 
                (fp, ap, comp, ra, sab, aa, dh, ed, ce, amb, rec, evid, obs, responsable, fecha, metodologia, material, estado) 
                VALUES
                (:fp, :ap, :comp, :ra, :sab, :aa, :dh, :ed, :ce, :amb, :rec, :evid, :obs, :responsable, :fecha, :metodologia, :material, :estado)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);

            $stmt->bindParam(":fp", $this->fp);
            $stmt->bindParam(":ap", $this->ap);
            $stmt->bindParam(":comp", $this->comp);
            $stmt->bindParam(":ra", $this->ra);
            $stmt->bindParam(":sab", $this->sab);
            $stmt->bindParam(":aa", $this->aa);
            $stmt->bindParam(":dh", $this->dh);
            $stmt->bindParam(":ed", $this->ed);
            $stmt->bindParam(":ce", $this->ce);
            $stmt->bindParam(":amb", $this->amb);
            $stmt->bindParam(":rec", $this->rec);
            $stmt->bindParam(":evid", $this->evid);
            $stmt->bindParam(":obs", $this->obs);
            $stmt->bindParam(":responsable", $this->responsable);
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":metodologia", $this->metodologia);
            $stmt->bindParam(":material", $this->material);
            $stmt->bindParam(":estado", $this->estado);

            $stmt->execute();
        }catch(Exception $e){
            ManejoError($e);
        }
    }
}
?>
