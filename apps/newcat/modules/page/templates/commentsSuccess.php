<?php
mb_internal_encoding('UTF-8');
slot('metaTitle', "Отзывы | Сеть магазинов «Он и Она»");
slot('metaKeywords', "Секс шоп отзывы к интим товарам");
slot('metaDescription', "С отзывами об интимных товарах секс-шопа «Он и Она» можно ознакомиться на сайте.");
?>
<ul style="padding:12px 0 0 31px" class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>
         Секс шоп отзывы к интим товарам
    </li>
</ul>
<script type="text/javascript">
  $(document).ready(function () {
    $('.comments-header').on('click', function(){
      $(this).parent().find('.drop').toggle();
    });
      var fixCenterHeight = function() {

          $("div.blockCommentCompensation").each(function(index) {
              var blocksH = 0;
              var parentH = $(this).parent().height();
              $(this).parent().find("div:not(.blockCommentCompensation)").each(function(index) {
                  blocksH = blocksH + $(this).height() + 30;
              });
              $(this).height(parentH - blocksH + 20);
          });

      };

      $(window).resize(function() {
          fixCenterHeight();
      });

      fixCenterHeight();
    });
</script>
<h1 class="title">Отзывы к товарам</h1>
<table class="noBorder blockComment cat-menu reviews">
    <tr>
        <td>
          <?php/*
            <div style='padding:10px; background-color: #ecfef0; color:#414141; text-align: center;margin-bottom: 10px;'>
                <span style='font-size: 18px;color:#c3060e;'>ОТЗЫВЫ К ТОВАРАМ</span>
                <br />
                (количество отзывов по категориям)
            </div>*/
          ?>
            <div class="comments-container first-row" style='background-color: #fbeeff;'>
                <span class="comments-header">ОН И ОНА - ИГРУШКИ ДЛЯ ПАР</span>
                <br />
                <ul class="drop">
                    <? foreach ($catComments[2] as $catComment): ?>
                        <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                    <? endforeach; ?>
                </ul>
            </div>
            <?php /*
              <div style='height: auto; background-color: #ededed;margin-bottom: 10px; height:40px;' class='blockCommentCompensation'></div>
            */?>
            <div class="comments-container" style='background-color: #fff3f3;'>
                <span class="comments-header">ИНТИМНАЯ КОСМЕТИКА</span>
                <br />
                <ul class="drop">
                    <? foreach ($catComments[5] as $catComment): ?>
                        <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                    <? endforeach; ?>
                </ul>
            </div>
            <div class="comments-container" style='background-color: #ededed;'>
                <span class="comments-header">БДСМ И ФЕТИШ</span>
                <br />
                <ul class="drop">
                    <? foreach ($catComments[4] as $catComment): ?>
                        <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                    <? endforeach; ?>
                </ul>
            </div>
        </td>
        <td>
            <div class="comments-container" style='background-color: #f3f3ff;'>
                <span class="comments-header">ОН - ИГРУШКИ ДЛЯ МУЖЧИН</span>
                <br />
                <ul class="drop">
                    <? foreach ($catComments[1] as $catComment): ?>
                        <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                    <? endforeach; ?>
                </ul>
            </div>
            <div class="comments-container" style='background-color: #ecfef0;'>
                <span class="comments-header">АКСЕСУАРЫ, РАЗНОЕ</span>
                <br />
                <ul class="drop">
                    <? foreach ($catComments[7] as $catComment): ?>
                        <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                    <? endforeach; ?>
                </ul>

            </div>
            <div class="comments-container send-your-review">
                <span style='font-size: 18px;'>Оставьте <span style='color:#c3060e;'>СВОЙ ОТЗЫВ</span> и получите бонусы</span>
                <br />
                <a href='/programma-on-i-ona-bonus'><u>подробнее о бонусах</u></a>
            </div>
            <?/*<div style='height: auto; background-color: #fceeff;margin-bottom: 10px;' class='blockCommentCompensation'></div>*/?>
        <td>
          <div class="comments-container" style='background-color: #fff3f3;'>
              <span class="comments-header">ОНА - ИГРУШКИ ДЛЯ ЖЕНЩИН</span>
              <br />
              <ul class="drop">
                  <? foreach ($catComments[3] as $catComment): ?>
                      <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                  <? endforeach; ?>
              </ul>
          </div>
          <div class="comments-container" style='background-color: #fceeff;'>
              <span class="comments-header">ЭРОТИЧЕСКОЕ БЕЛЬЕ</span>
              <br />
              <ul class="drop">
                  <? foreach ($catComments[6] as $catComment): ?>
                      <li style="padding: 3px 0;background: none; font-size: 12px;"><a href="/category/<?= $catComment['slug'] ?>?sortOrder=comments&direction=desc"> <?= $catComment['name'] ?> (<?= $catComment['countComment'] ?>)</a></li>
                  <? endforeach; ?>
              </ul>

          </div>
          <div style='height: auto; background-color: #fff3f3;margin-bottom: 10px;' <?/*class='blockCommentCompensation'*/?>></div>
          <div style='height: auto; background-color: #fceeff;' class='blockCommentCompensation'><span style="display: block; padding: 10px;">Отзывов всего <?=$countComments[0]['countComment']?></span></div>
        </td>
    </tr>
