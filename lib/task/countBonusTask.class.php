<?php

class countBonusTask extends sfBaseTask {

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
        $this->name = 'countBonus';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [countBonus|INFO] task does things.
Call it with:

  [php symfony countBonus|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT SUM( bonus ) AS summ
FROM  `bonus` 
WHERE bonus >0");

        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $result = $result->fetchAll();
        $setting = Doctrine::getTable('csSetting')->findOneBySlug('all_bonus_add');
        //echo csSettings::get('all_bonus_add');
        if ($result[0]['summ'] > csSettings::get('all_bonus_add') + 172800) {
            $setting->setValue($result[0]['summ']);
        } else {
            $setting->setValue(csSettings::get('all_bonus_add') + 172800);
        }
        $setting->save();
        //print_r($result[0]['summ']);

        // add your code here
    }

}
