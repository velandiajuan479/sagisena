<?php
class Mcmas{
    private $titpp;
    private $fheprf;
    private $nompro;
    private $mdeje; //presencial
    private $codpro;
    private $nompy;
    private $codpy;
    private $nomusu;
    private $fspy;
    private $nomact;
    private $descom;
    private $nomres;
    private $acappy;
    private $duract;
    private $hrtrd;
    private $hrtri;
    private $esdiac;
    private $amapti;
    private $nomaul;
    private $mtfr;
    private $insrs; //instructor responsable ambiente
    private $creva;
    private $dsevap;
    private $obs;

    //nuevas columnas

    private $colO;
    private $colP;
    private $colQ;
    private $colR;
    private $colS;
    private $colT;
    private $colU;
    private $colV;
    private $colW;
    private $colX;



    function getTitpp(){
        return $this->titpp;
    }
    function getFheprf(){
        return $this->fheprf;
    }
    function getNompro(){
        return $this->nompro;
    }
    function getMdeje(){
        return $this->mdeje;
    }
    function getCodpro(){
        return $this->codpro;
    }
    function getNompy(){
        return $this->nompy;
    }
    function getCodpy(){
        return $this->codpy;
    }
    function getIdusu(){
        return $this->idusu;
    }
    function getFspy(){
        return $this->fspy;
    }
    function getNomact(){
        return $this->nomact;
    }
    function getDescom(){
        return $this->descom;
    }
    function getNomres(){
        return $this->nomres;
    }
    function getAcappy(){
        return $this->acappy;
    }
    function getDuract(){
        return $this->duract;
    }
    function getEsdiac(){
        return $this->esdiac;
    }
    function getAmapti(){
        return $this->amapti;
    }
    function getNomaul(){
        return $this->nomaul;
    }
    function getMtfr(){
        return $this->mtfr;
    }
    function getInsrs(){
        return $this->insrs;
    }
    function getCreva(){
        return $this->creva;
    }
    function getDsevap(){
        return $this->dsevap;
    }
    function getObs(){
        return $this->obs;
    }

    function setTitpp($titpp){
        $this->titpp = $titpp;
    }
    function setFheprf($fheprf){
        $this->fheprf = $fheprf;
    }
    function setNompro($nompro){
        $this->nompro = $nompro;
    }
    function setMdeje($mdeje){
        $this->mdeje = $mdeje;
    }
    function setCodpro($codpro){
        $this->codpro = $codpro;
    }
    function setNompy($nompy){
        $this->nompy = $nompy;
    }
    function setCodpy($codpy){
        $this->codpy = $codpy;
    }
    function setIdusu($idusu){
        $this->idusu = $idusu;
    }
    function setFspy($fspy){
        $this->fspy = $fspy;
    }
    function setNomact($nomact){
        $this->nomact = $nomact;
    }
    function setDescom($descom){
        $this->descom = $descom;
    }
    function setNomres($nomres){
        $this->nomres = $nomres;
    }
    function setAcappy($acappy){
        $this->acappy = $acappy;
    }
    function setDuract($duract){
        $this->duract = $duract;
    }
    function setEsdiac($esdiac){
        $this->esdiac = $esdiac;
    }
    function setAmapti($amapti){
        $this->amapti = $amapti;
    }
    function setNomaul($nomaul){
        $this->nomaul = $nomaul;
    }
    function setMtfr($mtfr){
        $this->mtfr = $mtfr;
    }
    function setInsrs($insrs){
        $this->insrs = $insrs;
    }
    function setCreva($creva){
        $this->creva = $creva;
    }
    function setDsevap($dsevap){
        $this->dsevap = $dsevap;
    }
    function setObs($obs){
        $this->obs = $obs;
    }

    //nuevas funciones para las columnas 
    function getColO(){
        $this->colO;
    }
    function getColP(){
        $this->colP;
    }
    function getColQ(){
        $this->colQ;
    }
    function getColR(){
        $this->colR;
    }
    function getColS(){
        $this->colS;
    }
    function getColT(){
        $this->colT;
    }
    function getColU(){
        $this->colU;
    }
    function getColV(){
        $this->colV;
    }
    function getColW(){
        $this->colW;
    }
    function getColX(){
        $this->colX;
    }

