<?php require_once 'controllers/ccav.php'; ?>

<!-- Actualizar candidato -->
<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Candidato Vocero ",2); ?> 
    <?php if($idusu){ ?>
        <form method="POST" enctype="multipart/form-data">
        </form>
    <?php } ?>
</div>

<!-- Filtro  -- -->
<div class="conte">
    <form action="#" method="POST">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="fidcen">Centro de Formación</label>
                <select name="fidcen" id="fidcen" class="form-select" onchange="this.form.submit();">
                    <?php
                    if($dcen){
                        foreach ($dcen as $dj) { 
                    ?>
                            <option value="<?=$dj['idcen'];?>"
                                <?php if($fidcen==$dj['idcen']) echo " selected "; ?>><?=$dj['nomcen'];?></option>
                    <?php
                        }
                    } 
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="fidfic">Ficha</label>
                <select name="fidfic" id="fidfic" class="form-select" onchange="this.form.submit();">
                    <option value="">-- Seleccione una ficha --</option>
                    <?php
                    if($dfichas){
                        foreach ($dfichas as $ficha) { 
                    ?>
                            <option value="<?=$ficha['idfic'];?>"
                                <?php if($fidfic==$ficha['idfic']) echo " selected "; ?>>
                                <?=$ficha['idfic']?> - <?=$ficha['nomfic']?> (<?=$ficha['nomval']?>)
                            </option>
                    <?php
                        }
                    } 
                    ?>
                </select>
            </div>
        </div>
    </form>
</div>
<br><br>

<!-- candidatos vocero de la ficha seleccionada -->
<?php if($fidfic): ?>
<div class="container mt-4">
    <!-- Boton para exportar a excel y csv -->
    <div class="text-end mb-3" style="position: absolute; top: 180px; right: 20px;">
        <?php if($fidfic && !empty($dat)): ?>
            <a href="views/excav.php?fidfic=<?=$fidfic?>" title="Descargar Excel" target="_blank" class="me-2">
                <i class="fas fa-file-excel fa-2x"></i> 
            </a>
            <a href="views/csvcav.php?fidfic=<?=$fidfic?>" title="Descargar CSV" target="_blank">
                <i class="fas fa-file-csv fa-2x"></i>
            </a>
        <?php endif; ?>
    </div>
    
    <?php if(!empty($dat)): ?>
    <?php foreach($dat as $candidato): ?>
    <hr style="border-top: 1px solid #117f09; margin: 0px 0;">
    
    <div class="row" style="margin-bottom: 30px; margin-left:-100px">
        <!-- columna de foto candidato -->
        <div class="col-md-3">
            <h5 style="margin-bottom: 15px; color: #000000; font-size: 16px; text-align: left;margin-left:50px">Foto</h5>
            <img src="<?= file_exists($candidato['fotcan']) ? $candidato['fotcan'] : 'image/usuario.png' ?>" 
                 class="img-fluid rounded" alt="Foto del candidato" 
                 style="max-height: 150px; width: auto; display: block; margin: 0 auto;">
        </div>
        
        <!-- Columna de datos -->
        <div class="col-md-5">
            <h5 style="margin-bottom: 15px; color: #000000; font-size: 16px; text-align: left;">Candidato</h5>
            <div style="margin-bottom: 10px; text-align: left;">
                <strong>Nombre:</strong> <?= htmlspecialchars($candidato['nomusu']) ?>
            </div>
            <div style="margin-bottom: 10px; text-align: left;">
                <strong>Documento:</strong> <?= $candidato['ndocusu'] ?>
            </div>
            <div style="margin-bottom: 10px; text-align: left;">
                <strong>Número de candidato:</strong> #<?= $candidato['noca'] ?>
            </div>
            <div style="margin-bottom: 10px; text-align: left;">
                <strong>Email:</strong> <?= htmlspecialchars($candidato['emausu']) ?>
            </div>
            <div style="margin-bottom: 10px; text-align: left;">
                <strong>Ficha:</strong> <?= htmlspecialchars($candidato['nomfic']) ?>
            </div>
        </div>

        <div class="col-md-4 d-flex align-items-center justify-content-end"  style="margin-left:-90px">
            <!-- Copiar -->
            <button class="btn btn-link text-success copiar-datos p-0 me-3"
                    data-nombre="<?= htmlspecialchars($candidato['nomusu']) ?>"
                    data-documento="<?= $candidato['ndocusu'] ?>"
                    data-numero="<?= $candidato['noca'] ?>"
                    data-email="<?= htmlspecialchars($candidato['emausu']) ?>"
                    title="Copiar datos">
                <i class="fas fa-copy fa-2x" style="color: #117f09;margin-left:900px"></i>
            </button>
            
            <!-- Editar -->
            <a href="home.php?pg=1221&idusu=<?=$candidato['idusu'];?>" 
               class="me-3"
               title="Editar Propuesta" 
               target="_blank">
                <i class="fa-solid fa-pen-to-square fa-2x" style="color: #117f09;"></i>
            </a>
            
            <!-- Ver Propuesta -->
            <a href="home.php?pg=1220&idusu=<?=$candidato['idusu'];?>" 
               title="Ver Propuesta" 
               target="_blank">
                <i class="fa-solid fa-file-lines fa-2x" style="color: #117f09;"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div style="text-align: center; padding: 30px; color: red;">
        <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
        <p style="margin-top: 10px;">No hay candidatos a vocero para esta ficha.</p>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.copiar-datos').forEach(button => {
        button.addEventListener('click', () => {
            const nombre = button.getAttribute('data-nombre');
            const documento = button.getAttribute('data-documento');
            const numero = button.getAttribute('data-numero');
            const email = button.getAttribute('data-email');

            const datos = `Nombre: ${nombre}\nDocumento: ${documento}\nNúmero de candidato: ${numero}\nEmail: ${email}`;
            navigator.clipboard.writeText(datos)
                .then(() => alert('Datos del candidato copiados al portapapeles'))
                .catch(err => console.error('Error al copiar:', err));
        });
    });
});
</script>


<?php endif; ?>

<div style="float: left;width: 100%;padding: 0px 10px 100px 10px">
    <div class="inser">
        <form name="frm5" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <div class="form-group col-md-6">
                    <br>
                    <input type="submit" class="btn btn-primary" onclick="return eliminar();" value="Limpiar votaciones" style="border:rgb(14, 69, 9) 2px solid;margin-left:-6px;">
                    <input type="hidden" name="opera" value="Limpiar">
                </div>
            </div>
        </form>
    </div>
</div>
<script> ocul(0,0);</script>