</table>
<?php
$comments = $pager->getResults();
foreach ($comments as $comment):
    $comment = CommentsTable::getInstance()->createQuery()->where('product_id=' . $comment->getProduct()->getId())->addWhere('is_public = \'1\' and product_id > 0')->orderBy("created_at Desc")->limit(1)->execute();
    $comment = $comment[0];
    ?>
    <table class="item_comment">
        <tbody>
            <tr>
                <td valign="top" style="width: 100px;">
                    <a href="/product/<?= $comment->getProduct()->getSlug() ?>">
                        <img border="0" class="item_picture" alt="<?= $comment->getProduct()->getName() ?>" src="/uploads/photo/thumbnails_250x250/<?
                        $photoalbum = $comment->getProduct()->getPhotoalbums();
                        $photos = $photoalbum[0]->getPhotos();
                        ?><?= $photos[0]->getFilename() ?>" height="100">
                    </a>
                </td>
                <td valign="top" style="padding-left: 10px;">
                    <h3>
                        <a href="/product/<?= $comment->getProduct()->getSlug() ?>">
                            <?= $comment->getProduct()->getName() ?>
                        </a>
                        <?php
                        for ($i = 1; $i <= 10; $i++):
                            if ($i <= @round($comment->getProduct()->getRating() / $comment->getProduct()->getVotesCount()))
                                echo '<img src="/images/star_hover.gif" id="rate_divStarRating' . $i . '" alt="' . $i . '" title="' . $i . '">';
                            else
                                echo '<img src="/images/star.gif" id="rate_divStarRating' . $i . '" alt="' . $i . '" title="' . $i . '">';

                        endfor;
                        ?>(<?= @round($comment->getProduct()->getRating() / $comment->getProduct()->getVotesCount()) ?>)
                    </h3>
                    <p><?= $comment->getText() ?></p>
                    <p class="signature"><div class="tooltip text_tooltip"><i><?= date("d.m.Y H:i", strtotime($comment->getCreatedAt())) ?> от <?= $comment->getUsername() != "" ? $comment->getUsername() : ($comment->getSfGuardUser()->getFirstName() != "" ? $comment->getSfGuardUser()->getFirstName() : $comment->getSfGuardUser()->getLastName()) ?></i></div></p>

                </td>
            </tr>
        </tbody>
    </table>
<? endforeach; ?>
<?php /* if ($pager->haveToPaginate()): ?>
  <nav class="pager">
  <ol>
  <?php foreach ($pager->getLinks(20) as $page): ?>
  <?php if ($page == $pager->getPage()): ?>
  <li><?php echo $page ?></li>
  <?php else: ?>
  <li> <a href="/comments?page=<?php echo $page ?>"><?php echo $page ?></a></li>
  <?php endif; ?>
  <?php endforeach; ?>
  </ol>
  </nav>
  <?php endif; */ ?>

