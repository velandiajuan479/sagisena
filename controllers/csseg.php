<?php
require_once('models/msseg.php');
$msseg = new Msseg();

$idses = isset($_REQUEST['idses']) ? $_REQUEST['idses'] : NULL;
$idage = isset($_POST['idage']) ? $_POST['idage'] : NULL;
$fecses = isset($_REQUEST['fecses']) ? $_REQUEST['fecses'] : NULL;
$ideva = isset($_POST['ideva']) ? $_POST['ideva'] : NULL;
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
//$comeva = isset($_POST['comeva']) ? $_POST['comeva'] : NULL;
$valeva = isset($_POST['valeva']) ? $_POST['valeva'] : NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;
$idfic = isset($_REQUEST['idfic']) ? $_REQUEST['idfic']:NULL;
$idcom = isset($_REQUEST['idcom']) ? htmlspecialchars($_REQUEST['idcom']) : NULL;
$descom = isset($_POST['descom']) ? htmlspecialchars($_POST['descom']) : NULL;

$msseg=new Msseg();
$msseg->setIdses($idses);
if ($ope=="save") {
    $msseg->setIdfic($idfic);
    //$msseg->setDescom($descom);
    if($idses) $msseg->edit();
    else $msseg->save();

}
//if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descom'])) {
    //$_SESSION['descom'] = $_POST['descom'];
//}
//$descom = isset($_SESSION['descom']) ? $_SESSION['descom'] : '';

$m=2;
if ($ope=="del" && $idses) $msseg->del();
if ($ope=="edi" && $idses){
    $dtOne = $msseg->getOne();
    $m=1;
}else{ 
    $dtOne=NULL;
}


$dfi = $msseg->getFic();
$idcom = $msseg->getTipComp();
?>
