<div class="sidebar-brend articles-pre">
    <div class="sidebar-brend-h">
      Энциклопедия <br>секса
    </div>
    <div class="sidebar-brend-link">
      <? foreach ($list as $article):?>
      <?
        $precontent=$article->getPrecontent();
        if(mb_strlen($precontent)>270) {
          $precontent=mb_strcut($precontent, 0, 270, 'utf-8').'...';
        }
      ?>
        <div class="sidebar-brend-link-row">
            <a href="/sexopedia/<?= $article->getSlug() ?>" class="sidebar-brend-link-item">
              <?= $article->getName() ?>
            </a>
            <div class="articles-pre_text"><?= $precontent ?></div>
        </div>
      <? endforeach ?>
      <a href="/sexopedia" class="sidebar-brend-link-all">Все статьи &gt;&gt;</a>
    </div>
  </div>
