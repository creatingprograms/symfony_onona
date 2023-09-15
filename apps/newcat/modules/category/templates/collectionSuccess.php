<?php
$h1=$dopinfoProduct[0]->getDopInfo()->getValue();
$pageMeta== sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? ', страница №'. sfContext::getInstance()->getRequest()->getParameter('page', 1) :'';
slot('metaTitle', ((!empty($collection) && $collection->getTitle()) ? $collection->getTitle() : $h1.', Интим товары и игрушки в Он и Она').$pageMeta );
slot('metaKeywords', ((!empty($collection) && $collection->getKeywords()) ? $collection->getKeywords() : $h1));
slot('metaDescription', ((!empty($collection) && $collection->getDescription()) ? $collection->getDescription() : $h1.'. Он и Она - сеть магазинов для взрослых. Только проверенные товары с сертификатом качества. Быстрая анонимная доставка').$pageMeta);
slot('leftBlock', true);
$canonDop = sfContext::getInstance()->getRequest()->getParameter('page', 1) > 1 ? "?page=" . sfContext::getInstance()->getRequest()->getParameter('page', 1) : "";
if ($collection)
    slot('canonicalSlugCollection', $collection->getSlug() . $canonDop);
else
    slot('canonicalSlugCollection', sfContext::getInstance()->getRequest()->getParameter('slug') . $canonDop);
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
<ul class="breadcrumbs">
    <li><a href="/">Главная</a></li>
    <li><a href="/collections">Коллекции</a></li>
    <li><?= $h1 ?></li>
</ul>
<h1><?= $h1 ?></h1>
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
<? If ($pager->getPage() == 1) { ?>
    <?php if ($collection) echo '<div class="collections-content">'.$collection->getContent().'</div>' ?><br /><? } ?>


<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8" async></script>
<div class="yashare-wrapper" style="background: none;  width: 200px; height: 22px; margin-left:-6px;margin-top:-6px;">
    <div class="yashare-auto-init" data-yashareType="icon" data-yashareL10n="ru"
         data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,gplus" <? /* data-yashareTheme="counter" */ ?>

         ></div>
</div>
<?
if ($collection) {

    if ($sf_user->isAuthenticated()):
        $notificationCategory = NotificationCategoryTable::getInstance()->createQuery()->where("user_id=?", sfContext::getInstance()->getUser()->getGuardUser()->getId())->addWhere("category_id=?", $collection->getId()+100000)->addWhere("is_enabled='1'")->fetchOne();
        if ($notificationCategory) {
            ?>
            <div class="NotificationCategory">
                <div class="dinb" style='color: #c3060e;margin-bottom: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе. <a href="/delnotification/<?= $collection->getId()+100000 ?>" onclick=" $('.NotificationCategory').load($(this).attr('href'));
                    return false;">Отписаться</a></div>
            </div><?
        } else {
            ?><div class="NotificationCategory">
                <div  class="dinb" style="color: #c3060e;padding-top: 5px; margin-right: 10px;">
                  <input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:
                </div>
                <div class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                                        return false;
                                    }
                                    $(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $collection->getId()+100000 ?>});'>Подписаться</div>

            </div><?
        }
    else:
        ?><div class="NotificationCategory">
            <div  class="dinb" style="color: #c3060e;width: 240px; position: relative;top: -4px;">Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:</div>
            <div class="dinb">
                <div class="dinb"><input style="border: 1px solid #b2b2b2; height: 26px; background-color: #FFF;width: 250px;padding-left: 10px;" id="notificationMail" value="Ведите свой e-mail" onClick="$(this).val('');" /></div>
                <div class="silverButtonAddToNotification" onClick='$(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $collection->getId()+100000 ?>, emailAddNotification: $("#notificationMail").val()});'>Подписаться</div>
            </div>
        </div><?
    endif;
    ?>

    <? }

