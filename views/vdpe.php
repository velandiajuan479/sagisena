<?php require_once 'controllers/cdpe.php'; ?>

<?php
echo titulo2("<i class='" . $icono . "'></i> Datos personales", 2); ?>

<div class="datos-personales-container">
    <div class="logo-container">
        <img src="image/sena.png" class="logo-img">
    </div>
    <div class="datos-row bg1">
        <span class="datos-label font-bold">No. documento:</span>
        <span class="datos-value">
            <?php if ($datOne) echo $datOne[0]['ndocusu']; ?>
        </span>
    </div>
    <div class="datos-row bg2">
        <span class="datos-label font-bold">Nombre:</span>
        <span class="datos-value">
            <?php if ($datOne) echo $datOne[0]['nomusu']; ?>
        </span>
    </div>
    <div class="datos-row bg1">
        <span class="datos-label font-bold">Nombre del Centro:</span>
        <span class="datos-value">
            <?php if ($datOne) echo $datOne[0]['nomcen']; ?>
        </span>
    </div>
    <?php if ($datOne && $datOne[0]['nomfic']) { ?>
    <div class="datos-row bg2">
        <span class="datos-label font-bold">Ficha:</span>
        <span class="datos-value">
            <?php echo $datOne[0]['nomfic']; ?>
        </span>
    </div>
    <?php } ?>
    <?php if ($datOne && $datOne[0]['nomval']) { ?>
    <div class="datos-row bg1">
        <span class="datos-label font-bold">Jornada:</span>
        <span class="datos-value">
            <?php echo $datOne[0]['nomval']; ?>
        </span>
    </div>
    <?php } ?>

    <div class="botones-votar">
        <?php if (!$yaVotoRepresentante): ?>
            <a href="home.php?pg=1203" class="btn btn-success btn-votar">
                Votar Representante
            </a>
        <?php else: ?>
            <button class="btn btn-success btn-votar" disabled>
                <i class="fa fa-check"></i> Representante
            </button>
        <?php endif; ?>

        <?php if (!$yaVotoVocero): ?>
            <a href="home.php?pg=1202" class="btn btn-success btn-votar">
                Votar Vocero
            </a>
        <?php else: ?>
            <button class="btn btn-success btn-votar" disabled>
                <i class="fa fa-check"></i> Vocero
            </button>
        <?php endif; ?>
    </div>
</div>

<style>
.datos-personales-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 900px;
    margin: auto;
}
.logo-container {
    margin: 5px;
    width: 250px;
    display: flex;
    justify-content: center;
}
.logo-img {
    width: 100px;
    margin: 25px;
}
.datos-row {
    width: 100%;
    margin-bottom: 5px;
    display: flex;
    padding: 10px;
    border-radius: 10px;
}
.bg1 {
    background: #dadddd;
}
.bg2 {
    background: #e1e1e1;
}
.datos-label {
    flex: 1;
}
.font-bold {
    font-weight: bold;
}
.datos-value {
    flex: 1;
}
.botones-votar {
    padding: 10px;
    text-align: center;
    width: 100%;
}
.btn-votar {
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    margin: 5px;
}
</style>