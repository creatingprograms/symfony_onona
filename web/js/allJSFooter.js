function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
    return false;
  }
// console.log('-----------------___ready');
function viravnivanie() {
    if ($("#content").height() > $(".aside").height()) {
        maxHeightBlock = $("#content").height();
    } else {
        maxHeightBlock = $(".aside").height();
    }

    if (maxHeightBlock < $("#sidebar .cat-menu").height()) {
        $('.encSex').css('height', "350px");
    } else {
        if ($('#filters').css('display') == 'none' || !$('#filters').length) {
            dopMinus = $(".cat-menu:last").height();
        } else {
            // dopMinus = $("#filters").height();
            dopMinus = 950;
        }
        blockBrendHeight = maxHeightBlock - $(".article-box").height() - $("#sidebar .brand-galery .galery").height() - dopMinus - 130;
        if (blockBrendHeight < 0) {
            blockBrendHeight = 0;
            $('.encSex').remove();
        }
        // console.log('vyr:'+maxHeightBlock+'|'+$("#content").height()+'|'+$("#sidebar .cat-menu").height()+'|'+blockBrendHeight+'|'+$("#filters").height());
        $('.encSex').css('height', blockBrendHeight);
    }


}
function doPopup15(){
  $.colorbox({
    href: '/viewpopup15form',
    className: 'popup15-popup',
    open: true,
    opacity: '0.5',
    onClosed: function(){
      // console.log('set cookie popup15');
      $.cookie('popup15', 1, { expires: 5, path: '/' });
    }
  });
}
function initPopup15(){
  var isViewed=$.cookie('popup15');
  var utm=getUrlParameter('utm_source');
  if(utm==='YandexMarket'){
    // console.log('from market');
    $.cookie('popup15', 1, { expires: 5, path: '/' });
    $.cookie('rreiwfc', true, { expires: 5, path: '/' });
    isViewed=true;
  }
  else{
    // console.log('not from market');
  }

  if (isViewed || !$('.popup-15-need').length) {
    // console.log('already viewed');
    return;
  }
  var $settings=$('.popup-15-need');
  setTimeout(doPopup15, 15*1000);
  $(document).on('click', '.js-popup15-submit', function (e){
    var regexp= new RegExp('^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$');
    var $form=$(this).closest('form');

    if(!regexp.test($form.find('input').val())) {
      $form.find('input').addClass('has-error').focus();

      return;
    }
    try {
        rrApi.setEmail($form.find('input').val());
    } catch (e) {
      // console.log('rrApi.setEmail Error. allJSFooter, line 37');
    }
    $.ajax({
      type: 'post',
      url: $form.attr('action'),
      data: {
        token: 'foo bar zz',
        email: $form.find('input').val()
      },
      success: function (resp){
        $form.find('.description').html('<div class="result">'+resp.message+'<div>');
        $form.find('input').remove();
        $form.find('.submit').remove();
        // console.log(resp);
      }
    });

  })
  $(document).on('submit', '.popup-15-form', function (e){
    e.preventDefault();
    console.log('form submit disable');
  })

  // alert('popup15');
}
$(document).on('ready', function(){
  initPopup15();
  $('.js-top-menu-new').on('click', function (e){
    $(this).parent().find('.submenu-first').toggleClass('active');
  });
  $('.js-search-trigger').on('click', function (e){
    console.log('-----------------___ready 1');
    $(this).parent().find('.mobile-search').toggleClass('active');
  });

  $('#login form').on('submit', function (){
    try {
        dataLayer.push({'event': 'login'});
    } catch (e) {
      // console.log('yandex.event login Error. allJSFooter');
    }
  });
  $('#processOrder2').on('submit', function(e){
    // e.preventDefault();
    var filter = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/;
    var a = $(this).find("#sf_guard_user_email_address").val();
    var gender = $(this).find("input[name=sf_guard_user[sex]]:checked").val();

    if (filter.test(a)) {
      try {
          // console.log(a+'|'+gender)
          rrApi.setEmail(a, {gender: gender});
      } catch (e) {
        // console.log('rrApi.setEmail Error. allJSFooter, line 37');
      }
    }
  });
});
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
            ((expires) ? "; expires=" + expires : "") +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            ((secure) ? "; secure" : "");
}
// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function validateEmailRassilka(sendedGender) {
    var email = $("input[name=user_mail]");
    var emailInfo = $("input[name=user_mail]").next();
    //personal_accept
    if (!$("#accept-form").is(':checked')){
      email.addClass("error");
      emailInfo.html(" <br>Необходимо принять пользовательское соглашение!");
      emailInfo.addClass("error");

      return false;
    }
    //testing regular expression
    var a = $("input[name=user_mail]").val();
    $("input[name=user_mail]").val($.trim(a));
    var a = $("input[name=user_mail]").val();
    var filter = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/;
    //if it's valid email
    if (filter.test(a)) {
      try {
          rrApi.setEmail(a, {gender: sendedGender});
      } catch (e) {
        console.log('rrApi.setEmail Error. allJSFooter');
      }
      try {
          dataLayer.push({'event': 'podpiska'});
      } catch (e) {
        console.log('yandex.setEmail Error. allJSFooter');
      }
        $('#firstVisitYesNews').submit();
        return true;
    }
    //if it's NOT valid
    else {
        email.addClass("error");
        emailInfo.html(" <br>Введите реальную почту.");
        emailInfo.addClass("error");
        $.post('/logvalidmail', {
            mail: a,
            form: 'rassilka'
        });
        return false;
    }
}



