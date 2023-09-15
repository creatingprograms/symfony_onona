<?php

class pagesComponents extends sfComponents {

  public function executeBlockSliders(sfWebRequest $request){
    $sliders = SlidersTable::getInstance()->createQuery()->where("is_active='1'")->orderBy("position")->execute();
    $slidersBlock =
      '<div class="slide-gallery" id="gallery-mainPage">';
    foreach ($sliders as $key => $value) {
      $slidersBlock.=
        '<a href="'.($value->getHref() ? $value->getHref() : 'javascript:void(1)').'">'.
          '<img alt="'.$value->getAlt().'"  src="/uploads/banners/'.$value->getSrc().'" style="width: 100%;" />'.
        '</a>';
    }
    $slidersBlock.='</div>';
    $this->block = $slidersBlock;
  }

  public function executeBlockBanners(sfWebRequest $request) {

      $banners = BannersTable::getInstance()->createQuery()->where("is_public='1'")->orderBy("rand()")->execute();
      $bannersBlock = '<a class="prev" href="#"></a>'.
        '<a class="next" href="#"></a>'.
        '<div class="promo-gallery">'.
        '<ul>';
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
      $commentsBlock = "</p><div class=\"article-box\">'.
      '<div class=\"article-frame\">'.
        '<div class=\"img-holder left\">'.
          '<a href=\"/sexopedia\"><img width=\"172\" height=\"161\" src=\"/newdis/images/img02.png\" alt=\"image description\"></a></div>'.
            '<ul class=\"article-list\">";
      foreach ($articles as $key => $article) {
          $commentsBlock .="<li>'.
            '<div class=\"title\">'.
              '<a href=\"/sexopedia/" . $article->getSlug() . "\">" . $article->getName() . "</a></div>'.
              '<div class=\"stars\"><span style=\"width:" . ($article->getVotesCount() > 0 ? (($article->getRating() / $article->getVotesCount()) * 10) : "0") . "%;\"></span>'.
              '</div>'.
              '<p>" . mb_substr(strip_tags($article->getContent()), 0, 130) . "...'.
              '</p>'.
              '</li>	";
      }

      $commentsBlock .= "</ul>".
      "</div>".
      "</div><p>";
      $this->block = $commentsBlock;
  }

