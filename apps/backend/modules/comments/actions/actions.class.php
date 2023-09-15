<?php

require_once dirname(__FILE__) . '/../lib/commentsGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/commentsGeneratorHelper.class.php';

/**
 * comments actions.
 *
 * @package    Magazin
 * @subpackage comments
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commentsActions extends autoCommentsActions {

    protected function buildQuery() {
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters) {
            $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);


        $filters = $this->getFilters();
        if (isset($filters['customer_id'])) {
            $users = sfGuardUserTable::getInstance()->createQuery()->where("id = '" . $filters['customer_id'] . "' or email_address like '%" . $filters['customer_id'] . "%'")->fetchArray();
            if (count($users) > 0) {
                $filters['customer_id'] = array();
                foreach ($users as $user) {
                    $filters['customer_id'][] = $user['id'];
                }
            } else
                $filters['customer_id'] = "769853268540";
        }

        $query = $this->filters->buildQuery($filters);

        $this->addSortQuery($query);

        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();

        return $query;
    }

    public function executePublicChange() {
        $object = Doctrine::getTable('Comments')->findOneById($this->getRequestParameter('id'));

        $object->setIsPublic($object->getIsPublic() ? 0 : 1);
        $object->save();

        if ($object->getIsPublic()) {

            $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('comment_success');
            $mailTemplate->setText(str_replace('http://onona.ru/', "link", $mailTemplate->getText()));

            if ($object->getMail() != "") {
                $mailUser = $object->getMail();
            } elseif ($object->getSfGuardUser()) {

                $mailUser = $object->getSfGuardUser()->getEmailAddress();
            }
            $message = Swift_Message::newInstance()
                    ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                    ->setTo($mailUser)
                    ->setSubject($mailTemplate->getSubject())
                    ->setBody($mailTemplate->getText())
                    ->setContentType('text/html');
            if ($object->getProductId() != "") {
                $notifications = NotificationTable::getInstance()->createQuery()->where("type='Comment'")->addWhere("product_id='" . $object->getProductId() . "'")->execute();

                $product = ProductTable::getInstance()->findOneById($object->getProductId());
                foreach ($notifications as $notification) {

                            $UserNotification = $notification->getSfGuardUser();
                    $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_comment');
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
    <a href="http://onona.ru/product/' . $product->getSlug() . '" target="_blank" style="width: 276px;
    height: 40px;    
    background-image: url(\'http://onona.ru/images/newcat/imagesMail/button2.png\');
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
                    $mailLog->set("comment", "Письмо-уведомление о коментарии к товару <br>Почта: " . $notification->getSfGuardUser()->getEmailAddress());
                    $mailLog->save();
                }
            }
            // $this->getMailer()->send($message); 
        }
        $this->comments = $object;
    }

    protected function addSortQuery($query) {
        $query->addOrderBy('created_at DESC');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';

            try {
                $valuesForm = $form->getValues();
                if (!$form->getObject()->getIsPublic() and $valuesForm['is_public'] and $form->getObject()->getProductId() != "") {

                    $product = ProductTable::getInstance()->findOneById($form->getObject()->getProductId());
                    $notifications = NotificationTable::getInstance()->createQuery()->where("type='Comment'")->addWhere("product_id='" . $form->getObject()->getProductId() . "'")->execute();
                    foreach ($notifications as $notification) {

                            $UserNotification = $notification->getSfGuardUser();
                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('notification_comment');
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
    <a href="http://onona.ru/product/' . $product->getSlug() . '" target="_blank" style="width: 276px;
    height: 40px;    
    background-image: url(\'http://onona.ru/images/newcat/imagesMail/button2.png\');
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
                        $mailLog->set("comment", "Письмо-уведомление о коментарии к товару <br>Почта: " . $notification->getSfGuardUser()->getEmailAddress());
                        $mailLog->save();
                    }
                }
                $comments = $form->save();
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

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $comments)));

            if ($request->hasParameter('_save_and_add')) {
                $this->getUser()->setFlash('notice', $notice . ' You can add another one below.');

                $this->redirect('@comments_new');
            } else {
                $this->getUser()->setFlash('notice', $notice);

                $this->redirect(array('sf_route' => 'comments_edit', 'sf_subject' => $comments));
            }
        } else {
            $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
        }
    }

}
