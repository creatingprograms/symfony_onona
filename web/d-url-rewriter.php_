<?php

/**********************************************************************\
*          Внимание! На сайте реализован специальный скрипт.           *
*                                                                      *
* Данный скрипт является разработкой Demis Group и реализует ряд       *
* функций и методов, которые необходимы для полноценного и             *
* масштабного внесения SEO-правок при наличии ограничений системы      *
* управления сайтом. Этот скрипт не может быть причиной                *
* неработоспособности сайта. Если, на Ваш взгляд, он все же может      *
* являться причиной некорректной работы сайта, возможно ВРЕМЕННО       *
* отключить его в точке входа.                                         *
*                                                                      *
* Обычно это файл index.php в корне сайта.                             *
* Возможен также вариант подключения через htaccess.                   *
* В данном случае необходимо закомментировать строку вида:             *
*   php_value auto_prepend_file "...........d-url-rewriter.php"        *
*                                                                      *
* Во избежание нарушения функционирования сайта изменение данного      *
* файла лучше производить с предварительной консультацией специалистов *
* Demis Group. Обратитесь к нам, если у Вас возникли вопросы по        *
* данному скрипту, и мы оперативно их решим. Бесплатно!                *
*                                                                      *
* О факте отключения скрипта и сопутствующих проблемах также           *
* незамедлительно сообщите представителю компании Demis Group с целью  *
* сохранить реализованные SEO-правки и предотвратить падение позиций   *
* сайта в поисковых системах и снижение его посещаемости.              *
\**********************************************************************/


$u = $uri = $_SERVER['REQUEST_URI'];
/*********

Редиректы по правилам. Выполняются после частных редиректов из массива $aR301SkipCheck

$u = str_replace('/catalog/', '/katalog/', $u);
if(preg_match('#ukey=(.*)$#siU', $u, $m)){
$u = '/'.$m[1].'/';
}

*********/

// Раздел настройки ЧПУ (Все пути начинаются с ведущего слеша от корневой директории)

$aURLRewriter = array(

	// '/link1'=>'/link1',

);

// Сквозные редиректы

