<?php

class ordersreportTask extends sfBaseTask {

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
        $this->name = 'ordersreport';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [ordersreport|INFO] Формирует файл csv формата с указанием источника перехода.
Call it with:

  [php symfony ordersreport|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $isTest =false;
        // $isTest =true;
        $start='2019-01-01 00:00';
        $end='2019-01-31 23:59:59';

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        // add your code here

        ini_set("max_execution_time", 1800);
        $delimiter=';';


        $sqlBody="select concat(`source`,' ', `medium`) as ist, count(id) as count_id from orders where `created_at` > '".$start."' and `created_at`<'".$end."' group by ist";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetchAll();
        unset($resultArray);
        $resultArray[]=implode($delimiter, ['Источник', 'Количество']);
        foreach ($tmp as $value) {
          // $value['slug']='https://onona.ru/product/'.$value['slug'];
          $resultArray[]=implode($delimiter, $value);
        }
        if ($isTest) file_put_contents('/home/i9s/p702/run/web/ordersreport.csv', implode("\n",$resultArray));
        else file_put_contents('/var/www/ononaru/data/www/onona.ru/ordersreport.csv', implode("\n",$resultArray));


    }

}
