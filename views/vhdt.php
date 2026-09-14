<?php require_once("controllers/chdt.php"); ?>

<div class="conte">
    <?php echo titulo2("<i class='".$icono."'></i> Hoja de Trabajo",1);?>
    
    <div class="inser">
        <form id="frmins" action="home.php?pg=<?=$pg;?>" method="POST">
            <div class="row">
                <?php if($datOne){ ?>
                <div class="form-group col-md-12">
                    <h2>Editando Hoja No. <?=$datOne[0]['idnorad'];?></h2>
                </div>
                <?php } ?>
                <div class="form-group col-md-6">
                    <label for="codpro">Programa</label>
                    <select name="codpro" id="codpro" class="form-control form-select" required>
                        <?php if($datPr){ foreach($datPr AS $dt){ ?>
                            <option value="<?=$dt["codpro"]; ?>" <?php if($datOne && $datOne[0]['codpro']==$dt['codpro']) echo 'selected'; ?>>
                                <?=$dt["codpro"]." - ".$dt["nompro"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="idemp">Empresa</label>
                    <select name="idemp" id="idemp" class="form-control form-select" required>
                        <?php if($datEm){ foreach($datEm AS $dt){ ?>
                            <option value="<?=$dt["idemp"]; ?>" <?php if($datOne && $datOne[0]['idemp']==$dt['idemp']) echo 'selected'; ?>>
                                <?=$dt["nomemp"]; ?>
                            </option>
                        <?php }} ?>
                    </select> 
                </div>
                <div class="form-group col-md-6">
                    <label for="codproesp">Programa Especial</label>
                    <select name="codproesp" id="codproesp" class="form-control form-select" required>
                        <?php if($datPe){ foreach($datPe AS $dt){ ?> 
                            <option value="<?=$dt["idval"]; ?>" <?php if($datOne && $datOne[0]['codproesp']==$dt['idval']) echo 'selected'; ?>>
                                <?=$dt["nomval"]; ?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label for="feclini">Fecha Inicial</label>
                    <input type="date" name="feclini" id="feclini" onchange="nFfin(this.value);" class="form-control"value="<?php if($datOne && $datOne[0]['feclini']) echo $datOne[0]['feclini']; else echo $hoy; ?>" min="<?=$hoy; ?>" max="<?=$hmfin; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="feclin">Fecha Final</label>
                    <input type="date" name="feclin" id="feclin"  class="form-control" value="<?php if($datOne && $datOne[0]['feclin']) echo $datOne[0]['feclin']; else echo $hmes; ?>" min ="<?=$hoy; ?>" max="<?=$hmfin; ?>" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="cupo">Cupo</label>
                    <input type="number" min="1" name="cupo" id="cupo" class="form-control" value="<?php if($datOne && $datOne[0]['cupo']) echo $datOne[0]['cupo']; ?>" required>
                </div>

                <div class="form-group col-md-3">
                    <label for="jornada">Jornada</label>
                    <select name="jornada" id="jornada" class="form-control form-select" required>
                        <?php if($datJo){ foreach($datJo AS $dt){ ?>
                            <option value="<?=$dt["idval"]?>" <?php if($datOne && $datOne[0]['jornada']==$dt['idval']) echo 'selected'; ?>>
                                <?=$dt["nomval"]?>
                            </option>
                        <?php }} ?>
                    </select>
                </div>
                <?php if(isset($datOne)){ ?>
                <div class="form-group col-md-3">
                    <label for="idfic">Cod. Ficha</label>
                    <input type="number" name="idfic" id="idfic" class="form-control" value="<?php if($datOne && $datOne[0]['idfic']) echo $datOne[0]['idfic']; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="codslem">Cod. Solicitud Empresa</label>
                    <input type="text" name="codslem" id="codslem" class="form-control" value="<?php if($datOne && $datOne[0]['codslem']) echo $datOne[0]['codslem']; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="convht">Convenio</label>
                    <input type="text" name="convht" id="convht" class="form-control" value="<?php if($datOne && $datOne[0]['convht']) echo $datOne[0]['convht']; ?>">
                </div>
                <?php } ?>
                <div class="form-group col-md-3">
                    <input type="submit" class="btn btn-primary" value="<?=isset($datOne) ? 'Actualizar' : 'Guardar'?>">
                    <input type="hidden" name="opera" value="save">
                    <input type="hidden" name="idnorad" value="<?php if($datOne && $datOne[0]['idnorad']) echo $datOne[0]['idnorad']; ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Hoja de Trabajo</th>
            <th>Jornada</th>
            <th>Estado</th>
            <th>Acciones</th>

        </tr>
    </thead>
    <tbody>
        <?php if($datAll){ foreach ($datAll as $dta){ ?>
            <tr>
                <td>
                    <big><strong>Hoja de trabajo No. <?=$dta["idnorad"];?></strong></big><br>
                    <strong>Programa:</strong> <?=$dta["codpro"];?> - <?=$dta["nompro"];?><br>
                    <strong>Empresa:</strong> <?=$dta["nomemp"];?><br>
                    <strong>Fechas:</strong> <?=$dta["feclini"];?> al <?=$dta["feclin"];?>
                </td>
                
                <td><?=$dta["jorn"];?></td>
                <td>
                    <big><strong>Estado</strong>
                </td>
                <td>
                    <a href="home.php?pg=<?=$pg?>&opera=edi&idnorad=<?=$dta["idnorad"]?>">
                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                    </a>
                    <a href="home.php?pg=<?=$pg?>&opera=eli&idnorad=<?=$dta["idnorad"]?>" onclick="return eliminar();">
                        <i class="fa-solid fa-trash-can fa-2x"></i>
                    </a>

                    <a href="home.php?pg=2002&idnorad=<?=$dta["idnorad"]?>">
                        <i class="fa-solid fa-calendar fa-2x"></i>
                    </a>

                    <a href="home.php?pg=2007&idnorad=<?=$dta["idnorad"]?>">
                        <i class="fa-solid fa-user-group fa-2x"></i>
                    </a>    
                    
                    <a href="home.php?pg=2001&idnorad=<?=$dta["idnorad"]?>">
                        <i class="fa-solid fa-print fa-2x"></i>
                    </a>  

                    <a href="home.php?pg=2010&idnorad=<?=$dta["idnorad"]?>">
                        <i class="fa-solid fa-note-sticky fa-2x"></i>
                    </a>  
                     
                    <a href="home.php?pg=2004&idnorad=<?=$dta["idnorad"]?>">
                        <i class="fa-solid fa-lock fa-2x"></i>
                    </a>   

                </td>
                 
            </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Hoja de Trabajo</th>
            <th>Estado</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </tfoot>
</table>