<?
  $isFirstPage=$isLastPage=false;
  if ($pager->getPage() > 1 || isset($_GET['page'])) slot('canonical', $baselink);
  if ($pager->getPage() == 1)
    $isFirstPage=true;
  if ($pager->getPage() == count($pager->getLinks(2000)))
    $isLastPage=true;

?>
<?if ($show_more && !$isLastPage) :?>
  <div class="more js-show-more<?=$is_catalog ? '-catalog' : ''?>" data-page="<?= $pager->getPage()+1 ?>" data-url="<?= $baselink ?>" data-options="<?= $sortingUrl ?>" id="js-show-more-catalog">
    <a href="#">Показать  еще  <svg><use xlink:href="#arrowMoreIcon"></use></svg></a>
  </div>
<? endif ?>
<? if($numbers) :?>
  <div class="pag-line">
    <div class="pag<?=$is_catalog ? ' js-pag-catalog' : ''?>">
      <a data-page="1" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page=1'. $sortingUrl ?>" class="pag-first <?=$isFirstPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#double-arrow" />
        </svg>
      </a>
      <a data-page="<?=$pager->getPage() - 1?>" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page='.($pager->getPage() - 1). $sortingUrl ?>" class="pag-prev <?=$isFirstPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow" />
        </svg>
      </a>

      <?php foreach ($pager->getLinks(5) as $page): ?>
        <a data-page="<?= $page ?>" href="<?= $baselink.'?page='.$page.$sortingUrl ?>" class="pag-number<?= $page == $pager->getPage() ? ' active' : "" ?>"><?= $page ?></a>
      <?php endforeach; ?>

      <a data-page="<?=$pager->getPage() + 1?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.($pager->getPage() + 1). $sortingUrl ?>" class="pag-next <?=$isLastPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow" />
        </svg>
      </a>
      <a data-page="<?=count($pager->getLinks(2000))?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.count($pager->getLinks(2000)). $sortingUrl ?>" class="pag-last <?=$isLastPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#double-arrow" />
        </svg>
      </a>
    </div>
  </div>
<? endif ?>
