<?php

class pageComponents extends sfComponents {

    public function executeSubpage(sfWebRequest $request){//Выводит страницу
      $tmp=Doctrine_Core::getTable('Page')->findOneBySlug(array($this->page));
      if(is_object($tmp))
        $content=$tmp->getContent();
      else $content='';
      $this->content=$content;
    }
    public function executeLeftBanners(sfWebRequest $request){//Выводит страницу
      $tmp=Doctrine_Core::getTable('Page')->findOneBySlug('left_banners');
      if(is_object($tmp))
        $content=$tmp->getContent();
      else $content='';
      $this->content=$content;
    }

    public function executeGetFortune(sfWebRequest $request){//Колесо удачи
      $this->needToShow = $_COOKIE['no-show-wheel-2']<'5';
      sfContext::getInstance()->getResponse()->setCookie('no-show-wheel-2', 1*$_COOKIE['no-show-wheel-2']+1, time() + 60 * 60 * 24 * 365, '/', ".onona.ru");
      // $this->needToShow=true;
      // die('~~!!');
    }
    public function executeGet300(sfWebRequest $request){//Всплывающее окно 300 бонусов
      // die('<pre>'.print_r($_COOKIE['no-show-300'], true));
      $this->needToShow=$_COOKIE['no-show-300']!='true';
      // $this->needToShow=true;
      // die('~~!!');
    }
    public function executeShopsList(sfWebRequest $request){//Список магазинов
        if(!$this->is_krasnodar){
            $shops=ShopsTable::getInstance()
                ->createQuery()
                ->where('is_active=1 and city_id IN('.implode(', ', $this->city_id).')');
            if(isset($this->page) && isset($this->limit) && $this->limit)
                $shops = $shops->offset($this->page*$this->limit);
            if($this->limit)
                $shops = $shops->limit($this->limit);
            $this->shops=$shops->execute();

        }
        if(isset($this->limit) && $this->limit){
            if(!isset($this->offset)) $this->offset=0;
            $shopCount=ShopsTable::getInstance()
                ->createQuery()
                ->where('is_active=1 and city_id IN('.implode(', ', $this->city_id).')')
                // ->offset($this->offset)
                // ->limit($this->limit)
                ->execute()
            ;
            $numPages=ceil(sizeof($shopCount)/$this->limit);
            if($this->page<$numPages-1)
                $this->showMore=[
                    'page'=>$this->page+1,
                    'cities' => implode(', ', $this->city_id),
                    'limit' => $this->limit,
                ];
            // die('<h1> Count:'.sizeof($shopCount).'|$numPages='.$numPages);
        }
    }

    public function executeShopsDelivery(sfWebRequest $request){//Список магазинов для доставки
        $shops=ShopsTable::getInstance()
            ->createQuery()
            ->where('is_active=1 and city_id IN(3, 5, 126, 144, 59)');
        $this->shops=$shops->execute();
    }

    public function executeHeaderscripts(sfWebRequest $request){//Скрипты в хедере  - счетчики и пр.
    }

    public function executeFooterscripts(sfWebRequest $request){//Скрипты в футуре - счетчики и пр.
    }

    public function executeFooter(sfWebRequest $request){
        $subMenu = false;
        $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'footer_frontend'")->addWhere("is_active = 1")->addWhere("parents_id = 0 OR parents_id iS NULL")->execute();
        if(sizeof($menu)) foreach ($menu as $link){
            $tmp=[
                'link' => mb_strtolower($link->getUrl()),
                'name' => $link->getText(),
                'id'=> $link->getId(),
                'is_target_blank' => $link->getTargetBlank(),
                'submenu' => $this->getSubMenu($link->getId(), 'footer_frontend'),
            ];
            $tmp['is_noindex']=false;
            if(false !== stripos($tmp['link'], '//')) $tmp['is_noindex']=true;//Если есть двойная косая - ссылка внешняя
            $subMenu[]=$tmp;
        }
        $this->menu=$subMenu;
        // $this->csrf = new CSRFToken($this);

    }

    private function getBrands($brandsIdsList){//Формирует массив привязанных к категории брендов для меню
        $arTmp=explode('|', $brandsIdsList);
        $arIds[]=-1;
        foreach ($arTmp as $key => $id) {
            $arIds[]=1*$id;
        }

        $brands = ManufacturerTable::getInstance()
            ->createQuery()
            ->where("is_public = 1")
            ->addWhere("id IN(".implode(', ', $arIds).")")
            ->orderBy("position DESC")
            ->execute();

        foreach ($brands as $brand) {
            $image=false;
            if($brand->getImage()) $image='/uploads/manufacturer/'.$brand->getImage();
            if(!$image){
                $reg='/<img.+src="(.+\.png)".*>/mU';//Выдергивает из контента png
                $res=preg_match($reg, $brand->getContent(), $matches);
                if($res) $image=$matches[1];
            }
            if(!$image) $image='/frontend/images/noimage-brand.png';
            $arBrands[]=[
                'url' => '/manufacturer/'.mb_strtolower($brand->getSlug()),
                'image' => $image,
                'name' => $brand->getName(),
            ];
        }
        // die('<pre>'.print_r(implode(', ', $arBrands), true));

        return $arBrands;
    }

