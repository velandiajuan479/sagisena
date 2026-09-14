<?php
class Mprgmat{

    
    //SELECT codpro, nompro, despro, verpro, horlpro, horppro, crelpro, creppro, tippro, just, redcon, reqing, reqcer, idare,  FROM `programa`

    //Atributos
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

    //Metodos Get

    function getCodpro(){
        return $this->codpro;
    }

    function getNompro(){
        return $this->nompro;
    }
    function getDespro(){
        return $this->despro;
    }
    function getVerpro(){
        return $this->verpro;
    }
    function getHorlpro(){
        return $this->horlpro;
    }
    function getHorppro(){
        return $this->horppro;
    }
    function getCrelpro(){
        return $this->crelpro;
    }
    function getCreppro(){
        return $this->creppro;
    }
    function getTippro(){
        return $this->tippro;
    }
    function getJust(){
        return $this->just;
    }
    function getRedcon(){
        return $this->redcon;
    }
    function getReqing(){
        return $this->reqing;
    }
    function getReqcer(){
        return $this->reqcer;
    }
    function getIdare(){
        return $this->idare;
    }


    //Metodos Set

    function setCodpro($codpro){
        $this->codpro = $codpro;
    }

      function setNompro($nompro){
        $this->nompro =$nompro;
        }

    function setDespro($despro){
        $this->despro =$despro;
    }
    function setVerpro($verpro){
        $this->verpro =$verpro;
    }
    function setHorlpro($horlpro){
        $this->horlpro =$horlpro;
    }
    function setHorppro($horppro){
        $this->horppro =$horppro;
    }
    function setCrelpro($crelpro){
        $this->crelpro =$crelpro;
    }
    function setCreppro($creppro){
        $this->creppro =$creppro;
    }
    function setTippro($tippro){
        $this->tippro =$tippro;
    }
    function setJust($just){
        $this->just =$just;
    }
    function setRedcon($redcon){
        $this->redcon =$redcon;
    }
    function setReqing($reqing){
        $this->reqing =$reqing;
    }
    function setReqcer($reqcer){
        $this->reqcer =$reqcer;
    }
    function setIdare($idare){
        $this->idare =$idare;
    }


    //Metodos

  public function getALL(){
    $sql = "SELECT p.codpro , p.nompro , despro ,v.idval, v.nomval, v.act, a.idare , a.nomare, p.verpro , p.horlpro , p.horppro , p.crelpro , p.creppro, 
                   p.tippro , p.just , p.redcon , p.reqing , p.reqcer , p.idare FROM programa AS p
                   INNER JOIN valor AS v ON tippro=idval INNER JOIN area AS a ON p.idare=a.idare";  

                  // v.idval , v.nomval , v.iddom , v.parval , v.act , v.nhora , v.novam FROM valor AS v
                  //a.idare , a.nomare , a.idusu FROM area AS a ON p.idare=a.idare";

    $modelo = new Conexion();
    $conexion = $modelo->get_conexion();
    $result = $conexion->prepare($sql);
    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}


        public function getOne(){
        $sql = "SELECT codpro, nompro, despro, verpro, horlpro, horppro, crelpro, creppro, tippro, just, redcon, reqing, reqcer, idare FROM programa WHERE codpro = :codpro";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindparam(':codpro', $codpro);
        $result->execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;

        
   }

   public function save(){
        try{
        $sql = "INSERT INTO programa (codpro, nompro, despro, verpro, horlpro, horppro, crelpro, creppro, tippro, just, redcon, reqing, reqcer, idare) VALUES (:codpro, :nompro, :despro, :verpro, :horlpro, :horppro, :crelpro, :creppro, :tippro, :just, :redcon, :reqing, :reqcer, :idare) ";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);

        $codpro = $this->getCodpro();
		$result->bindParam(':codpro',$codpro);

        $nompro = $this->getNompro();
        $result->bindparam(':nompro', $nompro);

        $despro = $this->getDespro();
        $result->bindparam(':despro', $despro);
        

        $verpro = $this->getVerpro();
        $result->bindparam(':verpro', $verpro);

        $horlpro = $this->getHorlpro();
        $result->bindparam(':horlpro', $horlpro);

        $horppro = $this->getHorppro();
        $result->bindparam(':horppro', $horppro);

        $crelpro = $this->getCrelpro();
        $result->bindparam(':crelpro', $crelpro);

        $creppro = $this->getCreppro();
        $result->bindparam(':creppro', $creppro);

        $tippro = $this->getTippro();
        $result->bindparam(':tippro', $tippro);

        $just = $this->getJust();
        $result->bindparam(':just', $just);

        $redcon = $this->getRedcon();
        $result->bindparam(':redcon', $redcon);

        $reqing = $this->getReqing();
        $result->bindparam(':reqing', $reqing);

        $reqcer = $this->getReqcer();
        $result->bindparam(':reqcer', $reqcer);

        $idare = $this->getIdare();
        $result->bindparam(':idare', $idare);
      
        $result->execute();
         }catch(Exception $e){
			ManejoError($e);
		}
    
    }


    public function edit(){

        try{
        $sql = "UPDATE programa SET 
                nompro = :nompro, despro = :despro, verpro = :verpro, 
                horlpro = :horlpro, horppro = :horppro, crelpro = :crelpro, 
                creppro = :creppro, tippro = :tippro,  just = :just,  redcon = :redcon,  reqing = :reqing,  reqcer = :reqcer, idare = :idare 
                WHERE codpro = :codpro";        
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);

        $codpro = $this->getCodpro();
        $result->bindparam(':codpro', $codpro);


        $nompro = $this->getNompro();
        $result->bindparam(':nompro', $nompro);

        $despro = $this->getDespro();
        $result->bindparam(':despro', $despro);
        

        $verpro = $this->getVerpro();
        $result->bindparam(':verpro', $verpro);

        $horlpro = $this->getHorlpro();
        $result->bindparam(':horlpro', $horlpro);

        $horppro = $this->getHorppro();
        $result->bindparam(':horppro', $horppro);

        $crelpro = $this->getCrelpro();
        $result->bindparam(':crelpro', $crelpro);

        $creppro = $this->getCreppro();
        $result->bindparam(':creppro', $creppro);

        $tippro = $this->getTippro();
        $result->bindparam(':tippro', $tippro);

        $just = $this->getJust();
        $result->bindparam(':just', $just);

        $redcon = $this->getRedcon();
        $result->bindparam(':redcon', $redcon);

        $reqing = $this->getReqing();
        $result->bindparam(':reqing', $reqing);

        $reqcer = $this->getReqcer();
        $result->bindparam(':reqcer', $reqcer);

        $idare = $this->getIdare();
        $result->bindparam(':idare', $idare);
      
        $result->execute();

    }catch(Exception $e){
			ManejoError($e);
		}
}

   public function del(){
        try{
        $sql = "DELETE FROM programa WHERE codpro = :codpro";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $codpro = $this->getCodpro();
        $result->bindparam(':codpro', $codpro);
        $result->execute();
         }catch(Exception $e){
			ManejoError($e);
		}

        
   }


    public function getAllpro(){
		try{
			$sql = "SELECT idval, nomval FROM valor WHERE iddom=16 AND act=1;";
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

    public function getAllAre(){
		try{
			$sql = "SELECT idare, nomare FROM area";
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
}


?>