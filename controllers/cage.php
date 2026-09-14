<?php
require_once('models/mage.php');
include("models/mprg.php");

$mage = new Mage();
$mprg = new Mprg();

$codpro = $_POST['codpro'] ?? NULL;
$idage  = $_REQUEST['idage'] ?? NULL;
$idfic  = $_REQUEST['idfic'] ?? NULL;
$idusu  = $_POST['idusu'] ?? NULL;
$idres  = $_POST['idres'] ?? NULL;
$fchinc = $_POST['fchinc'] ?? NULL;
$fchfnl = $_POST['fchfnl'] ?? NULL;
$ope    = $_REQUEST['ope'] ?? NULL;

$mage->setIdage($idage);
$mage->setIdfic($idfic);

if ($ope == "save") {
    $mage->setIdage($idage);
    $mage->setIdfic($idfic);
    $mage->setIdres($idres);
    $mage->setFchinc($fchinc);
    $mage->setFchfnl($fchfnl);
    if ($idage) $mage->edit();
    else $mage->save();
}

if ($ope == "edi" && $idage) {
    $dtOne = $mhor->getOne(); // Asegúrate de que $mhor esté definido
    $m = 1;
} else {
    $dtOne = NULL;
}

$dtPrg = NULL;
$dtAge = [];

$mage->setIdfic($idfic);
$dtAge = $mage->getAllAge();

// Procesar datos de agenda con horarios, fases y actividades
if ($dtAge) {
    // Agrupar por fases para mejor organización
    $dtAgeAgrupados = array();
    $fases = array();
    
    foreach ($dtAge as &$dta) {
        // Obtener instrumentos por resultado
        $dta['instrumentos'] = $mage->getInstrumentosByResultado($dta['idres']);
        
        // Obtener horarios por competencia y resultado
        $dta['horarios'] = $mage->getHorariosPorCompetencia($dta['idcom'], $dta['idres']);
        
        // Obtener fases y actividades por competencia
        $dta['fases_actividades'] = $mage->getFasesYActividades($dta['idcom']);
        
        // Procesar días de la semana desde la nueva estructura
        $dta['dias_clase'] = array(
            'L' => array(), 'M' => array(), 'M' => array(), 'J' => array(), 
            'V' => array(), 'S' => array(), 'D' => array()
        );
        
        // Mapear días booleanos a array de días
        $dias_map = array(
            'L' => $dta['lun'],
            'M' => $dta['mar'], 
            'M' => $dta['mie'],
            'J' => $dta['jue'],
            'V' => $dta['vie'],
            'S' => $dta['sab']
        );
        
        foreach ($dias_map as $dia => $activo) {
            if ($activo) {
                $dta['dias_clase'][$dia][] = array(
                    'instructor' => $dta['nomusu'] ?: 'Sin asignar',
                    'fecha' => $dta['fecini'],
                    'hora_inicio' => '00:00',
                    'hora_fin' => '00:00',
                    'fecha_inicio' => $dta['fecini'],
                    'fecha_fin' => $dta['fecfin'],
                    'horario_texto' => $dta['hor']
                );
            }
        }
        
        // Instructor principal desde la nueva estructura
        $dta['instructor_principal'] = $dta['nomusu'] ?: 'Sin asignar';
        
        // Fechas de inicio y fin desde la nueva estructura
        $dta['fecha_inicio_agenda'] = $dta['fecini'];
        $dta['fecha_fin_agenda'] = $dta['fecfin'];
        
        // Asegurar que competencia y resultado estén disponibles
        $dta['descom'] = $dta['descom'] ?: 'No disponible';
        $dta['nomres'] = $dta['nomres'] ?: 'No disponible';
        
        // Agrupar por fase
        $fase = $dta['fas'] ?: 'Sin Fase';
        if (!isset($dtAgeAgrupados[$fase])) {
            $dtAgeAgrupados[$fase] = array();
            $fases[] = $fase;
        }
        $dtAgeAgrupados[$fase][] = $dta;
    }
    
    // Reorganizar datos manteniendo el orden de fases
    $dtAge = array();
    foreach ($fases as $fase) {
        foreach ($dtAgeAgrupados[$fase] as $dta) {
            $dtAge[] = $dta;
        }
    }
}

if ($dtAge && $dtAge[0]['codpro']) {
    $mprg->setCodpro($dtAge[0]['codpro']);
    $dtPrg = $mprg->getOne();
}
?>