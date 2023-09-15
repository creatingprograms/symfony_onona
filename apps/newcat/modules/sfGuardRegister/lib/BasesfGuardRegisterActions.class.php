<?php

class BasesfGuardRegisterActions extends sfActions {

    protected function processFormMyData(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $page = $form->save();
            $this->update = true;
        }
    }

    public function is_email($email) {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

    public function executeIndex(sfWebRequest $request) {
        $user = $this->getUser();

        $deliveryId = $user->getAttribute('deliveryId');
        $paymentId = $user->getAttribute('paymentId');
        $comments = $user->getAttribute('comments');
        $coupon = $user->getAttribute('coupon');
        $timeCall = $user->getAttribute('timeCall');
        $deliveryPrice = $user->getAttribute('deliveryPrice');
        $payBonus = $user->getAttribute('payBonus');
        $pickpoint_id = $user->getAttribute('pickpoint_id');
        $pickpoint_address = $user->getAttribute('pickpoint_address');

        if ($this->getUser()->isAuthenticated()) {
            /*  if ($this->getUser()->getAttribute('deliveryId') == "1" or $this->getUser()->getAttribute('deliveryId') == "2" or $this->getUser()->getAttribute('deliveryId') == "9")
              $form = new sfGuardUserStep3KurerForm($user->getGuardUser());
              else
              $form = new sfGuardUserStep3Form($user->getGuardUser());


              $this->form = $form;
              $this->update = false;
              $this->setTemplate("mydata");

              if ($request->isMethod('post')) {
              $this->processFormMyData($request, $this->form);

              $this->getUser()->setAttribute('RegisterSuccessRedirect', true);

              $this->redirect('@processorder_confirmed');
              } */

            $this->getUser()->setFlash('notice', 'You are already registered and signed in!');

            $this->redirect('@homepage');
        } else {

            $this->form = new sfGuardRegisterForm();

            if ($this->getUser()->getAttribute('deliveryId') == "1" or $this->getUser()->getAttribute('deliveryId') == "2" or $this->getUser()->getAttribute('deliveryId') == "9")
                $this->form = new sfGuardRegisterKurerForm();
            if ($this->getUser()->getAttribute('deliveryId') == "3")
                $this->form = new sfGuardRegisterMailForm();

            $this->deliveryId = $deliveryId;

            if ($request->isMethod('post')) {

                $this->getUser()->setAttribute('RegisterSuccessRedirect', true);

                $formValueReg = $request->getParameter($this->form->getName());

                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                    $user = $this->form->save();
                    $this->getUser()->signIn($user);
                    if ($this->is_email($user->getEmailAddress())) {
                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('register');
                        $message = Swift_Message::newInstance()
                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                                ->setTo($user->getEmailAddress())
                                ->setSubject(csSettings::get("theme_register_email")/* .$this->form->user->username */)
                                ->setBody(str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $formValueReg['password']), $mailTemplate->getText()), 'text/html')
                                ->setContentType('text/html')
                        ;
                        //echo $user->getEmailAddress();
                        $this->getMailer()->send($message);
                    }

                    //if ($this->getUser()->getAttribute('deliveryId') == "") {
                        $bonus = new Bonus();
                        $bonus->setUserId($user);
                        $bonus->setBonus(csSettings::get("register_bonus_add"));
                        $bonus->setComment("Зачисление за регистрацию");
                        $bonus->save();
                    //}

                    if ($this->getUser()->getAttribute('deliveryId') != "") {
                        /* $user->setAttribute('deliveryId', $deliveryId);
                          $user->setAttribute('paymentId', $paymentId);
                          $user->setAttribute('comments', $comments);
                          $user->setAttribute('coupon', $coupon);
                          $user->setAttribute('timeCall', $timeCall);
                          $user->setAttribute('deliveryPrice', $deliveryPrice);
                          $user->setAttribute('payBonus', $payBonus);
                          $user->setAttribute('pickpoint_id', $pickpoint_id);
                          $user->setAttribute('pickpoint_address', $pickpoint_address); */
                        //$this->redirect('/frontend_dev.php/cart/processorder/confirmed');
                        //
                        //$this->redirect('@processorder_confirmed');

                        return $this->redirect('/spasibo-za-registraciu');
                    } else {
                        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
                        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';

                        if (is_array($this->products_old)) {
                            //return $this->redirect('/cart');
                            return $this->redirect('/spasibo-za-registraciu');
                        } else {
                            //return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
                            return $this->redirect('/spasibo-za-registraciu');
                        }
                        //$this->redirect('@homepage');
                        return $this->redirect('/spasibo-za-registraciu');
                    }
                }
            }
        }
    }

}