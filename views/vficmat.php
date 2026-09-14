<?php require_once("controllers/cficmat.php"); ?>
<div class="conte">
  <?php echo titulo2("<i class='".$icono."'></i> Ficha",1); ?>
  <div class="inser">
    <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
      <div class="row">
        <div class="form-group col-md-4">
          <label for="idfic">No. Ficha</label>
          <input type="text" name="idfic" id="idfic" class="form-control" value="<?php if($datOne) echo $datOne[0]['idfic']; ?>" <?php if($datOne) echo "readonly"; ?>>
        </div>
        <div class="form-group col-md-4">
          <label for="nomfic">Nombre de ficha</label>
          <input type="text" name="nomfic" id="nomfic" class="form-control" value="<?php if($datOne) echo $datOne[0]['nomfic']; ?>">
        </div>
        <div class="form-group col-md-4">
          <label for="jornada">Jornada</label>
          <select name="jornada" id="jornada" class="form-control form-select">
						<?php if($datJor){ foreach($datJor as $dt){ ?>
							<option value="<?=$dt["idval"];?>" <?php if($datOne && $datOne[0]['jornada']==$dt["idval"]) echo "selected"; ?>><?=$dt["nomval"];?></option>
						<?php }} ?>
					</select>
        </div>


        <div class="form-group col-md-4">
					<label for="idcen">Centro</label>
					<select name="idcen" id="idcen" class="form-control form-select">
						<?php if($datCen){ foreach($datCen as $dt){ ?>
							<option value="<?=$dt["idcen"];?>" <?php if($datOne && $datOne[0]['idcen']==$dt["idcen"]) echo "selected"; ?>><?=$dt["nomcen"];?></option>
						<?php }} ?>
					</select>
				</div>


				<div class="form-group col-md-4">
          <label for="mun">Departamento</label>
          <select class="form-control form-select" onchange="recCiudad(this.value);">
          	<option value="0">Seleccione Departamento</option>
						<?php if($datDep){ foreach($datDep as $dt){ ?>
							<option value="<?=$dt["codubi"];?>"><?=$dt["nomubi"];?></option>
						<?php }} ?>
          </select>
        </div>
        <div class="form-group col-md-4">
            <label for="ubiest">Municipio</label>
            <div id="reloadMun">
            	<?php if($datOne){ ?>
            		<input type="text" class="form-control" disabled value="<?=$datOne[0]['nommun'];?> - <?=$datOne[0]['nomdep'];?>">
            	<?php }else{ ?>
            		<input type="text" class="form-control" disabled value="Primero selecione un departamento">
            	<?php } ?>
                <input type="hidden" name="ubiest" id="ubiest" value="<?php if($datOne) echo $datOne[0]['mun']; ?>">
            </div>
        </div>

        <div class="form-group col-md-4">
					<label for="finific">Fecha Inicial de Ficha</label>
					<input type="date" name="finific" id="finific" class="form-control" value=<?php if($datOne) echo $datOne[0]['finific']; else echo date('Y-m-d'); ?>>
				</div>

				<div class="form-group col-md-4">
					<label for="ffinfic">Fecha final de Ficha</label>
					<input type="date" name="ffinfic" id="ffinfic" class="form-control" value="<?php if($datOne) echo $datOne[0]['ffinfic']; else echo date('Y-m-d'); ?>">
				</div>

        <div class="form-group col-md-4">
          <br>
          <input type="submit" class="btn btn-primary" value="Enviar">
          <input type="hidden" name="opera" value="<?php if($datOne) echo "save1"; else echo "save"; ?>">
        </div>


      </div>
    </form>
  </div>
</div>

<table id="example" class="table table-striped" style="width: 100%">
	<thead>
		<tr>
			<th>Ficha</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
				<?php if ($datAll) { foreach ($datAll as $da){ ?>
								<tr>
									<td>
										<big><strong><?=$da['idfic']." ".$da['nomfic'];?></strong></big>
										<br>
										<?=$da['nomval'];?>
										<br>
										<?=$da['idcen']." ".$da['nomcen'];?>
										<br>
										<?=$da['nommun'];?> - <?=$da['nomdep'];?>
										<br>
										<strong>Fecha de Inicio:</strong>
										<?=$da['finific'];?>
										<br>
										<strong>Fecha de Fin:</strong>
										<?=$da['ffinfic'];?>
									</td>
									<td>
											<a href="home.php?pg=<?=$pg;?>&opera=edi&idfic=<?=$da['idfic'];?>" title="Editar">
												<i class="fa-solid fa-pen-to-square fa-2x"></i>
											</a>

											<a href="home.php?pg=<?=$pg;?>&opera=eli&idfic=<?=$da['idfic'];?>" title="Eliminar" onclick="return eli(this);">
												<i class="fa-solid fa-trash-can fa-2x"></i>
											</a>

									</td>
								</tr>
				<?php }} ?>
	</tbody>

	<tfoot>
		<tr>
			<th>Ficha</th>
			<th></th>
		</tr>
	</tfoot>
</table>