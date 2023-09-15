<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php use_stylesheet('admin.css') ?>
        <?php use_stylesheet('backendmenu.css') ?>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <script>


            $(document).ready(function () {
                $('.sf_admin_actions').clone().prependTo('form:last');
            });
        </script>
    </head>
    <body>
        <div id="container">

            <div id="menu">
                <?php
                if (sfContext::getInstance()->getUser()->hasPermission("All")):

                    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                    $countProducts = $q->execute("SELECT COUNT( * ) AS count "
                                    . "FROM product")
                            ->fetch(Doctrine_Core::FETCH_COLUMN);
                    $countProductsIsset = $q->execute("SELECT COUNT( * ) AS count "
                                    . "FROM product WHERE  `count` >0
                                        AND  `is_public` =1 ")
                            ->fetch(Doctrine_Core::FETCH_COLUMN);
                    ?>
                    <ul id="nav" class="backendnavmenu">
                        <li>
                            <a href="#" title="Всё что связано с товарами и категориями">Товары / Категории</a>
                            <ul>
                                <li><?php echo link_to('Товары ( ' . $countProductsIsset . ' / ' . $countProducts . ' )', 'product') ?></li>
                                <li><?php echo link_to('Товары ожидающие подтверждения', 'product/moder') ?></li>
                                <li><?php echo link_to('Excel файл с артикулами', 'product/getcodeproducts') ?></li>
                                <li><?php echo link_to('Отключённые товары', 'product/isnotenabled') ?></li>
                                <li><?php echo link_to('Добавление товаров менеджерами', 'product_manproduct') ?></li>
                                <li><?php echo link_to('Акции товаров', 'product_mproduct') ?></li>
                                <li><?php echo link_to('Дополнительные характеристики', 'dop_info') ?></li>
                                <li><?php echo link_to('Объединение доп. характеристик', 'dop_info_category_full') ?></li>
                                <li><?php echo link_to('Категории доп. хар.', 'dop_info_category') ?></li>
                                <li><?php echo link_to('Каталоги', 'catalog') ?></li>
                                <li><?php echo link_to('Категории', 'category') ?></li>
                                <li><?php echo link_to('Категории менеджера', 'category_mcategory') ?></li>
                                <li><?php echo link_to('Сравнение артикулов', 'product/codeisset') ?></li>
                                <li><?php echo link_to('Сортировка рекомендуемых товаров', 'product/indexrelated') ?></li>
                                <li><?php echo link_to('Производители', 'manufacturer') ?></li>
                                <li><?php echo link_to('Коллекции', 'collection') ?></li>
                                <li><?php echo link_to('Сообщить о поступлении', 'senduser') ?></li>
                                <li><?php echo link_to('Комментарии', 'comments') ?></li>
                                <li><?php echo link_to('Комментарии новая форма', 'comments2') ?></li>
                                <li><?php echo link_to('Акции', 'actions_discount') ?></li>
                                <li><?php echo link_to('Купоны', 'coupons') ?></li>
                                <li><?php echo link_to('Акции коллекций и брендов', 'dopinfoaction') ?></li>
                                <li><?php echo link_to('Фотографии пользователей', 'photos_user') ?></li>
                                <li><?php echo link_to('Скидки из Excel', 'setDiscount') ?></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="Всё что связано с заказами">Заказы</a>
                            <ul>
                                <li><?php echo link_to('Заказы', 'orders') ?></li>
                                <li><?php echo link_to('Документы оплаты', 'paymentdoc') ?></li>
                                <li><?php echo link_to('Заказы из магазинов', 'ordershop/index') ?></li>
                                <li><?php echo link_to('Бонусы', 'bonus') ?></li>
                                <li><?php echo link_to('Статистика бонусов', 'pages/bonusstats') ?></li>
                                <li><?php echo link_to('Начисление бонусов', 'bonus/adduser') ?></li>
                                <li><?php echo link_to('Доставка', 'delivery') ?></li>
                                <li><?php echo link_to('Оплата', 'payment') ?></li>
                                <li><?php echo link_to('Сортировка оплаты', 'delivery_payment') ?></li>
                                <li><?php echo link_to('Опросники', 'oprosnik') ?></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="Страницы, банеры и т.д.">Страницы</a>
                            <ul>
                                <li><?php echo link_to('Страницы', 'page') ?></li>
                                <li><?php echo link_to('Категории страниц', 'categorypage') ?></li>
                                <li><?php echo link_to('Новости', 'news') ?></li>
                                <li><?php echo link_to('Магазины', 'shops') ?></li>
                                <li><?php echo link_to('Комментарии', 'comments') ?></li>
                                <li><?php echo link_to('Меню', 'menu') ?></li>
                                <li><?php echo link_to('Баннеры', 'banners') ?></li>
                                <li><?php echo link_to('Верхний слайдер', 'sliders') ?></li>
                                <li><?php echo link_to('Нижняя плашка с таймером', 'bottombanner') ?></li>
                                <li><?php echo link_to('Видео', 'video') ?></li>
                                <li><?php echo link_to('Вакансии', 'vacancy') ?></li>
                                <li><?php echo link_to('Отклики на вакансии', 'vacancy_reply') ?></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="Статьи">Статьи / Гороскопы / Тесты</a>
                            <ul>
                                <li><?php echo link_to('Статьи', 'article') ?></li>
                                <li><?php echo link_to('Категории статей', 'articlecategory') ?></li>
                                <?/*<li><?php echo link_to('Каталоги статей', 'articlecatalog') ?></li>*/?>
                                <li><?php echo link_to('Ссылки в статьях', 'articlelink') ?></li>
                                <li><?php echo link_to('Faq', 'faq') ?></li>
                                <?/*<li><?php echo link_to('Категории Faq', 'faqcategory') ?></li>*/?>
                                <li><?php echo link_to('Гороскопы', 'horoscope') ?></li>
                                <li><?php echo link_to('Гороскопы совместимость', 'horoscopesovm') ?></li>
                                <li><?php echo link_to('Тесты', 'tests') ?></li>
                                <li><a href="/backend.php/experts">Эксперты</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="Настройки">Настройки</a>
                            <ul>
                                <li><?php echo link_to('Настройки', 'cs_setting') ?></li>
                                <li><?php echo link_to('Синонимы поиска', 'synonyms') ?></li>
                                <li><?php echo link_to('Сброс кэша', 'clearCache') ?></li>
                                <li><?php echo link_to('Колесо фортуны', 'wheelsectors') ?></li>
                                <li><?php echo link_to('Пользователи', '/backend.php/guard/users') ?></li>
                                <li><?php echo link_to('Экспорт Пользователей', 'getMailDB2') ?></li>
                                <li><?php echo link_to('Редирект', '/backend.php/redirect') ?></li>
                                <li><?php echo link_to('Письма при смене статусов', 'mailchangestatus') ?></li>
                                <li><?php echo link_to('Шаблоны писем', 'mail_templates') ?></li>
                                <li>
                                    <?php echo link_to(' Лог товаров с акциями', 'product_action_log') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Лог отсылки писем о сроках сгорания бонусов', 'bonus_mailsend_log') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Лог отправленных писем', 'mailsendlog') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Лог ошибок почты', '/logvalidmail.txt') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Все артикулы', 'pages/articlesshow') ?>
                                </li>
                                <li>
                                    <?php// echo link_to(' Список непривязанных городов', 'cityUndraw') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Валидность страниц', 'validw3') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' База почт', 'getMailDB') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Привязка товара к категории', 'pages/setCategoryToArt') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Загрузка промокодов', 'pages/importPromo') ?>
                                </li>
                                <li>
                                    <?php //echo link_to(' Обновление товаров Лидеры продаж', 'pages/setBestSellers') ?>
                                </li>
                                <li>
                                    <?php //echo link_to(' Обновление товаров Новые поступления', 'pages/setNewArrivals') ?>
                                </li>
                                <li>
                                    <?php //echo link_to(' Обновление товаров не входящих в Перс. Рекоменд.', 'pages/setNoPersonalRecomendation') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Отключение товаров списком Excel', 'setNotPublic') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Смена артикулов списком Excel', 'changeCode') ?>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" title="Статистика">Статистика</a>
                            <ul>
                                <li><a href="/backend.php/abandonedbaskets">Брошенные корзины</a></li>
                                <li><?php echo link_to('Переходы по QR-кодам', 'qrredirects') ?></li>
                                <li><?php echo link_to('Активации сервисной гарантии', 'servicerequest') ?></li>
                                <li><?php echo link_to('Подписки на уведомления об отсутствующих товарах', 'notify') ?></li>
                                <li><?php echo link_to('Статистика по отсутствующим товарам', 'pages/usersendstats') ?></li>
                                <li><?php echo link_to('Статистика по всем товарам(выгрузка в Excel)', 'pages/statsProduct') ?></li>
                                <li><?php echo link_to('Статистика по источникам заказов (выгрузка в Excel)', 'pages/ordersReports2') ?></li>
                                <li><?php echo link_to('Статистика по заказам (выгрузка в Excel)', 'pages/ordersReports') ?></li>
                                <li><?php echo link_to('Статистика по источникам заказов (метрика) (выгрузка в Excel)', 'pages/ordersReports2YM') ?></li>
                                <li><?php echo link_to('Статистика по заказам (метрика) (выгрузка в Excel)', 'pages/ordersReportsYM') ?></li>
                                <li><?php echo link_to('Статистика по купонам', 'pages/couponsReport') ?></li>
                                <li><?php echo link_to('Статистика заказов из магазинов', 'ordershop/stats') ?></li>
                                <li><?php echo link_to('Статистика менеджеров', 'pages/managersstats') ?></li>
                                <li><?php echo link_to('Просмотры страниц', 'pages/viewcount') ?></li>
                                <li>
                                    <?php echo link_to(' Статистика поисковых запросов', 'searchLog') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Статистика клиентов по картам и телефонам', 'pages/statsCard') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Cамые комментируемые товары', 'pages/bestCommentsProducts') ?>
                                </li>
                                <li>
                                    <?php echo link_to(' Cамые продаваемые товары', 'pages/bestSalesProducts') ?>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php echo link_to('Выход', '/backend.php/guard/logout') ?>
                        </li>
                    </ul>

                <?php endif; ?>


                <?php if (sfContext::getInstance()->getUser()->hasPermission("SEO Параметры")): ?>

                    <ul id="nav" class="backendnavmenu">
                        <li>
                            <a href="#" title="Модули">Модули</a>
                            <ul>
                                <li><?php echo link_to('Категории', 'category/index') ?></li>
                                <li><?php echo link_to('Товары', 'product/index') ?></li>
                                <li><?php echo link_to('Коллекции', 'collection/index') ?></li>
                                <li><?php echo link_to('Производители', 'manufacturer/index') ?></li>
                                <li><?php echo link_to('Страницы', 'pages/index') ?></li>
                            </ul>
                        </li>
                        <li>
                            <?php echo link_to('Выход', '/backend.php/guard/logout') ?>
                        </li>
                    </ul>
                <?php endif; ?>


                <ul>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Модерирование товаров")): ?>
                        <li><?php echo link_to('Товары ожидающие подтверждения', 'product/moder') ?></li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Просмотр фото и видео товаров")): ?>
                        <li>
                            <?php echo link_to('Товары - фото и видео', 'product') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Bonus")): ?>
                        <li>
                            <?php echo link_to('Бонусы', 'bonus') ?>
                        </li>
                        <li>
                            <?php echo link_to('Начисление бонусов', 'bonus/adduser') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Обновление товаров Лидеры продаж")): ?>
                        <li>
                            <?php echo link_to(' Обновление товаров Лидеры продаж', 'pages/setBestSellers') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Обновление товаров не входящих в Перс. Рекоменд.")): ?>
                        <li>
                            <?php echo link_to(' Обновление товаров не входящих в Перс. Рекоменд.', 'pages/setNoPersonalRecomendation') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager category")): ?>
                        <li>
                            <?php echo link_to('Категории', 'category_mcategory') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager product")): ?>

                        <li>
                            <?php echo link_to('Скидки на товары', 'product_mproduct') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager add product")): ?>

                        <li><?php echo link_to('Товары', 'product_manproduct') ?></li>
                        <li><?php echo link_to('Дополнительные характеристики', 'dop_info') ?></li>
                        <li><?php echo link_to('Объединение доп. характеристик', 'dop_info_category_full') ?></li>
                        <li><?php echo link_to('Категории доп. характеристик', 'dop_info_category') ?></li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager article")): ?>
                        <li><?php echo link_to('Категории статей', 'articlecategory') ?></li>
                        <?/*<li><?php echo link_to('Каталоги статей', 'articlecatalog') ?></li>*/?>
                        <li><?php echo link_to('Статьи', 'article') ?></li>
                        <?/*<li><?php echo link_to('Гороскопы', 'horoscope') ?></li>
                        <li><?php echo link_to('Гороскопы совместимость', 'horoscopesovm') ?></li>
                        <li><?php echo link_to('Тесты', 'tests') ?></li>*/?>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Менеджер тестов")): ?>
                        <li><?php echo link_to('Тесты', 'tests') ?></li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager orders")): ?>
                        <li>
                            <?php echo link_to('Заказы', 'orders') ?>
                        </li>
                        <li><?php echo link_to('Статистика менеджеров', 'pages/managersstats') ?></li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager oprosnik")): ?>
                        <li><?php echo link_to('Опросники', 'oprosnik') ?></li>
                    <?php endif; ?>
                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager Sravnenie Article")): ?>
                        <li><?php echo link_to('Сравнение артикулов', 'product/codeisset') ?></li>
                    <?php endif; ?>

                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager Product non count")): ?>

                        <li><?php echo link_to('Статистика по отсутствующим товарам', 'pages/usersendstats') ?></li>
                    <?php endif; ?>

                    <?php if (sfContext::getInstance()->getUser()->hasPermission("Manager Статистика по всем товарам")): ?>
                        <li><?php echo link_to('Статистика по всем товарам(выгрузка в Excel)', 'pages/statsProduct') ?></li>
                    <?php endif; ?>

                    <?php if (!sfContext::getInstance()->getUser()->hasPermission("All")): ?>
                        <li>
                            <?php echo link_to('Выход', '/backend.php/guard/logout') ?>
                        </li>
                    <?php endif; ?>


                </ul>
            </div>

            <p class="clear" />
            <?php if (sfContext::getInstance()->getUser()->hasPermission("All")): ?>
                <form action="/backend.php/search" id="search" method="post" style="margin-left: 43px;">
                    <input type="search" id="searchText" name="searchString" />
                    <input type="submit" id="find" value="Поиск" onclick="
                                if ($('#searchText').val().length < 3) {
                                    alert('Должно быть больше 3 символов');
                                    return false;
                                }" />
                </form>
                <script>

                    $(document).ready(function () {

                        $(".sf_admin_batch_actions_choice:first select").change(function () {
                            var str = "";

                            $(".sf_admin_batch_actions_choice select option:selected").each(function () {
                                if (str == "") {
                                    str = $(this).text();
                                    console.log(str);
                                }

                            });

                            $(".sf_admin_batch_actions_choice select").each(function () {


                                $(this).find("option").each(function () {

                                    if ($(this).text() == str) {
                                        $(this).attr("selected", "selected")
                                    }
                                });
                            });
                        }).change();

                        $(".sf_admin_batch_actions_choice:last select").change(function () {
                            var str = "";

                            $(".sf_admin_batch_actions_choice select:last option:selected").each(function () {
                                if (str == "") {
                                    str = $(this).text();
                                    console.log(str);
                                }

                            });

                            $(".sf_admin_batch_actions_choice select").each(function () {


                                $(this).find("option").each(function () {

                                    if ($(this).text() == str) {
                                        $(this).attr("selected", "selected")
                                    }
                                });
                            });
                        }).change();

                    });
                </script>
            <?php endif; ?>
            <p class="clear" />
            <div id="content">
                <?php
                echo $sf_content;
                ?>
            </div>

        </div>
    </body>
</html>
