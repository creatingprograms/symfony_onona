<?php

class optimizationTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'optimization';
        $this->briefDescription = 'Обрабатывает данные для оптимизации';
        $this->detailedDescription = <<<EOF
The [optimization|INFO] task does things.
Call it with:

  [php symfony optimization|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        /*
         * Создание списка ID товаров, которые считаются новыми
         */

        if (csSettings::get('logo_new') == "") {
            $day_logo_new = 7;
        } else {
            $day_logo_new = csSettings::get('logo_new');
        }
        $whereCreatedAtTimestamp = time() - ($day_logo_new * 60 * 60 * 24);
        $newProducts = $q->execute("select id from product "
                        . "WHERE created_at > '" . date("Y-m-d 00:00:00", $whereCreatedAtTimestamp) . "' and is_public='1' and count>0 and moder = '0' ")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);


        $setting = Doctrine::getTable('csSetting')->findOneBySlug('optimization_newProductId');
        $setting->setValue(implode(",", array_keys($newProducts)));
        $setting->save();



        /*
         * Создание списка ID товаров, с акцией Управляй ценой
         */
        $newProducts = $q->execute("select id from product "
                        . "WHERE bonuspay>30 and is_public='1' and count>0 and moder = '0' ")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);


        $setting = Doctrine::getTable('csSetting')->findOneBySlug('optimization_BonuspayProductId');
        $setting->setValue(implode(",", array_keys($newProducts)));
        $setting->save();



        /*
         * Создание списка ID товаров, с акцией Лучшая цена
         */
        $newProducts = $q->execute("select id from product "
                        . "WHERE discount>0 and is_public='1' and count>0 and moder = '0' ")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);


        $setting = Doctrine::getTable('csSetting')->findOneBySlug('optimization_bestpriceProductId');
        $setting->setValue(implode(",", array_keys($newProducts)));
        $setting->save();




        /*
         * Создание списка ID случайных товаров каталогов
         */
        $catalogs = CatalogTable::getInstance()->findAll();
        foreach ($catalogs as $catalog) {
            $categorys = $q->execute("SELECT cat.id "
                            . "FROM catalog "
                            . "LEFT JOIN category_catalog AS cc ON cc.catalog_id = catalog.id "
                            . "LEFT JOIN category AS cat ON cat.id = cc.category_id "
                            . "WHERE catalog.slug =  '" . $catalog->getSlug() . "' ")->fetchAll(Doctrine_Core::FETCH_UNIQUE);

            $productsRandom = $q->execute("select id "
                            . "from product "
                            . "LEFT JOIN category_product AS cp ON product.id = cp.product_id "
                            . "WHERE (cp.category_id in (" . implode(",", array_keys($categorys)) . ")"
                            . "OR cp.category_id IN (SELECT id FROM category AS cat WHERE cat.parents_id IN (" . implode(",", array_keys($categorys)) . "))) "
                            . "and is_public='1' and count>0 and moder = '0' "
                            . "order by rand() "
                            . "limit 20")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $catalog->set("prodid_random", implode(",", array_keys($productsRandom)));

            $productsBestprice = $q->execute("select id "
                            . "from product "
                            . "LEFT JOIN category_product AS cp ON product.id = cp.product_id "
                            . "WHERE  id in (" . csSettings::get("optimization_bestpriceProductId") . ") and "
                            . "(cp.category_id in (" . implode(",", array_keys($categorys)) . ")"
                            . "OR cp.category_id IN (SELECT id FROM category AS cat WHERE cat.parents_id IN (" . implode(",", array_keys($categorys)) . "))) "
                            . "and is_public='1' and count>0 and moder = '0' "
                            . "order by rand() "
                            . "limit 20")
                    ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $catalog->set("prodid_bestprice", implode(",", array_keys($productsBestprice)));
            $catalog->save();
        }




        /*
         * Обновление количества товаров с лучшей ценой.
         */
        $q->execute("UPDATE category SET countProductActions=0;");
        
        $categorys = $q->execute("select c.id, count(c.id) as count "
                        . "from category AS c "
                        . "LEFT JOIN product AS p ON p.generalcategory_id = c.id "
                        . "WHERE p.is_public = '1' "
                        . "AND p.moder = '0' "
                        . "AND p.count >0 "
                        . "AND p.step <> '' "
                        . "AND p.discount >0 "
                        . "GROUP BY c.id "
                        . "HAVING COUNT( c.id ) >1")
                ->fetchAll(Doctrine_Core::FETCH_ASSOC);
        foreach ($categorys as $category) {
            $categoryDB = CategoryTable::getInstance()->findOneById($category['id']);
            $categoryDB->set("countProductActions", $category['count']);
            $categoryDB->save();
        }


        exec("./symfony cc", $test);
    }

}
