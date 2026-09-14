<?php
require_once('controllers/cvvc.php');

echo titulo2("<i class='" . $icono . "'></i> Votación Vocero", 2);
?>

<style>
    .tarj {
        padding: 0;
        margin: 0 auto;
        border-radius: 5px;
    }
    .btn.btn-secondary.painted {
        background-color: #117f09 !important;
    }
    @media(max-width: 400px) {
        #nomusu {
            font-weight: bold;
            font-size: 12px;
        }
    }
</style>

<form name="frm1" action="home.php?pg=<?= $pg; ?>" method="POST">
    <div class="mycont" style="display: block; text-align: center;" class="row">
        <div class="col">
            <?php if ($dat): ?>
                    <?php foreach ($dat as $i => $d): ?>
                            <input type="radio" class="btn-check" name="canusu" id="option<?= $i; ?>" 
                                   value="<?= htmlspecialchars($d['idusu']); ?>" required>
                            <label id="mylabel<?= $i; ?>" class="btn btn-secondary" 
                                   style="margin:10px;width:300px;border-radius:10px;"
                                   for="option<?= $i; ?>" onclick="togglePainted('mylabel<?= $i; ?>')">
                                <div class="tarj">
                                    <img class="fotvot" src="<?= !empty($d['fotcan']) ? htmlspecialchars($d['fotcan']) : 'image/usuario.png'; ?>" 
                                         style="width:220px;height:250px;margin-top:10px; border-radius:5px">
                                    <div style="text-align:center;margin:0;">
                                        <strong><big><?= ($d['noca'] == "999") ? " " : htmlspecialchars($d['noca']); ?></big></strong>
                                        <br>
                                        <strong><big id="nomusu"><?= strtoupper(htmlspecialchars($d['nomusu'])); ?></big></strong><br>
                                        <?php if (!empty($d['idfic'])): ?>
                                                <small><?= htmlspecialchars($d['idfic']); ?><br></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </label>
                    <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div style="width: 100%;text-align: center;">
            <input type="hidden" name="opera" value="save">
            <br>
            <input type="submit" value="Votar" class="btn btn-success" style="width:50%;">
        </div>
    </div>
</form>

<script>
    function togglePainted(labelId) {
        document.querySelectorAll('.btn.btn-secondary.painted').forEach(label => {
            label.classList.remove("painted");
        });
        document.getElementById(labelId).classList.add("painted");
    }
</script>