    private function getCurrentCategoryLink($prefix) {
        $categorySlug = $this->getRequestParameter('slug');
        return mb_strtolower('/' . $prefix . '/' . $categorySlug);
    }

    private function getCatalogs($includeNovice=true){
        $categoryLink = $this->getCurrentCategoryLink('catalog');

        $menu = CatalogTable::getInstance()
            ->createQuery()
            ->where("is_public = 1")
            ->execute();

        foreach ($menu as $link){
            $menuItemLink = mb_strtolower('/catalog/'.$link->getSlug());

            $isActive = false;
            if($menuItemLink === $categoryLink) {
                $isActive = true;
            }

            $currentSubmenuItems = $this->getSubCats($link->getSlug(), true);
            foreach ($currentSubmenuItems as $currentSubmenuItem) {
                if($currentSubmenuItem['isActive']) {
                    $isActive = true;
                    break;
                }
            }

            $subMenu[]=[
                'link' => $menuItemLink,
                'name' => $link->getMenuName(),
                'is_target_blank' => false,
                'submenu' => $currentSubmenuItems,
                'is_noindex' => false,
                'name_part' => $link->getName(),
                'name_part_2' => $link->getDescription(),
                // 'brands' => $this->getBrands($link->getBrandsIds())
                'class' => $link->getClass(),
                'isActive' => $isActive,
            ];

        }
        if($includeNovice)
            $subMenu[]=[
                'link' => '/category/dlya_novichkov',
                'name' => 'Для новичков',
                'is_target_blank' => false,
                'is_noindex' => false,
                'name_part' => 'Для новичков',
                'name_part_2' => '',
                'class' => '-forNovice',
                'submenu' => [
                    [
                        'name' => 'Для мужчин',
                        'link' => '/category/dlya_novichkov?novice=for_her',
                        'is_target_blank' => false,
                        'is_noindex' => false,
                    ],
                    [
                        'name' => 'Для женщин',
                        'link' => '/category/dlya_novichkov?novice=for_she',
                        'is_target_blank' => false,
                        'is_noindex' => false,
                    ],
                    [
                        'name' => 'Для двоих',
                        'link' => '/category/dlya_novichkov?novice=for_pairs',
                        'is_target_blank' => false,
                        'is_noindex' => false,
                    ],
                ],
            ];
        return $subMenu;
    }

    private function getSubCatsById($categoryId){//Подразделы категории
        // return false;
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sqlBody="SELECT id, name, slug FROM category WHERE parents_id=$categoryId AND is_public=1";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $categorys = $result->fetchAll();

        $categoryLink = $this->getCurrentCategoryLink('category');;

        foreach ($categorys as  $value) {
            $itemLink = mb_strtolower('/category/'.$value['slug']);
            $isActive = false;
            if($categoryLink === $itemLink) {
                $isActive = true;
            }

            $tmp=[
                'link' => mb_strtolower('/category/'.$value['slug']),
                'name' => $value['name'],
                'is_target_blank' => false,
                'is_noindex' => false,
                'isActive' => $isActive,
            ];
            // if($thirdLevel) $tmp['submenu'] = $this->getSubCatsById($value['id']);
            $subMenu[]=$tmp;
        }

        return $subMenu;
    }

    private function getSubCats($catalogSlug, $thirdLevel=false){
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute(
            "SELECT category.slug, category.name, category.id, catalog.slug as catslug".
            " FROM  `catalog`".
            " LEFT JOIN category_catalog ON category_catalog.catalog_id = catalog.id".
            " LEFT JOIN category ON category_catalog.category_id = category.id".
            " WHERE catalog.is_public =  '1'".
            " AND catalog.slug =  '$catalogSlug'".
            " AND category.is_public =  '1'".
            " ORDER BY catalog.position ASC , category.position ASC ");

        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $categorys = $result->fetchAll();

        $categoryLink = $this->getCurrentCategoryLink('category');;

        foreach ($categorys as  $value) {
            $itemLink = mb_strtolower('/category/'.$value['slug']);
            $isActive = false;
            if($categoryLink === $itemLink) {
                $isActive = true;
            }

            $tmp=[
                'link' => $itemLink,
                'name' => $value['name'],
                'is_target_blank' => false,
                'is_noindex' => false,
                'id' => $value['id'],
                'isActive' => $isActive,
            ];

            if($thirdLevel) {
                $tmp['submenu'] = $this->getSubCatsById($value['id']);
                foreach ($tmp['submenu'] as $currentSubmenuItem) {
                    if ($currentSubmenuItem['isActive']) {
                        $tmp['isActive'] = true;
                        break;
                    }
                }
            }

            $subMenu[]=$tmp;
        }
        // die('<pre>'.print_r($subMenu, true));

        return $subMenu;

    }

