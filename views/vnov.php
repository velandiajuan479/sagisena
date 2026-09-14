<?php echo titulo2("<i class='fa " . $icono . "'></i> Novedades",0);
require_once 'controllers/cina.php';
require_once 'controllers/cnov.php'; ?>

<style>
    .caja {
        padding: 10px;
        margin-bottom: 10px;
        width: 100%;
        height: 95%;
    }
</style>

<div class="caja">
    <div class="row">
        <!-- Llamados por firmar -->
        <div class="col-12 col-lg-6 border border-2 rounded-4">
            <h1>Llamados por firmar</h1>

            <?php if (empty($llama)) : ?>
                <div class="alert alert-info text-center">
                    Aún no tienes llamados por firmar.
                </div>
            <?php else : ?>
                <?php foreach ($llama as $ll) { 
                    $tipo = ($ll['tipllam'] == 1) ? 'verbal' : (($ll['tipllam'] == 2) ? 'escrito' : 'desconocido');
                    $observacion = (trim($ll['obsllam']) !== '') ? $ll['obsllam'] : 'Sin motivo'; 

                    // Obtener nombre del instructor para este llamado
                    $nombreInstructor = $mnov->getInstructorNombre($ll['idllam']);
                ?>

                    <table id="mytable1" class="table table-striped" style="width:100%">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <strong>Llamado de atención <?php echo $tipo; ?></strong><br>
                                    <small>
                                        <strong>Motivo:</strong> <?php echo $observacion; ?><br>
                                        <strong>Instructor:</strong> <?php echo $nombreInstructor ?? 'No se encontró'; ?><br>
                                        <strong>Fecha y hora:</strong> 
                                        <?php 
                                            $fechaHora = new DateTime($ll['fecllam']); 
                                            echo $fechaHora->format('d/m/Y g:i A'); 
                                        ?><br>
                                    </small>
                                </td>
                                <td>  
                                    <form method="POST" action="home.php?pg=<?= $_GET['pg'] ?? 'novedades' ?>">
                                        <input type="hidden" name="idllam" value="<?= $ll['idllam'] ?>">
                                        <input type="hidden" name="ope" value="firmar">
                                        <input type="submit" class="btn btn-success" style="margin-top: 0px; float: right;" value="Firmar">
                                    </form>


                                    <a href="views/vrllam.php?idusu=<?= $ll['idusu'] ?>&idllam=<?= $ll['idllam'] ?>" title="Imprimir" target="_blank">
                                        <i class="fa fa-solid fa-print fa-2x" style="font-size: 30px;" title="Imprimir"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
            <?php endif; ?>
        </div>
        
        <!-- Llamados firmados -->
        <div class="col-12 col-lg-6 border border-2 rounded-4">
            <h1>Llamados firmados</h1>

            <?php if (empty($firma)) : ?>
                <div class="alert alert-info text-center">
                    Aún no tienes llamados firmados.
                </div>
            <?php else : ?>
                <?php foreach ($firma as $F) { 
                    $tipo = ($F['tipllam'] == 1) ? 'verbal' : (($F['tipllam'] == 2) ? 'escrito' : 'desconocido');
                    $observacion = (trim($F['obsllam']) !== '') ? $F['obsllam'] : 'Sin motivo'; 

                    // Obtener nombre del instructor para este llamado
                    $nombreInstructor = $mnov->getInstructorNombre($F['idllam']);
                ?>

                    <table id="mytable1" class="table table-striped" style="width:100%">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <strong>Llamado de atención <?php echo $tipo; ?></strong><br>
                                    <small>
                                        <strong>Motivo:</strong> <?php echo $observacion; ?><br>
                                        <strong>Instructor:</strong> <?php echo $nombreInstructor ?? 'No se encontró'; ?><br>
                                        <strong>Fecha y hora:</strong> 
                                        <?php 
                                            $fechaHora = new DateTime($F['fecllam']); 
                                            echo $fechaHora->format('d/m/Y g:i A'); 
                                        ?><br>
                                    </small>
                                </td>
                                <td>  
                                    <a href="views/vrllam.php?idusu=<?= $F['idusu'] ?>&idllam=<?= $F['idllam'] ?>" title="Imprimir" target="_blank">
                                        <i class="fa fa-solid fa-print fa-2x" style="font-size: 30px;" title="Imprimir"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
</div>
