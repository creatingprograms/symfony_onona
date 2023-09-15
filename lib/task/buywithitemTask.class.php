<?php

class buywithitemTask extends sfBaseTask {

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
        $this->name = 'buywithitem';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [buywithitem|INFO] task does things.
Call it with:

  [php symfony buywithitem|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $orders = OrdersTable::getInstance()->createQuery()->fetchArray();
        foreach ($orders as $numOrder => $order) {
            //print_r(unserialize($order['text']));

            foreach (unserialize($order['text']) as $numProduct => $product) {
                if ($product['productId'] == 0 and $product['article'] != "д1") {
                    unset($productgetDb);
                    $productgetDb = ProductTable::getInstance()->createQuery()->where("code = '" . $product['article'] . "'")->fetchOne(); //findOneByCode($product['article']);
                    if ($productgetDb)
                        $product['productId'] = $productgetDb->getId();
                }
                if ($product['productId'] != 0 and $product['productId'] != "14613")
                    $bestsellersArray[$product['productId']] = $bestsellersArray[$product['productId']] + 1;
                foreach (unserialize($order['text']) as $numProductInfo => $productInfo) {
                    if ($productInfo['productId'] == 0 and $productInfo['article'] != "д1") {
                        unset($productgetDb);
                        $productgetDb = ProductTable::getInstance()->createQuery()->where("code = '" . $productInfo['article'] . "'")->fetchOne();
                        if ($productgetDb)
                            $productInfo['productId'] = $productgetDb->getId();
                    }
                    if ($product['productId'] != $productInfo['productId'] and $productInfo['productId'] != 0 and $productInfo['productId'] != "14613")
                        $arrayProd[$product['productId']][$productInfo['productId']] = $arrayProd[$product['productId']][$productInfo['productId']] + 1;
                }
            }
        }

        arsort($bestsellersArray);
        /*if (count($bestsellersArray) > 10) {
            $setting = csSettingTable::getInstance()->findOneBySlug("bestsellersProducts");
            $setting->setValue(implode(",", array_keys(array_slice($bestsellersArray, 0, 20, true))));
            $setting->save();
        }*/
        /* foreach ($bestsellersArray as $prodId => $bestsellers) {
          $productSet = ProductTable::getInstance()->findOneById($prodId);
          if ($productSet) {
          $productSet->set('countsell', $bestsellers);
          $productSet->save();
          }
          //$arrayProd[$prodId] = $buywithitem;
          } */

//        $newcat_cache_dir = '/var/www/ononaru/data/www/cache/newcat/*/template';
//        $cache = new sfFileCache(array('cache_dir' => $newcat_cache_dir)); // Use the same settings as the ones defined in the frontend factories.yml
//        $cache->removePattern('/csSettings/*');

        foreach ($arrayProd as $prodId => $buywithitem) {
            arsort($buywithitem);
            $productSet = ProductTable::getInstance()->findOneById($prodId);
            if ($productSet) {
                $productSet->set('countsell', $bestsellersArray[$prodId]);
                $productSet->set('buywithitem', serialize($buywithitem));
                $productSet->save();
            }
            //$arrayProd[$prodId] = $buywithitem;
        }
        // add your code here
    }

}