    private function getSubMenu($menuId, $positionmenu='main_frontend'){
        $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = '$positionmenu'")->addWhere("is_active = 1")->addWhere("parents_id =".$menuId)->execute();
        $subMenu= false;
        foreach ($menu as $link){
            $tmp=[
                'link' => mb_strtolower($link->getUrl()),
                'name' => $link->getText(),
                'is_target_blank' => $link->getTargetBlank(),
            ];
            $tmp['is_noindex']=false;
            if(false !== stripos($tmp['link'], '//')) $tmp['is_noindex']=true;//Если есть двойная косая - ссылка внешняя


            $subMenu[]=$tmp;
        }
        return $subMenu;

    }

    public function executeCatalogSidebarFull(sfWebRequest $request){
      $menu=$this->getCatalogs();
      $this->menu=$menu;
    }

    public function executeCatalogSidebar(sfWebRequest $request){
      if(!isset($this->isShowFull)) $this->isShowFull=false;
      $menu=$this->getCatalogs(false);
      if(!$this->isShowFull) foreach ($menu as $key => $value) {
        if(!$value['isActive']) unset($menu[$key]);
      }
      // die('<pre>'.print_r(array_keys($this->menu[0]), true));
      $this->menu=$menu;
    }

    public function executeTopMenu(sfWebRequest $request){
        $menu = MenuTable::getInstance()->createQuery()->where(" positionmenu = 'top_menu_new_frontend' ")->addWhere("is_active = 1")->execute();
        foreach ($menu as $link){
            $subMenu[]=[
                'link' => mb_strtolower($link->getUrl()),
                'name' => $link->getText(),
            ];
        }
        // die('<pre>'.print_r($subMenu, true));
        $this->menu=$subMenu;
    }

    public function executeMobileMenu(sfWebRequest $request){
        $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'main_frontend'")->addWhere("is_active = 1")->addWhere("parents_id = 0 OR parents_id iS NULL")->execute();

        foreach ($menu as $link){
            $url=$link->getUrl();
            if($url=='/catalog'){
                $tmp=[
                    'link' => $url,
                    'name' => $link->getText(),
                    'submenu' => $this->getCatalogs(),
                    'is_catalog' => true,
                    'is_noindex' => false,
                    'is_target_blank' => $link->getTargetBlank(),
                ];
                $subMenu[]=$tmp;
            }
            else{
                $tmp=[
                    'link' => mb_strtolower($url),
                    'name' => $link->getText(),
                    'id'=> $link->getId(),
                    'is_target_blank' => $link->getTargetBlank(),
                    'submenu' => $this->getSubMenu($link->getId(), 'main_frontend'),
                ];
                $tmp['is_noindex']=false;
                if(false !== stripos($tmp['link'], '//')) $tmp['is_noindex']=true;//Если есть двойная косая - ссылка внешняя
                $subMenu[]=$tmp;
            }
        }
        $this->menuNav=$subMenu;
        $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'mobile_frontend'")->addWhere("is_active = 1")->addWhere("parents_id = 0 OR parents_id iS NULL")->execute();
        if (sizeof($menu)) foreach ($menu as $link){
            $url=$link->getUrl();
            $tmp=[
                'link' => mb_strtolower($url),
                'name' => $link->getText(),
                'id'=> $link->getId(),
                'is_target_blank' => $link->getTargetBlank(),
            ];
            $tmp['is_noindex']=false;
            if(false !== stripos($tmp['link'], '//')) $tmp['is_noindex']=true;//Если есть двойная косая - ссылка внешняя
            $botMenu[]=$tmp;
        }
        $this->menuBot=$botMenu;
    }

    public function executeTopMenuMainNew(sfWebRequest $request){ //Новая итерация меню
        $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'main_frontend'")->addWhere("is_active = 1")->addWhere("parents_id = 0 OR parents_id iS NULL")->execute();

        foreach ($menu as $link){
            $url=$link->getUrl();
            if($url=='/catalog'){
                $tmp=[
                    'link' => mb_strtolower($url),
                    'name' => $link->getText(),
                    'submenu' => $this->getCatalogs(),
                    'is_catalog' => true,
                    'is_noindex' => false,
                    'is_target_blank' => $link->getTargetBlank(),
                    // 'submenu' => $this->getSubCats(mb_substr($url, 2)),
                ];
                $subMenu[]=$tmp;
            }
            else{
                $tmp=[
                    'link' => mb_strtolower($url),
                    'name' => $link->getText(),
                    'id'=> $link->getId(),
                    'is_target_blank' => $link->getTargetBlank(),
                    'submenu' => $this->getSubMenu($link->getId(), 'main_frontend'),
                ];
                $tmp['is_noindex']=false;
                if(false !== stripos($tmp['link'], '//')) $tmp['is_noindex']=true;//Если есть двойная косая - ссылка внешняя
                $subMenu[]=$tmp;
            }
        }
        $this->menu=$subMenu;

    }

