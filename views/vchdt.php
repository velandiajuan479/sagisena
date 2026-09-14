<?php
require_once("controllers/cchdt.php");
if (!function_exists('titulo2')) {
    function titulo2($titulo, $nivel = 1) {
        return "<h$nivel>$titulo</h$nivel>";
    }
}
$cargar = new cchdt();
$cargar->CargarExcel();
$mensaje = $cargar->mensaje;
$datosExcel = $cargar->datosExcel;
?>

    <div class="conte">
        <?php echo titulo2("<i class='".$icono."'></i> Cargar Hoja de Trabajo",2); ?>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="inser">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col-4">
                        <label for="archivo" class="form-label">Selecciona un archivo Excel:</label>
                        <input class="form-control" type="file" name="archivo" id="archivo" accept=".xls,.xlsx" required>
                    </div>
                    <div class="form-group col-md-4 d-flex align-items-end gap-1">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='EXCEL/hdt.XLS'" download>
                        <i class="fa-solid fa-download"></i>
                    </button>
                        <input type="submit" value="Subir" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if (empty($datosExcel)) : ?>
    <div class="card col-4">
        <img src="EXCEL/plt.jpg" class="card-img" alt="imagen de la hoja de trabajo">
        <div class="card-img"></div>
    </div>
    <?php endif; ?>

<?php if (!empty($datosExcel)) { ?>
<div class="conte mt-4">
    <h4>Datos del archivo Excel:</h4>
    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <?php foreach ($datosExcel[0] as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($datosExcel); $i++): ?>
                <tr>
                    <?php foreach ($datosExcel[$i] as $celda): ?>
                        <td><?= htmlspecialchars($celda) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>
<?php } ?>
</div>