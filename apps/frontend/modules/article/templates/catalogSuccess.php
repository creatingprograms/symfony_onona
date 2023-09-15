<?php
// die('catalog success');
$h1 = $catalog->getName();

slot('metaTitle', $h1 . " * Энциклопедия секса * Главная");
slot('metaKeywords', $h1 . " * Энциклопедия секса * Главная");
slot('metaDescription', $h1 . " * Энциклопедия секса * Главная");
slot('h1', $h1);
slot('breadcrumbs', [
  ['link'=>'/sexopedia', 'text' => 'Энциклопедия секса'],
  ['text' => $h1],
]);
?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right -sexopedia">
    <?= $catalog->getContent() ?>
    <div class="art-list-group">
      <? include_component("article", "articlesBlock", array('sf_cache_key' => '$articlesNew'.$h1, 'list'=> $pagerArticles)); ?>
    </div>
  </main>
  <? include_component("article", "leftMenu", array('sf_cache_key' => 'articlesLeftMenu')); ?>
</div>


<?php /*
  $articles = $pager->getResults();
  if ($pager->haveToPaginate()): ?>
    <div class="paginator-box" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
      <?php if ($pager->getPage() == 1) {
          ?>
          <a class="first disable"></a>
          <a class="prev disable"></a>
          <?php
      } else {
          ?>
          <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=1" class="first"></a>
          <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
      <?php }
      ?>
      <?php if ($pager->getPage() == count($pager->getLinks(20))) {
          ?>
          <a class="next disable"></a>
          <a class="last disable"></a>
          <?php
      } else {
          ?>
          <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo ($pager->getPage() + 1) ?>" class="next"></a>
          <a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?= count($pager->getLinks(20)) ?>" class="last"></a>
      <?php }
      ?>
      <div class="paginator" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
          <ul>
              <?php foreach ($pager->getLinks(9) as $page): ?>
                  <?php if ($page == $pager->getPage()): ?>
                      <li class="active"><a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                  <?php else: ?>
                      <li><a href="<?php echo url_for('article_category', array('slug' => $sf_request->getParameter('slug'))) ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
          </ul>
      </div>
  </div>
<?php endif; */?>
