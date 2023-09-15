<? if(sizeof($list)):?>
  <div class="video-list">
    <? foreach($list as $key => $video) :?>
      <a class="video-item inlinePopupJS" href="#video-<?= $video->getId() ?>" data-keys="<?= implode(",", $list->getPrimaryKeys()) ?>" data-id="<?= $video->getId() ?>">
        <div class="video-item__img" style="background-image: url('/uploads/photovideo/<?= $video->getPhoto() ?>')">
          <? if($video->getTiming()):?>
            <div class="video-item__time"><?=$video->getTiming()?></div>
          <? endif ?>
        </div>
        <div class="video-item__title"><?= $video->getName() ?></div>
        <? if( $video->getSubname()):?>
          <div class="video-item__text"><?=$video->getSubname()?></div>
        <? endif ?>
        <div class="video-item__date">
          <?
            $days=round((time()-strtotime($video->getCreatedAt()))/24/60/60);
            if($days>365) $dateStr=ILTools::getWordForm(round($days/365), 'год');
            elseif($days > 40*7) $dateStr=ILTools::getWordForm(round($days/31), 'месяц');
            elseif($days > 7) $dateStr=ILTools::getWordForm(round($days/7), 'недел');
            else $dateStr=ILTools::getWordForm(round($days), 'день');
          ?>

          <?= $dateStr.' назад' ?>
        </div>

        <div id="video-<?= $video->getId() ?>" class="video-popup-wrapper mfp-hide popup">
          <div class="video-container">
            <?= $video->getVideoyoutube() ?>
          </div>
        </div>
      </a>
    <? endforeach ?>
  </div>

<? endif ?>
