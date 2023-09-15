<?php
/* $procBonus=230/665*100;
  echo floor($procBonus/5)*5; */
//echo phpinfo();
/* for($i=1;$i<=100;$i++){
  ?>

  .sale<?=$i?>{
  background:url(../images/sale<?=$i?>.png) no-repeat;
  width:62px;
  height:63px;
  position:absolute;
  top:40px;
  right:-13px;
  overflow:hidden;
  text-indent:-9999px;
  z-index:10;
  }<?
  } */
ini_set('display_errors', 1);
error_reporting(E_ALL);
/* require_once __DIR__ . '/sync/classes/PhpMailer.class.php';
  $mail = new PhpMailer();
  $mail->From = 'info@onona.ru';
  $mail->FromName = 'Почтовый робот сайта ';
  $mail->CharSet = 'utf-8';
  $mail->IsHTML(true);
  $mail->Body = "ddddd";
  $mail->Subject = "На сайте  появился ожидаемый товар.";

  $mail->AddAddress('test');

  if (!$mail->Send()) {
  echo 'Не могу отослать письмо!';
  } else {
  echo 'Письмо отослано!';
  } */
//SetCookie("testnewcat",true);
//$_COOKIE['testnewcat'] = true;
if (@$_GET['testnewdis'] == "1") {
    SetCookie("testnewcat", true);
} else {
    SetCookie("testnewcat", false);
}
if (@$_GET['testnewdisdev'] == "1") {
    SetCookie("testnewdisdev", true);
} else {
    SetCookie("testnewdisdev", false);
}/*
  ?><pre><?

  $target = "localhost";
  $port = 25;
  $errno = "";
  $errstr = "";
  $timeout = 9;
  $newline = "\r\n";
  $logArray = array();

  $connect = fsockopen($target, $port, $errno, $errstr, $timeout);
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  fputs($connect, "EHLO onona.ru" . $newline);
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  fputs($connect, "MAIL FROM: <info@onona.ru>" . $newline);
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  fwrite($connect, "RCPT TO: <info@onona.ru>" . $newline);
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  fputs($connect, "DATA" . $newline);
  $smtpResponse = fgets($connect, 4096);
  echo $smtpResponse.$newline;
  ?>
  </pre>
  <?php */ /* echo $_SERVER['REMOTE_ADDR']; */
?> 
<? /*
  <link rel="stylesheet" type="text/css" media="screen" href="/newdis/css/all_newcat.css?v=2" />










  <div style="width: 750px;  margin-bottom: 20px;  float: left;  margin-right: 20px;border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;">
  <div style="width: 400px;  float: left; border-top: 1px solid #e0e0e0;border-right: 1px solid #e0e0e0;">
  <div style="width: 400px; height: 400px;border-bottom: 1px solid #e0e0e0; text-align: center;">
  <img id="photoimg_13937" src="/uploads/photo/1e4adb9ec6a44e7657ff483003010a68b2d0d49b.jpg" style="max-width: 400px; max-height: 400px;" alt="Вагинальные шарики Tyro II со смещенным центром тяжести" title="Вагинальные шарики Tyro II со смещенным центром тяжести">
  </div>
  <div style="  text-align: center;  width: 400px;  position: relative;  padding: 10px 0;  height: 63px;">

  </div>
  </div>
  <div style="width: 349px;  float: left;">

  <div style="width: 349px;border-bottom: 1px solid #e0e0e0; padding: 0 10px 10px 10px; ">
  <h1 class="title" style="font: 14px/21px Tahoma, Geneva, sans-serif;margin-top: -4px;" itemprop="name">Вагинальные шарики Tyro II со смещенным центром тяжести</h1>
  </div>
  <div style="width: 349px;border-bottom: 1px solid #e0e0e0;  padding: 10px;">
  <span onclick="$(this).html('XT20020-12');">№: 11633</span>
  </div>
  <div style="width: 349px;   padding: 10px;">
  <div class="item-char"><fieldset>

  <script>
  function changeArtCode(slug) {
  document.location.href = "/product/" + slug;
  }
  </script>

  <dl>
  <dt>
  <input type="hidden" name="productOptions[]" value="823">
  Производитель:</dt>
  <dd><a href="/manufacturer/beauty-brands-limited-velikobritaniya"><u>Beauty Brands Limited, Великобритания</u></a></dd></dl>


  <dl>
  <dt>
  <input type="hidden" name="productOptions[]" value="824">
  Коллекция:</dt>
  <dd><a href="/collection/x-toy"><u>X-TOY</u></a></dd></dl>


  <dl>
  <dt>Материал:</dt>
  <dd>
  ABS Plastic <br>
  Silicone <br>
  </dd></dl>


  <dl>
  <dt>
  <input type="hidden" name="productOptions[]" value="190">
  Цвет:</dt>
  <dd>Фиолетовый </dd></dl>


  <dl>
  <dt>
  <input type="hidden" name="productOptions[]" value="360">
  Диаметр:</dt>
  <dd>3,5 см </dd></dl>


  <dl>
  <dt>
  <input type="hidden" name="productOptions[]" value="441">
  Свойство:</dt>
  <dd>Без вибрации </dd></dl>

  </fieldset></div>
  </div>
  </div>
  </div> */ ?>

