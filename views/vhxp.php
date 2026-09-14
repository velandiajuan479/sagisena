<?php require_once 'controllers/chxp.php'; ?>

<?php echo titulo2("<i class='".$icono."'></i> Horarios del usuario",0); ?>


<?php if(isset($_SESSION['idpef']) && $_SESSION['idpef']==21){ ?>
<form action="home.php?pg=<?=$pg;?>" method="POST">
  <div class="row"> 
    <div class="form-group col-md-4">
      <label for="idaul">No. Documento Instructor:</label>
      <input type="number" name="idusu" id="idusu" class="form-control" onchange="this.form.submit();">
    </div>
  </div>
</form>
<?php } ?>



<table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
  			<th>Ficha y Programa</th>
        <?php pintit($ddi); ?>
  		</tr>
    </thead>
	 <tbody>
    <?php $cntDs=0; ?>
		<?php if($dfi){ foreach ($dfi as $dt) { ?>
			<tr>
				        <td>
                    <?=$dt["idfic"];?> <?=$dt["nomfic"];?><br>
                    <small>
                      Jornada: <?=$dt["nomval"];?> 
                      Área: <?=$dt["nomare"];?> 
                      Cod. Programa: <?=$dt["codpro"];?> 
                      
                    </small>
                </td>
                <?php
                  if($ddi){ foreach ($ddi as $d) {
                    //echo "<th>".$dt["idfic"]." - ".$d['idval']."</th>";
                    echo "<td>";
                      $mhxp->setIdfic($dt["idfic"]);
                      $mhxp->setIddia($d["idval"]);
                      $mhxp->setIdusu($idusu);
                      $dat = $mhxp->getAll();
                      if($dat){ foreach ($dat as $dti) {
                        echo $dti["ndocusu"]." ".$dti["nomusu"]."<BR>".$dti['nomaul'];
                        $cntDs += $dt["nhora"];
                      }}
                    echo "</td>";
                  }}

                ?>
			</tr>
		<?php }} ?>
	</tbody>

  <tfoot>
    <tr>
      <th>Ficha y Programa</th>
    	<?php pintit($ddi); ?>
    </tr>
  </tfoot>
</table>

 
<table class="table table-striped" style="/*width:100%*/">
  <tr>
    <th>Horas directas a formación:</th>
    <td><?=$cntDs;?></td>
  </tr>
  <tr>
    <th>Horas alistamiento (planta):</th>
    <td>
      <?php 
        $halis=2.1*5;
        echo $halis;
      ?>
      </td>
  </tr>
  <tr>
    <th>Horas Disponibles:</th>
    <td><?=8.5*5-$cntDs-$halis;?></td>
  </tr>
  <tr>
    <th>Total Horas:</th>
    <th><?=8.5*5;?></th>
  </tr>
</table>

<script>
$(document).ready(function() {
    // Esperar a que DataTables se inicialice (desde valida.js) y agregar botón de impresión
    setTimeout(function() {
        // Agregar el botón al mismo nivel que los otros botones de exportar
        $('.dt-buttons').append(
            '<a href="views/vrphor.php?idusu=<?= $idusu ?>" title="Imprimir horario del instructor" target="_blank" class="btn" style="color: #00af00;"><i class="fa-solid fa-print fa-2x"></i></a>'
        );
    }, 500);
});
</script>