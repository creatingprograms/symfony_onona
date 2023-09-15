<? if(is_object($coupon)) :?>
<?
  $time=strtotime($coupon->getEndaction());
  $current=time();
?>
  <div class="footer-bot" style="position: fixed; bottom: 0; width: 100%; margin: 0 auto; background: #eeb9b8; z-index: 111;">
    <div class="wrapper wrapper-promo">
      <a href="<?= $coupon->getLink() ?>"><?= $coupon->getText() ?></a>
      <b class="timeAction" id="timeAction" data-time="<?= date('r', $time)?>">
      <?/*<b class="timeAction" id="timeAction" data-time="Wed Feb 15 2020 18:00:42 GMT+0300 (MSK)">*/?>
        <?$time-=$current?>
        <span class="hours"><?= round($time / 3600) ?></span>:
        <span class="minut"><?= date('i', $time) ?></span>:
        <span class="sec"><?= date('s', $time) ?></span>
      </b>
    </div>
  </div>
<? endif ?>