<?
/*
  $u="[Common] error_num=OK packet_id=24475 balance_before=5642,18 sender_ip=144.76.40.38 [1] packet_#=1 message_id=377461537 message_phone=77777777776 message=Ожидайте звонок о заказе A11158045 на сумму 232р. для уточнения адреса и даты доставки. Ваша www.onona.ru message_parts=2 message_zone=2 message_cost=1,9 [Summary] summ_phone=1 summ_parts=2 packet_cost=1,9 balance_after=5640,28 Error_code=0";

  preg_match("/message_cost\s*=\s*[0-9,]+/i", $u, $arr_cost); */
//$message_cost = preg_replace("/message_cost\s*=\s*/i", "", @strval($arr_cost[0]));
// print_r($message_cost);,Ю
/*


<div class="bonusAndDelivery">
    <img src="/images/mobile/mainPage/bonus.png" />
    <img src="/images/mobile/mainPage/delivery.png" />
</div>
<div class="promo">
    <div class="slide-gallery" id="gallery-mainPage">
        <a href="/kutuzovskii-prospekt">
            <img alt="Lieu Delicate – первое в России «деликатное место» для изысканного интимного шоппинга" src="/uploads/assets/images/new-shop_760x300.jpg" style="width: 100%;" />
        </a> 
        <a href="/manufacturer/baci-lingerie-black-label-ssha">
            <img alt="Скидки от 20 до 30% на весь ассортимент коллекции Black Label в интим магазине Он и Она" src="/uploads/assets/images/BaciBlackLabe-salel_760x300.jpg" style="width: 100%;" />
        </a> 
        <a href="/manufacturer/818">
            <img alt="Провокационная коллекция Fifty Shades of Grey по мотивам всемирно известной трилогии «Пятьдесят Оттенков Серого»" src="/uploads/assets/images/FiftyShadesofGrey_760x300.png" style="width: 100%;" />
        </a> 
        <a href="/product/EHko-lubrikant-Blue-Laguna-30ml">
            <img alt="Эко-лубрикант Blue Laguna – высококачественная смазка на основе природного экстракта красной морской водоросли" src="/uploads/assets/images/BlueLaguna_760x300.jpg" style="width: 100%;" />
        </a> 
        <a href="/collection/x-toy">
            <img alt="Искрометная и дерзкая коллекция X-TOY внесет новые ощущения и удовлетворение в интимную жизнь." src="/uploads/assets/images/X-TOY_760x300.jpg" style="width: 100%;" />
        </a> 
        <a href="/category/leaf-by-swan-ekologicheski-chistye-i-bezopasnye-vibratory">
            <img alt="Leaf by Swan - экологически чистые и безопасные вибраторы" src="/uploads/assets/images/leaf_760x300.jpg" style="width: 100%;" />
        </a>
    </div>
</div>

<div class="point popularPoint">
    <div class="topPoint">
        <div class="namePoint">
            Популярные категории
        </div>
    </div>
    <ul>
        <li>
            <a href="{url_for('@catalog?slug=sex-igrushki-dlja-par')}"><img src="/images/mobile/mainPage/category_onona.png"></a>
        </li>
        <li>
            <a href="{url_for('@catalog?slug=sex-igrushki-dlya-muzhchin')}"><img src="/images/mobile/mainPage/category_on.png"></a>
        </li>
        <li>
            <a href="{url_for('@catalog?slug=sex-igrushki-dlya-zhenschin')}"><img src="/images/mobile/mainPage/category_ona.png"></a>
        </li>
    </ul>
    <div class="bottomPoint">

        <div class="arrowRight">
        </div>
        <div class="textPoint">
            <a href="{url_for('@catalogs')}">Все категории каталога</a>
        </div>
    </div>
</div>

{bestsellerPoint}
{newProductsPoint}
{bonuspayPoint}
{bestpricePoint}



<div class="point actionsAndBonusPoint">
    <div class="topPoint">
        <div class="togglePoint minus" onclick="togglePoint(this);">
        </div>
        <div class="namePointRed">
            Акции и Бонусы
        </div>
    </div>
    <ul class="actionsAndBonus">
        <li>
            <a href="/akcia1">
                <img alt="Actions-30" class="mainPageAction" src="/uploads/Actions/30.png">
            </a>
        </li>
        <li>
            <a href="/category/upravlyai-cenoi">
                <img alt="Actions-Bonus" class="mainPageAction" src="/uploads/Actions/Bonus.png">
            </a>
        </li>
    </ul>

    <a href="/akcii-i-bonusy">
        <div class="bottomPoint">

            <div class="arrowRight">
            </div>
            <div class="textPoint">
                Все Акции и Бонусы
            </div>
        </div>
    </a>
</div>







<div class="point shopListPoint">
    <div class="topPoint">
        <div class="togglePoint minus" onclick="togglePointFromSubPoint(this);">
        </div>
        <div class="namePointRed">
            Адреса магазинов «Он и Она»
        </div>
    </div>
    <div class="subPoint">
        <div class="topPoint">
            <div class="togglePoint minus" onclick="togglePoint(this);">
            </div>
            <div class="namePointRed">
                Магазины в Москве
            </div>
        </div>
        <ul class="shop-list">
            <li>
                <div class="shop">
                    <div class="img-holder">
                        <div class="metro">
                            <span class="ico-holder"><img alt="image description" height="16" src="/newdis/images/metro16.png" width="16"> </span> Варшавская</div>
                        <a href="/magazin-on-i-ona-v-g-moskva-varshavskoe-shosse">
                            <img alt="Магазин для взрослых Он и Она, г. Москва, Варшавское шоссе, д. 68, стр. 2" src="/uploads/assets/images/20150615_142158-1_230px.jpg" style="width: 229px; height: 150px;">
                        </a>
                    </div>
                    <div class="title">
                        Адрес:</div>
                    <div class="address">
                        Варшавское шоссе, д. 68, стр. 2</div>
                    <div class="work-time">
                        КРУГЛОСУТОЧНО</div>
                </div>
            </li>
            <li>
                <div class="shop">
                    <div class="img-holder">
                        <div class="metro">
                            <span class="ico-holder"><img alt="image description" height="16" src="/newdis/images/metro01.png" width="16"> </span> <span class="text">Бабушкинская</span></div>
                        <a href="/ulitsa_Letchika_Babushkina"> 
                            <img alt="Магазин для взрослых Он и Она, г. Москва, ул. Летчика Бабушкина, д. 30" src="/uploads/assets/images/DSC_0087(1).jpg" style="width: 229px; height: 150px;">
                        </a>
                    </div>
                    <div class="title">
                        Адрес:</div>
                    <div class="address">
                        ул. Летчика Бабушкина, д. 30</div>
                    <div class="work-time">
                        с 10:00 до 22:00</div>
                </div>
            </li>
        </ul>
        <a href="/Adresa_magazinov_v_Moskve">
            <div class="bottomPoint">

                <div class="arrowRight">
                </div>
                <div class="textPoint">
                    Все магазины в Москве
                </div>
            </div>
        </a>
    </div>
    <div class="subPoint">
        <div class="topPoint">
            <div class="togglePoint minus" onclick="togglePoint(this);">
            </div>
            <div class="namePointRed">
                Магазины в Санкт-Петербурге
            </div>
        </div>
        <ul class="shop-list">
            <li>
                <div class="shop">
                    <div class="img-holder">
                        <div class="metro">
                            <span class="ico-holder"><img alt="image description" height="16" src="/newdis/images/metro15.png" width="16"> </span> <span class="text">Выборгская</span></div>
                        <a href="/lesnaya">
                            <img alt="Магазин для взрослых Он и Она, г. Санкт-Петербург, ул. Лесная д. 32 " src="/uploads/assets/images/IMG_6530-0.jpg" style="width: 229px; height: 150px;">
                        </a></div>
                    <div class="title">
                        Адрес:</div>
                    <div class="address">
                        Лесной проспект д. 32</div>
                    <div class="work-time">
                        КРУГЛОСУТОЧНО</div>
                </div>
            </li>
            <li>
                <div class="shop">
                    <div class="img-holder">
                        <div class="metro">
                            <span class="ico-holder"><img alt="image description" height="16" src="/newdis/images/metro13.png" width="16"> </span> <span class="text">Елизаровская</span></div>
                        <a href="/ulitsa_Babushkina"> 
                            <img alt="Магазин для взрослых Он и Она, г. Санкт-Петербург, ул. Бабушкина, д. 7" src="/uploads/assets/images/OnOna_0008_Layer-0(1).jpg" style="width: 229px; height: 150px;">
                        </a></div>
                    <div class="title">
                        Адрес:</div>
                    <div class="address">
                        ул. Бабушкина, д. 7</div>
                    <div class="work-time">
                        с 10:00 до 23:00</div>
                </div>
            </li>
        </ul>
        <a href="/magaziny-on-i-ona-v-sankt-peterburge">
            <div class="bottomPoint">

                <div class="arrowRight">
                </div>
                <div class="textPoint">
                    Все магазины в Санкт-Петербурге
                </div>
            </div>
        </a>
    </div>
    <div class="subPoint">
        <div class="topPoint">
            <div class="togglePoint minus" onclick="togglePoint(this);">
            </div>
            <div class="namePointRed">
                Магазины в Ростове-на-Дону
            </div>
        </div>

        <ul class="shop-list">
            <li>
                <div class="shop">
                    <div class="img-holder">
                        <div class="metro">
                            <span class="ico-holder"><img alt="image description" height="16" src="/newdis/images/metro00.png" width="16"> </span> <span class="text">г. Ростов-на-Дону</span></div>
                        <a href="/seks-shop-eros-v-g-rostov-na-donu-voroshilovskii-prospekt-dom-69-73"> 
                            <img alt="Секс-шоп «Эрос» в г. Ростов-на-Дону, Ворошиловский проспект, дом 69-73" src="/uploads/assets/images/2-0.jpg" style="width: 229px; height: 150px;">
                        </a></div>
                    <div class="title">
                        Адрес:</div>
                    <div class="address">
                        Ворошиловский проспект, д. 69-73</div>
                    <div class="work-time">
                        КРУГЛОСУТОЧНО</div>
                </div>
            </li>
            <li>
                <div class="shop">
                    <div class="img-holder">
                        <div class="metro">
                            <span class="ico-holder"><img alt="image description" height="16" src="/newdis/images/metro00.png" width="16"> </span> <span class="text">г. Ростов-на-Дону</span></div>
                        <a href="/seks-shop-eros-v-g-rostov-na-donu-ul-bolshaya-sadovaya-d-34a"> 
                            <img alt="Секс-шоп «Эрос» в г. Ростов-на-Дону, ул. Большая Садовая, д. 34а" src="/uploads/assets/images/IMG_5278-1.jpg" style="width: 229px; height: 150px;">
                        </a></div>
                    <div class="title">
                        Адрес:</div>
                    <div class="address">
                        ул. Большая Садовая, д. 34а</div>
                    <div class="work-time">
                        КРУГЛОСУТОЧНО</div>
                </div>
            </li>
        </ul>
        <a href="/set-magazinov-dlya-vzroslyh-eros-v-g-rostov-na-donu">
            <div class="bottomPoint">

                <div class="arrowRight">
                </div>
                <div class="textPoint">
                    Все магазины в Ростове-на-Дону
                </div>
            </div>
        </a>
    </div>
</div>



{videosPoint}







<div class="description">
    <div class="top">
        Интернет магазин для взрослых Он и Она
    </div>
    &laquo;Он и Она&raquo; &mdash; крупнейшая в России сеть магазинов для взрослых. Уже более 20 лет мы продаём только лучшие товары для счастливой сексуальной жизни.<br />
    Вы ищете интересные товары для взрослых, чтобы разнообразить сексуальную жизнь? Хотите освежить отношения в паре? Жаждете новых ярких ощущений? Мечтаете об идеальном оргазме?<br />

    <div class="btnSpoiler" onclick="toggleSpoiler(this);">
        <div class="Icon">
        </div>
        <div class="Text">
            Развернуть
        </div>
    </div>
    <div class="spoiler">
        <br />
        Вы на верном пути! Любой интим магазин сети &laquo;Он и Она&raquo; в Москве, Санкт-Петербурге и ещё 12 городах России предлагает вашему вниманию огромный ассортимент оригинальных товаров от мировых производителей, где вы найдёте все, что вам необходимо для того, чтобы сделать секс более волнующим.<br />
        <br />
        Не терпится попробовать? Интернет интим магазин товаров для взрослых ОнОна к Вашим услугам 24 часа в сутки. Выбирайте в любое удобное для вас время любые товары или эротические игрушки из более тысячи наименований от ведущих мировых производителей, представленных в интернет магазине для взрослых OnOna.ru. Все товары есть в наличии на складе и уже готовы к отправке.<br />
        <br />
        Наш интернет магазин интимных товаров осуществляет качественное обслуживание клиентов, быструю доставку, низкие цены и абсолютную конфиденциальность.<br />
        <br />
        Основные конкурентные преимущества нашего интернет интим магазина для взрослых &ndash; это гибкая система скидок и высокое качество товаров. Для клиентов, подписавшихся на рассылку, каждый месяц мы высылаем уникальные предложения с невероятными скидками и подарками. Всегда лучше и приятнее покупать подарки себе и своим любимым с выгодой.<br />
        <br />
        На витрине интернет магазинов интим &laquo;Он и Она&raquo; вы увидите только качественные бренды от ведущих мировых производителей товаров для сексуальной жизни. Все товары проверены на безопасность и имеют сертификаты качества, поскольку для нас самым важным является безопасность и здоровье наших клиентов.<br />
        <br />
        &nbsp;&laquo;Он и Она&raquo; - сексшоп №1 в России, убедитесь в этом, сделав хотя бы одну покупку в нашем интернет интим магазине эротических товаров.
    </div>
</div>
 * */
 