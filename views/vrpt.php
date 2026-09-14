<?php
require_once 'controllers/crpt.php';
require_once 'controllers/cage.php';

echo titulo2("<i class='" . $icono . "'></i> Resultados por Trimestre", 2);

// Guardamos el trimestre seleccionado (si viene del controlador)
$selTri = $trimestre ?? '';
?>

<!-- Datos de Programa -->
<?php if($dtPrg){ foreach ($dtPrg as $dtP){ ?>
    <div>
        <table class="table table-striped" style="width:100%">
            <tr>
                <th colspan='8' style="font-size:30px;font-weight: bold; text-align: center;">FICHA <?=$dtAge[0]['idfic']?> - <?=$dtAge[0]['nomfic']?></th>
            </tr>
            <tr>
                <th>Programa</th>
                <td colspan='7'><?=$dtP['codpro'];?> - <?=$dtP['nompro'];?> Versión: <?=$dtP['verpro'];?></td>
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
                <th>Área</th>
                <td><?=$dtP['nomare'];?></td>
            </tr>
        </table>
    </div>
<?php }} ?>

<div class="conte">
    <form method="GET" action="home.php">
        <input type="hidden" name="pg" value="<?= $pg ?>">
        <input type="hidden" name="idfic" value="<?= $idfic ?>">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="trimestre" class="form-label fw-bold">Seleccione Trimestre</label>
                <select name="trimestre" id="trimestre" class="form-control">
                    <option value="">-- Seleccione --</option>
                    <?php 
                    if (!empty($dtFechas)) {
                        $inicio = new DateTime($dtFechas['finific']);
                        $fin = new DateTime($dtFechas['ffinfic']);
                        $diff = $inicio->diff($fin);
                        $meses = ($diff->y * 12) + $diff->m;
                        $numTrimestres = ceil($meses / 3);

                        // Meses en español
                        $mesesES = [
                            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
                            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
                        ];

                        for ($i = 1; $i <= $numTrimestres; $i++) {
                            $inicioTrim = clone $inicio;
                            $inicioTrim->modify('+' . (($i - 1) * 3) . ' months');
                            $finTrim = clone $inicioTrim;
                            $finTrim->modify('+2 months')->modify('last day of this month');

                            $mesInicio = $mesesES[(int)$inicioTrim->format('n')] . ' ' . $inicioTrim->format('Y');
                            $mesFin    = $mesesES[(int)$finTrim->format('n')] . ' ' . $finTrim->format('Y');

                            $sel = ($selTri == $i) ? 'selected' : '';

                            echo "<option value='$i' $sel>Trimestre $i ($mesInicio - $mesFin)</option>";
                        }
                    }
                    ?>
                </select>

                <?php 
                // Mostrar rango seleccionado (opcional, para depuración)
                if (!empty($dtFechas) && !empty($selTri)) {
                    $inicioTrim = new DateTime($dtFechas['finific']);
                    $inicioTrim->modify('+' . (($selTri - 1) * 3) . ' months');
                    $finTrim = clone $inicioTrim;
                    $finTrim->modify('+2 months')->modify('last day of this month');

                    echo "<div class='small text-muted mt-1'>
                            Filtro: " . $inicioTrim->format('Y-m-d') . " al " . $finTrim->format('Y-m-d') . "
                          </div>";
                }
                ?>
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
        if ($dtComRes) { 
            $compActual = NULL;

            foreach ($dtComRes as $cr) {
                if ($compActual != $cr['idcom']) {
                    $compActual = $cr['idcom'];
                    $primeraFila = true;
                } else {
                    $primeraFila = false;
                }

                echo "<tr>
                        <td style='font-weight:bold; color:black;'>" . ($primeraFila ? htmlspecialchars($cr['descom']) : '') . "</td>
                        <td style='border-bottom:1px solid #ccc; padding:5px 0;'>" . htmlspecialchars($cr['nomres']) . "</td>
                      </tr>";
            }

        } elseif ($idfic) { ?>
            <tr>
                <td colspan="2" class="text-center text-dark">
                    No se encontraron resultados para la ficha ingresada.
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