<?php
require_once '../models/conexion.php';
include("../models/mrphfc.php");
include("../models/mhor.php");
$mrphfc = new Mrphfc();
$mhor = new Mhor();

$idfic = isset($_REQUEST['idfic']) ? $_REQUEST['idfic'] : null;

$mrphfc->setIdfic($idfic);

$dtOneHeader = $mrphfc->getOne();

// Cargar horarios del trimestre actual para la ficha
$__trActual = obtenerTrimestreActual();
$__iniTr = $__trActual['fecha_inicio'];
$__finTr = $__trActual['fecha_fin'];
$dtOne = $mrphfc->getHorariosFichaEnRango($idfic, $__iniTr, $__finTr);

/**
 * Obtener municipio desde la tabla ficha (columna mun)
 */
$__municipio = $mrphfc->getMunicipioPorFicha($idfic);

/**
 * Obtiene el trimestre actual desde BD usando eventos de tipo 'trimestre'
 * (misma lógica que filtros por rango evento-trimestre)
 */
function obtenerTrimestreActual() {
    global $mhor;

    $anio = (int)date('Y');
    $hoy = new DateTime('now', new DateTimeZone('America/Bogota'));
    $eventos = $mhor->getEventosPorTipo('trimestre', $anio);

    // Buscar el que contenga la fecha actual
    foreach ($eventos as $ev) {
        if (!empty($ev['fecha_inicio']) && !empty($ev['fecha_fin'])) {
            $ini = new DateTime($ev['fecha_inicio']);
            $fin = new DateTime($ev['fecha_fin']);
            if ($hoy >= $ini && $hoy <= $fin) {
                return [
                    'nombre' => $ev['trimestre'] ?: 'Trimestre',
                    'año' => (int)$ev['anio'],
                    'fecha_inicio' => $ev['fecha_inicio'],
                    'fecha_fin' => $ev['fecha_fin']
                ];
            }
        }
    }

    // Si no hay activo, tomar el último del año
    if (!empty($eventos)) {
        $ultimo = end($eventos);
        return [
            'nombre' => $ultimo['trimestre'] ?: 'Trimestre',
            'año' => (int)$ultimo['anio'],
            'fecha_inicio' => $ultimo['fecha_inicio'],
            'fecha_fin' => $ultimo['fecha_fin']
        ];
    }

    // Fallback: sin evento en BD
    return [
        'nombre' => 'Trimestre',
        'año' => $anio,
        'fecha_inicio' => date('Y-m-01'),
        'fecha_fin' => date('Y-m-t')
    ];
}

/**
 * Función para formatear fecha en español
 */
function formatearFecha($fecha) {
    if (empty($fecha)) return '';
    
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    
    $fechaObj = new DateTime($fecha);
    $dia = $fechaObj->format('j');
    $mes = $meses[(int)$fechaObj->format('n')];
    
    return $dia . ' de ' . $mes;
}

/**
 * Cargar horarios de una ficha dentro de un rango de fechas (usa fecha_especifica o rango legacy)
 */
