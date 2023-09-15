<?php
mb_internal_encoding('UTF-8');
$breadcrumbs[]=['link'=>'/faq', 'text' => 'FAQ'];
$slug='';
/*
if ($category){
  $startTitle = $category->getName();
  $catalog = $category->getCategoryarticleCatalogs();
  if ($catalog[0]->getName() != 'Сексопедия') {
     // $breadcrumbs[]=['link'=>'/sexopedia/catalog/'.$catalog[0]->getSlug(), 'text' => $catalog[0]->getName()];
  }
  $breadcrumbs[]=['link'=>'/sexopedia/category/'.$category->getSlug(), 'text' =>  $category->getName()];
  $slug=$category->getSlug();
}
else{
  $startTitle = $categoryName;
}*/


$h1=stripslashes($article->getName());

$breadcrumbs[]=['text' => mb_substr($h1, 0, 70).(mb_strlen($h1) > 70 ? '...' : '')];
slot('canonical', '/faq/'.$article->getSlug());

$image=$article->getImg();
if(!$image || !file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/photo/".$image))
  $image=false;
else $image='/uploads/photo/'.$image;
$siteUrl='https://onona.ru';

$metaTitle= $article->getTitle() == '' ?  str_replace(array("{name}"), array($article->getName()), csSettings::get('titleArticle')) : $article->getTitle() ;
$metaKeywords = $article->getKeywords() == '' ? (stripslashes($article->getName()) . " * " . $startTitle . " * Частые вопросы * Главная") : $article->getKeywords();
$metaDescription = $article->getDescription() == '' ? (stripslashes($article->getName()) . " * " . $startTitle . " * Частые вопросы * Главная") : $article->getDescription();

slot('metaTitle', $metaTitle);
slot('metaKeywords', $metaKeywords);
slot('metaDescription', $metaDescription);
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);
// slot('catalog-class', 'page-articles page-title-center');

?>

<div class="wrap-block">
  <div class="container">
    <?php if ($image): ?>
      <div class="block-content main-img-article">
        <img src="<?=$image?>" alt="<?= $h1 ?>">
      </div>
    <?php endif; ?>
    <div class="article-share">
      <script src="https://yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-curtain data-size="l" data-services="facebook,twitter,vkontakte,telegram"></div>
        <span class="article-date">
          <svg>
            <use xlink:href="#article-date" />
          </svg>
          <?=date('d.m', strtotime($article->getCreatedAt()))?>
        </span>
        <span class="article-views">
          <svg>
            <use xlink:href="#article-views" />
          </svg>
          <?= $article->getViewsCount() ?>
        </span>
    </div>
    <div class="block-content-art" itemscope itemtype="http://schema.org/Article">
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
      </span>

      <meta itemprop="author" content="<?= $expertObj ? $expertObj->name : 'onona.ru'?>">


      <?
      $text=$article->getContent();
      $text=str_replace('http://onona.ru', '', $text);//делаем ссылки относительными, там где они были абсолютными с неверным протоколом

      $dom = new DOMDocument;
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
      if(!!$dom){
        $arLink = $dom->getElementsByTagName('img');
        foreach ($arLink as $value) {
          $value->setAttribute('alt', $h1);
          $value->setAttribute('style', '');
        }
        $text=$dom->saveHTML();
        $text=str_replace('<?xml encoding="utf-8" ?>', '', $text);
      }

      $mask='/\{products:.+\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text=str_replace($group[0], strip_tags($group[0]), $text);
        //Очищаем строки с упоминанием товаров
      }

      $mask='/\{products:([0-9,]+)\}/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        ob_start();
        include_component('category', 'sliderItems',
          array(
            'sf_cache_key' => 'product-list'.$group[1],
            'type' => 'by-ids',
            'blockName' => 'Рекомендованные товары',
            'strIds' => $group[1],
            // 'limit' => 3,
          )
        );
        $productBlock=ob_get_contents();
        ob_end_clean();
        $text=str_replace($group[0], $productBlock, $text);
      }
      $mask='/<iframe.*iframe>/';
      preg_match_all($mask, $text, $matches, PREG_SET_ORDER, 0);
      if (sizeof($matches)) foreach ($matches as $group) {
        $text=str_replace($group[0], '<div class="video-container">'.$group[0].'</div>', $text);
        //Оборачиваем видео в контейнер
      }

      ?>
      <div  itemprop="articleBody">
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
<? //include_component("article", "top", array('sf_cache_key' => 'articlesTop-'.$article->getId(), 'active' => $article->getId(), 'catalog'=> 'inner' )); ?>
