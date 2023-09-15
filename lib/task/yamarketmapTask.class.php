<?php

class yamarketmapTask extends sfBaseTask {

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
        $this->name = 'yamarketmap';
        $this->briefDescription = 'Генерирует xml-файл для размещения магазинов на карте';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] Генерирует xml-файл для размещения магазинов на карте Yandex
https://yandex.ru/sprav/support/branches/xml-feed-sprav.html
https://webmaster.yandex.ru/tools/xml-validator/ раздел справочник
Call it with:

  [php symfony yamarketmap|INFO]
EOF;
    }
    private function prepareText($text, $isName = false, $notStipTags=false) {
        if(!$notStipTags) $text = strip_tags($text);
        if(!$notStipTags) $text = htmlspecialchars($text);
        if ($isName) $text = str_replace([' – в ассортименте', ' - в ассортименте', 'в ассортименте'], '', $text);
        return $text;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $sqlBody="SELECT * FROM `shops` WHERE `is_active`=true";
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $tmp = $result->fetchAll();

        $xml_name = 'Он и Она';
        $xml_company = 'ONONA';
        $SITE_NAME = "https://onona.ru";
        $cat ='';

        $content = '<?xml version="1.0" encoding="utf-8"?>';
        $content.= '<companies>';
        foreach ($tmp as $shop) {
          $content.='<company>';
          $content.='<company-id>'.$shop['id'].'</company-id>';
          $content.='<name lang="ru">'.$shop['name'].'</name>';
          $content.='<address lang="ru">'.$shop['address'].'</address>';
          $content.='<country lang="ru">Россия</country>';
          $content.='<url>'.$SITE_NAME.'</url>';
          $content.='<working-time lang="ru">'.$shop['worktime'].'</working-time>';
          $content.='<rubric-id>184106452</rubric-id>';
          $content.='<rubric-id>184107933</rubric-id>';
          $content.='<info-page>'.$SITE_NAME.'/shop/'.$shop['slug'].'</info-page>';
          $content.='<actualization-date>'.strtotime($shop['updated_at']).'</actualization-date>';
          $content.='<coordinates><lon>'.$shop['longitude'].'</lon><lat>'.$shop['latitude '].'</lat></coordinates>';
          $content.='<photos gallery-url="'.$SITE_NAME.'/shop/'.$shop['slug'].'"><photo url="/uploads/assets/images/'.$shop['preview_image'].'" type="exterior"/></photos>';

          $content.='</company>';
        }
        $content.= '</companies>';
        die($content);
    }

}
