<?php

class satisfierTask extends sfBaseTask {

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
        $this->name = 'satisfier';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] Формирует файл csv с товарами satisfier.
Call it with:

  [php symfony satisfier|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $isTest =false;
        $isTest =true;

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        // add your code here

        ini_set("max_execution_time", 1800);
        $delimiter=';';
        $articlesList=[
          'J6365-M',
          'J5481-P',
          'J2018-2-c',
          'J2018-3-c',
          'J2018-8-c',
          'J6440-V',
          'J6471-V',
          'J6464-V',
          'J6433-V',
          'J6488-V',
          'J6457-V',
          'J2018-16',
          'J2018-6-P',
          'J2018-7-P',
          'J2018-3-P',
          'J2018-8N-P',
          'J2018-TR',
          'J2018-2-P',
          'J2018-men',
          'J2018-18',
          'J2008-3-P',
          'J2008-5-P',
          'J2008-2-P',
          'J2018-17',
        ];
        die('do nothing )'."\n");

        $sqlBody="SELECT code, filename FROM product p LEFT JOIN product_photoalbum pp ON pp.product_id=p.id LEFT JOIN photo ON photo.album_id=pp.photoalbum_id";
        if($isTest) $sqlBody.=" LIMIT 5";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetchAll();
        foreach ($tmp as $value) {
          $value['filename']='https://onona.ru/uploads/photo/'.$value['filename'];
          $resultArray[]=implode($delimiter, $value);
        }
        if ($isTest) file_put_contents('/home/i9s/p702/run/web/photos_by_code.csv', implode("\n",$resultArray));
        else file_put_contents('/var/www/ononaru/data/www/onona.ru/photos_by_code.csv', implode("\n",$resultArray));

        $sqlBody="SELECT id, code, name, slug, price  FROM product ";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetchAll();
        unset($resultArray);
        $resultArray[]=implode($delimiter, ['id', 'Артикул', 'Название', 'Ссылка', 'Цена']);
        foreach ($tmp as $value) {
          $value['slug']='https://onona.ru/product/'.$value['slug'];
          $resultArray[]=implode($delimiter, $value);
        }
        if ($isTest) file_put_contents('/home/i9s/p702/run/web/items.csv', implode("\n",$resultArray));
        else file_put_contents('/var/www/ononaru/data/www/onona.ru/items.csv', implode("\n",$resultArray));


    }

}
