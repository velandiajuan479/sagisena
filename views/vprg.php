<?php require_once 'controllers/cprg.php'; ?>

<div class="conte">
  <?php echo titulo2("<i class='" . $icono . "'></i> Programa", 1); ?>

  <div class="inser container-form-pages">
    <form class="form-default-pages" id="frmins" action="home.php?pg=<?= $pg; ?>" method="POST">
      <div class="row gap-4 justify-content-between">
        <div class="form-group col-md-4">
          <label for="codpro" class="fw-semibold">Código Programa</label>
          <input type="number" name="codpro" id="codpro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['codpro'])
            echo $dtOne[0]['codpro']; ?>" <?php if ($dtOne && $dtOne[0]['codpro'])
                echo "readonly"; ?>required>
        </div>
        <div class="form-group col-md-4">
          <label for="nompro" class="fw-semibold">Programa</label>
          <input type="text" name="nompro" id="nompro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['nompro'])
            echo $dtOne[0]['nompro']; ?>" required>
        </div>
        <div class="form-group col-md-2">
          <label for="tippro" class="fw-semibold">Tipo de Programa</label>
          <select name="tippro" id="tippro" class="form-control form-select form--input-default">
            <?php if ($dtTippr) {
              foreach ($dtTippr as $dtTr) { ?>
                <option value="<?= $dtTr['idval']; ?>" <?php if ($dtOne && $dtOne[0]['tippro'] == $dtTr['idval'])
                    echo "selected"; ?>><?= $dtTr['nomval']; ?></option>
              <?php }
            } ?>
          </select>
        </div>
        <div class="form-group col-md-12">
          <label for="despro" class="fw-semibold">Descripcion</label>
          <textarea name="despro" id="despro" class="form-control form--input-default" required><?php if ($dtOne && $dtOne[0]['despro'])
            echo $dtOne[0]['despro']; ?></textarea>
        </div>
        <div class="form-group col-md-2">
          <label for="verpro" class="fw-semibold">Versión</label>
          <input type="text" name="verpro" id="verpro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['verpro'])
            echo $dtOne[0]['verpro']; ?>" required>
        </div>
        <div class="form-group col-md-6">
          <label for="redcon" class="fw-semibold">Red de Conocimiento</label>
          <input type="text" name="redcon" id="redcon" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['redcon'])
            echo $dtOne[0]['redcon']; ?>" required>
        </div>
        <div class="form-group col-md-3">
          <label for="idare" class="fw-semibold">Área</label>
          <select name="idare" id="idare" class="form-control form-select form--input-default">
            <?php if ($dtArea) {
              foreach ($dtArea as $dtTr) { ?>
                <option value="<?= $dtTr['idare']; ?>" <?php if ($dtOne && $dtOne[0]['idare'] == $dtTr['idare'])
                    echo "selected"; ?>><?= $dtTr['nomare']; ?></option>
              <?php }
            } ?>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="horlpro" class="fw-semibold">Horas Etapa Lectiva</label>
          <input type="number" name="horlpro" id="horlpro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['horlpro'])
            echo $dtOne[0]['horlpro']; ?>" required>
        </div>
        <div class="form-group col-md-2">
          <label for="horppro" class="fw-semibold">Horas Etapa Productiva</label>
          <input type="number" name="horppro" id="horppro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['horppro'])
            echo $dtOne[0]['horppro']; ?>" required>
        </div>
        <div class="form-group col-md-2">
          <label for="crelpro" class="fw-semibold">Créditos Etapa Lectiva</label>
          <input type="number" name="crelpro" id="crelpro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['crelpro'])
            echo $dtOne[0]['crelpro']; ?>">
        </div>
        <div class="form-group col-md-2">
          <label for="creppro" class="fw-semibold">Créditos Etapa Productiva</label>
          <input type="number" name="creppro" id="creppro" class="form-control form--input-default" value="<?php if ($dtOne && $dtOne[0]['creppro'])
            echo $dtOne[0]['creppro']; ?>">
        </div>
        <div class="form-group col-md-6">
          <label for="just" class="fw-semibold">Justificación</label>
          <textarea name="just" id="just" class="form-control form--input-default" required><?php if ($dtOne && $dtOne[0]['just'])
            echo $dtOne[0]['just']; ?></textarea>
        </div>
        <div class="form-group col-md-5">
          <label for="reqing" class="fw-semibold">Requisitos de Ingreso</label>
          <textarea name="reqing" id="reqing" class="form-control form--input-default" required><?php if ($dtOne && $dtOne[0]['reqing'])
            echo $dtOne[0]['reqing']; ?></textarea>
        </div>
        <div class="form-group col-md-6">
          <label for="reqcer" class="fw-semibold">Requisitos de Certificación</label>
          <textarea name="reqcer" id="reqcer" class="form-control form--input-default" required><?php if ($dtOne && $dtOne[0]['reqcer'])
            echo $dtOne[0]['reqcer']; ?></textarea>
        </div>

        <div class=" form-group col-md-4 d-flex flex-column justify-content-end align-items-start">
          <input type="hidden" name="ope" value="save">
          <input type="hidden" name="ope2" value="<?php if ($dtOne && $dtOne[0]['codpro'])
            echo "edit"; ?>">
          <?php if ($dtOne && $dtOne[0]['codpro'])
            echo '<input type="hidden" name="codpro" id="codpro" value="' . $dtOne[0]['codpro'] . '">' ?>
            <input type="submit" class="btn btn-success" value="Enviar">
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>Programa</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php if ($datfil) {
            foreach ($datfil as $dt) { ?>
        <tr>
          <td>
            <strong>
              <?= $dt["codpro"]; ?> - <?= $dt["nompro"]; ?>
            </strong><br><small>
              <?= $dt["verpro"]; ?>
              <?= $dt["horlpro"]; ?>
              <?= $dt["horppro"]; ?>
              <?= $dt["crelpro"]; ?>
              <?= $dt["creppro"]; ?>
              <?= $dt["tippro"]; ?>
              <?= $dt["idare"]; ?>
            </small>
          </td>
          <td>
            <a href="home.php?pg=1527&ope=del&codpro=<?= $dt['codpro'] ?>" onclick="return eli(this);" title="Eliminar"><i
                class="fa-solid fa-trash-can fa-2x"></i></a>
            <a href="home.php?pg=1527&ope=edi&codpro=<?= $dt["codpro"]; ?>" title="Editar"><i
                class="fa-solid fa-pen-to-square fa-2x"></i></a>
            <a href="home.php?pg=1510&codpro=<?= $dt["codpro"]; ?>" title="Competencias"><i
                class="fa-solid fa-folder-open fa-2x"></i></a>
          </td>
          <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'eliminado'): ?>
              <script>
                Swal.fire('¡Eliminado!', 'El registro se eliminó correctamente.', 'success');
              </script>
            <?php elseif ($_GET['msg'] === 'duplicado'): ?>
              <script>
                Swal.fire('Error', 'El código del programa ya existe.', 'error');
              </script>
            <?php endif; ?>
          <?php endif; ?>

        </tr>
      <?php }
          } ?>
  </tbody>

  <tfoot>
    <tr>
      <th>Programa</th>
      <th></th>
    </tr>
  </tfoot>
    </table>
  </div>

  <!-- Vista de tarjetas para móvil -->
  <div class="mobile-cards">
    <?php if ($datfil) {
      foreach ($datfil as $dt) { ?>
        <div class="programa-card">
          <div class="programa-header">
            <div class="programa-title">
              <?= $dt["codpro"]; ?> - <?= $dt["nompro"]; ?>
            </div>
            <div class="programa-version">
              Versión: <?= $dt["verpro"]; ?>
            </div>
          </div>
          
          <div class="programa-details">
            <div class="programa-detail">
              <div class="programa-detail-label">Horas Lectiva</div>
              <div class="programa-detail-value"><?= $dt["horlpro"]; ?></div>
            </div>
            <div class="programa-detail">
              <div class="programa-detail-label">Horas Productiva</div>
              <div class="programa-detail-value"><?= $dt["horppro"]; ?></div>
            </div>
            <div class="programa-detail">
              <div class="programa-detail-label">Créditos Lectiva</div>
              <div class="programa-detail-value"><?= $dt["crelpro"]; ?></div>
            </div>
            <div class="programa-detail">
              <div class="programa-detail-label">Créditos Productiva</div>
              <div class="programa-detail-value"><?= $dt["creppro"]; ?></div>
            </div>
            <div class="programa-detail">
              <div class="programa-detail-label">Tipo</div>
              <div class="programa-detail-value"><?= $dt["tippro"]; ?></div>
            </div>
            <div class="programa-detail">
              <div class="programa-detail-label">Área</div>
              <div class="programa-detail-value"><?= $dt["idare"]; ?></div>
            </div>
          </div>
          
          <div class="programa-actions">
            <a href="home.php?pg=1527&ope=del&codpro=<?= $dt['codpro'] ?>" 
               onclick="return eli(this);" 
               class="programa-action" 
               title="Eliminar">
              <i class="fa-solid fa-trash-can"></i>
              <span>Eliminar</span>
            </a>
            <a href="home.php?pg=1527&ope=edi&codpro=<?= $dt["codpro"]; ?>" 
               class="programa-action" 
               title="Editar">
              <i class="fa-solid fa-pen-to-square"></i>
              <span>Editar</span>
            </a>
            <a href="home.php?pg=1510&codpro=<?= $dt["codpro"]; ?>" 
               class="programa-action" 
               title="Competencias">
              <i class="fa-solid fa-folder-open"></i>
              <span>Competencias</span>
            </a>
          </div>
        </div>
      <?php }
    } ?>
  </div>

