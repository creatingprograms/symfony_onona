<?php
// if($isPickPoint) die('foo');
$timerAll = sfTimerManager::getTimer('Templates: Весь шаблон');
mb_internal_encoding('UTF-8');
$timer = sfTimerManager::getTimer('Templates: Передача meta тегов');
$title=$page->getTitle() == '' ? $page->getName() : $page->getTitle();
$keyw = $page->getKeywords() == '' ? $page->getName() : $page->getKeywords();
$descr = $page->getDescription() == '' ? $page->getName() : $page->getDescription();

if(stripos($page->getSlug(), 'akcia')!==false){//если в адресе есть akcia
  if($page->getTitle() == '') $title.= '. Акции секс-шопа "Он и Она"';
  if($page->getDescription() == '') $descr= 'Акции секс-шопа "Он и Она". '.$descr;
}
if(!isset($isPickPoint)){
  slot('metaTitle', $title);
  slot('metaKeywords', $keyw);
  slot('metaDescription', $descr);
}
else{
  slot('metaTitle', $page->getTitle() == '' ? $page->getName().' - Служба доставки Pick Point.' : $page->getTitle());
  slot('metaKeywords', $page->getKeywords() == '' ? $page->getName() : $page->getKeywords());
  slot('metaDescription', $page->getDescription() == '' ? $page->getName().' - Служба доставки Pick Point. Поиск ближайшего постамата или пункта выдачи.' : $page->getDescription());
}
slot('catalog-class', $page->getClass());
$isMain = false;
if($page->getSlug() == "main" || $page->getSlug() == "mainNew" || $page->getSlug() == "mainNewDis" || $page->getSlug() == "main_new")
  $isMain = true;
if($isMain) slot('is_main', true);

$timer->addTime();

$addBlock = '';

if (!$isMain) {
  slot('breadcrumbs', [
    ['text' => $page->getName()],
  ]);
  if($page->getName()!='404') {
    slot('h1', $page->getName());
  }
  else { //404
    $addBlock = '<br><br><div data-retailrocket-markup-block="5ba3a67897a5283ed03172e8"></div>';
  }
}
?>

<?
if($page->getClass() == 'tinkoff'){
  slot('h1', false);
  slot('breadcrumbs', false);
}
?>
<?if(!empty($_GET)) slot('canonical', '/'.$page->getSlug());
$timer = sfTimerManager::getTimer('Templates: Вывод основного контента');
if ($sf_user->isAuthenticated()) {

    if (sfContext::getInstance()->getUser()->getAttribute('regMO')) {
      $content=str_replace("{userName}", $sf_user->getGuardUser()->getEmailAddress(), ($page->getContentMo()!=""?$page->getContentMo():$page->getContent()));

    } else {
        $content=str_replace("{userName}", $sf_user->getGuardUser()->getEmailAddress(), $page->getContent());
    }
} else {
    if (sfContext::getInstance()->getUser()->getAttribute('regMO')) {
        $content= ($page->getContentMo()!=""?$page->getContentMo():$page->getContent());
    } else {
        $content= $page->getContent();
    }
}
$haveWrap=mb_stripos($content, 'wrap-block');

// if(mb_stripos($content, 'page-content')===false && !!mb_stripos($content, 'wrap-block'))//если в начале контента нет обертки - оборачиваем
if(mb_stripos($content, 'page-content')===false && ($haveWrap===false || $haveWrap>0))//если в начале контента нет обертки - оборачиваем
  $content='<div class="wrap-block"><div class="container"><div class="block-content">'.$content.'</div>'. $addBlock . '</div></div>';
echo $content;

$timer->addTime();
