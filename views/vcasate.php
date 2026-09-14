<?php 
    require_once 'controllers/ccasate.php';
?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i>Atención Casos",2); ?>
    <div class="row">
        <div class="inser">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Servicio Soporte</th>
                        <th>Evidencia servicio</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($dat) { foreach ($dat as $dt) { ?>
                        <tr>
                            <td>
                                <strong>
                                    <?=$dt["idsop"];?> - <?=$dt["nomusu"];?>
                                </strong>
                                <br>
                                    <?=$dt["nom_falrep"];?><br>
                                    <?=$dt["nom_carper"];?> - <?=$dt["nomper"];?><br>
                                    Fecha de inicio: <?=$dt["fecserini"];?><br>
                                    Fecha de fin: <?=$dt['fecserfin'];?><br>
                                    <?=$dt["desser"];?><br>
                                <strong>
                                    <?=$dt["nom_ultimo_estado"];?>
                                </strong>
                            </td>
                            <td class="img-cell">
                                <?php if(file_exists($dt['evisop'])){ ?>
                                    <img src="<?=$dt['evisop'];?>" alt="Evidencia" class="zoom-img" width="100%">
                                <?php } ?>
                            </td>
                            <td>
                                <a href="#" class="btn-asignar" data-idsop="<?=$dt["idsop"];?>" title="Asignarme este soporte">
                                    <i class="fa-solid fa-user-plus fa-2x text-success"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }} ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Servicio Soporte</th>
                        <th>Evidencia Soporte</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $(".btn-asignar").click(function(e){
        e.preventDefault();
        var btn = $(this);
        var idsop = btn.data("idsop");

        $.ajax({
            url: "controllers/ccasate.php",
            method: "POST",
            data: {
                ajax: "asignar_usuario",
                idsop: idsop
            },
            success: function(response){
    let res = JSON.parse(response);
    if(res.success){
            Swal.fire({
                icon: 'success',
                title: 'Soporte asignado',
                text: 'Soporte asignado correctamente.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                location.reload();
                btn.fadeOut();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al asignar el soporte.',
                confirmButtonText: 'Aceptar'
            });
        }
    },
    error: function(){
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'Error al conectar con el servidor.',
            confirmButtonText: 'Aceptar'
        });
    }
        });
    });
});
</script>