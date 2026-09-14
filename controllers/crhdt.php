<?php

require_once __DIR__ . '/../models/mrhdt.php';

$datos = [];

if (!empty($_GET['id'])) {
    $mrhdt = new Mrhdt();
    $res = $mrhdt->getOne($_GET['id']);
    if (!empty($res)) {
        $datos = [
            'idnorad' => $res['idnorad'],
            'codpro' => $res['codpro'],
            'idemp' => $res['idemp'],
            'codproesp' => $res['codproesp'],
            'codslem' => $res['codslem'],
            'fecha_inicio' => $res['feclini'],
            'fecha_fin' => $res['feclin'],
            'cupo' => $res['cupo'],
            'jornada' => $res['jornada'],
            'codigo_ficha' => $res['idfic'],
            'nompro' => $res['nompro'],
            'duracion' => '',
            'caracterizacion' => '',
            'convenio' => '',
            'programa_ser' => '',
            'aula_movil' => '',
            'bilingüismo' => '',
            'atencion_instituciones' => '',
            'fortalecimiento' => '',
            'mipymes' => '',
            'empresa' => $res['nomemp'],
            'direccion' => '',
            'municipio' => '',
            'contacto' => '',
            'telefono' => '',
            'dias_formacion_json' => '',
            'nombre_instructor' => '',
            'documento_instructor' => '',
            'cel_instructor' => '',
            'correo_instructor' => '',
            'control_apoyo' => '',
            'control_complementaria' => ''
        ];
    }
}

include __DIR__ . '/../views/vrhdt.php';