<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 

Клиенты по телефону: <?=$phoneMail?><br />
Клиенты по телефону НЕ зарегистрированные у нас: <?=$phoneMailNotReg?><br />
Клиенты по телефону НЕ валидная почта: <?=$phoneMailNotRegNotValid?><br />
Заказы клиентов по телефону: <?=$ordersCountPhone?><br />
<br /><br />
Клиенты по картке: <?=$cardMail?><br />
Клиенты по карте НЕ зарегистрированные у нас: <?=$cardMailNotReg?><br />
Клиенты по карте НЕ валидная почта: <?=$cardMailNotRegNotValid?><br />
Заказы клиентов по карте: <?=$ordersCountCard?><br />