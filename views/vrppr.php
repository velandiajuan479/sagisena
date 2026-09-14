<?php require_once 'controllers/crppr.php'; ?> 

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Reporte Estadistico Persona",1); ?>
    <div class="container my-4" id="frmins">
        <div class="mb-3">
            <label for="filterSelect" class="form-label">Filtrar por:</label>
            <select id="filterSelect" class="form-select" style="width: 150px;">
                <option value="">Todos</option>
                <option value="day">Día</option>
                <option value="month">Mes</option>
                <option value="year">Año</option>
            </select>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <canvas id="graficoSoporte" style="height:300px; width:300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <canvas id="graficoCargo" style="height:300px; width:300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Servicio Soporte</th>
            <th>Evidencia servicio</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if($dtRppr) { foreach ($dtRppr as $dtrp) { ?>
            <tr>
                <td>
                    <strong>
                        <?=$dtrp["idsop"];?> - <?=$dtrp["nomusu"];?>
                    </strong>
                    <br>
                        Falla Reportada: <?=$dtrp["nom_falrep"];?><br>
                        <?=$dtrp["nom_carper"];?> - <?=$dtrp["nomper"];?><br> 
                        Fecha de Inicio: <?=$dtrp["fecserini"];?><br>
                        Fecha de Fin: <?=$dtrp["fecserfin"];?><br>
                        Descripcion: <?=$dtrp["desser"];?><br>
                    <strong>
                        <?=$dtrp["nom_ultimo_estado"];?>
                    </strong>
                </td>
                <td class="img-cell">
                    <?php if(file_exists($dtrp['evisop'])){ ?>
                        <img src="<?=$dtrp['evisop'];?>" alt="Evidencia" class="zoom-img" width="100%"; />
                    <?php } ?>
                </td>
                <td>
                    <a href="home.php?pg=2204&idsop=<?=$dtrp["idsop"];?>" title="Seguimiento">
                        <i class="fa-solid fa-magnifying-glass fa-2x"></i>
                    </a>
                    <?php if(isset($_SESSION['idper']) && ($_SESSION['idper'] == 38)) { ?>
                        <a href="home.php?pg=<?=$pg;?>&idsop=<?=$dtrp['idsop'];?>&ope=del" title="Eliminar">
                            <i class="fa-solid fa-trash-can fa-2x"></i>
                        </a>
                    <?php } ?>
                </td>
            </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Servicio Soporte</th>
            <th>Evidencia Soporte</th>
            <th></th>
        </tr>
    </tfoot>
</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let filter = '';
    let chartSoporte, chartCargo, chartUsuario;

    function fetchDataAndRenderCharts() {
        fetch(`controllers/crppr.php?grafico=por_falla&filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.falla);
                const valores = data.map(item => item.total);
                if(chartSoporte) {
                    chartSoporte.data.labels = labels;
                    chartSoporte.data.datasets[0].data = valores;
                    chartSoporte.update();
                } else {
                    chartSoporte = new Chart(document.getElementById('graficoSoporte'), {
                        type: 'pie',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Soportes por Falla',
                                data: valores,
                                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40'],
                                borderWidth: 1
                            }]
                        },
                        options: { 
                            responsive: true, 
                            plugins: { 
                                title: {
                                    display: true,
                                    text: 'Soportes por Tipo de Falla'
                                },
                                legend: { position: 'bottom' } 
                            } 
                        }
                    });
                }
            });

        fetch(`controllers/crppr.php?grafico=por_cargo&filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.cargo);
                const valores = data.map(item => item.total);
                if(chartCargo) {
                    chartCargo.data.labels = labels;
                    chartCargo.data.datasets[0].data = valores;
                    chartCargo.update();
                } else {
                    chartCargo = new Chart(document.getElementById('graficoCargo'), {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Soportes por Cargo',
                                data: valores,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderWidth: 1
                            }]
                        },
                        options: { responsive: true, 
                            plugins: { 
                                title: {
                                    display: true,
                                    text: 'Soportes por Cargo'
                                },
                                legend: { display: false } 
                            } 
                        }
                    });
                }
            });

        fetch(`controllers/crppr.php?grafico=por_usuario&filter=${filter}`)
            .then(res => res.json())
            .then(data => {
                const labels = data.map(item => item.usuario);
                const valores = data.map(item => item.total);
                if(chartUsuario) {
                    chartUsuario.data.labels = labels;
                    chartUsuario.data.datasets[0].data = valores;
                    chartUsuario.update();
                } else {
                    chartUsuario = new Chart(document.getElementById('graficoUsuario'), {
                        type: 'doughnut',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Soportes por Usuario',
                                data: valores,
                                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff', '#ff9f40'],
                                borderWidth: 1
                            }]
                        },
                        options: { responsive: true, 
                            plugins: { 
                                title: {
                                    display: true,
                                    text: 'Soportes por Usuario'
                                },
                                legend: { position: 'right' } 
                            } 
                        }
                    });
                }
            });
    }

    document.getElementById('filterSelect').addEventListener('change', function() {
        filter = this.value;
        fetchDataAndRenderCharts();
    });

    fetchDataAndRenderCharts();
});
</script>
