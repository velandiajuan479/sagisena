<?php require_once 'controllers/cper.php'; ?>

<div style="margin-bottom: 150px">
    <div class="conte">
        <?php echo titulo2("<i class='" . $icono . "'></i> Perfil"); ?>
        <div class="inser">
            <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST">
                <div class="row" style="margin-bottom: 50px">
                    <div class="form-group col-md-6">
                        <label for="nomper">Nombre de perfil</label>
                        <input type="text" name="nomper" id="nomper" class="form-control" required value="<?php if ($datOne) echo $datOne[0]['nomper']; ?> ">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="idpag">Pagina Inicial</label>
                        <select name="idpag" id="idpag" class="form-select form-select-lg">
                            <?php
                            if ($datpag) {
                                foreach ($datpag  as $dt) { ?>
                                    <option value="<?= $dt['idpag']; ?>" <?php
                                        if ($datOne && $datOne[0]['idpag'] == $dt['idpag']) echo " selected "; ?>><?= $dt['nompag']; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="idmod">Módulo</label>
                        <select name="idmod" id="idmod" class="form-select">
                            <?php if ($datmod) {
                                foreach ($datmod as $dtm) { ?>
                                    <option value="<?= $dtm['idmod']; ?>" <?php if ($datOne && $datOne[0]['idmod'] == $dtm['idmod']) echo " selected ";
                                        ?>><?= $dtm['nommod']; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <br>
                        <input type="submit" class="btn btn-primary" value="Registrar">
                        <input type="hidden" name="opera" value="save">
                        <input type="hidden" name="idper" value="<?php if ($datOne) echo $datOne[0]['idper']; ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Perfil</th>     
                <th></th>           
                <!-- botones pdf -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($dat) {
                foreach ($dat as $d) {
            ?>
                    <tr>
                        <td>
                            <strong><?= $d['idper'] . " - " . $d['nomper']; ?></strong>
                            <br>
                            <small>
                                <strong>Módulo: </strong><?= $d['nommod']; ?>
                                <strong>Página: </strong><?= $d['nompag']; ?>
                            </small>

                        </td>
                        <td style="text-align:right;">
                            <a href="#"  title="Ver Páginas" data-bs-toggle="modal" data-bs-target="#myModal<?= $d['idper']; ?>">
                                <i class="fa-solid fa-clipboard-check fa-2x"></i>
                            </a>
                            
                            <?php                             
                            $mper->setIdmod($d['idmod']);
                            $dtm = $mper->getPagMod();
                            $mper->setIdper($d['idper']);
                            $dps = $mper->getPxP();
                            $dms = arrstr($dps);                            
                            modal($d['idper'], $d['nomper'], $dtm, $pg,$dms); ?>
                            <a href="home.php?pg=<?= $pg; ?>&idper=<?= $d['idper']; ?>&opera=edi" title="edit">
                                <i class="fa-solid fa-pen-to-square fa-2x"></i>
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
                <th>Perfil</th>        
                <th></th>        
            </tr>
        </tfoot>
    </table>
</div>