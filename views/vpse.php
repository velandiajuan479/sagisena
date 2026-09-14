<?php require_once 'controllers/cpse.php'; ?>
<br><br>

<?php echo titulo2("<i class='fa fa-file-alt'></i> Plan de Sesión", 1); ?>

<style>
@media print {
    button, .no-print, .btn, nav, footer {
        display: none !important;
    }

    body {
        margin: 0;
        padding: 0;
    }

    table {
        width: 100%;
        font-size: 12px;
    }
}
</style>


<div class="no-print">
    <button class="btn btn-danger" onclick="window.print();">
        <i class="fa fa-file-pdf"></i> Imprimir como PDF
    </button>
</div>

<table class="table table-striped mt-3" style="width:100%">
    <thead>
        <tr>
            <th>PLANEACIÓN PEDAGÓGICA DEL PROYECTO FORMATIVO</th>
            <th>SESIÓN</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($datAll) {
            foreach ($datAll as $dta) { ?>
                <tr>
                    <td>
                        <strong>Fecha Elaboración:</strong> <?= $dta['fecses']; ?><br>
                        <strong>Programa:</strong> <?= $dta['nomfic']; ?> (<?= $dta['codpro']; ?>)<br>
                        <strong>Modalidad:</strong> <?= $dta['nomval']; ?><br>
                        <strong>Instructor:</strong> <?= $dta['nomusu']; ?>
                    </td>
                    <td>
                        <strong>Competencia:</strong> <?= $dta['descom']; ?><br>
                        <strong>RAPS:</strong> <?= $dta['nomres']; ?><br>
                        <strong>Actividad:</strong> <?= $dta['nomact']; ?><br>
                        <strong>Horas:</strong> <?= $dta['duract']; ?><br>
                        <strong>Descripción:</strong> <?= $dta['desact']; ?><br>
                        <strong>Ambiente:</strong> <?= $dta['idaul']; ?> - <?= $dta['nomaul']; ?><br>
                        <strong>Fecha Inicio:</strong> <?= $dta['fchinc']; ?><br>
                        <strong>Fecha Final:</strong> <?= $dta['fchfnl']; ?><br>
                        <strong>Instructor Responsable:</strong> <?= $dta['nomusu']; ?><br>
                        <strong>Evidencia:</strong> <?= $dta['descom']; ?><br>
                        <strong>Observaciones:</strong> <?= $dta['observaciones'] ?? 'N/A'; ?>
                    </td>
                </tr>
        <?php } } ?>
    </tbody>
</table>
