<?php
class Muasp{
    //Atributos
    private $idasp;
    private $idusu;
    private $nomusu;
    private $dnorad;
    private $idper;
    private $pasusu;
    private $emausu;
    private $idcen;
    private $actusu;
    private $fotcan;
    private $telcan;
    private $noca;
    private $colfon;
    private $coltex;
    private $fecsol;
    private $keyolv;
    private $bloqkey;

    // Getters y Setters (igual que antes)
    // ...existing code...

    // Métodos
    public function getAll(){
        $sql = "SELECT 
            u.idasp, u.idusu, u.nomusu, u.dnorad, u.idper, u.pasusu, u.emausu, u.idcen, u.actusu, u.fotcan, u.telcan, u.noca, u.colfon, u.coltex, u.fecsol, u.keyolv, u.bloqkey, p.nomval AS perfil, c.nomcen AS centro
        FROM usuario AS u
        INNER JOIN valor AS p ON u.idper = p.idval
        INNER JOIN centro AS c ON u.idcen = c.idcen";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getOne(){
        $sql = "SELECT 
            u.idasp, u.idusu, u.nomusu, u.dnorad, u.idper, u.pasusu, u.emausu, u.idcen, u.actusu, u.fotcan, u.telcan, u.noca, u.colfon, u.coltex, u.fecsol, u.keyolv, u.bloqkey, p.nomval AS perfil, c.nomcen AS centro
        FROM usuario AS u
        INNER JOIN valor AS p ON u.idper = p.idval
        INNER JOIN centro AS c ON u.idcen = c.idcen
        WHERE u.idasp = :idasp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idasp = $this->getIdasp();
        $result->bindParam(':idasp', $idasp);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function save(){
        $sql = "INSERT INTO usuario (idasp, idusu, nomusu, dnorad, idper, pasusu, emausu, idcen, actusu, fotcan, telcan, noca, colfon, coltex, fecsol, keyolv, bloqkey)
        VALUES (:idasp, :idusu, :nomusu, :dnorad, :idper, :pasusu, :emausu, :idcen, :actusu, :fotcan, :telcan, :noca, :colfon, :coltex, :fecsol, :keyolv, :bloqkey)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idasp', $this->idasp);
        $result->bindParam(':idusu', $this->idusu);
        $result->bindParam(':nomusu', $this->nomusu);
        $result->bindParam(':dnorad', $this->dnorad);
        $result->bindParam(':idper', $this->idper);
        $result->bindParam(':pasusu', $this->pasusu);
        $result->bindParam(':emausu', $this->emausu);
        $result->bindParam(':idcen', $this->idcen);
        $result->bindParam(':actusu', $this->actusu);
        $result->bindParam(':fotcan', $this->fotcan);
        $result->bindParam(':telcan', $this->telcan);
        $result->bindParam(':noca', $this->noca);
        $result->bindParam(':colfon', $this->colfon);
        $result->bindParam(':coltex', $this->coltex);
        $result->bindParam(':fecsol', $this->fecsol);
        $result->bindParam(':keyolv', $this->keyolv);
        $result->bindParam(':bloqkey', $this->bloqkey);
        $result->execute();
    }

    public function edit(){
        $sql = "UPDATE usuario SET 
            idusu=:idusu, nomusu=:nomusu, dnorad=:dnorad, idper=:idper, pasusu=:pasusu, emausu=:emausu, idcen=:idcen, actusu=:actusu, fotcan=:fotcan, telcan=:telcan, noca=:noca, colfon=:colfon, coltex=:coltex, fecsol=:fecsol, keyolv=:keyolv, bloqkey=:bloqkey
            WHERE idasp=:idasp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->bindParam(':idasp', $this->idasp);
        $result->bindParam(':idusu', $this->idusu);
        $result->bindParam(':nomusu', $this->nomusu);
        $result->bindParam(':dnorad', $this->dnorad);
        $result->bindParam(':idper', $this->idper);
        $result->bindParam(':pasusu', $this->pasusu);
        $result->bindParam(':emausu', $this->emausu);
        $result->bindParam(':idcen', $this->idcen);
        $result->bindParam(':actusu', $this->actusu);
        $result->bindParam(':fotcan', $this->fotcan);
        $result->bindParam(':telcan', $this->telcan);
        $result->bindParam(':noca', $this->noca);
        $result->bindParam(':colfon', $this->colfon);
        $result->bindParam(':coltex', $this->coltex);
        $result->bindParam(':fecsol', $this->fecsol);
        $result->bindParam(':keyolv', $this->keyolv);
        $result->bindParam(':bloqkey', $this->bloqkey);
        $result->execute();
    }

    public function del(){
        $sql = "DELETE FROM usuario WHERE idasp=:idasp";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idasp = $this->getIdasp();
        $result->bindParam(':idasp', $idasp);
        $result->execute();
    }
}
?>
