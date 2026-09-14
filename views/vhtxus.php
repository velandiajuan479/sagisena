<?php
require_once 'models/mhtxu.php';
$mhtxu = new Mhtxu();
$instructores = $mhtxu->getAllIns();
?>

<!-- Modal para agregar instructor -->
<div class="modal fade" id="AgreUsuIns" tabindex="-1" aria-labelledby="AgreUsuInsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Aquí va el formulario original, sin cambios -->
      <form action="home.php?pg=<?=$pg;?>" method="POST">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="AgreUsuInsLabel">Agregar Nuevo Instructor</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <select class="form-control form-select" name="idinstructor">
              <?php foreach($instructores as $inst): ?>
                  <option value="<?=$inst['idusu']?>"><?=$inst['nomusu']?></option>
              <?php endforeach; ?>
          </select>
          <input type="hidden" name="idnorad" value="<?=$datOneHt[0]['idnorad'];?>
          <input type="hidden" name="opera" value="AgrIns">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
      </form>
    </div>
  </div>
</div>
