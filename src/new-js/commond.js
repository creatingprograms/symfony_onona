var isDebug=false;
if (window.location.origin.indexOf('://dev.') + 1) isDebug=true;
var needToDeliveryCalc = false;//Поскольку расчет доставки операция затратная - будем сначала проверять нужна ли она
var shopsDeliveryMap=false;
var deliveryPointSelected = false;
var mapWidget=false;
var myMap=false;
var maxCount;
var suggestionForTaxi=false;
var slShops=false;
var galleryThumbs = false;
var galleryTop = false;
var russianPostId = 36969;
var russianPostSelected = false;
if (!isDebug) russianPostId = 37243;
var viaSelected = false;
var proofInterval = false;
// var ozonWindow;
// logDebug('zzz');
var sl;//ползунок бонуса глобальный, т.к. нужен в других процедурах

function requestProof($form, type = '') {
  $form.find('.js-error-msg').remove();
  $.ajax({
    'url': '/sms_proof',
    'type': 'post',
    'data': {
      'type': 'send',
      'phone': $form.find('.js-phone-mask').val(),
      'call': type,
      'token': 'sms_proof'
    },
    'success': function (resp) {
      if ('error' in resp) {
        $form.find('.js-proof-result').prepend($('<div class="red" style="color: red;">' + resp.error + '<div>'));
        logDebug(resp.error)
      }
      else {
        $form.find('.js-proof-result').html(resp.success);
        $form.find('[name="create_proof"]').focus();
        proofInterval = setInterval(function (){
          var timerContainer = $form.find('.js-proof-timer');
          value = parseInt(timerContainer.text());
          value -= 1;
          timerContainer.text(value);
          if(value == 0) {
            clearInterval(proofInterval);
            $('.js-proof-send-again').removeClass('disabled');
            $('.js-timer-container').addClass('mfp-hide');
          }


        }, 1000)

      }
    }
  });
}

function callbackRussianPost($data){
  logDebug('---------------------------callbackRussianPost');
  logDebug($data);
  $('.js-post-wrapper').addClass('mfp-hide');
  russianPostSelected = $data;
  needToDeliveryCalc = 'one more time';//Нужен повторный расчет
  calcDelivery();

}

var startPos;

var geoOptions = {
    enableHighAccuracy: false,
    timeout: 10 * 1000,
    // maximumAge: 30 * 60 * 1000,
}

function geoSuccess (position) {
  logDebug('geoSuccess fired. Position is')
  logDebug(position)
  // startPos = position;
  var url="/near-shops?lat="+position.coords.latitude+'&lon='+position.coords.longitude;//5578
  // logDebug('url is ' + url);
  window.location.href=url;
}

function geoError(error){
  logDebug('Error occurred. Error code: ' + error.code);
  // logDebug(error)
  if(error.code==1){
    alert("Для определения лучшего определения магазинов рядом разрешите сайту доступ к вашему местоположению")
    return;
  }
  if(error.code==2){
    alert("Не получается определить местоположение. Попробуйте позднее")
    return;
  }
  if(error.code==3){
    alert("Не получается определить местоположение. Попробуйте позднее")
    return;
  }
    // error.code can be:
    //   0: unknown error
    //   1: permission denied
    //   2: position unavailable (error response from location provider)
    //   3: timed out
  // logDebug(position)
}

window.addEventListener("message", receiveMessage, false);

function parseVIAMessage(dataJSON){
  if (!$("#via_id").length) return; //Находимся не в корзине
  let data;
  try {
    data = JSON.parse(dataJSON);
  } catch (error) {
    logDebug(error)
    return;
  }
  viaSelected = data;
  needToDeliveryCalc = 'one more time';//Нужен повторный расчет
  var addr = data.description + ' ' + data.full_address;
  $("#via_id").val(data.id);
  $("#via_address").val(addr);
  $('#address-21').text(addr);
  // console.log(data)
  calcDelivery();
}

function updateVIAMap(){
  logDebug('update VIAMap fired')
  const key = $('#basketForJs').data('via-shop');
  const map = document.getElementById("map-via");
  if(undefined == key || map == undefined || !map) return;

  const orderCost = parseInt($('.js-items-in-cart').data('price-for-delivery'));
  const orderWeight = getOrderWeigth();

  let lat = $("#lat").val();
  if (undefined == lat || lat == '' || lat == '0') lat = "55.755";
  let lon = $("#lon").val();
  if (undefined == lon || lon == '' || lon == '0') lon = "37.6173";

  let strCoords = "&lat=" + lat + "&lng=" + lon;

  const city = $('#city').val()
  const street = $('#street').val();

  let strAddr = '';
  if (city != '' && city != undefined) {
    strAddr = '&address=' + city;
    strCoords = '';
    if (street != '' && street != undefined) strAddr += ', ' + street;
  }

  let src = "https://widget.viadelivery.pro/via.maps/?dealerId=" + key +
    strCoords +
    strAddr +
    "&zoom=10&action=true&lang=ru" +
    "&orderCost=" +
    orderCost +
    "&orderWeight=" +
    orderWeight;

  map.src = src;

}

function receiveMessage(event) {//Озон, VIA
  // Важно не слушать чужие события
  // logDebug('---------------------------------------------------------- receiveMessage v2')
  // logDebug(event)
  if (event.origin == "https://widget.viadelivery.pro"){
    parseVIAMessage(event.data);
    return;
  }
  if (event.origin !== "https://rocket.ozon.ru")
    return;
  // logDebug(event.data)
  var data=JSON.parse(event.data)
  // logDebug(data.id)
  $('#ozon_id').val(data.id);
  $('#ozon_address').val(data.address);

  needToDeliveryCalc = false;
  calcDelivery();
}

function logDebug(data){
  if(isDebug) console.log(data);
}
function logToFile(name, data){
  console.log(data);
  $.ajax({
    type: 'post',
    data: {'data' : data},
    url: '/log2file/' + name,
    success: function (resp) {
      logDebug(resp);
    }
  });
}

var sdek = {
  data: false,
  init: false,
  onReady : function (){
    logDebug('sdek.ready');
    initMapWidget()
  },
  onChoose : function (data){
    logDebug('sdek.onChoose')
    // logDebug(data)
    var addr = data.cityName + ', ' + data.PVZ.Address;
    // logDebug(addr)

    $("#sdek_tariff").val(data.tarif);
    $("#sdek_city_id").val(data.city);
    $("#sdek_id").val(data.id);
    $("#sdek_address").val(data.cityName + ', ' + data.PVZ.Address);
    $('#address-19').text(addr);
    $('.js-sdek-wrapper').addClass('mfp-hide');
    sdek.data = data;
    logDebug('this.data')
    logDebug(sdek.data)
    needToDeliveryCalc = 'one more time sdek';//Нужен повторный расчет
    calcDelivery();
  },
  onCalculate : function (){
    logDebug('sdek.onCalculate')
  },
}

function initSdekWidget(){
  if (!$('.-delivery .sdek-map').length) return;
  logDebug('sdek map init')
  var widjet = new ISDEKWidjet({
    hideMessages: false,
    defaultCity: 'Москва',
    cityFrom: 'Москва',
    choose: false,
    // path: '/frontend/js/plugins/sdek/',
    link: 'map-sdek',
    hidedress: true,
    bymapcoord: false,
    hidecash: false,
    hidedelt: false,
    detailAddress: false,
    goods: [{
      length: 10,
      width: 10,
      height: 10,
      weight: 1
    }],
    onReady: sdek.onReady,
    // onChoose: sdek.onChoose,
    // onChooseProfile: sdek.onChooseProfile,
    // onChooseAddress: sdek.onChooseAddress,
    // onCalculate: sdek.onCalculate
  });
}

function initSdekWidgetChekout(){
  if (!$('#sdek-widget').length) return;
  // logDebug('sdek map checkout init')
  // logDebug('getOrderWeigth()' + getOrderWeigth())
  var city = 'Москва';
  if ($('#city').val()) city = $('#city').val();
  if(sdek.init) return;
  var widjet = new ISDEKWidjet({
    hideMessages: false,
    defaultCity: city,
    cityFrom: 'Москва',
    choose: true,
    // path: '/frontend/js/plugins/sdek/',
    link: 'sdek-widget',
    hidedress: true,
    bymapcoord: false,
    hidecash: false,
    hidedelt: true,
    detailAddress: false,
    goods: [{
      length: 30,
      width: 10,
      height: 10,
      weight: getOrderWeigth()
    }],
    onReady: sdek.onReady,
    onChoose: sdek.onChoose,
    // onChooseProfile: sdek.onChooseProfile,
    // onChooseAddress: sdek.onChooseAddress,
    // onCalculate: sdek.onCalculate
  });
  sdek.init = true;
}

function initMapWidget(){
  if(!$('.-delivery .dlh-map').length) return ;

  var mapWidget = new MapWidget(151, "map", function() {
    alert('Точная стоимость доставки будет рассчитана в корзине')
  }, {
    'size': 'xs'
  });
  // запустить инициализацию виджета
  mapWidget.init();
}

function initMap(){//Карта на странице доставки
  if(!$('#YMapsID').length) return;
  ymaps.ready(showByCity);
  $('#cities-list').on('change',  showByCity );

}

function showByCity( ){
  logDebug('showByCity');
  var $cityId=$('#cities-list').val();
  if (!myMap) myMap = new ymaps.Map('YMapsID', { center: [55.75222, 37.61556], zoom: 9 , controls:[]});
  $.ajax({
    type: 'post',
    url: '/get_delivery_points/'+$cityId,
    success: function (resp){
      myMap.geoObjects.removeAll();


      for(var i=0; i<resp.points.length; i++){
        myMap.geoObjects.add(
            new ymaps.Placemark(
              [resp.points[i].lat, resp.points[i].lon],
              { balloonContent: '<h3>'+resp.points[i].name+'</h3>'+
              '<div class="address">'+
              (resp.points[i].metro ? '<p>'+resp.points[i].metro +'</p>' : '')+
              '<p>'+resp.points[i].address+'</p>'+
              (resp.points[i].pay ? '<p><b>Способы оплаты:</b> '+resp.points[i].pay +'</p>' : '')+
              '<p><b>Время работы: </b><br />'+resp.points[i].worktime+'<br /></p>'+
              '</div>'
            },
            {
              iconLayout: 'default#image',
              iconImageHref: resp.points[i].image,
              iconImageSize: resp.points[i].imagesize,
              iconImageOffset: [-3, -42]
            }
          )
        );
      }
      i--;
      myMap.setCenter([resp.points[i].lat, resp.points[i].lon], $cityId==3 ? 9 : 12, {
        checkZoomRange: true
      });

    }
  });
}

function customAlert(message){
  $.magnificPopup.open({
    items:{
      src: '<div class="white-popup-block block-popup">'+message+'</div>',
    },
    type: 'inline',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    fixedContentPos: true,
    closeMarkup: '<div class="mfp-close">×</div>'
  });
}

function btnScrollTop() {
  $('<style>'+
   '.scrollTop{ display:none; z-index:9999; position:fixed;'+
   'bottom:55px; left:25px; right: auto; width:60px; height:60px;'+
   'background:url(/images/up.svg) 0 0 no-repeat; background-size: contain;}' +
   '</style>').appendTo('body');
  var
    speed = 550,
    $scrollTop = $('<a href="#" class="scrollTop">').appendTo('body');
  $scrollTop.click(function(e){
    e.preventDefault();
    $( 'html:not(:animated),body:not(:animated)' ).animate({ scrollTop: 0}, speed );
  });
  //появление
  function show_scrollTop(){
    ( $(window).scrollTop() > 330 ) ? $scrollTop.fadeIn(700) : $scrollTop.fadeOut(700);
  }
  $(window).scroll( function(){ show_scrollTop(); } );
  show_scrollTop();
}

function initDadata(){
  if ($('.js-dadata').length){
    var token = "ed8946dc9a194c7c4ac7c0b2b81eb6cc78e70e8d";
    var $city   = $("#city");
    var $street = $("#street");
    var $house  = $("#house");

    // город и населенный пункт
    $city.suggestions({
      token: token,
      type: "ADDRESS",
      hint: false,
      bounds: "city-settlement",
      onSelect: function(suggestion) {
        logDebug('-------------------------------------------city select')
        // logDebug('suggestion.data|' + suggestion.data);
        logDebug(suggestion.data.city_kladr_id);
        if ($('#shiptor_widget_pvz').length){
          window.JCShiptorWidgetPvz.setParams({ location: { kladr_id: suggestion.data.city_kladr_id } });
          window.JCShiptorWidgetPvz.refresh()
        }

        $('#region-fias-id').val(suggestion.data.region_fias_id);
        updateVIAMap();
        calcDelivery();
      }
    });

    // улица
    $street.suggestions({
      token: token,
      type: "ADDRESS",
      hint: false,
      bounds: "street",
      constraints: $city
    });

    // дом
    $house.suggestions({
      token: token,
      type: "ADDRESS",
      hint: false,
      noSuggestionsHint: false,
      bounds: "house",
      constraints: $street,
      onSelect: function(suggestion) {
        suggestionForTaxi=suggestion;

        calcDelivery();
      }
    });

  }
}

