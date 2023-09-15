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

    public function __construct($context, $moduleName, $controllerName) {
        parent::__construct($context, $moduleName, $controllerName);

        $this->csrf = new CSRFToken($this);
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
	      if (!$this->csrf->isValidKey('_csrf_token', $_POST['sf_guard_user'])) {
          return $this->renderText(json_encode(['error' => 'некорректный CSRF-токен']));
        }

        // die(print_r($_POST, true));
        if($_POST['token'] != 'Роботы нам тут не нужны!')
          return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
        if(!$_POST['agreement'])
          return $this->renderText(json_encode(['error' => 'Необходимо принять пользовательское соглашение', 'field' => 'agreement']));
        if(!$_POST['sf_guard_user']['password'] || $_POST['sf_guard_user']['password']<>$_POST['sf_guard_user']['password_again'])
          return $this->renderText(json_encode(['error' => 'Неверный пароль', 'field' => 'password']));
        if(!$this->is_email($_POST['sf_guard_user']['email_address']))
          return $this->renderText(json_encode(['error' => 'Некорректная почта', 'field' => 'sf_guard_user[email_address]']));
        $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($_POST['sf_guard_user']['email_address']));
        if(!!$user)
          return $this->renderText(json_encode(['error' => '<p>Данная почта уже используется. Если она принадлежит Вам - <a href="/guard/forgot_password">восстановите</a> свой пароль</p>']));
        if(!$_POST['sf_guard_user']['first_name'])
          return $this->renderText(json_encode(['error' => 'Имя не может быть пустым', 'field' => 'sf_guard_user[first_name]']));
        if(!$_POST['sf_guard_user']['last_name'])
          return $this->renderText(json_encode(['error' => 'Фамилия не может быть пустой', 'field' => 'sf_guard_user[last_name]']));
        if(!$_POST['sf_guard_user']['phone'])
          return $this->renderText(json_encode(['error' => 'Телефон не может быть пустым', 'field' => 'sf_guard_user[phone]']));
        if(!$_POST['sf_guard_user']['city'])
          return $this->renderText(json_encode(['error' => 'Город не может быть пустым', 'field' => 'sf_guard_user[city]']));
        if(!$_POST['sf_guard_user']['street'])
          return $this->renderText(json_encode(['error' => 'Город не может быть пустым', 'field' => 'sf_guard_user[street]']));

        $user = new sfGuardUser();
        $user->set("password", $_POST['sf_guard_user']['password']);
        $user->setUsername($this->prepareField($_POST['sf_guard_user']['email_address']));
        $user->setFirstName($this->prepareField($_POST['sf_guard_user']['first_name']));
        $user->setLastName($this->prepareField($_POST['sf_guard_user']['last_name']));
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
        $user->setCity($this->prepareField($_POST['sf_guard_user']['city']));
        $user->setStreet($this->prepareField($_POST['sf_guard_user']['street']));
        $user->setHouse($this->prepareField($_POST['sf_guard_user']['house']));
        $user->setApartament($this->prepareField($_POST['sf_guard_user']['apartament']));
        $user->setKorpus($this->prepareField($_POST['sf_guard_user']['korpus']));
        $user->setSex($this->prepareField($_POST['sf_guard_user']['sex']));
        $user->set("index_house", $_POST['sf_guard_user']['index_house']);
        // die('<pre>'.print_r($_POST, true));
        $user->save();
        //Запилили пользователя
        $this->getUser()->signin($user);

        $newBonusActive = new Bonus();
        $newBonusActive->setUserId($user->getId());
        $newBonusActive->setBonus(300);
        $newBonusActive->setComment('Зачисление за регистрацию');
        $newBonusActive->save();



        return $this->renderText(json_encode(['success' => 'Вы успешно зарегистрированы', 'redirect' => '/lk', 'caltat_user' => $user->getId()]));

      }
      $this->page=PageTable::getInstance()->findOneBySlug("tekst-stranicy-registracii");
    }

}