    function setColO($colO){
        $this->colO = $colO;
    }
    function setColP($colP){
        $this->colP = $colP;
    }
    function setColQ($colQ){
        $this->colQ = $colQ;
    }
    function setColR($colR){
        $this->colR = $colR;
    }
    function setColS($colS){
        $this->colS = $colS;
    }
    function setColT($colT){
        $this->colT = $colT;
    }
    function setColU($colU){
        $this->colU = $colU;
    }
    function setColV($colV){
        $this->colV = $colV;
    }
    function setColW($colW){
        $this->colW = $colW;
    }
    function setColX($colX){
        $this->colX = $colX;
    }

    public function getAll(){
        try{
            $sql = "SELECT * FROM programa";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchall(PDO::FETCH_ASSOC);
            return $res;
        }catch(Excention $e){
            ManError($e);
        }
    }
 

    public function savePrcm(){
        try{
            $sql = "INSERT INTO programa(codpro, nompro) VALUES(:codpro, :nompro)";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $codpro = $this->getCodpro();
            $result->bindParam(":codpro", $codpro);
            $nompro = $this->getNompro();
            $result->bindParam(":nompro", $nompro);
            $result->execute();
        }catch(Exception $e){
            ManejoError($e);
        }
    }
    public function saveplane(){
    try{
        $sql = "INSERT INTO programa (
            titpp, fheprf, nompro, mdeje, codpro, nompy, codpy, idusu, fspy, 
            nomact, descom, nomres, acappy, duract, hrtrd, hrtri, esdiac, amapti, 
            nomaul, mtfr, insrs, creva, dsevap, obs
        ) VALUES (
            :titpp, :fheprf, :nompro, :mdeje, :codpro, :nompy, :codpy, :idusu, :fspy, 
            :nomact, :descom, :nomres, :acappy, :duract, :hrtrd, :hrtri, :esdiac, :amapti, 
            :nomaul, :mtfr, :insrs, :creva, :dsevap, :obs
        )";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);

        $result->bindParam(":titpp", $titpp);
        $result->bindParam(":fheprf", $fheprf);
        $result->bindParam(":nompro", $nompro);
        $result->bindParam(":mdeje", $mdeje);
        $result->bindParam(":codpro", $codpro);
        $result->bindParam(":nompy", $nompy);
        $result->bindParam(":codpy", $codpy);
        $result->bindParam(":idusu", $idusu);
        $result->bindParam(":fspy", $fspy);

        $result->bindParam(":nomact", $nomact);
        $result->bindParam(":descom", $descom);
        $result->bindParam(":nomres", $nomres);
        $result->bindParam(":acappy", $acappy);
        $result->bindParam(":duract", $duract);
        $result->bindParam(":hrtrd", $hrtrd);
        $result->bindParam(":hrtri", $hrtri);
        $result->bindParam(":esdiac", $esdiac);
        $result->bindParam(":amapti", $amapti);

        $result->bindParam(":nomaul", $nomaul);
        $result->bindParam(":mtfr", $mtfr);
        $result->bindParam(":insrs", $insrs);
        $result->bindParam(":creva", $creva);
        $result->bindParam(":dsevap", $dsevap);
        $result->bindParam(":obs", $obs);

        $result->bindParam(":colO", $colO);
        $result->bindParam(":colP", $colP);

        $result->execute();

    }catch(Exception $e){
        ManejoError($e);
    }
}

    public function getIdcom(){
        try{
            $sql = "SELECT idpro FROM programa WHERE codpro=:codpro and nompro=:nompro and despro=:despro and verpro=:verpro";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $codpro = $this->getCodpro();
        }catch(Exception $e){
            ManejoError($e);
        }
    }

    public function selPrcm(){
        $sql = "SELECT COUNT(*) AS sum FROM programa WHERE codpro=:codpro";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindParam(":nidele, $nidele");
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getAula(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT codpro, nompro, despro, verpro, horlpro, horppro, crelpro, creppro, tippro, just, redcon, reqing, reqcer, idare FROM programa";
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getActi(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT idact, idses, nomact, desact, duract, tipact, ordact FROM actividad";
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getResu(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT idres, nomres, idcom, ndeses FROM resultado";
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getComp(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT idcom, descom, vercom, horcom FROM competencia";
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getUsua(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT idusu, ndocusu, nomusu, idper, pasusu, emausu, idcen, actusu, fotcan, telcan, noca FROM usuario";
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getProg(){
        $res = NULL;
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT codpro, nompro, despro, verpro, horlpro, horppro, crelpro, creppro, tippro, just, redcon, reqing, reqcer, idare FROM programa";
        $result = $conexion->prepare($sql);
        $result->execute();
        $res = $result->fetchall(PDO::FETCH_ASSOC);
        return $res;
    }
   public function saveHorarios() {
    try {
        $sql = "INSERT INTO programa (
            titpp, fheprf, nompro, mdeje, codpro, nompy, codpy, idusu, fspy, 
            nomact, descom, nomres, acappy, duract, hrtrd, hrtri, esdiac, amapti, 
            nomaul, mtfr, insrs, creva, dsevap, obs, colO, colP
        ) VALUES (
            :titpp, :fheprf, :nompro, :mdeje, :codpro, :nompy, :codpy, :idusu, :fspy, 
            :nomact, :descom, :nomres, :acappy, :duract, :hrtrd, :hrtri, :esdiac, :amapti, 
            :nomaul, :mtfr, :insrs, :creva, :dsevap, :obs, :colO, :colP
        )";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);

        $result->bindParam(":titpp", $this->titpp);
        $result->bindParam(":fheprf", $this->fheprf);
        $result->bindParam(":nompro", $this->nompro);
        $result->bindParam(":mdeje", $this->mdeje);
        $result->bindParam(":codpro", $this->codpro);
        $result->bindParam(":nompy", $this->nompy);
        $result->bindParam(":codpy", $this->codpy);
        $result->bindParam(":idusu", $this->idusu);
        $result->bindParam(":fspy", $this->fspy);

        $result->bindParam(":nomact", $this->nomact);
        $result->bindParam(":descom", $this->descom);
        $result->bindParam(":nomres", $this->nomres);
        $result->bindParam(":acappy", $this->acappy);
        $result->bindParam(":duract", $this->duract);
        $result->bindParam(":hrtrd", $this->hrtrd);
        $result->bindParam(":hrtri", $this->hrtri);
        $result->bindParam(":esdiac", $this->esdiac);
        $result->bindParam(":amapti", $this->amapti);

        $result->bindParam(":nomaul", $this->nomaul);
        $result->bindParam(":mtfr", $this->mtfr);
        $result->bindParam(":insrs", $this->insrs);
        $result->bindParam(":creva", $this->creva);
        $result->bindParam(":dsevap", $this->dsevap);
        $result->bindParam(":obs", $this->obs);
        $result->bindParam(":colO", $this->colO);
        $result->bindParam(":colP", $this->colP);

        $result->execute();
    } catch (Exception $e) {
        ManejoError($e);
    }
}

       public function savePedagogia() {
    try {
        $sql = "INSERT INTO programa (
            titpp, fheprf, nompro, mdeje, codpro, nompy, codpy, idusu, fspy, 
            nomact, descom, nomres, acappy, duract, hrtrd, hrtri, esdiac, amapti, 
            nomaul, mtfr, insrs, creva, dsevap, obs
        ) VALUES (
            :titpp, :fheprf, :nompro, :mdeje, :codpro, :nompy, :codpy, :idusu, :fspy, 
            :nomact, :descom, :nomres, :acappy, :duract, :hrtrd, :hrtri, :esdiac, :amapti, 
            :nomaul, :mtfr, :insrs, :creva, :dsevap, :obs
        )";

        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);

        $result->bindParam(":titpp", $this->titpp);
        $result->bindParam(":fheprf", $this->fheprf);
        $result->bindParam(":nompro", $this->nompro);
        $result->bindParam(":mdeje", $this->mdeje);
        $result->bindParam(":codpro", $this->codpro);
        $result->bindParam(":nompy", $this->nompy);
        $result->bindParam(":codpy", $this->codpy);
        $result->bindParam(":idusu", $this->idusu);
        $result->bindParam(":fspy", $this->fspy);

        $result->bindParam(":nomact", $this->nomact);
        $result->bindParam(":descom", $this->descom);
        $result->bindParam(":nomres", $this->nomres);
        $result->bindParam(":acappy", $this->acappy);
        $result->bindParam(":duract", $this->duract);
        $result->bindParam(":hrtrd", $this->hrtrd);
        $result->bindParam(":hrtri", $this->hrtri);
        $result->bindParam(":esdiac", $this->esdiac);
        $result->bindParam(":amapti", $this->amapti);

        $result->bindParam(":nomaul", $this->nomaul);
        $result->bindParam(":mtfr", $this->mtfr);
        $result->bindParam(":insrs", $this->insrs);
        $result->bindParam(":creva", $this->creva);
        $result->bindParam(":dsevap", $this->dsevap);
        $result->bindParam(":obs", $this->obs);

        $result->execute();
    } catch (Exception $e) {
        ManejoError($e);
    }
}




}
?>