<?php 
require_once('controllers/cage.php'); 
require_once('models/mcmas.php');

$mcmas = new Mcmas();
$dtCargue = $mcmas->getAllCarguePlaneacion(); 
$idper = $_SESSION['idper'] ?? null;
?>

<?php echo titulo2("<i class='" . $icono . "'></i> Agenda", 2); ?>

<?php if($dtPrg){ foreach ($dtPrg as $dtP){ ?>
    <div>
        <table class="table table-striped" style="width:100%">
            <tr>
                <th colspan='10' style="font-size:30px;font-weight: bold; text-align: center;">FICHA <?=$dtAge[0]['idfic']?> - <?=$dtAge[0]['nomfic']?></th>
            </tr>
            <tr>
                <th>Programa</th>
                <td colspan='10'><?=$dtP['codpro'];?> - <?=$dtP['nompro'];?> Versión: <?=$dtP['verpro'];?></td>
            </tr> 
            <tr>
                <th>Horas Lectiva</th>
                <td><?=$dtP['horlpro'];?></td>
                <th>Créditos Lectiva</th>
                <td><?=$dtP['crelpro'];?></td>
                <th>Horas Productiva</th>
                <td><?=$dtP['horppro'];?></td>
                <th>Créditos Productiva</th>
                <td><?=$dtP['creppro'];?></td>
            </tr>
            <tr>
                <th>Tipo Formación</th>
                <td><?=$dtP['nomval'];?></td>
                <th>Red de Conocimiento</th>
                <td>
                    <a href="#" 
                       data-bs-toggle="modal" 
                       data-bs-target="#modalPlaneacion"
                       title="Planeación pedagógica"
                       style="display: inline-flex; align-items: center; gap: 4px; padding: 4px; 
                              color: black; text-decoration: none; 
                              border-radius: 4px; font-family: sans-serif; font-size: 14px;">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
                <td colspan="3"><?=$dtP['redcon'];?></td>
                <th>Área</th>
                <td><?=$dtP['nomare'];?></td>
            </tr>
        </table>
    </div>
<?php }} ?>


<div class="modal fade" id="modalPlaneacion" tabindex="-1" aria-labelledby="modalPlaneacionLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95vw;">
    <div class="modal-content" style="height: 90vh;">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalPlaneacionLabel">Planeación Pedagógica</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="overflow-y: auto; padding: 1.5rem;">
        <?php if (!empty($dtCargue)): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-sm">
            <thead class="table-dark text-center">
              <tr>
                <?php foreach (array_keys($dtCargue[0]) as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dtCargue as $row): ?>
                <tr>
                  <?php foreach ($row as $cell): ?>
                      <td><?= htmlspecialchars($cell) ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="alert alert-warning text-center">
          <i class="fa fa-exclamation-triangle"></i> No se encontraron registros en <strong>cargueplaneacion</strong>.
        </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>


<table id="example" class="table table-striped dataTable" style="width: 100%;">
    <thead>
        <tr class="fila">
            <th>Instructor</th>
            <th>Competencia</th>
            <th>Resultado</th>
            <th>Lun</th>
            <th>Mar</th>
            <th>Mie</th>
            <th>Jue</th>
            <th>Vie</th>
            <th>Sab</th>
            <th>Dom</th>
            <th>Fecha Inicio</th>
            <th>Fecha Final</th>
        </tr>
    </thead>
    <tbody>
        <?php if($dtAge){
            foreach ($dtAge as $dta) { ?>
        <tr>
            <td><?=$dta['idusu']?></td>
            <td><?=$dta['descom']?></td>
            <td style="position: relative; padding-bottom: 30px;">
                <div><?=$dta['nomres']?></div>

                <?php if (in_array($idper, [21, 7])): ?>
                    <a href="home.php?pg=1545&idres=<?=$dta['idres']?>&idfic=<?=$idfic?>" 
                       style="position: absolute; bottom: 0; left: 5; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <i class="fa fa-plus" style="color: green; font-size: 22px;"></i>
                    </a>
                <?php endif; ?>

                <?php if (!empty($dta['instrumentos'])): ?>
                    <div style="position: absolute; bottom: 0; left: 35px; display: flex; gap: 5px;">
                        <?php
                        if (is_array($dta['instrumentos'])) {
                            foreach ($dta['instrumentos'] as $instrumento) {
                                $title = htmlspecialchars($instrumento['nomins'] ?? $instrumento);
                                $link = (in_array($idper, [21, 7])) 
                                    ? "home.php?pg=1547&idins={$instrumento['idins']}&idfic={$idfic}" 
                                    : "#";
                                ?>
                                <a href="<?= $link ?>" title="<?= $title ?>" 
                                   style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    <i class="fa fa-book" style="color: green; font-size: 22px;"></i>
                                </a>
                            <?php
                            }
                        } else {
                            for ($i = 0; $i < intval($dta['instrumentos']); $i++) { ?>
                                <a href="#" title="Instrumento <?= $i + 1 ?>" 
                                   style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    <i class="fa fa-book" style="color: green; font-size: 22px;"></i>
                                </a>
                            <?php }
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </td>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <td></td>
            <td>
                <i class="fa-solid fa-pen-to-square fa-2x"></i>
            </td>
        </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr class="fila">
            <th>Ficha</th>
            <th>Instructor</th>
            <th>Competencia</th>
            <th>Resultado</th>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miercoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
            <th>Sabado</th>
            <th>Domingo</th>
            <th>Fecha Inicio</th>
            <th>Fecha Final</th>
        </tr>
    </tfoot>
</table>
