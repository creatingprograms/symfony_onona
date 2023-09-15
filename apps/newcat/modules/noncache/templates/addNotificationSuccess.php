<div style='color: #c3060e; margin-right: 10px;'>Вы подписаны на уведомления о новых поступлениях товаров в этом разделе.
    <? if ($sf_user->isAuthenticated()) { ?>
        <a href="/delnotification/<?= $category ?>" onclick=" $('.NotificationCategory').load($(this).attr('href'));return false;">Отписаться</a>
    <? } else { ?>
    <? } ?>
</div> 