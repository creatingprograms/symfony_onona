<?php
$timerAll = sfTimerManager::getTimer('Templates: Весь шаблон');

$products = $pager->getResults();

$timer = sfTimerManager::getTimer('Templates: Передача meta тегов');
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
slot('canonicalSlugCategory', $category->getSlug() . $canonDop);
slot('leftBlock', true);
slot('metaTitle', $category->getTitle() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") : $category->getTitle() . (sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? " - Страница " . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "") );
slot('metaKeywords', $category->getKeywords() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getKeywords());
slot('metaDescription', $category->getDescription() == '' ? str_replace("{name}", $category->getName(), csSettings::get('titleCategory')) : $category->getDescription());
$timer->addTime();

$products_old = unserialize($sf_user->getAttribute('products_to_cart'));
if (is_array($products_old))
    foreach ($products_old as $key => $product) {
        $arrayProdCart[] = $product['productId'];
    }

$products_desire = $sf_user->getAttribute('products_to_desire');
$products_desire = $products_desire != '' ? unserialize($products_desire) : '';

$products_compare = $sf_user->getAttribute('products_to_compare');
$products_compare = $products_compare != '' ? unserialize($products_compare) : '';
?>
<?
$agentIsMobile = false;
?>
<script>
    hs.graphicsDir = '/js/highslide/graphics/';
    hs.align = 'center';
    hs.transitions = ['expand', 'crossfade'];
    hs.outlineType = 'rounded-white';
    hs.fadeInOut = true;
    hs.dimmingOpacity = 0.75;
</script>

<!--<script src="/js/liTip_newcat.js"></script>-->
<? $timer = sfTimerManager::getTimer('Templates: Вывод хлебных крошек'); ?>
<div class="category-show">
  <ul class="breadcrumbs">
      <li><a href="/">Секс-шоп главная</a></li>
      <?php
      if ($category->getParentsId() != "") {
          echo '
      <li><a href="/category/' . $category->getParent()->getSlug() . '">' . $category->getParent()->getName() . '</a></li>';
      }
      ?>
      <li><?php echo $category->getName() ?></li>
  </ul>
  <?
  $timer->addTime();

  $timer = sfTimerManager::getTimer('Templates: Вывод шапки категории');
  ?>
  <h1 class="title"><?= $category->getH1() != "" ? stripslashes($category->getH1()) : stripslashes($category->getName()) ?></h1>

  <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
  <div class="yashare-wrapper" style="background: none;  width: 200px; height: 22px; margin-left:-6px;margin-top:-6px;">
      <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
           data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

           ></div>
  </div>
  <?
  $timer->addTime();
  ?>

  <?
  $timer = sfTimerManager::getTimer('Templates: Вывод таблицы с набором сортировок');


  if ($sf_user->isAuthenticated()):
      $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", sfContext::getInstance()->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $category->getId())->addWhere("is_enabled='1'")->fetchOne();
      if ($notificationCategory) { ?>
          <div class="NotificationCategory">
              <div style='color: #c3060e;margin-bottom: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе. <a href="/delnotification/<?= $category->getId() ?>" onclick=" $('.NotificationCategory').load($(this).attr('href'));
                  return false;">Отписаться</a></div>
          </div>
      <? }
      else { ?>
          <div class="NotificationCategory">
              <div class="notification-text"><input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:</div>
              <div style="float: left;" class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                                  return false;
                              }
                              $(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $category->getId() ?>});'>Подписаться</div>

          </div>
      <? }
  else: ?>
      <div class="NotificationCategory">
          <div class="notification-text-not-autorized">Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:</div>
          <div style="float: right;">
              <div style="float: left;"><input style="border: 1px solid #b2b2b2; height: 26px; background-color: #FFF;width: 250px;padding-left: 10px;" id="notificationMail" value="Введите свой e-mail" onClick="$(this).val('');" /></div>
              <div style="float: left;" class="silverButtonAddToNotification" onClick='$(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $category->getId() ?>, emailAddNotification: $("#notificationMail").val()});'>Подписаться</div>
          </div>
      </div><?
  endif;
  ?>
  <div style="clear: both;"></div>

  <?

  if ($sf_request->getParameter('page', 1) == 1) {
      ?>
      <?php echo $category->getContent() ?><br /><?
  }
  ?>
  <?
    include_component(
      'category',
      'catalogsorters',
      array(
        'link'=> '/category/'.$category->getSlug(),
        'isStock'=>$isStock,
        'sortOrder'=>$sortOrder,
        'direction'=>$direction,
        'set'=>2,
        // 'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
      )
    );?>
  <?/*
  <div class="blockSort on-manage-price">
      <div class="left-side">
          Сортировать по: <a href="/category/<?php echo $category->getSlug() ?>?sortOrder=date"<?= ($sortOrder == "date" or $sortOrder == "sortorder" or $sortOrder == "") ? " style='color: #c3060e;'" : "" ?>>Новинки</a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=price&amp;direction=<?= ($direction == "asc" and $sortOrder == "price") ? 'desc' : 'asc' ?>"<?= $sortOrder == "price" ? " style='color: #c3060e;'" : "" ?>>Цена <span style="font-weight: bold;"><?= $sortOrder == "price" ? ($direction == "asc" ? '&darr;' : '&uarr;') : "" ?></span></a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=comments"<?= $sortOrder == "comments" ? " style='color: #c3060e;'" : "" ?>>Отзывы</a><span style="padding: 0 10px;">|</span><a href="/category/<?php echo $category->getSlug() ?>?sortOrder=rating"<?= $sortOrder == "rating" ? " style='color: #c3060e;'" : "" ?>>Рейтинг</a>
      </div>
      <div class="right-side">
        <span class="nonCountShow" onclick="if ($(this).text() == 'Скрыть отсутствующие товары') {
                  $(this).text('Показать отсутствующие товары');
                  $('.liProdNonCount').each(function (i) {
                      $(this).fadeOut(0);
                  })
              } else {
                  $(this).text('Скрыть отсутствующие товары');
                  $('.liProdNonCount').each(function (i) {
                      $(this).fadeIn(0);
                  })
              }
              ;
              viravnivanie();">Скрыть отсутствующие товары</span>
      </div>
  </div>*/?>
  <?
  $timer->addTime();
  ?>
  <script type="text/javascript">
      function CallPrint(strid)
      {
          var prtContent = $(strid);
          var prtCSS = '<link rel="stylesheet" href="/newdis/css/all.css" type="text/css" />';
          var WinPrint = window.open('', '', 'left=50,top=50,width=800,height=700,toolbar=0,scrollbars=1,status=0');
          WinPrint.document.write('<div class="popup quick-view" style="margin: 0px; top: 10px; left: 10px;">');
          WinPrint.document.write(prtCSS);
          WinPrint.document.write(prtContent.html());
          WinPrint.document.write('</div></div></article>');
          WinPrint.document.close();
          WinPrint.focus();
          WinPrint.print();
          WinPrint.close();
      }

  </script>

  <?php
  $timer = sfTimerManager::getTimer('Templates: Определение количества товаров для блока лучшая цена');
  if ($category->getSlug() == "upravlyai-cenoi") {
      $prouctCountAction = 0;
  } else {
      $prouctCountAction = 0;
      if ($products[2]->getEndaction() != "" and $products[2]->getCount() > 0 and $products[2]->getDiscount() > 0) {
          $prouctCountAction = 2;
      }
      if ($products[5]->getEndaction() != "" and $products[5]->getCount() > 0 and $products[5]->getDiscount() > 0) {
          $prouctCountAction = 5;
      }
  }
  $timer->addTime();
  ?><ul class="item-list gtm-category-show" data-list="Управляй ценой<?=($sf_request->getParameter('page', 1) == 1 ? '' : ' страница '.$sf_request->getParameter('page', 1))?>"><?
  ?>
      <?php
      if ($sf_user->isAuthenticated()):
          $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
          $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
      endif;
      $timerAllProd = sfTimerManager::getTimer('Templates: Вывод товаров');
      foreach ($products as $prodNum => $product):
          if ($product->getCount() > 0) {
              $prodCount = 1;
          } else {
              $prodCount = 0;
          }
          ?>
          <?php
          if ($product->getCount() < 1) {
              $productCountLast = ProductTable::getInstance()->createQuery()->where("parents_id=?", $product->getId())->addWhere("count>0")->addwhere("is_public=1")->fetchOne();
              if ($productCountLast)
                  $product = $productCountLast;
          }
          if (in_array($product->getId(), $arrayProdCart) === true)
              $prodInCart = true;
          else
              $prodInCart = false;

          if (in_array($product->getId(), $products_desire) === true)
              $prodInDesire = true;
          else
              $prodInDesire = false;

          if (in_array($product->getId(), $products_compare) === true)
              $prodInCompare = true;
          else
              $prodInCompare = false;
          include_component('category', 'products', array('slug' => "upravlyai-cenoi", 'product' => $product, 'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . $agentIsMobile . "-" . $prodInCart . "-" . $prodInDesire . "-" . $prodInCompare . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-showarticle-" . $showarticle, 'prodCount' => $prodCount, 'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'), 'prodNum' => $prodNum, 'last' => ((($prodNum + 1) % 3) == 0 ? true : false), 'agentIsMobile' => $agentIsMobile, "productsKeys" => implode(",", $products->getPrimaryKeys()), "prodInCart" => $prodInCart, "prodInDesire" => $prodInDesire, "prodInCompare" => $prodInCompare));
          ?>
          <?php
      endforeach;

      $timerAllProd->addTime();
      ?>
  </ul><!--item-list end-->
  <div class="manage-price-paginator">
    <?
    include_component(
      'category',
      'paginator',
      array(
        'slug' => "upravlyai-cenoi",
        'sortOrder' => $sortOrder,
        'sf_cache_key' => "upravlyai-cenoi" . "-" . $sortOrder . "-" . $direction . "-" . $pager->getPage() . "-" . $sf_request->getParameter('page', 1),
        'direction' => $direction,
        'category' => $category,
        'pager' => $pager
      )
    );

    $timerAll->addTime();
    ?>
  </div>
</div>
