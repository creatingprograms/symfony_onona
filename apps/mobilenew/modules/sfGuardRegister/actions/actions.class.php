<?php

require_once dirname(__FILE__) . '/../lib/BasesfGuardRegisterActions.class.php';

/**
 * sfGuardRegister actions.
 *
 * @package    guard
 * @subpackage sfGuardRegister
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z jwage $
 */
class sfGuardRegisterActions extends BasesfGuardRegisterActions {

    public function executeRegss(sfWebRequest $request) {

        /*
         * 
         * 
         * 
         * <script src="//ulogin.ru/js/ulogin.js"></script>
          <div id="uLogin" data-ulogin="display=small;fields=first_name,last_name,email;optional=bdate,city;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fonona.ru%2Fregss"></div>
         */
        if ($this->getUser()->isAuthenticated()) {
            $this->getUser()->setFlash('notice', 'You are already registered and signed in!');

            $this->redirect('@homepage');
        } else {
            $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
            $user = json_decode($s, true);
            if (!empty($user) and ! isset($user['error'])) {
                $userSf = sfGuardUserTable::getInstance()->createQuery()->where("ssidentity=?", $user['identity'])->fetchOne();
                if (!$userSf) {

                    $userSfMail = sfGuardUserTable::getInstance()->createQuery()->where("email_address=?", $user['email'])->fetchOne();
                    if (!$userSfMail) {
                        $userSf = new sfGuardUser();
                        $userSf->set("ssidentity", $user['identity']);
                        $userSf->setEmailAddress($user['email']);
                        $userSf->setFirstName($user['first_name']);
                        $userSf->setLastName($user['last_name']);
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
                        $userSf->set("username", $username);
                        $bdate = explode(".", $user['bdate']);
                        $userSf->set("birthday", $bdate[2] . "-" . $bdate[1] . "-" . $bdate[0]);
                        //$password = rand(100000, 999999);

                        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
                        $max = 10;
                        $size = StrLen($chars) - 1;
                        $password = null;
                        while ($max--) {
                            $password.=$chars[rand(0, $size)];
                        }

                        $userSf->set("password", $password);
                        $userSf->set("city", $user['city']);
                        $userSf->save();

                        $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('register');
                        $message = Swift_Message::newInstance()
                                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                                ->setTo($userSf->getEmailAddress())
                                ->setSubject(csSettings::get("theme_register_email")/* .$this->form->user->username */)
                                ->setBody(str_replace(array('{$login}', '{$password}'), array($userSf->getEmailAddress(), $password), $mailTemplate->getText()), 'text/html')
                                ->setContentType('text/html');
                        $this->getMailer()->send($message);


                        $bonus = new Bonus();
                        $bonus->setUserId($userSf);
                        $bonus->setBonus(csSettings::get("register_bonus_add"));
                        $bonus->setComment("Зачисление за регистрацию через соц. сети");
                        $bonus->save();
                    } else {
                        $this->getUser()->setAttribute('emailReggSSAuth', $user['email']);
                        return $this->redirect('/regss_auth');
                    }
                }

                $this->getUser()->signIn($userSf);
                //$signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $this->getUser()->getReferer($request->getReferer()));

                return $this->redirect('' != $signinUrl ? $signinUrl : '/sexshop');
            } else {
                $this->getUser()->setFlash('notice', 'Нет данных!');

                $this->redirect('@homepage');
            }
            //$user['network'] - соц. сеть, через которую авторизовался пользователь
            //$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
            //$user['first_name'] - имя пользователя
            //$user['last_name'] - фамилия пользователя
        }
    }

}
