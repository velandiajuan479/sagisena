<?php require_once 'controllers/cnvot.php'; ?>

<?php echo titulo2("<i class='fa" . $icono . "'></i> No votantes", 2); ?>
<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Highcharts Example</title>
    <style>
        
        .container {
            display: flex;
            flex-direction: row;
        }

        .chart-container {
            flex: 1;
            max-width: 100%;
        }

        .table-container {
            flex: 1;
            max-width: 600px;
            margin-top: 10%;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            text-align: center;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }

        @media only screen and (max-width: 600px) {
            .container {
                flex-direction: column;
            }

            .table-container {
                max-width: 100%;
                justify-content: center;
                margin-top: 10%;
            }

            .chart-container {
                display: flex;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="chart-container">
            <figure class="highcharts-figure">
                <div id="container"></div>
                <br>
            </figure>
        </div>
        <div class="table-container">
            <div class="highcharts-data-table">
                <table>
                    <caption id="frmins">Resumen de Votos</caption>
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>No votaron</td>
                            <td>
                                <?= $gaf[0]['no_votaron']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Votaron</td>
                            <td>
                                <?= $gaf[0]['votaron']; ?>
                            </td>
                        </tr>
                         <tr>
                            <td>TOTAL VOTOS</td>
                            <td>
                                <?= $gaf[0]['total_personas']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="./js/highcharts.js"></script>
    <script src="./js/exporting.js"></script>
    <script src="./js//accessibility.js"></script>

    <script>

Highcharts.setOptions({
            lang: {
                decimalPoint: ',',
                thousandsSep: '.',
                loading: 'Cargando...',
                noData: 'No hay datos',
                printChart: 'Imprimir gráfico',
                downloadPNG: 'Descargar en PNG',
                downloadJPEG: 'Descargar en JPEG',
                downloadPDF: 'Descargar en PDF',
                downloadSVG: 'Descargar en SVG',
                resetZoom: 'Restablecer zoom',
                viewFullscreen: 'Ver a pantalla completa',
                exitFullscreen: 'Salir de pantalla completa'
                // Puedes agregar más traducciones según sea necesario
            }
        });
        
        Highcharts.chart('container', {
            chart: {
                type: 'pie',
                width: 370,
                height: 370
            },
            title: {
                text: 'Porcentaje de votos',
                style: {
                    fontSize: '20px'
                }
            },
            tooltip: {
                valueSuffix: '  personas'
            },
            subtitle: {
                text: 'Source: <a href="https://www.mdpi.com/2072-6643/11/3/684/htm" target="_default">MDPI</a>'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: [{
                        enabled: true,
                        distance: 20
                    }, {
                        enabled: true,
                        distance: -40,
                        format: '{point.percentage:.1f}%',
                        style: {
                            fontSize: '18px',
                            textOutline: 'none',
                            opacity: 0.7
                        },
                        filter: {
                            operator: '>',
                            property: 'percentage',
                            value: 10
                        }
                    }]
                }
            },
            series: [{
                name: 'Total',
                colorByPoint: true,
                data: [{
                    name: 'No votaron',
                    y: <?= $gaf[0]['no_votaron']; ?>
                },
                {
                    name: 'Votaron',
                    y: <?= $gaf[0]['votaron']; ?>
                }
                ]
            }]
        });
    </script>
</body>

</html>
<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Perfil</th>
            <th>Usuario</th>
            <th>Voto</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dat as $d): ?>
            <tr>
                <td>
                    <?= $d['nomper']; ?>
                </td>
                <td>
                    <?= $d['nomusu']; ?><br>
                    <small>
                        <strong>No. Documento:</strong>
                        <?= $d['ndocusu']; ?><br>
                        <?php if ($d['idfic']): ?>
                            <strong>Ficha:</strong>
                            <?= $d['idfic']; ?>
                            <?= $d['nomfic']; ?>
                            <?= $d['nomval']; ?><br>
                        <?php endif; ?>
                        <?= $d['nomcen']; ?>
                    </small>
                </td>
                <td>
                    <?php
                    $votado = $mnvot->getVotU($d['idusu']);
                    if ($votado) {
                        ?>
                        <a href="home.php?pg=<?= $pg; ?>&idusu=<?= $d['idusu']; ?>" title="Voto">
                            <i class="fa fa-solid fa-thumbs-up fa-2x" style="color:#117f09;"></i>
                        </a>
                    <?php } else { ?>
                        <a href="home.php?pg=<?= $pg; ?>&idusu=<?= $d['idusu']; ?>" title="No voto">
                            <i class="fa fa-solid fa-thumbs-down fa-2x" style="color:#f00; display:flex;"></i>
                        </a>
                    <?php } ?>
                </td>

            </tr>
        <?php endforeach; ?>

    </tbody>
    <tfoot>
        <tr>
            <th>Perfil</th>
            <th>Usuario</th>
            <th>Voto</th>
        </tr>
    </tfoot>
</table>