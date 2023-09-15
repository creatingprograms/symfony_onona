<?php
// if($isPickPoint) die('foo');
$timerAll = sfTimerManager::getTimer('Templates: Весь шаблон');
mb_internal_encoding('UTF-8');
$timer = sfTimerManager::getTimer('Templates: Передача meta тегов');
if(!isset($isPickPoint)){
  slot('metaTitle', $page->getTitle() == '' ? $page->getName() : $page->getTitle());
  slot('metaKeywords', $page->getKeywords() == '' ? $page->getName() : $page->getKeywords());
  slot('metaDescription', $page->getDescription() == '' ? $page->getName() : $page->getDescription());
}
else{
  slot('metaTitle', $page->getTitle() == '' ? $page->getName().' - Служба доставки Pick Point.' : $page->getTitle());
  slot('metaKeywords', $page->getKeywords() == '' ? $page->getName() : $page->getKeywords());
  slot('metaDescription', $page->getDescription() == '' ? $page->getName().' - Служба доставки Pick Point. Поиск ближайшего постамата или пункта выдачи.' : $page->getDescription());
}
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод хлебных крошек');
if ($page->getSlug() != "mainNew" and $page->getSlug() != "mainNewDis" and $page->getSlug() != "programma-on-i-ona-bonus") {
    ?>
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a></li>
        <li>
            <?php echo $page->getName() ?></li>
    </ul>
    <?
}/*
  ?>
  <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
  <div class="yashare-wrapper" style="background: none; height: 22px; margin-left:-6px;margin-top:-6px;float:right;">
  <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
  data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"

  ></div>
  </div>
  <? */
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод шапки страницы');
?>

<script type="text/javascript">
    function CallPrint(strid, WinPrint)
    {
        var prtContent = $(strid);
        var prtCSS = '<link rel="stylesheet" href="/newdis/css/all.css" type="text/css" />';
        //var WinPrint = window.open('', '', 'left=50,top=50,width=800,height=700,toolbar=0,scrollbars=1,status=0');
        WinPrint.document.write(prtCSS);
        WinPrint.document.write('<body style="width: 760px;min-width: 760px;"><div id="content" class="left">' + prtContent.html() + '</div></body>');
        //WinPrint.document.close();
        WinPrint.focus();
        //WinPrint.print();
        setTimeout(function () {
            WinPrint.print();
            WinPrint.close();
        }, 500);
        //setTimeout(WinPrint.print(), 10000);
        //WinPrint.print();
        //WinPrint.close();
    }

</script>
<h1 class="title"><?php echo $page->getName() ?></h1>
<?
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод блока новостей');
if ($newsBlock != "")
    echo $newsBlock;
$timer->addTime();


$timer = sfTimerManager::getTimer('Templates: Вывод счётчика бонусов(Программа он и она бонус)');
if ($page->getSlug() == "programma-on-i-ona-bonus") {
    ?>

    <script>
        $(document).ready(function () {

            setInterval(function () {
                str = parseFloat($('#bonusAddAll').text().replace(/ /g, "")) + 2;
                str = str + '';
                $('#bonusAddAll').text(str.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
            }, 1000)
        });
    </script><?
    $bonusAdd = csSettings::get('all_bonus_add') + (time() - mktime(0, 0, 0)) * 2;
    $page->setContent(str_replace("{bonusAddAll}", number_format($bonusAdd, 0, ',', ' '), $page->getContent()));
}

$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод основного контента');
if ($sf_user->isAuthenticated()) {
    /* if ($page->getSlug() == "mainNewDis") {
      ?>

      <?
      } else */
    if (sfContext::getInstance()->getUser()->getAttribute('regMO')) {
        echo str_replace("{userName}", $sf_user->getGuardUser()->getEmailAddress(), ($page->getContentMo()!=""?$page->getContentMo():$page->getContent()));
    } else {
        echo str_replace("{userName}", $sf_user->getGuardUser()->getEmailAddress(), $page->getContent());
    }
} else {
    if (sfContext::getInstance()->getUser()->getAttribute('regMO')) {
        echo ($page->getContentMo()!=""?$page->getContentMo():$page->getContent());
    } else {
        echo $page->getContent();
    }
}
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод кнопки печати страницы');

if ($page->getSlug() != "mainNew" and $page->getSlug() != "mainNewDis" and $page->getSlug() != "programma-on-i-ona-bonus") {
    ?>
    <a href="javascript:CallPrint('#content',window.open('', '_blank', 'left=50,top=50,width=800,height=700,toolbar=0,scrollbars=1,status=0'));" class="print"><img src="/newdis/images/ico02.png" width="16" height="16" alt="image description" /> Распечатать страницу</a>

    <?
}
?>

<?php
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Распределение боковых блоков');
if ($page->getSlug() == "sex-igrushki" or $page->getSlug() == "intimnye_tovary") {
    slot('sexshop', true);
}
if ($page->getSlug() != "sexshop" and $page->getSlug() != "sexshopnew") {
    if ($page->getIsShowRightBlock()) {
        slot('rightBlock', true);
    }
} else {
    slot('leftBlock', true);
    slot('sexshop', true);
}
if ($page->getSlug() == "mainNew" or $page->getSlug() == "mainNewDis" or $page->getSlug() == "articleTest") {
    ?>

    <?
    slot('mainPage', true);
    slot('rightBlock', false);
}
if ($page->getSlug() == "akcii-i-bonusy" or $page->getSlug() == "testpage" or $page->getSlug() == "programma-on-i-ona-bonus") {

    slot('rightBlock', false);
    slot('leftBlock', false);
}
$timer->addTime();

$timer = sfTimerManager::getTimer('Templates: Вывод тегов');
if ($page->getTags() != "") {
    ?><div style="float: right; color: #c0c0c0">Тэги: <a href="#" onclick="$('#tags').toggle();
                return false" class="more-expand" style="float: right;margin: 0 5px;"></a> <div id="tags" style="display: none; float: right;margin: 0 5px;"><?= $page->getTags() ?></div> </div><?
}
$timer->addTime();
$timerAll->addTime();
