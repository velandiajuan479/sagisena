<?php
ini_set('memory_limit', '512M');
require_once("../models/mbit.php");
require_once("../models/mevb.php");
require_once("../models/conexion.php");
require_once('../vendor/autoload.php');
include "../controllers/optimg.php";

use Dompdf\Dompdf;

// --------CONTROLADOR----------
$mbit = new Mbit();
$mevb = new Mevb();

$pdf = isset($_GET['pdf']) ? $_GET['pdf'] : NULL;
$idusu = isset($_GET['idusu']) ? $_GET['idusu'] : NULL;
$idbitacora = isset($_GET['idbitacora']) ? $_GET['idbitacora'] : NULL;

// Función para convertir imágenes a base64
function imagenes($imgn) {
    if (file_exists($imgn)) {
        $imgnBase64 = "data:image/png;base64," . base64_encode(file_get_contents($imgn));
        return $imgnBase64;
    }
    return null;
}

function urlimg($url) {
    if (file_exists($url)) {
        $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
        return $imagenBase64;
    }
    return null;
}

// Obtener datos
$datOne = $mbit->getOneUsu($idusu);
$datOne = $mevb->getOneUsu($idusu); 
$datPrograma = $mbit->getNombrePrograma($idusu);
$datFicha = $mbit->getFichaUsuario($idusu);

if ($idbitacora) {
    $todasBitacoras = $mbit->getBitacorasByUsuario($idusu);
    $datBitacora = array_filter($todasBitacoras, function($bit) use ($idbitacora) {
        return $bit['idbitacora'] == $idbitacora;
    });
    $datBitacora = array_values($datBitacora);
} else {
    $datBitacora = $mbit->getBitacorasByUsuario($idusu);
}

$datActividades = $mbit->getActividadesByUsuario($idusu);
$datActividadesByBitacoras = $mbit->getActividadesByBitacora($idbitacora);

