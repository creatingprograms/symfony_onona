<? if(is_array($breadcrumbs) && sizeof($breadcrumbs)) :?>
    <div class="breadcrumbs wrapper" itemscope itemtype="https://schema.org/BreadcrumbList">
      <? foreach ($breadcrumbs as $k => $crumb) : ?>

        <? if (isset($crumb['link'])) : ?>
            <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
              <? $lastLink=$crumb['link']; ?>
                <a href="<?= mb_strtolower($crumb['link'])?>" itemprop="item" href="<?= mb_strtolower($crumb['link'])?>">
                  <span itemprop="name"><?= $crumb['text'] ?></span>
                </a>
                <meta itemprop="position" content="<?= $k + 1 ?>" />
            </span>
        <? else : ?>
            <span><?= $crumb['text'] ?></span>
        <? endif ?>

      <? endforeach ?>
    </div>
<? endif ?>
