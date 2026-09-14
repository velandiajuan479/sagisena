<?php include('controllers/ccanusu.php'); ?>

<div class="body2">
  <h1 class="es-header">
    <i class='<?= $icono; ?>'></i> Entradas y Salidas
  </h1>

  <div class="row">
    <div class="col-md-1">&nbsp;</div>

    <div class="col-md-3 text-center">
      <div>
        <br><br><span class="es-section__title">Sena</span>

        <div class="alert alert-light elemo es-item" role="alert">
          <i class="fa-solid fa-laptop es-icon"></i>
          <?php cantidad(72, "I"); ?>
          <i class="fa-solid fa-arrow-right"></i> /
          <i class="fa-solid fa-arrow-left"></i>
          <?php cantidad(72, "S"); ?>
        </div>

        <div class="alert alert-light elemo es-item" role="alert">
          <i class="fa-solid fa-tablet es-icon"></i>
          <?php cantidad(74, "I"); ?>
          <i class="fa-solid fa-arrow-right"></i> /
          <i class="fa-solid fa-arrow-left"></i>
          <?php cantidad(74, "S"); ?>
        </div>

        <div class="alert alert-light elemo es-item" role="alert">
          <i class="fa-solid fa-screwdriver-wrench es-icon"></i>
          <?php cantidad(80, "I"); ?>
          <i class="fa-solid fa-arrow-right"></i> /
          <i class="fa-solid fa-arrow-left"></i>
          <?php cantidad(80, "S"); ?>
        </div>
      </div>
    </div>

    <div class="col-md-4 text-center">
      <div class="card elemo2 es-summary">
        <i class="fa-solid fa-users fa-8x es-summary__icon"></i><br><br>
        <div class="card-body">
          <h3 class="card-title">
            <?= str_pad($S[0]['ctn'], 4, "0", STR_PAD_LEFT); ?>
            <i class="fa-solid fa-arrow-right"></i><br>
            <i class="fa-solid fa-arrow-left"></i>
            <?= str_pad($F[0]['ctn'], 4, "0", STR_PAD_LEFT); ?>
          </h3>
        </div>
      </div>
    </div>

    <div class="col-md-3 text-center">
      <div>
        <br><br><span class="es-section__title">Personal</span>

        <div class="alert alert-light elemo es-item" role="alert">
          <i class="fa-solid fa-laptop es-icon"></i>
          <?php cantidad(71, "I"); ?>
          <i class="fa-solid fa-arrow-right"></i> /
          <i class="fa-solid fa-arrow-left"></i>
          <?php cantidad(71, "S"); ?>
        </div>

        <div class="alert alert-light elemo es-item" role="alert">
          <i class="fa-solid fa-tablet es-icon"></i>
          <?php cantidad(73, "I"); ?>
          <i class="fa-solid fa-arrow-right"></i> /
          <i class="fa-solid fa-arrow-left"></i>
          <?php cantidad(73, "S"); ?>
        </div>

        <div class="alert alert-light elemo es-item" role="alert">
          <i class="fa-solid fa-screwdriver-wrench es-icon"></i>
          <?php cantidad(79, "I"); ?>
          <i class="fa-solid fa-arrow-right"></i> /
          <i class="fa-solid fa-arrow-left"></i>
          <?php cantidad(79, "S"); ?>
        </div>
      </div>
    </div>

    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-1">&nbsp;</div>

    <div class="col-md-3">
      <div class="alert alert-light elemo elemo3 es-item es-item--vehicle" role="alert">
        <i class="fa-solid fa-bicycle es-icon"></i>
        <?php cantidad(77, "I"); ?>
        <i class="fa-solid fa-arrow-right"></i> /
        <i class="fa-solid fa-arrow-left"></i>
        <?php cantidad(77, "S"); ?>
      </div>
    </div>

    <div class="col-md-4">
      <div class="alert alert-light elemo elemo3 es-item es-item--vehicle" role="alert">
        <i class="fa-solid fa-motorcycle es-icon"></i>
        <?php cantidad(76, "I"); ?>
        <i class="fa-solid fa-arrow-right"></i> /
        <i class="fa-solid fa-arrow-left"></i>
        <?php cantidad(76, "S"); ?>
      </div>
    </div>

    <div class="col-md-3">
      <div class="alert alert-light elemo elemo3 es-item es-item--vehicle" role="alert">
        <i class="fa-solid fa-car es-icon"></i>
        <?php cantidad(75, "I"); ?>
        <i class="fa-solid fa-arrow-right"></i> /
        <i class="fa-solid fa-arrow-left"></i>
        <?php cantidad(75, "S"); ?>
      </div>
    </div>

    <div class="col-md-1">&nbsp;</div>
  </div>

</div>

</div>

<script type="text/javascript">
  setInterval(recargar, 40000);
  function recargar() {
    // document.myForm.submit()
    window.location.href = 'home.php?pg=1405';
  }
</script>