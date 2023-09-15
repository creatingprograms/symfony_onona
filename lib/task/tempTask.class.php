<?php

class tempTask extends sfBaseTask {

    protected function configure() {
// // add your own arguments here
// $this->addArguments(array(
//   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
// ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'temp';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [temp|INFO] task does things.
Call it with:

  [php symfony temp|INFO]
EOF;
    }

    public function is_email($email) {
//return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//echo "Valid email address.";
            return true;
        } else {
            return false;
//echo "Invalid email address.";
        }
    }

    protected function execute($arguments = array(), $options = array()) {
// initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        /*
         * Сколько бонусов за отменёные и возвращённые заказы по месяцам


          $orders = OrdersTable::getInstance()->createQuery()->where("status like \"%Отмена%\" or status like \"%Возврат%\"")->fetchArray();
          foreach ($orders as $num => $order) {
          //print_r($order);exit;
          //echo $order->getId();
          $bonus = BonusTable::getInstance()->findOneByComment("Зачисление за заказ #A11" . $order['id']);
          if ($bonus) {
          if ($order['status'] == "Отмена") {
          $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_canсel'] = $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_canсel'] + 1;
          $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Bonus_canсel'] = $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Bonus_canсel'] + $bonus->getBonus();
          $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_canсel_id'] = $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_canсel_id'] . ", A11" . $order['id'];
          } elseif ($order['status'] == "Возврат") {
          $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_return'] = $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_return'] + 1;
          $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Bonus_return'] = $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Bonus_return'] + $bonus->getBonus();
          $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_return_id'] = $arrayOrders[date("m.Y", strtotime($order['created_at']))]['Orders_return_id'] . ", A11" . $order['id'];
          }
          unset($bonus);
          }
          }
          print_r($arrayOrders); */

        /*
         * Заказы с оценкой доставки меньше 3 после 1 января 2015
         * 
         */
        /* $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute('SELECT * FROM `oprosnik` where (dataans like \'%"deliveryWork";s:1:"3"%\' or dataans like \'%"deliveryWork";s:1:"2"%\' or dataans like \'%"deliveryWork";s:1:"1"%\') and created_at > "2015-01-01 00:00:00"');
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $oprosniks = $result->fetchAll();

          foreach ($oprosniks as $oprosnik) {
          $data=  unserialize($oprosnik['dataans']);
          echo "Заказ №A11".$oprosnik['orderid']." - Оценка ".$data['deliveryWork']." - Комментарий:".$data['commentDelivery']."\n";
          //print_r($data);
          } */

        /*
          for($i=0;$i<3000;$i++){
          sleep(1);
          print_r(time()."\r\n");
          }
         */
        /* $products = ProductTable::getInstance()->createQuery()->where("name LIKE '% JO %'")->orderBy("position asc")->execute();
          foreach ($products as $numProd => $product) {
          $product->setPosition($numProd);
          $product->save();
          } */
        /*

          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute('SELECT *
          FROM (

          SELECT COUNT( phone ) as count_phone , phone
          FROM  `sf_guard_user`
          WHERE phone !=  "" and phone !="123456"
          GROUP BY phone
          ) AS phone where phone.count_phone>1');
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $phones = $result->fetchAll();

          foreach ($phones as $phone) {
          $users = sfGuardUserTable::getInstance()->findByPhone($phone['phone']);
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute('        SELECT SUM( bonus ) as sum_bonus, user_id
          FROM `bonus`
          WHERE `user_id`
          IN ( ' . implode(",", $users->getPrimaryKeys()) . ' )
          GROUP BY user_id
          ORDER BY SUM( bonus ) DESC
          LIMIT 0, 1');
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonus_max_users = $result->fetchAll();
          $BonusMaxUsers=$BonusMaxUsers+$bonus_max_users['0']['sum_bonus'];


          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute('SELECT SUM( bonus_users.sum_bonus )  as bonus_all_users
          FROM (
          SELECT SUM( bonus ) as sum_bonus, user_id
          FROM `bonus`
          WHERE `user_id`
          IN ( ' . implode(",", $users->getPrimaryKeys()) . ' )
          GROUP BY user_id) AS bonus_users');
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonus_all_users = $result->fetchAll();
          $BonusAllUsers=$BonusAllUsers+$bonus_all_users['0']['bonus_all_users'];
          }
          echo $BonusMaxUsers."-".$BonusAllUsers;

         */


        /* echo "select * from (SELECT *, MAX(created_at) as max, unix_timestamp(MAX(created_at)) as max_unix, sum(bonus) as sum FROM bonus GROUP BY user_id ORDER BY max asc) as fr WHERE sum>0 and max_unix<" . (time() - 90 * 24 * 60 * 60);
         */

        /*
          for ($i = 1; $i <= 30; $i++) {
          if ($i != 2):
          $spisano = 0;
          $firstTotalPrice = 0;
          $ordersGood = 0;
          $firstTotalPriceGood = 0;
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT b . * , u.id
          FROM  `bonus_mailsend_log` m
          LEFT JOIN sf_guard_user u ON u.email_address = m.mail
          LEFT JOIN bonus b ON b.user_id = u.id
          AND b.created_at >  '2015-06-" . ($i < 10 ? ('0' . $i) : $i) . " 02:00:00'
          AND b.created_at <  '2015-" . (($i + 3) > 30 ? '07' : '06') . "-" . (($i + 3) > 30 ? ($i - 27) :($i+3 < 10 ? ('0' . $i+3) : $i+3)) . " 03:00:00'
          AND b.comment LIKE  '%Снятие бонусов в счет оплаты заказа %'
          WHERE m.created_at LIKE  '%2015-06-" . ($i < 10 ? ('0' . $i) : $i) . "%'
          AND b.id IS NOT NULL ");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonus_all_users = $result->fetchAll();
          echo "Письма отправлялись " . $i . ".06.15. Дата сгорания бонусов " . (($i + 3) > 30 ? ($i - 27) : ($i+3 < 10 ? ('0' . $i+3) : $i+3)) . "." . (($i + 3) > 30 ? '07' : '06') . ".15. \r\n";

          $q2 = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result2 = $q2->execute("SELECT COUNT( * ) as count
          FROM  `bonus_mailsend_log`
          WHERE  `created_at` LIKE  '%2015-06-" . ($i < 10 ? ('0' . $i) : $i) . "%'");
          $result2->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonus_mail_count = $result2->fetchColumn();
          echo "Cколько писем было отправлено: " . $bonus_mail_count . " \r\n";
          echo "Сколько сделали заказ после письма до сгорания бонусов: " . count($bonus_all_users) . " \r\n";
          foreach ($bonus_all_users as $bonus) {
          $spisano = $spisano + $bonus['bonus'];
          $order = OrdersTable::getInstance()->findOneById(substr($bonus['comment'], -6));
          $firstTotalPrice = $firstTotalPrice + $order->getFirsttotalcost();
          if ($order->getStatus() != "Отмена") {
          $ordersGood = $ordersGood + 1;
          $firstTotalPriceGood = $firstTotalPriceGood + $order->getFirsttotalcost();
          }
          }

          echo "Сколько заказов не в статусе отмена: " . $ordersGood . " \r\n";
          echo "Сумма списаных бонусов: " . $spisano . " \r\n";
          echo "Сумма первоначальной цены всех заказов(за вычетом бонусов): " . $firstTotalPrice . " \r\n";
          echo "Сумма первоначальной цены заказов не в статусе отмена(за вычетом бонусов): " . $firstTotalPriceGood . " \r\n";


          echo "\r\n";
          endif;
          } */
        /*
          $users = sfGuardUserTable::getInstance()->createQuery()->addWhere("phone is not null and LENGTH(phone)>8 and LENGTH(phone)<16")->limit(100000)->fetchArray();
          $numPhoneUnCorrect = 0;
          foreach ($users as $user) {

          if ($user['phone'][0] != "+" and $user['phone'][0] != "8") {
          $user['phone'] = "+7" . $user['phone'];
          }
          $numbersPhoneReplace77 = str_replace("+77", "+7", $user['phone']);
          $numbersPhoneReplace77 = preg_replace('/[^\d]/', '', $numbersPhoneReplace77);
          $numbersPhone = preg_replace('/[^\d]/', '', ($user['phone']));
          if (strlen($numbersPhone) == 11 and ( $numbersPhone[0] == 7 or $numbersPhone[0] == 8)) {

          $sPhone = "+7(" . $numbersPhone[1] . $numbersPhone[2] . $numbersPhone[3] . ")" . $numbersPhone[4] . $numbersPhone[5] . $numbersPhone[6] . "-" . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9] . $numbersPhone[10];

          //echo $user ['phone'] . "  -  " . $sPhone . "  \r\n";
          } elseif (strlen($numbersPhone) == 10 and $numbersPhone[0] != 8 and $numbersPhone[0] != 7) {
          $sPhone = "+7(" . $numbersPhone[0] . $numbersPhone[1] . $numbersPhone[2] . ")" . $numbersPhone[3] . $numbersPhone[4] . $numbersPhone[5] . "-" . $numbersPhone[6] . $numbersPhone[7] . $numbersPhone[8] . $numbersPhone[9];

          //echo $user ['phone'] . "  -  " . $sPhone . "  \r\n";
          } elseif (strlen($numbersPhoneReplace77) == 11 and ( $numbersPhoneReplace77[0] == 7 or $numbersPhoneReplace77[0] == 8)) {

          $sPhone = "+7(" . $numbersPhoneReplace77[1] . $numbersPhoneReplace77[2] . $numbersPhoneReplace77[3] . ")" . $numbersPhoneReplace77[4] . $numbersPhoneReplace77[5] . $numbersPhoneReplace77[6] . "-" . $numbersPhoneReplace77[7] . $numbersPhoneReplace77[8] . $numbersPhoneReplace77[9] . $numbersPhoneReplace77[10];

          //echo $user ['phone'] . "  -  " . $sPhone . "  \r\n";
          } else {
          $sPhone = $user ['phone'];
          $numPhoneUnCorrect++;
          echo $user ['phone'] . "  -  " . $sPhone . "  \r\n";
          }
          $userClass = sfGuardUserTable::getInstance()->findOneById($user ['id']);
          if ($userClass->getOldphone() == "") {
          $userClass->setOldphone($userClass->getPhone());
          $userClass->setPhone($sPhone);
          $userClass->save();
          }
          }
          echo $numPhoneUnCorrect; */


        /* echo sprintf("MAIL FROM: <%s>%s\r\n", "info@onona.ru", "")."<br>"; */

   
        /* $is_error = false;
          $photos = PhotoTable::getInstance()->createQuery()->fetchArray();
          foreach ($photos as $photo) {

          if (!file_exists("/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename'])) {
          echo "not File photo/" . $photo['filename'] . "\r\n";

          $is_error = true;
          } else {
          if (exif_imagetype("/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename']) === false) {
          echo "Error image photo/" . $photo['filename'] . "\r\n";

          $is_error = true;
          }
          }
          if (!file_exists("/var/www/ononaru/data/www/onona.ru/uploads/photo/thumbnails_60x60/" . $photo['filename'])) {
          echo "not File photo/thumbnails_60x60/" . $photo['filename'] . "\r\n";
          $is_error = true;
          } else {
          if (exif_imagetype("/var/www/ononaru/data/www/onona.ru/uploads/photo/thumbnails_60x60/" . $photo['filename']) === false) {
          echo "Error image photo/thumbnails_60x60/" . $photo['filename'] . "\r\n";

          $is_error = true;
          }
          }
          if (!file_exists("/var/www/ononaru/data/www/onona.ru/uploads/photo/thumbnails_250x250/" . $photo['filename'])) {
          echo "not File photo/thumbnails_250x250/" . $photo['filename'] . "\r\n";
          $is_error = true;
          } else {
          if (exif_imagetype("/var/www/ononaru/data/www/onona.ru/uploads/photo/thumbnails_250x250/" . $photo['filename']) === false) {
          echo "Error image photo/thumbnails_250x250/" . $photo['filename'] . "\r\n";

          $is_error = true;
          }
          }
          if (!file_exists("/var/www/ononaru/data/www/onona.ru/uploads/photo/original_photo/" . $photo['filename'])) {
          //                if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename'])) {
          //                    copy("/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename'], "/var/www/ononaru/data/www/onona.ru/uploads/photo/original_photo/" . $photo['filename']);
          //                }
          echo "not File photo/original_photo/" . $photo['filename'] . "\r\n";
          $is_error = true;
          } else {
          if (exif_imagetype("/var/www/ononaru/data/www/onona.ru/uploads/photo/original_photo/" . $photo['filename']) === false) {
          echo "Error image photo/original_photo/" . $photo['filename'] . "\r\n";

          $is_error = true;
          }
          }
          if ($is_error) {
          $photoalmub = ProductPhotoalbumTable::getInstance()->findOneByPhotoalbumId($photo['album_id']);
          echo "Product Id: " . $photoalmub->getProductId() . "\r\n";
          }
          $is_error = false;
          } */

        /*   sfContext::createInstance($this->configuration);
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT *
          FROM  `bonus`
          WHERE  `comment` LIKE  '%Зачисление за покупку в магазине. %'
          AND  `created_at` >  '2015-09-11 00:00:00'
          AND  `created_at` <  '2015-09-11 05:00:00'");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonusLog = $result->fetchAll();
          foreach ($bonusLog as $bonus) {
          $user = sfGuardUserTable::getInstance()->findOneById($bonus['user_id']);
          if ($this->is_email(trim($user->getEmailAddress()))) {
          $message = Swift_Message::newInstance();
          $message->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
          ->setTo(trim($user->getEmailAddress()))
          ->setSubject("Уведомления о зачисление бонусов на сайте onona.ru")
          ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($user->getName(), "OnOna.ru", $bonus['bonus'], csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html');


          $numSent = $this->getMailer()->send($message);
          sleep(3);
          }
          }
         */
        /*
         * SELECT * 
          FROM (

          SELECT * , COUNT(
          COMMENT ) AS count
          FROM  `bonus`
          WHERE created_at >  '2015-11-01 00:00:00'
          AND COMMENT LIKE  '%Зачисление за покупку в магазине. Номер чека%'
          GROUP BY COMMENT ORDER BY COUNT(
          COMMENT ) ASC
          ) AS b
          WHERE b.count >1
         */
        /* $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT *
          FROM (

          SELECT * , COUNT(
          COMMENT ) AS count, sum(bonus) as sum
          FROM  `bonus`
          WHERE created_at >  '2015-11-01 00:00:00'
          AND COMMENT LIKE  '%Зачисление за покупку в магазине. Номер чека%'
          GROUP BY COMMENT ORDER BY COUNT(
          COMMENT ) ASC
          ) AS b
          WHERE b.count >1");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonusLog = $result->fetchAll();
          foreach ($bonusLog as $bonus) {

          $result = $q->execute("
          SELECT sum(bonus) as sum
          FROM  `bonus`
          WHERE user_id= '" . $bonus['user_id'] . "'");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $sumUserBonus = $result->fetchAll();
          if ($sumUserBonus[0]['sum'] < ($bonus['sum'] - $bonus['bonus'])) {
          print_r($sumUserBonus);
          print_r($bonus);
          exit;
          } else {
          echo $bonus['user_id'] . " " . $sumUserBonus[0]['sum'] . " " . ($bonus['sum'] - $bonus['bonus']) . "\n";
          }
          $result = $q->execute("
          SELECT *
          FROM  `bonus`
          WHERE created_at >  '2015-11-01 00:00:00' AND
          COMMENT LIKE  '%" . $bonus['comment'] . "%'"
          . " ORDER BY `bonus`.`created_at`  ASC"
          . " limit 1,10000");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $bonusLogThisCheck = $result->fetchAll();
          $result = $q->execute("
          DELETE FROM  bonus
          WHERE created_at >  '2015-11-01 00:00:00' AND COMMENT LIKE  '%" . $bonus['comment'] . "%'  ORDER BY created_at  DESC
          limit ".($bonus['count']-1));

          //print_r($bonusLogThisCheck);
          } */
