<?php echo titulo2("<i class='".$icono."'></i> Ventanas Emergentes"); ?>

	<div class="row" style="margin-bottom: 50px">
		<div class = "form-group col-md-6">
			<label>Módulo 1</label>
		<select class="form-select">
		<option>1</option>
		<option>2</option>
		</select>
		</div>
		<div class = "form-group col-md-6">
			<label>Módulo 2</label>
		<select class="form-select">
		<option>1</option>
		<option>2</option>
	</select>
		</div>
	</div>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $n=100;
        for ($i=0;$i<$n;$i++) { 
        ?>
	<tr>
	<td><?=$i+1;?></td>
	<td>Soila Bacca <?=$i+1;?></td>
	<td></td>
	</tr>
	<?php
        }
    ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th></th>
            </tr>
        </tfoot>
    </table>