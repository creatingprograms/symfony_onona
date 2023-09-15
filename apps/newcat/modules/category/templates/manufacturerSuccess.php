<?php
$h1=$dopinfoProduct[0]->getDopInfo()->getValue();
$title=$manufacturer->getTitle();
$keywords = $manufacturer->getKeywords();
$description =  $manufacturer->getDescription();
$page = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? ', страница №'. sfContext::getInstance()->getRequest()->getParameter('page', 1) :'';
slot('metaTitle', ($title ? $title.$page : $h1.$page.'. Интим товары и игрушки в Он и Она') );
slot('metaKeywords', $keywords ? $keywords : $h1);
slot('metaDescription', $description ? $description.$page : $h1.$page.'. Он и Она - сеть магазинов для взрослых. Только проверенные товары с сертификатом качества. Быстрая анонимная доставка.');
slot('leftBlock', true);
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
if ($manufacturer)
    slot('canonicalSlugManufacturer', $manufacturer->getSlug() . $canonDop);
else
    slot('canonicalSlugManufacturer', sfContext::getInstance()->getRequest()->getParameter('slug') . $canonDop);
?>
<script>
    function changeButtonToGreen(id) {
        $("#buttonId_" + id).removeClass("red-btn");
        $("#buttonId_" + id).addClass("green-btn");
        $("#buttonId_" + id).html("<span>В корзине</span>");
        $("#buttonId_" + id).attr("onclick", "");
        $("#buttonId_" + id).attr("title", "Перейти в корзину");
        $(".popup-holder #buttonIdP_" + id).removeClass("red-btn");
        $(".popup-holder #buttonIdP_" + id).addClass("green-btn");
        $(".popup-holder #buttonIdP_" + id).html("<span>В корзине</span>");
        $(".popup-holder #buttonIdP_" + id).attr("onclick", "");
        $(".popup-holder #buttonIdP_" + id).attr("title", "Перейти в корзину");

        ;
        window.setTimeout('$("#buttonId_' + id + '").attr("href","/cart")', 1000);
        window.setTimeout('$(".popup-holder #buttonIdP_' + id + '").attr("href","/cart")', 1000);
        $("#buttonIdP_" + id).removeClass("red-btn");
        $("#buttonIdP_" + id).addClass("green-btn");
        $("#buttonIdP_" + id).html("<span>В корзине</span>");
        $("#buttonIdP_" + id).attr("onclick", "");
        $("#buttonIdP_" + id).attr("title", "Перейти в корзину");

        window.setTimeout('$("#buttonIdP_' + id + '").attr("href","/cart")', 1000);
    }