function initEvents(){
  var wheelInterval = false;
  if ($('.wheel-shutter-mod').length){
    var nextShow = parseInt($('.wheel-shutter-mod').data('nextshow'));
    wheelInterval = setTimeout(function(){
      $('.wheel-wrapper-mod').addClass('active');
      // $(".wheel-shutter-mod").toggleClass('mfp-hide');
    }, nextShow * 1000);
    if(nextShow <= 20) $(document).on('mouseleave', function (e){
      if (e.clientY > 10) return;
      $('.wheel-wrapper-mod').addClass('active');
      if (wheelInterval) clearInterval(wheelInterval);
      $(document).off('mouseleave');
    })
  }
  if ($("#shiptor_widget_pvz").length) $("#shiptor_widget_pvz").on("onPvzSelect", function (ce) {
    logDebug(ce.detail);
  });
  $('.js-target-to').on('click', function(e){
    e.preventDefault();
    $('html,body').animate({ scrollTop: $($(this).data('target')).offset().top - 40 });
  });
  $(document).on('click', '.js-proof-send-again', function(e){
    e.preventDefault();
    if($(this).hasClass('disabled')) return;
    requestProof($(this).closest('form'));
  });
  $(document).on('click', '.js-proof-send', function(e){
    e.preventDefault();
    var $form = $(this).closest('form');
    $form.find('[name="create_proof"]').removeClass('hasError');
    $form.find('.js-error-msg').remove();
    $.ajax({
      'url': '/sms_proof',
      'type': 'post',
      'data': {
        'type': 'check',
        'phone': $form.find('.js-phone-mask').val(),
        'token': 'sms_proof',
        'code': $form.find('[name="create_proof"]').val()
      },
      'success': function (resp){
        if ('error' in resp) {
          $form.find('[name="create_proof"]').addClass('hasError').val('').focus();
          if ('error' in resp) {
            $form.find('.js-proof-result').prepend($('<div class="js-error-msg" style="color: red;">' + resp.error + '<div>'));
            logDebug(resp.error)
          }
        }
        else{
          $form.find('.js-proof-container').remove();
          if ($form.attr('id') != 'basketForm')
            $form.find('.js-submit-button').removeClass('mfp-hide').click();
          else{
            if ($('.js-payment:checked').val() == 60) $('.js-tinkoff').removeClass('mfp-hide');
            else $('.js-no-tinkoff').removeClass('mfp-hide');
          }
        }
      }
    })
    
  });
  $(document).on('click', '.js-save-click', function(e){
    let id = $(this).data('id');
    if(!!!id) return;
    var href = $(this).attr('href');
    e.preventDefault();
    // logDebug('.js-save-click click fired. id is ' + id)
    $.ajax({
      url: '/bannerclick/'+id,
      success: function(res){
        // logDebug(res)
        if(!!href) window.location.href = href;
      }
    });
  });
  $(document).on('click', '.js-save-click-banner', function(e){
    let id = $(this).data('id');
    if(!!!id) return;
    var href = $(this).attr('href');
    e.preventDefault();
    $.ajax({
      url: '/bannerclick-little/'+id,
      success: function(res){
        // logDebug(res)
        if(!!href) window.location.href = href;
      }
    });
  });
  $('.js-description').on('click', function(e){
    e.preventDefault();
    customAlert($(this).data('description'));
  });
  $('.js-review-select').on('change', function(e){
    e.preventDefault();
    $(this).closest('form').submit();
  });

  $('.js-hover-change').on('click', function (e){//проход мыши над слайдом
    galleryTop.enable();
    galleryTop.slideTo($(this).data('index'));
    galleryTop.disable();
  });
  $('.js-hover-change').on('mouseenter', function (e){//проход мыши над слайдом
    if(galleryTop) {
      galleryTop.disable();
    }
  });
  $('.js-hover-change').on('mouseleave', function (e){//проход мыши над слайдом
    if(galleryTop) {
      galleryTop.enable();
    }
  })

  $('.js-form-open').on('click', function (e){//Открыть форму
    e.preventDefault();
    var $this=$(this);
    var formId=$this.attr('href');
    if(formId=='#popup-notify'){
      $(formId).find('input[name="product-id"]').val($this.data('id'));
      $(formId).find('.block-popup__descr').text($this.data('name'));
    }
    logDebug('js-form-open click');
    logDebug(formId)
    $.magnificPopup.open({
      items:{
        src: formId,
      },
      type: 'inline',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      fixedContentPos: true,
      closeMarkup: '<div class="mfp-close">X</div>'
    });
  });

  $('.js-fav-remove').on('click', function (){//Удалить из избранное
    logDebug('fav-remove click');
    var url = '/remove_from_favorite/'+$(this).data('id');
    $.ajax({
      url: url,
      // data: data,
      type: 'post',
      dataType: 'json',
      success: function(resp){
        if(resp.success){
          location.href=location.href;
        }
        else
          customAlert(resp.message);
      }
    });
  });

  $('.js-add-choosen').on('click', function (){//Добавить в избранное
    logDebug('js-add-choosen click');
    var $this=$(this);
    var data='token=Роботы нам тут не нужны!';
    var url = '/add_to_favorite/'+$(this).data('id');
    $.ajax({
      url: url,
      data: data,
      type: 'post',
      dataType: 'json',
      success: function(resp){
        if(resp.success){
          if(resp.direction=='in') {
            $this.addClass('active');
            $this.find('span').text("Удалить из избранного");
          }
          else {
            $this.removeClass('active');
            $this.find('span').text("Добавить в избранное");
          }
        }
        customAlert(resp.message);

        logDebug(resp);
      }
    });
  });

  $('.js-show-stocks').on('click', function (){
    $('html,body').animate({scrollTop: $('.shop_stocks').offset().top-40});
    $('.shop_stocks').click();
  });

  $('.js-search-toggle').on('click', function (){
    if($(this).data('action')=='open')
      $(this).closest('header').addClass('search-open');
    else
      $(this).closest('header').removeClass('search-open');
  });

  maxCount=parseInt($('body').data('max-cart-count'));

  if($('.wheel-wrapper').length){//Если будет колесо фортуны
    setTimeout(function(){
      $('.wheel-shutter').addClass('small');
    }, 1500);

    $('.js-wheel-counter').on('click', function(){
      logDebug('send wheel open');
      if(!isDebug){
        try {
          ym(144683, 'reachGoal', 'fortuna-otkryt')
        } catch (error) {
          console.log(error)
        }
      }
    });
    $('.js-wheel-close').on('click', function(){
      $('.wheel-wrapper').toggleClass('active');
      if (wheelInterval) clearInterval(wheelInterval);
      if ($(this).hasClass('close')) sendWheelTimeout();
      $(document).off('mouseleave');
    });
    $('.js-hide-wheel').on('focus', function(){
      $('.wheel-container').addClass('hide');
      if (wheelInterval) clearInterval(wheelInterval);
      $(document).off('mouseleave');
    });
    $('.js-hide-wheel').on('blur', function(){
      $('.wheel-container').removeClass('hide');
      if (wheelInterval) clearInterval(wheelInterval);
      $(document).off('mouseleave');
    });
  }

  $('.js-geo-find').on('click', function(e){//Клик на геометке
    e.preventDefault();
    if (!navigator.geolocation) {
      alert('Определение местоположения не поддерживается вашим браузером');
      $(this).off('click');
      return;
    }
    logDebug('Geolocation is supported!');
    navigator.geolocation.watchPosition(geoSuccess, geoError);
  });

  $('.js-post-postamat').on('click', function(e){//Клик на включателе карты почтоматов почты россии
    logDebug('js-post-postamat click fired');
    $('.js-post-wrapper').removeClass('mfp-hide');
    ecomStartWidget({
      id: russianPostId, //Потом заменить на бой ?>,
      callbackFunction: callbackRussianPost,
      containerId: 'ecom-widget'
    });

  });
  $('.js-sdek').on('click', function(e){//Клик на включателе карты почтоматов почты россии
    $('.js-sdek-wrapper').removeClass('mfp-hide');
    logDebug('.js-sdek click fired')
    initSdekWidgetChekout();
  });

  $('.js-ozon-init').on('click', function(e){//Клик на включателе карты озона
    logDebug('js-ozon click fired');
    ozonWindow = $.magnificPopup.open({
      items:{
        src: '<div class="white-popup-block block-popup popup-ozon"><h2 class="block-popup__title">Выберите пункт доставки</h2><iframe title="Ozon widget" style="width: 100%; height: 100%; min-width: 320px; min-height: 350px; border: none; overflow: hidden" src="https://rocket.ozon.ru/lk/widget?token=fFjODfqOCVV0I50CVFTcqA%3D%3D">Браузер не поддерживает iframe</iframe></div>',
      },
      type: 'inline',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      fixedContentPos: true,
      closeMarkup: '<div class="mfp-close">&nbsp;</div>'
    });
  });

  $('.js-sets-switch').on('change', function(e){//Клик на переключателе наборов
    logDebug('js-sets-switch click ');
    var $this=$(this);
    $this.closest('form').submit()
  });

  if ($('.js-instant-redirect').length) window.location.href=$('.js-instant-redirect').attr('href');//Если мы на странице "Спасибо за заказ" - сразу переходим к оплате

  $('#cities-list-menu .ui-menu-item-wrapper').on('click', function(){
    logDebug('ui-menu-item-wrapper click');
  });

  $('.js-phone-mask').mask('+7(999)999-99-99');

  $('.js-tests-sort').on('change', function(e){//Клик на сортировке
    e.preventDefault();
    var direction='desc';
    if ($(this).data('direction')) direction=$(this).data('direction');
    if($(this).is(':checked')) document.location = $(this).closest('form').attr('action')+'?sortOrder='+$(this).data('sort')+'&direction='+direction;
  });

  $('.js-tests-sort-cat').on('change', function(e){//Клик на сортировке каталога
    e.preventDefault();
    var direction='desc';
    if ($(this).data('direction')) direction=$(this).data('direction');
    if($(this).is(':checked')) { //Добставляем данные в форму
      var $form=$('.js-filters-form');
      $form.find('#form-filter-direction').val(direction);
      $form.find('#form-filter-page').val(1);
      $form.find('#form-filter-sort').val($(this).data('sort'));
      $form.submit();
    }
  });

  var timeoutId=false;//Фильтр будем запустить после секунды-двух

  $('.js-filter-shutter').on('change', function(e){
    // logDebug('js-filter-shutter change');
    if(timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(submitFilter, 2000);
    // logDebug('set timeout to 2s');
  });

  $('.submit-js').on('click', function(e){//Показ формы поиска на мобилке
    $(this).next().toggleClass('active');
  })

  $('.js-form-clear').on('click', function(e){//Очистка формы
    e.preventDefault();
    location.href=$(this).closest('form').attr('action');
  });

  $('.js-pagination a').on('click', function(e){//Пагинация с формами
    logDebug('.js-pagination a click fired');
    e.preventDefault();
    e.stopPropagation();
    var $form=$('.js-pagination-form');
    var $this=$(this);

    if($this.hasClass('-noActive') || $this.hasClass('active') || !$this.data('page')){//если мы уже на нужной странице или страница ссылка неактивна
      return false;
    }
    var page=$this.data('page');
    // $form.attr('action', window.location.pathname+'?page='+page);
    $form.find('input[name="page"]').val(page); //Указываем нужную страницу и отправляем форму
    $form.submit();
  });

  $('.js-pag-catalog a').on('click', function(e){//Пагинация в каталоге
    e.preventDefault();
    e.stopPropagation();
    var $form=$('.js-filters-form');
    var $this=$(this);

    if($this.hasClass('-noActive') || $this.hasClass('active') || !$this.data('page')){//если мы уже на нужной странице или страница ссылка неактивна
      return false;
    }
    var page=$this.data('page');
    $form.attr('action', window.location.pathname+'?page='+page);
    $form.find('#form-filter-page').val(page); //Указываем нужную страницу и отправляем форму
    $form.submit();
  });

  $('.js-change-view').on('click', function (e){
    // e.preventDefault()
    $(this).closest('form').submit();
    logDebug('js-change-view click');
  })

  if(!$('.btn-login.js-popup-form').length){//Удаляет и подменяет ссылки на регистрацию/авторизацию, если пользователь авторизован
    logDebug('user autorized');
    $('a[href="#popup-reg"]').remove()
    $('a[href="#popup-login"]').removeClass('js-popup-form');
    $('a[href="#popup-login"]').off();
    $('a[href="#popup-login"]').attr('href', '/lk');
  }

  $('.js-need-all').on('change', function(e){//Разрешает отправку при отсутствии аналогичных элементов (нужно отметить все)
    logDebug('.js-need-all fired');
    $(this).closest('.js-question-wrapper').find('.js-need-all').each(
      function(index, el){
        $(el).removeClass('js-need-all')
    });
    if(!$('.js-need-all').length)
      $('.js-submit-button').removeClass('disabled');
  });

  $('.js-rr-send-delay').on('click', function(){//Отправляем в RR
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email = $(this).closest('form').find('.js-rr-send-email').val();
    console.log('try to send RR delay email is '+ email)
    if (regex.test(email)) {
        try {
            var $name=$(this).closest('form').find('.js-rr-send-name');
            var name=false;
            if($name.length) {
              name=$name.val();
            }
            var object={};
            var $gender=$(this).closest('form').find('.js-rr-send-gender:checked');
            var gender=false;
            if($gender.length) {
              gender=$gender.val();
            }
            var $date=$(this).closest('form').find('.js-rr-send-day');
            var date=false;
            var diff=false;
            if($date.length) {
              date= new Date(
                $(this).closest('form').find('.js-rr-send-year').val(),
                $(this).closest('form').find('.js-rr-send-month').val()-1,
                $date.val()
              );
              var now= new Date();
              diff= Math.floor((now - date)/1000/365/60/60/24);
              if(diff<100) {
                object.age=diff;
                object.birthday=$date.val()+'.'+$(this).closest('form').find('.js-rr-send-month').val()+'.'+$(this).closest('form').find('.js-rr-send-year').val();
              }
            }
            if(gender=='m') gender='Male';
            if(gender=='g') gender='Female';

            if(gender && gender != ''){
              object.gender=gender
            }
            if(name && name != ''){
              object.name=name
            }
            rrApi.setEmail(email, object);
            sendRRWelcome(email);
        } catch (e) {
        }
    }
  });

  $('.js-rr-send').on('blur', function(){//Отправляем в RR
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    console.log('try to send RR')
    if (regex.test(this.value)) {
        try {
            var $name=$(this).closest('form').find('.js-rr-send-name');
            var name=false;
            if($name.length) {
              name=$name.val();
            }
            var object={};
            var $gender=$(this).closest('form').find('.js-rr-send-gender:checked');
            var gender=false;
            if($gender.length) {
              gender=$gender.val();
            }
            var $date=$(this).closest('form').find('.js-rr-send-day');
            var date=false;
            var diff=false;
            if($date.length) {
              date= new Date(
                $(this).closest('form').find('.js-rr-send-year').val(),
                $(this).closest('form').find('.js-rr-send-month').val()-1,
                $date.val()
              );
              var now= new Date();
              diff= Math.floor((now - date)/1000/365/60/60/24);
              if(diff<100) {
                object.age=diff;
                object.birthday=$date.val()+'.'+$(this).closest('form').find('.js-rr-send-month').val()+'.'+$(this).closest('form').find('.js-rr-send-year').val();
              }
            }
            if(gender=='m') gender='Male';
            if(gender=='g') gender='Female';

            if(gender && gender != ''){
              object.gender=gender
            }
            if(name && name != ''){
              object.name=name
            }
            rrApi.setEmail(this.value, object);
            sendRRWelcome(this.value);
        } catch (e) {
        }
    }
  });

  $(document).on('change', '.js-popup-basket-input', function (e){//Изменение в корзине из всплывахи
    // logDebug('input change fired');
    let newCount=parseInt($(this).val());
    if(maxCount && newCount>maxCount) {
      newCount=maxCount;
      $(this).val(newCount);
    }

    $.ajax({
      url: "/cart/addtocartcount/"+$(this).data('id'),
      data: {count: newCount},
      success: function (resp){
        logDebug(resp)
        showTopCart();

      }
    })
  });

  $(document).on('click', '.js-popup-basket-button', function (e){//Изменение в корзине из всплывахи
    // logDebug('.js-popup-basket-button click fired')
    var $input=$(this).closest('.field-number').find('.js-popup-basket-input');

    let newCount=parseInt($input.val())+parseInt($(this).data('dir'));
    if(maxCount && newCount>maxCount) newCount=maxCount;

    if(newCount > 0){
      $input.val(newCount);
      $input.change();
    }
  });

  $(document).on('click', '.js-basket-add', function (e){//Добавление в корзину
    // logDebug('line 172 .js-basket-add click. need to check it and uncomment');
    e.preventDefault();
    e.stopPropagation();

    if($(this).hasClass('disabled')) return;
    var id=$(this).data('id');

    $.ajax({
      type: 'post',
      data: {'id': id},
      url: '/cart/addtocart/'+id,
      success: function (resp){
        if($('#basketForJs').length) window.location.reload() //Если мы сейчас в корзине
        else{
          $.magnificPopup.open({
            items:{
              src: resp,
            },
            type: 'inline',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: true,
            closeMarkup: '<div class="mfp-close">&nbsp;</div>'
          });

           showTopCart();
        }
      }
    });

  });

  $('.js-yandex-send').on('click', function (e){//Отправляет достижение цели в яндекс
    // e.preventDefault();
    var target = $(this).data('target');
    if(!target) return;

    logDebug(target);

    if (!isDebug) {
      try {
        ym(144683, 'reachGoal', target)
      } catch (error) {
        logDebug(error);
      }
    }
  });

  $('.js-art-show').on('click', function (e){//Меняет артикул на id и обратно
    e.preventDefault();
    var text=$(this).data('content');
    $(this).data('content', $(this).text());
    $(this).text(text);
  });

  $(document).on('submit', '.js-ajax-form', function(e){//запрещаем отправку всем формам
    e.preventDefault();
    return false;
  });

  $('.js-submit-enable').on('change', function(e){//При изменении элемента разрешаем отправку
    $('.js-submit-button').removeClass('disabled');
  });

  $(document).on('click', '.js-submit-button', function(e){//специальному блоку делаем отправку

    e.preventDefault();
    if($(this).hasClass('disabled')) return false;
    var $form=$(this).closest('form');

    $form.find('.hasError').removeClass('hasError');
    if ($form.find('.js-duplicate-pass').length){
      let pass = $form.find('.js-duplicate-pass').val();
      logDebug(pass)
      $form.find('input[name="sf_guard_user[password_again]"]').val(pass);
    }
    if($form.find('input[name="agreement-18"]').length)//Подтверждение 18 лет
      if(!$form.find('input[name="agreement-18"]').is(':checked')){
        $form.find('input[name="agreement-18"]').next().addClass('hasError');
        return;
      }
    if($form.find('input[name="agreement"]').length)//Согласие на обработку персональных данных
      if(!$form.find('input[name="agreement"]').is(':checked')){
        $form.find('input[name="agreement"]').next().addClass('hasError');
        return;
      }
    if($form.find('.js-password-again').length)//Подтверждение пароля
      if($form.find('.js-password-again').val() != $form.find('.js-password').val() || $form.find('.js-password').val()==''){
        logDebug('password wrong!');
        $form.find('.js-password-again').addClass('hasError');
        $form.find('.js-password').addClass('hasError');
        $('html,body').animate({scrollTop: $('.js-password').offset().top-40});
        return;
      }

    var data=$form.serialize();
    data+='&token=Роботы нам тут не нужны!';
    $.ajax({
      url: $form.attr('action'),
      data: data,
      type: 'post',
      dataType: 'json',
      success: function(resp){
        if('ym_target' in resp){
          logDebug('ym_target found. send ' + resp.ym_target);
          if(!isDebug) {
            try {
              ym(144683,'reachGoal',resp.ym_target);
            } catch (error) {
              console.log('send yandex target "'+ resp.ym_target + '" error');
            }
          }
        }
        if('target' in resp){
          $form.remove();
          $(resp.target).append(resp.success);
        }
        if('sendRR' in resp){
          logDebug('--------------------------------------sendRR------------------------------')

          try {
            logDebug('senr to RR order from one click. product_id is '+resp.sendRR.productID+', transaction is '+resp.sendRR.transaction + ', price is '+resp.sendRR.price)
            rrApi.order({
                "transaction": resp.sendRR.transaction,
                "items": [
                    { "id": resp.sendRR.productID, "qnt": 1,  "price": resp.sendRR.price}
                ]
            });
          } catch(e) {
            logDebug('failed sent one click order to rr')
          }

        }
        if('error' in resp){
          if('field' in resp){
            if(resp.field=='alert') customAlert(resp.error);
            else
              if (resp.field == 'create_proof'){
                if($form.find('[name="create_proof"]').length){
                  $form.find('[name="create_proof"]').addClass('hasError');
                  $form.find('[name="create_proof"]').focus();
                  return;
                }
                else{
                  var $container = $form.find('.js-phone-mask').parent().parent();
                  $('.js-submit-button').addClass('mfp-hide');
                  var proofType = resp.proofType;
                  var block = 
                    '<div class="js-proof-container field field-default proof-container">' +
                      '<input type="text" name="create_proof">' +
                      '<input type="hidden" name="proof_type" value="' + proofType + '">' +
                      '<div class="js-proof-send btn-full btn-full_rad btn-half">Подтвердить</div>' +
                      '<div class="js-proof-result proof-result"></div>' +
                    '</div>';

                  $container.append($(block));
                  requestProof($form, 'call');
                  return;

                }
                
              }
              if (resp.field == 'move'){
                $('html,body').animate({ scrollTop: $(resp.element).offset().top - 40 });
              }
              else {
                $form.find('input[name="'+resp.field+'"]').addClass('hasError').focus();
                $form.find('textarea[name="'+resp.field+'"]').addClass('hasError');

                var $label = $form.find('input[name="'+resp.field+'"]').closest('.group-field_gender');
                if($label.length) $label.children().first().addClass('hasError');
              }
          }
          else $form.html(resp.error)
          return;
        }
        if('caltat_user' in resp){
          try {
            caltat.event(1004, {userid: resp.caltat_user});
          } catch (e) {
            console.log('caltat user send error')
          }
        }
        if('redirect' in resp){
          if(resp.redirect=='same') {
            window.location.href=window.location.href;
          }
          else{
            window.location.pathname=resp.redirect;
          }
        }
        if('wheel' in resp){//Если это форма колеса

          var angle=360+parseInt(resp.angle);
          $('.wheel-body').css('transform', 'rotate('+angle+'deg)');

          setTimeout(function (resp){
            $form.html(resp.text)
            $form.addClass('wide');
          }, 6000, resp);
          return true;
        }
        if('tinkoff' in resp){//Проверка Тиньков
          proceedTinkoff($form);
          return;
          // logDebug('tinkoff form checked');
        }
        if('checkout' in resp){
          $form.off();
          $form.removeClass('js-ajax-form');
          $form.append('<input type="hidden" name="checked" value="1">');
          $form.attr('action', '/cart/confirmed');
          var form=$('#basketForm');
          form.submit();
        }
        $form.html(resp.success)
        $('html,body').animate({scrollTop: $form.offset().top-40});
        if('initslider' in resp){
          initAllTestsSlider();
        }
      }
    });
  });

  $(document).on('click', '.js-magnific-close', function (e){//Закрытие магнифика
    e.preventDefault();
    $.magnificPopup.close();
  });

  $('.js-hide-show-next').on('click', function(e){//Показать/скрыть следующий блок
    e.preventDefault();
    $(this).toggleClass('active');
    var targetClass=$(this).data('class');
    if(!targetClass || targetClass=='') targetClass='mfp-hide';
    $(this).next().toggleClass(targetClass);
  });

  if($('#bonusAddAll').length){//Мелькание бонусами
    setInterval(function () {
        var str = parseFloat($('#bonusAddAll').text().replace(/ /g, "")) + 2;
        str = str + '';
        $('#bonusAddAll').text(str.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
    }, 1000);
  }

  $('.js-coupon-apply').on('click', function (e) {//клик "Применить купон"
    e.preventDefault()
    var coupon=$('input[name="coupon"]').val();
    var $delivery = $('input[name="deliveryId"]:checked');
    var deliveryId = $delivery.val()|0;
      $.ajax({//Отправляем купон на проверку
      url: '/cart/check-coupon',
      data: {coupon:coupon, deliveryId: deliveryId},
      type: 'post',
      dataType: 'json',
      success: function(resp){
        doCouponCheckReady(resp);
      }
    })
  });

  $(document).on('click', '.js-shop-init', function (e) {//клик "показать точки самовывоза"
    if(!$(this).data('dont-stop')) e.preventDefault();
    $.magnificPopup.open({
      items:{
        src: $('#shops-delivery'),
      },
      type: 'inline',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      fixedContentPos: true,
      closeMarkup: '<div class="mfp-close">X</div>'
    });
    //карта магазинов, ныне отключена
    var lat=55.753215, lng=37.622504;
    if($('#shops-map').length)
      if(!shopsDeliveryMap){
        shopsDeliveryMap = new ymaps.Map('shops-map', {
          center: [lat, lng],
          //controls: [],
          zoom: 10,
        });
        var myPlacemark ;
        $('#shops-delivery .js-shop-item').each(function (index, el){
          var $el=$(el);
          myPlacemark=new ymaps.Placemark([$el.data('lat'), $el.data('lng')], {
            balloonContent:
              $el.html()
          },
          {
           iconLayout: 'default#image',
           iconImageHref: '/frontend/images/favicon.ico',
           iconImageSize: [32, 32],
           iconImageOffset: [-15, -30],

          })
          shopsDeliveryMap.geoObjects.add(myPlacemark);
        })
      }

  });

  $(document).on('change', '.js-shop-select-list', function (e) {//клик "Выбрать магазин"
    // logDebug('js-shop-select-list work')
    e.preventDefault();
    var id=$(this).attr('id');
    var text=$('label[for="'+id+'"]').text();
    $('#address-10').html(text)
    $('#shop_id').val($(this).val())
    $('#shop_address_line').val(text)
    needToDeliveryCalc='need calc';
    calcDelivery();
    $.magnificPopup.close();
  });

  $(document).on('click', '.js-shop-select', function (e) {//клик "Выбрать магазин"
    logDebug('js-shop-select')
    e.preventDefault();
    var $shopBlock=$(this).closest('.shops-delivery')
    var $shop=$(this).prev()

    var text=$shop.find('.name').text()+'<br>'+$shop.find('.shop-address').text()

    $shopBlock.find('.js-current-shop-point').html(text)

    $('#shop_id').val($shop.data('id'))
    $('#shop_address_line').val(text)
    needToDeliveryCalc='need calc';
    calcDelivery();
    $.magnificPopup.close();

  });

  $(document).on('click', '.js-dlh-init', function (e) {//клик "показать точки" Евросеть
    e.preventDefault();
    showDrhLogistic();
  });

  $(document).on('click', '.js-pickpoint-init', function (e){//Открывает окно Pickpoint
    e.preventDefault();
    PickPoint.open(initPickpoint);
  });

  $(document).on('change', '.js-payment', function (e){//Изменение типа оплаты
    e.preventDefault();
    // logDebug('.js-payment need to rewrite ----------------------')
    if(!$(this).is(':checked')) {
      return false;
    }
    $('.payment-item').removeClass('active');
    $(this).parent().addClass('active');
    if($(this).val()==60) {//Тинькоф
      $('.js-tinkoff').removeClass('mfp-hide');
      $('.js-no-tinkoff').addClass('mfp-hide');
    }
    else{
      $('.js-tinkoff').addClass('mfp-hide');
      $('.js-no-tinkoff').removeClass('mfp-hide');
    }
    logDebug($(this).val())

    calcAllPrice();
  });

  $('.js-delivery-calc').on('change', function (e){//Изменение адреса доставки
    calcDelivery();
    // logDebug('.js-delivery-calc change :-/ ')
  });

  $('.js-delivery').on('change', function (e){//Изменение типа доставки
    e.preventDefault();
    // logDebug('.js-payment need to rewrite ----------------------')
    if(!$(this).is(':checked')) {
      return false;
    }
    $('.js-delivery').closest('label').removeClass('active');
    $(this).closest('label').addClass('active');
    $('.tab__content').removeClass('active');
    $('#tab_content-'+$(this).val()).addClass('active');

    $('.address').html( '');
    var $this=$(this);
    var currentDelivery=$this.val();
    $('#delivery-info').html($('#description-'+currentDelivery).html());//Показали описание доставки
    calcDelivery();
    // $('#payment-header').removeClass('mfp-hide');
    // $('#payment-body').removeClass('mfp-hide');
    // $('#delivery-info').removeClass('mfp-hide');
    $.ajax({//получаем оплаты для выбранного типа доставки
      url: '/cart/payments',
      data: {id:currentDelivery},
      type: 'post',
      success: function (resp){
        $('#payment-body').html(resp);
        showHideTinkoff();
        if (currentDelivery == 10 || currentDelivery == 14 || currentDelivery == 11 || currentDelivery == 17 || currentDelivery == 18)//Самовывоз, Евросеть или PickPoint, Ozon, Почта России
          $('.js-no-address-need').addClass('mfp-hide')
        else
          $('.js-no-address-need').removeClass('mfp-hide')
        doCouponRecalc(); // перепроверить купон при смене доставки
      }
    })

  });

  $('.js-basket-delete').on('click', function() {//Удаление товара из корзины
    // logDebug('.js-basket-delete may be need to rewrite ----------------------')

    var self = $(this);
    var prnt = self.closest('.basket-item');
    var mainPrnt = prnt.parent();
    var id=prnt.data('id');
    if(!id) return;
    $.ajax({
      url: '/deletefromcart/'+id,
      type: 'post',
      dataType: 'json',
      success : function (resp){
        if(resp.success=='success'){
          prnt.remove();
          var item = mainPrnt.find('.basket-item');
          if(false) {
          // if(item.length) {
            parseAjaxResult(resp.data);
            // calcAllPrice();
            // allBonusState = realbonusState - calcBonus();
            // showTopCart();
            // doCouponRecalc();
          } else {
            // logDebug('.js-basket-delete ---- Корзина пуста ----------------------')
            window.location.reload();

          }
        }
      }
    });
    // return false;
  });

  $('.js-product-row').on('change', function() {//Уменьшение/увеличение количества товара в корзине
    var self = $(this);

    let newCount=parseInt(self.val());
    if(maxCount && newCount>maxCount) {
      newCount=maxCount;
      self.val(newCount);
    }

    saveProductCount(self.closest('.basket-item').data('id'), newCount);
  });
  $('.js-toggle-class').on('click', function (){
    logDebug('foo')
  });

}

function sendWheelTimeout() {
  $.post({
    url: '/wheel-timeout'
  });
}

function showHideTinkoff(){// В зависимости от суммы заказа показывает/скрывает оплату Тинковым
  // logDebug('---------------- showHideTinkoff() ------------------');

  var priceToDelivery=parseInt($('.js-items-in-cart').data('price-for-delivery'));
  var price=parseInt($('[name="deliveryPriceSend"]').val());

  /*
    var discount = parseInt($('.js-discount').text());

    if (discount > 0) {// закрываем показ тинькова если есть скидка
      price = priceToDelivery = 0;
    }
  */

  if(tinkoffSettings.minimumPrice !== undefined) { // Есть порог для покупки через Tinkoff
    if(tinkoffSettings.minimumPrice > priceToDelivery + price){
      // logDebug('закрываем показ тинькова')
      $('.js-tinkoff-payment').addClass('mfp-hide');
      // $('#info-user-bonus').removeClass('mfp-hide')
      if($('.js-tinkoff-payment').find('input').is(':checked')) $('.js-tinkoff-payment').prev().click();
    }
    else{
      // logDebug('открываем показ тинькова')
      $('.js-tinkoff-payment').removeClass('mfp-hide');
      // $('#info-user-bonus').addClass('mfp-hide')
    }
  }
}

function proceedTinkoff($form){
  var items=[], total=0, deliveryPrice;
  const regex = /([^\d]*)/gm;
  $form.find('.js-product-row').each( function (index, item){
      let $item = $(item);
      let priceLine = $item.closest('.basket-item').find('.product-item__price').text();
      priceLine = priceLine.replace(regex, '');
      let price = parseInt(priceLine);
      items.push({
        name: $item.data('name'),
        quantity : parseInt($item.val()),
        price : price
      });
      total += price * parseInt($item.val());
  });

  deliveryPrice = parseInt($('[name="deliveryPriceSend"]').val());
  if(deliveryPrice > 0){
    items.push({
      name: 'Доставка',
      quantity : 1,
      price : deliveryPrice
    });
    total += deliveryPrice;
  }
  tinkoff.methods.on(tinkoff.constants.SUCCESS, tinkoffMessageHandler);
  tinkoff.methods.on(tinkoff.constants.REJECT, tinkoffMessageHandler);
  tinkoff.methods.on(tinkoff.constants.APPOINTED, tinkoffMessageHandler);
  tinkoff.methods.on(tinkoff.constants.APPROVED, tinkoffMessageHandler);
  // tinkoff.methods.on(tinkoff.constants.CANCEL, tinkoffMessageHandler);
  // logDebug('email is ' + $('[name="user_mail"]').val());


  if(isDebug)
    tinkoff.createDemo(
      {
        sum: total,
        items: items,
        demoFlow: 'sms',
        // demoFlow: 'appointment',
        // promoCode: '',
        shopId: tinkoffSettings.shopId,
        promoCode: 'installment_0_0_3_4,34',
        showcaseId: tinkoffSettings.showcaseId,
        values: {
          contact: {
            mobilePhone: $('[name="phone"]').val(),
            email: $('[name="user_mail"]').val()
          }
        }
      }
    );
  else
    tinkoff.create(
      {
        sum: total,
        items: items,
        demoFlow: 'sms',
        promoCode: 'installment_0_0_3_4,34',
        // promoCode: 'installment_0_0_3_5,19',
        // demoFlow: 'appointment',
        // promoCode: '',
        shopId: tinkoffSettings.shopId,
        showcaseId: tinkoffSettings.showcaseId,
        values: {
          contact: {
            mobilePhone: $('[name="phone"]').val(),
            email: $('[name="user_mail"]').val()
          }
        }
      }
    );
  // logDebug(items);

}

function tinkoffMessageHandler(data){//Обработчик ответов от тинькова
  logDebug(data);
  logToFile('tinkoff', data.type + '|' + $('.js-ajax-form').find('[name="user_mail"]').val() + '|' + $('.js-ajax-form').find('[name="phone"]').val());
  switch (data.type) {
    case tinkoff.constants.SUCCESS:
      var $form = $('.js-ajax-form');
      $form.off();
      $form.removeClass('js-ajax-form');
      $form.append('<input type="hidden" name="checked" value="1">');
      $form.attr('action', '/cart/confirmed');
      $form.append('<input type="hidden" name="tinkoff-payment" value="1">');

      $form.submit();

      return ;
      break;
    case tinkoff.constants.REJECT:
      console.log('REJECT', data.meta.iframe.url);
      break;
    case tinkoff.constants.CANCEL:
      console.log('CANCEL', data.meta.iframe);
      console.log('CANCEL', data.meta.iframe.url);
      break;
    default:
      return;
  }
  tinkoff.methods.off(tinkoff.constants.SUCCESS, tinkoffMessageHandler);
  tinkoff.methods.off(tinkoff.constants.REJECT, tinkoffMessageHandler);
  tinkoff.methods.off(tinkoff.constants.CANCEL, tinkoffMessageHandler);
  // data.meta.iframe.destroy();
}

function submitFilter(){
  if($('#js-filters-form').length) $('#js-filters-form').submit();
}

function showDrhLogistic(){//Показ окна виджета Евросеть
  // logDebug('showDrhLogistic need to check ----------------------')
  $('.drh-container').addClass('active');
  window.onPointSelected = function(deliveryPoint) {
    deliveryPointSelected=deliveryPoint;
    needToDeliveryCalc = 'one more time';//Нужен повторный расчет
    $('.drh-container').removeClass('active');
    calcDelivery();

      // описание объекта deliveryPoint
        // {
        //   city: "Москва" //город доставки
        //   region: "Москва г." // регион доставки, необходим для городов, находящихся в нескольких регионах
        //   address: "Кутузовский пр-кт 22", // Адрес точки
        //   deliveryCost1: 1000, // Стоимость доставки до 1кг
        //   deliveryCost2: 1100, // Стоимость доставки до 2кг
        //   deliveryCost3: 1200, // Стоимость доставки до 3кг
        //   deliveryCost4: 1300, // Стоимость доставки до 4кг
        //   deliveryCost5: 1400, // Стоимость доставки до 5кг
        //   deliveryCost6: 1500, // Стоимость доставки до 6кг
        //   deliveryCost7: 1600, // Стоимость доставки до 7кг
        //   deliveryCost8: 1700, // Стоимость доставки до 8кг
        //   hash: "D9B04D555562C41955612352F046E6CE", // Хеш сумма, для валидации подлинности данных для устаревшей тарифной сетки до 4 кг (не использовать, оставлено для совместимости с ранее интегрировавшимися партнерами)
        //   hash8: "31AE97914586F6AA97F52E0AE4B41FD5", // Хеш сумма, для валидации подлинности данных для тарифной сетки до 8 кг на доставку (не использовать, оставлено для совместимости с ранее интегрировавшимися партнерами)
        //   hash16: "5E0680EBB4BC2607EE4875F3C2825C98", // Хеш сумма, для валидации подлинности данных для тарифных сеток до 8 кг на доставку и на возврат
        //   name: "Кутузовский пр-т, 22", // Имя точки
        //   returnCost: 500, // Стоимость возврата (не использовать, оставлено для совместимости с ранее интегрировавшимися партнерами)
        //   returnCost1: 500, // Стоимость возврата до 1кг
        //   returnCost2: 550, // Стоимость возврата до 2кг
        //   returnCost3: 600, // Стоимость возврата до 3кг
        //   returnCost4: 650, // Стоимость возврата до 4кг
        //   returnCost5: 700, // Стоимость возврата до 5кг
        //   returnCost6: 750, // Стоимость возврата до 6кг
        //   returnCost7: 800, // Стоимость возврата до 7кг
        //   returnCost8: 850, // Стоимость возврата до 8кг
        //   shopId: "M354" // Идентификатор точки
        //   deliveryOperatorId: 1 // Идентификатор торговой сети
        //   deliveryOperator: "Евросеть" // Наименование торговой сетиы
      // }


  }
  // partnerId - Идентификатор партнера 147
  // map - id элемента страницы, куда необходимо вставить виджет. Элемент должен быть не менее 900px в ширину и 720px в высоту
  // для адаптивных интерфейсов ширина 900px должна соблюдаться при ширине экрана более 992px. Высота должна быть 720px всегда
  // onPointSelected - функция, которая будет вызвана после выбора точки пользователем
  if (!mapWidget) console.log('need to show and init vidget')
  if (!mapWidget) mapWidget = new MapWidget(151, "map", onPointSelected, {'size':'xs'});
  // запустить инициализацию виджета
  mapWidget.init();
}

function initPickpoint(result) {//Инициализация виджета пикпойнт
  // logDebug('initPickpoint need to check ----------------------')
    // устанавливаем в скрытое поле ID терминала
    document.getElementById('pickpoint_id').value = result['id'];
    document.getElementById('pickpoint_address').value = result['name'] + '<br />' + result['address'];

    // показываем пользователю название точки и адрес доствки
    $('.address').html( result['name'] + '<br />' + result['address']);
    needToDeliveryCalc = 'one more time';//Нужен повторный расчет
    calcDelivery();
}

function calcDelivery(){

  var $delivery=$('input[name="deliveryId"]:checked');
  var deliveryId=$delivery.val();

  /* var productsCount = 0;
  $('.js-product-row').each(function () {
    productsCount += $(this).val()|0;
  }); */

  var express_disc_50_if_gt_3 = $('#basketForm [name=express_disc_50_if_gt_3]').val()|0;

  var freeDeliveryLimit=parseInt($('#basketForm').data('freedelivery'));//Изначально считаем, что порог бесплатной доставки тут

  if(parseInt($delivery.data('free-from'))) {
    //Если у варианта доставки прописано свое значение
    freeDeliveryLimit=parseInt($delivery.data('free-from'));
    // logDebug('$delivery.data is set '+freeDeliveryLimit);
  }
  var weight=getOrderWeigth();

  var price=290;

  var cartTotalCost=parseInt($('.js-items-in-cart').data('price-for-delivery'));
  var onlinePayment = false;
  if($('.js-payment:checked').length){
    if($('.js-payment:checked').data('online')){
      onlinePayment=true;
    }
  }
  var taxiDefaultPrice=1000;

  if(onlinePayment){//Если выбрана оплата онлайн
    //если выставлена общая цифра для бесплатной доставки онлайн по России - берем ее
    if(parseInt($('#basketForm').data('freedelivery-online-russia'))) {
      freeDeliveryLimit=parseInt($('#basketForm').data('freedelivery-online-russia'));
    }
    if(parseInt($delivery.data('free-from-online'))) {
      //Если у варианта доставки прописано свое значение для онлайн
      freeDeliveryLimit=parseInt($delivery.data('free-from-online'));
    }
    if($('#region-fias-id').val()=='0c5b2444-70a0-4932-980c-b4dc0d3f02b5'){
      //Если доставка в Москву и выставлен лимит отдельно для Москвы - берем его
      if(parseInt($('#basket').data('freedelivery-online-moscow'))) {
        freeDeliveryLimit=parseInt($('#basketForm').data('freedelivery-online-moscow'));
      }

      if(parseInt($delivery.data('free-from-online-moscow'))) {
        //Если у варианта доставки прописано свое значение для онлайн для Москвы
        freeDeliveryLimit=parseInt($delivery.data('free-from-online-moscow'));
      }

    }
  }

  if($('#region-fias-id').val()=='0c5b2444-70a0-4932-980c-b4dc0d3f02b5' && deliveryId==16){//Любая поездка по москве 300
    freeDeliveryLimit = 9999999999;
    price=1000;
    taxiDefaultPrice=1000;
  }

  var city =      $('input[name="city"]').val(),
      street =    $('input[name="street"]').val(),
      house =     $('input[name="house"]').val(),
      needCalc = weight + '|' + city + '|' + street+ '|'  + house+ '|' +  onlinePayment+ '|' + cartTotalCost+'|'+deliveryId+'|'+freeDeliveryLimit;

  if($('#ozon_id').length) needCalc += $('#ozon_id').val();
  if(deliveryId==9) freeDeliveryLimit=4990; //Курьер по России
  if(deliveryId==11) freeDeliveryLimit=3590;//PickPoint
  if($('#not-add-delivery').val()) {
    freeDeliveryLimit = -1;
    // logDebug('perform not add delivery logic')
  }
  if(cartTotalCost>freeDeliveryLimit) price=0;
  // logDebug([needToDeliveryCalc!=needCalc, needToDeliveryCalc,needCalc, 'v1'])
  if(deliveryId==16) price = taxiDefaultPrice;

  if(needToDeliveryCalc!=needCalc) {//Выполняем рассчет
    needToDeliveryCalc=needCalc;
    if(!price) { //Если бесплатно - дальше не считаем
      if(deliveryId==17) $.magnificPopup.close();
      showDeliveryCost(price);
      return;
    }
    if(deliveryId==17){//OzonDelivery
      $.ajax({  //Запросили api
        url: '/ozon_get_delivery_price',
        data: {
          'id': $('#ozon_id').val(),
        },
        type: 'post',
        success: function(response){
          if(response.status) {
            $.magnificPopup.close();
            showDeliveryCost(response.price); //показали
          }
          else{
            if('alert' in response) alert(response.alert)
            logDebug(response.message)
            showDeliveryCost(price); //показали
          }
        }
      });
    }
    if(deliveryId==19){//SDEK
      logDebug('calcDelivery id == 19');
      logDebug(sdek);
      if(!!!sdek.data) return;
      price = sdek.data.price;
      showDeliveryCost(price); //показали
    }
    if(deliveryId==21){//VIA
      logDebug('calcDelivery id == 21');
      logDebug(viaSelected);
      if (!viaSelected) return;
      price = viaSelected.price;
      showDeliveryCost(price); //показали
    }
    if(deliveryId==18){//Почта россии новая
      // logDebug(russianPostSelected)
      logDebug(!russianPostSelected.indexTo)
      logDebug(russianPostSelected.indexTo)
      if (!russianPostSelected.indexTo) return;
      price = Math.ceil(parseFloat(russianPostSelected.cashOfDelivery)/100);
      showDeliveryCost(price); //показали

      let addrString = russianPostSelected.regionTo;
      if (russianPostSelected.areaTo) addrString += ', ' + russianPostSelected.areaTo;
      if (russianPostSelected.cityTo) addrString += ', ' + russianPostSelected.cityTo;
      if (russianPostSelected.addressTo) addrString += ', ' + russianPostSelected.addressTo;

      $('#post_id').val(russianPostSelected.indexTo);
      if (russianPostSelected.pvzType == "russian_post") addrString += ', ' + 'Почтовое отделение ' + russianPostSelected.indexTo;
      else addrString += ', ' + 'Постомат ' + russianPostSelected.indexTo;

      $('#post_address').val(addrString);

      $('#address-18').text(addrString);

    }
    if(deliveryId==16){//Яндекс такси новая
      logDebug(suggestionForTaxi)

      price=taxiDefaultPrice;
      if(!!suggestionForTaxi){
        if($('#region-fias-id').val()=='0c5b2444-70a0-4932-980c-b4dc0d3f02b5'){
          logDebug('Такси будет стоить 500')
          price=1000;

        }
        else{
          logDebug('Запрашиваем стоимость такси')
          $.ajax({  //Запросили api
            url: '/yandex_get_delivery_price',
            data: {
              // 'weight': weight,
              // 'city': city,
              // 'street': street,
              // 'house': house,
              // 'pay-online': onlinePayment,
              // 'order-price': cartTotalCost,
              'lat': suggestionForTaxi.data.geo_lat,
              'lon': suggestionForTaxi.data.geo_lon,
            },
            type: 'post',
            success: function(response){
              if(response.status) {
                showDeliveryCost(response.price); //показали
                $('#lat').val(suggestionForTaxi.data.geo_lat);
                $('#lon').val(suggestionForTaxi.data.geo_lon);
              }
              else{
                price=taxiDefaultPrice;
                logDebug(response.message)
                showDeliveryCost(price); //показали
                alert('Доставка данной службой временно недоступна')
              }
            }
          });
        }
      }
      else logDebug('Нет координат для расчета');
    }
    if(deliveryId==15){//Яндекс
      price=299;
    }
    if(deliveryId==3){//Почта России
      if(cartTotalCost<freeDeliveryLimit) {
        price=Math.round(220+0.05*cartTotalCost);
      }
      weight = 1000 * weight;
      $.ajax({  //Запросили api
        url: '/russianpost_get_delivery_price',
        data: {
          'weight': weight,
          'city': city,
          'street': street,
          'house': house,
          'pay-online': onlinePayment,
          'order-price': cartTotalCost
        },
        type: 'post',
        success: function(response){
          if(response.status) {
            showDeliveryCost(response.price); //показали
          }
        }
      });
    }
    if(deliveryId==9){//Курьер по России
      freeDeliveryLimit=4990;
      if(cartTotalCost<freeDeliveryLimit) {
        price=300;
      }
    }
    if(deliveryId==11){//PickPoint
      freeDeliveryLimit=3590;
      if(cartTotalCost>freeDeliveryLimit) price=0;
      else{
        price=290;
        if(!$('#pickpoint_id').length || !$('#pickpoint_id').val()){
          // alert('Выберите точку доставки');
          console.log('PickPoint. точка неясна');
          // needToDeliveryCalc = 'one more time';//Нужен повторный расчет
          // return;
        }
        else{
          weight=String(weight).replace('.',',');

          $.ajax({
            url: '/pickpoint_get_delivery_price/'+$('#pickpoint_id').val()+'/'+weight,
            type: 'post',
            success: function(response){
              if(!response[0]) console.log(response[1]);//alert(response[1]);
              else {
                price=response[1];

                showDeliveryCost(price)
              }
            }
          });
        }
      }
    }
    if(deliveryId==14){//Евросеть
      if(!deliveryPointSelected) {
        logDebug('Евросеть. точка неясна');
        // needToDeliveryCalc = 'one more time';//Нужен повторный расчет
        // return;
      }
      else{
        // var weight=getOrderWeigth();
        price=deliveryPointSelected.deliveryCost1;
        var message=deliveryPointSelected.region+', '+deliveryPointSelected.city+', '+deliveryPointSelected.address+'. Работает с '+deliveryPointSelected.openTime+' до '+deliveryPointSelected.closeTime;
        // $('#dlh_address_line').val(message);//Куда-то вывести
        if(weight>1) price=deliveryPointSelected.deliveryCost2;
        if(weight>2) price=deliveryPointSelected.deliveryCost3;
        if(weight>3) price=deliveryPointSelected.deliveryCost4;
        if(weight>4) price=deliveryPointSelected.deliveryCost5;
        if(weight>5) price=deliveryPointSelected.deliveryCost6;
        if(weight>6) price=deliveryPointSelected.deliveryCost7;
        if(weight>7) price=deliveryPointSelected.deliveryCost8;
        if(weight>=8){
          price=0;
          message='Доставка данным оператором невозможна. Пожалуйста, выберите другой вид доставки';
        }
        $('.address').html( message);
        $('#dlh_address_line').val( message);

        $('#dlh_shop_id').val(deliveryPointSelected.shopId);
        if(cartTotalCost>freeDeliveryLimit) price=0;

      }
    }
    if(deliveryId==10){//Самовывоз
      $('.address').html($('#shop_address_line').val());
      // $('#').val( message);
      price=290;
    }
    showDeliveryCost(price);
  }
  else {
    logDebug('don not need to calc and show delivery price');
    checkPickpointDiscount();
    // return ;
  }

  if(cartTotalCost>freeDeliveryLimit) {
    price=0;
    showDeliveryCost(price);
  }

  /* if (deliveryId == 16 && express_disc_50_if_gt_3 && productsCount >= 3) {
    price *= 0.5;
    showDeliveryCost(price);
  } */
}

function showDeliveryCost(price){
  var priceToDelivery=1*$('.js-items-in-cart').data('price-for-delivery');
  price = Math.round(1*price);
  $('.js-total-cost').html(formatPrice(priceToDelivery+price));
  $('.js-delivery-price').html(formatPrice(price));
  $('#deliveryPriceSend').val(formatPrice(price));
  checkPickpointDiscount();
  showHideTinkoff();
}

function checkPickpointDiscount(){
  console.log('Должны проверить предоставление скидки по Pickpoint');
  var deliveryId = 0;
  var
    onlineDiscount = 0;

  if ($('.js-delivery:checked').length) {
    deliveryId = $('.js-delivery:checked').val();
  }
  if (deliveryId == 11) onlineDiscount = 0.02;

  showPickpointDiscount(onlineDiscount);
}

function showPickpointDiscount(onlineDiscount) {
  var totalSum = parseInt($('.js-items-in-cart').data('sum')),
    couponDiscount = parseInt($('.js-items-in-cart').data('coupon')),
    bonusDiscount = parseInt($('.js-bonus-count').val()),
    // onlineDiscount = 0,
    // bonusToAdd = 0,
    totalWOdiscount = 0,
    totalDiscount = 0,
    total = 0,
    deliveryPrice,
    onlineDiscountSum = 0;

  var coef = 1 - onlineDiscount;
  var totalSumWODiscount = 0;

  $('.js-product-row').each(function (index, item) {
    var $item = $(item);
    if ($item.data('price') == $item.data('price-wo')) {
      totalSumWODiscount += Math.round($item.data('bonus-max-percent')) ? parseInt($item.data('price-wo')) * parseInt($item.val()) : 0;
    }
  });

  var step = 0;
  if (totalSumWODiscount)
    step = bonusDiscount / totalSumWODiscount;
  var totalBonus = 0;

  $('.js-product-row').each(function (index, item) {
    var $item = $(item),
      priceLine = $item.closest('.basket-item').find('.product-item__price'),
      currentPrice = $item.data('price'),
      itemBonus = Math.round($item.data('bonus-max-percent')) ? currentPrice * step : 0,
      $bonusLine = $item.closest('.basket-item').find('.purchase-card__bonus span')
      ;

    if ($item.data('price-wo') != $item.data('price')) itemBonus = 0;
    var newPrice = coef * parseInt(currentPrice - itemBonus);
    let bonusLineSum = Math.round($item.data('bonus-max-percent') * newPrice * $item.val() / 100);

    // logDebug({ bonusLineSum: bonusLineSum, bonusMaxPercent: Math.round($item.data('bonus-max-percent')), newPrice : newPrice, itemVal :$item.val()});
    $bonusLine.text(formatPrice(bonusLineSum));
    totalBonus += bonusLineSum;
    totalWOdiscount += parseInt($item.data('price-wo')) * parseInt($item.val());

    if (newPrice == $item.data('price-wo')) {
      priceLine.removeAttr('old-price');
    }
    else {
      priceLine.attr('old-price', formatPrice($item.data('price-wo')) + ' ₽');
    }

    $('.js-bonus-add').text(formatPrice(totalBonus));
    priceLine.text(formatPrice(Math.round(newPrice)) + ' ₽');

  });

  total = totalSum - couponDiscount - bonusDiscount;
  onlineDiscountSum = Math.round(onlineDiscount * total);

  total = total - onlineDiscountSum;
  totalDiscount = totalWOdiscount - total;

  deliveryPrice = parseInt($('#deliveryPriceSend').val().replace(/[^0-9\.]/, ''));
  logDebug('deliveryPrice is ' + deliveryPrice);

  $('.js-online-discount').html(formatPrice(onlineDiscountSum));
  $('.js-total').html(formatPrice(total));
  $('.js-total-cost').html(formatPrice(total + deliveryPrice));
  $('.js-discount').text(formatPrice(totalDiscount));

  if (!totalDiscount) {//Показываем/скрываем общий блок скидок
    $('.js-discount-block').addClass('mfp-hide');
  }
  else {
    $('.js-discount-block').removeClass('mfp-hide');
  }

  if (!onlineDiscount) {//Показываем/скрываем блок скидок pickpoint
    $('.js-online-discount-block').addClass('mfp-hide');
  }
  else {
    $('.js-online-discount-block').removeClass('mfp-hide');
  }

}

function calcAllPrice(){//считает стоимость доставки
  // logDebug('calcAllPrice need to rewrite ----------------------');
  var totalSum=parseInt($('.js-items-in-cart').data('sum')),
      couponDiscount = parseInt($('.js-items-in-cart').data('coupon')),
      bonusDiscount = parseInt($('.js-bonus-count').val()),
      onlineDiscount=0,
      bonusToAdd=0,
      totalWOdiscount = 0,
      totalDiscount=0,
      onlineDiscountSum=0;
  /*if($('.js-payment:checked').length){
    if($('.js-payment:checked').data('online')){
      onlineDiscount=0.07;
    }
  }*/
  /*if($('.js-delivery:checked').length){
    if($('.js-delivery:checked').val() == 11){
      onlineDiscount=0.02;
    }
  }*/
  if(!onlineDiscount) $('.js-online-discount-block').addClass('mfp-hide');
  else $('.js-online-discount-block').removeClass('mfp-hide');

  if(bonusDiscount){
    $('.js-bonus-container').removeClass('mfp-hide');
  }
  else{
    $('.js-bonus-container').addClass('mfp-hide');
  }
  $('.js-bonus-view').text(formatPrice(bonusDiscount));
  var total = totalSum - couponDiscount - bonusDiscount;
  onlineDiscountSum = Math.round(onlineDiscount*total);
  $('.js-online-discount').html(formatPrice(onlineDiscountSum));
  logDebug({text: 'до вычитания скидки', totalSum:totalSum,  total:total, onlineDiscountSum: onlineDiscountSum});
  total = total - onlineDiscountSum;
  $('.js-items-in-cart').data('price-for-delivery', total);
  bonusToAdd=Math.round(total*0.01*$('.js-items-in-cart').data('bonus-add-percent'));
  $('.js-bonus-add').html(formatPrice(bonusToAdd));
  $('.js-total').html(formatPrice(total));
  $('.js-product-row').each(function (index, item){
    var $item=$(item);
    var count = parseInt($item.val());
    var isExpress = $item.data('is-express') || false;
  });
  $('.js-product-row').each(function (index, item){
    var $item=$(item);
    var count = parseInt($item.val());
    var thisPrice = parseInt($item.data('price-wo')) * count;
    totalWOdiscount += thisPrice;
  });
  totalDiscount = totalWOdiscount-total;
  $('.js-discount').text(formatPrice(totalDiscount));
  if(!totalDiscount) $('.js-discount-block').addClass('mfp-hide');
  else $('.js-discount-block').removeClass('mfp-hide');
  showOnlineDiscount(onlineDiscount, totalSum, bonusDiscount);

  // logDebug({total:total, onlineDiscountSum: onlineDiscountSum, totalWOdiscount:totalWOdiscount, totalDiscount:totalDiscount});
  calcDelivery();
}

function showOnlineDiscount(onlineDiscount, totalSum, bonusDiscount){

  var coef=1-onlineDiscount;
  var totalSumWODiscount=0;

  $('.js-product-row').each(function ( index, item){
    var $item=$(item);
    if($item.data('price')==$item.data('price-wo')){
      totalSumWODiscount += Math.round($item.data('bonus-max-percent')) ? parseInt($item.data('price-wo'))*parseInt($item.val()) : 0;
    }
  });

  var step=0;
  if(totalSumWODiscount)
    step=bonusDiscount/totalSumWODiscount;
  var totalBonus=0;

  $('.js-product-row').each(function ( index, item){
    var $item=$(item),
        priceLine=$item.closest('.basket-item').find('.product-item__price'),
        currentPrice=$item.data('price'),
        itemBonus = Math.round($item.data('bonus-max-percent')) ? currentPrice*step : 0,
        $bonusLine=$item.closest('.basket-item').find('.purchase-card__bonus span')
    ;

    if($item.data('price-wo')!=$item.data('price')) itemBonus = 0;
    var newPrice=coef*parseInt(currentPrice-itemBonus);
    let bonusLineSum = Math.round($item.data('bonus-max-percent')*newPrice*$item.val()/100);

    logDebug(bonusLineSum + '|' + Math.round($item.data('bonus-max-percent')) + '|' + newPrice + '|' +  $item.val());
    $bonusLine.text(formatPrice(bonusLineSum));
    totalBonus+=bonusLineSum;

    if(newPrice==$item.data('price-wo')) {
      priceLine.removeAttr('old-price');
    }
    else{
      priceLine.attr('old-price', formatPrice($item.data('price-wo'))+' ₽');
    }

    $('.js-bonus-add').text(formatPrice(totalBonus));
    priceLine.text(formatPrice(Math.round(newPrice))+' ₽');

  });

  logDebug('showOnlineDiscount fired')

}

function formatPrice(str){//форматирует число добавляя пробелы
  return String(str).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
}

function doCouponRecalc(){
  // logDebug('doCouponRecalc need to check');
  var coupon=$('input[name="coupon"]').val();
  var $delivery = $('input[name="deliveryId"]:checked');
  var deliveryId = $delivery.val()|0;
  if(coupon){ //Если есть купон, то надо проверить скидку по нему
    // logDebug('have coupon in /cart/addtocartcount/  ajax success. need to check');
    $.ajax({//Отправляем купон на проверку
      url: '/cart/check-coupon',
      data: {coupon:coupon, deliveryId: deliveryId},
      type: 'post',
      dataType: 'json',
      success: function(resp){
        // logDebug('/cart/check-coupon ajax success in /cart/addtocartcount/:id ajax success ----------------------');
        doCouponCheckReady(resp);
      }
  })}
  else
    calcAllPrice();

}

function saveProductCount (id, count){//Сохраняет значения количества в корзине пользователя
  if(maxCount && count>maxCount) count=maxCount;

  $.ajax({
    url: "/cart/addtocartcount/"+id,
    data: {count: count},
    success: function (resp){
      showTopCart();
      var total=0,
        totalWOdiscount=0;
      $('.js-product-row').each(function (index, item){
        var $item=$(item);
        total+=$item.val()*$item.data('price');
        totalWOdiscount+=$item.val()*$item.data('price-wo');
        $item.data('sum', $item.val()*$item.data('price'));
      });
      // logDebug('total|' + total)
      $('.js-items-in-cart').data('sum', total);
      $('.js-items-in-cart').attr('old-price', formatPrice(totalWOdiscount)+' ₽');
      doCouponRecalc();
      updateVIAMap();
    }
  })
}

function doCouponCheckReady(resp){
  logDebug('----------------------------------------------doCouponCheckReady');
  // logDebug('products:')
  // logDebug(resp.products)
  // logDebug('resp.products.length=' + resp.products.length)
  var total=0;
  $('input[name="coupon"]').val(resp.couponText)
  if('success' in resp){
    if('successCoupon' in resp){
      if(resp.successCoupon) {//Купон успешно применен, выполняем необходимые действия
        $('.wrap-field-range').addClass('mfp-hide');
        $('.js-bonus-count').val(0);
        sl.slider( "option", "value", 0);
        $('input[name="coupon"]').addClass('success')
        $('input[name="coupon"]').removeClass('fail')
      }
    }

    for(var i=0; i<resp.products.length; i++){
      var $product=$('#basket-item-'+resp.products[i].productId);
      if($product.data('init-price')-resp.products[i].price_w_discount >0)
        total+=resp.products[i].quantity*$product.data('init-price') - resp.products[i].price_w_discount*resp.products[i].quantity;
    }
  }
  else {
    $('input[name="coupon"]').removeClass('success')
    $('input[name="coupon"]').addClass('fail');
    if(!$('.wrap-field-range').hasClass('not-show')) $('.wrap-field-range').removeClass('mfp-hide');
  }
  if(resp.couponText==''){
    if(!$('.wrap-field-range').hasClass('not-show')) $('.wrap-field-range').removeClass('mfp-hide');
    $('input[name="coupon"]').removeClass('success');
    $('input[name="coupon"]').removeClass('fail');
  }
  $('.js-items-in-cart').data('coupon', total);

  for(var i=0; i<resp.products.length; i++){//показываем скидки
    var prodResp=resp.products[i];
    // logDebug(prodResp);
    var $product=$('#basket-item-'+prodResp.productId);
    var $prodItemPrice=$product.find('.product-item__price');
    if(prodResp.price==prodResp.price_w_discount) { //если скидки нет
      $prodItemPrice.removeAttr('old-price');
    }
    else{//если скидка есть
      $prodItemPrice.attr('old-price', formatPrice(prodResp.price)+' ₽');
      // $prodItemPrice.text(prodResp.price_w_discount);
    }
    $prodItemPrice.text(formatPrice(prodResp.price_w_discount)+' ₽');
    $product.find('.js-product-row').data('price', prodResp.price_w_discount);
    if (!prodResp.price_w_discount) {//цена ноль
      $product.find('input').attr('disabled', 'disabled');
    }
    else {
      $product.find('input').removeAttr('disabled');
    }
    // logDebug('set price to ' + formatPrice(prodResp.price_w_discount)+' ₽')
  }

  var express_disc_50_if_gt_3 = resp.express_disc_50_if_gt_3 || false;
  var $input_express_disc_50_if_gt_3 = $('#basketForm [name=express_disc_50_if_gt_3]');
  if (!$input_express_disc_50_if_gt_3.length) {
    $input_express_disc_50_if_gt_3 = $('<input type="hidden" name="express_disc_50_if_gt_3">');
    $('#basketForm').prepend($input_express_disc_50_if_gt_3);
  }
  $input_express_disc_50_if_gt_3.val(express_disc_50_if_gt_3 ? 1 : 0);

  calcAllPrice();
}

function getOrderWeigth(){//Текущий вес товара в корзине
  var weight=0;
  $('.js-product-row').each(function(i, el){
    weight+=parseFloat($(el).data('weight'))*parseInt($(el).val());
  })
  return weight;
}

function showTopCart(){//Показывает корзину вверху страницы
  $.ajax({
    type: 'post',
    url: '/carttop',
    success: function (resp){
      $('#header-basket').html(resp);
      const regex = /count="(\d+)"/gm;
      let m;
      m = regex.exec(resp)
      logDebug(m[1]);//первое
      if(!!m[1]) $('.mobile-bottom-menu').find('.btn-cart_count').attr('count', m[1]);
    }
  })
}

function closeAllSelect(elmnt) {
  /* A function that will close all select boxes in the document,
  except the current select box: */
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

/* If the user clicks anywhere outside the select box,
then close all select boxes: */
document.addEventListener("click", closeAllSelect);

$(document).ready(function () {

  initEvents();
  initDadata();
  initMap();
  if (!$('.-delivery .sdek-map').length)  initMapWidget();
  initPostWidget();
  initSdekWidget();
  initSelect();
  // btnScrollTop();

  if ($(".sl-sexopedy").length) {
    var slCatIconsNew = new Swiper('.sl-sexopedy', {
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
        767: {
          freeMode: false,
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          freeMode: false,
          slidesPerView: 3,
          spaceBetween: 22,
        },
      }
    });
  }
  if ($(".shop-image-slider").length) {
    var slCatIconsNew = new Swiper('.shop-image-slider', {
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
        767: {
          freeMode: false,
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          freeMode: false,
          slidesPerView: 3,
          spaceBetween: 22,
        },
      }
    });
  }
  if ($(".shop-image-slider-mobile").length) {
   slShops = new Swiper('.shop-image-slider-mobile', {
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
        767: {
          freeMode: false,
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          freeMode: false,
          slidesPerView: 3,
          spaceBetween: 22,
        },
      }
    });
  }
  if ($(".table-shop-side").length) {
    // logDebug('tableshop swiper 5.5_bis')
      var swiper = new Swiper(".table-shop-side", {
        direction: 'vertical',
        slidesPerView: "auto",
        freeMode: true,
        scrollbar: {
          el: '.swiper-scrollbar',
          hide: false,

        },
        mousewheel: true,
      });
  }
  if ($(".sl-news").length) {
    var slNews = new Swiper('.sl-news', {
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },
      breakpoints: {
        320: {
          slidesPerView: 2,
          spaceBetween: 15,
        },
        767: {
          freeMode: false,
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1024: {
          freeMode: false,
          slidesPerView: 4,
          spaceBetween: 26,
        },
      }
    });
  }
  if ($(".sl-catalog__new").length) {
    var slCatIconsNew = new Swiper('.sl-catalog__new', {
      freeMode: true,
      slidesPerView: "auto",
      watchOverflow: true,

      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },
      breakpoints: {
        320: {
          spaceBetween: 10,
        },
        767: {
          spaceBetween: 27,
        }
      }
    });
  }
  if ($(".slider-inside").length) {
    $(".slider-inside .swiper-container").each(function(index, item){
      // if($(item).find('.swiper-slide').length >1)
        var sw = new Swiper(item, {
          slidesPerView: 1,
          navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
          },

        });
    });
  }

  if ($(".sl-brands").length) {
    var slAdvantages = new Swiper(".sl-brands", {
      freeMode: true,
      slidesPerView: "auto",
      watchOverflow: true,
    });
  }
  if ($(".sl-advantages").length) {
    var slAdvantages = new Swiper(".sl-advantages", {
      freeMode: true,
      slidesPerView: "auto",
      watchOverflow: true,
      // on: {
      //   init: _calcHeightSlider

      //  },
    });
  }

  if ($(".sl-overview-shop").length) {
    slOverviewShop();
  }
  function slOverviewShop(){
    slOverviewShop = new Swiper(".sl-overview-shop", {
      freeMode: false,
      slidesPerView: 1,
      watchOverflow: true,
      spaceBetween: 24,
      navigation: {
        nextEl: ".sl-overview-shop + .wrap-nav .swiper-button-next",
        prevEl: ".sl-overview-shop + .wrap-nav .swiper-button-prev",
      },
      breakpoints: {
        440: {
          slidesPerView: 1,
          spaceBetween: 24,
        },
        765: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
        1100: {
          slidesPerView: 3,
          spaceBetween: 24,
        },

      }/*,
      /*on: {
        update: _calcHeightSlider,
      },*/
    });
    statusDestroy = false;
  }
  if ($(".sl-overview").length) {
    slOverview();
  }

  function slOverview() {
    var statusDestroy = false;
    var slOverview = undefined;

    function initSl() {
      slOverview = new Swiper(".sl-overview", {
        freeMode: false,
        slidesPerView: 1,
        watchOverflow: true,
        spaceBetween: 24,
        navigation: {
          nextEl: ".sl-overview + .wrap-nav .swiper-button-next",
          prevEl: ".sl-overview + .wrap-nav .swiper-button-prev",
        },
        breakpoints: {
          440: {
            slidesPerView: 2,
            spaceBetween: 24,
          },
          765: {
            slidesPerView: 3,
            spaceBetween: 24,
          },
          980: {
            slidesPerView: 4,
            spaceBetween: 24,
          },
        },
        on: {
          update: _calcHeightSlider,
        },
      });
      statusDestroy = false;
    }

    function destroySl() {
      slOverview.destroy();
      statusDestroy = true;
    }

    if ($(window).width() > 440) {
      initSl();
    }

    $(window).resize(function () {
      if ($(window).width() > 440 && statusDestroy) {
        initSl();
      } else if ($(window).width() <= 440 && !statusDestroy) {
        destroySl();
      }
    });
  }
  function slCatalog2(){
    logDebug('-------------------slCatalog2 2 new')
    var statusDestroyCat = true;
    var slCatalog2 = undefined;
    initSlCat();
    /*function destroySlCat() {
      logDebug('destroySlCat()')
      slCatalog2.destroy();
      statusDestroyCat = true;
    }
    if ($(window).width() > 440) {
      initSlCat();
    }
    $(window).resize(function () {
      logDebug('window resize')
      if ($(window).width() > 440 && statusDestroyCat) {
        initSlCat();
      } else if ($(window).width() <= 440 && !statusDestroyCat) {
        destroySlCat();
      }
    });*/
    function initSlCat() {
      logDebug('initSlCat()')
      slCatalog2 = new Swiper(".sl-catalog", {
        freeMode: true,
        slidesPerView: "auto",
        watchOverflow: true,
        spaceBetween: 12,
        breakpoints: {
          1024: {
            slidesPerView: 6,
            // spaceBetween: 24,
          },
        },
        pagination: {
          el: ".swiper-pagination",
          dynamicBullets: true,
        },/*
        on: {
          update: _calcHeightSlider,
        },*/
      });
      if ($(".shadow-white").length) {
        slCatalog2.on("slideChange", function () {
          if (slCatalog2.isEnd) {
            $(".shadow-white").removeClass("shadow-white_active");
          } else {
            $(".shadow-white").addClass("shadow-white_active");
          }
        });
      }
      statusDestroyCat = false;
    }
  }
  function initSelect(){
    // logDebug('initSelect');
    $('.js-show-shops').on('change', function (e){
      e.preventDefault();
      logDebug('js-show-shops change fired id is '+$(this).find('[selected]').data('id'));
      slShops.slideTo($(this).find('[selected]').data('id'))
    });
    $('.js-show-shops_mod').on('change', function (e){
      e.preventDefault();
      logDebug('js-show-shops change fired id is '+$(this).find('[selected]').data('href'));
      location.href=$(this).find('[selected]').data('href');
    });
    if(!$('.my-custom-select').length) return;
    var x, i, j, l, ll, selElmnt, a, b, c, img, imgText;
    x = document.getElementsByClassName("my-custom-select");
    l = x.length;
    for (i = 0; i < l; i++) {
      selElmnt = x[i].getElementsByTagName("select")[0];
      ll = selElmnt.length;
      /* For each element, create a new DIV that will act as the selected item: */
      a = document.createElement("DIV");
      a.setAttribute("class", "select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
      x[i].appendChild(a);
      /* For each element, create a new DIV that will contain the option list: */
      b = document.createElement("DIV");
      b.setAttribute("class", "select-items select-hide");

      for (j = 0; j < ll; j++) {
        // logDebug('j is '+j)
        /* For each option in the original select element,
        create a new DIV that will act as an option item: */
        c = document.createElement("DIV");
        c.setAttribute('data-id', j);
        img=$(selElmnt.options[j]).data('image');
        if(img) imgText='<img src="'+img+'">';
        else imgText='';

        c.innerHTML = imgText+selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
            /* When an item is clicked, update the original select box,
            and the selected item: */
            logDebug('event click fired')

            var $container = $(this).closest('.my-custom-select');
            var $select = $container.find('select');
            var html = $(this).html();

            $container.find('.select-selected').html(html);
            $select.find('[data-id="'+$(this).data('id')+'"]').click();
            $select.find('[selected]').removeAttr('selected', 'selected');
            $select.find('[data-id="'+$(this).data('id')+'"]').attr('selected', 'selected');
            // console.log($select.find('[data-id="'+$(this).data('id')+'"]').val());
            // console.log($(this).data('id'));
            $select.change();

        });
        b.appendChild(c);
      }
      x[i].appendChild(b);
      a.addEventListener("click", function(e) {
        /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
      });
    }
  }
  if($(".sl-catalog").length) slCatalog2()

  if ($(".sl-promotions").length) {
    var slPromotions = new Swiper(".sl-promotions", {
      freeMode: true,
      slidesPerView: "auto",
      watchOverflow: true,
      spaceBetween: 15,
      /*navigation: {
        nextEl: $(".sl-promotions .swiper-button-next"),
        prevEl: $(".sl-promotions .swiper-button-prev"),
      },*/
      breakpoints: {
        765: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
      },
      on: {
        update: _calcHeightSlider,
      },
    });
  }
  
  if ($(".sl-b-new").length) {
    logDebug('banners slider init');
    var swiperTime=1000*$(".sl-top-new").data('timer');
    if(!swiperTime) swiperTime=3000;
    var slB = new Swiper(".sl-b-new", {
      loop: true,
      // slidesPerView: 2,
      // spaceBetween: 33,
      pagination: {
        el: ".swiper-pagination",
      },
      autoplay: {
        delay: swiperTime,
        disableOnInteraction: false,
      },
      breakpoints: {
        1240: {
          slidesPerView: 2,
          spaceBetween: 33,
        },
        800: {
          slidesPerView: 2,
          spaceBetween: 8,
        },
        320: {
          slidesPerView: 2,
          spaceBetween: 8,
        },
      },
      /*navigation: {
        nextEl: $(".sl-reviews + .wrap-nav .swiper-button-next"),
        prevEl: $(".sl-reviews + .wrap-nav .swiper-button-prev"),
      },*/

    });
  }
  if ($(".sl-top-new").length) {
    var swiperTime=1000*$(".sl-top-new").data('timer');
    if(!swiperTime) swiperTime=3000;
    // if (isDebug) swiperTime = 600;
    var slTopNew = new Swiper(".sl-top-new", {
      slidesPerView: 1,
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true
      },
      autoplay: {
        delay: swiperTime,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  }
  if ($(".sl-reviews").length) {
    var swiperTime=1000*$(".sl-top").data('timer');
    if(!swiperTime) swiperTime=3000;
    var slReviwe = new Swiper(".sl-reviews", {
      watchOverflow: true,
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },
      autoplay: {
        delay: swiperTime,
        disableOnInteraction: false,
      },
      freeMode: false,
      /*navigation: {
        nextEl: $(".sl-reviews + .wrap-nav .swiper-button-next"),
        prevEl: $(".sl-reviews + .wrap-nav .swiper-button-prev"),
      },*/
      breakpoints: {
        1920: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
        1024: {
          slidesPerView: 2,
          spaceBetween: 24,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 12,
        },
        320: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
      },
      on: {
        update: _calcHeightSlider,
      },
    });
  }

  if ($(".sl-doc").length) {
    logDebug('line 2686')
    var slDoc = new Swiper(".sl-doc", {
      freeMode: true,
      slidesPerView: 'auto',
      spaceBetween: 50,
      watchOverflow: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        update: _calcHeightSlider,
      },
    });
  }

  if ($(".sl-top").length) {
    var swiperTime=1000*$(".sl-top").data('timer');
    if(!swiperTime) swiperTime=3000;
    var slTop = new Swiper(".sl-top", {
      freeMode: false,
      slidesPerView: 1,
      watchOverflow: true,
      spaceBetween: 0,
      loop: true,
      autoplay: {
        delay: swiperTime,
        disableOnInteraction: false,
      },
      navigation: {
        nextEl: $(".sl-top .swiper-button-next"),
        prevEl: $(".sl-top .swiper-button-prev"),
      },
      pagination: {
        el: ".sl-top .swiper-pagination",
        type: "bullets",
      },
      on: {
        update: _calcHeightSlider,
      },
    });
  }

  if ($(".sl-sidebar").length) {
    slSidebar();
  }
  function slSidebar() {
    var statusDestroy = true;
    var slSidebar = undefined;

    function initSl() {
      slSidebar = new Swiper(".sl-sidebar", {
        freeMode: true,
        slidesPerView: 1,
        watchOverflow: true,
        spaceBetween: 0,
        breakpoints: {
          980: {
            slidesPerView: 2,
            spaceBetween: 24,
          },
        },
        // on: {
        //   update: _calcHeightSlider
        //  },
      });
      statusDestroy = false;
    }

    function destroySl() {
      slSidebar.destroy();
      statusDestroy = true;
    }

    if ($(window).width() <= 765) {
      initSl();
    }

    $(window).resize(function () {
      if ($(window).width() <= 765 && statusDestroy) {
        initSl();
      } else if ($(window).width() > 765 && !statusDestroy) {
        destroySl();
      }
    });
  }

  if ($(".sl-products").length) {
    // slProduct();
  }

  function slProduct() {
    var statusDestroy = true;
    var sliderProduct = [];
    var elemSlider = document.querySelectorAll(".sl-products");
    function initSl() {
      for (var i = 0; i < elemSlider.length; i++) {
        sliderProduct[i] = new Swiper(elemSlider[i], {
          freeMode: true,
          slidesPerView: "auto",
          watchOverflow: true,
          spaceBetween: 24,
          // navigation: {
          //   nextEl: elem.querySelector(".swiper-button-next"),
          //   prevEl: elem.querySelector(".swiper-button-prev"),
          // },
          // pagination: {
          //   el: elem.querySelector(".swiper-pagination"),
          //   type: "bullets",
          //   clickable: true,
          // },
          on: {
            init: _calcHeightSlider,
          },
        });

      }
      statusDestroy = false;
    }

    $('.products-tab__title').click(function() {
      var index = $(this).index()
      setTimeout(function() {
        sliderProduct[index].update()
      }, 200)

          })


    function destroySl() {
      for (var i = 0; i < sliderProduct.length; i++) {
        sliderProduct[i].destroy();
        statusDestroy = true;
      }
    }

    if ($(window).width() > 440) {
      initSl();
    }

    $(window).resize(function () {
      if ($(window).width() <= 440 && !statusDestroy) {
        destroySl();
      } else if ($(window).width() > 440 && statusDestroy) {
        initSl();
      }
    });

  }


