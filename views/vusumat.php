<?php require_once("controllers/cusumat.php"); ?>

<div class="conte">
  <?php echo titulo2("<i class='".$icono."'></i> Usuarios Individuales"); ?> 

  <div class="inser">
    <form id="frmins" action="index.php" method="POST">
      <div class="row">
        <div class="form-group col-md-4">
          <label for="ndocusu">Documento</label>
          <input type="text" name="ndocusu" id="ndocusu" class="form-control"
            value="<?= isset($datOne[0]['ndocusu']) ? $datOne[0]['ndocusu'] : '' ?>" required>
        </div>

        <div class="form-group col-md-4">
          <label for="nomusu">Nombre</label>
          <input type="text" name="nomusu" id="nomusu" class="form-control"
            value="<?= isset($datOne[0]['nomusu']) ? $datOne[0]['nomusu'] : '' ?>" required>
        </div>

        <div class="form-group col-md-4">
          <label for="emausu">Email</label>
          <input type="email" name="emausu" id="emausu" class="form-control"
            value="<?= isset($datOne[0]['emausu']) ? $datOne[0]['emausu'] : '' ?>" required>
        </div>

        <div class="form-group col-md-4">
          <label for="pasusu">Contraseña</label>
          <input type="text" name="pasusu" id="pasusu" class="form-control"
            value="<?= isset($datOne[0]['pasusu']) ? $datOne[0]['pasusu'] : '' ?>" required>
        </div>

      
        <div class="form-group col-md-4">
          <br>
          <input type="submit" value="Guardar" class="btn btn-primary">
          <a href="index.php" class="btn btn-secondary">Cancelar</a>
          <input type="hidden" name="opera" value="<?= isset($datOne) ? 'save1' : 'save'; ?>">
        </div>
      </div>
    </form>
  </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
  <thead>
    <tr>
      <th>Usuario</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if ($usuAll) { foreach ($usuAll as $du) { ?>
      <tr>
        <td>
          <big><strong><?= $du['nomusu']; ?></strong></big><br>
          <strong>Documento:</strong> <?= $du['ndocusu']; ?><br>
          <strong>Email:</strong> <?= $du['emausu']; ?><br>
          <strong>Contraseña:</strong> <?= $du['pasusu']; ?>
        </td>
        <td>
          <a href="index.php?opera=edi&idusu=<?= $du['idusu']; ?>" title="Editar">
            <i class="fa-solid fa-pen-to-square fa-2x"></i>
          </a>
          <a href="index.php?opera=eli&idusu=<?= $du['idusu']; ?>" title="Eliminar" onclick="return confirm('¿Deseas eliminar este usuario?');">
            <i class="fa-solid fa-trash-can fa-2x"></i>
          </a>
        </td>
      </tr>
    <?php }} ?>
  </tbody>
  <tfoot>
    <tr>
      <th>Usuario</th>
      <th></th>
    </tr>
  </tfoot>
</table>