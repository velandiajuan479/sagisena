<?php
require_once '../models/conexion.php';
include("../models/mrphor.php");
include("../models/mhor.php");
require_once '../controllers/chor.php';

$mrphor = new Mrphor();
$mhor   = new Mhor();
$idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : null;
$mrphor->setIdusu($idusu);
//$dat = $mrphor->getAll();
$dtOne = $mrphor->getOne();

// Trimestre actual desde eventos BD (tipo 'trimestre')
function obtenerTrimestreActualHor() {
    global $mhor;
    $anio = (int)date('Y');
    $hoy  = new DateTime('now', new DateTimeZone('America/Bogota'));
    $eventos = $mhor->getEventosPorTipo('trimestre', $anio);
    foreach ($eventos as $ev) {
        if (!empty($ev['fecha_inicio']) && !empty($ev['fecha_fin'])) {
            $ini = new DateTime($ev['fecha_inicio']);
            $fin = new DateTime($ev['fecha_fin']);
            if ($hoy >= $ini && $hoy <= $fin) {
                return [
                    'nombre' => $ev['trimestre'] ?: 'Trimestre',
                    'anio' => (int)$ev['anio'],
                    'fecha_inicio' => $ev['fecha_inicio'],
                    'fecha_fin' => $ev['fecha_fin']
                ];
            }
        }
    }
    if (!empty($eventos)) {
        $ultimo = end($eventos);
        return [
            'nombre' => $ultimo['trimestre'] ?: 'Trimestre',
            'anio' => (int)$ultimo['anio'],
            'fecha_inicio' => $ultimo['fecha_inicio'],
            'fecha_fin' => $ultimo['fecha_fin']
        ];
    }
    return [
        'nombre' => 'Trimestre',
        'anio' => $anio,
        'fecha_inicio' => date('Y-m-01'),
        'fecha_fin' => date('Y-m-t')
    ];
}

$__trActual = obtenerTrimestreActualHor();

// La función obtenerHorariosInstructorEnRango ya está definida en controllers/chor.php

$dtRange = obtenerHorariosInstructorEnRango($mhor, $idusu, $__trActual['fecha_inicio'], $__trActual['fecha_fin']);

// Determinar tipo de contrato y parámetros esperados
$__contrato = function_exists('obtenerTipoContratoYMaxHoras') ? obtenerTipoContratoYMaxHoras($idusu) : ['tipo_contrato' => 'contratista', 'max_horas' => (defined('CONTRATISTA_MAX_HORAS') ? CONTRATISTA_MAX_HORAS : 180)];
$__tipoContrato = $__contrato['tipo_contrato'] ?? 'contratista';
$__maxContratista = $__contrato['max_horas'] ?? (defined('CONTRATISTA_MAX_HORAS') ? CONTRATISTA_MAX_HORAS : 180);

// Contar días laborales (lunes-viernes, no festivos) en el solapamiento del mes con el rango
function contarDiasLaboralesEnRangoMes($mes, $anio, $rangoInicio, $rangoFin) {
    $inicioMes = new DateTime(sprintf('%04d-%02d-01', $anio, $mes));
    $finMes = clone $inicioMes; $finMes->modify('last day of this month');
    $inicio = max(new DateTime($rangoInicio), $inicioMes);
    $fin = min(new DateTime($rangoFin), $finMes);
    if ($fin < $inicio) return 0;
    $dias = 0;
    $cursor = clone $inicio;
    while ($cursor <= $fin) {
        $dow = (int)$cursor->format('N'); // 1=Lunes..7=Domingo
        $fechaIso = $cursor->format('Y-m-d');
        $anioF = (int)$cursor->format('Y');
        if ($dow >= 1 && $dow <= 5 && (!function_exists('esFechaFestiva') || !esFechaFestiva($fechaIso, $anioF))) {
            $dias++;
        }
        $cursor->modify('+1 day');
    }
    return $dias;
}

