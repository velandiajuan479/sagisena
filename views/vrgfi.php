<?php
require_once('models/mrgfi.php');

$icono = "fas fa-chart-bar";

// Conexión PDO
$pdo = new PDO('mysql:host=localhost;dbname=sagi', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$modelo = new MRFGI($pdo);

// Obtener datos usando el método del modelo
$datAll = $modelo->obtFic();

function nomJor($codigo) {
    switch($codigo) {
        case 1: return "Mañana";
        case 2: return "Tarde";
        case 3: return "Noche";
        case 4: return "Fines de semana";
        case 5: return "Mixto";
        default: return "Desconocida";
    }
}

?>

<!-- Incluye Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Reporte Gerencial por Inscritos", 2); ?>

    <table id="example" class="table table-striped table-bordered table-hover" style="width:100%">
        <thead class="thead-dark">
            <tr>
                <th>Código Ficha</th>
                <th>Nombre Ficha</th>
                <th>Centro de formación</th>
                <th>Jornada</th>
                <th>Programa</th>
                <th>Total Inscritos</th>
                <th>Gráfico</th>
            </tr>
        </thead>
        <tbody>
           <?php
if($datAll) {
    foreach($datAll as $index => $dt){
        $idfic = $dt["idfic"];
        $cupos = 35; // Cupos por defecto
        $cantidad = $dt["cantidad"];
        $disponibles = max(0, $cupos - $cantidad);
?>
    <tr>
        <td><?= $idfic ?></td>
        <td><?= $dt["nomfic"] ?></td>
        <td><?= $dt["centro"] ?></td>
        <td><?= nomJor($dt["horario"]) ?></td>
        <td><?= $dt["programa"] ?></td>
        <td><?= $cantidad =25 ?></td>
        <td>
            <canvas id="grafico<?= $index ?>" width="250" height="250"></canvas>
            <script>
                const ctx<?= $index ?> = document.getElementById('grafico<?= $index ?>').getContext('2d');
                new Chart(ctx<?= $index ?>, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total inscritos', 'Cupos disponibles'],
                        datasets: [{
                            data: [<?= $cantidad ?>, <?= $cupos - $cantidad ?>],
                            backgroundColor: ['#FF6384', '#36A2EB']
                        }]
                    },
                    options: {
                        responsive: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Cupos para la ficha <?= $cupos ?>'
                            }
                        }
                    }
                });
            </script>
        </td>
    </tr>
<?php }} ?>

        </tbody>
        <tfoot>
            <tr>
                <th>Código Ficha</th>
                <th>Nombre Ficha</th>
                <th>Centro de formación</th>
                <th>Jornada</th>
                <th>Programa</th>
                <th>Total Inscritos</th>
                <th>Gráfico</th>
            </tr>
        </tfoot>
    </table>
</div>