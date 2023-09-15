<?php

class getOrderShopTask extends sfBaseTask {

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
        $this->name = 'getOrderShop';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [getOrderShop|INFO] task does things.
Call it with:

  [php symfony getOrderShop|INFO]
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

        $host = "onona.ru";
        $userFTP = "prog1c";
        $passwordFTP = "ZDsY5yLxRX";










        $connect = ftp_connect($host);
        $result = ftp_login($connect, $userFTP, $passwordFTP);

        ftp_get($connect, "/var/www/ononaru/data/www/xml/cards.xml", "cards.xml", FTP_ASCII);

        ftp_quit($connect);

        $xml = simplexml_load_string(file_get_contents("/var/www/ononaru/data/www/xml/cards.xml"), 'SimpleXMLElement', LIBXML_NOCDATA);
        $phoneMail = 0;
        $cardMail = 0;
        $phoneMailNotReg = 0;
        $cardMailNotReg = 0;
        $countRegister = 0;
        $phoneMailNotRegNotValid = 0;
        $cardMailNotRegNotValid = 0;

        sfContext::createInstance($this->configuration);
        foreach ($xml->discount_cards->card as $card) {
            if ($countRegister < 100) {
                if ((string) $card->cardEmail != "" or (string) $card->EMail != "") {
                    if ((string) $card->cardEmail != "")
                        $mail = (string) $card->cardEmail;
                    else
                        $mail = (string) $card->EMail;

                    unset($phone);
                    if ((string) $card->cardphone != "")
                        $phone = (string) $card->cardphone;
                    else
                        $phone = (string) $card->phone;

                    unset($name);
                    if ((string) $card->ownername != "")
                        $name = (string) $card->ownername;
                    else
                        $name = (string) $card->owner_fullname;

                    if (substr_count($card->attributes()->discountcard, "!Телефон") > 0)
                        $phoneMail++;
                    else
                        $cardMail++;


                    $user = sfGuardUserTable::getInstance()->findOneByEmailAddress($mail);

                    if (!$user and filter_var((string) $mail, FILTER_VALIDATE_EMAIL)) {

                        $user = new sfGuardUser();
                        $username = 'user_' . rand(0, 9999999999999);
                        $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                        if ($isExistUserName->count() != 0) {
                            $username = 'user_' . rand(0, 9999999999999);
                            $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                            if ($isExistUserName->count() != 0) {
                                $username = 'user_' . rand(0, 9999999999999);
                                $isExistUserName = sfGuardUserTable::getInstance()->findByUsername($username);
                                if ($isExistUserName->count() != 0) {
                                    $username = 'user_' . rand(0, 9999999999999);
                                }
                            }
                        }
                        $user->setUsername($username);
                        $password = uniqid();
                        $user->set("password", $password);
                        $user->setFirstName($name);
                        $user->setEmailAddress((string) $mail);


                        $phone = preg_replace('/[^\d]/', '', $phone);
                        if (strlen($phone) == 11 and ( $phone[0] == 7 or $phone[0] == 8)) {

                            $phone = "+7(" . $phone[1] . $phone[2] . $phone[3] . ")" . $phone[4] . $phone[5] . $phone[6] . "-" . $phone[7] . $phone[8] . $phone[9] . $phone[10];
                        } elseif (strlen($phone) == 10 and $phone[0] != 8 and $phone[0] != 7) {
                            $phone = "+7(" . $phone[0] . $phone[1] . $phone[2] . ")" . $phone[3] . $phone[4] . $phone[5] . "-" . $phone[6] . $phone[7] . $phone[8] . $phone[9];
                        } else {
                            $phone = $phone;
                        }
                        $user->setPhone((string) $phone);
                        $user->save();


                        $bonus = new Bonus();
                        $bonus->setUserId($user);
                        $bonus->setBonus(csSettings::get("register_bonus_add"));
                        $bonus->setComment("Зачисление за регистрацию после покупки в магазине");
                        $bonus->save();


                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('register_card');
                        $message = Swift_Message::newInstance()
                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                ->setTo($user->getEmailAddress())
                                ->setSubject(csSettings::get("theme_register_email"))
                                ->setBody(str_replace(array('{$login}', '{$password}', '{$name}'), array($user->getEmailAddress(), $password, (string) $card->ownername), $mailTemplate->getText()), 'text/html')
                                ->setContentType('text/html')
                        ;
                        //echo $user->getEmailAddress();
                        $this->getMailer()->send($message);
                        sleep(1);
                        $countRegister++;

                        if (substr_count($card->attributes()->discountcard, "!Телефон") > 0)
                            $phoneMailNotReg++;
                        else
                            $cardMailNotReg++;
                    } elseif (!$user and (string) $mail != "") {


                        if (substr_count($card->attributes()->discountcard, "!Телефон") > 0)
                            $phoneMailNotRegNotValid++;
                        else
                            $cardMailNotRegNotValid++;
                    }
                    if ($user) {
                        $orders = OrdersTable::getInstance()->findByCustomerId($user->getId());

                        if (substr_count($card->attributes()->discountcard, "!Телефон") > 0)
                            $ordersCountPhone = $ordersCountPhone + $orders->count();
                        else
                            $ordersCountCard = $ordersCountCard + $orders->count();
                    }
                }
            } else {
                continue;
            }
        }