    public function executeTopMenuMain(sfWebRequest $request){
        $menu = MenuTable::getInstance()->createQuery()->where("positionmenu = 'main_frontend'")->addWhere("is_active = 1")->addWhere("parents_id = 0 OR parents_id iS NULL")->execute();

        foreach ($menu as $link){
            $url=$link->getUrl();
            if($url=='/catalog'){
                $tmp=[
                    'link' => $url,
                    'name' => $link->getText(),
                    'submenu' => $this->getCatalogs(),
                    'is_catalog' => true,
                    'is_noindex' => false,
                    'is_target_blank' => $link->getTargetBlank(),
                    // 'submenu' => $this->getSubCats(mb_substr($url, 2)),
                ];
                $subMenu[]=$tmp;
            }
            else{
                $tmp=[
                    'link' => mb_strtolower($url),
                    'name' => $link->getText(),
                    'id'=> $link->getId(),
                    'is_target_blank' => $link->getTargetBlank(),
                    'submenu' => $this->getSubMenu($link->getId(), 'main_frontend'),
                ];
                $tmp['is_noindex']=false;
                if(false !== stripos($tmp['link'], '//')) $tmp['is_noindex']=true;//Если есть двойная косая - ссылка внешняя
                $subMenu[]=$tmp;
            }
        }
        $this->menu=$subMenu;
    }

    public function executeBlockSliders(sfWebRequest $request){
        if(!isset($this->positionpage)) $this->positionpage='main_page';
        if(!isset($this->half_size)) $this->half_size=false;
        if($this->is_onlymoscow)
            $slides = SlidersTable::getInstance()
                ->createQuery()
                ->where("is_active='1'")
                ->andWhere("positionpage='".$this->positionpage."'")
                ->andWhere("positionpage='".$this->positionpage."'")
                ->andWhere("is_small=0")
                // ->andWhere("is_onlymoscow='".$this->is_onlymoscow."'")
                ->orderBy("position")
                ->execute();
        else
            $slides = SlidersTable::getInstance()
                ->createQuery()
                ->where("is_active='1'")
                ->andWhere("positionpage='".$this->positionpage."'")
                ->andWhere("is_onlymoscow=0")
                ->andWhere("is_small=0")
                ->orderBy("position")
                ->execute();
        if($this->half_size){
          $half_slides=SlidersTable::getInstance()
              ->createQuery()
              ->where("is_active='1'")
              ->andWhere("positionpage='".$this->positionpage."'")
              ->andWhere("is_small=1")
              ->orderBy("position")
              ->limit(2)
              ->execute();
          if(sizeof($half_slides)) $this->half_slides=$half_slides;
          else $this->half_size=false;
        }

        $this->slides = $slides;
    }

    public function executeSvg(sfWebRequest $request){
        //Выводит svg из вьюхи
    }

    public function executeTestsSidebar(sfWebRequest $request){
        //Выводит левый блок в тестах
    }

    public function executeDlhLogistic(sfWebRequest $request) {//Выводит калькулятор сети доставки Связной/Евросеть
    }

    public function executeFormVoprosSeksologu(sfWebRequest $request) {//Форма вопрос сексологу
    }

    public function executeShopsMapsRf(sfWebRequest $request) {//Выводит на странице доставки карту со списком точек доставки
      $q = Doctrine_Manager::getInstance()->getCurrentConnection();
      $sqlBody=
        "SELECT c.`id`, c.`name`, COUNT(p.`id`) as `pid`, COUNT(s.`id`) as `sid` "
        // "SELECT c.`id`, c.`name`, p.`id` as `pid`, s.`id` as `sid` "
        ." FROM `city` c "
        ." LEFT JOIN `pickpoint` p ON (p.`city_id`=c.`id` AND p.`is_public`=1) "
        ." LEFT JOIN `shops` s ON (s.`city_id` = c.`id` AND s.`is_active` =1) "
        // ." WHERE c.`id`=3 "
        ." GROUP BY c.`id`, c.`name` "
        ." HAVING `sid`>0 OR `pid`>0 "
        ." ORDER BY c.`name` "
        ."";
      // $cities=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_UNIQUE);
      $cities=$q->execute($sqlBody)->fetchAll(Doctrine_Core::FETCH_ASSOC);


      $this->cities=$cities;
      // $this->pickPoint=$pickPoint;
      // $this->shops=$shops;

      // die('<pre>!'.print_r([$shops, $pickPoint, $cities], true));
      // die($sqlBody.';');

    }