// Generar HTML
$html = '';
$html .= '<style>
    @page { 
        margin: 0.8cm; 
        size: letter;
    }
    body { 
        font-family: Arial, sans-serif; 
        font-size: 8px; 
        line-height: 1.1;
        margin: 0;
        padding: 10px;
        background: white;
    }
    .container {
        width: 100%;
        max-width: none;
    }
    table { 
        border-collapse: collapse; 
        width: 100%; 
        margin: 0 0 3px 0;
    }
    td { 
        border: 1px solid #000; 
        padding: 2px 3px; 
        vertical-align: top;
        font-size: 8px;
        line-height: 1.1;
    }
    .no-border { border: none; }
    .border-bottom { border-bottom: 1px solid #000; }
    
    /* Clasificación superior */
    .clasificacion-text {
        font-size: 7px;
        font-weight: bold;
        margin-bottom: 3px;
    }
    .clasificacion-row td {
        font-size: 7px;
        text-align: center;
        padding: 1px 2px;
    }
    
    /* Encabezado */
    .header-logo {
        width: 70px;
        text-align: center;
        vertical-align: middle;
        font-size: 6px;
    }
    .header-main {
        background-color: #000;
        color: white;
        text-align: center;
        font-weight: bold;
        font-size: 9px;
        padding: 3px;
    }
    .header-info {
        text-align: center;
        font-size: 7px;
        vertical-align: middle;
        width: 140px;
        padding: 3px;
    }
    
    /* Labels y campos */
    .label {
        font-weight: bold;
        font-size: 7px;
        background-color: #f5f5f5;
    }
    .field {
        font-size: 8px;
        background-color: white;
    }
    .field-data {
        font-size: 8px;
        background-color: white;
        color: #0066cc;
    }
    
    /* Secciones especiales */
    .section-title {
        font-weight: bold;
        font-size: 8px;
        text-align: center;
        background-color: #e0e0e0;
        padding: 3px;
    }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    
    /* Tabla de alternativas */
    .alt-header {
        font-weight: bold;
        font-size: 7px;
        text-align: center;
        background-color: #f0f0f0;
        padding: 2px;
    }
    .alt-field {
        font-size: 7px;
        padding: 2px;
        text-align: center;
    }
    
    /* Tabla de actividades */
    .act-header {
        font-weight: bold;
        font-size: 7px;
        text-align: center;
        background-color: #f0f0f0;
        padding: 2px;
    }
    .act-field {
        font-size: 7px;
        padding: 3px;
        vertical-align: top;
        min-height: 25px;
    }
    
    /* Información ARL */
    .arl-info {
        font-size: 6px;
        padding: 3px;
        background-color: #f9f9f9;
        margin: 3px 0;
        border: 1px solid #ccc;
    }
    
    /* Firmas */
    .firma-cell {
        height: 60px;
        text-align: center;
        vertical-align: bottom;
        padding: 5px;
        font-size: 7px;
    }
    .firma-line {
        border-top: 1px solid #000;
        margin-top: 20px;
        padding-top: 3px;
        font-weight: bold;
    }
    
    /* Logo placeholder */
    .logo-box {
        width: 50px;
        height: 50px;
        border: 1px dashed #999;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 6px;
        color: #666;
        background: #fafafa;
    }
    
    /* Checkboxes */
    .checkbox {
        font-size: 10px;
        font-weight: bold;
    }
    
    /* Notas finales */
    .nota-final {
        font-size: 7px;
        margin-top: 5px;
        padding: 3px;
        text-align: justify;
        line-height: 1.2;
    }
</style>';

$html .= '<body>';
$html .= '<div class="container">';

// ENCABEZADO PRINCIPAL
$html .= '<table>
    <tr>
        <td rowspan="3" class="header-logo">';
$logoSena = urlimg('../image/sena.png');
if ($logoSena) {
    $html .= '<img src="' . $logoSena . '" width="50px">';
} else {
    $html .= '<div class="logo-box">LOGO<br>SENA</div>';
}
$html .= '</td>
        <td class="header-main">SERVICIO NACIONAL DE APRENDIZAJE SENA</td>
        <td rowspan="3" class="header-info">
            <div style="font-weight: bold;">Código:</div>
            <div>GFPI-F-147</div>
            <div style="font-weight: bold;">Versión: 04</div>
            <br>
            <div style="font-weight: bold;">PROCESO</div>
            <div style="font-weight: bold;">GESTIÓN DE FORMACIÓN PROFESIONAL INTEGRAL</div>
            <br>
            <div style="font-weight: bold;">NOMBRE DEL FORMATO</div>
            <div style="font-weight: bold;">FORMATO BITÁCORA DE SEGUIMIENTO ETAPA PRODUCTIVA</div>
        </td>
    </tr>
    <tr>
        <td class="header-main">GESTIÓN DE FORMACIÓN PROFESIONAL INTEGRAL</td>
    </tr>
    <tr>
        <td class="header-main">FORMATO BITÁCORA DE SEGUIMIENTO ETAPA PRODUCTIVA</td>
    </tr>
</table>';

$html .= '<table style="margin-bottom: 5px;">
    <tr class="clasificacion-row">
        <td style="width: 16.66%; font-weight: bold;">Pública</td>
        <td style="width: 16.66%; border:1px solid #000; text-align:center;">X</td>
        <td style="width: 16.66%; font-weight: bold;">Pública Reservada</td>
        <td style="width: 16.66%; border:1px solid #000;"></td>
        <td style="width: 16.66%; font-weight: bold;">Pública Clasificada</td>
        <td style="width: 16.66%; border:1px solid #000;"></td>
    </tr>
</table>';

// DATOS DEL APRENDIZ
$html .= '<table>
    <tr>
        <td class="label" style="width: 25%;">Nombre de la persona con rol de aprendiz:</td>
        <td class="field-data" style="width: 30%;">' . (!empty($datOne) ? htmlspecialchars($datOne[0]['nomusu']) : '') . '</td>
        <td class="label" style="width: 15%;">Tipo de documento</td>
        <td class="field-data" style="width: 10%;">' . (!empty($datOne) ? htmlspecialchars($datOne[0]['tipo_doc'] ?? 'CC') : '') . '</td>
        <td class="label" style="width: 20%;">Número de identificación</td>
        <td class="field-data">' . (!empty($datOne) ? htmlspecialchars($datOne[0]['ndocusu']) : '') . '</td>
    </tr>
    <tr>
        <td class="label">Teléfono de contacto</td>
        <td class="field-data">' . (!empty($datOne) ? htmlspecialchars($datOne[0]['telcan']) : '') . '</td>
        <td class="label">Correo electrónico institucional</td>
        <td class="field-data" colspan="3">' . (!empty($datOne) ? htmlspecialchars($datOne[0]['emausu']) : '') . '</td>
    </tr>
    <tr>
        <td class="label">Correo electrónico personal</td>
        <td class="field-data">' . (!empty($datOne) ? htmlspecialchars($datOne[0]['email_personal'] ?? $datOne[0]['emausu']) : '') . '</td>
        <td class="label">Número de grupo</td>
        <td class="field-data">' . (!empty($datFicha) ? htmlspecialchars($datFicha[0]['idfic']) : '') . '</td>
        <td class="label" colspan="2">Programa de formación</td>
    </tr>
    <tr>
        <td class="field-data" colspan="6">' . (!empty($datPrograma) ? htmlspecialchars($datPrograma[0]['nomfic']) : '') . '</td>
    </tr>
</table>';

// DATOS DE LA EMPRESA
$html .= '<table>
    <tr>
        <td class="label" style="width: 35%;">Nombre de la empresa, ente u organización donde está realizando la etapa productiva</td>
        <td class="field-data" style="width: 30%;">' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['nombre_empresa']) : '') . '</td>
        <td class="label" style="width: 8%;">NIT</td>
        <td class="field-data" style="width: 15%;">' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['nit']) : '') . '</td>
        <td class="label" style="width: 12%;">Bitácora N°</td>
        <td class="field-data">' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['numero_bitacora']) : '') . '</td>
    </tr>
    <tr>
        <td class="label">Período a reportar</td>
        <td class="field-data" colspan="5">' . (!empty($datBitacora) 
            ? 'Desde ' . htmlspecialchars($datBitacora[0]['fecha_inicio']) . ' hasta ' . htmlspecialchars($datBitacora[0]['fecha_fin']) 
            : '') . '</td>
    </tr>
