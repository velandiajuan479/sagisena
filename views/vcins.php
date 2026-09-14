    <?php
    require_once 'controllers/ccins.php';
    ?>

    <?php echo titulo2("<i class='" . $icono . "'></i> Instrumento de evaluación", 0); ?>
            <div>
                <table class="table table-striped" style="width:100%">
                    <tr>
                        <th colspan='8' style="font-size:30px; font-weight:bold; text-align:center;">
                            FICHA <?= htmlspecialchars($idfic) ?> - <?= htmlspecialchars($nomfic) ?>
                        </th>
                    </tr>
                </table>
            </div>
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php } ?>
        

    <div class="conte container-form-pages">
        <form class="form-default-pages" id="frm1" action="home.php?pg=<?= $pg ?>" method="POST"
            enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nomins" class="fw-semibold">Nombre del Instrumento</label>
                    <input type="text" class="form-control form--input-default" name="nomins" id="nomins"
                        value="<?= isset($dtOne['nomins']) ? htmlspecialchars($dtOne['nomins']) : '' ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <br>
                    <input type="hidden" name="ope" value="save">
                    <input type="hidden" name="idins" value="<?= isset($dtOne['idins']) ? $dtOne['idins'] : '' ?>">
                    <input type="hidden" name="idres" value="<?= $idres ?>">
                    <input type="hidden" name="idfic" value="<?= $idfic ?>">
                    <input type="submit" class="btn btn-success" value="Enviar">
                </div>
            </div>
        </form>
        <div class="form-group" style="width: 100%;">
            <br>
            <form name="frm1" action="home.php?pg=1545&idres=<?= $idres ?>&idfic=<?= $idfic ?>" method="POST"
                enctype="multipart/form-data" class="needs-validation form-default-pages" novalidate>
                <div class="row gap-4">
                    <div class="form-group col-md-12">
                        <div class="upload-container">
                            <label for="EXCEL" class="upload-label fw-semibold">Selecciona el archivo Excel</label>
                            <div class="upload-wrapper">
                                <div class="upload-dropzone">
                                    <input type="file" class="upload-input" name="EXCEL" id="EXCEL"
                                        accept=".xls,.xlsx" required />

                                    <label for="fileInput" class="upload-preview">
                                        <i class="fa-solid fa-cloud-arrow-up upload-info-icon"></i>
                                    </label>

                                    <p class="upload-hint" id="fileName">Arrastre y suelte o haga clic para cargar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <button type="submit" class="btn btn-success text-nowrap" style="white-space: nowrap;">
                            <i class="bi bi-upload me-2"></i> Subir Archivo
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Nombre</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dat)
                foreach ($dat as $dta) { ?>
                    <tr>
                        <td><?= htmlspecialchars($dta['nomins']) ?></td>
                        <td style="text-align:center;" title="Crear Criterios">
                            <a href="home.php?pg=1546&idins=<?= $dta['idins'] ?>&idfic=<?= $idfic ?>">
                                <i class="fa-solid fa-pencil fa-2x"></i>
                            </a>
                        </td>
                        <td style="text-align:right;">
                            <a href="home.php?pg=1545&idins=<?= $dta['idins'] ?>&idres=<?= $idres ?>&idfic=<?= $idfic ?>&ope=edi" title="Editar">
                                <i class="fa-solid fa-pen-to-square fa-2x"></i>
                            </a>
                            <a href="home.php?pg=<?= $pg ?>&idins=<?= $dta['idins'] ?>&idres=<?= $idres ?>&idfic=<?= $idfic ?>&ope=del"
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
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <script>
    previewFile('EXCEL')
    </script>