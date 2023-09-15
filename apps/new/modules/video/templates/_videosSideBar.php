<? if(sizeof($arrayTags)) :?>
<?/*<pre><?=print_r($arrayTags)?></pre>*/?>
<div class="filter-video">
  <div class="wrap-show-count">
    <div class="show-count">
      <div class="show-block">
        <span></span>
        <svg>
          <use xlink:href="#single-arrow"></use>
        </svg>
      </div>
      <div class="block-number">
        <a href="/video" <?= $active=='' ? 'class="active"' : ''?>>Все</a>
        <? foreach ($arrayTags as$nameTag => $countVideo) :?>
          <a <?= $active==$nameTag ? 'class="active" ' : '' ?>href="/video/<?=$nameTag?>"><?= $nameTag ?></a>
        <? endforeach ?>
      </div>
    </div>
  </div>
</div>
<? endif ?>
