<?php include('controllers/mgmat.php');?>
 <div class="conte">
  <?php echo titulo2("<i class='".$icono."'> </i> Gestión de Matricula",2); ?>


  <form action="home.php?pg=<?=$_GET['pg']  ?>" method="POST">
    <div class="row">
      <div class="form-group col-md-2">
        <label for="campo">Campo de búsqueda</label>
        <select name="campo" id="campo" class="form-control form-select">
          <option value="">Seleccionar campo...</option>
          <option value="idusu" <?= ($campo ?? '') === 'idusu' ? 'selected' : '' ?>>Usuario</option>
          <option value="idpag" <?= ($campo ?? '') === 'idpag' ? 'selected' : '' ?>>Página</option>
          <option value="idcen" <?= ($campo ?? '') === 'idcen' ? 'selected' : '' ?>>Centro</option>
          <option value="idhor" <?= ($campo ?? '') === 'idhor' ? 'selected' : '' ?>>Horario</option>
          <option value="idasp" <?= ($campo ?? '') === 'idasp' ? 'selected' : '' ?>>Aspirante</option>
          <option value="idfic" <?= ($campo ?? '') === 'idfic' ? 'selected' : '' ?>>Ficha</option>
          <option value="codpro" <?= ($campo ?? '') === 'codpro' ? 'selected' : '' ?>>Programa</option>
        </select>
      </div>
    
      <div class="form-group col-md-2">
        <label for="idusu">Usuario</label>
        <input type="number" name="idusu" id="idusu" class="form-control" value="<?= $idusu ?? '' ?>">
      </div>
      <div class="form-group col-md-2">
        <label for="codpro">Programa</label>
        <select name="codpro" id="codpro" class="form-control form-select">
          <option value="">Seleccionar programa...</option>
          <?php if($dtProcesos){ foreach ($dtProcesos as $dtPro) { ?>
            <option value="<?=$dtPro['codpro'];?>" <?= ($codpro ?? '') === $dtPro['codpro'] ? 'selected' : '' ?>><?=$dtPro['nompro'];?></option>
          <?php }} ?>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label for="estado">Estado</label>
        <select name="estado" id="estado" class="form-control form-select">
          <option value="">Todos los estados</option>
          <option value="activo" <?= ($estado ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
          <option value="inactivo" <?= ($estado ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
        </select>
      </div>
      <div class="form-group col-md-2">
        <br>
        <input type="hidden" name="ope" value="search">
        <input type="submit" class="btn btn-primary" value="Buscar">
        <a href="home.php?pg=<?=$_GET['pg']?>" class="btn btn-secondary">Limpiar</a>
      </div>
    </div>
  </form>

  <!-- Tabla de registros -->
  <table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Página</th>
        <th>Centro</th>
        <th>Proceso</th>
        <th>Estado</th>
        <th>Fechas</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($dtmgmat) { foreach ($dtmgmat as $d) { ?>
      <tr>
        <td><span class="badge bg-primary"><?=$d['id'];?></span></td>
        <td>
          <?=$d['nomusu'] ?? 'Usuario ID: '.$d['idusu'];?>
          <br><small class="text-muted">ID: <?=$d['idusu'];?></small>
        </td>
        <td><?=$d['idpag'];?></td>
        <td>
          <?=$d['nomcen'] ?? 'Centro ID: '.$d['idcen'];?>
          <br><small class="text-muted">ID: <?=$d['idcen'];?></small>
        </td>
        <td>
          <span class="badge bg-info"><?=$d['codpro'];?></span>
          <?php if(isset($d['nompro'])): ?>
            <br><small><?=$d['nompro'];?></small>
          <?php endif; ?>
        </td>
        <td>
          <?php if($d['idhor']): ?>
            <strong>Horario:</strong> <?=$d['idhor'];?><br>
          <?php endif; ?>
          <?php if($d['idasp']): ?>
            <strong>Aspirante:</strong> <?=$d['idasp'];?> <?=$d['nomasp'] ?? '';?><br>
          <?php endif; ?>
          <?php if($d['idfic']): ?>
            <strong>Ficha:</strong> <?=$d['idfic'];?><br>
          <?php endif; ?>
          <?php if(!$d['idhor'] && !$d['idasp'] && !$d['idfic']): ?>
            <small class="text-muted">Sin información adicional</small>
          <?php endif; ?>
        </td>
        <td>
          <span class="badge <?= $d['estado'] === 'activo' ? 'bg-success' : 'bg-secondary' ?>">
            <?= ucfirst($d['estado']) ?>
          </span>
        </td>
        <td>
          <small>
            <strong>Creado:</strong> <?=date('d/m/Y H:i', strtotime($d['fecha_creacion']));?><br>
            <?php if($d['fecha_actualizacion']): ?>
              <strong>Actualizado:</strong> <?=date('d/m/Y H:i', strtotime($d['fecha_actualizacion']));?>
            <?php endif; ?>
          </small>
        </td>
        <td>
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-primary" onclick="editarRegistro(<?=$d['id'];?>)" title="Editar">
              <i class="fa fa-edit"></i>
            </button>
            <button type="button" class="btn btn-outline-danger" onclick="eliminarRegistro(<?=$d['id'];?>)" title="Eliminar">
              <i class="fa fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    <?php }} ?>
    </tbody>
  </table>
    </div>

    </script>
