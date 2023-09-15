<?php

class categoryfilterTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'categoryfilter';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [categoryfilter|INFO] task does things.
Call it with:

  [php symfony categoryfilter|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        /*
          SELECT dop_info.* FROM `dop_info`,dop_info_product, product where
          dop_info.name <>"" and dop_info.name <> 'Таблица размеров' and
          dop_info.id=dop_info_product.dop_info_id and dop_info_product.product_id=product.id and product.id in(
          SELECT p.id FROM product p LEFT JOIN category_product c ON p.id = c.product_id WHERE p.parents_id IS NULL AND p.is_public = '1' AND c.category_id IN (" . $this->category->getId() . $idChildrenCategory . ") GROUP BY p.id
          ) GROUP BY dop_info.id
         */
        /* $q = Doctrine_Manager::getInstance()->getCurrentConnection();
          $result = $q->execute(" -- RAW SQL HERE -- ");
         */
        /*
          $pdo = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
          and then execute your SQL as follows:

          $query = "SELECT * FROM table WHERE param1 = :param1 AND param2 = :param2";
          $stmt = $pdo->prepare($query);

          $params = array(
          "param1"  => "value1",
          "param2"  => "value2"
          );
          $stmt->execute($params);

          $results = $stmt->fetchAll();
         */





        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();









        $this->categorys = Doctrine_Core::getTable('Category')->findAll();


        foreach ($this->categorys as $this->category) {
            $categoryChildrens = $this->category->getChildren();
            $idChildrenCategory = "";
            foreach ($categoryChildrens as $categoryChildren)
                $idChildrenCategory.="," . $categoryChildren->getId();


            $q = Doctrine_Manager::getInstance()->getCurrentConnection();
            $result = $q->execute("SELECT dop_info.* FROM `dop_info`,  `dop_info_category`,dop_info_product, product where
                (
dop_info.name <>  'Таблица размеров'
OR dop_info.name IS NULL
)
and
              dop_info.id=dop_info_product.dop_info_id and dop_info_product.product_id=product.id
AND dop_info_category.id = dop_info.dicategory_id
AND dop_info_category.is_public =1
and product.id in(
              SELECT p.id FROM product p LEFT JOIN category_product c ON p.id = c.product_id WHERE p.is_public = '1' AND p.moder = '0' AND c.category_id IN (" . $this->category->getId() . $idChildrenCategory . ") GROUP BY p.id
              ) GROUP BY dop_info.id");

            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $result = $result->fetchAll();
            $filters = array();
            foreach ($result as $filter) {
                if (@is_array($filters[$filter['dicategory_id']])) {
                    $filterFull = DopInfoTable::getInstance()->findOneById($filter['id']);
                    $filtersFull = $filterFull->getDopInfoCategoryFullDopInfo();
                    if (count($filtersFull) > 0) {
                        foreach ($filtersFull as $keyFilterFull => $filterFull) {
                            if (@array_search(trim($filterFull->getName()), $filters[$filter['dicategory_id']]) === FALSE) {
                                $filters[$filter['dicategory_id']][] = trim($filterFull->getName());
                            }
                        }
                    } else {

                        if ($this->category->getSlug() == "erekcionnye-kolca-nasadki") {
                            //   echo $filter['dicategory_id'] . " " . $filter['value'] . "\r\n";
                        }
                        // var_dump(array_search(trim($filter['value']), $filters[$filter['dicategory_id']]));
                        if (@array_search(trim($filter['value']), $filters[$filter['dicategory_id']]) === FALSE) {
                            $filters[$filter['dicategory_id']][] = trim($filter['value']);
                        }
                    }
                } else {
                    $filterFull = DopInfoTable::getInstance()->findOneById($filter['id']);
                    $filtersFull = $filterFull->getDopInfoCategoryFullDopInfo();

                    if (count($filtersFull) > 0) {
                        foreach ($filtersFull as $keyFilterFull => $filterFull) {

                            // if (@array_search(trim($filterFull->getName()), $filters[$filter['dicategory_id']]) === FALSE) {
                            $filters[$filter['dicategory_id']][] = trim($filterFull->getName());
                            // }
                        }
                    } else {
                        if ($this->category->getSlug() == "erekcionnye-kolca-nasadki") {
                            //  echo $filter['id'] . " " . $filter['value'] . "\r\n";
                        }
                        $filters[$filter['dicategory_id']][] = trim($filter['value']);
                    }
                }
            }

            foreach ($filters as $key => $filter) {
                if (count($filter) < 2) {
                    unset($filters[$key]);
                }
            }
            $newFilters = array();
            if (isset($filters[24]))
                @$newFilters[24] = $filters[24];
            if (isset($filters[56]))
                @$newFilters[56] = $filters[56];
            if (isset($filters[58]))
                @$newFilters[58] = $filters[58];
            if (isset($filters[23]))
                @$newFilters[23] = $filters[23];
            if (isset($filters[22]))
                @$newFilters[22] = $filters[22];
            if (is_array($filters))
                foreach ($filters as $key => $filter) {
                    natsort($filter);
                    $newFilters[$key] = $filter;
                }
            $filters = $newFilters;
           // print_r($filters);
           // exit;
            $this->category->setFilters(serialize($filters));

            unset($newFilters);
            $newFilters = array();



            $idProductsInCategory = $q->execute("SELECT product.id "
                            . "FROM product "
                            . "LEFT JOIN category_product AS cp ON cp.product_id = product.id "
                            . "WHERE cp.category_id IN ( " . $this->category->getId() . $idChildrenCategory . " ) and parents_id IS NULL and is_public = '1' and moder = '0'")->fetchAll(Doctrine_Core::FETCH_UNIQUE);


            foreach ($filters as $dicId => $di) {
                $di = array_values($di);
                $dopInfoCategoryObject = DopInfoCategoryTable::getInstance()->findOneById($dicId);
                foreach ($di as $param) {

                    if ($dicId == 22) {

                        $newParam['value'] = $param;
                        $dopInfo = DopInfoTable::getInstance()->createQuery()->where("value=?", $param)->addWhere("dicategory_id = ?", $dicId)->fetchOne();
                        $newParam['id'] = $dopInfo->getId();
                        $result = $q->execute("SELECT product_id from dop_info_product where dop_info_id = ?", array($dopInfo->getId()))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
                        $newParam['productsId'] = implode(",", array_keys(array_intersect_key($result,$idProductsInCategory)));
                        $newParam['countProducts'] = count(array_intersect_key($result,$idProductsInCategory));
                        $dopInfoCategoryFull = DopInfoCategoryFullTable::getInstance()->createQuery()->where("name=?", $param)->addWhere("id in (" . implode(',', $dopInfoCategoryObject->getCategory()->getPrimaryKeys()) . ")")->fetchOne();
                        if ($dopInfoCategoryFull)
                            $newParam['filename'] = $dopInfoCategoryFull->getFilename();

                        $newFilters[$dicId][$dopInfo->getId()] = $newParam;
                        unset($newParam);
                    } else {
                        $newParam['value'] = $param;
                        $dopInfo = DopInfoTable::getInstance()->createQuery()->where("value=?", $param)->addWhere("dicategory_id = ?", $dicId)->fetchOne();
                        if(!$dopInfo) continue;
                        // echo print_r(['di'=> !$dopInfo, "value=" => $param, "dicategory_id =" => $dicId], true)."\n";
                        $newParam['id'] = $dopInfo->getId();
                        $result = $q->execute("SELECT product_id from dop_info_product where dop_info_id = ?", array($dopInfo->getId()))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
                        $newParam['productsId'] = implode(",", array_keys(array_intersect_key($result,$idProductsInCategory)));
                        $newParam['countProducts'] = count(array_intersect_key($result,$idProductsInCategory));
                        $newFilters[$dicId][$dopInfo->getId()] = $newParam;
                        unset($newParam);
                    }
                }

                if ((float) str_replace(",", ".", $di[0]) > 0 and (float) end($di) > 0 and $dicId != 57) {
                    $newFilters[$dicId]['range']['min'] = ((float) str_replace(",", ".", $di[0]));
                    $newFilters[$dicId]['range']['max'] = ((float) str_replace(",", ".", end($di)));
                }
                $newFilters[$dicId]['nameCategory'] = $dopInfoCategoryObject->getName();
            }
            $filters = $newFilters;
            $minMaxPrice = $q->execute("SELECT max(price) as maxPrice, min(price) as minPrice "
                            . "FROM product "
                            . "LEFT JOIN category_product AS cp ON cp.product_id = product.id "
                            . "WHERE cp.category_id IN ( " . $this->category->getId() . $idChildrenCategory . " ) and parents_id IS NULL and is_public = '1' and moder = '0'")->fetch(Doctrine_Core::FETCH_ASSOC);

            $this->category->setFiltersnew(serialize($filters));
            $this->category->setMaxPrice($minMaxPrice['maxPrice']);
            $this->category->setMinPrice($minMaxPrice['minPrice']);
            $this->category->save();
        }
    }

}