// add your code here
        /*
          sfContext::createInstance($this->configuration);
          $message = Swift_Message::newInstance()
          ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
          ->setTo('smakemy@gmail.com')
          ->setSubject("Уведомления о зачисление бонусов на сайте onona.ru")
          ->setBody('Test', 'text/html')
          ->setContentType('text/html');


          $numSent = $this->getMailer()->send($message); */




        /* $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT *
          FROM  `sf_guard_user`
          WHERE  `last_ip` IS NOT NULL ");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $users = $result->fetchAll();
          $SxGeo = new SxGeo('apps/newcat/lib/SxGeoCity.dat');
          $int = 0;
          foreach ($users as $user) {
          $geoData = $SxGeo->getCityFull($user['last_ip']); //Москва Московская область

          if ($geoData['region']['name_ru'] == "Москва" or $geoData['region']['name_ru'] == "Московская область") {
          $int++;
          }
          }
          //echo $int;



          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("SELECT *
          FROM  `orders`
          WHERE  created_at > '2015-01-01 00:00:00' ");
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $users = $result->fetchAll();
          $SxGeo = new SxGeo('apps/newcat/lib/SxGeoCity.dat');
          $int = 0;
          foreach ($users as $user) {
          $geoData = $SxGeo->getCityFull($user['ipuser']); //Москва Московская область

          if ($geoData['region']['name_ru'] == "Москва" or $geoData['region']['name_ru'] == "Московская область") {
          $int++;
          }
          }
          echo $int; */
        /*
          $templatesHTML = '<p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          Здравствуйте, жители {cityNameRod}.<br />
          Компания &laquo;Он и Она&raquo; предлагает самую выгодную доставку в ваш город. Выбирайте наиболее подходящий вам вариант доставки. Если возникнут трудности с выбором, то наши менеджеры помогут вам во всем разобраться и сделать правильный выбор.</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="color: rgb(128, 0, 0);">Мы гарантируем полную конфиденциальность на всех этапах обработки и доставки заказа.</span></p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="color: rgb(128, 0, 0);">Все заказы отправляем в нейтральной непрозрачной упаковке, без логотипов.</span></p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          &nbsp;</p>
          <script src="//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full" type="text/javascript"></script>
          <script type="text/javascript">
          ymaps.ready(function () {

          ymaps.geocode("{cityName}").then(function (res) {
          var myMap = new ymaps.Map("YMapsID", {

          center: res.geoObjects.get(0).geometry.getCoordinates(),
          zoom: 10
          });


          });
          });
          </script>

          <style type="text/css">
          #YMapsID {
          width: 760px;
          height: 450px;
          }
          </style>

          <div id="YMapsID"></div><br />

          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="font-size: 16px; color: rgb(178, 34, 34);">Доставка почтой России в г. {cityName}</span></p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <img alt="" src="/uploads/assets/images/crest-post0t.jpg" style="border: 0px; width: 120px; height: 71px; margin-left: 5px; margin-right: 5px; float: left;" />Срок доставки в г. {cityName} составляет 2-3 недели.<br />
          <br />
          <span style="color: rgb(128, 0, 128);">Стоимость доставки почтой России</span><br />
          <u>1. Стоимость почтовой доставки заказов до 2990 р.</u><br />
          - Если&nbsp;стоимость вашего заказа до 2990 р., то стоимость доставки рассчитывается так:&nbsp;5% от суммы заказа (почтовые сборы) + 220 р. обработка и отправка и заказа.&nbsp;<br />
          - Если&nbsp;вы вносите<span style="color: rgb(178, 34, 34);">&nbsp;предоплату от 20%</span>&nbsp;от стоимости заказа, то платите только 220 р. за обработку и отправку заказа,&nbsp;<u>почтовые сборы в этом случае не взимаются</u>.</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <u>2. Стоимость почтовой доставки заказов от 2990 р.</u><br />
          -&nbsp;Если&nbsp;стоимость вашего заказа выше 2990 р., то мы доставим ваш заказ совершенно&nbsp;<span style="color: rgb(128, 0, 0);">БЕСПЛАТНО*</span>.<br />
          - Заказы свыше 5000 р. отправляются только по предоплате от 20% от стоимости заказа!</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="color: rgb(128, 0, 0);">*</span>- Бесплатная доставка предоставляется при стоимости заказа от 2990 руб. после вычета всех скидок и бонусов.<br />
          &nbsp;<br />
          <span style="font-size: 14px;"><strong>Внимание!</strong></span>&nbsp;Почта России взимает комиссию за пересылку денежных средств (наложенного платежа), которую оплачивает получатель посылки.<br />
          <span style="color: rgb(128, 0, 128);">Тарифы почты за пересылку денежных средств:</span><br />
          - до 1 000 руб. включительно 40 руб. + 5% от суммы<br />
          - свыше 1 000 до 5 000 руб. включительно 50 руб. + 4% от суммы<br />
          - свыше 5 000 руб. до 20 000 руб. включительно 150 руб. + 2% от суммы<br />
          - свыше 20 000 руб. до 500 000 руб. включительно 250 руб. + 1,5% от суммы<br />
          Тариф включает НДС в размере, предусмотренном действующим законодательством Российской Федерации.<br />
          <br />
          <span style="font-size: 14px;"><span style="color: rgb(178, 34, 34);">Мы гарантируем полную конфиденциальность.</span></span><br />
          Кроме вас посылку на почте никто получить не сможет, т.к. требуется предъявлять паспорт.<br />
          <strong>Все заказы отправляем в нейтральной почтовой упаковке без логотипов.</strong><br />
          Отправитель: ООО &quot;Ай Эм Логистикс Почта&quot;, получатель платежа: ООО &quot;Он и Она&quot;.</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          &nbsp;</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          &nbsp;</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          Желаем вам приятных покупок!</p>
          ';

          require_once 'PHPExcel/Classes/PHPExcel.php';
          $objReader = PHPExcel_IOFactory::createReaderForFile('/var/www/ononaru/data/mod-tmp/cities.csv');
          $objReader->setReadDataOnly(true);
          $this->PHPExcel = $objReader->load('/var/www/ononaru/data/mod-tmp/cities.csv');
          foreach ($this->PHPExcel->getActiveSheet()->toArray() as $keyRows => $row) {
          $cities[$row[1]]['rod'] = $row[2];
          $cities[$row[1]]['pred'] = $row[3];
          }

          $objReader = PHPExcel_IOFactory::createReaderForFile('/var/www/ononaru/data/mod-tmp/KLADR.xlsx');
          $objReader->setReadDataOnly(true);
          $this->PHPExcel = $objReader->load('/var/www/ononaru/data/mod-tmp/KLADR.xlsx');
          // $objPHPExcel = PHPExcel_IOFactory::load(file_get_contents("test.xls"));
          $i = 0;
          foreach ($this->PHPExcel->getActiveSheet()->toArray() as $keyRows => $row) {
          if ($keyRows != 0) {
          //if ($row[1] == "г" or $row[1] == "пгт" or $row[1] == "п" ) {
          if ($row[1] == "г") {
          $RussionPostPage = RussianPostCityTable::getInstance()->findOneBySlug("sexshop_" . SlugifyClass::Slugify($row[0]) . "_dostavka");
          if (!$RussionPostPage) {
          $newRussionPostPage = new RussianPostCity();
          $newRussionPostPage->set("name", "Быстрая, выгодная доставка в г. " . $row[0] . " от секс-шопа «Он и Она»");
          $newRussionPostPage->set("slug", "sexshop_" . SlugifyClass::Slugify($row[0]) . "_dostavka");
          $newRussionPostPage->set("content", str_replace(array("{cityName}", "{cityNameRod}"), array($row[0], ($cities[$row[0]]['rod'] != "" ? $cities[$row[0]]['rod'] : $row[0])), $templatesHTML));
          $newRussionPostPage->set("city", $row[0]);
          $newRussionPostPage->set("is_public", true);
          $newRussionPostPage->set("is_show_right_block", true);
          $newRussionPostPage->save();
          $i++;
          }
          unset($RussionPostPage);
          }
          }
          }
          echo $i; */
        /* $startMemory = memory_get_usage();
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();

          if ($xmldb = simplexml_load_file("http://lk.4partner.ru/public/database.xml")) {

          $productInBaseCode = $q->execute("SELECT code FROM  `product` ")->fetchAll(Doctrine_Core::FETCH_COLUMN);
          foreach ($xmldb->xpath('products/product') as $product) {
          $productInXMLCode[] = trim($product['code']);
          }

          //            print_r(array_diff($productInBaseCode,$productInXMLCode));
          //            echo count(array_diff($productInBaseCode,$productInXMLCode));
          }
          printf("Bytes diff: %d\n", memory_get_usage() - $startMemory); */






        /*



          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $i = 0;
          $allUser = $q->execute("SELECT * FROM  `sf_guard_user` WHERE  `created_at` >=  '2012-06-21 17:12:24' ")->fetchAll(Doctrine_Core::FETCH_ASSOC);
          foreach ($allUser as $user) {
          $bonus = $q->execute("SELECT count(*) FROM  `bonus` where bonus<0 and user_id='".$user['id']."' and comment NOT LIKE 'Снятие средств. Истекло время жизни.%'")->fetch(Doctrine_Core::FETCH_COLUMN);
          if ($bonus == 0) {
          $i++;
          }
          }
          print_r($i);

         */
        /*
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $allCitys = CityTable::getInstance()->findAll();
          foreach ($allCitys as $city) {
          $city->setSlug(SlugifyClass::Slugify($city->getName()));


          $templatesHTML = '<p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          Здравствуйте, жители {cityNameRod}.<br />
          Компания &laquo;Он и Она&raquo; предлагает самую выгодную доставку в ваш город. Выбирайте наиболее подходящий вам вариант доставки. Если возникнут трудности с выбором, то наши менеджеры помогут вам во всем разобраться и сделать правильный выбор.</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="color: rgb(128, 0, 0);">Мы гарантируем полную конфиденциальность на всех этапах обработки и доставки заказа.</span></p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="color: rgb(128, 0, 0);">Все заказы отправляем в нейтральной непрозрачной упаковке, без логотипов.</span></p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          &nbsp;</p>
          <br />
          <a href="/dostavka_PickPoint"><img alt="" src="/userfiles/images/LogoPickPoint.jpg" style="width: 200px; height: 59px; float: left; margin-left: 5px; margin-right: 5px;" /></a>Мы предлагаем вашему вниманию службу доставки <a href="/dostavka_PickPoint">Pick Point</a>, которая в {cityNamePred} имеет сеть автоматических терминалов (постаматов) и пунктов выдачи. Вы можете выбрать ближайший к вам постамат или пункт выдачи заказов, и в удобное время забрать и оплатить заказ, сделанный в нашем интернет-магазине.<br />
          &nbsp;<br />
          <em>Чем интересны постаматы Pick Point?</em><br />
          Постаматы &ndash; специальные автоматические терминалы, которые удобно территориально расположены, быстры, надежны, просты в использовании и у получателя всегда есть свободный выбор времени получения заказа.<br />
          Срок доставки в г. {cityName} составляет 3-4 дней.</p>
          <p>
          <span style="color:#4b0082;">Тарифы на доставку Pick Point</span><br />
          <img alt="" src="/userfiles/images/dostavka-cena.jpg" style="width: 240px; height: 64px;" /></p>
          <p>
          <script type="text/javascript" src="https://pickpoint.ru/select/postamat.js"></script><span style="color:#4b0082;">{shopsMap}<br />
          {shopsListPickpoint}<br />
          <img alt="" src="/userfiles/images/poloska-OniOna.png" style="width: 530px; height: 12px;" /></span></p>

          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="font-size: 16px; color: rgb(178, 34, 34);">Доставка почтой России в г. {cityName}</span></p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <img alt="" src="/uploads/assets/images/crest-post0t.jpg" style="border: 0px; width: 120px; height: 71px; margin-left: 5px; margin-right: 5px; float: left;" />Срок доставки в г. {cityName} составляет 2-3 недели.<br />
          <br />
          <span style="color: rgb(128, 0, 128);">Стоимость доставки почтой России</span><br />
          <u>1. Стоимость почтовой доставки заказов до 2990 р.</u><br />
          - Если&nbsp;стоимость вашего заказа до 2990 р., то стоимость доставки рассчитывается так:&nbsp;5% от суммы заказа (почтовые сборы) + 220 р. обработка и отправка и заказа.&nbsp;<br />
          - Если&nbsp;вы вносите<span style="color: rgb(178, 34, 34);">&nbsp;предоплату от 20%</span>&nbsp;от стоимости заказа, то платите только 220 р. за обработку и отправку заказа,&nbsp;<u>почтовые сборы в этом случае не взимаются</u>.</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <u>2. Стоимость почтовой доставки заказов от 2990 р.</u><br />
          -&nbsp;Если&nbsp;стоимость вашего заказа выше 2990 р., то мы доставим ваш заказ совершенно&nbsp;<span style="color: rgb(128, 0, 0);">БЕСПЛАТНО*</span>.<br />
          - Заказы свыше 5000 р. отправляются только по предоплате от 20% от стоимости заказа!</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          <span style="color: rgb(128, 0, 0);">*</span>- Бесплатная доставка предоставляется при стоимости заказа от 2990 руб. после вычета всех скидок и бонусов.<br />
          &nbsp;<br />
          <span style="font-size: 14px;"><strong>Внимание!</strong></span>&nbsp;Почта России взимает комиссию за пересылку денежных средств (наложенного платежа), которую оплачивает получатель посылки.<br />
          <span style="color: rgb(128, 0, 128);">Тарифы почты за пересылку денежных средств:</span><br />
          - до 1 000 руб. включительно 40 руб. + 5% от суммы<br />
          - свыше 1 000 до 5 000 руб. включительно 50 руб. + 4% от суммы<br />
          - свыше 5 000 руб. до 20 000 руб. включительно 150 руб. + 2% от суммы<br />
          - свыше 20 000 руб. до 500 000 руб. включительно 250 руб. + 1,5% от суммы<br />
          Тариф включает НДС в размере, предусмотренном действующим законодательством Российской Федерации.<br />
          <br />
          <span style="font-size: 14px;"><span style="color: rgb(178, 34, 34);">Мы гарантируем полную конфиденциальность.</span></span><br />
          Кроме вас посылку на почте никто получить не сможет, т.к. требуется предъявлять паспорт.<br />
          <strong>Все заказы отправляем в нейтральной почтовой упаковке без логотипов.</strong><br />
          Отправитель: ООО &quot;Ай Эм Логистикс Почта&quot;, получатель платежа: ООО &quot;Он и Она&quot;.</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          &nbsp;</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          &nbsp;</p>
          <p style="color: rgb(66, 65, 65); font-family: Tahoma, Geneva, sans-serif; font-size: 13px; line-height: 18px;">
          Желаем вам приятных покупок!</p>
          ';

          require_once 'PHPExcel/Classes/PHPExcel.php';
          $objReader = PHPExcel_IOFactory::createReaderForFile('/var/www/ononaru/data/mod-tmp/cities.csv');
          $objReader->setReadDataOnly(true);
          $this->PHPExcel = $objReader->load('/var/www/ononaru/data/mod-tmp/cities.csv');
          foreach ($this->PHPExcel->getActiveSheet()->toArray() as $keyRows => $row) {
          $cities[$row[1]]['rod'] = $row[2];
          $cities[$row[1]]['pred'] = $row[3];
          }

          $city->set("pickpointpage", str_replace(array("{cityName}", "{cityNameRod}", "{cityNamePred}"), array($city->getName(), ($cities[$city->getName()]['rod'] != "" ? $cities[$city->getName()]['rod'] : $city->getName()), ($cities[$city->getName()]['pred'] != "" ? $cities[$city->getName()]['pred'] : $city->getName())), $templatesHTML));

          $city->save();
          $i++;

          echo $i;
          } */
        /*
          $productsIsPublickOff = array("1-8 BXW-V II Purple", "1-8 BXW-V II Ruby", "1-8 BXW-V II Teal", "1-8 BXW-V Salsa Red", "1-8 BXW-V Tango Blue", "1-8 BXW-V Touch Purple", "1-8 BXW-V Touch Teal", "10185-01 PJ", "10186-01 PJ", "2705-10BXSE", "63-01 PP", "DESm-NI-belm PP", "DESm-NI-cool PP", "DESm-NI-go PP", "DESw-NI-btb PP", "DESw-NI-eap PP", "DESw-NI-enj PP", "DESw-NI-esm PP", "DESw-NI-incl PP", "DESw-NI-wspr PP", "DESw-NI-yg PP", "DJ0100-01CD", "DJ0100-02CD", "DJ0101-01CD", "DJ0101-05CD", "DJ0102-13CD", "DJ0102-14CD", "DJ0102-20CD", "DJ0102-21CD", "DJ0103-01CD", "DJ0103-02CD", "DJ0103-05CD", "DJ0103-06CD", "DJ0103-09CD", "DJ0103-10CD", "DJ0105-01CD", "DJ0107-01CD", "DJ0107-02XX", "DJ0110-01CD", "DJ0110-02CD", "DJ0110-05CD", "DJ0110-06CD", "DJ0110-09CD", "DJ0110-10CD", "DJ0276-02CD", "DJ0642-00BX", "GLAS-20", "GLAS-33", "GLAS-57", "GLAS-69", "GLAS-72", "LR6-AA", "MC1512B-L/XL", "MC1512W-L/XL", "PD1120-01", "PD1120-02", "PD1120-03", "PD1151-15", "PD2271-99", "PD3454-01", "PD3454-02", "PD3454-04", "PD3639-23", "PD3714-00", "PD4280-12", "PD4701-00", "PD4702-00", "PD5082-11", "PDRD134-21", "PP-SL-gay-5", "PP-SLife12-M-4", "PP-SLife12-M-6", "PP-SLife29-J-16", "PP-SLife29-J-23", "PP-SLife29-J-4", "PP-SLife29-J-6", "PP-SLife29-J-9", "PP100-00", "PP117-00", "PP124-00", "PP193-01", "PP193-02", "PP193-03", "PP193-04", "PP193-05", "PP193-06", "PP193-07", "PP193-08", "PP193-09", "PP193-10", "PP32-00", "PP483-01", "WV 3-GR", "WV 3-RED", "1100036 FF", "1101071 FF", "1120004 FF", "1121004 FF", "1121008 FF", "1121036 FF", "1130035 FF", "1130051 FF", "1131074 FF", "1601865 FF", "1611835 FF", "1611866 FF", "1621808 FF", "1621823 FF", "1631831 FF", "24404 FF", "24406 FF", "24438 FF", "25064 FF", "25151 FF", "25265 FF", "34151 FF", "34323 FF", "3436 FF", "3471 FF", "3538 FF", "3564 FF", "3904 FF", "3906 FF", "3908 FF", "39166 FF", "39308 FF", "4000166 FF", "4000308 FF", "4000331 FF", "4000374 FF", "4236 FF", "4271 FF", "71100 FF", "71200 FF", "71300 FF", "Gartelle-12 Classic", "Gartelle-12 Jeans", "Gartelle-12 Light", "Gartelle-12 Vanilla ice", "Gartelle-12 XXL Black", "Gartelle-3 Classic", "Gartelle-3 Jeans", "Gartelle-3 Light", "Gartelle-3 Vanilla ice", "Gartelle-3 XXL Black", "Gartelle-6 Classic", "Gartelle-6 Jeans", "Gartelle-6 Light", "Gartelle-6 Vanilla ice", "Gartelle-6 XXL Black", "IA100-bianco-L/XL", "IA100-bianco-M/L", "IA100-bianco-S/M", "IA100-nero-L/XL", "IA100-nero-M/L", "IA100-nero-S/M", "IA101-bianco-L/XL", "IA101-bianco-M/L", "IA101-bianco-S/M", "IA101-naturale-L/XL", "IA101-naturale-M/L", "IA101-naturale-S/M", "IA101-nero-L/XL", "IA101-nero-M/L", "IA101-nero-S/M", "IA102-bianco-L/XL", "IA102-bianco-M/L", "IA102-bianco-S/M", "IA102-nero-L/XL", "IA102-nero-M/L", "IA102-nero-S/M", "IA103-bianco-L/XL", "IA103-bianco-M/L", "IA103-bianco-S/M", "IA103-naturale-L/XL", "IA103-naturale-M/L", "IA103-naturale-S/M", "IA103-nero-L/XL", "IA103-nero-M/L", "IA103-nero-S/M", "IA104-bianco-L/XL", "IA104-bianco-M/L", "IA104-bianco-S/M", "IA104-nero-L/XL", "IA104-nero-M/L", "IA104-nero-S/M", "IA104-nero-XS", "IA105-nero-L/XL", "IA105-nero-M/L", "IA105-nero-S/M", "IA106-nero-L/XL", "IA106-nero-M/L", "IA106-nero-S/M", "IA107-naturale-L/XL", "IA107-naturale-M/L", "IA107-naturale-S/M", "IA107-naturale-XS", "IA108-naturale-L/XL", "IA108-naturale-M/L", "IA108-naturale-S/M", "IA109-nero-L/XL", "IA109-nero-M/L", "IA109-nero-S/M", "IA109-nero-XS", "IA110-bianco-L/XL", "IA110-bianco-M/L", "IA110-bianco-S/M", "IA110-nero-L/XL", "IA110-nero-M/L", "IA110-nero-S/M", "IA111-naturale-L/XL", "IA111-naturale-M/L", "IA111-naturale-S/M", "IA111-nero-L/XL", "IA111-nero-M/L", "IA111-nero-S/M", "IA112-bianco-L/XL", "IA112-bianco-M/L", "IA112-bianco-S/M", "IA112-nero-L/XL", "IA112-nero-M/L", "IA112-nero-S/M", "IA113-bianco-L/XL", "IA113-bianco-M/L", "IA113-bianco-S/M", "IA113-nero-L/XL", "IA113-nero-M/L", "IA113-nero-S/M", "IA114-nero-L/XL", "IA114-nero-M/L", "IA114-nero-S/M", "IA201-beige-2", "IA201-beige-3", "IA201-beige-4", "IA201-nero-2", "IA201-nero-3", "IA201-nero-4", "IA202-beige-2", "IA202-beige-3", "IA202-beige-4", "IA202-nero-2", "IA202-nero-3", "IA202-nero-4", "IA203-beige-2", "IA203-beige-3", "IA203-beige-4", "IA203-beige-5", "IA203-nero-2", "IA203-nero-3", "IA203-nero-4", "IA203-nero-5", "IA203-nero-6", "IA204-beige-1/2", "IA204-beige-3/4", "IA204-beige-5", "IA204-nero-1/2", "IA204-nero-3/4", "IA204-nero-5", "IA205-grigio-1/2", "IA205-grigio-3/4", "IA205-grigio-5", "IA205-oro-1/2", "IA205-oro-3/4", "IA205-oro-5", "IA206-grigio-1/2", "IA206-grigio-3/4", "IA206-nero-1/2", "IA206-nero-3/4", "IA207-nero-L", "IA207-nero-M", "IA207-nero-S", "MC785B-S/M", "MC785R-S/M", "MC786B-S/M", "MC786R-S/M", "MC788P-S/M", "MC788W-S/M", "MC789-S/M", "211197DR", "211203DR", "211210DR", "211227DR", "211234DR", "211241DR", "211258DR", "Sagami N10 Feel Fit", "Sagami N10 Feel Long", "Sagami N10 Xtreme СOLA", "Sagami N12 Exeed 1000", "Sagami N12 Exeed 1500", "Sagami N12 Exeed 2000", "Sagami N3 Xtreme", "Sagami N3 Xtreme Form-fit", "TZ-6595", "TZ-9484", "TZ-9486", "TZ-9487", "TZ-9488", "TZ-ALS", "TZ-BLS", "TZ-CLS", "20423 WET", "20426 WET", "20428 WET", "21503 WET", "21506 WET", "TJ9102-00BX", "GS00001", "GS00004", "GS00005", "GS00007", "GS00008", "GS00009", "GS00010", "BL947-32A", "BL1000", "BL1002", "BL1007", "BL1012-ML", "BL1012-SM", "BL1020", "BL1021", "BL1023-ML", "BL1023-SM", "BL1026-SM", "BL1027", "BL1030-ML", "BL1033", "BL104", "BL1043", "BL1044", "BL1045", "BL1047", "BL1048", "BL1051", "BL1060", "BL1067", "BL1072-SM", "BL1078", "BL1079", "BL1080", "BL1081", "BL1083", "BL1085", "BL1092-ML", "BL1093", "BL1094", "BL1097", "BL1099", "BL1105-ML", "BL1105-SM", "BL1106-ML", "BL1106-SM", "BL1111", "BL1112-SM", "BL1113", "BL1114", "BL1115", "BL1119", "BL1120", "BL1121", "BL1125", "BL1126", "BL1127", "BL1132-SM", "BL1134-SM", "BL1139", "BL1140-ML", "BL1140-SM", "BL1143", "BL1145-ML", "BL1145-SM", "BL1150-ML", "BL1151", "BL1155", "BL1159", "BL1161", "BL1163", "BL1166", "BL1173", "BL1176-SM", "BL1178-SM", "BL1183", "BL1186-ML", "BL1186-SM", "BL1190", "BL1191", "BL1192", "BL1194", "BL1196", "BL1202", "BL1204", "BL1206", "BL1210", "BL190", "BL692-ML", "BL692-SM", "BL695-SM", "BL698", "BL699", "BL700", "BL701", "BL703", "BL708", "BL710-ML", "BL710-SM", "BL711", "BL712", "BL716", "BL717", "BL719", "BL722", "BL732-ML", "BL732-SM", "BL733", "BL737", "BL739", "BL741-ML", "BL741-SM", "BL743-SM", "BL746", "BL748", "BL751", "BL752", "BL753", "BL754", "BL758", "BL759-ML", "BL759-SM", "BL761-ML", "BL761-SM", "BL762-SM", "BL763", "BL764", "BL766-SM", "BL767-ML", "BL769", "BL773", "BL774", "BL776-ML", "BL776-SM", "BL778-ML", "BL778-SM", "BL779", "BL782", "BL783", "BL784", "BL786-ML", "BL786-SM", "BL787", "BL791", "BL792", "BL793-ML", "BL793-SM", "BL796", "BL797", "BL798", "BL799-SM", "BL805-ML", "BL805-SM", "BL806", "BL807", "BL808", "BL814", "BL815-SM", "BL819", "BL821", "BL822-ML", "BL822-SM", "BL823", "BL826", "BL833", "BL835", "BL838", "BL839-ML", "BL839-SM", "BL840", "BL843", "BL844", "BL851", "BL853", "BL869-SM", "BL876", "BL882", "BL883", "BL885", "BL892", "BL895-SM", "BL902", "BL906", "BL916", "BL926", "BL933-ML", "BL933-SM", "BL935-ML", "BL935-SM", "BL936-ML", "BL939-SM", "BL942", "BL944", "BL946", "BL949-SM", "BL951", "BL954", "BL955", "BL956", "BL957", "BL964", "BL967", "BL969-ML", "BL969-SM", "BL971", "BL972", "BL978", "BL979-SM", "BL980-SM", "BL988-ML", "BL988-SM", "BL989-ML", "BL989-SM", "BL991", "BL992", "BL993", "BL996-ML", "MASQ3", "MASQ9", "BL001", "BL003-L", "BL003-M", "BL003-S", "BL005-L", "BL005-M", "BL005-S", "BL006-M", "BL007-L", "BL007-M", "BL007-S", "BL008", "BL010", "BL011", "BL012-M", "BL012-S", "BL013-S", "BL016-L", "BL016-M", "BL016-S", "BL017-L", "BL017-M", "BL021-L", "BL021-M", "BL021-S", "BL022", "BL026", "BL028", "BL030", "BL031", "BL034", "BL035-M", "BL035-S", "BL040-L", "BL040-M", "BL040-S", "BL041-L", "BL041-M", "BL041-S", "BL042", "BL044", "BL049", "BL051", "BL053-L", "BL053-M", "BL053-S", "BL054", "BL056", "BL059", "BL060-L", "BL060-M", "BL060-S", "BL062", "BL063", "BL064", "BL065", "BL066", "BL067", "BL068", "BL070-M", "BL070-S", "BL073", "BL078", "BL080-S", "BL084", "BL096", "BL097", "BL099", "BL100", "BL101", "BL103", "BL107", "BL108", "BL109", "BL110", "BL111", "BL113-M", "BL113-S", "BL116", "BL117-M", "BL117-S", "BL120", "BL121", "BL122", "BL123", "BL126-M", "BL128-L", "BL128-M", "BL128-S", "BL130", "BL131-S", "BL135", "BL138", "BL139", "BL140", "BL141", "BL142", "BL149-M", "BL149-S", "BL151-ML", "BL151-SM", "BL152-SM", "BL153-L", "BL153-M", "BL153-S", "BL156-L", "BL156-M", "BL158-L", "BL158-M", "BL158-S", "BL159-L", "BL162", "BL163", "BL164", "BL165", "BL167-L", "BL167-M", "BL167-S", "BL168", "BL169", "BL170", "BL172", "BL173", "BL174", "BL175-L", "BL175-M", "BL175-S", "BL176", "BL177", "BL178", "BL182", "BL184", "BL187", "BL188", "BL191", "BL192", "BL193", "BL194", "BL197", "BL198", "BL200", "BL202-S", "BL204", "BL206", "BL208", "BL210-L", "BL210-M", "BL210-S", "BL215", "BL219", "BL221", "BL222", "BL224", "BL225", "BL226-L", "BL229-M", "BL231", "BL232", "BL235-L", "BL236", "BL238", "BL239", "BL244", "BL245", "BL246", "BL247-L", "BL247-M", "BL247-S", "BL249", "BL250-S", "BL252", "BL254-L", "BL254-M", "BL254-S", "BL257", "BL260", "BL261", "BL263", "BL265", "BL266", "BL267", "BL268", "BL269", "BL270", "BL271", "BL272", "BL273", "BL274", "BL275", "BL276-L", "BL276-M", "BL276-S", "BL277", "BL279-M", "BL280", "BL281", "BL283", "BL293", "BL294", "BL297", "BL299", "BL300", "BL303", "BL304", "BL305-L", "BL305-M", "BL305-S", "BL306", "BL307", "BL308", "BL310", "BL311", "BL312-S", "BL314", "BL315", "BL316-M", "BL316-S", "BL317", "BL318", "BL319", "BL320", "BL324", "BL325", "BL329", "BL331", "BL334", "BL340", "BL348", "BL353", "BL355", "BL357", "BL358", "BL364", "BL365", "BL377", "BL387", "BL399-L", "BL399-M", "BL399-S", "BL401", "BL402", "BL409", "BL412", "BL413", "BL414", "BL418", "BL419", "BL420", "BL421", "BL422", "BL425", "BL426", "BL427", "BL429", "BL430", "BL431", "BL432", "BL436", "BL437", "BL440", "BL441-L", "BL441-M", "BL441-S", "BL443", "BL445", "BL448", "BL449-L", "BL449-M", "BL449-S", "BL450", "BL454", "BL460", "BL461", "BL463", "BL466-L", "BL466-M", "BL466-S", "BL469", "BL470", "BL472", "BL473", "BL475", "BL478", "C001-BLK-S", "BL626", "BMS-23418", "BMS-23618", "BMS-30528", "BMS-376911", "BMS-376915", "BMS-38815", "BMS-5010-00-1", "BMS-5015-01", "BMS-5016-3", "BMS-5115-1", "BMS-5116-8", "BMS-5117-3", "BMS-5128-3", "BMS-52014", "BMS-5210-1", "BMS-5210-8", "BMS-5520-8", "BMS-5528-8", "BMS-566612", "BMS-997-16", "LF28113-EU", "LF28213-EU", "LF28313-EU", "LF28513-EU", "LF28613-EU", "LX3879-11", "LX3880-11", "LX3881-11", "LX3882-11", "LX4761-11", "LX4762-11", "SW3-20016", "SW3-20116", "SW3-20216", "SW3-20316", "SW3-20516", "SW3-20716", "SW3-20816", "SW3-20916", "SW3-21016", "SW3-21116", "SW3-21216", "SW3-21316", "SW3-21416", "SW3-50016", "SW3-58916", "SE-JO-4760-14-3", "SE-JO-4761-14-3", "SE-JO-4763-14-3", "SE-JO-4764-20-3", "ED1002-4", "ED1302-1", "ED2001-01", "ED2001-02", "FS-40188", "FS-40189", "FS-40190", "FS-40191", "FS-40166", "FS-40167", "FS-40168", "FS-40169", "FS-40172", "FS-40173", "FS-40174", "FS-40175", "FS-40176", "FS-40177", "FS-40178", "FS-40179", "FS-40181", "FS-40183", "FS-40184", "FS-40185", "FS-40186", "FS-48291", "FS-48292", "FS-48293", "FS-48294", "FS-52411", "FS-52413", "FS-52417", "FS-52420", "FS-52421", "FS-52422", "FS-52423", "FS-52424", "FS-52425", "FS-52427", "XP10005", "XP10961", "XP10985", "XP10992", "XP20978", "XP30012", "KL-003-0311", "KL-003-0312", "KL-007-01", "KL-007-02", "KL-007-03", "KL-008-01", "KL-008-02", "KL-008-03", "KL-008-04", "KL-008-0512", "KL-008-0523", "KL-008-0612", "KL-008-0623", "KL-008-0712", "KL-008-0723", "KL-010-01", "KL-010-04", "KL-010-05", "KL-011-01", "KL-011-02", "KL-1202-12", "KL-1213-11", "KL-1216-11", "KL-1216-12", "KL-1219-12", "KL-1506-11", "KL-2002-11", "KL-6001-11", "KL-6008-11", "KL-6013-11", "KL-6019-11", "KL-6101-11", "KL-6107-13", "KL-6108-23", "KL-6108-24", "KL-6109-11", "KL-8103-12", "KL-8109-12", "LP00103", "LP603007", "LP603008", "LP603017", "LP603022", "LP603023", "LP6030677", "LP6030684", "LP6030844", "LP6031117", "LP6031155", "LP6031162", "LP6031179", "LH239", "LH246", "LH260", "LH576", "LH702", "LH722", "LH-42220", "LH-46092", "LP7010005", "LP7010012", "LP7010029", "LP7010036", "LP7010043", "LP7010050", "LP7010067", "LP7010074", "LP7010081", "LP7010098", "LP7010104", "LP7010333", "LP7010340", "LP7010357", "LP7010364", "LP7010371", "LP7010388", "LP7010548", "LP7010555", "LP7010579", "LP7010586", "LP7010593", "LP7010609", "MS00011", "MS00012", "MS00013", "MS00014", "MS00015", "MS00016", "MS00034", "MS00035", "MS00036", "MS00038", "MS00041", "MS00046", "MS00048", "MS00049", "MS00052", "MS00056", "MS00059", "MS00062", "MS00064", "MS00070", "MS00073", "MS00076", "MS1038-5", "NSN-0201-14", "NSN-0201-16", "NSN-0201-24", "NSN-0202-14", "NSN-0203-11", "NSN-0203-14", "NSN-0203-15", "NSN-0203-16", "NSN-0210-13", "NSN-0210-14", "NSN-0210-23", "NSN-0210-24", "NSN-0210-33", "NSN-0210-34", "NSN-0210-43", "NSN-0210-44", "NSN-0212-13", "NSN-0212-14", "NSN-0212-15", "NSN-0214-13", "NSN-0215-14", "NSN-0215-15", "NSN-0215-24", "NSN-0215-26", "NSN-0230-14", "NSN-0230-17", "NSN-0250-14", "NSN-0250-15", "NSN-0250-17", "NSN-0250-33", "NSN-0250-34", "NSN-0250-44", "NSN-0250-47", "NSN-0250-55", "NSN-0250-64", "NSN-0250-65", "NSN-0260-14", "NSN-0302-14", "NSN-0302-15", "NSN-0302-23", "NSN-0302-24", "NSN-0325-11", "NSN-0325-14", "NSN-0355-14", "NSN-0380-14", "NSN-0380-15", "NSN-0380-17", "NSN-0380-18", "NSN-0401-13", "NSN-0401-14", "NSN-0401-15", "NSN-0402-13", "NSN-0402-14", "NSN-0402-17", "NSN-0405-15", "NSN-0405-23", "NSN-0405-25", "NSN-0410-15", "NSN-0410-16", "NSN-0411-15", "NSN-0411-16", "NSN-0415-13", "NSN-0415-31", "NSN-0416-13", "NSN-0425-14", "NSN-0425-26", "NSN-0425-38", "NSN-0425-91", "NSN-0506-11", "NSN-0506-13", "NSN-0508-14", "NSN-0508-15", "NSN-0508-24", "NSN-0508-34", "NSN-0508-35", "NSN-0510-16", "NSN-0512-13", "NSN-0512-14", "NSN-0512-23", "NSN-0512-24", "NSN-0512-33", "NSN-0512-34", "NSN-0525-11", "NSN-0525-13", "NSN-0525-15", "NSN-0550-34", "NSN-0601-18", "NSN-0601-26", "NSN-0601-27", "NSN-0601-31", "NSN-0601-37", "NSN-0602-11", "NSN-0602-12", "NSN-0603-11", "NSN-0603-13", "NSN-0603-14", "NSN-0603-21", "NSN-0603-23", "NSN-0603-24", "NSN-0604-11", "NSN-0604-12", "NSN-0604-17", "NSN-0610-13", "NSN-0610-21", "NSN-0610-26", "NSN-0610-31", "NSN-0610-39", "NSN-1124-13", "NSN-1125-16", "NSN-1126-11", "NSN-1305-17", "OB0912-04", "OB0912-05 Pink", "OB0912-05 Purple ", "OB092-01", "OB1011-01", "PD1026-11", "PD1026-12", "PD1028-12", "PD1028-17", "PD1109-14", "PD1119-12", "PD1139-11", "PD1143-99", "PD1144-11", "PD1146-11", "PD1146-12", "PD1147-11", "PD1147-12", "PD1159-19", "PD1159-23", "PD1160-11", "PD1160-19", "PD1160-23", "PD1203-01", "PD1203-02", "PD1203-03", "PD1203-04", "PD1203-05", "PD1360-14", "PD1363-00", "PD1385-00", "PD1388-11", "PD1389-11", "PD1399-11", "PD1451-11", "PD1452-11", "PD1453-11", "PD1454-11", "PD1455-11", "PD1456-11", "PD1457-11", "PD1458-11", "PD1459-11", "PD1460-11", "PD1461-11", "PD1462-11", "PD1754-01", "PD1754-02", "PD1754-03", "PD1755-11", "PD1756-11", "PD1757-11", "PD2225-99", "PD2263-99", "PD2312-99", "PD2370-01", "PD2370-02", "PD2372-00", "PD2373-00", "PD2374-00", "PD2375-00", "PD2376-00", "PD2377-00", "PD2378-00", "PD2379-00", "PD3030-99", "PD3124-29", "PD3302-14", "PD3303-05", "PD3724-05", "PD3818-00", "PD4200-11", "PD4200-14", "PD4200-20", "PD4200-23", "PD4200-29", "PD4201-11", "PD4201-14", "PD4201-20", "PD4201-23", "PD4201-29", "PD4203-11", "PD4203-12", "PD4203-14", "PD4203-20", "PD4203-23", "PD4203-29", "PD4208-11", "PD4208-20", "PD4208-23", "PD4208-29", "PD4209-11", "PD4209-14", "PD4209-20", "PD4209-23", "PD4210-11", "PD4210-12", "PD4210-14", "PD4210-21", "PD4210-23", "PD4210-29", "PD4220-11", "PD4220-14", "PD4220-20", "PD4220-23", "PD4220-29", "PD4220-32", "PD4221-11", "PD4221-14", "PD4221-20", "PD4221-23", "PD4222-11", "PD4222-14", "PD4222-20", "PD4222-23", "PD4222-29", "PD4223-11", "PD4223-14", "PD4223-20", "PD4223-23", "PD4223-29", "PD4224-11", "PD4224-20", "PD4224-23", "PD4226-11", "PD4226-23", "PD4226-29", "PD4227-11", "PD4227-14", "PD4227-23", "PD4227-29", "PD4228-11", "PD4228-14", "PD4228-15", "PD4228-23", "PD4228-29", "PD4229-11", "PD4229-14", "PD4229-23", "PD4229-29", "PD4230-11", "PD4230-14", "PD4230-23", "PD4230-29", "PD4240-11", "PD4240-14", "PD4240-23", "PD4240-29", "PD4241-11", "PD4241-20", "PD4241-23", "PD4241-29", "PD4261-11", "PD4261-14", "PD4261-15", "PD4261-20", "PD4261-23", "PD4262-11", "PD4262-12", "PD4262-14", "PD4262-15", "PD4262-20", "PD4262-23", "PD4264-11", "PD4264-20", "PD4264-21", "PD4264-23", "PD4265-11", "PD4265-14", "PD4265-15", "PD4265-20", "PD4265-21", "PD4265-23", "PD4266-11", "PD4266-12", "PD4266-14", "PD4266-15", "PD4266-20", "PD4266-23", "PD4281-11", "PD4281-15", "PD4281-23", "PD4300-11", "PD4300-14", "PD4300-20", "PD4300-29", "PD4301-29", "PD4302-21", "PD4302-29", "PD4305-11", "PD4305-14", "PD4305-20", "PD4305-23", "PD4305-29", "PD4320-02", "PD4501-11", "PD4502-12", "PD4503-11", "PD4506-12", "PD4509-11", "PD4511-11", "PD4512-12", "PD4514-23", "PD4516-23", "PD4517-15", "PD4518-23", "PD4519-15", "PD4520-23", "PD4521-15", "PD4522-23", "PD4523-15", "PD4524-23", "PD4525-15", "PD4526-11", "PD4527-12", "PD4528-11", "PD4530-11", "PD4531-12", "PD4532-23", "PD4533-15", "PD4534-23", "PD4535-23", "PD4536-11", "PD4536-23", "PD4537-23", "PD4538-12", "PD4539-23", "PD4540-12", "PD4542-12", "PD4543-19", "PD4544-19", "PD4555-23", "PD4556-23", "PD4560-23", "PD4562-23", "PD4573-12", "PD4573-23", "PD4901-00", "PD4902-00", "PD4954-23", "PD4961-11", "PD4965-26", "PD4966-27", "PD4975-26", "PD4976-27", "PD5004-02", "PD5011-00", "PD5013-02", "PD5078-02", "PD5086-00", "PD5116-00", "PD5143-00", "PD6016-00", "PD6025-00", "PD6040-00", "PD6043-11", "PD6043-15", "PD6052-00", "PD6077-02", "PD6121-00", "PD6203-02", "PD6203-03", "PD6204-99", "PD6423-99", "PD6426-99", "PD6430-99", "PD6439-00", "PD6454-00", "PD6471-00", "PD6472-00", "PD6473-00", "PD6476-00", "PD6502-01", "PD6553-00", "PD6555-00", "PD6576-00", "PD6578-00", "PD7610-99", "PD7611-99", "PD7708-99", "PD8202-01", "PD8211-00", "PD8405-00", "PD8500-32", "PD8511-00", "PD8600-00", "PD8601-00", "PD8604-00", "PD8605-00", "PD8606-00", "PD8607-19", "PD8608-00", "PD8609-00", "PD8611-00", "PD8612-00", "PD8614-00", "PD8619-00", "PD8622-00", "PD8623-00", "PD8630-00", "PD8631-00", "PDHH118", "PDHH121", "PDMI101", "PDMI102", "PDMI103", "PDMI105", "PDRD117", "PDRD133", "PDRD153", "PDRD227", "PDRD239", "PDRD240", "PDRD288", "PDRD330", "PDRD333", "PDRD360", "SC BO-1061", "SC BUD-1306", "SC BUL-1214", "SC FNG-1122", "SC GFN-1532", "SC LNG-1139", "SC OCT-1153", "SC OG-1580", "SC OGB-1481", "SC OH-1092", "SC OMN-1399", "SC OW-1344", "SC PROP-1634", "SC RNG-1382", "SC SO-1030", "SP007-A", "SP014-V", "SP172-01", "SP189-02", "SP242-01", "SP811-V", "SP842-02", "SP987-02", "VX1005", "VX1007", "VX1101", "VX1104", "VX1106", "VX1107", "VX1108", "VX1109", "VX1303", "900039DR", "900053DR", "900060DR", "900077DR", "900084DR", "900091DR", "1-8 BXW-V Salsa Black", "1-8 BXW-V Salsa White", "1-8 BXW-V Tango Purple ", "1-8 BXW-V Tango Rose ", "1-8 BXW-V Touch Ruby ", "WV 3-PUR", "WV 4-PINK", "WV 4-PUR", "XT10010-12", "XT10010-19", "XT10020-11", "XT10020-12", "XT10020-19", "XT10031-12", "XT10031-19", "XT10261-11", "XT10261-12", "XT10261-19", "XT10271-11", "XT10271-12", "XT10271-19", "XT10281-11", "XT10281-19", "XT10291-11", "XT10291-12", "XT10291-19", "XT10301-11", "XT10301-12", "XT10301-19", "XT10331-12", "XT10331-19", "XT10341-11", "XT10361-11", "XT10361-12", "XT10361-14", "XT10371-12", "XT10371-19", "XT10381-11", "XT10381-14", "XT10381-40", "XT20010-11", "XT20010-13", "XT20010-21", "XT20301-11", "XT20311-13", "ZA501", "ZA502", "ZA503", "ZA511", "ZA512", "ZA513", "ZM711", "ZV010", "ZV012", "ZV013", "ZV022", "ZV042", "ZV051", "ZV053", "ZV072", "ZV074", "LB-10005", "LB-15005", "LB-15008", "PZ-2055", "PZ-2062", "PZ-2079", "PZ-2086", "PZ-2093", "PZ-2109", "PZ-2116", "PZ-2123", "PZ-2130", "PZ-2147", "PZ-2154", "PZ-2161", "PZ-2178", "PZ-2185", "PZ-2192", "Contex № 12 Black Rose", "Contex № 12 Classic", "Contex № 12 Colour ", "Contex № 12 Extra Large", "Contex № 12 Glowing", "Contex № 12 Imperial", "Contex № 12 Lights", "Contex № 12 Long", "Contex № 12 Opium", "Contex № 12 Relief", "Contex № 12 Romantic", "Contex № 12 Tornado ", "Contex № 3 Black", "Contex № 3 Classic", "Contex № 3 Colour", "Contex № 3 Dotted ", "Contex № 3 Extra Large", "Contex № 3 Forced ", "Contex № 3 Glowing", "Contex № 3 Imperial", "Contex № 3 Lights", "Contex № 3 Long Love", "Contex № 3 Opium ", "Contex № 3 Ribbed", "Contex № 3 Romantic", "Contex № 3 Tornado", "Contex №8 Gold Goal", "Durex 12 Classic", "Durex 12 Comfort", "Durex 12 Elite", "Durex 12 Extra", "Durex 12 Performa", "Durex 12 Pleasuremax", "Durex 12 Select", "Durex 3 Comfort XL", "Durex 3 Extra safe", "Durex 3 Performa", "Durex 3 Pleasuremax", "Durex 3 Select", "PP-T-235", "PP-T-242", "PP-T-259", "PP-T-266", "PP-T-273", "PP-T-280", "PP-T-297", "PP-T-303", "PP-T-310", "PP-T-327", "PP-T-334", "PU-100", "PU-120", "SF760027", "SF760041", "SF760065", "SF760072", "8005 BX SIT", "46001 SIT", "46003 SIT", "46004 SIT", "46005 SIT", "46006 SIT", "46007 SIT", "46009 SIT", "46010 SIT", "46011 SIT", "SilaMens", "13-00 HP", "13-01 HP ", "37-00 HP", "68-02 HP", "75-02 HP", "82-01 HP");


          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          foreach ($productsIsPublickOff as $code) {

          //echo "UPDATE product SET is_public=0 where code ='" . $code . "';";exit;
          $q->execute("UPDATE product SET is_public=0 where code ='" . $code . "';");
          }
         */
        /*
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $allUser = $q->execute("SELECT code
          FROM  `product`
          WHERE  `count` >0")->fetchAll(Doctrine_Core::FETCH_COLUMN);
          //print_r($allUser);
          $dbh = new PDO('mysql:host=localhost;dbname=4partnerru', '', '');
          $stmt = $dbh->query('SELECT article
          FROM  `products`
          WHERE  `in_stock` >0')->fetchAll(PDO::FETCH_COLUMN);
          $testarr = array_diff($stmt, $allUser);
          foreach ($testarr as $test) {
          echo $test . "\r\n";
          }
         * *
         */
        /* $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $products = ProductTable::getInstance()->createQuery()->where("code in ('SF-Pro2','3097-1CH BX SIT','3120-1CH BX SIT','3062-1CH BX SIT','FF0046-23','SE-JO-4725-05-3','SE-4604-15','SE-4612-15','SE-4612-50','SE-0972-12-3','SE-4561-10-3','SE-0611-20-3','SE-4460-04-3','SE-4415-04-3','SE-4410-04-3','SE-0394-30-2','SE-4610-45','SE-4612-05','SE-4615-05','SE-4615-10','SE-4616-05','E021-BKWTSM','E035-REDSM','E022-BKWTML','E027-BLKLXL','E001-BLKML','KL-1040','KL-1002','KL-1020','SE-JO-4716-00-3','SE-JO-4717-00-3','SE-JO-4731-00-3','SE-4435-04-3','SE-JO-8021-00','SE-JO-8021-10','SE-JO-8080-05','SE-JO-8075-05','SE-JO-8048-05','SE-JO-8051-00','SE-JO-8051-10','SE-JO-8050-05','SE-JO-8055-00','SE-JO-8055-10','CZ-001','SE-2744-10-3','SE-2744-20-3','PD4746-08','PD4743-00','PD4743-08','PD4745-08','PD4749-00','PD4749-08','SE-0506-05-2')")->execute();
          foreach ($products as $product) {
          if ($product->getDiscount() > 0) {
          $product->setDiscount(null);
          $product->setPrice($product->getOldPrice()>$product->getPrice()?$product->getOldPrice():$product->getPrice());

          $product->setOldPrice(null);
          $product->save();
          //exit;
          }
          } *//*
          $products = ProductTable::getInstance()->createQuery()->where("video !=  \"\" and video not like '%.webm%'")->execute();
          foreach ($products as $product) {
          if (substr_count($product->getVideo(), '/') == 0) {
          $videoSRC = "/var/www/ononaru/data/www/onona.ru/uploads/video/" . $product->getVideo();
          $fileexist = file_exists(str_replace(".ogv", ".mp4", $videoSRC));
          } elseif ((substr_count($product->getVideo(), 'onona.ru') > 0)) {
          $videoSRC = str_replace(array("http://www.onona.ru/video/", "https://onona.ru/uploads/video/", "https://onona.ru/video/", "http://onona.ru/video/", "http://new.onona.ru/video/"), "/var/www/ononaru/data/www/onona.ru/uploads/video/", $product->getVideo());
          $fileexist = file_exists(str_replace(".ogv", ".mp4", $videoSRC));
          } elseif (( substr_count($product->getVideo(), '/uploads/video/') > 0)) {
          $videoSRC = str_replace(array("/uploads/video/"), "/var/www/ononaru/data/www/onona.ru/uploads/video/", $product->getVideo());
          $fileexist = file_exists(str_replace(".ogv", ".mp4", $videoSRC));
          } elseif (( substr_count($product->getVideo(), 'http') > 0)) {
          $videoSRC = $product->getVideo();
          $fileexist = true;
          } else {
          $videoSRC = "/var/www/ononaru/data/www/onona.ru/uploads/video/" . $product->getVideo();
          $fileexist = file_exists(str_replace(".ogv", ".mp4", $videoSRC));
          }
          if ($fileexist === false) {
          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $product->getCode() . ".mp4")) {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 999999999));
          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . ".mp4")) {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 9999999999));
          }
          copy("/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $product->getCode() . ".mp4", "/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $newFilename . ".mp4");
          exec("sh /var/www/ononaru/data/www/encodeVideo.sh /var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $newFilename . ".mp4 /var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . "", $test);
          $product->set('video', $newFilename . ".webm");
          $product->save();
          }
          $codeExplode = explode("-", $product->getCode());

          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $codeExplode[0] . " " . $codeExplode[1] . ".mp4")) {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 999999999));
          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . ".mp4")) {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 9999999999));
          }
          copy("/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $codeExplode[0] . " " . $codeExplode[1] . ".mp4", "/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $newFilename . ".mp4");
          exec("sh /var/www/ononaru/data/www/encodeVideo.sh /var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $newFilename . ".mp4 /var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . "", $test);
          $product->set('video', $newFilename . ".webm");
          $product->save();
          }
          $dir = opendir("/var/www/ononaru/data/www/onona.ru/uploads/old_video/");
          while (($file = readdir($dir)) !== false) {
          if (strstr($file, $codeExplode[0] . "-" . $codeExplode[1]) != "") {
          $a[] = strstr($file, $codeExplode[0] . "-" . $codeExplode[1]);
          }
          }
          //print_r($a);
          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/old_video/" .  $a[0] . "") and $a[0]!="") {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 999999999));
          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . ".mp4")) {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 9999999999));
          }
          copy("/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $a[0] . "", "/var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $newFilename . ".mp4");
          exec("sh /var/www/ononaru/data/www/encodeVideo.sh /var/www/ononaru/data/www/onona.ru/uploads/old_video/" . $newFilename . ".mp4 /var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . "", $test);
          $product->set('video', $newFilename . ".webm");
          $product->save();
          }
          unset($a);
          closedir($dir);
          } else {
          if (substr_count($product->getVideo(), '.ogv') > 0) {
          $VideoFileName = reset(explode(".", $product->getVideo()));
          $newFilename = $VideoFileName;
          } else {

          $VideoFileName = end(explode("/", $product->getVideo()));

          $newFilename = sha1($product->getVideo() . rand(1111111111, 999999999));
          if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . ".mp4")) {
          $newFilename = sha1($product->getVideo() . rand(1111111111, 9999999999));
          }
          }
          echo $product->getVideo();
          echo $videoSRC;
          exit;
          exec("sh /var/www/ononaru/data/www/encodeVideo.sh " . str_replace(".ogv", ".mp4", $videoSRC) . " /var/www/ononaru/data/www/onona.ru/uploads/video/" . $newFilename . "", $test);
          $product->set('video', $newFilename . ".webm");
          $product->save();

          $productIssetCount = ProductTable::getInstance()->createQuery()->where("video like '%" . $VideoFileName . "%'")->execute();
          if ($productIssetCount->count() == 0) {
          rename($videoSRC, str_replace("/uploads/video/", "/uploads/old_video/", $videoSRC));
          }
          echo $newFilename . "\r\n";
          //exit;
          }
          } */
        /*
          $products = ProductTable::getInstance()->createQuery()->where("video !=  \"\"")->execute();
          foreach ($products as $product) {
          if (!file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" .  $product->getVideo() . "") or !file_exists("/var/www/ononaru/data/www/onona.ru/uploads/video/" . explode(".", $product->getVideo())[0] . ".mp4")) {
          echo $product->getCode()."\n";
          }
          }
         *//*
          $status = "Отмена";
          if ($status != "Новый" and $status != "Отмена" and $status != "В обработке" and $status != "Заказано поставщику" and $status != "В резерве" and $status != "Оплачен") {
          $data = array("order" => array('status' => 'DELIVERY'));
          } elseif ($status == "Отмена") {
          $data = array("order" => array('status' => 'CANCELLED', "substatus"=>"USER_CHANGED_MIND"));
          } elseif ($status == "Оплачен") {
          $data = array("order" => array('status' => 'DELIVERED'));
          } elseif ($status == "В резерве") {
          $data = array("order" => array('status' => 'RESERVED'));
          } else {
          $data = array("order" => array('status' => 'PROCESSING'));
          }
          $data_json = json_encode($data);

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "https://api.partner.market.yandex.ru/v2/campaigns/21016997/orders/1496693/status.json");
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: OAuth oauth_token="AQAAAAACJc4mAAPxF4ktOKzR30e6q5LahhjMOi8", oauth_client_id="1a7defe1cd0246a2a6cc8acfbd5b9bcd"'));
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $responsePUT = curl_exec($ch);
          curl_close($ch);
          echo $responsePUT; */
        //print_r(array_diff($stmt, $allUser));
        /*
          $persRec=  unserialize("a:22:{i:123;i:90;i:156;i:5;i:201;i:9;i:178;i:4;i:227;i:1;i:177;i:8;i:61;i:19;i:8;i:9;i:10;i:1;i:34;i:1;i:67;i:1;i:167;i:1;i:51;i:17;i:80;i:1;i:108;i:8;i:199;i:6;i:98;i:6;i:55;i:3;i:238;i:1;i:202;i:5;i:3;i:1;i:163;i:22;}");
          arsort($persRec);
          print_r($persRec); */
        /*
          $arrayActions = array("0150472661", "0285207673", "0304416228", "0418987604", "0454971123", "0482841146", "0616673851", "0919944461", "0986057859", "1189369725", "1249611735", "1347061991", "1387745496", "1416231774", "1417165558", "1511887495", "1740161517", "1772241398", "1859581576", "1910657524", "1978178070", "2354727717", "2381461329", "2389153896", "2562217568", "2729365789", "2824357348", "2909758295", "2919556923", "3002365000", "3007838377", "3010496472", "3029239958", "3121654521", "3133149049", "3142651327", "3179563055", "3269172318", "3326817847", "3441775775", "3443625820", "3482768559", "3489278925", "3617472652", "3682143892", "3736735599", "3747621285", "3770921557", "3908823568", "3948335545", "4580508937", "4986233609", "5049764590", "5056650629", "5238195419", "5559788261", "5632585764", "5656947930", "5674740325", "5691208290", "5759239369", "5819092859", "6230678578", "6338247308", "6454011693", "6607786268", "6700444296", "6879243986", "7005925956", "7017604050", "7108416269", "7229682284", "7266819230", "7338347170", "7442233604", "7536572734", "7935916809", "7983876566", "7997580051", "8053360956", "8106647936", "8107170296", "8160567122", "8175889014", "8299611505", "8417253266", "8635194235", "8741778892", "8821561259", "8888056956", "9049625548", "9062060663", "9294850644", "9314841172", "9394245329", "9422615980", "9515641723", "9591536412", "9835187666", "9954622135");
          foreach ($arrayActions as $actionCode) {

          $action = new ActionsDiscount();
          $action->set("startaction", "2016-12-27 00:00:00");
          $action->set("endaction", "2017-03-27 23:59:59");
          $action->set("text", $actionCode);
          $action->set("discount", "20");
          $action->save();
          } */
        /* $arrayProductSlug = array("lubrikant-na-vodnoi-osnove-jo-h2o-original-30-ml", "sogrevauschii-lubrikant-jo-silicone-free-hybrid-warming-s-maslom-kokosa-30-ml", "neitralnyi-lubrikant-jo-personal-premium-120-ml", "neitralnyi-lubrikant-jo-personal-premium-60-ml", "neitralnyi-lubrikant-jo-personal-h2o-120-ml", "vozbuzhdauschii-lubrikant-jo-h2o-warming-dlya-zhenschin-60-ml", "vozbuzhdauschii-lubrikant-jo-personal-h2o-warming-120-ml", "analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-60-ml", "analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-120-ml", "sogrevauschii-analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-warming-120-ml", "universalnyi-analnyi-lubrikant-jo-h2o-120-ml", "sogrevauschii-analnyi-lubrikant-jo-h2o-warming-60-ml", "sogrevauschii-analnyi-lubrikant-jo-h2o-warming-120-ml", "universalnyi-analnyi-lubrikant-jo-h2o", "ohlazhdauschii-analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-cool-75-ml", "analnyi-ohlazhdauschii-lubrikant-jo-anal-premium-cool-120-ml", "ohlazhdauschii-analnyi-lubrikant-jo-h2o-cool-120-ml", "vozbuzhdauschii-gel-dlya-tochki-g-myagkogo-deistviya-jo-g-spot-mild-16-ml", "prolongiruuschii-sprei-jo-prolonger-spray-2-ml", "massazhnoe-maslo-dona-let-me-tease-you-naughty-c-feromonami-vesennii-125-ml", "massazhnoe-maslo-dlya-oralnyh-lask-dona-let-me-kiss-you-so-vkusom-shokoladnogo-mussa-125-ml", "massazhnaya-svecha-dona-let-me-tease-you-naughty-s-feromonami-vesennii-135-g", "sogrevauschii-lubrikant-jo-silicone-free-hybrid-warming-s-maslom-kokosa-120-ml", "zheleobraznyi-lubrikant-na-vodnoi-osnove-jo-h2o-jelly-light-120-ml", "zheleobraznyi-lubrikant-na-vodnoi-osnove-jo-h2o-jelly-original-120-ml", "zheleobraznyi-lubrikant-legkoi-plotnosti-na-silikonovoi-osnove-jo-premium-jelly-light-120-ml", "naturalnyi-lubrikant-na-vodnoi-osnove-jo-naturelove-usda-organic-s-ekstraktom-romashki-30-ml", "naturalnyi-lubrikant-na-vodnoi-osnove-jo-naturelove-usda-organic-s-ekstraktom-romashki-60-ml", "lubrikant-dlya-zhenschin-jo-agape-original-60-ml", "naturalnyi-lubrikant-na-vodnoi-osnove-jo-naturelove-usda-organic-s-ekstraktom-romashki-120-ml", "lubrikant-dlya-zhenschin-jo-agape-original-120-ml", "maslo-dlya-vanny-sweet-sensation-sensual-bath-oil-100-ml", "massazhnoe-maslo-sensual-touch-massage-oil-100-ml", "karamel-dlya-tela-dona-taste-me-lollipop-s-feromonami-ledenec-59-ml", "karamel-dlya-tela-dona-taste-me-maple-sugar-s-feromonami-klenovyi-sahar-59-ml", "karamel-dlya-tela-dona-taste-me-honeysuckle-s-feromonami-zhimolost-59-ml", "pena-dlya-vanny-dona-pamper-me-pretty-naughty-s-feromonami-vesennii-240-ml", "cvetnaya-sol-dlya-vanny-dona-pamper-me-pretty-naughty-s-feromonami-vesennii-215-g", "cvetnaya-sol-dlya-vanny-dona-pamper-me-pretty-flirty-s-feromonami-yagodnyi-215-g", "vkusovaya-massazhnaya-svecha-dona-let-me-kiss-you-strawberry-souffle-klubnichnyi-135-g", "vkusovaya-massazhnaya-svecha-dona-let-me-kiss-you-vanilla-buttercream-vanilnyi-135-g", "vkusovaya-massazhnaya-svecha-dona-let-me-kiss-you-chocolate-mousse-shokoladnyi-135-g", "podarochnyi-nabor-dona-be-sexy-naughty-s-feromonami-vesennii", "podarochnyi-nabor-dona-be-sexy-flirty-s-feromonami-yagodnyi", "podarochnyi-nabor-dona-be-romanced-naughty-s-feromonami-vesennii", "podarochnyi-nabor-dona-be-romanced-sassy-s-feromonami-tropicheskii", "podarochnyi-nabor-dona-be-romanced-flirty-s-feromonami-yagodnyi", "analnaya-probka-tee-probes-rozovyi", "analnaya-probka-tee-probes-fioletovyi", "analnaya-cepochka-basic-essentials-beaded-probes-gibkaya-seryi", "vaginalnye-shariki-coco-licious-kegel-balls-iz-silikona-rozovye", "vaginalnye-shariki-so-smeschennym-centrom-tyazhesti-lia-love-balls-purple", "vibromassazher-extreme-pure-gold-platinum-elegance-serebristyi", "vibromassazher-extreme-pure-gold-24-karat-elegance-zolotistyi", "vibromassazher-coco-licious-fluttering-butterfly-rozovyi", "vibromassazher-coco-licious-hide-and-play-chernyi", "beskontaktnyi-volnovoi-hai-tek-stimulyator-klitora-satisfyer-pro-2-zolotistyi", "masturbator-anus-vivid-raw-butt-fuck-telesnyi", "masturbator-anus-vivid-raw-ass-banger-s-vibraciei-telesnyi", "masturbator-vagina-vivid-raw-hot-ass-pussy-nagrevaemyi-telesnyi", "nabor-vaginalnyh-sharikov-her-kegel-kit-s-vibropulei-fioletovyi", "nabor-her-g-spot-kit-dlya-stimulyacii-tochki-g-rozovyi", "analnyi-nabor-his-prostate-training-kit-s-vibropulei-chernyi", "erekcionnoe-kolco-super-stretch-stimulator-sleeve-noduled-rozovyi", "zhemchuzhiny-udovolstviya-pleasure-pearls-belyi", "vaginalnye-shariki-silver-balls-v-podarochnoi-korobke-serebristye", "vodonepronicaemyi-mini-vibromassazher-posh-petite-teaser-4", "besprovodnoi-stimulyator-babochka-venus-butterfly-s-tremya-skorostyami-rozovyi", "falloimitator-realistik-s-moshonkoi-king-cock-plus-6-5-dual-density-na-prisoske-telesnyi", "falloimitator-realistik-s-moshonkoi-king-cock-plus-7-5-dual-density-na-prisoske-telesnyi", "falloimitator-realistik-s-moshonkoi-king-cock-plus-8-dual-density-na-prisoske-telesnyi", "falloimitator-realistik-s-moshonkoi-king-cock-plus-9-dual-density-na-prisoske-telesnyi", "falloimitator-realistik-s-moshonkoi-king-cock-plus-10-dual-density-fat-cock-na-prisoske-telesnyi", "analnaya-probka-vibrating-perfect-plug-s-vibraciei", "analnaya-probka-starter-plug-chernyi", "trusiki-s-vibraciei-hanky-spank-me-chernyi", "vibromassazher-s-moshonkoi-real-feel-deluxe-2-6-5-telesnyi", "vibromassazher-tochki-g-jelly-gems-3", "erekcionnoe-vibro-kolco-yours-and-mine", "nasadka-stimuliruuschaya-magnum-support-plus-single-girth-cage-chernyi", "nasadka-stimuliruuschaya-magnum-support-plus-single-girth-cage-prozrachnyi", "nasadka-udlinitel-kanikule-extender-cap-2-telesnaya", "nasadka-udlinitel-kanikule-extender-cap-3-telesnaya", "nasadka-udlinitel-kanikule-extender-cap-2-s-kolcom-na-moshonku-telesnyi", "uvelichivauschaya-nasadka-na-penis-kanikule-extender-cap-2-telesnyi", "vakuumnaya-pompa-beginners-power-red", "vibromassazher-impress-dove-so-stimulyaciei-klitora-goluboi", "vibromassazher-impress-dove-so-stimulyaciei-klitora-rozovyi", "vibromassazher-impress-g-so-stimulyaciei-klitora-rozovyi", "vibromassazher-impress-rabbit-so-stimulyaciei-klitora-fioletovyi", "vibromassazher-impress-rabbit-so-stimulyaciei-klitora-goluboi", "shlepalka-lineika-spank-me-please-chernaya", "sistema-remnei-premium-ring-harness-dlya-krepleniya-strapona", "pletka-scandal-flogger-s-ruchkoi-v-atlase", "maska-na-glaza-scandal-eye-mask-cherno-krasnaya", "kreplenie-dlya-faloimmitatora-scandal-thong-harness", "fiksaciya-na-dver-s-naruchnikami-scandal-over-the-door-cuffs-v-atlase", "fiksaciya-dlya-ruk-i-nog-scandal-over-the-door-cross-krestoobraznaya-c-krepleniem-na-dver", "povodok-s-cepu-scandal-leash", "manzhety-na-zapyastya-starter", "naruchniki-furry-love-leopard", "naruchniki-furry-love-tiger", "klyap-bar-scandal-bar-gag-s-atlasnymi-lentami", "klyap-ramka-scandal-open-mouth-gag-with-clamps-s-zazhimami-na-soski", "shlepalka-scandal-paddle-v-atlase", "nanozhniki-scandal-love-sling-s-dlinnym-remeshkom", "metallicheskii-sterzhen-s-krepleniyami-dlya-bondazha-scandal-spreader-bar-v-atlase", "nanozhniki-atlasnye-scandal-control-cuffs", "klyap-scandal-ball-gag-s-atlasnymi-lentami", "korset-s-naruchnikami-scandal-corset-with-cuffs", "nabor-dlya-bondazha-fetish-fantasy-gold-chernyi-s-zolotom", "nabor-feather-fantasy-krasnyi", "nabor-dlya-intimnyh-udovolstvii-lovers-bondage-kit-krasnyi", "nabor-dlya-bdsm-silky-seduction-chernyi", "nabor-dlya-intimnyh-udovolstvii-shut-me-up-kit-chernyi", "nabor-dlya-intimnyh-udovolstvii-purple-passion-kit-fioletovyi", "nabor-dlya-bdsm-tease-n-please-chernyi", "igrovoi-kostum-uletnaya-stuardessa", "igrovoi-kostum-uletnaya-stuardessa-queen-size", "igrovoi-kostum-klassnaya-medsestra-cherno-krasnyi", "igrovoi-kostum-idealnaya-sekretarsha-queen-size", "eroticheskii-kostum-seksualnaya-sekretarsha-queen-size", "igrovoi-kostum-prilezhnaya-uchenica-queen-size", "eroticheskii-kostum-ozornaya-shkolnica", "igrovoi-kostum-shkolnica-koketka-queen-size", "igrovoi-kostum-neskromnaya-otlichnica-queen-size", "igrovoi-kostum-skromnaya-shkolnica-belo-krasnyi", "igrovoi-kostum-nochnoi-patrul", "eroticheskii-kostum-sluzhanka-keti-belo-chernyi", "igrovoi-kostum-seksualnaya-sluzhanka-queen-size", "igrovoi-kostum-vezhlivaya-gornichnaya-queen-size", "igrovoi-kostum-nochnaya-gornichnaya-cherno-belyi", "eroticheskii-kostum-molniya-lubvi-queen-size", "igrovoi-kostum-pokornaya-saba-queen-size", "eroticheskii-kostum-seksualnyi-serzhant-queen-size", "igrovoi-kostum-plennica-seksa-queen-size", "igrovoi-kostum-strogaya-nadziratelnica-queen-size", "trusiki-have-fun-princess-s-rushami-chernyi-belyi-s-m", "soblaznitelnye-chernye-trusiki-have-fun-princess", "krasnaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-one-size", "komplekt-baci-white-label-iz-bustgaltera-s-kruzhevnoi-otdelkoi-poyasa-s-podvyazkami-dlya-chulok-i-trusikov-leopardovyi", "seksualnoe-bodi-baci-white-label-s-dekorativnym-pleteniem-speredi-i-otkrytymi-chashechkami-lifa-krasnyi-s-chernym", "chernoe-kruzhevnoe-bodi-baci-white-label-s-prorezyami-na-chashechkah-buste-i-krasnymi-bantikami-one-size", "komplekt-zhenskogo-belya-bustgalter-s-seksualnymi-razrezami-i-ubochka-chernyi-s-krasnym", "originalnyi-komplekt-zhenskogo-belya-bustgalter-so-shnurovkoi-i-ubochka-chernyi-s-krasnym", "komplekt-zhenskogo-belya-bustgalter-i-ubochka-chernyi-s-krasnym", "kruzhevnoi-setchatyi-bodistoking-baci-white-label-s-vyrezom-chernyi", "chernyi-kruzhevnoi-bodistoking-baci-white-label-iz-buste-s-imitaciei-shnurovki-i-chulok-na-podvyazkah-queen-size", "mini-plate-na-odno-plecho-baci-white-label-s-kruzhevami-i-krupnoi-setkoi-romb-chernyi", "prozrachnaya-tulevaya-sorochka-baci-deeper-in-hell-s-kruzhevami-chernyi", "prozrachnaya-tulevaya-sorochka-baci-back-in-heaven-s-kruzhevami-i-stringami-belyi", "sverkauschii-monokini-baci-deeper-in-hell-s-kruzhevami-chernyi", "kruzhevnoe-kimono-baci-deeper-in-hell-chernyi", "monokini-baci-dolce-vita-iz-tulevoi-tkani-s-punktirnym-uzorom-na-zavyazkah-chernyi", "kruzhevnoe-kimono-baci-back-in-heaven-belyi", "kruzhevnoi-komplekt-baci-white-label-iz-bustgaltera-trusikov-i-poyasa-s-podvyazkami-chernyi", "kruzhevnoe-bodi-baci-white-label-collection-s-otkrytoi-spinoi-chernyi", "kruzhevnoe-mini-plate-baci-white-label-s-bokovymi-vyrezami-i-glubokim-dekolte-leopardovyi-s-chernym", "chulki-v-setku-baci-after-dark-vysokie-s-dekorativnym-zadnim-shvom-chernyi", "bodistoking-iz-dvuh-chastei-baci-after-dark-v-setku-romb-chernyi", "vysokie-chulki-v-setochku-baci-after-dark-s-dekorativnymi-polosami-na-manzhetah-chernyi", "celnye-chulki-baci-after-dark-s-kruzhevnym-verhom-podvyazkami-i-dekorativnym-zadnim-shvom-chernyi", "vysokie-zhakkardovye-chulki-baci-after-dark-chernyi", "vysokie-chulki-baci-after-dark-s-temnoi-matovoi-vstavkoi-chernyi", "vysokie-chulki-baci-after-dark-v-setochku-chernyi", "chernaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-one-size", "chernaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-queen-size", "krasnaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-queen-size", "atlasnoe-kimono-beauty-inside-the-beast-leopardovyi", "zheltye-stringi-s-reguliruemymi-bretelyami-lets-play", "zelenye-stringi-s-reguliruemymi-bretelyami-lets-play", "seksualnyi-krasnyi-bikini-s-chernoi-otdelkoi-criminal-minds", "chulok-na-telo-s-dlinnymi-rukavami-criminal-minds-queen-size", "roskoshnyi-chulok-na-telo-criminal-minds-queen-size", "chulki-setka-s-myagkimi-peryami-criminal-minds", "velikolepnye-belye-shortiki-love-angels-queen-size", "voshititelnye-fioletovye-shortiki-criminal-minds-queen-size", "chernye-g-stringi-s-rozovoi-otdelkoi-lets-play", "g-stringi-golubogo-cveta-s-rozovoi-otdelkoi-love-angels-m", "kruzhevnye-trusiki-s-razrezom-love-angels-diva-size", "soblaznitelnyi-komplekt-bikini-iz-setochki-spanish-dreams", "voshititelnoe-bodi-iz-kruzheva-spanish-dreams", "komplekt-s-mini-ubkoi-chernyi-rozovyi", "soblaznitelnye-v-stringi-fioletovogo-cveta-neonbarock", "seksualnyi-komplekt-pod-zebru-animal", "chulki-sexy-secretary-vysokie-chernye", "chulki-night-patrol-police-s-uzorom-vysokie-chernye", "chulki-obedient-slave-vysokie-chernye", "komplekt-mafia-so-shnurovkoi-chernyi", "eroticheskii-kostum-medsestry-dream", "korset-essential-satin-and-lace-corset-chernyi-s", "korset-metallics-corset-serebristyi-s", "korset-metallics-corset-zolotistyi-s", "korset-suede-and-leather-cincher-chernyi-s", "korset-suit-inspired-cincher-seryi-s", "korset-suit-inspired-waistcoat-corset-korichnevyi-s", "korset-suit-inspired-tank-corset-seryi-s", "korset-satin-heart-corset-rozovyi-s", "korset-satin-heart-corset-krasnyi-s", "korset-satin-and-lace-corset-rozovyi-s", "korset-satin-and-lace-corset-krasnyi-s", "korset-satin-and-lace-cincher-rozovyi-s", "vysokie-chulki-v-setochku-baci-after-dark-s-blestyaschim-kruzhevnym-verhom-chernyi", "vysokie-chulki-baci-after-dark-s-tochechnym-uzorom-chernyi", "seksualnoe-bodi-baci-white-label-na-remeshkah-s-otkrytymi-chashechkami-chernyi", "prozrachnyi-monokini-baci-have-fun-princess-iz-tulevoi-tkani-v-goroshek-bordovyi", "monokini-baci-back-in-heaven-iz-dekorirovannoi-tulevoi-tkani-s-kruzhevami-belyi", "komplekt-baci-deeper-in-hell-iz-blestyaschego-kruzhevnogo-bustgaltera-i-ubochki-chernyi-s-zolotom", "komplekt-baci-back-in-heaven-iz-bustgaltera-i-ubochki-s-podvyazkami-dlya-chulok-belyi", "seksualnyi-nabor-baci-white-label-iz-bustgaltera-na-remeshkah-s-otkrytymi-chashechkami-i-trusikov-chernyi", "seksualnyi-nabor-baci-white-label-iz-bustgaltera-na-remeshkah-s-otkrytymi-chashechkami-i-trusikov-krasnyi", "komplekt-nizhnego-belya-love-slave-chernyi-1", "eroticheskii-komplekt-gornichnoi-maid-bustgalter-i-ubochka-chernyi", "ocharovatelnyi-komplekt-nizhnego-belya-maid-bustalter-trusiki-i-vorotnichok-cherno-belyi", "komplekt-nizhnego-belya-love-slave-s-otkrytym-bustgalterom-chernyi", "krasnaya-sorochka-baci-white-label-collection-s-otkrytymi-chashechkami-i-trusikami-one-size", "prozrachnaya-kruzhevnaya-sorochka-boci-have-fun-princess-iz-tulevoi-tkani-s-zavyazkami-na-shee-malinovyi", "prozrachnoe-mini-plate-baci-back-in-heaven-s-kruzhevami-i-podvyazkami-dlya-chulok-belyi", "stilnyi-komplekt-iz-maiki-i-trusov-envy-s-w-a-t-chernyi-m-l", "eroticheskii-igrovoi-kostum-envy-cop-kop-hipsy-i-furazhka-sinii-m-l", "eroticheskii-igrovoi-kostum-envy-tuxedo-dzhentlmen-vorotnichok-manzhety-i-trusy-belyi-s-chernym-m-l", "eroticheskii-igrovoi-komplekt-envy-biker-baiker-chernyi-m-l", "eroticheskii-igrovoi-komplekt-envy-sailor-moi-kapitan-belyi-m-l", "tulevaya-sorochka-baci-dolce-vita-s-cvetochnoi-vyshivkoi-chernyi", "penuar-baci-back-in-heaven-s-kruzhevnym-buste-i-stringami-svetlo-rozovyi", "prozrachnaya-tulevaya-sorochka-boci-have-fun-princess-s-kruzhevami-bordovyi", "eroticheskii-igrovoi-kostum-envy-fireman-pozharnik-hipsy-s-podtyazhkami-sinii-l-xl", "kruzhevnoi-komplekt-bikini-agent-of-love-s-rushami-svetlo-bezhevyi", "kruzhevnye-stringi-have-fun-princess-s-chernoi-applikaciei-belye-m-l", "elegantnyi-kruzhevnoi-komplekt-spanish-dreams", "komplekt-bikini-spanish-s-rushami-chernyi-krasnyi", "komplekt-bikini-barbie-v-setochku-rozovyi-chernyi", "umopomrachitelnyi-setchatyi-teddi-spanish-dreams", "osobennyi-komplekt-bebidoll-spanish-dreams");
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute('SELECT * FROM  `product` WHERE slug in ("lubrikant-na-vodnoi-osnove-jo-h2o-original-30-ml","sogrevauschii-lubrikant-jo-silicone-free-hybrid-warming-s-maslom-kokosa-30-ml","neitralnyi-lubrikant-jo-personal-premium-120-ml","neitralnyi-lubrikant-jo-personal-premium-60-ml","neitralnyi-lubrikant-jo-personal-h2o-120-ml","vozbuzhdauschii-lubrikant-jo-h2o-warming-dlya-zhenschin-60-ml","vozbuzhdauschii-lubrikant-jo-personal-h2o-warming-120-ml","analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-60-ml","analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-120-ml","sogrevauschii-analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-warming-120-ml","universalnyi-analnyi-lubrikant-jo-h2o-120-ml","sogrevauschii-analnyi-lubrikant-jo-h2o-warming-60-ml","sogrevauschii-analnyi-lubrikant-jo-h2o-warming-120-ml","universalnyi-analnyi-lubrikant-jo-h2o","ohlazhdauschii-analnyi-lubrikant-na-silikonovoi-osnove-jo-premium-cool-75-ml","analnyi-ohlazhdauschii-lubrikant-jo-anal-premium-cool-120-ml","ohlazhdauschii-analnyi-lubrikant-jo-h2o-cool-120-ml","vozbuzhdauschii-gel-dlya-tochki-g-myagkogo-deistviya-jo-g-spot-mild-16-ml","prolongiruuschii-sprei-jo-prolonger-spray-2-ml","massazhnoe-maslo-dona-let-me-tease-you-naughty-c-feromonami-vesennii-125-ml","massazhnoe-maslo-dlya-oralnyh-lask-dona-let-me-kiss-you-so-vkusom-shokoladnogo-mussa-125-ml","massazhnaya-svecha-dona-let-me-tease-you-naughty-s-feromonami-vesennii-135-g","sogrevauschii-lubrikant-jo-silicone-free-hybrid-warming-s-maslom-kokosa-120-ml","zheleobraznyi-lubrikant-na-vodnoi-osnove-jo-h2o-jelly-light-120-ml","zheleobraznyi-lubrikant-na-vodnoi-osnove-jo-h2o-jelly-original-120-ml","zheleobraznyi-lubrikant-legkoi-plotnosti-na-silikonovoi-osnove-jo-premium-jelly-light-120-ml","naturalnyi-lubrikant-na-vodnoi-osnove-jo-naturelove-usda-organic-s-ekstraktom-romashki-30-ml","naturalnyi-lubrikant-na-vodnoi-osnove-jo-naturelove-usda-organic-s-ekstraktom-romashki-60-ml","lubrikant-dlya-zhenschin-jo-agape-original-60-ml","naturalnyi-lubrikant-na-vodnoi-osnove-jo-naturelove-usda-organic-s-ekstraktom-romashki-120-ml","lubrikant-dlya-zhenschin-jo-agape-original-120-ml","maslo-dlya-vanny-sweet-sensation-sensual-bath-oil-100-ml","massazhnoe-maslo-sensual-touch-massage-oil-100-ml","karamel-dlya-tela-dona-taste-me-lollipop-s-feromonami-ledenec-59-ml","karamel-dlya-tela-dona-taste-me-maple-sugar-s-feromonami-klenovyi-sahar-59-ml","karamel-dlya-tela-dona-taste-me-honeysuckle-s-feromonami-zhimolost-59-ml","pena-dlya-vanny-dona-pamper-me-pretty-naughty-s-feromonami-vesennii-240-ml","cvetnaya-sol-dlya-vanny-dona-pamper-me-pretty-naughty-s-feromonami-vesennii-215-g","cvetnaya-sol-dlya-vanny-dona-pamper-me-pretty-flirty-s-feromonami-yagodnyi-215-g","vkusovaya-massazhnaya-svecha-dona-let-me-kiss-you-strawberry-souffle-klubnichnyi-135-g","vkusovaya-massazhnaya-svecha-dona-let-me-kiss-you-vanilla-buttercream-vanilnyi-135-g","vkusovaya-massazhnaya-svecha-dona-let-me-kiss-you-chocolate-mousse-shokoladnyi-135-g","podarochnyi-nabor-dona-be-sexy-naughty-s-feromonami-vesennii","podarochnyi-nabor-dona-be-sexy-flirty-s-feromonami-yagodnyi","podarochnyi-nabor-dona-be-romanced-naughty-s-feromonami-vesennii","podarochnyi-nabor-dona-be-romanced-sassy-s-feromonami-tropicheskii","podarochnyi-nabor-dona-be-romanced-flirty-s-feromonami-yagodnyi","analnaya-probka-tee-probes-rozovyi","analnaya-probka-tee-probes-fioletovyi","analnaya-cepochka-basic-essentials-beaded-probes-gibkaya-seryi","vaginalnye-shariki-coco-licious-kegel-balls-iz-silikona-rozovye","vaginalnye-shariki-so-smeschennym-centrom-tyazhesti-lia-love-balls-purple","vibromassazher-extreme-pure-gold-platinum-elegance-serebristyi","vibromassazher-extreme-pure-gold-24-karat-elegance-zolotistyi","vibromassazher-coco-licious-fluttering-butterfly-rozovyi","vibromassazher-coco-licious-hide-and-play-chernyi","beskontaktnyi-volnovoi-hai-tek-stimulyator-klitora-satisfyer-pro-2-zolotistyi","masturbator-anus-vivid-raw-butt-fuck-telesnyi","masturbator-anus-vivid-raw-ass-banger-s-vibraciei-telesnyi","masturbator-vagina-vivid-raw-hot-ass-pussy-nagrevaemyi-telesnyi","nabor-vaginalnyh-sharikov-her-kegel-kit-s-vibropulei-fioletovyi","nabor-her-g-spot-kit-dlya-stimulyacii-tochki-g-rozovyi","analnyi-nabor-his-prostate-training-kit-s-vibropulei-chernyi","erekcionnoe-kolco-super-stretch-stimulator-sleeve-noduled-rozovyi","zhemchuzhiny-udovolstviya-pleasure-pearls-belyi","vaginalnye-shariki-silver-balls-v-podarochnoi-korobke-serebristye","vodonepronicaemyi-mini-vibromassazher-posh-petite-teaser-4","besprovodnoi-stimulyator-babochka-venus-butterfly-s-tremya-skorostyami-rozovyi","falloimitator-realistik-s-moshonkoi-king-cock-plus-6-5-dual-density-na-prisoske-telesnyi","falloimitator-realistik-s-moshonkoi-king-cock-plus-7-5-dual-density-na-prisoske-telesnyi","falloimitator-realistik-s-moshonkoi-king-cock-plus-8-dual-density-na-prisoske-telesnyi","falloimitator-realistik-s-moshonkoi-king-cock-plus-9-dual-density-na-prisoske-telesnyi","falloimitator-realistik-s-moshonkoi-king-cock-plus-10-dual-density-fat-cock-na-prisoske-telesnyi","analnaya-probka-vibrating-perfect-plug-s-vibraciei","analnaya-probka-starter-plug-chernyi","trusiki-s-vibraciei-hanky-spank-me-chernyi","vibromassazher-s-moshonkoi-real-feel-deluxe-2-6-5-telesnyi","vibromassazher-tochki-g-jelly-gems-3","erekcionnoe-vibro-kolco-yours-and-mine","nasadka-stimuliruuschaya-magnum-support-plus-single-girth-cage-chernyi","nasadka-stimuliruuschaya-magnum-support-plus-single-girth-cage-prozrachnyi","nasadka-udlinitel-kanikule-extender-cap-2-telesnaya","nasadka-udlinitel-kanikule-extender-cap-3-telesnaya","nasadka-udlinitel-kanikule-extender-cap-2-s-kolcom-na-moshonku-telesnyi","uvelichivauschaya-nasadka-na-penis-kanikule-extender-cap-2-telesnyi","vakuumnaya-pompa-beginners-power-red","vibromassazher-impress-dove-so-stimulyaciei-klitora-goluboi","vibromassazher-impress-dove-so-stimulyaciei-klitora-rozovyi","vibromassazher-impress-g-so-stimulyaciei-klitora-rozovyi","vibromassazher-impress-rabbit-so-stimulyaciei-klitora-fioletovyi","vibromassazher-impress-rabbit-so-stimulyaciei-klitora-goluboi","shlepalka-lineika-spank-me-please-chernaya","sistema-remnei-premium-ring-harness-dlya-krepleniya-strapona","pletka-scandal-flogger-s-ruchkoi-v-atlase","maska-na-glaza-scandal-eye-mask-cherno-krasnaya","kreplenie-dlya-faloimmitatora-scandal-thong-harness","fiksaciya-na-dver-s-naruchnikami-scandal-over-the-door-cuffs-v-atlase","fiksaciya-dlya-ruk-i-nog-scandal-over-the-door-cross-krestoobraznaya-c-krepleniem-na-dver","povodok-s-cepu-scandal-leash","manzhety-na-zapyastya-starter","naruchniki-furry-love-leopard","naruchniki-furry-love-tiger","klyap-bar-scandal-bar-gag-s-atlasnymi-lentami","klyap-ramka-scandal-open-mouth-gag-with-clamps-s-zazhimami-na-soski","shlepalka-scandal-paddle-v-atlase","nanozhniki-scandal-love-sling-s-dlinnym-remeshkom","metallicheskii-sterzhen-s-krepleniyami-dlya-bondazha-scandal-spreader-bar-v-atlase","nanozhniki-atlasnye-scandal-control-cuffs","klyap-scandal-ball-gag-s-atlasnymi-lentami","korset-s-naruchnikami-scandal-corset-with-cuffs","nabor-dlya-bondazha-fetish-fantasy-gold-chernyi-s-zolotom","nabor-feather-fantasy-krasnyi","nabor-dlya-intimnyh-udovolstvii-lovers-bondage-kit-krasnyi","nabor-dlya-bdsm-silky-seduction-chernyi","nabor-dlya-intimnyh-udovolstvii-shut-me-up-kit-chernyi","nabor-dlya-intimnyh-udovolstvii-purple-passion-kit-fioletovyi","nabor-dlya-bdsm-tease-n-please-chernyi","igrovoi-kostum-uletnaya-stuardessa","igrovoi-kostum-uletnaya-stuardessa-queen-size","igrovoi-kostum-klassnaya-medsestra-cherno-krasnyi","igrovoi-kostum-idealnaya-sekretarsha-queen-size","eroticheskii-kostum-seksualnaya-sekretarsha-queen-size","igrovoi-kostum-prilezhnaya-uchenica-queen-size","eroticheskii-kostum-ozornaya-shkolnica","igrovoi-kostum-shkolnica-koketka-queen-size","igrovoi-kostum-neskromnaya-otlichnica-queen-size","igrovoi-kostum-skromnaya-shkolnica-belo-krasnyi","igrovoi-kostum-nochnoi-patrul","eroticheskii-kostum-sluzhanka-keti-belo-chernyi","igrovoi-kostum-seksualnaya-sluzhanka-queen-size","igrovoi-kostum-vezhlivaya-gornichnaya-queen-size","igrovoi-kostum-nochnaya-gornichnaya-cherno-belyi","eroticheskii-kostum-molniya-lubvi-queen-size","igrovoi-kostum-pokornaya-saba-queen-size","eroticheskii-kostum-seksualnyi-serzhant-queen-size","igrovoi-kostum-plennica-seksa-queen-size","igrovoi-kostum-strogaya-nadziratelnica-queen-size","trusiki-have-fun-princess-s-rushami-chernyi-belyi-s-m","soblaznitelnye-chernye-trusiki-have-fun-princess","krasnaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-one-size","komplekt-baci-white-label-iz-bustgaltera-s-kruzhevnoi-otdelkoi-poyasa-s-podvyazkami-dlya-chulok-i-trusikov-leopardovyi","seksualnoe-bodi-baci-white-label-s-dekorativnym-pleteniem-speredi-i-otkrytymi-chashechkami-lifa-krasnyi-s-chernym","chernoe-kruzhevnoe-bodi-baci-white-label-s-prorezyami-na-chashechkah-buste-i-krasnymi-bantikami-one-size","komplekt-zhenskogo-belya-bustgalter-s-seksualnymi-razrezami-i-ubochka-chernyi-s-krasnym","originalnyi-komplekt-zhenskogo-belya-bustgalter-so-shnurovkoi-i-ubochka-chernyi-s-krasnym","komplekt-zhenskogo-belya-bustgalter-i-ubochka-chernyi-s-krasnym","kruzhevnoi-setchatyi-bodistoking-baci-white-label-s-vyrezom-chernyi","chernyi-kruzhevnoi-bodistoking-baci-white-label-iz-buste-s-imitaciei-shnurovki-i-chulok-na-podvyazkah-queen-size","mini-plate-na-odno-plecho-baci-white-label-s-kruzhevami-i-krupnoi-setkoi-romb-chernyi","prozrachnaya-tulevaya-sorochka-baci-deeper-in-hell-s-kruzhevami-chernyi","prozrachnaya-tulevaya-sorochka-baci-back-in-heaven-s-kruzhevami-i-stringami-belyi","sverkauschii-monokini-baci-deeper-in-hell-s-kruzhevami-chernyi","kruzhevnoe-kimono-baci-deeper-in-hell-chernyi","monokini-baci-dolce-vita-iz-tulevoi-tkani-s-punktirnym-uzorom-na-zavyazkah-chernyi","kruzhevnoe-kimono-baci-back-in-heaven-belyi","kruzhevnoi-komplekt-baci-white-label-iz-bustgaltera-trusikov-i-poyasa-s-podvyazkami-chernyi","kruzhevnoe-bodi-baci-white-label-collection-s-otkrytoi-spinoi-chernyi","kruzhevnoe-mini-plate-baci-white-label-s-bokovymi-vyrezami-i-glubokim-dekolte-leopardovyi-s-chernym","chulki-v-setku-baci-after-dark-vysokie-s-dekorativnym-zadnim-shvom-chernyi","bodistoking-iz-dvuh-chastei-baci-after-dark-v-setku-romb-chernyi","vysokie-chulki-v-setochku-baci-after-dark-s-dekorativnymi-polosami-na-manzhetah-chernyi","celnye-chulki-baci-after-dark-s-kruzhevnym-verhom-podvyazkami-i-dekorativnym-zadnim-shvom-chernyi","vysokie-zhakkardovye-chulki-baci-after-dark-chernyi","vysokie-chulki-baci-after-dark-s-temnoi-matovoi-vstavkoi-chernyi","vysokie-chulki-baci-after-dark-v-setochku-chernyi","chernaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-one-size","chernaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-queen-size","krasnaya-sorochka-baci-white-label-s-leopardovoi-vstavkoi-i-podvyazkami-dlya-chulok-queen-size","atlasnoe-kimono-beauty-inside-the-beast-leopardovyi","zheltye-stringi-s-reguliruemymi-bretelyami-lets-play","zelenye-stringi-s-reguliruemymi-bretelyami-lets-play","seksualnyi-krasnyi-bikini-s-chernoi-otdelkoi-criminal-minds","chulok-na-telo-s-dlinnymi-rukavami-criminal-minds-queen-size","roskoshnyi-chulok-na-telo-criminal-minds-queen-size","chulki-setka-s-myagkimi-peryami-criminal-minds","velikolepnye-belye-shortiki-love-angels-queen-size","voshititelnye-fioletovye-shortiki-criminal-minds-queen-size","chernye-g-stringi-s-rozovoi-otdelkoi-lets-play","g-stringi-golubogo-cveta-s-rozovoi-otdelkoi-love-angels-m","kruzhevnye-trusiki-s-razrezom-love-angels-diva-size","soblaznitelnyi-komplekt-bikini-iz-setochki-spanish-dreams","voshititelnoe-bodi-iz-kruzheva-spanish-dreams","komplekt-s-mini-ubkoi-chernyi-rozovyi","soblaznitelnye-v-stringi-fioletovogo-cveta-neonbarock","seksualnyi-komplekt-pod-zebru-animal","chulki-sexy-secretary-vysokie-chernye","chulki-night-patrol-police-s-uzorom-vysokie-chernye","chulki-obedient-slave-vysokie-chernye","komplekt-mafia-so-shnurovkoi-chernyi","eroticheskii-kostum-medsestry-dream","korset-essential-satin-and-lace-corset-chernyi-s","korset-metallics-corset-serebristyi-s","korset-metallics-corset-zolotistyi-s","korset-suede-and-leather-cincher-chernyi-s","korset-suit-inspired-cincher-seryi-s","korset-suit-inspired-waistcoat-corset-korichnevyi-s","korset-suit-inspired-tank-corset-seryi-s","korset-satin-heart-corset-rozovyi-s","korset-satin-heart-corset-krasnyi-s","korset-satin-and-lace-corset-rozovyi-s","korset-satin-and-lace-corset-krasnyi-s","korset-satin-and-lace-cincher-rozovyi-s","vysokie-chulki-v-setochku-baci-after-dark-s-blestyaschim-kruzhevnym-verhom-chernyi","vysokie-chulki-baci-after-dark-s-tochechnym-uzorom-chernyi","seksualnoe-bodi-baci-white-label-na-remeshkah-s-otkrytymi-chashechkami-chernyi","prozrachnyi-monokini-baci-have-fun-princess-iz-tulevoi-tkani-v-goroshek-bordovyi","monokini-baci-back-in-heaven-iz-dekorirovannoi-tulevoi-tkani-s-kruzhevami-belyi","komplekt-baci-deeper-in-hell-iz-blestyaschego-kruzhevnogo-bustgaltera-i-ubochki-chernyi-s-zolotom","komplekt-baci-back-in-heaven-iz-bustgaltera-i-ubochki-s-podvyazkami-dlya-chulok-belyi","seksualnyi-nabor-baci-white-label-iz-bustgaltera-na-remeshkah-s-otkrytymi-chashechkami-i-trusikov-chernyi","seksualnyi-nabor-baci-white-label-iz-bustgaltera-na-remeshkah-s-otkrytymi-chashechkami-i-trusikov-krasnyi","komplekt-nizhnego-belya-love-slave-chernyi-1","eroticheskii-komplekt-gornichnoi-maid-bustgalter-i-ubochka-chernyi","ocharovatelnyi-komplekt-nizhnego-belya-maid-bustalter-trusiki-i-vorotnichok-cherno-belyi","komplekt-nizhnego-belya-love-slave-s-otkrytym-bustgalterom-chernyi","krasnaya-sorochka-baci-white-label-collection-s-otkrytymi-chashechkami-i-trusikami-one-size","prozrachnaya-kruzhevnaya-sorochka-boci-have-fun-princess-iz-tulevoi-tkani-s-zavyazkami-na-shee-malinovyi","prozrachnoe-mini-plate-baci-back-in-heaven-s-kruzhevami-i-podvyazkami-dlya-chulok-belyi","stilnyi-komplekt-iz-maiki-i-trusov-envy-s-w-a-t-chernyi-m-l","eroticheskii-igrovoi-kostum-envy-cop-kop-hipsy-i-furazhka-sinii-m-l","eroticheskii-igrovoi-kostum-envy-tuxedo-dzhentlmen-vorotnichok-manzhety-i-trusy-belyi-s-chernym-m-l","eroticheskii-igrovoi-komplekt-envy-biker-baiker-chernyi-m-l","eroticheskii-igrovoi-komplekt-envy-sailor-moi-kapitan-belyi-m-l","tulevaya-sorochka-baci-dolce-vita-s-cvetochnoi-vyshivkoi-chernyi","penuar-baci-back-in-heaven-s-kruzhevnym-buste-i-stringami-svetlo-rozovyi","prozrachnaya-tulevaya-sorochka-boci-have-fun-princess-s-kruzhevami-bordovyi","eroticheskii-igrovoi-kostum-envy-fireman-pozharnik-hipsy-s-podtyazhkami-sinii-l-xl","kruzhevnoi-komplekt-bikini-agent-of-love-s-rushami-svetlo-bezhevyi","kruzhevnye-stringi-have-fun-princess-s-chernoi-applikaciei-belye-m-l","elegantnyi-kruzhevnoi-komplekt-spanish-dreams","komplekt-bikini-spanish-s-rushami-chernyi-krasnyi","komplekt-bikini-barbie-v-setochku-rozovyi-chernyi","umopomrachitelnyi-setchatyi-teddi-spanish-dreams","osobennyi-komplekt-bebidoll-spanish-dreams")');
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $products = $result->fetchAll();
          foreach ($products as $product) {
          unset($arrayProductSlug[array_search($product['slug'], $arrayProductSlug)]);
          //var_dump(array_search($product['slug'], $arrayProductSlug));
          }
          print_r($arrayProductSlug);
         */