if( $('.gallery-thumbs').length) {
  doubleSl();
}

  function doubleSl() {
    galleryThumbs = new Swiper(".gallery-thumbs", {
      freeMode: false,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,/*
      autoplay: {
        delay: 3000,
        // disableOnInteraction: false,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },*/
      loop: true,
      breakpoints: {
        560: {
          direction: "vertical",
          slidesPerView: 3,
        },
        320: {
          direction: "horizontal",
          slidesPerView: 4,
        }
      }
    });
    // logDebug('line 2861-4');
    if ($('.gallery-top .swiper-slide').length > 1)
      galleryTop = new Swiper(".gallery-top", {
        // effect: "fade",
        slidesPerView: 1,
        autoplay: {
          delay: 3000,
          // disableOnInteraction: false,
          disableOnInteraction: false,
          pauseOnMouseEnter: true
        },
        loop: true,
        fadeEffect: {
          crossFade: true,
        },
        navigation: {
          nextEl: ".wrap-double-slider .swiper-button-next",
          prevEl: ".wrap-double-slider .swiper-button-prev",
        },
        thumbs: {
          swiper: galleryThumbs,
        },
        on: {
          slideChange: function(swiper){
            var $slide = $(swiper.slides[swiper.activeIndex]);
            // logDebug('slide change ' + swiper.realIndex + ' | ' + swiper.activeIndex)
            if ($('#slider-video').length){

              if($slide.find('video').length){
                // logDebug('video play');
                $('#slider-video').get(0).play();
              }
              else {
                // logDebug('video - pause');
                $('#slider-video').get(0).pause();
              }
            }
          }
        }
      });
  }

  function slPopupCart() {
  if ($(".sl-popup").length) {
    var slPopup = new Swiper(".sl-popup", {
      freeMode: true,
      slidesPerView: "auto",
      watchOverflow: true,
      spaceBetween: 0,

      navigation: {
        nextEl: $(".sl-popup .swiper-button-next"),
        prevEl: $(".sl-popup .swiper-button-prev"),
      },
      on: {
        update: _calcHeightSlider,
      },
    });
    if ($(".shadow-white").length) {
      slPopup.on("slideChange", function () {
        if (slPopup.isEnd) {
          $(".shadow-white").removeClass("shadow-white_active");
        } else {
          $(".shadow-white").addClass("shadow-white_active");
        }
      });
    }
  }
  }

  if($('.custom-select').length) {
    logDebug('custom select init 3')
    $( ".custom-select" ).selectmenu({
      change: function( event, ui ) {
        console.log({event: event.target/*, ui:ui*/})
        $(event.target).change();
      }
    })
  }


  // tabs
  tabs();
  function tabs() {
    $(".tab__title").click(function () {
      var isProdPage=$('body').hasClass('catalog-cart');
      var par = $(this).closest(".tabs");
      var index = $(this).index();

      if(!isProdPage){
        $(".tab__title").removeClass("active");
        $(this).addClass("active");

        par.find(".tab__content").removeClass("active");
        par.find(".tab__content").eq(index).addClass("active");
        showText()
      }
      else{
        var isActive=false;
        if($(this).hasClass('active')) isActive=true;
        $('.js-mobile-content').html('');

        logDebug('prodPage');

        par.find(".tab__title").removeClass("active");

        if(!isActive){
          $(this).addClass("active");
          $(this).next().html(par.find(".tab__content").eq(index).html());
          $('html,body').animate({scrollTop: $(this).offset().top-40});
          $(this).next().find('#write-review').attr('id', 'write-review-mobile');
          $(this).next().find('[href="#write-review"]').attr('href', '#write-review-mobile');

          par.find(".tab__content").removeClass("active");
          par.find(".tab__content").eq(index).addClass("active");
          showText()
        }
      }

    });
  }
  /*function tabs() {
    $(".tab__title").click(function (e) {

    par.find(".tab__title").removeClass("active");

      var isActive=false;
      if($(this).hasClass('active')) isActive=true;
      $('.js-mobile-content').html('');

      if(!isActive){
        $(this).addClass("active");
        $(this).next().html(par.find(".tab__content").eq(index).html());
        $('html,body').animate({scrollTop: $(this).offset().top-40});
        $(this).next().find('#write-review').attr('id', 'write-review-mobile');
        $(this).next().find('[href="#write-review"]').attr('href', '#write-review-mobile');

        par.find(".tab__content").removeClass("active");
        par.find(".tab__content").eq(index).addClass("active");
        showText()
      }

    });
  }*/
  // выравнивание высот слайдов

  function _calcHeightSlider() {
    var that = this;
    setTimeout(function() {
      var slides = that.slides.find(".sl-item");
      var maxHeight = 0;
      slides.each(function (slide) {
        $(this).height("auto");
      });
      slides.each(function (slide) {
        if ($(this).innerHeight() > maxHeight) {
          maxHeight = $(this).height();
        }
      });
      slides.each(function (slide) {
        $(this).height(maxHeight);
      });
    }, 300)

  }

  // отображение звезд рейтинга на карточке товара
  rating();
  function rating() {
    if ($("[rating]").length) {
      $("[rating]").each(function () {
        var fullNumber = Math.floor($(this).attr("rating"));
        for (var i = 0; i < fullNumber; i++) {
          $(this).find(".rating-star").eq(i).addClass("active");
        }
      });
    }
    $('.put-rating .rating-star').click(function() {
      var par = $(this).closest('.put-rating')
      var index = $(this).index()
      par.find('.rating-star').removeClass('active')
      for (var i = 0; i <= index; i++) {
        par.find('.rating-star').eq(i).addClass('active')
      }
      $('.input-rating').val(index + 1)
    })
  }

  mobileNav();
  function mobileNav() {
    $(".header-nav-but").click(function () {
      $("body").toggleClass("-isNav");
      $(".btn-menu").toggleClass("active");

      // var div = document.createElement('div');
      // div.style.overflowY = 'scroll';
      // div.style.width =  '50px';
      // div.style.height = '50px';
      // div.style.visibility = 'hidden';
      // document.body.appendChild(div);
      // var scrollWidth = div.offsetWidth - div.clientWidth;
      // document.body.removeChild(div);
      $("body").toggleClass("no-scroll");
      // if($('body').hasClass('no-scroll')) {
      //   $('body').removeChild
      // }
    });
    $(".mob-nav [data-href]").on("click", function (e) {
      var self = $(this);
      var idItem = self.data("href");
      $(idItem).toggleClass("-isUp");
      e.preventDefault();
    });
    $(".mob-nav .-back").on("click", function () {
      $(this).closest(".mob-nav-pop").removeClass("-isUp");
    });
  }

  $(".btn-footer-menu").click(function () {
    $(this).toggleClass("active");
    $(this).closest(".footer-h").next().toggle();
  });

  // open filter articles
  $(".filter-article__title").click(function () {
    $(this).toggleClass("active");
    $(this).next().slideToggle(0);
    $(this).next().toggleClass('active')
  });

  //color label for active check
  $(".custom-check_color .check-check_input").click(function () {
    $(this).closest(".custom-check_color").toggleClass("active");
  });

  // switch .form-group
  $(".form-group__title").click(function () {
    $(this).toggleClass("active");
    $(this).closest(".accordion").find(".form-group__content").slideToggle();
  });

  range();
  function range() {
    $(".range").each(function () {
      var self = $(this);
      var minInp = self.find(".minCost");
      var maxInp = self.find(".maxCost");
      var slider = self.find(".range-ui");
      var minInitVal = self.data("min");
      var maxInitVal = self.data("max");

      slider.slider({
        min: parseInt(minInitVal),
        max: parseInt(maxInitVal),
        values: [self.data("initminval"), self.data("initmaxval")],
        animate: 400,
        range: true,
        step: parseInt(self.data("step")),
        stop: function (event, ui) {
          var minVal = slider.slider("values", 0);
          var maxVal = slider.slider("values", 1);
          // Если нужно на разряды разбивать
          if (self.data("dimension")) {
            minVal = String(minVal).replace(
              /(\d)(?=(\d\d\d)+([^\d]|$))/g,
              "$1 "
            );
            maxVal = String(maxVal).replace(
              /(\d)(?=(\d\d\d)+([^\d]|$))/g,
              "$1 "
            );
          }
          // Если число с точкой
          if (self.data("fraction")) {
            minVal = minVal / self.data("fraction");
            maxVal = maxVal / self.data("fraction");
          }
          minInp.val(minVal);
          maxInp.val(maxVal);
        },
        slide: function (event, ui) {
          // logDebug('range line 1538')
          var minVal = slider.slider("values", 0);
          var maxVal = slider.slider("values", 1);
          if (self.data("dimension")) {
            minVal = String(minVal).replace(
              /(\d)(?=(\d\d\d)+([^\d]|$))/g,
              "$1 "
            );
            maxVal = String(maxVal).replace(
              /(\d)(?=(\d\d\d)+([^\d]|$))/g,
              "$1 "
            );
          }
          if (self.data("fraction")) {
            minVal = minVal / self.data("fraction");
            maxVal = maxVal / self.data("fraction");
          }
          minInp.val(minVal);
          maxInp.val(maxVal);
          minInp.change();
          // logDebug('minInp.change()');
        },
      });

      minInp.on("change", function () {
        var value1 = minInp.val().replace(" ", "");
        var value2 = maxInp.val().replace(" ", "");
        // Если значение с точкой
        if (self.data("fraction")) {
          value1 = value1 * self.data("fraction");
          value2 = value2 * self.data("fraction");
          if (parseInt(value1) > parseInt(value2)) {
            value1 = value2;
            slider.slider("values", 0, value1);
            minInp.val(value1 / self.data("fraction"));
            return false;
          }
          slider.slider("values", 0, value1);
        } else {
          // Нормальное условие
          if (parseInt(value1) > parseInt(value2)) {
            value1 = value2;
            minInp.val(value1);
          }
          slider.slider("values", 0, value1);
        }
      });

      maxInp.on("change", function () {
        var value1 = minInp.val().replace(" ", "");
        var value2 = maxInp.val().replace(" ", "");
        if (self.data("fraction")) {
          value1 = value1 * self.data("fraction");
          value2 = value2 * self.data("fraction");
          if (value2 > maxInitVal) {
            value2 = maxInitVal;
            slider.slider("values", 1, value2);
            maxInp.val(maxInitVal / self.data("fraction"));
            return false;
          }
          if (parseInt(value1) > parseInt(value2)) {
            value2 = value1;
            maxInp.val(value2 / self.data("fraction"));
            slider.slider("values", 1, value2);
          }
          slider.slider("values", 1, value2);
        } else {
          if (value2 > maxInitVal) {
            value2 = maxInitVal;
            maxInp.val(maxInitVal);
          }
          if (parseInt(value1) > parseInt(value2)) {
            value2 = value1;
            maxInp.val(value2);
          }
          slider.slider("values", 1, value2);
        }
      });

      // Для разбивания на разряды после потери фокуса

      if (self.data("dimension")) {
        maxInp.on("blur", function () {
          var self = $(this);
          self.val(
            String(self.val()).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 ")
          );
        });
        minInp.on("blur", function () {
          var self = $(this);
          self.val(
            String(self.val()).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 ")
          );
        });
      }

      // Запрет ввода символов
      $(
        ".sidebar-f-range-services .minCost, .sidebar-f-range-services .maxCost"
      ).keypress(function (event) {
        var key, keyChar;
        if (!event) var event = window.event;
        if (event.keyCode) key = event.keyCode;
        else if (event.which) key = event.which;
        if (
          key == null ||
          key == 0 ||
          key == 8 ||
          key == 13 ||
          key == 9 ||
          key == 46 ||
          key == 37 ||
          key == 39
        )
          return true;
        keyChar = String.fromCharCode(key);
        if (!/\d/.test(keyChar)) return false;
      });
    });
  }

  slideRange()
  function slideRange() {
    // logDebug('1760 slider init')
    $( ".slider-range-max" ).each(function() {
      var maxCount = parseInt($(this).attr('max'))
      var input = $(this).closest('.wrap-slider-range').find('.slide-range-input')
      input.next('.max-count-range').find('span').text(maxCount)

      sl = $(this).slider({
        range: "min",
        min: 0,
        max: maxCount,
        value: 0,
        create: function( event, ui ) {
        },
        slide: function( event, ui ) {
          input.val( ui.value );
          calcAllPrice();
        }
    });

    input.val($(this).slider( "value" ) );

    input.on('input', function() {
      var value = parseInt($(this).val())
      if(value > maxCount) {
        value = maxCount;
        $(this).val(value);
      }
      if(value <= maxCount) {
        sl.slider( "option", "value", value )
        calcAllPrice();
      }

    })


    // Запрет ввода символов
    input.on('keypress', function (event) {
      var key, keyChar;
      if (!event) var event = window.event;
      if (event.keyCode) key = event.keyCode;
      else if (event.which) key = event.which;
      if (
        key == null ||
        key == 0 ||
        key == 8 ||
        key == 13 ||
        key == 9 ||
        key == 46 ||
        key == 37 ||
        key == 39
      )
        return true;
      keyChar = String.fromCharCode(key);
      if (!/\d/.test(keyChar)) return false;
    });

    })
  }

  function resizeInput() {
    var sizeInput = 1;
    if ($(this).val().length - 1 !== 0) {
      sizeInput = $(this).val().length;
    }
    if($(this).val().length == 0) {
      sizeInput = 1
    }
    $(this).attr("size", sizeInput);

  }

  $(".custom-range__input")
    // event handler
    .keyup(resizeInput)
    // resize on page load
    .each(resizeInput);

  //position catalog filter
  $(window).resize(posFilterCatalog);
  posFilterCatalog();
  function posFilterCatalog() {
    if (
      $(window).width() <= 765 &&
      $(".block-content_catalog > .sidebar-filter").length
    ) {
      $("footer").after($(".block-content_catalog > .sidebar-filter").detach());
    } else if ($(window).width() > 765 && $("body > .sidebar-filter").length) {
      $(".wrap-catalog-result").before($("body > .sidebar-filter").detach());
    }
  }
  filterCatalogMob();
  function filterCatalogMob() {
    $(".sidebar-mob-but").click(function () {
      $(".sidebar-filter").addClass("active");
      $("body, html").addClass("no-scroll");
    });
  }
  $(".sidebar-filter .btn-close").click(function () {
    $(".sidebar-filter").removeClass("active");
    $("body, html").removeClass("no-scroll");
  });

  // добавление класс .active кнопке корзины и избранное в карточке товара

  // $(".product-item  .product-item__cart, .product-item .btn-chosen").click(
  //   function () {
  //     $(this).toggleClass("active");
  //   }
  // );

  // sticky sidebar catalog-cart
  if($('.product-sidebar').length && $(window).width() > 1100) {
    stickySidebarCatalogCart()
  }
  function stickySidebarCatalogCart() {
    var a = document.querySelector(".product-sidebar"),
      b = null,
      P = 0; // если ноль заменить на число, то блок будет прилипать до того, как верхний край окна браузера дойдёт до верхнего края элемента. Может быть отрицательным числом
    window.addEventListener("scroll", Ascroll, false);
    document.body.addEventListener("scroll", Ascroll, false);
    function Ascroll() {
      if (b == null) {
        var Sa = getComputedStyle(a, ""),
          s = "";
        for (var i = 0; i < Sa.length; i++) {
          if (
            Sa[i].indexOf("overflow") == 0 ||
            Sa[i].indexOf("padding") == 0 ||
            Sa[i].indexOf("border") == 0 ||
            Sa[i].indexOf("outline") == 0 ||
            Sa[i].indexOf("box-shadow") == 0 ||
            Sa[i].indexOf("background") == 0
          ) {
            s += Sa[i] + ": " + Sa.getPropertyValue(Sa[i]) + "; ";
          }
        }
        b = document.createElement("div");
        b.style.cssText =
          s + " box-sizing: border-box; width: " + a.offsetWidth + "px;";
        a.insertBefore(b, a.firstChild);
        var l = a.childNodes.length;
        for (var i = 1; i < l; i++) {
          b.appendChild(a.childNodes[1]);
        }
        a.style.height = b.getBoundingClientRect().height + "px";
        a.style.padding = "0";
        a.style.border = "0";
      }
      var Ra = a.getBoundingClientRect(),
        R = Math.round(
          Ra.top +
            b.getBoundingClientRect().height -
            document
              .querySelector(".wrap-block-features + .wrap-block")
              .getBoundingClientRect().top +
            64
        ); // селектор блока, при достижении верхнего края которого нужно открепить прилипающий элемент;  Math.round() только для IE; если ноль заменить на число, то блок будет прилипать до того, как нижний край элемента дойдёт до футера
      if (Ra.top - P <= 0) {
        if (Ra.top - P <= R) {
          b.className = "stop";
          b.style.top = -R + "px";
        } else {
          b.className = "sticky";
          b.style.top = P + "px";
        }
      } else {
        b.className = "";
        b.style.top = "";
      }
      window.addEventListener(
        "resize",
        function () {
          a.children[0].style.width = getComputedStyle(a, "").width;
        },
        false
      );
    }
  }

  //btn .btn-show-more
  btnShowMore()
  function btnShowMore() {
    $('body').on('click', '.btn-show-more', function () {
      var text = $(this).find('span')
      var textM = $(this).attr('text-main');
      var textR = $(this).attr('text-replace')
      var textNow = $(this).find('span').text()
      if(textNow == textM) {
        text.text(textR)
      } else {
        text.text(textM)
      }

      $(this).toggleClass('active')

      $(this).prev('.review-item__content').toggleClass('show')
    })
  }

  // replace hide text
  if($('.features-tab__content').eq(3).hasClass('active')) {
    showText()
  }

  function showText() {
    $('.review-item__content').each(function() {
      if((parseInt($(this).css('line-height'), 10) * 4) < $(this).height() && !$(this).hasClass('old-item')) {
        $(this).css('max-height', parseInt($(this).css('line-height'), 10) * 4)
        $(this).next('.btn-show-more').css('display', 'flex')
      }
      $(this).addClass('old-item')
    })
  }

  // запуск видео по кнопке
  $(".btn-paly").click(function () {
    if ($(this).siblings(".video").get(0).paused) {
      $(this).siblings(".video").get(0).play();
      $(this).fadeOut();
      $('.video-info').fadeOut();
      $(this).siblings(".video").attr('controls', 'controls')
    }
    //  else {
    //   $(this).children(".video").get(0).pause();
    //   $(this).children(".btn-paly").fadeIn();
    // }
  });

