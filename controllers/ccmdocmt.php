<?php
require_once("models/mcmdocmt.php");

$mcmdocmt = new Mcmdocmt();

$documentos = [

"dcm_id" => "Documento de Identidad",
"dcm_med" => "Tratamiento de Menores",
"dcm_rci" => "Registro Civil",
"dcm_cap" => "Compromiso del Aprendiz",
"dcm_dba" => "Diploma o Acta de Grado",
"dcm_eps" => "Certificado EPS",
"dcm_icf" => "Certificado ICFES"

];

$docped_map = [];
$tipos_documento_bd = $mcmdocmt->getDoctipos();
foreach ($tipos_documento_bd as $doc_tipo){
    $docped_map[$doc_tipo['nomdocp']] = $doc_tipo['iddocp'];
}

$iddocp_genera_from_post = isset($_POST['iddocp']) ? $_POST['iddocp']:NULL;
$iduxf = isset($_POST['iduxf']) ? $_POST['iduxf']:NULL;
$aprdcma = "No";

$opera = isset($_POST['opera']) ? $_POST['opera']:NULL;

if($opera=="save"){
    foreach($documentos as $campo => $nombreDoc){
        if(isset($_FILES[$campo]) && $_FILES[$campo]['error'] == 0){
            $archivo = $_FILES[$campo];

            //Se valida q sea pdf
            $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            if(strtolower($ext) != 'pdf'){
                continue;
            }

            //Obtener el iddocp correcto para este tipo de documento
            $current_iddocp = NULL;
            if(isset($docped_map[$nombreDoc])){
                $current_iddocp = $docped_map[$nombreDoc];
            }else{
                continue;
            }

            if(empty($iduxf) || empty($current_iddocp)){
                continue;
            }

            //Ruta de guardado
            $rutaDestino = "docs/".time()."_".basename($archivo['name']);

            //Mueve el archivo temporal
            if(move_uploaded_file($archivo['tmp_name'], $rutaDestino)){

                $mcmdocmt->setNomdcma($nombreDoc);
                $mcmdocmt->setIduxf($iduxf);
                $mcmdocmt->setIddocp($current_iddocp);
                $mcmdocmt->setRutdcma($rutaDestino);
                $mcmdocmt->setFecdcma(date("Y-m-d"));
                $mcmdocmt->setAprdcma($aprdcma);

                $mcmdocmt->save();
                }else{continue;}

            
        }
    }
}

$datDocmt = $mcmdocmt->getAll();

?>