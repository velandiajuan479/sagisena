<?php require_once 'controllers/cnvv.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Votación - Aprendices por Ficha</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .ficha-header {
            background-color: #117f09;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .stats-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: conic-gradient(
                #28a745 <?= $gaf['total_personas'] > 0 ? round(($gaf['votaron'] / $gaf['total_personas']) * 360) : 0 ?>deg,
                #fe051eff 0deg
            );
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-right: 30px;
            margin-left: 230px;
        }
        
        .stats-box {
            flex-grow: 1;
            padding: 15px;
            background-color: white;
            width: 120px;
            margin-left: 500px;
            margin-right: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .aprendiz-container {
            margin-top: 20px;
        }
        
        .aprendiz-card {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .aprendiz-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .tipo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            margin-left: 10px;
            text-align: center;
            padding: 10px;
        }
        
        .aprendiz-info {
            flex-grow: 1;
        }
        
        .aprendiz-nombre {
            margin-bottom: 5px;
            font-size: 14px;
            margin-left: 480px;
        }
        
        .aprendiz-documento {
            color: #666;
            font-size: 14px;
            margin-left: 480px;
        }

        
        .voto-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-left: 20px;
            margin-right: 100px;
        }
        
        .votado-si {
            background-color: #28a745;
            color: white;
        }
        
        .votado-no {
            background-color: #dc3545;
            color: white;
        }
        
        .no-aprendices {
            text-align: center;
            padding: 30px;
            background-color:rgb(64, 108, 152);
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .select-ficha {
            max-width: 500px;
        }
    </style>
</head>
<body>
    <?php echo titulo2("<i class='fas fa-users'></i> No votantes vocero", 2); ?>

    <!-- seleccion de la ficha -->
    <div class="select-ficha mb-5">
        <form action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="form-group">
                <label for="fidfic" class="form-label fw-semibold">Seleccione Ficha</label>
                <select name="fidfic" id="fidfic" class="form-select form--input-default" required onchange="this.form.submit()">
                    <option value="">-- Seleccione una ficha --</option>
                    <?php foreach ($fichas as $ficha): ?>
                        <option value="<?= $ficha['idfic'] ?>" 
                            <?= ($fidfic == $ficha['idfic']) ? 'selected' : '' ?>>
                            <?= $ficha['idfic'] ?> - <?= $ficha['nomfic'] ?> (<?= $ficha['nomval'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <?php if($fidfic): ?>
        <?php 
        $fichaActual = current(array_filter($fichas, function($f) use ($fidfic) {
            return $f['idfic'] == $fidfic;
        }));
        
        $porcentajeVotaron = $gaf['total_personas'] > 0 ? round(($gaf['votaron']/$gaf['total_personas'])*100) : 0;
        ?>
        
        <!-- encabezado de la ficha -->
        <div class="ficha-header">
            <h4>
                <i class="fas fa-id-card"></i> Ficha: <?= $fichaActual['idfic'] ?> - <?= $fichaActual['nomfic'] ?>
                <small>(<?= $fichaActual['nomval'] ?>)</small>
            </h4>
        </div>
        
        <!-- estadisticas en circulo -->
        <div class="stats-container">
            <div class="circle">
                <?= $porcentajeVotaron ?>%
            </div>
            <!-- tabla de estadisticas -->
            <table style="width: 60%; border-collapse: collapse; margin-bottom: 30px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <tr>
                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Totales</th>
                    <th style="text-align: right; padding: 10px; border-bottom: 1px solid #ddd;">Cantidad</th>
                    <th style="text-align: right; padding: 10px; border-bottom: 1px solid #ddd;">Porcentaje</th>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">Total Aprendices</td>
                    <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee; border-right: 1px solid #ddd;"><?= $gaf['total_personas'] ?></td>
                    <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee;">100%</td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">Votaron</td>
                    <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee; border-right: 1px solid #ddd;"><?= $gaf['votaron'] ?></td>
                    <td style="text-align: right; padding: 10px; border-bottom: 1px solid #eee;"><?= $porcentajeVotaron ?>%</td>
                </tr>
                <tr>
                    <td style="padding: 10px;">No votaron</td>
                    <td style="text-align: right; padding: 10px; border-right: 1px solid #ddd;"><?= $gaf['no_votaron'] ?></td>
                    <td style="text-align: right; padding: 10px;"><?= 100 - $porcentajeVotaron ?>%</td>
                </tr>
            </table>
        </div>
        
        <!-- lista de aprendices -->
        <div class="aprendiz-container">
            <?php if(!empty($dat)): ?>
                <?php foreach ($dat as $aprendiz): ?>
                    <div class="aprendiz-card">
                        <!-- tipo de aprendiz -->
                        <div class="tipo">
                            <?php 
                            if(strpos(strtolower($aprendiz['nomper']), 'vocero') !== false) {
                                echo 'Candidato a Vocero';
                            } elseif(strpos(strtolower($aprendiz['nomper']), 'candidato') !== false) {
                                echo 'Candidato';
                            } else {
                                echo 'Aprendiz';
                            }
                            ?>
                        </div>
                        
                        <!-- datos -->
                        <div class="aprendiz-info">
                            <div class="aprendiz-nombre"><?= $aprendiz['nomusu'] ?></div>
                            <div class="aprendiz-documento">Documento: <?= $aprendiz['ndocusu'] ?></div>
                        </div>
                        
                        <!-- voto -->
                        <div class="voto-status <?= $aprendiz['votado'] ? 'votado-si' : 'votado-no' ?>">
                            <i class="fas fa-thumbs-<?= $aprendiz['votado'] ? 'up' : 'down' ?>"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-aprendices">
                    <i class="fas fa-exclamation-triangle fa-2x" style="color:rgb(255, 7, 7);"></i>
                    <h5>No se encontraron aprendices en esta ficha</h5>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" style="text-align: center; padding: 20px;">
            <i class="fas fa-info-circle fa-2x"></i>
            <h4>Por favor seleccione una ficha para ver los aprendices</h4>
        </div>
    <?php endif; ?>
</body>
</html>