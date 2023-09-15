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

    public function executeAjaxLogin(sfWebRequest $request) {//Пытаемся авторизоваться
      if ($this->getUser()->isAuthenticated()) {
      return $this->renderText(json_encode(['success' => 'Вы уже автозизованы'/*, 'redirect' => '/lk', 'caltat_user' => $user->getId()*/]));
      }
      if ($request->isMethod('post')){
        // die(print_r($_POST, true));
        if($_POST['token'] != 'Роботы нам тут не нужны!')
          return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
        if(!$_POST['password'])
          return $this->renderText(json_encode(['error' => 'Неверный пароль', 'field' => 'password', 'server'=>$_SERVER]));
        if(!$_POST['username'])
          return $this->renderText(json_encode(['error' => 'Не указан логин', 'field' => 'username']));
        $user=sfGuardUserTable::getInstance()->findOneByUsername($_POST['username']);
        if(!is_object($user))
          $user=sfGuardUserTable::getInstance()->findOneByEmailAddress($_POST['username']);
        if(!is_object($user))
          return $this->renderText(json_encode(['error' => 'Пользователь не найден', 'field' => 'username']));
        if(!$user->checkPassword(trim($_POST['password'])))
          return $this->renderText(json_encode(['error' => 'Пользователь не найден', 'field' => 'username']));

        $this->getUser()->signIn($user);
      return $this->renderText(json_encode(['success' => 'Вы автозизованы', 'redirect' => 'same', 'ym_target' => 'login'/*, 'caltat_user' => $user->getId()*/]));
      }
      $this->forward404Unless(false);

    }
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

    private function prepareField($text){
      return htmlspecialchars(trim($text));
    }

    public function is_email($email) {
      //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
      } else {
        return false;
      }
    }

    public function executeIndex(sfWebRequest $request){

      if ($request->isMethod('post')){
        // die(print_r($_POST, true));
        if($_POST['token'] != 'Роботы нам тут не нужны!')
          return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
        if(!$_POST['agreement'])
          return $this->renderText(json_encode(['error' => 'Необходимо принять пользовательское соглашение', 'field' => 'agreement']));
        if(!$_POST['sf_guard_user']['password'] || $_POST['sf_guard_user']['password']<>$_POST['sf_guard_user']['password_again'])
          return $this->renderText(json_encode(['error' => 'Неверный пароль', 'field' => 'sf_guard_user[password_again]']));
        if(empty($_POST['sf_guard_user']['username']))
          return $this->renderText(json_encode(['error' => 'Некорректный логин', 'field' => 'sf_guard_user[username]']));
        
        $user = sfGuardUserTable::getInstance()->findOneByUsername(trim($_POST['sf_guard_user']['username']));
        if(!!$user)
          return $this->renderText(json_encode(['error' => '<p>Логин уже используется. Если он принадлежит Вам - <a href="/guard/forgot_password">восстановите</a> свой пароль</p>', 'field' => 'alert']));

        if(!$this->is_email($_POST['sf_guard_user']['email_address']))
          return $this->renderText(json_encode(['error' => 'Некорректная почта', 'field' => 'sf_guard_user[email_address]']));

        $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($_POST['sf_guard_user']['email_address']));
        if(!!$user)
          return $this->renderText(json_encode(['error' => '<p>Данная почта уже используется. Если она принадлежит Вам - <a href="/guard/forgot_password">восстановите</a> свой пароль</p>', 'field' => 'alert']));

        // if(!$_POST['sf_guard_user']['first_name'])
        //   return $this->renderText(json_encode(['error' => 'Имя не может быть пустым', 'field' => 'sf_guard_user[first_name]']));
        // if(!$_POST['sf_guard_user']['last_name'])
        //   return $this->renderText(json_encode(['error' => 'Фамилия не может быть пустой', 'field' => 'sf_guard_user[last_name]']));
        if(!$_POST['sf_guard_user']['phone'])
          return $this->renderText(json_encode(['error' => 'Телефон не может быть пустым', 'field' => 'sf_guard_user[phone]']));

        $phone = ILTools::cleanPhone($_POST['sf_guard_user']['phone']);
        if ('on' == csSettings::get("red_sms_active")) {
          $current = Doctrine_Core::getTable('SmsProof')->findOneByPhone($phone);

          if (
            empty($current) || $current->getUpdatedAt() < date('Y-m-d H:i:s', time() - 24 * 60 * 60) || !empty($current->getText())
          ) {
            return $this->renderText(json_encode(['error' => 'Подтвердите телефон', 'field' =>'create_proof', 'proofType' => 'reg']));
          }
        }
        // if(!$_POST['sf_guard_user']['city'])
        //   return $this->renderText(json_encode(['error' => 'Город не может быть пустым', 'field' => 'sf_guard_user[city]']));
        // if(!$_POST['sf_guard_user']['street'])
        //   return $this->renderText(json_encode(['error' => 'Улица не может быть пустым', 'field' => 'sf_guard_user[street]']));

        $user = new sfGuardUser();
        $user->set("password", $_POST['sf_guard_user']['password']);
        $user->setUsername($this->prepareField($_POST['sf_guard_user']['username']));
        if(!empty($_POST['sf_guard_user']['first_name'])) $user->setFirstName($this->prepareField($_POST['sf_guard_user']['first_name']));
        if(!empty($_POST['sf_guard_user']['last_name'])) $user->setLastName($this->prepareField($_POST['sf_guard_user']['last_name']));
        $user->setEmailAddress($this->prepareField($_POST['sf_guard_user']['email_address']));
        $user->setPhone($this->prepareField($_POST['sf_guard_user']['phone']));
        $birthdate=strtotime(
          $this->prepareField(
            $_POST['sf_guard_user']['birthday']['year'].'-'.
            $_POST['sf_guard_user']['birthday']['month'].'-'.
            $_POST['sf_guard_user']['birthday']['day']
          )
        );
        if($birthdate){
          // $birthdate=implode('-', array_reverse(explode('.',$birthdate)));
          $user->setBirthday(date('Y-m-d', $birthdate));
        }
        if(!empty($_POST['sf_guard_user']['city'])) $user->setCity($this->prepareField($_POST['sf_guard_user']['city']));
        if(!empty($_POST['sf_guard_user']['street'])) $user->setStreet($this->prepareField($_POST['sf_guard_user']['street']));
        if(!empty($_POST['sf_guard_user']['house'])) $user->setHouse($this->prepareField($_POST['sf_guard_user']['house']));
        if(!empty($_POST['sf_guard_user']['apartament'])) $user->setApartament($this->prepareField($_POST['sf_guard_user']['apartament']));
        if(!empty($_POST['sf_guard_user']['korpus'])) $user->setKorpus($this->prepareField($_POST['sf_guard_user']['korpus']));
        if(!empty($_POST['sf_guard_user']['sex'])) $user->setSex($this->prepareField($_POST['sf_guard_user']['sex']));
        if(!empty($_POST['sf_guard_user']['index_house'])) $user->set("index_house", $_POST['sf_guard_user']['index_house']);
        // die('<pre>'.print_r($_POST, true));
        $user->save();
        //Запилили пользователя
        $this->getUser()->signin($user);

        $newBonusActive = new Bonus();
        $newBonusActive->setUserId($user->getId());
        $newBonusActive->setBonus(csSettings::get('register_bonus_add'));
        $newBonusActive->setComment('Зачисление за регистрацию');
        $newBonusActive->save();



        return $this->renderText(json_encode(['success' => 'Вы успешно зарегистрированы', 'redirect' => '/lk', 'caltat_user' => $user->getId(), 'ym_target' => 'registration']));

      }
      $this->page=PageTable::getInstance()->findOneBySlug("tekst-stranicy-registracii");
    }

}
