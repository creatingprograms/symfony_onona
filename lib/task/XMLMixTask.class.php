<?php

class XMLMixTask extends sfBaseTask {

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
        $this->name = 'XMLMix';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [XMLMix|INFO] task does things.
Call it with:

  [php symfony XMLMix|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // add your code here
        $cUrl = curl_init();
        curl_setopt($cUrl, CURLOPT_URL, 'http://mixmarket.biz/uni/gate.php?cid=1294929770&hash1=c5e47c6fca4bf20d1252341419d4b6ac&login=onona.ru&pass_=64feCXYxje&e=ask');
        curl_setopt($cUrl, CURLOPT_PORT, 80);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cUrl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)");
        curl_setopt($cUrl, CURLOPT_TIMEOUT, 5);
        $content = curl_exec($cUrl);

        curl_close($cUrl);
        preg_match_all("/<id>(.*)<\/id>/Uis", $content, $items);
        //print_r($items);

        
         // Отправка оплаченых
         
        $res.="<uni version=\"1.0\">
<condition id=\"1294929770\">
";
        $countPay = 0;
        foreach ($items[1] as $item) {
            $ordersPay = OrdersTable::getInstance()->createQuery()->where('id=' . $item)->andWhere("status LIKE  'оплачен'")->fetchOne();
            if ($ordersPay) {

                $TotalSumm = 0;
                $products_old = $ordersPay->getText();
                $products_old = $products_old != '' ? unserialize($products_old) : '';
                foreach ($products_old as $key => $productInfo):
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
                endforeach;
                $countPay++;
                $res .= '<object><id>' . $item . '</id><price>' . $TotalSumm . '</price></object>';
            }
        }

        $res.="
</condition>
</uni>";
        if ($countPay > 0) {
            //echo $res;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://mixmarket.biz/uni/gate.php?cid=1294929770&hash1=c5e47c6fca4bf20d1252341419d4b6ac&login=onona.ru&pass_=64feCXYxje&e=send');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "msg=" . $res);
            $out = curl_exec($curl);
            //echo $out;
            curl_close($curl);
        }





        
         // Отправка отменёных
         



        $res="<uni version=\"1.0\">
<condition id=\"1294929769\">
";
        $countPay = 0;
        foreach ($items[1] as $item) {
            $ordersPay = OrdersTable::getInstance()->createQuery()->where('id=' . $item)->andWhere("status LIKE  'Отмена'")->fetchOne();
            if ($ordersPay) {

                $TotalSumm = 0;
                $products_old = $ordersPay->getText();
                $products_old = $products_old != '' ? unserialize($products_old) : '';
                foreach ($products_old as $key => $productInfo):
                    $product = ProductTable::getInstance()->findOneById($productInfo['productId']);
                    $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
                endforeach;
                $countPay++;
                $res .= '<object><id>' . $item . '</id></object>';
            }
        }

        $res.="
</condition>
</uni>";
        if ($countPay > 0) {
            //echo $res;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://mixmarket.biz/uni/gate.php?cid=1294929769&hash1=264eb4219dfbea37a39f5cb6897993a4&login=onona.ru&pass_=64feCXYxje&e=send');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "msg=" . $res);
            $out = curl_exec($curl);
            //echo $out;
            curl_close($curl);
        }
    }

}