function contarDiasLaboralesMesCompleto($mes, $anio) {
    $inicioMes = new DateTime(sprintf('%04d-%02d-01', $anio, $mes));
    $finMes = clone $inicioMes; $finMes->modify('last day of this month');
    $dias = 0; $cursor = clone $inicioMes;
    while ($cursor <= $finMes) {
        $dow = (int)$cursor->format('N');
        $fechaIso = $cursor->format('Y-m-d');
        $anioF = (int)$cursor->format('Y');
        if ($dow >= 1 && $dow <= 5 && (!function_exists('esFechaFestiva') || !esFechaFestiva($fechaIso, $anioF))) {
            $dias++;
        }
        $cursor->modify('+1 day');
    }
    return $dias;
}

// Debug temporal para ver qué está pasando
echo "<!-- DEBUG: idusu = " . ($idusu ?? 'null') . " -->";
echo "<!-- DEBUG: Total horarios obtenidos: " . count($dtRange) . " -->";
if (!empty($dtRange)) {
    echo "<!-- DEBUG: Primer horario: " . json_encode($dtRange[0]) . " -->";
    echo "<!-- DEBUG: IDs de jornada encontrados: " . implode(', ', array_unique(array_column($dtRange, 'jornada'))) . " -->";
    echo "<!-- DEBUG: IDs de día encontrados: " . implode(', ', array_unique(array_column($dtRange, 'iddia'))) . " -->";
    
    // Debug específico para ver qué está pasando en las comparaciones
    echo "<!-- DEBUG: Probando comparación para Lunes Mañana (1042, 1) -->";
    $testLunesManana = agruparFichasPorDiaYJornada($dtRange, 1042, 1);
    echo "<!-- DEBUG: Resultado Lunes Mañana: " . htmlspecialchars($testLunesManana) . " -->";
} else {
    echo "<!-- DEBUG: No se encontraron horarios -->";
}


// Obtener las jornadas disponibles en los datos
$jornadasDisponibles = [];
if (!empty($dtRange)) {
    $idsJornada = array_unique(array_column($dtRange, 'iddia'));
    foreach ($idsJornada as $idJornada) {
        // Buscar el nombre de la jornada en los datos
        foreach ($dtRange as $item) {
            if ($item['iddia'] == $idJornada) {
                $jornadasDisponibles[$idJornada] = $item['jornada'] ?? 'Jornada ' . $idJornada;
                break;
            }
        }
    }
}


// Función para obtener meses del trimestre actual
function obtenerMesesTrimestre($fechaInicio, $fechaFin) {
    $meses = [];
    $inicio = new DateTime($fechaInicio);
    $fin = new DateTime($fechaFin);
    
    // Asegurar que fin sea el último día del mes
    $fin->modify('last day of this month');
    
    $periodo = new DatePeriod($inicio, new DateInterval('P1M'), $fin);
    
    foreach ($periodo as $fecha) {
        $meses[] = [
            'mes' => (int)$fecha->format('n'),
            'año' => (int)$fecha->format('Y'),
            'nombre_mes' => $fecha->format('F')
        ];
    }
    
    return $meses;
}

// La función esFechaFestiva ya está definida en controllers/chor.php

