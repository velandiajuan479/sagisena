<?php require_once("controllers/cdoc.php"); ?>
<style>
#form-doc { display: block !important; }
</style>
<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Documento",2); ?>
    <div class="inser border border-success p-4 mb-4 bg-light">
        <h4 class="mb-3">Registrar/Editar Documento</h4>
        <form id="form-doc" action="index.php" method="POST" autocomplete="off">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="iddoc">ID Documento</label>
                    <input type="text" name="iddoc" id="iddoc" class="form-control"
                        value="<?php if($datOne) echo $datOne[0]['iddoc']; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="nomdoc">Nombre del Documento</label>
                    <input type="text" name="nomdoc" id="nomdoc" class="form-control"
                        value="<?php if($datOne) echo $datOne[0]['nomdoc']; ?>" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="idnorad">Número de Radicado</label>
                    <input type="text" name="idnorad" id="idnorad" class="form-control"
                        value="<?php if($datOne) echo $datOne[0]['idnorad']; ?>" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <input type="submit" value="Guardar" class="btn btn-success">
                    <input type="hidden" name="opera" value="save">
                </div>
            </div>
        </form>
    </div>
</div>

<table class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del Documento</th>
            <th>Número de Radicado</th>
        </tr>
    </thead>
    <tbody>
        <?php if($datAll){ foreach($datAll as $d){ ?>
            <tr>
                <td><?= $d['iddoc']; ?></td>
                <td><?= $d['nomdoc']; ?></td>
                <td><?= $d['idnorad']; ?></td>
            </tr>
        <?php }} else { ?>
            <tr><td colspan="3">No hay documentos registrados.</td></tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Nombre del Documento</th>
            <th>Número de Radicado</th>
        </tr>
    </tfoot>
</table>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">