</table>';

// DATOS DEL ENTE COFORMADOR
$datjefeperfil = $mbit->getPerfil();
$datjefe = $mbit->getJefe();

$html .= '<table>
    <tr>
        <td class="label" style="width: 35%;">Nombre del Ente Coformador (jefe inmediato/responsable/supervisor)</td>
        <td class="field-data" style="width: 30%;">' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['nombre_jefe'] ?? '') : '') . '</td>
        <td class="label" style="width: 20%;">Cargo del Ente Coformador</td>
        <td class="field-data">' . (!empty($datBitacora) ? htmlspecialchars($datjefeperfil[0]['nomper'] ?? '') : '') . '</td>
    </tr>
    <tr>
        <td class="label">Teléfono de contacto</td>
        <td class="field-data">' . (!empty($datBitacora) ? htmlspecialchars($datjefe[0]['telcan'] ?? '') : '') . '</td>
        <td class="label">Correo electrónico</td>
        <td class="field-data">' . (!empty($datBitacora) ? htmlspecialchars($datjefe[0]['emausu'] ?? '') : '') . '</td>
    </tr>
</table>';

// DATOS DEL INSTRUCTOR
$html .= '<table>
    <tr>
        <td class="section-title" colspan="4">Datos de la persona con rol de instructor de seguimiento</td>
    </tr>
    <tr>
        <td class="label" style="width: 35%;">Nombre de la persona con rol de instructor de seguimiento:</td>
        <td class="field-data" style="width: 30%;">' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['nombre_instructor'] ?? '') : '') . '</td>
        <td class="label" style="width: 15%;">Correo electrónico:</td>
        <td class="field-data">' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['email_instructor'] ?? '') : '') . '</td>
    </tr>
