<?php require_once 'controllers/cscins.php'; ?>
<div style="margin-bottom: 150px">
  <div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Sesión creación instructor", 1); ?>
    <div class="inser">
      <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="idage">Agenda</label>
            <select name="idage" id="idage" class="form-select">
              <?php
              if ($dage) {
                foreach ($dage as $de) {
              ?>
                  <option value="<?= $de['idage']; ?>" <?php if ($de && $idage == $de['idage']) echo " selected "; ?>> <?= $de['idage']; ?></option>
              <?php }
              } ?>
            </select>
          </div>
          <div class="form-group col-4">
            <label for="fecses">Fecha de sesión</label>
            <input type="date" name="fecses" id="fecses" class="form-control form-control-sm" required value="<?php if ($datOne) echo $datOne[0]['fecses']; ?>">
          </div>
          <div class=" form-group col-md-2">
            <br>
            <input type="hidden" name="ope" value="save">
            <input type="hidden" name="idses" value="<?php if ($dtOne && $dtOne[0]['idses']) echo $dtOne[0]['idses']; ?>" required>
            <input type="submit" class="btn btn-primary" value="Enviar">
          </div>
        </div>
      </form>
    </div>
  </div>


  <table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>Id Sesión</th>
        <th>Agenda</th>
        <th>Fecha</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if ($dat) {
        foreach ($dat as $dt) { ?>
          <tr>
            <td><?= $dt["idses"]; ?></td>
            <td><?= $dt["idage"]; ?></td>
            <td><?= $dt["fecses"]; ?></td>
            <td>
              <a href="home.php?pg=<?= $pg; ?>&ope=del&idses=<?= $dt["idses"]; ?>" onclick="return eli();" title="Eliminar"><i class="fa-solid fa-trash-can fa-2x"></i></a>
              <a href="home.php?pg=<?= $pg; ?>&ope=edit&idses=<?= $dt["idses"]; ?>" title="edit"><i class="fa-solid fa-pen-to-square fa-2x"></i></a>
            </td>

        <?php }
      } ?>
    </tbody>
    <tfoot>
      <tr>
        <th>Id Sesión</th>
        <th>Agenda</th>
        <th>Fecha</th>
        <th></th>
      </tr>
    </tfoot>
    </table>
</div>
