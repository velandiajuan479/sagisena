<?php
include "models/mfic.php";
include "models/musu.php";
include "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$pag = 1219;

$arc = isset($_FILES["arch"]["name"]) ? $_FILES["arch"]["name"] : NULL;
$inic = isset($_POST["inic"]) ? $_POST["inic"] : NULL;
$finc = isset($_POST["finc"]) ? $_POST["finc"] : NULL;
$arc = substr($arc, 0, strpos($arc, ".xls"));

$mfic = new Mfic();
$musu = new Musu();

if ($arc) {
	$dat = opti($_FILES["arch"], $arc, "fic", "");
	$inputFileType = IOFactory::identify($dat);
	$objReader = IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($dat);
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();
	$highestColumn = $sheet->getHighestColumn();
	for ($row = 7; $row <= $highestRow; $row++) {
		// obtengo el valor de la celda
		$ndocusu = $sheet->getCell("A" . $row)->getValue();
		$ndocusu = substr($ndocusu,strpos($ndocusu, "- ")+2,strlen($ndocusu));
		$nomusu = $sheet->getCell("B" . $row)->getValue();
		$telcan = '3101112222';//$sheet->getCell("E" . $row)->getValue();
		$emausu = $ndocusu.'@soysena.edu.co';//$sheet->getCell("F" . $row)->getValue();
		$idfic = $sheet->getCell("D" . $row)->getValue();
		$nomfic = $sheet->getCell("E" . $row)->getValue();
		$jornada = $sheet->getCell("G" . $row)->getValue();
		$mun = $sheet->getCell("H" . $row)->getValue();
		$noca = NULL;
		
		if($jornada=='Mañana' OR $jornada=='mañana' OR $jornada=='MAÑANA') $jornada='1';
		else if($jornada=='Tarde' OR $jornada=='tarde' OR $jornada=='TARDE') $jornada='2';
		else if($jornada=='Noche' OR $jornada=='noche' OR $jornada=='NOCHE') $jornada='3';
		else if($jornada=='Fin de Semana' OR $jornada=='fin de semana' OR $jornada=='FIN DE SEMANA') $jornada='4';
		else if($jornada=='Virtual' OR $jornada=='virtual' OR $jornada=='VIRTUAL') $jornada='5';
		else $jornada='1';

		//echo $ndocusu . "-" . $nomusu . "-" . $telcan . "-" . $emausu . "-" . $idfic . "-" . $nomfic . "-" . $jornada . "-" . $mun . "<br>";

		$mfic->setIdfic($idfic);
	    $mfic->setNomfic($nomfic);
	    $mfic->setJornada($jornada);
	    $mfic->setIdcen($_SESSION["idcen"]);
	    $mfic->setMun($mun);
	    $mfic->setFinific(date("Y-m-d"));
		$mfic->setFfinfic(date("Y-m-d"));

		$musu->setIdper(4);
		$musu->setNdocusu($ndocusu);
		$musu->setNomusu($nomusu);
		$musu->setPasusu($inic.$ndocusu.$finc);
		$musu->setActusu(1);
		$musu->setIdcen($_SESSION["idcen"]);
		$musu->setEmausu($emausu);
		$musu->setTelcan($telcan);
		$musu->setNoca('');
		$musu->setFotcan('');

		$mfic->ins();
		$existingData = $musu->selectUsu();

		if (!empty($ndocusu)) {
			if ($existingData[0]['sum'] == 0) {
				$musu->save(3);
			}else {
				//echo "Datos ya existen, por lo tanto, actualiza en lugar de guardar";
				$musu->edi();
			}
		}
		$existingData = $musu->selectUsu();
		if($existingData AND $existingData[0]['idusu'] AND $idfic){
			$idusu = $existingData[0]['idusu'];
			$musu->setIdusu($idusu);
			$musu->ediUxF();
			$dtuxf = $musu->getFicUsuSec($idusu,$idfic);
			if(!$dtuxf) $musu->insUxF($idusu, $idfic);
		}
	}
}

?>