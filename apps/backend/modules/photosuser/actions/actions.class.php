<?php

require_once dirname(__FILE__) . '/../lib/photosuserGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/photosuserGeneratorHelper.class.php';

/**
 * photosuser actions.
 *
 * @package    test
 * @subpackage photosuser
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photosuserActions extends autoPhotosuserActions {

    public function executePublicChange() {
        $object = Doctrine::getTable('PhotosUser')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();

        if ($object->getIsPublic()) {

            $notifications = NotificationTable::getInstance()->createQuery()->where("type='UserPhoto'")->addWhere("product_id='" . $object->getProductId() . "'")->execute();

            $product = ProductTable::getInstance()->findOneById($object->getProductId());
            foreach ($notifications as $notification) {

                $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_userphoto');
                $UserNotification = $notification->getSfGuardUser();
                $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));
                $mailTemplate->setText(str_replace('{prodLink}', '<a href="http://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a>', $mailTemplate->getText()));

                $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();

                $prodDescription = '    <div style="  border: 1px solid #e0e0e0;
         width: 300px;
         height: 300px;
         /* margin: 5px; */
         cursor: pointer;
         text-align: center;
         display: inline-block;
         vertical-align: middle;
         float: left;
         position: relative">
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
                <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>' . round($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100)) . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость без скидки:</div>
                <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . $product->getPrice() . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Экономия:</div>
                <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() - round($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100)), 0, '', ' ') . ' р.</div>
            

    ';
                } elseif ($product->getBonuspay() > 0) {
                    $prodDescription .= '      <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость с учетом бонусов:</div>
        <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop="price">' . number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Полная стоимость:</div>
        <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . number_format($product->getPrice(), 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Оплата бонусами:</div>
        <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') . ' р.</div>

    ';
                } else {
                    $prodDescription .= '      <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость сегодня:</div>
                <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>' . number_format($product->getPrice(), 0, '', ' ') . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость при оплате бонусами:</div>
                <div style="float: left;"><span style="font-size: 16px; margin-right: 3px;text-decoration: underline;">' . number_format($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100, 0, '', ' ') . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Бонусы за покупку:</div>
                <div style="float: left;font-size: 16px; color: #414141;margin-right: 3px;">
                    <span style="text-decoration: underline;">' . round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) . ' р.
                    </span></div>
              
    ';
                }

                $prodDescription .= '<div style="clear:both">
        &nbsp;</div>
    <a href="http://onona.ru/product/' . $product->getSlug() . '" target="_blank" style="width: 299px;
    height: 40px;    
    background-image: url(\'http://onona.ru/images/newcat/imagesMail/button3.png\');
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
//echo $notification->getSfGuardUser()->getEmailAddress();exit;
                $numSent = $this->getMailer()->send($message);

                $mailLog = new MailsendLog();
                $mailLog->set("comment", "Письмо-уведомление о пользовательском фото к товару <br>Почта: " . $notification->getSfGuardUser()->getEmailAddress());
                $mailLog->save();
            }
            // $this->getMailer()->send($message); 
        }
        $this->photosUser = $object;
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $valuesForm = $form->getValues();
                if (!$form->getObject()->getIsPublic() and $valuesForm['is_public']) {
                    $notifications = NotificationTable::getInstance()->createQuery()->where("type='UserPhoto'")->addWhere("product_id='" . $form->getObject()->getProductId() . "'")->execute();
                    $product = ProductTable::getInstance()->findOneById($form->getObject()->getProductId());
                    foreach ($notifications as $notification) {

                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_userphoto');
                        $UserNotification = $notification->getSfGuardUser();
                        $mailTemplate->setText(str_replace('{username}', $UserNotification->getFirstName(), $mailTemplate->getText()));
                        $mailTemplate->setText(str_replace('{prodLink}', '<a href="http://onona.ru/product/' . $product->getSlug() . '">' . $product->getName() . '</a>', $mailTemplate->getText()));

                        $photos = PhotoTable::getInstance()->createQuery()->where("album_id=(select photoalbum_id from product_photoalbum where product_id=" . $product->getId() . " limit 0,1)")->orderBy("position")->execute();

                        $prodDescription = '    <div style="  border: 1px solid #e0e0e0;
         width: 300px;
         height: 300px;
         /* margin: 5px; */
         cursor: pointer;
         text-align: center;
         display: inline-block;
         vertical-align: middle;
         float: left;
         position: relative">
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
                <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>' . round($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100)) . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость без скидки:</div>
                <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . $product->getPrice() . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Экономия:</div>
                <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() - round($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100)), 0, '', ' ') . ' р.</div>
            

    ';
                        } elseif ($product->getBonuspay() > 0) {
                            $prodDescription .= '      <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость с учетом бонусов:</div>
        <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop="price">' . number_format($product->getPrice() - $product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Полная стоимость:</div>
        <div style="float: left;"><span style="font-size: 16px; color: #414141;text-decoration: line-through; margin-right: 10px;">' . number_format($product->getPrice(), 0, '', ' ') . ' р.</span></div>
        <div style="clear:both;  height: 10px;"></div>
        <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Оплата бонусами:</div>
        <div style="float: left;font-size: 16px; color: #414141;">' . number_format($product->getPrice() * $product->getBonuspay() / 100, 0, '', ' ') . ' р.</div>

    ';
                        } else {
                            $prodDescription .= '      <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость сегодня:</div>
                <div style="float: left;"><span style="font-size: 24px; color: #c3060e; margin-right: 10px;" itemprop=price>' . number_format($product->getPrice(), 0, '', ' ') . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Стоимость при оплате бонусами:</div>
                <div style="float: left;"><span style="font-size: 16px; margin-right: 3px;text-decoration: underline;">' . number_format($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100, 0, '', ' ') . ' р.</span></div>
                <div style="clear:both;  height: 10px;"></div>
                <div style="float: left;font-size: 11px; color: #707070;width:75px; margin-right: 10px;line-height: 11px;">Бонусы за покупку:</div>
                <div style="float: left;font-size: 16px; color: #414141;margin-right: 3px;">
                    <span style="text-decoration: underline;">' . round(($product->getPrice() - $product->getPrice() * ($product->getBonuspay() > 0 ? $product->getBonuspay() : csSettings::get('PERSENT_BONUS_PAY')) / 100) * (($product->getBonus() > 0 ? $product->getBonus() : csSettings::get('persent_bonus_add')) / 100)) . ' р.
                    </span></div>
              
    ';
                        }

                        $prodDescription .= '<div style="clear:both">
        &nbsp;</div>
    <a href="http://onona.ru/product/' . $product->getSlug() . '" target="_blank" style="width: 299px;
    height: 40px;    
    background-image: url(\'http://onona.ru/images/newcat/imagesMail/button3.png\');
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
//echo $notification->getSfGuardUser()->getEmailAddress();exit;
                        $numSent = $this->getMailer()->send($message);

                        $mailLog = new MailsendLog();
                        $mailLog->set("comment", "Письмо-уведомление о пользовательском фото к товару <br>Почта: " . $notification->getSfGuardUser()->getEmailAddress());
                        $mailLog->save();
                    }
                }
                $photos_user = $form->save();
            } catch (Doctrine_Validator_Exception $e) {

                $errorStack = $form->getObject()->getErrorStack();

                $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ? 's' : null) . " with validation errors: ";
                foreach ($errorStack as $field => $errors) {
                    $message .= "$field (" . implode(", ", $errors) . "), ";
                }
                $message = trim($message, ', ');

                $this->getUser()->setFlash('error', $message);
                return sfView::SUCCESS;
            }

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $photos_user)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@photos_user_new');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'photos_user_edit', 'sf_subject' => $photos_user));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }

    protected function addSortQuery($query) {
        if (@$_GET['sort'] == "")
            $query->addOrderBy('id DESC');
        else
            $query->addOrderBy($_GET['sort'] . ' ' . $_GET['sort_type']);
    }

}