/*
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        require_once 'PHPExcel/Classes/PHPExcel.php';
        $excel2 = PHPExcel_IOFactory::createReader('Excel2007');
        $excel2 = $excel2->load('/var/www/ononaru/data/www/testProducts2.xlsx'); // Empty Sheet
        $excel2->setActiveSheetIndex(0);


        $objReader = PHPExcel_IOFactory::createReaderForFile('/var/www/ononaru/data/www/testProducts2.xlsx');
        $objReader->setReadDataOnly(true);
        $this->PHPExcel = $objReader->load('/var/www/ononaru/data/www/testProducts2.xlsx');
        foreach ($this->PHPExcel->getActiveSheet()->toArray() as $keyRows => $row) {
            //print_r($row[1]);
            if (@$row[1] > 0) {
                //print_r($row);
                $slugProd = trim(str_replace("https://onona.ru/product/", "", $row[9]));
                $product = ProductTable::getInstance()->findOneBySlug($slugProd);
                if ($product) {
                    $product->setOldPrice($row[1]);
                    $product->setPrice(round($row[2]));
                    $product->setDiscount(round($row[3]));
                    $product->setSortpriority(100);
                    $product->save();

                    $photosAll = $q->execute("SELECT photo.filename as filename FROM photo "
                                    . "LEFT JOIN product_photoalbum as ppa ON photo.album_id=ppa.photoalbum_id "
                                    . "WHERE ppa.product_id in (" . ($product->getId()) . ") "
                                    . "ORDER BY photo.position DESC")
                            ->fetchColumn();

                    $excel2->getActiveSheet()->setCellValue('K' . ($keyRows + 1), "https://onona.ru/uploads/photo/" . $photosAll);

                    $objWriter = PHPExcel_IOFactory::createWriter($excel2, 'Excel2007');
                    $objWriter->save('/var/www/ononaru/data/www/testProductsFinish.xlsx');
                    $issetcatprod = CategoryProductTable::getInstance()->createQuery()->where("category_id = 248 and product_id = " . $product->getId())->fetchOne();
                    if (!$issetcatprod) {
                        $categoryProduct = new CategoryProduct();
                        $categoryProduct->set("category_id", 248);
                        $categoryProduct->set("product_id", $product->getId());
                        $categoryProduct->save();
                    }
                    //print_r($keyRows);
                    unset($slugProd, $photosAll, $categoryProduct);
                } else {
                    echo $slugProd . "\n";
                }
                //exit;
            }
        }

        exit;*/
