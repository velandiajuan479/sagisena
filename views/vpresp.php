<?php require_once('controllers/cpresp.php'); ?>

<div class="inser">
  <?php echo titulo2("<i class='".$icono."'></i> Prestado de Espacios Especializados", 2); ?>
<?php if ($dtAuls) { foreach ($dtAuls as $da) { ?>
  <div class="space">
    <h1><?=$da['nomaul'];?></h1>
    <div class="calSpace">
    <?php
      echo calPorEsp($da['idaul'], $pg);
      $mpresp->setIdaul($da['idaul']);
      $solicited = $mpresp->getSpaceSolicited(); ?>
      <div class="interaction">
      <?php if ($_SESSION['idper'] == 29) { if ($solicited) { ?>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Por</th>
              <th>Fecha</th>
              <th>Aprobación</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($solicited as $so) { ?>
            <tr>
              <td>
                <?=$so['nomper'].'<br>
                '.$so['nomusu'].'<br>
                Documento - '.$so['ndocusu'];?>
              </td>
              <td>
                <?=$so['fecfin'];?><br>
                <?='Desde '.$so['horini'].'<br>
                Hasta '.$so['horfin'];?>
              </td>
              <td>
              <?php if ($so['apresp'] == '2') { ?>
                <a href="home.php?pg=<?=$pg;?>&idpresp=<?=$so['idpresp'];?>&idaul=<?=$da['idaul'];?>&idusu=<?=$so['idusu'];?>&fecfin=<?=$so['fecfin'];?>&whoapr=<?=$_SESSION['idusu'];?>&ope=aprSpace">Aprobar</a>
                <a href="home.php?pg=<?=$pg;?>&idpresp=<?=$so['idpresp'];?>&idaul=<?=$da['idaul'];?>&idusu=<?=$so['idusu'];?>&fecfin=<?=$so['fecfin'];?>&whoapr=<?=$_SESSION['idusu'];?>&ope=noApro">Desaprobar</a>
                <?php echo timeOff($so['idpresp']); ?>
              <?php } else if ($so['apresp'] == '0') { ?>
                No aprobado
              <?php } else { ?>
                Aprobado
              <?php } ?>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      <?php } else { ?>
        <p>Aun no hay reservas</p>
    <?php }} else {
      $mpresp->setIdusu($_SESSION['idusu']);
      $usuSol = $mpresp->usuSolicited();
      if (isset($usuSol)) { foreach ($usuSol as $us) { ?>
        <div class="boxUsuSol">
          <p>Fecha <?=$us['fecfin'];?></p>
          <p>Hora inicio <?=$us['horini'];?></p>
          <p>Hora fin <?=$us['horfin'];?></p>
          <div class="btns-sol">
            <a href="#" data-bs-toggle="modal" data-bs-target="#space<?=$da['idaul'];?>" class="a-editModal" title="Editar solicitud">
              <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <?=spaceModal($da['idaul'], $da['nomaul'], $us['idpresp'], $pg);?>
            <a href="home.php?pg=<?=$pg;?>&idaul=<?=$da['idaul'];?>&idusu=<?=$_SESSION['idusu'];?>&ope=dessol" class="btn btn-primary">Desolicitar</a>
          </div>
        </div>
      <?php }} ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solE<?=$da['idaul'];?>">Solicitar</button>
        <?=solEvent($da['idaul'], $da['nomaul'],$pg);?>
      <?php } ?>
      </div>
    </div>
  </div>
<?php }} ?>
</div>
<style>
  .space {
    text-align: center;
    margin: 20px;
    align-content: center;
  }

  .space h1 {
    text-transform: uppercase;
    font-weight: bold;
    font-weight: 800;
  }

  .space .calSpace {
    display: flex;
    justify-content: center;
    gap: 20px;
  }

  /* *** USUARIO *** */

  .space .a-editModal {
    font-size: 23px;
    margin-top: 10px;
    color: #117f09;
    margin-right: 5px;
  }

  .space .a-editModal:hover {
    background: #117f0900;
  }

  /* *** ADMIN *** */

  .space .calSpace .interaction {
    border: 2px solid #117f09;
    padding: 20px;
    height: max-content;
    border-radius: 1rem;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .space .calSpace .interaction p {
    margin: 0;
  }

  .space .calSpace .interaction .boxUsuSol {
    box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.3);
    border-radius: 1rem;
    padding: 10px;
  }

  .space .calSpace .interaction .btns-sol {
    display: flex;
    align-items: center;
  }

  .space table tr td {
    align-content: center;
  }

  .space table tbody a {
    background: green;
    border-radius: 1rem;
    color: #fff;
    padding: 5px 10px;
    text-transform: uppercase;
    transition: background 0.5s ease;
  }

  .space table tbody a:hover {
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.3);
    cursor: pointer;
    background: #117f09;
  }
</style>