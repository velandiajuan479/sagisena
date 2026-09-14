<?php
require_once '../models/conexion.php';

setlocale(LC_TIME, 'es_ES.UTF-8');
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    setlocale(LC_TIME, 'spanish');
}
date_default_timezone_set('America/Bogota');
$fechaActual = strftime('%d de %B de %Y');

$logoPath = 'image/favicon.png';

$html = '<html>
<head>
    <title>Reporte de Instrumentos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .header-table td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #000;
        }
        .logo { width: 80px; }
        h2, h3 { margin: 4px 0; }
        th { background-color: #f2f2f2; }
        h3 {
            background-color: #d9d9d9;
            padding: 8px;
            border: 1px solid #000;
            margin: 20px 0 10px;
        }
        .apr-table th, .apr-table td {
            font-size: 11px;
        }
    </style>
</head>
<body>';

$html .= '
<table class="header-table">
    <tr>
        <td style="width: 20%;"><img src="' . $logoPath . '" class="logo" alt="Logo SENA"></td>
        <td style="width: 60%;">
            <strong>CENTRO DE DESARROLLO AGROEMPRESARIAL CHÍA</strong><br>
            <h2>REPORTE DE INSTRUMENTOS DE EVALUACIÓN</h2>
        </td>
        <td style="width: 20%;"><strong>Fecha:</strong><br>' . $fechaActual . '</td>
    </tr>
</table>
';

$conexion = (new Conexion())->get_conexion();

$sql_instrumentos = "SELECT i.idins, i.nomins, i.idfic, f.nomfic 
                     FROM inseva i 
                     LEFT JOIN ficha f ON i.idfic = f.idfic 
                     ORDER BY i.nomins";
$stmt_ins = $conexion->prepare($sql_instrumentos);
$stmt_ins->execute();
$instrumentos = $stmt_ins->fetchAll(PDO::FETCH_ASSOC);

if ($instrumentos) {
    foreach ($instrumentos as $inst) {
        $html .= '<h3>Instrumento: ' . htmlspecialchars($inst['nomins']) . ' | Ficha: ' . htmlspecialchars($inst['nomfic']) . '</h3>';

        // Obtener criterios
        $sql_criterios = "SELECT idcri, nomcri, vscum, vpar, vncum FROM criterio WHERE idins = :idins";
        $stmt_crit = $conexion->prepare($sql_criterios);
        $stmt_crit->bindParam(":idins", $inst['idins']);
        $stmt_crit->execute();
        $criterios = $stmt_crit->fetchAll(PDO::FETCH_ASSOC);

        if ($criterios) {
            $html .= '<table>
                        <thead>
                            <tr>
                                <th>Criterio</th>
                                <th>Si Cumple</th>
                                <th>Cumple Parcialmente</th>
                                <th>No Cumple</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($criterios as $c) {
                $html .= '<tr>
                            <td>' . htmlspecialchars($c['nomcri']) . '</td>
                            <td>' . $c['vscum'] . '</td>
                            <td>' . $c['vpar'] . '</td>
                            <td>' . $c['vncum'] . '</td>
                          </tr>';
            }
            $html .= '</tbody></table>';

            // Aprendices por ficha
            $sql_apr = "SELECT u.idusu, u.nomusu 
                        FROM usuario u
                        INNER JOIN usufic uf ON u.idusu = uf.idusu
                        WHERE uf.idfic = :idfic AND uf.actfic = 1";
            $stmt_apr = $conexion->prepare($sql_apr);
            $stmt_apr->bindParam(":idfic", $inst['idfic']);
            $stmt_apr->execute();
            $aprendices = $stmt_apr->fetchAll(PDO::FETCH_ASSOC);

            if ($aprendices) {
                $html .= '<table class="apr-table">
                            <thead>
                                <tr>
                                    <th>Aprendiz</th>';
                foreach ($criterios as $c) {
                    $html .= '<th>' . htmlspecialchars($c['nomcri']) . '</th>';
                }
                $html .= '</tr></thead><tbody>';

                foreach ($aprendices as $apr) {
                    $html .= '<tr><td>' . htmlspecialchars($apr['nomusu']) . '</td>';
                    foreach ($criterios as $c) {
                        $sql_calif = "SELECT calif FROM calcri WHERE idcri = :idcri AND idusu = :idusu";
                        $stmt_calif = $conexion->prepare($sql_calif);
                        $stmt_calif->bindParam(":idcri", $c['idcri']);
                        $stmt_calif->bindParam(":idusu", $apr['idusu']);
                        $stmt_calif->execute();
                        $calif = $stmt_calif->fetchColumn();

                        if ($calif === false || $calif === null) {
                            $resultado = '-'; // No se ha calificado
                        } elseif ($calif == $c['vscum']) {
                            $resultado = 'Cumple';
                        } elseif ($calif == $c['vpar']) {
                            $resultado = 'Parcial';
                        } elseif ($calif == 0 || $calif === '0') {
                            $resultado = 'No Cumple';
                        } else {
                            $resultado = '-';
                        }
                        
                        $html .= '<td>' . $resultado . '</td>';
                    }
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';
            } else {
                $html .= '<p><em>No hay aprendices en esta ficha.</em></p>';
            }

        } else {
            $html .= '<p><em>No hay criterios registrados para este instrumento.</em></p>';
        }
    }
} else {
    $html .= '<p><strong>No hay instrumentos registrados.</strong></p>';
}

$html .= '</body></html>';

echo $html;
?>

<script type="text/javascript">window.print();</script>
