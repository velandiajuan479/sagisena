<?php 
include("models/mcom.php");
include("models/mprg.php");

$mprg = new Mprg();
$mcom = new Mcom();

$codpro = isset($_REQUEST['codpro']) ? $_REQUEST['codpro']:NULL;

$idcom = isset($_REQUEST['idcom']) ? $_REQUEST['idcom']:NULL;
$descom = isset($_POST['descom']) ? $_POST['descom']:NULL;
$vercom = isset($_POST['vercom']) ? $_POST['vercom']:NULL;
$horcom = isset($_POST['horcom']) ? $_POST['horcom']:NULL;
$idval = isset($_POST['idval']) ? $_POST['idval']:NULL;

$idres = isset($_REQUEST['idres']) ? $_REQUEST['idres']:NULL;
$nomres = isset($_POST['nomres']) ? $_POST['nomres']:NULL;
$ndeses = isset($_POST['ndeses']) ? $_POST['ndeses']:NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;
$ope2 = isset($_REQUEST['ope2']) ? $_REQUEST['ope2']:NULL;

$mcom->setIdcom($idcom);
$mcom->setCodpro($codpro);
if ($ope=="save") {
	$mcom->setDescom($descom);
	$mcom->setVercom($vercom);
    $mcom->setHorcom($horcom);
    $mcom->setIdval($idval);
	if($idcom && $ope2=='edit') $mcom->edit();
	else $mcom->save();
	/*echo "<br><br><br>".$idcom." - ".$descom." - ".$vercom." - ".$horcom." - ".$idval;
    die();*/
	$mcom->delPxC();
	$mcom->savePxC();
}

// echo $ope." - ".$codpro." - ".$idcom." - ".$idres." - ".$nomres." - ".$ndeses;

if ($ope=="saveRs") {
	$mcom->setIdres($idres);
	$mcom->setNomres($nomres);
    $mcom->setNdeses($ndeses);
	if($idcom && $ope2=='editRS') $mcom->editRs();
	else $mcom->saveRs();
}

if ($ope=="del" && $idcom){ 
	$mcom->delPxC();
	$mcom->del();
}
if ($ope=="delRS" && $idres){ 
	$mcom->setIdres($idres);
	$mcom->delRs();
}
if ($ope=="edi" && $idcom){
	$datOne = $mcom->getOne();
}else{ 
	$datOne=NULL;
}

$mprg->setCodpro($codpro);
$dtPrg = $mprg->getOne();

$datVal = $mcom->getTipComp();

$datAll = $mcom->getAll();


function modal($idcom, $descom, $pg, $codpro){        
    $html = '';
    $html .= '<div class="modal" id="myModal'.$idcom.'" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">';
        $html .= '<div class="modal-dialog">';
            $html .= '<div class="modal-content">';                
                    $html .= '<div class="modal-header">';
                        $html .= '<h3 class="modal-title" style="text-align: left;">Comp. '.substr($descom,0,37).'...</h3>';
                        $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    $html .= '</div>';
                    
                        $html .='<form action="home.php?pg='.$pg.'" method="POST">';
                        $html .= '<div class="modal-body">';        
                            $html .= '<input type="hidden" name="idcom" value="'.$idcom.'">';
                            $html .= '<input type="hidden" name="codpro" value="'.$codpro.'">';
                            $html .= '<div class="row">';
                                $html .= '<div class="form-group col-md-6" style="margin-bottom: 0px; text-align:left !important;">';
                                    $html .= '<label for="idres">Código</label>';
                                    $html .= '<input type="number" name="idres" id="idres" class="form-control" required>';
                                $html .= '</div>';
                                $html .= '<div class="form-group col-md-6" style="margin-bottom: 0px; text-align:left !important;">';
                                    $html .= '<label for="ndeses">No. Sesiones</label>';
                                    $html .= '<input type="number" name="ndeses" id="ndeses" class="form-control" required>';
                                $html .= '</div>';
                                $html .= '<div class="form-group col-md-12" style="margin-bottom: 0px; text-align:left !important;">';
                                    $html .= '<label for="nomres">Resultado</label>';
                                    $html .= '<textarea name="nomres" id="nomres" class="form-control" required></textarea>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';                                                        
                    $html .= '<div class="modal-footer">';
                        $html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>';
                        $html .= '<input type="hidden" value="saveRs" name="ope">';
                        $html .= '<input type="submit" class="btn btn-primary" value="Guardar">';
                    $html .= '</div>';
                $html .= '</form>';
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
    echo $html;
}
?>
