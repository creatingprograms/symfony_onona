
  // function map() {
  //   if($('#map').length) {
  //     var lat = $('#map').data('lat');
  //     var lng = $('#map').data('lon');
  //     // console.log(lat+' '+lng);
  //     var internalScript = document.createElement('script');
  //     internalScript.src = "https://api-maps.yandex.ru/2.1.75/?lang=ru_RU";
  //     document.getElementsByTagName('head')[0].appendChild(internalScript);
  //     internalScript.onload = function() {
  //       var mainCoord = [
  //         [
  //           {
  //             lat: lat,
  //             lng: lng
  //           }
  //         ]
  //       ]
  //       function ymapShow(selector){
  //         var init = function() {
  //           var myMap;
  //           var yaMap = function() {
  //             myMap = new ymaps.Map(selector, {
  //               center: [lat, lng],
  //               //controls: [],
  //               zoom: 15,
  //             });
  //             var placemarkOptions;
  //             if (window.screen.width<768) placemarkOptions={
  //                balloonContent: '<p style="text-align: center;"><a href="yandexnavi://build_route_on_map?lat_to='+lat+'&lon_to='+lng+'" target="_blank">Ехать с Яндекс.Навигатор</a></p>',
  //             };
  //             var myPlacemark = new ymaps.Placemark([lat, lng],
  //               placemarkOptions,
  //               {
  //                 iconLayout: 'default#image',
  //                 iconImageHref: '/frontend/images/favicon.ico',
  //                 iconImageSize: [32, 32],
  //                 iconImageOffset: [-15, -30]
  //               });
  //             myMap.geoObjects.add(myPlacemark);
  //           }
  //           yaMap();
  //         };
  //         ymaps.ready(init);
  //       }
  //       ymapShow('map');
  //     };
  //   }
  // }