</script>
<div class="category-show gtm-category-show" data-list="<?= $h1 ?>">
  <ul class="breadcrumbs">
      <li><a href="/">Главная</a></li>
      <li><a href="/manufacturers">Производители</a></li>
      <li><?= $h1 ?></li>
  </ul>
  <h1><?=$h1?></h1>
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

  </script><? If ($pager->getPage() == 1) { ?>
  <?php if ($manufacturer)
      echo $manufacturer->getContent().'<br style="clear: both;"/>';
  } ?>

  <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
  <div class="yashare-wrapper" style="background: none;  width: 200px; height: 22px; margin-left:-6px;margin-top:-6px;">
      <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
           data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

           ></div>
  </div>
  <?
  if ($manufacturer) {

      if ($sf_user->isAuthenticated()):
          $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", sfContext::getInstance()->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $manufacturer->getId() + 200000)->addWhere("is_enabled='1'")->fetchOne();
          if ($notificationCategory) {
              ?>
              <div class="NotificationCategory">
                  <div style='color: #c3060e;margin-bottom: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе. <a href="/delnotification/<?= $manufacturer->getId() + 200000 ?>" onclick=" $('.NotificationCategory').load($(this).attr('href'));
                      return false;">Отписаться</a></div>
              </div><?
          } else {
              ?><div class="NotificationCategory">
                  <div  class="notification-text"><input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:</div>
                  <div style="float: left;" class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                              return false;
                          }
                          $(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $manufacturer->getId() + 200000 ?>});'></div>

              </div><?
          }
      else:
          ?><div class="NotificationCategory">
              <div class="notification-text-not-autorized">Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:</div>
              <div style="float: right;">
                  <div style="float: left;"><input style="border: 1px solid #b2b2b2; height: 26px; background-color: #FFF;width: 250px;padding-left: 10px;" id="notificationMail" value="Ведите свой e-mail" onClick="$(this).val('');" /></div>
                  <div style="float: left;" class="silverButtonAddToNotification" onClick='$(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $manufacturer->getId() + 200000 ?>, emailAddNotification: $("#notificationMail").val()});'></div>
              </div>
          </div><?
      endif;
      ?>
      <div style="clear: both;"></div>

      <div style="clear: both;"></div>
  <? }
  ?>
  <?
  include_component(
    'category',
    'catalogsorters',
    array(
      'link'=> '/manufacturer/'.sfContext::getInstance()->getRequest()->getParameter('slug') ,
      'isStock'=>$isStock,
      'sortOrder'=>$sortOrder,
      'direction'=>$direction,
      'set'=>1,
    )
  );?>
  <ul class="item-list">
      <?php
      $products = $pager->getResults();

      if ($sf_user->isAuthenticated()):
          $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
          $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
      endif;
      foreach ($products as $prodNum => $product):
        $gdeslonCodes[]='{ id : "'.$product['id'].'", quantity: 1, price: '.$product['price'].'}';
          $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();
          $comments = Doctrine_Core::getTable('Comments')
                  ->createQuery('c')
                  ->where("is_public = '1'")
                  ->addWhere('product_id = ?', $product->getId())
                  ->orderBy('id ASC')
                  ->execute();
          ?>
          <li style="display: none"><?php if ($product->getCount() < 1): ?>
                  <!--
              Сообщить о поступлении
                  -->
                  <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
                      <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>

                      <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px; text-align: center;">Сообщить о поступлении товара</div>

                      <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
                          <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступление товара.</div>
                      <?php else: ?>
                          <script>
                              $(document).ready(function () {
                                  var options = {
                                      target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                      //beforeSubmit:  showRequest,  // pre-submit callback
                                      success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                              // other available options:
                                              //url:       url         // override for form's 'action' attribute
                                              //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                              //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                              //clearForm: true        // clear all form fields after successful submit
                                              //resetForm: true        // reset the form after successful submit

                                              // $.ajax options can be used here too, for example:
                                              //timeout:   3000
                                  };

                                  // bind form using 'ajaxForm'
                                  $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                              });

                              function enableAjaxFormSendUser_<?= $product->getId() ?>() {

                                  var options = {
                                      target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                      //beforeSubmit:  showRequest,  // pre-submit callback
                                      success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                              // other available options:
                                              //url:       url         // override for form's 'action' attribute
                                              //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                              //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                              //clearForm: true        // clear all form fields after successful submit
                                              //resetForm: true        // reset the form after successful submit

                                              // $.ajax options can be used here too, for example:
                                              //timeout:   3000
                                  };

                                  // bind form using 'ajaxForm'
                                  $('#senduser_<?= $product->getId() ?>').ajaxForm(options);
                              }

                              function showResponse_<?= $product->getId() ?>(responseText, statusText, xhr, $form) {
                                  var options = {
                                      target: '.highslide-maincontent #senduserdiv_<?= $product->getId() ?>', // target element(s) to be updated with server response
                                      //beforeSubmit:  showRequest,  // pre-submit callback
                                      success: showResponse_<?= $product->getId() ?>  // post-submit callback

                                              // other available options:
                                              //url:       url         // override for form's 'action' attribute
                                              //type:      type        // 'get' or 'post', override for form's 'method' attribute
                                              //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
                                              //clearForm: true        // clear all form fields after successful submit
                                              //resetForm: true        // reset the form after successful submit

                                              // $.ajax options can be used here too, for example:
                                              //timeout:   3000
                                  };

                                  // bind form using 'ajaxForm'
                                  $('.highslide-container #senduser_<?= $product->getId() ?>').ajaxForm(options);

                                  $('.senduser_<?= $product->getId() ?>').each(function () {
                                      $(this).ajaxForm(options);
                                  });
                              }
                          </script>
                          <div id="senduserdiv_<?= $product->getId() ?>">
                              <form id="senduser_<?= $product->getId() ?>" class="senduser_<?= $product->getId() ?>"  action="/product/<?= $product->getSlug() ?>/senduser" method="post">
                                  Данный товар скоро появится в продаже на сайте. Чтобы своевременно узнать об этом, нужно отправить нам запрос. Для этого в соответствующих полях напишите свое имя, e-mail и нажмите на кнопку "Отправить запрос". Как только товар поступит на склад, мы отправим уведомление на указанный вами e-mail.<br /><br />
                                  <div style="clear: both; color:#4e4e4e; text-align: left;">
                                      <table style=" width: 100%" class="noBorder">
                                          <tbody><tr>
                                                  <td style="width: 120px;padding: 5px 0;text-align: left;">
                                                      Представьтесь*:
                                                  </td>
                                                  <td style="padding: 5px 0;text-align: left;">
                                                      <input type="text" name="name" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") != "" ? sfContext::getInstance()->getRequest()->getParameter("name") : $nameSenduser ?>" style="width: 254px;">
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                      Ваш e-mail*:
                                                  </td>
                                                  <td style="padding: 5px 0;text-align: left;">
                                                      <input type="text" name="mail" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") != "" ? sfContext::getInstance()->getRequest()->getParameter("mail") : $mailSenduser ?>" style="width: 254px;">
                                                  </td>
                                              </tr>
                                              <tr>
                                                  <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                      Введите код*:
                                                  </td>
                                                  <td style="padding: 5px 0;text-align: left;">

                                                      <img  class="captchasu" src="/images/pixel.gif" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                                      <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
                                                      <?php if ($errorCapSu) { ?><br />
                                                          <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                                          <br />
                                                      <?php } ?>
                                                  </td>
                                              </tr>
                                          </tbody></table>
                                      <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                                      <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#" onclick = "$('#senduser_<?= $product->getId() ?>').submit();
                                              return false;"><span style = "width: 195px;">Отправить запрос</span></a>

                                  </div>

                              </form></div>
                      <?php endif; ?>
                  </div>
                  <!--
              Сообщить о поступлении
                  -->
                  <? Endif; ?></li>
          <?


          if ($product->getCount() < 1) {
              $productCountLast = ProductTable::getInstance()->createQuery()->where("parents_id=?", $product->getId())->addWhere("count>0")->addwhere("is_public=1")->addwhere("is_visiblecategory=0")->fetchOne();
              if ($productCountLast)
                  $product = $productCountLast;
          }
          include_component(
            'category',
            'products',
            array(
              'slug' => $sf_request->getParameter('slug'),
              'product' => $product,
              'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article') . "-" . ((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast') . "-manufacturer",
              'prodCount' => $prodCount,
              'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
              'last' => ((($prodNum + 1) % 3) == 0 ? true : false),
              "productsKeys" => implode(",", $products->getPrimaryKeys())
            )
          );

      endforeach;
      ?>
  </ul><!--item-list end-->

  <?php if ($pager->haveToPaginate()):
      if ($sortOrder != "") {
          $sortingUrl = "&sortOrder=" . $sortOrder . "&direction=" . $direction;
  } ?>

    <?php
      JSInPages("function setPages(id){
      document.location.href = '" . url_for('manufacturer_show', array('slug' => $dopinfoProduct[0]->getDopInfo()->getId())) . "?page='+id+'" . $sortingUrl . "';

                      return false;
                  }");
    ?>

      <div class="paginator-box" style="width: <?= (((count($pager->getLinks(9)) - 9) > 0 ? 9 : count($pager->getLinks(9)))) * 30 + 5 ?>px;float: right;margin-top: 25px;padding: 0 90px 0 90px;">
          <?php if ($pager->getPage() != 1) {
              ?>
              <a href="<?php echo url_for('manufacturer_show', array('slug' => $dopinfoProduct[0]->getDopInfo()->getId())) ?>" onclick="setPages(<?= $pager->getPage() - 1 ?>);
                      return false;" style="    width: 80px;
                 height: 23px;
                 top: 0;
                 left: 0;
                 overflow: hidden;
                 position: absolute;
                 text-decoration: none;
                 padding: 6px 0;
                 ">Предыдущая</a>
             <?php }
             ?>
             <?php if ($pager->getPage() != count($pager->getLinks(100))) {
                 ?>
              <a href="<?php echo url_for('manufacturer_show', array('slug' => $dopinfoProduct[0]->getDopInfo()->getId())) ?>" onclick="setPages(<?= $pager->getPage() + 1 ?>);
                      return false;" style="    width: 80px;
                 height: 23px;
                 top: 0;
                 overflow: hidden;
                 position: absolute;
                 text-decoration: none;
                 right:0;
                 padding: 6px 0;">Следующая</a>
             <?php }
             ?>
             <?php
             $page = $pager->getPage();
             ?>
          <div class="paginator" style="width: <?= (((count($pager->getLinks(100)) - 9) > 0 ? 9 : count($pager->getLinks(100)))) * 30 + 5 ?>px;">
              <ul>

                  <?php
                  if (count($pager->getLinks(100)) <= 9) {
                      for ($i = 1; $i <= count($pager->getLinks(100)); $i++) {
                          ?>
                          <li<?= $i == $page ? ' class="active"' : '' ?>>
                              <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)">
              <?= $i ?>
                              </div>
                          </li>
                          <?php
                      }
                  } elseif ($page <= 5) {
                      for ($i = 1; $i <= 7; $i++) {
                          ?>
                          <li<?= $i == $page ? ' class="active"' : '' ?>>
                              <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
              <?= $i ?>
                              </div>
                          </li>
                          <?php
                      }
                      ?>
                      <li>
                          •••
                      </li>
                      <li>
                          <div onclick="setPages(<?= count($pager->getLinks(100)) ?>)">
          <?= count($pager->getLinks(100)) ?>
                          </div>
                      </li>
                      <?php
                  } elseif ($page < count($pager->getLinks(100)) - 4) {
                      ?>
                      <li>
                          <div onclick="setPages(1)" class="pageId-1">
                              1
                          </div>
                      </li>
                      <li>
                          •••
                      </li>
                      <?php
                      for ($i = $page - 2; $i <= $page + 2; $i++) {
                          ?>
                          <li<?= $i == $page ? ' class="active"' : '' ?>>
                              <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
              <?= $i ?>
                              </div>
                          </li>
                          <?php
                      }
                      ?>
                      <li>
                          •••
                      </li>
                      <li>
                          <div onclick="setPages(<?= count($pager->getLinks(100)) ?>)">
          <?= count($pager->getLinks(100)) ?>
                          </div>
                      </li>
                      <?php
                  } else {
                      ?>
                      <li>
                          <div onclick="setPages(1)" class="pageId-1">
                              1
                          </div>
                      </li>
                      <li>
                          •••
                      </li>
                      <?php
                      for ($i = count($pager->getLinks(100)) - 6; $i <= count($pager->getLinks(100)); $i++) {
                          ?>
                          <li<?= $i == $page ? ' class="active"' : '' ?>>
                              <div<?= $i == $page ? ' class="active pageId-' . $i . '"' : ' class="pageId-' . $i . '"' ?> onclick="setPages(<?= $i ?>)" class="pageId-<?= $i ?>">
              <?= $i ?>
                              </div>
                          </li>
                          <?php
                      }
                  }
                  ?>
              </ul>
          </div>
      </div>
  <?php endif; ?>
</div>
<? slot('gdeSlonMode', 'list'); ?>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
<style>
  #sidebar .box.brand-galery{
    display: none;
  }
</style>