$aR301SkipCheck = array(
	'/shops/Aminevskoe_shosse'=>'/shops/aminevskoe_shosse',
	'/shops/ulitsa_Savushkina'=>'/shops/ulitsa_savushkina',
	'/shops/ulitsa_Letchika_Babushkina'=>'/shops/ulitsa_letchika_babushkina',
	'/sexshop_Moskovskaya_oblast_dostavka'=>'/sexshop_moskovskaya_oblast_dostavka',
	'/novogodnie_skidki'=>'/Novogodnie_skidki',
	'/sexshop_Vologda_dostavka'=>'/sexshop_vologda_dostavka',
	'/Banery_sleva'=>'/banery_sleva',
	'/sexshop_Kostroma_dostavka'=>'/sexshop_kostroma_dostavka',
	'/akcia_Biglion'=>'/akcia_biglion',
	'/sexshop_Tyumen_dostavka'=>'/sexshop_tyumen_dostavka',
	'/Banery_sprava'=>'/banery_sprava',
	'/sexshop_Rostov-na-Donu_dostavka'=>'/sexshop_rostov-na-donu_dostavka',
	'/sexshop_Ivanovo_dostavka'=>'/sexshop_ivanovo_dostavka',
	'/sexshop_Yaroslavl_dostavka'=>'/sexshop_yaroslavl_dostavka',
	'/news/lubimye-zhenschiny-pozdravlyaem-vas-s-nastupivshei-vesnoi-i-8-marta-1'=>'/news/lubimye-zhenschiny-pozdravlyaem-vas-s-nastupivshei-vesnoi-i-8-marta',
	'/X-Show_2012'=>'/x-show_2012',
	'/newshop_Kutuzovskiy26'=>'/newshop_kutuzovskiy26',
	'/adresa_magazinov_v_rossii'=>'/',
'/%c2%a0'=>'/',
'/casino-onlain.org'=>'/',
'/catalog/***-igrushki-dlja-par'=>'/catalog/sex-igrushki-dlja-par',
'/catalog/***-igrushki-dlja-par/newprod'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/catalog/***-igrushki-dlja-par/relatecategory'=>'/',
'/catalog/***-igrushki-dlya-muzhchin'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/catalog/***-igrushki-dlya-muzhchin/newprod'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/catalog/***-igrushki-dlya-muzhchin/relatecategory'=>'/',
'/catalog/***-igrushki-dlya-zhenschin'=>'/catalog/sex-igrushki-dlya-zhenschin',
'/catalog/***-igrushki-dlya-zhenschin/newprod'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/catalog/***-igrushki-dlya-zhenschin/relatecategory'=>'/',
'/category/118'=>'/',
'/category/31/?utm_source=yandex&utm_medium=cpc&utm_content=analnaya_probka&utm_campaign=analnie_probki_moskva'=>'/',
'/category/31/?utm_source=yandex&utm_medium=cpc&utm_content=analnaya_probka&utm_campaign=analnie_probki_rossiya'=>'/category/seksualnye-trusiki',
'/category/5'=>'/',
'/category/5/?utm_source=yandex&utm_medium=cpc&utm_content=prodazha_vibratorov&utm_campaign=vibratori_rossiya'=>'/',
'/category/analnaya-pr...'=>'/',
'/category/analnoe_udovolstvie'=>'/',
'/category/analnye_igr...'=>'/',
'/category/analnye_sha...'=>'/',
'/category/dildo-bez-v...'=>'/',
'/category/dvoinogo-de...'=>'/',
'/category/elitnye-vib...'=>'/',
'/category/erotichesko...'=>'/',
'/category/igrovye-kos...'=>'/',
'/category/***_kukly'=>'/category/sex_kukly',
'/category/kupit_***inalnye_shariki'=>'/category/kupit_vaginalnye_shariki',
'/category/kupit_vagin...'=>'/',
'/category/massazhery-...'=>'/',
'/category/mega-*******atory-realistiki'=>'/sexopedia/kak-zanimatsya-virtualnym-seksom-9-vazhnyh-sovetov-dlya-kachestvennogo-virta',
'/category/muzhskie-*******atory'=>'/category/muzhskie-masturbatory',
'/category/osheiniki'=>'/',
'/category/stimulyator'=>'/',
'/category/stimulyator...'=>'/',
'/category/strapony_ha...'=>'/',
'/category/strapony-na...'=>'/',
'/category/trusiki_har...'=>'/',
'/category/udlinyauschie-nasadki-na-*****'=>'/category/udlinyauschie-nasadki-na-penis',
'/category/universalny...'=>'/',
'/category/vibratory-d...'=>'/',
'/horoscope'=>'/catalog/BDSM-i-fetish',
'/horoscope/kozerog'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/horoscope/ryby'=>'/sexopedia/kak-naiti-klitor-rukovodstvo-dlya-muzhchin',
"/javascript:$('"=>'/',
"/javascript:setcookie('age***',%20true,%20'mon,%2001-jan-2101%2000:00:00%20gmt',%20'/');%20$('"=>'/sexopedia/mzhm-ili-seks-vtroem',
'/javascript:void(1)'=>'/',
'/***opedia'=>'/',
'/product/12027'=>'/',
'/product/582'=>'/',
'/product/963'=>'/',
'/product/analnaya-cep...'=>'/',
'/product/analnaya-pro...'=>'/',
'/product/analnaya-probka-my-toy-small-chernaya/?r=971755712'=>'/',
'/product/analnaya-probka-so-smeschennym-centrom-tyazhesti-geisha-plug-ruby'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/product/analnyi-rass...'=>'/',
'/product/*******ator-zakrytogo-tipa-s-imitatsiej-popki-calexotics-stroke-it%e2%84%a2-ass-ivory-telesnyj'=>'/category/vaginy-realistiki',
'/product/barhatistye-***inalnye-shariki-geisha-balls-2-rozovyi'=>'/category/kupit_vaginalnye_shariki',
'/product/bolshaya-probka-jewelry-silver-blue'=>'/product/ruka-dlya-fistinga',
'/product/bondazh-dlya-*****a-toyfa-metal-serebristyi-m'=>'/category/naruchniki-bondazh',
'/product/dve-naduvnye-kukly-luv-twins'=>'/',
'/product/erekcionnoe-kolco-nutz'=>'/',
'/product/erekcionnoe-kolco-twin-teazer-rabbit-ring-s-vibraciei-fioletovyi.'=>'/',
'/product/ergonomichna...'=>'/',
'/product/falloimitator-basix-rubber-7-5-black'=>'/sexopedia/bazovye-pravila-muzhskoi-masturbacii',
'/product/falloimitator-na-prisoske-fetish-fantasy-elite-10'=>'/',
'/product/fiksaciya-dlya-ruk'=>'/',
'/product/igra-pecker-toss-nakin-kolco'=>'/',
'/product/innovatsionnyj-*******ator-s-vibratsiej-satisfyer-men-vibration-chernyj'=>'/category/muzhskie-masturbatory',
'/product/intellektual...'=>'/',
'/product/klyap-i-maska-na-glaza-fetish-fantasy-elite-black'=>'/category/seks-mashiny',
'/product/luchshii-v-mire-vibrator-dlya-par-we-vibe-4-purple'=>'/',
'/product/masturbator-hands-free-novogo-pokoleniya-spider-meiki-one-black'=>'/',
'/product/masturbator-spider-realism-vagina'=>'/',
'/product/masturbator-vstavka-spider-meiki-one-v-vide-vaginy'=>'/',
'/product/moschnyi-vib...'=>'/',
'/product/nabor-dlya-b...'=>'/',
'/product/nabor-kokteilnyh-trubochek-dicky-sipping-straws'=>'/catalog/sex-igrushki-dlya-muzhchin',
'/product/naduvnaya-kukla-barack'=>'/',
'/product/naduvnaya-ovechka-lovin-lamb'=>'/',
'/product/nasadka-udlinitel-real-feel-1-extension...also'=>'/',
'/product/nasos-dlya-vakuumnoj-pompy-renegade'=>'/category/igrovye-kostumy',
'/product/palochka-dlya-nagreva-masturbatorov-topco-sales-warming-wand-s-usb-zaryadkoi'=>'/category/vakuumnye-pompy',
'/product/perezaryazha...'=>'/',
'/product/perezaryazhaemyi-vibromassazher-vitality-by-leaf'=>'/category/vibratory-vibromassazhery',
'/product/realistichnaya-vagina-laura-doone-1974'=>'/',
'/product/realistichny...'=>'/',
'/product/realistichnyi-slepok-vaginy-i-anusa-stella-styles-s-vibraciei'=>'/category/analnye_shariki_elochki',
'/product/realistik-s-vibraciei-i-prisoskoi-mr-just-right-super-seven-dongs'=>'/',
'/product/realistik-s-vibraciei-i-prisoskoi-mr-just-right-super-seven-dongs/?comment=true'=>'/sexopedia/mzhm-ili-seks-vtroem',
'/product/si-stringi-s-vibropulei-bitch'=>'/',
'/product/soblaznitelnoe-plate-s-podvyazkami-criminal-minds'=>'/category/strapon',
'/product/strapon-na-n...'=>'/',
'/product/strapon-s-vi...'=>'/',
'/product/strapon-s-vibraciei-10-function-love-rider-g-caress,'=>'/catalog/BDSM-i-fetish',
'/product/strapon-trus...'=>'/',
'/product/universalnyi-koncentrat-feromona-social-contact'=>'/sexopedia/10-luchshih-tehnik-mineta',
'/product/universalnyi-koncentrat-feromona-social-contact/?comment=true'=>'/sexopedia/mzhm-ili-seks-vtroem',
'/product/vagina%c2%ads%c2%advibraciei%c2%adcassia%c2%adriley%c2%a0'=>'/',
'/product/vaginalnye-s...'=>'/',
'/product/vibromassazh...'=>'/',
'/product/vibromassazher-fetish-fantasy-6-purple'=>'/category/vibratory-vibromassazhery',
'/product/vibromassazher-zini-seed-purple'=>'/category/vibratory-vibromassazhery',
'/product/vibropompa-dlya-soskov/casino-onlain.org'=>'/',
'/product/vibropulya-so-svetyaschimsya-pultom-glace-dancer-black'=>'/',
'/product/vibropulya-so-svetyaschimsya-pultom-glace-dancer-pink'=>'/',
'/product/vibroyaico-so-svetyaschimsya-pultom-glace-cuties-pink'=>'/',
'/product/vibroyaico-so-svetyaschimsya-pultom-glace-cuties-purple'=>'/category/seksualnye-bodi',
'/product/vozbuzhdauschii-gel-viamax-sensitive-dlya-zhenschin-50-ml?comment=true'=>'/category/ochistitelnye-klizmy',
'/product/vysokoklassnyi-vibrator-wow-no-3'=>'/category/vibratory-vibromassazhery',
'/product/we-vibe3_action/?utm_source=yandex&utm_medium=cpc&utm_content=we_vibe_salsa&utm_campaign=vv'=>'/',
'/product/we-vibe3-ruby'=>'/',
'/product/we-vibeii-ruby'=>'/',
'/product/white-spider-fleshlight'=>'/product/ruka-dlya-fistinga',
'/product/zini_janus_anti-shock'=>'/',
'/set-magazinov-dlya-vzroslyh-on-i-ona-v-g-krasnodar'=>'/',
'/sexopedia/analnyi-se...'=>'/',
'/sexopedia/kak-delat-pravilny'=>'/',
'/sexopedia/kak-vybrat-vibrator-podrobnaya-instrukciya-dlya-novichkov'=>'/sexopedia/luchshie-sposoby-zhenskoi-masturbacii-top-10',
'/sexopedia/mzhm-ili-s...'=>'/',
'/sexopedia/poterya-soznaniya-vo-vre'=>'/',
'/sexopedia/zhmzh-ili-...'=>'/',
'/sexshop/casino-onlain.org'=>'/',
'/shops/1-ya-tverskaya-yamskaya'=>'/sexopedia/kak-priznatsya-chto-ty-futfetishist',
'/shops/magazin-solnechnogorskij-rajon-derevnya-brehovo-80'=>'/sexopedia/kak-zanimatsya-virtualnym-seksom-9-vazhnyh-sovetov-dlya-kachestvennogo-virta',
'/shops/punkt-vydachi-on-i-ona-v-g-moskva-proezd-serebryakova'=>'/category/bolshie-falloimitatory',
'/shops/spb_turistskaya_10'=>'/category/stimulyatory-klitora',
'/shops/suschevsky_val'=>'/category/vaginy',
'/skidki_do_60_percent'=>'/category/falloimitatory',
'/tel:84953749878'=>'/category/dvustoronnie_fallosy',
'/tel:88005009878'=>'/category/dvustoronnie_fallosy',

);

// Удаленные страницы

$a410Response = array(
	'/test3',
);
$a404Response = array(
	'/test4',
);

// Только замена ссылок

$aURLRewriterOnly = array(
'/Garantii'=>'/garantii',
'/Adresa_magazinov_v_Moskve'=>'/adresa-magazinov-on-i-ona-v-moskve-i-mo',
'/shops/Aminevskoe_shosse'=>'/shops/aminevskoe_shosse',
//'/customer/bonus'=>'/shops/aminevskoe_shosse',
'/manufacturer/hot-products-ltd-velikobritaniya'=>'/manufacturer/hot-products-ltd-avstriya',
'/shops/ulitsa_Letchika_Babushkina'=>'/shops/ulitsa_letchika_babushkina',
'/shops/ulitsa_Savushkina'=>'/shops/ulitsa_savushkina',
'/trehgorny_val'=>'/shops/trehgorny_val',
'/manufacturer/1658'=>'/',
'/shops/SHCHelkovskoe_shosse'=>'/shops/shchelkovskoe_shosse',
'/shops/Frunzenskaya_naberegnaya'=>'/shops/frunzenskaya_naberegnaya',
'/shops/ulitsa_Babushkina'=>'/shops/ulitsa_babushkina',
'/sexshop_Moskva_dostavka'=>'/sexshop_moskva_dostavka',
'/dostavka_PickPoint'=>'/dostavka_pickpoint',
'/product/realistik-s-vibraciei-i-prisoskoi-mr-just-right-super-seven-dongs'=>'/',
'/product/masturbator-spider-realism-vagina'=>'/',
"/manufacturer/Lady's Life"=>"/manufacturer/lady%27s%20life",
'/news/pozdravlyaem-vas-s-nastupivshei-vesnoi-i-8-marta-1'=>'/news/pozdravlyaem-vas-s-nastupivshei-vesnoi-i-8-marta',
'/product/perezaryazhaemyi-vibromassazher-vitality-by-leaf'=>'/category/vibratory-vibromassazhery',
'/product/masturbator-hands-free-novogo-pokoleniya-spider-meiki-one-black'=>'/',
'/product/palochka-dlya-nagreva-masturbatorov-topco-sales-warming-wand-s-usb-zaryadkoi'=>'/category/vakuumnye-pompy',
'/product/strapon-universalnyi-s-vibraciei-6-hollow-strap-on'=>'/',
'/product/vaginalnyi-sharik-my-ball-smart-so-smeschennym-centrom-tyazhesti'=>'/',
'/product/trenazher-dlya-intimnyh-myshc-pelvic-muscle-trainer'=>'/',
'/product/na-pyatdesyat-ottenkov-temnee'=>'/',
'/product/silikonovyi-lubrikant-viamax-silicone-glide-70-ml'=>'/',
'/product/naturalnyi-lubrikant-viamax-water-glide-70-ml'=>'/',
'/product/mini-vibrator-love-lips'=>'/',
'/product/malaya-probka-jewelry-gold-crystal'=>'/',
'/product/mnogohvostaya-plet-please-sir'=>'/',
'/product/perezaryazhaemyi-massazher-anasteisha-vibe-fioletovyi'=>'/',
'/sexopedia/catalog/'=>'/',
'/shops/ulitsa_Bolshaya_Tulskaya'=>'/shops/ulitsa_bolshaya_tulskaya',
'/Vakansii'=>'/vakansii',
'/shops/Pyatnitskoe_shosse'=>'/shops/pyatnitskoe_shosse',
'/shops/Lesnoj_prospekt'=>' /shops/lesnoj_prospekt',
'/shops/SredneohtinskiY_prospekt'=>'/shops/sredneohtinskiy_prospekt',
'/shops/Sadovaya'=>'/shops/sadovaya',
'/shops/ulitsa_Sovetskoy_Armii'=>'/shops/ulitsa_sovetskoy_armii',
'/shops/SHCHelkovskoe_shosse'=>'/shops/shchelkovskoe_shosse',
'/product/prodlevauschie-prezervativy-s-relefom-masculan-ultra-3-3-sht'=>'/',
'/product/voshititelnyi-komplekt-dream-weaver'=>'/',
'/product/ocharovatelnaya-sorochka-lets-play'=>'/',
'/product/ehko-lubrikant-blue-laguna-30ml'=>'/',
'/product/pyatdesyat-ottenkov-svobody'=>'/',
'/product/kniga-3-trilogii-ya-lublu-tebya-avtor-kao-i'=>'/',
'/product/perezaryazhaemoe-vibrokolco-lux-lx4'=>'/',
'/product/klassicheskaya-vibropulya-we-aim-to-please'=>'/',
'/product/igrivoe-korotenkoe-platice-the-castle-of-love'=>'/',
'/product/malenkoe-plate-truba-like-a-devil-chernoe'=>'/',
'/product/perezaryazhaemyi-massazher-my-vibe-rozovyi'=>'/',
'/sexopedia/category'=>'/',
'/product/falloimitator-v-vide-morozhenogo-iscream'=>'/',
'/product/kniga-2-trilogii-ya-chuvstvuu-tebya-avtor-kao-i'=>'/',
'/product/we-vibe3-teal'=>'/',
'/product/vibromassazher-klitora-s-trusikami-fantasy-elite-white'=>'/',
'/product/luchshii-v-mire-vibrator-dlya-par-we-vibe-4-pink'=>'/',
'/product/kolgotki-devil-s-imitaciei-poyasa-dlya-chulok-chernye'=>'/',
'/product/vibromassazher-palmpower-personal-massager'=>'/',
'/product/sbruya-na-penis-metal-worx'=>'/',
'/product/ochischauschii-sprei-toycleaner-flush-dlya-seks-igrushek'=>'/',
'/product/perezaryazhaemyi-vibrator-so-stimulyatorom-klitora-the-silver-swan'=>'/',
'/product/komplekt-shkolnica-belyi-krasnyi'=>'/',
'/product/yaico-pulsator-my-ball-s-press-vklucheniem-rozovoe'=>'/',
'/product/nezhnye-prezervativy-masculan-classic-1-10-sht'=>'/',
'/product/bezremnevoi-strapon-fantasy-elite-8-black'=>'/',
'/manufacturer/1632'=>'/',
'/product/vakuumnaya-pompa-his-essential-pump-kit-i-analnyi-stimulyator-chernyi'=>'/',
'/product/lateksnye-prezervativy-sagami-xtreme-cobra-10-sht'=>'/',
'/product/perezaryazhaemyi-stimulyator-sqweel-go-dlya-oralnogo-seksa-purple'=>'/',
'/product/analnaya-probka-mini-vibro-tease-s-vibraciei-rozovyi'=>'/',
'/product/perezaryazhaemyi-stimulyator-sqweel-go-dlya-oralnogo-seksa-white'=>'/',
'/product/vibrokolco-the-big-o'=>'/',
'/product/prezervativy-uvelichennogo-razmera-masculan-classic-4-10-sht'=>'/',
'/product/zamechatelnoe-platice-the-animal-inside'=>'/',
'/product/seksualnyi-penuar-dolce-vita'=>'/',
'/product/prezervativy-s-dvoinoi-zaschitoi-masculan-ultra-2-10-sht'=>'/',
'/product/falloimitator-realistic-cock-6-black'=>'/',
'/product/strapon-zhenskii-s-vibraciei-double-delight-strap-on'=>'/',
'/product/pompa-vibromassazher-renegade-powerhouse-pump-chernaya'=>'/',
'/product/fallos-realistic-black-cock'=>'/',
'/product/effektnyi-podarochnyi-paket-tukzar'=>'/',
'/product/vibromassazher-realistik-casanova-s-utolscheniem-na-osnovanii-rozovyi'=>'/',
'/product/provociruuschee-plate-have-fun-princess'=>'/',
'/product/komplekt-bust-i-ubochka-deeper-in-hell'=>'/',
'/product/serebristoe-mini-plate-deeper-in-hell'=>'/',
'/product/strapon-universalnyi-passion-hollow-strap-on-10-fioletovyi'=>'/',
'/product/relefnyi-vibrator-kanikule-my-vibe-rozovyi'=>'/',
'/product/universalnye-trusiki-dlya-krepleniya-falloimitatorov-basix-universal-harness-one-size'=>'/',
'/product/vibro-yaico-relentless-vibrations-remote-control-egg-na-radioupravlenii-chernoe'=>'/',
'/Adresa_magazinov_v_Moskve/'=>'/adresa-magazinov-on-i-ona-v-moskve-i-mo',
'/category/analnaya-smazka'=>'/category/analnye-smazki',
'/category/Analnye_igrushki'=>'/category/analnye_igrushki',
'/category/Analnye_shariki_elochki'=>'/category/analnye_shariki_elochki',
'/category/Bezremnevye-strapony'=>'/category/bezremnevye-strapony',
'/category/Bolshie-falloimitatory'=>'/category/bolshie-falloimitatory',
'/category/dildo'=>'/category/falloimitatory',
'/category/Duhi_s_feromonami'=>'/category/duhi_s_feromonami',
'/category/Dvustoronnie_fallosy'=>'/category/dvustoronnie_fallosy',
'/category/Erektsionnye-koltsa'=>'/category/erekcionnye-kolca-nasadki',
'/category/Eroticheskoe-bele'=>'/category/eroticheskoe-bele',
'/category/Fisting'=>'/category/fisting',
'/category/Icicles'=>'/category/icicles',
'/category/Intimnaya-kosmetika'=>'/category/intimnaya-kosmetika',
'/category/letnee-specpredlozhenie'=>'/category/podarki-k-novomu-2017-godu',
'/category/Maski-klyapy'=>'/category/maski-klyapy',
'/category/Massazhery-prostaty'=>'/category/massazhery-prostaty',
'/category/Massazhnye-masla'=>'/category/massazhnye-masla',
'/category/Naruchniki-bondazh'=>'/category/naruchniki-bondazh',
'/category/oralnaya-smazka'=>'/category/oralnye-smazki',
'/category/pestisy-nakladki-na-grud'=>'/category/pestisy-nakladki-na-soski',
'/category/Pletki-shlepalki'=>'/category/pletki-shlepalki',
'/category/Prezervativy'=>'/category/prezervativy',
'/category/ScreamingO'=>'/category/screamingo',
'/category/Seks-mebel-kacheli'=>'/category/seks-mebel-kacheli',
'/category/service-BDSM-i-fetish'=>'/category/service-bdsm-i-fetish',
'/category/Sex_kukly'=>'/category/sex_kukly',
'/category/smazka-na-silikonovoi-osnove'=>'/category/smazki-na-silikonovoi-osnove',
'/category/smazka-na-vodnoi-osnove'=>'/category/smazki-na-vodnoi-osnove',
'/category/Smazki-lubrikanty'=>'/category/smazki-lubrikanty',
'/category/stimulyatory-iz-stekla-i-metalla'=>'/category/stimulyatory-iz-metalla-stekla-i-keramiki',
'/category/Stimulyatory-klitora'=>'/category/stimulyatory-klitora',
'/category/Stimulyatory-tochki-G'=>'/category/stimulyatory-tochki-g',
'/category/Strapony_Harness'=>'/category/strapony_harness',
'/category/Trusiki_Harness'=>'/category/trusiki_harness',
'/category/uvelichenie-chlena'=>'/category/udlinyauschie-nasadki-na-penis',
'/category/vaginalnaya-smazka'=>'/category/vaginalnye-smazki',
'/category/Vaginalnye_shariki'=>'/category/kupit_vaginalnye_shariki',
'/category/Vaginy-Realistiki'=>'/category/vaginy-realistiki',
'/category/Vaginy-realistiki'=>'/category/vaginy-realistiki',
'/category/Vakuumnye-pompy'=>'/category/vakuumnye-pompy',
'/category/vesennee-specpredlozhenie'=>'/category/podarki-k-novomu-2017-godu',
'/category/vibratory-dlya-apple'=>'/category/ohmibod-vibratory-dlya-apple',
'/category/vibratory-dlya-partnerov'=>'/category/vibratory-dlya-par',
'/category/vibratory-Hi-Tech'=>'/category/vibratory-hi-tech',
'/category/We-Vibe'=>'/category/we-vibe',
'/category/zazhimy-dlya-soskov-kolca'=>'/category/zazhimy-dlya-soskov-i-polovyh-gub',
'/category/zazhimy-dlya-soskov-ukrasheniya-dlya-polovyh-gub'=>'/category/zazhimy-dlya-soskov-i-polovyh-gub',
'/category/ZHenskie-pompy'=>'/category/zhenskie-pompy',
'/category/Zini'=>'/category/zini',
'/dostavka/'=>'/dostavka',
'/Frunzenskaya_naberegnaya'=>'/shops/frunzenskaya_naberegnaya',
'/horoscope/'=>'/catalog/BDSM-i-fetish',
'/kutuzovskii-prospekt_26'=>'/shops/kutuzovskii-prospekt_26',
'/Leningradskoe_shosse'=>'/shops/leningradskoe-shosse',
'/leningradskoe-shosse'=>'/shops/leningradskoe-shosse',
'/Lesnoj_prospekt'=>'/shops/lesnoj_prospekt',
'/magazin-on-i-ona-v-g-moskva-leninskii-prospekt'=>'/shops/magazin-on-i-ona-v-g-moskva-leninskii-prospekt',
'/magazin-on-i-ona-v-g-moskva-varshavskoe-shosse'=>'/shops/magazin-on-i-ona-v-g-moskva-varshavskoe-shosse',
'/manufacturer/Bathmate'=>'/manufacturer/bathmate',
'/manufacturer/Bijoux'=>'/manufacturer/bijoux',
'/news/lubimye-zhenschiny-pozdravlyaem-vas-s-nastupivshei-vesnoi-i-8-marta-1'=>'/news/lubimye-zhenschiny-pozdravlyaem-vas-s-nastupivshei-vesnoi-i-8-marta',
'/product/Analnaya-probka-Renegade-2'=>'/product/analnaya-probka-renegade-2',
'/product/Bolshaya-analnaya-probka-Renegade-1-XL'=>'/product/bolshaya-analnaya-probka-renegade-1-xl',
'/product/chistyaschee-sredstvo-dlya-igrushek-jo-unscented-anti-bacterial-toy-cleaner-50-ml'=>'/product/antibakterialnoe-ochischauschee-sredstvo-dlya-igrushek-jo-unscented-anti-bacterial-toy-cleaner-50-ml',
'/product/Dyshashhij_klyap_Sinful-Ball_Gag-'=>'/product/dyshashhij_klyap_sinful-ball_gag-',
'/product/EHrektsionnoe-vibro-koltso-s-petlej-dlya-moshonki-Guardian-Clear'=>'/product/ehrektsionnoe-vibro-koltso-s-petlej-dlya-moshonki-guardian-clear',
'/product/eroticheskoe-massazhnoe-maslo-sensation-lavender'=>'/product/eroticheskoe-massazhnoe-maslo-sensation-lavender-250-ml',
'/product/Kukla-Ashlynn-Brooke'=>'/product/kukla-ashlynn-brooke',
'/product/Maska_na_glaza_Sinful-Blindfold'=>'/product/maska_na_glaza_sinful-blindfold',
'/product/Muzhskaya-bolshaya-kolba-Renegade-3'=>'/product/muzhskaya-bolshaya-kolba-renegade-3',
'/product/Muzhskaya-malaya-kolba-Renegade-2'=>'/product/muzhskaya-malaya-kolba-renegade-2',
'/product/Muzhskaya-srednyaya-kolba-Renegade-2,5'=>'/product/muzhskaya-srednyaya-kolba-renegade-2,5',
'/product/Nanozhniki-soedinennye-tsepyu-Sinful-Ankle-Cuffs'=>'/product/nanozhniki-soedinennye-tsepyu-sinful-ankle-cuffs',
'/product/Naruchniki-soedinennye-tsepyu-Sinful-Wrist-Cuffs'=>'/product/naruchniki-soedinennye-tsepyu-sinful-wrist-cuffs',
'/product/Nasos-dlya-vakuumnoj-pompy-Renegade'=>'/category/igrovye-kostumy',
'/product/Nezhno-rozovyie-stringi-s-reguliruemoy-manzhetoy-Dolce-Vita'=>'/product/nezhno-rozovyie-stringi-s-reguliruemoy-manzhetoy-dolce-vita',
'/product/Nezhno-rozovyie-stringi-s-reguliruemoy-manzhetoy-Dolce-Vita-ML'=>'/product/nezhno-rozovyie-stringi-s-reguliruemoy-manzhetoy-dolce-vita-ml',
'/product/Oshejnik-s-tsepyu-povodkom-Sinful-Collar'=>'/product/oshejnik-s-tsepyu-povodkom-sinful-collar',
'/product/Plet_mnogokhvostnaya_Sinful-Whip'=>'/product/plet_mnogokhvostnaya_sinful-whip',
'/product/pyatdesyat-ottenkov-serogo'=>'/product/kniga-1-trilogii-pyatdesyat-ottenkov-serogo-avtor-e-l-dzheims',
'/product/SHlepalka-rozovaya-Sinful-Paddle'=>'/product/shlepalka-rozovaya-sinful-paddle',
'/product/Stringi-s-prozrachnoy-peredney-chastyu-Deeper-in-Hell-ML'=>'/product/stringi-s-prozrachnoy-peredney-chastyu-deeper-in-hell-ml',
'/product/Svetlo-rozovyie-G-stringi-s-seryim-kruzhevom-Dolce-Vita-ML'=>'/product/svetlo-rozovyie-g-stringi-s-seryim-kruzhevom-dolce-vita-ml',
'/product/Tektsurirovannye-analnye-busy-Perles-DAmour-Black'=>'/product/tektsurirovannye-analnye-busy-perles-damour-black',
'/product/Vakuumnaya_pompa_Pump_Worx-Red'=>'/product/vakuumnaya_pompa_pump_worx-red',
'/product/vibromassazher-dr-joel-kaplan-gyrating-massager-s-rotaciei-chernyi'=>'/product/originalnyi-vibromassazher-dr-joel-kaplan-gyrating-massager-chernyi',
'/product/Waterglide_Anal'=>'/product/waterglide_anal',
'/product/Waterglide_Premium'=>'/product/waterglide_premium',
'/product/Waterglide_strawberry'=>'/product/waterglide_strawberry',
'/product/Waterglide_vanilla'=>'/product/waterglide_vanilla',
'/product/ZINI_HUA_pink'=>'/product/zini_hua_pink',
'/product/ZINI_ROAE'=>'/product/zini_roae',
'/product/ZINI_ROAE_cherry'=>'/product/zini_roae_cherry',
'/product/ZINI_SEED'=>'/product/zini_seed',
'/sadovaya'=>'/shops/sadovaya',
'/sexshop'=>'/',
'/SredneohtinskiY_prospekt'=>'/shops/sredneohtinskiy_prospekt',
'/ulica-yarcevskaya'=>'/shops/ulica-yarcevskaya',
'/ulitsa_Babushkina'=>'/shops/ulitsa_babushkina',
'/ulitsa_Bolshaya_Tulskaya'=>'/shops/ulitsa_bolshaya_tulskaya',
'/ulitsa_Savushkina'=>'/shops/ulitsa_savushkina',
'/ulitsa_Sovetskoy_Armii'=>'/shops/ulitsa_sovetskoy_armii',
'/zelenograd'=>'/shops/zelenograd',
'/collection/101'=>'/collection/neon-luv-touch',
'/collection/1013'=>'/collection/adam-male-toys',
'/collection/1014'=>'/collection/jolie-platinum',
'/collection/1015'=>'/collection/sliders',
'/collection/1016'=>'/collection/maximum',
'/collection/1020'=>'/collection/starlight-gems',
'/collection/1023'=>'/collection/fusion',
'/collection/1027'=>'/collection/apollo',
'/collection/1028'=>'/collection/vivid-raw',
'/collection/1032'=>'/collection/masq-by-baci',
'/collection/1037'=>'/collection/fetish-factory',
'/collection/1072'=>'/collection/4play',
'/collection/1076'=>'/collection/bare-bondage',
'/collection/1098'=>'/collection/american-bombshell',
'/collection/1124'=>'/collection/vanity-by-jopen',
'/collection/1126'=>'/collection/silhouette',
'/collection/1127'=>'/collection/nipple-play',
'/collection/1133'=>'/collection/dona',
'/collection/1146'=>'/collection/first-time',
'/collection/1148'=>'/collection/body-and-soul',
'/collection/1149'=>'/collection/coco-licious',
'/collection/1150'=>'/collection/pocket-exotics',
'/collection/1151'=>'/collection/power-play',
'/collection/1152'=>'/collection/charisma',
'/collection/279'=>'/collection/extreme-toyz',
'/collection/304'=>'/collection/fetish-fantasy-series',
'/collection/306'=>'/collection/classix',
'/collection/515'=>'/collection/icicles',
'/collection/518'=>'/collection/metal-worx',
'/collection/555'=>'/collection/natural-instinct',
'/collection/591'=>'/collection/fetish-fantasy-lingerie',
'/collection/599'=>'/collection/real-feel',
'/collection/614'=>'/collection/jelly-gems',
'/collection/616'=>'/collection/fetish-fantasy-elite',
'/collection/659'=>'/collection/tlc',
'/collection/706'=>'/collection/fetish-fantasy-series-limited-edition',
'/collection/718'=>'/collection/lux-male-stimulator',
'/collection/719'=>'/collection/swan',
'/collection/720'=>'/collection/leaf-by-swan',
'/collection/763'=>'/collection/renegade',
'/collection/764'=>'/collection/crystal',
'/collection/766'=>'/collection/white-box-collection',
'/collection/768'=>'/collection/star-signature-boxes',
'/collection/769'=>'/collection/black-box-collection',
'/collection/796'=>'/collection/pump-worx',
'/collection/797'=>'/collection/fetish-fantasy-extreme',
'/collection/804'=>'/collection/nouvelle',
'/collection/824'=>'/collection/x-toy',
'/collection/833'=>'/collection/key-by-jopen',
'/collection/839'=>'/collection/gmi-x',
'/collection/842'=>'/collection/booty-call',
'/collection/843'=>'/collection/lia',
'/collection/847'=>'/collection/colt',
'/collection/849'=>'/collection/dr-joel-kaplan',
'/collection/851'=>'/collection/love-rider',
'/collection/852'=>'/collection/posh',
'/collection/853'=>'/collection/power-stud',
'/collection/854'=>'/collection/pure-skin',
'/collection/901'=>'/collection/couture',
'/collection/904'=>'/collection/dr-laura-berman',
'/collection/905'=>'/collection/ceramix',
'/collection/907'=>'/collection/jack-rabbits',
'/collection/909'=>'/collection/adonis',
'/collection/910'=>'/collection/bendies',
'/collection/911'=>'/collection/shane-s-world',
'/collection/912'=>'/collection/embrace',
'/collection/933'=>'/collection/kanikule',
'/collection/938'=>'/collection/pure-aluminium',
'/collection/951'=>'/collection/sqweel-go',
'/collection/971'=>'/collection/lust-by-jopen',
'/collection/975'=>'/collection/scandal',
'/collection/978'=>'/collection/entice',
'/collection/981'=>'/collection/corsets-by-baci',
'/collection/986'=>'/collection/extreme-dollz',
'/collection/993'=>'/collection/real-feel-deluxe',
'/collection/994'=>'/collection/anal-fantasy',
'/collection/999'=>'/collection/fetish-fantasy-gold',
'/manufacturer/1010'=>'/manufacturer/casmir-polsha',
'/manufacturer/1024'=>'/manufacturer/gopaldas-kitai',
'/manufacturer/1085'=>'/manufacturer/womanizer-germaniya',
'/manufacturer/1089'=>'/manufacturer/chilirose-polsha',
'/manufacturer/1090'=>'/manufacturer/excellent-beauty-polsha',
'/manufacturer/1128'=>'/manufacturer/avanua-polsha',
'/manufacturer/1129'=>'/manufacturer/andalea-polsha',
'/manufacturer/1132'=>'/manufacturer/anais-polsha',
'/manufacturer/1184'=>'/manufacturer/vnew-ssha',
'/manufacturer/1187'=>'/manufacturer/hustler-lingerie-ssha',
'/manufacturer/1188'=>'/manufacturer/hustler-toys-ssha',
'/manufacturer/1189'=>'/manufacturer/ann-devine-ssha',
'/manufacturer/1190'=>'/manufacturer/coquette-int-kanada',
'/manufacturer/1199'=>'/manufacturer/blueline-ssha',
'/manufacturer/1202'=>'/manufacturer/seven-til-midnight-ssha',
'/manufacturer/1203'=>'/manufacturer/ganzo-velikobritaniya',
'/manufacturer/1204'=>'/manufacturer/okamoto-yaponiya',
'/manufacturer/1237'=>'/manufacturer/diogol-franciya',
'/manufacturer/1239'=>'/manufacturer/kokos-u-koreya',
'/manufacturer/1240'=>'/manufacturer/satisfyer-germaniya',
'/manufacturer/1241'=>'/manufacturer/baile-kitai',
'/manufacturer/1252'=>'/manufacturer/danalife-aps-daniya',
'/manufacturer/1258'=>'/manufacturer/demoniq-polsha',
'/manufacturer/1264'=>'/manufacturer/passion-polsha',
'/manufacturer/1266'=>'/manufacturer/fever-velikobritaniya',
'/manufacturer/1270'=>'/manufacturer/templife-kitai',
'/manufacturer/1271'=>'/manufacturer/le-frivole-kitai',
'/manufacturer/1274'=>'/manufacturer/me-seduce-polsha',
'/manufacturer/1275'=>'/manufacturer/obsessive-polsha',
'/manufacturer/1278'=>'/manufacturer/lux-fetish-ssha',
'/manufacturer/1279'=>'/manufacturer/classic-erotica-ssha',
'/manufacturer/1289'=>'/manufacturer/revel-body-ssha',
'/manufacturer/1292'=>'/manufacturer/unilatex-ispaniya',
'/manufacturer/1296'=>'/manufacturer/shots-toys-niderlandy',
'/manufacturer/1302'=>'/manufacturer/erotic-fantasy-shveicariya',
'/manufacturer/201'=>'/manufacturer/pipedream-ssha',
'/manufacturer/394'=>'/manufacturer/doc-johnson-ssha',
'/manufacturer/44'=>'/manufacturer/we-vibe-kanada',
'/manufacturer/468'=>'/manufacturer/screaming-o-ssha',
'/manufacturer/477'=>'/manufacturer/internetmarketing-bielefeld-gmbh-germaniya',
'/manufacturer/495'=>'/manufacturer/zini-koreya',
'/manufacturer/522'=>'/manufacturer/sk-vizit-rossiya',
'/manufacturer/544'=>'/manufacturer/gvibe-ex-funtoys-velikobritaniya',
'/manufacturer/587'=>'/manufacturer/gtc-koreya',
'/manufacturer/637'=>'/manufacturer/topco-sales-ssha',
'/manufacturer/660'=>'/manufacturer/baci-lingerie-ssha',
'/manufacturer/786'=>'/manufacturer/masculan-germaniya',
'/manufacturer/799'=>'/manufacturer/envy-ssha',
'/manufacturer/813'=>'/manufacturer/bistli-aksessoriz-rossiya',
'/manufacturer/823'=>'/manufacturer/beauty-brands-limited-velikobritaniya',
'/manufacturer/831'=>'/manufacturer/jopen-ssha',
'/manufacturer/844'=>'/manufacturer/jo-system-ssha',
'/manufacturer/891'=>'/manufacturer/lovehoney-angliya',
'/manufacturer/992'=>'/manufacturer/daydream-germaniya',

);

define('DUR_DEBUG', 0); //Включение режима отладки (вывод инфо в конце исходного текста на странице)
define('DUR_PREPEND_APPEND', 0); //Единая точка входа (.htaccess) Не рекомендуется
define('DUR_BASE_ROOT', 0); //Прописать принудительно <base href="https://domain.com/"> Бывает полезно при ссылках вида href="?page=2". При указании строки, пропишет ее
define('DUR_LINK_PARAM', 0); //Дописать путь перед ссылками вида href="?page=2"
define('DUR_ANC_HREF', 0); //Пофиксить ссылки вида href="#ancor"
define('DUR_ROOT_HREF', 1); //Пофиксить ссылки вида href="./"
define('DUR_REGISTER_GLOBALS', 0); //Регистрировать глобальные переменные
define('DUR_SKIP_POST', 1); //Не выполнять подмену при запросе POST
define('DUR_CMS_TYPE', 'NONE'); //Включение особенностей для CMS, возможные значения: NONE, NETCAT, JOOMLA, HTML, DRUPAL, WEBASYST, ICMS, UMI
define('DUR_OUTPUT_COMPRESS', 'AUTO'); //Сжатие выходного потока, возможные значения: NONE, GZIP, DEFLATE, AUTO, SKIP
define('DUR_SUBDOMAINS', 0); //Обрабатывать поддомены, указываем здесь основной домен!
define('DUR_SKIP_USERAGENT', '#^(|mirror)$#'); //Не выполнять редиректы при указанном HTTP_USER_AGENT (регулярка)
define('DUR_SKIP_URLS', '#^/_?(admin|manag|bitrix|indy|cms|phpshop|varvara.php|captcha|jscripts/|modules|includes|templates|wp-admin|published|webasyst)#siU'); //Skip URLS
define('DUR_FIX_CONTLEN', 0); //Фиксить Content-Length
define('DUR_PATHINFO', 0); //Регистрировать переменные для передачи вида /index.php/uri
define('DUR_REMOVE_UTM', '#^(_openstat|yclid|gclid)#siU');
// define('DUR_REMOVE_UTM', '#^(_openstat|yclid|gclid|utm_)#siU');
// /new

define('DUR_FIX_RELATIVE', 1); //Фиксить относительные ссылки (только для DUR_MAIN_CYCLE = ortodox)
define('DUR_FIX_DOTTED', 0); //Фиксить ссылки от "./" (только для DUR_MAIN_CYCLE = ortodox)
define('DUR_FIX_HTTP_HOST', $_SERVER['HTTP_HOST']); //Фиксить HTTP_HOSTв ссылках, прописываем, например, значение "www.mysite.ru", чтобы сократить количество host-зависимых подмен ссылок
define('DUR_CACHE_REWRITED', 0); //Кэшировать все замены в этом рерайтере, должна быть создана папка d-cache в корне с правами на запись
define('DUR_CACHE_MEMORY', 40960000); //Критическая масса кеша (в байтах), при превышении этого значения кеш очищается
define('DUR_CACHE_TIME', 3); //Критическое время жизни кеша, при превышении этого значения кеш очищается
define('DUR_MAIN_CYCLE', 'callback'); //Константа для выбора типа основного цикла, значения: callback, str_replace, ortodox

// Раздел обработки

define('DUR_TIME_START', microtime(true));
define('DUR_REQUEST_URI', $_SERVER['REQUEST_URI']);
define('DUR_HTTP_HOST', $_SERVER['HTTP_HOST']);
define('DUR_FULL_URI', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('BX_COMPRESSION_DISABLED', true); //Hack for bitrix
define('DUR_SKIP_THIS', preg_match(DUR_SKIP_URLS, DUR_REQUEST_URI, $aM));
define('DUR_SKIP_R301', !isset($_SERVER['HTTP_USER_AGENT']) || preg_match(DUR_SKIP_USERAGENT, $_SERVER['HTTP_USER_AGENT']));

if (defined('DUR_DEBUG') && DUR_DEBUG) {
	ini_set('display_errors', 1);
	ini_set('error_reportings', E_ALL);
}

if (defined('DUR_REMOVE_UTM') && DUR_REMOVE_UTM) {
	$url_parts = parse_url($_SERVER['REQUEST_URI']);

	if (array_key_exists('query', $url_parts) && $url_parts['query']) {
		parse_str($url_parts['query'], $params);
		$params_keys = array_keys($params);
		$keys_length = count($params_keys);

		global $HTTP_GET_VARS;

		for ($j=0; $j<$keys_length; $j++) {
			$key = $params_keys[$j];
			if (preg_match(DUR_REMOVE_UTM, $key)) {
				unset($params[$key]);
				unset($_GET[$key]);
				unset($_REQUEST[$key]);
				if (!is_null($HTTP_GET_VARS)) {
					unset($HTTP_GET_VARS[$key]);
				}
			}
		}

		$url_parts['query'] = http_build_query($params);

		$_SERVER['REQUEST_URI'] = $url_parts['path'] . ($url_parts['query'] ? '?' . $url_parts['query'] : '');
	}
}

if (in_array($_SERVER['REQUEST_URI'], $a410Response) && !DUR_SKIP_THIS) {
	header('HTTP/1.0 410 Gone');
	echo '<h1 style="font-size: 18pt;">Ошибка 410</h1><p>Страница удалена</p><p style="text-align: right; margin: 10px;"><a href="/">На главную</a></p>';
	exit;
}

if (in_array($_SERVER['REQUEST_URI'], $a404Response) && !DUR_SKIP_THIS) {
	dur404native();
}

if (isset($aR301SkipCheck[$_SERVER['REQUEST_URI']]) && !DUR_SKIP_THIS && !DUR_SKIP_R301) {
	if (!defined('DUR_SKIP_POST') || !DUR_SKIP_POST || (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
		header('Location: ' . $aR301SkipCheck[$_SERVER['REQUEST_URI']], true, 301);
		exit;
	}
}

if ($u != $uri && !DUR_SKIP_THIS && !DUR_SKIP_R301) {
	if (!defined('DUR_SKIP_POST') || !DUR_SKIP_POST || (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
		header('Location: ' . $u, true, 301);
		exit;
	}
}

foreach($aURLRewriter as $sKey => $sVal) {
	$aURLRewriter[$sKey] = str_replace(array(
		'р',
		'у',
		'к',
		'е',
		'н',
		'х',
		'в',
		'а',
		'о',
		'ч',
		'с',
		'м',
		'и',
		'т',
		' '
	) , array(
		'p',
		'y',
		'k',
		'e',
		'h',
		'x',
		'b',
		'a',
		'o',
		'4',
		'c',
		'm',
		'n',
		't',
		'_'
	) , $sVal);
	if (!defined('DUR_SEO_REQUEST_URI') && ($sVal == $_SERVER['REQUEST_URI'])) {
		define('DUR_SEO_REQUEST_URI', $sKey);
	}
}

$aURFlip = array_flip($aURLRewriter);

// Многократная вложенность замен (до 10)

for ($i = 0; $i < 10; $i++) {
	foreach($aURLRewriter as $sFrom => $sTo) {
		if (isset($aURLRewriter[$sTo])) {
			$aURLRewriter[$sFrom] = $aURLRewriter[$sTo];
			$aURFlip[$aURLRewriter[$sTo]] = $sFrom;
		}
	}
}

// Joomla hack! (Против защиты от register globals)

if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'JOOMLA')) {
	$_SERVER['dur'] = array(
		$aURLRewriter,
		$aURFlip,
		$aURLRewriterOnly
	);
}

// Единая точка входа

if (defined('DUR_PREPEND_APPEND') && DUR_PREPEND_APPEND && !DUR_SKIP_THIS) {
	durRun();
}

// Функции

function durRun()
{
	if (defined('DUR_RUNNED')) return;

	//    if (isset())

	define('DUR_RUNNED', 1);
	durR301();
	ob_start('durLinkChanger');
	durIFRewrite();
}

function dur404()
{
	$aPages404 = array(
		'404.php',
		'404.html',
		'404.htm',
		'index.php',
		'index.html',
		'index.htm'
	);
	header('HTTP/1.1 404 Not found');
	foreach($aPages404 as $sPage404) {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $sPage404)) {
			include ($_SERVER['DOCUMENT_ROOT'] . '/' . $sPage404);

			exit;
		}
	}

	echo '<h1>Ошибка 404</h1><p>Страница не найдена</p><p style="text-align: right; margin: 10px;"><a href="/">На главную</a></p>';
	exit;
}

function dur404native()
{
	$_SERVER['REQUEST_URI'] = '/thispagewasdeleted';
	$_GET = $_REQUEST = array();
}

function durRewrite($sURL)
{
	global $QUERY_STRING, $REQUEST_URI, $REDIRECT_URL, $HTTP_GET_VARS;
	define('DUR_DEBUG_BEFORE', "SERVER:\n" . durDebugVar($_SERVER) . "\n\nGET:\n" . durDebugVar($_GET) . "\n\nREQUEST:\n" . durDebugVar($_REQUEST) . "\n");
	if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'WEBASYST')) {
		$sURL = '/?__furl_path=' . substr($sURL, 1) . '&frontend=1';
	}

	if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'ICMS')) {
		$sURL = '/index.php?path=' . substr($sURL, 1, -5) . '&frontend=1';
	}

	$QUERY_STRING = strpos($sURL, '?') ? substr($sURL, strpos($sURL, '?') + 1) : '';
	$REQUEST_URI = $sURL;
	$REDIRECT_URL = $sURL;
	$_SERVER['QUERY_STRING'] = $QUERY_STRING;
	$_SERVER['REDIRECT_URL'] = $sURL;
	$_SERVER['REQUEST_URI'] = $sURL;
	if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'NETCAT')) {
		putenv('REQUEST_URI=' . $sURL);
		$_ENV['REQUEST_URI'] = $sURL;
	}

	if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'DRUPAL')) {
		$_GET['q'] = substr($sURL, 1);
		$_REQUEST['q'] = substr($sURL, 1);
	}

	if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'UMI')) {
		$_GET['path'] = substr($sURL, 1);
		$_REQUEST['path'] = substr($sURL, 1);
	}

	if (preg_match_all('%[\?&]([^\=]+)\=([^&]*)%', $sURL, $aM)) {
		$aParams = array();
		foreach($aM[1] as $iKey => $sName) {
			$sVal = urldecode($aM[2][$iKey]);
			if (preg_match('#^(.+)\[\]$#siU', $sName, $aMatch)) {
				$aParams[$aMatch[1]][] = $sVal;
			}
			elseif (preg_match('#^(.+)\[([\w-]+)\]$#siU', $sName, $aMatch)) {
				$aParams[$aMatch[1]][$aMatch[2]] = $sVal;
			}
			else {
				$aParams[$sName] = $sVal;
			}
		}

		foreach($aParams as $sKey => $mVal) {
			$_GET[$sKey] = $mVal;
			$HTTP_GET_VARS[$sKey] = $mVal;
			$_REQUEST[$sKey] = $mVal;
			if (defined('DUR_REGISTER_GLOBALS') && DUR_REGISTER_GLOBALS) {
				global $$sKey;
				$$sKey = $mVal;
			}
		}
	}

	if (defined('DUR_PATHINFO') && DUR_PATHINFO) {
		$_SERVER['PATH_INFO'] = substr($sURL, 1);
		$_SERVER['PHP_SELF'] = $sURL;
	}

	if (DUR_CMS_TYPE == 'HTML') {
		$sFName = $sURL;
		if ($iPos = strpos($sFName, '?')) {
			$sFName = substr($sFName, 0, $iPos);
		}

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $sFName)) {
			include ($_SERVER['DOCUMENT_ROOT'] . $sFName);

			exit;
		}
		else {
			dur404();
		}
	}
}

function durIFRewrite()
{
	global $aURFlip, $aURLRewriter;
	if (DUR_SKIP_THIS) return;
	$sKey = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if (defined('DUR_SUBDOMAINS') && DUR_SUBDOMAINS && isset($aURFlip[$sKey])) {
		if (!defined('DUR_ORIG_RURI')) {
			define('DUR_ORIG_RURI', $aURFlip[$sKey]);
		}

		durRewrite($aURFlip[$sKey]);
	}
	elseif (isset($aURFlip[$_SERVER['REQUEST_URI']])) {
		if (!defined('DUR_ORIG_RURI')) {
			define('DUR_ORIG_RURI', $aURFlip[$_SERVER['REQUEST_URI']]);
		}

		durRewrite($aURFlip[$_SERVER['REQUEST_URI']]);
	}
	elseif (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'HTML')) {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
			durRewrite($_SERVER['REQUEST_URI']);
		}
		else {
			dur404();
		}
	}
}

function durR301()
{
	global $aURFlip, $aURLRewriter;
	if (DUR_SKIP_THIS || DUR_SKIP_R301) return;
	if (defined('DUR_SKIP_POST') && DUR_SKIP_POST && (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')) {
		return;
	}

	if (isset($aURLRewriter[$_SERVER['REQUEST_URI']])) {
		if ('https://' . DUR_HTTP_HOST == trim($aURLRewriter[$_SERVER['REQUEST_URI']], '/')) {
			return;
		}

		header('Location: ' . $aURLRewriter[$_SERVER['REQUEST_URI']], true, 301);
		exit;
	}
}

function durRExpEscape($sStr)
{
	return str_replace(array(
		'?',
		'.',
		'-',
		':',
		'%',
		'[',
		']',
		'(',
		')'
	) , array(
		'\\?',
		'\\.',
		'\\-',
		'\\:',
		'\\%',
		'\\[',
		'\\]',
		'\\(',
		'\\)'
	) , $sStr);
}

function durReplaceOnceLink($sLink, $sNewLink, $sContent)
{
	$sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*' . durRExpEscape($sLink) . '([#\'"\s>])%siU', '$1' . $sNewLink . '$2', $sContent);
	if (strpos($sLink, '&')) $sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*' . durRExpEscape(str_replace('&', '&amp;', $sLink)) . '([#\'"\s>])%siU', '$1' . $sNewLink . '$2', $sContent);
	return $sContent;
}

function durReplaceLink($sHost, $sBase, $sFrom, $sTo, $sContent)
{
	$sNewLink = $sTo;

	//  Link type: "https://domain/link"

	$sContent = durReplaceOnceLink('https://' . $sHost . $sFrom, $sNewLink, $sContent);

	//  Link type: "https://domain.com/link"
	//     $sContent = durReplaceOnceLink ('https://' . $sHost . $sFrom, $sNewLink, $sContent);
	//  Link type: "//domain.com/link"
	//     $sContent = durReplaceOnceLink ('//' . $sHost . $sFrom, $sNewLink, $sContent);

	if (!defined('DUR_FIRST_TIC')) {

		//    Link type: "/link"

		$sContent = durReplaceOnceLink($sFrom, $sNewLink, $sContent);

		//    Link type: "./link"

		if (defined('DUR_FIX_DOTTED') && DUR_FIX_DOTTED) {
			$sContent = durReplaceOnceLink('.' . $sFrom, $sNewLink, $sContent);
		}

		if (defined('DUR_FIX_RELATIVE') && DUR_FIX_RELATIVE) {

			// Link type: "link" (Calc fromlink)

			$aLink = explode('/', $sFrom);
			$aBase = empty($sBase) ? array(
				''
			) : explode('/', str_replace('//', '/', '/' . $sBase));
			$sReplLnk = '';
			for ($i = 0; $i < max(count($aLink) , count($aBase)); $i++) {
				if (isset($aBase[$i]) && isset($aLink[$i])) {
					if ($aLink[$i] == $aBase[$i]) {
						continue;
					}
					else {
						for ($j = $i; $j < count($aBase); $j++) {
							$sReplLnk.= '../';
						}

						for ($j = $i; $j < count($aLink); $j++) {
							$sReplLnk.= $aLink[$j] . '/';
						}

						break;
					}
				}
				elseif (isset($aLink[$i])) {
					$sReplLnk.= $aLink[$i] . '/';
				}
				elseif (isset($aBase[$i])) {
					$sReplLnk.= '../';
				}
			}

			$sReplLnk = preg_replace('%/+%', '/', $sReplLnk);
			$sReplLnk2 = trim($sReplLnk, '/');
			$sReplLnk3 = rtrim($sReplLnk2, '.');
			if (strlen($sReplLnk) > 1) {
				$sContent = durReplaceOnceLink($sReplLnk, $sNewLink, $sContent);
				if (defined('DUR_FIX_DOTTED') && DUR_FIX_DOTTED) {
					$sContent = durReplaceOnceLink('./' . $sReplLnk, $sNewLink, $sContent);
				}
			}

			if (($sReplLnk2 != $sReplLnk) && (strlen($sReplLnk2) > 1)) {
				$sContent = durReplaceOnceLink($sReplLnk2, $sNewLink, $sContent);
				if (defined('DUR_FIX_DOTTED') && DUR_FIX_DOTTED) {
					$sContent = durReplaceOnceLink('./' . $sReplLnk2, $sNewLink, $sContent);
				}
			}

			if (($sReplLnk3 != $sReplLnk2) && (strlen($sReplLnk3) > 1)) {
				$sContent = durReplaceOnceLink($sReplLnk3, $sNewLink, $sContent);
				if (defined('DUR_FIX_DOTTED') && DUR_FIX_DOTTED) {
					$sContent = durReplaceOnceLink('./' . $sReplLnk3, $sNewLink, $sContent);
				}
			}
		}
	}

	return $sContent;
}

function durGZDecode($sS)
{
	$sM = ord(substr($sS, 2, 1));
	$iF = ord(substr($sS, 3, 1));
	if ($iF & 31 != $iF) return null;
	$iLH = 10;
	$iLE = 0;
	if ($iF & 4) {
		if ($iL - $iLH - 2 < 8) return false;
		$iLE = unpack('v', substr($sS, 8, 2));
		$iLE = $iLE[1];
		if ($iL - $iLH - 2 - $iLE < 8) return false;
		$iLH+= 2 + $iLE;
	}

	$iFCN = $iFNL = 0;
	if ($iF & 8) {
		if ($iL - $iLH - 1 < 8) return false;
		$iFNL = strpos(substr($sS, 8 + $iLE) , chr(0));
		if ($iFNL === false || $iL - $iLH - $iFNL - 1 < 8) return false;
		$iLH+= $iFNL + 1;
	}

	if ($iF & 16) {
		if ($iL - $iLH - 1 < 8) return false;
		$iFCN = strpos(substr($sS, 8 + $iLE + $iFNL) , chr(0));
		if ($iFCN === false || $iL - $iLH - $iFCN - 1 < 8) return false;
		$iLH+= $iFCN + 1;
	}

	$sHCRC = '';
	if ($iF & 2) {
		if ($iL - $iLH - 2 < 8) return false;
		$calccrc = crc32(substr($sS, 0, $iLH)) & 0xffff;
		$sHCRC = unpack('v', substr($sS, $iLH, 2));
		$sHCRC = $sHCRC[1];
		if ($sHCRC != $calccrc) return false;
		$iLH+= 2;
	}

	$sScrc = unpack('V', substr($sS, -8, 4));
	$sScrc = $sScrc[1];
	$iSZ = unpack('V', substr($sS, -4));
	$iSZ = $iSZ[1];
	$iLBD = $iL - $iLH - 8;
	if ($iLBD < 1) return null;
	$sB = substr($sS, $iLH, $iLBD);
	$sS = '';
	if ($iLBD > 0) {
		if ($sM == 8) $sS = gzinflate($sB);
		else return false;
	}

	if ($iSZ != strlen($sS) || crc32($sS) != $sScrc) return false;
	return $sS;
}

function durGZDecode2($sS)
{
	$iLen = strlen($sS);
	$sDigits = substr($sS, 0, 2);
	$iMethod = ord(substr($sS, 2, 1));
	$iFlags = ord(substr($sS, 3, 1));
	if ($iFlags & 31 != $iFlags) return false;
	$aMtime = unpack('V', substr($sS, 4, 4));
	$iMtime = $aMtime[1];
	$sXFL = substr($sS, 8, 1);
	$sOS = substr($sS, 8, 1);
	$iHeaderLen = 10;
	$iExtraLen = 0;
	$sExtra = '';
	if ($iFlags & 4) {
		if ($iLen - $iHeaderLen - 2 < 8) return false;
		$iExtraLen = unpack('v', substr($sS, 8, 2));
		$iExtraLen = $iExtraLen[1];
		if ($iLen - $iHeaderLen - 2 - $iExtraLen < 8) return false;
		$sExtra = substr($sS, 10, $iExtraLen);
		$iHeaderLen+= 2 + $iExtraLen;
	}

	$iFilenameLen = 0;
	$sFilename = '';
	if ($iFlags & 8) {
		if ($iLen - $iHeaderLen - 1 < 8) return false;
		$iFilenameLen = strpos(substr($sS, $iHeaderLen) , chr(0));
		if ($iFilenameLen === false || $iLen - $iHeaderLen - $iFilenameLen - 1 < 8) return false;
		$sFilename = substr($sS, $iHeaderLen, $iFilenameLen);
		$iHeaderLen+= $iFilenameLen + 1;
	}

	$iCommentLen = 0;
	$sComment = '';
	if ($iFlags & 16) {
		if ($iLen - $iHeaderLen - 1 < 8) return false;
		$iCommentLen = strpos(substr($sS, $iHeaderLen) , chr(0));
		if ($iCommentLen === false || $iLen - $iHeaderLen - $iCommentLen - 1 < 8) return false;
		$sComment = substr($sS, $iHeaderLen, $iCommentLen);
		$iHeaderLen+= $iCommentLen + 1;
	}

	$sCRC = '';
	if ($iFlags & 2) {
		if ($iLen - $iHeaderLen - 2 < 8) return false;
		$sCalcCRC = crc32(substr($sS, 0, $iHeaderLen)) & 0xffff;
		$sCRC = unpack('v', substr($sS, $iHeaderLen, 2));
		$sCRC = $sCRC[1];
		if ($sCRC != $sCalcCRC) return false;
		$iHeaderLen+= 2;
	}

	$sDataCRC = unpack('V', substr($sS, -8, 4));
	$sDataCRC = sprintf('%u', $sDataCRC[1] & 0xFFFFFFFF);
	$iSize = unpack('V', substr($sS, -4));
	$iSize = $iSize[1];
	$iBodyLen = $iLen - $iHeaderLen - 8;
	if ($iBodyLen < 1) return false;
	$sBody = substr($sS, $iHeaderLen, $iBodyLen);
	$sS = '';
	if ($iBodyLen > 0) {
		switch ($iMethod) {
		case 8:
			$sS = gzinflate($sBody);
			break;

		default:
			return false;
		}
	}

	$sCRC = sprintf('%u', crc32($sS));
	$bCRCOK = ($sCRC == $sDataCRC);
	$bLenOK = ($iSize == strlen($sS));
	if (!$bLenOK || !$bCRCOK) return false;
	return $sS;
}

function durGZCheck($sContent)
{
	$iLen = strlen($sContent);
	if ($iLen < 18 || strcmp(substr($sContent, 0, 2) , "\x1f\x8b")) {
		return $sContent;
	}

	$sData = durGZDecode2($sContent);
	if (!$sData) {
		$sData = durGZDecode($sContent);
	}

	return $sData ? $sData : $sContent;
}

function durOutputCompress($sContent)
{
	if (!defined('DUR_OUTPUT_COMPRESS')) {
		define('DUR_OUTPUT_COMPRESS', 'SKIP');
	}

	if (DUR_OUTPUT_COMPRESS == 'SKIP') {
		return $sContent;
	}

	$aAccept = array();
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
		$aAccept = array_map('trim', explode(',', strtolower($_SERVER['HTTP_ACCEPT_ENCODING'])));
	}

	$bGZIP = in_array('gzip', $aAccept) && function_exists('gzencode');
	$bDEFL = in_array('deflate', $aAccept) && function_exists('gzdeflate');
	$sCompress = DUR_OUTPUT_COMPRESS;
	if ((!$bGZIP && !$bDEFL) || (!$bGZIP && ($sCompress == 'GZIP')) || (!$bDEFL && ($sCompress == 'DEFLATE'))) {
		$sCompress = 'NONE';
	}

	if ($sCompress == 'AUTO') {
		$sCompress = $bGZIP ? 'GZIP' : ($bDEFL ? 'DEFLATE' : 'NONE');
	}

	switch ($sCompress) {
	case 'GZIP':
		header('Content-Encoding: gzip');
		$sContent = gzencode($sContent);
		break;

	case 'DEFLATE':
		header('Content-Encoding: deflate');
		$sContent = gzdeflate($sContent, 9);
		break;

	default:

		// header('Content-Encoding: none');

	}

	return $sContent;
}

function durDebugEscape($sText)
{
	return str_replace(array(
		'--',
		'-->'
	) , array(
		'==',
		'==}'
	) , $sText);
}

function durDebugVar($mVar, $sPref = '  ')
{
	$Ret = '';
	foreach($mVar as $sKey => $sVal) {
		$Ret.= "{$sPref}{$sKey} => ";
		if (is_array($sVal)) {
			$Ret.= "ARRAY (\n" . durDebugVar($sVal, $sPref . '  ') . "{$sPref})\n";
		}
		else {
			$Ret.= "{$sVal}\n";
		}
	}

	return durDebugEscape($Ret);
}

function durLinkChanger($sContent)
{
	global $aURFlip, $aURLRewriter, $aURLRewriterOnly;
	if (DUR_SKIP_THIS) return $sContent;
	if (strlen($sContent) < 500) return $sContent;
	if (DUR_CACHE_REWRITED && file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-cache')) {

		// / Модуль кеширования контента - start

		$aDataStore = array();
		$icachedays = DUR_CACHE_TIME * 60 * 60 * 24;
		$sMD5Content = md5($sContent); // MD5 от контента составляет часть имени файла кэша; это значение нужно для поиска готового к выводу контента в кеше
		$sCacheFName = $_SERVER['DOCUMENT_ROOT'] . '/d-cache/' . $sMD5Content . '.html.cache'; // имя файла кеша текущего контента
		$sTimeFName = $_SERVER['DOCUMENT_ROOT'] . '/d-cache/time.cache'; // имя файла данных кеша
		$aStoredData = array();
		$aStoredData = unserialize(file_get_contents($sTimeFName)); // массив из файла данных кеша
		$timestamp = $aStoredData['d_scripts_time']; // время последнего изменения файлов d-seo и d-url-rewriter
		$tOverallLenght = $aStoredData['cache_weight']; // Занимаемое кешем место на диске
		$tLastClear = $aStoredData['last_clear_time'];
		$dtimestamp = filemtime($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php'); // время изменения d-url-rewriter

		// если есть файл d-seo, то берем его время изменения и записываем в переменную $dtimestamp максимум от времени последнего изменения скриптов

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php')) {
			$dTMPtimestamp = filemtime($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php');
			if ($dTMPtimestamp > $dtimestamp) $dtimestamp = $dTMPtimestamp;
		}

		// если время последнего изменения, записанное в файл данных кеша отличается от реального, чистим весь кеш.

		if ($timestamp != $dtimestamp || $tOverallLenght > DUR_CACHE_MEMORY || time() - $tLastClear > $icachedays) {
			if ($dh = @opendir($_SERVER['DOCUMENT_ROOT'] . '/d-cache/')) {
				while (($obj = readdir($dh)) !== false) {
					if ($obj == '.' || $obj == '..') continue;
					@unlink($_SERVER['DOCUMENT_ROOT'] . '/d-cache/' . $obj);
				}

				closedir($dh);
			}

			$aDataToStore['d_scripts_time'] = $dtimestamp; // записываем в массив время последнего изменения файлов d-seo и d-url-rewriter
			$aDataToStore['last_clear_time'] = $tLastClear = time();
			$aDataToStore['cache_weight'] = $tOverallLenght = 0;
			file_put_contents($sTimeFName, serialize($aDataToStore));
		}
	}

	// если есть соотв. файл в кеше, записываем его содержимое в $sContent

	if (isset($sCacheFName) && file_exists($sCacheFName)) {
		$sContent = file_get_contents($sCacheFName);
	}

	// / Модуль кеширования контента - break

	else {
		$iTimeStart = microtime(true);
		$sContent = durGZCheck($sContent);
		if (defined('DUR_CMS_TYPE') && (DUR_CMS_TYPE == 'JOOMLA') && isset($_SERVER['dur'])) {
			$aURLRewriter = $_SERVER['dur'][0];
			$aURFlip = $_SERVER['dur'][1];
			$aURLRewriterOnly = $_SERVER['dur'][2];
			unset($_SERVER['dur']);
		}

		$aURLRewriter = array_merge($aURLRewriter, $aURLRewriterOnly);

		// Base path

		if (preg_match('%<[^<>]*base[^<>]*href=[\'"]?([\w_\-\.\:/]+)[\'"\s>][^<>]*>%siU', $sContent, $aM)) {
			$sBase = $aM[1];
			$sBaseHref = $aM[1];
		}
		else {
			$sBase = 'https://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));
			$sBaseHref = '';
		}

		$sBase = trim(str_replace(array(
			'https://',
			'https://'
		) , '', $sBase) , '/');
		$aHosts = array(
			$_SERVER['HTTP_HOST']
		);
		if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
			$aHosts[] = substr($_SERVER['HTTP_HOST'], 4);
		}

		if (defined('DUR_SUBDOMAINS') && DUR_SUBDOMAINS) {
			$sExtHost = str_replace('www.www.', 'www.', 'www.' . DUR_SUBDOMAINS);
			$aHosts[] = $sExtHost;
			$aHosts[] = str_replace('www.', '', $sExtHost);
		}

		$aHosts = array_unique($aHosts);
		$sBase = str_replace($aHosts, '', $sBase);

		// href="?..."

		if (defined('DUR_LINK_PARAM') && defined('DUR_ORIG_RURI') && DUR_LINK_PARAM) {
			$sContent = preg_replace('%(href\s*=\s*[\'"]?)\s*([?#].*[#\'"\s>])%siU', '$1' . DUR_ORIG_RURI . '$2', $sContent);
		}

		if (defined('DUR_ANC_HREF') && DUR_ANC_HREF) {
			$sContent = preg_replace('%(href\s*=\s*["\']+)(#\w)%siU', '$1' . DUR_REQUEST_URI . '$2', $sContent);
		}

		if (defined('DUR_ROOT_HREF') && DUR_ROOT_HREF) {
			$sContent = preg_replace('%(href\s*=\s*["\']*)\./%siU', '$1https://' . $_SERVER['HTTP_HOST'] . $sBase , $sContent);
		}


		if (defined('DUR_FIX_HTTP_HOST') && DUR_FIX_HTTP_HOST) {
			$aHosts = array(
				DUR_FIX_HTTP_HOST
			);
			$sFalseHost = str_replace('www.www.', '', 'www.' . DUR_FIX_HTTP_HOST);
			$sContent = str_replace('https://' . $sFalseHost, 'https://' . DUR_FIX_HTTP_HOST, $sContent);
		}

		// Main cicle

		if (defined('DUR_MAIN_CYCLE')) {
			if (DUR_MAIN_CYCLE == 'str_replace' || DUR_MAIN_CYCLE == 'callback') {

				// /Нормализация ссылок, все ссылки на сайте должны прийти к виду href="https://HTTP_HOST/REQUEST_URI"   , т.е. в кавычках, без пробелов и с хостом
				// /Опасносте начинается здесь

				$sContent = preg_replace('#\shref\s*=[\s]?([A-Za-z0-9\?\/][^\s]*)(\s|/>|>)#siU', ' href="https://' . DUR_FIX_HTTP_HOST . '/$1"$2', $sContent);
				$sContent = preg_replace('#\shref\s*=[\s]?"([A-Za-z0-9\?\/][^"]*)"#siU', ' href="https://' . DUR_FIX_HTTP_HOST . '/$1"', $sContent);
				$sContent = preg_replace('#\shref\s*=[\s]?\'([A-Za-z0-9\?\/][^"]*)\'#siU', ' href="https://' . DUR_FIX_HTTP_HOST . '/$1"', $sContent); // подмена href в одинарных кавычках
				$sContent = str_replace(array(
					'https://' . DUR_FIX_HTTP_HOST . '/https://' . DUR_FIX_HTTP_HOST . '',
					'https://' . DUR_FIX_HTTP_HOST . '//',
					'https://' . DUR_FIX_HTTP_HOST . '/http',
					'https://' . DUR_FIX_HTTP_HOST . '/mailto:',
					'https://' . DUR_FIX_HTTP_HOST . '/skype:',
					'https://' . DUR_FIX_HTTP_HOST . '/tel:',
					'https://' . DUR_FIX_HTTP_HOST . '/javascript',
				) , array(
					'https://' . DUR_FIX_HTTP_HOST . '',
					'https://' . DUR_FIX_HTTP_HOST . '/',
					'http',
					'mailto:',
					'skype:',
					'tel:',
					'javascript',
				) , $sContent);

				// / - end

			}

			if (DUR_MAIN_CYCLE == 'str_replace') {
				foreach($aURLRewriter as $sFrom => $sTo) {
					if (strpos($sContent, 'href="https://' . DUR_FIX_HTTP_HOST . $sFrom . '"')) $sContent = str_replace('href="https://' . DUR_FIX_HTTP_HOST . $sFrom . '"', 'href="https://' . DUR_FIX_HTTP_HOST . $sTo . '"', $sContent);
				}
			}
			else
			if (DUR_MAIN_CYCLE == 'callback') {
				function durMainCycleCallback($href)
				{
					global $aURLRewriter;
					if (isset($aURLRewriter[$href[1]])) return 'href="https://' . DUR_FIX_HTTP_HOST . $aURLRewriter[$href[1]] . '"';
					else return $href[0];
				}

				$sContent = preg_replace_callback('#href="https://' . DUR_FIX_HTTP_HOST . '([^"]*)"#siU', 'durMainCycleCallback', $sContent);
				function durMainCycleCallback2($href)
				{ // обработка одинарных кавычек
					global $aURLRewriter;
					if (isset($aURLRewriter[$href[1]])) return 'href=\'https://' . DUR_FIX_HTTP_HOST . $aURLRewriter[$href[1]] . '\'';
					else return $href[0];
				}

				$sContent = preg_replace_callback('#href=\'https://' . DUR_FIX_HTTP_HOST . '([^\']*)\'#siU', 'durMainCycleCallback2', $sContent);
			}
			else
			if (DUR_MAIN_CYCLE == 'ortodox') {
				foreach($aHosts as $sHost) {
					foreach($aURLRewriter as $sFrom => $sTo) {
						$sContent = durReplaceLink($sHost, $sBase, $sFrom, $sTo, $sContent);
					}

					if (!defined("DUR_FIRST_TIC")) define("DUR_FIRST_TIC", true);
				}
			}
			else {
				$sContent.= "<!--Nothing to do here!-->";
			}
		}

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php')) {
			include_once ($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php');

		}

		if ((defined('DUR_BASE_ROOT') && DUR_BASE_ROOT) || !empty($sBaseHref)) {
			if (strlen(DUR_BASE_ROOT) > 7) {
				$sBaseHref = DUR_BASE_ROOT;
			}
			else {
				$sBaseHref = (empty($sBaseHref) ? 'https://' . $aHosts[0] : $sBaseHref) . '/';
			}

			$sBaseHref = trim($sBaseHref, '/') . '/';
			$sBaseHref = '<base href="' . $sBaseHref . '">';
			$sContent = preg_replace('%<base[^>]+href[^>]+>%siU', '', $sContent);
			$sContent = preg_replace('%(<head(>|\s.*>))%siU', "$1" . $sBaseHref, $sContent);
		}

		if (function_exists('durOtherReplacer')) {
			$sContent = durOtherReplacer($sContent);
		}
	}

	if (DUR_CACHE_REWRITED && file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-cache')) {

		// / Модуль кеширования контента - continue

		file_put_contents($sCacheFName, $sContent);

		//       $aDataToStore = $aStoredData;

		$aDataToStore['last_clear_time'] = $tLastClear;
		$aDataToStore['d_scripts_time'] = $dtimestamp;
		$aDataToStore['cache_weight'] = (int)$tOverallLenght + (int)strlen($sContent);
		file_put_contents($sTimeFName, serialize($aDataToStore));

		// / Модуль кеширования контента - end

	}

	if (defined('DUR_DEBUG') && DUR_DEBUG) {
		$sContent.= "\n<!--\n";
		if (defined('DUR_DEBUG_BEFORE') && DUR_DEBUG_BEFORE) {
			$sContent.= " ===== VARS BEFORE REWRITE =====\n\n" . DUR_DEBUG_BEFORE;
		}

		$sContent.= "===== VARS AFTER REWRITE =====\n\nSERVER:\n" . durDebugVar($_SERVER) . "\n\nGET:\n" . durDebugVar($_GET) . "\n\nREQUEST:\n" . durDebugVar($_REQUEST) . "\n";
		$sContent.= "\nCONSTANTS:\n" . '  DUR_REQUEST_URI     => ' . durDebugEscape(DUR_REQUEST_URI) . "\n" . '  DUR_HTTP_HOST       => ' . durDebugEscape(DUR_HTTP_HOST) . "\n" . '  DUR_FULL_URI        => ' . durDebugEscape(DUR_FULL_URI) . "\n" . '  DUR_ORIG_RURI       => ' . (defined('DUR_ORIG_RURI') ? durDebugEscape(DUR_ORIG_RURI) : 'NOT-SET') . "\n" . '  DUR_SEO_REQUEST_URI => ' . (defined('DUR_SEO_REQUEST_URI') ? durDebugEscape(DUR_SEO_REQUEST_URI) : 'NOT-SET') . "\n";
		$iTimeNow = microtime(true);
		$iTimeAll = ($iTimeNow - DUR_TIME_START) / 1000;
		$iTimeContent = ($iTimeStart - DUR_TIME_START) / 1000;
		$iTimeLinks = ($iTimeNow - $iTimeStart) / 1000;
		$sContent.= "\nTIME:\n" . '  ALL: ' . number_format($iTimeAll, 8) . " sec. (100%)\n" . '  CMS: ' . number_format($iTimeContent, 8) . ' sec. (' . number_format($iTimeContent / $iTimeAll * 100, 2) . "%)\n" . '  DUR: ' . number_format($iTimeLinks, 8) . ' sec. (' . number_format($iTimeLinks / $iTimeAll * 100, 2) . "%)\n";
		$sContent.= "\nD-Data:\n" . durDebugVar($aDataToStore);
		$sContent.= '-->';
	}

	$sContent = durOutputCompress($sContent);
	if (defined('DUR_FIX_CONTLEN') && DUR_FIX_CONTLEN) {
		header('Content-Length: ' . strlen($sContent));
	}

	return $sContent;
}

function outerlinks($matches)
{
	$sEq = false; //есть ли совпадения
	$res = $matches[0];
	$arMassNotNoindex = array(
		'%demis.ru%siU',
		'%demis-promo.ru%siU',
		'%\shref\s*=\s*[\'"]https?:\/\/[A-Za-z0-9-]*\.?' . DUR_FIX_HTTP_HOST . '%siU',
	);
	foreach($arMassNotNoindex as $item) {
		if (preg_match($item, $matches[0])) {
			$sEq = true;
			break;
		}
	}

	if (!$sEq) { // если совпадений не нашлось

		// $res = '<noindex>'.$matches[0].'</noindex>';
		// если отсутствует rel, то добавляем его

		if (!strpos($res, 'rel=')) {
			$res = str_replace('<a ', '<a rel=nofollow ', $res);
		}
	}

	return $res;
}

function durOtherReplacer($sContent)
{
	// How-to debug after ob-start() ?
	// $sContent .= "<!--\nDEBUG:\n".var_export($AnyVarialble, true)."\n-->\n";

	// закрываем исходящие ссылки в ноиндекс и нофоллоу
	// $sContent = preg_replace_callback('%<a[^>]*href=[\'"]?https?://.*</a>%siU','outerlinks',$sContent);
	// $sContent = str_replace(array('<b>','</b>','<u>','</u>',),array('<span style="font-weight: bold;">','</span>','<span style="text-decoration: underline;">','</span>',),$sContent);

	return $sContent;
}

/* Подключение в начале файла

// ЧПУ ---

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php')) {
include_once($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php');
durRun ();
}

// --- ЧПУ

/* Для поддоменов неплохо было прописывать

RewriteCond %{HTTP_HOST} ^www.(.{4,}.nickon.ru)$
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

RewriteCond %{HTTP_HOST} ^(.{4,}).nickon.ru$
RewriteRule ^robots\.txt$ robots-%1.txt [L]

*/
/* Подключение с единой точкой входа
RemoveHandler .html .htm
AddType application/x-httpd-php .php .htm .html .phtml
php_value auto_prepend_file "d-url-rewriter.php"
*/
