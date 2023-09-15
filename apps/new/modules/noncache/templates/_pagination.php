<?
  $isFirstPage=$isLastPage=false;
  $lastPage=count($pager->getLinks(2000));
  if ($pager->getPage() > 1 || isset($_GET['page'])) slot('canonical', $baselink);
  if ($pager->getPage() == 1)
    $isFirstPage=true;
  if ($pager->getPage() == $lastPage)
    $isLastPage=true;

?>
<?if ($show_more && !$isLastPage) :?>
  <div class="more js-show-more<?=$is_catalog ? '-catalog' : ''?>" data-page="<?= $pager->getPage()+1 ?>" data-url="<?= $baselink ?>" data-options="<?= $sortingUrl ?>" id="js-show-more-catalog">
    <a href="#">Показать  еще  <svg><use xlink:href="#arrowMoreIcon"></use></svg></a>
  </div>
<? endif ?>
<? if($numbers) :?>
  <div class="pag-line pag-line_mod <?= $class ?>">
    <div class="pag">
      <a data-page="<?=$pager->getPage() - 1?>" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page='.($pager->getPage() - 1). $sortingUrl ?>" class="pag-prev pag-arr-nav <?=$isFirstPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow"></use>
        </svg>
      </a>
      <? if($pager->getPage() > 3 && $lastPage>5) :?>
        <a data-page="1" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page=1'. $sortingUrl ?>" class="pag-number <?=$isFirstPage ? '-noActive' : '' ?>">
          1
          <?/*<svg>
            <use xlink:href="#double-arrow"></use>
          </svg>*/?>
        </a>
        <? if($pager->getPage() > 4 && $lastPage>6) :?>
          <span data-page="0" class="pag-number pag-number_sep">...</span>
        <? endif ?>
      <? endif ?>

      <?php foreach ($pager->getLinks(5) as $page): ?>
        <a data-page="<?= $page ?>" href="<?= $baselink.'?page='.$page.$sortingUrl ?>" class="pag-number<?= $page == $pager->getPage() ? ' active' : "" ?>"><?= $page ?></a>
      <?php endforeach; ?>
      <? if($page<$lastPage) :?>
        <? if($page<$lastPage-1) :?>
          <span data-page="0" class="pag-number pag-number_sep">...</span>
        <? endif ?>
        <a data-page="<?=$lastPage?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.$lastPage. $sortingUrl ?>" class="pag-number <?=$isLastPage ? '-noActive' : '' ?>">
          <?= $lastPage ?>
          <?/*<svg>
            <use xlink:href="#double-arrow" />
          </svg>*/?>
        </a>
      <? endif ?>

      <a data-page="<?=$pager->getPage() + 1?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.($pager->getPage() + 1). $sortingUrl ?>" class="pag-next pag-arr-nav <?=$isLastPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow"></use>
        </svg>
      </a>

    </div>
  </div>

<? endif ?>
