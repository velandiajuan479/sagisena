<?php require_once 'controllers/csersop.php'; ?> 

<div class="conte">
    <?php echo titulo2("<i class='<?php echo $icono; ?>'></i> Detalle Soporte", 1); ?>
    <div class="row">
        <div class="inser" id="frmins">
            <div style="position: relative; width: 100%; height: 400px;">
                <canvas id="graficoSoporte"></canvas>
            </div>
            <br>
            <div style="position: relative; width: 100%; height: 400px;">
                <canvas id="graficoSoporte_2"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("controllers/csersop.php?grafico=por_falla")
        .then(res => res.json())
        .then(data => {
            const labels = data.map(item => item.falla);
            const valores = data.map(item => item.total);

            new Chart(document.getElementById('graficoSoporte'), {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        label: 'Soportes por Falla',
                        data: valores,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(199, 199, 199, 0.6)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Soportes por Tipo de Falla'
                        },
                        legend: { position: 'bottom' },
                        datalabels: {
                            color: '#000',
                            font: { weight: 'bold', size: 14 },
                            formatter: value => value
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    fetch("controllers/csersop.php?grafico_2=por_tiempo")
        .then(res => res.json())
        .then(data => {
            const labels = data.map(item => item.tiempo);
            const valores = data.map(item => item.total);

            new Chart(document.getElementById('graficoSoporte_2'), {
                type: 'pie',
                data: {
                    labels,
                    datasets: [{
                        label: 'Soportes por Tiempo',
                        data: valores,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(199, 199, 199, 0.6)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Soportes por Tiempo de Atención'
                        },
                        legend: { position: 'bottom' },
                        datalabels: {
                            color: '#000',
                            font: { weight: 'bold', size: 14 },
                            formatter: value => value
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
});
</script>