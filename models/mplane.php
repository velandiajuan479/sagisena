<?php
class Mplane {
    private $fase_proyecto;
    private $actividad_proyecto;
    private $competencia;
    private $resultado_aprendizaje;
    private $tipo;
    private $saberes_conceptos_principios;
    private $saberes_proceso;
    private $criterios_evaluacion;
    private $actividades_aprendizaje;
    private $horas_por_rap;
    private $horas_trabajo_directo;
    private $horas_trabajo_independiente;
    private $descripcion_evidencia;
    private $estrategias_didacticas;
    private $ambiente;
    private $materiales_formacion;
    private $instructores_responsables;
    private $observaciones;


    public function getFaseProyecto() { return $this->fase_proyecto; }
    public function getActividadProyecto() { return $this->actividad_proyecto; }
    public function getCompetencia() { return $this->competencia; }
    public function getResultadoAprendizaje() { return $this->resultado_aprendizaje; }
    public function getTipo() { return $this->tipo; }
    public function getSaberesConceptosPrincipios() { return $this->saberes_conceptos_principios; }
    public function getSaberesProceso() { return $this->saberes_proceso; }
    public function getCriteriosEvaluacion() { return $this->criterios_evaluacion; }
    public function getActividadesAprendizaje() { return $this->actividades_aprendizaje; }
    public function getHorasPorRap() { return $this->horas_por_rap; }
    public function getHorasTrabajoDirecto() { return $this->horas_trabajo_directo; }
    public function getHorasTrabajoIndependiente() { return $this->horas_trabajo_independiente; }
    public function getDescripcionEvidencia() { return $this->descripcion_evidencia; }
    public function getEstrategiasDidacticas() { return $this->estrategias_didacticas; }
    public function getAmbiente() { return $this->ambiente; }
    public function getMaterialesFormacion() { return $this->materiales_formacion; }
    public function getInstructoresResponsables() { return $this->instructores_responsables; }
    public function getObservaciones() { return $this->observaciones; }


    public function setFaseProyecto($val) { $this->fase_proyecto = $val; }
    public function setActividadProyecto($val) { $this->actividad_proyecto = $val; }
    public function setCompetencia($val) { $this->competencia = $val; }
    public function setResultadoAprendizaje($val) { $this->resultado_aprendizaje = $val; }
    public function setTipo($val) { $this->tipo = $val; }
    public function setSaberesConceptosPrincipios($val) { $this->saberes_conceptos_principios = $val; }
    public function setSaberesProceso($val) { $this->saberes_proceso = $val; }
    public function setCriteriosEvaluacion($val) { $this->criterios_evaluacion = $val; }
    public function setActividadesAprendizaje($val) { $this->actividades_aprendizaje = $val; }
    public function setHorasPorRap($val) { $this->horas_por_rap = $val; }
    public function setHorasTrabajoDirecto($val) { $this->horas_trabajo_directo = $val; }
    public function setHorasTrabajoIndependiente($val) { $this->horas_trabajo_independiente = $val; }
    public function setDescripcionEvidencia($val) { $this->descripcion_evidencia = $val; }
    public function setEstrategiasDidacticas($val) { $this->estrategias_didacticas = $val; }
    public function setAmbiente($val) { $this->ambiente = $val; }
    public function setMaterialesFormacion($val) { $this->materiales_formacion = $val; }
    public function setInstructoresResponsables($val) { $this->instructores_responsables = $val; }
    public function setObservaciones($val) { $this->observaciones = $val; }

    public function getAll() {
    try {
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();

        $sql = "SELECT * FROM planeacion_proyecto_formativo ORDER BY id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Error al obtener planeaciones: " . $e->getMessage());
    }
}
    public function save() {
        try {
            $sql = "INSERT INTO planeacion_proyecto_formativo (
                fase_proyecto, actividad_proyecto, competencia, resultado_aprendizaje, tipo, 
                saberes_conceptos_principios, saberes_proceso, criterios_evaluacion, actividades_aprendizaje, 
                horas_por_rap, horas_trabajo_directo, horas_trabajo_independiente, descripcion_evidencia, 
                estrategias_didacticas, ambiente, materiales_formacion, instructores_responsables, observaciones
            ) VALUES (
                :fase_proyecto, :actividad_proyecto, :competencia, :resultado_aprendizaje, :tipo, 
                :saberes_conceptos_principios, :saberes_proceso, :criterios_evaluacion, :actividades_aprendizaje, 
                :horas_por_rap, :horas_trabajo_directo, :horas_trabajo_independiente, :descripcion_evidencia, 
                :estrategias_didacticas, :ambiente, :materiales_formacion, :instructores_responsables, :observaciones
            )";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);

            $stmt->bindParam(":fase_proyecto", $this->fase_proyecto);
            $stmt->bindParam(":actividad_proyecto", $this->actividad_proyecto);
            $stmt->bindParam(":competencia", $this->competencia);
            $stmt->bindParam(":resultado_aprendizaje", $this->resultado_aprendizaje);
            $stmt->bindParam(":tipo", $this->tipo);
            $stmt->bindParam(":saberes_conceptos_principios", $this->saberes_conceptos_principios);
            $stmt->bindParam(":saberes_proceso", $this->saberes_proceso);
            $stmt->bindParam(":criterios_evaluacion", $this->criterios_evaluacion);
            $stmt->bindParam(":actividades_aprendizaje", $this->actividades_aprendizaje);
            $stmt->bindParam(":horas_por_rap", $this->horas_por_rap);
            $stmt->bindParam(":horas_trabajo_directo", $this->horas_trabajo_directo);
            $stmt->bindParam(":horas_trabajo_independiente", $this->horas_trabajo_independiente);
            $stmt->bindParam(":descripcion_evidencia", $this->descripcion_evidencia);
            $stmt->bindParam(":estrategias_didacticas", $this->estrategias_didacticas);
            $stmt->bindParam(":ambiente", $this->ambiente);
            $stmt->bindParam(":materiales_formacion", $this->materiales_formacion);
            $stmt->bindParam(":instructores_responsables", $this->instructores_responsables);
            $stmt->bindParam(":observaciones", $this->observaciones);

            return $stmt->execute();

        } catch (Exception $e) {
           
            die("Error al guardar planeación: " . $e->getMessage());
        }
    }
}
?>
