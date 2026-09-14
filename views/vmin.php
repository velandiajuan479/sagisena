<?php require_once 'controllers/cmin.php'; ?>

</style>
<div class="conte">
    <?php echo titulo2("<i class='fa" . $icono . "'></i> Minuta", 0); ?>

    <form action="home.php?pg=1404" method="post">
        <div class="row">
            <div class="form-group col-md-3">
                <label id="fechos">Fecha inicial</label>
                <input type="date" name="fechos" id="fechos" value="<?= $fechos; ?>" max="<?= $fecha; ?>" onchange="this.form.submit()" class="form-control form-control-sm" required>
            </div>
            <div class="form-group col-md-3">
                <label id="fhlle">Fecha final</label>
                <input type="date" name="fhlle" id="fhlle" value="<?= $fhlle; ?>" max="<?= $fecha; ?>" onchange="this.form.submit()" class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-3">
                <label for="ndocusu">Usuario: </label>
                <input type="text" name="ndocusu" id="ndocusu" value="<?= $ndocusu; ?>" onkeydown="return enter(event);" onchange="this.form.submit();" onkeypress="return solonum(event);" class="form-control form-control">
            </div>
        </div>
    </form>
    <div style="margin: -50px 30px 20px 20px;float: right;">
        <a href="views/pdfmin.php?&fechos=<?= $fechos ?>&fhlle=<?= $fhlle ?>&ndocusu=<?= $ndocusu ?>" title="imprimir" target="_blank">
            <i class="fa-solid fa-print fa-2x" style="color:#027902;"></i>
        </a>
        <a href="views/pdfmin.php?&fechos=<?= $fechos ?>&fhlle=<?= $fhlle ?>&ndocusu=<?= $ndocusu ?>&pdf=ok" title="PDF" target="_blank">
            <i class="fa-solid fa-file-pdf fa-2x" style="color:#027902;"></i>
        </a>
    </div>
    <br>
    <table id="example" class="table table-striped" style="width:100% ">
        <thead>

            <tr>
                <th>Usuario</th>
                <th>Perfil</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Tiempo</th>
                <th>Estado</th>
                <th>Elementos</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($dat) {
                foreach ($dat as $d) {
            ?>
                    <tr>
                        <td>
                            <?= $d['ndocusu']; ?> <?= $d['nomusu']; ?><br><small>
                                <?= $d['idfic']; ?> <?= $d['nomfic']; ?> <?= $d['nomval']; ?></small>
                        </td>
                        <td>
                            <?= $d['nomper']; ?>
                        </td>
                        <td><?= $d['fechos']; ?></td>
                        <td><?= $d['fhlle'] ?></td>
                        <td><?= $d['tiempo'] ?></td>
                        <td><?php
                            if ($d['tipmin'] == "I") {
                                echo "Entro";
                            } else {
                                echo "Salio";
                            }
                            ?></td>
                        <td><?php
                            $eles = explode(";", $d['ideles']);
                            if ($eles) {
                                foreach ($eles as $ele) {
                                    $dtela = $mmin->selOneEle($ele);
                                    if ($dtela) echo $dtela[0]['nomele'] . " <small>" . $dtela[0]['nidele'] . " (" . $dtela[0]['nomval'] . ")</small><br>";
                                }
                            }
                            ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Usuario</th>
                <th>Perfil</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Tiempo</th>
                <th>Estado</th>
                <th>Elementos</th>
            </tr>
        </tfoot>
    </table>
    <br><br><br><br>

    <script>
        function enter(event) {
        // Verificar si la tecla presionada es "Enter"
        if (event.key === 'Enter') {
            // Enviar el formulario
            document.getElementById('ndocusu').form.submit();
            return false; // Evitar que se agregue un salto de línea al presionar "Enter"
        }
        return true;
    }

        document.addEventListener("DOMContentLoaded", function() {
            var formulario = document.getElementById("usu");
            var campoTexto = document.getElementById("ndocusu");

            campoTexto.addEventListener("keypress", function(event) {
                if (event.keyCode === 13) {
                    formulario.submit();
                }
            });

            /* formulario.addEventListener("submit", function(event) {
              event.preventDefault(); 
              enviarFormulario();
            });

            function enviarFormulario() {
              console.log("Formulario enviado!");
            } */
        });
    const fechaInput = document.getElementById('fechos');
    const fechaFinInput = document.getElementById('fhlle');
    const fechaActual = new Date();
    const anioActual = fechaActual.getFullYear();
    const mesActual = fechaActual.getMonth();
    const primerDiaDelMes = new Date(anioActual, mesActual, 1);
    const ultimoDiaDelMes = new Date(anioActual, mesActual + 1, 0);
    const fechaFormateada = primerDiaDelMes.toISOString().split('T')[0];
    const fechaFinFormateada = ultimoDiaDelMes.toISOString().split('T')[0];
    fechaInput.value = fechaFormateada;
    fechaFinInput.value = fechaFinFormateada;
    </script>