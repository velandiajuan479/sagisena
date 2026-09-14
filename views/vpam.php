<?php 
  require_once 'controllers/cpam.php';
  echo titulo2("<i class='".$icono."'></i> Ambientes",2);
?>
<div class="inser">
  <form name="frm1" action="home.php?pg=<?=$pg;?>" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="form-group col-md-6">
        <label for="idval">Ambientes</label>
        <select class="form-select" name="idval" id="idval" type="select">
        <?php if ($datAmb) { foreach ($datAmb As $dta) { ?>
          <option value="<?=$dta['idval'];?>"><?=$dta['parval'].' - '.$dta['nomval'];?></option>
        <?php }} ?>
        </select>
      </div>
      <div class="form-group col-md-7" style="margin:auto; left:0;">
        <input class="btn btn-primary" type="submit" value="Registrar">
        <input type="hidden" name="ope" value="save">
        <!-- <input type="hidden" name="idval" value="<?php if ($datOne) echo $datOne[0]['idval']; ?>"> -->
      </div>
    </div>
  </form>
</div>
<table id="example" class="table table-striped">
  <thead>
    <tr>
      <th>Ambientes</th>
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
      <td><?=$d['ndocusu'];?> - <?=$d['nomusu'];?></td>            
      <td><?=$d['fechos'];?></td>
      <td><?=$d['fhlle'];?></td>
      <td><?=$d['parval'];?> - <?=$d['nomval'];?></td>
      <td>
        <?php
          if ($d['tipmin'] == "A") echo "Ambiente Entregado";
          else echo "Ambiente Apartado";
        ?>
      </td>
      <td style="text-align: right;">
          <a class="btn btn-primary" href="home.php?pg=<?=$pg;?>&idval=<?=$d['ideles'];?>&nummin=<?=$d['nummin'];?>&ope=edi">Entrega</a>
      </td>
    </tr>
  <?php }} ?>
  </tbody>
</table>
