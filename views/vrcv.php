<?php require_once 'controllers/crcv.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Resultados por Ficha", 2); ?>
</div>

<!-- Selector de Ficha -->
<div class="conte mb-5">
    <form action="home.php?pg=<?= $pg ?>" method="POST" id="fichaForm">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <div class="form-group" style="flex-grow: 1; margin-left: 10px;">
                        <label for="idfic" class="fw-semibold">Seleccionar Ficha</label>
                        <select name="fidfic" id="idfic" class="form-select form--input-default" required onchange="this.form.submit();">
                            <option value="">-- Seleccione una ficha --</option>
                            <?php foreach ($fichas as $ficha): ?>
                                <option value="<?= $ficha['idfic'] ?>" <?= ($fidfic == $ficha['idfic']) ? 'selected' : '' ?>>
                                    <?= $ficha['idfic'] ?> - <?= $ficha['nomfic'] ?> (<?= $ficha['nomval'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function descargarPDF() {
    // Obtener el id de la ficha seleccionada
    var idfic = document.getElementById('idfic').value;
    
    // Redireccionar al archivo que genera el PDF
    if(idfic) {
        window.location.href = 'views/pdfrev.php?idfic=' + idfic;
    } else {
        Swal.fire({
        icon: 'warning',
        title: 'Atención',
        text: 'Por favor seleccione una ficha primero',
        confirmButtonText: 'Aceptar'
    });
    }
}
</script>

<?php if($fidfic): ?>
<!-- informacion de la ficha seleccionada -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #117f09; color: white;">
        <h4>Ficha: <?= $fichaActual['idfic'] ?> - <?= $fichaActual['nomfic'] ?></h4>
        <small>Jornada: <?= $fichaActual['nomval'] ?></small>
    </div>
    <div class="d-flex">
        <?php if($fidfic): ?>
            <button type="button" class="btn btn-success" onclick="descargarPDF()" title="Descargar PDF"  style="background-color:transparent;border:none">
                <i class="fas fa-file-pdf" style="color: #117f09ff; font-size: 2.3rem;margin-left:1460px"></></i>
            </button>
        <?php endif; ?>
    </div>

    <!-- candidatos a vocero -->
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: center;">Votos</th>
                <th>Foto</th>
                <th>Candidato a Vocero</th>
                <th>Documento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totm = 0;
            if ($dat) {
                foreach ($dat as $d) {
                    $nvo = $mrcv->nvoCan($d['idusu']);
                    $nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo'] : 0;
                    $totm += $nvo;
            ?>
                    <tr>
                        <td style="text-align: center;font-size: 24px;">
                            <?= $nvo ?>
                        </td>
                        <td width="100px">
                            <?php if (file_exists($d['fotcan'])) { ?>
                                <img src="<?= $d['fotcan']; ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                            <?php } else { ?>
                                <img src="image/usuario.png" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                            <?php } ?>
                        </td>
                        <td>
                            <?= $d['nomusu']; ?>
                        </td>
                        <td style="position: relative;">
                            <?= $d['ndocusu']; ?>
                            <a href="home.php?pg=1204&idusu=<?=$d['idusu'];?>" title="Ver Propuesta" target="_blank" 
                            style="position: absolute; right: 10px; top: 20%; transform: translateY(-50%); color: #12730bff;">
                                <i class="fa-solid fa-file-lines fa-2x"></i>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="4" class="text-center">No hay candidatos a vocero en esta ficha</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="table-info">
                <td style="text-align: center;font-size: 24px; background-color:darkgray;font-weight:lighter;">
                    <strong><?= $totm ?></strong>
                </td>
                <td colspan="3" style="font-size: 15px; background-color:darkgray;font-weight:lighter;">
                    <strong>Total de votos</strong>
                </td>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Por favor seleccione una ficha para ver los candidatos a vocero
    </div>
<?php endif; ?>