<div class="art-list">
  <? foreach ($list as $article) : ?>
    <?//= '<pre>'.print_r($article, true).'</pre>' ?>
    <?
      $image=$article->getImg();
      if(!$image || !file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/photo/".$image))
        $image='/frontend/images/no-image.png';
      else $image='/uploads/photo/'.$image;
    ?>
    <div class="art-list-item">
      <a href="/sexopedia/<?= $article->getSlug() ?>" class="art-list-img">
        <img src="<?= $image ?>" alt="<?= $article->getName() ?>">
      </a>
      <div class="art-list-desc">
        <div class="art-list-wrap">
          <h2>
            <a href="/sexopedia/<?= $article->getSlug() ?>" >
                <?= $article->getName() ?>
            </a>
          </h2>
          <div class="art-list-text">
            <?= strip_tags($article->getPrecontent()) ?>
          </div>
          <div class="art-list-more">
            <a href="/sexopedia/<?= $article->getSlug() ?>" >Читать дальше>></a>
          </div>
        </div>
        <?php if ($article->getTags() != "") { ?>
            <div class="art-list-tag">
                <?php
                $tags = explode(",", $article->getTags());
                foreach ($tags as $key => $tag) {
                    echo '<span>'.trim($tag).'</span>';
                    // echo '<a href="javascript:">'.trim($tag).'</a>';
                    /*if ($key != (count($tags) - 1))
                        echo ", ";*/
                }
                ?>
            </div>
        <?php } ?>

      </div>
    </div>
  <? endforeach ?>

</div>
