<?php
slot('articleleftBlock', true);
slot('no-float', true);
mb_internal_encoding('UTF-8');
slot('articcategorySlug', $category->getSlug());
/* slot('metaTitle', $article->getTitle() == '' ? $article->getName() : $article->getTitle());
  slot('metaKeywords', $article->getKeywords() == '' ? $article->getName() : $article->getKeywords());
  slot('metaDescription', $article->getDescription() == '' ? $article->getName() : $article->getDescription()); */
if ($category)
    $startTitle = $category->getName();
else
    $startTitle = $categoryName;

slot('canonicalSlugArticle', $article->getSlug());
//slot('metaTitle', stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная");
$metaTitle= $article->getTitle() == '' ?  str_replace(array("{name}"), array($article->getName()), csSettings::get('titleArticle')) : $article->getTitle() ;
$metaKeywords = $article->getKeywords() == '' ? (stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная") : $article->getKeywords();
$metaDescription = $article->getDescription() == '' ? (stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная") : $article->getDescription();

slot('metaTitle', $metaTitle);
slot('metaKeywords', $metaKeywords);
slot('metaDescription', $metaDescription);

// slot('metaTitle', str_replace(array("{name}"), array($article->getName()), csSettings::get('titleArticle')));
// slot('metaKeywords', stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная");
// slot('metaDescription', stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная");
?>
<?php
$comments = Doctrine_Core::getTable('Comments')
        ->createQuery('c')
        ->where("is_public = '1'")
        ->addWhere('article_id = ?', $article->getId())
        ->orderBy('created_at desc')
        ->execute();
?>

<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li><a href="/sexopedia">Энциклопедия секса </a></li>
    <? $catalog = $category->getCategoryarticleCatalogs(); ?><? if ($catalog[0]->getName() != 'Сексопедия') { ?>
        <li><a href="/sexopedia<?= $catalog[0]->getName() == 'Сексопедия' ? "" : "/catalog/" . $catalog[0]->getSlug() ?>"><?= $catalog[0]->getName() ?> </a></li><? } ?>
    <li><a href="/sexopedia/category/<?= $category->getSlug() ?>"><?= $category->getName() ?> </a></li>
    <li><?= mb_substr(stripslashes($article->getName()), 0, 30) ?><?php if (mb_strlen($article->getName()) > 30) echo "..."; ?> </li>
</ul>
<h1 class="title" style="max-width: 760px;"><?= stripslashes($article->getName()) ?></h1>
<?php if ($comments->count() > 0): ?>

    <div style="float: right;color: #707070;  font: 10px/13px Tahoma,Geneva,sans-serif;"><a class="rewiev" href="/sexopedia/<?= $article->getSlug() ?>/?comment=true#comments" style="color: #707070;">Отзывы: <?= $comments->count() ?></a></div>
<?php endif; ?>Рейтинг: <div class="stars" id="rate_div" style=" display: inline;top: 4px;">
<? /* <span style="width:100%;" id="rate_div"></span> */ ?>
</div>
<span><?= $article->getVotesCount() ?></span>
<script src="/js/jquery.starRating.js">
</script>
<script>
    function changeButtonToGreen(id) {
        $("#buttonId_" + id).removeClass("red-btn");
        $("#buttonId_" + id).addClass("green-btn");
        $("#buttonId_" + id).html("<span>В корзине</span>");
        $("#buttonId_" + id).attr("onclick", "");
        $("#buttonId_" + id).attr("title", "Перейти в корзину");
        $(".popup-holder #buttonIdP_" + id).removeClass("red-btn");
        $(".popup-holder #buttonIdP_" + id).addClass("green-btn");
        $(".popup-holder #buttonIdP_" + id).html("<span>В корзине</span>");
        $(".popup-holder #buttonIdP_" + id).attr("onclick", "");
        $(".popup-holder #buttonIdP_" + id).attr("title", "Перейти в корзину");

        ;
        window.setTimeout('$("#buttonId_' + id + '").attr("href","/cart")', 1000);
        window.setTimeout('$(".popup-holder #buttonIdP_' + id + '").attr("href","/cart")', 1000);
        $("#buttonIdP_" + id).removeClass("red-btn");
        $("#buttonIdP_" + id).addClass("green-btn");
        $("#buttonIdP_" + id).html("<span>В корзине</span>");
        $("#buttonIdP_" + id).attr("onclick", "");
        $("#buttonIdP_" + id).attr("title", "Перейти в корзину");

        window.setTimeout('$("#buttonIdP_' + id + '").attr("href","/cart")', 1000);
    }
</script>
<script>
    $(document).ready(function () {
        /*$('.zoomPad').css('top', (($('#main div.img-holder').height() - $('#photoimg_<?= $article->getId() ?>').height())/2)-);*/
        $('.zoomPad').css('left', (($('#main div.img-holder').width() - $('#photoimg_<?= $article->getId() ?>').width()) / 2) - 2);
        $('#rate_div').starRating({
        basicImage  : '/images/star.gif',
                ratedImage : '/images/star_hover.gif',
                hoverImage : '/images/star_hover2.gif',
                ratingStars   : 10,
                ratingUrl       : '/sexopedia/rate',
                paramId       :  'article',
                paramValue  : 'value',
                rating			  : '<?= $article->getRating() > 0 ? @round($article->getRating() / $article->getVotesCount()) : 0 ?>',
                customParams : {articleId : '<?= $article->getId() ?>'}

  <? if (sfContext::getInstance()->getRequest()->getCookie("ratear_" . $article->getId())) { ?>
              ,
                      clickable : false,
                      hoverable : false
  <? } ?>

    });
    });
</script>

<div class="article-content">
  <?php
    $text=$article->getContent();
    $text=str_replace('http://onona.ru', '', $text);

    $mask='/\{products:.+\}/';
    preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
    if (sizeof($matches)) foreach ($matches as $group) {
      $text=str_replace($group[0], strip_tags($group[0]), $text);
      // echo '<pre>'.print_r(['group'=>$group[0], 'strip'=>strip_tags($group[0])], true).'</pre>';
    }
    // echo $text;
    // return;
    $mask='/\{products:([0-9,]+)\}/';
    preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
    if (sizeof($matches)) foreach ($matches as $group) {
      // echo '<pre>'.print_r(explode(',',$group[1]), true).'</pre>';
      ob_start();
      include_component('category', 'articleproductpart',
        array(
          'ids' => explode(',',$group[1]),
        )
      );
      $productBlock=ob_get_contents();
      ob_end_clean();
      // echo "<pre>".print_r($group, true).'</pre>';
      $text=str_replace($group[0], $productBlock, $text);
    }

  ?>
  <?php echo $text ?>
</div>
<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
<div class="yashare-wrapper" style="background: none;  width: 200px; height: 22px; margin-left:-6px;margin-top:-6px;">
    <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
         data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

         ></div>
</div>

<div class="old-browsers"></div>

<div class="article-arrow long">Отзывы о статье <img src="/newdis/images/strelka.png" id="commStrel" alt="strelka"></div>

<div class="add-review">
    <a href="#" onclick="$('#commentDiv').toggle();
        return false;">Оставить свой отзыв о статье</a>
</div>
<a id="comments"></a>

<?php if ($comments->count() > 0): ?>

    <ul class="review-list">
        <?php
        foreach ($comments as $key => $comment):
            ?>

            <li style="background: none;">
              <noindex>
                <div class="head">
                    <div style="float:left;">
                        <?php if ($comment->getUsername() != ""): ?>
                            <?= strip_tags($comment->getUsername()) ?>
                        <?php else: ?>
                            <?= $comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName() ?>
                        <?php endif; ?>
                    </div>
                    <div style="float:right;"><?= date("d.m.Y H:i", strtotime($comment->getCreatedAt())) ?></div>
                    <div style="border-bottom: 1px dashed rgb(170, 170, 170); height: 9px; margin: 0px 120px;"></div>
                    <div style="clear:both;"></div>

                </div>
                <p><?= strip_tags($comment->getText()) ?></p>
              </noindex>
            </li>
            <?php
        endforeach;
        ?>
    </ul>
<?php else: ?>
    <div class="no-coments">Об этой статье отзывов пока нет. Будьте первым.</div>
<?php endif; ?>

<div class="add-coment" style="display:none;" id="commentDiv">
    <form id="commentForm" method="post" action="/sexopedia/<?= $article->getSlug() ?>/addcomment" name="comment">
        <fieldset>
            <? /* <div class="add-review">
              <a href="#">Оставить свой отзыв о товаре</a>
              </div> */ ?>
            <div class="descr">Внимание! Публикация отзывов производится после предварительной модерации.</div>
            <div class="row">
                <div class="label-holder">
                    <label>Ваше имя:*</label>
                </div>
                <div class="input-holder">
                    <input type="text" name="cName" />
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label>Ваш e-mail:</label>
                </div>
                <div class="input-holder">
                    <input type="text" name="cEmail" />
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label>Сообщение:*</label>
                </div>
                <div class="textarea-holder">
                    <textarea cols="30" rows="5" name="cComment"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="label-holder">
                    <label>Укажите код:*</label>
                </div>
                <div class="capcha-holder">
                    <img src="/captcha/kcaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" width="139" height="48" alt="image description" />
                </div>
            </div>
            <div class="row">
                <div class="label-holder">&nbsp;</div>
                <div class="input-holder">
                    <input type="text" name="cText" />
                </div>
            </div>
            <div class="required">* - поля, отмеченные * обязательны для заполнения.</div>
            <div class="btn-holder centr">
                <div class="red-btn  colorWhite">
                    <span>Отправить</span>
                    <input type="button" value="Отправить" class="red-btn" onclick="$('#commentForm').submit();
                            return false;" />
                </div>
            </div>
        </fieldset>
    </form>
</div>

<div class="mobile-hide">
  <div class="old-browsers first"></div>
  <div class="article-arrow long">Читайте также <img src="/newdis/images/strelka.png" alt="strelka"></div>
  <?
  $catArticles = $category->getCategoryArticles();
  if ($catArticles->count() > 1):
      ?>
      <div class="promo-gallery-holder two-item">
          <a href="#" class="prev" style="top:70px"></a>
          <a href="#" class="next" style="top:70px"></a>
          <div class="promo-gallery" style="width: 630px;">
              <ul>
                  <?php
                  foreach ($catArticles as $numberArt => $articleDop):
                      if ($numberArt < 44) {
                          ?>
                          <?php if ($articleDop->getId() != $article->getId()): ?>
                              <li><a href="/sexopedia/<?= $articleDop->getSlug() ?>" style="border: 0px;height: 170px; width: auto;"><img src="/uploads/photo/<?= $articleDop->getImg(); ?>" style="width: 186px;" alt="<?= str_replace(array("'", '"'), "", $articleDop->getName()) ?>" /><br />

                                      <?= mb_substr(stripslashes($articleDop->getName()), 0, 30) ?><?php if (mb_strlen($articleDop->getName()) > 30) echo "..."; ?>
                                  </a></li>

                          <?php endif; ?>
                      <?php } endforeach; ?>
              </ul>
          </div>
      </div>
  <?php endif; ?>
</div>

<?php
  // echo '|!<pre>'.print_r($matches, true).'</pre>!|';
  include_component('category', 'articlecategory',
    array(
      'slug' => $article->getCategorySlug(),
    )
  );
?>