function obtenerHorariosFichaEnRango($idfic, $fechaInicio, $fechaFin) {
    try {
        $modelo   = new Conexion();
        $conexion = $modelo->get_conexion();
        $sql = "SELECT 
                    h.iddia,
                    u.nomusu AS un,
                    a.idaul AS ai,
                    a.nomaul AS an,
                    h.es_otros,
                    h.actividad,
                    h.horas_otros,
                    h.fecha_especifica,
                    h.fecha_inicio,
                    h.fecha_fin
                FROM horario h
                LEFT JOIN usuario u ON h.idusu = u.idusu
                LEFT JOIN aula a ON h.idaul = a.idaul
                WHERE h.idfic = :idfic
                  AND (
                        (h.fecha_especifica IS NOT NULL AND h.fecha_especifica BETWEEN :ini AND :fin)
                     OR (
                        h.fecha_especifica IS NULL 
                        AND h.fecha_inicio IS NOT NULL AND h.fecha_fin IS NOT NULL
                        AND h.fecha_inicio <= :fin AND h.fecha_fin >= :ini
                     )
                  )
                ORDER BY h.iddia ASC, h.fecha_especifica ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idfic', $idfic);
        $stmt->bindParam(':ini', $fechaInicio);
        $stmt->bindParam(':fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log('Error obteniendo horarios por rango en vrphfc: ' . $e->getMessage());
        return [];
    }
}

/**
 * Ordenar y renderizar listado simple: nombre -> fechas
 */
function renderListadoSimple($nombreToFechas) {
    if (empty($nombreToFechas)) return '';

    ksort($nombreToFechas, SORT_NATURAL | SORT_FLAG_CASE);

    $html = '';
    foreach ($nombreToFechas as $nombre => $fechasRaw) {
        $fechasRaw = array_filter($fechasRaw, function($f) { return !empty($f); });

        $tieneSinFecha = false;
        $soloFechas = [];
        foreach ($fechasRaw as $f) {
            if ($f === 'SIN_FECHA') { $tieneSinFecha = true; continue; }
            $soloFechas[] = $f;
        }

        // Unificar y ordenar fechas cronológicamente
        $soloFechas = array_values(array_unique($soloFechas));
        usort($soloFechas, function($a, $b) { return strcmp($a, $b); });

        $fechasFmt = array_map(function($f) { return formatearFecha($f); }, $soloFechas);
        if ($tieneSinFecha) {
            // Agregar al final la leyenda si aplica
            $fechasFmt[] = 'Sin fecha específica';
        }

        $html .= '<div class="item-line"><span class="item-name">' . htmlspecialchars($nombre) . '</span>';
        if (!empty($fechasFmt)) {
            $html .= '<span class="item-dates">(' . htmlspecialchars(implode(', ', $fechasFmt)) . ')</span>';
        }
        $html .= '</div>';
    }
    return $html;
}

/**
 * Render para actividades: nombre (+horas) -> fechas
 */
function renderListadoActividades($claveToInfo) {
    if (empty($claveToInfo)) return '';

    // Ordenar por nombre de actividad
    uasort($claveToInfo, function($a, $b) {
        return strnatcasecmp($a['nombre'] ?? '', $b['nombre'] ?? '');
    });

    $html = '';
    foreach ($claveToInfo as $info) {
        $tieneSinFecha = false;
        $soloFechas = [];
        foreach (($info['fechas'] ?? []) as $f) {
            if ($f === 'SIN_FECHA') { $tieneSinFecha = true; continue; }
            $soloFechas[] = $f;
        }
        $soloFechas = array_values(array_unique($soloFechas));
        usort($soloFechas, function($a, $b) { return strcmp($a, $b); });
        $fechasFmt = array_map(function($f) { return formatearFecha($f); }, $soloFechas);
        if ($tieneSinFecha) {
            $fechasFmt[] = 'Sin fecha específica';
        }

        $nombre = htmlspecialchars($info['nombre'] ?? '');
        $horas = isset($info['horas']) && $info['horas'] ? (int)$info['horas'] : 0;
        $html .= '<div class="item-line"><span class="item-name">' . $nombre . '</span>';
        if ($horas > 0) {
            $html .= '<span class="item-hours">(' . $horas . ' hrs)</span>';
        }
        if (!empty($fechasFmt)) {
            $html .= '<span class="item-dates">(' . htmlspecialchars(implode(', ', $fechasFmt)) . ')</span>';
        }
        $html .= '</div>';
    }
    return $html;
}

/**
 * Función para agrupar datos por día y mostrar fechas
 */
function agruparPorDia($dtOne, $iddia, $campo) {
    if (!$dtOne) return '';
    
    $trimestreActual = obtenerTrimestreActual();
    $datos = [];
    
    foreach ($dtOne as $item) {
        if ($item['iddia'] == $iddia) {
            $valor = $item[$campo];
            if (!empty($valor)) {
                if (!isset($datos[$valor])) {
                    $datos[$valor] = [];
                }
                
                // Si tiene fecha específica, verificar que esté en el trimestre
                if (!empty($item['fecha_especifica'])) {
                    $fechaHorario = new DateTime($item['fecha_especifica']);
                    $inicioTrimestre = new DateTime($trimestreActual['fecha_inicio']);
                    $finTrimestre = new DateTime($trimestreActual['fecha_fin']);
                    
                    if ($fechaHorario >= $inicioTrimestre && $fechaHorario <= $finTrimestre) {
                        $datos[$valor][] = $item['fecha_especifica'];
                    }
                } else {
                    // Si no tiene fecha específica, verificar si el rango de fechas del horario traslapa con el trimestre
                    if (!empty($item['fecha_inicio']) && !empty($item['fecha_fin'])) {
                        $fechaInicioHorario = new DateTime($item['fecha_inicio']);
                        $fechaFinHorario = new DateTime($item['fecha_fin']);
                        $inicioTrimestre = new DateTime($trimestreActual['fecha_inicio']);
                        $finTrimestre = new DateTime($trimestreActual['fecha_fin']);
                        
                        // Verificar si hay traslape
                        if ($fechaInicioHorario <= $finTrimestre && $fechaFinHorario >= $inicioTrimestre) {
                            // Si hay traslape, marcar sin fecha específica
                            $datos[$valor][] = 'SIN_FECHA';
                        }
                    } else {
                        // Si no tiene fechas, marcar sin fecha específica
                        $datos[$valor][] = 'SIN_FECHA';
                    }
                }
            }
        }
    }
    
    return renderListadoSimple($datos);
}

/**
 * Función para agrupar ambientes por día
 */
function agruparAmbientesPorDia($dtOne, $iddia) {
    if (!$dtOne) return '';
    
    $trimestreActual = obtenerTrimestreActual();
    $ambientes = [];
    
    foreach ($dtOne as $item) {
        if ($item['iddia'] == $iddia) {
            $ambiente = $item['ai'] . ' - ' . $item['an'];
            if (!empty($ambiente) && $ambiente !== ' - ') {
                if (!isset($ambientes[$ambiente])) {
                    $ambientes[$ambiente] = [];
                }
                
                // Si tiene fecha específica, verificar que esté en el trimestre
                if (!empty($item['fecha_especifica'])) {
                    $fechaHorario = new DateTime($item['fecha_especifica']);
                    $inicioTrimestre = new DateTime($trimestreActual['fecha_inicio']);
                    $finTrimestre = new DateTime($trimestreActual['fecha_fin']);
                    
                    if ($fechaHorario >= $inicioTrimestre && $fechaHorario <= $finTrimestre) {
                        $ambientes[$ambiente][] = $item['fecha_especifica'];
                    }
                } else {
                    // Si no tiene fecha específica, verificar si el rango de fechas del horario traslapa con el trimestre
                    if (!empty($item['fecha_inicio']) && !empty($item['fecha_fin'])) {
                        $fechaInicioHorario = new DateTime($item['fecha_inicio']);
                        $fechaFinHorario = new DateTime($item['fecha_fin']);
                        $inicioTrimestre = new DateTime($trimestreActual['fecha_inicio']);
                        $finTrimestre = new DateTime($trimestreActual['fecha_fin']);
                        
                        // Verificar si hay traslape
                        if ($fechaInicioHorario <= $finTrimestre && $fechaFinHorario >= $inicioTrimestre) {
                            $ambientes[$ambiente][] = 'SIN_FECHA';
                        }
                    } else {
                        $ambientes[$ambiente][] = 'SIN_FECHA';
                    }
                }
            }
        }
    }
    
    return renderListadoSimple($ambientes);
}

/**
 * Función para agrupar actividades por día
 */
function agruparActividadesPorDia($dtOne, $iddia) {
    if (!$dtOne) return '';
    
    $trimestreActual = obtenerTrimestreActual();
    $actividades = [];
    
    foreach ($dtOne as $item) {
        if ($item['iddia'] == $iddia) {
            if (!empty($item['es_otros']) && $item['es_otros']) {
                $actividad = $item['actividad'];
                if (!empty($actividad)) {
                    $clave = $actividad . '_' . ($item['horas_otros'] ?? '0');
                    if (!isset($actividades[$clave])) {
                        $actividades[$clave] = [
                            'nombre' => $actividad,
                            'horas' => $item['horas_otros'] ?? 0,
                            'fechas' => []
                        ];
                    }
                    
                    // Si tiene fecha específica, verificar que esté en el trimestre
                    if (!empty($item['fecha_especifica'])) {
                        $fechaHorario = new DateTime($item['fecha_especifica']);
                        $inicioTrimestre = new DateTime($trimestreActual['fecha_inicio']);
                        $finTrimestre = new DateTime($trimestreActual['fecha_fin']);
                        
                        if ($fechaHorario >= $inicioTrimestre && $fechaHorario <= $finTrimestre) {
                            $actividades[$clave]['fechas'][] = $item['fecha_especifica'];
                        }
                    } else {
                        // Si no tiene fecha específica, verificar si el rango de fechas del horario traslapa con el trimestre
                        if (!empty($item['fecha_inicio']) && !empty($item['fecha_fin'])) {
                            $fechaInicioHorario = new DateTime($item['fecha_inicio']);
                            $fechaFinHorario = new DateTime($item['fecha_fin']);
                            $inicioTrimestre = new DateTime($trimestreActual['fecha_inicio']);
                            $finTrimestre = new DateTime($trimestreActual['fecha_fin']);
                            
                            // Verificar si hay traslape
                            if ($fechaInicioHorario <= $finTrimestre && $fechaFinHorario >= $inicioTrimestre) {
                                $actividades[$clave]['fechas'][] = 'SIN_FECHA';
                            }
                        } else {
                            $actividades[$clave]['fechas'][] = 'SIN_FECHA';
                        }
                    }
                }
            }
        }
    }
    
    return renderListadoActividades($actividades);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reporte de ficha</title>

    <style>
        * {
            margin: 0;
            padding: 5px;
            text-align: center;
            font-size: 11px;
            font-family: "Roboto", sans-serif;
        }
    table{
    width: 100%;
    margin: auto;
    }
    table, th, td {
        border-collapse: collapse;
    }
    table td, table th {
        border: 1px solid #B6BCC6 !important;
    }
    img{
        height: 100px;
        width: 100px;
    }
    .version, .gpf{
        max-width: 30px;
    }
    .pri{
        text-align: left;
        height: 10px;
    }
    .dato{
        height: 20px;
    }
    input{
        width: 100%;
    }
    /* Mejora visual para filas y contenido */
    tbody th { text-transform: uppercase; letter-spacing: .3px; font-size: 12px; }
    td { text-align: left; vertical-align: top; font-size: 11px; }
    .item-line { line-height: 1.25; margin: 2px 0; }
    .item-name { font-weight: 700; margin-right: 4px; font-size: 11px; }
    .item-hours { font-weight: 600; margin-left: 2px; font-size: 10px; }
    .item-dates { color: #333; font-size: 10px; margin-left: 6px; }
    .horxfic th[scope="col"], .horxfic th[scope="row"] { font-weight: 700; font-size: 12px; }
    /* Anchos mínimos para evitar saltos feos */
    .horxfic th:nth-child(3), .horxfic th:nth-child(4), .horxfic th:nth-child(5),
    .horxfic th:nth-child(6), .horxfic th:nth-child(7), .horxfic th:nth-child(8), .horxfic th:nth-child(9) { min-width: 110px; }
    /* Títulos de columnas (ITEM / INSTRUCTOR / AMBIENTE / ACTVIDAD / OBSERVACIONES) en vertical */
    .col-item-title {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 2px 4px;
        width: 22px;
        font-size: 11px;
    }
    @media print {
        html, body { width: 216mm; height: 279mm; font-size: 8.8px; line-height: 1.15; }
        table { width: 100% !important; page-break-inside: avoid; border-collapse: collapse; }
        .horxfic thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { padding: 3px 5px !important; }
        tbody th { font-size: 10px; }
        td { font-size: 8.8px; }
        .item-name { font-size: 9px; }
        .item-hours { font-size: 8.8px; }
        .item-dates { font-size: 8.2px; }
        .col-item-title { font-size: 9.5px; padding: 1px 3px; width: 18px; }
    }
</style>
</head>
<body>
<div class="container my-5">
    <div class="table-responsive">
        <table class="table table-bordered text-center horxfic" style="border: 1px solid #B6BCC6">
            <thead>
                <tr>
                    <th colspan="2">
                        <img src="https://bogota.gov.co/sites/default/files/inline-images/logosena.png" alt="LogoSENA" width="140px" height="100px">
                    </th>
                    <th colspan="6">
                        <div>SENA - REGIONAL CUNDINAMARCA</div>
                        <div>CENTRO DE DESARROLLO AGROEMPRESARIAL</div>
                    </th>
                    <!-- Información del trimestre actual -->
                    <th colspan="2">
                        <?php 
                        $trimestreActual = obtenerTrimestreActual();
                        $fechaInicioFormateada = formatearFecha($trimestreActual['fecha_inicio']);
                        $fechaFinFormateada = formatearFecha($trimestreActual['fecha_fin']);
                        ?>
                        <div>VIGENCIA: <?= $trimestreActual['año'] ?></div>
                        <div class="row"><?= strtoupper($trimestreActual['nombre']) ?></div>
                        <div class="row">
                            <div class="col-6 colu">INICIO: <?= $fechaInicioFormateada ?></div>
                            <div class="col-6 colu">TERMINACION: <?= $fechaFinFormateada ?></div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>FICHA</th>
                    <td colspan="3"><?=$idfic?></td>
                    <th>MUNICIPIO</th>
                    <td colspan="4"><?php echo htmlspecialchars($__municipio); ?></td>
                </tr>
                <tr>
                    <th>JORNADA</th>
                    <td colspan="3"><?php if ($dtOneHeader && isset($dtOneHeader[0])) {
                        echo $dtOneHeader[0]['nomval'];
                    } ?></td>
                    <th>PROGRAMA</th>
                    <td colspan="4"><?php if ($dtOneHeader && isset($dtOneHeader[0])) {
                        echo $dtOneHeader[0]['nf'];
                    } ?></td>
                </tr>
                <tr>
                    <th>HORARIO</th>
                    <th>ITEM</th>
                    <th>LUNES</th>
                    <th>MARTES</th>
                    <th>MIERCOLES</th>
                    <th>JUEVES</th>
                    <th>VIERNES</th>
                    <th>SABADO</th>
                    <th>DOMINGO</th>
                </tr>
                <tr>
                    <th rowspan="5"> <?php
                        if (($dtOneHeader && isset($dtOneHeader[0])) && ($dtOneHeader[0]['jornada'] == 1)) {
                            echo "07:00 - 13:00";
                        } elseif (($dtOneHeader && isset($dtOneHeader[0])) && ($dtOneHeader[0]['jornada'] == 2)) {
                            echo "13:00 - 18:00";
                        } elseif (($dtOneHeader && isset($dtOneHeader[0])) && ($dtOneHeader[0]['jornada'] == 3)) {
                            echo "18:00 - 22:00";
                        }

?></th>
                    <th class="col-item-title">INSTRUCTOR</th>
                    <td><?= agruparPorDia($dtOne, 1042, 'un') ?></td>
                    <td><?= agruparPorDia($dtOne, 1043, 'un') ?></td>
                    <td><?= agruparPorDia($dtOne, 1044, 'un') ?></td>
                    <td><?= agruparPorDia($dtOne, 1045, 'un') ?></td>
                    <td><?= agruparPorDia($dtOne, 1046, 'un') ?></td>
                    <td><?= agruparPorDia($dtOne, 1047, 'un') ?></td>
                    <td><?= agruparPorDia($dtOne, 1048, 'un') ?></td>
                </tr>
                <tr>
                    <th class="col-item-title">AMBIENTE</th>
                    <td><?= agruparAmbientesPorDia($dtOne, 1042) ?></td>
                    <td><?= agruparAmbientesPorDia($dtOne, 1043) ?></td>
                    <td><?= agruparAmbientesPorDia($dtOne, 1044) ?></td>
                    <td><?= agruparAmbientesPorDia($dtOne, 1045) ?></td>
                    <td><?= agruparAmbientesPorDia($dtOne, 1046) ?></td>
                    <td><?= agruparAmbientesPorDia($dtOne, 1047) ?></td>
                    <td><?= agruparAmbientesPorDia($dtOne, 1048) ?></td>
                </tr>
                <tr>
                    <th class="col-item-title">ACTIVIDAD</th>
                    <td><?= agruparActividadesPorDia($dtOne, 1042) ?></td>
                    <td><?= agruparActividadesPorDia($dtOne, 1043) ?></td>
                    <td><?= agruparActividadesPorDia($dtOne, 1044) ?></td>
                    <td><?= agruparActividadesPorDia($dtOne, 1045) ?></td>
                    <td><?= agruparActividadesPorDia($dtOne, 1046) ?></td>
                    <td><?= agruparActividadesPorDia($dtOne, 1047) ?></td>
                    <td><?= agruparActividadesPorDia($dtOne, 1048) ?></td>
                </tr>
                <tr>
                    <th class="col-item-title">OBSERVACIONES:</th>
                    <td colspan="8"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<script type="text/javascript">window.print();</script>