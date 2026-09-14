<?php require_once 'controllers/cfpro.php'; ?>
<?php $ta="700px";?>

<div>
<table style="margin: 0 auto;" width="<?=$ta?>">
	<tr>
		<td style="text-align: center;">
			<img src="image/sena.png" width="80px">
			<br><br>
		</td>
	</tr>
</table>
<table style="margin: 0 auto;" width="<?=$ta?>" cellpadding="5px" cellspacing="0">
    <tr>
        <td>
<a style="margin-right: 20px;" href="views/pfpro.php?idusu=<?=$dus[0]['idusu'];?>" target="_blank">
    <i class="fa-solid fa-print fa-2x" title="Inprimir"></i>
</a>
        &nbsp;&nbsp;&nbsp;
<a href="views/pfpro.php?pdf=ok&idusu=<?=$dus[0]['idusu'];?>" target="_blank">
	<i class="fas fa-file-pdf fa-2x" title="Generar PDF"></i>
</a>
        </td>
    </tr>
	<tr>
		<td style="text-align: center;border: 1px solid #000;" colspan="3">
			<strong>FORMATO DE PROPUESTA</strong>
		</td>
	</tr>
	<tr>
    <td style="border: 1px solid #000;">
			<strong>NOMBRE DEL CANDIDATO:</strong> 
		</td>
        <td style="border: 1px solid #000;" colspan="2">
        <?=$dus[0]['nomusu'];?>
		</td>
	</tr>
	<tr>
    <td style="border: 1px solid #000;">
			<strong>FICHA DEL CANDIDATO:</strong><br>
            <?=$dus[0]['nomfic'],$dus[0]['idfic'];?>
		</td>
		<td style="border: 1px solid #000;">
			<strong>CENTRO DE FORMACION:</strong><br>
            <?=$dus[0]['nomcen'];?>
		</td>
        <td style="border: 1px solid #000;">
			<strong>JORNADA:</strong><br>
            <?=$dus[0]['nomval'];?>
		</td>
	</tr>
    <?php  
        if($dvpr){
				$n=0;
				foreach($dvpr AS $dv){
					if(!$dv['parval']){
                        ?>
                        <tr>
                            <td style="border: 1px solid #000;" colspan="2">
                                <?=$dv['nomval'];?>
                            </td>
                            <td style="border: 1px solid #000;">
                                <?php if($datOne) echo $datOne[$n]['texpro']; ?>
                            </td>
                        </tr>
    <?php
		$n++;
			}else{
	?>
            <tr>
                <td style="text-align: center;border: 1px solid #000;" colspan="3">
                    <strong><?=$dv['nomval'];?></strong>  
                </td>  
            </tr>
            <tr>         
                <td style="border: 1px solid #000;" colspan="3">
                    <strong><?=$dv['parval'];?></strong><br> 
                    <?php if($datOne) echo $datOne[$n]['texpro']; ?>  
                </td>
            </tr>
            <?php
									$n++;
								}?></tr>
            <?php
							}
						}
				?>
                
</table>
<br> <br> <br>
</div>

