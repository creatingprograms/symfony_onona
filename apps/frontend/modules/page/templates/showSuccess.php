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
if($page->getSlug() == "main" || $page->getSlug() == "mainNew" || $page->getSlug() == "mainNewDis")
  $isMain = true;
if($isMain) slot('is_main', true);

$timer->addTime();

if (!$isMain) {
  slot('breadcrumbs', [
    ['text' => $page->getName()],
  ]);
  if($page->getName()!='404') slot('h1', $page->getName());
}
if( $page->getSlug() == "about") $isMain = true;//Костыль имени Жучкова
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

if(!$isMain) echo '<main class="wrapper -action"><div class="-innerpage">';
echo $content;
if(!$isMain) echo '</div></main>';

$timer->addTime();
