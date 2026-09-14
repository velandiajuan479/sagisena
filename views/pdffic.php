<?php
include "../models/conexion.php";
include "../controllers/optimg.php";
include "../models/mfic.php";

session_start();
date_default_timezone_set('America/Bogota');

$mes = ["", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
$fecha = "CHÍA, " . $mes[intval(date('m'))] . " " . date('d') . " DE " . date('Y');
$hora = date("H:i a");

$idact = isset($_GET['idactdc']) ? $_GET['idactdc'] : NULL;
$idfic = isset($_REQUEST['idfic']) ? $_REQUEST['idfic'] : (isset($_SESSION["idfic"]) ? $_SESSION["idfic"] : NULL);

$mfic = new Mfic();
$mfic->setIdact($idact);
$dt = $mfic->getOne();

$dtAcDe = $mfic->getOneAcDe();

if (!$dt || !is_array($dt)) {
    die("Error: No se encontró el acta con ID $idact.");
}

$ficha = $mfic->selAll($idfic);
if (!$ficha || !is_array($ficha) || count($ficha) === 0) {
    die("Error: No se encontró la ficha con ID $idfic.");
}
$datosFicha = $ficha[0];

$total = $mfic->getTotalResultados($idfic);
$datos = $mfic->getCompetenciasYResultados($idfic) ;
$aprendices = $mfic->selApr2($idfic);
$apreina = $mfic->selAprInactivos($idfic);
$desertores = $mfic->getAprendicesDesercion($idfic);




$mfic->setTit("Acta de cierre");
$mfic->setNac($dt['numact']); 
$mfic->setNcre("ACTA DE SEGUIMIENTO NOVEDADES DE APRENDICES, ESTRATEGIAS DE RETENCIÓN Y CIERRE PARCIAL DE LA ETAPA LECTIVA DE LA FICHA " . strtoupper($datosFicha['idfic']) . " PROGRAMA " . strtoupper($datosFicha['nomfic']) . ", JORNADA " . strtoupper($datosFicha['nomval']) . ", MUNICIPIO DE " . strtoupper($datosFicha['mun']));
$mfic->setCife($fecha);
$mfic->setHini($hora);
$mfic->setHfin((date("H") + 1) . ":" . date("i a"));
$mfic->setJgen("CHÍA, CUNDINAMARCA");
$mfic->setDrcd("CUNDINAMARCA, CENTRO DE DESARROLLO AGROEMPRESARIAL");
$mfic->setAgep("ACTA DE SEGUIMIENTO NOVEDADES DE APRENDICES, ESTRATEGIAS DE RETENCIÓN Y CIERRE PARCIAL DE LA ETAPA LECTIVA DE LA FICHA " . strtoupper($datosFicha['idfic']) . " PROGRAMA " . strtoupper($datosFicha['nomfic']) . ", JORNADA " . strtoupper($datosFicha['nomval']) . ", MUNICIPIO CHÍA, INCLUIR ESTADO JUICIOS TÉCNICOS E INTEGRALIDAD.");
$mfic->setObjr("Identificar novedades en la ficha No. " . strtoupper($datosFicha['idfic']) . " programa " . strtolower($datosFicha['nomfic']) . ", jornada " . strtolower($datosFicha['nomval']) . " municipio de Chía, validar trámites pendientes (si aplica), socializar estrategias de retención y etapa lectiva.");


    $desa = '';
	$desa .= '<br>Conforme a solicitud de la coordinación académica, Gina Sabogal coordinadora académica, al apoyo de coordinación académica y los instructores líderes de ficha ' . strtoupper($datosFicha['idfic']).
             ' se reúnen a fin de validar el estado actual de la ficha '. strtoupper($datosFicha['idfic']).  ' programa '. strtoupper($datosFicha['nomfic']) . ' de Información en lo referente a analizar novedades de aprendices, trámites pendientes, estrategias de retención, el avance, desarrollo del proceso de formación y cierre parcial de la etapa lectiva de la ficha anteriormente referencia así: <br><br>';

    $desa .= '<br>1. Estado avance planeación pedagógica resultados de aprendizaje en ejecución APROBADOS. (Teniendo en cuenta las agendas) En total son '.$total["tot"].' resultados  <br><br>';

$aprobados = '';
$noAprobados = '';
$pendientes = '';

$tablaInicio = '<table border="1" cellpadding="4" cellspacing="0">';
$tablaInicio .= '<tr><th>Competencia</th><th>Resultado de Aprendizaje</th><th>Estado</th><th>Instructor</th></tr>';
$tablaFin = '</table>';

$contAprobados = 0;
$contNoAprobados = 0;
$contPendientes = 0;

foreach ($datos as $row) {
    $competencia = $row['competencia'] ?? '';
    $resultado = $row['resultado'] ?? '';
    $estado = strtolower(trim($row['calificacion'] ?? 'Por Evaluar'));
    $instructor = $row['instructor'] ?? '-';

    if ($estado === 'aprobado') {
        $contAprobados++;
        $aprobados .= "<tr>
            <td>" . htmlspecialchars($competencia) . "</td>
            <td>" . htmlspecialchars($resultado) . "</td>
            <td>Aprobado</td>
            <td>" . htmlspecialchars($instructor) . "</td>
        </tr>";
    } elseif ($estado === 'no aprobado') {
        $contNoAprobados++;
        $noAprobados .= "<tr>
            <td>" . htmlspecialchars($competencia) . "</td>
            <td>" . htmlspecialchars($resultado) . "</td>
            <td>No Aprobado</td>
            <td>" . htmlspecialchars($instructor) . "</td>
        </tr>";
    } else {
        $contPendientes++;
        $pendientes .= "<tr>
            <td>" . htmlspecialchars($competencia) . "</td>
            <td>" . htmlspecialchars($resultado) . "</td>
            <td>Por Evaluar</td>
            <td>" . htmlspecialchars($instructor) . "</td>
        </tr>";
    }
}

if ($aprobados) $desa .= "<h3>Resultados Aprobados ($contAprobados) para ficha {$datosFicha['idfic']}:</h3>" . $tablaInicio . $aprobados . $tablaFin;
if ($noAprobados) $desa .= "<h3>Resultados No Aprobados ($contNoAprobados) para ficha {$datosFicha['idfic']}:</h3>" . $tablaInicio . $noAprobados . $tablaFin;
if ($pendientes) $desa .= "<h3>Resultados Pendientes ($contPendientes) para ficha {$datosFicha['idfic']}:</h3>" . $tablaInicio . $pendientes . $tablaFin;

if (!$aprobados && !$noAprobados && !$pendientes) $desa .= "<h3>No hay resultados registrados para esta ficha.</h3>";



           
         $desa .= '<table border="2" cellpadding="25" cellspacing="0" style="margin: 0 auto;">';

        

                $desa .= '<br>3. Total, aprendices activos en formación. Final: '. strtoupper($datosFicha['idfic']). ' total de aprendicez : ' . count($aprendices) .  '<br><br>';
                
        

                    $desa .= '<tr><th>No.</th><th>Identificación</th><th>Nombre</th><th>Estado</th></tr>';
                    $contador = 1;
                    foreach ($aprendices as $row) {
                    $estiloFila = ($row['estado'] === 'RETIRO VOLUNTARIO') ? 'style="background-color:rgb(145, 138, 138);"' : '';

                        $desa .= '<tr ' . $estiloFila . '>';
                        $desa .= '<td>' . $contador++ . '</td>';
                        $desa .= '<td>' . $row['identificacion'] . '</td>';
                        $desa .= '<td>' . $row['nombre'] . '</td>';
                        $desa .= '<td>' . $row['estado'] . '</td>';
                        $desa .= '</tr>';
                    }

                $desa .= '</table>';

                $desa .= '<br>4. 	Aprendices con novedad retiro voluntario (registrado, en proceso, inconcluso) Informe sofiaplus List_Aprend (Estado) <br><br>';
               $desa .= '<table border="2" cellpadding="10" cellspacing="0" style="margin: 0 auto;">';
$desa .= '<tr><th>No.</th><th>Identificación</th><th>Nombre</th><th>Estado</th></tr>';

$contador = 1;

// === RETIRO VOLUNTARIO ===
$inactivos = array_filter($aprendices, function($row) {
    return strtoupper($row['estado']) === 'RETIRO VOLUNTARIO';
});

foreach ($inactivos as $row) {
    $desa .= '<tr">'; 
    $desa .= '<td>' . $contador++ . '</td>';
    $desa .= '<td>' . htmlspecialchars($row['identificacion']) . '</td>';
    $desa .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
    $desa .= '<td>RETIRO VOLUNTARIO</td>';
    $desa .= '</tr>';
}


if (!empty($desertores)) {
    foreach ($desertores as $row) {
        $desa .= '<tr style="background-color:rgb(248, 0, 0);">';
        $desa .= '<td>' . $contador++ . '</td>';
        $desa .= '<td>' . htmlspecialchars($row['identificacion']) . '</td>';
        $desa .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        $desa .= '<td>DESERCIÓN</td>';
        $desa .= '</tr>';
    }
}                       
$desa .= '</table>';
$desa .= '<table>';
if ($desertores && count($desertores) > 0) {
    
    $desa .= '<br>5.Aprendices con novedad solicitud comité de evaluación y seguimiento. (en conocimiento de la coordinación, en proceso de documentación, a la espera de fecha y hora comité)

Se solicita la deserción de los aprendices que se muestran en la siquiente tabla, ya se encuentran radicadas las inasistencias en SenaSofiaPlus y se solicita el debido proceso.<br><br>';

    // SEGUNDA SECCIÓN: imágenes por aprendiz
    $desa .= '<br><br><strong>Imágenes asociadas a aprendices con deserción:</strong><br>';

    foreach ($desertores as $row) {
        $imagenes = $mfic->obtenerImagenesUsuario($row['idusu']);
        if ($imagenes && count($imagenes) > 0) {
            $desa .= '<div style="margin: 20px 0; padding: 10px; border: 1px solid #ccc;">';
            $desa .= '<strong>'.'Aprendiz' . htmlspecialchars($row['nombre']) . ' (' . htmlspecialchars($row['identificacion']) . '  ha faltado a formación, a continuación, los registros en sofia '.')</strong><br><br>';
            $desa .= '<div style="display: flex; flex-wrap: wrap; justify-content: center;">';

            foreach ($imagenes as $img) {
                $ruta = '../img/inasi/' . $img['nomimg'];
                $desa .= "<div style='margin: 10px; text-align: center;'><img src='{$ruta}' style='max-width:450px; border:1px solid #000; padding:4px;'><br></div>";
            }

            $desa .= '</div>';
            $desa .= '</div>';
        }
    }

} else {
    $desa .= '<br><span style="color:red;">No hay aprendices registrados con deserción en el sistema para esta ficha.</span><br>';
}

$desa .= '<br>6.	Aprendices con novedad solicitud comité de evaluación y seguimiento. (en conocimiento de la coordinación, en proceso de documentación, a la espera de fecha y hora comité)

Se solicita la deserción de los aprendices que se muestran en la siguiente tabla, ya se encuentran radicadas las inasistencias en SenaSofiaPlus y se solicita el debido proceso.
<br><br>';

 $desa .= '<table border="2" cellpadding="25" cellspacing="0" style="margin: 0 auto;">';
    $desa .= '<tr><th>No.</th><th>Identificación</th><th>Nombre</th><th>Estado</th></tr>';

    $contador = 1;
    foreach ($desertores as $row) {
        $desa .= '<tr style="background-color:rgb(248, 0, 0);">';
        $desa .= '<td>' . $contador++ . '</td>';
        $desa .= '<td>' . htmlspecialchars($row['identificacion']) . '</td>';
        $desa .= '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        $desa .= '<td>' . htmlspecialchars($row['obsdec']) . '</td>';
        $desa .= '</tr>';
    }

    $desa .= '</table>';

    $desa .= '<br>7.	Estrategias de retención empleadas por el equipo ejecutor (describir quien lo conforma para esta fecha ) en la ficha  '. strtoupper($datosFicha['idfic']). '<br><br>';
$desa .= '<div style="font-size: 14px; margin-left: 50px;">';

$estrategias = [
    'WhatsApp',
    'Trabajo Colaborativo',
    'Meet',
    'Chat',
    'Sesión Asincrónicas (Grabación Sesiones)',
    'Horarios flexibles en la entrega de evidencias',
    'Videollamada - Sesiones Sincrónicas programadas por Calendar (Google) Acceder a la grabación de la Sesión',
    'Estudios de Caso (Simulación)',
    'Plataforma LMS Abierta 24/7',
    'Google Drive',
    'Meet',
    'Correo',
    'Hacerle utilizar el ensayo y error.',
    'Ejercicios de simulación - Modelación del conocimiento en las sesiones sincrónicas',
    'Guías e instrumentos de evaluación que facilite la autoevaluación',
    'Resolución de problemas prácticos.',
    'Proyectos prácticos.',
    'Demostraciones prácticas.',
    'Participar en debates.',
    'Asistir a conferencias'
];

foreach ($estrategias as $item) {
    $desa .= '• ' . htmlspecialchars($item) . '<br>';
}

$desa .= '</div>';

$desa .= '<br>8.	Varios.

Los ' . count($aprendices) .  ' aprendices ya tienen las competencias aprobadas parcialmente y otras pendientes, como lo indica el numeral 1 y 2.
<br><br>';
 
$mfic->setDesr($desa);
$mfic->setConc("Los  " . count($aprendices) . " aprendices matriculados hasta la fecha cumplieron con los $contAprobados  resultados de aprendizaje de acuerdo con la agenda hay algunos resultados no evaluados, puede ser verificado por SenaSofíaPlus y se lleve bien el proceso de la etapa lectiva, teniendo en cuenta el numeral $contPendientes  de pendientes. Se reportan un numero de " . count($desertores) . "  deserción de la matricula por falta de asistencia a las sesiones de formación.
Todos los aprendices leyeron esta acta, están de acuerdo y autorizan esta acta.
");

$mfic->setEaco($dtAcDe);

$tablaCompromisos = '';
$tablaCompromisos .= '<br><br>';
$tablaCompromisos .= '<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-top: 30px;">';
$tablaCompromisos .= '    <tr><th colspan="5" style="text-align:center;">ESTABLECIMIENTO Y ACEPTACIÓN DE COMPROMISOS</th></tr>';
$tablaCompromisos .= '    <tr>';
$tablaCompromisos .= '        <th>ACTIVIDAD / DECISIÓN</th>';
$tablaCompromisos .= '        <th colspan="2">FECHA</th>';
$tablaCompromisos .= '        <th>RESPONSABLE</th>';
$tablaCompromisos .= '        <th>FIRMA</th>';
$tablaCompromisos .= '    </tr>';

if ($mfic->getEaco()) {
    foreach ($mfic->getEaco() as $dEa) {
        $tablaCompromisos .= '<tr>';
        $tablaCompromisos .= '    <td>' . $dEa['desacde'] . '</td>';
        $tablaCompromisos .= '    <td colspan="2">' . $dEa['fecacde'] . '</td>';
        $tablaCompromisos .= '    <td>' . firdig($dEa['nomusu'], $dEa['idacde'], date("Y-m-d H:i:s"), 2) . '</td>';
        $tablaCompromisos .= '    <td>' . firdig($dEa['nomusu'], $dEa['idacde'], date("Y-m-d H:i:s"), 3) . '</td>';
        $tablaCompromisos .= '</tr>';
    }
}
$tablaCompromisos .= '</table>';

$mfic->setEaco($dtAcDe);
$tablaAsistentes = '';
$tablaAsistentes .= '<br><br>';
$tablaAsistentes .= '<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; margin-left: 0px; margin-top: 30px;">';
$tablaAsistentes .= '<tr><th colspan="4" style="text-align:center; font-size:16px;">ASISTENTES Y APROBACIÓN DECISIONES</th></tr>';
$tablaAsistentes .= '<tr>';
$tablaAsistentes .= '    <th style="width: 30%;">NOMBRE</th>';
$tablaAsistentes .= '    <th style="width: 30%;">DEPENDENCIA</th>';
$tablaAsistentes .= '    <th style="width: 20%;">FIRMA</th>';
$tablaAsistentes .= '</tr>';


$idsDesertores = array_column($desertores, 'idusu');


foreach ($aprendices as $apr) {

    
    if (in_array($apr['idusu'], $idsDesertores)) {
        continue;
    }
    if (strtoupper($apr['estado']) === 'RETIRO VOLUNTARIO') {
        continue;
    }

    $nombre = htmlspecialchars($apr['nombre']);
    $ficha = htmlspecialchars($datosFicha['idfic']);
    $programa = htmlspecialchars($datosFicha['nomfic']);
    $dependencia = "Aprendiz - $ficha - $programa";

    if ($mfic->getEaco()) {
    foreach ($mfic->getEaco() as $dEa) {
        $tablaAsistentes .= '<tr>';
        $tablaAsistentes .= '    <td style="width: 40%;">' . $nombre . '</td>';
        $tablaAsistentes .= '    <td style="width: 40%;">' . $dependencia . '</td>';
        $tablaAsistentes .= '    <td>' . firdig($dEa['nomusu'], $dEa['idacde'], date("Y-m-d H:i:s"), 3) . '</td>';
        $tablaAsistentes.= '</tr>';
    }
}

    
}



$tablaAsistentes .= '</table>';

$contenidoConc = $mfic->getConc();
$mfic->setConc($contenidoConc .$tablaCompromisos . $tablaAsistentes);




echo $mfic->getActaFic();
?>

