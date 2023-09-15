<?php
//slot('articleleftBlock', true);
slot('testsleftBlock', true);
slot('metaTitle', "Любовные тесты онлайн | Сеть магазинов «Он и Она»" . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : ""));
slot('metaKeywords', "Любовные тесты онлайн");
slot('metaDescription', "Любовные тесты онлайн. На страницах секс-шопа «Он и Она» вы найдете много интересного.");
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugLovetest', "lovetest" . $canonDop);

slot('articlerightpad', true);
?>


<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>Тесты</li>
</ul>

<div>
    <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
    <div class="yashare-wrapper" style="background: none; height: 22px; float:right;">
        <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
             data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

             ></div>
    </div>
</div>
<h1 class="title">Любовные тесты онлайн</h1>

<table class="noBorder test-table">
    <tr>

        <td onClick="document.location = '/lovetest?sortOrder=date&#38;direction=<?= ($direction == "desc" and $sortOrder == "date") ? 'asc' : 'desc' ?>'" class="tableTdFilter<?= $sortOrder == "date" ? "Select" : "" ?>">Новые<?= $sortOrder == "date" ? ($direction == "asc" ? '<img src="/images/asc.png">' : '<img src="/images/desc.png">') : "" ?></td>
        <td onClick="document.location = '/lovetest?sortOrder=rating&#38;direction=<?= ($direction == "desc" and $sortOrder == "rating") ? 'asc' : 'desc' ?>'" class="tableTdFilter<?= $sortOrder == "rating" ? "Select" : "" ?>">Популярные<?= $sortOrder == "rating" ? ($direction == "asc" ? '<img src="/images/asc.png">' : '<img src="/images/desc.png">') : "" ?></td>
    </tr>
</table>

<?php
$test = $pager->getResults();
foreach ($test as $test):
    ?>
    <div class="divArticleHover divArticleHover--test">
        <div class="test-image"><?php if ($test->getImg() != "") { ?><a href="/tests/<?= $test->getSlug() ?>"><img width="200" class="thumbnails" src="/images/pixel.gif" data-original="/uploads/photo/<?= $test->getImg() ?>" alt="<?= str_replace(array("'", '"'), "", $test->getName()) ?>" style="margin: 0 10px;"/></a><?php } else { ?>&nbsp; <?php } ?></div>
        <div class="article-container">
            <div><a href="/tests/<?= $test->getSlug() ?>"><span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $test->getName() ?></span></a></div>

            <div class="stars" style="margin: 6px 0 0px;">
                <span style="width:<?= $test->getRating() > 0 ? (@round($test->getRating() / $test->getVotesCount())) * 10 : 0 ?>%;"></span>
            </div>
            <div class="tests-announce">
                <p>
                    <?= strip_tags($test->getContent()) ?>
                </p>
            </div>
            <div class="test-counter"><div style="float: left;">Вопросов: <?= $test->Question->count() ?></div><div style="float: right; margin-right: 10px;">Тест прошли: <?= $test->getWriting() == "" ? 0 : $test->getWriting() ?> человек</div></div>

        </div>
    </div>
<?php endforeach; ?>


<?php if ($pager->haveToPaginate()):

        if($sortOrder!=""){
            $sortingUrl="&sortOrder=".$sortOrder."&direction=".$direction;

        }
        ?>
    <div class="paginator-box" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
        <?php if ($pager->getPage() == 1) {
            ?>
            <a class="first disable"></a>
            <a class="prev disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('tests_general') ?>?page=1<?=$sortingUrl?>" class="first"></a>
            <a href="<?php echo url_for('tests_general') ?>?page=<?php echo ($pager->getPage() - 1) ?><?=$sortingUrl?>" class="prev"></a>
        <?php }
        ?>
        <?php if ($pager->getPage() == count($pager->getLinks(20))) {
            ?>
            <a class="next disable"></a>
            <a class="last disable"></a>
            <?php
        } else {
            ?>
            <a href="<?php echo url_for('tests_general') ?>?page=<?php echo ($pager->getPage() + 1) ?><?=$sortingUrl?>" class="next"></a>
            <a href="<?php echo url_for('tests_general') ?>?page=<?= count($pager->getLinks(20)) ?><?=$sortingUrl?>" class="last"></a>
        <?php }
        ?>
        <div class="paginator" style="width: <?= count($pager->getLinks(9)) * 37 ?>px;">
            <ul>
                <?php foreach ($pager->getLinks(9) as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <li class="active"><a href="<?php echo url_for('tests_general') ?>?page=<?php echo $page ?><?=$sortingUrl?>"><span><?php echo $page ?></span></a></li>
                                <?php else: ?>
                        <li><a href="<?php echo url_for('tests_general') ?>?page=<?php echo $page ?><?=$sortingUrl?>"><span><?php echo $page ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>


<?
/*

  <h1>Testss List</h1>

  <table>
  <thead>
  <tr>
  <th>Id</th>
  <th>Name</th>
  <th>Slug</th>
  <th>Content</th>
  <th>Is public</th>
  <th>Img</th>
  <th>Views count</th>
  <th>Votes count</th>
  <th>Rating</th>
  <th>Wirting</th>
  <th>Title</th>
  <th>Keywords</th>
  <th>Description</th>
  <th>Created at</th>
  <th>Updated at</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($testss as $tests): ?>
  <tr>
  <td><a href="<?php echo url_for('tests/show?id='.$tests->getId()) ?>"><?php echo $tests->getId() ?></a></td>
  <td><?php echo $tests->getName() ?></td>
  <td><?php echo $tests->getSlug() ?></td>
  <td><?php echo $tests->getContent() ?></td>
  <td><?php echo $tests->getIsPublic() ?></td>
  <td><?php echo $tests->getImg() ?></td>
  <td><?php echo $tests->getViewsCount() ?></td>
  <td><?php echo $tests->getVotesCount() ?></td>
  <td><?php echo $tests->getRating() ?></td>
  <td><?php echo $tests->getWirting() ?></td>
  <td><?php echo $tests->getTitle() ?></td>
  <td><?php echo $tests->getKeywords() ?></td>
  <td><?php echo $tests->getDescription() ?></td>
  <td><?php echo $tests->getCreatedAt() ?></td>
  <td><?php echo $tests->getUpdatedAt() ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
  </table>

  <a href="<?php echo url_for('tests/new') ?>">New</a>
 */?>
