<?php 
require_once("models/mfxus.php"); 

$mfxus = new Mfxus();

$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu']:NULL;
$idfic = isset($_POST['idfic']) ? $_POST['idfic']:NULL;
$actfic = isset($_POST['actfic']) ? $_POST['actfic']:NULL;

$opera = isset($_REQUEST['opera']) ? $_REQUEST['opera']:NULL;+
//echo $idusu." - ".$idfic." - ".$actfic." - ".$opera;
$datOne = NULL;


$mfxus->setIdusu($idusu);
if($opera=="save"){
    $mfxus->setIdusu($idusu);
    $mfxus->setIdfic($idfic);
    $mfxus->setActfic($actfic);
    $mfxus->save();
    if(!$idusu) $mfxus->save(); else $mfxus->edit();
    /*if(!$mfxus) $mfxus->save(); else $mfxus->edit();
    $mfxus = NULL;    */

}

if($opera=="eli" && $idusu){
    $mfxus->del();
}
    
if($opera== "edi" && $idusu){
    $datOne = $mfxus->getOne();
}

/*if($opera== "eli" && $mfxus){
    $mfxus->del();

}
 */

//if($opera== "edi"&& $mfxus)$datOne = $mfxus->$mfxus{}




$datUsu = $mfxus->getAllUsu();
$datFic = $mfxus->getAllFic();
$datAll = $mfxus->getAll();
?>