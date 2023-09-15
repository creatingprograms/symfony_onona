<?php

class sendMailCardIssetTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'sendMailCardIsset';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [sendMailCardIsset|INFO] task does things.
Call it with:

  [php symfony sendMailCardIsset|INFO]
EOF;
    }

    static function is_email($email) {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

    protected function execute($arguments = array(), $options = array()) {
        exit;
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT * FROM  `sessions` WHERE  `sess_data` LIKE  '%productId%' and  `sess_data` LIKE  '%authenticated|b:1%' and sess_time > " . (time() - 259200) . " and sess_time < " . (time() - 172800));
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $sessions = $result->fetchAll();
        //echo count($sessions);
        foreach ($sessions as $session):
            $session_data = $session['sess_data'];
            $return_data = array();
            $offset = 0;
            while ($offset < strlen($session_data)) {
                if (!strstr(substr($session_data, $offset), "|")) {
                    throw new Exception("invalid data, remaining: " . substr($session_data, $offset));
                }
                $pos = strpos($session_data, "|", $offset);
                $num = $pos - $offset;
                $varname = substr($session_data, $offset, $num);
                $offset += $num + 1;
                $data = unserialize(substr($session_data, $offset));
                $return_data[$varname] = $data;
                $offset += strlen(serialize($data));
            }
            $sessionUser = $return_data;
            $userAtribute = $sessionUser['symfony/user/sfUser/attributes']['symfony/user/sfUser/attributes'];
            $products_old = $userAtribute['products_to_cart'];
            $products_old = $products_old != '' ? unserialize($products_old) : '';
            $userId = $sessionUser['symfony/user/sfUser/attributes']['sfGuardSecurityUser']['user_id'];

            $user = sfGuardUserTable::getInstance()->findOneById($userId);
            if ($this->is_email($user->getEmailAddress())):
                $tableOrder = "";
                $TotalSumm = 0;
                $bonusAddUser = 0;
                $this->bonus = BonusTable::getInstance()->findBy('user_id', $user->getId());
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
                $mailTemplate->setText(str_replace('{nameCustomer}', $user->getFirstName(), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText()));


                sfContext::createInstance($this->configuration);


                $message = Swift_Message::newInstance()
                        ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                        ->setTo($user->getEmailAddress())
                        ->setSubject($mailTemplate->getSubject())
                        ->setBody($mailTemplate->getText())
                        ->setContentType('text/html');

                $numSent = $this->getMailer()->send($message);
                
                            $bonusLog2 = new MailsendLog();
                            $bonusLog2->set("comment", "Письмо-напоминание о наличии товаров в корзине. <br>Последнее посещение: ".date("d.m.Y H:i:s",$session['sess_time'])."<br>Почта: ".$user->getEmailAddress());
                            $bonusLog2->save();
            endif;
        endforeach;
        // exit;
        // add your code here
    }

}