// Función para calcular horas mensuales del instructor usando la lógica de "Ver Detalle"
function calcularHorasMesInstructor($idInstructor, $mes, $año) {
    try {
        // Usar la misma consulta que calcularHorasVerDetalle
        $modelo = new Conexion();
        $conexion = $modelo->get_conexion();
        
        $sql = 'SELECT h.*, f.jornada, v.nomval as nombre_jornada
                FROM horario h
                INNER JOIN ficha f ON h.idfic = f.idfic
                INNER JOIN valor v ON f.jornada = v.idval
                WHERE h.idusu = :id_instructor 
                AND h.fecha_especifica IS NOT NULL
                AND MONTH(h.fecha_especifica) = :mes 
                AND YEAR(h.fecha_especifica) = :anio';
        
        $result = $conexion->prepare($sql);
        $result->bindParam(':id_instructor', $idInstructor);
        $result->bindParam(':mes', $mes);
        $result->bindParam(':anio', $año);
        $result->execute();
        
        $horarios = $result->fetchAll(PDO::FETCH_ASSOC);
        
        // Usar la misma lógica que calcularHorasVerDetalle con filtro de días festivos
        $horasFormacionDirecta = 0;
        $horasOtros = 0;
        
        foreach ($horarios as $horario) {
            // Verificar si la fecha es festiva o fin de semana
            $fechaHorario = $horario['fecha_especifica'];
            $fechaObj = new DateTime($fechaHorario);
            $diaSemana = $fechaObj->format('N'); // 1=Lunes, 7=Domingo
            
            // Solo contar si es día laboral (lunes a viernes) y no es festivo
            if ($diaSemana >= 1 && $diaSemana <= 5 && !esFechaFestiva($fechaHorario, $año)) {
                if ($horario['es_transversal'] || $horario['es_otros'] == 0) {
                    // Horario normal o transversal: usar jornada de la ficha - SIEMPRE FORMACIÓN
                    $horasJornada = obtenerHorasJornadaLocal($horario['jornada']);
                    $horasFormacionDirecta += $horasJornada;
                } elseif ($horario['es_otros'] == 1 && isset($horario['es_formacion_directa']) && $horario['es_formacion_directa'] == 2) {
                    // Actividad "otros" con formación directa: usar horas_otros - CUENTA COMO FORMACIÓN
                    $horasOtrosGrupo = $horario['horas_otros'] ?? 0;
                    $horasFormacionDirecta += $horasOtrosGrupo;
                } else {
                    // Actividad "otros" tradicional: NO CUENTA PARA FORMACIÓN
                    $horasOtrosGrupo = $horario['horas_otros'] ?? 0;
                    $horasOtros += $horasOtrosGrupo;
                }
            }
        }
        
        $totalHoras = $horasFormacionDirecta + $horasOtros;
        
        return [
            'total_horas' => $totalHoras,
            'horas_formacion_directa' => $horasFormacionDirecta,
            'horas_otros' => $horasOtros
        ];
        
    } catch (Exception $e) {
        return [
            'total_horas' => 0,
            'horas_formacion_directa' => 0,
            'horas_otros' => 0
        ];
    }
}


// Función local para obtener horas de jornada
function obtenerHorasJornadaLocal($jornada) {
    $horasJornada = [
        1 => 6, // Mañana
        2 => 5, // Tarde  
        3 => 4  // Noche
    ];
    return $horasJornada[$jornada] ?? 6;
}

// Función para calcular resumen por trimestre
function calcularResumenTrimestre($idInstructor, $fechaInicio, $fechaFin) {
    $meses = obtenerMesesTrimestre($fechaInicio, $fechaFin);
    $resumenMensual = [];
    $totalTrimestre = [
        'total_horas' => 0,
        'horas_formacion_directa' => 0,
        'horas_otros' => 0
    ];
    
    foreach ($meses as $mes) {
        $horasMes = calcularHorasMesInstructor($idInstructor, $mes['mes'], $mes['año']);
        $resumenMensual[] = [
            'mes' => $mes['mes'],
            'año' => $mes['año'],
            'nombre_mes' => $mes['nombre_mes'],
            'horas' => $horasMes
        ];
        
        // Sumar al total del trimestre
        $totalTrimestre['total_horas'] += $horasMes['total_horas'];
        $totalTrimestre['horas_formacion_directa'] += $horasMes['horas_formacion_directa'];
        $totalTrimestre['horas_otros'] += $horasMes['horas_otros'];
    }
    
    return [
        'resumen_mensual' => $resumenMensual,
        'total_trimestre' => $totalTrimestre
    ];
}

// Calcular resumen del trimestre actual
$resumenTrimestre = calcularResumenTrimestre($idusu, $__trActual['fecha_inicio'], $__trActual['fecha_fin']);


