<?php require_once 'controllers/cpmod.php';
include('controllers/ccanusu.php');
include('controllers/ctots.php');
$votacion = getVotTot(); ?>

<header class="cabecera-sena">
    <div class="cabecera-contenido">
        <section class="cabecera-contendo__bx-logs">
            <img class="logo-sena" src="<?php if ($val)
                echo "img/" . $val[0]['logcof'] ?>" style="image-rendering: inherit;">
                <div class="texto-sena">
                <?php if ($val) {
                $partes = explode(" ", $val[0]['titcof']); ?>
                    <h3>
                        <span class="palabra1"><?= $partes[0] ?></span>
                        <span class="palabra2"><?= $partes[1] ?></span>
                    </h3>
                <?php } ?>
            </div>
        </section>

        <!-- Carrusel -->
        <div class="carrusel-sena">
            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/index1.jpg" class="d-block w-100" alt="Imagen 1">
                    </div>
                    <div class="carousel-item">
                        <img src="img/index2.jpg" class="d-block w-100" alt="Imagen 2">
                    </div>
                    <div class="carousel-item">
                        <img src="img/index3.jpg" class="d-block w-100" alt="Imagen 3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<section class="container-search">
    <div class="container-inp-buscador">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="search" id="buscador" placeholder="Buscar opción..." autocomplete="off">
    </div>
    <div class="position-relative">
        <ul id="resultados">

        </ul>
    </div>
