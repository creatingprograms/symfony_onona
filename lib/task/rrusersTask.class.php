<?php

class rrusersTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'rrusers';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [rrusers|INFO] task does things.
Call it with:

  [php symfony rrusers|INFO]
EOF;
    }
    // const API_KEY        = '550fdc951e99461fcffc015b'; //левый
    const API_KEY        = '550fdc951e99461fc47c015b';
    const PARTNER_ID     = '550fdc951e99461fc47c015c';
    const CSV_DELIMITER  = ',';

    protected function execute($arguments = array(), $options = array()) {
      echo "\n\n\n--------\nstart at ".date('d.m.Y H:i:s')."\n";
      $isTest =false;
      // $isTest =true;
      ini_set("max_execution_time", 1800);
      $lastSyncDateFile='onona.ru/rruserslastsyncdate.txt';
      $logFile='onona.ru/rr_users_sync.log';
      $lastSyncDate=file_get_contents($lastSyncDateFile);
      $dataArrayHeader=implode(self::CSV_DELIMITER, [
        'Email',
        'IsSubscribed',
        'Bonus',
        'Gender',
        'Birth',
        'Id',
        'City',
      ]);
      $query=
        'SELECT u.id as id, email_address, SUM(b.bonus) AS bonus, birthday, sex '.
          ', MAX(b.updated_at) as dtlm, city '.
        'FROM sf_guard_user u '.
        'LEFT JOIN bonus b ON u.id=b.user_id '.
        'GROUP BY u.id, email_address, birthday, sex, city '.
        'HAVING MAX(b.updated_at)>"'.date('Y-m-d H:i:s', $lastSyncDate-1*24*60*60).'" '.
        'ORDER BY b.updated_at DESC '.
        ($isTest ? 'LIMIT 10 ' : '').
        ';';

      $config=$this->getDBConfig();

      $dbId=@mysql_connect($config['host'], $config['user'], $config['pass']);
      @mysql_query('SET NAMES UTF8');
      @mysql_select_db($config['db_name']);
      $resourseId = @mysql_query($query, $dbId);
      if ($error=@mysql_error()) die($error)."\n";
      $i=0;

      while ($rowOne = mysql_fetch_assoc($resourseId)) {
        $email=trim($rowOne['email_address']);
        if(!$this->testForEmail($email)) continue;
        $i++;
        $row=implode(self::CSV_DELIMITER, [
          $email,
          // trim($rowOne['email_address']),
          'true',
          $rowOne['bonus'],
          $rowOne['sex'],
          $rowOne['birthday'] ? $rowOne['birthday'] : '1970-01-01',
          $rowOne['id'],
          $this->formatText($rowOne['city']),

        ]);
        $dataArray[]=$row;
      }
      @mysql_free_result($resourseId);

      /* Получили данные */
      if($isTest) die(print_r(['header'=>$dataArrayHeader, 'data' => $dataArray], true)."\n\n");

      // $dataArrayParts=array_chunk($dataArray, 3, true);
      $dataArrayParts=array_chunk($dataArray, 49999, true);
      $partNo=0;

      foreach ($dataArrayParts as $part){
        $dataPart=$dataArrayHeader."\n".implode("\n", $part);
        if(!$this->sendPart($dataPart, $partNo++)){
          // if(!$isTest) file_put_contents($lastSyncDateFile, time());
          if(!$isTest) file_put_contents($logFile, date('Ошибка отправки данных d.m.Y H:i'."\n"), FILE_APPEND);
          $this->sendEmailError('Проблема выгрузки файла с пользователями для retailRocket с onona.ru', 'Проблема выгрузки файла с пользователями для retailRocket с onona.ru, часть'." $part");

          die( "\nError!\n");
        }
      }

      if(!$isTest) file_put_contents($lastSyncDateFile, time());
      echo "\n------\nsuccess at ".date('d.m.Y H:i:s')."\n$i records sended\n";

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
    protected function formatText($string){
      return str_replace(["\n", "\n\n", "\n\n\n", ','], [';', ';', ';', ';'], trim($string));
    }
    protected function testForEmail($string){
      $rule='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
      return preg_match($rule, $string);
    }
    protected function sendPart(&$data_string, $partNo){
      file_put_contents('onona.ru/rrUsersPart'.$partNo.'.csv', $data_string);
      // return true;
      $ch = curl_init();
      curl_setopt(
        $ch,
        CURLOPT_URL,"https://api.retailrocket.ru/api/2.0/partner/".self::PARTNER_ID."/subscribers?apikey=".self::API_KEY."");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         'Content-Type: text/plain',
         'Content-Length: ' . strlen($data_string))
      );
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $result = curl_exec($ch);
      echo "[result] - $result\n";
      if(false === stristr($result, 'HTTP/1.1 200 OK')){
        $this->sendEmailError('Проблема выгрузки файла с пользователями для retailRocket с onona.ru', $result);
      }
      if(!$result) {
        echo print_r(['info' => curl_getinfo($ch), 'result'=> $result], true);
        curl_close ($ch);
        return false;
      }
      curl_close ($ch);
      return true;
    }
    protected function getDBConfig(){
      $config=file_get_contents($this->configuration->getConfigCache()->checkConfig('config/databases.yml'));
      $reg='/host=(\S+);dbname=(\S+)\'(.|\n)*username\'.+\'(\S+)\'(.|\n)*password\'.+\'(\S+)\'/U';
      preg_match_all($reg, $config, $matches, PREG_SET_ORDER, 0);
      return [
        'host'     =>  $matches[0][1],
        'db_name'  =>  $matches[0][2],
        'user'     =>  $matches[0][4],
        'pass'     =>  $matches[0][6],
      ];
    }

    protected function prepareText($text) {
      $text = strip_tags($text);
      $text = htmlspecialchars($text);
      return $text;
    }
}
