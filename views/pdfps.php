<?php
ini_set('memory_limit', '512M');
require_once("../models/mpys.php");
require_once("../models/conexion.php");
require_once('../vendor/autoload.php');

use Dompdf\Dompdf;

$mpys = new Mpys();

$documento = $_GET['documento'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$fecha = date('d/m/Y');

$empleado = $mpys->obtenerDatosEmpleadoPorDocumento($documento);
$empleado = $empleado[0] ?? null;

$dependencia = $empleado['dependencia'] ?? '---';
$fecha_contrato = $empleado['fecha_contrato'] ?? 'No registrada';
$numero_contrato = $empleado['numero_contrato'] ?? 'No registrado';

$historial = $mpys->obtenerHistorialDetallesPorUsuario($empleado['idusu']);
$ultima = $historial[0] ?? null;


$html = '';
$html .= '<html><head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        .firmas td { border: none; text-align: center; padding-top: 50px; }
        .no-border { border: none !important; }
    </style>
</head><body>';

$html .= '<h2>FORMATO PAZ Y SALVO</h2>';
$html .= '<h3>Centro de Desarrollo Agroempresarial - SENA Chía</h3>';

$html .= '<table>
    <tr><td><strong>Fecha:</strong></td><td>' . $fecha . '</td></tr>
    <tr><td><strong>Nombre:</strong></td><td>' . htmlspecialchars($nombre) . '</td></tr>
    <tr><td><strong>Documento:</strong></td><td>' . htmlspecialchars($documento) . '</td></tr>
    <tr><td><strong>Dependencia:</strong></td><td>' . htmlspecialchars($dependencia) . '</td></tr>
    <tr><td><strong>Fecha de Contrato:</strong></td><td>' . htmlspecialchars($fecha_contrato) . '</td></tr>
    <tr><td><strong>Número de Contrato:</strong></td><td>' . htmlspecialchars($numero_contrato) . '</td></tr>
</table>';

$html .= '<h3>Última Observación</h3>';
$html .= '<table>
    <tr><td><strong>Observación:</strong></td><td>' . ($ultima['observacion'] ?? 'Sin observación') . '</td></tr>
    <tr><td><strong>Calificación:</strong></td><td>' . (
    isset($ultima['calificacion']) ? ($ultima['calificacion'] == 1 ? 'Aprobado' : 'No Aprobado') : '---') . '</td></tr>
    <tr><td><strong>Fecha:</strong></td><td>' . ($ultima['fechayhora'] ?? '---') . '</td></tr>
</table>';

$html .= '<h3>Historial de Observaciones</h3>';
$html .= '<table>
    <tr><th>Fecha y Hora</th><th>Observación</th><th>Calificación</th></tr>';
if ($historial) {
    foreach ($historial as $h) {
        $cal = $h['calificacion'] == 1 ? 'Aprobado' : 'No Aprobado';
        $html .= '<tr>
            <td>' . $h['fechayhora'] . '</td>
            <td>' . $h['observacion'] . '</td>
            <td>' . $cal . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="3">Sin historial registrado.</td></tr>';
}
$html .= '</table>';

$html .= '<br><h3>OBLIGACIONES</h3>';
$html .= '<p>
    En el cumplimiento de sus funciones con compromiso, respeto a los principios y valores institucionales, 
    promoción de la formación profesional integral y contribución a un ambiente de trabajo adecuado y productivo.
</p>';

$html .= '<br><br><table class="firmas">
    <tr>
        <td>_________________________<br>Firma del Empleado</td>
        <td>_________________________<br>Firma del Jefe Inmediato</td>
    </tr>
</table>';

$html .= '</body></html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Paz_y_Salvo_$documento.pdf", ["Attachment" => false]);
?>