</section>
<section class="home">

    <?php
    $accesosPorModulo = []; // idmod => [accesos]
    foreach ($datAccDir as $fila) {
        $accesosPorModulo[$fila['idmod']][] = $fila;
    }
    $delay = 0.2;
    $step = 0.2;
    if ($datAll) {
        foreach ($datAll as $dt) {
            // Convertir delay a string
            if ($delay < 1) {
                // Ej: 0.2 -> '02'
                $delayNum = str_pad(intval($delay * 10), 2, '0', STR_PAD_LEFT);
                $delayClass = "animate__delay-{$delayNum}s";
            } else {
                // Ej: 1.2 -> '12', 1.4 -> '14', etc.
                $delayNum = str_replace('.', '', number_format($delay, 1));
                $delayClass = "animate__delay-{$delayNum}s";
            }
            $delay += $step;
            $key = array_search($dt['idmod'], $datMod);
            if ($key) {
                $mmod->setIdmod($datMd[$key - 1]["idmod"]);
                $datMus = $mmod->getAllModUsu();
                $mmod->setIdper($datMus[0]["idper"]);
                $datPer = $mmod->getAllPer();
                $pagsXMod = getPagsXMod($dt['idmod'], $_SESSION['idusu'], $_SESSION['idpergen']);
                $modalId = 'myModal' . $dt['idmod'];
                ?>
                <form id="form-<?= $dt['idmod']; ?>" action="mod.php" method="POST"
                    class="formu animate__animated animate__fadeIn animate__delay-02s">
                    <div class="row btnmod bx-mod-main" onclick="this.closest('form').submit();" style="cursor:pointer;">
                        <div class="col bx-hd-mod">
                            <div class="row bx-hd-mod__tit">
                                <div class="bx-circ-lg">
                                    <?php if (file_exists($dt['imgmod'])) { ?>
                                        <img src="<?= $dt['imgmod']; ?>">
                                    <?php } ?>
                                </div>
                                <div class="bx-nom-mod d-flex align-items-center w-auto">
                                    <h5><?= $dt['nommod']; ?></h5>
                                </div>
                                <div class="hd-mod_bsx-ico-edit" onclick="event.stopPropagation();">
                                    <i class="fa fa-solid fa-ellipsis-vertical" data-bs-toggle="modal"
                                        data-bs-target="#<?= $modalId ?>"></i>
                                </div>
                                <?php ?>
                            </div>
                        </div>

                        <!-- Accesos directos -->
                        <div class="col bx-acc-mod">
                            <ul>
                                <?php if (isset($accesosPorModulo[$dt['idmod']])) {
                                    foreach ($accesosPorModulo[$dt['idmod']] as $acceso) { ?>
                                        <li>
                                            <a href="#" class="acceso-directo" data-idpag="<?= $acceso['idpag']; ?>"
                                                data-form="form-<?= $dt['idmod']; ?>">
                                                <i class="acc-ico <?= $acceso['icopag']; ?>"></i> <?= $acceso['nompag']; ?>
                                            </a>
                                        </li>
                                    <?php }
                                } else {
                                    echo "<p class='acc-mod--sin-acc'>Sin accesos directos</p>";
                                } ?>
                            </ul>
                        </div>

                        <div class="col bx-hd-mod ft">
                            <div class="row bx-ft-mod">

                                <!-- VOTASENA INICIO -->
                                <!-- MOSTRAR CANTIDAD DE POSTULADOS UN ADMINISTRADOR -->
                                <?php if ($dt['idmod'] == 1 && $datMus[0]['idper'] == 2) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user-check"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= getCantUsuPer(); ?>
                                        </span>
                                    </div>
                                <?php } ?>

                                <!-- MOSTRAR CANTIDAD POSTUILADOS POR FICHA PARA UN ESTUDIANTE -->
                                <?php if ($dt['idmod'] == 1 && $datMus[0]['idper'] == 4) { ?>
                                    <?php if (!empty($votacion)) {
                                        $datos = $votacion[0];
                                        ?>
                                        <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                            style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                            <i class="fa-solid fa-vote-yea"
                                                style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                            <?php if ($datos['nombre_mas_votado']) { ?>
                                                <span
                                                    style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">
                                                    <?= $datos['nombre_mas_votado'] ?> (<?= $datos['total_votos'] ?>)
                                                </span>
                                            <?php } else { ?>
                                                <span
                                                    style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;"><?= $datos['total_candidatos'] ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>


                                <!-- VOTASENA FINAL -->

                                <!-- E/S INICIO -->
                                <!-- MOSTRAR MOVIMIENTOS DE ENTRADA Y SALIDA PARA UN SUPERADMIN -->
                                <?php if ($dt['idmod'] == 2 && $datMus[0]['idper'] == 6) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= $S[0]['ctn']; ?>
                                        </span>
                                    </div>

                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= $F[0]['ctn']; ?>
                                        </span>
                                    </div>
                                <?php } ?>


                                <!-- MOSTRAR MOVIMIENTOS DE ENTRADA Y SALIDA PARA UN PROFESOR -->
                                <?php if ($dt['idmod'] == 2 && $datMus[0]['idper'] == 7) { ?>
                                    <?php
                                    $total_entradas = 0;
                                    $total_salidas = 0;

                                    if (!empty($movimientos)) {
                                        foreach ($movimientos as $mov) {
                                            if ($mov['tipmin'] == 'I') {
                                                $total_entradas++;
                                            } elseif ($mov['tipmin'] == 'F') {
                                                $total_salidas++;
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= $total_entradas; ?>
                                        </span>
                                    </div>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user-times"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= $total_salidas; ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <!-- E/S FINAL -->

                                <!-- DESERCIONES Y LLAMADOS INICIO -->
                                <!-- TOTAL FICHAR PARA UN ADMINISTRADOR -->
                                <?php if ($dt['idmod'] == 5 && $datMus[0]['idper'] == 24) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-landmark"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= totFics(); ?>
                                        </span>
                                    </div>

                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-calendar-xmark"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #ff7f7f; flex-shrink: 0;"></i>
                                        <span
                                            style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #ff7f7f;"><?= getCantIna(); ?>
                                        </span>
                                    </div>

                                <?php } ?>

                                <!-- DESERCIONES Y LLAMADOS FINAL -->

                                <!-- PRESTAMOS INICIO -->
                                <!-- MOSTRAR TOTAL ELEMENTOS Y ELEMENTOS PRESTADOS PARA UN ADMINISTRADOR -->
                                <?php if ($dt['idmod'] == 6 && $datMus[0]['idper'] == 29) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-box"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= totEle(); ?>
                                        </span>
                                    </div>

                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-list-check"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= totPresEle(); ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <!-- PRESTAMOS FINAL -->


                                <!-- ADMINISTRATIVO INICIO -->

                                <?php if ($dt['idmod'] == 7 && $datMus[0]['idper'] == 28) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-person"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= totInst(); ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <!-- ADMINISTRATIVO FINAL -->

                                <!-- COMPLEMENTARIOS INICIO -->

                                <!-- COMPLEMENTARIOS FINAL -->

                                <!-- MATRICULAS INICIO -->

                                <!-- MATRICULAS FINAL -->

                                <!-- MESA DE AYUDA INICIO -->

                                <!-- MESA DE AYUDA FINAL -->


                                <!-- HORARIOS Y AGENDAS INICIO -->
                                <!-- MOSTRAR ESTUDIANTES QUE HAN LLEGADO Y FALTAN POR LLEGAR PARA UN PROFESOR -->
                                <?php if ($dt['idmod'] == 3 && $datMus[0]['idper'] == 17) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= cantUsuFichaById($_SESSION['idusu']); ?>
                                        </span>
                                    </div>

                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-user"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= usuFalt($_SESSION['idusu']); ?>
                                        </span>
                                    </div>
                                <?php } ?>

                                <!-- MOSTRAR TOTAL DE FICHAS Y TOTAL DE INSTRUCTORES PARA UN ADMINISTRADOR -->
                                <?php if ($dt['idmod'] == 12 && $datMus[0]['idper'] == 21) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-landmark"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= totFics(); ?>
                                        </span>
                                    </div>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-person"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fd87f; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fd87f;">
                                            <?= totInst(); ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <!-- HORARIOS Y AGENDAS FINAL -->

                                <!-- ETAPA PRODUCTIVA INICIO -->
                                <?php if ($dt['idmod'] == 13 && $datMus[0]['idper'] == 31) { ?>
                                    <div class="col-auto sp-notf d-flex align-items-center justify-content-center"
                                        style="gap: 5px; min-width: fit-content; max-width: 300px; overflow: hidden; padding: 3px 10px; border-radius: 6px; background-color: rgba(255, 255, 255, 0.1);">
                                        <i class="fa-solid fa-book"
                                            style="color: white; font-size: 17px; text-shadow: 1px 1px 4px #7fb3d8; flex-shrink: 0;"></i>
                                        <span style="font-size: 17px; color: white; text-shadow: 1px 1px 4px #7fb3d8;">
                                            <?= getCantBit(); ?>
                                        </span>
                                    </div>
                                <?php } ?>

                                <!-- ETAPA PRODUCTIVA FINAL -->

                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="idmod" value="<?= $dt['idmod']; ?>">
                    <input type="hidden" name="pg" id="pg-<?= $dt['idmod']; ?>" value="<?= $datPer[0]['idpag']; ?>">
                    <input type="hidden" name="idper" value="<?= $datPer[0]['idper']; ?>">
                    <input type="hidden" name="nomper" value="<?= $datPer[0]['nomper']; ?>">
                    <input type="hidden" name="ope" value="dircc">
                </form>

                <!-- Modal único por módulo -->
                <div class="modal" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h3 class="modal-title">Accesos Directos - <?= htmlspecialchars($dt['nommod']) ?></h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="controllers/cpmod.php" class="form-acces-dir" method="POST">
                                <div class="modal-body">
                                    <p>Max. 3 Accesos Directos</p>
                                    <input type="hidden" value="<?= $datPer[0]['idper']; ?>" name="idper">
                                    <div class="row">
                                        <?php if ($pagsXMod && is_array($pagsXMod)) {
                                            foreach ($pagsXMod as $pag) { ?>
                                                <div class="dpag form-group col-md-6" style="margin-bottom: 0px;">
                                                    <i class="<?= htmlspecialchars($pag['icopag']) ?>" style="color: #117f09;"></i>
                                                    <input type="checkbox" name="mdl[]" value="<?= $pag['idpag'] ?>"
                                                        id="<?= $pag['idpag'] ?>inp" <?php if ($pag['es_acceso'] != 0)
                                                              echo "checked" ?>>
                                                        <label for="<?= $pag['idpag'] ?>inp"><?= htmlspecialchars($pag['nompag']) ?></label>
                                                </div>
                                            <?php }
                                        } else
                                            echo "<p class='acc-mod--sin-acc'>No hay páginas disponibles</p>"; ?>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <input type="hidden" value="savepxp" name="opera">
                                    <input type="hidden" name="idmod" value="<?= $dt['idmod']; ?>">
                                    <input type="submit" class="btn btn-success" value="Guardar">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="btnmod2" style="display:none;">
                    <?php if (file_exists($dt['imgmod'])) { ?>
                        <img src="<?= $dt['imgmod']; ?>" width="100px">
                    <?php } ?>
                    <br>
                    <?= $dt['nommod']; ?>
                </div>
                </form>
            <?php }
        }
    } ?>
</section>
<?php require_once "views/vfooter.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const accesos = document.querySelectorAll(".acceso-directo");

        accesos.forEach(acceso => {
            acceso.addEventListener("click", function (e) {
                e.preventDefault();

                // Tomar el idpag y el form correspondiente
                const idpag = this.getAttribute("data-idpag");
                const formId = this.getAttribute("data-form");
                const form = document.getElementById(formId);

                if (form) {
                    const pgInput = form.querySelector("input[name='pg']");
                    if (pgInput) {
                        pgInput.value = idpag; // asignar idpag del acceso
                    }
                    form.submit(); // enviar formulario
                }
            });
        });
    });

    
</script>