<?php
if ($pager->haveToPaginate()):
    ?>

    <?php
    JSInPages("function setPages(id){
    document.location.href = '/comments?page='+id;

                    return false;
                }");
    ?>

    <div class="paginator-box review-paginator" style="width: <?= (((count($pager->getLinks(9)) - 9) > 0 ? 9 : count($pager->getLinks(9)))) * 30 + 5 ?>px;">
        <?php if ($pager->getPage() != 1) {
            ?>
            <a href="/comments" onclick="setPages(<?= $pager->getPage() - 1 ?>);
                    return false;" class="prev-btn">Предыдущая</a>
           <?php }
           ?>
           <?php if ($pager->getPage() != count($pager->getLinks(100))) {
               ?>
            <a href="/comments" onclick="setPages(<?= $pager->getPage() + 1 ?>);
                    return false;" class="next-btn" >Следующая</a>
           <?php }
           ?>
           <?php
           /* if ($pager->getPage() == 1) {
             ?>
             <a class="first disable"></a>
             <a class="prev disable"></a>
             <?php
             } else {
             ?>
             <a href="/comments?page=1<?= $sortingUrl ?>" class="first"></a>
             <a href="/comments?page=<?php echo ($pager->getPage() - 1) ?><?= $sortingUrl ?>" class="prev"></a>
             <?php }
             ?>
             <?php if ($pager->getPage() == count($pager->getLinks(100))) {
             ?>
             <a class="next disable"></a>
             <a class="last disable"></a>
             <?php
             } else {
             ?>
             <a href="/comments?page=<?php echo ($pager->getPage() + 1) ?><?= $sortingUrl ?>" class="next"></a>
             <a href="/comments?page=<?= count($pager->getLinks(100)) ?><?= $sortingUrl ?>" class="last"></a>
             <?php } */
           $page = $pager->getPage();
           ?>
        <div class="paginator" style="width: <?= (((count($pager->getLinks(100)) - 9) > 0 ? 9 : count($pager->getLinks(100)))) * 30 + 5 ?>px;">
            <ul>
                <?php /* $stopPageNum = ((($sf_request->getParameter('page', 1) - 10) > 1 ? ($sf_request->getParameter('page', 1) - 10) : 1) + 14);
                  $stopPageNum = $stopPageNum > count($pager->getLinks(100)) ? count($pager->getLinks(100)) : $stopPageNum;
                  for ($page = (($sf_request->getParameter('page', 1) - 10) > 1 ? ($sf_request->getParameter('page', 1) - 10) : 1); $page <= $stopPageNum; $page++):
                  ?>
                  <?php if ($page == $sf_request->getParameter('page', 1)): ?>
                  <li class="active" id="pageId-<?= $page ?>"><a href="/comments?page=<?= $page ?>" onclick="setPages(<?= $page ?>);
                  return false;"><span><?php echo $page ?></span></a></li>
                  <?php else: ?>
                  <li id="pageId-<?= $page ?>"><a href="/comments?page=<?= $page ?>" onclick="setPages(<?= $page ?>);
                  return false;"><span><?php echo $page ?></span></a></li>
                  <?php endif; ?>
                  <?php endfor; */ ?>

                <?php
                if (count($pager->getLinks(100)) <= 9) {
                    for ($i = 1; $i <= count($pager->getLinks(100)); $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                } elseif ($page <= 5) {
                    for ($i = 1; $i <= 7; $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        •••
                    </li>
                    <li>
                        <div onclick="setPages(<?= count($pager->getLinks(100)) ?>)">
        <?= count($pager->getLinks(100)) ?>
                        </div>
                    </li>
                    <?php
                } elseif ($page < count($pager->getLinks(100)) - 4) {
                    ?>
                    <li>
                        <div onclick="setPages(1)" class="pageId-1">
                            1
                        </div>
                    </li>
                    <li>
                        •••
                    </li>
                    <?php
                    for ($i = $page - 2; $i <= $page + 2; $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        •••
                    </li>
                    <li>
                        <div onclick="setPages(<?= count($pager->getLinks(100)) ?>)">
        <?= count($pager->getLinks(100)) ?>
                        </div>
                    </li>
                    <?php
                } else {
                    ?>
                    <li>
                        <div onclick="setPages(1)" class="pageId-1">
                            1
                        </div>
                    </li>
                    <li>
                        •••
                    </li>
                    <?php
                    for ($i = count($pager->getLinks(100)) - 6; $i <= count($pager->getLinks(100)); $i++) {
                        ?>
                        <li<?= $i == $page ? ' class="active"' : '' ?>>
                            <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
            <?= $i ?>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
<?php endif; ?>


<?php /*if ($pager->haveToPaginate()): ?>
    <div class="paginator-box" style="width: <?= count($pager->getLinks(20)) * 37 ?>px;">
        <?php if ($pager->getPage() == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('comments') ?>?page=1" class="first"></a>
            <a href="<?php echo url_for('comments') ?>?page=<?php echo ($pager->getPage() - 1) ?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == count($pager->getLinks(200))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('comments') ?>?page=<?php echo ($pager->getPage() + 1) ?>" class="next"></a>
            <a href="<?php echo url_for('comments') ?>?page=<?= count($pager->getLinks(200)) ?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= count($pager->getLinks(20)) * 37 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(20) as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <li class="active"><a href="<?php echo url_for('comments') ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                                <?php else: ?>
                        <li><a href="<?php echo url_for('comments') ?>?page=<?php echo $page ?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif;*/ ?>
