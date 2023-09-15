<?php

class articlemoveTask extends sfBaseTask {
    const DELIMITER='|';
    const LINE_DELIMITER='}';
    const LOGNAME='articlemove.log';

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('mode', null, sfCommandOption::PARAMETER_REQUIRED, 'mode', 'test'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'articlemove';
        $this->briefDescription = 'Обрабатывает и добавляет статьи из файла в базу';
        $this->detailedDescription = "\n".
          "The [articlemove|INFO] Обрабатывает и добавляет статьи из файла в базу\n".
          "Call it with:\n\n".

          "[php symfony articlemove|INFO]\n";
    }

    protected function execute($arguments = array(), $options = array()) {
      $localPath=str_replace('lib/task', '', __DIR__).'';
      $_SERVER['DOCUMENT_ROOT']=$localPath.'web';
      $this->localPath=$localPath;
      $this->isTest=($options['env']=='dev');

      if(!$this->isTest)
        error_reporting(E_ERROR);

      $i=0;
      $databaseManager = new sfDatabaseManager($this->configuration);
      $this->q = Doctrine_Manager::getInstance()->getCurrentConnection();
      switch($options['mode']){
        case 'import':
          $this->slug=false;

          $this->getMap();

          if(file_exists($localPath.'blog_export.csv')) {
            $tmp=explode(self::LINE_DELIMITER, file_get_contents($localPath.'blog_export.csv'));
            foreach ($tmp as $key => $line) {
              if(!$key) {
                $keys = explode(self::DELIMITER, $line);
                // print_r($keys); die("\n");
                continue;
              }
              $art=array_combine($keys, explode(self::DELIMITER, $line));
              $arts[]=$art;
              $i++;
              // if($this->isTest && $i>0 ) break;
            }
          }

          ILTools::logToFile("\n=========================================================================================================\n", self::LOGNAME, true);

          foreach ($arts as $art) {
            $slug=$art['slug'];
            $image=$this->getImage($art['image'], $slug, true);
            $pre=$this->parseImages($art['introductory'], $slug);
            $pre=$this->parseLinks($pre, $art);
            $content=$this->parseImages($art['content'], $slug);
            $content=$this->parseLinks($content, $art);
            if(!isset($this->map[$art['category_id']])){//Категория статьи не найдена в карте
              $this->myLog($slug, $this->cats[$art['category_id']]['slug']."|Категория статьи '".$this->cats[$art['category_id']]['name']."' не найдена в активных, привязана к 'Без категории'");
              $art['category_id']=0;
            }
            $newCat= $this->map[$art['category_id']]['new_id'];

            $artDB=Doctrine_Core::getTable('Article')->findOneBySlug($slug);
            if(!is_object($artDB)){
              $artDB=Doctrine_Core::getTable('Article')->findOneByName($art['title']);
            }
            if(!is_object($artDB)){
              $sqlBody='';
              $artDB = new Article();
            }
            else{
              $this->myLog($slug, "Статья присутствовала на сайте, обновляем");
              $sqlBody="DELETE FROM `category_article` where `article_id`=$artDB[id];";
            }
            $artDB->setName($art['title']);
            $artDB->setSlug($art['slug']);
            $artDB->setImg($image);
            $artDB->setCreatedAt($art['published_at']);
            $artDB->setPrecontent($pre);
            $artDB->setContent($content);

            $artDB->setKeywords($art['seo_keywords']);
            $artDB->setTitle($art['seo_title']);
            $artDB->setDescription($art['seo_desc']);

            $artDB->setIsPublic($art['status']);
            $artDB->setIsNew(1);

            $artDB->save();

            $sqlBody.="INSERT INTO `category_article` (`article_id`, `articlecategory_id`) VALUES (".$artDB->getId().", $newCat);";
            $this->q->execute($sqlBody);

            if($this->isTest) die("\n");

          }

          break;

        case 'deactivate':
          $fileToParse='slugs.txt';

          if(file_exists($localPath.$fileToParse)) {
            $slugs = explode("\r\n", file_get_contents($localPath.$fileToParse));
            foreach ($slugs as $slug) {
              if($slug) $slugsIn[]="'".$slug."'";
            }
            $sqlBody = "UPDATE `article` SET `is_public`=0 WHERE `slug` NOT IN(".implode(',', $slugsIn).")";

            if($this->isTest) print_r("\n".$sqlBody."\n");
            else $this->q->execute($sqlBody);
          }
          else echo "file $localPath$fileToParse not found\n";

          die("done\n");

          break;


        default:
          die ("\nMode '".$options['mode']."' is no supported\n");
      }
    }

