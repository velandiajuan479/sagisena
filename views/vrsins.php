<?php
require_once 'controllers/crsins.php';
require_once 'controllers/cage.php';

echo titulo2("<i class='" . $icono . "'></i> Resultados por Instructor", 2);

// Inicializar variables si no existen para evitar warnings
$dtPrg = $dtPrg ?? [];
$dtAge = $dtAge ?? [];
$dtInstructores = $dtInstructores ?? [];
$dtComRes = $dtComRes ?? [];
$idusu = $idusu ?? '';
$idfic = $idfic ?? null;
?>

<!-- Datos de Programa -->
<?php if (!empty($dtPrg)) { foreach ($dtPrg as $dtP) { ?>
    <div>
        <table class="table table-striped" style="width:100%">
            <tr>
                <th colspan='8' style="font-size:30px;font-weight: bold; text-align: center;">
                    FICHA <?= $dtAge[0]['idfic'] ?? '' ?> - <?= $dtAge[0]['nomfic'] ?? '' ?>
                </th>
            </tr>
            <tr>
                <th>Programa</th>
                <td colspan='7'>
                    <?= $dtP['codpro'] ?? '' ?> - <?= $dtP['nompro'] ?? '' ?> 
                    Versión: <?= $dtP['verpro'] ?? '' ?>
                </td>
            </tr>
            <tr>
                <th>Horas Lectiva</th>
                <td><?= $dtP['horlpro'] ?? '' ?></td>
                <th>Créditos Lectiva</th>
                <td><?= $dtP['crelpro'] ?? '' ?></td>
                <th>Horas Productiva</th>
                <td><?= $dtP['horppro'] ?? '' ?></td>
                <th>Créditos Productiva</th>
                <td><?= $dtP['creppro'] ?? '' ?></td>
            </tr>
            <tr>
                <th>Tipo Formación</th>
                <td><?= $dtP['nomval'] ?? '' ?></td>
                <th>Red de Conocimiento</th>
                <th>Área</th>
                <td><?= $dtP['nomare'] ?? '' ?></td>
            </tr>
        </table>
    </div>
<?php }} ?>

<div class="conte">
    <form method="GET" action="home.php">
        <input type="hidden" name="pg" value="<?= $pg ?? '' ?>">
        <input type="hidden" name="idfic" value="<?= $idfic ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="idusu" class="form-label fw-bold">Seleccione Instructor</label>
                <select name="idusu" id="idusu" class="form-control form--input-default">
                    <option value="">-- Seleccione --</option>
                    <?php if (!empty($dtInstructores)) { 
                        foreach ($dtInstructores as $inst) { ?>
                            <option value="<?= $inst['idusu'] ?>" 
                                <?= ($idusu == $inst['idusu']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($inst['nomusu']) ?>
                            </option>
                    <?php } } ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Consultar</button>
            </div>
        </div>
    </form>
</div>

<table id="example" class="table table-striped dataTable" style="width:100%">
    <thead>
        <tr class="fila">
            <th>Competencia</th>
            <th>Resultados</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($dtComRes)) { 
            $compActual = null;

            foreach ($dtComRes as $cr) {
                $nuevaComp = $compActual != $cr['idcom'];
                $compActual = $cr['idcom'];

                echo "<tr>
                        <td style='font-weight:bold; color:black;'>" . ($nuevaComp ? htmlspecialchars($cr['descom']) : '') . "</td>
                        <td style='border-bottom:1px solid #ccc; padding:5px 0;'>" . htmlspecialchars($cr['nomres']) . "</td>
                      </tr>";
            }

        } elseif ($idfic) { ?>
            <tr>
                <td colspan="2" class="text-center text-warning-dark">
                    No se encontraron resultados para el instructor seleccionado.
                </td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr class="fila">
            <th>Competencia</th>
            <th>Resultados</th>
        </tr>
    </tfoot>
</table>
