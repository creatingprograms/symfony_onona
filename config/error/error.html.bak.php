<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= csSettings::get('defaultTitle') ?></title>
        <meta name="keywords" content="<?= csSettings::get('defaultKeywords') ?>" />
        <meta name="description" content="<?= csSettings::get('defaultDescription') ?>" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen"/><![endif]-->
        <link rel="stylesheet" type="text/css" media="screen" href="/newdis/css/all.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="/css/highslide.css" />
        <script type="text/javascript" src="/newdis/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/newdis/js/form.js"></script>
        <script type="text/javascript" src="/newdis/js/clear-form-fields.js"></script>
        <script type="text/javascript" src="/newdis/js/jquery-ui.js"></script>
        <script type="text/javascript" src="/newdis/js/ui.js"></script>
        <script type="text/javascript" src="/newdis/js/jquery.main.js"></script>
        <script type="text/javascript" src="/newdis/js/jquery.countdown.js"></script>

        <script type="text/javascript" src="/newdis/js/jquery.countdown-ru.js"></script>
        <script type="text/javascript" src="/newdis/js/coin-slider.min.js"></script>
        <script type="text/javascript" src="/js/highslide/highslide-full.js"></script>
        <script type="text/javascript" src="/js/jquery.form.js"></script>


    </head>
    <body>
        <div style="display: none; position: absolute; z-index: 10000; top: 50%; left: 50%; margin-left: -100px; width: 200px; background: none repeat scroll 0% 0% rgb(255, 255, 255); font-weight: bold; height: 44px; margin-top: -22px;" id="TextAddProductToCard"><br>Добавление товара...</div>
        <div style="display: none; position: absolute; z-index: 9999;left: 0px;width: 100%; background: #000;height: 100%;
             opacity:0.5;filter:alpha(opacity=50);-moz-opacity:0.5;" id="BackgrounAddProductToCard"></div>
        <script type="text/javascript" src="/player/js/hdwebplayer.js"></script>


        <div id="playerdiv" class="simple_overlay">
            <div id="playerVideoDiv"></div>
            <a class="close"></a>
            <? /*
              <a
              href="http://1.onona.ru/uploads/video/<? include_slot('video') ?>"
              style="display:block;width:620px;height:366px; margin: 15px;"
              id="player1">
              </a>
             */ ?>

        </div>

        <div class="wrapper-holder">
            <div id="wrapper">
                <div id="header">
                    <div class="head-row">
                        <div class="right">
                            <div class="age">
                                <div class="tips">Сайт предназначен только для лиц, достигших 18-ти летнего возраста.</div>
                            </div>

                        </div>
                        <ul class="top-menu">

                            <?php
                            $menu = MenuTable::getInstance()->findByPositionmenu('Над шапкой(новый дизайн)');
                            foreach ($menu as $link):
                                ?>
                                <li><a href="<?= $pre . $link->getUrl() ?>"><?= $link->getText() ?></a></li>
                                <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                    <div class="header-frame">
                        <div class="logo">
                            <a href="<?= $pre ?>/">Сеть магазинов для взрослых Он и Она</a>
                            <div class="slogan">Работаем с <span>1992</span> года</div>
                        </div>

                        <div class="phone-box">
                            <div class="title">Профессиональная консультация</div>
                            <ul>
                                <li>
                                    <span class="descr">по России (бесплатно)</span>
                                    <span class="phone">8 800 700 98 85</span>
                                </li>
                                <li>
                                    <span class="descr">по Москве</span>
                                    <span class="phone"><span class="code">+7 (495)</span> 787 98 86</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <? if (true): ?>
                        <div class="nav-holder">
                            <div class="nav-deco"></div>
                            <div class="nav-frame">
                                <form action="<?= $pre ?>/search" class="search">
                                    <fieldset>
                                        <input id="period" type="text" value="Поиск" name="searchString" />
                                        <input class="srch-btn" type="submit" value="" />
                                    </fieldset>
                                </form>
                                <ul id="nav">
                                    <?php
                                    $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'Под шапкой(новый дизайн)'")->addWhere("parents_id is NULL")->execute();
                                    foreach ($menu as $link):
                                        ?>
                                        <li><a href="<?= $pre . $link->getUrl() ?>"><span><?= $link->getText() ?></span></a>
                                            <?php
                                            $subMenu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'Под шапкой(новый дизайн)'")->addWhere("parents_id = '" . $link->getId() . "'")->execute();
                                            if ($subMenu->count() > 0) {
                                                ?><div class="drop">
                                                    <span class="cur"></span>
                                                    <ul>
                                                        <?php
                                                        foreach ($subMenu as $subLink):
                                                            ?>
                                                            <li><a href="<?= $pre . $subLink->getUrl() ?>"><?= $subLink->getText() ?></a></li>
                                                        <? endforeach; ?>
                                                    </ul>
                                                </div><?
                                                    }
                                                    ?>
                                        <?php endforeach; ?>

                                </ul>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
                <div id="main">



                    <?php if (true): ?>
                        <div class="aside">
                            <div class="benefits-box box">
                                <div class="title-holder-box">
                                    <div class="title-holder">Наши преимущества</div>
                                </div>
                                <?
                                $footer = PageTable::getInstance()->findOneBySlug("nashi-preimuschestva");
                                echo $footer->getContent();
                                ?>
                            </div>
                            <div class="ads">
                                <a href="<?= $pre ?>/rassylka"><img src="/newdis/images/img33.jpg" width="210" height="180" alt="image description" /></a>
                            </div>
                            <div class="ads">
                                <a target="_blank" href="http://club.onona.ru/index.php/topic/130-specialnaja-premija-roznichnaja-set-goda/"><img src="/newdis/images/img01.jpg" width="210" height="160" alt="image description" /></a>
                            </div>
                            <?php
                            $relatedProduct = ProductTable::getInstance()->getRelatedProduct();
                            //echo $relatedProduct->count();
                            if ($relatedProduct->count() > 0):
                                ?>
                                <div class="leaders-galery box">
                                    <div class="title-holder">Лидеры продаж</div>
                                    <div class="galery-holder">
                                        <a href="#" class="prev"></a>
                                        <a href="#" class="next"></a>
                                        <div class="galery" style="height: 550px">
                                            <ul>
                                                <?php
                                                $q = Doctrine_Query::create()->select("name, slug, (select filename from photo where album_id=(select photoalbum_id from product_photoalbum where product_id=product.id) order by position asc limit 0,1) as filename")->from("product")->where("`is_related` = 1")->orderBy("rand()")->execute();
                                                foreach ($q as $product):
                                                    ?>
                                                    <li><a href="<?= $pre ?>/product/<?= $product->getSlug() ?>" style="display: table-cell;vertical-align: middle;height: 268px;"><img src="/uploads/photo/thumbnails_250x250/<?= $product->getFilename() ?>" style="max-width: 188px; max-height: 260px;" alt="<?= $product->getName() ?>" /></a></li>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>
                    <?
                    echo $page->getContent();
                    ?>
                    <br /><br /><br />
                    Уникальный ID ошибки(пришлите его нам): <b><?= $UniqueId ?></b>
                </div>
                <div style="clear: both;margin-bottom: 20px;"></div>
                <div id="footer"><?
                    $footer = PageTable::getInstance()->findOneBySlug("footer");
                    echo $footer->getContent();
                    ?>
                </div>
            </div>
            <a href="#" class="to-up">наверх</a>
        </div>

        <div id="login" class="login-popup" style="left:500px;">
            <a href="#" class="close"></a>
            <span class="deco-left"></span>
            <span class="deco-right"></span>
            <form action="<?php echo url_for('@sf_guard_signin') ?>" method="POST">
                <?php $authForm = new sfGuardFormSignin() ?>
                <fieldset>
                    <div class="title">Авторизация</div>
                    <div class="error-msg">Неправильный логин или пароль</div>
                    <div class="input-frame">
                        <div class="input-holder">
                            <input type="text" value="Логин" name="signin[username]" onclick="$(this).parent().parent().parent().children('.input-frame:last').addClass('parent-active');
                                    $(this).parent().parent().parent().children('.input-frame:last').find('input:first').addClass('text-active');
                                    $(this).parent().parent().parent().children('.input-frame:last').find('.input-placeholder-text').fadeOut();" />
                        </div>
                    </div>
                    <div class="input-frame">
                        <div class="input-holder">
                            <input type="password" value="Пароль" name="signin[password]" />

                            <? /* <?= $authForm['_csrf_token'] ?> */ ?>
                        </div>
                    </div>
                    <div class="checkbox-holder">
                        <input id="lbl01" type="checkbox" class="checkbox" name="signin[remember]" />
                        <label for="lbl01">Запомнить</label>
                    </div>
                    <div class="btn-holder">
                        <div class="red-btn">
                            <span onclick="$(this).next('input').click()">Вход</span>
                            <input type="submit" value="Вход" class="button" />
                        </div>
                    </div>
                    <div class="forgot">
                        <a class="forgot-password" href="#">Забыли пароль?</a>
                    </div>
                </fieldset>
            </form>
        </div>
        <div id="forgot-password" class="login-popup" style="left:1000px;">
            <a href="#" class="close"></a>
            <span class="deco-left"></span>
            <span class="deco-right"></span>
            <form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="POST">
                <?php $fpForm = new sfGuardRequestForgotPasswordForm() ?>
                <fieldset>
                    <div class="title">Восстановление <br /> пароля</div>
                    <div class="input-frame">
                        <div class="input-holder">
                            <input type="text" value="E-mail" name="forgot_password[email_address]" />
                            <? /* <?= $fpForm['_csrf_token'] ?> */ ?>
                        </div>
                    </div>
                    <div class="text">Введите e-mail, который указывали при регистрации</div>
                    <div class="btn-holder">
                        <div class="red-btn send">
                            <span>Отправить</span>
                            <input type="submit" value="Отправить" class="button" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <div id="LK" class="login-popup" style="left:500px;">
            <a href="#" class="close"></a>
            <span class="deco-left"></span>
            <span class="deco-right"></span>
            <fieldset>
                <div class="title">Личный кабинет</div>
                <div class="text"><a href="<?= $pre ?>/customer/myorders">Ваши заказы</a></div>
                <div class="text"><a href="<?= $pre ?>/customer/mydata">Ваши данные</a> </div>
                <div class="text"><a href="<?= $pre ?>/customer/bonus">Ваши бонусы</a></div>
                <div class="text"><a href="<?= $pre ?>/guard/logout">Выход</a></div>
            </fieldset>
        </div>
        <div class="floating-menu">
            <div class="floating-menu-frame">
                <ul class="menu right">
                    <li><a href="<?= $pre ?>/compare" class="desire"><span></span></a></li>
                    <?php
                    $user = sfContext::getInstance()->getUser();
                    if ($user->isAuthenticated()) {

                        $bonus = BonusTable::getInstance()->findBy('user_id', $user->getGuardUser()->getId());
                        $bonusCount = 0;

                        foreach ($bonus as $bonus) {
                            $bonusCount = $bonusCount + $bonus->getBonus();
                        }
                        echo "<li><a href=\"" . $pre . "/customer/bonus\" class=\"bonus\"><span>" . $bonusCount . " бонусов</span></a></li>";
                    }
                    ?>

                    <li><a href="<?= $pre ?>/cart" class="card"><span></span></a></li>
                </ul>
                <ul class="menu">
                    <?php
                    $menu = MenuTable::getInstance()->findByPositionmenu('Над шапкой(новый дизайн)');
                    foreach ($menu as $link):
                        ?>
                        <li><a href="<?= $pre . $link->getUrl() ?>"><?= $link->getText() ?></a></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
        </div>

















        <? if ($this->getModuleName() == "product"): ?>

            <div class="popup-holder" style="left:-9999px;">
                <div class="bg">&nbsp;</div>
                <div class="popup bonus">
                    <a href="#" class="close"></a>
                    <div class="head">
                        <div class="img-holder left">
                            <img src="/newdis/images/img49.jpg" width="79" height="80" alt="image description" />
                        </div>
                        <div class="title-holder">
                            <div class="name">Программа «Он и Она – Бонус»</div>
                            <div class="descr">Каждая ваша покупка оплачивает следующую!</div>
                        </div>
                    </div>
                    <p>Программа для постоянных покупателей заключается в предоставлении вознаграждения за каждую покупку в интернет магазине OnOna.ru.</p>
                    <p><span class="blue">1 рубль = 1 Бонус.</span></p>
                    <p>Это позволит вам совершать покупки со скидкой от 20 до 50% от суммы вашего заказа. Ведь каждая ваша покупка оплачивает следующую!</p>
                    <p>Любой потраченный рубль – это заработанный Бонус.</p>
                    <p><br /></p>
                    <h3>Все очень просто и выгодно:</h3>
                    <p>с каждой новой покупкой в нашем интернет магазине OnOna.ru вы получаете на свой счет Бонусы в размере до 100% от вашего заказа.</p>
                    <p>Бонусами можно оплатить до 50% любой покупки!</p>
                    <p><br /></p>
                    <h3>Как пользоваться программой «Он и Она – Бонус»?</h3>
                    <p>1. Пройти регистрацию на сайте OnOna.ru и получить <span class="blue">300 приветственных Бонусов</span> на свой личный счет.</p>
                    <p>2. Сделать любую покупку в интернет магазине OnOna.ru.</p>
                    <p>3. Оплатить заказ. Как только вы оплачиваете свой заказ, вам на счет начисляются <span class="blue">Бонусы от 30 до 100%</span> от стоимости оплаченного заказа. Бонусы за покупки суммируются.</p>
                    <p>4. Итак, Бонусы в вашем распоряжении и при следующей покупке вы можете использовать их для оплаты своего заказа, для этого вам просто необходимо зайти в свой личный кабинет под своим логином и паролем.</p>
                    <p>«Он и Она – Бонус» работает просто и выгодно.</p>
                    <p><br /></p>
                    <h3>Действие Бонусов</h3>
                    <p>Накопленные Бонусы действительны в течение 90 дней с момента начисления. Каждая ваша последующая покупка прибавляет Бонусы и продлевает действие неиспользованных Бонусов.</p>
                    <p><br /></p>
                    <h3>Количество Бонусов</h3>
                    <p>Узнать о количестве накопленных Бонусов вы можете на сайте OnOna.ru в своем личном кабинете <span class="blue">в разделе Ваши Бонусы.</span></p>
                    <p>Также при совершении покупки вам на e-mail придет письмо, информирующее о начислении Бонусов за оплаченный заказ и общем количестве накопленных Бонусов.</p>
                    <div class="img-holder right" style="margin: -6px 0 -10px 10px;">
                        <img src="/newdis/images/img48.jpg" width="249" height="80" alt="image description" style="max-height: 100%;max-width: 100%;" />
                    </div>
                    <p><br /></p>
                    <p>Приятных вам покупок!</p>
                    <p><br /></p>
                    <p>P.S. бонусы и скидки не суммируются.</p>
                    <p>Задать интересующие вас вопросы вы можете на форуме Он и Она <a href="#">перейти></a></p>
                </div>
            </div>
        <? else: ?>

            <div class="popup-holder" style="left:-9999px;">
                <div class="bg">&nbsp;</div>
                <div class="popup quick-view">
                    <a href="#" class="close"></a>
                    <div class="title">Быстрый просмотр</div>
                    <h2 class="title centr">Набор фиксаторов Naughty Nurse</h2>
                    <div class="item-box">
                        <div class="item-media">
                            <div class="img-holder">
                                <a href="#"><img src="/newdis/images/img43.jpg" width="177" height="223" alt="image description" /></a>
                            </div>
                            <div class="more-photo">
                                <a href="#">Фотогалерея 6 фото</a>
                            </div>
                        </div>
                        <div class="item-char">
                            <form action="#" class="search">
                                <fieldset>
                                    <dl>
                                        <dt>Рейтинг:</dt><dd>
                                            <div class="stars">
                                                <span style="width:100%;"></span>
                                            </div>
                                            <span>8</span>
                                        </dd>
                                        <dt>Производитель:</dt><dd><a href="#">Pipedream, США</a></dd>
                                        <dt>Коллекция:</dt><dd><a href="#">Fetish Fantasy Series</a></dd>
                                        <dt>Материал:</dt><dd>Vinyl</dd>
                                        <dt>Цвет:</dt><dd>Белый</dd>
                                        <dt>Количество:</dt><dd>
                                            <div class="input-holder">
                                                <input type="text" value="1" />
                                            </div>
                                        </dd>
                                    </dl>
                                    <div class="more-expand-holder">
                                        <a href="#" class="more-expand"></a>
                                    </div>
                                    <div class="price-box">
                                        <div class="row">
                                            <div class="btn-holder">
                                                <a href="#" class="red-btn to-card small">
                                                    <span>В корзину</span>
                                                </a>
                                                <a href="#" class="to-desire"></a>
                                            </div>
                                            <div class="price-col">
                                                <div class="title">&nbsp;</div>
                                                <span class="new-price">3000 р.</span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="bonus-box">
                        <a href="#" class="bonus-item">
                            <span class="icon">
                                <strong>30%</strong>
                                <em>бонусы</em>
                            </span>
                            <span class="text">возвращаем на ваш личный счет</span>
                        </a>
                        <a href="#" class="bonus-item">
                            <span class="icon">
                                <img src="/newdis/images/ico01.png" width="40" height="40" alt="image description" />
                            </span>
                            <span class="text">быстрый, выгодный заказ товара</span>
                        </a>
                    </div>
                    <h2 class="title">Описание товара</h2>
                    <div class="info-box">
                        <div class="video-holder">
                            <a href="#">
                                <img src="/newdis/images/video.png" width="142" height="90" alt="image description" />
                                <span class="name">Видео-презентация</span>
                                <span class="play"></span>
                            </a>
                        </div>
                        <p>Игривый комплект в стиле порочной медсестры состоит из маски на глаза, наручников и наножников, и отлично подходит для ролевых игр. <br /> Каждая деталь изготовлена из блестящего винила и украшена медицинским крестом. <br /> Наручники и наножники можно использовать оба сразу или по отдельности, в зависимости от вашего желания, они крепкие с тугой липучкой, которая легко позволяет регулировать их размер, а металлическая цепочка соединяет между собой каждую пару. <br />Маска плотно закрывает глаза и добавляет вашим играм особую пикантность и неожиданность.</p>
                    </div>
                    <div class="social-box">
                        <div class="row">
                            <a href="#" class="print"><img src="/newdis/images/ico02.png" width="16" height="16" alt="image description" /></a>
                            <ul class="social">
                                <li><a href="#"><img src="/newdis/images/ico03.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico04.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico05.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico06.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico07.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico08.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico09.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico10.png" width="16" height="16" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/ico11.png" width="16" height="16" alt="image description" /></a></li>
                            </ul>
                        </div>
                        <div class="row">
                            <a href="#" class="more-expand"></a>
                            <ul class="share">
                                <li><a href="#"><img src="/newdis/images/vk.png" width="102" height="20" alt="image description" /></a></li>
                                <li><a href="#"><img src="/newdis/images/fb.png" width="123" height="20" alt="image description" /></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>
        <?php
        if ($SiteViewCount < 4) {
            ?>
            <div id="isviVideoBlock" style="width: 350px; height: 350px; position: fixed; right: 0%; bottom: 0px; z-index:999"> </div><?php } ?>
        <script language="javascript">
            $(document).ready(function () {
                if ($('html').scrollTop() > 150 || $('body').scrollTop() > 150) {
                    $('#dynamic-to-top').fadeIn();
                } else {
                    $('#dynamic-to-top').fadeOut();
                }

                $(window).scroll(function () {
                    if ($('html').scrollTop() > 150 || $('body').scrollTop() > 150) {
                        $('#dynamic-to-top').fadeIn();
                    } else {
                        $('#dynamic-to-top').fadeOut();
                    }
                });
                if (($(window).width() - $(".mainWrap").width()) / 2 > 50) {
                    $("#dynamic-to-top").css("left", ($(window).width() - $(".mainWrap").width()) / 2 - 90);
                } else {
                    $("#dynamic-to-top").css("left", 0);
                }
            });
        </script>

        <!---/Main Wrap-->

        <script type='text/javascript'> /* build:::7 */
            var liveTex = true,
                    liveTexID = 24264,
                    liveTex_object = true;
            (function () {
                var lt = document.createElement('script');
                lt.type = 'text/javascript';
                lt.async = true;
                lt.src = 'http://cs15.livetex.ru/js/client.js';
                var sc = document.getElementsByTagName('script')[0];
                if (sc)
                    sc.parentNode.insertBefore(lt, sc);
                else
                    document.documentElement.firstChild.appendChild(lt);
            })();
        </script>


        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            var yaParams = {/*Здесь параметры визита*/};
        </script>

        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function () {
                    try {
                        w.yaCounter144683 = new Ya.Metrika({id: 144683, enableAll: true, webvisor: true, params: window.yaParams || {}});
                    } catch (e) {
                    }
                });

                var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () {
                            n.parentNode.insertBefore(s, n);
                        };
                s.type = "text/javascript";
                s.async = true;
                s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f);
                } else {
                    f();
                }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="//mc.yandex.ru/watch/144683" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->

        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-29176584-1']);
            _gaq.push(['_addOrganic', 'images.yandex.ru', 'q', true]);
            _gaq.push(['_addOrganic', 'blogsearch.google.ru', 'q', true]);
            _gaq.push(['_addOrganic', 'blogs.yandex.ru', 'text', true]);
            _gaq.push(['_addOrganic', 'nigma.ru', 's']);
            _gaq.push(['_addOrganic', 'webalta.ru', 'q']);
            _gaq.push(['_addOrganic', 'aport.ru', 'r']);
            _gaq.push(['_addOrganic', 'poisk.ru', 'text']);
            _gaq.push(['_addOrganic', 'km.ru', 'sq']);
            _gaq.push(['_addOrganic', 'liveinternet.ru', 'ask']);
            _gaq.push(['_addOrganic', 'quintura.ru', 'request']);
            _gaq.push(['_addOrganic', 'search.qip.ru', 'query', true]);
            _gaq.push(['_addOrganic', 'gde.ru', 'keywords']);
            _gaq.push(['_addOrganic', 'gogo.ru', 'q']);
            _gaq.push(['_addOrganic', 'ru.yahoo.com', 'p', true]);
            _gaq.push(['_addOrganic', 'price.ru', 'pnam']);
            _gaq.push(['_addOrganic', 'tyndex.ru', 'pnam']);
            _gaq.push(['_addOrganic', 'torg.mail.ru', 'q', true]);
            _gaq.push(['_addOrganic', 'tiu.ru', 'query']);
            _gaq.push(['_addOrganic', 'tech2u.ru', 'text']);
            _gaq.push(['_addOrganic', 'akavita.by', 'z']);
            _gaq.push(['_addOrganic', 'tut.by', 'query']);
            _gaq.push(['_addOrganic', 'all.by', 'query']);
            _gaq.push(['_addOrganic', 'meta.ua', 'q']);
            _gaq.push(['_addOrganic', 'bigmir.net', 'q']);
            _gaq.push(['_addOrganic', 'i.ua', 'q']);
            _gaq.push(['_addOrganic', 'online.ua', 'q']);
            _gaq.push(['_addOrganic', 'a.ua', 's']);
            _gaq.push(['_addOrganic', 'ukr.net', 'search_query']);
            _gaq.push(['_addOrganic', 'search.ua', 'query']);
            _gaq.push(['_trackPageview']);

