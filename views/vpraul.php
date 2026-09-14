<?php include('controllers/cpraul.php'); ?>
<div class="conte piso">
  <?php echo titulo2("<i class='".$icono."'></i> Prestamo de Aula", 2); ?>
<?php if ($dtPiso) { foreach ($dtPiso as $dtp) {
  $maula->setPiso($dtp['piso']);
  $dtBloque = $maula->getAllBloque(); ?>
  <h3>Piso <?=$dtp['piso'];?></h3>
  <div class="row">
  <?php if ($dtBloque) { foreach ($dtBloque as $dbq) {
    $maula->setBloqau($dbq['bloqau']);
    $dtaula = $maula->getAllAula(); ?>
    <div class="form-group col-md-5 bloque">
      <h4>Bloque <?=$dbq['bloqau'];?></h4>
      <div class="row aula">
      <?php if ($dtaula) { foreach ($dtaula as $dtau) {
        $mpraul->setIdaul($dtau['idaul']);
        $dtAuOc = $mpraul->getAulOcu();
        $clCua = ($dtAuOc) ? 'cuaaula2' : 'cuaaula';
        $dataModal = (!$dtAuOc) ? 'data-bs-toggle="modal" data-bs-target="#regaula'.$dtau['idaul'].'"' : '';
        $taula = $dtau['taula'];
        if (!$taula) $taula=1; ?>
        <div class="form-group col-md-<?=3*$taula.' '.$clCua;?>" style="width: <?=23*$taula;?>% !important;" <?=$dataModal;?>>
          <h5><?=$dtau['nomaul'];?></h5>
          <div>
          <?php if (!$dtAuOc) { if ($_SESSION['idper'] != 29) { ?>
            <form action="home.php?pg=<?=$pg;?>" method="POST">
              <input type="hidden" name="idusu" value="<?=$_SESSION['idusu'];?>">
              <input type="hidden" name="idaul" value="<?=$dtau['idaul'];?>">
              <input type="hidden" name="ope" value="save">
              <button type="submit" class="btn btn-primary" style="padding: 5px 10px !important;">Ocupar</button>
            </form>
          <?php } else { ?>
            <h5>No hay reservas</h5>
          <?php }} else { 
            if ($_SESSION['idper'] != 29) { if ($dtAuOc[0]['idusu'] == $_SESSION['idusu']) { ?>
            <h5>LO OCUPASTE</h5>
            <a href="home.php?pg=<?=$pg;?>&idusu=<?=$dtAuOc[0]['idusu'];?>&idaul=<?=$dtau['idaul'];?>&ope=desSolAul" class="btn btn-desocupar">Desocupar</a>
          <?php } else { ?>
            <h5>Ocupado</h5>
          <?php }} else {
            $noms = explode(" ", $dtAuOc[0]['nomusu']);
            if (isset($noms[0]) && isset($noms[1]) && isset($noms[2]) && isset($noms[3]) && isset($noms[4])) $nom = $noms[0]." ".$noms[3];
            else if (isset($noms[0]) && isset($noms[1]) && isset($noms[2]) && isset($noms[3])) $nom = $noms[0]." ".$noms[2];
            else if ((isset($noms[0]) && isset($noms[1]) && isset($noms[2])) OR (isset($noms[0]) && isset($noms[1]))) $nom = $noms[0]." ".$noms[1];
            else $nom = $dtAuOc[0]['nomusu']; ?>
            <small><?=substr($nom, 0, 29);?></small>
            <i class="fa-solid fa-file-export objIzq" data-bs-toggle="modal" data-bs-target="#libaul<?=$dtAuOc[0]['idpres'];?>" title="Liberar con Comentario"></i>
            <?=modalLibCom($dtAuOc[0]['idpres'], $dtau['nomaul'], $pg); ?>
            <a href="home.php?pg=<?=$pg;?>&idpres=<?=$dtAuOc[0]['idpres'];?>&ope=save" onclick="return eliminar();" style="color: #fff;" title="Liberar">
              <i class="fa-solid fa-arrow-right-from-bracket objDer"></i>
            </a>
          <?php }} ?>
          </div>
        </div>
      <?php }} ?>
      </div>
    </div>
  <?php }} ?>
  </div>
<?php }} ?>
</div>
<style>
  .piso h3 {
    text-align: center;
    text-transform: uppercase;
    margin: 10px 0;
  }

  .piso .row {
    text-align: center;
    display: flex;
    justify-content: center;
  }

  .piso .bloque {
    border: 2px solid #117f09;
    border-radius: 1rem;
    padding: 20px;
    margin: 5px;
  }

  .piso .bloque h4 {
    text-transform: uppercase;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .piso .bloque .aula .cuaaula {
    border-radius: 5px;
    position: relative;
    align-content: center;
    text-align: center;
    margin: 1%;
    height: 70px;
    box-shadow: 0px 0px 3px #117f09;
  }

  .piso .bloque .aula .cuaaula div {
    display: flex;
    justify-content: center;
    color: #fff;
  }

  .piso .bloque .aula .cuaaula div h5 {
    background-color: #117f09;
    width: max-content;
    padding: 3px 6px;
    border-radius: 1rem;
  }

  .piso .bloque .aula .cuaaula2 {
    position: relative;
  }

  .piso .bloque .aula .cuaaula2 .btn-desocupar {
    background-color: #17AD0C;
    margin: 4px;
    padding: 5px;
    color: #fff;
  }
</style>