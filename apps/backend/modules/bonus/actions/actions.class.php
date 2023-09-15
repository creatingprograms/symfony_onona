<?php

require_once dirname(__FILE__) . '/../lib/bonusGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/bonusGeneratorHelper.class.php';

/**
 * bonus actions.
 *
 * @package    Magazin
 * @subpackage bonus
 * @author     Belfegor
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bonusActions extends autoBonusActions {

    protected function addSortQuery($query) {
        $query->addOrderBy('created_at DESC');
    }

    public function is_email($email) {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

    public function executePererbonusoffshop(sfWebRequest $request) {
        /* $bonus = BonusTable::getInstance()->createQuery()->where("comment like '%Зачисление за покупку в магазине%'")->execute();
          foreach($bonus as $bonu){
          echo $bonu->getBonus()."-".ceil($bonu->getBonus()*3.33333333333333333333333333333333333333333333333333333333333333333333333333333333334)."-".$bonu->getComment()."-".round((ceil($bonu->getBonus()*3.33333333333333333333333333333333333333333333333333333333333333333333333333333333334)*0.5))."<br>";
          $bonu->setBonus(round((ceil($bonu->getBonus()*3.33333333333333333333333333333333333333333333333333333333333333333333333333333333334)*0.5)));
          $bonu->save();


          }
          echo $bonus->count(); */
        return true;
    }

    public function executeFilter(sfWebRequest $request) {

        $this->setPage(1);

        if ($request->hasParameter('_reset')) {
            $this->setFilters($this->configuration->getFilterDefaults());

            $this->redirect('@bonus');
        }

        $this->filters = $this->configuration->getFilterForm($this->getFilters());

        $this->filters->bind($request->getParameter($this->filters->getName()));
        if ($this->filters->isValid()) {
            $this->setFilters($this->filters->getValues());



            $this->redirect('@bonus');
        }
        $this->pager = $this->getPager();
        $this->sort = $this->getSort();

        $this->setTemplate('index');
    }

    protected function buildQuery() {
        $tableMethod = $this->configuration->getTableMethod();
        if (null === $this->filters) {
            $this->filters = $this->configuration->getFilterForm($this->getFilters());
        }

        $this->filters->setTableMethod($tableMethod);


        $filters = $this->getFilters();
        if (isset($filters['user_id'])) {
            $users = sfGuardUserTable::getInstance()->createQuery()->where("id = '" . $filters['user_id'] . "' or email_address like '%" . $filters['user_id'] . "%'")->fetchArray();
            if (count($users) > 0) {
                $filters['user_id'] = array();
                foreach ($users as $user) {
                    $filters['user_id'][] = $user['id'];
                }
            } else
                $filters['user_id'] = "769853268540";
        }

        $query = $this->filters->buildQuery($filters);

        $this->addSortQuery($query);

        $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
        $query = $event->getReturnValue();

        return $query;
    }

    public function executeIndex(sfWebRequest $request) {
        if(isset($_GET['reset_filter'])) {
          $this->setFilters($this->configuration->getFilterDefaults());
        }
        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }


        //print_r($this->getFilters());

        $this->pager = $this->getPager();
        $this->sort = $this->getSort();
    }

    public function generate_password($number) {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0', '.', ',',
            '(', ')', '[', ']', '!', '?',
            '&', '^', '%', '@', '*', '$',
            '<', '>', '/', '|', '+', '-',
            '{', '}', '`', '~');
        // Генерируем пароль
        $pass = "";
        for ($i = 0; $i < $number; $i++) {
            // Вычисляем случайный индекс массива
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

    public function executeAdduser(sfWebRequest $request) {
        if ($request->isMethod('post')) {
            $this->log = "";


            $user = sfGuardUserTable::getInstance()->findOneBy("email_address", $request->getParameter("mail"));

            if ($user) {
                $bonus = new Bonus();
                $bonus->setBonus($request->getParameter("bonusCount"));
                $bonus->setComment($request->getParameter("comm"));
                $bonus->setUserId($user);
                $bonus->save();

                $this->log = $this->log . "<br />Пользователю " . $request->getParameter("mail") . " зачислены бонусы в размере " . $request->getParameter("bonusCount");
                //echo $this->is_email($dan[0]);exit;
                if ($request->getParameter("bonusCount") > 0 and $this->is_email($request->getParameter("mail"))) {
                    $message = Swift_Message::newInstance()
                            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                            ->setTo($request->getParameter("mail"))
                            ->setSubject('Уведомления о зачисление бонусов на сайте onona.ru'/* .$this->form->user->username */)
                            ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($user->getName(), "OnOna.ru", $request->getParameter("bonusCount"), csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html')
                    //->setContentType('text/html')
                    ;
                    try{
                      $this->getMailer()->send($message);
                    }
                    catch(Exception $e){
                      //Как обрабатывать - ХЗ
                    }
                }
            } else {
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
                $birthday = $request->getParameter("birthday");
                $user = new sfGuardUser();
                $user->set("email_address", $request->getParameter("mail"));
                $password = $this->generate_password(8);
                $user->set("password", $password);
                $user->set("username", $username);
                $user->set("first_name", $request->getParameter("firstname"));
                $user->set("last_name", $request->getParameter("lastname"));
                $user->set("city", $request->getParameter("city"));
                $user->set("phone", $request->getParameter("phone"));
                $user->set("birthday", $birthday['year'] . "-" . $birthday['month'] . "-" . $birthday['day']);
                $user->save();

                $bonus = new Bonus();
                $bonus->setBonus($request->getParameter("bonusCount"));
                $bonus->setComment($request->getParameter("comm"));
                $bonus->setUserId($user);
                $bonus->save();

                $this->log = $this->log . "<br />Пользователь " . $request->getParameter("mail") . " зарегистрирован и ему зачислены бонусы в размере " . $request->getParameter("bonusCount");
                if ($this->is_email($request->getParameter("mail"))) {
                    $message = Swift_Message::newInstance()
                            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                            ->setTo($request->getParameter("mail"))
                            ->setSubject('Регистрация на сайте onona.ru'/* .$this->form->user->username */)
                            ->setBody(str_replace(array('{$login}', '{$password}'), array($request->getParameter("mail"), $password), csSettings::get("templates_register")))
                    //->setContentType('text/html')
                    ;
                    try{
                      $this->getMailer()->send($message);
                    }
                    catch(Exception $e){
                      //Как обрабатывать - ХЗ
                    }
                }

                if ($request->getParameter("bonusCount") > 0 and $this->is_email($request->getParameter("mail"))) {
                    $message = Swift_Message::newInstance()
                            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru"/* sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com') */)
                            ->setTo($request->getParameter("mail"))
                            ->setSubject('Уведомления о зачисление бонусов на сайте onona.ru'/* .$this->form->user->username */)
                            ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($user->getName(), "OnOna.ru", $request->getParameter("bonusCount"), csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html')
                    //->setContentType('text/html')
                    ;

                    try{
                      $this->getMailer()->send($message);
                    }
                    catch(Exception $e){
                      //Как обрабатывать - ХЗ
                    }
                }
            }
            $this->getUser()->setAttribute('logAddBonus', $this->log);
            $this->redirect('bonus/bonusadd');

            /*
             * Вариант с несколькими почтами
             *
              foreach (explode("\r\n", $request->getParameter("bonus")) as $nach) {
              $dan = explode(";", $nach);
              $user = sfGuardUserTable::getInstance()->findOneBy("email_address", $dan[0]);
              $dan[1] = ($dan[1] != "" and isset($dan[1])) ? $dan[1] : $request->getParameter("bonusCount");
              print_r($dan);
              if ($user) {
              $bonus = new Bonus();
              $bonus->setBonus($dan[1]);
              $bonus->setComment($request->getParameter("comm"));
              $bonus->setUserId($user);
              $bonus->save();

              $this->log = $this->log . "<br />Пользователю " . $dan[0] . " зачислены бонусы в размере " . $dan[1];
              //echo $this->is_email($dan[0]);exit;
              if ($dan[1] > 0 and $this->is_email($dan[0])) {
              $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'))// sfConfig::get('app_sf_guard_plugin_default_from_email', 'from@noreply.com')
              ->setTo($dan[0])
              ->setSubject('Уведомления о зачисление бонусов на сайте onona.ru')
              ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($user->getName(), "OnOna.ru", $dan[1], csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html')
              //->setContentType('text/html')
              ;

              $this->getMailer()->send($message);
              }
              } else {
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
              $birthday = $request->getParameter("birthday");
              $user = new sfGuardUser();
              $user->set("email_address", $dan[0]);
              $password = rand(100000, 999999);
              $user->set("password", $password);
              $user->set("username", $username);
              $user->set("first_name", $request->getParameter("firstname"));
              $user->set("last_name", $request->getParameter("lastname"));
              $user->set("city", $request->getParameter("city"));
              $user->set("phone", $request->getParameter("phone"));
              $user->set("birthday", $birthday['year'] . "-" . $birthday['month'] . "-" . $birthday['day']);
              $user->save();

              $bonus = new Bonus();
              $bonus->setBonus($dan[1]);
              $bonus->setComment($request->getParameter("comm"));
              $bonus->setUserId($user);
              $bonus->save();

              $this->log = $this->log . "<br />Пользователь " . $dan[0] . " зарегистрирован и ему зачислены бонусы в размере " . $dan[1];
              if ($this->is_email($dan[0])) {
              $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'))
              ->setTo($dan[0])
              ->setSubject('Регистрация на сайте onona.ru')
              ->setBody(str_replace(array('{$login}', '{$password}'), array($dan[0], $password), csSettings::get("templates_register")))
              //->setContentType('text/html')
              ;
              $this->getMailer()->send($message);
              }

              if ($dan[1] > 0 and $this->is_email($dan[0])) {
              $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'))
              ->setTo($dan[0])
              ->setSubject('Уведомления о зачисление бонусов на сайте onona.ru')
              ->setBody(str_replace(array('{firstname}', '{shop}', '{bonus}', '{perbonus}', "\r\n"), array($user->getName(), "OnOna.ru", $dan[1], csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_add_bonus")), 'text/html')
              //->setContentType('text/html')
              ;

              $this->getMailer()->send($message);
              }
              }
              }
             */
        }
    }

    public function executeBonusadd(sfWebRequest $request) {
        $this->log = $this->getUser()->getAttribute('logAddBonus');
    }

}
