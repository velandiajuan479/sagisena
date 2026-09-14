<?php require_once 'controllers/cprele.php'; ?>
<div class="conte">
  <?php echo titulo2("<i class='".$icono . "'></i> Prestamo",2); ?>
  <div class="inser">
  <?php if ($_SESSION['idper'] == 29) { ?>
    <form name="frm1" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="form-group col-md-5">
          <label for="idusu">No. Documento</label>
          <input type="text" name="idusu" id="idusu" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
          <label for="idele">Elemento</label>
          <select class="form-select" name="idele" id="idele" required>
            <option>Selecciona un elemento</option>
          <?php if ($dtEle) { foreach ($dtEle as $de) { ?>
            <option value="<?=$de['idele'];?>"><?=$de['nomele'];?></option>
          <?php }} ?>
          </select>
        </div>
        <div class="form-group col-md-3">
          <label for="can">Cantidad</label>
          <input type="number" min="1" max="30" name="can" id="can" class="form-control" required>
        </div>
        <div class="form-group col-md-2">
          <br>
          <input class="btn btn-primary" type="submit" value="Registrar">
          <input type="hidden" name="ope" value="save">
          <input type="hidden" name="idprele" value="<?php if ($dtOne) echo $dtOne[0]['idprele'];?>">
        </div>
      </div>
    </form>
  <?php } ?>
  </div>
</div>
<table id="example" class="table table-striped">
  <thead>
    <tr>
      <th>A quien</th>
      <th>Perfil</th>
      <th>FH Prestamo</th>
      <th>FH Entrega</th>
      <th>Estado</th>
      <th>Elemento</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php if ($dtAll) { foreach ($dtAll as $d) { ?>
    <tr>
      <td>
        <?=$d['docu'].' '.$d['nomu'];?><br>
        <small><?=$d['idfic'].' '.$d['nomfic'].' '.$d['jornada'];?></small>
      </td>
      <td><?=$d['peru'];?></td>
      <td><?=$d['fhpre'];?></td>
      <td>
        <?php if ($d['fhent']) echo $d['fhent'];
        else echo 'Vigente'; ?>
      </td>
      <td>
        <?php if ($d['estpre'] == 1) echo "Prestamo Activo";
        else echo "Entregado"; ?>
      </td>
      <td>
        <?php /* $eles = explode(";", $d['ideles']);
        if ($eles) { foreach ($eles as $ele) {
          $dtela = $mmin->selOneEle($ele);
          if ($dtela) echo $dtela[0]['nomele']." <small>(".$d['obs'].")</small><br>";
        }} */ ?>
      <?php if (file_exists($d['rutfot'])) { ?>
        <img src="<?=$d['rutfot'];?>" alt="" width="100px">
      <?php } else { ?>
        <img src="" alt="" width="100px ">
      <?php } ?>
        <br><small>
          <?=$d['nomele'].'<br>
          No. '.$d['noplasena'].'<br>
          Marca: '.$d['marele'];?>
        </small>
      </td>
      <td style="text-align: right;">
      <?php if ($_SESSION['idper'] == 29) { if ($d['estpre'] != 2) { ?>
        <a class="btn btn-primary" href="home.php?pg=<?=$pg;?>&idprele=<?=$d['idprele'];?>&idele=<?=$d['idele'];?>&idusu=<?=$d['idusu'];?>&ope=entEle">Entregado</a>
      <?php } else { echo 'Entregado'; }
      } else { if ($d['estpre'] == 1) { ?>
        Prestamo activo
      <?php } else { echo 'Entregado'; }} ?>
      </td>
    </tr>
  <?php }} ?>
  </tbody>
</table>