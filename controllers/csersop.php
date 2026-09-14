<?php
if (isset($_GET['_autocomplete'])) {
    while (ob_get_level() > 0) ob_end_clean();
    
    ini_set('display_errors', 0);
    ini_set('html_errors', 0);
    
    require_once ROOT_PATH . 'models/conexion.php';
    require_once ROOT_PATH . 'models/msersop.php';
    
    header_remove();
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate');
    header('X-Content-Type-Options: nosniff');
    
    try {
        $msersop = new Msersop();
        $term = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $results = $msersop->buscarUsuarios($term ?: '');
        
        echo json_encode([
            'success' => true,
            'data' => is_array($results) ? $results : [],
            'timestamp' => time()
        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Error en el servidor',
            'message' => $e->getMessage()
        ]);
    }
    
    exit(0);
}

include(__DIR__ . '/../models/msersop.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$msersop = new Msersop();

$idcen = isset($_SESSION['idcen']) ? $_SESSION['idcen'] : null;

$idsop = isset($_REQUEST['idsop']) ? $_REQUEST['idsop'] : NULL;
$idusu = isset($_POST['idusu']) ? $_POST['idusu'] : NULL;
$idusu2 = isset($_POST['idusu2']) ? $_POST['idusu2'] : NULL;
$falrep = isset($_POST['falrep']) ? $_POST['falrep'] : NULL;
$carper = isset($_POST['carper']) ? $_POST['carper'] : NULL;
$fecser = isset($_POST['fecser']) ? $_POST['fecser'] : NULL;
$nomper = isset($_POST['nomper']) ? $_POST['nomper'] : NULL;
$desser = isset($_POST['desser']) ? $_POST['desser'] : NULL;
$foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : NULL;
$evisop = NULL;

$ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;

/*if($foto){
    if (!is_dir('fevisop')) mkdir('fevisop', 0755, true);
    $filename = opti($_FILES['foto'], $idsop, 'fevisop', 'fevisop');
    $evisop = $filename ? 'fevisop/' . $filename : null;
}*/

$dtOne = NULL;
$msersop->setIdsop($idsop);


if($ope=="save"){
    $msersop->setIdusu($idusu);
    $msersop->setIdusu2($idusu2);
    $msersop->setFalrep($falrep);
    $msersop->setCarper($carper);
    $msersop->setFecser($fecser);
    $msersop->setNomper($nomper);
    $msersop->setDesser($desser);

    if(!$idsop){
        $msersop->setEvisop(null);
        $idsop = $msersop->save();
        $msersop->setIdsop($idsop);
        if($foto){
            $filename = opti($_FILES['foto'], $idsop, 'fevisop', 'fevisop');
            $evisop = $filename ? 'fevisop/' . $filename : null;
            $msersop->setEvisop($evisop);
            $msersop->updateEvisop();
        }
    } else {
        if($foto){
            $filename = opti($_FILES['foto'], $idsop, 'fevisop', 'fevisop');
            $evisop = $filename ? 'fevisop/' . $filename : null;
            $msersop->setEvisop($evisop);
            $msersop->updateEvisop();
        }
        $msersop->edit();
    }
}

if ($ope=="del" && $idsop) $msersop->del();
if ($ope=="edit" && $idsop){
    $dtOne = $msersop->getOne();
}else{
    $dtOne=NULL;
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $pg = isset($_GET['pg']) ? $_GET['pg'] : 'default value';
    echo ("<script>window.location.href = 'home.php?pg=".$pg."'</script>");
}

if (isset($_GET['grafico'])) {
    header('Content-Type: application/json');
    $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
    $idper = isset($_GET['idper']) ? $_GET['idper'] : null;
    $idusu = isset($_GET['idusu']) ? $_GET['idusu'] : null;
    switch ($_GET['grafico']) {
        case 'por_falla':
            echo json_encode($msersop->getSoporteFinalizadoPorFalla($filter, $idper, $idusu));
            break;
        case 'por_cargo':
            echo json_encode($msersop->getSoportePorCargo($filter, $idper, $idusu));
            break;
        case 'por_usuario':
            echo json_encode($msersop->getSoportePorUsuario($filter, $idper, $idusu));
            break;
        case 'por_evaluacion':
            echo json_encode($msersop->getSoportePorEvaluacion());
            break;
        case 'por_tiempo':
            echo json_encode($msersop->getSoportePorTiempo());
            break;
        default:
            echo json_encode([]);
    }
    exit;
}

$idper = isset($_SESSION['idper']) ? $_SESSION['idper'] : null;
$idusu_session = isset($_SESSION['idusu']) ? $_SESSION['idusu'] : null;

$dat = $msersop->getAll($idper, $idusu_session);
$dtSelsop=$msersop->getSelSop($idcen);
$dtCargo=$msersop->getCargoPer();
$dtFalla=$msersop->getFallaRep();
?>