<?php
// Datos de ejemplo, los puedes cambiar o llenar dinámicamente
$datos = [
    'tipo_documento' => '', // opciones: 'Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería', 'Otro',
    'otro_cual' => '', // si eliges 'Otro', escribir acá
    'numero_documento' => '',
    'programa_formacion' => '',
    'ficha_caracterizacion' => '',
    'centro_formacion' => '',
    'nombre_aprendiz' => '',
    'firma_aprendiz' => '',
    'firma_tutor' => '',
    'tipo_documento_tutor' => '',
    'numero_documento_tutor' => '',
    'fecha_diligenciamiento' => date('d/m/Y'),
    'dia' => date('d'),
    'mes' => date('m'),
];
?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            background: #fff;
            color: #000;
        }
        h1, h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 20px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center; /* Centra horizontalmente */
            gap: 10px; /* Espacio entre logo y texto */
        }

        .logo-h1 {
            height: 50px; /* Ajusta el tamaño del logo */
            width: auto;
        }

        h2 {
            font-size: 16px;
            margin-top: 5px;
            margin-bottom: 25px;
        }
        .section {
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .section2 {
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .checkbox-group {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            user-select: none;
        }
        .checkbox-box {
            width: 18px;
            height: 18px;
            border: 1px solid black;
            margin-right: 6px;
            text-align: center;
            line-height: 18px;
            font-weight: bold;
        }
        .checkbox-box.checked {
            background: black;
            color: white;
        }
        .firma-block {
            margin-top: 40px;
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: space-between;
            font-size: 14px;
        }
        .firma-item {
            flex: 1 1 40%;
            min-width: 280px;
            border-top: 1px solid black;
            padding-top: 6px;
            text-align: center;
            user-select: none;
        }
        .firma-label {
            margin-top: 6px;
        }
        .small-text {
            font-size: 12px;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <h1>
        <img src="img/logoSena.png" alt="Logo SENA" class="logo-h1">
        PROCESO GESTIÓN DE FORMACIÓN PROFESIONAL INTEGRAL
    </h1>
    <h2>FORMATO “MI COMPROMISO COMO APRENDIZ SENA”</h2>

        <div class="section">

        <!-- Sección: Tipo de documento -->
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 0px; border-collapse: collapse; width: 100%;">
            <tr>
                <th colspan="2" style="text-align: left;">&nbsp;Yo,</th>
            </tr>
            <tr>
        </table>
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 0px; border-collapse: collapse; width: 100%;">
            <tr>
                <?php 
                $tipos = ['Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería', 'Otro'];
                foreach ($tipos as $tipo): 
                    $checked = ($datos['tipo_documento'] === $tipo) ? '' : '';
                ?>
                    <th><?= $tipo ?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($tipos as $tipo): ?>
                    <td style="text-align: center;">
                        <?= ($datos['tipo_documento'] === $tipo) ? '' : '' ?>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php if ($datos['tipo_documento'] === 'Otro'): ?>
            <tr>
                <td colspan="2"><strong>Otro:</strong> <?= htmlspecialchars($datos['otro_cual']) ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <!-- Sección: Número de documento -->
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 0px; border-collapse: collapse; width: 100%;">
            <tr>
                <th>&nbsp;No. de documento</th>
                <td><?= htmlspecialchars($datos['numero_documento']) ?></td>
            </tr>
        </table>

        <!-- Sección: Programa de formación -->
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 0px; border-collapse: collapse; width: 100%;">
            <tr>
                <th>&nbsp;Matriculado en el programa de formación:</th>
                <td><?= htmlspecialchars($datos['programa_formacion']) ?></td>
            </tr>
        </table>

        <!-- Sección: Ficha de caracterización -->
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 0px; border-collapse: collapse; width: 100%;">
            <tr>
                <th>&nbsp;Ficha de caracterización No.</th>
                <td><?= htmlspecialchars($datos['ficha_caracterizacion']) ?></td>
            </tr>
        </table>

        <!-- Sección: Centro de formación -->
        <table border="1" cellpadding="8" cellspacing="0" style="margin-bottom: 0px; border-collapse: collapse; width: 100%;">
            <tr>
                <th>&nbsp;Centro de formación</th>
                <td><?= htmlspecialchars($datos['centro_formacion']) ?></td>
            </tr>
        </table>

    </div>
    <div class="section2">
        <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <tr>
                <td colspan="2">
                    <p>&nbsp;Me comprometo con el Servicio Nacional de Aprendizaje - SENA, en mi calidad de Aprendiz, y como persona responsable de mis actos, a:</p>
                    <ol>
                        <li>Cumplir y promover las disposiciones contempladas en el Reglamento del Aprendiz SENA, publicado en la página web del Sena y en el blog de cada centro de formación, del cual hago constar que he leído y entendido, por lo que acepto las responsabilidades, derechos y obligaciones establecidas; así como acatar las Normas y los Acuerdos de Convivencia Institucional de conformidad con el contexto geográfico y social del Centro de Formación.</li>
                        <li>Participar en todo el proceso de inducción para iniciar el programa de formación, de acuerdo con la programación del Centro de Formación.</li>
                        <li>Portar en todo momento el carné de identificación institucional en sitio visible.</li>
                        <li>Proyectar la imagen corporativa del Sena dentro y fuera de la Entidad asumiendo una actitud ética, con principios y valores sociales en cada una de mis actuaciones.</li>
                        <li>Respetar la orientación sexual, identidad de género, edad, etnia, culto, religión, ideología, procedencia y ocupación, de todos los integrantes de la comunidad educativa.</li>
                        <li>Al finalizar la formación dar cumplimiento oportuno a todos los trámites académicos y administrativos para lograr la certificación dentro del término que establece el reglamento.</li>
                        <li>Si soy seleccionado como beneficiario para recibir apoyo de sostenimiento, alimentación, transporte u otro, por parte de la entidad, me comprometo a realizar de forma adecuada todo los trámites administrativos y académicos correspondientes reglamentados por el Sena.</li>
                        <li>Registrar y mantener actualizados mis datos personales y de contacto en los aplicativos informáticos que el Sena determine y actuar como veedor del registro oportuno de las situaciones académicas y administrativas que se presenten. Cualquier dato registrado por el aprendiz que no corresponda con la información real, será sujeto a  lo establecido  en la  ley de delitos informáticos y demás normatividad vigente sobre uso de plataformas públicas.</li>
                        <li>Con la firma del presente compromiso autorizo al Sena para que me notifique a través de mi correo electrónico registrado en el aplicativo Sofia plus, todos los actos académicos y administrativos, así como también los procedimientos y trámites en general que profiera, de acuerdo con las  políticas de uso y confidencialidad.</li>
                    </ol>
                </td>
            </tr>

            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                <tr>
                    <td colspan="2">
                        <strong>&nbsp;FIRMA DEL APRENDIZ:</strong> ____________________________ &nbsp;&nbsp;
                        <strong>&nbsp;No. Documento de Identidad:</strong> <?= htmlspecialchars($datos['numero_documento']) ?>
                    </td>
                </tr>
            </table>

            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                <tr>
                    <td colspan="2">
                        <strong>&nbsp;FIRMA DE: LA MADRE, EL PADRE O TUTOR (A):</strong> ____________________________ &nbsp;&nbsp;
                        <br>
                        <strong>&nbsp;Tipo y No. Documento de Identidad:</strong> <?= htmlspecialchars($datos['tipo_documento_tutor']) ?> <?= htmlspecialchars($datos['numero_documento_tutor']) ?>
                        <br>
                        <em>&nbsp;(Únicamente en caso de que el (la) aprendiz sea menor de edad, debe anexar copia del documento oficial que acredite la condición de padre, madre o tutor(a))</em>
                    </td>
                </tr>
            </table>

            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                <tr>
                    <td colspan="2">
                        <strong>&nbsp;FECHA DE DILIGENCIAMIENTO:&nbsp;</strong> <?= htmlspecialchars($datos['fecha_diligenciamiento']) ?> &nbsp;&nbsp;
                        <strong>&nbsp;DÍA:</strong> <?= htmlspecialchars($datos['dia']) ?> &nbsp;&nbsp;
                        <strong>&nbsp;MES:</strong> <?= htmlspecialchars($datos['mes']) ?>
                    </td>
            </tr>
            </table>

            <!-- Nota final -->
            <tr>
                <td colspan="2">
                    <p2><strong>
                             &nbsp;Este documento forma parte de la ficha académica del aprendiz y es prueba del compromiso que adquiere con el SENA de cumplir el Reglamento de Aprendices SENA, el cual es firmado durante el proceso de matrícula en un programa de formación en el.
                        </strong>
                    </p2>
                </td>
            </tr>

        </table>
    </div>

</body>

    