    public function executeShopsMapsRf_____old(sfWebRequest $request) {//Старая версия
        $htmlMaps = "";
        $pickPoint = PickpointTable::getInstance()->findAll();
        $cities = CityTable::getInstance() ->createQuery() ->where() ->orderBy("name ASC") ->execute();
        foreach ($cities as $key => $value) {
            $citiesList[$value->getId()]=[
                'name' => $value->getName(),
                'is_used' => false,
            ];
        }

        $shops = ShopsTable::getInstance()->findAll();
        $infoDiv='<div id="points-info" style="display: none;">';//style="display: none;"
        $htmlMaps.=

            '~|script'." ymaps.ready(function () {".
            " var myMap = new ymaps.Map('YMapsID', {".
            " center: [63.765152, 99.449527],".
            " zoom: 2"." });".
            " myMap.geoObjects";
        foreach ($pickPoint as $point) {
            $workTime = explode(",", $point->getWorkTime());
            if ($workTime[0] == $workTime[6]) {
                $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
            } elseif ($workTime[0] == $workTime[4]) {
                $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                if ($workTime[5] == $workTime[6]) {
                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                } else {
                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                }
            } else {
                $strWorktime = "Пн " . $workTime[0] . "<br />";
                $strWorktime.="Вт " . $workTime[1] . "<br />";
                $strWorktime.="Ср " . $workTime[2] . "<br />";
                $strWorktime.="Чт " . $workTime[3] . "<br />";
                $strWorktime.="Пн " . $workTime[4] . "<br />";
                if ($workTime[5] == $workTime[6]) {
                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                } else {
                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                }
            }


            if ($point->getStatus() != 3)
                $infoDiv.=
                    '<div class="city-'.$point->getCity_id().'"'.
                    ' data-pay="'.($point->getCash() != "" ? "Наличными" : "") . "" . ($point->getCard() != "" ? ", Банковской картой" : "").'"'.
                    ' data-worktime="'.$strWorktime.'"'.
                    ' data-metro="'.$point->getMetro().'"'.
                    ' data-address="'.$point->getAddress().'"'.
                    ' data-imagesize="[41, 35]"'.
                    ' data-image="http://pickpoint.ru/i/markers/'.($point->getTypeTitle() == "ПВЗ" ? "office.png" : "postamat.png").'"'.
                    ' data-lat="'.$point->getLatitude().'"'.
                    ' data-lon="'.$point->getLongitude().'"'.
                    ' data-name="'.$point->getName().'"'.
                    '></div>';
            $citiesList[$point->getCity_id()]['is_used']= true;

        }

        foreach ($shops as $point) {
            $strWorktime = $point->getWorkTime();
            $infoDiv.=
                '<div class="city-'.$point->getCity_id().'"'.
                ' data-pay=""'.
                ' data-worktime="'.$strWorktime.'"'.
                ' data-metro="'.$point->getMetro().'"'.
                ' data-address="'.$point->getAddress().'"'.
                ' data-imagesize="[28, 28]"'.
                ' data-image="https://onona.ru/favicon.ico"'.
                ' data-lat="'.$point->getLatitude().'"'.
                ' data-lon="'.$point->getLongitude().'"'.
                ' data-name="'.$point->getName().'"'.
                '></div>';
            $citiesList[$point->getCity_id()]['is_used']= true;
            $htmlMaps.=
                " .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {".
                " balloonContent: '<h3>" . $point->getName() .
                "</h3><div class=\"address\">" . ($point->getMetro() != "" ? "<p>м. " . $point->getMetro() . "</p>" : "") .
                "<p>" . $point->getAddress() . "</p><p><b>Время работы: </b><br />" . $strWorktime . "</p></div>'".
                " }, {".
                " iconLayout: 'default#image',".
                " iconImageHref: 'https://onona.ru/favicon.ico',".
                " iconImageSize: [28, 28],".
                " iconImageOffset: [-3, -42]".
                " }))";
        }
        $select='<select id="cities-list">';
        foreach ($citiesList as $key => $value) {
            if( !$value['is_used'] ) continue;
            /*Если точек в городе нет*/
            /* 3 это defaultCity - Moscow*/
            $select.='<option value="'.$key.'"'.($key==3 ? ' selected="true"' : '').'>'.$value['name'].'</option>';
        }
        $select.='</select>';

        $htmlMaps.= " });".
            " script|~".
            "";
        $infoDiv.='</div>';
        $infoDiv.=
            '<div id="YMapsID" class="map-box"></div>'.
            //  "<script src=\"//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full\" type=\"text/javascript\"></script>"
            '';

        $this->htmlMaps = $select.$infoDiv/*.$htmlMaps*/;
        //$htmlMaps;
    }

    /* ********************************************** old **************************************************** */
    public function executeTopShops(sfWebRequest $request){
        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $sqlBody=
            "SELECT id, metro, slug, count(id) as count_shops, iconmetro ".
            "FROM shops ".
            "WHERE is_active=1 ".
            "AND city_id in(3, 5, 126, 144, 59) ".
            "AND id<>16 ".
            "GROUP BY metro ".
            "ORDER BY metro ".
            "";
        $result = $q->execute($sqlBody);
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $shops = $result->fetchAll();
        foreach ($shops as $key => $metro) {
            if($metro['count_shops']<2) continue;
            $sqlBody =
                "SELECT id, metro, slug, street, house ".
                "FROM shops ".
                "WHERE is_active=1 ".
                "AND city_id in(3, 5, 126, 144, 59) ".
                "AND id<>16 ".
                "AND metro='".$metro['metro']."'".
                "";
            $tmp=$q->execute($sqlBody);
            $tmp->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $subShops = $tmp->fetchAll();
            $shops[$key]['shops']=$subShops;
            // $shops[$key]['shops']=$sqlBody;
        }
        /*
        $shops=ShopsTable::getInstance()
          ->createQuery()
          ->where(" is_active=1 and city_id in (3, 5, 126, 144, 59) and id<>16")
          ->orderBy("metro")
          ->groupBy('metro')
          ->execute()
        ;*/
        $this->shops=$shops;
    }

