<?php require_once("controllers/cregm.php"); ?>

<div class="conte">

    <!-- FORMULARIO REGISTRO INDIVIDUAL -->
    <div class="inser">
        <?php echo titulo2("<i class='fa-solid fa-pen-to-square'></i> Registro de Matrícula", 1); ?>
        <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="idusu">Usuario</label>
                    <!--
                    <select name="idusu" id="idusu" class="form-control form-select" required>
                        <?php if ($datUsu) { foreach ($datUsu as $dt) { ?>
                            <option value="<?= $dt["idusu"]; ?>" <?= ($dt["idusu"] == $idusu ? "selected" : "") ?>>
                                <?= $dt["ndocusu"] . " - " . $dt["nomusu"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                    -->
                </div>
                <div class="form-group col-md-4">
                    <label for="idfic">Ficha</label>
                    <!--
                    <select name="idfic" id="idfic" class="form-control form-select" required>
                        <?php if ($datFic) { foreach ($datFic as $dt) { ?>
                            <option value="<?= $dt["idfic"]; ?>" <?= ($dt["idfic"] == $idfic ? "selected" : "") ?>>
                                <?= $dt["idfic"] . " - " . $dt["nomfic"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                    -->
                </div>
                <div class="form-group col-md-4">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="iduxf" value="<?= $iduxf; ?>">
                    <button type="submit" class="btn btn-primary w-100">Enviar</button>
                </div>
            </div>
        </form>
    </div>

<!-- Button trigger modal  modalCmausu  CarExc -->
<span data-bs-toggle="modal" data-bs-target="#modalCmausu" title="Cargar listado de matriculas" style="cursor: pointer;">
  <i class="fa-solid fa-file-arrow-up fa-2x"></i>
</span>
<?php include("views/vcmausu.php"); ?>

<!-- Button trigger modal  modalCmausu  CarExc -->
<span data-bs-toggle="modal" data-bs-target="#modalCmausu2" title="Cargar listado de matriculas nueva plantilla" style="cursor: pointer;">
  <i class="fa-solid fa-file-arrow-up fa-2x" style="color: #ffffff;text-shadow: 0px 0px 2px #00af00, 0px 0px 2px #00af00, 0px 0px 2px #00af00, 0px 0px 2px #00af00;"></i>
</span>
<?php include("views/vcmausu2.php"); ?>

    <!-- SECCIÓN DE VISUALIZACIÓN DE DATOS -->
    <div class="card mt-4 p-3">
        <div class="row">
            <!-- GRÁFICO Y TOTAL -->
            <div class="d-flex align-items-center mt-4" style="gap: 2rem;">
                <div style="flex:0 0 auto; width:180px; height:180px;">
                    <canvas id="graficoCircular" width="200" height="200"></canvas>
                </div>
                <div class="alert alert-info text-center flex-grow-1" style="font-size:1.2rem;">
                    <strong>Total Fichas:</strong><br>
                    <span id="totalRegistros" style="font-size:2rem;"><?=count($datAll); ?></span>
                </div>
                <div class="alert alert-info text-center flex-grow-1" style="font-size:1.2rem;">
                    <strong>Total Registrados:</strong><br>
                    <span id="totalFichasBD" style="font-size:2rem;"><?=$datAllTot[0]['can'];?></span>
                </div>
                <div class="alert alert-info text-center flex-grow-1" style="font-size:1.2rem;">
                    <strong>No. Personas Doc. Completa:</strong><br>
                    <span id="totalFichasBD" style="font-size:2rem;"><?=$datAllTot[0]['can']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE USUARIOS REGISTRADOS -->
    <table id="example" class="table table-striped mt-5" style="width: 100%">
        <thead>
            <tr class="fila-ficha" data-idfic="<?= $dt['idfic']; ?>">

                <th>Ficha</th>
                <th>Cantidad Registrados</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($datAll) {
                foreach ($datAll as $dt) { ?>
                    <tr class="fila-ficha">
                        <td>
                            <strong><?= $dt["idfic"] . " - " . $dt["nomfic"]; ?></strong>
                            <br>
                            <strong>Jornada: </strong><?=$dt["nomval"]; ?>
                            <strong>Municipio: </strong><?=$dt["nommun"]; ?> - <?=$dt["nomdep"]; ?>
                        </td>
                        <td>
                            <?=$dt["can"]; ?>
                        </td>
                        <td>
                        </td>
                    </tr>
            <?php }} ?>
        </tbody>
        <thead>
            <tr class="fila-ficha" data-idfic="<?= $dt['idfic']; ?>">

                <th>Ficha</th>
                <th>Cantidad Registrados</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<!-- SCRIPT GRÁFICO DINÁMICO -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const datosFichas = <?= $graficaFichas; ?>;
const ctx = document.getElementById('graficoCircular').getContext('2d');

let grafico = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [],
        datasets: [{
            data: [],
            backgroundColor: ['#36A2EB'],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Función para actualizar la gráfica al seleccionar ficha
function actualizarGrafico(idFicha) {
    if (idFicha && datosFichas[idFicha]) {
        grafico.data.labels = [datosFichas[idFicha].nombre];
        grafico.data.datasets[0].data = [datosFichas[idFicha].cantidad];
    } else {
        // Si no hay selección, mostrar todas las fichas
        grafico.data.labels = [];
        grafico.data.datasets[0].data = [];

        for (let id in datosFichas) {
            grafico.data.labels.push(datosFichas[id].nombre);
            grafico.data.datasets[0].data.push(datosFichas[id].cantidad);
        }
    }
    grafico.update();
}

// Inicializar gráfica con todos los datos
actualizarGrafico("");

// Detectar cambio en el selector de ficha
document.getElementById('selectorFicha').addEventListener('change', function () {
    const fichaSeleccionada = this.value;
    actualizarGrafico(fichaSeleccionada);
});
</script>
<script>
document.getElementById('selectorFicha').addEventListener('change', function () {
    const idFichaSeleccionada = this.value;
    const filas = document.querySelectorAll('.fila-ficha');

    filas.forEach(fila => {
        const idFichaFila = fila.getAttribute('data-idfic');
        if (!idFichaSeleccionada || idFichaSeleccionada === idFichaFila) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
});
</script>

