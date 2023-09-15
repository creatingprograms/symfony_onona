<?php

class yataxiTask extends sfBaseTask {

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
        $this->name = 'yataxi';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] Подтверждает заказы в ЯндексТакси. Должен запускаться каждую минуту
Call it with:

  [php symfony yataxi|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
      $databaseManager = new sfDatabaseManager($this->configuration);
      $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $localPath=str_replace('lib/task', '', __DIR__).'web/../';
      $sqlBody=
        "SELECT `id`, `yataxi_id`, `yataxi_status`, `created_at` FROM `orders` ".
        "WHERE `yataxi_status`='new' AND created_at>'".date('Y-m-d H:i:s', time()-590)."'";
      $result = $q->execute($sqlBody);
      $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);

      // print_r($sqlBody);

      $yandexDelivery = ILTools::getYandexTaxiObject();

      while($order=$result->fetch()){
        $res=$yandexDelivery->claimAccept($order['yataxi_id']);
        if(!empty($res->status)) {
          $sqlBody="UPDATE `orders` SET `yataxi_status`='".$res->status."' WHERE `id`=".$order['id'];
          $q->execute($sqlBody);
        }
        ILTools::logToFile(['order'=>$order, 'res'=>$res], $localPath.'yataxi_accept_log.log');
        // print_r($order);
      }

    }

}
