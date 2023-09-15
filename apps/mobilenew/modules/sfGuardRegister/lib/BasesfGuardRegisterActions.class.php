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
        if ($this->getUser()->isAuthenticated()) {

            $this->getUser()->setFlash('notice', 'You are already registered and signed in!');
            $this->redirect('@homepage');
        }


        $this->form = new sfGuardUserMobileForm();

        if ($request->isMethod('post')) {
            $formValueReg = $request->getParameter($this->form->getName());
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $user = $this->form->save();
                $this->getUser()->signIn($user);
                if (preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $user->getEmailAddress())) {
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
                $this->redirect('@homepage');
            }
        }
    }

}
