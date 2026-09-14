<?php
include("models/mhxp.php");

$idhor = isset($_REQUEST['idhor']) ? $_REQUEST['idhor']:NULL;
$idfic = isset($_REQUEST['idfic']) ? $_REQUEST['idfic']:NULL;
$idaul = isset($_REQUEST['idaul']) ? $_REQUEST['idaul']:NULL;
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:$_SESSION["idusu"];
$iddia = isset($_REQUEST['iddia']) ? $_REQUEST['iddia']:NULL;
$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope']:NULL;

$mhxp=new Mhxp();
$dtOne=NULL;

$m=2;

$dat=$mhxp->getAll();
$mhxp->setIdusu($idusu);
$dfi = $mhxp->getFicha();
$ddi = $mhxp->getDia();
$din = $mhxp->getInst();
$dau = $mhxp->getAula();

function pintit($ddi){
    if($ddi){ foreach ($ddi as $d) {
      echo "<th>".$d['nomval']."</th>";
    }}
}
?>

