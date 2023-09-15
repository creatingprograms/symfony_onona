
<ul class="breadcrumbs">
    <li>
        <a href="/">Главная</a>
    </li>
    <li>
        Мои оповещения
    </li>
</ul>

<h1 class="title">Управление оповещениями</h1>
<script>
    function settingNotification($id) {
        $('<div/>').click(function (e) {
            if (e.target != this)
                return;
            $(this).remove();
        }).css("padding-top", $(window).scrollTop() + 100).css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockSettingNotification" + $id).addClass("blockSettingNotification").appendTo('body');
        $('.blockSettingNotification' + $id).html($(".SettingNotificationBlock" + $id).html());
        $(document).keyup(function (e) {

            if (e.keyCode == 27) {
                $('.blockSettingNotification' + $id).remove();
            }   // esc
        });
    }
</script>
<div class="CustomersNotification">
    Настроенные оповещения будут отправлены на ваш e-mail: <?= $sf_user->getGuardUser()->getEmailAddress() ?><br /><br />
    <?
      $notificationUser = NotificationTable::getInstance()->createQuery()->where("user_id=?", $sf_user->getGuardUser()->getId())->groupBy("product_id")->execute();
      if ($notificationUser->count() > 0): ?>
        <table>
            <?
             foreach ($notificationUser as $productInfo):
                if ($productInfo->getProductId() != ""):
                    $product = ProductTable::getInstance()->findOneById($productInfo->getProductId());

                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    ?>
                    <tr>
                      <td>
                        <a href="/product/<?= $product->getSlug() ?>">
                          <img src="/uploads/photo/thumbnails_250x250/<?= $photos[0]->getFilename() ?>">
                        </a>
                      </td>
                        <td><a href="/product/<?= $product->getSlug() ?>"><?= $product->getName() ?></a></td>
                        <td>
                          <div class="customSetNotificationCategory">
                            <?
                              $notificationProduct = NotificationTable::getInstance()->createQuery()->where("user_id=?", $sf_user->getGuardUser()->getId())->addWhere("product_id=?", $productInfo->getProductId())->execute();
                              $infoWrite = false;
                              foreach ($notificationProduct as $notification):
                                if ($notification->getType() == "Action") { ?>
                                  <div class="dinb">
                                    <div class="notificationAction"></div>
                                    <span>Акции</span>
                                  </div>
                                <? }
                                elseif (($notification->getType() == "Comment" or $notification->getType() == "UserPhoto") and ! $infoWrite) {
                                  $infoWrite = true;
                                  ?>
                                    <div class="dinb">
                                      <div class="notificationComment"></div>
                                      <span>Информация</span>
                                    </div>
                                <?}
                                if ($notification->getType() == "ReminderThisProd") { ?>
                                  <div class="dinb">
                                    <div class="notificationReminderThisProd"></div>
                                    <span>Напоминание</span>
                                  </div>
                                <? }
                            endforeach;
                            ?>

                            </div>

                            <div class="SettingNotificationBlock<?= $productInfo->getProductId() ?>" style="display: none;">
                                <div class="form-popup-wrapper">
                                    <div class="form-popup-content">

                                        <div onClick="$('.blockSettingNotification<?= $productInfo->getProductId() ?>').remove();" class='close'></div>

                                        <div class="header">Настроить оповещения</div><br/>
                                        <div class="settingNotificationContent settingNotificationContent<?= $productInfo->getProductId() ?>">
                                            Оповестить меня по e-mail, когда:
                                            <form action="/setnotification" class="setNotification<?= $productInfo->getProductId() ?>" method="POST">
                                                <input type="hidden" name="productId" value="<?= $product->getId() ?>">
                                                <table style="width: 100%;">
                                                  <tr>
                                                        <td>
                                                            <div class="notificationAction">
                                                                <span>Акции</span>
                                                            </div>
                                                            <div class="dinb">
                                                              <input type="checkbox" name="ThisProd"<?
                                                                if ($sf_user->getGuardUser())
                                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                                        echo " checked";
                                                                    }
                                                                ?>>
                                                            </div>
                                                            <div class="notification-field"> Акция только для этого товара</div><br>
                                                            <div class="dinb">
                                                              <input type="checkbox" name="ActionAllProd"<?
                                                                if ($sf_user->getGuardUser())
                                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='Action'")->addWhere("product_id is null")->fetchOne()) {
                                                                        echo " checked";
                                                                    }
                                                                ?>>
                                                              </div>
                                                              <div class="notification-field"> Все акции сайта</div>
                                                        </td>
                                                        <td>
                                                            <div class="notificationComment">
                                                                <span>Информация</span>
                                                            </div>
                                                            <div class="dinb">
                                                              <input type="checkbox" name="CommentThisProd"<?
                                                                if ($sf_user->getGuardUser())
                                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='Comment'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                                        echo " checked";
                                                                    }
                                                                ?>>
                                                            </div>
                                                            <div class="notification-field"> Новые отзывы к этому товару</div><br>
                                                            <div class="dinb">
                                                              <input type="checkbox" name="UserPhotoThisProd"<?
                                                                if ($sf_user->getGuardUser())
                                                                    if (NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='UserPhoto'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                                        echo " checked";
                                                                    }
                                                                ?>></div>
                                                            <div class="notification-field"> Новые фото от пользователей</div>
                                                        </td>
                                                        <td>
                                                            <div class="notificationReminderThisProd">
                                                                <span>Напоминание</span>
                                                            </div>
                                                            <div class="dinb"><input type="checkbox" name="ReminderThisProd"<?
                                                                if ($sf_user->getGuardUser())
                                                                    if ($notificationReminder = NotificationTable::getInstance()->createQuery()->where("user_id = ?", $sf_user->getGuardUser()->getId())->addWhere("type='ReminderThisProd'")->addWhere("product_id=?", $product->getId())->fetchOne()) {
                                                                        echo " checked";
                                                                    }
                                                                ?>>
                                                            </div>
                                                            <div class="notification-field">Напомнить купить к моему событию</div>
                                                            <script type="text/javascript" src="/js/datepicker.js"></script>
                                                            <link rel="stylesheet" type="text/css" media="all" href="/css/datepicker.css" />
                                                            <input type="text" value="<?
                                                            if ($notificationReminder) {
                                                                echo $notificationReminder->getDateevent();
                                                            } else {
                                                                echo "Выберите дату";
                                                            }
                                                            ?>" name="dateevent" class="datepicker" id="anyID"><br/>
                                                            <input type="text" value="<?
                                                            if ($notificationReminder) {
                                                                echo $notificationReminder->getComment();
                                                            } else {
                                                                echo "Оставьте комментарий";
                                                            }
                                                            ?>" name="comment" style="width: 193px; border: 1px solid #e0e0e0; height: 23px; margin-top: 5px; padding-left: 2px;">
                                                        </td>
                                                    </tr>
                                                  </table>
                                                      <div class="notification-buttons">
                                                        <div>
                                                          <a class="SaveNotificationButton" href="" onclick="$('.blockSettingNotification<?= $productInfo->getProductId() ?> .setNotification<?= $productInfo->getProductId() ?>').ajaxForm(function (result) {
                                                            $('.blockSettingNotification<?= $productInfo->getProductId() ?> .settingNotificationContent<?= $productInfo->getProductId() ?>').html(result);
                                                            });
                                                            $('.blockSettingNotification<?= $productInfo->getProductId() ?> .setNotification<?= $productInfo->getProductId() ?>').submit();
                                                            $('#content').load('/customer/notification');
                                                            return false;">
                                                          </a>
                                                        </div>
                                                        <div>
                                                          <a class="SettingNotificationButton" href="/customer/notification"></a>
                                                        </div>
                                                      </div>


                                                <div style="clear: both;"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="editButton"  onclick="settingNotification(<?= $productInfo->getProductId() ?>)"></div>
                            <div class="deleteButton" onclick="$.post('/delnotification', {productId: <?= $productInfo->getProductId() ?>});
                                    $('#content').load('/customer/notification');"></div>
                        </td>
                    </tr>
                    <?
                endif;
            endforeach;
            ?>
        </table>

        <script>
            function dellAll() {
                $('<div/>').css("height", $("body").outerHeight() - 100 - $(window).scrollTop()).addClass("blockDelAll").appendTo('body');
                $('.blockDelAll').html($(".DellAllBlock").html());
                $('.blockDelAll').css({position: "absolute", top: ($(".deleteAllButton").offset().top + 30), left: ($(".deleteAllButton").offset().left -28)});
            }
        </script>
        <div class="deleteAllButton" onclick="dellAll();"><span>Удалить все оповещения</span></div>
        <div class="DellAllBlock" style="display: none;">
            <div style="
                 position: relative;
                 width: 210px;
                 ">
                <div style="
                     background-color: rgba(255, 255, 255, 1);
                     border:1px solid #c3060e; padding: 20px; ">
                    Вы уверены, что хотите удалить все оповещения?
                    <div style="width:50%; float: left;"><div class="buttonYes" onclick="$.post('/delnotification');
                            $('#content').load('/customer/notification');
                            $('.blockDelAll').remove();"></div></div>
                    <div style="width:50%; float: left;"><div class="buttonNo" onClick="$('.blockDelAll').remove();"></div></div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
      <? else:
        ?>
        У вас нет настроенных оповещений.
    <? endif; ?>
</div>
