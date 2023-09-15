define([
  'jquery',
  'jquery/swiper.min',
  'jquery/range',
  'jquery/countdown',
  'jquery/jquery.magnific-popup.min',
  'jquery/jquery.easing.compatibility',
  'jquery/jquery.easing.min',
  'jquery/suggestions.min',
  'jquery/jquery.maskedinput.min'
], function($, Swiper, countdown) {

  "use strict";

  $(function() {
    actionTime();
    back();
    popup();
    mobileNav();
    choseRating();
    hoverTopNav();
    range();
    toggleNav();
    filtrSidebar();
    catMobFiltr();
    scrollProgBonus();
    allBut();
    downLink();
    productCard();
    sliders();
    basket();
    sidebarFiltr();
    sidebarMobBut();
    initEvents();
    initDiscount60();
    initMapWidget();
    initMap();
    initAllTestsSlider();
    initCountDown();
    initDadata();
    btnScrollTop();
  });
  var isDebug=false;
  if (window.location.origin.indexOf('://dev.') + 1) isDebug=true;

  function btnScrollTop() {
    $('<style>'+
     '.scrollTop{ display:none; z-index:9999; position:fixed;'+
     'bottom:40px; left:auto; right: 0; width:60px; height:55px;'+
     'background:url(/images/up.svg) 0 0 no-repeat; }' +
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

  var myMap=false;

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
          if(isDebug) console.log('-------------------------------------------city select')
          if(isDebug) console.log('region_fias_id|' + suggestion.data.region_fias_id);
          // console.log('fias_id|' + suggestion.data.fias_id);
          $('#region-fias-id').val(suggestion.data.region_fias_id);
          $('.js-payment').change();
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
        constraints: $street
      });

    }
  }

  function initMapWidget(){
    if(!$('.delivery .dlh-map').length) return ;
    // console.log('init map dlh')
    var mapWidget = new MapWidget(147, "map", function() {
  		alert('Точная стоимость доставки будет рассчитана в корзине')
  	}, {
  		'size': 'xs'
  	});
  	// запустить инициализацию виджета
  	mapWidget.init();
  }

  function initMap(){
    if(!$('#YMapsID').length) return;

    ymaps.ready(showByCity);
    $('#cities-list').on('change',  showByCity );

  }

  function showByCity( ){
    // console.log('showByCity')
    var $cityId=$('#cities-list').val();
    if (!myMap) myMap = new ymaps.Map('YMapsID', { center: [55.75222, 37.61556], zoom: 9 });
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
    /*$('.city-'+$cityId).each(function( index, value ){

      myMap.geoObjects.add(
        new ymaps.Placemark(
          [$(value).data('lat'), $(value).data('lon')],
          { balloonContent: '<h3>'+$(value).data('name')+'</h3>'+
          '<div class="address">'+
          ($(value).data('metro') ? '<p>'+$(value).data('metro') +'</p>' : '')+
          '<p>'+$(value).data('address')+'</p>'+
          ($(value).data('pay') ? '<p><b>Способы оплаты:</b> '+$(value).data('pay') +'</p>' : '')+
          '<p><b>Время работы: </b><br />'+$(value).data('worktime')+'<br /></p>'+
          '</div>'
        },
        {
          iconLayout: 'default#image',
          iconImageHref: $(value).data('image'),
          iconImageSize: $(value).data('imagesize'),
          iconImageOffset: [-3, -42]
        }
      )
    );

  });*/
}

  function initCountDown(){
    // console.log('init')
    $('.js-countdown').each(function (index, value){
      // console.log('init each')
      $(value).removeClass('js-countdown');
      // console.log($(value).data('until'))
      $(value).countdown($(value).data('until'))
        .on('update.countdown', function (event){
          $(value).text(event.strftime('%H:%M:%S'))
        });
    });
  }

  function initDiscount60(){
    if (!$('.js-cat-filter').length) return;
    $('.js-cat-filter').on('click', function(){
      var hasClass=$(this).hasClass('active');
      $('.js-cat-filter').removeClass('active');
      if(!hasClass)
        $(this).addClass('active');
      applyDiscount60Filter();
    });
    $('.discount-60-form').on('submit', applyDiscount60Filter);
  }

  function applyDiscount60Filter(){
    var filterCat=[];
    $('.cat.active').each( function (index, value){
      filterCat[index]=$(value).data('cat');
    });
    var newHref=location.protocol+'//'+location.hostname+location.pathname;
    newHref+='?page='+$('#form-filter-page').val();
    newHref+='&filter_cat='+filterCat.join('|');
    if ($('.discount-60-form .max_price').val()) newHref+='&max_price='+$('.discount-60-form .max_price').val();
    if ($('.discount-60-form .min_price').val()) newHref+='&min_price='+$('.discount-60-form .min_price').val();
    // console.log(newHref);
    location.href=newHref;
    return false;
  }

  function showTopCart(){//Показывает корзину вверху страницы
    $.ajax({
      type: 'post',
      url: '/carttop',
      success: function (resp){
        $('#header-basket').html(resp);
      }
    })
  }

  function initEvents(){//Общие события на кнопки
    if ($('.js-need-to-blur').length) $('body').addClass('blured');//Если надо заблюрить изображение
    if ($('.js-instant-redirect').length) window.location.href=$('.js-instant-redirect').attr('href');//Если мы на странице "Спасибо за заказ" - сразу переходим к оплате
    $('.js-radio').on('change', function (e){
      e.preventDefault();
      console.log('js-radio change')
      var state=$(this).prop('checked');
      $('.js-radio').prop('checked', false);
      if(state) {
        console.log('js-radio change to checked')
        $(this).prop('checked', true);
      }
    });
      // if() $('.js-quiz-answer').on('change', function(e){}
    if($('.js-quiz-next').length){//Если будет колесо quiz_id
      $('.js-quiz-prev').on('click', function(e){
        // console.log('js-quiz-prev fired');
        $(this).closest('.quiz-block').removeClass('active');
        $(this).closest('.quiz-block').prev().addClass('active');
      });
      $('.js-quiz-next').on('click', function(e){
        e.preventDefault();
        if($(this).hasClass('disabled')) return;
        // $(this).remove();
        if($(this).data('id')) {
          $('#'+$(this).data('id')).addClass('active');
          $(this).addClass('mfp-hide');
          $(this).closest('.test-detail').addClass('mfp-hide');
          // $(this).closest('.test-detail').find('.test-detail-left').addClass('mfp-hide');
        }
        else{
          $(this).closest('.quiz-block').removeClass('active');
          $(this).closest('.quiz-block').next().addClass('active');
        }
      })

      $('.js-quiz-answer').on('change', function(e){
        e.preventDefault();
        $(this).closest('.question-container').addClass('show-answer');
        $(this).closest('.question-container').find('.answer-wrapper').removeClass('active');
        $(this).closest('.answer-wrapper').addClass('active');
        $(this).closest('.quiz-block').find('.js-quiz-next').removeClass('disabled');
        // $('.quiz-block').removeClass('active');
        // $(this).closest('.quiz-block').next().addClass('active');
      });
    }
    if($('.wheel-wrapper').length){//Если будет колесо фортуны
      /*$('.js-wheel-button').on('click', function(){
        // console.log('----------------------------------- spin -----------------------------------');
        // console.log($(this).closest('form').find('input').val());
        // console.log('rotate('++'deg)');
        var angle=360+parseInt($(this).closest('form').find('input').val());
        // console.log('rotate('+angle+'deg)');
        $('.wheel-body').css('transform', 'rotate('+angle+'deg)');
      });*/
      $('.js-wheel-close').on('click', function(){
        $('.wheel-wrapper').toggleClass('active');
      });
      $('.js-hide-wheel').on('focus', function(){
        $('.wheel-container').addClass('hide');
      });
      $('.js-hide-wheel').on('blur', function(){
        $('.wheel-container').removeClass('hide');
      });
    }
    if($('#get-300').length){//Если будет всплываха 300 бонусов в подарок
      $(document).on('click', '.js-300-close', function(e){
        e.preventDefault();
        $('#get-300').remove();
        $.cookie('no-show-300', true, { expires: 7, path: '/' })
      })
      $(document).on('mouseleave', function (){
        // console.log('document on mouseleave------------------------');
        if($('.wheel-wrapper').hasClass('active')) return;//Не показываем, если клиент играет в колесо фортуны
        if($('#age-18').length) return;//Не показываем, если клиент не подтвердил возраст
        $('#get-300').removeClass('mfp-hide');
      })
      /*
      setTimeout(function(){
        if($('.wheel-wrapper').hasClass('active')) return;//Не показываем, если клиент играет в колесо фортуны
        if($('#age-18').length) return;//Не показываем, если клиент не подтвердил возраст
        $('#get-300').removeClass('mfp-hide');
      }, 7000)*/
    }
    $('.js-phone-mask').mask('+7(999)999-99-99');

    $(document).on('blur', '.cq-popup__input', function(){//Отправляем в RR
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      console.log('try to send RR')
      if (regex.test(this.value)) {
          try {
              rrApi.setEmail(this.value);
              sendRRWelcome(this.value);
          } catch (e) {
          }
      }
    });
    // console.log('common line 344')
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

    $(document).on('click', '.js-novice-filter', function(){//Фильтр для новичков
      // console.log('------------------------novice-click v2');
      var thisState=$(this).hasClass('active');
      $('.js-novice-filter').removeClass('active');
      if(thisState){
        // console.log('------------------------novice-click remove');
        $('#form-filter-is-novice').val('');
      }
      else{
        // console.log('------------------------novice-click add');
        $(this).addClass('active');
        $('#form-filter-is-novice').val($(this).data('value'));
      }
      $('#form-filter-page').val(1);
      $(".js-filters-form").submit();
    });

    $(document).on('click', '.js-magnific-close', function (e){//Закрытие магнифика
      e.preventDefault();
      $.magnificPopup.close();
    });

    $(document).on('click', '.js-basket-add', function (e){//Добавление в корзину
      e.preventDefault();
      e.stopPropagation();
      if($(this).hasClass('disabled')) return;
      var id=$(this).data('id');
      // console.log('add to basket click');
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

    $('.js-art-show').on('click', function (e){//Меняет артикул на id и обратно
      e.preventDefault();
      var text=$(this).data('content');
      $(this).data('content', $(this).text());
      $(this).text(text);
    });

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
      // console.log('apply-filters');
      // document.location = $(this).closest('form').attr('action')+'?sortOrder='+$(this).data('sort')+'&direction='+direction;
    });

    $('.js-pag-catalog a').on('click', function(e){//Пагинация в каталоге
      e.preventDefault();
      e.stopPropagation();
      var $form=$('.js-filters-form');
      var $this=$(this);
      // console.log('we need to go at page ' + $this.data('page'));
      if($this.hasClass('-noActive') || $this.hasClass('active') || !$this.data('page')){//если мы уже на нужной странице или страница ссылка неактивна
        // console.log('нам некуда идти');
        return false;
      }
      var page=$this.data('page');
      $form.attr('action', window.location.pathname+'?page='+page);
      $form.find('#form-filter-page').val(page); //Указываем нужную страницу и отправляем форму
      $form.submit();
    });
    $('.js-filters-form').on('submit', function(e){//поскольку пагинация через get
      //предполагает передачу параметра в урле придется мутить обработку сброса страницы при отправке непосредственно из формы
      //В случае когда мы пришли не из пагинации - у формы пустой action.
      //чтобы перейти на первую страницу фильтрации - заполняем action без get-параметра page
      if(!$(this).attr('action')) $(this).attr('action', window.location.pathname);
    });

    $('.js-show-more-catalog').on('click', function(e){//Показать еще в каталоге
      e.preventDefault();
      e.stopPropagation();
      var $form=$('.js-filters-form');
      var $this=$(this);
      if(!$form.length) {
        // console.log('filters not fond');
        return false;
      }
      $form.find('#form-filter-page').val(1+1*$form.find('#form-filter-page').val()); //Увеличиваем на 1 текущую страницу
      var coords=document.getElementById("js-show-more-catalog").getBoundingClientRect();
      var currentScreenPosition=$this.offset().top-coords.top;
      // console.log('current location ')
      // console.log(window.location);
      // return false;
      $.ajax({
        url: window.location.pathname+window.location.search,
        type: 'post',
        data: $form.serialize(),
        success: function(resp){
          var arSplit=resp.split('<!------------------------- page data ----------------------------->');
          // console.log();
          $this.prev().append(arSplit[1]);
          $('html').animate({scrollTop: currentScreenPosition}, 0);
          if(arSplit[2].indexOf('js-show-more-catalog') > 0){
            // console.log('there are more pages');
            // $this.data('page', currentPage+1);
          }
          else $this.remove();
        }
      });
    });

    $('.js-show-more').on('click', function(e){//Показать еще
      e.preventDefault();
      e.stopPropagation();
      // console.log('.js-show-more fired click');
      var $this=$(this);
      var currentPage=$this.data('page'),
      url=$this.data('url');
      var coords=document.getElementById("js-show-more").getBoundingClientRect();
      var currentScreenPosition=$this.offset().top-coords.top;

      if(!url || !currentPage){
        // console.log('nothing to paginate')
        return false;
      }
      $.ajax({
        url: url,
        type: 'get',
        data: 'page='+currentPage+$this.data('options'),
        success: function (resp){
          var arSplit=resp.split('<!------------------------- page data ----------------------------->');
          // console.log();
          $this.prev().append(arSplit[1]);
          $('html').animate({scrollTop: currentScreenPosition}, 0);
          if(arSplit[2].indexOf('js-show-more') > 0)
            $this.data('page', currentPage+1);
          else $this.remove();
        }
      });

      // console.log({url: url, page: currentPage})

    });

    $('.js-ajax-form').on('submit', function(e){//запрещаем отправку всем формам
      e.preventDefault();
      return false;
    });

    $('.js-submit-enable').on('change', function(e){//При изменении элемента разрешаем отправку
      $('.js-submit-button').removeClass('disabled');
    });

    $('.js-need-all').on('change', function(e){//Разрешает отправку при отсутствии аналогичных элементов (нужно отметить все)
      $(this).closest('.js-question-wrapper').find('.js-need-all').each(
        function(index, el){
          $(el).removeClass('js-need-all')
      });
      if(!$('.js-need-all').length)
        $('.js-submit-button').removeClass('disabled');
    });

    $('.js-submit-button').on('click', function(e){//специальному блоку делаем отправку
      // console.log('js-submit-button click');
      e.preventDefault();
      if($(this).hasClass('disabled')) return false;
      var $form=$(this).closest('form');

      $form.find('.hasError').removeClass('hasError');
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
          console.log('password wrong!');
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
          if('sendRR' in resp){
            console.log('--------------------------------------sendRR------------------------------')
            // console.log(resp.sendRR)
            /*
            try {
              console.log('senr to RR addToBasket from one click. product_id is '+resp.sendRR.productID)
              rrApi.addToBasket(resp.sendRR.productID)
            } catch(e) {
              console.log ('failed sent one click addToBasket to rr')
            }*/

            try {
              console.log('senr to RR order from one click. product_id is '+resp.sendRR.productID+', transaction is '+resp.sendRR.transaction + ', price is '+resp.sendRR.price)
              rrApi.order({
                  "transaction": resp.sendRR.transaction,
                  "items": [
                      { "id": resp.sendRR.productID, "qnt": 1,  "price": resp.sendRR.price}
                  ]
              });
            } catch(e) {
              console.log ('failed sent one click order to rr')
            }

          }
          if('error' in resp){
            // console.log('wow '+resp.error)
            if('field' in resp){
              $form.find('input[name="'+resp.field+'"]').addClass('hasError');
              $form.find('textarea[name="'+resp.field+'"]').addClass('hasError');
            }
            else $form.html(resp.error)
            return;
          }
          if('caltat_user' in resp){
            try {
              // console.log('send caltat user '+resp.caltat_user);
              caltat.event(1004, {userid: resp.caltat_user});
            } catch (e) {
              console.log('caltat user send error')
            }
          }
          if('redirect' in resp){
            window.location.pathname=resp.redirect;
            // console.log('need redirect to '+resp.redirect);
          }
          if('wheel' in resp){//Если это форма колеса

            var angle=360+parseInt(resp.angle);
            $('.wheel-body').css('transform', 'rotate('+angle+'deg)');

            setTimeout(function (resp){
              $form.html(resp.text)
              $form.addClass('wide');
              // console.log('after 6 second show resp');
              // console.log(resp)
            }, 6000, resp);
            return true;
          }
          if('checkout' in resp){
            $form.off();
            $form.removeClass('js-ajax-form');
            $form.append('<input type="hidden" name="checked" value="1">');
            $form.attr('action', '/cart/confirmed');
            var form=$('#basketForm');
            form.submit();
            // console.log('form must be submitted');
          }
          $form.html(resp.success)
          $('html,body').animate({scrollTop: $form.offset().top-40});
          if('initslider' in resp){
            initAllTestsSlider();
          }
          // console.log(resp);
        }
      });
      // console.log('fine, ajax'+data)
    });

    if($('#bonusAddAll').length){//Мелькание бонусами
      setInterval(function () {
          var str = parseFloat($('#bonusAddAll').text().replace(/ /g, "")) + 2;
          str = str + '';
          $('#bonusAddAll').text(str.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
      }, 1000);
    }

    $('.js-hide-show-next').on('click', function(e){//Показать/скрыть следующий блок
      e.preventDefault();
      $(this).toggleClass('active');
      var targetClass=$(this).data('class');
      if(!targetClass || targetClass=='') targetClass='mfp-hide';
      $(this).next().toggleClass(targetClass);
    });

    $('.js-toggle-target').on('click', function(e){//Показать/скрыть блок из data-href
      e.preventDefault();
      var $class='mfp-hide';
      if($(this).data('class')) $class=$(this).data('class');
      $($(this).data('href')).toggleClass($class);
    });

  }

  var needToDeliveryCalc = false;//Поскольку расчет доставки операция затратная - будем сначала проверять нужна ли она

  function getOrderWeigth(){//Текущий вес товара в корзине
    var weight=0;
    $('.js-product-row').each(function(i, el){
      weight+=parseFloat($(el).data('weight'))*parseInt($(el).find('.basket-numb input').val());
    })
    return weight;
  }

  function mobileNav() {
    $('.header-nav-but').on('click', function() {
      $('body').toggleClass('-isNav');
    });
    $('.mob-nav [data-href]').on('click', function(e) {
      // console.log('.mob-nav [data-href] click');
      var self = $(this);
      var idItem = self.data('href');
      $(idItem).toggleClass('-isUp');
      e.preventDefault();
    });
    $('.mob-nav .-back').on('click', function() {
      $(this).closest('.mob-nav-pop').removeClass('-isUp');
    });
  }

  function choseRating() {
    $('.rating.-chose .rating-item').on('mouseover', function() {
      var self = $(this);
      var selfIndex = self.index();
      var items = self.closest('.rating').find('.rating-item');
      for(var i = 0; i<=selfIndex; i++) {
        items.eq(i).addClass('-hovered');
      }
    });
    $('.rating.-chose .rating-item').on('mouseout', function() {
      $(this).closest('.rating').find('.-hovered').removeClass('-hovered');
    });
    $('.rating.-chose .rating-item').on('click', function() {
      var self = $(this);
      var selfIndex = self.index();
      var prnt = self.closest('.rating');
      var items = prnt.find('.rating-item');
      prnt.find('.-isActive').removeClass('-isActive');
      for(var i = 0; i<=selfIndex; i++) {
        items.eq(i).addClass('-isActive');
      }
      prnt.find('#rating').val((selfIndex+1)*2);

    });
  }

  function sidebarFiltr() {
    $('.sidebar-f-accord-plate').on('click', function() {
      var self = $(this);
      var prnt = self.closest('.sidebar-f-accord');
      var upBlock = prnt.find('.sidebar-f-accord-up-block');
      var contBlock = upBlock.find('.sidebar-f-accord-cont');
      if(prnt.hasClass('-up')) {
        upBlock.stop().animate({'height': '1px', 'opacity': 0}, 400, function() {
          prnt.removeClass('-up');
          upBlock.removeAttr('style');
        });
      } else {
        upBlock.stop().animate({'height': contBlock.innerHeight(), 'opacity': 1}, 400, function() {
          prnt.addClass('-up');
          upBlock.removeAttr('style');
        });
      }
    });
    $('.sidebar-f-prop-all a').on('click', function() {
      var self = $(this);
      $(self.attr('href')).toggleClass('-isProps');
      return false;
    });
  }

  function back() {
    $('.back').on('click', function() {
      window.history.back();
    });
  }

  function hoverTopNav() {
    $('#mainNav .main-nav-pop').each(function() {
      $(this).closest('li').addClass('-hasSubmenu');
    });
    $('#mainNav>li').on('mouseover', function() {
      var self = $(this);
      if(self.hasClass('-hasSubmenu')) {
        $('body').addClass('-isPop');
      }
      self.css('width', self.innerWidth()).addClass('-hovered');
    });
    $('#mainNav>li').on('mouseout', function() {
      var self = $(this);
      $('body').removeClass('-isPop');
      self.removeAttr('style').removeClass('-hovered');
    });
  }

  function basket() {
    var deliveryPointSelected = false;
    var mapWidget=false;
    // Нужно будет каким-то образом передать сколько бонусов у пользователя (realbonusState), я пока сделаю 200.
    var realbonusState = $('#basket').data('bonus');
    var allBonusState;

    var calcBonus = function() {
      // console.log('-------------calcBonus---------------')
      var usedBonusState = 0;
      $('#basket .basket-bonus input[type="number"]').each(function() {
        usedBonusState += parseInt($(this).data('bonus'));
        // console.log($(this).closest('tr').data('id')+'|'+parseInt($(this).data('bonus')))
      });
      // console.log("usedBonusState|"+usedBonusState);
      // console.log("\n")
      return usedBonusState;
    };

    allBonusState = realbonusState - calcBonus();

    // Для замены стоимости когда кликаем на плюс/минус
    var checkPrice = function(sumI, priceI, inpI, bonusI) {
      allBonusState = realbonusState - calcBonus();
      // console.log('---------------checkPrice---------------');

      var $bonusInput = inpI.closest('tr').find('.basket-bonus input');
      var tempBonus = (allBonusState+1*$bonusInput.data('bonus'));
      var currentCount=parseInt(inpI.val());
      var usedBonusPercent=$bonusInput.val();
      var step=parseFloat($bonusInput.data('step'))
      var needBonus=(0.2*$bonusInput.val()*currentCount*step);

      // console.log('Проценсов для добавления товара: '+ needBonus);
      if(needBonus <= tempBonus) {
        // console.log('Бонусов достаточно, сохраняем новое значение')
      }
      else{
        usedBonusPercent=Math.floor(tempBonus/(step)/currentCount)*5;
        // console.log('считаем, считаем...'+usedBonusPercent);

      }
      // console.log('usedBonusPercent|'+usedBonusPercent);
      bonusI=Math.round(step/5*currentCount*usedBonusPercent)
      $bonusInput.val(usedBonusPercent);
      var calcSum = sumI*priceI-bonusI;
      inpI.data('bonus', bonusI);
      $bonusInput.data('bonus', bonusI);
      var checkedPriceBlock = inpI.attr('data-sum', calcSum).closest('tr').find('.basket-sum').find('span').html(calcSum);
      allBonusState = realbonusState - calcBonus();
    };
    // Для замены стоимости когда вводим значение в инпуте, при потере фокуса, получается матрешка, но лучше так, потому что он скорее всего будет повторяться
    var checkPriceInp = function(x, valueInp) {

      var selfVal;
      if(valueInp == undefined) {
        selfVal = x.val();
      } else {
        selfVal = valueInp;
      }
      var price = x.data('price');
      if(selfVal.length == 0) {
        selfVal = 1;
      }
      saveProductCount(x.closest('tr').data('id'), x.val());
      checkPrice(selfVal, price, x, x.attr('data-bonus'));
    };

    // Расчет общей стоимости
    var calcDelivery = function(){//Расчет стоимости заказа
      // if(isDebug) console.log('---------------------------- calcDelivery ---------------------------')
      if(!needToDeliveryCalc) $('#delivery-sum-block').removeClass('mfp-hide'); //При первом заходе отображаем стоимость доставки
      var $delivery=$('input[name="deliveryId"]:checked');
      var deliveryId=$delivery.val();
      if(!deliveryId) return false;
      var freeDeliveryLimit=parseInt($('#basket').data('freedelivery'));//Изначально считаем, что порог бесплатной доставки тут
      // if(isDebug) console.log('freeDeliveryLimit из настроек сайта|' + freeDeliveryLimit);

      if(parseInt($delivery.data('free-from'))) {
        //Если у варианта доставки прописано свое значение
        freeDeliveryLimit=parseInt($delivery.data('free-from'));
        // if(isDebug) console.log('freeDeliveryLimit из настроек службы|' + freeDeliveryLimit);
      }
      var weight=getOrderWeigth();
      var price=290;
      var cartTotalCost=parseInt($('#basketAllPrice span').data('final-sum'));
      var onlinePayment = $('input[name="basketRadio"]:checked').val();
      if(!onlinePayment) onlinePayment=0;
      if(onlinePayment){//Если выбрана оплата онлайн
        //если выставлена общая цифра для бесплатной доставки онлайн по России - берем ее
        if(parseInt($('#basket').data('freedelivery-online-russia'))) {
          freeDeliveryLimit=parseInt($('#basket').data('freedelivery-online-russia'));
          // if(isDebug) console.log('freeDeliveryLimit из настроек сайта для онлайн для всех|' + freeDeliveryLimit);
        }
        if(parseInt($delivery.data('free-from-online'))) {
          //Если у варианта доставки прописано свое значение для онлайн
          freeDeliveryLimit=parseInt($delivery.data('free-from-online'));
          // if(isDebug) console.log('freeDeliveryLimit из настроек службы для онлайн|' + freeDeliveryLimit);
        }
        if($('#region-fias-id').val()=='0c5b2444-70a0-4932-980c-b4dc0d3f02b5'){
          //Если доставка в Москву и выставлен лимит отдельно для Москвы - берем его
          if(parseInt($('#basket').data('freedelivery-online-moscow'))) {
            freeDeliveryLimit=parseInt($('#basket').data('freedelivery-online-moscow'));
            // if(isDebug) console.log('freeDeliveryLimit из настроек сайта для онлайн для Москвы|' + freeDeliveryLimit);
          }

          if(parseInt($delivery.data('free-from-online-moscow'))) {
            //Если у варианта доставки прописано свое значение для онлайн для Москвы
            freeDeliveryLimit=parseInt($delivery.data('free-from-online-moscow'));
            // if(isDebug) console.log('freeDeliveryLimit из настроек службы для онлайн для Москвы|' + freeDeliveryLimit);
          }
        }
      }
      // if(isDebug) console.log('freeDeliveryLimit Итого|' + freeDeliveryLimit);
      // if(isDebug) console.log('--------------------------------------------------------------------')
      var city =      $('input[name="city"]').val(),
          street =    $('input[name="street"]').val(),
          house =     $('input[name="house"]').val(),
          needCalc = weight + '|' + city + '|' + street+ '|'  + house+ '|' +  onlinePayment+ '|' + cartTotalCost+'|'+deliveryId+'|'+freeDeliveryLimit;
      // if(onlinePayment) needCalc+='|'+$('#region-fias-id').val();//Если оплата онлайн, то нужно учитывать еще и регион

      if(needToDeliveryCalc==needCalc) return false;
      // console.log(needCalc);
      needToDeliveryCalc=needCalc;
      if(cartTotalCost>freeDeliveryLimit) price=0;
      if(!price) { //Если бесплатно - дальше не считаем
        showDeliveryCost(price);
        return;
      }
      if(deliveryId==15){//Яндекс
        price=299;
      }
      if(deliveryId==3){//Почта России
        if(cartTotalCost<freeDeliveryLimit) {
          price=220+0.05*cartTotalCost;
          // console.log('russion post price='+price);
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
            // console.log('russian post success calc');
            // console.log(response);
            // $jsRPMessage.text(response.comment);
            if(response.status) {
              showDeliveryCost(response.price); //показали
              // $('#PriceDeliverTable').html(price);
              // $('#PriceAllTable').html(price+cartTotalCost);
              // $('#deliveryPriceSend').val(price);
            }
          }
        });
      }
      if(deliveryId==9){//Курьер по России
        if(cartTotalCost<4990) {
          price=300;
          // console.log('not default courier price=300');
        }
      }
      if(deliveryId==11){//PickPoint
        freeDeliveryLimit=3590;
        if(cartTotalCost>freeDeliveryLimit) price=0;
        else{
          price=290;
          if(!$('#pickpoint_id').length || !$('#pickpoint_id').val()){
            // alert('Выберите точку доставки');
            console.log('точка неясна');
            // needToDeliveryCalc = 'one more time';//Нужен повторный расчет
            // return;
          }
          else{
            weight=String(weight).replace('.',',');
            // console.log('Обращаемся к api за расчетом');
            $.ajax({
              url: '/pickpoint_get_delivery_price/'+$('#pickpoint_id').val()+'/'+weight,
              type: 'post',
              success: function(response){
                // console.log('  service=PickPoint ');
                // console.log(response);
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
          // console.log('Евросеть. точка неясна');
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
          // $('#dlh_address').html(message);
          $('#dlh_shop_id').val(deliveryPointSelected.shopId);
          if(cartTotalCost>freeDeliveryLimit) price=0;
          // price=Math.ceil(price);
          // $('#dlh_price').val(price);
        }
      }
      if(deliveryId==10){//Самовывоз
        $('.address').html($('#shop_address_line').val());
        // $('#').val( message);
        price=290;
      }
      // if(cartTotalCost>freeDeliveryLimit) price=0;
      showDeliveryCost(price);

    }
    $('.js-delivery-calc').on('change', function (e) {
      var deliveryId=$('input[name="deliveryId"]:checked').val();
      if(deliveryId!=3) return false;
      // console.log('try to calc russian post')
      calcDelivery();
    });
    $('.js-coupon-apply').on('click', function (e) {//клик "Применить купон"
      e.preventDefault()
      var coupon=$('input[name="coupon"]').val();
      // if(coupon=='') return;
      // console.log('------------------------------------------------coupon is '+coupon);
      $.ajax({//Отправляем купон на проверку
        url: '/cart/check-coupon',
        data: {coupon:coupon},
        type: 'post',
        dataType: 'json',
        success: function(resp){
          if(isDebug) console.log('/cart/check-coupon ajax success ----------------------');
          doCouponCheckReady(resp)
        }
      })
    });
    function doCouponCheckReady(resp){
        if(isDebug) console.log('doCouponCheckReady fired ----------------------');
        if('success' in resp){
          $('input[name="coupon"]').val(resp.couponText)
          var total=0;

          for(var i=0; i<resp.products.length; i++){//Обновляем информацию о продуктах
            var $product=$('#product-line'+resp.products[i].productId);
            // if(resp.products[i].discount > 0) {
              $product.find('input[name="bonusPercentForm['+resp.products[i].productId+']"]').val(0);
              $product.find('input[name="bonusPercentForm['+resp.products[i].productId+']"]').data('bonus', 0);
              if(resp.products[i].discount > 0){
                $product.find('.basket-calc-plus').addClass('disabled');
                $product.find('.basket-calc-min').addClass('disabled');
              }
              else{
                $product.find('.basket-calc-plus').removeClass('disabled');
                $product.find('.basket-calc-min').removeClass('disabled');
              }
              // console.log(resp.products[i].productId +' | data-sum old | ' +$product.find('.basket-numb input[type="number"]').data('sum'));
              $product.find('.basket-numb input[type="number"]').attr('data-sum', resp.products[i].quantity*resp.products[i].price_w_discount);
              $product.find('.basket-numb input[type="number"]').data('price', resp.products[i].price_w_discount);
              // console.log(resp.products[i].productId +' | data-sum new | ' +$product.find('.basket-numb input[type="number"]').data('sum'));
              $product.find('.basket-sum span').text(resp.products[i].quantity*resp.products[i].price_w_discount)
              total+=resp.products[i].quantity*resp.products[i].price_w_discount;
            // }
          }
          $('#basket').data('bonus', 0);
          if('successCoupon' in resp){
            $('input[name="coupon"]').addClass('success')
            $('input[name="coupon"]').removeClass('fail')
            // $(document, body).
            $('html,body').animate({scrollTop: $('#basketAllPriceVlock').offset().top-10});
          }
          else {
            $('input[name="coupon"]').removeClass('success')
            $('input[name="coupon"]').addClass('fail')
          }
          if(resp.couponText==''){
            $('input[name="coupon"]').removeClass('success')
            $('input[name="coupon"]').removeClass('fail')
          }
          // }
          // $('#basketAllPrice span').data('final-sum', total)
          // console.log('total is | '+total);

          calcAllPrice()
        }
        // console.log(resp);

    }
    $(document).on('change', '.js-shop-select-list', function (e) {//клик "Выбрать магазин"
      e.preventDefault();
      // if()
      var $shopBlock=$(this).closest('.shops-delivery');
      var id=$(this).attr('id');
      var text=$('label[for="'+id+'"]').text();
      $shopBlock.find('.js-current-shop-point').html(text)
      // console.log('js-shop-select-list fired. id='+id+'|text='+text)
      $('#shop_id').val($(this).val())
      $('#shop_address_line').val(text)
      needToDeliveryCalc='need calc';
      calcDelivery();
      $.magnificPopup.close();
    });
    $(document).on('click', '.js-shop-select', function (e) {//клик "Выбрать магазин"
      e.preventDefault();
      var $shopBlock=$(this).closest('.shops-delivery')
      var $shop=$(this).prev()
      // console.log($shop);
      var text=$shop.find('.name').text()+'<br>'+$shop.find('.shop-address').text()

      $shopBlock.find('.js-current-shop-point').html(text)
      // $('#deliveri-info .address').html(text)
      $('#shop_id').val($shop.data('id'))
      $('#shop_address_line').val(text)
      needToDeliveryCalc='need calc';
      calcDelivery();
      $.magnificPopup.close();
      // console.log('js-shop-select click fired| '+text);
    });
    var shopsDeliveryMap=false;
    $(document).on('click', '.js-shop-init', function (e) {//клик "показать точки самовывоза"
      if(!$(this).data('dont-stop')) e.preventDefault();
      // console.log('js-shop-init click fired');
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
    $(document).on('click', '.js-dlh-init', function (e) {//клик "показать точки"
      e.preventDefault();
      // console.log('js-dlh-init click fired');
      showDrhLogistic()
    });

    function showDrhLogistic(){//Показ окна виджета Евросеть
      // console.log('map open');
      $('.drh-container').addClass('active');
      window.onPointSelected = function(deliveryPoint) {
        // console.log('popint selected');
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

          // console.log('map close');

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
        // устанавливаем в скрытое поле ID терминала
        document.getElementById('pickpoint_id').value = result['id'];
        document.getElementById('pickpoint_address').value = result['name'] + '<br />' + result['address'];

        // показываем пользователю название точки и адрес доствки
        $('.address').html( result['name'] + '<br />' + result['address']);
        needToDeliveryCalc = 'one more time';//Нужен повторный расчет
        calcDelivery();
    }

    $(document).on('click', '.js-pickpoint-init', function (e){//Открывает окно Pickpoint
      // console.log('js-pickpoint-init')
      e.preventDefault();
      PickPoint.open(initPickpoint);
    });

    function showDeliveryCost(price){//Отображает стоимость доставки в нужных местах
      price = Math.round(price);
      $('#deliveryPriceSend').val(price);
      $('#deliveryPriceSendSpan').html(String(price).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '))

      calcAllPrice();
    }

    var calcAllPrice = function() {//Вычисляет общую стоимость заказа
      // console.log('calcAllPrice fired');
      var allPrice = 0;
      var total;
      var allPriceNoBonus = 0;
      $('#basket .basket-numb input[type="number"]').each(function() {
        var self = $(this);
        allPrice += parseInt(self.attr('data-sum'));
        allPriceNoBonus+= parseInt(self.attr('data-sum'))+parseInt(self.attr('data-bonus'));//Сумма в корзине без примененных бонусов
        // console.log('calc all price item | '+ parseInt(self.attr('data-sum')))
      });
      var topLimit=$('#basket').data('bonustoplimit');
      var bottomLimit=$('#basket').data('bonusminlimit');
      if(false){
        // var consoleObj;
        // consoleObj.topLimit=topLimit;
        // consoleObj.bottomLimit=bottomLimit;
        // consoleObj.allPrice=allPrice;
        console.log({topLimit:topLimit, bottomLimit:bottomLimit,allPrice:allPrice, allPriceNoBonus: allPriceNoBonus});
      }
      if(allPriceNoBonus != allPrice && ((topLimit && allPriceNoBonus>topLimit) || (bottomLimit && allPriceNoBonus<bottomLimit))){
        window.location.reload()
        // console.log('need to reload. allPriceNoBonus='+allPriceNoBonus);
      }
      $('#basketAllPrice span').html(String(allPrice).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
      if($('#basketRadio-1').prop('checked')) {
        var discountNotApply=0;
        $('.js-product-row').each(function(i, item){
          if($(item).data('discount-not-apply')) discountNotApply+=1*$(item).find('.gtm-basket-item-count').data('sum');
          // console.log([i, item])
        })
        var discount = $('#basketRadio-1').data('discount');
        total = Math.round(0.93 * (allPrice-discountNotApply) + discountNotApply);
      } else {
        total = allPrice;
      }
      $('#basketAllPrice span').data('final-sum',total);
      total+=parseInt($('#deliveryPriceSend').val());
      var totalPrice=String(total).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
      $('#basketAllPriceTotal span').html(totalPrice);
      if($('#basketAllPriceTotalAicoin span').length){
        var aiTokenTotal=Math.round(total/parseFloat($('#basketAllPriceTotalAicoin').data('change-rate')));
        // console.log({
        //   rate:parseFloat($('#basketAllPriceTotalAicoin').data('change-rate')),
        //   total: total,
        //   aiTokenTotal: aiTokenTotal,
        // });
        $('#basketAllPriceTotalAicoin span').html(String(aiTokenTotal).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
      }


      calcDelivery();
    };

    var saveProductCount = function (id, count){//Сохраняет значения количество в корзине пользователя
      // console.log('saveProductCount ' + id + ':' + count);
      $.ajax({
        url: "/cart/addtocartcount/"+id,
        data: {count: count},
        success: function (resp){
          // console.log(resp)
          doCouponRecalc();

        }
      })
    }
    function doCouponRecalc(){
      var coupon=$('input[name="coupon"]').val();
      if(coupon){ //Если есть купон, то надо проверить скидку по нему
        if(isDebug) console.log('have coupon in /cart/addtocartcount/  ajax success. need to check');
        $.ajax({//Отправляем купон на проверку
          url: '/cart/check-coupon',
          data: {coupon:coupon},
          type: 'post',
          dataType: 'json',
          success: function(resp){
            if(isDebug) console.log('/cart/check-coupon ajax success in /cart/addtocartcount/:id ajax success ----------------------');
            doCouponCheckReady(resp)
          }
      })}
    }

    $(document).on('change', '.js-payment', function (e){//Изменение типа оплаты
      e.preventDefault();
      // console.log(this)
      if(!$(this).is(':checked')) {
        // console.log('nothing to do')
        return false;
      }
      if($(this).val()==58){//iMe Wallet
        $('.js-ime-only').removeClass('mfp-hide');
      }
      else{
        $('.js-ime-only').addClass('mfp-hide');
      }
      $('#services-header').removeClass('mfp-hide');
      $('#basketRadio-'+$(this).data('online')).prop( "checked", true ).trigger('change');
      // console.log('#basketRadio-'+$(this).data('online')+' clicked');
      calcDelivery();
      setTimeout(function(){$('#services-body').removeClass('mfp-hide')}, 300);
    });

    $('.js-delivery').on('change', function (e){//Изменение типа доставки
      e.preventDefault();
      if(!$(this).is(':checked')) {
        // console.log('nothing to do')
        return false;
      }
      $('.address').html( '');
      var $this=$(this);
      var currentDelivery=$this.val();
      $('#delivery-info').html($('#description-'+currentDelivery).html());//Показали описание доставки
      calcDelivery();
      $('#payment-header').removeClass('mfp-hide');
      $('#payment-body').removeClass('mfp-hide');
      $('#delivery-info').removeClass('mfp-hide');
      $.ajax({//получаем оплаты для выбранного типа доставки
        url: '/cart/payments',
        data: {id:currentDelivery},
        type: 'post',
        success: function (resp){
          // console.log(resp);
          $('#payment-body').html(resp);
          if(currentDelivery==10 || currentDelivery==14 || currentDelivery==11)//Самовывоз, Евросеть или PickPoint
            $('.js-no-address-need').addClass('mfp-hide')
          else
            $('.js-no-address-need').removeClass('mfp-hide')
          // setTimeout(function(){}, 300);
        }
      })

      // console.log($(this).val() + $('#description-'+currentDelivery).html());
    });

    // Пересчет если выбрано "оплата онлайн"

    $('#basketAllPriceVlock input:radio').on('change', function() {
      calcAllPrice();
    });

    // Удаление товара

    $('#basket .js-basket-delete div').on('click', function() {
      var self = $(this);
      var prnt = self.closest('tr');
      var mainPrnt = prnt.closest('tbody');
      var id=prnt.data('id');
      if(!id) return;
      $.ajax({
        url: '/deletefromcart/'+id,
        type: 'post',
        dataType: 'json',
        success : function (resp){
          if(resp.success=='success'){
            prnt.remove();
            var item = mainPrnt.find('tr');
            if(item.length) {
              calcAllPrice();
              allBonusState = realbonusState - calcBonus();
              showTopCart();
              doCouponRecalc();
            } else {
              window.location.reload()
              // $('#basketForJs').addClass('-deleted');
            }
          }
        }
      });
      // return false;
    });

    // Общее для инпутов, запрет ввода букв

    $('#basket input[type="number"]').on('keydown', function(e) {
      var selfVal = e.keyCode;
      if((selfVal>=48 && selfVal<=57) || selfVal==8 || selfVal==46 || (selfVal>=96 && selfVal<=105)) {} else {
        return false;
      }
    });

    // Инпуты для расчета стоимости

    $('#basket .basket-numb input[type="number"]').on('keyup', function() {
      var self = $(this);
      var selfVal= self.val().length;
      if(selfVal>3) {
        self.val(self.val().substring(0, 3));
      }
      checkPriceInp(self, selfVal);
      calcAllPrice();
    });

    $('#basket .basket-numb input[type="number"]').on('keyup change', function() {
      var self = $(this);
      checkPriceInp(self);
      calcAllPrice();
    });

    $('#basket .basket-numb input[type="number"]').on('blur', function() {
      var self = $(this);
      var selfVal = self.val();
      if(selfVal.length == 0 || selfVal == '0') {
        selfVal = 1;
        self.val(selfVal);
      }
      checkPriceInp(self);
      calcAllPrice();
    });

    $('#basket .basket-numb .basket-calc-plus').on('click', function() {
      var self = $(this);
      var inp = self.parent().find('input[type="number"]');
      var price = inp.data('price');
      var inpVal = parseInt(inp.val());
      var sum = inpVal;
      if(inpVal<999) {
        sum += 1;
      }
      inp.val(sum);
      saveProductCount(self.closest('tr').data('id'), sum);
      checkPrice(sum, price, inp, inp.data('bonus'));
      calcAllPrice();
    });

    $('#basket .basket-numb .basket-calc-min').on('click', function() {
      var self = $(this);
      var inp = self.parent().find('input[type="number"]');
      var price = inp.data('price');
      var inpVal = parseInt(inp.val());
      var sum = inpVal;
      if(inpVal>1) {
        sum -= 1;
      }
      inp.val(sum);
      saveProductCount(self.closest('tr').data('id'), sum);
      checkPrice(sum, price, inp, inp.attr('data-bonus'));
      calcAllPrice();
    });

    // Инпуты для расчета бонусов

    $('#basket .basket-bonus .basket-calc-plus').on('click', function() {
      var self = $(this);
      if(self.hasClass('disabled')) return false; //Если товар со скидкой

      var inp = self.parent().find('input[type="number"]');
      var price = inp.data('price');
      var inpVal = parseInt(inp.val());
      var sum = inpVal;
      var step=inp.data('step');
      var quant = self.closest('tr').find('.basket-numb').find('input[type="number"]').val();
      if((1*inpVal + 5) <= 1*inp.data('maxbonus') && allBonusState >= Math.round(quant*step)) {
        sum += 5;
      }
      else {
      }
      inp.val(sum);
      inp.data('bonus', Math.round(quant*sum*step/5));
      self.closest('tr').find('.basket-numb').find('input[type="number"]').attr('data-bonus', Math.round(quant*sum*step/5)).trigger('change');
    });

    $('#basket .basket-bonus .basket-calc-min').on('click', function() {
      var self = $(this);
      if(self.hasClass('disabled')) return false; //Если товар со скидкой

      var inp = self.parent().find('input[type="number"]');
      var price = inp.data('price');
      var inpVal = parseInt(inp.val());
      var sum = inpVal;
      var step=inp.data('step');
      var quant = self.closest('tr').find('.basket-numb').find('input[type="number"]').val();
      if(inpVal-5 >= 0 && allBonusState<=realbonusState) {
        sum -= 5;
      }
      else{
      }
      inp.val(sum);
      inp.data('bonus', Math.round(quant*sum*step/5));
      self.closest('tr').find('.basket-numb').find('input[type="number"]').data('bonus', Math.round(quant*sum*step/5)).trigger('change');
    });

    // Инициализация при загрузке страницы

    $('#basket .basket-numb input[type="number"]').each(function() {
      var self = $(this);
      self.trigger('blur');
    });

    calcAllPrice();

  }

  function downLink() {
    $('.downLinkJS').on('click', function() {
      var self = $(this);
      $(self.attr('href')).stop().slideToggle(function() {
        self.toggleClass('-isVis');
      });
      return false;
    });
  }

  function allBut() {
    $('.allButJS').on('click', function() {
      var self = $(this);
      $(self.attr('href')).toggleClass('-isVis');
      self.toggleClass('-isAll');
      return false;
    });
  }

  function scrollProgBonus() {
    $('#scrollLink a').on('click', function() {
      var self = $(this);
      var blockOffset = $(self.attr('href')).offset().top;
      $('html, body').animate({'scrollTop': blockOffset}, 800, 'easeOutQuad');
      return false;
    });
  }

  function toggleNav() {
    $('.sidebar-nav-toggle').each(function() {
      var self = $(this);
      var prnt = self.parent();
      var pop = prnt.find('ul');
      if(pop.css('display') != 'block') {
        prnt.addClass('-isUp');
      }
    });
    $('.sidebar-nav-toggle').on('click', function() {
      var self = $(this);
      var prnt = self.closest('li');
      var popB = prnt.find('ul');
      if(prnt.hasClass('-isUp')) {
        popB.slideDown(function() {
          prnt.removeClass('-isUp');
          prnt.addClass('-isActive');
        });
      } else {
        popB.slideUp(function() {
          prnt.addClass('-isUp');
          prnt.removeClass('-isActive');
        });
      }
    });
  }

  function productCard() {
    $('#checkProductCard').on('click', 'a', function() {
      var self = $(this);
      $('#checkProductCard .active').removeClass('active');
      self.addClass('active');
      $('#productCardImg').attr('href', self.data('bimg')).find('img').attr('src', self.attr('href'));
      // console.log('current index |'+self.parent().data('swiper-slide-index'))
      $('#productCardImg').data('index', self.parent().data('swiper-slide-index'))
      return false;
    });
  }

  function range() {
    $('.range').each(function() {
      var self = $(this);
      var minInp = self.find('.minCost');
      var maxInp = self.find('.maxCost');
      var slider = self.find('.range-ui');
      var minInitVal = self.data('min');
      var maxInitVal = self.data('max');

      slider.slider({
        min: parseInt(minInitVal),
        max: parseInt(maxInitVal),
        values: [self.data('initminval'), self.data('initmaxval')],
        animate: 400,
        range: true,
        step: parseInt(self.data('step')),
        stop: function(event, ui) {
          var minVal = slider.slider("values", 0);
          var maxVal = slider.slider("values", 1);
          // Если нужно на разряды разбивать
          if(self.data('dimension')) {
            minVal = String(minVal).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            maxVal = String(maxVal).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
          }
          // Если число с точкой
          if(self.data('fraction')) {
            minVal = minVal/self.data('fraction');
            maxVal = maxVal/self.data('fraction');
          }
          minInp.val(minVal);
          maxInp.val(maxVal);
        },
        slide: function(event, ui){
          var minVal = slider.slider("values", 0);
          var maxVal = slider.slider("values", 1);
          if(self.data('dimension')) {
            minVal = String(minVal).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            maxVal = String(maxVal).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
          }
          if(self.data('fraction')) {
            minVal = minVal/self.data('fraction');
            maxVal = maxVal/self.data('fraction');
          }
          minInp.val(minVal);
          maxInp.val(maxVal);
        }
      });

      minInp.on('change', function(){
        var value1 = minInp.val().replace(' ', '');
        var value2 = maxInp.val().replace(' ', '');
        // Если значение с точкой
        if(self.data('fraction')) {
          value1 = value1*self.data('fraction');
          value2 = value2*self.data('fraction');
          if(parseInt(value1) > parseInt(value2)){
            value1 = value2;
            slider.slider("values", 0, value1);
            minInp.val(value1/self.data('fraction'));
            return false;
          }
          slider.slider("values", 0, value1);
        } else {
          // Нормальное условие
          if(parseInt(value1) > parseInt(value2)){
            value1 = value2;
            minInp.val(value1);
          }
          slider.slider("values", 0, value1);
        }
      });

      maxInp.on('change', function(){
        var value1 = minInp.val().replace(' ', '');
        var value2 = maxInp.val().replace(' ', '');
        if(self.data('fraction')) {
          value1 = value1*self.data('fraction');
          value2 = value2*self.data('fraction');
          if(value2 > maxInitVal) {
            value2 = maxInitVal;
            slider.slider("values", 1, value2);
            maxInp.val(maxInitVal/self.data('fraction'));
            return false;
          }
          if(parseInt(value1) > parseInt(value2)){
            value2 = value1;
            maxInp.val(value2/self.data('fraction'));
            slider.slider("values", 1, value2);
          }
          slider.slider("values", 1, value2);
        } else {
          if(value2 > maxInitVal) {
            value2 = maxInitVal;
            maxInp.val(maxInitVal)
          }
          if(parseInt(value1) > parseInt(value2)){
            value2 = value1;
            maxInp.val(value2);
          }
          slider.slider("values", 1, value2);
        }
      });

      // Для разбивания на разряды после потери фокуса

      if(self.data('dimension')) {
        maxInp.on('blur', function() {
          var self = $(this);
          self.val(String(self.val()).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
        });
        minInp.on('blur', function() {
          var self = $(this);
          self.val(String(self.val()).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
        });
      }

      // Запрет ввода символов
      $('.sidebar-f-range-services .minCost, .sidebar-f-range-services .maxCost').keypress(function(event){
        var key, keyChar;
        if(!event) var event = window.event;
        if (event.keyCode) key = event.keyCode;
        else if(event.which) key = event.which;
        if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
        keyChar=String.fromCharCode(key);
        if(!/\d/.test(keyChar)) return false;
      });
    });
  }

  function popup() {
    $('.inlinePopupJS').magnificPopup({
      // disableOn: 700,
      type: 'inline',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      fixedContentPos: true,
      closeMarkup: '<div class="mfp-close">&nbsp;</div>'
    });
    $('.js-image-popup').magnificPopup({
      // disableOn: 700,
      type: 'image',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      image: {
  			verticalFit: false
  		},
      closeMarkup: '<div class="mfp-close">&nbsp;</div>'
    });
    var mfpInstance;
    if($('.gallery-element').length){
      // console.log('gallery init')
      /*
      $('.gallery-element').on('click', function (e){
        // console.log('preventDefault');
        // e.preventDefault()
      });*/
      mfpInstance=$('#gallery-container').magnificPopup({
      // $('.gallery-element').magnificPopup({
        type: 'image',
        image: {
    			verticalFit: false
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
        // console.log('open mfp gallery' + $(this).data('index'));
        mfpInstance.magnificPopup('open').magnificPopup('goTo', $(this).data('index'))
      })
    }
  }

  //Стилизация select
  function stylizeSelect(selector, style, parentSelector) {
    var $item = $(selector);
    if ($item.length) {
        $item.ikSelect(style);
    }
    $(parentSelector).append('<div class="ik-arrow"></div>');
  }

  // пока не сделал отсчет до акции
  function actionTime() {
    if($('#timeAction').length) {
      var calcTime = function(duration) {
        if(duration<0) {
          $('body').removeClass('-isFooterAction');
        } else {
          var milliseconds = parseInt((duration % 1000) / 100),
            seconds = Math.floor((duration / 1000) % 60),
            minutes = Math.floor((duration / (1000 * 60)) % 60),
            // Скорее всего 'hours' по дизайну сутки в часах отсчитывает, но вполне возможно нужно будет еше и дни сделать, пока оставлю
            hours = Math.floor((duration / (1000 * 60 * 60)) % 24),
            days = Math.floor((duration / (1000 * 60 * 60)));
          hours = (hours < 10) ? "0" + hours : hours;
          minutes = (minutes < 10) ? "0" + minutes : minutes;
          seconds = (seconds < 10) ? "0" + seconds : seconds;
          //$('#timeAction .hours').html(hours);
          $('#timeAction .hours').html(days);
          $('#timeAction .minut').html(minutes);
          $('#timeAction .sec').html(seconds);
        }
      };
      var actionTime = new Date($('#timeAction').data('time'));
      var buildDate = function() {
        var tDate = new Date();
        var calcTimeAction = actionTime - tDate;
        calcTime(calcTimeAction);
      };
      $('body').addClass('-isFooterAction');
      setInterval(buildDate, 1000);
    }
  }

  function initAllTestsSlider(){//Слайдер тестов после ajax
    var self = $('.test-all');
    var mySwiper = new Swiper(self.find('.swiper-container'), {
      // loop: true,
      navigation: {
        nextEl: self.find('.swiper-button-next'),
        prevEl: self.find('.swiper-button-prev'),
      },
      pagination: {
        el: self.find('.swiper-pagination'),
        clickable: true
      },
      slidesPerView: 3,
      slidesPerColumn: 3,
      breakpoints:{
        767:{
          slidesPerView: 1,
          slidesPerColumn: 3,
        },
        1200:{
          slidesPerView: 2,
          slidesPerColumn: 3,
        }
      }
    });
  }

  function sliders() {
    //Верхний слайдер
    $('.top-slider').each(function() {
      var self = $(this);
      var mySwiper = new Swiper(self.find('.swiper-container'), {
        loop: true,
        preloadImages: false,
        // lazy: true,
        lazy: {
          loadPrevNext: true
        },
        spaceBetween: 20,
        navigation: {
          nextEl: self.find('.swiper-button-next'),
          prevEl: self.find('.swiper-button-prev'),
        },
        pagination: {
          el: self.find('.swiper-pagination'),
          clickable: true
        }
      });
    });
    //Слайдер в карточки товара
    if($('#checkProductCard').length) {
      var mySwiper = new Swiper($('#checkProductCard .swiper-container'), {
        loop: true,
        slidesPerView: 'auto',
        navigation: {
          nextEl: $('#checkProductCard .swiper-button-next'),
        }
      });
    }
    //Слайдеры на главной странице
    $('.v-block').each(function() {
      var self = $(this);
      var mySwiper = new Swiper (self.find('.swiper-container'), {
        loop: true,
        preloadImages: false,
        lazy: {
          loadPrevNext: true,
        },
        watchSlidesVisibility: true,
        slidesPerView: 'auto',
        navigation: {
          nextEl: self.find('.swiper-button-next'),
          prevEl: self.find('.swiper-button-prev'),
        },
        pagination: {
          el: self.find('.swiper-pagination'),
          clickable: true
        }
      });
    });
    //Популярные бренды
    $('.popular-brends').each(function() {
      var self = $(this);
      var mySwiper = new Swiper (self.find('.swiper-container'), {
        loop: true,
        slidesPerView: 'auto',
        navigation: {
          nextEl: self.find('.swiper-button-next'),
          prevEl: self.find('.swiper-button-prev'),
        },
        pagination: {
          el: self.find('.swiper-pagination'),
          clickable: true
        },
        breakpoints: {
          1010: {
            centeredSlides: true,
          }
        }
      });
    });
    //Слайдер на странице магазина
    $('.shop-item-slider').each(function() {
      var self = $(this);
      var items = self.find('.swiper-slide');
      if(items.length>1) {
        var mySwiper = new Swiper (self.find('.swiper-container'), {
          loop: true,
          slidesPerView: 'auto',
          navigation: {
            nextEl: self.find('.swiper-button-next'),
            prevEl: self.find('.swiper-button-prev'),
          },
          pagination: {
            el: self.find('.swiper-pagination'),
            clickable: true
          },
        });
      }
    });

    if($('#showcaseSlider').length) {
      var swiperSlider;
      $(window).on('resize.showcaseSlider', function() {
        if($('#showcaseSlider .swiper-button-prev').css('display') == 'block') {
          if(swiperSlider == undefined) {
            // console.log('------------------------------init swiper here');
            swiperSlider = new Swiper($('#showcaseSlider .swiper-container'), {
              loop: true,
              slidesPerView: 'auto',
              initialSlide: $('#current-slide-index').data('index'),
              navigation: {
                nextEl: $('#showcaseSlider .swiper-button-next'),
                prevEl: $('#showcaseSlider .swiper-button-prev'),
              },
            });
          }
        } else {
          if(swiperSlider != undefined) {
            swiperSlider.destroy();
            swiperSlider = undefined;
          }
        }
      });
      $(window).trigger('resize.showcaseSlider');
    }
  }

  var filterWindow = $('.sidebar-f'),
      filterBtnOpen = $('.sidebar-mob-but-filtr'),
      filterClose = $('.sidebar-f .close-btn'),
      filterOverlay = $('body');

  function filtrSidebar() {
    filterBtnOpen.on('click', function() {
      $(this).closest('.sidebar').addClass('-isVisibleFiltr');
      $(this).closest('.sidebar').removeClass('-isHiddenFiltr');
      filterOverlay.addClass('-isPop');
    });
    filterClose.click(function() {
      $(this).closest('.sidebar').removeClass('-isVisibleFiltr');
      $(this).closest('.sidebar').addClass('-isHiddenFiltr');
      filterOverlay.removeClass('-isPop');
    });
  }

  function catMobFiltr() {
    if(isTouchFunc() && $('.cat-list-filtr').length) {
      $('body').on('click', function() {
        $('.cat-list-filtr-mob-col.-isFiltr').removeClass('-isFiltr');
      });
      $('.cat-list-filtr-mob-text').on('click', function(e) {
        var self = $(this);
        self.closest('.cat-list-filtr-mob-col').toggleClass('-isFiltr');
        e.stopPropagation();
      });
    }
  }

  function isTouchFunc() {
    var isTouch = ('ontouchstart' in document.documentElement);
    return isTouch;
  }

  function sidebarMobBut() {
    $('.sidebar.-sexopedia .but, .sidebar.-video .but').on('click', function() {
      var self = $(this);
      var idBlock = self.attr('href');
      self.closest('.sidebar').find('[data-upblock="'+idBlock+'"]').slideToggle();
      return false;
    });
  }

});
