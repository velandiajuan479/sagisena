<?php

class Mbit {
    private $idbitacora;
    private $idaprendiz;
    private $idjefe;
    private $idinstructor;
    private $nombre_empresa;
    private $nit;
    private $numero_bitacora;
    private $fecha_inicio;
    private $fecha_fin;
    private $fecha_entrega;
    private $idaltep;
    private $idsubaltep;
    private $archivo_evidencia;
    private $link_evidencia;
    private $nvlarl;

    // Getters
    public function getIdbitacora() {
        return $this->idbitacora;
    }

    public function getIdaprendiz() {
        return $this->idaprendiz;
    }

    public function getIdjefe() {
        return $this->idjefe;
    }

    public function getIdinstructor() {
        return $this->idinstructor;
    }

    public function getNombreEmpresa() {
        return $this->nombre_empresa;
    }

    public function getNit() {
        return $this->nit;
    }

    public function getNumeroBitacora() {
        return $this->numero_bitacora;
    }

    public function getFechaInicio() {
        return $this->fecha_inicio;
    }

    public function getFechaFin() {
        return $this->fecha_fin;
    }

    public function getFechaEntrega() {
        return $this->fecha_entrega;
    }

    public function getIdaltep() {
        return $this->idaltep;
    }

    public function getIdsubaltep(){
        return $this->idsubaltep;
    }

    public function getArchivoEvidencia() {
        return $this->archivo_evidencia;
    }
    public function getLinkEvidencia() {
        return $this->link_evidencia;
    }

    public function getNvlarl() {
        return $this->nvlarl;
    }

    // Setters
    public function setIdbitacora($idbitacora) {
        $this->idbitacora = $idbitacora;
    }

    public function setIdaprendiz($idaprendiz) {
        $this->idaprendiz = $idaprendiz;
    }

    public function setIdjefe($idjefe) {
        $this->idjefe = $idjefe;
    }

    public function setIdinstructor($idinstructor) {
        $this->idinstructor = $idinstructor;
    }

    public function setNombreEmpresa($nombre_empresa) {
        $this->nombre_empresa = $nombre_empresa;
    }

    public function setNit($nit) {
        $this->nit = $nit;
    }

    public function setNumeroBitacora($numero_bitacora) {
        $this->numero_bitacora = $numero_bitacora;
    }

    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFechaFin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    public function setFechaEntrega($fecha_entrega) {
        $this->fecha_entrega = $fecha_entrega;
    }

    public function setIdaltep($idaltep) {
        $this->idaltep = $idaltep;
    }

    public function setIdsubaltep($idsubaltep){
        $this->idsubaltep = $idsubaltep;
    }
    public function setFirmaAprendiz($firma_aprendiz) {
        $this->firma_aprendiz = $firma_aprendiz;
    }

    public function setFirmaJefe($firma_jefe) {
        $this->firma_jefe = $firma_jefe;
    }

