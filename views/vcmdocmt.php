<?php require_once("controllers/ccmdocmt.php"); ?>
<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Carga de Documentos",1); ?>
    <div class="inser">
        <form id="frmins" action="home.php?pg<?=$pg;?>" method="POST" enctype="multipart/form-data">
        <!--<form id="frmins" action="index.php" method="POST"> -->
            <p>Los archivos a subir deben estar en formato PDF(.pdf).</p>
            <div class="row">
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Documento de Identidad (C.C, T.I, C.E)</b></label>
                    <input type="file" name="dcm_id" id="dcm_id" class="form-control"> <!--Documento Identidad-->
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Tratamiento de Menores de Edad.</b></label>
                    <input type="file" name="dcm_med" id="dcm_med" class="form-control"> <!--Documento Menor de edad-->
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Registro Civil.</b></label>
                    <input type="file" name="dcm_rci" id="dcm_rci" class="form-control"> <!--Documento Registro Civil-->
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Compromiso del Aprendiz.</b></label>
                    <input type="file" name="dcm_cap" id="dcm_cap" class="form-control"> <!--Documento Compromiso Aprendiz-->
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Diploma de Bachiller o Acta de Grado.</b></label>
                    <input type="file" name="dcm_dba" id="dcm_dba" class="form-control"> <!--Documento Bachiller-->
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Certificado de la EPS.</b></label>
                    <input type="file" name="dcm_eps" id="dcm_eps" class="form-control"> <!--Documento EPS-->
                </div>
                <div class="form-group col-md-4">
                    <br>
                    <label><b>Certificado del ICFES.</b></label>
                    <input type="file" name="dcm_icf" id="dcm_icf" class="form-control"> <!--Documento ICFES-->
                </div>
                <div class="form-group col-md-4">
                    <br><br>
                    <input type="submit" value="Enviar Documentos" class="btn btn-primary">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="iddocp" value="<?=$iddocp;?>">
                    <input type="hidden" name="iduxf" value="<?=$iduxf;?>">
                </div>
            </div>
        </form>
    </div>
</div>