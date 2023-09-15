<?
  $pageStart=$page-2;
  $pageEnd=$page+2;

  $isFirstPage=$isLastPage=false;
  if ($page == 1)
    $isFirstPage=true;
  if ($page == $pagesCount)
    $isLastPage=true;
  if($page==2 || $isFirstPage) $pageEnd=5;
  if($page == $pagesCount-1 || $isLastPage) $pageStart=$pagesCount-4;
  if($pageStart<1) $pageStart=1;
  if($pageEnd>$pagesCount) $pageEnd=$pagesCount;

?>
<?if ($show_more && !$isLastPage) :?>
  <div class="more js-show-more-catalog" data-page="<?= $page+1 ?>" id="js-show-more-catalog">
    <a href="#">Показать  еще  <svg><use xlink:href="#arrowMoreIcon"></use></svg></a>
  </div>
<? endif ?>
<? if($numbers) :?>
  <div class="pag-line">
    <div class="pag js-pag-catalog">
      <a data-page="1" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page=1' ?>" class="pag-first <?=$isFirstPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#double-arrow" />
        </svg>
      </a>
      <a data-page="<?=$page - 1?>" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page='.($page - 1)?>" class="pag-prev <?=$isFirstPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow" />
        </svg>
      </a>

      <?php for ($i=$pageStart; $i <= $pageEnd; $i++): ?>
        <a data-page="<?= $i ?>" href="<?= $baselink.'?page='.$i ?>"  class="pag-number<?= $page == $i ? ' active' : "" ?>"><?= $i ?></a>
      <?php endfor; ?>

      <a data-page="<?=$page + 1?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.($page + 1) ?>" class="pag-next <?=$isLastPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow" />
        </svg>
      </a>
      <a data-page="<?=$pagesCount?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.$pagesCount ?>" class="pag-last <?=$isLastPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#double-arrow" />
        </svg>
      </a>
    </div>
  </div>
<? endif ?>