    public function setFirmaInstructor($firma_instructor) {
        $this->firma_instructor = $firma_instructor;
    }
    public function setArchivoEvidencia($archivo_evidencia) {
        $this->archivo_evidencia = $archivo_evidencia;
    }
    public function setLinkEvidencia($link_evidencia) {
        $this->link_evidencia = $link_evidencia;
    }
    public function setNvlarl($nvlarl) {
        $this->nvlarl = $nvlarl;
    }
    // Método para guardar una nueva bitácora
    public function save($dt = 1) {
        try {
            $sql = "INSERT INTO bitacora (
                idaprendiz, idjefe, idinstructor, nombre_empresa, nit,
                numero_bitacora, fecha_inicio, fecha_fin, fecha_entrega,
                idaltep, idsubaltep, archivo_evidencia, link_evidencia, nvlarl
            ) VALUES (
                :idaprendiz, :idjefe, :idinstructor, :nombre_empresa, :nit,
                :numero_bitacora, :fecha_inicio, :fecha_fin, :fecha_entrega,
                :idaltep, :idsubaltep, :archivo_evidencia, :link_evidencia, :nvlarl
            )";
            $modelo = new conexion(); 
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            
            $idaprendiz = $this->getIdaprendiz();
            $result->bindParam(":idaprendiz", $this->idaprendiz);
            
            $idjefe = $this->getIdjefe();
            $result->bindParam(":idjefe", $this->idjefe);
            
            $idinstructor = $this->getIdinstructor();
            $result->bindParam(":idinstructor", $this->idinstructor);
            
            $nombre_empresa = $this->getNombreEmpresa();
            $result->bindParam(":nombre_empresa", $this->nombre_empresa);
            
            $nit = $this->getNit();
            $result->bindParam(":nit", $this->nit);
            
            $numero_bitacora = $this->getNumeroBitacora();
            $result->bindParam(":numero_bitacora", $this->numero_bitacora);
            
            $fecha_inicio = $this->getFechaInicio();
            $result->bindParam(":fecha_inicio", $fecha_inicio);

            $fecha_fin = $this->getFechaFin();
            $result->bindParam(":fecha_fin", $fecha_fin);

            $fecha_entrega = $this->getFechaEntrega();
            $result->bindParam(":fecha_entrega", $fecha_entrega);
            
            $idaltep = $this->getIdaltep();
            $result->bindParam(":idaltep", $this->idaltep);

            $idsubaltep = $this->getIdsubaltep();
            $result->bindParam(":idsubaltep", $this->idsubaltep);

            $archivo_evidencia = $this->getArchivoEvidencia();
            $result->bindParam(":archivo_evidencia", $archivo_evidencia);
            
            $link_evidencia = $this->getLinkEvidencia();
            $result->bindParam(":link_evidencia", $link_evidencia);

            $nvlarl = $this->getNvlarl();
            $result->bindParam(":nvlarl", $nvlarl);

            $result->execute();
            return true; 
        } catch (Exception $e) {
            if ($dt == 1) ManejoError($e); 
            echo "Error al guardar: " . $e->getMessage();
            return false;
        }
    }

    // Método para editar una bitácora
    function edit (){
        try {
            $sql = "UPDATE bitacora SET nombre_empresa= :nombre_empresa, nit = :nit, numero_bitacora = :numero_bitacora, fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, idaltep = :idaltep, idsubaltep = :idsubaltep, nvlarl = :nvlarl WHERE idbitacora = :idbitacora";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idbitacora = $this->getIdbitacora();
            $result->bindParam(":idbitacora", $idbitacora);
            $nombre_empresa = $this->getNombreEmpresa();
            $result->bindParam(":nombre_empresa", $nombre_empresa);
            $nit = $this->getNit();
            $result->bindParam(":nit", $nit);
            $numero_bitacora = $this->getNumeroBitacora();
            $result->bindParam(":numero_bitacora", $numero_bitacora);
            $fecha_inicio = $this->getFechaInicio();
            $result->bindParam(":fecha_inicio", $fecha_inicio);
            $fecha_fin = $this->getFechaFin();
            $result->bindParam(":fecha_fin", $fecha_fin);
            $idaltep = $this->getIdaltep();
            $result->bindParam(":idaltep", $idaltep);
            $idsubaltep = $this->getIdsubaltep();
            $result->bindParam(":idsubaltep", $idsubaltep);
            $nvlarl = $this->getNvlarl();
            $result->bindParam(":nvlarl", $nvlarl);
            $result->execute();
        }catch (Exception $e) {
            echo "Error al editar: " . $e->getMessage();
        }
            
    }

    /*public function editBitacora($idbitacora, $dt = 1) {
        try {
            $sql = "UPDATE bitacora SET nombre_empresa= :nombre_empresa, nit = :nit, numero_bitacora = :numero_bitacora, fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, idaltep = :idaltep, firma_aprendiz = :firma_aprendiz
                WHERE idbitacora = :idbitacora";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);

            $idbitacora = $this->getIdbitacora();
            $result->bindParam(":idbitacora",$idbitacora);
            $nombre_empresa = $this->getNombreEmpresa();
            $result->bindParam(":nombre_empresa", $nombre_empresa);
            $nit = $this->getNit();
            $result->bindParam(":nit", $nit);
            $numero_bitacora = $this->getNumeroBitacora();
            $result->bindParam(":numero_bitacora", $numero_bitacora);
            $fecha_inicio = $this->getFechaInicio();
            $result->bindParam(":fecha_inicio", $fecha_inicio);
            $fecha_fin = $this->getFechaFin();
            $result->bindParam(":fecha_fin", $fecha_fin);
            $idaltep = $this->getIdaltep();
            $result->bindParam(":idaltep", $idaltep);
            $firma_aprendiz = $this->getFirmaAprendiz();
            $result->bindParam(":firma_aprendiz", $firma_aprendiz);
            $result->execute();
        } catch (Exception $e) {
            echo "Error al editar: " . $e;
        }
    }*/

    //metodo para editar una bitácora

    /*public function edi() {
        try {
            $sql = "UPDATE bitacora SET nombre_empresa= :nombre_empresa, nit = :nit, numero_bitacora = :numero_bitacora, fecha_inicio = :fecha_inicio, 
                fecha_fin = :fecha_fin, idaltep = :idaltep, firma_aprendiz = :firma_aprendiz
                WHERE idbitacora = :idbitacora";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);

            $idbitacora = $this->getIdbitacora();
            $result->bindParam(":idbitacora",$idbitacora);
            $nombre_empresa = $this->getNombreEmpresa();
            $result->bindParam(":nombre_empresa", $nombre_empresa);
            $nit = $this->getNit();
            $result->bindParam(":nit", $nit);
            $numero_bitacora = $this->getNumeroBitacora();
            $result->bindParam(":numero_bitacora", $numero_bitacora);
            $fecha_inicio = $this->getFechaInicio();
            $result->bindParam(":fecha_inicio", $fecha_inicio);
            $fecha_fin = $this->getFechaFin();
            $result->bindParam(":fecha_fin", $fecha_fin);
            $idaltep = $this->getIdaltep();
            $result->bindParam(":idaltep", $idaltep);
            $firma_aprendiz = $this->getFirmaAprendiz();
            $result->bindParam(":firma_aprendiz", $firma_aprendiz);
            $result->execute();
        } catch (Exception $e) {
            echo "Error al editar: " . $e;
        }
    }*/

    // Método para actualizar una actividad en la bitácora
    public function updateActividad($datos) {
        $sql = "UPDATE actividades SET 
                    descripcion_actividad = :desc,
                    fecha_inicio_act = :fi,
                    fecha_fin_act = :ff,
                    evidencia_cumplimiento = :ev
                WHERE idactividad = :idactividad";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);

        $result->bindValue(':desc', $datos['descripcion_actividad']);
        $result->bindValue(':fi', $datos['fecha_inicio_act']);
        $result->bindValue(':ff', $datos['fecha_fin_act']);
        $result->bindValue(':ev', $datos['evidencia_cumplimiento']);
        $result->bindValue(':idactividad', $datos['idactividad']);

        $result->execute();
    }

    // Método para guardar una actividad en la bitácora
    public function saveActEp($data, $dt = 1) {
        try {

            $sql = "INSERT INTO actividades (
                idbitacora, descripcion_actividad, fecha_inicio_act, fecha_fin_act,
                evidencia_cumplimiento, archivo_evidencia
            ) VALUES (
                :idbitacora, :descripcion_actividad, :fecha_inicio_act, :fecha_fin_act,
                :evidencia_cumplimiento, :archivo_evidencia
            )";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();

            $result = $conexion->prepare($sql);
            $result->bindValue(':idbitacora', $data['idbitacora']);
            $result->bindValue(':descripcion_actividad', $data['descripcion_actividad']);
            $result->bindValue(':fecha_inicio_act', isset($data['fecha_inicio_act']) ? $data['fecha_inicio_act'] : null);
            $result->bindValue(':fecha_fin_act', isset($data['fecha_fin_act']) ? $data['fecha_fin_act'] : null);
            $result->bindValue(':evidencia_cumplimiento', $data['evidencia_cumplimiento']);
            $result->bindValue(':archivo_evidencia', isset($data['archivo_evidencia']) ? $data['archivo_evidencia'] : null);

            $result->execute();

            return true; 
        } catch (Exception $e) {
            if ($dt == 1) {
                ManejoError($e); 
            }
            echo "Error al guardar: " . $e->getMessage();
            return false;
        }
    }
    // Método para eliminar una bitácora
    public function delete($idbitacora, $dt = 1) {
        try {
            $sql = "DELETE FROM bitacora WHERE idbitacora = :idbitacora";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idbitacora", $idbitacora);
            $result->execute();
            return true; 
        } catch (Exception $e) {
            if ($dt == 1) ManejoError($e); 
            echo "Error al eliminar: " . $e->getMessage();
            return false;
        }
    }
    // Método para obtener una bitácora por su ID
    public function getOne($idbitacora) {
    $sql = "SELECT nombre_empresa, nit, numero_bitacora, fecha_inicio, fecha_fin, firma_aprendiz, idaltep, idsubaltep, nvlarl
            FROM bitacora 
            WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idbitacora', $idbitacora);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve solo un array asociativo
    }
    // Método para obtener todas las bitácoras de un usuario
    public function getBitacorasByUsuario($idusu) {
        $sql = "SELECT 
                    b.*, 
                    u_jefe.nomusu AS nombre_jefe, 
                    u_instructor.nomusu AS nombre_instructor,
                    u_instructor.emausu AS email_instructor
                FROM bitacora b
                LEFT JOIN usuario u_jefe 
                    ON b.idjefe = u_jefe.idusu
                LEFT JOIN usuario u_instructor 
                    ON b.idinstructor = u_instructor.idusu
                WHERE b.idaprendiz = :idusu
        ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener bitácoras por número de bitácora
   /*public function getBitacorasByNumero($idusu, $numero_bitacora) {
        $sql = "SELECT b.*, 
                    u_jefe.nomusu AS nombre_jefe, 
                    u_instructor.nomusu AS nombre_instructor
                FROM bitacora b
                LEFT JOIN usuario u_jefe ON b.idjefe = u_jefe.idusu
                LEFT JOIN usuario u_instructor ON b.idinstructor = u_instructor.idusu
                WHERE b.idaprendiz = :idusu AND b.numero_bitacora = :numero_bitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->bindParam(':numero_bitacora', $numero_bitacora);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/

    // Métodos para obtener datos de usuario
    public function getOneUsu($idusu) {
        $sql = "SELECT idusu, nomusu, ndocusu, telcan, emausu FROM usuario WHERE idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Métodos para obtener datos de ficha y programa
    public function getFichaUsuario($idusu) {
        $sql = "SELECT uf.idfic FROM usufic uf INNER JOIN usuario u ON uf.idusu = u.idusu WHERE u.idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método para obtener el nombre del programa asociado a un usuario
    public function getNombrePrograma($idusu) {
        $sql = "SELECT f.nomfic FROM ficha f INNER JOIN usufic uf ON f.idfic = uf.idfic WHERE uf.idusu = :idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método para obtener los nombres de los dominios(son alternativas)
    public function getNombresDominios() {
        $sql = "SELECT iddom, nomdom FROM dominio WHERE iddom IN (19, 20, 21, 22, 23);";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener los valores de los dominios específicos (son subalternativas)
    public function getValoresPorDominio() {
        $sql = "SELECT v.idval, v.nomval, v.iddom
            FROM valor v
            JOIN dominio d ON v.iddom = d.iddom
            WHERE v.iddom IN (19, 20, 21, 22, 23)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método para obtener los valores de un dominio específico
    public function getValoresDominios($iddom) {
        $sql = "SELECT idval AS idsubtipo, nomval AS nomsubtipo FROM valor WHERE iddom = :iddom";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':iddom', $iddom, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Métodos para obtener datos de jefe e instructor
    public function getJefe() {
        $sql = "SELECT idusu, nomusu, telcan, emausu FROM usuario WHERE idper = 34;";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método para obtener el perfil de un usuario
    public function getPerfil(){
        $sql = "SELECT nomper FROM perfil WHERE idper = 34;";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Método para obtener el instructor
    public function getInstructor($idusu) {
        $sql = "SELECT u.idusu, u.nomusu, u.telcan, u.emausu
                FROM usuario u
                WHERE u.idusu = (
                    SELECT ins.idusu
                    FROM insseg ins
                    WHERE ins.idficha = (
                        SELECT uf.idfic
                        FROM usufic uf
                        WHERE uf.idusu = :idusu
                        LIMIT 1
                    )
                    LIMIT 1
                );";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Función para obtener la última bitácora creada por un usuario
    function getUltimaBitacoraUsuario($idusu) {
        $sql = "SELECT idbitacora, fecha_fin     FROM bitacora WHERE idaprendiz = :idusu ORDER BY idbitacora DESC LIMIT 1";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Método para obtener la fecha de finalización de la ficha asociada a un usuario
    function getFechaFinFichaPorUsuario($idusu) {
        $sql = "SELECT f.ffinfic
                FROM ficha f
                INNER JOIN usufic uf ON f.idfic = uf.idfic
                WHERE uf.idusu = :idusu
                LIMIT 1";
        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener las actividades de una bitácora
    public function getActividadesByBitacora($idbitacora) {
        $sql = "SELECT * FROM actividades WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idbitacora', $idbitacora);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFechasBitacora($idbitacora) {
        $sql = "SELECT fecha_inicio, fecha_fin, estado FROM bitacora WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idbitacora', $idbitacora);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUltimoNumeroBitacora($idaprendiz) {
        $sql = "SELECT MAX(numero_bitacora) AS ultimo_numero FROM bitacora WHERE idaprendiz = :idaprendiz";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idaprendiz', $idaprendiz);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['ultimo_numero'];
    }
    // Método para actualizar el estado de una bitácora
    public function actualizarEstadoBitacora($idbitacora, $estado) {
        $sql = "UPDATE bitacora SET estado = :estado WHERE idbitacora = :idbitacora";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':idbitacora', $idbitacora);
        return $stmt->execute();
    }
    public function getActividadesByUsuario($idusu) {
        $sql = "SELECT a.*, b.numero_bitacora, b.nombre_empresa 
                FROM actividades a
                INNER JOIN bitacora b ON a.idbitacora = b.idbitacora
                WHERE b.idaprendiz = :idusu
                ORDER BY b.numero_bitacora, a.idactividad";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idusu', $idusu);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlternativaAndSubalternativa($idbitacora) {
        $sql = "
            SELECT 
                d.nomdom AS alternativa,
                v.nomval AS subalternativa
            FROM bitacora b
            LEFT JOIN dominio d ON b.idaltep = d.iddom
            LEFT JOIN valor v ON b.idsubaltep = v.idval
            WHERE b.idbitacora = :idbitacora
        ";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idbitacora', $idbitacora, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>
