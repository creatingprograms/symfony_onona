<?php

class productactionTask extends sfBaseTask {

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
        $this->name = 'productaction';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [productaction|INFO] task does things.
Call it with:

  [php symfony productaction|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        sfContext::createInstance($this->configuration);
        $productsAction = ProductTable::getInstance()->findByStartaction(date("Y-m-d"));
        foreach ($productsAction as $product) {
            if ($product->get('nextdiscount') != "" and ( $product->get('old_price') == "" or $product->get('old_price') == 0)) {

                $product->set('discount', $product->get('nextdiscount'));
                $product->set('old_price', $product->get('price'));
                $product->set('price', round($product->get('price') - ($product->get('price') * $product->get('discount') / 100)));
                $product->save();
            }
            unset($product);
        }



        $discountMatrix = array("5", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55", "60", "65", "70", "75", "80", "85", "90", "95");
        $stepMatrix = array('1 сутки', '2 суток', '3 суток');

        $manufacturers = ManufacturerTable::getInstance()->createQuery()->where('maxdiscount<>"" and countactionproduct<>""')->execute();
        foreach ($manufacturers as $manufacturer) {

            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $this->dopinfoProduct = $q->execute("SELECT product_id FROM dop_info_product WHERE dop_info_id=?", array($manufacturer->getSubid()))->fetchAll(Doctrine_Core::FETCH_UNIQUE);

            $query = Doctrine_Core::getTable('Product')
                    ->createQuery('p')
                    ->where("p.id in (" . implode(",", array_keys($this->dopinfoProduct)) . ")")
                    ->addWhere('parents_id IS NULL')
                    ->addWhere('is_public = \'1\'')
                    ->addWhere(/* 'step <> "" and Endaction <> ""' */'discount>0')
                    ->addOrderBy("count desc")
                    ->execute();
            //echo $query->get();

            if ($query->count() < $manufacturer->getCountactionproduct()) {
                $querycount = $query->count();
                $query = Doctrine_Core::getTable('Product')
                        ->createQuery('p')
                        ->where("p.id in (" . implode(",", array_keys($this->dopinfoProduct)) . ")")
                        ->addWhere('parents_id IS NULL')
                        ->addWhere('is_public = \'1\'')
                        ->addWhere('price> \'' . $manufacturer->getMinprice() . '\'')
                        ->addWhere('count> 1')
                        ->addWhere(/* '(step = "" or step is null) and *//* '(Endaction = "" or Endaction is null) ' */'(discount is null or discount=0)')
                        ->addOrderBy("count desc")
                        ->limit(($manufacturer->getCountactionproduct() - $query->count()) * 2)
                        ->execute();

                /*   if ($manufacturer->getSubid() == 712) {
                  echo Doctrine_Core::getTable('Product')
                  ->createQuery('p')
                  ->where("(".$prod.")")
                  ->addWhere('parents_id IS NULL')
                  ->addWhere('is_public = \'1\'')
                  ->addWhere('price> \'' . $manufacturer->getMinprice() . '\'')
                  ->addWhere('count> 1')
                  ->addWhere('(Endaction = "" or Endaction is null) ')
                  ->addOrderBy("count desc")
                  ->limit(($manufacturer->getCountactionproduct() - $query->count()) * 2);
                  exit;
                  } */

                if (count($query->getPrimaryKeys()) > 0)
                    $query = Doctrine_Core::getTable('Product')
                            ->createQuery('p')
                            ->where("id in (" . implode(",", $query->getPrimaryKeys()) . ")")
                            ->addOrderBy("rand()")
                            ->limit($manufacturer->getCountactionproduct() - $querycount)
                            ->execute();
                foreach ($query as $product) {

                    do {
                        $discount = $discountMatrix[rand(0, count($discountMatrix) - 1)];
                    } while ($discount > $manufacturer->getMaxdiscount());

                    $product->setDiscount($discount);
                    $product->setEndaction(date("Y-m-d", time() + rand(3, 7) * 86400));
                    $product->setStep($stepMatrix[rand(0, 2)]);

                    if ($product->get('old_price') == "" or $product->get('old_price') == 0) {
                        $product->set('old_price', $product->get('price'));
                        $product->set('price', round($product->get('price') - ($product->get('price') * $product->get('discount') / 100)));
                    } else {
                        $product->set('price', round($product->get('old_price') - ($product->get('old_price') * $product->get('discount') / 100)));
                    }
                    $product->save();


                    $notifications = NotificationTable::getInstance()->createQuery()->where("type='Action'")->addWhere("product_id='" . $product->getId() . "' or product_id is null")->execute();
                    foreach ($notifications as $notification) {

                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_action');
                        $mailTemplate->setText(str_replace('{username}', $notification->getSfGuardUser()->getFirstName(), $mailTemplate->getText()));
                        $mailTemplate->setText(str_replace('{prodLink}', '<a href="http://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a>', $mailTemplate->getText()));
                        $mailTemplate->setText(str_replace('{actionDescription}', ($product->getDiscount() > 0) ? '<a href="http://onona.ru/category/Luchshaya_tsena">Лучшая цена</a> - скидка ' . $product->getDiscount() . '%' : '<a href="http://onona.ru/category/upravlyai-cenoi">Управляй ценой</a> - оплата бонусами до ' . $product->getBonuspay() . '%', $mailTemplate->getText()));

                        $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();

                        $prodDescription = '    <div style="  border: 1px solid #e0e0e0;
         width: 300px;
         height: 300px;
         /* margin: 5px; */
         cursor: pointer;
         text-align: center;
         display: inline-block;
         vertical-align: middle;
         float: left;">
        <a href="http://onona.ru/product/' . $product->getSlug() . '"><img src="http://onona.ru/uploads/photo/' . $photos[0]->getFilename() . '" style="max-width: 300px; max-height: 300px;"></a>
    </div>
    <div style="  border: 0px solid #e0e0e0;
         width: 378px;
         margin-left: 20px;
         height: 300px;
         /* margin: 5px; */
         cursor: pointer;
         display: inline-block;
         vertical-align: middle;
         float: left;">
        <a href="http://onona.ru/product/' . $product->getSlug() . '"><span style="color: #c3060e;font: 14px/18px Tahoma, Geneva, sans-serif;margin-top: -4px;">' . $product->getName() . '</span></a><br />
<br />';
                        if ($product->getDiscount() > 0) {

                            $prodDescription .= '        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость сегодня:</div>
                <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>' . $product->getPrice() . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость без скидки:</div>
                <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . $product->getOldPrice() . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Экономия:</div>
                <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getOldPrice() - $product->getPrice(), 0, '', ' ') . ' р.</div>
            

    ';
                        } else {
                            $prodDescription .= '      <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость с учетом бонусов:</div>
        <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop="price">' . number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Полная стоимость:</div>
        <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . number_format($product->getPrice(), 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Оплата бонусами:</div>
        <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') . ' р.</div>

    ';
                        }

                        $prodDescription .= '<div style="clear:both">
        &nbsp;</div>
    <a href="http://onona.ru/product/' . $product->getSlug() . '" target="_blank" style="width: 211px;
    height: 40px;    
    background-image: url(\'http://onona.ru/images/newcat/imagesMail/button1.png\');
    margin: auto;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    text-align: center;
    display: block;"></a>
    </div>';

                        $mailTemplate->setText(str_replace('{prodDescription}', $prodDescription, $mailTemplate->getText()));
                        /* $mailTemplate->setText(str_replace('{nameCustomer}', $user->getFirstName(), $mailTemplate->getText()));
                          $mailTemplate->setText(str_replace('{bonusCustomer}', $this->bonusCount, $mailTemplate->getText()));
                          $mailTemplate->setText(str_replace('{summOrder}', $TotalSumm, $mailTemplate->getText()));
                          $mailTemplate->setText(str_replace('{bonusPayOrder}', ($bonusDropCost > 0 ? $bonusDropCost : 0), $mailTemplate->getText()));
                          $mailTemplate->setText(str_replace('{endPriceOrder}', ($TotalSumm) - $bonusDropCost, $mailTemplate->getText()));
                          $mailTemplate->setText(str_replace('{bonusCreateOrder}', $bonusAddUser, $mailTemplate->getText()));
                          $mailTemplate->setText(str_replace('{tableOrder}', $tableOrderHeader . $tableOrder . $tableOrderFooter, $mailTemplate->getText())); */


                        $message = Swift_Message::newInstance()
                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                                ->setTo($notification->getSfGuardUser()->getEmailAddress())
                                ->setSubject($mailTemplate->getSubject())
                                ->setBody($mailTemplate->getText())
                                ->setContentType('text/html');

                        $numSent = $this->getMailer()->send($message);

                        $mailLog = new MailsendLog();
                        $mailLog->set("comment", "Письмо-уведомление о акции на товар <br>Почта: " . $notification->getSfGuardUser()->getEmailAddress());
                        $mailLog->save();
                    }


                    $productLog = new ProductActionLog();
                    $productLog->set("prodid", $product->getId());
                    $productLog->set("manid", $manufacturer->getId());
                    $productLog->set("count", $product->getCount());
                    $productLog->set("discount", $product->getDiscount());
                    $productLog->set("endaction", $product->getEndaction());
                    $productLog->set("step", $product->getStep());
                    $productLog->save();
                    //echo $product->getStep();
                    //echo date("m",time()+ran(3,7)*86400);
                }
            }
        }

        //select id FROM `product` where parents_id in (SELECT id FROM `product` where code is null)
        /* $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("select id FROM `product` where parents_id in (SELECT id FROM `product` where code is null)");

          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $result = $result->fetchAll();
          if (count($result) > 0) {
          foreach ($result as $filter) {
          $productProbe = ProductTable::getInstance()->findOneById($filter['id']);
          echo $productProbe->getId();
          $productProbe->set("parents_id", null);
          $productProbe->save();
          }
          $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute("DELETE FROM product WHERE code is NULL");

          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $result = $result->fetchAll();
          } */
        sleep(30);
        exec("./symfony cc", $test);
        //echo $discount;
        // add your code here*/
    }

}
