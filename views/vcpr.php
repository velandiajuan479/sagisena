<?php
require_once 'controllers/ccpr.php';
?>

<div class="conte">
    <?php echo titulo2("<i class='fas fa-file-contract'></i> Generar Compromiso", 1); ?>
</div>

<div class="inser">
    <?php if ($mensaje): ?>
        <div class="alert alert-<?= strpos($mensaje, 'Error') === 0 ? 'danger' : 'success' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para generar compromiso -->
    <form id="frmins" action="home.php?pg=<?= $pg ?>" method="POST">
        <div class="row">
            <div class="form-group col-md-4">
                <label for="idcom">Tipo de Compromiso</label>
                <select name="idcom" id="idcom" class="form-control form-select" required>
                    <option value="">Seleccione</option>
                    <option value="p1com" <?= ($detalle && $detalle['p1com']) ? 'selected' : '' ?>>Mayor de Edad</option>
                    <option value="p2com" <?= ($detalle && $detalle['p2com']) ? 'selected' : '' ?>>Menor de Edad</option>
                </select>
            </div>
        
            <div class="form-group col-md-4">
                <label for="detfech">Fecha de Registro</label>
                <input type="date" name="detfech" id="detfech" class="form-control" value="<?= htmlspecialchars($detalle['detfech'] ?? date('Y-m-d')) ?>" required>
            </div>
            <div class="form-group col-md-12 mt-3">
                <input type="submit" class="btn btn-primary" name="action" value="<?= $detalle ? 'Actualizar' : 'Crear' ?>">
                <?php if ($detalle): ?>
                    <a href="home.php?pg=<?= $pg ?>" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Secciones del compromiso generado -->
<div class="detalles_compromiso mt-4">
    <?php if ($detalle): ?>
        <!-- Secciones para Mayor de Edad -->
        <?php if ($detalle['p1com']): ?>
            <div id="encabezado1" class="section">
                <h1>
                    <img src="img/logoSena.png" alt="Logo SENA" class="logo-h1">
                    <?= htmlspecialchars($detalle['p1com']) ?>
                </h1>
            </div>
            <div id="cuerpo1" class="section2">

            </div>
            <div id="pie1" class="firma-block">
                <div class="firma-item"><strong>FIRMA DEL APRENDIZ:</strong> ____________________________</div>
                <div class="firma-item"><strong>FECHA:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($detalle['detfech']))) ?></div>
            </div>
        <?php endif; ?>

        <!-- Secciones para Menor de Edad -->
        <?php if ($detalle['p2com']): ?>
            <div id="encabezado2" class="section">
                <h1>
                    <img src="img/logoSena.png" alt="Logo SENA" class="logo-h1">
                    <?= htmlspecialchars($detalle['p2com']) ?>
                </h1>
            </div>
            <div id="cuerpo2" class="section2">
                <?php include 'prueba/cprmen.php'; ?>
            </div>
            <div id="pie2" class="firma-block">
                <div class="firma-item"><strong>FIRMA DE: LA MADRE, EL PADRE O TUTOR (A):</strong> ____________________________</div>
                <div class="firma-item"><strong>FECHA:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($detalle['detfech']))) ?></div>
                <div class="small-text"><em>Únicamente en caso de que el aprendiz sea menor de edad...</em></div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">No se ha generado un compromiso aún. Por favor, complete el formulario.</div>
    <?php endif; ?>
</div>

<style>
    .conte { padding: 20px; }
    h1 { font-size: 20px; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .logo-h1 { height: 50px; width: auto; }
    .section { margin-bottom: 20px; font-size: 14px; border: 1px solid #ccc; padding: 10px; }
    .section2 { margin-bottom: 20px; font-size: 14px; border: 1px solid #ccc; padding: 10px; }
    .firma-block { margin-top: 20px; display: flex; flex-wrap: wrap; gap: 20px; font-size: 14px; border: 1px solid #ccc; padding: 10px; }
    .firma-item { flex: 1 1 40%; min-width: 200px; text-align: center; }
    .small-text { font-size: 12px; font-style: italic; }
</style>