    private function parseImages($pageText, $slug){//Заменяем все изображения на локальные пути, заодно выкачивая их
      $re = '/<img.+src="(.+)".*>/mU';
      preg_match_all($re, $pageText, $matches, PREG_SET_ORDER, 0);
      foreach ($matches as $entity) {
        $newImageFile=$this->getImage($entity[1], $slug);
        if($newImageFile) {
          $newImg=str_replace($entity[1], $newImageFile, $entity[0]);
          $pageText=str_replace($entity[0], $newImg, $pageText);
        }
        else $pageText=str_replace($entity[0], '', $pageText);
      }

      return $pageText;

    }

    private function parseLinks($pageText, $page){//Корректируем урлы в тексте
      $re = '/<a.+href="(.+)".*>/mU';

      $slug=$page['slug'];
      preg_match_all($re, $pageText, $matches, PREG_SET_ORDER, 0);
      foreach ($matches as $match) {
        $href=str_replace(['https://blog.onona.ru', 'http://blog.onona.ru'], '', $match[1]);
        if(mb_substr($href, 0, 4)=='http') {
          $this->myLog($slug, "$href|Внешняя ссылка");
          continue;  //Никак не обрабатываем внешние ссылки
        }
        elseif(mb_substr($href, 0, 6)=='/post/'){//Внутренняя ссылка на статью
          $this->myLog($slug, "$href|Ссылка на статью");
          $href=str_replace('/post/', '/sexopedia/', $href);
          $newAnchor=str_replace($match[1], $href, $match[0]);
          $pageText = str_replace($match[0], $newAnchor, $pageText);
        }
        elseif(mb_substr($href, 0, 10)=='/category/'){//Внутренняя ссылка на категорию
          if(isset($this->linkMap[$href])){
            $this->myLog($slug, "$href|Ссылка на категорию заменена на корректную");
            $href=$this->linkMap[$href];
          }
          else{
            $this->myLog($slug, "$href|Ссылка на категорию заменена на разводящую");
            $href='/sexopedia';
          }
          $newAnchor=str_replace($match[1], $href, $match[0]);
          $pageText = str_replace($match[0], $newAnchor, $pageText);
        }
        else{//Внутренняя ссылка неизвестно куда
          $this->myLog($slug, "$href|Ссылка оставлена без изменений");
        }

      }
      // echo "\n-------------------------------------------\n";
      // echo $pageText;
      // print_r($matches);
      return $pageText;

    }

    private function getImage($src, $slug, $isMain=false){
      if($isMain) $src='/storage/app/media'.$src;
      if(mb_substr($src, 0, 4)!='http'){
        $src='https://blog.onona.ru'.$src;
      }
      $filename=md5($src);
      $tmp=explode('.', $src);
      $ext=end($tmp);
      $pref=mb_substr($slug, 0, 4);
      $localPath=$this->localPath.'web';
      if($isMain) $localPathSub='/uploads/photo';
      else $localPathSub='/frontend/images/articles/'.$pref;

      $imageName=$localPathSub.'/'.$filename.'.'.$ext;
      if(!file_exists($localPath.$localPathSub)) //Создаем папку, если ее нет
        mkdir($localPath.$localPathSub);
      if(!file_exists($localPath.$imageName)) {
        $tmp=file_get_contents($src);
        if(!$tmp) { //не загрузился
          $this->myLog($slug, "$src|Изображение не загружено");

          return false;
        }
        file_put_contents($localPath.$imageName, $tmp);
      }

      if($isMain) $imageName = str_replace($localPathSub.'/', '', $imageName);

      return $imageName;

    }

