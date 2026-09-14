<?php
require_once('controllers/cvot.php');
?>
<?php
echo titulo2("<i class='" . $icono . "'></i></i> Votacion", 2);
?>
<style>
    .tarj {
        padding: 0 !important;
        border-radius: 10px;
        outline: 1px solid #b3b3b3;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 225px;
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
<div class="mycont" style="display: block; text-align: center;" class="row">
    <div class="row gap-5 d-flex justify-content-center mb-5">
        <?php
        if ($dat) {
            foreach ($dat as $d) {
                ?>
                <form class="col-2 tarj" action="home.php?pg=<?= $pg; ?>" method="POST"
                    onsubmit="return confirmarVotacion(this, '<?= strtoupper($d['nomusu']); ?>')">

                    <div style="width: 100%;">
                        <?php if ($d['fotcan']) { ?>
                            <img class="fotvot" src="<?= $d['fotcan']; ?>" style="width:100%;height:250px; border-radius:10px 10px 0px 0px">
                        <?php } else { ?>
                            <img src="image/usuario.png" style="width:auto;height:250px; border-radius:10px 10px 0">
                        <?php } ?>
                        <strong>
                            <?php if ($d['noca'] == "999")
                                echo " ";
                            else
                                echo $d['noca']; ?>
                        </strong>
                    </div>
                    <div class="row" style="width: 100%;height: 100%;">
                        <div class="col-7">

                            <strong><?= strtoupper($d['nomusu']); ?></strong>
                            <small>
                                <?php if ($d['idfic']) { ?>
                                    <?= $d['idfic']; ?><br>
                                <?php } ?>
                            </small>
                        </div>
                        <div class="col d-flex p-0">
                            <input type="hidden" name="canusu" value="<?= $d['idusu']; ?>">
                            <input type="hidden" name="opera" value="save">
                            <button type="submit" class="btn btn-success form-control"
                                style="border-radius: 0px 10px 10px 0; border: none;">Votar</button>
                        </div>
                    </div>

                </form>
                <?php
            }
        }
        ?>
    </div>
</div>

<script>
    function confirmarVotacion(form, nombre) {
    event.preventDefault();

    Swal.fire({
        title: '¿Está seguro?',
        text: "Está a punto de votar por " + nombre,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, votar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#00af00'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}
</script>