    public function executeSliderShops(sfWebRequest $request){
        if(implode(', ', $this->city_id)<>'11')
            $this->shops=ShopsTable::getInstance()
                ->createQuery()
                ->where('is_active=1 and city_id IN('.implode(', ', $this->city_id).')'.($this->is_onmain ? ' and is_onmain=1' : ''))
                ->execute();
        else {
            $this->shopsVib=ShopsTable::getInstance()
                ->createQuery()
                ->where('is_active=1 and city_id =11 and is_onmain=1' )
                ->execute();
            $this->shopsAdam=ShopsTable::getInstance()
                ->createQuery()
                ->where('is_active=1 and city_id =11 and is_onmain=0')
                ->execute();
        }

    }

    public function executeBlockBanners(sfWebRequest $request) {

        $banners = BannersTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->execute();
        $bannersBlock = '<a class="prev" href="#"></a>
          <a class="next" href="#"></a>
          <div class="promo-gallery">
          <ul>';
        foreach ($banners as $key => $banner) {
            $target = ((substr_count($banner->getHref(), "http://") > 0) ? "target=\"_blank\"" : "");
            $bannersBlock.="<li><a href=\"" . $banner->getHref() . "\" " . $target . " style=\"border: 0px;\"><img src=\"/uploads/banners/" . $banner->getSrc() . "\" width=\"188\" height=\"198\" alt=\"" . $banner->getAlt() . "\" /></a></li>";
        }
        $bannersBlock.="</ul></div>";
        $this->block = $bannersBlock;
    }

    public function executeBlockArticle(sfWebRequest $request) {

        $num = $this->num;

        mb_internal_encoding('UTF-8');
        $articles = ArticleTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->limit($num)->execute();
        unset($rowCom, $article, $commentsBlock);
        $article = array();
        $commentsBlock = "</p><div class=\"article-box\">
        	<div class=\"article-frame\">
        		<div class=\"img-holder left\">
        			<a href=\"/sexopedia\"><img width=\"172\" height=\"161\" src=\"/newdis/images/img02.png\" alt=\"image description\"></a></div>
        		<ul class=\"article-list\">";
        foreach ($articles as $key => $article) {
            $commentsBlock .="<li>
        				<div class=\"title\">
        					<a href=\"/sexopedia/" . $article->getSlug() . "\">" . $article->getName() . "</a></div>
        				<div class=\"stars\"><span style=\"width:" . ($article->getVotesCount() > 0 ? (($article->getRating() / $article->getVotesCount()) * 10) : "0") . "%;\"></span>
        					 </div>
        				<p>" . mb_substr(strip_tags($article->getContent()), 0, 130) . "...
        					</p>
        			</li>	";
        }

        $commentsBlock .= "
          	</ul>
          	</div>
          </div><p>";
        $this->block = $commentsBlock;
    }

    public function executeBlockCommentsShop(sfWebRequest $request) {

        $num = $this->num;
        mb_internal_encoding('UTF-8');
        $comments =
            CommentsTable::getInstance()
                ->createQuery('c')
                ->leftJoin('c.Page p')
                ->where("created_at > '2017-01-01' and is_public='1' and ((page_id is not NULL and p.name not like '%Быстрая, выгодная доставка%') or shops_id IS NOT NULL)")
                ->orderBy("rand()")
                ->limit($num)
                ->execute();
        unset($rowCom, $comment, $commentsBlock);
        $comment = array();
        $commentsBlock = "</p><div style=\"clear: both;\"></div><div class=\"blocksCommentMainPage\">";
        foreach ($comments as $key => $comment2) {
            if($comment2->getPageId()){
                $product = PageTable::getInstance()->findOneById($comment2->getPageId());
                $link='/'. $product->getSlug();
            }
            else{
                $product = ShopsTable::getInstance()->findOneById($comment2->getShopsId());
                $link='/shops/'. $product->getSlug();
            }
            $commentsBlock .="	<div class=\"col\">
          		<div class=\"title\" style=\"height: 55px;\">
          			<a href=\"" . $link . "\">" . mb_substr($product->getName(), 0, 300) . "</a></div>


          			<div class=\"comment\">" . mb_substr($comment2->getText(), 0, 300) . "</div>";

            $commentsBlock .="<div class=\"nameDate\"><div style=\"float: left;\">";
            if ($comment2->getUsername() != ""):
                $commentsBlock .=$comment2->getUsername();
            else:
                $commentsBlock .=$comment2->getSfGuardUser()->getFirstName() != "" ? $comment2->getSfGuardUser()->getFirstName() : $comment2->getSfGuardUser()->getLastName();

            endif;
            $commentsBlock .="</div><div style=\"float: right;\">" . date("d.m.Y", strtotime($comment2->getCreatedAt())) . "</div></div></div>";
        }

        $commentsBlock .= "
          </div><p>";
        $this->block = $commentsBlock;
    }

