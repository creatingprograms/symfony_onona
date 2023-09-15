<?php

class pickpointTask extends sfBaseTask {

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
        $this->name = 'pickpoint';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [pickpoint|INFO] task does things.
Call it with:

  [php symfony pickpoint|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        Doctrine_Query::create()
  ->update('City c')
  ->set('c.is_public', '0')
  ->execute();

        Doctrine_Query::create()
  ->update('pickpoint p')
  ->set('p.is_public', '0')
  ->execute();
        // add your code here
        //$pickpointPoint = file_get_contents("http://e-solution.pickpoint.ru/apitest/postamatlist");
        $pickpointPoint = file_get_contents("https://e-solution.pickpoint.ru/api/postamatlist");
        $pickpointarray = json_decode($pickpointPoint);
        // die(print_r($pickpointarray, true));
        foreach ($pickpointarray as $pointApi) {
            $point = PickpointTable::getInstance()->findOneByDopid($pointApi->Id);
            if (!$point)
                $point = new Pickpoint();
            $point->set("Address", $pointApi->Address);
            $point->set("Card", $pointApi->Card);
            $point->set("Cash", $pointApi->Cash);
            $point->set("CitiId", $pointApi->CitiId);
            $point->set("CitiName", $pointApi->CitiName);
            $point->set("CitiOwnerId", $pointApi->CitiOwnerId);
            $point->set("CountryName", $pointApi->CountryName);
            $point->set("House", $pointApi->House);
            $point->set("dopid", $pointApi->Id);
            $point->set("InDescription", $pointApi->InDescription);
            $point->set("IndoorPlace", $pointApi->IndoorPlace);
            $point->set("Latitude", $pointApi->Latitude);
            $point->set("Longitude", $pointApi->Longitude);
            $point->set("Metro", $pointApi->Metro);
            $point->set("Name", $pointApi->Name);
            $point->set("Number", $pointApi->Number);
            $point->set("OutDescription", $pointApi->OutDescription);
            $point->set("OwnerId", $pointApi->OwnerId);
            $point->set("PostCode", $pointApi->PostCode);
            $point->set("Region", $pointApi->Region);
            $point->set("Status", $pointApi->Status);
            $point->set("Street", $pointApi->Street);
            $point->set("TypeTitle", $pointApi->TypeTitle);
            $point->set("WorkTime", $pointApi->WorkTime);
            $point->set("is_public", 1);
            $city = CityTable::getInstance()->findOneByName($pointApi->CitiName);
            if (!$city) {
                $city = new City();
                $city->setName($pointApi->CitiName);
                $city->set("is_public",true);
                $city->save();
            }else{
                $city->set("is_public",true);
                $city->save();
            }
            $point->setCityId($city);
            $point->save();
        }
        //print_r(json_decode($pickpointPoint));
    }

}
