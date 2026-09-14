<?php
include('controllers/caula.php'); 
?>
<div class="conte">
    <?php echo titulo2("<i class='" . $icono . "'></i> Aula"); ?>
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="nomaul">Ambiente Numero</label>
                    <input type="text" name="nomaul" id="nomaul" maxlength="255" class="form-control" required value="<?php if (!empty($datOne)) echo $datOne[0]['nomaul']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="piso">Piso</label>
                    <input type="number" name="piso" id="piso" class="form-control" value="<?php if(!empty($datOne)) echo $datOne[0]['piso']; else echo "1" ?>" min="1" max="99">
                </div>
                <div class="form-group col-md-6">
                    <label for="bloqau">Bloque</label>
                    <input type="number" name="bloqau" id="bloqau" class="form-control" value="<?php if(!empty($datOne)) echo $datOne[0]['bloqau']; else echo "1" ?>" min="0" max="99">
                </div>
                <div class="form-group col-md-6">
                    <label for="taula">Tamaño</label>
                    <input type="number" name="taula" id="taula" class="form-control" value="<?php if(!empty($datOne)) echo $datOne[0]['taula']; else echo "1" ?>" min="1" max="4">
                </div>
                <div class="form-group col-md-6">
                    <label for="tipaul">Tipo</label>
                    <select name="tipaul" id="tipaul" class="form-select" required>
                        <option value="1" <?php if ($datOne && $datOne[0]['tipaul'] == 1) echo 'selected'; ?>>Ambiente</option>
                        <option value="2" <?php if ($datOne && $datOne[0]['tipaul'] == 2) echo 'selected'; ?>>Especial</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="desaula">Descripción</label>
                    <textarea name="desaula" id="desaula" class="form-control"><?php if (!empty($datOne)) echo $datOne[0]['desaula']; ?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <input class="btn btn-primary" type="submit" value="Enviar">
                    <input type="hidden" name="ope" value="save">
                    <input type="hidden" name="idaul" id="idaul" value="<?php if (!empty($datOne)) echo $datOne[0]['idaul']; ?>">
                </div>
            </div>
        </form>
        <br><br>

    <?php if($dtPiso){ foreach ($dtPiso as $dtp) {
        $maula->setPiso($dtp['piso']);
        $dtBloque = $maula->getAllBloque();
    ?>
        <h3>Piso <?=$dtp['piso'];?></h3>
        <div class="row">
            <?php if($dtBloque){ foreach ($dtBloque as $dbq) {
                $maula->setBloqau($dbq['bloqau']);
                $dtaula = $maula->getAllAula();
            ?>
                <div class="form-group col-md-6">
                    <h4>Bloque <?=$dbq['bloqau'];?></h4>
                    <div class="row">
                        <?php
                            if($dtaula){ foreach ($dtaula as $dtau) {
                                $taula = $dtau['taula'];
                                if(!$taula) $taula=1;
                        ?>
                            <div class="form-group col-md-<?=3*$taula;?> cuaaula" style="width: <?=23*$taula;?>% !important;">
                                <h5><?=$dtau['nomaul'];?></h5>
                                <a href="home.php?pg=<?=$pg;?>&idaul=<?=$dtau['idaul'];?>&ope=edi" title="Editar">
                                    <i class="fa-solid fa-pen-to-square fa-2x" style="font-size: 16px;"></i>
                                </a>
                                <a href="home.php?pg=<?=$pg;?>&idaul=<?=$dtau['idaul'];?>&ope=eli" title="Eliminar" onclick="return eli(this);">
                                    <i class="fa-solid fa-trash-can fa-2x" style="font-size: 16px;"></i>
                                </a>
                            </div>
                        <?php }} ?>
                    </div>
                </div>
            <?php }} ?>
        </div>
    <?php }} ?>
</div>