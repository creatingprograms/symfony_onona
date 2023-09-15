<?php

class sitemapTask extends sfBaseTask {

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
        $this->name = 'sitemap';
        $this->briefDescription = '';
        $this->detailedDescription =
        "\nThe [sitemap|INFO] task is geneterate sitemap.xml for onona project.".
        "\nCall it with:\n[php symfony sitemap|INFO]";
    }

    protected function execute($arguments = array(), $options = array()) {
      $this->counter=0;
        $isTest =false;
        // $isTest =true;
        if($isTest) echo "\nStart at ".date('d.m.Y H:i:s');
        ini_set("max_execution_time", 1800);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        /* sitemap */

        $sitemap =
          "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
            "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">".
              $this->echoUrl('','1.0');


        $catalog = CategoryTable::getInstance()->findAll();
        foreach ($catalog as $category) {
            if ($category->getId() != '135'):
              if(!$category->getIsPublic()) continue;
                $sitemap.=
                  $this->echoUrl('category/'. $category->getSlug(),($category->getSlug() != '' ? '0.6' : '0.4'));
            endif;
            if($isTest) break; //если тест выходим после первой итерации
        }

        $catalog = CatalogTable::getInstance()->findAll();
        foreach ($catalog as $category) {
            if(!$category->getIsPublic()) continue;
              $sitemap.=
                $this->echoUrl('catalog/'. $category->getSlug(),($category->getSlug() != '' ? '0.6' : '0.4'));
            if($isTest) break; //если тест выходим после первой итерации
        }
        $catalog = CollectionTable::getInstance()->findAll();
        foreach ($catalog as $category) {
            if(!$category->getIsPublic()) continue;
              $sitemap.=
                $this->echoUrl('collection/'. $category->getSlug(),($category->getSlug() != '' ? '0.6' : '0.4'));
            if($isTest) break; //если тест выходим после первой итерации
        }
        $catalog = ManufacturerTable::getInstance()->findAll();
        foreach ($catalog as $category) {
          // die('---------|'.$category->getIsPublic().'|'.$category->getSlug()."\n");
            if(!$category->getIsPublic()) continue;
              $sitemap.=
                $this->echoUrl('manufacturer/'. $category->getSlug(),($category->getSlug() != '' ? '0.6' : '0.4'));
            if($isTest) break; //если тест выходим после первой итерации
        }
        $catalog = TestsTable::getInstance()->findAll();
        foreach ($catalog as $category) {
          // die('---------|'.$category->getIsPublic().'|'.$category->getSlug()."\n");
            if(!$category->getIsPublic()) continue;
              $sitemap.=
                $this->echoUrl('tests/'. $category->getSlug(),($category->getSlug() != '' ? '0.6' : '0.4'));
            if($isTest) break; //если тест выходим после первой итерации
        }


        $products = ProductTable::getInstance()->findAll();
        foreach ($products as $product) {
            $categoryProduct = $product->getCategoryProducts();
            if ($categoryProduct[0]->getId() != 135) {
              if(!$product->getIsPublic()) continue;
                $sitemap.=
                  $this->echoUrl('product/' . $product->getSlug(), ($product->getSlug() != '' ? '0.6' : '0.4'));
            }
            if($isTest) break; //если тест выходим после первой итерации
        }

        $pages = PageTable::getInstance()->findAll();
        $excludesList=[
          'IzmaYlovskoe_shosse',
          'Nosovihinskoe_shosse',
          'LigovskiY_prospekt',
          'magazin-dlya-vzroslyh-on-i-ona-v-g-pushkino',
          'set-magazinov-dlya-vzroslyh-on-i-ona-v-g-krasnodar',
          'banner-nad-shapkoi',
          'Adresa_magazinov_v_Rossii',
          'dostavka-i-oplata',
          'articleTest',
          'programma-on-i-ona-bonus-1',
          'podarochnaya_upakovka',
          'programma-on-i-ona-bonus2',
          'lk',
          'login',
          'error404',
          'mainNew',
          'mainNewDis',
          'sexshop',
          'main',
          'ulitsa_SHirokaya',
          'magazin-on-i-ona-v-moskve-schitnikovo',
          'magazin-balashiha-kvartal-shhitnikovo-46-a',
        ];
        foreach ($pages as $page) {
          if(!$page->getIsPublic()) continue;
          if (in_array($page->getSlug(), $excludesList)) continue; //Пропускаем страницы из списка

            $sitemap.=
              $this->echoUrl($page->getSlug(), ($page->get('sitemapRate') != '' ? $page->get('sitemapRate') : '0.6'));
            if($isTest) break; //если тест выходим после первой итерации
        }

        $sitemap.=
          $this->echoUrl("sexopedia" , '0.6');

        $artCategory = ArticlecategoryTable::getInstance()->findAll();
        foreach ($artCategory as $page) {
            $sitemap.=
              $this->echoUrl("sexopedia/category/" . $page->getSlug(), '0.6');
            if($isTest) break; //если тест выходим после первой итерации
        }
        $article = ArticleTable::getInstance()->findAll();
        foreach ($article as $page) {
            $sitemap.=
              $this->echoUrl("sexopedia/" . $page->getSlug(), '0.6');
            if($isTest) break; //если тест выходим после первой итерации
        }

        $shops = ShopsTable::getInstance()->findAll();
        foreach ($shops as $page) {
          if(!$page->getIsActive()) continue;
            $sitemap.=
              $this->echoUrl("shops/" . $page->getSlug(), '0.6');
            if($isTest) break; //если тест выходим после первой итерации
        }

        $sitemap.='</urlset>';
        $filePath='/var/www/ononaru/data/www/onona.ru/sitemap.xml';
        if($isTest)
          $filePath='/srv/www/pub/dev.onona.ru/web/sitemap_test.xml';
        $file = fopen($filePath, "w+");
        if (!$file) {
            echo("Ошибка открытия файла");
        } else {
            ftruncate($file, 0);
            fputs($file, $sitemap);
        }
        fclose($file);
        if($isTest) echo "\nStart at ".date('d.m.Y H:i:s')."\n";
        echo $this->counter." urls\n";
    }
    static $counter;
    protected function echoUrl($url, $priority){
      $isTest=false;
      // $isTest=true;
      $this->counter++;
      $slug=str_replace('&', '&amp;', $url);
      $slug=mb_strtolower($slug, 'utf-8');
      return
        "<url>".
          "<loc>https://onona.ru/" . $slug . "</loc>".
          // "<lastmod>" . date('Y-m-d') . "</lastmod>".
          // "<changefreq>weekly</changefreq>".
          // "<priority>$priority</priority>".
        "</url>".($isTest ? "\n" : '');
    }

}
