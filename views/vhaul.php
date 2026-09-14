<?php include('controllers/chaul.php'); ?>
<div class="conte">
  <?php echo titulo2("<i class='".$icono."'></i> Histórico de Aula",2); ?>
  <form action="home.php?pg=1533" method="POST">
    <div class="row">
      <div class="form-group col-md-2">
        <label id="fin">Fecha inicial</label>
        <input type="date" name="fin" id="fin" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label id="ffi">Fecha final</label>
        <input type="date" name="ffi" id="ffi" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <label id="idaul">Aula</label>
        <select name="idaul" id="idaul" class="form-control form-select">
          <option value="0">Selecciona un aula</option>
        <?php if ($dtAula) { foreach ($dtAula as $dtAu) { ?>
          <option value="<?=$dtAu['idaul'];?>"><?=$dtAu['nomaul'];?></option>
        <?php }} ?>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label id="ndcusu">No. Documento</label>
        <input type="text" name="ndcusu" id="ndcusu" maxlength="10" class="form-control">
      </div>
      <div class="form-group col-md-2">
        <br>
        <input type="hidden" name="ope" value="search">
        <input type="submit" class="btn btn-primary" value="Buscar">
        <?php echo printHis($fin, $ffi, $idusu, $idaul); ?>
      </div>
    </div>
  </form>
  <table id="example" class="table table-striped" style="width:100% ">
    <thead>
      <tr>
        <th>Usuario</th>
        <th>Aula</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($dtHis) { foreach ($dtHis as $d) { ?>
      <tr>
        <td><?=$d['ndocusu'];?> <?=$d['nomusu'];?></td>
        <td>
          <?=$d['nomaul'];?><br>
          <small>
            <strong>Fecha Recibido:</strong> <?=$d['fechin'];?> 
            <strong>Fecha Entrega:</strong> <?=$d['fechfin'];?>
          </small>
        </td>
        <td><?=$d['estado'];?></td>
      </tr>
    <?php }} ?>
    </tbody>
  </table>
</div>