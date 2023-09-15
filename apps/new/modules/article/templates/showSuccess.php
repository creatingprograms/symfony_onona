<?php
mb_internal_encoding('UTF-8');
$breadcrumbs[] = ['link' => '/sexopedia', 'text' => 'Энциклопедия секса'];
$slug = '';
if ($category) {
  $startTitle = $category->getName();
  $catalog = $category->getCategoryarticleCatalogs();
  if ($catalog[0]->getName() != 'Сексопедия') {
    // $breadcrumbs[]=['link'=>'/sexopedia/catalog/'.$catalog[0]->getSlug(), 'text' => $catalog[0]->getName()];
  }
  $breadcrumbs[] = ['link' => '/sexopedia/category/' . $category->getSlug(), 'text' =>  $category->getName()];
  $slug = $category->getSlug();
} else {
  $startTitle = $categoryName;
}
$stars = $article->getVotesCount() ? ceil($article->getRating() / $article->getVotesCount() / 2) : 0;
$votesCount = $article->getVotesCount() ? $article->getVotesCount() : 5;
if ($stars < 3) $stars = 4;
$h1 = stripslashes($article->getName());
// echo '<h1>'.'|'.print_r(['1'=>$stars, $article->getRating(),$article->getVotesCount()], true).'</h1>';
$breadcrumbs[] = ['text' => mb_substr($h1, 0, 70) . (mb_strlen($h1) > 70 ? '...' : '')];
slot('canonical', '/sexopedia/' . $article->getSlug());
$image = $article->getImg();
if (!$image || !file_exists($_SERVER['DOCUMENT_ROOT'] . "/uploads/photo/" . $image))
  $image = false;
else $image = '/uploads/photo/' . $image;
$siteUrl = 'https://onona.ru';
/*
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
*/

$metaTitle = $article->getTitle() == '' ?  $h1 : $article->getTitle();
$metaKeywords = $article->getKeywords() == '' ? $h1 : $article->getKeywords();
$metaDescription = $article->getDescription() == '' ? ('Популярно о сексе. ' . $h1 . " " . strip_tags($article->getPrecontent())) : $article->getDescription();

slot('metaTitle', $metaTitle);
slot('metaKeywords', $metaKeywords);
slot('metaDescription', $metaDescription);
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);
// slot('catalog-class', 'page-articles page-title-center');

$videoUrl = $article->getVideo();
$videoUrlArr = explode('.', $videoUrl);

if ($videoUrlArr[1] == 'webm') $videoUrlArr[1] = 'mp4';
else $videoUrlArr[1] = 'webm';

if ($videoUrl) $videoUrl = '/uploads/video/' . $videoUrl;
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $videoUrl)) $videoUrl = false;

if (!$videoUrl && file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads/video/' . implode('.', $videoUrlArr))) $videoUrl = '/uploads/video/' . implode('.', $videoUrlArr);
?>

<div class="wrap-block">
  <div class="container">
    <?php if ($image) : ?>
      <div class="block-content main-img-article">
        <img src="<?= $image ?>" alt="<?= $h1 ?>">
      </div>
    <?php endif; ?>
    <div class="article-share">
      <script src="https://yastatic.net/share2/share.js"></script>
      <div class="ya-share2" data-curtain data-size="l" data-services="facebook,twitter,vkontakte,telegram"></div>
      <span class="article-date">
        <svg>
          <use xlink:href="#article-date" />
        </svg>
        <?= date('d.m', strtotime($article->getCreatedAt())) ?>
      </span>
      <span class="article-views">
        <svg>
          <use xlink:href="#article-views" />
        </svg>
        <?= $article->getViewsCount() ?>
      </span>
    </div>
    <?php if ($videoUrl && $article->getSlug() != 'obzor-realistichnyx-masturbatorov-real') : ?>
      <div class="main-img-article">
        <div class="video-container">
          <video controls="controls">
            <source src="<?= $videoUrl ?>">
          </video>
        </div>
      </div>
    <? elseif($videoUrl) : ?>
      <video controls="controls" class="vertical">
        <source src="<?= $videoUrl ?>">
      </video>
    <? endif ?>
    <div class="block-content-art" itemscope itemtype="http://schema.org/Article">
      <meta itemprop="name" content="<?= str_replace('"', '', $h1) ?>">
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
          <img itemprop="contentUrl" src="https://onona.ru/frontend/images/logo_new.png" alt="Logo">
          <a itemprop="url" href="https://onona.ru" />onona.ru</a>
        </span>
      </span>

      <meta itemprop="author" content="<?= $expertObj ? $expertObj->name : 'onona.ru' ?>">
      <?/*<div class="rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
        <meta itemprop="bestRating" content="5">
        <meta itemprop="ratingValue" content="<?= $stars ?>" />
        <meta itemprop="ratingCount" content="<?= $votesCount ?>" />
      </div>*/?>

      <?
      $text = $article->getContent();
      $text = str_replace('http://onona.ru', '', $text); //делаем ссылки относительными, там где они были абсолютными с неверным протоколом

      $dom = new DOMDocument;
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
      if (!!$dom) {
        $arLink = $dom->getElementsByTagName('img');
        foreach ($arLink as $value) {
          $value->setAttribute('alt', $h1);
          $value->setAttribute('style', '');
        }
        $text = $dom->saveHTML();
        $text = str_replace('<?xml encoding="utf-8" ?>', '', $text);
      }

      $mask = '/\{products:.+\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text = str_replace($group[0], strip_tags($group[0]), $text);
        //Очищаем строки с упоминанием товаров
      }

      $mask = '/\{category:.+\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text = str_replace($group[0], strip_tags($group[0]), $text);
        //Очищаем строки с упоминанием категорий
      }

      $mask = '/\{products:([0-9,]+)\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        ob_start();
        include_component(
          'category',
          'sliderItems',
          array(
            'sf_cache_key' => 'product-list' . $group[1],
            'type' => 'by-ids',
            'blockName' => 'Рекомендованные товары',
            'strIds' => $group[1],
            // 'limit' => 3,
          )
        );
        $productBlock = ob_get_contents();
        ob_end_clean();
        $text = str_replace($group[0], $productBlock, $text);
      }
      
      $mask = '/\{category:([0-9]+)\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        ob_start();
        include_component(
          'category',
          'sliderItems',
          array(
            'sf_cache_key' => 'by-cat-id-' . $group[1],
            'type' => 'by-cat-id',
            'blockName' => 'Рекомендованные товары',
            'catId' => $group[1],
            'limit' => 4,
          )
        );
        $productBlock = ob_get_contents();
        ob_end_clean();
        $text = str_replace($group[0], $productBlock, $text);
      }

      $mask = '/<iframe.*iframe>/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text = str_replace($group[0], '<div class="video-container">' . $group[0] . '</div>', $text);
        //Оборачиваем видео в контейнер
      }

      ?>
      <div itemprop="articleBody">
        <?= $text ?>
      </div>

    </div>
  </div>
</div>
<?
include_component("page", "bannerNew", array(
  'type' => 'Двойной Главная',
  'variant' => "double",
  'sf_cache_key' => $lowerSlug,
));
?>
<? include_component("article", "top", array('sf_cache_key' => 'articlesTop-' . $article->getId(), 'active' => $article->getId(), 'catalog' => 'inner')); ?>