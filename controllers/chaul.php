<?php
  require_once("models/mpraul.php");

  $mpraul = new Mpraul();

  $fin = isset($_POST['fin']) ? $_POST['fin'] : NULL;
  $ffi = isset($_POST['ffi']) ? $_POST['ffi'] : NULL;
  $ndcusu = isset($_POST['ndcusu']) ? $_POST['ndcusu'] : NULL;
  $idaul = isset($_POST['idaul']) ? $_POST['idaul'] : NULL;

  $ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

  $dtAula = $mpraul->getAulas();
  if (empty($ndocusu) || $ndocusu == 0) $idusu = NULL;
  else $idusu = $mpraul->getUser($ndcusu);
  
  $dtHis = $mpraul->getAll($fin, $ffi, $idusu, $idaul);
  function printHis($fin, $ffi, $idusu, $idaul) {
    $inte = ($fin || $ffi || $idusu || $idaul) ? '?' : '';
    $fin = ($fin) ? '&fin='.$fin : '';
    $ffi = ($ffi) ? '&ffi='.$ffi : '';
    $idusu = ($idusu) ? '&idusu='.$idusu[0]['idusu'] : '';
    $idaul = ($idaul) ? '&idaul='.$idaul : '';
    $html = '';
    $html .= '<a href="views/pdfaul.php'.$inte.$fin.$ffi.$idusu.$idaul.'" title="Imprimir" target="_blank">
              <i class="fa-solid fa-print fa-2x" style="color:#027902;"></i>
            </a>';
    return $html;
  }
?>