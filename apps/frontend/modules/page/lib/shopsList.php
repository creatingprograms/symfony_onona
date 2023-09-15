<?php

class shopsList {

    static function getShopsMaps($city) {
        $htmlMaps = "";
        if ($city->getId() == "")
            return "";
        //$pickPoint = PickpointTable::getInstance()->findByCityId($city->getId());
        $pickPoint = PickpointTable::getInstance()->createQuery()->where("City_id=?",$city->getId())->addWhere("is_public = '1'")->execute();
        //$qiwi = QiwiTable::getInstance()->findByCityId($city->getId());
        //$iml = ImlTable::getInstance()->findByCityId($city->getId());
        $shops = ShopsTable::getInstance()->findByCityId($city->getId());
        if($city->getId()=="32"){
            $shops = ShopsTable::getInstance()->createQuery()->where("city_id='".$city->getId()."' or id=14")->execute();
        }

        $htmlMaps.= "    <script src=\"//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full\" type=\"text/javascript\"></script>

<script type=\"text/javascript\">
ymaps.ready(function () {

ymaps.geocode('" . ($city->getName()!="Москва"?($pickPoint[0]->getRegion()):'') . ', ' . $city->getName() . "').then(function (res) {
    var myMap = new ymaps.Map('YMapsID', {

        center: res.geoObjects.get(0).geometry.getCoordinates(),
            zoom: 10
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
            iconImageHref: 'https://pickpoint.ru/i/markers/" . ($point->getTypeTitle() == "ПВЗ" ? "office.png" : "postamat.png") . "',
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
        foreach ($shops as $point) {
            $workTime = $point->getWorkTime();
            // $workTime = explode(",", $point->getWorkTime());
            /*//Задается напрямую в тексте
            if ($workTime[0] == $workTime[6]) {
                $strWorktime = "Пн-Вс " . $workTime[0] . "<br />";
            }
            elseif ($workTime[0] == $workTime[4]) {
                $strWorktime = "Пн-Пт " . $workTime[0] . "<br />";
                if ($workTime[5] == $workTime[6]) {
                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                } else {
                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                }
            }
            else {
                $strWorktime = "Пн " . $workTime[0] . "<br />";
                $strWorktime.="Вт " . $workTime[1] . "<br />";
                $strWorktime.="Ср " . $workTime[2] . "<br />";
                $strWorktime.="Чт " . $workTime[3] . "<br />";
                $strWorktime.="Пн " . $workTime[4] . "<br />";
                if ($workTime[5] == $workTime[6]) {
                    $strWorktime.= "Сб-Вс " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                }
                else {
                    $strWorktime.="Сб " . ($workTime[5] != "NODAY" ? $workTime[5] : "Выходной") . "<br />";
                    $strWorktime.="Вс " . ($workTime[6] != "NODAY" ? $workTime[6] : "Выходной") . "<br />";
                }
            }*/
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
});
</script>

<style type=\"text/css\">
    #YMapsID {
        width: 100%;
        height: 450px;
    }
</style>

    <div id=\"YMapsID\"></div>";


        return $htmlMaps;
    }

    static function getShopsMapsRf() {
        ///echo get_component("page", "shopsMapsRf");
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
            iconImageSize: [24, 36],
            iconImageOffset: [-3, -42]
        }))";
        }*/
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
        width: 760px;
        height: 450px;
    }
</style>

    <div id=\"YMapsID\"></div>";


        return $htmlMaps;
    }

    static function getShopsMapsMO() {
        $htmlMaps = "";
        $pickPoint = PickpointTable::getInstance()->findByRegion("Московская обл.");
        //$qiwi = QiwiTable::getInstance()->createQuery()->where("citygroup = 'Москва' or citygroup = 'Московская область' ");//findByCitygroup($city->getId());
        //$iml = ImlTable::getInstance()->findByCityId(3);//findByCitygroup($city->getId());
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
            iconImageSize: [24, 36],
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
        width: 760px;
        height: 450px;
    }
</style>

    <div id=\"YMapsID\"></div>";

        $htmlMaps.= "<div style=\"overflow: auto; width: 760px; max-height: 250px;\">" . $htmlMapsListShop . $htmlMapsListPickpoint . "</div>";

        return $htmlMaps;
    }

    static function getShopsList($city) {
        $htmlMaps = "";
        if ($city->getId() == "")
            return "";
        $pickPoint = PickpointTable::getInstance()->findByCityId($city->getId());
        $shops = ShopsTable::getInstance()->findByCityId($city->getId());
        $htmlMaps.= "<span style=\"color:#4b0082;\">Адреса постаматов, пунктов выдачи и магазинов в г. " . $city->getName() . "</span><br />";


        foreach ($shops as $point) {
            $htmlMaps.= "
            " . ($point->getMetro() != "" ? "м. " . $point->getMetro() . "" : "") . " " . $point->getAddress() . " <br />";
        }
        foreach ($pickPoint as $point) {
            if ($point->getStatus() != 3)
                $htmlMaps.= "
            " . ($point->getMetro() != "" ? " " . $point->getMetro() . "" : "") . " " . $point->getAddress() . " <br />";
        }
        return $htmlMaps;
    }

    static function getShopsListPickpoint($city) {
        $htmlMaps = "";
        if ($city->getId() == "")
            return "";
        $pickPoint = PickpointTable::getInstance()->createQuery()->where("City_id=?",$city->getId())->addWhere("is_public = '1'")->execute();
        $shops = ShopsTable::getInstance()->findByCityId($city->getId());
        $header = PageTable::getInstance()->findOneById(243);
        $htmlMaps = $header->getContent() . "<div style=\"overflow: auto;max-height: 250px;\">";
        foreach ($pickPoint as $point) {
            if ($point->getStatus() != 3)
                $htmlMaps.= "
            " . ($point->getMetro() != "" ? " " . $point->getMetro() . "" : "") . " " . $point->getAddress() . " <br />";
        }
        return $htmlMaps . "</div>";
    }

    static function getShopsListOnona($city) {
        $htmlMaps = "";
        if ($city->getId() == "")
            return "";
        $pickPoint = PickpointTable::getInstance()->findByCityId($city->getId());
        $shops = ShopsTable::getInstance()->createQuery()->where("City_id=?",$city->getId())->addWhere("is_active = '1'")->execute();
        $header = PageTable::getInstance()->findOneById(244);
        $htmlMaps = $header->getContent() . "<div style=\"overflow: auto; width: 760px; max-height: 250px;\">";
        foreach ($shops as $point) {
            $htmlMaps.= "
            " . ($point->getMetro() != "" ? "м. " . $point->getMetro() . "" : "") . " " . $point->getAddress() . " <br />";
        }
        return $htmlMaps . "</div>";
    }

}

?>