<style>
/* Ocultar tarjetas en desktop por defecto */
.mobile-cards {
    display: none;
}

/* Responsive design para vista de programa */
@media (max-width: 768px) {
    /* Título responsive */
    .tit h1 {
        font-size: 2.2rem;
        text-align: center;
    }
    
    /* Formulario responsive */
    .container-form-pages {
        padding: 1rem;
    }
    
    .form-default-pages .row {
        margin: 0;
    }
    
    .form-default-pages .form-group {
        margin-bottom: 1rem;
        padding: 0 0.5rem;
    }
    
    .form-default-pages .col-md-4,
    .form-default-pages .col-md-3,
    .form-default-pages .col-md-2,
    .form-default-pages .col-md-6,
    .form-default-pages .col-md-5,
    .form-default-pages .col-md-12 {
        width: 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    /* Campos táctiles */
    .form-control, .form-select {
        font-size: 1rem;
        padding: 0.75rem;
        min-height: 48px;
        margin-bottom: 0.5rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #00af00;
        box-shadow: 0 0 0 0.2rem rgba(0, 175, 0, 0.25);
    }
    
    /* Labels más legibles */
    .fw-semibold {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    /* Botón de envío */
    .btn-success {
        font-size: 1rem;
        padding: 0.75rem 2rem;
        min-height: 48px;
        width: 100%;
        margin-top: 1rem;
    }
    
    /* Ocultar tabla en móvil */
    #example {
        display: none;
    }
    
    /* Diseño de tarjetas para móvil */
    .mobile-cards {
        display: block;
    }
    
    .programa-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        min-height: 200px;
    }
    
    .programa-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .programa-header {
        border-bottom: 2px solid #00af00;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .programa-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #00af00;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .programa-version {
        font-size: 0.9rem;
        color: #6c757d;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
    
    .programa-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .programa-detail {
        display: flex;
        flex-direction: column;
    }
    
    .programa-detail-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .programa-detail-value {
        font-size: 1.1rem;
        color: #495057;
        font-weight: 600;
    }
    
    .programa-actions {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    
    .programa-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #6c757d;
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 8px;
        min-width: 60px;
    }
    
    .programa-action:hover {
        color: #00af00;
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .programa-action i {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    
    .programa-action span {
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
    }
    
    /* Botones de acción más grandes */
    .fa-2x {
        font-size: 1.5rem !important;
        padding: 0.5rem;
        margin: 0.25rem;
        border-radius: 4px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .fa-2x:hover {
        background-color: #00af00;
        color: white;
        transform: scale(1.1);
    }
    
    /* Texto de la tabla más legible */
    #example td strong {
        font-size: 1rem;
        line-height: 1.4;
    }
    
    #example td small {
        font-size: 0.8rem;
        line-height: 1.3;
        display: block;
        margin-top: 0.5rem;
    }
}

@media (max-width: 576px) {
    /* Título más pequeño */
    .tit h1 {
        font-size: 1.8rem;
    }
    
    /* Formulario más compacto */
    .container-form-pages {
        padding: 0.5rem;
    }
    
    /* Tabla más compacta */
    #example {
        min-width: 500px;
        font-size: 0.8rem;
    }
    
    #example th,
    #example td {
        font-size: 0.75rem;
        padding: 0.5rem 0.25rem;
    }
    
    /* Botones más pequeños pero táctiles */
    .fa-2x {
        font-size: 1.2rem !important;
        padding: 0.4rem;
        margin: 0.2rem;
    }
    
    /* Botón de envío más compacto */
    .btn-success {
        font-size: 0.95rem;
        padding: 0.6rem 1.5rem;
    }
}

@media (max-width: 400px) {
    /* Título extra pequeño */
    .tit h1 {
        font-size: 1.5rem;
    }
    
    /* Tabla muy compacta */
    #example {
        min-width: 450px;
        font-size: 0.75rem;
    }
    
    #example th,
    #example td {
        font-size: 0.7rem;
        padding: 0.4rem 0.2rem;
    }
    
    /* Botones más compactos */
    .fa-2x {
        font-size: 1rem !important;
        padding: 0.3rem;
        margin: 0.1rem;
    }
    
    /* Botón de envío más compacto */
    .btn-success {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
}

/* Mejoras adicionales para tablets */
@media (min-width: 769px) and (max-width: 1024px) {
    .tit h1 {
        font-size: 2.8rem;
    }
    
    .form-default-pages .col-md-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .form-default-pages .col-md-3 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
    
    .form-default-pages .col-md-2 {
        flex: 0 0 25%;
        max-width: 25%;
    }
}
</style>