<div class="pagination">
  <? if ($pageNum<>1) : ?>
    <a href="<?=url_for('comments2/')?>?page=1<?=$filter?>">Первая</a>
  <? endif ?>
  <? if( $pageNum>3 ) echo '...'; ?>
  <? for($j=$pageNum-2; $j<=$pageNum+2; $j++) :?>
    <? if($j<1 || $j>$pagesCount) continue; ?>
    <a href="<?=url_for('comments2/')?>?page=<?=$j.$filter?>"><?=$j==$pageNum ? "<strong>$j</strong>" : $j?></a>
  <? endfor ?>
  <? if($pageNum<=$pagesCount-3) echo '...' ?>
  <? if ($pageNum<>$pagesCount) : ?>
    <a href="<?=url_for('comments2/')?>?page=<?=$pagesCount.$filter?>">Последняя</a>
  <? endif ?>
</div>
