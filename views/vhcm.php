<?php require_once("controllers/chcm.php"); ?>

<?php 
$icono = "fa-solid fa-clock-rotate-left";

if (!function_exists('titulo1')) {
    function titulo1($titulo, $nivel = 1) {
        return "<h$nivel>$titulo</h$nivel>";
    }
}
?>

<div class="conte">
    <?= titulo1("<i class='{$icono}'></i> Histórico de cursos", 1); ?>

    <?php if (isset($success) && $success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="inser">
        <form id="frmins" action="home.php?pg=<?= htmlspecialchars($pg); ?>" method="POST">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="codpro">Programa</label>
                    <select name="codpro" id="codpro" class="form-control form-select" required>
                        <?php if (isset($datPr) && is_array($datPr)) {
                            foreach ($datPr as $dt): ?>
                                <option value="<?= htmlspecialchars($dt["codpro"]); ?>">
                                    <?= htmlspecialchars($dt["nompro"]); ?>
                                </option>
                        <?php endforeach; } ?>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="idemp">Empresa</label>
                    <select name="idemp" id="idemp" class="form-control form-select" required>
                        <?php if (isset($datEm) && is_array($datEm)) {
                            foreach ($datEm as $dt): ?>
                                <option value="<?= htmlspecialchars($dt["idemp"]); ?>">
                                    <?= htmlspecialchars($dt["nomemp"]); ?>
                                </option>
                        <?php endforeach; } ?>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="codproesp">Programa Especial</label>
                    <select name="codproesp" id="codproesp" class="form-control form-select" required>
                        <?php if (isset($datPe) && is_array($datPe)) {
                            foreach ($datPe as $dt): ?>
                                <option value="<?= htmlspecialchars($dt["idval"]); ?>">
                                    <?= htmlspecialchars($dt["nomval"]); ?>
                                </option>
                        <?php endforeach; } ?>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="feclini">Fecha Inicial</label>
                    <input type="date" name="feclini" id="feclini" class="form-control"
                           value="<?= isset($dtaEdit) ? htmlspecialchars($dtaEdit['feclini']) : '' ?>" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="feclin">Fecha Final</label>
                    <input type="date" name="feclin" id="feclin" class="form-control"
                           value="<?= isset($dtaEdit) ? htmlspecialchars($dtaEdit['feclin']) : '' ?>" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="cupo">Cupo</label>
                    <input type="number" min="1" name="cupo" id="cupo" class="form-control"
                           value="<?= isset($dtaEdit) ? htmlspecialchars($dtaEdit['cupo']) : '' ?>" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="jornada">Jornada</label>
                    <select name="jornada" id="jornada" class="form-control form-select" required>
                        <?php if (isset($datJo) && is_array($datJo)) {
                            foreach ($datJo as $dt): ?>
                                <option value="<?= htmlspecialchars($dt["idval"]) ?>"

                                    <?= (isset($dtaEdit) && $dtaEdit['jornada'] == $dt['idval']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dt["nomval"]) ?>
                                </option>
                        <?php endforeach; } ?>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <br>
                    <input type="submit" class="btn btn-primary" value="<?= isset($dtaEdit) ? 'Actualizar' : 'Guardar' ?>">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idnorad" value="<?= isset($dtaEdit) ? htmlspecialchars($dtaEdit['idnorad']) : '' ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Historial</th>
            <th>Jornada</th>
            <th>Cupo</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($datAll) && is_array($datAll)) {
            foreach ($datAll as $dta): ?>
                <tr>
                    <td>
                        <strong>Curso No. <?= htmlspecialchars($dta["idnorad"]); ?></strong><br>
                        <strong>Programa:</strong> <?= htmlspecialchars($dta["codpro"]); ?> - <?= htmlspecialchars($dta["nompro"]); ?><br>
                        <strong>Empresa:</strong> <?= htmlspecialchars($dta["nomemp"]); ?><br>
                        <strong>Fechas:</strong> <?= htmlspecialchars($dta["feclini"]); ?> / <?= htmlspecialchars($dta["feclin"]); ?>
                        <strong>Ficha:</strong> <?= htmlspecialchars($dta["nomfic"]); ?>
                    </td>
                    <td>
                        <strong>Jornada:</strong> <?= htmlspecialchars($dta["jornada"]); ?>
                    </td>
                    <td>
                        <strong>Cupo:</strong> <?= htmlspecialchars($dta["cupo"]); ?>
                    </td>
                </tr>
        <?php endforeach; } ?>
    </tbody>
</table>
