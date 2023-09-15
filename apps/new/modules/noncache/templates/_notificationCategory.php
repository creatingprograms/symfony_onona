<?php if (isset($notificationCategory) && $notificationCategory): ?>
  <div class="collection-page-subscription -sign">
    <form action="/delnotification/<?= $collectionId ?>" class="collection-page-form js-ajax-form">
      Вы подписаны на уведомления о новых поступлениях товаров в этом разделе.
      <input type="hidden" value="<?= $collectionId ?>" name="categoryAddNotification">
      <div class="but js-submit-button">Отписаться</div>
    </form>
  </div>
<? else: ?>
  <div class="collection-page-subscription -sign">
    Подпишитесь на уведомления о новых поступлениях товаров в этом разделе:
  </div>
  <form action="/addnotification" class="collection-page-form js-ajax-form">
    <input type="email" name="email" value="" placeholder="Введите свой e-mail" >
    <input type="hidden" value="<?= $collectionId ?>" name="categoryAddNotification">
    <div class="but js-submit-button">Подписаться</div>
    <?/*<input type="submit" name="" value="Подписаться" class="but">*/?>
  </form>
<?php endif; ?>