    private function getMap(){//Соответствие новых id старым
      // echo $this->localPath."\n";

      if(file_exists($this->localPath.'blog_export_cats.csv')) {
        $tmp=explode(self::LINE_DELIMITER, file_get_contents($this->localPath.'blog_export_cats.csv'));
        foreach ($tmp as $key => $line) {
          if(!$key) {
            $keys = explode(self::DELIMITER, $line);
            // print_r($keys);
            continue;
          }
          $cat=array_combine($keys, explode(self::DELIMITER, $line));
          unset($cat['status'], $cat['hidden'], $cat['content'], $cat['image'], $cat['sort_order'], $cat['created_at'], $cat['updated_at'], $cat['color'], $cat['nav'], $cat['popular'], $cat['group']);

          $cats[$cat['id']]=$cat;

        }
        $this->cats=$cats;

      }

      if($this->isTest)
        $map=[
          8  => [
            'name' => 'Анальный секс',
            'new_id' => 3,
          ],
          6  => [
            'name' => 'БДСМ',
            'new_id' => 4,
          ],
          22 => [
            'name' => 'Гигиена',
            'new_id' => 44,
          ],
          9  => [
            'name' => 'Оральные ласки',
            'new_id' => 18,
          ],
          2  => [
            'name' => 'Чувства и отношения',
            'new_id' => 19,
          ],
          20 => [
            'name' => 'Первый секс',
            'new_id' => 41,
          ],
          19=> [
            'name' => 'Позы в сексе',
            'new_id' => 10,
          ],
          -1  => [
            'name' => 'Новинки и новости',
            'new_id' => 47,
          ],
          -2  => [
            'name' => 'Секс эксперименты',
            'new_id' => 48,
          ],
          -3  => [
            'name' => 'Эротические истории',
            'new_id' => 49,
          ],
          -4  => [
            'name' => 'Женские секреты (сужение влагалища, тампоны, чаши и пр.)',
            'new_id' => 50,
          ],
          -5  => [
            'name' => 'Подарки',
            'new_id' => 51,
          ],
          0 => [
            'name' => 'Без названия',
            'new_id' => 52,
          ],
        ];
      else
        $map=[
          8  => [
            'name' => 'Анальный секс',
            'new_id' => 3,
          ],
          6  => [
            'name' => 'БДСМ',
            'new_id' => 4,
          ],
          22 => [
            'name' => 'Гигиена',
            'new_id' => 44,
          ],
          9  => [
            'name' => 'Оральные ласки',
            'new_id' => 18,
          ],
          2  => [
            'name' => 'Чувства и отношения',
            'new_id' => 19,
          ],
          20 => [
            'name' => 'Первый секс',
            'new_id' => 41,
          ],
          19=> [
            'name' => 'Позы в сексе',
            'new_id' => 10,
          ],
          8  => [
            'name' => '',
            'new_id' => 3,
          ],
          -1  => [
            'name' => 'Новинки и новости',
            'new_id' => 46,
          ],
          -2  => [
            'name' => 'Секс эксперименты',
            'new_id' => 47,
          ],
          -3  => [
            'name' => 'Эротические истории',
            'new_id' => 48,
          ],
          -4  => [
            'name' => 'Женские секреты (сужение влагалища, тампоны, чаши и пр.)',
            'new_id' => 49,
          ],
          -5  => [
            'name' => 'Подарки',
            'new_id' => 50,
          ],
          0 => [
            'name' => 'Без названия',
            'new_id' => 51,
          ],
        ];

      $sqlBody="SELECT * FROM `articlecategory` where `is_public`=1";

      $catsDB=$this->q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);

      foreach ($map as $key => $mapLine) {
        $linkMap['/category/'.$cats[$key]['slug']]='/sexopedia/category/'.$catsDB[$mapLine['new_id']]['slug'];
      }
      $linkMap['/category/']='/sexopedia';

      $this->map=$map;
      $this->linkMap=$linkMap;

    }

    private function myLog($slug, $data){
      if($this->slug!=$slug){
        $this->slug=$slug;
        ILTools::logToFile("\n---------------------------------------------------------------------\n".'https://blog.onona.ru/post/'.$slug."\n", self::LOGNAME, true);
      }
      ILTools::logToFile("$data\n", self::LOGNAME, true);


    }
}
?>
