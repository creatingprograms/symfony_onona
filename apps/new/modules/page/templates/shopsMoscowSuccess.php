<?
$h1 = 'Адреса магазинов «Он и Она» в Москве и МО';
slot('h1', $h1);
slot('catalog-class', 'new-shops-moscow-page');
slot('breadcrumbs', [
  ['text' => $h1],
]);
?>

<div class="wrap-block">
  <div class="container">
    <div class="shops-page-metro alter v2">
      <div class="shops-page-metro-img small-text">
        <img src="/frontend/images_new/metro_1670318870.jpg" />
        <!-- <img src="/frontend/images_new/metro_1670314089.jpg" /> -->
        <!-- <img src="/frontend/images/metro/mos-metro.jpg" /> -->
        <a class="shops-page-metro-station -pos-1 msk-red" href="/shops/magazin-on-i-ona-v-g-moskva-leninskii-prospekt">Университет</a>
        <a class="shops-page-metro-station -pos-2 msk-green" href="/shops/leningradskoe-shosse">Речной вокзал</a>
        <a class="shops-page-metro-station -pos-3 msk-orange" href="/shops/ulitsa_letchika_babushkina">Бабушкинская</a>
        <a class="shops-page-metro-station -pos-4 msk-red" href="/shops/shchelkovskoe_shosse">Черкизовская</a>
        <a class="shops-page-metro-station -pos-6 msk-salad" href="/shops/ulitsa_sovetskoy_armii">Марьина роща</a>
        <a class="shops-page-metro-station -pos-7 msk-grey-clear alter" href="/shops/aminevskoe_shosse">Давыдково</a>
        <a class="shops-page-metro-station -pos-8 msk-pink" href="/shops/kutuzovskii-prospekt">Кутузовский проспект</a>
        <a class="shops-page-metro-station -pos-9 msk-red <?/*alter*/ ?>" href="/shops/frunzenskaya_naberegnaya">Парк культуры</a>
        <a class="shops-page-metro-station -pos-10 msk-grey" href="/shops/ulitsa_bolshaya_tulskaya">Тульская</a>
        <a class="shops-page-metro-station -pos-11 msk-violet alter" href="/shops/trehgorny_val">Улица 1905 года</a>
        <a class="shops-page-metro-station -pos-12 msk-grey-clear" href="/shops/magazin-on-i-ona-v-g-moskva-varshavskoe-shosse">Варшавская</a>
        <a class="shops-page-metro-station -pos-13 msk-blue" href="/shops/ulica-yarcevskaya">Молодежная</a>
        <a class="shops-page-metro-station -pos-15 msk-light-blue <?/*alter*/ ?>" href="/shops/kutuzovskii-prospekt_26">Кутузовская</a>
        <a class="shops-page-metro-station -pos-16 msk-brown alter" href="/shops/magazin-on-i-ona-v-g-moskva-ploschad-kievskogo-vokzala">Киевская</a>
        <a class="shops-page-metro-station -pos-17 msk-violet" href="/shops/metro-begovaya">Беговая</a>
        <a class="shops-page-metro-station -pos-19 msk-salad alter" href="/shops/ulitsa_simonovskiy_val">Дубровка</a>
        <a class="shops-page-metro-station -pos-20 msk-red" href="/shops/onona_metro_krasnoselskaya">Красносельская</a>
        <a class="shops-page-metro-station -pos-21 msk-blue" href="/shops/magazin-on-i-ona-v-g-moskva-ulica-pervomaiskaya-d-44-20">Первомайская</a>
        <a class="shops-page-metro-station -pos-22 msk-brown" href="/shops/ulitsa-suschevskaya">Новослободская</a>
        <a class="shops-page-metro-station -pos-24 msk-yellow" href="/shops/michurinskiy_prospekt">Ломоносовский проспект</a>
        <?/*<a class="shops-page-metro-station -pos-25 msk-red" href="/shops/sadovo-chernogryazskaya">Красные ворота</a>*/ ?>
        <a class="shops-page-metro-station -pos-26 msk-red" href="/shops/magazin-on-i-ona-v-g-moskva-p-sosenskoe-d-sosenki">Ольховая</a>
        <a class="shops-page-metro-station -pos-27 msk-blue" href="/shops/pyatnitskoe_shosse">Волоколамская</a>
        <a class="shops-page-metro-station -pos-28 msk-grey alter" href="/shops/otradnoe">Отрадное</a>
      </div>
    </div>
    <? include_component("page", "shopsList", array(
      'sf_cache_key' => '$shopsImagesMoscowSliderMod',
      'city_id' => [3, 5, 126, 144, 59],/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
      'page' => 0,
      'type' => 'mobile_mod',
      'is_krasnodar' => false,
    ));
    ?>
    <? include_component("page", "shopsList", array(
      'sf_cache_key' => '$shopsImagesMoscowSlider',
      'city_id' => [3, 5, 126, 144, 59],/*Москва, Химки, Балашиха, Солнечногорск, Лобня*/
      // 'limit' => 9,
      'page' => 0,
      'type' => 'images',
      'is_krasnodar' => false,
    ));
    ?>


  </div>
</div>