<?php

use Illuminate\Support\Facades\Date;

  require_once('models/mpresp.php');

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
?>

<div class="calendar-container">
  <div class="calendar-header">
    <div class="calendar-title">
      <span class="month-year"><?php echo $months[date('n', $firstDay) - 1] . ' ' . date('Y', $firstDay); ?></span>
    </div>
    <div class="calendar-nav">
      <a href="home.php?pg=<?=$pg;?>&month=<?=$prevMonth;?>&year=<?=$prevYear;?>">Anterior</a>
      <a href="home.php?pg=<?=$pg;?>&month=<?=date('n');?>&year=<?=date('Y');?>">Hoy</a>
      <a href="home.php?pg=<?=$pg;?>&month=<?=$nextMonth;?>&year=<?=$nextYear;?>">Siguiente</a>
    </div>
  </div>
  <div class="calendar-week">
    <?php foreach ($week as $day): ?>
    <div><?=$day;?></div>
    <?php endforeach; ?>
  </div>
  <div class="calendar-days">
    <?php for ($i = 0; $i < $dayWeek; $i++): ?>
      <div class="day empty"></div>
    <?php endfor; 
    for ($day = 1; $day <= $totDays; $day++):
      $isPast = ($year < $currentYear) || ($year == $currentYear && $month < $currentMonth) || ($year == $currentYear && $month == $currentMonth && $day < $currentDay);
      $isCurrent = ($year == $currentYear && $month == $currentMonth && $day == $currentDay);
      if ($month < 10) $month = '0'.$month;
      if ($day < 10) $day = '0'.$day;
      $dateFecfin = $year.'-'.$month.'-'.$day;
      $mpresp->setFecfin($dateFecfin);
      $hasEvent = $mpresp->eventOccuped();
      $class = 'day ';
      if ($isPast) $class .= ' past ';
      if ($isCurrent) $class .= ' current ';
      if ($hasEvent) $class .= ' event '; ?>
      <div class="<?=$class;?>">
        <div class="day-number"><?=$day;?></div>
        <div class="event"> xxxx
        <?php if ($hasEvent) { foreach ($hasEvent as $e) { ?>
          <h4><strong><?=$e['nomaul'];?></strong></h4>
          <p><?=$e['ndocusu'].'<br>'.$e['nomusu'];?></p>
          <p>Desde <?=$e['horini'].'<br>
          Hasta '.$e['horfin'];?></p>
          <?php
            $dateCurrent = new DateTime();
            if ($_SESSION['idper'] == 29 && $e['fecini'] > $dateCurrent->format('Y-m-d')) { 
          ?>
          <a href="home.php?pg=<?=$pg;?>&idpresp=<?=$e['idpresp'];?>&incpre=0&whoapr=<?=$_SESSION['idusu'];?>&ope=eventInc">Incumplido</a>
          <?php } ?>
        <?php }} ?>
        </div>
      </div>
    <?php endfor; ?>
  </div>
</div>

<style>
  .calendar-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
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
    padding: 15px;
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
</style>