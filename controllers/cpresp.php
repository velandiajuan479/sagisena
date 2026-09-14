<?php
  require_once('models/mpresp.php');
  require_once('models/mpraul.php');

  $mpraul = new Mpraul();
  $mpresp = new Mpresp();

  $idpresp = isset($_REQUEST['idpresp']) ? $_REQUEST['idpresp'] : NULL;
  $idaul = isset($_REQUEST['idaul']) ? $_REQUEST['idaul'] : NULL;
  $idusu = isset($_REQUEST['idusu']) ? $_REQUEST['idusu'] : NULL;
  $apresp = isset($_POST['apresp']) ? $_POST['apresp'] : NULL;
  $whoapr = isset($_REQUEST['whoapr']) ? $_REQUEST['whoapr'] : NULL;
  $date = date('Y-m-d H:i:s');
  $fecini = isset($_POST['fecini']) ? $_POST['fecini'] : $date;
  $fecfin = isset($_REQUEST['fecfin']) ? $_REQUEST['fecfin'] : NULL;
  $horini = isset($_POST['horini']) ? $_POST['horini'] : NULL;
  $horfin = isset($_POST['horfin']) ? $_POST['horfin'] : NULL;
  $incpre = isset($_REQUEST['incpre']) ? $_REQUEST['incpre'] : NULL;

  $ope = isset($_REQUEST['ope']) ? $_REQUEST['ope'] : NULL;
  $dtOne = NULL;

  $mpresp->setIdpresp($idpresp);
  if ($ope == 'save' && $idaul) {
    $mpresp->setIdaul($idaul);
    $mpresp->setIdusu($_SESSION['idusu']);
    $mpresp->setFecini($fecini);
    $mpresp->setFecfin($fecfin);
    $mpresp->setHorini($horini);
    $mpresp->setHorfin($horfin);
    $mpresp->setIncpre(1);
    $eventApro = $mpresp->verOtherSols();
    if (empty($eventApro) || (isset($eventApro) && $eventApro[0]['idusu'] == $_SESSION['idusu'])) {
      if ($idpresp) $mpresp->edit();
      else $mpresp->save();
    } else {
      echo '<script> alert("Espacio ya ocupado"); </script>';
    }
    echo '<script> window.location.href = "home.php?pg='.$pg.'"; </script>';
  }

  if ($ope == 'aprSpace' || $ope == 'noApro' && $idpresp && $idusu && $idaul && $whoapr && $fecfin) {
    $mpresp->setIdusu($idusu);
    $mpresp->setIdaul($idaul);
    $mpresp->setFecfin($fecfin);
    $mpresp->setWhoapr($whoapr);
    if ($ope == 'aprSpace') {
      $mpresp->setApresp(1);
    } else if ($ope == 'noApro') {
      $mpresp->setApresp(0);
    }
    $mpresp->editApro();
    $dtEsp = $mpresp->getOne();
    $mpresp->setApresp(0);
    $mpresp->setFecfin($dtEsp[0]['fecfin']);
    $mpresp->setHorini($dtEsp[0]['horini']);
    $mpresp->setHorfin($dtEsp[0]['horfin']);
    $mpresp->editAprEvents();
    echo '<script> window.location.href = "home.php?pg='.$pg.'"; </script>';
  }

  if ($ope == 'eventInc' && $idpresp) {
    $mpresp->setIdpresp($idpresp);
    $mpresp->setIncpre(0);
    $mpresp->setWhoapr($_SESSION['idusu']);
    $mpresp->setApresp(0);
    $mpresp->editIncpre();
  }

  /* if ($ope == 'edit' && $idpresp) $dtOne = $mpresp->getOne(); */
  if ($ope == 'del' && $idpresp) {
    $mpresp->del();
    echo '<script> window.location.href = "home.php?pg='.$pg.'"; </script>';
  } 

  if ($ope == 'dessol' && $idusu && $idaul) {
    $mpresp->setIdusu($idusu);
    $mpresp->setIdaul($idaul);
    $mpresp->delSol();
    echo '<script> window.location.href = "home.php?pg='.$pg.'"; </script>';
  }

  $dtAuls = $mpresp->getAulEsp();

  // Cancelacion automatica si no se toma alguna accion por el admin
  function timeOff($idpresp) {
    $mpresp = new Mpresp();
    $mpresp->setIdpresp($idpresp);
    $oneEvent = $mpresp->getOne();
    try {
      if ($oneEvent) {
        $eventFecini = new DateTime($oneEvent[0]['fecini']);
        $timeOff = new DateTime();
        $timeOff->format('Y-m-d');
        if ($eventFecini >= $timeOff) {
          $mpresp->setIdusu($oneEvent[0]['idusu']);
          $mpresp->setIdaul($oneEvent[0]['idaul']);
          $mpresp->setWhoapr($_SESSION['idusu']);
          $mpresp->setApresp(0);
          $mpresp->editApro();
        }
      } else {
        echo 'No se encuentra el evento';
      }
    } catch (Exception $e) {
      echo 'Error: '.$e->getMessage();
      exit;
    }
  }

  function spaceModal($idaul, $nomaul, $idpresp, $pg) {
    $mpresp = new Mpresp();
    $mpresp->setIdpresp($idpresp);
    $mpresp->setIdaul($idaul);
    $dtOne = $mpresp->getOne();
    $html = '';
    $html .= '<div class="modal" id="space'.$idaul.'" tabindex="-1" aria-labelledby="space'.$idaul.'Label" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">'.$nomaul.'</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form method="POST" action="home.php?pg='.$pg.'">
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="fecfin">Fecha</label>
                          <input type="date" id="fecfin" name="fecfin" class="form-control" min="" value="'; if ($dtOne) $html .= $dtOne[0]['fecfin']; $html .= '" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label foru0="horini">Hora inicio</label>
                          <input type="time" id="horini" name="horini" class="form-control" value="'; if ($dtOne) $html .= $dtOne[0]['horini']; $html .= '" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="horfin">Hora fin</label>
                          <input type="time" id="horfin" name="horfin" class="form-control" value="'; if ($dtOne) $html .= $dtOne[0]['horfin']; $html .= '" required>
                        </div>
                        <div class="form-group col-md-12">
                          <br>
                          <input type="hidden" name="idpresp" value="'; if ($dtOne) $html .= $dtOne[0]['idpresp']; $html .= '">
                          <input type="hidden" name="idaul" value="'.$idaul.'">
                          <input type="hidden" name="ope" value="save">
                          <input type="submit" class="btn btn-primary" value="Solicitar">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
    $html .= '<script>
              const fecini = document.getElementById("fecini");';
              $today = new DateTime();
              $today = $today->format('Y-m-d');
    $html .= 'fecini.setAttribute("min", '.$today.');
            </script>';
    return $html;
  }

  function solEvent($idaul, $nomaul, $pg) {
    $mpresp = new Mpresp();
    $html = '';
    $html .= '<div class="modal" id="solE'.$idaul.'" tabindex="-1" aria-labelledby="solE'.$idaul.'Label" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">'.$nomaul.'</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form method="POST" action="home.php?pg='.$pg.'">
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="fecfin">Fecha</label>
                          <input type="date" id="fecfin" name="fecfin" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                          <label foru0="horini">Hora inicio</label>
                          <input type="time" id="horini" name="horini" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="horfin">Hora fin</label>
                          <input type="time" id="horfin" name="horfin" class="form-control">
                        </div>
                        <div class="form-group col-md-12">
                          <br>
                          <input type="hidden" name="idaul" value="'.$idaul.'">
                          <input type="hidden" name="ope" value="save">
                          <input type="submit" class="btn btn-primary" value="Solicitar">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
    $html .= '<script>
              const fecini = document.getElementById("fecini");';
              $today = new DateTime();
              $today = $today->format('Y-m-d');
    $html .= 'fecini.setAttribute("min", '.$today.');
            </script>';
    return $html;
  }

  function calPorEsp($idaul, $pg) {
    $mpresp = new Mpresp();
    $month = isset($_GET['month']) ? $_GET['month'] : date('n');
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
    if ($month < 1 || $month > 12) $month = date('n');
    if ($year < 2020 || $year > 2030) $year = date('Y');

    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $totDays = date('t', $firstDay);
    $currentDay = date('j');
    $currentMonth = date('n');
    $currentYear = date('Y');
    $dayWeek = date('w', $firstDay);
    $week = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) {
      $prevMonth = 12;
      $prevYear--;
    }
    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
      $nextMonth = 1;
      $nextYear++;
    }

    $cal = '';
    $cal .= '<div class="calendar-container">
              <div class="calendar-header">
                <div class="calendar-title">
                  <span class="month-year">'.$months[date('n', $firstDay) - 1] . ' ' . date('Y', $firstDay).'</span>
                </div>
                <div class="calendar-nav">
                  <a href="home.php?pg='.$pg.'&month='.$prevMonth.'&year='.$prevYear.'">Anterior</a>
                  <a href="home.php?pg='.$pg.'&month='.date('n').'&year='.date('Y').'">Hoy</a>
                  <a href="home.php?pg='.$pg.'&month='.$nextMonth.'&year='.$nextYear.'">Siguiente</a>
                </div>
              </div>
              <div class="calendar-week">';
      foreach ($week as $day):
        $cal .= '<div>'.$day.'</div>';
      endforeach;
      $cal .= '</div>
              <div class="calendar-days">';
      for ($i = 0; $i < $dayWeek; $i++):
        $cal .= '<div class="day empty"></div>';
      endfor;
      for ($day = 1; $day <= $totDays; $day++):
        $isPast = ($year < $currentYear) || ($year == $currentYear && $month < $currentMonth) || ($year == $currentYear && $month == $currentMonth && $day < $currentDay);
        $isCurrent = ($year == $currentYear && $month == $currentMonth && $day == $currentDay);
        if ($month < 10) $month = '0'.$month;
        if ($day < 10) $day = '0'.$day;
        $dateFecfin = $year.'-'.$month.'-'.$day;
        $mpresp->setFecfin($dateFecfin);
        $mpresp->setIdaul($idaul);
        $hasEvent = $mpresp->eventOccuped();
        $class = 'day ';
        if ($isPast) $class .= ' past ';
        if ($isCurrent) $class .= ' current ';
        if ($hasEvent) $class .= ' event ';
          $cal .= '<div class="'.$class.'">
                    <div class="day-number">'.$day.'</div>
                    <div class="event">';
            if ($hasEvent) { foreach ($hasEvent as $hs) {
              $cal .= '<p>'.$hs['undocusu'].'<br>'.$hs['unomusu'].'</p>
                      <p>Desde '.$hs['horini'].'<br>
                      Hasta '.$hs['horfin'].'</p>';
              $dateCurrent = new DateTime();
              $dateCurrent = $dateCurrent->format('Y-m-d');
            if ($_SESSION['idper'] == 29 && $hs['fecfin'] > $dateCurrent) {
              $cal.= '<a href="home.php?pg='.$pg.'&idpresp='.$hs['idpresp'].'&ope=eventInc">Incumplido</a>';
              }
              $cal .= '<hr>';
            }} else {
              $cal .= '<span>Libre</span>';
            }
            $cal .= '</div>
                  </div>';
      endfor;
      $cal .= '</div>
            </div>
            <style>
              .calendar-container {
                background: white;
                width: 70%;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
              }

              .calendar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
              }

              .calendar-title {
                font-size: 24px;
                font-weight: bold;
                color: #333;
              }

              .calendar-nav {
                display: flex;
                gap: 10px;
              }

              .calendar-nav a {
                text-decoration: none;
                padding: 8px 15px;
                background-color: #4caf50;
                color: white;
                border-radius: 5px;
                transition: background-color 0.3s;
              }

              .calendar-nav a:hover {
                background-color: #45a049;
              }

              .calendar-week {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                text-align: center;
                font-weight: bold;
                margin-bottom: 10px;
              }

              .calendar-days {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
              }

              .day {
                padding: 10px;
                border-radius: 5px;
                text-align: center;
                min-height: 60px;
                background-color: #f9f9f9;
                position: relative;
              }

              .day.empty {
                background-color: transparent;
              }

              .day.past {
                background-color: #e0e0e0;
                color: #999;
              }

              .day.current {
                background-color: #e3f2fd;
                font-weight: bold;
              }

              .day.event {
                width: 150px;
                background-color: #fff8e1;
                border: 1px solid #ffd54f;
                height: 140px;
                overflow-y: auto;
              }

              .day.event hr {
                border: 1px solid #000;
              }

              .day.event a {
                background: #000;
                padding: 4px 7px;
                text-transform: uppercase;
                color: #fff;
                border-radius: 0.5rem;
                transition: all 0.5s ease;
              }

              .day.event a:hover {
                background: #fff;
                color: #000;
              }

              .event-title {
                font-size: 12px;
                margin-top: 5px;
                color: #e65100;
                font-weight: bold;
              }

              .day-number {
                font-size: 18px;
              }

              .month-year {
                color: #666;
              }
            </style>';
    return $cal;
  }

  function viewSols($idaul, $nomaul, $pg) {
    $mpresp = new Mpresp();
    $mpresp->setIdaul($idaul);
    $dtSols = $mpresp->getSolsAul();
    $txt = '';
    $txt .= '<div class="modal fade" id="ocu'.$nomaul.'" tabindex="-1" role="dialog" aria-labelledby="ocu'.$nomaul.'CenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="ocu'.$nomaul.'LongTitle">'.$nomaul.' Fecha '.$dtSols[0]['fecfin'].'</h5>
                    </div>
                    <form action="home.php?pg='.$pg.'" method="post" class="formModal">
                      <div class="modal-body">
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <style>
                .modal.fade .modal-content .modal-header h5 {
                  text-transform: uppercase;
                  justify-items: center !important;
                }

                .modal.fade .modal-content .modal-body .row {
                  display: flex;
                  gap: 10px;
                  justify-content: center;
                }

                .modal.fade .modal-content .modal-body .row .form-group {
                  box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.4);
                  padding: 10px;
                  border-radius: 1rem;
                }

                .modal.fade .modal-content .modal-body .row .form-group p {
                  font-size: 12px;
                }

                .modal.fade .modal-content .modal-body .row .form-group a {
                  background: #000;
                  color: #fff;
                  text-transform: uppercase;
                  padding: 5px 15px;
                  border-radius: 1rem;
                }

                .modal.fade .modal-content .modal-footer button {
                  justify-items: center !important;
                }
              </style>';
    return $txt;
  }