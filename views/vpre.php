<?php require_once 'controllers/cpre.php';
  echo titulo2("<i class='".$icono . "'></i> Prestamo",2); ?>
<div class="inser">
  <form name="frm1" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="form-group col-md-5">
        <label for="idusu">No. Documento</label>
        <input type="text" name="idusu" id="idusu" class="form-control" required>
      </div>
      <div class="form-group col-md-3">
        <label for="idele">Elemento</label>
        <select class="form-select" name="idele" id="idele">
          <option value="">Selecciona un elemento</option>
        <?php if ($datEle) { foreach ($datEle As $dta) { if ($dta['desele'] > 0) { ?>
          <option value="<?=$dta['idele'];?>"><?=$dta['nomele']." <small>(".$dta['desele'].")</small>";?></option>
        <?php }}} ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="can">Cantidad</label>
        <input type="number" min="1" name="can" id="can" class="form-control" required>
      </div>
      <div class="form-group col-md-2">
        <input class="btn btn-primary" type="submit" value="Registrar">
        <input type="hidden" name="opera" value="save">
        <input type="hidden" name="idele" value="<?php if ($datOne) echo $datOne[0]['idele'];?>">
      </div>
    </div>
  </form>
</div>
<table id="example" class="table table-striped">
  <thead>
    <tr>
      <th>Prestamos</th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php if ($datAll) { foreach ($datAll as $d) { ?>
    <tr>
      <td>
        <?=$d['ndocusu'].' '.$d['nomusu'];?><br>
        <small><?=$d['idfic'].' '.$d['nomfic'].' '.$d['nomval'];?></small>
      </td>
      <td><?=$d['nomper'];?></td>
      <td><?=$d['fechos'];?></td>
      <td><?=$d['fhlle'];?></td>
      <td>
        <?php if ($d['tipmin'] == "A") echo "Prestamo Activo";
        else echo "Entregado"; ?>
      </td>
      <td>
        <?php $eles = explode(";", $d['ideles']);
        if ($eles) { foreach ($eles as $ele) {
          $dtela = $mmin->selOneEle($ele);
          if ($dtela) echo $dtela[0]['nomele']." <small>(".$d['obs'].")</small><br>";
        }} ?>
      </td>
      <td style="text-align: right;">
        <a class="btn btn-primary" href="home.php?pg=<?=$pg;?>&idele=<?=$d['ideles'];?>&nummin=<?=$d['nummin'];?>&opera=edi&idusu=<?=$d['idusu'];?>">Entrega</a>
      </td>
    </tr>
  <?php }} ?>
  </tbody>
</table>