?>
<?
include_component(
  'category',
  'catalogsorters',
  array(
    'link'=> '/collection/'.sfContext::getInstance()->getRequest()->getParameter('slug'),
    'isStock'=>$isStock,
    'sortOrder'=>$sortOrder,
    'direction'=>$direction,
    'set'=>1,
    // 'sf_cache_key' => $sf_request->getParameter('slug') . "-" . sfContext::getInstance()->getRouting()->getCurrentRouteName()
  )
);?>
<ul class="item-list gtm-category-show" data-list="<?= $h1 ?>">
    <?php
    $products = $pager->getResults();

    if ($sf_user->isAuthenticated()):
        $nameSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getFirstName();
        $mailSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress();
        $phoneSenduser = sfContext::getInstance()->getUser()->getGuardUser()->getPhone();
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
                    <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous">
                      <a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>

                    <div style="color: #C3060E; font: 17px/21px Tahoma,Geneva,sans-serif;margin-bottom: 10px; text-align: center;">Сообщить о поступлении товара</div>

                    <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
                        <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступлении товара.</div>
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

                            function ajaxSend (form){
                              var
                                phone = form.find('input[name="phone"]').val(),
                                name = form.find('input[name="name"]').val(),
                                mail = form.find('input[name="mail"]').val(),
                                regexp;
                              if (name=='') {
                                form.find('input[name="name"]').focus();
                                return;
                              }
                              regexp = new RegExp('^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$');
                              if (mail=='' || !regexp.test(mail)) {
                                form.find('input[name="mail"]').focus();
                                return;
                              }
                              regexp = /^[\d\+\-\(\)\s]{6,19}$/;
                              if (phone=='' || !regexp.test(phone)) {
                                form.find('input[name="phone"]').focus();
                                return;
                              }
                              if (form.find('input[name="sucaptcha"]').val()=='') {
                                form.find('input[name="sucaptcha"]').focus();
                                return;
                              }
                              var data={
                                'mail' : mail,
                                'phone' : phone,
                                'name' : name,
                                'sucaptcha' : 'robot will not pass!',

                              }
                              $.ajax({
                                type: 'post',
                                data: data,
                                url: form.attr('action'),
                                success: function (response){
                                  form.parent().html(response);
                                  // console.log(response);
                                }
                              });
                            }

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
                                        <tbody>
                                          <tr>
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
                                                  Ваш телефон*:
                                              </td>
                                              <td style="padding: 5px 0;text-align: left;">
                                                  <input type="text" name="phone" value="<?= sfContext::getInstance()->getRequest()->getParameter("phone") != "" ? sfContext::getInstance()->getRequest()->getParameter("phone") : $phoneSenduser ?>" style="width: 254px;">
                                              </td>
                                          </tr>
                                          <tr>
                                              <td style="width: 120px; padding: 5px 0;text-align: left;">
                                                  Введите код*:
                                              </td>
                                              <td style="padding: 5px 0;text-align: left;">

                                                  <?/*<img  class="captchasu" src="/images/pixel.gif" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />*/?>
                                                  <img  class="captchasu" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" data-original="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>" alt="Captcha" />
                                                  <input type="text" name="sucaptcha" style="position: relative; top: -45px; width: 130px;">
                                                  <?php if ($errorCapSu) { ?><br />
                                                      <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
                                                      <br />
                                                  <?php } ?>
                                              </td>
                                          </tr>
                                        </tbody>
                                    </table>
                                    <span style="font-size: 10px;">* - обязательны для заполнения.</span>
                                    <a  style="margin-top: 20px; margin-left: 155px;" class = "red-btn colorWhite" href = "#"
                                      onclick = "ajaxSend($(this.closest('form'))); return false; $('#senduser_<?= $product->getId() ?>').submit();
                                                        return false;"><span style = "width: 195px;">Отправить запрос</span></a>

                                </div>

                            </form></div>
                    <?php endif; ?>
                </div>
                <!--
            Сообщить о поступлении
                -->
                <? Endif; ?></li><?
            /*  ?><li>
              <!--
              Сообщить о поступлении
              -->
              <div style="display: none" class="highslide-maincontent" id="ContentToSend_<?= $product->getId() ?>">
              <div class="highslide-header" style="height: 0;"><ul><li class="highslide-previous"><a onclick="return hs.previous(this)" title="Предыдущая (arrow left)" href="#"><span>Предыдущая</span></a></li><li class="highslide-next"><a onclick="return hs.next(this)" title="Следующая (arrow right)" href="#"><span>Следующая</span></a></li><li class="highslide-move"><a onclick="return false" title="Переместить" href="#"><span>Переместить</span></a></li><li class="highslide-close"><a onclick="return hs.close(this)" title="Закрыть (esc)" href="#"><span>Закрыть</span></a></li></ul></div>
              <img src="/images/topToSend.png" alt="Сообщить о поступлении">

              <?php if (!$errorCapSu and $sf_params->get('senduser')): ?>
              <div style="width:100%; text-align: center;">Спасибо за запрос. Вам будет сообщено о поступление товара.</div>
              <?php else: ?>
              <form id="senduser-<?= $product->getId() ?>" action="/product/<?= $product->getSlug() ?>/senduser" method="post">
              <div style="clear: both; color:#4e4e4e; text-align: left;">
              <table style="width:100%" class="noBorder">
              <tbody><tr>
              <td style="width:120px;color:#073f72;padding: 5px 0;text-align: left;">
              Представьтесь<span style="color: #ff94bc;">*</span>
              </td>
              <td style="padding: 5px 0;text-align: left;">
              <input type="text" name="name" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;" value="<?= sfContext::getInstance()->getRequest()->getParameter("name") ?>">
              </td>
              </tr>
              <tr>
              <td style="width:120px;color:#073f72;padding: 5px 0;text-align: left;">
              Ваш e-mail<span style="color: #ff94bc;">*</span>
              </td>
              <td style="padding: 5px 0;text-align: left;">
              <input type="text" name="mail" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;" value="<?= sfContext::getInstance()->getRequest()->getParameter("mail") ?>">
              </td>
              </tr>
              </tbody></table>
              <span style="font-size: 10px;">Поля отмеченные * обязательны для заполнения.</span>
              <div style="width:100%; text-align: center;">
              <br />
              <img alt="captcha" src="/captcha/sucaptcha.php?<?php echo session_name() ?>=<?php echo session_id() ?>">
              <br /><br />
              <input type="text" name="sucaptcha" style="padding: 0 5px;border-radius: 10px; width: 190px; height: 20px;border-color: #073f72;">
              <br />
              <?php if ($errorCapSu) { ?>
              <span style="font-size: 10px;color: red;">Ошибка. Попробуйте ещё раз.</span>
              <br />
              <?php } ?>
              <span style="font-size: 10px;">Введите текст с картинки.</span>
              <br />
              <br />
              <input type="submit" style="width: 198px; position: relative; left: 0px; top: 0px; margin: 5px 0px 7px 0px;" value="Отправить запрос" id="addToBasket1-<?= $product->getId() ?>">
              </div>

              </div>
              </form>
              <?php endif; ?>
              </div>
              <!--
              Сообщить о поступлении
              -->


              <div class="t"></div>
              <div class="c">
              <div class="content">
              <?= $product->getDiscount() > 0 ? '<span class="sale' . $product->getDiscount() . '">-' . $product->getDiscount() . '%</span>' : ''; ?>
              <div class="title"><a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a></div>
              <?= ($product->getVideo() != ''  or $product->getVideoenabled() )  ? '<span class="videoProduct" title="К этому товару есть видео-презентация"></span>' : ''; ?>
              <div class="img-holder">
              <a href="/product/<?= $product->getSlug() ?>"><img id="photoimg_<?= $product->getId() ?>" src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" /></a>

              </div>
              <div class="bottom-box">
              <div class="row">
              <? if ($comments->count() > 0) { ?><a href="/product/<?= $product->getSlug() ?>/?comment=true#comments" class="rewiev">Отзывы: <?= $comments->count() ?></a><? } ?>
              <div class="stars">
              <span style="width:<?= $product->getRating() > 0 ? (@round($product->getRating() / $product->getVotesCount())) * 10 : 0 ?>%;"></span>
              </div>
              </div>
              <div class="price-box">
              <?php if ($product->getOldPrice() > 0) { ?>
              <span class="old-price"><?= number_format($product->getOldPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
              <span class="new-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
              <? } else { ?>
              <span class="price"><?= number_format($product->getPrice(), 0, '', ' ') ?>    <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
              <? } ?>
              </div>
              <div class="tools">
              <a href="#" class="att"></a><?php if ($product->getCount() > 0): ?>
              <a href="#" id="buttonId_<?= $product->getId() ?>" class="red-btn small to-card" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: 1 },  function(data) {});
              addToCartAnim('Cart', '#photoimg_<?= $product->getId() ?>', true);changeButtonToGreen(<?= $product->getId() ?>);
              ">

              <span>В корзину</span>
              </a>
              <a href="#" class="to-desire" onClick='javascript: addToCartAnim("Jel", "#photoimg_<?= $product->getId() ?>", true);$("#JelHeader").load("/cart/addtojel/<?= $product->getId() ?>");'></a>

              <?php else: ?>
              <a id="addToBasket3-<?= $product->getId() ?>" class="red-btn to-card small highslide" onclick="return hs.htmlExpand(this, { contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
              headingText: '', width: 553, height: 470, slideshowGroup: 'groupToSend', left: -9 } )"
              style=""><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

              <?php endif; ?>
              </div>
              </div>
              <div class="popup-content" style="display:none">
              <h2 class="title centr"><?= $product->getName() ?></h2>
              <div class="item-box">
              <div class="item-media">
              <div class="img-holder">
              <a href="#"><img id="photoimg_pr_<?= $product->getId() ?>" src="/images/pixel.gif" data-original="/uploads/photo/thumbnails_250x250/<?= $photos[0]['filename'] ?>" alt='<?= str_replace(array("'", '"'), "", $product->getName()) ?>' class="thumbnails" /></a>
              </div>
              </div>
              <div class="item-char">
              <form action="/cart/addtocart/<?= $product->getId() ?>" class="search">
              <fieldset>
              <?php
              $arrayDopInfo = array();
              $dopInfos = $product->getDopInfoProducts();
              if ($product->getParent() != "")
              $productProp = $product->getParent();
              else
              $productProp = $product;
              $i = 0;
              $childrens = $productProp->getChildren();
              $childrens[] = $productProp;
              foreach ($dopInfos as $key => $property):
              $doparray['value'] = $property['value'];
              $doparray['product_id'] = $product->getSlug() != "" ? $product->getSlug() : $product->getId();

              $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
              $doparray['sort'] = $dopInfoCategory->getPosition();


              $doparray['dopInfoID'] = $property->getId();
              $arrayDopInfo[$property['dicategory_id']][] = $doparray;
              foreach ($childrens as $children) {
              foreach ($children->getDopInfoProducts() as $dopInfoChildren) {
              //echo $dopInfoChildren['value'];
              //echo $dopInfoChildren['dicategory_id'].' '.$property['dicategory_id']." упымап ";
              if ($dopInfoChildren['dicategory_id'] == $property['dicategory_id'] and $children->getId() != $product->getId()) {

              $in_array_di = false;

              foreach ($arrayDopInfo[$property['dicategory_id']] as $value) {
              //print_r($value);
              if (in_array($dopInfoChildren['value'], $value)) {
              //print_r($dopInfoChildren['value']);

              $in_array_di = true;
              }
              }

              if ($in_array_di === false) {
              //echo "<pre>".$dopInfoChildren['value'];
              $doparray['value'] = $dopInfoChildren['value'];
              $doparray['product_id'] = $children->getSlug() != "" ? $children->getSlug() : $children->getId();
              $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($property['dicategory_id']);
              $doparray['sort'] = $dopInfoCategory->getPosition();
              $arrayDopInfo[$property['dicategory_id']][] = $doparray;
              }
              //print_r($arrayDopInfo);
              }
              }
              }
              $i++;

              endforeach; //print_r($arrayDopInfo);
              //print_r($arrayDopInfo);
              unset($sort_numcie, $arrayDopInfoKeys);
              foreach ($arrayDopInfo as $c => $key) {
              $sort_numcie[$c] = $key['0']['sort'];
              }
              $sort_numcie2 = $sort_numcie;
              array_multisort($sort_numcie, SORT_ASC, $arrayDopInfo);
              asort($sort_numcie2);

              foreach ($sort_numcie2 as $c => $null) {
              $arrayDopInfoKeys[] = $c;
              }
              //print_r($arrayDopInfo);
              $arrayDopInfo = array_combine($arrayDopInfoKeys, $arrayDopInfo);
              if ($arrayDopInfo):
              foreach ($arrayDopInfo as $key => $property):
              $key_old = $key;
              $dopInfoCategory = DopInfoCategoryTable::getInstance()->findOneById($key);
              $key = $dopInfoCategory->getName();
              ?>

              <?php
              if (count($property) == 1):

              if ($key == "Таблица размеров" and $property[0]['value'] == 1):
              $tableSize = true;
              else:
              ?>
              <dl>
              <dt>
              <input type="hidden" name="productOptions[]" value="<?= $property[0]['dopInfoID'] ?>" />
              <?= $key ?>:</dt>
              <dd><?php
              if ($key == "Производитель") {
              echo "<a href=\"/collection/" . $property[0]['dopInfoID'] . "\">" . $property[0]['value'] . "</a>";
              } else {
              echo $property[0]['value'];
              }
              ?></dd></dl>
              <?php
              endif;
              else:
              ?><dl>
              <dt><?= $key ?>:</dt>
              <dd>
              <script>
              function changeArtCode(select){
              document.location.href = "/product/"+select.value;
              }
              </script>
              <select name="productOptions[]" onchange="changeArtCode(this)" style="width: 82px;" id="select_<?= $i ?>-<?= $product->getId() ?>">
              <?php foreach ($property as $keySub => $sub): ?>
              <option value="<?= $sub['product_id'] ?>"><?= $sub['value'] ?></option>
              <?php endforeach; ?>
              </select>
              </dd></dl>
              <?php endif; ?>

              <?php
              endforeach;
              endif;
              ?>

              <dl>
              <dt>Количество:</dt>
              <dd><?php if ($product->getCount() > 0): ?><input type="number" required="required" id="count_<?= $product->getId() ?>" value="1" min="1" max="100" name="quantity" style="width: 30px;" /><?php else: ?>
              Нет в наличии

              <?php endif; ?></dd>
              </dl>
              <div class="more-expand-holder">
              <dl style="display: none;" class="productCode">
              <dt>Артикул:</dt>
              <dd class="artCode"><?= $product->getCode() ?></dd>
              </dl>
              <a href="#" class="more-expand" onclick=" $(this).prevAll('.productCode').toggle();return false"></a>
              </div>
              <div class="price-box">
              <div class="row">
              <div class="btn-holder">
              <?php if ($product->getCount() > 0): ?>
              <a href="#" id="buttonIdP_<?= $product->getId() ?>" class="red-btn to-card small" onClick="javascript:$.post('/cart/addtocart/<?= $product->getId() ?>', { quantity: $('.popup-holder #count_<?= $product->getId() ?>').val() },  function(data) {});
              addToCartAnim('Cart', '#photoimg_pr_<?= $product->getId() ?>');changeButtonToGreen(<?= $product->getId() ?>);
              ">
              <span>В корзину</span>
              </a>
              <a href="#" class="to-desire" onClick='javascript: addToCartAnim("Jel", "#photoimg_pr_<?= $product->getId() ?>");$("#JelHeader").load("/cart/addtojel/<?= $product->getId() ?>");'></a>
              <?php else: ?>
              <a id="addToBasket4-<?= $product->getId() ?>" class="red-btn to-card small highslide" onclick="return hs.htmlExpand(this, { contentId: 'ContentToSend_<?= $product->getId() ?>', outlineType: 'rounded-white', wrapperClassName: 'draggable-header no-controlbar',
              headingText: '', width: 553, height: 470, slideshowGroup: 'groupToSend', left: -9 } )"
              style=""><span style="font-size: 12px; width: 160px; padding: 4px 0pt 0pt 5px;">Сообщить о поступлении</span></a>

              <?php endif; ?>
              </div>
              <div class="price-col">
              <div class="title">&nbsp;</div>
              <span class="new-price"><?= number_format($product->getPrice(), 0, '', ' ') ?> <span style="font-family: 'PT Sans Narrow', sans-serif;">&#8381;</span></span>
              </div>
              </div>
              </div>
              </fieldset>
              </form>
              </div>
              </div>
              <?php if ($product->getoldPrice() == "" or $product->getoldPrice() == 0): ?>
              <div class="bonus-box"><div class="plashbon" onClick="location.replace('http://club.onona.ru/index.php/topic/116-programma-on-i-ona--bonus/'); "><?= $product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add') ?>%
              <span class="plashbontxt">= <?=round($product->getPrice()*(($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add'))/100))?> бонусов возвращаем на ваш личный счет / 1 бонус = 1 руб.</span></div>
              </div>
              <? Endif; ?>
              <h2 class="title">Описание товара</h2>
              <div class="info-box"><?php if ($product->getVideo() != ''): ?>
              <div class="video-holder">
              <a href="#" class="player" onClick="
              hdwebplayer({
              id       : 'playerVideoDiv',
              swf      : '/player/player.swf?api=true',
              width    : '620',
              height   : '366',
              margin   : '15',
              video    : '<?= str_replace(array("http://www.onona.ru/video/", "http://onona.ru/video/", "http://new.onona.ru/video/"), "/uploads/video/", $product->getVideo()) ?>',
              autoStart: 'true',
              shareDock: 'false'
              });
              $('.close').click(function(){
              $('#playerBG').remove();
              $('#playerdiv').css('display','none');
              player = document.getElementById('playerVideoDiv');
              player.stopVideo();
              });

              $('#playerdiv').css({'display':'block',
              'position':'fixed',
              'top':(($(window).height() - $('#playerdiv').height())/2),
              'left':(($(window).width() - $('#playerdiv').width())/2)});


              return false;
              ">
              <img src="/newdis/images/video.png" width="142" height="90" alt="image description" />
              <span class="name">Видео-презентация</span>
              <span class="play"></span>
              </a>
              </div><?php endif; ?>
              <?= $product->getContent() ?>
              </div>
              <div class="social-box">
              <div class="row">
              <a href="javascript:CallPrint('.quick-view');" class="print"><img src="/newdis/images/ico02.png" width="16" height="16" alt="image description" /></a>

              </div>
              <div class="row">

              </div>
              </div>
              </div>
              </div>
              </div>
              <div class="b"></div>
              </li>
              <?php */
            include_component('category', 'products',
              array(
                'slug' => $sf_request->getParameter('slug'),
                'product' => $product,
                'sf_cache_key' => $product->getId() . "-" . sfContext::getInstance()->getUser()->hasPermission('Show article')."-".((($prodNum + 1) % 3) == 0 ? 'last' : 'nolast')."-collection",
                'prodCount' => $prodCount,
                'showarticle' => sfContext::getInstance()->getUser()->hasPermission('Show article'),
                'last' => ((($prodNum + 1) % 3) == 0 ? true : false),
                "productsKeys" => implode(",", $products->getPrimaryKeys())
              )
            );

        endforeach;
        ?>
