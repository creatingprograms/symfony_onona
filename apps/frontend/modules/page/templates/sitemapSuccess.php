<?php
  $h1='Карта сайта';
  slot('metaTitle', $h1);
  slot('breadcrumbs', [
    ['text' => $h1],
  ]);
  slot('h1', $h1);
  slot('metaTitle', $h1.' | Сеть магазинов для взрослых 18+ "Он и Она"');
  slot('metaKeywords', $h1);
  slot('metaDescription', $h1.' | Интернет-магазин "Он и Она"');
?>
<main class="wrapper -action">
  <img src="/frontend/images/sitemap-image.jpg" alt="">
  <div class="sitemap-wrapper">
    <ul class="pink">
      <li>
    		<a href="/kompaniya_onona">О нас</a></li>
    	<li>
    		<a href="/Adresa_magazinov_v_Moskve">Адреса магазинов в Москве и МО</a></li>
    	<li>
    		<a href="/magaziny-on-i-ona-v-sankt-peterburge">Магазины «Он и Она» в Санкт-Петербурге</a></li>
    	<li>
    		<a href="/set-magazinov-dlya-vzroslyh-eros-v-g-rostov-na-donu">Сеть магазинов для взрослых «Эрос» в г. Ростове-на-Дону</a></li>
    	<?/*<li>
    		<a href="/set-magazinov-dlya-vzroslyh-on-i-ona-v-g-krasnodar">Сеть магазинов для взрослых «Он и Она» в г. Краснодар</a></li>*/?>
      <li>
    		<a href="/set-magazinov-dlya-vzroslyh-on-i-ona-v-krymu">Сеть магазинов для взрослых «Он и Она» в Крыму</a></li>
    	<?/*<li>
    		<a href="/Adresa_magazinov_v_Rossii">Адреса магазинов в других городах России</a></li>*/?>
      <li>
    		<a href="/manufacturers">Оптовые поставки</a></li>
    	<li>
    		<a href="/Vakansii">Вакансии</a></li>
    	<li>
    		<a href="/kontakty">Контакты</a></li>
    	<li>
    		<a href="/kak-sdelat-zakaz">Как сделать заказ</a></li>
    	<li>
    		<a href="/dostavka">Доставка и оплата</a></li>
    	<li>
    		<a href="/Garantii">Гарантии</a></li>
    	<li>
    		<a href="/dogovor-oferta">Договор оферта</a></li>
    	<li>
    		<a href="/newprod">Новые поступления</a></li>
    	<?/*<li>
    		<a href="/related">Лидеры продаж</a></li>*/?>
    	<li>
    		<a href="/support">Обратная связь</a></li>
    	<li>
    		<a href="/programma-on-i-ona-bonus">Программа &laquo;ОН И ОНА - Бонус&raquo;</a></li>
    	<li><?/*
    		<a href="/category/upravlyai-cenoi">Акция &laquo;Управляй ценой!&raquo;</a></li>
    	<?/*<li>
    		<a href="/category/Luchshaya_tsena">Акция &laquo;Лучшая цена!&raquo;</a></li>*/?>
    	<li>
    		<a href="/hochu-druguu-cenu">Программа &laquo;Хочу другую цену&raquo;</a></li>
    	<li>
    		<a href="/diskontnaya_karta">Дисконтные карты</a></li>
    	<li>
    		<a href="/podarochnye_sertifikaty">Подарочные сертификаты</a></li>
    	<li>
    		<noindex><a href="http://club.onona.ru/" rel="nofollow">Наш форум</a></li></noindex>
    	<li>
    		<a href="/vopros-seksologu">Вопросы сексологу</a></li>
    	<li>
    		<a href="/sexopedia">Сексопедия - онлайн журнал</a></li>
    	<?/*<li>
    		<a href="/horoscope">Любовный гороскоп</a></li>*/?>
    	<li>
    		<a href="/lovetest">Любовные тесты</a></li>
    	<li>
    		<a href="/comments">Отзывы покупателей о товарах</a></li>
    	<li>
    		<a href="/video">Он и Она | TUBE</a></li>

      <li>Каталог
        <ul class="pink black">
          <?php
          $currentCatalog = 0;
          foreach ($categorys as $row): ?>
            <?php if ($row['catid'] != $currentCatalog && $currentCatalog){
              echo '</ul>';
            }?>
            <?php if ($row['catid'] != $currentCatalog){
              echo '<li><a href="/catalog/'.$row['catslug'].'">'. $row['catname'].' '.$row['catdescription'].'</a><ul class="pink grey">';
              $currentCatalog=$row['catid'];
            }?>
            <li><a href="/category/<?=$row['slug']?>"><?=$row['name']?></a></li>

          <?php endforeach; ?>
        </ul>
    </li>
    </ul>
  </li>
</main>
