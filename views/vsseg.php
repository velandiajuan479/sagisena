<?php require_once 'controllers/csseg.php'; ?>

<head>
    <script src="./js/chart.js"></script>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            margin-top: 9%;
            margin-left: 20%;
        }

        .chart-container {
            width: 40%;
        }    
        .list-container {
            width: 40%;
            padding-left: 10px;
        }
    </style>
</head>
<body>
<div class="conte">
    <?php echo titulo2("<i class='" .$icono ."'></i> Sesiones de seguimiento", 1); ?>

    <div class="inser">
        <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="idfic">Seleccione Ficha</label>
                    <select name="idfic" id="idfic" class="form-select">
                    <?php
                    if ($dfi) {
                    foreach ($dfi as $de) {
                    ?>
                    <option value="<?= $de['idfic']; ?>" <?php if ($dat && $idfic==$de['idfic']) echo " selected "; ?> > <?= $de['idfic']; ?> - <?= $de['nomfic']; ?> <?= $de['nomval']; ?></option>
                    <?php }
                    } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="descom">Nombre de la competencia</label>
                    <input type="text" name="descom" id="descom" class="form-control" maxlength="20" required value="<?= htmlspecialchars($descom); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="idval">Tipo de competencia</label>
                    <select name="idval" id="idval" class="form-select">
                        <?php if ($datVal) {
                            foreach ($datVal as $ddo) { ?>
                                <option value="<?= $ddo['idval']; ?>" <?= isset($datOne) && isset($datOne[0]['idval']) && $ddo['idval'] == $datOne[0]['idval'] ? "selected" : ""; ?>>
                                    <?= $ddo['nomval']; ?>
                                </option>
                        <?php } } ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <br>
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="id" value="<?= isset($datOne) && isset($datOne[0]['idcom']) ? $datOne[0]['idcom'] : ''; ?>" required>
                    <input type="submit" class="btn btn-primary" value="Enviar">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="chart-container">
        <canvas id="sesionesChart" width="300" height="200"></canvas>
    </div>
    <div class="list-container">
        <h3 id="listaSesionesT"><?= htmlspecialchars($descom) ?: 'Lista de Sesiones'; ?></h3>
        <ul>
            <li>Se vio sesión: </li>
            <li>Se vio sesión parcialmente: </li>
            <li>No se vio sesión: </li>
        </ul>
    </div>
</div>

<?php
$sesiones = [
    ["datos" => "Se vio sesión", "sesiones" => 30],
    ["datos" => "Se vio sesión parcialmente", "sesiones" => 15],
    ["datos" => "No se vio sesión", "sesiones" => 5],
];

$datos = json_encode(array_column($sesiones, 'datos'));
$valeva = json_encode(array_column($sesiones, 'sesiones'));

$coloresFondo = json_encode(['#3fa000', '#69c60e', '#93ec1b']);
$coloresBorde = json_encode(['#69c60e', '#69c60e', '#69c60e']);
?>

<script>
    var datos = <?php echo $datos; ?>;
    var valeva = <?php echo $valeva; ?>;
    var coloresFondo = <?php echo $coloresFondo; ?>; 
    var coloresBorde = <?php echo $coloresBorde; ?>; 

    var ctx = document.getElementById('sesionesChart').getContext('2d');
    var sesionesChart = new Chart(ctx, {
        type: 'pie', 
        data: {
            labels: datos,
            datasets: [{
                label: 'Sesiones',
                data: valeva,
                backgroundColor: coloresFondo,
                borderColor: coloresBorde, 
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    document.getElementById('descom').addEventListener('input', function() {
        var descom = this.value;
        var listaSesionesT = document.getElementById('listaSesionesT');
        listaSesionesT.textContent = descom ? descom : 'Lista de Sesiones';
    });
</script>
</body>