// Utilidades de apilado
function _formatearFechaCorta($iso) {
    if (empty($iso) || $iso === 'SIN_FECHA') return '';
    $meses = [1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'];
    $d = new DateTime($iso);
    return $d->format('j') . ' de ' . $meses[(int)$d->format('n')];
}
function _ordenarFechas(&$fechas) {
    $fechas = array_values(array_unique(array_filter($fechas, fn($f)=>!empty($f))));
    sort($fechas);
}
function agruparAulasPorDiaYJornada($dt, $iddia, $jornada) {
    if (!$dt) return '';
    
    $map = [];
    foreach ($dt as $it) {
        
        // Comparar iddia (ID numérico) y jornada (ID numérico)
        $itJornada = $it['jornada'] ?? null;
        $itIddia = $it['iddia'] ?? null;
        
        if ($itIddia == $iddia && $itJornada == $jornada) {
            $amb = trim(($it['idaul'] ?? '') . ' - ' . ($it['nomaul'] ?? ''));
            if ($amb === ' - ') continue;
            if (!isset($map[$amb])) $map[$amb] = [];
            $map[$amb][] = $it['fecha_especifica'] ?? 'SIN_FECHA';
        }
    }
    
    if (empty($map)) return '';
    ksort($map, SORT_NATURAL | SORT_FLAG_CASE);
    $out = '';
    foreach ($map as $nombre => $fechas) {
        _ordenarFechas($fechas);
        $fechasFmt = array_map('_formatearFechaCorta', array_filter($fechas, fn($f)=>$f!=='SIN_FECHA'));
        
        $line = '<div class="item-line"><span class="item-name">' . htmlspecialchars($nombre) . '</span>';
        if (!empty($fechasFmt)) $line .= ' <span class="item-dates">(' . htmlspecialchars(implode(', ', $fechasFmt)) . ')</span>';
        if (in_array('SIN_FECHA', $fechas, true)) $line .= ' <span class="item-dates">(Sin fecha específica)</span>';
        $line .= '</div>';
        $out .= $line;
    }
    return $out;
}
function agruparFichasPorDiaYJornada($dt, $iddia, $jornada) {
    if (!$dt) return '';
    $grupos = [];
    
    foreach ($dt as $it) {
        
        // Comparar iddia (ID numérico) y jornada (ID numérico)
        $itJornada = $it['jornada'] ?? null;
        $itIddia = $it['iddia'] ?? null;
        
        if ($itIddia == $iddia && $itJornada == $jornada) {
            $grp = trim(($it['idfic'] ?? '') . ' - ' . ($it['nomfic'] ?? ''));
            if ($grp === ' - ') continue;
            if (!isset($grupos[$grp])) {
                $grupos[$grp] = ['fechas'=>[], 'actividades'=>[], 'transversales'=>[]];
            }
            $grupos[$grp]['fechas'][] = $it['fecha_especifica'] ?? 'SIN_FECHA';
            
            // Verificar si es actividad "Otros"
            if (!empty($it['es_otros'])) {
                $actNombre = $it['actividad'] ?? '';
                $actClave = $actNombre . '_' . (string)($it['horas_otros'] ?? '0');
                if ($actNombre !== '') {
                    if (!isset($grupos[$grp]['actividades'][$actClave])) {
                        $grupos[$grp]['actividades'][$actClave] = [
                            'nombre'=>$actNombre,
                            'horas'=>(int)($it['horas_otros'] ?? 0),
                            'fechas'=>[]
                        ];
                    }
                    $grupos[$grp]['actividades'][$actClave]['fechas'][] = $it['fecha_especifica'] ?? 'SIN_FECHA';
                }
            }
            
            // Verificar si es horario transversal
            if (!empty($it['es_transversal'])) {
                $transNombre = $it['nombre_transversal'] ?? '';
                if ($transNombre !== '') {
                    if (!isset($grupos[$grp]['transversales'][$transNombre])) {
                        $grupos[$grp]['transversales'][$transNombre] = [
                            'nombre'=>$transNombre,
                            'fechas'=>[]
                        ];
                    }
                    $grupos[$grp]['transversales'][$transNombre]['fechas'][] = $it['fecha_especifica'] ?? 'SIN_FECHA';
                }
            }
        }
    }
    if (empty($grupos)) return '';
    ksort($grupos, SORT_NATURAL | SORT_FLAG_CASE);
    $out = '';
    foreach ($grupos as $gNombre => $info) {
        _ordenarFechas($info['fechas']);
        $fechasFmt = array_map('_formatearFechaCorta', array_filter($info['fechas'], fn($f)=>$f!=='SIN_FECHA'));
        
        $line = '<div class="item-line"><span class="item-name">' . htmlspecialchars($gNombre) . '</span>';
        if (!empty($fechasFmt)) $line .= ' <span class="item-dates">(' . htmlspecialchars(implode(', ', $fechasFmt)) . ')</span>';
        if (in_array('SIN_FECHA', $info['fechas'], true)) $line .= ' <span class="item-dates">(Sin fecha específica)</span>';
        $line .= '</div>';
        $out .= $line;
        
        // Mostrar actividades "otros"
        if (!empty($info['actividades'])) {
            uasort($info['actividades'], fn($a,$b)=>strnatcasecmp($a['nombre'],$b['nombre']));
            foreach ($info['actividades'] as $act) {
                _ordenarFechas($act['fechas']);
                $grupoIso = array_values(array_unique(array_filter($info['fechas'], fn($f)=>$f!=='SIN_FECHA')));
                $actIso   = array_values(array_unique(array_filter($act['fechas'], fn($f)=>$f!=='SIN_FECHA')));
                sort($grupoIso); sort($actIso);
                $grupoSinFecha = in_array('SIN_FECHA', $info['fechas'], true);
                $actSinFecha   = in_array('SIN_FECHA', $act['fechas'], true);
                $mostrarFechasActividad = ($actIso !== $grupoIso) || ($actSinFecha && !$grupoSinFecha) || (!$actSinFecha && $grupoSinFecha);
                $out .= '<div class="item-line"><span class="item-name">' . htmlspecialchars($act['nombre']) . '</span>';
                if (!empty($act['horas'])) $out .= ' <span class="item-hours">(' . (int)$act['horas'] . ' hrs)</span>';
                if ($mostrarFechasActividad && !empty($actIso)) {
                    $out .= ' <span class="item-dates">(' . htmlspecialchars(implode(', ', array_map('_formatearFechaCorta', $actIso))) . ')</span>';
                }
                if ($mostrarFechasActividad && $actSinFecha) {
                    $out .= ' <span class="item-dates">(Sin fecha específica)</span>';
                }
                $out .= '</div>';
            }
        }
        
        // Mostrar horarios transversales
        if (!empty($info['transversales'])) {
            uasort($info['transversales'], fn($a,$b)=>strnatcasecmp($a['nombre'],$b['nombre']));
            foreach ($info['transversales'] as $trans) {
                _ordenarFechas($trans['fechas']);
                $grupoIso = array_values(array_unique(array_filter($info['fechas'], fn($f)=>$f!=='SIN_FECHA')));
                $transIso = array_values(array_unique(array_filter($trans['fechas'], fn($f)=>$f!=='SIN_FECHA')));
                sort($grupoIso); sort($transIso);
                $grupoSinFecha = in_array('SIN_FECHA', $info['fechas'], true);
                $transSinFecha = in_array('SIN_FECHA', $trans['fechas'], true);
                $mostrarFechasTransversal = ($transIso !== $grupoIso) || ($transSinFecha && !$grupoSinFecha) || (!$transSinFecha && $grupoSinFecha);
                $out .= '<div class="item-line"><span class="item-name">[TRANSVERSAL] ' . htmlspecialchars($trans['nombre']) . '</span>';
                if ($mostrarFechasTransversal && !empty($transIso)) {
                    $out .= ' <span class="item-dates">(' . htmlspecialchars(implode(', ', array_map('_formatearFechaCorta', $transIso))) . ')</span>';
                }
                if ($mostrarFechasTransversal && $transSinFecha) {
                    $out .= ' <span class="item-dates">(Sin fecha específica)</span>';
                }
                $out .= '</div>';
            }
        }
    }
    return $out;
}
?>

<style>
@font-face {
    font-family: 'Roboto';
    src: url('../font/Fugaz_One-Roboto/Roboto/Roboto-VariableFont_wdth\,wght.ttf') format('woff2-variations');
    font-weight: 100 900;
    font-stretch: 75% 125%;
    font-style: normal;
}

@font-face {
    font-family: 'Roboto';
    src: url('../font/Fugaz_One-Roboto/Roboto/Roboto-Italic-VariableFont_wdth\,wght.ttf') format('woff2-variations');
    font-weight: 100 900;
    font-stretch: 75% 125%;
    font-style: italic;
}

* {
    font-family: "Roboto", sans-serif;
    font-size: 11px;
}
/* Bordes delgados y elegantes para todas las tablas */
table { border-collapse: collapse; }
table td, table th { border: 1px solid #B6BCC6 !important; }
/* Salida compacta alineada */
td { text-align: left; vertical-align: top; font-size: 11px; }
.item-line { line-height: 1.25; margin: 2px 0; }
.item-name { font-weight: 700; margin-right: 4px; font-size: 11px; }
.item-hours { font-weight: 600; margin-left: 2px; font-size: 10px; }
.item-dates { color: #333; font-size: 10px; margin-left: 6px; }
@media print {
    html, body { width: 216mm; height: 279mm; font-size: 8.8px; line-height: 1.15; }
    th, td { padding: 3px 5px !important; }
    td { font-size: 8.8px; }
    .item-name { font-size: 9px; }
    .item-hours { font-size: 8.8px; }
    .item-dates { font-size: 8.2px; }
    /* Asegurar que las tablas no se corten */
    table { page-break-inside: avoid; }
    /* Ajustar márgenes para mejor impresión */
    @page { margin: 1cm; }
}
</style>

<table width="950px">
    <tr>
        <td rowspan="3" align="center"><img src="../image/sena.png" width="50px"></td>
        <td>SENA - REGIONAL CUNDINAMARCA</td>
        <td colspan="2" align="center" style="border: 1px solid #B6BCC6;">VIGENCIA: <?= htmlspecialchars($__trActual['anio']) ?></td>
    </tr>
    <tr>
        <td>CENTRO DE DESARROLLO AGROEMPRESARIAL CHIA</td>
        <td colspan="2" align="center" style="border: 1px solid #B6BCC6;"><?= htmlspecialchars(strtoupper($__trActual['nombre'])) ?></td>
    </tr>
    <tr>
        <td></td>
        <td align="center" style="border: 1px solid #B6BCC6;">INICIO: <?= htmlspecialchars($__trActual['fecha_inicio']) ?></td>
        <td align="center" style="border: 1px solid #B6BCC6;">TERMINACION: <?= htmlspecialchars($__trActual['fecha_fin']) ?></td>
    </tr>
</table>
<br>
<table width="950px">
    <tr>
        <td align="center" style="border: 1px solid #000;"><b>Instructor</b></td>
        <td align="center" style="border: 1px solid #000;"><?php if ($dtOne && isset($dtOne[0])) {
            echo $dtOne[0]['un'];
        } ?></td>
        <td></td>
        <td align="center" style="border: 1px solid #000;"><b>MUNICIPIO</b></td>
        <td align="center" style="border: 1px solid #000;"><?php if ($dtOne && isset($dtOne[0])) {
            echo $dtOne[0]['nomubi'];
        } ?></td>
    </tr>
</table>
<br>
<table width="950px" cellpadding="5px" cellspacing="0">
        <thead>
            <th colspan="2"></th>
            <th style="border: 2px solid #000;"><b>LUNES</b></th>
            <th style="border: 2px solid #000;"><b>MARTES</b></th>
            <th style="border: 2px solid #000;"><b>MIERCOLES</b></th>
            <th style="border: 2px solid #000;"><b>JUEVES</b></th>
            <th style="border: 2px solid #000;"><b>VIERNES</b></th>
            <th style="border: 2px solid #000;"><b>SABADO</b></th>
            <th style="border: 2px solid #000;"><b>DOMINGO</b></th>
        </thead>
        <?php 
        // Usar la misma estructura que vrphau.php: filas fijas por horario
        ?>
        <tr>
            <td rowspan="2" style="border: 2px solid #000;">07:00 - 13:00</td>
            <td style="border: 2px solid #000;">AULA</td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1042, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1043, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1044, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1045, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1046, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1047, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1048, 1) ?></td>
        </tr>
        <tr>
            <td style="border: 2px solid #000;">FICHA</td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1042, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1043, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1044, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1045, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1046, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1047, 1) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1048, 1) ?></td>
        </tr>
        <tr>
            <td rowspan="2" style="border: 2px solid #000;">13:00 - 18:00</td>
            <td style="border: 2px solid #000;">AULA</td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1042, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1043, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1044, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1045, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1046, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1047, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1048, 2) ?></td>
        </tr>
        <tr>
            <td style="border: 2px solid #000;">FICHA</td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1042, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1043, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1044, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1045, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1046, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1047, 2) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1048, 2) ?></td>
        </tr>
        <tr>
            <td rowspan="2" style="border: 2px solid #000;">18:00 - 22:00</td>
            <td style="border: 2px solid #000;">AULA</td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1042, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1043, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1044, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1045, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1046, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1047, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparAulasPorDiaYJornada($dtRange, 1048, 3) ?></td>
        </tr>
        <tr>
            <td style="border: 2px solid #000;">FICHA</td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1042, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1043, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1044, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1045, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1046, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1047, 3) ?></td>
            <td style="border: 2px solid #000;"><?= agruparFichasPorDiaYJornada($dtRange, 1048, 3) ?></td>
        </tr>
        <?php 
        ?>
    </table>

    <br>
    <br>
    
    <!-- Tabla de Resumen por Trimestre -->
    <table width="950px" cellpadding="5px" cellspacing="0">
        <thead>
            <tr>
                <th colspan="4" style="border: 2px solid #000; background-color: #f0f0f0; text-align: center;">
                    <b>RESUMEN DE HORAS POR TRIMESTRE - <?= htmlspecialchars(strtoupper($__trActual['nombre'])) ?> <?= htmlspecialchars($__trActual['anio']) ?></b>
                </th>
        </tr>
        <tr>
                <th style="border: 2px solid #000; background-color: #e0e0e0;"><b>MES</b></th>
                <th style="border: 2px solid #000; background-color: #e0e0e0;"><b>FORMACIÓN<?= ($__tipoContrato === 'planta' ? ' (x/x)' : '') ?></b></th>
                <th style="border: 2px solid #000; background-color: #e0e0e0;"><b>OTROS<?= ($__tipoContrato === 'planta' ? ' (x/x)' : '') ?></b></th>
                <th style="border: 2px solid #000; background-color: #e0e0e0;"><b>TOTAL (x/x)</b></th>
        </tr>
        </thead>
        <tbody>
            <?php 
            $mesesEspanol = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
                7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];
            // Calcular esperadas por mes con días laborales dentro del rango del trimestre
            $meses = obtenerMesesTrimestre($__trActual['fecha_inicio'], $__trActual['fecha_fin']);
            $totalEsperadas = 0; $totalEsperadasForm = 0; $totalEsperadasOtros = 0;
            $sumFormAsig = 0; $sumOtrosAsig = 0; $sumTotalAsig = 0;
            foreach ($meses as $mesData): 
                $mesNombre = $mesesEspanol[$mesData['mes']] ?? 'Mes ' . $mesData['mes'];
                $horas = calcularHorasMesInstructor($idusu, $mesData['mes'], $mesData['año']);
                $diasLaboralesEnRango = contarDiasLaboralesEnRangoMes($mesData['mes'], $mesData['año'], $__trActual['fecha_inicio'], $__trActual['fecha_fin']);
                $diasLaboralesMesCompleto = contarDiasLaboralesMesCompleto($mesData['mes'], $mesData['año']);
                if ($diasLaboralesMesCompleto <= 0) $diasLaboralesMesCompleto = 1;
                $factorForm = defined('FACTOR_FORMACION') ? FACTOR_FORMACION : 6.4;
                $factorOtros = defined('FACTOR_OTROS') ? FACTOR_OTROS : 2.1;
                $esperadasMes = ($__tipoContrato === 'planta')
                    ? ($diasLaboralesEnRango * ($factorForm + $factorOtros))
                    : ($__maxContratista * ($diasLaboralesEnRango / $diasLaboralesMesCompleto));
                // Desglose esperadas por formación y otros
                if ($__tipoContrato === 'planta') {
                    $esperadasFormMes = $diasLaboralesEnRango * $factorForm;
                    $esperadasOtrosMes = $diasLaboralesEnRango * $factorOtros;
                } else {
                    $totalFactor = ($factorForm + $factorOtros);
                    $ratioForm = $totalFactor > 0 ? ($factorForm / $totalFactor) : 0.75;
                    $esperadasFormMes = $esperadasMes * $ratioForm;
                    $esperadasOtrosMes = $esperadasMes - $esperadasFormMes;
                }
                $totalEsperadas += $esperadasMes; 
                $totalEsperadasForm += $esperadasFormMes; 
                $totalEsperadasOtros += $esperadasOtrosMes;
                $sumFormAsig += $horas['horas_formacion_directa'];
                $sumOtrosAsig += $horas['horas_otros'];
                $sumTotalAsig += $horas['total_horas'];
            ?>
            <tr>
                <td style="border: 2px solid #000; text-align: center; font-weight: bold;"><?= htmlspecialchars($mesNombre) ?></td>
                <td style="border: 2px solid #000; text-align: center;"><?= ($__tipoContrato === 'planta' ? (number_format($horas['horas_formacion_directa'], 0) . '/' . number_format($esperadasFormMes, 1)) : number_format($horas['horas_formacion_directa'], 0)) ?></td>
                <td style="border: 2px solid #000; text-align: center;"><?= ($__tipoContrato === 'planta' ? (number_format($horas['horas_otros'], 0) . '/' . number_format($esperadasOtrosMes, 1)) : number_format($horas['horas_otros'], 0)) ?></td>
                <td style="border: 2px solid #000; text-align: center; font-weight: bold; background-color: #f0f0f0;"><?= number_format($horas['total_horas'], 0) ?>/<?= number_format($esperadasMes, 1) ?></td>
        </tr>
            <?php endforeach; ?>
            
            <!-- Fila de totales -->
            <tr style="background-color: #e8e8e8;">
                <td style="border: 2px solid #000; text-align: center; font-weight: bold; background-color: #d0d0d0;">TOTAL TRIMESTRE</td>
                <td style="border: 2px solid #000; text-align: center; font-weight: bold; background-color: #d0d0d0;"><?= ($__tipoContrato === 'planta' ? (number_format($sumFormAsig, 0) . '/' . number_format($totalEsperadasForm, 1)) : number_format($sumFormAsig, 0)) ?></td>
                <td style="border: 2px solid #000; text-align: center; font-weight: bold; background-color: #d0d0d0;"><?= ($__tipoContrato === 'planta' ? (number_format($sumOtrosAsig, 0) . '/' . number_format($totalEsperadasOtros, 1)) : number_format($sumOtrosAsig, 0)) ?></td>
                <td style="border: 2px solid #000; text-align: center; font-weight: bold; background-color: #d0d0d0;"><?= number_format($sumTotalAsig, 0) ?>/<?= number_format($totalEsperadas, 1) ?></td>
        </tr>
        </tbody>
    </table>

    <!-- Espaciado adicional para evitar que la tabla se corte -->
    <br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br>

    <script type='text/javascript'>window.print();</script>