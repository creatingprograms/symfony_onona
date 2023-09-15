<?php

class imlTask extends sfBaseTask {

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
        $this->name = 'iml';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [iml|INFO] task does things.
Call it with:

  [php symfony iml|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $imlPoint = file_get_contents("http://list.iml.ru/sd?type=json");
        $imlarray = json_decode($imlPoint);
        foreach ($imlarray as $pointApi) {

                $iml = ImlTable::getInstance()->findOneByCode($pointApi->Code);
                if (!$iml) {
                    $iml = new Iml();
                }
                $iml->setAddress($pointApi->Address);
                $iml->setRegioncode($pointApi->RegionCode);
                $iml->setWorkmode($pointApi->WorkMode);
                $iml->setCode($pointApi->Code);
                $iml->setName($pointApi->Name);
                $iml->setAddress($pointApi->Address);
                $iml->setLatitude($pointApi->Latitude);
                $iml->setLongitude($pointApi->Longitude);
                $city = CityTable::getInstance()->findOneByName($pointApi->RegionCode);
                if (!$city) {
                    $city = new City();
                    $city->setName($pointApi->RegionCode);
                    $city->save();
                }
                $iml->setCityId($city);
                $iml->save();
                /*print_r($row);
                exit;*/
            
        }
        //print_r($imlarray);
    }

}