</ul><!--item-list end-->
<?php
if ($pager->haveToPaginate()):
    if ($sortOrder != "") {
        $sortingUrl = "&sortOrder=" . $sortOrder . "&direction=" . $direction;
    }
    ?>

    <?php
    JSInPages("function setPages(id){
    document.location.href = '" . url_for('collection_show', array('slug' => $dopinfoProduct[0]->getDopInfo()->getId())) . "?page='+id+'" . $sortingUrl . "';

                    return false;
                }");
    ?>

    <div class="paginator-box" style="width: <?= (((count($pager->getLinks(9)) - 9) > 0 ? 9 : count($pager->getLinks(9)))) * 30 + 5 ?>px;float: right;margin-top: 25px;padding: 0 90px 0 90px;">
        <?php if ($pager->getPage() != 1) {
            ?>
            <a href="<?php echo url_for('collection_show', array('slug' => $dopinfoProduct[0]->getDopInfo()->getId())) ?>" onclick="setPages(<?= $pager->getPage() - 1 ?>);
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
            <a href="<?php echo url_for('collection_show', array('slug' => $dopinfoProduct[0]->getDopInfo()->getId())) ?>" onclick="setPages(<?= $pager->getPage() + 1 ?>);
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
<? slot('gdeSlonMode', 'list'); ?>
<? slot('gdeSlonCodes', 'products: [ '.implode(', '."\n", $gdeslonCodes).' ],'); ?>
