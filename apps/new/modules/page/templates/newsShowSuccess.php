<?php
// mb_internal_encoding('UTF-8');
$breadcrumbs[]=['link'=>'/news', 'text' => 'Новости'];
$slug='';

$h1=stripslashes($article->getName());
// echo '<h1>'.'|'.print_r(['1'=>$stars, $article->getRating(),$article->getVotesCount()], true).'</h1>';
$breadcrumbs[]=['text' => mb_substr($h1, 0, 70).(mb_strlen($h1) > 70 ? '...' : '')];
// slot('canonical', '/sexopedia/'.$article->getSlug());
$image=$article->getPhotoUrl();
if(!$image || !file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/news/".$image))
  $image=false;
else $image='/uploads/news/'.$image;

// slot('metaTitle', $metaTitle);
// slot('metaKeywords', $metaKeywords);
// slot('metaDescription', $metaDescription);
slot('breadcrumbs', $breadcrumbs);
slot('h1', $h1);

?>

<div class="wrap-block">
  <div class="container">
    <div class="article-share">
      <script src="https://yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-curtain data-size="l" data-services="facebook,twitter,vkontakte,telegram"></div>
        <span class="article-date">
          <svg>
            <use xlink:href="#article-date" />
          </svg>
          <?=date('d.m.Y', strtotime($article->getCreatedAt()))?>
        </span>
    </div>
    <div class="block-content">
      <?
      $text=$article->getContent();
      $text=str_replace('http://onona.ru', '', $text);//делаем ссылки относительными, там где они были абсолютными с неверным протоколом

      $dom = new DOMDocument;
      $dom->loadHTML('<?xml encoding="utf-8" ?>' . $text);
      if(!!$dom){
        $arLink = $dom->getElementsByTagName('img');
        foreach ($arLink as $value) {
          $value->setAttribute('alt', $h1);
          $value->setAttribute('style', '');
        }
        $text=$dom->saveHTML();
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
