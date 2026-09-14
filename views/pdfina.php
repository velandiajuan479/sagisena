<?php
require_once '../models/conexion.php';
require_once '../models/mina.php';
ini_set('memory_limit', '4096M');
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;

$pdf = isset($_GET['pdf']) ? $_GET['pdf']:NULL;
$idusu =isset($_GET['idusu']) ? $_GET['idusu']:NULL;
$idfic = isset($_REQUEST['idfic']) ? $_REQUEST['idfic']:NULL;

date_default_timezone_set('America/Bogota');
$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$fecha = date('d')." de ".$mes[date('m')-1]." de ".date('Y');
$fecha2 = date('YmdHis');

$mina = new Mina();
$mina->setIdusu($idusu);
$datAllgi = $mina->selAll($idfic);
$datAllig = $mina->getAll($idfic);

$dfi = $mina->getFicha();


$ancho = 750;

function urlimg($url)
{
    $imagenBase64 = "data:image/png;base64," . base64_encode(file_get_contents($url));
    return $imagenBase64;
}


$html = '';

// Cabecera del PDF
$html.='<body>';
	$html.='<table width="'.$ancho.'px">';
		$html.='<tr>';
			$html.='<td style="text-align: center;">';
				$html.='<img src="'.urlimg('../image/sena.png').'" width="80px">';
				$html.='<br><br>';
			$html.='</td>';
		$html.='</tr>';
	$html.='</table>';


	$html.='<table id="inn" border=1 style="border-collapse: collapse;width:'.$ancho.'px;" width="'.$ancho.'px" >';
		$html.='<tr>';
			$html.='<th colspan="3">';
					$html.='<strong>PROCESO DE GESTIÓN DE FORMACIÓN PROFESIONAL INTEGRAL
					FORMATO ACTA DE CIERRE POR DESERCIÓN EN LA ALTERNATIVA DE PROYECTO PRODUCTIVO
					</strong>';
			$html.='</th>';
		$html.='</tr>';


		$html.='<tr>';
			$html.='<td>';
				$html.='<strong>No. Acta:</strong>';
			$html.='</td>';
			$html.='<td>';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
			$html.='<td>';
				$html.='<strong>Hora inicio:</strong>';
			$html.='</td>';
			$html.='<td>';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<td>';
				$html.='<strong>Ciudad y Fecha:</strong>';
			$html.='</td>';
			$html.='<td>';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
			$html.='<td>';
				$html.='<strong>Hora Fin:</strong>';
			$html.='</td>';
			$html.='<td>';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<td>';
				$html.='<strong>Lugar y fecha:</strong>';
			$html.='</td>';
			$html.='<td colspan="3">';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td>';
				$html.='<strong>Dirección general/Regional/<br>Centro de formación:</strong>';
			$html.='</td>';
			$html.='<td colspan="3">';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr rowspan="4">';
			$html.='<td>';
				$html.='<strong>Convocatoria:</strong>';
				$html.='<div style="margin:10px;">';
					$html .='<div><strong>Nacional</strong></div> <div><input type="radio" name="cln"></div>';
					$html .='<div><strong>Liderada por centro</strong></div>  <div><input type="radio" name="cln"></div>';
				$html.='</div>';
			$html.='</td>';
			$html.='<td colspan="3">';
				$html.='<div style="margin:10px;">';
					$html .='<div><strong>Empresarial</strong></div> <div><input type="radio" name="cln"></div>';
					$html .='<div><strong>l+D+i</strong></div>  <div><input type="radio" name="cln"></div>';
				$html.='</div>';
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<td colspan="3">';
				$html.='<strong>Causal de deserción:</strong>';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr rowspan="4">';
			$html.='<td>';
				$html.='<strong>Cambio de Alternativa:</strong>';
				$html.='<div style="margin:10px;">';
					$html .='<div><input type="radio" name="cln"></div>';
					$html .='<div><strong>(Se requiere el acta del debido proceso avalando el cambio por el coordinador académico).</strong></div>';
				$html.='</div>';
			$html.='</td>';
			$html.='<td colspan="2">';
			$html.='<strong>Retiro voluntario:</strong>';
				$html.='<div style="margin:10px;">';
					$html .='<div><input type="radio" name="cln"></div>';
					$html .='<div><strong>(Se requiere aval por parte del facilitador, el aprendiz debe estar al día en sus actividades).</strong></div>';
				$html.='</div>';
			$html.='</td>';
			$html.='<td colspan="">';
			$html.='<strong>Incumplimiento:</strong>';
				$html.='<div style="margin:10px;">';
					$html .='<div><input type="radio" name="cln"></div>';
				$html.='</div>';
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<td colspan="3" ';
				$html.='<strong>Descripción más detallada de la causa de deserción:</strong><br><br>';
				if($datAllgi) $html .=  $datAllgi[0]['nomusu'];
			$html.='</td>';
		$html.='</tr>';
		
		$html.='<tr>';
			$html.='<td colspan="3">';
			$html.='<strong>Agenda o puntos para desarrollar:</strong>';
				$html.='<ol>';
					$html.='<li>Presentación de asistentes a la reunión</li>';
					$html.='<li>Presentación de asistentes a la reunión</li>';
					$html.='<li>Presentación de asistentes a la reunión</li>';
					$html.='<li>Presentación de asistentes a la reunión</li>';
				$html.='</ol>';
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<td colspan="3" >';
			$html.='<strong>Objetivo(s) de la reunión:</strong>';
				$html.='<ol>';
					$html.='<li>Dar al Aprendiz la posibilidad de explicar su situación particular.</li>';
					$html.='<li>Asegurar un entendimiento preciso y objetivo de las circunstancias que conllevan al cierre de la asesoría según la causal de deserción.</li>';
					$html.='<li>Realizar el cierre de asesoría para la formulación del Proyecto Productivo.</li>';
					$html.='<li>Establecer las acciones procedentes al cierre: Notificación al Centro de Formación, formato GFPI-F-023 Formato Planeación seguimiento y evaluación etapa productiva, registro en base de datos de aprendices con deserción , registro en archivo seguimiento aprendices, des-enrolamientos LMS, entre otras.</li>';
				$html.='</ol>';
			$html.='<p>Se hace presente:</p>';
				$html.='<ol>';
					$html.='<li>El (la) Instructor(a) de Seguimiento: ________________________________. </li>';
					$html.='<li>El(la) Facilitador(a) de Etapa Productiva del Grupo de Ejecución de la Formación Profesional - Formación Virtual y/o  Instructor(a) de Proyecto Productivo designado por el Centro de Formación :_____________________________.</li>';
					$html.='<li>El(la)(los) aprendiz(ces): ________________________________________, para presentar las circunstancias que lo llevan a solicitar el cierre de la asesoría de  Proyecto Productivo, como alternativa de Etapa Productiva.</li>';
				$html.='</ol>';
			$html.='<p>Se hace presente:</p>';
				$html.='<ol>';
					$html.='<li>El(la)(los) aprendiz(ces): _____________________________, para presentar las circunstancias que lo llevan a solicitar el cierre de la asesoría de Proyecto Productivo, como alternativa de Etapa Productiva.</li>';
				$html.='</ol>';
				$html.='<p>El(la) Facilitador(a) de Etapa Productiva del Grupo de Ejecución de la Formación Profesional - Formación Virtual y/o Instructor(a) de Proyecto Productivo , da paso al (a la) Aprendiz para que notifique el nivel de su avance en el desarrollo de su alternativa y las circunstancias que lo llevan a solicitar el cierre de su alternativa …………………………….</p><br>';
				$html.='<strong>Nota:</strong><p>En caso de que no se presente el aprendiz o los aprendices el acta procede y tiene validez.</p>';
			$html.='</td>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<th colspan="3">CONCLUSIONES</th>';
		$html.='</tr>';

		$html.='<tr>';
		$html.='<td colspan="3" >';
			$html.='<ol type="a">';
				$html.='<li><strong>Aplica para Convocatoria Grupo de Ejecución de la Formación Profesional - Formación Virtual:  SÍ___   NO___</strong></li><br>';
				$html.='<p>Se evaluó conjuntamente entre el Ente Co-formador (Facilitador(a) de Etapa Productiva del Grupo de Ejecución de la Formación Profesional - Formación Virtual) y el(la) Instructor(a) de Seguimiento, la asesoría del Proyecto Productivo con resultado:</p>';
				$html.='<div style="">';
					$html .='<div>Aprobado</div><input type="radio" name="cln">';
					$html .='<div>No aprobado</div><input type="radio" name="cln">';
				$html.='</div>';
				$html.='<p>Validando y confirmando que la causal de “deserción” del aprendiz en su alternativa, queda denominada como:</p>';
				$html.='<div style="">';
					$html .='<div>Cambio de alternativa</div><input type="radio" name="cln">';
					$html .='<div>Retiro Voluntario</div><input type="radio" name="cln">';
					$html .='<div>Incumplimiento</div><input type="radio" name="cln"><br><br>';
				$html.='</div>';
				$html.='<li><strong>Aplica para Convocatoria liderada por Centro de Formación:  SÍ___   NO___</strong></li><br>';
				$html.='<p>Se evaluó conjuntamente entre el Ente Co-formador (Instructor(a) de Proyecto Productivo) y el(la) Instructor(a) de Seguimiento, la asesoría del Proyecto Productivo con resultado:</p>';
				$html.='<div style="">';
					$html .='<div>Aprobado</div><input type="radio" name="cln">';
					$html .='<div>No aprobado</div><input type="radio" name="cln">';
				$html.='</div>';
				$html.='<p>Validando y confirmando que la causal de “deserción” del aprendiz en su alternativa, queda denominada como:</p>';
				$html.='<div style="">';
					$html .='<div>Cambio de alternativa</div><input type="radio" name="cln">';
					$html .='<div>Retiro Voluntario</div><input type="radio" name="cln">';
					$html .='<div>Incumplimiento</div><input type="radio" name="cln">';
				$html.='</div>';	
				$html.='<p>Mediante la firma de la presente acta, se procede a realizar cierre de la asesoría de Proyecto Productivo como alternativa de Etapa Productiva.</p><br>';
				$html.='<strong>Nota:</strong><p>Se notifica el acta de deserción a la Coordinación Académica para que lleve a cabo el debido proceso.</p>';
			$html.='</ol>';
		$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="3">';
				$html.='<p>De acuerdo con La Ley 1581 de 2012, Protección de Datos Personales se debe garantizar la seguridad y protección de los datos personales que se encuentran almacenados en este documento. El Servicio Nacional de Aprendizaje SENA solicita la siguiente clasificación de la información:</p><br>';
				$html.='<p><strong>La información de este documento se debe clasificar como:</strong></p>';
				$html.='<div style="">';
					$html .='<div>Público</div><input type="radio" name="cln">';
					$html .='<div>Privado</div><input type="radio" name="cln">';
					$html .='<div>Semiprivado</div><input type="radio" name="cln">';
					$html .='<div>Sensible</div><input type="radio" name="cln"><br><br>';
				$html.='</div>';	
			$html.='</td>';
		$html.='</tr>';
		
		$html.='<tr>';
			$html.='<th colspan="3">';
				$html.='COMPROMISOS';	
			$html.='</th>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<th>';
				$html.='ACTIVIDAD';
			$html.='</th>';
			$html.='<th colspan="2">';
				$html.='RESPONSABLE';
			$html.='</th>';
			$html.='<th>';
				$html.='FECHA';	
			$html.='</th>';
		$html.='</tr>';

		$html.='<tr>';
			$html.='<td>';
				$html.='Envio  del correo con la presente Acta GFPI-F-XXX Formato Acta de Cierre por 
				Deserción en la Alternativa de Proyecto Productivo  a la persona asignada por el Grupo 
				Ejecución de la Formación Profesional o al Apoyo a la Coordinación Académica del 
				Centro de Formación, según corresponda; junto con la documentación que soporta la deserción. <br><br>';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Facilitador de Etapa Productiva o Instructor de Proyecto Productivo.';
			$html.='</td>';
			$html.='<td>';
				$html.='DD/MM/AÑO';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td>';
				$html.='Envío del correo con la presente Acta GFPI-F-XXX Formato Acta de Cierre por 
				Deserción en la Alternativa de Proyecto Productivo a la Coordinación Académica 
				del Centro de Formación.<br><br><br>';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Persona asignada por el Grupo Ejecución de la Formación Profesional y/o Apoyo a la Coordinación Académica del Centro de Formación.';
			$html.='</td>';
			$html.='<td>';
				$html.='DD/MM/AÑO';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td>';
				$html.='Envío del correo con la presente Acta GFPI-F-XXX Formato Acta de Cierre por 
				Deserción en la Alternativa de Proyecto Productivo con copia al aprendiz para su 
				debido conocimiento. <br><br><br>';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Facilitador de Etapa Productiva o Instructor de Proyecto Productivo.';
			$html.='</td>';
			$html.='<td>';
				$html.='DD/MM/AÑO';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="3">';
				$html.='<strong>ASISTENTES: (Incorporar registro de asistencia</strong>';
				$html.='<strong>Nota:</strong> Puede incluirse imagen o captura de pantalla 
				de los asistentes, si se trata de una reunión virtual o, de los asistentes 
				que participan a través de una plataforma virtual.<br><br>';
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="3">';
				$html.='<strong>EVIDENCIAS:</strong>';
				$html.='<strong>Nota:</strong> Ideal incluir imagen (imágenes), captura(s) 
				de pantalla o links (enlaces) de todos los elementos multimedia que permitan 
				evidenciar las instancias circunstanciales y/o los momentos de incumplimiento
				que justifican o ameritan el cierre de la Alternativa del Aprendiz.<br><br>';
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<th colspan="3" style="text-align:center;">';
				$html.='<strong>Instrucciones</strong>';
			$html.='</th>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="3">';
				$html.='<ol>';
					$html.='<li>Quién(es) lo diligencian:  El formato de acta de cierre por 
					deserción en la alternativa de proyecto productivo debe ser diligenciado por 
					el instructor de seguimiento, y el Facilitador de Etapa Productiva y/o Instructor 
					de Proyecto Productivo.</li>';
					$html.='<li>Cuando el se diligencia: El acta debe ser diligenciada si se 
					requiere cerrar la asesoría de Proyecto Productivo por deserción del aprendiz. </li>';
					$html.='<li>Frecuencia de diligenciamiento: N/A pues se diligencia cada vez 
					que se requiere realizar un cierre por deserción del aprendiz. </li>';
					$html.='<li>Qué trámite surte el formato una vez diligenciado : Generar el cierre de 
					asesoría de Proyecto Productivo como alternativa de Etapa Productiva por deserción del aprendiz.</li>';
					$html.='<li>Si se requiere imprimir (en lo posible no): No. </li>';
					$html.='<li>Quién lo guarda: Dirección de Formación Profesional - Grupo de Ejecución de la 
					Formación - Equipo Etapa Productiva Virtual, el aprendiz al cual se le realiza el cierre por 
					deserción, el Instructor de Seguimiento y Coordinador Académico del Centro de Formación 
					al que pertenece el aprendiz. <br><br></li>';
				$html.='</ol>';
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<th>';
				$html.='Campo de formato';
			$html.='</th>';
			$html.='<th colspan="2">';
				$html.='Instrucción';
			$html.='</th>';
			$html.='<th>';
				$html.='Tener en cuenta';	
			$html.='</th>';
		$html.='</tr>';
	
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='SOLICITAR PERMISO PARA GRABAR LAS REUNIONES';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se debe solicitar permiso para grabar las reuniones Especialmente para las reuniones virtuales donde no se puede incluir la firma de estos participantes. La aprobación se puede registrar en la columna junto a la firma. ';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='ACTA No.';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se inicia un consecutivo por aprendiz, iniciando desde N° 1 hasta N° veces.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='Se archiva en orden cronológico, del más antiguo al más reciente ';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='CIUDAD Y FECHA';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Registre el nombre de la ciudad y el día en que se celebra la reunión.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='HORA INICIO/HORA FIN';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Registre la hora de inicio de la reunión y la hora de finalización ';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='LUGAR Y/O ENLACE';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Registre el lugar donde se lleva a cabo la reunión y el enlace si se trata de una reunión virtual y/o si la reunión fue grabada registre el enlace de la grabación. Puede registrar el lugar y el enlace al tiempo si se trata de una reunión con participación presencial y virtual al tiempo.  ';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='Solicitar permiso para grabar las reuniones';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='DIRECCIÓN GENERAL/REGIONAL/CENTRO';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Registre el nombre del lugar en el que se celebra la reunión.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='ENFOQUE DE PROYECTO PRODUCTIVO';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se debe definir a través de que enfoque (Empresarial o I+D+i) se esta ejecutando la alternativa de Proyecto Productivo.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='CAUSAL DE DESERCIÓN:';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se debe definir si es por: Cambio de Alternativa, Retiro Voluntario o Incumplimiento.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='<strong>Cambio de Alternativa:</strong> Se requiere el acta del debido proceso Avalando el cambio por el Coordinador Académico.
				<strong>Retiro Voluntario:</strong> Se requiere Aval por parte del facilitador, el Aprendiz debe estar al día en sus actividades';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='DESCRIPCIÓN MÁS DETALLADA DE LA CAUSAL DE DESERCIÓN';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se debe describir el causal de la deserción del aprendiz del Proyecto Productivo.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='AGENDA O PUNTOS PARA DESARROLLAR';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Listar las temáticas centrales de la reunión';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="OBJETIVO(S) DE LA REUNION">';
				$html.='';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Iniciando con un verbo en infinitivo indicar el propósito o finalidad de la reunión ';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='DESARROLLO DE LA REUNIÓN';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Realizar descripción del desarrollo de la reunión conforme con la agenda o los puntos a desarrollar.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='CONCLUSIONES';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Resumen de las decisiones y aspectos más importantes tratados.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='COMPROMISOS';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se registran las tareas asignadas a los participantes y las fechas de cumplimiento.';	
			$html.='</td>';
			$html.='<td colspan="1">';
			$html.='Realice revisión compromisos anteriores';	
		$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='FIRMAS';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Se requiere anexar la firma digital del instructor de seguimiento y el facilitador de Etapa Productiva o Instructor de Proyecto Productivo, aprendiz (en caso de asistir), para dar validez al cierre por deserción.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<th colspan="3">';
				$html.='LA INFORMACIÓN DE ESTE DOCUMENTO SE DEBE CLASIFICAR COMO';
			$html.='</th>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<th colspan="3">';
				$html.='PROTECCIÓN DE DATOS PERSONALES';
			$html.='</th>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='DATO PERSONAL';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Cualquier información vinculada o que pueda asociarse a una o varias personas naturales determinadas o determinables';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='ENCARGADO DEL TRATAMIENTO';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Persona natural o jurídica, pública o privada, que por sí misma o en asocio con otros, realice el Tratamiento de datos personales por cuenta del responsable del Tratamiento';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='RESPONSABLE DEL TRATAMIENTO';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Persona natural o jurídica, pública o privada, que por sí misma o en asocio con otros, decida sobre la base de datos y/o el Tratamiento de los datos';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='TITULAR';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Persona natural cuyos datos personales sean objeto de Tratamiento';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='TRATAMIENTO';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Cualquier operación o conjunto de operaciones sobre datos personales, tales como la recolección, almacenamiento, uso, circulación o supresión';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='FINALIDAD';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='La utilización de los datos debe sujetarse a una finalidad legítima de acuerdo con la Constitución y la ley. La finalidad de la utilización de los datos debe ser informada al titular de la información previa o concomitantemente con el otorgamiento de la autorización, cuando ella sea necesaria o en general siempre que el titular solicite información al respecto';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<th colspan="3">';
				$html.='CLASIFICACIÓN DE LA INFORMACIÓN';
			$html.='</th>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='TIPOS DATOS: PÚBLICA';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Es el dato que la ley o la Constitución Política determina como tal, así como todos aquellos que no sean semiprivados o privados.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='Ejemplos: Datos relativos al estado civil de las personas, su profesión u oficio, su calidad de comerciante o servidor público y aquellos que pueden obtenerse sin reserva alguna';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='TIPOS DATOS: SEMIPRIVADA';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Es el dato que no tiene naturaleza íntima, reservada, ni pública y cuyo conocimiento o divulgación puede interesar no sólo a su titular sino a cierto sector o grupo de personas.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='Ejemplos:
				Datos financieros y crediticios, dirección, teléfono, correo electrónico';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='TIPOS DATOS: PRIVADA';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Es el dato que por su naturaleza íntima o reservada sólo es relevante para el titular de la información.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='Ejemplos:
				fotografías, videos, datos relacionados con su estilo de vida
				';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="1">';
				$html.='TIPOS DATOS: SENSIBLE';
			$html.='</td>';
			$html.='<td colspan="2">';
				$html.='Es el dato que afecta la intimidad del titular o cuyo uso indebido puede generar su discriminación.';	
			$html.='</td>';
			$html.='<td colspan="1">';
				$html.='Ejemplos:
				Estado de salud, Origen racial o étnico, Orientación sexual, Afiliación a organizaciones sindicales o políticas, Creencias religiosas o filosóficas, Aspectos biométricos o genéticos';	
			$html.='</td>';
		$html.='</tr>';
		$html.='<tr>';
			$html.='<td colspan="3">';
				$html.='Si la información marcada es (Privada, Semiprivada) y de conformidad con lo dispuesto en la Ley 1581 de 2012 el Servicio Nacional de Aprendizaje – SENA es responsable del tratamiento de datos personales y en tal virtud recolectará, almacenará y usará su información personal para las siguientes finalidades: (1) Gestionar actividades de capacitación, reunión o asistencia.

				Si la información marcada es (Sensible) y de acuerdo con la Ley 1581 de 2012 esto se considerará datos sensibles, puesto que pueden llegar a afectar la intimidad o cuyo uso indebido llegue a generar discriminación. En caso en que la Entidad requiera la recolección de esta información, el titular tiene el derecho a contestar o no las preguntas que se le formulan y a entregar o no los datos solicitados. Adicionalmente como titular de sus datos personales usted tiene derecho a: (i) Acceder en forma gratuita a los datos proporcionados que hayan sido objeto de tratamiento. (ii) Conocer, actualizar y rectificar su información frente a datos parciales, inexactos, incompletos, fraccionados, que induzcan a error, o a aquellos cuyo tratamiento esté prohibido o no haya sido autorizado. (iii) Solicitar prueba de la autorización otorgada. (iv) Presentar ante la Superintendencia de Industria y Comercio (SIC) quejas por infracciones a lo dispuesto en la normatividad vigente. (v) Revocar la autorización y/o solicitar la supresión del dato, siempre que no exista un deber legal o contractual que impida eliminarlos. (vi) Abstenerse de responder las preguntas sobre datos sensibles. El titular podrá ejercer sus derechos siguiendo el procedimiento descrito en nuestra Política de Protección de Datos Personales, la cual puede consultar ingresando en la página web www.sena.edu.co. El diligenciar la información requerida en este formato se entenderá como una conducta inequívoca de que usted como titular de los datos personales, otorga su consentimiento al Servicio Nacional de Aprendizaje – SENA para que trate su información personal de acuerdo con las finalidades mencionadas anteriormente, manifiesta que la presente autorización le fue solicitada,  puesta en conocimiento antes de entregar sus datos y que la suscribe de forma libre y voluntaria una vez leída en su totalidad.
				
				**La autorización del Titular no será necesaria cuando se trate de Información requerida por una entidad pública o administrativa en ejercicio de sus funciones legales o por orden judicial, datos de naturaleza pública, casos de urgencia médica o sanitaria, datos relacionados con el Registro Civil de las Personas.
				**En el Tratamiento se asegurará el respeto a los derechos prevalentes de los niños, niñas y adolescentes.
				
				** Si la información en este documento es solicitada por un tercero y cuenta con clasificación ( Semi-Privada, Privada o Sensible) se debe realizar una finalidad del proceso de anonimización la cual es evitar la identificación de las personas y reducir su probabilidad de reidentificación sin afectar la veracidad de los resultados y la utilidad de los datos que han sido tratados. 
				
				El proceso de anonimización de datos personales requiere una adecuada comprensión del propósito final de la utilización de la información, así como de su nivel de utilidad, teniendo en cuenta que independientemente de las técnicas empleadas, una vez realizado el proceso de anonimización se reduce la información original del conjunto de datos. Por tal motivo es importante determinar el costo de oportunidad entre la utilidad que se busca obtener a partir de los datos y el nivel de riesgo de reidentificación.
				
				Una vez los datos son anonimizados, estos se pueden usar, reutilizar y divulgar sin violar el derecho a la protección de datos de los titulares de la información.
				
				Para realizar el proceso de anonimizarían diríjase a la guía de GUÍA DE ANONIMIZACIÓN DE DATOS ESTRUCTURADOS del Archivo general de la nación.
				https://www.archivogeneral.gov.co/sites/default/files/Estructura_Web/5_Consulte/Recursos/Publicacionees/Guia_de_Anonimizacion-min.pdf';	
			$html.='</td>';
		$html.='</tr>';
	$html.='</table>';
$html.='</body>';

if($pdf=="ok"){
	$dompdf = new Dompdf();
    $paper_size = array(0,0,612,792);
	$dompdf->loadHtml($html);
	$dompdf->setPaper($paper_size);
	$dompdf->render();
	$dompdf->stream("Ina_".$fecha2.".pdf");
	exit(); // Agregamos esta línea para salir del script después de generar el PDF
}else{
	echo $html;
	echo "<script>window.print();</script>";
}
?>