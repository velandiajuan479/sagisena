<?php 
class Mprg{
    //atributos
    private $codpro;
    private $nompro;
    private $despro;
    private $verpro;
    private $horlpro;
    private $horppro;
    private $crelpro;
    private $creppro;
    private $tippro;
    private $just;
    private $redcon;
    private $reqing;
    private $reqcer;
    private $idare;
    //metodos get
    public function getCodpro(){
        return $this->codpro;
    }
    public function getNompro(){
        return $this->nompro;
    }
    public function getDespro(){
        return $this->despro;
    }
    public function getVerpro(){
        return $this->verpro;
    }
    public function getHorlpro(){
        return $this->horlpro;
    }
    public function getHorppro(){
        return $this->horppro;
    }
    public function getCrelpro(){
        return $this->crelpro;
    }
    public function getCreppro(){
        return $this->creppro;
    }
    public function getTippro(){
        return $this->tippro;
    }
    public function getJust(){
        return $this->just;
    }
    public function getRedcon(){
        return $this->redcon;
    }
    public function getReqing(){
        return $this->reqing;
    }
    public function getReqcer(){
        return $this->reqcer;
    }
    public function getIdare(){
        return $this->idare;
    }
    public function setCodpro($codpro){
        $this->codpro = $codpro;
    }
    public function setNompro($nompro){
        $this->nompro = $nompro;
    }
    public function setDespro($despro){
        $this->despro = $despro;
    }
    public function setVerpro($verpro){
        $this->verpro = $verpro;
    }
    public function setHorlpro($horlpro){
        $this->horlpro = $horlpro;
    }
    public function setHorppro($horppro){
        $this->horppro = $horppro;
    }
    public function setCrelpro($crelpro){
        $this->crelpro = $crelpro;
    }
    public function setCreppro($creppro){
        $this->creppro = $creppro;
    }
    public function setTippro($tippro){
        $this->tippro = $tippro;
    }
    public function setJust($just){
        $this->just = $just;
    }
    public function setRedcon($redcon){
        $this->redcon = $redcon;
    }
    public function setReqing($reqing){
        $this->reqing = $reqing;
    }
    public function setReqcer($reqcer){
        $this->reqcer = $reqcer;
    }
    public function setIdare($idare){
        $this->idare = $idare;
    }
    //metodos publicos
    public function getAll(){
        $res = NULL;
        $sql = "SELECT p.codpro, p.nompro, p.despro, p.verpro, p.horlpro, p.horppro, p.crelpro, p.creppro, p.tippro, v.nomval, p.just, p.redcon, p.reqing, p.reqcer, p.idare, a.nomare, a.idusu FROM programa AS p INNER JOIN valor AS v ON p.tippro=v.idval LEFT JOIN area AS a ON p.idare=a.idare";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res= $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getOne(){
        $res = NULL;
        $sql = "SELECT p.codpro, p.nompro, p.despro, p.verpro, p.horlpro, p.horppro, p.crelpro, p.creppro, p.tippro, v.nomval, p.just, p.redcon, p.reqing, p.reqcer, p.idare, a.nomare, a.idusu FROM programa AS p INNER JOIN valor AS v ON p.tippro=v.idval LEFT JOIN area AS a ON p.idare=a.idare WHERE codpro=:codpro";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindParam(":codpro", $codpro);
        $result->execute();
        $res= $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getArea(){
        $res = NULL;
        $sql = "SELECT idare, nomare, idusu FROM area";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res= $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
    public function getTipPr(){
        $res = NULL;
        $sql = "SELECT idval, nomval, act FROM valor WHERE iddom=16";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $result->execute();
        $res= $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
    public function save(){
        $sql = "INSERT INTO programa (codpro, nompro, despro, verpro, horlpro, horppro, crelpro, creppro, tippro, just, redcon, reqing, reqcer, idare) VALUES (:codpro, :nompro, :despro, :verpro, :horlpro, :horppro, :crelpro, :creppro, :tippro, :just, :redcon, :reqing, :reqcer, :idare)";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindParam(":codpro", $codpro);
        $nompro = $this->getNompro();
        $result->bindParam(":nompro", $nompro);
        $despro = $this->getDespro();
        $result->bindParam(":despro", $despro);
        $verpro = $this->getVerpro();
        $result->bindParam(":verpro", $verpro);
        $horlpro = $this->getHorlpro();
        $result->bindParam(":horlpro", $horlpro);
        $horppro = $this->getHorppro();
        $result->bindParam(":horppro", $horppro);
        $crelpro = $this->getCrelpro();
        $result->bindParam(":crelpro", $crelpro);
        $creppro = $this->getCreppro();
        $result->bindParam(":creppro", $creppro);
        $tippro = $this->getTippro();
        $result->bindParam(":tippro", $tippro);
        $just = $this->getJust();
        $result->bindParam(":just", $just);
        $redcon = $this->getRedcon();
        $result->bindParam(":redcon", $redcon);
        $reqing = $this->getReqing();
        $result->bindParam(":reqing", $reqing);
        $reqcer = $this->getReqcer();
        $result->bindParam(":reqcer", $reqcer);
        $idare = $this->getIdare();
        $result->bindParam(":idare", $idare);
        /*echo $sql."<br><br>'".$codpro."','".$nompro."','".$despro."','".$verpro."','".$horlpro."','".$horppro."','".$crelpro."','".$creppro."','".$tippro."','".$just."','".$redcon."','".$reqing."','".$reqcer."','".$idare."'";
        die();*/
        $result->execute();
    }
    public function edit(){
        $sql = "UPDATE programa SET nompro=:nompro, despro=:despro, verpro=:verpro, horlpro=:horlpro, horppro=:horppro, crelpro=:crelpro, creppro=:creppro, tippro=:tippro, just=:just, redcon=:redcon, reqing=:reqing, reqcer=:reqcer, idare=:idare WHERE codpro=:codpro";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindParam(":codpro", $codpro);
        $nompro = $this->getNompro();
        $result->bindParam(":nompro", $nompro);
        $despro = $this->getDespro();
        $result->bindParam(":despro", $despro);
        $verpro = $this->getVerpro();
        $result->bindParam(":verpro", $verpro);
        $horlpro = $this->getHorlpro();
        $result->bindParam(":horlpro", $horlpro);
        $horppro = $this->getHorppro();
        $result->bindParam(":horppro", $horppro);
        $crelpro = $this->getCrelpro();
        $result->bindParam(":crelpro", $crelpro);
        $creppro = $this->getCreppro();
        $result->bindParam(":creppro", $creppro);
        $tippro = $this->getTippro();
        $result->bindParam(":tippro", $tippro);
        $just = $this->getJust();
        $result->bindParam(":just", $just);
        $redcon = $this->getRedcon();
        $result->bindParam(":redcon", $redcon);
        $reqing = $this->getReqing();
        $result->bindParam(":reqing", $reqing);
        $reqcer = $this->getReqcer();
        $result->bindParam(":reqcer", $reqcer);
        $idare = $this->getIdare();
        $result->bindParam(":idare", $idare);
        $result->execute();
    }

    public function del(){
        $sql = "DELETE FROM programa WHERE codpro=:codpro";
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindParam(":codpro", $codpro);
        $result->execute();
    }
}
?>