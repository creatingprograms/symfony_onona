<?php

class usersTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'users';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [users|INFO] task does things.
Call it with:

  [php symfony users|INFO]
EOF;
    }
    
    const CSV_DELIMITER  = ',';

    protected function execute($arguments = array(), $options = array()) {
      echo "\n\n\n--------\nstart at ".date('d.m.Y H:i:s')."\n";
      $isTest =false;
      //$isTest =true;
      ini_set("max_execution_time", 1800);
      
      $dataArrayHeader=implode(self::CSV_DELIMITER, [
        'Email',
        'Name',
        'Birth',
      ]);
      $query=
        'SELECT u.id as id, email_address, birthday, first_name, last_name '.
        'FROM sf_guard_user u '.
        'WHERE birthday is not null and birthday<>\'0000-00-00\' and birthday<>\'1970-01-01\' '.
        ($isTest ? 'LIMIT 100 ' : '').
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
          $rowOne['first_name'].($rowOne['last_name'] ? ' '.$rowOne['last_name'] : ''),
          $rowOne['birthday'] ? $rowOne['birthday'] : '1970-01-01'

        ]);
        $dataArray[]=$row;
      }
      @mysql_free_result($resourseId);

      /* Получили данные */
      if($isTest) die(print_r(['header'=>$dataArrayHeader, 'data' => $dataArray], true)."\n\n");
      else {
        $dataPart=$dataArrayHeader."\n".implode("\n", $dataArray);
        $file = fopen('/var/www/ononaru/data/www/onona.ru/' . substr($SITE_NAME, 0, -3) . '_birthday.csv', "w+");
        fputs($file, $dataPart, (strlen($dataPart) + 1));
        fclose($file);
      }
    }
    protected function formatText($string){
      return str_replace(["\n", "\n\n", "\n\n\n", ','], [';', ';', ';', ';'], trim($string));
    }
    protected function testForEmail($string){
      $rule='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
      return preg_match($rule, $string);
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