        $arrayStats['phoneMail'] = $phoneMail;
        $arrayStats['phoneMailNotReg'] = $phoneMailNotReg;
        $arrayStats['phoneMailNotRegNotValid'] = $phoneMailNotRegNotValid;
        $arrayStats['ordersCountPhone'] = $ordersCountPhone;
        $arrayStats['cardMail'] = $cardMail;
        $arrayStats['cardMailNotReg'] = $cardMailNotReg;
        $arrayStats['cardMailNotRegNotValid'] = $cardMailNotRegNotValid;
        $arrayStats['ordersCountCard'] = $ordersCountCard;

        $setting = csSettingTable::getInstance()->findOneBySlug("stats_card_and_phones");
        $setting->setValue(serialize($arrayStats));
        $setting->save();
        //echo $phoneMail."-".$phoneMailNotReg."-".$phoneMailNotRegNotValid."-".$cardMail."-".$cardMailNotReg."-".$cardMailNotRegNotValid;












/*
        ini_set("display_errors", 1);
        error_reporting(E_ALL);*/

        $connect = ftp_connect($host);
        $result = ftp_login($connect, $userFTP, $passwordFTP);

        ftp_get($connect, "/var/www/ononaru/data/www/xml/checks.xml", "checks.xml", FTP_ASCII);

        ftp_quit($connect);

        $xml = simplexml_load_string(file_get_contents("/var/www/ononaru/data/www/xml/checks.xml"), 'SimpleXMLElement', LIBXML_NOCDATA);

        sfContext::createInstance($this->configuration);
        foreach ($xml->checks->check as $order) {

            //echo strtotime((string) $order->attributes()->date);exit;
            //$orderShop = OrdersShopTable::getInstance()->findOneByDopid((string) $order->attributes()->id);
            $orderShop = OrdersShopTable::getInstance()->createQuery()->where('dopid=?', (string) $order->attributes()->id)->addwhere('checknumber=?', str_replace(" ", '', (string) $order->attributes()->number))->addwhere('price=?', (string) $order->attributes()->total_price)->fetchOne();
            if (!$orderShop) {
                $orderShop = new OrdersShop();
            }

            $prodsSetDb = array();
            foreach ($order->products->product as $product) {

                $productDb = ProductTable::getInstance()->findOneByCode((string) $product->attributes()->article);
                $prodSetDb = array();
                if ($productDb) {
                    $prodSetDb['productId'] = $productDb->getId();
                }
                $prodSetDb["quantity"] = (string) $product->attributes()->count;
                $prodSetDb["price"] = (string) $product->attributes()->price_w_discount;
                $prodSetDb["article"] = (string) $product->attributes()->article;
                $prodSetDb['title'] = trim((string) $product->title);
                $prodsSetDb[] = $prodSetDb;
            }
            $orderShop->set('text', serialize($prodsSetDb));


            if ($order->attributes()->email != "") {
                $findUserThisOrder = sfGuardUserTable::getInstance()->findOneByEmailAddress((string) $order->attributes()->email);
                if ($findUserThisOrder) {
                    //$bonus = BonusTable::getInstance()->createQuery()->where("comment like '%Зачисление за покупку в магазине. Номер чека " . str_replace(" ", '', (string) $order->attributes()->number) . ". Сумма: " . ((string) $order->attributes()->total_price) . "%'")->fetchOne();
                    if (/* !$bonus and */!$orderShop->getActive()) {
                        $total_price = (string) $order->attributes()->total_price;
                        $bonusCount = round(($total_price * csSettings::get("bonus_percent_shop")) / 100);
                        $bonusSet = new Bonus();
                        $bonusSet->setBonus($bonusCount);
                        $bonusSet->setComment("Зачисление за покупку в магазине. Номер чека " . str_replace(" ", '', (string) $order->attributes()->number) . ". Сумма: " . ((string) $order->attributes()->total_price) . "");
                        $bonusSet->setUserId($findUserThisOrder);
                        $bonusSet->save();

                        if (filter_var(trim((string) $order->attributes()->email), FILTER_VALIDATE_EMAIL)) {
                            $message = Swift_Message::newInstance()
                                    ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                    ->setTo(trim((string) $order->attributes()->email))
                                    ->setSubject("Уведомления о зачисление бонусов на сайте onona.ru")
                                    ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($findUserThisOrder->getName(), "OnOna.ru", $bonusCount, csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html')
                                    ->setContentType('text/html');


                            $numSent = $this->getMailer()->send($message);
                            sleep(1);
                        }

                        $orderShop->setActive(true);
                        $orderShop->setDateactive(date("Y-m-d H:i:s", time()));
                    }
                }
            }
            $orderShop->set('dopid', (string) $order->attributes()->id);
            $orderShop->set('date', (string) $order->attributes()->date);
            $orderShop->set('checknumber', str_replace(" ", '', (string) $order->attributes()->number));

            $orderShop->set('smena', (string) $order->attributes()->smena);
            $orderShop->set('discountcard', (string) $order->attributes()->discountcard);
            $orderShop->set('cardownername', (string) $order->attributes()->cardownername);
            $orderShop->set('cardowner', (string) $order->attributes()->cardowner);
            $orderShop->set('price', (string) $order->attributes()->total_price);
            $orderShop->save();
        }
    }

}
