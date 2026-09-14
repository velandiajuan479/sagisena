<?php

class Mrphau
{
    //atributosidhor
    private $idhor;
    private $idfic;
    private $idaul;
    private $idusu;
    private $iddia;

    private $jornada;

    //metodos get
    public function getIdhor()
    {
        return $this->idhor;
    }
    public function getIdfic()
    {
        return $this->idfic;
    }
    public function getIdaul()
    {
        return $this->idaul;
    }
    public function getIdusu()
    {
        return $this->idusu;
    }
    public function getIddia()
    {
        return $this->iddia;
    }
    public function getJornada()
    {
        return $this->jornada;
    }
    //metodos SET
    public function setIdhor($idhor)
    {
        $this->idhor = $idhor;
    }
    public function setIdfic($idfic)
    {
        $this->idfic = $idfic;
    }
    public function setIdaul($idaul)
    {
        $this->idaul = $idaul;
    }
    public function setIdusu($idusu)
    {
        $this->idusu = $idusu;
    }
    public function setIddia($iddia)
    {
        $this->iddia = $iddia;
    }
    public function setJornada($jornada)
    {
        $this->jornada = $jornada;
    }
    //metodos publicos
    public function getAll()
    {
        $res = null;
        $sql = "SELECT h.idhor, f.idfic, f.jornada, a.idaul AS ai, u.idusu AS ui, u.nomusu AS un, h.iddia
		FROM horario AS h
		INNER JOIN ficha AS f ON h.idfic=f.idfic
		INNER JOIN aula AS a ON h.idaul=a.idaul
		INNER JOIN usuario AS u ON h.idusu=u.idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getAllaul()
    {
        $res = null;
        $sql = "SELECT * FROM aula";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne()
    {
        $res = null;
        $sql = "SELECT h.idhor, f.idfic, f.nomfic AS nf, f.jornada, a.idaul AS ai, ub.codubi, ub.nomubi, u.idusu AS ui, u.nomusu AS un, h.iddia,
		       h.es_otros, h.actividad, h.horas_otros
		FROM horario AS h
		INNER JOIN ficha AS f ON h.idfic=f.idfic
		INNER JOIN aula AS a ON h.idaul=a.idaul
		INNER JOIN ubica AS ub ON a.codubi=ub.codubi
		INNER JOIN usuario AS u ON h.idusu=u.idusu
		WHERE h.idaul=:idaul";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idaul = $this->getIdaul();
        $result->bindParam(":idaul", $idaul);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Obtener horarios de un aula dentro de un rango de fechas (trimestre actual)
     */
    public function getHorariosAulaEnRango($idaul, $fechaInicio, $fechaFin)
    {
        try {
            $sql = "SELECT 
                        h.iddia,
                        f.idfic,
                        f.nomfic AS nf,
                        f.jornada,
                        u.nomusu AS un,
                        h.es_otros,
                        h.actividad,
                        h.horas_otros,
                        h.fecha_especifica,
                        h.fecha_inicio,
                        h.fecha_fin
                    FROM horario h
                    INNER JOIN ficha f ON h.idfic = f.idfic
                    LEFT JOIN usuario u ON h.idusu = u.idusu
                    WHERE h.idaul = :idaul
                      AND (
                            (h.fecha_especifica IS NOT NULL AND h.fecha_especifica BETWEEN :ini AND :fin)
                         OR (
                            h.fecha_especifica IS NULL 
                            AND h.fecha_inicio IS NOT NULL AND h.fecha_fin IS NOT NULL
                            AND h.fecha_inicio <= :fin AND h.fecha_fin >= :ini
                         )
                      )
                    ORDER BY h.iddia ASC, h.fecha_especifica ASC";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idaul", $idaul);
            $result->bindParam(":ini", $fechaInicio);
            $result->bindParam(":fin", $fechaFin);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Mrphau::getHorariosAulaEnRango error: ' . $e->getMessage());
            return [];
        }
    }
}
