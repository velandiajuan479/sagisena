<?php
include "models/mele.php";
include "models/musu.php";
include "vendor\autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$pag=1504;

$arc = isset($_FILES["arch"]["name"]) ? $_FILES["arch"]["name"]:NULL;
$arc = substr($arc,0,strpos($arc,".xls"));

$mele = new Mele();
$musu = new Musu();


if($arc){
	$dat = opti($_FILES["arch"], $arc, "fic", "");
	$inputFileType = IOFactory::identify($dat);
	$objReader = IOFactory::createReader($inputFileType);
	$objPHPExcel = $objReader->load($dat);
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();
	for ($row = 2; $row <= $highestRow; $row++){
		// obtengo el valor de la celda
		$ndocusu = $sheet->getCell("A".$row)->getValue();//Cedula cuentadante
		$nomusu = $sheet->getCell("B".$row)->getValue();
		$nomele = $sheet->getCell("I".$row)->getValue();
		$nidele = $sheet->getCell("J".$row)->getValue();
		$tipele = $sheet->getCell("F".$row)->getValue();
		$desele = $sheet->getCell("K".$row)->getValue(); //Numero placa sena
		$preele = $sheet->getCell("G".$row)->getValue();//descripcion elemento

		$id = $musu->getUsu($ndocusu);
		if($id[0]['idusu']==NULL){
			$musu->setNdocusu($ndocusu);
			$musu->setNomusu($nomusu);
			$musu->setIdper(10);
			$musu->setActusu(1);
			$musu->setTelcan("");
			$musu->setEmausu("");
			$musu->setIdcen($_SESSION["idcen"]);
			$musu->setIdfic("");
			$musu->setPasusu($ndocusu);
			$musu->setNoca("");
			$musu->save();
			$idusu = $musu->getUsu($ndocusu);
		}else{
			$idusu = $musu->getUsu($ndocusu);
		}

    	$mele->setIdusu($idusu[0]['idusu']);
		$mele->setNomele($nomele);
		$mele->setNidele($nidele);
		$mele->setMarele("SENA");
		$mele->setTipele($tipele);
		$mele->setNoplasena($nidele);
		$mele->setDesele($desele);

		$existingData = $mele->selectEle(); 
		//Verifica si la cedula del cuentadante existe
		//condicional para comparar si la cedula de usuario tiene un id de usuario asociado
		if(!empty($nidele)){
			if ($existingData[0]['sum'] == 0) {
				// Datos ya existen, por lo tanto, actualiza en lugar de guardar
				$mele->save();
			}else {
				$mele->edit();
			}
		}
	}		
}
?>