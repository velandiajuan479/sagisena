<?php
$dias_semana = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];
$dias = !empty($datos['dias_formacion_json']) ? json_decode($datos['dias_formacion_json'], true) : [];
?>
<head>
    <meta charset="UTF-8">
    <title>Hoja de Trabajo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .contenedor { border: 2px solid black; padding: 20px; width: 900px; margin: auto; background: #fff; }
        .titulo { text-align: center; font-weight: bold; margin-bottom: 10px; font-size: 18px; }
        .subtitulo { text-align: center; font-size: 14px; margin-bottom: 20px; }
        .fila, .fila2 { display: flex; margin-bottom: 5px; }
        .campo, .campo2 { border: 1px solid black; padding: 4px 8px; font-size: 13px; min-height: 24px; }
        .campo { width: 50%; }
        .campo2 { width: 33.33%; }
        .etiqueta { font-weight: bold; display: inline-block; min-width: 180px; }
        .checkboxes { display: flex; flex-wrap: wrap; margin-bottom: 10px; }
        .checkboxes div { width: 33.33%; display: flex; align-items: center; font-size: 13px; }
        .checkbox { width: 18px; height: 18px; border: 1px solid black; margin-right: 6px; display: inline-block; text-align: center; line-height: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; }
        td, th { border: 1px solid black; padding: 4px; text-align: center; font-size: 13px; }
        .label-grande { font-weight: bold; font-size: 15px; }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="titulo">SENA CENTRO DE DESARROLLO AGROEMPRESARIAL</div>
        <div class="subtitulo">REPORTE HOJA DE TRABAJO SOLICITUD CURSOS DE COMPLEMENTARIA - TITULADA</div>

        <!-- Primera fila -->
        <div class="fila">
            <div class="campo"><span class="etiqueta">RADICADO</span></div>
            <div class="campo2"><?= $datos['idnorad'] ?? '' ?></div>
            <div class="campo"><span class="etiqueta">NOMBRE DEL PROGRAMA DE FORMACIÓN</span></div>
            <div class="campo2"><?= $datos['nompro'] ?? '' ?></div>
        </div>

        <!-- Segunda fila -->
        <div class="fila">
            <div class="campo"><span class="etiqueta">CÓDIGO DEL PROGRAMA</span></div>
            <div class="campo2"><?= $datos['codpro'] ?? '' ?></div>
            <div class="campo"><span class="etiqueta">DURACIÓN MÁXIMA HORAS</span></div>
            <div class="campo2"><?= $datos['duracion'] ?? '' ?></div>
        </div>

        <!-- Tercera fila -->
        <div class="fila">
            <div class="campo"><span class="etiqueta">CÓDIGO SOLICITUD DE EMPRESA</span></div>
            <div class="campo2"><?= $datos['idemp'] ?? '' ?></div>
            <div class="campo"><span class="etiqueta">CARACTERIZACIÓN DE LA FICHA</span></div>
            <div class="campo2"><?= $datos['caracterizacion'] ?? '' ?></div>
        </div>

        <!-- Cuarta fila -->
        <div class="fila">
            <div class="campo" style="width:100%"><span class="etiqueta">CONVENIO:</span> <?= $datos['convenio'] ?? '' ?></div>
        </div>

        <div class="campo2"><span class="etiqueta">PROGRAMA ESPECIAL:</span> <?= $datos['codproesp'] ?? '' ?></div>

        <!-- Checkboxes -->
        <div class="campo" style="width:50%">
            <div class="checkboxes">
                <div><span class="checkbox"><?= !empty($datos['programa_ser']) ? 'X' : '' ?></span>PROGRAMA SER</div>
                <div><span class="checkbox"><?= !empty($datos['aula_movil']) ? 'X' : '' ?></span>AULA MOVIL</div>
                <div><span class="checkbox"><?= !empty($datos['bilingüismo']) ? 'X' : '' ?></span>BILINGUISMO</div>
                <div><span class="checkbox"><?= !empty($datos['atencion_instituciones']) ? 'X' : '' ?></span>ATENCIÓN INSTITUCIONES</div>
                <div><span class="checkbox"><?= !empty($datos['fortalecimiento']) ? 'X' : '' ?></span>FORTALECIMIENTO</div>
                <div><span class="checkbox"><?= !empty($datos['mipymes']) ? 'X' : '' ?></span>MIPYME</div>
            </div>
        </div>

        <!-- Datos empresario -->
        <div class="label-grande">DATOS EMPRESARIO</div>
        <div class="fila2">
            <div class="campo2"><span class="etiqueta">EMPRESA:</span></div>
            <div class="campo2"><?= $datos['nomemp'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">DIRECCIÓN DEL LUGAR DE FORMACIÓN:</span></div>
            <div class="campo2"><?= $datos['direccion'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">MUNICIPIO:</span></div>
            <div class="campo2"><?= $datos['municipio'] ?? '' ?></div>
        </div>

        <div class="fila2">
            <div class="campo2"><span class="etiqueta">CONTACTO:</span></div>
            <div class="campo2"><?= $datos['contacto'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">TELÉFONO:</span></div>
            <div class="campo2"><?= $datos['telefono'] ?? '' ?></div>
        </div>

        <!-- Programación de la ficha -->
        <div class="label-grande">PROGRAMACIÓN DE LA FICHA:</div>
        <div class="fila2">
            <div class="campo2"><span class="etiqueta">FECHA DE INICIO</span></div>
            <div class="campo2"><?= $datos['fecha_inicio'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">FECHA DE TERMINACIÓN:</span></div>
            <div class="campo2"><?= $datos['fecha_fin'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">CUPO:</span> </div>
            <div class="campo2"><?= $datos['cupo'] ?? '' ?></div>
        </div>

        <div class="fila2">
            <div class="campo2"><span class="etiqueta">JORNADA:</span></div>
            <div class="campo2"><?= $datos['jornada'] ?? '' ?></div>
        </div>

        <!-- Tabla de días de formación -->
        <div class="label-grande">INDICAR DÍAS DE FORMACIÓN Y HORAS EN FORMA MILITAR</div>
        <table>
            <tr><th>DÍA</th><th>HORA INICIO</th><th>HORA TERMINACIÓN</th></tr>
            <?php foreach ($dias_semana as $dia): ?>
                <tr>
                    <td><?= $dia ?></td>
                    <td><?= $dias[$dia]['inicio'] ?? '' ?></td>
                    <td><?= $dias[$dia]['fin'] ?? '' ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Datos del instructor -->
        <div class="fila2">
            <div class="campo2"><span class="etiqueta">NOMBRE Y APELLIDO DEL INSTRUCTOR:</span></div>
            <div class="campo2"><?= $datos['nombre_instructor'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">DOCUMENTO DEL INSTRUCTOR:</span></div>
            <div class="campo2"><?= $datos['documento_instructor'] ?? '' ?></div>
        </div>

        <div class="fila2">
            <div class="campo2"><span class="etiqueta">CORREO INSTRUCTOR:</span></div>
            <div class="campo2"><?= $datos['correo_instructor'] ?? '' ?></div>
            <div class="campo2"><span class="etiqueta">CEL.:</span></div>
            <div class="campo2"><?= $datos['cel_instructor'] ?? '' ?></div>
        </div>

        <div class="fila2">
            <div class="campo2"><span class="etiqueta">CONTROL DEL COORDINADOR:</span></div>
            <div class="campo2"></div>
            <div class="campo2"><span class="etiqueta">CÓDIGO DE FICHA:</span></div>
            <div class="campo2"><?= $datos['codigo_ficha'] ?? '' ?></div>
        </div>

        <div class="fila2">
            <div class="campo2"><span class="etiqueta">CONTROL APOYO A LA SUPERVISIÓN:</span></div>
            <div class="campo2"></div>
            <div class="campo2"><span class="etiqueta">CONTROL COMPLEMENTARIA:</span></div>
            <div class="campo2"></div>
        </div>

    </div>
</body>
</html>

<!-- Botón de prueba -->
<a href="controllers/crhdt.php?id=1">
    <button>Generar reporte de prueba</button>
</a>