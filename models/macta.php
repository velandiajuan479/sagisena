<?php
class Macta{
	private $idact;
	private $tipact;
	private $fecact;
	private $conact;
	private $idusu;
	private $coorusu;

	private $idacde;
	private $desacde;
	private $fecacde;
	private $idasi;
	private $fecasi;

	private $iddec;
	private $idfic;
	private $fecregdec;
	private $obsdec;

	private $tit; //Titulo de la página
	private $nac; //Número de acta
	private $ncre; //Nombre del comite o reunión
	private $cife; //Ciudad y Fecha
	private $hini; //Hora inicio
	private $hfin; //Hora fin
	private $jgen; //lugar y/o enlace
	private $drcd; //Dirección / Regional / Centro
	private $agep; //Agenda o puntos s desarrollar
	private $objr; //Objetivos de la reuníon
	private $desr; //DESARROLLO DE LA REUNIÓN
	private $conc; //CONCLUSIONES
	private $eaco; //ESTABLECIMIENTO Y ACEPTACIÓN DE COMPROMISOS
	private $aade; //ASISTENTES Y APROBACIÓN DECISIONES

	function getIdact(){
		return $this->idact;
	}
	function getTipact(){
		return $this->tipact;
	}
	function getFecact(){
		return $this->fecact;
	}
	function getConact(){
		return $this->conact;
	}
	function getIdusu(){
		return $this->idusu;
	}
	function getCoorusu(){
		return $this->coorusu;
	}

	function getIdacde(){
		return $this->idacde;
	}
	function getDesacde(){
		return $this->desacde;
	}
	function getFecacde(){
		return $this->fecacde;
	}
	function getIdasi(){
		return $this->idasi;
	}
	function getFecasi(){
		return $this->fecasi;
	}

	function getIddec(){
		return $this->iddec;
	}
	function getIdfic(){
		return $this->idfic;
	}
	function getFecregdec(){
		return $this->fecregdec;
	}
	function getObsdec(){
		return $this->obsdec;
	}

	function getTit(){
		return $this->tit;
	}
	function getNac(){
		return $this->nac;
	}
	function getNcre(){
		return $this->ncre;
	}
	function getCife(){
		return $this->cife;
	}
	function getHini(){
		return $this->hini;
	}
	function getHfin(){
		return $this->hfin;
	}
	function getJgen(){
		return $this->jgen;
	}
	function getDrcd(){
		return $this->drcd;
	}
	function getAgep(){
		return $this->agep;
	}
	function getObjr(){
		return $this->objr;
	}
	function getDesr(){
		return $this->desr;
	}
	function getConc(){
		return $this->conc;
	}
	function getEaco(){
		return $this->eaco;
	}
	function getAade(){
		return $this->aade;
	}

	function setIdact($idact){
		$this->idact = $idact;
	}
	function setTipact($tipact){
		$this->tipact = $tipact;
	}
	function setFecact($fecact){
		$this->fecact = $fecact;
	}
	function setConact($conact){
		$this->conact = $conact;
	}
	function setIdusu($idusu){
		$this->idusu = $idusu;
	}
	function setCoorusu($coorusu){
		$this->coorusu = $coorusu;
	}

	function setIdacde($idacde){
		$this->idacde = $idacde;
	}
	function setDesacde($desacde){
		$this->desacde = $desacde;
	}
	function setFecacde($fecacde){
		$this->fecacde = $fecacde;
	}
	function setIdasi($idasi){
		$this->idasi = $idasi;
	}
	function setFecasi($fecasi){
		$this->fecasi = $fecasi;
	}

	function setIddec($iddec){
		$this->iddec = $iddec;
	}
	function setIdfic($idfic){
		$this->idfic = $idfic;
	}
	function setFecregdec($fecregdec){
		$this->fecregdec = $fecregdec;
	}
	function setObsdec($obsdec){
		$this->obsdec = $obsdec;
	}

