
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
  if(empty($sortingUrl)) $sortingUrl='';
  // if(!empty($classicLinkPagination))

?>
<?/*if ($show_more && !$isLastPage) :?>
  <div class="more js-show-more-catalog" data-page="<?= $page+1 ?>" id="js-show-more-catalog">
    <a href="#">Показать  еще  <svg><use xlink:href="#arrowMoreIcon"></use></svg></a>
  </div>
<? endif */?>
<? if($numbers) :?>
  <div class="pag-line pag-line_mod">
    <div class="pag <?=empty($classicLinkPagination) ? 'js-pag-catalog' : ''?>">
      <a data-page="<?=$page - 1?>" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page='.($page - 1).$sortingUrl?>" class="pag-prev pag-arr-nav <?=$isFirstPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow" />
        </svg>
      </a>
      <? if($page > 3 && $pagesCount>4) :?>
        <a data-page="1" href="<?= $isFirstPage ? 'javascript: void();' : $baselink.'?page=1'.$sortingUrl ?>" class="pag-number <?=$isFirstPage ? '-noActive active' : '' ?>">1</a>
      <? endif ?>
      <? if($page > 4) :?>
        <span class="pag-number pag-number_sep">...</span>
      <? endif ?>
      <?php for ($i=$pageStart; $i <= $pageEnd; $i++): ?>
        <a data-page="<?= $i ?>" href="<?= $baselink.'?page='.$i.$sortingUrl ?>"  class="pag-number<?= $page == $i ? ' active' : "" ?>"><?= $i ?></a>
      <?php endfor; ?>
      <? if($page<$pagesCount-3) :?>
        <span class="pag-number pag-number_sep">...</span>
      <? endif ?>
      <? if($page<$pagesCount-2 && $pagesCount >4) :?>
        <a data-page="<?=$pagesCount?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.$pagesCount.$sortingUrl ?>" class="pag-number pag-last <?=$isLastPage ? '-noActive active' : '' ?>"><?=$pagesCount?></a>
      <? endif ?>

      <a data-page="<?=$page + 1?>" href="<?= $isLastPage ? 'javascript: void();' : $baselink.'?page='.($page + 1).$sortingUrl ?>" class="pag-next pag-arr-nav <?=$isLastPage ? '-noActive' : '' ?>">
        <svg>
          <use xlink:href="#single-arrow" />
        </svg>
      </a>
    </div>
  </div>
<? endif ?>
