<?php
require_once 'controllers/ccce.php';
$idres = 0;
?>
<?php echo titulo2("<i class='" . $icono . "'></i> Crear Criterio de Evaluacion", 0); ?>
            <div>
                <table class="table table-striped" style="width:100%">
                    <tr>
                        <th colspan='8' style="font-size:30px; font-weight:bold; text-align:center;">
                            FICHA <?= htmlspecialchars($idfic) ?> - <?= htmlspecialchars($nomfic) ?>
                        </th>
                    </tr>
                </table>
            </div>
<div class="conte container-form-pages">
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php } ?>

    <form class="form-default-pages" id="frm1" action="home.php?pg=<?= $pg; ?>" method="POST"
        enctype="multipart/form-data">
        <div id="forms-container">
            <?php
$formularios = [];

if (!empty($form_data)) {
    $formularios = $form_data;
} elseif (!empty($dtOne)) {
    $formularios = $dtOne; // <-- Aquí cargamos todos los criterios del instrumento
} else {
    $formularios = [[]]; // Vacío para nuevo
}


            foreach ($formularios as $f) {
                ?>
                <div class="row criterio-form mb-3">
                    <div class="form-group col-md-4">
                        <label for="" class="fw-semibold">Nombre del Criterio</label>
                        <input type="text" class="form-control form--input-default" name="nomcri[]"
                            value="<?= htmlspecialchars($f['nomcri'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="" class="fw-semibold">Valor si Cumple</label>
                        <input type="number" class="form-control form--input-default" name="vscum[]"
                            value="<?= htmlspecialchars($f['vscum'] ?? '') ?>" required max="100">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="" class="fw-semibold">Valor Parcialmente</label>
                        <input type="number" class="form-control form--input-default" name="vpar[]"
                            value="<?= htmlspecialchars($f['vpar'] ?? '') ?>" required>
                    </div>
                    <input type="hidden" name="idcri[]" value="<?= htmlspecialchars($f['idcri'] ?? '') ?>">
                </div>
            <?php } ?>

            <div class="col">
                <input type="hidden" name="ope" value="save">
                <input type="hidden" name="idins" value="<?= $idins ?>">
                <input type="hidden" name="idfic" value="<?= $idfic ?>">
            </div>
        </div>

        <div class="form-group mt-3 d-flex justify-content-center gap-3">
            <button type="button" id="add-form-btn" class="btn btn-success">Agregar otro criterio</button>
            <input type="submit" class="btn btn-success" value="Enviar">
        </div>
    </form>


</div>

<!-- Tabla de criterios existentes -->
<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Si Cumple</th>
            <th>Parcialmente</th>
            <th>No Cumple</th>
            <th colspan="5"></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($dat)
            foreach ($dat as $dta) { ?>
                <tr>
                    <td><?= $dta['nomcri']; ?></td>
                    <td><?= $dta['vscum']; ?></td>
                    <td><?= $dta['vpar']; ?></td>
                    <td><?= $dta['vncum']; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:center;">
                        <a href="home.php?pg=1547&idfic=<?= $dta['idfic'] ?>">
                            <i class="fa-solid fa-pencil fa-2x"></i>
                        </a>
                    </td>
                    <td style="text-align:right;">
                        <a href="home.php?pg=<?= $pg; ?>&idcri=<?= $dta['idcri']; ?>&idins=<?= $idins ?>&idfic=<?= $idfic ?>&ope=edi"
                            title="Editar">
                            <i class="fa-solid fa-pen-to-square fa-2x"></i>
                        </a>
                        <a href="home.php?pg=<?= $pg ?>&idcri=<?= $dta['idcri'] ?>&idins=<?= $idins ?>&idfic=<?= $idfic ?>&ope=del"
                            title="Eliminar" onclick="return eliminar(this);">
                            <i class="fa-solid fa-trash-can fa-2x"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Nombre</th>
            <th>Si Cumple</th>
            <th>Parcialmente</th>
            <th>No Cumple</th>
            <th colspan="5"></th>
        </tr>
    </tfoot>
</table>

<!-- Script para clonar formularios -->
<script>
    document.getElementById('add-form-btn').addEventListener('click', function () {
        const container = document.getElementById('forms-container');
        const original = container.querySelector('.criterio-form');
        const clone = original.cloneNode(true);

        // Limpiar inputs
        clone.querySelectorAll('input').forEach(input => {
            if (input.type !== 'hidden') {
                input.value = '';
            } else if (input.name === 'idcri[]') {
                input.value = '';
            }
        });

        // Eliminar etiquetas <label>
        clone.querySelectorAll('label').forEach(label => label.remove());

        // Crear contenedor padre
        const rowWrapper = document.createElement('div');
        rowWrapper.className = 'row align-items-center mb-3';

        // Crear columna principal
        const formCol = document.createElement('div');
        formCol.className = 'col';
        formCol.appendChild(clone);

        // Crear botón eliminar
        if (!clone.querySelector('.remove-criterio-btn')) {
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger remove-criterio-btn';
            removeBtn.innerHTML = '<i class="fa-solid fa-trash-can fa-lg"></i>';
            removeBtn.addEventListener('click', () => rowWrapper.remove());

            const buttonCol = document.createElement('div');
            buttonCol.className = 'col-auto';
            buttonCol.appendChild(removeBtn);

            rowWrapper.appendChild(formCol);
            rowWrapper.appendChild(buttonCol);
        }

        container.appendChild(rowWrapper);
    });
</script>

<script>
    document.querySelectorAll('.open-modal').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const idcri = this.dataset.idcri;
            const idins = this.dataset.idins;
            const idfic = this.dataset.idfic;
            const pg = this.dataset.pg;

            const url = `controllers/ccce.php?ope=getEditForm&idcri=${idcri}&idins=${idins}&idfic=${idfic}&pg=${pg}`;

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Agregamos el modal al final del body
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    document.body.appendChild(tempDiv);

                    // Buscamos el ID del modal
                    const modalElement = tempDiv.querySelector('.modal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Eliminar el modal del DOM cuando se cierre para evitar duplicados
                    modalElement.addEventListener('hidden.bs.modal', () => {
                        modal.dispose();
                        tempDiv.remove();
                    });
                })
                .catch(err => {
                    console.error('Error al cargar el formulario:', err);
                });
        });
    });
</script>