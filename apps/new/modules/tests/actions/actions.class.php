<?php

/**
 * tests actions.
 *
 * @package    test
 * @subpackage tests
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testsActions extends sfActions {
  public function is_email($email) {
      //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
          //echo "Valid email address.";
          return true;
      } else {
          return false;
          //echo "Invalid email address.";
      }
  }
  public function executeQuiz(sfWebRequest $request) {
    $this->quiz = Doctrine_Core::getTable('Quiz')->findOneBySlug(array('chto-o-vas-rasskazhut-vashi-seksualnye-predpochteniya'));
  }

  public function executeQuizdone(sfWebRequest $request) {
    if($_POST['token'] != 'Роботы нам тут не нужны!'){
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    }
    if(!$_POST['name'])
      return $this->renderText(json_encode(['error' => 'Заполните имя', 'field' => 'name']));
    if(!$_POST['email'])
      return $this->renderText(json_encode(['error' => 'Заполните email', 'field' => 'email']));
    if(!$this->is_email($_POST['email']))
      return $this->renderText(json_encode(['error' => 'Укажите корректный email', 'field' => 'email']));
    $this->test = Doctrine_Core::getTable('Quiz')->findOneBySlug(array($request->getParameter('slug')));
    if(!is_object($this->test)){
      return $this->renderText(json_encode(['error' => 'Квиз не найден!']));
    }
    $ballsPrevPage = 0;
    if (isset($_POST['answer']))
      foreach ($_POST['answer'] as $answer)
        $ballsPrevPage += $answer;
    $this->result = QuizResultTable::getInstance()->createQuery()->where("quiz_id = ?", $this->test->getId())->addWhere("balls_to<=?", $ballsPrevPage)->addWhere("balls_from>=?", $ballsPrevPage)->fetchOne();
    // die('$ballsPrevPage='.$ballsPrevPage.(is_object($this->result) ? 'yes' : 'no'));
    // $this->test->setWriting($this->test->getWriting() + 1);
    // $this->test->save();

    $userIsSet = sfGuardUserTable::getInstance()->findOneByEmailAddress($request->getParameter('email'));

    if (!$userIsSet) {

      $user = new sfGuardUser();
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
      $user->setUsername($username);
      $password = uniqid();
      $user->set("password", $password);
      $user->setFirstName($username);
      $user->setEmailAddress($request->getParameter('email'));
      // $user->setSex($request->getParameter('user_sex'));

      $user->save();
      $this->getUser()->signin($user);

      $bonus = new Bonus();
      $bonus->setUserId($user);
      $bonus->setBonus(csSettings::get("register_bonus_add"));
      $bonus->setComment("Зачисление за подписку");
      $bonus->save();
      if ($this->is_email($user->getEmailAddress())) {
          $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('register');
          $message = Swift_Message::newInstance()
                  ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                  ->setTo($user->getEmailAddress())
                  ->setSubject(csSettings::get("theme_register_email"))
                  ->setBody(str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $password), $mailTemplate->getText()), 'text/html')
                  ->setContentType('text/html')
          ;
          try {
            $this->getMailer()->send($message);
          } catch (\Exception $e) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log_not_send_email.log', date('d.m.Y H:i').'|'.$user->getEmailAddress().'|'.str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $password), $mailTemplate->getText())."\n------------------------------------\n", FILE_APPEND);
            //Не отправили
          }
        }
    }
    else $user=$userIsSet;

      if ($this->is_email($user->getEmailAddress())) {
        $message = Swift_Message::newInstance()
                ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                ->setTo($user->getEmailAddress())
                ->setSubject('Ваш результат на сайте onona.ru')
                ->setBody('<h2>'.$this->result->getName().'</h2>
                <p>'.$this->result->getResults().'</p>
                <p><a href="'.$this->result->getLink().'">Посмотреть</a></p>'.
                (trim($this->result->getLink_1()) ? '<p><a href="'.$this->result->getLink_1().'">Посмотреть</a></p>' : '').
                (trim($this->result->getLink_2()) ? '<p><a href="'.$this->result->getLink_2().'">Посмотреть</a></p>' : '').
                '<p>Ваш промокод на скидку <strong>Qv20</strong>', 'text/html')
                ->setContentType('text/html')
        ;
        try {
          $this->getMailer()->send($message);
        } catch (\Exception $e) {
          file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log_not_send_email.log', date('d.m.Y H:i').'|'.$user->getEmailAddress().'|'.str_replace(array('{$login}', '{$password}'), array($user->getEmailAddress(), $password), $mailTemplate->getText())."\n------------------------------------\n", FILE_APPEND);
          //Не отправили
        }


        //Roistat Отправка данных в проксилид
        if(file_exists($_SERVER["DOCUMENT_ROOT"].'/roistat/proxilid.php')){
          include $_SERVER["DOCUMENT_ROOT"].'/roistat/proxilid.php';
          $roisData = array(
            'email'     => $request->getParameter('email'),
            'name'     => $request->getParameter('name'),
            'form_name' => 'Квиз',
          );
          proxilid($roisData);
        }
        //Roistat END
    }

    return $this->renderText(json_encode([
      'success' => $this->getPartial('quizdone', [
          'result' => $this->result,
          // 'url' => '/tests/'.$request->getParameter('slug'),
          // 'allTests' => $allTests
      ]),
      // 'initslider' => true
    ]));
  }

  public function executeIndex(sfWebRequest $request) {
    $this->pagesize=10;

    $this->pager = new sfDoctrinePager('tests', $this->pagesize);

    $query=Doctrine_Core::getTable('Tests')->createQuery('a')->where("is_public = 1");

     if (sfContext::getInstance()->getRequest()->getCookie('sortOrder') != "") {
        //$this->sortOrder = sfContext::getInstance()->getRequest()->getCookie('sortOrder');
    }
    if ($request->getParameter('sortOrder') != "") {
        sfContext::getInstance()->getResponse()->setCookie('sortOrder', $request->getParameter('sortOrder'));
        $this->sortOrder = $request->getParameter('sortOrder');
    }
    if (sfContext::getInstance()->getRequest()->getCookie('direction') != "") {
        //$this->direction = sfContext::getInstance()->getRequest()->getCookie('direction');
    }
    if ($request->getParameter('direction') != "") {
        sfContext::getInstance()->getResponse()->setCookie('direction', $request->getParameter('direction'));
        $this->direction = $request->getParameter('direction');
    }

    if ($this->sortOrder == "date") {
        if ($this->direction == "asc") {
            $query->addOrderBy("created_at asc");
        } else {
            $query->addOrderBy("created_at desc");
        }
    }

    if ($this->sortOrder == "rating") {
        if ($this->direction == "asc") {
            $query->addOrderBy("writing asc");
        } else {
            $query->addOrderBy("writing desc");
        }
    }

    $this->pager->setQuery($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

  }

  public function executeDone(sfWebRequest $request) {
    // global $isTest;
    if($_POST['token'] != 'Роботы нам тут не нужны!'){
      return $this->renderText(json_encode(['error' => 'Робот заблокирован']));
    }
    $this->test = Doctrine_Core::getTable('Tests')->findOneBySlug(array($request->getParameter('slug')));
    if(!is_object($this->test)){
      return $this->renderText(json_encode(['error' => 'Тест не найден!']));
    }
    $ballsPrevPage = 0;
    if (isset($_POST['answer']))
      foreach ($_POST['answer'] as $answer)
        $ballsPrevPage = $ballsPrevPage + $answer;
    // if($isTest) $ballsPrevPage=20;
    $this->result = TestsResultTable::getInstance()->createQuery()->where("test_id = ?", $this->test->getId())->addWhere("balls_to<=?", $ballsPrevPage)->addWhere("balls_from>=?", $ballsPrevPage)->fetchOne();

    $this->test->setWriting($this->test->getWriting() + 1);
    $this->test->save();
    $allTests= Doctrine_Core::getTable('Tests')->createQuery('a')->where("is_public = 1 and id <>".$this->test->getId())->execute();

    $this->allTests=$allTests;
    return $this->renderText(json_encode([
      'success' => $this->getPartial('done', [
          'result' => $this->result,
          'url' => '/tests/'.$request->getParameter('slug'),
          'allTests' => $allTests,
      ]),
      'target' => '.page-content',
    ]));


  }

  public function executeShow(sfWebRequest $request) {
    $this->test = Doctrine_Core::getTable('Tests')->findOneBySlug(array($request->getParameter('slug')));
    $this->forward404Unless($this->test);

    $this->pager = new sfDoctrinePager('tests', 50);

    $this->pager->setQuery(Doctrine_Core::getTable('TestsQuestion')->createQuery('a')->where("test_id = ?", $this->test->getId())->orderBy("number ASC"));
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    if(count($this->pager->getLinks(2000))<$request->getParameter('page', 1)) $this->forward404();
  }

  /* ********************************************** old **************************************************** */



    public function executeRate(sfWebRequest $request) {

        $test = Doctrine_Core::getTable('Tests')->findOneById(array($request->getParameter('testId')));
        $test->setRating($test->getRating() + $request->getParameter('value'));
        $test->setVotesCount($test->getVotesCount() + 1);
        $test->save();
        $this->getResponse()->setCookie("ratete_" . $test->getId(), 1, time() + 60 * 60 * 24 * 365, '/');
        $this->test = $test;
    }

}