    public function executeBlockCommentsShop(sfWebRequest $request) {

        $num = $this->num;
        mb_internal_encoding('UTF-8');
        $comments = CommentsTable::getInstance()->createQuery('c')
                        ->leftJoin('c.Page p')->where("is_public='1' and page_id is not NULL and p.name not like '%Быстрая, выгодная доставка%'")->orderBy("rand()")->limit($num)->execute();
        unset($rowCom, $comment, $commentsBlock);
        $comment = array();
        $commentsBlock = "</p><div style=\"clear: both;\"></div><div class=\"blocksCommentMainPage\">";
        foreach ($comments as $key => $comment2) {
            $product = PageTable::getInstance()->findOneById($comment2->getPageId());
            $commentsBlock .="	<div class=\"col\">
		<div class=\"title\" style=\"height: 55px;\">
			<a href=\"/" . $product->getSlug() . "\">" . mb_substr($product->getName(), 0, 300) . "</a></div>


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
        $comments = CommentsTable::getInstance()->createQuery()->where("is_public='1' and page_id is NULL and article_id is NULL")->orderBy("rand()")->limit($num)->execute();
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
        $htmlMaps.= "    <script src=\"//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full\" type=\"text/javascript\"></script>

<script type=\"text/javascript\">
ymaps.ready(function () {

    var myMap = new ymaps.Map('YMapsID', {

        center: [55.751569, 37.617161],
            zoom: 7
        });

        myMap.geoObjects";
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
        /*foreach ($qiwi as $point) {


            $htmlMaps.= "
            .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
            balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\"><p>" . $point->getAddr() . "</p><p><b>Время работы: </b><br />" . $point->getOh() . "</p></div>'
        }, {
            iconLayout: 'default#image',
            iconImageHref: 'http://geowidget-ru.easypack24.net/images/code_ru/marker-a1.png',
            iconImageSize: [24, 36],
            iconImageOffset: [-3, -42]
        }))";
        }*/
        /*foreach ($iml as $point) {


                $htmlMaps.= "
            .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
            balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\"><p>" . $point->getAddress() . "</p><p><b>Время работы: </b><br />" . $point->getWorkmode() . "</p></div>'
        }, {
            iconLayout: 'default#image',
            iconImageHref: 'http://iml.ru/static/main/img/iml-logo.png',
            iconImageSize: [36, 36],
            iconImageOffset: [-3, -42]
        }))";
        }*/
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
        width: 100%;
        height: 450px;
    }
</style>

    <div id=\"YMapsID\"></div>";

        $htmlMaps.= "<div style=\"overflow: auto; width: 100%; max-height: 250px;\">" . $htmlMapsListShop . $htmlMapsListPickpoint . "</div>";

        $this->htmlMaps = $htmlMaps;
    }

    public function executeShopsMapsRf(sfWebRequest $request) {
      $pickPoint = PickpointTable::getInstance()->findAll();
      $cities = CityTable::getInstance()->createQuery()->orderBy("name ASC")->execute();
      foreach ($cities as $key => $value) {
        $citiesList[$value->getId()]=[
          'name' => $value->getName(),
          'is_used' => false,
        ];
      }
      $shops = ShopsTable::getInstance()->findAll();
      $infoDiv='<div id="points-info" style="display: none;">';

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
      }
      $select='<select id="cities-list">';
      foreach ($citiesList as $key => $value) {
        if( !$value['is_used'] ) continue;
        /*Если точек в городе нет*/
        /* 3 это defaultCity - Moscow*/
        $select.='<option value="'.$key.'"'.($key==3 ? ' selected="true"' : '').'>'.$value['name'].'</option>';
      }
      $select.='</select>';

      $infoDiv.='</div>';
      $infoDiv.=
        " <style type=\"text/css\"> ".
        " #cities-list{width: 100%;}".
        " #YMapsID {".
          " width: 100%;".
          " height: 450px;".
          " } </style>"."<div id=\"YMapsID\"></div>".
        "<script src=\"//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full\" type=\"text/javascript\"></script>";

      $this->htmlMaps = $select.$infoDiv;

      /* Старый вариант
        $htmlMaps = "";
        $pickPoint = PickpointTable::getInstance()->findAll();
        //$qiwi = QiwiTable::getInstance()->findAll();
        //$iml = ImlTable::getInstance()->findAll();
        $shops = ShopsTable::getInstance()->findAll();
        $htmlMaps.= "    <script src=\"//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full\" type=\"text/javascript\"></script>

              <script type=\"text/javascript\">
              ymaps.ready(function () {

                  var myMap = new ymaps.Map('YMapsID', {

                      center: [63.765152, 99.449527],
                          zoom: 2
                      });

                      myMap.geoObjects";
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
                      // foreach ($qiwi as $point) {
                      //
                      //
                      //     $htmlMaps.= "
                      //     .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
                      //     balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\"><p>" . $point->getAddr() . "</p><p><b>Время работы: </b><br />" . $point->getOh() . "</p></div>'
                      // }, {
                      //     iconLayout: 'default#image',
                      //     iconImageHref: 'http://geowidget-ru.easypack24.net/images/code_ru/marker-a1.png',
                      //     iconImageSize: [24, 36],
                      //     iconImageOffset: [-3, -42]
                      // }))";
                      // }

                      // foreach ($iml as $point) {
                      //
                      //
                      //         $htmlMaps.= "
                      //     .add(new ymaps.Placemark([" . $point->getLatitude() . ", " . $point->getLongitude() . "], {
                      //     balloonContent: '<h3>" . $point->getName() . "</h3><div class=\"address\"><p>" . $point->getAddress() . "</p><p><b>Время работы: </b><br />" . $point->getWorkmode() . "</p></div>'
                      // }, {
                      //     iconLayout: 'default#image',
                      //     iconImageHref: 'http://iml.ru/static/main/img/iml-logo.png',
                      //     iconImageSize: [36, 36],
                      //     iconImageOffset: [-3, -42]
                      // }))";
                      // }
                      foreach ($shops as $point) {
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
                      width: 100%;
                      height: 450px;
                  }
              </style>

                  <div id=\"YMapsID\"></div>";
                      $this->htmlMaps = $htmlMaps;
                      */
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
}
?>