</table>';

// Traer alternativas y subalternativas desde el modelo
$alternativas = $mbit->getNombresDominios();
$subalternativas = $mbit->getValoresPorDominio();

// Traer la alternativa y subalternativa seleccionada por el usuario
$alternativaData = $mbit->getAlternativaAndSubalternativa($idbitacora);
$alternativaSel = $alternativaData['alternativa'] ?? '';
$subalternativaSel = $alternativaData['subalternativa'] ?? '';

$grupo1 = array_slice($alternativas, 0, 2);
$grupo2 = array_slice($alternativas, 2, 3);

function construirTabla($grupoAlternativas, $subalternativas, $alternativaSel, $subalternativaSel) {
    $html = '<table border="1" cellpadding="5" cellspacing="0" width="100%" style="margin-bottom:0;">
        <tr>
            <td class="section-title" colspan="3" align="center">
                ALTERNATIVA Y SUBALTERNATIVA DE ETAPA PRODUCTIVA
            </td>
        </tr>
        <tr>
            <td class="alt-header" style="width: 30%;"><strong>ALTERNATIVA</strong></td>
            <td class="alt-header" style="width: 50%;"><strong>SUBALTERNATIVA</strong></td>
            <td class="alt-header" style="width: 20%;"><strong>SELECCIÓN</strong></td>
        </tr>';

    foreach ($grupoAlternativas as $alt) {
        $altNombre = $alt['nomdom'];
        $altId = $alt['iddom'];

        // Filtrar subalternativas de esta alternativa
        $subsDeAlt = array_filter($subalternativas, function($s) use ($altId) {
            return $s['iddom'] == $altId;
        });

        $rowspan = count($subsDeAlt);
        $primera = true;

        foreach ($subsDeAlt as $sub) {
            $subNombre = $sub['nomval'];
            $marcado = ($altNombre == $alternativaSel && $subNombre == $subalternativaSel) ? 'X' : '';

            $html .= '<tr>';
            if ($primera) {
                $html .= '<td class="alt-field" rowspan="' . $rowspan . '" align="center" valign="middle">' . htmlspecialchars($altNombre) . '</td>';
                $primera = false;
            }
            $html .= '
                <td class="alt-field">' . htmlspecialchars($subNombre) . '</td>
                <td class="alt-field" align="center">' . $marcado . '</td>
            </tr>';
        }
    }

    $html .= '</table>';
    return $html;
}

// Mostrar ambas tablas en una fila usando una tabla contenedora
$html .= '
<table width="100%" style="margin-bottom:20px;">
    <tr>
        <td valign="top" style="width:50%;padding-right:5px;">' . construirTabla($grupo1, $subalternativas, $alternativaSel, $subalternativaSel) . '</td>
        <td valign="top" style="width:50%;padding-left:5px;">' . construirTabla($grupo2, $subalternativas, $alternativaSel, $subalternativaSel) . '</td>
    </tr>
</table>
';



$html .= '<table>
    <tr>
        <td class="act-header" style="width: 30%;">DESCRIPCIÓN DE LA ACTIVIDAD<br>(Ingrese cuantas filas sean necesarias)</td>
        <td class="act-header" style="width: 12%;">FECHA DE INICIO</td>
        <td class="act-header" style="width: 12%;">FECHA DE FIN</td>
        <td class="act-header" style="width: 23%;">EVIDENCIA DE CUMPLIMIENTO<br>(Indique si corresponde a un documento, proceso, producto, entregable u otro )<br>En anexo puede fortalecer la evidencia si es el caso.</td>
        <td class="act-header" style="width: 23%;">OBSERVACIONES, INASISTENCIAS, DIFICULTADES PRESENTADAS Y/O COMENTARIOS REALIZADOS POR EL INSTRUCTOR</td>
    </tr>';

