<?php
require_once 'controllers/csersop.php';
?>
<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Servicio Soporte", 1); ?>
    <div class="inser container-form-pages">
        <form class="form-default-pages" id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-md-4">
                    <div class="upload-container">
                        <label for="evisop" class="upload-label fw-semibold">Foto Evidencia</label>
                        <div class="upload-wrapper">
                            <div class="upload-dropzone">
                                <?php if ($dtOne and $dtOne[0]['evisop']) { ?>
                                    <input type="hidden" name="evisop" id="evisop" value="<?= $dtOne[0]['evisop']; ?>">
                                <?php } ?>
                                <input type="file" class="upload-input" name="foto" id="evisop"
                                    accept="image/png, image/jpeg" />

                                <label for="fileInput" class="upload-preview">
                                    <i class="fa-solid fa-cloud-arrow-up upload-info-icon"></i>
                                </label>

                                <p class="upload-hint" id="fileName">Arrastre y suelte o haga clic para cargar</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row gap-4 justify-content-between">
                        <div class="form-group col-md-6">
                            <label for="idusu" class="fw-semibold">Nombre de quien atiende</label>
                            <select name="idusu" id="idusu" class="form-control form-select form--input-default">
                                <?php if ($dtSelsop) {
                                    foreach ($dtSelsop as $dtsp) { ?>
                                        <option value="<?= $dtsp['idusu']; ?>" <?php if ($dtOne && $dtOne[0]['nomusu'] == $dtsp['idusu'])
                                            echo "selected"; ?>><?= $dtsp['nomusu']; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="falrep" class="fw-semibold">Falla Reportada</label>
                            <select name="falrep" id="falrep" class="form-control form-select form--input-default">
                                <?php if ($dtFalla) {
                                    foreach ($dtFalla as $dtFll) { ?>
                                        <option value="<?= $dtFll['idval']; ?>" <?php if ($dtOne && $dtOne[0]['falrep'] == $dtFll['idval'])
                                            echo "selected"; ?>><?= $dtFll['nomval']; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-8">
                            <label for="nomper" class="fw-semibold">Nombres de quien recibe el servicio</label>
                            <input type="text" name="nomper" id="nomper"
                                class="form-control ui-autocomplete-input form--input-default"
                                value="<?= $dtOne && $dtOne[0]['nomper'] ? htmlspecialchars($dtOne[0]['nomper']) : '' ?>"
                                autocomplete="off">
                            <input type="hidden" name="idusu2" id="idusu2"
                                value="<?= $dtOne && $dtOne[0]['idusu2'] ? $dtOne[0]['idusu2'] : '' ?>">
                            <div id="autocomplete-error" class="text-danger small mt-1" style="display:none;"></div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="carper" class="fw-semibold">Cargo</label>
                            <select name="carper" id="carper" class="form-control form-select form--input-default">
                                <?php if ($dtCargo) {
                                    foreach ($dtCargo as $dtCr) { ?>
                                        <option value="<?= $dtCr['idval']; ?>" <?php if ($dtOne && $dtOne[0]['carper'] == $dtCr['idval'])
                                            echo "selected"; ?>><?= $dtCr['nomval']; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-9">
                            <label for="desser" class="fw-semibold">Descripción del servicio prestado</label>
                            <textarea name="desser" id="desser" class="form-control form--input-default"><?php if ($dtOne && $dtOne[0]['desser'])
                                echo $dtOne[0]['desser']; ?></textarea>
                        </div>
                        <div class="form-group col">
                            <br>
                            <input class="btn btn-success" type="submit" value="Enviar">
                            <input type="hidden" name="ope" value="save">
                            <input type="hidden" name="idsop" id="idsop" value="<?php if ($dtOne)
                                echo $dtOne[0]['idsop']; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Servicio Soporte</th>
            <th>Detalles</th>
            <th>Evidencia servicio</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($dat) {
            foreach ($dat as $dt) { ?>
                <tr>
                    <td>
                        <strong>
                            <?= $dt["idsop"]; ?> - <?= $dt["nomusu"]; ?>
                        </strong>
                        <br>

                        <?= $dt["nom_carper"]; ?> - <?= $dt["nomper"]; ?><br>
                        Fecha de inicio: <?= $dt["fecserini"]; ?><br>
                        Fecha de fin: <?= $dt['fecserfin']; ?><br>

                        <strong>
                            <?= $dt["nom_ultimo_estado"]; ?>
                        </strong>
                    </td>
                    <td>
                        <b>Tipo:</b> <?= $dt["nom_falrep"]; ?><br>
                        <b>Descripción:</b> <br>
                        <?= $dt["desser"]; ?>
                    </td>
                    <td class="img-cell">
                        <?php if (file_exists($dt['evisop'])) { ?>
                            <img src="<?= $dt['evisop']; ?>" alt="Evidencia" class="zoom-img" width="100%">
                        <?php } ?>
                    </td>
                    <td>
                        <a href="home.php?pg=2204&idsop=<?= $dt["idsop"]; ?>" title="Seguimiento">
                            <i class="fa-solid fa-magnifying-glass fa-2x"></i>
                        </a>
                        <?php if(isset($_SESSION['idper']) && ($_SESSION['idper'] == 38)) { ?>
                            <a href="home.php?pg=<?=$pg;?>&idsop=<?=$dt['idsop'];?>&ope=del" title="Eliminar">
                                <i class="fa-solid fa-trash-can fa-2x"></i>
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php }
        } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Servicio Soporte</th>
            <th>Detalles</th>
            <th>Evidencia servicio</th>
            <th></th>
        </tr>
    </tfoot>
</table>
<div id="lightbox" class="lightbox">
    <span class="close">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
</div>

<script>
    $(document).ready(function () {
        $("#nomper").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "controllers/api_autocomplete.php",
                    data: { term: request.term },
                    dataType: "json",
                    type: "GET",
                    beforeSend: function () {
                        $("#nomper").addClass('ui-autocomplete-loading');
                        $("#autocomplete-error").hide();
                    },
                    complete: function () {
                        $("#nomper").removeClass('ui-autocomplete-loading');
                    },
                    success: function (data) {
                        if (data && data.status === 'success') {
                            var items = $.map(data.data, function (item) {
                                return {
                                    label: item.nomusu,
                                    value: item.nomusu,
                                    idusu: item.idusu
                                };
                            });
                            response(items.length > 0 ? items : [{
                                label: "No se encontraron coincidencias",
                                value: "",
                                disabled: true
                            }]);
                        } else {
                            showAutocompleteError("Error en el formato de respuesta");
                            response([]);
                        }
                    },
                    error: function (xhr, status, error) {
                        showAutocompleteError("Error al cargar sugerencias");
                        response([]);
                    }
                });
            },
            minLength: 2,
            delay: 300,
            select: function (event, ui) {
                if (!ui.item.disabled) {
                    $("#nomper").val(ui.item.value);
                    $("#idusu_per").val(ui.item.idusu);
                    $("#idusu2").val(ui.item.idusu);
                }
                return false;
            }
        });

        function showAutocompleteError(message) {
            $("#autocomplete-error").text(message).show();
        }

        window.eli = function (link) {
            event.preventDefault(); // Previene el comportamiento por defecto del enlace

            Swal.fire({
                title: '¿Está seguro que desea eliminar este registro?',
                text: 'Verifique antes de continuar',
                icon: 'question',
                width: '500px',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                confirmButtonColor: '#00af00',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirige al enlace original si confirma
                    window.location.href = link.href;
                }
            });

            return false;
        };

    });
    previewImgTable();

    previewFile('evisop')
</script>