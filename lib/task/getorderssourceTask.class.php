<?php
use App\OnonaGoogleAnalitics;

class getorderssourceTask extends sfBaseTask {
    const ACCESS_TOKEN='ya29.GmTCBr7UCb-lLZYlMtf32phUcjA1YWKeuI7sn90pqQRK4fVnSC6Ewd0GRVumnWDLwASTZDJZHIF6eiRi59imeb0n4SCBj7FCX43SN-WeE1OECBWp2Mdy0f9_gReNv9lQgADNc4Zh';
    const URL='https://www.googleapis.com/analytics/v3/data/ga';
    const ID='ga:178119779';
    //
    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('yandex', null, sfCommandOption::PARAMETER_REQUIRED, 'Обработать только яндекс', false),
            new sfCommandOption('start', null, sfCommandOption::PARAMETER_REQUIRED, 'Стартовая дата', false),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'getorderssource';
        $this->briefDescription = 'Обновляеет информацию об источнике перехода из гугл-аналитики';
        $this->detailedDescription = 
          'The [getorderssource|INFO] Обновляеет информацию об источнике перехода из гугл-аналитики.'
          ."\nCall it with:\n\n"
          ."[php symfony getorderssource|INFO]"
        ;
    }
    protected function sendEmailError($subject, $text){
      sfContext::createInstance($this->configuration);
      $message = Swift_Message::newInstance()
              ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
              ->setTo('aushakov@interlabs.ru')
              ->setSubject('<pre>'.$subject.'</pre>')
              ->setBody($text, 'text/html')
              ->setContentType('text/html');
      $this->getMailer()->send($message);
    }

    protected function execute($arguments = array(), $options = array()) {
        $isTest =false;
        // $isTest =true;
        
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $orderPrefix=csSettings::get("order_prefix"); //Настройка в СУ, префикс заказа

        $ym = new YandexMetrika('y0_AgAAAAAHl_h1AAoqigAAAADnhPU-PLaSVJXORRmzP72cZ8a70kyw-sQ', 144683, $isTest);
        $date1 = '3daysAgo';
        $date2 = 'yesterday';
        if(!empty($options['start'])) $date1 = $options['start'];

        $data = $ym->getOrders($date1, $date2);
        $orderMax = 0;

        if(is_object($data)){
          foreach($data->data as $row){
            $orderId = str_replace($orderPrefix, '', $row->dimensions[0]->name);
            $source = $row->dimensions[1]->id;
            $medium = $row->dimensions[2]->id;
            $sqlBody = "UPDATE `orders` SET `source_ym`='$source', `medium_ym`='$medium' WHERE `id`='$orderId'";
            if($orderId > $orderMax) $orderMax = $orderId;

            if(!$isTest) $result = $q->execute($sqlBody);

            // die(__FILE__ . '|' . __LINE__ . '<pre>' . print_r([$sqlBody], true) . '</pre>' . "\n");
          }
        }



        if($options['yandex'] == "Y") die(__FILE__ . '|' . __LINE__ . print_r($orderMax, true) . "\n");

        if($isTest) echo('getorderssource start at . '.date('d.m.Y H:i')."\n");

        require_once 'vendor/autoload.php';
        require_once 'OnonaGoogleAnalitics.php';
        $a=new OnonaGoogleAnalitics(__DIR__.'/key.json');

        $date=date('Y-m-d', time() - 1*25*60*60);
        $dateStart=date('Y-m-d', time() - 2*25*60*60);
        if($isTest)
          $data = $a->getStat(self::ID, $dateStart, $date);
        else
          $data = $a->getStat(self::ID, $date, $date);

        foreach ($data as $row) {
          $orderId=str_replace($orderPrefix, '', $row['ga:transactionId']);
          $source=$row['ga:source'];
          $medium=$row['ga:medium'];
          $region=str_replace("'", '', $row['ga:region']);
          if(false /*$isTest*/){
            echo print_r(['orderId'=> $orderId, 'source'=> $source, 'row' => $row], true);
            break;
          }
          $sqlBody="UPDATE orders SET source='$source', medium='$medium', region='$region' WHERE id='$orderId'"; //Проставляем источник перехода
          $result = $q->execute($sqlBody);

        }

        if($isTest) exit('getorderssource test run success. '.date('d.m.Y H:i')."\n");
    }

}
