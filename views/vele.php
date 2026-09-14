<?php require_once 'controllers/cele.php'; ?>
<div class="conte">
  <?php echo titulo2("<i class='" . $icono . "'></i> Elementos"); ?>
  <div class="inser">
    <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="form-group col-md-3">
          <label for="nomele">Nombre</label>
          <input type="text" name="nomele" id="nomele" maxlength="255" class="form-control" required value="<?php if ($datOne) echo $datOne[0]['nomele']; ?>">
        </div>
        <div class="form-group col-md-2">
          <label for="nidele">Serial o Placa</label>
          <input type="text" name="nidele" id="nidele" class="form-control" maxlength="255" required value="<?php if ($datOne) echo $datOne[0]['nidele']; ?>">
        </div>
      <?php if ($_SESSION['idper'] == 29) { ?>
        <div class="form-group col-md-2">
          <label for="noplasena">No. Placa Sena</label>
          <input type="number" name="noplasena" id="noplasena" class="form-control" value="<?php if ($datOne) echo $datOne[0]['noplasena']; ?>">
        </div>
      <?php } ?>
        <div class="form-group col-md-1">
          <label for="marele">Marca</label>
          <input type="text" name="marele" id="marele" class="form-control" maxlength="255" required value="<?php if ($datOne) echo $datOne[0]['marele']; ?>">
        </div>
        <div class="form-group col-md-2">
          <label for="tipele">Tipo de Elemento</label>
          <select name="tipele" id="tipele" class="form-select">
          <?php
            if ($datAllTe) { foreach ($datAllTe as $dtte) {
              $sena = str_contains($dtte['nomval'], 'Sena');
              var_dump($sena);
              if ($_SESSION['idper'] == 30) { if ($sena === false) { ?>
              <option value="<?= $dtte['idval']; ?>" <?php if ($datOne && $datOne[0]['tipele'] == $dtte['idval']) echo " selected "; ?>><?=$dtte['nomval'];?></option>
              <?php }} else { if ($sena === true) { ?>
              <option value="<?= $dtte['idval']; ?>" <?php if ($datOne && $datOne[0]['tipele'] == $dtte['idval']) echo " selected "; ?>><?=$dtte['nomval'];?></option>
          <?php }}}} ?>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="rutfot">Foto</label>
          <?php if (isset($dtOne) && $dtOne[0]['rutfor']) echo '<input type="hidden" name="rutfot" value="'.$dtOne[0]['rutfot'].'">'; ?>
          <input class="form-control" type="file" id="rutfot" name="foto" accept="image/png,image/jpeg,image/gif" >
        </div>
        <br><br><br>
        <div class="form-group col-md-12">
          <label for="desele">Descripción</label>
          <textarea name="desele" id="desele" class="form-control"><?php if ($datOne) echo $datOne[0]['desele']; ?></textarea>
        </div>
        <div class="form-group col-md-6">
        <br>
          <input class="btn btn-primary" type="submit" value="Enviar">
          <input type="hidden" name="opera" value="save">
          <input type="hidden" name="idele" id="idele" value="<?php if ($datOne) echo $datOne[0]['idele']; ?>">
        </div>
      </div>
    </form>
  </div>
</div>
<table id="example" class="table table-striped" style="width:100%">
  <thead>
    <tr>
      <th style="width: 110px;">Foto</th>
      <th>Elemento</th>
      <th>Tipo</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
<?php if ($datAll) { foreach ($datAll as $d) { 
  if ($d['preele'] == 1 || $d['preele'] == 2) { ?>
    <tr>
      <td>
      <?php if (file_exists($d['rutfot'])) { ?>
        <img src="<?=$d['rutfot'];?>" class="fotele" width="150px">
      <?php } ?>
      </td>
      <td>
    <?php if ($_SESSION["idper"] == 6 || $_SESSION["idper"] == 9 || $_SESSION["idper"] == 11 || $_SESSION["idper"] == 2 || $_SESSION["idper"] == 1) {
      if ($d['idusu']) { ?>
        <strong>Propietario: </strong><?=$d['nomusu'];?><br>
        <strong>Documento: </strong><?=$d['ndocusu'];?><br>
      <?php }
    } ?>
        <strong><?=$d['idele'];?> - <?=$d['nomele'];?></strong><br>
        <small>
          <strong>Serial o Placa: </strong><?= $d['nidele']; ?><br>
          <strong>Marca: </strong><?=$d['marele'];?>
        <?php if ($d['noplasena']) { ?>
          <br><strong>No. Placa Sena: </strong><?=$d['noplasena'];?>
        <?php } ?>
        </small>
      </td>
      <td><?=$d['nomval'];?></td>
      <td style="text-align: center;">
    <?php if ($_SESSION["idusu"] == $d['idusu']) {
      if ($d['preele'] == 1) { ?>
        <a href="home.php?pg=<?= $pg; ?>&idele=<?= $d['idele']; ?>&opera=editPre&preele=2" title="Descativar">
          <i class="fa-solid fa-star fa-2x" style="color: #ffff00;text-shadow: 0px 0px 3px #000;"></i>
        </a>
      <?php } else { ?>
        <a href="home.php?pg=<?= $pg; ?>&idele=<?= $d['idele']; ?>&opera=editPre&preele=1" title="Activar">
          <i class="fa-solid fa-star fa-2x" style="color: #b5afaf;"></i>
        </a>
      <?php }
    } ?>
        <a href="home.php?pg=<?= $pg; ?>&idele=<?= $d['idele']; ?>&opera=eli" title="Eliminar" onclick="return eliminar();">
          <i class="fa-solid fa-trash-can fa-2x"></i>
        </a>
        <a href="home.php?pg=<?= $pg; ?>&idele=<?= $d['idele']; ?>&ope=edi" title="Editar">
          <i class="fa-solid fa-pen-to-square fa-2x"></i>
        </a>
      </td>
    </tr>
  <?php }
}} ?>
  </tbody>
</table>
<?php if ($dtAllPr && $_SESSION['idper'] == 30) { ?>
  <h2 style="text-align: center;">Elementos SENA Prestados</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Imagen</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($dtAllPr as $dpr) { ?>
      <tr>
        <td>
        <?php if (file_exists($dpr['rutfot'])) { ?>
          <img src="<?=$dpr['rutfot'];?>" class="fotele">
        <?php } else { ?>
          <img src="" alt="sin img" class="fotele">
        <?php } ?>
        </td>
        <td>
          <?=$dpr['idele'].' - '.$dpr['nomele'];?><br>
          <small>
            <strong>Serial o Placa: </strong><?= $dpr['nidele']; ?><br>
            <strong>Marca: </strong><?=$dpr['marele'];?><br>
            <strong>No. Placa Sena: </strong><?=$dpr['noplasena'];?><br>
            <?php $estPre = ($dpr['estpre'] == 1) ? 'Activo' : 'Entregado'; ?>
            <strong>Estado: </strong><?=$estPre;?>
          </small>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
<?php } ?>