<? include_slot('googleCart') ?>

            (function () {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </body>
</html>





















<?php
/* $path = sfConfig::get('sf_relative_url_root', preg_replace('#/[^/]+\.php5?$#', '', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : ''))) ?>

  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="title" content="symfony project" />
  <meta name="robots" content="index, follow" />
  <meta name="description" content="symfony project" />
  <meta name="keywords" content="symfony, project" />
  <meta name="language" content="en" />
  <title>symfony project</title>

  <link rel="shortcut icon" href="/favicon.ico" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $path ?>/sf/sf_default/css/screen.css" />
  <!--[if lt IE 7.]>
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $path ?>/sf/sf_default/css/ie.css" />
  <![endif]-->

  </head>
  <body>
  <div class="sfTContainer">
  <a title="symfony website" href="http://www.symfony-project.org/"><img alt="symfony PHP Framework" class="sfTLogo" src="<?php echo $path ?>/sf/sf_default/images/sfTLogo.png" height="39" width="186" /></a>
  <div class="sfTMessageContainer sfTAlert">
  <img alt="page not found" class="sfTMessageIcon" src="<?php echo $path ?>/sf/sf_default/images/icons/tools48.png" height="48" width="48" />
  <div class="sfTMessageWrap">
  <h1>Oops! An Error Occurred</h1>
  <h5>The server returned a "<?php echo $code ?> <?php echo $text ?>".</h5>
  </div>
  </div>

  <dl class="sfTMessageInfo">
  <dt>Something is broken</dt>
  <dd>Please e-mail us at [email] and let us know what you were doing when this error occurred. We will fix it as soon as possible.
  Sorry for any inconvenience caused.</dd>

  <dt>What's next</dt>
  <dd>
  <ul class="sfTIconList">
  <li class="sfTLinkMessage"><a href="javascript:history.go(-1)">Back to previous page</a></li>
  <li class="sfTLinkMessage"><a href="/">Go to Homepage</a></li>
  </ul>
  </dd>
  </dl>
  </div>
  </body>
  </html>
 */?>
