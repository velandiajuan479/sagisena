<?php require_once ("controllers/cdpdr.php"); ?>
<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Documentos a pedir",1); ?>
	<div class="inser">
	<form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
			<!-- <form id="frmins" action="index.php" method="POST"> -->
				<div class="row">
						<div class="form-group col-md-6">
							<label for="nomdocp">Nombre del Documento</label>
							<input type="text" name="nomdocp" id="nomdocp" class="form-control" required
								value="<?php if(isset($datOne[0]['nomdocp'])) echo $datOne[0]['nomdocp']; ?>">
						</div>
						<div class="form-group col-md-6">
							<label for="tipdocp">Tipo de Documento</label>
							    <select name="tipdocp" id="tipdocp" class="form-control" required>
								<option value="">Seleccione...</option>
								<?php
								function obtenerNomval($idval, $tipdocdom) {
									foreach($tipdocdom as $tipo) {
										if($tipo['idval'] == $idval) return $tipo['nomval'];
									}
									return $idval;
								}
								?>
								<?php if(isset($tipdocdom)){ 
									foreach($tipdocdom as $tipo){ 
										$selected = (isset($datOne[0]['tipdocp']) && $datOne[0]['tipdocp'] == $tipo['nomval']) ? 'selected' : '';
								?>
									<option value="<?= $tipo['idval']; ?>" <?= $selected; ?>><?= $tipo['nomval']; ?></option>
								<?php }} ?>
							</select>
						</div>
						<div class="form-group col-md-6">
							<br>
							<input type="submit" class="btn btn-primary" value="Guardar">
							<input type="hidden" name="opera" value="save">
							<input type="hidden" name="iddocp" value="<?php if(isset($datOne[0]['iddocp'])) echo $datOne[0]['iddocp']; ?>">
						</div>
					</div>
				</form>	
	</div>
</div>

<table id="example" class="table table-striped" style="width: 100%">
	<thead>
		<tr>
			<th>ID</th>
			<th>Nombre del Documento</th>
			<th>Estado</th>
		</tr>
	</thead>
   <tbody>
        <?php if($datAll){ foreach($datAll as $dt){ ?>
            <tr>
                <td>
                    <big><strong><?= $dt["iddocp"]; ?> - <?= $dt["nomdocp"]; ?></strong></big><br>
                    Tipo: <?= obtenerNomval($dt["tipdocp"], $tipdocdom); ?>
                </td>
                <td>
                    <a href="home.php?pg=<?=$pg;?>&iddocp=<?=$dt['iddocp'];?>&opera=edi" title="Editar">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </a>
                    <a href="home.php?pg=<?=$pg;?>&iddocp=<?=$dt['iddocp'];?>&opera=eli" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este documento?');">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                    </a>
                </td>
				<td>
    <?php
        // Buscar el valor de 'act' para el tipo de documento actual
        $act = null;
        foreach ($tipdocdom as $tipo) {
            if ($tipo['idval'] == $dt['tipdocp']) {
                $act = $tipo['act'];
                break;
            }
        }
        // Cambiar el icono según el valor de 'act'
        if ($act == 1) {
            // Activo
            $icon = 'fa-solid fa-circle-check fa-2x';
            $title = 'Documento activo';
        } else {
            // Inactivo
            $icon = 'fa-solid fa-circle-xmark fa-2x';
            $title = 'Documento inactivo';
        }
    ?>
    <a href="home.php?pg=<?=$pg;?>&iddocp=<?=$dt['iddocp'];?>&opera" title="<?= $title; ?>">
        <i class="<?= $icon; ?>"></i>
    </a>
</td>
            </tr>
        <?php }} ?>
    </tbody>
	<tfoot>
		<tr>
			<th>ID</th>
			<th>Nombre del Documento</th>
			<th>Estado</th>
		</tr>
	</tfoot>	
</table>