$actividades = !empty($datActividadesByBitacoras) ? $datActividadesByBitacoras : [];
$numActividades = count($actividades);

// Si no hay actividades, mostrar 3 filas vacías
if ($numActividades === 0) {
    $numActividades = 3;
    for ($i = 0; $i < 3; $i++) {
        $html .= '<tr>
            <td class="act-field"></td>
            <td class="act-field center"></td>
            <td class="act-field center"></td>
            <td class="act-field"></td>';
        // Solo en la primera fila se muestra la celda de observaciones con rowspan
        if ($i === 0) {
            $html .= '<td class="act-field" rowspan="3">' . htmlspecialchars($datBitacora[0]['observacion'] ?? '') . '</td>';
        }
        $html .= '</tr>';
    }
} else {
    foreach ($actividades as $idx => $actividad) {
        $html .= '<tr>
            <td class="act-field">' . htmlspecialchars($actividad['descripcion_actividad']) . '</td>
            <td class="act-field center">' . htmlspecialchars($actividad['fecha_inicio_act'] ?? '') . '</td>
            <td class="act-field center">' . htmlspecialchars($actividad['fecha_fin_act'] ?? '') . '</td>
            <td class="act-field">' . htmlspecialchars($actividad['evidencia_cumplimiento'] ?? '') . '</td>';
        // Solo en la primera fila se muestra la celda de observaciones con rowspan igual al número de actividades
        if ($idx === 0) {
            $html .= '<td class="act-field" rowspan="' . $numActividades . '">' . htmlspecialchars($datBitacora[0]['observacion'] ?? '') . '</td>';
        }
        $html .= '</tr>';
    }
}


$html .= '</table>';

// INFORMACIÓN ARL
$html .= '<div class="arl-info">
    <strong>Decreto 055 de 2015, por el cual se reglamenta la afiliación de estudiantes al Sistema General de Riesgos Laborales y se dictan otras disposiciones</strong><br>
    <strong>Artículo 11. Obligaciones de la institución de educación.</strong> Corresponde a las instituciones de educación a las que pertenezcan los estudiantes, que deban ser afiliados al Sistema General de Riesgos Laborales de conformidad con el presente decreto:<br>
    1. Revisar periódicamente que el estudiante en práctica desarrolle labores relacionadas exclusivamente con su programa de formación o educación, que ameritaron su afiliación al Sistema General de Riesgos Laborales.<br>
    2. Verificar que el espacio de práctica cuente con los elementos de protección personal apropiados según el riesgo ocupacional.<br>
    <strong>Este espacio debe ser siempre diligenciado.</strong>
</div>';

 // PREGUNTAS ARL
if ($datBitacora && isset($datBitacora[0]['nvlarl'])) {
    $nvlarl = $datBitacora['nvlarl'] ?? '';
} else {
    $nvlarl = '';
}
$html .= '<table>
    <tr>
        <td class="label" style="width: 40%;">¿La persona con rol de aprendiz se encuentra afiliado a la ARL?</td>
        <td class="field-data center" style="width: 10%;">SI <span class="checkbox"></span></td>
        <td class="label" style="width: 25%;">Indique el nivel de riesgo actual</td>
        <td class="field-data center" style="width: 10%;">' . htmlspecialchars($nvlarl ? 'Nivel ' . $nvlarl : '') . '</td>
        <td class="label" style="width: 15%;">¿El nivel de riesgo de la ARL corresponde a las actividades que desarrolla la persona con rol de aprendiz en la empresa?</td>
        <td class="field-data center">SI <span class="checkbox"></span></td>
    </tr>
    <tr>
        <td class="label">¿La persona con rol de aprendiz cuenta con los elementos de protección personal (EPP), requeridos para desarrollar su etapa productiva?</td>
        <td class="field-data center" colspan="5">SI <span class="checkbox"></span></td>
    </tr>
