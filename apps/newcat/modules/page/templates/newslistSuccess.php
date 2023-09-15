<?php
    slot('rightBlock', true);
foreach ($news as $new):
    ?>
    <div style=" padding:10px 0;border-bottom: 1px dashed #DDD;" class="divArticleHover">
        <div class="article-container">
            <div><a href="/news/<?= $new->getSlug() ?>"><span style="font: 14px/16px Tahoma,Geneva,sans-serif;"><?= $new->getName() ?></span></a></div>

            <?/*<div style="margin: 10px 0 4px;">
                <?=date("d.m.Y", strtotime($new->getCreatedAt()))?>
            </div>*/?>
            <div  style="height: 50px;">
                <p>
                  <?= strip_tags($new->getPrecontent()) ?>
                </p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
