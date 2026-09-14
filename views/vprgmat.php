<?php  require_once("controllers/cprgmat.php");?>

<div class="conte">
    <?php echo titulo2("<i class='" .$icono."'></i> Programas ",1); ?>
    
    
    <div class="inser">
          <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
        
        
            <div class="row">
                <div class="form-group col-md-4">
                        <label for="codpro">Codigo de Programa</label>
                        <input type="text" name="codpro" id="codpro" class="form-control" 
                        value="<?php if($datOne) echo $datOne[0]['codpro']; ?>" <?php if($datOne) echo "readonly"; ?>>
                </div>

                <div class="form-group col-md-4">
                    <label for="nompro"><strong>Programa</strong></label>
                    <input type="text" name="nompro" id="nompro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['nompro']; ?>"required>
                </div>
            </div>

            <div>
                <div class="form-group col-md-100">
                    <label for="despro"><strong>Descripción</strong></label>
                    <input type="text" name="despro" id="despro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['despro']; ?>"required>
                 </div>
            </div>

             <div class="row">
                <div class="form-group col-md-2">
                    <label for="verpro"><strong>Versión</strong></label>
                    <input type="text" name="verpro" id="verpro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['verpro']; ?>"required>
                </div>

                <div class="form-group col-md-2">
                    <label for="horlpro"><strong>Horas Etapa Lectiva</strong></label>
                    <input type="text" name="horlpro" id="horlpro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['horlpro']; ?>"required>
                </div>

                 <div class="form-group col-md-2">
                    <label for="horppro"><strong>Horas Etapa Productiva</strong></label>
                    <input type="text" name="horppro" id="horppro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['horppro']; ?>"required>
                </div>

                 <div class="form-group col-md-2">
                    <label for="crelpro"><strong>Créditos Etapa Lectiva</strong></label>
                    <input type="text" name="crelpro" id="crelpro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['crelpro']; ?>"required>
                </div>
                
                <div class="form-group col-md-2">
                    <label for="creppro"><strong>Créditos Etapa Productiva</strong></label>
                    <input type="text" name="creppro" id="creppro" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['creppro']; ?>"required>
                </div>
                
                 <div class="form-group col-md-2">
                    <label for="tippro"><strong>Tipo de Programa</strong></label>
                    <select name="tippro" id="tippro" class="form-control form-select" required>
                        <option value="">Seleccione el tipo programa</option>
                        <?php if($datpro){ foreach($datpro as $dt){ ?>
                            <option value="<?=$dt["idval"];?>" <?php if($datOne && $datOne[0]['tippro']==$dt["idval"]) echo "selected"; ?>>
                                <?=$dt["nomval"];?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>





            </div>

            <div class="row">
                <div class="form-group col-md-8">
                    <label for="redcon"><strong>Red de Conocimiento</strong></label>
                    <input type="text" name="redcon" id="redcon" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['redcon']; ?>"required>
                </div>

                <div class="form-group col-md-2">
                    <label for="idare"><strong>Área</strong></label>
                    <select name="idare" id="idare" class="form-control form-select" required>
                        <option value="">Asgne un Área</option>
                        <?php if($datAre){ foreach($datAre as $dt){ ?>
                            <option value="<?=$dt["idare"];?>" <?php if($datOne && $datOne[0]['idare']==$dt["idare"]) echo "selected"; ?>>
                                <?=$dt["nomare"];?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                
            </div>

             <div>
                <div class="form-group col-md-100">
                    <label for="just"><strong>Justificación</strong></label>
                    <input type="text" name="just" id="just" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['just']; ?>"required>
                 </div>

                 <div class="form-group col-md-100">
                    <label for="reqing"><strong>Requisitos de Ingreso</strong></label>
                    <input type="text" name="reqing" id="reqing" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['reqing']; ?>"required>
                 </div>
                 <div class="form-group col-md-100">
                    <label for="reqcer"><strong>Requisitos de Certificación</strong></label>
                    <input type="text" name="reqcer" id="reqcer" class="form-control"
                     value="<?php if($datOne) echo $datOne[0]['reqcer']; ?>"required>
                 </div>
            </div>

                <div class="form-group col-md-4">
                    <br>
                    <br>
                    <input type="submit" class="btn btn-primary" value="Enviar">
                    <input type="hidden" name="opera" value="<?php if($datOne) echo "save1"; else echo "save"; ?>">
                </div>
            
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Programa</th>
             <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php if($datAll){ foreach($datAll as $du){ ?>
        <tr>
            <td>
                <big><strong><?=$du['codpro'];?> - <?=$du['nompro'];?></strong></big><br>
                Tipo de programa: <?=$du['nomval']; ?>
                <br>
                Área: <?=$du['nomare']; ?>
                <br>
                Duración: <?=$du['horlpro']; ?>
                <br>
                Version: <?=$du['verpro']; ?>
                <br>
                Descripción: <?=$du['despro']; ?>
            </td>
            <td>
                <a href="home.php?pg=<?=$pg;?>&opera=edi&codpro=<?=$du['codpro'];?>" title="Editar">
				    <i class="fa-solid fa-pen-to-square fa-2x"></i>
			    </a>

				<a href="home.php?pg=<?=$pg;?>&opera=eli&codpro=<?=$du['codpro'];?>" title="Eliminar" onclick="return eli(this);">
                    <i class="fa-solid fa-trash-can fa-2x"></i>
                </a>     
            </td>
        </tr>
        <?php }} ?>
    </tbody>

    <tfoot>
        <tr>
            <th>Programa</th>
            <th></th>
        </tr>
    </tfoot>

</table>