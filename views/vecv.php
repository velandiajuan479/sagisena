<?php require_once 'controllers/cecv.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Selección de Voceros", 2); ?>
    <?php if (isset($_REQUEST['idficfil'])): ?>
        <div class="alert alert-info">
            <strong>Nota:</strong> Puedes seleccionar máximo 4 candidatos a vocero por ficha.
        </div>
    <?php endif; ?>
</div>
<section class="container-form-pages">
    <!-- Filtro por ficha -->
    <form class="form-default-pages" name="frm1" action="home.php?pg=<?= $pg; ?>" method="POST">
        <input type="hidden" name="pg" value="<?= $pg; ?>">
        <div class="row">
            <div class="form-group col">
                <label for="combobox" class="fw-semibold">Seleccione Ficha para ver aprendices</label>
                <select name="idficfil" id="ficha-selector" class="form-select ficha-select  form--input-default"
                    onchange="this.form.submit();" required>
                    <option value="">-- Seleccione una ficha --</option>
                    <?php
                    if ($dfi) {
                        foreach ($dfi as $de) {
                            ?>
                            <option value="<?= $de['idfic']; ?>" <?php if (isset($_REQUEST['idficfil']) && $_REQUEST['idficfil'] == $de['idfic'])
                                  echo " selected "; ?>><?= $de['idfic']; ?> -
                                <?= $de['nomfic']; ?>
                                (<?= $de['nomval']; ?>)</option>
                        <?php }
                    } ?>
                </select>

            </div>
        </div>
    </form>

</section>
<?php
if (isset($_REQUEST['idficfil']) && !empty($_REQUEST['idficfil'])):
    // obtiene aprendices de la ficha seleccionada
    $idficfil = $_REQUEST['idficfil'];
    $dat = $mecv->getAprendicesPorFicha($idficfil);

    // obtiene conteo de candidatos actuales
    $conteoCandidatos = $mecv->contarCandidatosVocero($idficfil);
    ?>
    <div class="card mt-4">
        <div class="card-header text-black">
            <h4>Aprendices de la Ficha <?= $idficfil; ?>
                <span class="badge bg-secondary float-end">
                    Candidatos: <?= $conteoCandidatos; ?>/4
                </span>
            </h4>
        </div>
        <div class="card-body">
            <?php if ($dat && count($dat) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Acción</th>
                            <th>Estado</th>
                            <th>Foto</th>
                            <th>Aprendiz</th>
                            <th>Documento</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dat as $d): ?>
                            <tr>
                                <td>
                                    <?php if ($d['es_candidato'] > 0 || $d['idper'] == 13): ?>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmarEliminarVocero(<?= $d['idusu']; ?>, '<?= addslashes($d['nomusu']); ?>')">
                                            <i class="fas fa-user-minus"></i> Quitar
                                        </button>
                                    <?php else: ?>
                                        <?php if ($conteoCandidatos < 4): ?>
                                            <button class="btn btn-sm btn-success"
                                                onclick="confirmarVocero(<?= $d['idusu']; ?>, '<?= addslashes($d['nomusu']); ?>')">
                                                <i class="fas fa-user-plus"></i> Seleccionar
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                <i class="fas fa-ban"></i> Límite alcanzado
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($d['actusu'] == 1): ?>
                                        <i class="fas fa-circle-check text-success"></i> Activo
                                    <?php else: ?>
                                        <i class="fas fa-circle-xmark text-danger"></i> Inactivo
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($d['fotcan']) && file_exists($d['fotcan'])): ?>
                                        <img src="<?= $d['fotcan']; ?>" width="50" height="50" class="rounded-circle">
                                    <?php else: ?>
                                        <img src="img/user.jpg" width="50" height="50" class="rounded-circle">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($d['nomusu']); ?></td>
                                <td><?= $d['ndocusu']; ?></td>
                                <td>
                                    <?= $d['nomper']; ?>
                                    <?php if ($d['es_candidato'] > 0 || $d['idper'] == 13): ?>
                                        <span class="badge bg-success ms-2">Candidato</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <div class="alert alert-info">
                    No se encontraron aprendices en esta ficha
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
     $(document).ready(function () {
        $('.ficha-select').select2({
            placeholder: "Buscar ficha...",
            allowClear: true,
            width: '100%'
        });
    });
    function confirmarVocero(idusu, nombre) {
        if (confirm(`¿Estás seguro de elegir a ${nombre} como candidat@ a vocer@?`)) {
            //envio la solicitud a ajax para no recargar la pagina
            fetch(`home.php?pg=<?= $pg; ?>&idusu=${idusu}&opera=make_vocero&idficfil=<?= isset($_REQUEST['idficfil']) ? $_REQUEST['idficfil'] : ''; ?>`)
                .then(response => response.text())
                .then(data => {
                    location.reload();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar',
                        confirmButtonText: 'Aceptar'
                    });
                });
        }
    }
    function confirmarEliminarVocero(idusu, nombre) {
        if (confirm(`¿Estás seguro de quitar a ${nombre} como candidat@ a vocer@?\n\nSe cambiará su perfil a aprendiz pero permanecerá en la lista.`)) {
            fetch(`home.php?pg=<?= $pg; ?>&idusu=${idusu}&opera=remove_vocero&idficfil=<?= $_REQUEST['idficfil'] ?? ''; ?>`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.text();
                })
                .then(data => {
                    location.reload();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al eliminar',
                        text: 'Ocurrió un error al eliminar: ' + error.message,
                        confirmButtonText: 'Aceptar'
                    });
                });
        }
    }
</script>

<style>
    table td button {
        position: static !important;
        float: none !important;
        display: inline-block !important;
        margin: 0 !important;
    }
</style>