<?php
class mgmat {
    // Atributos
    private $id;
    private $idusu;
    private $idpag;  
    private $idcen; 
    private $idhor;    
    private $idasp;    
    private $idfic;  
    private $codpro;
    private $fecha_creacion;
    private $fecha_actualizacion;
    private $estado;
    
    // Constructor
    public function __construct($data = null) {
        try {
            if ($data) {
                $this->cargarDatos($data);
            }
            $this->fecha_creacion = date('Y-m-d H:i:s');
            $this->estado = 'activo';
        } catch (Exception $e) {
            throw new Exception("Error en constructor: " . $e->getMessage());
        }
    }
    
    // Métodos GET
    public function getId() {
        return $this->id;
    }
    
    public function getIdusu() {
        return $this->idusu;
    }
    
    public function getIdpag() {
        return $this->idpag;
    }
    
    public function getIdcen() {
        return $this->idcen;
    }
    
    public function getIdhor() {
        return $this->idhor;
    }
    
    public function getIdasp() {
        return $this->idasp;
    }
    
    public function getIdfic() {
        return $this->idfic;
    }
    
    public function getCodpro() {
        return $this->codpro;
    }
    
    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }
    
    public function getFechaActualizacion() {
        return $this->fecha_actualizacion;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    // Métodos SET
    public function setId($id) {
        try {
            $this->id = $id;
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID: " . $e->getMessage());
        }
    }
    
    public function setIdusu($idusu) {
        try {
            $this->idusu = $idusu;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID usuario: " . $e->getMessage());
        }
    }
    
    public function setIdpag($idpag) {
        try {
            $this->idpag = $idpag;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID página: " . $e->getMessage());
        }
    }
    
    public function setIdcen($idcen) {
        try {
            $this->idcen = $idcen;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID centro: " . $e->getMessage());
        }
    }
    
    public function setIdhor($idhor) {
        try {
            $this->idhor = $idhor;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID horario: " . $e->getMessage());
        }
    }
    
    public function setIdasp($idasp) {
        try {
            $this->idasp = $idasp;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID aspirante: " . $e->getMessage());
        }
    }
    
    public function setIdfic($idfic) {
        try {
            $this->idfic = $idfic;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer ID ficha: " . $e->getMessage());
        }
    }
    
    public function setCodpro($codpro) {
        try {
            $this->codpro = $codpro;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer código proceso: " . $e->getMessage());
        }
    }
    
    public function setEstado($estado) {
        try {
            $this->estado = $estado;
            $this->actualizarFecha();
        } catch (Exception $e) {
            throw new Exception("Error al establecer estado: " . $e->getMessage());
        }
    }
    
    private function cargarDatos($data) {
        try {
            if (isset($data['id'])) $this->id = $data['id'];
            if (isset($data['idusu'])) $this->idusu = $data['idusu'];
            if (isset($data['idpag'])) $this->idpag = $data['idpag'];
            if (isset($data['idcen'])) $this->idcen = $data['idcen'];
            if (isset($data['idhor'])) $this->idhor = $data['idhor'];
            if (isset($data['idasp'])) $this->idasp = $data['idasp'];
            if (isset($data['idfic'])) $this->idfic = $data['idfic'];
            if (isset($data['codpro'])) $this->codpro = $data['codpro'];
            if (isset($data['estado'])) $this->estado = $data['estado'];
            if (isset($data['fecha_creacion'])) $this->fecha_creacion = $data['fecha_creacion'];
            if (isset($data['fecha_actualizacion'])) $this->fecha_actualizacion = $data['fecha_actualizacion'];
        } catch (Exception $e) {
            throw new Exception("Error al cargar datos: " . $e->getMessage());
        }
    }
    
    private function actualizarFecha() {
        try {
            $this->fecha_actualizacion = date('Y-m-d H:i:s');
        } catch (Exception $e) {
            throw new Exception("Error al actualizar fecha: " . $e->getMessage());
        }
    }
    
    public function validar() {
        try {
            $errores = [];
            
            if (empty($this->idusu)) {
                $errores[] = "ID de usuario es requerido";
            }
            
            if (empty($this->idpag)) {
                $errores[] = "ID de página es requerido";
            }
            
            if (empty($this->idcen)) {
                $errores[] = "ID de centro es requerido";
            }
            
            if (empty($this->codpro)) {
                $errores[] = "Código de proceso es requerido";
            }
            
            return empty($errores) ? true : $errores;
        } catch (Exception $e) {
            throw new Exception("Error en validación: " . $e->getMessage());
        }
    }
    
    public function toArray() {
        try {
            return [
                'id' => $this->id,
                'idusu' => $this->idusu,
                'idpag' => $this->idpag,
                'idcen' => $this->idcen,
                'idhor' => $this->idhor,
                'idasp' => $this->idasp,
                'idfic' => $this->idfic,
                'codpro' => $this->codpro,
                'fecha_creacion' => $this->fecha_creacion,
                'fecha_actualizacion' => $this->fecha_actualizacion,
                'estado' => $this->estado
            ];
        } catch (Exception $e) {
            throw new Exception("Error al convertir a array: " . $e->getMessage());
        }
    }
    
    public function obtenerRelaciones() {
        try {
            return [
                'usuario' => $this->obtenerUsuario(),
                'pagina' => $this->obtenerPagina(),
                'centro' => $this->obtenerCentro(),
                'horario' => $this->obtenerHorario(),
                'aspirante' => $this->obtenerAspirante(),
                'ficha' => $this->obtenerFicha(),
                'proceso' => $this->obtenerProceso()
            ];
        } catch (Exception $e) {
            throw new Exception("Error al obtener relaciones: " . $e->getMessage());
        }
    }
    
    private function obtenerUsuario() {
        try {
            return "Usuario ID: " . $this->idusu;
        } catch (Exception $e) {
            return "Error al obtener usuario: " . $e->getMessage();
        }
    }
    
    private function obtenerPagina() {
        try {
            return "Página ID: " . $this->idpag;
        } catch (Exception $e) {
            return "Error al obtener página: " . $e->getMessage();
        }
    }
    
    private function obtenerCentro() {
        try {
            return "Centro ID: " . $this->idcen;
        } catch (Exception $e) {
            return "Error al obtener centro: " . $e->getMessage();
        }
    }
    
    private function obtenerHorario() {
        try {
            return $this->idhor ? "Horario ID: " . $this->idhor : "Sin horario asignado";
        } catch (Exception $e) {
            return "Error al obtener horario: " . $e->getMessage();
        }
    }
    
    private function obtenerAspirante() {
        try {
            return $this->idasp ? "Aspirante ID: " . $this->idasp : "Sin aspirante asignado";
        } catch (Exception $e) {
            return "Error al obtener aspirante: " . $e->getMessage();
        }
    }
    
    private function obtenerFicha() {
        try {
            return $this->idfic ? "Ficha ID: " . $this->idfic : "Sin ficha asignada";
        } catch (Exception $e) {
            return "Error al obtener ficha: " . $e->getMessage();
        }
    }
    
    private function obtenerProceso() {
        try {
            return "Proceso: " . $this->codpro;
        } catch (Exception $e) {
            return "Error al obtener proceso: " . $e->getMessage();
        }
    }
    
    public static function getAll($conexion = null) {
        try {
            if (!$conexion) {
                throw new Exception("Conexión a base de datos requerida");
            }
            
            $sql = "SELECT * FROM mgmat WHERE estado = 'activo' ORDER BY fecha_creacion DESC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = new self($row);
            }
            
            return $resultados;
        } catch (PDOException $e) {
            throw new Exception("Error de base de datos en getAll(): " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error en getAll(): " . $e->getMessage());
        }
    }
    
    public static function getAllWithRelations($conexion = null) {
        try {
            if (!$conexion) {
                throw new Exception("Conexión a base de datos requerida");
            }
            
            $sql = "SELECT 
                        m.*,
                        u.nombre as usuario_nombre,
                        h.descripcion as horario_descripcion,
                        f.numero as ficha_numero,
                        p.nombre as programa_nombre,
                        a.nombre as aspirante_nombre
                    FROM mgmat m
                    LEFT JOIN Usuario u ON m.idusu = u.id
                    LEFT JOIN Horario h ON m.idhor = h.id
                    LEFT JOIN Ficha f ON m.idfic = f.id
                    LEFT JOIN Programa p ON m.codpro = p.codigo
                    LEFT JOIN Aspirante a ON m.idasp = a.id
                    WHERE m.estado = 'activo'
                    ORDER BY m.fecha_creacion DESC";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mgmat = new self($row);
                $mgmat->relaciones_completas = [
                    'usuario_nombre' => $row['usuario_nombre'],
                    'horario_descripcion' => $row['horario_descripcion'],
                    'ficha_numero' => $row['ficha_numero'],
                    'programa_nombre' => $row['programa_nombre'],
                    'aspirante_nombre' => $row['aspirante_nombre']
                ];
                $resultados[] = $mgmat;
            }
            
            return $resultados;
        } catch (PDOException $e) {
            throw new Exception("Error de base de datos en getAllWithRelations(): " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error en getAllWithRelations(): " . $e->getMessage());
        }
    }
    
    public static function buscarPor($campo, $valor, $conexion = null) {
        try {
            if (!$conexion) {
                throw new Exception("Conexión a base de datos requerida");
            }
            
            $camposPermitidos = ['idusu', 'idpag', 'idcen', 'idhor', 'idasp', 'idfic', 'codpro'];
            
            if (!in_array($campo, $camposPermitidos)) {
                throw new Exception("Campo de búsqueda no válido");
            }
            
            $sql = "SELECT * FROM mgmat WHERE $campo = :valor AND estado = 'activo'";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':valor', $valor);
            $stmt->execute();
            
            $resultados = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = new self($row);
            }
            
            return $resultados;
        } catch (PDOException $e) {
            throw new Exception("Error de base de datos en buscarPor(): " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error en buscarPor(): " . $e->getMessage());
        }
    }
    
    public function guardar($conexion = null) {
        try {
            if (!$conexion) {
                throw new Exception("Conexión a base de datos requerida");
            }
            
            $validacion = $this->validar();
            if ($validacion !== true) {
                throw new Exception("Errores de validación: " . implode(", ", $validacion));
            }
            
            if ($this->id) {
                $sql = "UPDATE mgmat SET 
                        idusu = :idusu, idpag = :idpag, idcen = :idcen, 
                        idhor = :idhor, idasp = :idasp, idfic = :idfic, 
                        codpro = :codpro, fecha_actualizacion = :fecha_actualizacion, 
                        estado = :estado 
                        WHERE id = :id";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':id', $this->id);
            } else {
                $sql = "INSERT INTO mgmat (idusu, idpag, idcen, idhor, idasp, idfic, codpro, fecha_creacion, estado) 
                        VALUES (:idusu, :idpag, :idcen, :idhor, :idasp, :idfic, :codpro, :fecha_creacion, :estado)";
                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':fecha_creacion', $this->fecha_creacion);
            }
            
            $stmt->bindParam(':idusu', $this->idusu);
            $stmt->bindParam(':idpag', $this->idpag);
            $stmt->bindParam(':idcen', $this->idcen);
            $stmt->bindParam(':idhor', $this->idhor);
            $stmt->bindParam(':idasp', $this->idasp);
            $stmt->bindParam(':idfic', $this->idfic);
            $stmt->bindParam(':codpro', $this->codpro);
            $stmt->bindParam(':estado', $this->estado);
            
            if ($this->id) {
                $stmt->bindParam(':fecha_actualizacion', $this->fecha_actualizacion);
            }
            
            $resultado = $stmt->execute();
            
            if (!$this->id) {
                $this->id = $conexion->lastInsertId();
            }
            
            return $resultado;
        } catch (PDOException $e) {
            throw new Exception("Error de base de datos al guardar: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error al guardar: " . $e->getMessage());
        }
    }
    
    public static function crearRelacion($idusu, $idpag, $idcen, $codpro, $opcionales = []) {
        try {
            $modelo = new self();
            $modelo->setIdusu($idusu);
            $modelo->setIdpag($idpag);
            $modelo->setIdcen($idcen);
            $modelo->setCodpro($codpro);

            if (isset($opcionales['idhor'])) $modelo->setIdhor($opcionales['idhor']);
            if (isset($opcionales['idasp'])) $modelo->setIdasp($opcionales['idasp']);
            if (isset($opcionales['idfic'])) $modelo->setIdfic($opcionales['idfic']);
            
            return $modelo;
        } catch (Exception $e) {
            throw new Exception("Error al crear relación: " . $e->getMessage());
        }
    }

    public function __toString() {
        try {
            return "Relación [ID: {$this->id}, Usuario: {$this->idusu}, Página: {$this->idpag}, Centro: {$this->idcen}, Proceso: {$this->codpro}]";
        } catch (Exception $e) {
            return "Error al convertir a string: " . $e->getMessage();
        }
    }
}
?>