<?php
require_once(__DIR__ . '/../models/muasp.php');

$mensaje = '';
if (isset($_GET['ok']) && $_GET['ok'] == 1) {
    $mensaje = '<div class="alert alert-success">¡Aspirante registrado correctamente!</div>';
} elseif (isset($_GET['error']) && $_GET['error'] == 'doc') {
    $mensaje = '<div class="alert alert-danger">El documento debe ser numérico.</div>';
}
?>

<h2>Registro de Aspirante</h2>
<?= $mensaje ?>
<form action="procesar_registro.php" method="post">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="form-group col-md-4">
            <label for="documento">Número de Documento:</label>
            <input type="number" name="documento" id="documento" class="form-control" required min="1" step="1">
        </div>

        <div class="form-group col-md-4">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group col-md-4">
            <label for="tipoId">Tipo de Identificación:</label>
            <select name="tipoId" id="tipoId" class="form-control" required>
                <option value="">Seleccione...</option>
                <option value="TI">Tarjeta de Identidad</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="CE">Cédula de Extranjería</option>
                <option value="PA">Pasaporte</option>
            </select>
        </div>

        <div class="form-group col-md-12">
            <br>
            <button type="submit" class="btn btn-primary">Registrar Aspirante</button>
            <a href="vuasp.php" class="btn btn-secondary">Nuevo Registro</a>
        </div>
    </div>
</form>