	function setTit($tit){
		$this->tit = $tit;
	}
	function setNac($nac){
		$this->nac = $nac;
	}
	function setNcre($ncre){
		$this->ncre = $ncre;
	}
	function setCife($cife){
		$this->cife = $cife;
	}
	function setHini($hini){
		$this->hini = $hini;
	}
	function setHfin($hfin){
		$this->hfin = $hfin;
	}
	function setJgen($jgen){
		$this->jgen = $jgen;
	}
	function setDrcd($drcd){
		$this->drcd = $drcd;
	}
	function setAgep($agep){
		$this->agep = $agep;
	}
	function setObjr($objr){
		$this->objr = $objr;
	}
	function setDesr($desr){
		$this->desr = $desr;
	}
	function setConc($conc){
		$this->conc = $conc;
	}
	function setEaco($eaco){
		$this->eaco = $eaco;
	}
	function setAade($aade){
		$this->aade = $aade;
	}

private $img = '';

public function setImg($imgHtml) {
    $this->img = $imgHtml;
}

public function getImg() {
    return $this->img;
}


// ACTA
	public function getOne(){
		$res=NULL;
        $sql = "SELECT a.idact, a.nac, a.tipact, a.fecact, a.conact, a.idusu, u.ndocusu, u.nomusu, a.coorusu, c.ndocusu, c.nomusu FROM acta AS a INNER JOIN usuario AS u ON a.idusu=u.idusu INNER JOIN usuario AS c ON a.coorusu=c.idusu WHERE a.idact=:idact";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $idact= $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result -> execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getNacGen($idfic){
		$res=NULL;
        $sql = "SELECT (COUNT(idact)+1) AS can FROM desercion WHERE idfic='".$idfic."'";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $result -> execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getInasis(){
		$res=NULL;
        $sql = "SELECT idina, idusu, fecina, fecreg, horasina, idfic FROM inasistencia WHERE idusu=:idusu AND idfic=:idfic";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $idusu= $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $idfic= $this->getIdfic();
        $result->bindParam(":idfic", $idfic);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getTotIna(){
		$res=NULL;
        $sql = "SELECT SUM(horasina) AS tot FROM inasistencia WHERE idusu=:idusu AND idfic=:idfic";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $idusu= $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $idfic= $this->getIdfic();
        $result->bindParam(":idfic", $idfic);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getOneIdact(){
		$res=NULL;
        $sql = "SELECT idact FROM acta WHERE nac=:nac AND tipact=:tipact AND fecact=:fecact AND conact=:conact AND idusu=:idusu AND coorusu=:coorusu";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $nac = $this->getNac();
        $result->bindParam(":nac", $nac);
        $tipact = $this->getTipact();
        $result->bindParam(":tipact", $tipact);
        $fecact = $this->getFecact();
        $result->bindParam(":fecact", $fecact);
        $conact = $this->getConact();
        $result->bindParam(":conact", $conact);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $coorusu = $this->getCoorusu();
        $result->bindParam(":coorusu", $coorusu);
        $result -> execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res;
	}
	public function save(){
        $sql= "INSERT INTO acta (nac, tipact, fecact, conact, idusu, coorusu) VALUES (:nac, :tipact, :fecact, :conact, :idusu, :coorusu)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $nac = $this->getNac();
        $result->bindParam(":nac", $nac);
        $tipact = $this->getTipact();
        $result->bindParam(":tipact", $tipact);
        $fecact = $this->getFecact();
        $result->bindParam(":fecact", $fecact);
        $conact = $this->getConact();
        $result->bindParam(":conact", $conact);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $coorusu = $this->getCoorusu();
        $result->bindParam(":coorusu", $coorusu);
        $result->execute();
    }

    public function edit(){
        $sql= "UPDATE acta SET nac=:nac, tipact=:tipact, fecact=:fecact, conact=:conact, idusu=:idusu, coorusu=:coorusu WHERE idact=:idact";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $nac = $this->getNac();
        $result->bindParam(":nac", $nac);
        $tipact = $this->getTipact();
        $result->bindParam(":tipact", $tipact);
        $fecact = $this->getFecact();
        $result->bindParam(":fecact", $fecact);
        $conact = $this->getConact();
        $result->bindParam(":conact", $conact);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $coorusu = $this->getCoorusu();
        $result->bindParam(":coorusu", $coorusu);
        $result->execute();
    }

// ACTIVIDAD / DECISIÓN
    public function getOneAcDe(){
		$res=NULL;
        $sql = "SELECT a.idacde, a.idact, a.desacde, a.fecacde, a.idusu, u.ndocusu, u.nomusu FROM actdec AS a INNER JOIN usuario AS u ON a.idusu=u.idusu WHERE a.idact=:idact";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $idact= $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
	}

	public function saveAcDe(){
        $sql= "INSERT INTO actdec (idact, desacde, fecacde, idusu) VALUES (:idact, :desacde, :fecacde, :idusu)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $desacde = $this->getDesacde();
        $result->bindParam(":desacde", $desacde);
        $fecacde = $this->getFecacde();
        $result->bindParam(":fecacde", $fecacde);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $result->execute();
    }

    public function delAcDe(){
        $sql= "DELETE FROM actdec WHERE idact=:idact";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result->execute();
    }

// ASISTENTES
    public function getOneAsis(){
		$res=NULL;
        $sql = "SELECT a.idasi, a.idact, a.idusu, u.ndocusu, u.nomusu, a.fecasi FROM asistente AS a INNER JOIN usuario AS u ON a.idusu=u.idusu WHERE a.idact=:idact";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $idact= $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result -> execute();
        $res = $result->fetchAll(PDO::FETCH_ASSOC);
        return $res;
	}
	public function saveAsis(){
        $sql= "INSERT INTO asistente (idact, idusu, fecasi) VALUES (:idact, :idusu, :fecasi)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $fecasi = $this->getFecasi();
        $result->bindParam(":fecasi", $fecasi);
        $result->execute();
    }
    public function delAsis(){
        $sql= "DELETE FROM asistente WHERE idact=:idact";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result->execute();
    }


// Deserción
    public function getOneDes(){
		$res=NULL;
        $sql = "SELECT d.iddec, d.idact, d.idusu, u.ndocusu, u.nomusu, d.idfic, f.nomfic, d.fecregdec, d.obsdec FROM desercion AS d INNER JOIN usuario AS u ON d.idusu=u.idusu INNER JOIN ficha AS f ON d.idfic=f.idfic WHERE d.idact=:idact";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $idact= $this->getIdact();
        $result->bindParam(":idact", $idact);
        $result -> execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res;
	}

	public function getOneActDes($idusu, $idfic){
		$res=NULL;
        $sql = "SELECT idact FROM desercion WHERE idusu='".$idusu."' AND idfic='".$idfic."'";
        $modelo = new conexion();
        $conexion= $modelo->get_conexion();
        $result= $conexion-> prepare($sql);
        $result -> execute();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        return $res;
	}

	public function saveDes(){
        $sql= "INSERT INTO desercion (idact, idusu, idfic, fecregdec, obsdec) VALUES (:idact, :idusu, :idfic, :fecregdec, :obsdec)";
        $modelo = new conexion();
        $conexion = $modelo->get_conexion();
        $result = $conexion->prepare($sql);
        $idact = $this->getIdact();
        $result->bindParam(":idact", $idact);
        $idusu = $this->getIdusu();
        $result->bindParam(":idusu", $idusu);
        $idfic = $this->getIdfic();
        $result->bindParam(":idfic", $idfic);
        $fecregdec = $this->getFecregdec();
        $result->bindParam(":fecregdec", $fecregdec);
        $obsdec = $this->getObsdec();
        $result->bindParam(":obsdec", $obsdec);
        $result->execute();
    }
function obtenerImagenesUsuario($idusu) {
    $modelo = new conexion();
    $conexion = $modelo->get_conexion();
    $sql = "SELECT nomimg FROM imagen_usuario WHERE idusu = :idusu ORDER BY fecha DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idusu', $idusu);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



	public function getActa(){
		$html = '';
		$html .= '<!DOCTYPE html>';
		$html .= '<html>';
		$html .= '<head>';
			$html .= '<meta charset="utf-8">';
			$html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
			$html .= '<title>'.$this->getTit().'</title>';
			$html .= '<style type="text/css">';
				$html .= 'body{';
					$html .= 'font-family: Arial, Verdana;';
					$html .= 'font-size: 11px;';
				$html .= '}';
				$html .= 'td{';
					$html .= 'text-align: justify;';
				$html .= '}';
				$html .= '.neg{';
					$html .= 'font-weight: bold;';
				$html .= '}';
				$html .= '.tdprin{';
					$html .= 'background-color: #888888;';
				$html .= '}';
				$html .= '.tdcen{';
					$html .= 'text-align: center;';
				$html .= '}';
			$html .= '</style>';
		$html .= '</head>';
		$html .= '<body style="margin: 0; padding: 0; text-align: center;">';
		$html .= '<div style="width: 750px; margin: 0 auto; text-align: left;">'; 
			$html .= '<header style="text-align: center;">';
				$html .= '<table width="730px" style="margin: 0 auto;">';
					$html .= '<tr>';
						$html .= '<th style="text-align: center;">';
							$html .= '<img src="../image/logocom.png" style="width: 80px;">';
						$html .= '</th>';
					$html .= '</tr>';
				$html .= '</table>';
			$html .= '</header>';
			$html .= '<section>';
				$html .= '<br>';
				$html .= '<table width="730px" border="1" cellspacing="0px" cellpadding="5px">';
					$html .= '<tr>';
						$html .= '<th colspan="5">';
							$html .= 'ACTA No. '.str_pad($this->getNac(),3,"0",STR_PAD_LEFT);
						$html .= '</th>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td class="neg" colspan="5">';
							$html .= 'NOMBRE DEL COMITÉ O DE LA REUNIÓN:<br>';
							$html .= $this->getNcre();
						$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td class="neg" colspan="2">';
							$html .= 'CIUDAD Y FECHA:<br>';
							$html .= $this->getCife();
						$html .= '</td>';
						$html .= '<td class="neg" colspan="2">';
							$html .= 'HORA INICIO:<br>';
							$html .= $this->getHini();
						$html .= '</td>';
						$html .= '<td class="neg">';
							$html .= 'HORA FIN:<br>';
							$html .= $this->getHfin();
						$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td class="neg" colspan="2">';
							$html .= 'LUGAR Y/O ENLACE:<br>';
							$html .= $this->getJgen();
						$html .= '</td>';
						$html .= '<td class="neg" colspan="3">';
							$html .= 'DIRECCIÓN / REGIONAL / CENTRO:<br>';
							$html .= $this->getDrcd();
						$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td colspan="5">';
							$html .= '<span class="neg">AGENDA O PUNTOS PARA DESARROLLAR:</span><br>';
							$html .= $this->getAgep();
						$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td colspan="5">';
							$html .= '<span class="neg">OBJETIVO(S) DE LA REUNIÓN:</span><br>';
							$html .= $this->getObjr();
						$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<th colspan="5">';
							$html .= 'DESARROLLO DE LA REUNIÓN';
						$html .= '</th>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<td colspan="5">';
							$html .= $this->getDesr();
						$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
    						$html .= '<th colspan="5">CONCLUSIONES</th>';
					$html .= '</tr>';
					$html .= '<tr>';
    						$html .= '<td colspan="5">' . $this->getConc() . '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
    					$html .= '<th colspan="5" style="text-align:center;">';
        					$html .= 'ESTABLECIMIENTO Y ACEPTACIÓN DE COMPROMISOS';
    					$html .= '</th>';
					$html .= '</tr>';

					$html .= '<tr>';
    					$html .= '<th>ACTIVIDAD / DECISIÓN</th>';
    					$html .= '<th colspan="2">FECHA</th>';
    					$html .= '<th>RESPONSABLE</th>';
    					$html .= '<th>FIRMA</th>';
					$html .= '</tr>';

					if($this->getEaco()){ foreach ($this->getEaco() as $dEa) {
						$html .= '<tr>';
							$html .= '<td>'.$dEa['desacde'].'</td>';
							$html .= '<td colspan="2">'.$dEa['fecacde'].'</td>';
							$html .= '<td>';
								$html .= firdig($dEa['nomusu'], $dEa['idacde'], date("Y-m-d H:i:s"),2); 
							$html .= '</td>';
							$html .= '<td>';
								$html .= firdig($dEa['nomusu'], $dEa['idacde'], date("Y-m-d H:i:s"),3); 
							$html .= '</td>';
						$html .= '</tr>'; 
					}}
					$html .= '<tr>';
						$html .= '<th colspan="5">';
							$html .= 'ASISTENTES Y APROBACIÓN DECISIONES';
						$html .= '</th>';
					$html .= '</tr>';
					$html .= '<tr>';
						$html .= '<th colspan="2">NOMBRE</th>';
						$html .= '<th>DEPENDENCIA</th>';
						$html .= '<th colspan="2">FIRMA</th>';
					$html .= '</tr>';
					if($this->getAade()){ foreach ($this->getAade() as $dAa) {
						$html .= '<tr>';
							$html .= '<td colspan="2">'.$dAa['nomusu'].'</td>';
							$html .= '<td></td>';
							$html .= '<td colspan="2">';
								$html .= firdig($dAa['nomusu'], $dAa['idasi'], date("Y-m-d H:i:s"),1);
							$html .= '</td>';
						$html .= '</tr>';
					}}
				$html .= '</table>';
			$html .= '</section>';
			$html .= '<footer>';
				$html .= '<table width="730px">';
					$html .= '<tr>';
						$html .= '<td class="tdcen">GOR-F-084V01</td>';
					$html .= '</tr>';
				$html .= '</table>';
			$html .= '</footer>';
		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';

		return $html;
	}
}
?>