// magnificPopup
  $('.js-image-popup').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom',
		image: {
			verticalFit: true
		},

	});
  var mfpInstance;
  // logDebug('gallery init')
  if($('.gallery-element').length){
    // logDebug('gallery init fired')

    mfpInstance=$('#gallery-container').magnificPopup({
      type: 'image',
      image: {
        verticalFit: true
      },
      closeMarkup: '<div class="mfp-close">&nbsp;</div>',
      mainClass: 'mfp-fade',
      navigateByImgClick: true,
      delegate: 'a',
      gallery: {
        enabled:true
      }
    })
    $('.js-image-popup-gallery').on('click', function (e){

      e.preventDefault()
      // logDebug('open mfp gallery' + $(this).data('index'));
      mfpInstance.magnificPopup('open').magnificPopup('goTo', $(this).data('index'))
    })
  }

// акордеон
  $(".accordion__title").click(function () {
    $(this).toggleClass("active");
    $(this).closest(".accordion__item").find(".accordion__content").slideToggle();
  });

  $('.inlinePopupJS').magnificPopup({
    type: 'inline',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    fixedContentPos: true,
    closeMarkup: '<div class="mfp-close js-magnific-close"><svg><use xlink:href="#closesIcon" /></svg></div>',
    callbacks: {
      close: function () {
        $('.video-popup-wrapper video').each(
          function (i, item) { 
            item.pause()
          }
        )
      },
      open: function() {
        logDebug('mfp open')
        if ($('.mfp-inline-holder video').length) $('.mfp-inline-holder video').get(0).play();
      }
    }
  });

  showCount();
  function showCount() {
    $(".show-block span").text($(".block-number .active").text());
    $(".show-block").click(function() {
      $(this).toggleClass('active')
      $(".block-number").toggleClass("active");
    });
  }

  $('.btn-bonus-js').click(function(e) {
    e.preventDefault()
    $(this).hide()
    $('.wrap-activation-bonuses').show()
  })

  $('.btn-show-pass').click(function() {
    var type = $(this).parent().find('input').prop('type');
    if(type == 'password') {
      $(this).parent().find('input').prop('type', 'text');
      $(this).addClass('active')
    } else {
      $(this).parent().find('input').prop('type', 'password');
      $(this).removeClass('active')
    }
  })

  $('.js-popup-form').magnificPopup({
		type: 'inline',
		preloader: false,
    callbacks: {
      open: function() {
        slPopupCart()
      },
    }

	});

  setInputNumber()
  function setInputNumber() {
    $(".number-plus:not(.cart_b)").click(function() {
      var input = $(this)
        .closest(".field-number")
        .children("input");
      if (input.attr('disabled') == 'disabled'){//Запрещен
        logDebug('adding disabled by price')
        return;
      }
      // logDebug(input.attr('disabled'))
      var val = input.val();
      // var val = input.attr("value");
      val++;
      input.val(val);
      input.change();
    });
    $(".number-min:not(.cart_b)").click(function() {
      var input = $(this)
        .closest(".field-number")
        .children("input");
      var val = input.val();
      if (val > 1) {
        val--;
        input.val(val);
        input.change();
      }
    });
  }

  $('.payment-item').click(function() {
    var box = $(this).closest('.block-payment')
    box.find('.payment-item').removeClass('active')
    $(this).addClass('active')
  })

  $('.btn-add-comment').click(function() {
    $(this).hide()
    $(this).closest('.field-cooment').removeClass('hide-textarea')
  })


  map()


  function map() {
    if($('#mapShop').length) {
      var lat = $('#mapShop').data('lat');
      var lng = $('#mapShop').data('lon');
      // var lat = 55.888230
      // var lng = 37.438502
      logDebug('line 2047');

      var internalScript = document.createElement('script');
      internalScript.src = "https://api-maps.yandex.ru/2.1.75/?lang=ru_RU";
      document.getElementsByTagName('head')[0].appendChild(internalScript);
      internalScript.onload = function() {
        var mainCoord = [
          [
            {
              lat: lat,
              lng: lng
            },
          ]
        ]
        function ymapShow(selector){
          var init = function() {
            var myMap;
            var yaMap = function() {
              myMap = new ymaps.Map(selector, {
                center: [lat, lng],
                //controls: [],
                zoom: 12.56,
              });
              var placemarkOptions;
              if (window.screen.width<768) placemarkOptions={
                 balloonContent: '<p style="text-align: center;"><a href="yandexnavi://build_route_on_map?lat_to='+lat+'&lon_to='+lng+'" target="_blank">Ехать с Яндекс.Навигатор</a></p>',
              };
              var myPlacemark = new ymaps.Placemark([lat, lng],
                placemarkOptions,
                {
                  iconLayout: 'default#image',
                  iconImageHref: '/frontend/images/favicon.ico',
                  iconImageSize: [32, 32],
                  iconImageOffset: [-15, -30]
                });
              myMap.geoObjects.add(myPlacemark);
            }
            yaMap();
          };
          ymaps.ready(init);
        }
        ymapShow('mapShop');
      };
    }

  }

  function initPostWidget(){
    if (!$('#ecom-widget-d').length) return;
    ecomStartWidget({
      id: russianPostId,
      callbackFunction: callbackRussianPost,
      containerId: 'ecom-widget-d'
    });
  }

  (function() {
    $('.product-item').hover(function(e) { return false;}, function() {
      $(this).find('.product-item__img img').attr('src', $(this).find('.product-item__img img').attr('original-img'));
    })
    // $('.product-item__sub-img').hover(function(e) { return false;}, function() {
    //   $(this).closest('.product-item').find('.product-item__img img').attr('src', $(this).closest('.product-item').find('.product-item__img img').attr('original-img'));
    // })
    $('.product-item__sub-img .-item').hover(function(e) {
      var container = $(this).closest('.product-item');
      container.find('.product-item__img img').attr('src', $(this).attr('full-img'));
    })
  })()

});