<?php

/**
 * Интеграция с cardsmobile
 *
 * @package    test
 * @subpackage cardsmobile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cardsmobileActions extends sfActions {
  const USERNAME='cardsmobile';
  const PASSWORD='GHJj34;x_&b0';

  public function executeGetCard(sfWebRequest $request) {
    /*
    /cardsmobile/v1/card
    Поиск карты лояльности по данным профиля пользователя
    ?msisdn=79216668899&email=sample@domain.com&birthDate=1990-01-01
    */

    $this->getAuth();
    $number=$request->getParameter('msisdn', false);
    $email=$request->getParameter('email', false);
    $number=$this->formatPhone($number);
    if(!$number && !$email) $this->showError('incorrect_phone_email');
    try {//получаем карту
      $card=Doctrine_Core::getTable('Cardsmobile')->findOneByPhone($number);
    } catch (\Exception $e) {
      $this->showError('database_error');
    }
    if(!$card){//если карта не найдена по телефону - ищем по email
      try {//получаем карту
        $card=Doctrine_Core::getTable('Cardsmobile')->findOneByEmail($email);
      } catch (\Exception $e) {
        $this->showError('database_error');
      }
    }
    if(!$card){
      $this->showResponse('success', 'empty');//если карта не найдена
    }
    else{
      $this->showResponse('success', [
        'card' =>[
          'cardNumber' => $card->getId(),
          'cardState' => $card->getIsPublic() ? 'active' : 'inactive',
          'barcode'=>[
            'barcodeNumber' => $card->getBarcode(),
            'barcodeType' => 'EAN_13',
          ],
        ]
      ]);//если карта найдена
    }
  }

  public function executeCardGetHistory(sfWebRequest $request) {
    /*
      /cardsmobile/v1/card/:num/purchases
      История покупок по карте
    */
    $this->getAuth();
    $number=$request->getParameter('num', false);
    if(!$number) $this->showError('card_number_needed');

    $startDate=$request->getParameter('startDate', false);
    if(!$startDate) $this->showError('start_date_needed');

    $endDate=$request->getParameter('endDate', false);

    try {//получаем карту
      $card=Doctrine_Core::getTable('Cardsmobile')->findOneById($number);
    } catch (\Exception $e) {
      $this->showError('database_error');
    }
    $sqlBody="SELECT `id`, `text`, `created_at`, `bonuspay` FROM `orders` "
      ."WHERE `customer_id`=".$card->getUserId()." "
        ."AND `created_at` >= '$startDate 00:00' "
        .($endDate ? "AND `created_at`<='$endDate 23:59' " : '')
      ."";
    // $databaseManager = new sfDatabaseManager($this->configuration);
    $q=Doctrine_Manager::getInstance()->getCurrentConnection();
    $result = $q->execute($sqlBody);
    $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
    $orders=$result->fetchAll();
    $resOrders=[];
    if(sizeof($orders)) foreach ($orders as $order) {
      $items=unserialize($order['text']);
      if(sizeof($items)) foreach ($items as $item) {
        //Добавить получение товара по его id
        $product=Doctrine_Core::getTable('Product')->findOneById($item['productId']);
        $resOrders[]=[
          'date' => explode(' ', $order['created_at'])[0],
          'item' => $product->getName(),
          'amount' => 100*$item['price_w_discount'],
          'quantity' => $item['quantity'],
          'location' =>'onona.ru',
          'bonuses' => ''
        ];
        unset($product);
      }
    }

    // die('<pre>'.print_r([$resOrders], true));
    $this->showResponse('success', $resOrders);

  }

  public function executeCardSaveCommunication(sfWebRequest $request) {
    /*
      /cardsmobile/v1/card/:num/communication
      Устанавливает способы связи с пользователем
    */
    $this->getAuth();
    $number=$request->getParameter('num', false);
    if(!$number) $this->showError('card_number_needed');

    try {//получаем карту
      $card=Doctrine_Core::getTable('Cardsmobile')->findOneById($number);
    } catch (\Exception $e) {
      $this->showError('database_error');
    }

    if(!$card){ //если карты нет - отдаем ошибку
      $this->showError('card_not_found');
    }
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);
    $isRecordChanged=false;
    if(sizeof($data['allow'])) foreach($data['allow'] as $service){//Разрешенные способы
      switch ($service) {
        case 'sms':
          $card->setIsAllowSms(true);
          $isRecordChanged=true;
          break;
        case 'call':
          $card->setIsAllowCall(true);
          $isRecordChanged=true;
          break;
        case 'email':
          $card->setIsAllowEmail(true);
          $isRecordChanged=true;
          break;

        default:
          break;
      }
    }
    if(sizeof($data['deny'])) foreach($data['deny'] as $service){//Запрещенные способы
      switch ($service) {
        case 'sms':
          $card->setIsAllowSms(false);
          $isRecordChanged=true;
          break;
        case 'call':
          $card->setIsAllowCall(false);
          $isRecordChanged=true;
          break;
        case 'email':
          $card->setIsAllowEmail(false);
          $isRecordChanged=true;
          break;

        default:
          break;
      }
    }

    if($isRecordChanged) $card->save();

    $this->showResponse('success', 'empty');
  }

  public function executeCardGetInfo(sfWebRequest $request) {
    /*
      /cardsmobile/v1/card/:slug
      отдает или принимает информацию о карте в зависимости от вида запроса
    */
    $this->getAuth();
    $number=$request->getParameter('num', false);
    if(!$number) $this->showError('card_number_needed');
    try {//получаем карту
      $card=Doctrine_Core::getTable('Cardsmobile')->findOneById($number);
    } catch (\Exception $e) {
      $this->showError('database_error');
    }

    if(!$card){ //если карты нет - отдаем ошибку
      $this->showError('card_not_found');
    }
    if($_SERVER['REQUEST_METHOD']=='GET'){//отдаем серверу информацию о карте, ее статусе и вот это вот все

      $sqlBody='SELECT SUM(`bonus`) as `bonustotal` FROM `bonus` WHERE `user_id`='.$card->getUserId().';';
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $sum=$result->fetch();
      $sqlBody="SELECT `bonus`, `comment`, `created_at` "
        ."FROM `bonus` "
        ."WHERE user_id=".$card->getUserId()." "
          // ."AND bonus<>0 "
        ."ORDER BY `id` DESC "
        ."";
      // die($sqlBody);
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
      $bonuses=$result->fetchAll();
      $validUntil=false;

      if(sizeof($bonuses)) foreach ($bonuses as $bonus) {
        if(!$validUntil) {//Считаем один раз, так как бонусы списываются целиком
          $bonusTime=csSettings::get('BONUS_TIME');//Время жизни бонусов в днях после последней транзакции
          $validUntil=date('Y-m-d', strtotime($bonus['created_at'])+$bonusTime*24*60*60);
        }
        if($bonus['bonus'])
          $bonusTable[]=[
            'description' => $bonus['comment'],
            'amount' => $bonus['bonus'],
            'validUntil' => $validUntil
          ];
      }

      $resp=[
        'card'=>[
          'cardNumber' => $card->getId(),
          'cardState' => $card->getIsPublic() ? 'active' : 'inactive',
          'barcode'=>[
            'barcodeNumber' => $card->getBarcode(),
            'barcodeType' => 'EAN_13',
          ],
          'bonus' => [
            'total' => $sum['bonustotal'],
            'available' => $sum['bonustotal'],
            'bonuses' => $bonusTable,
          ],
        ],
      ];
      $this->showResponse('success', $resp);
    }
    else{//должны обновить данные владельца карты
      try {//получаем карту
        $card=Doctrine_Core::getTable('Cardsmobile')->findOneById($number);
      } catch (\Exception $e) {
        $this->showError('database_error');
      }
      $postData = file_get_contents('php://input');
      $data = json_decode($postData, true);

      if(isset($data['email'])){
        $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($data['email']));
        if(is_object($user) && $user->getId()!=$card->getUserId()) $this->showError('email_already_in_use');
        unset($user);
      }

      $phone=$this->formatPhone($data['phone']);
      if($phone) {
        $user = sfGuardUserTable::getInstance()->findOneByPhone($phone);
        if(is_object($user) && $user->getId()!=$card->getUserId()) $this->showError('phone_already_in_use');
        unset($user);
      }
      if($phone) $card->setPhone($phone);
      if(isset($data['sex']) && $data['sex']) $card->setSex($data['sex']);
      if(isset($data['countryCode']) && $data['countryCode']) $card->setCountry($data['countryCode']);
      if(isset($data['locality']) && $data['locality']) $card->setCity($data['locality']);
      if(isset($data['email']) && $data['email']) $card->setEmail(trim($data['email']));
      if(isset($data['firstname']) && $data['firstname']) $card->setUserName($data['firstname']);
      if(isset($data['patronymic']) && $data['patronymic']) $card->setUserSubname($data['patronymic']);
      if(isset($data['surname']) && $data['surname']) $card->setUserFamily($data['surname']);
      if(isset($data['birthDate']) && $data['birthDate']) $card->setBirthday($data['birthDate']);
      $card->save();
      $this->showResponse('success', 'empty');//все ок

    }

  }

  public function executeNewCardCreate(sfWebRequest $request) {
    /*
      /cardsmobile/v1/card/anonymous/:slug
      создает новую карту по ранее зарезервированному номеру
      должен привязать ее к существующему пользователю или создать новую
    */
    // die($this->formatPhone('+7(920)274 80-99'));

    $this->getAuth();
    $number=$request->getParameter('slug', false);
    if(!$number) $this->showError('card_number_needed');
    try {
      $card=Doctrine_Core::getTable('Cardsmobile')->findOneById($number);
    } catch (\Exception $e) {
      $this->showError('database_error');
    }
    if(!$card) $this->showError('reserved_card_not_found');
    if(!$card->getIsReserved()) $this->showError('card_already_in_use');
    // die('<pre>'.print_r($card, true));
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);
    $user=false;
    if(isset($data['email']) && $data['email'])//если передан email
      $user = sfGuardUserTable::getInstance()->findOneByEmailAddress(trim($data['email']));

    $phone=$this->formatPhone($data['phone']);
    if(!$user){//если не нашли по email, ищем по телефону
      $user = sfGuardUserTable::getInstance()->findOneByPhone($phone);
    }

    if(!$user){//Если не нашли ни по телефону, ни по email
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
      $password = rand(100000, 999999);
      $user->set("password", $password);
      $user->setFirstName($data['firstname']);
      $user->setEmailAddress($data['email']);
      $user->setPhone($phone);
      $user->setSex($data['sex']);
      $user->setCity($data['locality']);
      $user->setBirthday($data['birthDate']);
      $user->save();

      $newBonusActive = new Bonus();
      $newBonusActive->setUserId($user->getId());
      $newBonusActive->setBonus(csSettings::get('register_bonus_add'));
      $newBonusActive->setComment('Регистрация через приложение Кошелёк');
      $newBonusActive->save();
      //Добавили 300 приветственных бонусов

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
    /*else{//
      //Тут можно исправлять данные
    }*/
    $card->setUserId( $user->getId());
    $card->setPhone($phone);
    $card->setSex($data['sex']);
    $card->setCountry($data['countryCode']);
    $card->setCity($data['locality']);
    $card->setEmail($data['email']);
    $card->setUserName($data['firstname']);
    $card->setUserSubname($data['patronymic']);
    $card->setUserFamily($data['surname']);
    $card->setBirthday($data['birthDate']);
    $card->setIsReserved(false);
    $card->save();
    //Сохранили карту

    $this->showResponse('success', 'empty');
  }

  public function executeNewCardReserve(sfWebRequest $request) {
    /*
    /cardsmobile/v1/card/anonymous
    Необходимо создать новую карту с определенным номером и держать его пустой в течении часа. потом можно убивать
    */
    $this->getAuth();
    // $this->showResponse('error', ['code'=>'040', 'description'=>print_r([$user, $_SERVER], true)]);
    try {
      $card = new Cardsmobile ();
      $card->save();
    } catch (\Exception $e) {
      $this->showError('database_error');
    }
    $this->showResponse('success', $card->getId());
  }

  private function formatPhone($phone){
    $phone=trim($phone);
    if(!$phone) return false;
    $phone=preg_replace('/\D/', '', $phone);
    $phoneRes='+'.mb_substr($phone, 0, 1).'('.mb_substr($phone, 1, 3).')'.mb_substr($phone, 4, 3).'-'.mb_substr($phone, 7, 2).'-'.mb_substr($phone, 9, 2);

    return $phoneRes;
  }

  private function showError($code){
    $errors=[
      'wrong_path'=>[
        'code' => '001',
        'description' => 'Недопустимый путь запроса',
      ],
      'incorrect_phone_email'=>[
        'code' => '002',
        'description' => 'Необходимо передать email или телефон',
      ],
      'incorrect_auth'=>[
        'code' => '003',
        'description' => 'Неверные логин или пароль',
      ],
      'reserved_card_not_submit'=>[
        'code' => '004',
        'description' => 'Не передан номер зарезервированной карты',
      ],
      'reserved_card_not_found'=>[
        'code' => '005',
        'description' => 'Нет зарезервированной карты',
      ],
      'database_error'=>[
        'code' => '006',
        'description' => 'Недоступен сервер баз данных',
      ],
      'card_already_in_use'=>[
        'code' => '007',
        'description' => 'Номер зарезервированной карты уже занят',
      ],
      'card_number_needed'=>[
        'code' => '008',
        'description' => 'Не передан обязательный параметр - номер карты',
      ],
      'card_not_found'=>[
        'code' => '009',
        'description' => 'Карта не найдена',
      ],
      'start_date_needed'=>[
        'code' => '010',
        'description' => 'Дата начала периода - обязательный параметр',
      ],
      'email_already_in_use'=>[
        'code' => '011',
        'description' => 'К этому email уже привязана другая бонусная карта',
      ],
      'phone_already_in_use'=>[
        'code' => '012',
        'description' => 'К этому телефону уже привязана другая бонусная карта',
      ],
    ];
    $this->showResponse('error', ['code' => $errors[$code]['code'], 'description' => $errors[$code]['description']]);
  }

  private function getAuth(){
    if(!isset($_GET['is_debug']))
      if($_SERVER['PHP_AUTH_USER']!=self::USERNAME || $_SERVER['PHP_AUTH_PW']!=self::PASSWORD)
        $this->showError('incorrect_auth');
  }

  private function showResponse($status, $data){
    header('Content-Type: application/json');
    if($status=='error') header('422 Unprocessable Entity', false, 422);
    if($data!='empty'){
      if(!is_array($data))
        die($data);
      else
        die(json_encode($data));
    }
    die();
  }

  public function executeIndex(sfWebRequest $request) {
    $this->showError('wrong_path');
  }

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
}