</table>';

$html .= '<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td class="firma-cell" style="padding: 10px; text-align: center;">
            <div style="margin-bottom: 25px;">' .
                (!empty($datOne) ? firdigBit($datOne[0]['nomusu'], $datOne[0]['idusu'] ?? 0, date("Y-m-d H:i:s"), 4) : '') . 
            '</div>
            <div class="firma-line" style="font-style: italic; font-size: 12px; border-top: 1px solid #000; padding-top: 5px;">
                Firma de la persona con rol de aprendiz
            </div>
            <!-- Nombre eliminado: ya está en la firma -->
        </td>

        <td class="firma-cell" style="padding: 10px; text-align: center;">
            <div style="margin-bottom: 25px;">' .
                (!empty($datBitacora) ? firdigBit($datBitacora[0]['nombre_instructor'] ?? '', $datBitacora[0]['id_instructor'] ?? 0, date("Y-m-d H:i:s"), 4) : '') . 
            '</div>
            <div class="firma-line" style="font-style: italic; font-size: 12px; border-top: 1px solid #000; padding-top: 5px;">
                Firma de la persona con rol de instructor de seguimiento
            </div>
        </td>

        <td class="firma-cell" style="padding: 10px; text-align: center;">
            <div style="margin-bottom: 25px;">' .
                (!empty($datBitacora) ? firdigBit($datBitacora[0]['nombre_jefe'] ?? '', $datBitacora[0]['id_jefe'] ?? 0, date("Y-m-d H:i:s"), 4) : '') . 
            '</div>
            <div class="firma-line" style="font-style: italic; font-size: 12px; border-top: 1px solid #000; padding-top: 5px;">
                Firma de la persona con rol de jefe inmediato (Si es del caso)
            </div>
        </td>
    </tr>
    <tr>
        <td class="label center" style="font-weight: bold; padding: 8px; background: #eee; text-align: center;">
            Fecha entrega bitácora
        </td>
        <td class="field-data center" colspan="2" style="padding: 8px; text-align: center;">
            ' . (!empty($datBitacora) ? htmlspecialchars($datBitacora[0]['fecha_entrega'] ?? '') : '') . '
        </td>
    </tr>
</table>';


// NOTAS FINALES
$html .= '<div class="nota-final">
    <strong>Persona con rol de aprendiz:</strong> recuerde diligenciar completamente el formato bitácora y entregarlo o subirlo al espacio asignado para este fin.<br><br>
    <strong>Nota:</strong> Los datos proporcionados serán tratados de acuerdo con la Política de Tratamiento de Datos Personales del SENA y a la Ley 1581 de 2012<br><br>
    <strong>Anexo:</strong> Es opcional relacionar evidencia fotográfica de las actividades desarrolladas<br>
    (No aplica documentos de la empresa u otros aspectos que se consideren sensibles)
</div>';

$html .= '</div>';
$html .= '</body>';

// Generar PDF
if ($pdf == "ok") {
    $dompdf = new Dompdf();
    $dompdf->set_option('enable_php', true);
    $dompdf->set_option('enable_remote', true);
    $dompdf->set_option('enable_html5_parser', true);
    
    $paper_size = array(0, 0, 612, 792); // Tamaño carta
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper_size, 'portrait');
    $dompdf->render();
    
    $nombreAprendiz = !empty($datOne) ? $datOne[0]['nomusu'] : 'Usuario';
    
    // Traer numero_bitacora desde el array
    $numeroBitacora = !empty($datBitacora) ? $datBitacora[0]['numero_bitacora'] : '0';
    
    // Nombre del archivo: numero_bitacora + nombre
    $nombreArchivo = $numeroBitacora . "_" . str_replace(' ', '', $nombreAprendiz) . ".pdf";
    
    $dompdf->stream($nombreArchivo, array("Attachment" => false));
} else {
    echo $html;
}


?>