function clearYaSov() {

    /*var observer = new MutationObserver(function (allmutations) {

        allmutations.map(function (mutations) {
            var m = mutations.addedNodes[0];
            if ($("div").is("#" + $(m).find('[title *= "Более выгодная цена на "]:last').attr("id")) || $("div").is("#" + $(m).find('[title *= "Есть предложение на "]:last').attr("id")) || $("div").is("#" + $(m).find('[title *= "не продаётся на Яндекс.Маркете в вашем регионе"]:last').parent().attr("id"))) {
                $('[title *= "Более выгодная цена на "]:last').parent().remove();
                $('[title *= "Есть предложение на "]:last').parent().remove();
                $('[title *= "не продаётся на Яндекс.Маркете в вашем регионе"]:last').parent().remove();
                $("html").css("margin-top", 0);
                window.setTimeout('$("html").css("margin-top", 0);', 50);
                window.setTimeout('$("html").css("margin-top", 0);', 150);
                window.setTimeout('$("html").css("margin-top", 0);', 250);
                window.setTimeout('$("html").css("margin-top", 0);', 350);
            }


        });
    });
    observer.observe(document.querySelector('body'), {childList: true});*/
}

$(document).on('ready', function () {
  // console.log('-----------------___ready 2');
    initDiscount60();
    initShopsMetro();
    viravnivanie();
    // clearYaSov();
    bottomInit();
    initMap();/* Карта для страницы доставка*/
    initMobileBasketAdd();//кнопка для добавления в корзину на мобильных
    window.setTimeout("viravnivanie();", 1000);
    window.setTimeout("viravnivanie();", 2000);
    window.setTimeout("viravnivanie();", 5000);


    $(document).on('click', '.gtm-detail-add-to-basket', function(){sendItemIdToRR($(".gtm-detail-product-page").data('id'))});
    $(document).on('click', '.gtm-list-add-to-basket', function(){sendItemIdToRR($(this).closest('li').find('.gtm-list-item').data('id'))});
    $(document).on('click', '.js-rr-buy', function(){sendItemIdToRR($(this).data('id'))});

});

function sendItemIdToRR(itemId){
  $("#rr-basket").data('products', itemId);
  console.log('RR cart sent-- '+itemId);
  retailrocket.markup.render();
  console.log('and render');
}


function initMobileBasketAdd(){
  // console.log('init mobile basket button');
  $( '.js-button-add-compare').on('click', function(e){
    e.preventDefault();
    var $this=$(this);
    if($this.hasClass('added')) {
      location.href='/compare';
      return;
    }
    $.post("/cart/addtocompare/"+$this.data('id'));
    // $this.removeClass('js-button-add-compare');
    $this.addClass('added');
    $this.closest('.new-item-container').find('.new-item-label-compared').addClass('active');
  });
  $( '.js-button-add-desire').on('click', function(e){
    e.preventDefault();
    var $this=$(this);
    if($this.hasClass('added')) {
      location.href='/desire';
      return;
    }
    addToCartAnim("Jel", "#photoimg_"+$this.data('id'), true);
    $("#JelHeader").load("/cart/addtodesire"+$this.data('id'));
    // $this.removeClass('js-button-add-desire');
    $this.addClass('added');
    $this.closest('.new-item-container').find('.new-item-label-faved').addClass('active');
  });
  $( '.js-mobile-button-buy-new').on('click', function(e){
    var $this=$(this);
    if($this.hasClass('added')) {
      location.href='/cart';
      return;
    }
    else {
      $this.addClass('added');
      $this.removeClass('gtm-list-add-to-basket');
      $this.closest('.new-item-container').find('.new-item-label-bought').addClass('active');
    }
    $.ajax({
        url: '/cart/addtocart/'+$this.data('id'),
        cache: false
    }).done(function (html) {
      addToCartNew(
        $this.data('image'),
        $this.data('name'),
        $this.data('price'),
        $this.data('bonus-add')
      );
    });
    try {
      rrApi.addToBasket($this.data('id'))
    }
    catch (e) { }
  });

  $( '.js-mobile-button-buy').on('click', function(e){
    // console.log('init mobile basket button alter click');

  // });
  // $(document).on('click', '.js-mobile-button-buy', function(){
    // console.log('init mobile basket button click');
    // e.preventDefault();
    var $this=$(this);
    // console.log(['product-add', $this.data('id'), $this.data('image'), $this.data('name'), $this.data('price'), $this.data('bonus-add')]);
    // return;
    $.ajax({
        url: '/cart/addtocart/'+$this.data('id'),
        cache: false
    }).done(function (html) {
      addToCartNew(
        '/uploads/photo/thumbnails_250x250/'+$this.data('image'),
        $this.data('name'),
        $this.data('price'),
        $this.data('bonus-add')
      );
    });
    try {
      rrApi.addToBasket($this.data('id'))
    }
    catch (e) { }
  });
}