/*

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT * FROM  `sf_guard_user` WHERE cart is not null and cart !='' and cart !='a:0:{}' and cart !='s:0:\"\";' and cart !='b:0;' and last_login > '" . date("Y-m-d H:i:s", (time() - 259200)) . "' and last_login < '" . date("Y-m-d H:i:s", (time() - 172800)) . "'");
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $users = $result->fetchAll();

        foreach ($users as $user):

            if ($this->is_email($user['email_address'])):

                $products_old = $user['cart'];
                $products_old = $products_old != '' ? unserialize($products_old) : '';
                //print_r($products_old);exit;
                $tableOrder = "";
                $TotalSumm = 0;
                $bonusAddUser = 0;
                $this->bonus = BonusTable::getInstance()->findBy('user_id', $user['id']);
                $this->bonusCount = 0;
                foreach ($this->bonus as $bonus) {
                    $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                }
                foreach ($products_old as $key => $productInfo):
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    $photoalbum = $product->getPhotoalbums();
                    $photos = $photoalbum[0]->getPhotos();
                    if ($product->getBonus() != 0) {
                        $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $productInfo['quantity'] * $product->getBonus()) / 100);
                    } else {
                        $bonusAddUser = $bonusAddUser + round(($product->getPrice() * $productInfo['quantity'] * csSettings::get("persent_bonus_add")) / 100);
                    }
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price']) );
                    $tableOrder .= '<tbody>
                    <tr>
                        <td style="text-align: left;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    text-align: left;
    vertical-align: top;">                                <div style="float:left;margin: -10px 10px -10px 0"> <a href="http://onona.ru/product/' . $product->getSlug() . '"><img width="70" src="http://onona.ru/uploads/photo/thumbnails_250x250/' . $photos[0]->getFilename() . '" style="margin-right: 10px;"></a></div>

                            <a href="http://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a>
                        </td>
                        <td style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">' . $productInfo['price'] . '</td>
                        <td style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">' . ($productInfo['price_w_discount'] > 0 ? (round((1 - ($productInfo['price_w_discount'] / $productInfo['price'])) * 100)) : '0') . '</td>
                        <td class="count" style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;">

                            <div class="cartCount" style="" id="quantity_765">' . $productInfo['quantity'] . '</div>

                        </td>
                        <td style="text-align: center;   -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #DDDDDD;
    border-image: none;
    border-style: dashed solid;
    border-width: 1px;
    color: #414141;
    padding: 10px;
    vertical-align: top;"><div id="totalcost_765" style="display: inline-block;">' . ($productInfo['quantity'] * ($productInfo['price_w_discount'] > 0 ? $productInfo['price_w_discount'] : $productInfo['price'])) . '</div></td>
                    </tr>
                </tbody>';
                endforeach;
                //  print_r($products_old);
                $tableOrderHeader = '<table width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000" border="0" align="center" class="cartContent" style="margin-top: 10px;border-collapse: collapse;">
                <thead style="background-color: #F1F1F1;"><tr>
                        <th style="border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Наименование</th>
                        <th style=" width: 111px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Цена, руб.</th>
                        <th style=" width: 88px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Скидка, %</th>
                        <th style=" width: 110px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Кол-во</th>
                        <th style=" width: 108px;border: 1px solid #DDDDDD;
    color: #414141;
    font-weight: normal;
    height: 28px;
    text-align: center;">Сумма, руб.</th>
                    </tr>
                </thead>';
                $tableOrderFooter = "
		</table>";

                if (round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm) > $this->bonusCount) {
                    $bonusDropCost = $this->bonusCount;
                } else {
                    $bonusDropCost = round((csSettings::get('PERSENT_BONUS_PAY') / 100) * $TotalSumm);
                }

                $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('cart_isset');
                $mailTemplate->setText(str_replace('{nameCustomer}', $user['first_name'], $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText()));


                sfContext::createInstance($this->configuration);


                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($user['email_address'])
                        ->setSubject($mailTemplate->getSubject())
                        ->setBody($mailTemplate->getText())
                        ->setContentType('text/html');

                //$numSent = $this->getMailer()->send($message);

                $bonusLog2 = new MailsendLog();
                $bonusLog2->set("comment", "Письмо-напоминание о наличии товаров в корзине. <br>Последнее посещение: " . date("d.m.Y H:i:s", $session['sess_time']) . "<br>Почта: " . $user['email_address']);
            //$bonusLog2->save();
            endif;
        endforeach;
*/
        /*          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute('DELETE FROM  sessions where sess_data like \'%"user_id";s:5:"17101"%\'');
          //$result = $q->execute('SELECT * FROM  sessions where sess_time > (1483753948)');
          $result = $q->execute('SELECT * FROM  sessions where sess_data like \'%"user_id";s:5:"17101"%\' order by sess_time desc');
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          
        $users = $result->fetchAll();
          print_r($users);*/
        
        

        //echo "SELECT * FROM  `sf_guard_user` WHERE cart is not null and cart !='' and cart !='a:0:{}' and cart !='s:0:\"\";' and cart !='b:0;' and last_login > '" . date("Y-m-d H:i:s", (time() - 259200)) . "' and last_login < '" . date("Y-m-d H:i:s", (time() - 172800)) . "'";
    }

}
