<?
global $isTest;
$h1='Отзывы к товарам';
slot('breadcrumbs', [
  ['text' => $h1],
]);
// slot('h1', $h1);
?>
<?/*<pre><?= print_r($catNames, true)?></pre>*/?>
<main class="wrapper">
  <a href='/programma-on-i-ona-bonus'><img src="/frontend/images/comments.jpg" alt=""></a>
  <div class="page-comments">
    <div class="page-comments-list"><div></div><div>Отзывов всего <?=$countComments[0]['countComment']?></div><div></div></div>
    <div class="page-comments-list">
      <div class="page-comments-list-element">
        <?
          // $catComments[10]=['countComment'=>100];
          // $catComments[11]=['countComment'=>100];
          $colLength=sizeof($catComments)%3;
          $colSize=ceil(sizeof($catComments)/3);
          $i=$colLength==1 ? 1 : 0;
          $isOpen=true;
        ?>
        <? foreach($catComments as $key => $category) :?>
          <?if($i++==$colSize){ $i=1; $isOpen=true;?>
            </div><div class="page-comments-list-element">
          <?}?>
          <div class="page-comments-cat cat-<?=$key?>">
            <div class="header js-hide-show-next <?=$isOpen ? 'active' : '' ?>">
              <?=$catNames[$key]['name'].' '.$catNames[$key]['description']?>
              <span class="arrow"></span>
            </div>
            <div class="page-comments-cat-list <?=!$isOpen ? 'mfp-hide' : ''?>">
              <?php foreach ($category as $key => $catComment): ?>
                <a href="/category/<?= $catComment['slug'] ?><?/*?sortOrder=comments&direction=desc*/?>"><?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a>
              <?php endforeach; ?>
            </div>
          </div>
          <? $isOpen=false; ?>
          <?//= $i.'|'.$colSize.'|'.$colLength.'|'.$key."|".print_r($category['countComment'], true).'<br>' ?>
        <? endforeach ?>
      </div>
    </div>
    <div class="page-comments-details">
      <!------------------------- page data ----------------------------->
      <?
        $comments=$pager->getResults();

        foreach ($comments as $comment) :?>
          <?
            $product = $comment->getProduct();
            $rating = round(($product->getRating() > 0 ? @round($product->getRating() / $product->getVotesCount()) : 0) / 2)+0.1;
            $photoalbum=$product->getPhotoalbums();
            $image=false;
            if(is_object($photoalbum[0])) {
              $photos = $photoalbum[0]->getPhotos();
              if(is_object($photos[0]))
              $image='/uploads/photo/thumbnails_250x250/'.$photos[0]->getFilename();
              if(!file_exists($_SERVER['DOCUMENT_ROOT'].$image)) $image=false;
            }
          ?>
          <div class="element">
            <div class="pic">
              <img src="<?= $image ? $image : '/frontend/images/no-image.png' ?>">
            </div>
            <div class="details">
              <a href="/product/<?= $product->getSlug() ?>/"><?= $product->getName() ?></a>
              <div class="rating">
                <? for ($i=1; $i<6; $i++) :?>
                  <div class="rating-item<?= $i < $rating ? ' -isActive' : ''?>">
                    <svg>
                      <use xlink:href="#rateItemIcon" />
                    </svg>
                  </div>
                <? endfor ?>
                <span class="summary">(<?= $product->getVotesCount() ?>)</span>
              </div>
              <div class="text">
                <?= $comment->getText() ?>
              </div>
              <div class="date">
                <?= date("d.m.Y H:i", strtotime($comment->getCreatedAt())) ?>,
                <strong>
                  <?php if ($comment->getUsername() != ""): ?>
                      <?= htmlspecialchars($comment->getUsername()) ?>
                  <?php else: ?>
                      <?= htmlspecialchars($comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName()) ?>
                  <?php endif; ?>
                </strong>
              </div>
            </div>
          </div>
        <? endforeach ?>
        <?/*include_component('page', 'listComments',
          array(
            // 'sf_cache_key' => '',
            'comments' => $comments,
          )
        );*/
      ?>
      <!------------------------- page data ----------------------------->
    </div>
    <?php if ($pager->haveToPaginate()):?>
      <? include_component("noncache", "pagination", array(
        'pager' => $pager,
        'sortingUrl' =>  '',
        'baselink' => '/comments/',
        'show_more' => true,
        'numbers' => true,
      )); ?>
    <?php endif; ?>
  </div>
</main>
