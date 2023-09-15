<?php

class validw3Task extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "frontend"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'validw3';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [validw3|INFO] task does things.
Call it with:

  [php symfony validw3|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $products = ProductTable::getInstance()->findAll();
        foreach ($products as $product) {
            $url = "http://www.onona.ru/product/" . $product->getSlug();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
            curl_setopt($ch, CURLOPT_URL, "http://validator.w3.org/check?uri=" . urlencode($url) . "&charset=%28detect+automatically%29&doctype=Inline&group=0");
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

            $data = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<td colspan="2" class="invalid">(.*)<\/td>/Uis', $data, $errors);
            if (count($errors[0]) == 0) {
                preg_match_all('/<td colspan="2" class="valid">(.*)<\/td>/Uis', $data, $errors);
            }
            $errors = $errors[1][0];


            preg_match_all('/<div id="results_container">(.*)<\/div><!-- results_container-->/Uis', $data, $page);
            $validw3 = Validw3Table::getInstance()->findOneByPageurl($url);
            if ($validw3) {
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            } else {
                $validw3 = new Validw3();
                $validw3->setPageurl($url);
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            }
            sleep(60);
        }
         $categorys = CategoryTable::getInstance()->findAll();
        foreach ($categorys as $category) {
            $url = "http://www.onona.ru/category/" . $category->getSlug();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
            curl_setopt($ch, CURLOPT_URL, "http://validator.w3.org/check?uri=" . urlencode($url) . "&charset=%28detect+automatically%29&doctype=Inline&group=0");
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

            $data = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<td colspan="2" class="invalid">(.*)<\/td>/Uis', $data, $errors);
            if (count($errors[0]) == 0) {
                preg_match_all('/<td colspan="2" class="valid">(.*)<\/td>/Uis', $data, $errors);
            }
            $errors = $errors[1][0];



            preg_match_all('/<div id="results_container">(.*)<\/div><!-- results_container-->/Uis', $data, $page);
            $validw3 = Validw3Table::getInstance()->findOneByPageurl($url);
            if ($validw3) {
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            } else {
                $validw3 = new Validw3();
                $validw3->setPageurl($url);
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            }
            sleep(60);
        }
        
         $pages = PageTable::getInstance()->findAll();
        foreach ($pages as $page) {
            $url = "http://www.onona.ru/" . $page->getSlug();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
            curl_setopt($ch, CURLOPT_URL, "http://validator.w3.org/check?uri=" . urlencode($url) . "&charset=%28detect+automatically%29&doctype=Inline&group=0");
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

            $data = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<td colspan="2" class="invalid">(.*)<\/td>/Uis', $data, $errors);
            if (count($errors[0]) == 0) {
                preg_match_all('/<td colspan="2" class="valid">(.*)<\/td>/Uis', $data, $errors);
            }
            $errors = $errors[1][0];



            preg_match_all('/<div id="results_container">(.*)<\/div><!-- results_container-->/Uis', $data, $page);
            $validw3 = Validw3Table::getInstance()->findOneByPageurl($url);
            if ($validw3) {
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            } else {
                $validw3 = new Validw3();
                $validw3->setPageurl($url);
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            }
            sleep(60);
        }
        
        
        $articles = ArticleTable::getInstance()->findAll();
        foreach ($articles as $article) {
            $url = "http://www.onona.ru/sexopedia/" . $article->getSlug();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
            curl_setopt($ch, CURLOPT_URL, "http://validator.w3.org/check?uri=" . urlencode($url) . "&charset=%28detect+automatically%29&doctype=Inline&group=0");
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

            $data = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<td colspan="2" class="invalid">(.*)<\/td>/Uis', $data, $errors);
            if (count($errors[0]) == 0) {
                preg_match_all('/<td colspan="2" class="valid">(.*)<\/td>/Uis', $data, $errors);
            }
            $errors = $errors[1][0];



            preg_match_all('/<div id="results_container">(.*)<\/div><!-- results_container-->/Uis', $data, $page);
            $validw3 = Validw3Table::getInstance()->findOneByPageurl($url);
            if ($validw3) {
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            } else {
                $validw3 = new Validw3();
                $validw3->setPageurl($url);
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            }
            sleep(60);
        }
        
        $articlesCategorys = ArticlecategoryTable::getInstance()->findAll();
        foreach ($articlesCategorys as $articlesCategory) {
            $url = "http://www.onona.ru/sexopedia/category/" . $articlesCategory->getSlug();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
            curl_setopt($ch, CURLOPT_URL, "http://validator.w3.org/check?uri=" . urlencode($url) . "&charset=%28detect+automatically%29&doctype=Inline&group=0");
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");

            $data = curl_exec($ch);
            curl_close($ch);
            preg_match_all('/<td colspan="2" class="invalid">(.*)<\/td>/Uis', $data, $errors);
            if (count($errors[0]) == 0) {
                preg_match_all('/<td colspan="2" class="valid">(.*)<\/td>/Uis', $data, $errors);
            }
            $errors = $errors[1][0];



            preg_match_all('/<div id="results_container">(.*)<\/div><!-- results_container-->/Uis', $data, $page);
            $validw3 = Validw3Table::getInstance()->findOneByPageurl($url);
            if ($validw3) {
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            } else {
                $validw3 = new Validw3();
                $validw3->setPageurl($url);
                $validw3->setError($errors);
                $validw3->setPage($page[1][0]);
                $validw3->save();
            }
            sleep(60);
        }
        // add your code here
    }

}
