<? if(sizeof($list)):?>
  <div class="video-group">
    <? foreach($list as $key => $video) :?>
      <a href="#video-<?= $video->getId() ?>" class="video-item inlinePopupJS" data-keys="<?= implode(",", $list->getPrimaryKeys()) ?>" data-id="<?= $video->getId() ?>">
        <span class="video-img"><?/*js-video-item*/?>
          <img src="/uploads/photovideo/<?= $video->getPhoto() ?>" alt="<?= $video->getName() ?>">
        </span>
        <span class="video-head"<?/* data-keys="<?= implode(",", $list->getPrimaryKeys()) ?>" data-id="<?= $video->getId() ?>"*/?>>
          <?= $video->getName() ?>
        </span>
      </a>
      <div id="video-<?= $video->getId() ?>" class="video-popup-wrapper mfp-hide popup">
        <div class="video-container">
          <?= $video->getVideoyoutube() ?>
        </div>
      </div>
    <? endforeach ?>
  </div>
<? endif ?>
