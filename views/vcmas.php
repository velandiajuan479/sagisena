<?php include("controllers/ccmas.php"); ?>
<?php include("controllers/cplaneacion.php"); ?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Carga Masiva - Programas y Competencias / Planeación",2); ?>

    <!-- Botón para abrir la ventana modal -->
    <div class="text-center mb-4">
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalSubida">
            📂 Subir Archivos
        </button>
    </div>

    <!-- Modal de Subida -->
    <div class="modal fade" id="modalSubida" tabindex="-1" aria-labelledby="modalSubidaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalSubidaLabel">Subir archivos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <div class="row">
                        <div>Para estar seguro de los archivos verifique en vista previa</div>
                        <!-- Formulario Programas/Competencias -->
                        <div class="col-md-6 border-end">
                            <form id="frmCmas" name="frmCmas" action="home.php?pg=<?=$pag;?>" method="POST" enctype="multipart/form-data" class="text-center">
                                <div class="mb-3">
                                    <label for="EXCEL_CMAS" class="form-label fw-bold">Cargar Archivo Agendas</label>
                                    <input type="file" name="EXCEL_CMAS" id="EXCEL_CMAS" class="form-control text-center" accept=".xls,.xlsx" required>
                                </div>
                                <input type="submit" class="btn btn-success mt-2 w-100" name="btnCmas" value="Subir Agenda">
                            </form>
                        </div>

                        <!-- Formulario Planeación -->
                        <div class="col-md-6">
                            <div>Para estar seguro de los archivos verifique en vista previa</div>
                            <form id="frmPlaneacion" name="frmPlaneacion" action="home.php?pg=<?=$pag;?>" method="POST" enctype="multipart/form-data" class="text-center">
                                <div class="mb-3">
                                    <label for="EXCEL_PLANE" class="form-label fw-bold">Cargar Archivo Planeación Proyecto Formativo</label>
                                    <input type="file" name="EXCEL_PLANE" id="EXCEL_PLANE" class="form-control text-center" accept=".xls,.xlsx" required>
                                </div>
                                <input type="submit" class="btn btn-success mt-2 w-100" name="btnPlaneacion" value="Subir Planeación">
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Información -->
<div class="modal fade" id="modalInfoSubida" tabindex="-1" aria-labelledby="modalInfoSubidaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalInfoSubidaLabel">Información de la Subida</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <p>
          ✅ El archivo se está subiendo correctamente.  

        </p>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Descargas -->
<div class="container mt-4">
    <div class="p-4 border rounded bg-light">
        <div class="row justify-content-center text-center">
            <div class="col-md-5 mb-4">
                <div>Agendas</div>
                <img src="img/imagenagendas.png" class="img-fluid mb-3" alt="Imagen 1">
                <a href="EXCEL/1.GPFI-F-018_PPPF-2022 2023 - 2773071A ADSO M.xlsx" download class="btn btn-success">
                    <i class="bi bi-download"></i> Descargar 
                </a>
            </div>
            <div class="col-md-5 mb-4">
                <div>Planeación Pedagógica</div>
                <img src="img/imagenpedagogia.png" class="img-fluid mb-3" alt="Imagen 2">
                <a href="EXCEL/GPFI-F-134PlaneacionPedagogicaProyectoFormativo.xlsx" download class="btn btn-success">
                    <i class="bi bi-download"></i> Descargar 
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alert -->
<div id="alertSuccess" class="alert alert-success mt-3" style="display:none;">
    ✅ El archivo se subió correctamente.
</div>

<!-- Vista previa -->
<div class="mt-3">
    <h5>Vista previa del archivo Excel:</h5>
    <div id="preview" style="overflow:auto; max-height:600px; border:1px solid #ddd; padding:1rem; background:#fff; font-size:13px;"></div>
</div>

<!-- Dependencia SheetJS -->
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<script>
['EXCEL_CMAS', 'EXCEL_PLANE'].forEach(function(id) {
    const input = document.getElementById(id);
    if (!input) return;

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewDiv = document.getElementById('preview');
        const alertDiv = document.getElementById('alertSuccess');

        if (!file) {
            previewDiv.innerHTML = '';
            alertDiv.style.display = "none";
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            const data = new Uint8Array(event.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

            if (jsonData.length === 0) {
                previewDiv.innerHTML = '<p>El archivo está vacío.</p>';
                alertDiv.style.display = "none";
                return;
            }

            let table = '<table class="table table-bordered table-sm"><thead><tr>';
            jsonData[0].forEach(function(headCell) {
                table += `<th>${headCell}</th>`;
            });
            table += '</tr></thead><tbody>';

            for (let i = 1; i < jsonData.length; i++) {
                table += '<tr>';
                jsonData[i].forEach(function(cell) {
                    table += `<td>${cell !== undefined ? cell : ''}</td>`;
                });
                table += '</tr>';
            }
            table += '</tbody></table>';

            previewDiv.innerHTML = table;
            alertDiv.style.display = "block";
        };

        reader.readAsArrayBuffer(file);
    });
});


// Mostrar modal de información al enviar formularios
['frmCmas', 'frmPlaneacion'].forEach(function(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function() {
        // Cierra modal de subida
        var subidaModal = bootstrap.Modal.getInstance(document.getElementById('modalSubida'));
        if (subidaModal) subidaModal.hide();

        // Abre modal de información
        var infoModal = new bootstrap.Modal(document.getElementById('modalInfoSubida'), {
            backdrop: 'static',   // no se cierra al hacer clic fuera
            keyboard: true        // se puede cerrar con ESC
        });
        infoModal.show();
    });
});
</script>
