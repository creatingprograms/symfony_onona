<?php
slot('metaTitle', "Сексопедия. Энциклопедия секса | Секс-шоп «Он и Она»");
slot('metaKeywords', "сексопедия , все о сексе энциклопедия");
slot('metaDescription', "Сексопедия. Статьи о сексуальном здоровье, о взаимоотношениях в семье, о сексе и любви ");

slot('articleleftBlock', true);
slot('articlerightpad', true);
?>
<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>Энциклопедия секса </li>
</ul>
<div>
    <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>

    <div class="yashare-wrapper" style="background: none; height: 22px; margin-left:-6px;margin-top:-6px;float:right;">
        <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
             data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

             ></div>
    </div>
</div>
<h1 class="title">Энциклопедия секса - Sexopedia 18+</h1>

<div class="old-browsers first"></div>
<div class="article-arrow"><a href="/sexopedia/catalog/new" style="color: #C3060E">Новые статьи <img src="/newdis/images/strelka.png" alt="strelka"></a></div>
<?php
$articlesRelated = ArticleTable::getInstance()->createQuery()->addWhere("is_public='1'")->orderBy("created_at DESC")->limit(4)->execute();
foreach ($articlesRelated as $article):
    ?>
    <div class="divArticleHover">
        <div class="article-image">
          <?php if ($article->getImg() != "") { ?>
            <a href="/sexopedia/<?= $article->getSlug() ?>">
              <img class="thumbnails" src="/images/pixel.gif" data-original="/uploads/photo/<?= $article->getImg() ?>" alt="<?= $article->getName() ?>" style="margin: 0 10px;"/>
            </a>
          <?php } else { ?>
            &nbsp; <?php } ?>
        </div>
        <div class="article-container">
            <div><a href="/sexopedia/<?= $article->getSlug() ?>"><span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $article->getName() ?></span></a></div>
            <?php
            $comments = Doctrine_Core::getTable('Comments')
                    ->createQuery('c')
                    ->where("is_public = '1'")
                    ->addWhere('article_id = ?', $article->getId())
                    ->orderBy('created_at desc')
                    ->execute();
            ?>
            <?php if ($comments->count() > 0): ?>

                <div style="float: right;color: #707070;  font: 10px/13px Tahoma,Geneva,sans-serif;"><a class="rewiev" href="/sexopedia/<?= $article->getSlug() ?>/?comment=true#comments">Отзывы: <?= $comments->count() ?></a></div>
            <?php endif; ?>
                <div class="stars" style="margin: 10px 0 4px;">
                <span style="width:<?= $article->getRating() > 0 ? (@round($article->getRating() / $article->getVotesCount())) * 10 : 0 ?>%;"></span>
            </div>
            <div class="article-wpapper">
                <p>
                    <?= strip_tags($article->getPrecontent()) ?>
                </p>
            </div>
            <?php if ($article->getTags() != "") { ?>
                <div class="article-tags">Тэги:
                    <?php
                    $tags = explode(",", $article->getTags());
                    foreach ($tags as $key => $tag) {
                        echo trim($tag);
                        if ($key != (count($tags) - 1))
                            echo ", ";
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="old-browsers"></div>
<div class="article-arrow"><a href="/sexopedia/catalog/recommend" style="color: #C3060E">Рекомендуемые статьи <img src="/newdis/images/strelka.png" alt="strelka"></a></div>
<?php
$articlesRelated = ArticleTable::getInstance()->createQuery()->where('is_related = 1')->addWhere("is_public='1'")->orderBy("positionrelated DESC")->limit(3)->execute();
foreach ($articlesRelated as $article):
    ?>
    <div class="divArticleHover">
        <div class="article-image"><?php if ($article->getImg() != "") { ?><a href="/sexopedia/<?= $article->getSlug() ?>"><img class="thumbnails" src="/images/pixel.gif" data-original="/uploads/photo/<?= $article->getImg() ?>" alt="<?= $article->getName() ?>" style="margin: 0 10px;"/></a><?php } else { ?>&nbsp; <?php } ?></div>
        <div class="article-container">
            <div><a href="/sexopedia/<?= $article->getSlug() ?>"><span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $article->getName() ?></span></a></div>
            <?php
            $comments = Doctrine_Core::getTable('Comments')
                    ->createQuery('c')
                    ->where("is_public = '1'")
                    ->addWhere('article_id = ?', $article->getId())
                    ->orderBy('created_at desc')
                    ->execute();
            ?>
            <?php if ($comments->count() > 0): ?>

                <div style="float: right;color: #707070;  font: 10px/13px Tahoma,Geneva,sans-serif;"><a class="rewiev" href="/sexopedia/<?= $article->getSlug() ?>/?comment=true#comments">Отзывы: <?= $comments->count() ?></a></div>
            <?php endif; ?>
            <div class="stars" style="margin: 10px 0 4px;">
                <span style="width:<?= $article->getRating() > 0 ? (@round($article->getRating() / $article->getVotesCount())) * 10 : 0 ?>%;"></span>
            </div>
            <div class="article-wpapper">
                <p>
                    <?= strip_tags($article->getPrecontent()) ?>
                </p>
            </div>
            <?php if ($article->getTags() != "") { ?>
                <div class="article-tags">Тэги:
                    <?php
                    $tags = explode(",", $article->getTags());
                    foreach ($tags as $key => $tag) {
                        echo trim($tag);
                        if ($key != (count($tags) - 1))
                            echo ", ";
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php endforeach; ?>

<div class="old-browsers"></div>
<div  class="article-arrow"><a href="/sexopedia/catalog/pop" style="color: #C3060E">Популярные статьи <img src="/newdis/images/strelka.png" alt="strelka"></a></div>
<?php
$articlesRelated = ArticleTable::getInstance()->createQuery()->addWhere("is_public='1'")->orderBy("rating/votes_count DESC, votes_count DESC")->limit(3)->execute();
foreach ($articlesRelated as $article):
    ?>
    <div class="divArticleHover">
        <div class="article-image"><?php if ($article->getImg() != "") { ?><a href="/sexopedia/<?= $article->getSlug() ?>"><img class="thumbnails" src="/images/pixel.gif" data-original="/uploads/photo/<?= $article->getImg() ?>" alt="<?= $article->getName() ?>" style="margin: 0 10px;"/></a><?php } else { ?>&nbsp; <?php } ?></div>
        <div class="article-container">
            <div><a href="/sexopedia/<?= $article->getSlug() ?>"><span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $article->getName() ?></span></a></div>
            <?php
            $comments = Doctrine_Core::getTable('Comments')
                    ->createQuery('c')
                    ->where("is_public = '1'")
                    ->addWhere('article_id = ?', $article->getId())
                    ->orderBy('created_at desc')
                    ->execute();
            ?>
            <?php if ($comments->count() > 0): ?>

                <div style="float: right;color: #707070;  font: 10px/13px Tahoma,Geneva,sans-serif;"><a class="rewiev" href="/sexopedia/<?= $article->getSlug() ?>/?comment=true#comments">Отзывы: <?= $comments->count() ?></a></div>
            <?php endif; ?>
                <div class="stars" style="margin: 10px 0 4px;">
                <span style="width:<?= $article->getRating() > 0 ? (@round($article->getRating() / $article->getVotesCount())) * 10 : 0 ?>%;"></span>
            </div>
            <div class="article-wpapper">
                <p>
                    <?= strip_tags($article->getPrecontent()) ?>
                </p>
            </div>
            <?php if ($article->getTags() != "") { ?>
                <div class="article-tags">Тэги:
                    <?php
                    $tags = explode(",", $article->getTags());
                    foreach ($tags as $key => $tag) {
                        echo trim($tag);
                        if ($key != (count($tags) - 1))
                            echo ", ";
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php endforeach; ?>
