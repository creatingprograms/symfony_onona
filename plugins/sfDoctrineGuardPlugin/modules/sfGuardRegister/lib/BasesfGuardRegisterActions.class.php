<?php

class BasesfGuardRegisterActions extends sfActions {

    protected function processFormMyData(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $page = $form->save();
            $this->update = true;
        }
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
            if ($this->getUser()->getAttribute('deliveryId') == "1" or $this->getUser()->getAttribute('deliveryId') == "2" or $this->getUser()->getAttribute('deliveryId') == "9")
                $form = new sfGuardUserStep3KurerForm($user->getGuardUser());
            else
                $form = new sfGuardUserStep3Form($user->getGuardUser());

            //$form->setDefault('password', '');
            //$form->setWidget('password', new sfWidgetFormInputPassword(array('label' => 'Пароль(заполняйте, если хотите изменить)')));

            $this->form = $form;
            $this->update = false;
            $this->setTemplate("mydata");

            if ($request->isMethod('post')) {
                $this->processFormMyData($request, $this->form);

                $user->setAttribute('deliveryId', $deliveryId);
                $user->setAttribute('paymentId', $paymentId);
                $user->setAttribute('comments', $comments);
                $user->setAttribute('coupon', $coupon);
                $user->setAttribute('timeCall', $timeCall);
                $user->setAttribute('deliveryPrice', $deliveryPrice);
                $user->setAttribute('payBonus', $payBonus);
                $user->setAttribute('pickpoint_id', $pickpoint_id);
                $user->setAttribute('pickpoint_address', $pickpoint_address);

                $this->getUser()->setAttribute('RegisterSuccessRedirect', true);

                //$this->redirect('/frontend_dev.php/cart/processorder/confirmed');

                $this->redirect('@processorder_confirmed');
            }
            /*
              $this->getUser()->setFlash('notice', 'You are already registered and signed in!');
              if ($this->getUser()->getAttribute('deliveryId') != "") {
              $this->redirect('@processorder_confirmed');
              } else {
              $this->redirect('@homepage');
              } */
        } else {

            $this->form = new sfGuardRegisterForm();

            if ($this->getUser()->getAttribute('deliveryId') == "1" or $this->getUser()->getAttribute('deliveryId') == "2" or $this->getUser()->getAttribute('deliveryId') == "9")
                $this->form = new sfGuardRegisterKurerForm();
            if ($this->getUser()->getAttribute('deliveryId') == "3")
                $this->form = new sfGuardRegisterMailForm();

            $this->deliveryId = $deliveryId;

            if ($request->isMethod('post')) {

                $this->getUser()->setAttribute('RegisterSuccessRedirect', true);

                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                    $user = $this->form->save();
                    $this->getUser()->signIn($user);

                    if ($this->getUser()->getAttribute('deliveryId') == "") {
                        $bonus = new Bonus();
                        $bonus->setUserId($user);
                        $bonus->setBonus(csSettings::get("register_bonus_add"));
                        $bonus->setComment("Зачисление за регистрацию");
                        $bonus->save();
                    }

                    if ($this->getUser()->getAttribute('deliveryId') != "") {
                        $user->setAttribute('deliveryId', $deliveryId);
                        $user->setAttribute('paymentId', $paymentId);
                        $user->setAttribute('comments', $comments);
                        $user->setAttribute('coupon', $coupon);
                        $user->setAttribute('timeCall', $timeCall);
                        $user->setAttribute('deliveryPrice', $deliveryPrice);
                        $user->setAttribute('payBonus', $payBonus);
                        $user->setAttribute('pickpoint_id', $pickpoint_id);
                        $user->setAttribute('pickpoint_address', $pickpoint_address);
                        //$this->redirect('/frontend_dev.php/cart/processorder/confirmed');
                        $this->redirect('@processorder_confirmed');
                    } else {
                        $this->products_old = $this->getUser()->getAttribute('products_to_cart');
                        $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';

                        if (is_array($this->products_old)) {
                            return $this->redirect('/cart');
                        } else {
                            return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
                        }
                        $this->redirect('@homepage');
                    }
                }
            }
        }
    }

}