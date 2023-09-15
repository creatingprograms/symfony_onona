<?php

class qiwiTask extends sfBaseTask {

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
        $this->name = 'qiwi';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [qiwi|INFO] task does things.
Call it with:

  [php symfony qiwi|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        /* $qiwiPoint = file_get_contents("http://wt.qiwipost.ru/selectterminal");
          preg_match_all('#Qiwipost.initData\((.+?)\);
          QiwipostWidget.mapClick =#is', $qiwiPoint, $qiwiPoint);
         */
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        file_put_contents('/var/www/ononaru/data/mod-tmp/terminalsQIWIlistxls', file_get_contents('http://wt.qiwipost.ru/terminalslistxls'));

        require_once 'PHPExcel/Classes/PHPExcel.php';
        $objReader = PHPExcel_IOFactory::createReaderForFile('/var/www/ononaru/data/mod-tmp/terminalsQIWIlistxls');
        $objReader->setReadDataOnly(true);
        $this->PHPExcel = $objReader->load('/var/www/ononaru/data/mod-tmp/terminalsQIWIlistxls');
        // $objPHPExcel = PHPExcel_IOFactory::load(file_get_contents("test.xls"));

        foreach ($this->PHPExcel->getActiveSheet()->toArray() as $keyRows => $row) {
            if ($keyRows != 0) {

                $qiwi = QiwiTable::getInstance()->findOneByName($row[0]);
                if (!$qiwi) {
                    $qiwi = new Qiwi();
                }
                $qiwi->setName($row[0]);
                $qiwi->setTown($row[3]);
                $qiwi->setCitygroup($row[2]);
                $qiwi->setAddr($row[4]);
                $qiwi->setLatitude($row[5]);
                $qiwi->setLongitude($row[6]);
                $qiwi->setDescr($row[8]);
                $qiwi->setOh($row[9]);
                $city = CityTable::getInstance()->findOneByName($row[3]);
                if (!$city) {
                    $city = new City();
                    $city->setName($row[3]);
                    $city->save();
                }
                $qiwi->setCityId($city);
                $qiwi->save();
                /*print_r($row);
                exit;*/
            }
        }
        // add your code here
    }

}