    public function executeBlockComments(sfWebRequest $request) {
        $num = $this->num;
        mb_internal_encoding('UTF-8');
        $comments = CommentsTable::getInstance()->createQuery()->where("is_public='1' and product_id is NOT NULL and created_at > '2017-01-01'")->orderBy("rand()")->limit($num)->execute();
        // $comments = CommentsTable::getInstance()->createQuery()->where("is_public='1' and page_id is NULL and article_id is NULL")->orderBy("rand()")->limit($num)->execute();
        unset($rowCom, $comment, $commentsBlock);
        $comment = array();
        $commentsBlock = "<div style=\"clear: both;\"></div><div class=\"blocksCommentMainPage\">";
        foreach ($comments as $key => $comment2) {
            $product = ProductTable::getInstance()->findOneById($comment2->getProductId());
            $photoalbum = $product->getPhotoalbums();
            $photos = $photoalbum[0]->getPhotos();
            /*
             * <span class=\"img-box\"><img src=\"/uploads/photo/thumbnails_250x250/" . $photos[0]->getFilename() . "\" alt=\"image description\"></span>
             */
            $commentsBlock .="	<div class=\"col\">
          		<div class=\"title\">
          			<a href=\"/product/" . $product->getSlug() . "\">" . mb_substr($product->getName(), 0, 100) . " </a></div>
                                      <div style=\"clear: both;height: 5px;\"></div>
          		<div class=\"stars\">
                                          <span style=\"width:" . ($product->getVotesCount() > 0 ? (($product->getRating() / $product->getVotesCount()) * 10) : "0") . "%;\"></span></div>

          			<div class=\"comment\">" . mb_substr($comment2->getText(), 0, 300) . "</div>";

            $commentsBlock .="<div class=\"nameDate\"><div style=\"float: left;\">";
            if ($comment2->getUsername() != ""):
                $commentsBlock .=$comment2->getUsername();
            else:
                $commentsBlock .=$comment2->getSfGuardUser()->getFirstName() != "" ? $comment2->getSfGuardUser()->getFirstName() : $comment2->getSfGuardUser()->getLastName();

            endif;
            $commentsBlock .="</div><div style=\"float: right;\">" . date("d.m.Y", strtotime($comment2->getCreatedAt())) . "</div></div></div>";
        }

        $commentsBlock .= "</div>"
            . "";
        $this->block = $commentsBlock;
    }

    public function executeBlockActionProduct(sfWebRequest $request) {
        $this->block = '</p><ul class="item-list">' . dopBlockPage::getProductAction(1) . dopBlockPage::getProductAction(2) . dopBlockPage::getProductAction(3) . '</ul><p>';
    }

