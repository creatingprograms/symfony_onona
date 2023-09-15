<? if(sizeof($arrayTags)) :?>
<?/*<pre><?=print_r($arrayTags)?></pre>*/?>
  <aside class="sidebar -video">
    <div class="sidebar-up-but">
      <a href="#pop-1" class="but">
      все видео
    </a>
    </div>
    <div class="sidebar-video" data-upblock="#pop-1">
      <div class="sidebar-video-h">
        Он и Она | TUBE
      </div>
      <ul class="sidebar-video-nav">
        <? foreach ($arrayTags as$nameTag => $countVideo) :?>
          <li>
            <a <?= $active==$nameTag ? 'class="active" ' : '' ?>href="/video/<?=$nameTag?>"><?= $nameTag ?></a>
          </li>
        <? endforeach ?>
      </ul>
    </div>
  </aside>
<? endif ?>
