<?php require_once 'controllers/cdpc.php'; ?>

<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Resultados", 2); ?>
</div>
<div class="row">
    <div class="form-group col-md-10">
        <form class="form-default-pages" action="home.php?pg=<?= $pg ?>" method="POST">
            <div class="row">
                <div class="form-group col-md-5">
                    <label for="idfic" class="fw-semibold">Jornada</label>
                    <select name="fidjor" id="idfic" class="form-select form--input-default" onchange="this.form.submit();">
                        <?php
                        if ($djor) {
                            foreach ($djor as $dj) {
                        ?>
                                <option value="<?= $dj['idval']; ?>" <?php if ($fidjor == $dj['idval']) echo " selected "; ?>><?= $dj['nomval']; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                <div class="form-group col-md-7">
                    <label for="idcen" class="fw-semibold">Centro de Formación</label>
                    <select name="fidcen" id="idcen" class="form-select form--input-default" onchange="this.form.submit();">
                        <?php
                        if ($dcen) {
                            foreach ($dcen as $dj) {
                        ?>
                                <option value="<?= $dj['idcen']; ?>" <?php if ($fidcen == $dj['idcen']) echo " selected "; ?>><?= $dj['nomcen']; ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="form-group col-md-1" style="text-align: center;">
        <br>
        <a href="views/pdfact.php?&fidcen=<?= $fidcen; ?>&fidjor=<?= $fidjor; ?>" target="_blank">
            <i class="fas fa-print fa-2x" title="Imprimir Acta"></i>
        </a>
    </div>
    <div class="form-group col-md-1" style="text-align: center;">
        <br>
        <a href="views/vrfivt.php" title="Listado de Votantes" target="_blank">
            <i class="fa-regular fa-file-lines fa-2x"></i>
        </a>
    </div>
</div>
<!-- 
Filtro Fin ---------------------------------------------
<div style="margin: -15px 20px 10px 0px;float: right;">
    
    
    <a href=".views/pdfact.php?idusu=1" target="_blank">
        <i class="fa-solid fa-file-pdf fa-2x" title="PDF" style="color:#027902;"></i>
    </a>
    
                &nbsp;&nbsp;&nbsp;
    <a href= "dompdf/autoload.inc.php" target="_blank">
        <i class="fas fa-file-pdf fa-2x" title="Generar PDF"></i>
    </a>
</div> -->


<!-- Mostrar Candidato  ------------------------------- -->

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th style="text-align: center;">Votos</th>
            <th>Foto</th>
            <th>Candidato</th>
            <th></th>
        </tr>

    </thead>
    <tbody>
        <?php
        $totm = 0;
        if ($dat) {
            foreach ($dat as $d) {
        ?>
                <tr>
                    <td style="text-align: center;font-size: 50px;">
                        <?php
                        $nvo = $mdpc->nvoCan($d['idusu']);
                        $nvo = isset($nvo[0]['nvo']) ? $nvo[0]['nvo'] : 0;
                        echo $nvo;
                        $totm += $nvo;
                        ?>
                    </td>
                    <td width="100px">
                        <?php //if(file_exists("fcan/".$d['ndocusu'].".jpg")){
                        if (file_exists($d['fotcan'])) {
                        ?>
                            <img src="<?= $d['fotcan']; ?>" style="width: 100%;">
                        <?php } else { ?>
                            <img src="image/usuario.png" style="width: 100%;">
                        <?php } ?>
                    </td>
                    <td>
                        <?= $d['nomusu']; ?><br>
                        <small>
                            <strong>No. Documento: </strong> <?= $d['ndocusu']; ?><br>
                            <?php if ($d['idfic']) { ?>
                                <strong>Ficha: </strong> <?= $d['idfic']; ?> <?= $d['nomfic']; ?> <?= $d['nomval']; ?> <br>
                            <?php } ?>
                            <?= $d['nomcen']; ?><br>

                        </small>
                    </td>
                    <td style="text-align: left;">
                        <a href="home.php?pg=1204&idusu=<?= $d['idusu']; ?>" title="Ver Propuesta" target="_blank">
                            <i class="fa-solid fa-file-lines fa-2x fa-2x"></i>
                        </a>
                    </td>
                </tr>
        <?php
            }
        }
        ?>

    </tbody>
    <tfoot>
        <tr>
            <td style="text-align: center;font-size: 50px;">
                <?= $totm; ?>
            </td>
            <td style="font-size: 50px;">Total</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: center;">Votos</th>
            <th>Foto</th>
            <th>Candidato</th>
            <th></th>
        </tr>
    </tfoot>
</table>


<script>
document.addEventListener("DOMContentLoaded", () => {
		let h1 = document.querySelector(".title-page");
		if (h1) {
			document.title = h1.textContent.trim();
		}
	});
</script>