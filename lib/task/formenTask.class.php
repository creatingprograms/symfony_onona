<?php

class formenTask extends sfBaseTask {

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
        $this->name = 'formen';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [formen|INFO] обновляет служебные категории для каталогов.
Call it with:

  [php symfony formen|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $isTest =false;
        // $isTest =true;
        if($isTest) echo 'start at '.date('H:i:s')."\n";
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        ini_set("max_execution_time", 1800);
        $sqlBody="SELECT id, slug, name FROM catalog WHERE is_public=1";
        $result=$q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $catalogs=$result->fetchAll();
        /*
          Array (
             [id] => 4
             [slug] => BDSM-i-fetish
         )

        */
        foreach ($catalogs as $cat) {
          $catSlug='service-'.$cat['slug'];
          $catalog=CategoryTable::getInstance()->findOneBySlug($catSlug); //Находим категорию-костыль для каталога
          if(!$catalog) {//Служебной категории нет, создаем
            $sqlBody=
              "INSERT INTO `category` (`slug`, `name`, `is_public`, `created_at`, `updated_at`) ".
              "VALUES ('$catSlug', 'Служебная ".$cat['name']."', 0, '".date('Y.m.d H:i:s')."', '".date('Y.m.d H:i:s')."') ".
              "";
            // echo 'категории нет, создаем:'."\n".$sqlBody."\n";
            $result = $q->execute($sqlBody);
            $catalog=CategoryTable::getInstance()->findOneBySlug($catSlug); //Находим категорию-костыль для каталога после ее создания
          }
          $catalogId = $catalog->getId();
          $sqlBody=
            "SELECT id"
            ." FROM category_catalog cc"
            ." LEFT JOIN category c ON cc.category_id=c.id OR cc.category_id=c.parents_id"
            ." WHERE catalog_id=".$cat['id']
            ."";
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $tmp = $result->fetchAll();
          unset($ids);
          if(sizeof($tmp)) foreach ($tmp as $key => $value) {
            $ids[]=$value['id'];
          }
          $catIn=implode(', ', $ids);
          // echo "\n".'catalog id:'.$catalogId."\n";
          // echo "\n".'catalog in:'.$catIn."\n";
          $sqlBody="select product_id from category_product where category_id in ($catIn) GROUP BY product_id"; //Собираем все товары каталога
          $result = $q->execute($sqlBody);
          $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
          $tmp = $result->fetchAll();
          unset($prodsAddIds);
          unset($prodIds);
          if(sizeof($tmp)) foreach ($tmp as $key => $value) {
            $prodsAddIds[]="($catalogId, $value[product_id])";
            $prodIds[]=$value['product_id'];
          }
          $prodsInIds=implode(', ', $prodIds);
          $prodsAddValues=implode(', ', $prodsAddIds);
          // echo 'products in:'.$prodsInIds."\n";
          // echo 'products add values:'.$prodsAddValues."\n";
          // $sqlBody="select id $categoryIds\n";
          $sqlBody="DELETE FROM category_product WHERE category_id=$catalogId AND product_id NOT IN($prodsInIds)"; //Удаляем товары которые больше не входят
          $result = $q->execute($sqlBody);
          $sqlBody="INSERT IGNORE category_product (category_id, product_id) VALUES $prodsAddValues"; //Привязываем все товары из списка к категории-костылю
          // echo 'products insert:'.$sqlBody."\n";
          $result = $q->execute($sqlBody);
          // echo 'категория есть'."\n".(print_r($cat, true)."\n");

          $sqlBody="SELECT `id` FROM `category` WHERE `slug`='service_all'";
          $result = $q->execute($sqlBody);
          $tmp = $result->fetch(Doctrine_Core::FETCH_ASSOC);
          $sqlBody="INSERT IGNORE `category_product` (`category_id`, `product_id`) SELECT ".$tmp['id'].", `id` from `product`";
          $result = $q->execute($sqlBody);

        }
        
        $strDate = date('Y-m-d 00:00:00', time() - csSettings::get('logo_new') * 24*60*60);
        $sqlBody = "UPDATE `product` SET `is_new_on_site` = 0 WHERE `created_at` < '$strDate' AND `is_new_on_site` = 1";
        $result = $q->execute($sqlBody);
        
        die(/*'done at '.date('H:i:s')."\n"*/);


        //дальше все не нужно


        $catalog = CategoryTable::getInstance()->findOneBySlug('service_for_her');//Находим категорию-костыль
        $catalogId = $catalog->getId();
        $sqlBody=
          "SELECT id"
          ." FROM category_catalog cc"
          ." LEFT JOIN category c ON cc.category_id=c.id OR cc.category_id=c.parents_id"
          ." WHERE catalog_id=1" //1 it's for men
          ."";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetchAll();
        if(sizeof($tmp)) foreach ($tmp as $key => $value) {
          $ids[]=$value['id'];
        }
        $catIn=implode(', ', $ids);
        // echo "\n".'catalog id:'.$catalogId."\n";
        // echo "\n".'catalog in:'.$catIn."\n";
        $sqlBody="select product_id from category_product where category_id in ($catIn) GROUP BY product_id"; //Собираем все товары каталога
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetchAll();
        if(sizeof($tmp)) foreach ($tmp as $key => $value) {
          $prodsAddIds[]="($catalogId, $value[product_id])";
          $prodIds[]=$value['product_id'];
        }
        $prodsInIds=implode(', ', $prodIds);
        $prodsAddValues=implode(', ', $prodsAddIds);
        // echo 'products in:'.$prodsInIds."\n";
        // echo 'products add values:'.$prodsAddValues."\n";
        // $sqlBody="select id $categoryIds\n";
        $sqlBody="DELETE FROM category_product WHERE category_id=$catalogId AND product_id NOT IN($prodsInIds)"; //Удаляем товары которые больше не входят
        $result = $q->execute($sqlBody);
        $sqlBody="INSERT IGNORE category_product (category_id, product_id) VALUES $prodsAddValues"; //Привязываем все товары из списка к категории-костылю
        // echo 'products insert:'.$sqlBody."\n";
        $result = $q->execute($sqlBody);
        // echo "----------------------------------\n".'All done at '.date('H:i:s')."\n";


    }

}
