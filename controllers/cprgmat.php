<?php require_once("models/mprgmat.php"); 

$mprgmat = new Mprgmat();

$codpro = isset($_REQUEST["codpro"]) ? $_REQUEST["codpro"] : NULL;
$nompro = isset($_POST["nompro"]) ? $_POST["nompro"] : NULL;
$despro = isset($_POST["despro"]) ? $_POST["despro"] : NULL;
$verpro = isset($_POST["verpro"]) ? $_POST["verpro"] : NULL;
$horlpro = isset($_POST["horlpro"]) ? $_POST["horlpro"] : NULL;
$horppro = isset($_POST["horppro"]) ? $_POST["horppro"] : NULL;
$crelpro = isset($_POST["crelpro"]) ? $_POST["crelpro"] : NULL;
$creppro = isset($_POST["creppro"]) ? $_POST["creppro"] : NULL;
$tippro = isset($_POST["tippro"]) ? $_POST["tippro"] : NULL;
$just   = isset($_POST["just"])   ? $_POST["just"]   : NULL;
$redcon = isset($_POST["redcon"]) ? $_POST["redcon"] : NULL;
$reqing = isset($_POST["reqing"]) ? $_POST["reqing"] : NULL;
$reqcer = isset($_POST["reqcer"]) ? $_POST["reqcer"] : NULL;
$idare  = isset($_POST["idare"])  ? $_POST["idare"]  : NULL;

$opera  = isset($_REQUEST["opera"])  ? $_REQUEST["opera"]  : NULL;
$datOne = NULL;

$mprgmat->setCodpro($codpro);
if ($opera == "save" || $opera == "save1") {
    $mprgmat->setNompro($nompro);
    $mprgmat->setDespro($despro);   
    $mprgmat->setVerpro($verpro);   
    $mprgmat->setHorlpro($horlpro); 
    $mprgmat->setHorppro($horppro); 
    $mprgmat->setCrelpro($crelpro); 
    $mprgmat->setCreppro($creppro); 
    $mprgmat->setTippro($tippro);   
    $mprgmat->setJust($just);   
    $mprgmat->setRedcon($redcon);   
    $mprgmat->setReqing($reqing);   
    $mprgmat->setReqcer($reqcer);   
    $mprgmat->setIdare($idare); 

    if ($opera == "save") {
        $mprgmat->save();
    } else {
        $mprgmat->edit();
    }
}


if ($opera == "eli" && $codpro) {
    $mprgmat->del();
    $codpro = NULL;

    echo "<script>window.location.href='home.php?pg=$pg&msg=eliminado';</script>";
    exit;
}


if ($opera == "edi" && $codpro) {
    $datOne = $mprgmat->getOne();
}


$datAll = $mprgmat->getAll();
$datpro = $mprgmat->getAllpro();
$datAre = $mprgmat->getAllAre();
?>