    public function executeShopsMapsMO(sfWebRequest $request) {
        $htmlMaps = "";
        $pickPoint = PickpointTable::getInstance()->findByRegion("Московская обл.");
        //$qiwi = QiwiTable::getInstance()->createQuery()->where("citygroup = 'Москва' or citygroup = 'Московская область' "); //findByCitygroup($city->getId());
        // $iml = ImlTable::getInstance()->findByCityId(3);
        $shops = ShopsTable::getInstance()->findByCityId(3);
        $htmlMaps.= "<script src=\"//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full\" type=\"text/javascript\"></script>".
            " <script type=\"text/javascript\">".
            " ymaps.ready(function () {".
            " var myMap = new ymaps.Map('YMapsID', {".
            " center: [55.751569, 37.617161],".
            " zoom: 7".
            " });".
            " myMap.geoObjects";
        $htmlMapsListPickpoint = "";
        foreach ($pickPoint as $point) {

            if ($point->getStatus() != 3 and $point->getCityId() != 3)
                $htmlMapsListPickpoint.= "
            " . ($point->getMetro() != "" ? " " . $point->getMetro() . "" : "") . " " . $point->getAddress() . " <br />";

            $workTime = explode(",", $point->getWorkTime());
            if ($workTime[0] == $workTime[6]) {
                $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
            } elseif ($workTime[0] == $workTime[4]) {
                $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                if ($workTime[5] == $workTime[6]) {
                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                } else {
                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                }
            } else {
                $strWorktime = "Пн " . $workTime[0] . "<br />";
                $strWorktime.="Вт " . $workTime[1] . "<br />";
                $strWorktime.="Ср " . $workTime[2] . "<br />";
                $strWorktime.="Чт " . $workTime[3] . "<br />";
                $strWorktime.="Пн " . $workTime[4] . "<br />";
                if ($workTime[5] == $workTime[6]) {
                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                } else {
                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                }
            }


            if ($point->getStatus() != 3)
                $htmlMaps.= "
            .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
            balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\">" . ($point->getMetro() != "" ? "<p> " . $point->getMetro() . "</p>" : "") . "<p>" . $point->getAddress() . "</p><p><b>Способы оплаты:</b> " . ($point->getCash() != "" ? "Наличными" : "") . "" . ($point->getCard() != "" ? ", Банковской картой" : "") . "</p><p><b>Время работы: </b><br />" . $strWorktime . "</p></div>'
        }, {
            iconLayout: 'default#image',
            iconImageHref: 'http://pickpoint.ru/i/markers/" . ($point->getTypeTitle() == "ПВЗ" ? "office.png" : "postamat.png") . "',
            iconImageSize: [41, 35],
            iconImageOffset: [-3, -42]
        }))";
        }
        /* foreach ($qiwi as $point) {


          $htmlMaps.= "
          .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
          balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\"><p>" . $point->getAddr() . "</p><p><b>Время работы: </b><br />" . $point->getOh() . "</p></div>'
          }, {
          iconLayout: 'default#image',
          iconImageHref: 'http://geowidget-ru.easypack24.net/images/code_ru/marker-a1.png',
          iconImageSize: [24, 36],
          iconImageOffset: [-3, -42]
          }))";
          } */
        /* foreach ($iml as $point) {


          $htmlMaps.= "
          .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
          balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\"><p>" . $point->getAddress() . "</p><p><b>Время работы: </b><br />" . $point->getWorkmode() . "</p></div>'
          }, {
          iconLayout: 'default#image',
          iconImageHref: 'http://iml.ru/static/main/img/iml-logo.png',
          iconImageSize: [36, 36],
          iconImageOffset: [-3, -42]
          }))";
          } */
        $htmlMapsListShop = "";
        foreach ($shops as $point) {
            if ($point->getCityId() != 3)
                $htmlMapsListShop.= "
            " . ($point->getMetro() != "" ? "м. " . $point->getMetro() . "" : "") . " " . $point->getAddress() . " <br />";


            if ($point->getWorkTime() != "") {
                $workTime = explode(",", $point->getWorkTime());
                if ($workTime[0] == $workTime[6]) {
                    $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
                } elseif ($workTime[0] == $workTime[4]) {
                    $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                    if ($workTime[5] == $workTime[6]) {
                        $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    } else {
                        $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                        $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                    }
                } else {
                    $strWorktime = "Пн " . $workTime[0] . "<br />";
                    $strWorktime.="Вт " . $workTime[1] . "<br />";
                    $strWorktime.="Ср " . $workTime[2] . "<br />";
                    $strWorktime.="Чт " . $workTime[3] . "<br />";
                    $strWorktime.="Пн " . $workTime[4] . "<br />";
                    if ($workTime[5] == $workTime[6]) {
                        $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    } else {
                        $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                        $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                    }
                }
            }
            $htmlMaps.= "
            .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
            balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\">" . ($point->getMetro() != "" ? "<p>м. " . $point->getMetro() . "</p>" : "") . "<p>" . $point->getAddress() . "</p><p><b>Время работы: </b><br />" . $strWorktime . "</p></div>'
        }, {
            iconLayout: 'default#image',
            iconImageHref: 'https://onona.ru/favicon.ico',
            iconImageSize: [28, 28],
            iconImageOffset: [-3, -42]
        }))";
        }
        $htmlMaps.= "

});
</script>

<style type=\"text/css\">
    #YMapsID {
        width: 760px;
        height: 450px;
    }
</style>

    <div id=\"YMapsID\"></div>";

        $htmlMaps.= "<div style=\"overflow: auto; width: 760px; max-height: 250px;\">" . $htmlMapsListShop . $htmlMapsListPickpoint . "</div>";

        $this->htmlMaps = $htmlMaps;
    }



    public function executeMainPageProduct(sfWebRequest $request) {

    }

    public function executeMainPageTube(sfWebRequest $request) {

    }

    public function executeMainPagePodpis(sfWebRequest $request) {

    }

    public function executeRussianPostList(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка алфавита');
        $this->alf = $q->execute("SELECT *
FROM (

SELECT LEFT( city, 1 ) AS alf
FROM  `russian_post_city`
ORDER BY  `russian_post_city`.`city` ASC
) AS alf
GROUP BY alf")->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $timerPage->addTime();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка городов');
        $this->citys = $q->execute("SELECT city, slug
FROM  `russian_post_city`
ORDER BY  `russian_post_city`.`city` ASC ")->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $timerPage->addTime();
    }

    public function executePickpointList(sfWebRequest $request) {

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка алфавита');
        $this->alf = $q->execute("SELECT *
FROM (

SELECT LEFT( name, 1 ) AS alf
FROM  `city`
where is_public='1'
ORDER BY  `city`.`name` ASC
) AS alf
GROUP BY alf")->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $timerPage->addTime();

        $timerPage = sfTimerManager::getTimer('Action: Загрузка городов');
        $this->citys = $q->execute("SELECT name, slug
FROM  `city`
where is_public='1'
ORDER BY  `city`.`name` ASC ")->fetchAll(Doctrine_Core::FETCH_ASSOC);
        $timerPage->addTime();
    }

}
?>
