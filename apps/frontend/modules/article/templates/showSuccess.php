<?php
mb_internal_encoding('UTF-8');
$breadcrumbs[]=['link'=>'/sexopedia', 'text' => 'Энциклопедия секса'];
$slug='';
if ($category){
  $startTitle = $category->getName();
  $catalog = $category->getCategoryarticleCatalogs();
  if ($catalog[0]->getName() != 'Сексопедия') {
     $breadcrumbs[]=['link'=>'/sexopedia/catalog/'.$catalog[0]->getSlug(), 'text' => $catalog[0]->getName()];
  }
  $breadcrumbs[]=['link'=>'/sexopedia/category/'.$category->getSlug(), 'text' =>  $category->getName()];
  $slug=$category->getSlug();
}
else{
  $startTitle = $categoryName;
}
$stars=$article->getVotesCount() ? ceil($article->getRating()/$article->getVotesCount()/2) : 0;
$votesCount=$article->getVotesCount() ? $article->getVotesCount() : 5;
if($stars<3) $stars=4;
$h1=stripslashes($article->getName());
// echo '<h1>'.'|'.print_r(['1'=>$stars, $article->getRating(),$article->getVotesCount()], true).'</h1>';
$breadcrumbs[]=['text' => mb_substr($h1, 0, 70).(mb_strlen($h1) > 70 ? '...' : '')];
slot('canonical', '/sexopedia/'.$article->getSlug());
$image=$article->getImg();
if(!$image || !file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/photo/".$image))
  $image='/frontend/images/no-image.png';
else $image='/uploads/photo/'.$image;
$siteUrl='https://onona.ru';
if(!$img)
slot('OpenGraph', [
  'title' => $h1,
  'image' => $siteUrl.$image,
  'url' => sfContext::getInstance()->getRequest()->getUri(),
  'type' => 'article',
  'description' => $article->getPrecontent(),
  'locale' => 'ru_RU',
  'site_name' => 'onona.ru',
]);


$metaTitle= $article->getTitle() == '' ?  str_replace(array("{name}"), array($article->getName()), csSettings::get('titleArticle')) : $article->getTitle() ;
$metaKeywords = $article->getKeywords() == '' ? (stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная") : $article->getKeywords();
$metaDescription = $article->getDescription() == '' ? (stripslashes($article->getName()) . " * " . $startTitle . " * Энциклопедия секса * Главная") : $article->getDescription();

slot('metaTitle', $metaTitle);
slot('metaKeywords', $metaKeywords);
slot('metaDescription', $metaDescription);
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);

?>
<div class="wrapper wrap-cont -clf -sidebar">
  <main class="cont-right -sexopedia">
    <div class="art-list-group art-detail" itemscope itemtype="http://schema.org/Article">

      <meta itemprop="name" content="<?= $h1 ?>">
      <meta itemprop="image" content="<?= $article->getImg() ?>">
      <meta itemprop="headline" content="<?= $h1 ?>">
      <meta itemprop="description" content="<?= strip_tags($article->getPrecontent()) ?>">
      <meta itemprop="datePublished" content="<?= $article->getCreatedAt() ?>">
      <meta itemprop="dateModified" content="<?= $article->getUpdatedAt() ?>">
      <link itemprop="mainEntityOfPage" href="<?= sfContext::getInstance()->getRequest()->getUri() ?>" />

      <span itemprop="publisher" itemscope="" itemtype="http://schema.org/Organization" class=" mfp-hide">
          <meta itemprop="address" content="(123022), Москва г, Трехгорный Б. пер, 18" />
          <meta itemprop="telephone" content="<?= preg_replace('/[^\d+]/', '', csSettings::get('phone2')) ?>" />
          <meta itemprop="name" content="onona.ru" />
          <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
            <img  itemprop="contentUrl" src="https://onona.ru/frontend/images/logo_new.png" alt="Logo">
            <a itemprop="url" href="https://onona.ru"/>onona.ru</a>
          </span>
          <?/*<span itemprop="logo"  itemtype="http://schema.org/ImageObject" class=" mfp-hide" src="https://onona.ru/frontend/images/logo_new.png">
              <meta itemprop="url" itemtype="http://schema.org/URL" content="https://onona.ru/frontend/images/logo_new.png" />
          </span>*/?>
      </span>

      <meta itemprop="author" content="<?= $expertObj ? $expertObj->name : 'onona.ru'?>">

      <div class="rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating" >
        <meta itemprop="bestRating" content="5">
        <meta itemprop="ratingValue" content="<?= $stars ?>" />
        <meta itemprop="ratingCount" content="<?= $votesCount ?>" />
        <? for ($i=1; $i<6; $i++) :?>
          <div class="rating-item<?= $i < $stars ? ' -isActive' : ''?>">
            <svg>
              <use xlink:href="#rateItemIcon" />
            </svg>
          </div>
        <? endfor ?>
        <?/*<div class="rating-numb">
          (<?= $votesCount ?>)
        </div>*/?>
      </div>
      <?
      $text=$article->getContent();
      $text=str_replace('http://onona.ru', '', $text);//делаем ссылки относительными, там где они были абсолютными с неверным протоколом

      $mask='/\{products:.+\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text=str_replace($group[0], strip_tags($group[0]), $text);
        // echo '<pre>'.print_r(['group'=>$group[0], 'strip'=>strip_tags($group[0])], true).'</pre>';
        //Очищаем строки с упоминанием товаров
      }

      $mask='/\{products:([0-9,]+)\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        // echo '<pre>2.'.print_r(explode(',',$group[1]), true).'</pre>';
        ob_start();
        include_component('category', 'listItems',
          array(
            'sf_cache_key' => 'product-list'.$group[1],
            'ids' => explode(',',$group[1]),
          )
        );
        $productBlock=ob_get_contents();
        ob_end_clean();
        // echo "<pre>".print_r($group, true).'</pre>';
        $text=str_replace($group[0], $productBlock, $text);
      }
      $mask='/<iframe.*iframe>/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text=str_replace($group[0], '<div class="video-container">'.$group[0].'</div>', $text);
        // die( '<pre>'.print_r(['group'=>$group[0], 'strip'=>strip_tags($group[0])], true).'</pre>');
        //Оборачиваем видео в контейнер
      }
      $dom = new DOMDocument;
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $text);
      if(!!$dom){
        $arLink = $dom->getElementsByTagName('img');
        foreach ($arLink as $value) {
          $value->setAttribute('alt', $h1);
        }
        $text=$dom->saveHTML();
      }
      ?>
      <div itemprop="articleBody">
        <?= $text ?>
      </div>


      <script src="https://yastatic.net/share2/share.js"></script>
      <div class="ya-share2" data-curtain data-services="vkontakte,facebook,odnoklassniki,telegram,twitter"></div>
    </div>
    <?php if($expertObj): ?>
      <div class="wrapper">
        <div class="product-card-row shop-item-review">
          <a href="#" class="art-list-img">
            <img src="<?= $expertObj->photo_url ?>" alt="" style="max-height: 150px;">
          </a>

          <div class="shop-item-review-h">
            Статью проверил эксперт
          </div>

          <div class="review-list review-list-down" id="reviewList">
            <h4><?= $expertObj->name ?></h4>
            <p><?= $expertObj->description ?></p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </main>
  <? include_component("article", "leftMenu", array('sf_cache_key' => 'articlesLeftMenu', 'active' => $slug)); ?>
</div>
