<?php

class Mrphor
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
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne()
    {
        $res = null;
        $sql = "SELECT h.idhor, f.idfic, f.nomfic AS nf, f.jornada, 
		       COALESCE(a.idaul, 0) AS ai, 
		       COALESCE(ub.codubi, '') AS codubi, 
		       COALESCE(ub.nomubi, 'Sin ubicación') AS nomubi, 
		       COALESCE(a.nomaul, 'Sin aula') AS an, 
		       u.idusu AS ui, u.nomusu AS un, h.iddia,
		       h.es_otros, h.actividad, h.horas_otros
		FROM horario AS h
		INNER JOIN ficha AS f ON h.idfic=f.idfic
		LEFT JOIN aula AS a ON h.idaul=a.idaul
		LEFT JOIN ubica AS ub ON a.codubi=ub.codubi
		INNER JOIN usuario AS u ON h.idusu=u.idusu
		WHERE u.idusu=:idusu";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idusu = $this->getIdusu();

        $result->bindParam(":idusu", $idusu);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
}