function initShopsMetro(){
  $('.js-button-all').on('click', function (){
    $('.top-shops-popup').toggleClass('active');
  });
  $('.js-subshops').on('click', function (){
    $(this).find('.sub-shops-wrapper').toggleClass('active');
  });
}
function bottomInit(){
  if(!$('.js-footer-menu')) return;
  $('.js-footer-menu').on('click', function(){
    $('.footer-menu').toggleClass('active');
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
  newHref+='?filter_cat='+filterCat.join('|');
  if ($('.discount-60-form .max_price').val()) newHref+='&max_price='+$('.discount-60-form .max_price').val();
  if ($('.discount-60-form .min_price').val()) newHref+='&min_price='+$('.discount-60-form .min_price').val();
  // console.log(newHref);
  location.href=newHref;
  return false;
}
function initMap(){
  if(!$('#YMapsID').length) return;
  ymaps.ready(showByCity);
  $('#cities-list').on('change',  showByCity );
}
var myMap=false;
function showByCity( ){
  var $cityId=$('#cities-list').val();
  if (!myMap) myMap = new ymaps.Map('YMapsID', { center: [63.765152, 99.449527], zoom: 3 });
  myMap.geoObjects.removeAll();
  $('.city-'+$cityId).each(function( index, value ){

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
  myMap.setCenter([$(value).data('lat'), $(value).data('lon')], $cityId==3 ? 10 : 12, {
    checkZoomRange: true
  });
});
}

function patchEvent() {
    var a = this;
    window.addEventListener("message", function (b) {
        if (/*console.log(b.data),*/ "string" == typeof b.data)
            try {
                a.data = JSON.parse(b.data)
            } catch (a) {
                return
            }
        else
            a.data = b.data;
        if (a.data && "MBR_ENVIRONMENT" === a.data.type) {
            /*console.log("Поймали");*/
            try {
                b.stopImmediatePropagation(), b.stopPropagation(), b.data = {}
            } catch (a) {
            }
        }
    }, !0)
}
function generateStyle(a, b) {
    var c = document.createElement("style"), d = "";
    for (var e in b)
        b.hasOwnProperty(e) && (d += e + ":" + b[e] + " !important;\n");
    return c.type = "text/css", c.appendChild(document.createTextNode(a + ", " + a + ":hover{" + d + "}")), c
}
function appendStyleToNode(a, b) {
    var c = generateStyle(a, b);
    document.body.appendChild(c)
}

var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver, target = document.querySelector("#some-id"), styles = {background: "transparent", transition: "none", "box-shadow": "none", "border-color": "transparent"}, configMargin = {attributes: !0, attributeFilter: ["style"]}, observer = new MutationObserver(function (a) {
    a.forEach(function (a) {
        "childList" === a.type && [].slice.call(a.addedNodes).forEach(function (a) {
            if ("DIV" === a.tagName && a.querySelector('[href*="sovetnik.market.yandex.ru"]')) {
                 setTimeout(function () {
                    var b = function () {
                        appendStyleToNode("#" + a.id, {"pointer-events": "none"}), a.removeEventListener("mouseover", b, !0), a.removeEventListener("mouseenter", b, !0)
                    };
                    a.addEventListener("mouseover", b, !0), a.addEventListener("mouseenter", b, !0)
                }, 1e4), appendStyleToNode("#" + a.id, styles), appendStyleToNode("#" + a.id + " *", {opacity: "0", "pointer-events": "none"});
                var b = new MutationObserver(function () {
                    var a = document.documentElement.style.marginTop;
                    a && 0 !== parseInt(a, 10) && (document.documentElement.style.marginTop = "")
                }), c = new MutationObserver(function () {
                    var a = document.body.style.marginTop;
                    a && 0 !== parseInt(a, 10) && (document.documentElement.style.marginTop = "")
                });
                setTimeout(function () {
                    b.disconnect(), c.disconnect(), b = null, c = null
                }, 1e4), b.observe(document.documentElement, this.configMargin), c.observe(document.body, this.configMargin), document.documentElement.style.marginTop = ""
            }
            "DIV" === a.tagName && a.innerHTML.indexOf("offer.takebest.pw") !== -1 && a.remove()
        })
    })
}), config = {childList: !0};

document.body ? (observer.observe(document.body, config), patchEvent()) : setTimeout(function () {
    observer.observe(document.body, config)
}, 100);
