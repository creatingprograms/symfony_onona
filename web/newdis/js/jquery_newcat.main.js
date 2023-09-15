function showFullPreviousList(target){
  $(target).addClass('full-list');
  // console.log(this);
}
$(document).ready(function(){
    initSuggestions ();
    initPanel ();
    initLogin ();
    initLK ();
    initUp ();
    initAccordion ();
    initSwiperShops();
    initHamburger();
    // initMap();

    $('.promo-gallery-holder.test-result').makeGallery({
        'autoRotation':3000000
    });
    $('.brand-galery.box').makeGallery({
        'vertical':true,
        'autoRotation':3000000
    });
    $('.aside .leaders-galery.box').makeGallery({
        'vertical':true,
        'autoRotation':5000
    });
    $('.articleLeft .leaders-galery.box').makeGallery({
        'vertical':true,
        'autoRotation':5000
    });
      // console.log('------------------------filter-init');
      // alert("log('------------------------filter-init')");
    /* фильтры в каталоге начало */
    /*
    $(document).on('click', '.js-show-filters', function(){
      $(this).addClass('active');
      $('.filters-hided-block').addClass('active');
    });*/
    $(document).on('click', '.js-filters-toogle', function(){
      // console.log('filter-toggle');
      var alredyOpen=false;
      if($(this).hasClass('active')){
        alredyOpen=true;
      }
      $('.js-filters-value').removeClass('active');
      $('.js-filters-toogle').removeClass('active');
      if(alredyOpen==true){
        return;
      }
      $($(this).data('id')).addClass('active');
      $(this).toggleClass('active');
      $('html,body').animate({scrollTop: $(this).offset().top-40});
      // console.log('scroll');
    });
    $(document).on('change', '.js-is-stock-simple', function(){
      var isChecked=1*($(this).is(':checked'));
      if(isChecked){
        $('.liProdNonCount').each(function (i) {
            $(this).fadeOut(1);
        });
      }
      else{
        $('.liProdNonCount').each(function (i) {
            $(this).fadeIn(1);
        })
      }
      // console.log('js-is-stock-simple'+'|'+isChecked);

    });
    /*
    $(document).on('change', '.js-is-stock', function(){
      // console.log('js-is-stock');
      if ($(this).is(':checked')){
        $('#isStockFilter').val(1);
      }
      else{
        $('#isStockFilter').val(0);
      }
      $('#categoryFilters').submit();
    });*/
    $(document).on('change', '.js-sort-order', function(){
      // console.log('sort-order:'+$(this).find(':checked').val()+'|'+$(this).find(':checked').data('direction'));
      $('#sortOrderFilter').val($(this).find(':checked').val());
      $('#directionFilter').val($(this).find(':checked').data('direction'));
      $('#pageFromFilter').val(1);

      // $('#categoryFilters').submit();
    });
    $(document).on('change', '.js-sort-order-simple', function(){
      var $this=$(this), currentOption=$this.find(':checked'), direction=currentOption.data('direction');
      var url=$this.data('link')
      if (currentOption.val()!='sortorder') url+='?sortOrder='+currentOption.val();
      if (direction=='desc' || direction=='asc') url+='&direction='+direction;

      // console.log('sort-order-simple:'+currentOption.val()+'|'+direction+'|'+$this.data('link')+'|'+url);
      document.location.href=url;
    });

    /* фильтры в каталоге конец */

    // console.log('------------------------novice-init');
    $(document).on('click', '.js-novice-filter', function(){
      // console.log('------------------------novice-click v2');
      var thisState=$(this).hasClass('active');
      $('.js-novice-filter').removeClass('active');
      if(thisState){
        // console.log('------------------------novice-click remove');
        $('#isNovice').val('');
      }
      else{
        // console.log('------------------------novice-click add');
        $(this).addClass('active');
        $('#isNovice').val($(this).data('value'));
      }
      $('#pageFromFilter').val(1);
      $("#categoryFilters").submit();
    });

    if ($('.main-slider-tubes').length){
      var count=2;
      if (window.screen.width<768) {
        count=1;
      }
      mySwiper = new Swiper ('.main-slider-tubes', {
        loop: true,
        slidesPerView: count,
        spaceBetween: 40,
        // centeredSlides: true,
        // autoplay: 3000, //4 секунды
        // pagination: '.swiper-pagination',
        // paginationClickable: true,
        nextButton: '.main-slider-tubes .next',
        prevButton: '.main-slider-tubes .prev',
      })
    }
    resizeGallery($('.brand-galery.box').find('.galery'));
    resizeGallery($('.leaders-galery.box').find('.galery'));
    initTab();
    initPopup();
});
function initSwiperShops(){
  if ($('.main-slider-shops').length){
    var count=3;
    if (window.screen.width<768) count=1;
    mySwiper = new Swiper ('.main-slider-shops', {
      loop: true,
      slidesPerView: count,
      spaceBetween: 10,
      roundLengths: true,
      // autoHeight: true,
      // autoplay: 3000, //4 секунды
      // pagination: '.swiper-pagination',
      // paginationClickable: true,
      nextButton: '.main-slider-shops .next',
      prevButton: '.main-slider-shops .prev',
    })
  }
}

function initHamburger(){
  if (!$(".sandwich").length) return;
  $(".sandwich").on('click', function(){
    $(".nav-holder").toggle();
  });
  $(".cur").on('click', function (){
    $(this).parent().find('ul').toggle();
    $(this).parent().find('table').toggle();
    $(this).toggleClass('opened');
  });
  $(".cur-shops").on('click', function(){
    $(this).closest('li').toggleClass('active');
    $(this).closest('li').find('.cur').click();
    // $(this).closest('li').find('.cur').toggleClass('opened');
    // $(this).closest('li').find('table').toggle();
    // $(this).closest('li').find('ul').toggle();
  })

}

$(window).resize(function(){
    resizeGallery($('.brand-galery.box').find('.galery'));
    resizeGallery($('.leaders-galery.box').find('.galery'));
})
/*
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
*/
function initPopup(){
    $('body').popup({
        "opener":"#bonus",
        "popup_holder":".popup-holder:has(.bonus)",
        "popup":".popup",
        "close_btn":".close",
        "beforeOpen":function(popup_holder){
            $(popup_holder).css('left',0).hide();
        }
    }).popup({
        "opener":"#fast-order",
        "popup_holder":".popup-holder:has(.quick-view)",
        "popup":".popup",
        "close_btn":".close",
        "beforeOpen":function(popup_holder){
            $(popup_holder).css('left',0).hide();
        }
    }).popup({
        "opener":".tools .att",
        "popup_holder":".popup-holder:has(.quick-view)",
        "popup":".popup",
        "close_btn":".close",
        "beforeOpen":function(popup_holder){
            //var newContent=$(this).parents('.content').find('.popup-content>*').clone();
            idProduct = $(this).next("a").attr('id').replace("buttonId_","");
            idProduct = idProduct.replace("addToBasket3-","");

            //console.log(typeof idProduct);

            $.post("/product/preshow/"+idProduct,
                function(data){
                    newContent=data;
                    //console.log(popup_holder);
                    $(popup_holder).find('.popup>*:not(.close,.title:first)').remove().end().find('.popup').append(newContent);
                    //$(popup_holder).css('left',0).hide();
                    $(popup_holder).css('left',0).fadeIn();

                    $('.popup-holder:has(.quick-view) select:last').selectmenu({
                        style:'dropdown'
                    });
                    imgOpen=$(popup_holder).find(".img-holder img");
                    $(imgOpen).attr('src', $(imgOpen).attr('data-original'));
                });
        }
    });
}
function initTab(){
    $('.tabset .tab-control li').click(function(e){
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.tabset').find('.tab:visible').hide();
        $(this).parents('.tabset').find('.tab').eq($(this).index()).show();
        e.preventDefault();
        initSwiperShops();
    });
    $('.tabsetDelivery .tab-controlDelivery li').click(function(e){
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.tabsetDelivery').find('.tabDelivery:visible').hide();
        $(this).parents('.tabsetDelivery').find('.tabDelivery').eq($(this).index()).show();
        e.preventDefault();
    });
}
function resizeGallery(gallery){
    if(gallery.size()){
        gallery.height(gallery.find('li').outerHeight()*2);
    }
}
function initAccordion () {
    var timer=0;
    $('.cat-menu > li').mouseenter(function(){
        var _this = $(this);
        timer=setTimeout(function(){
            if(!_this.hasClass('active')){
                _this.find('.drop').slideDown(200);
            }
        },200);
    }).mouseleave(function(){
        if (timer) {
            clearTimeout(timer);
        }
        var _this = $(this);
        if(!_this.hasClass('active')){
            _this.find('.drop').slideUp(200,function(){
                $(window).trigger('resize');
            });
        }
    }).find('>a').click(function(e){
        if (timer) {
            clearTimeout(timer);
        }
        if(!$(this).parent().is('.active') && $(this).parent().find('.drop').is(':visible')){
            if (!$(this).parent().find(':animated').size()) {
                $(this).parent().addClass('active');
            }
            $(this).parents('.cat-menu').find('>li.active>ul').not($(this).parent().find('ul')).slideUp(500).parent().removeClass('active');
        }else{
            if(!$(this).parents('.cat-menu').find(':animated').size()){
                if($(this).parents('.cat-menu').find('>li.active').index()==$(this).parent().index()){
                    $(this).parent().removeClass('active');
                    $(this).parent().find('.drop').slideUp(500,function(){
                        $(window).trigger('resize');
                    });
                }else{
                    $(this).parents('.cat-menu').find('>li.active>ul').slideUp(500).parent().removeClass('active');
                    $(this).parent().toggleClass('active');
                    $(this).parent().find('.drop').slideToggle(500,function(){
                        $(window).trigger('resize');
                    });
                }

            }
        }
        e.preventDefault();
    }).parent().find('ul>li>a').click(function(){
        if(!$(this).parents('.drop').find(':animated').size()){
            if($(this).parents('.drop').find('li>ul:visible').parent().index()==$(this).parent().index()){
                $(this).parent().find('ul').slideUp(500,function(){
                    $(window).trigger('resize');
                });
                $(this).parent().removeClass('active');
            }else{
                $(this).parents('.drop').find('li>ul:visible').slideUp(500,function(){
                    $(window).trigger('resize')
                }).parent().removeClass('active');
                $(this).parent().addClass('active');
                $(this).parent().find('ul').slideToggle(500,function(){
                    $(window).trigger('resize');
                });

            }

        }
    });
}
function initUp () {
    var _h = 200;//$('.wrapper-holder').height()/7,
    footer=$('#footer-new'),
    btnUp=$('a.to-up');
    $(window).scroll(function(){
        if ($(window).scrollTop() > _h) {
            btnUp.fadeIn(200);

        if ($(window).width() > 1300) {
            btnUp.removeAttr('style').css({
                'display':'inline',
                'position':'fixed',
                'bottom':25
            });
        }else{
            btnUp.removeAttr('style').css({
                'display':'inline',
                'position':'fixed',
                'bottom':25,
                'right':10
            });

        }
            $('#isviVideoBlock').css({
                'position':'fixed',
                'bottom':0,
                'top':'inherit'
            });
        } else {
            btnUp.fadeOut(200);
        }
        if(btnUp.offset().top+btnUp.height()+25>footer.offset().top){
            btnUp.css({
                'position':'absolute',
                'top':footer.offset().top-btnUp.height()-25
            });
            $('#isviVideoBlock').css({
                'position':'absolute',
                'top':footer.offset().top-btnUp.height()-25-273
            });
        }
    })
    $('a.to-up').click(function(){
        $('body, html').animate({
            'scrollTop': 0
        }, 500);
        return false;
    })
}
jQuery.fn.makeGallery = function(o) {
    o = $.extend( {
        interval : 2000, /* интервал вращения 1000 = 1секунда */
        speed : 700, /* скорость перемещения 1000 = 1секунда */
        gallery_frame : 'promo-gallery',
        pauseOnHover:true,
        autoRotation: 4000,
        gallery_holder : 'ul',
        gallery_item : 'li',
        vertical:false
    }, o || {});
    return this.each(
        function() {
            var _phase = true;
            var main_holder = $(this);
            var _gal_item = o.gallery_item;
            var btn_prev = $('.prev',main_holder);
            var btn_next = $('.next',main_holder);
            var _holder = $(o.gallery_holder,main_holder);
            var _speed = o.speed;
            var mouseover=false;
            btn_prev.click(function(e){
                if (_phase) {
                    _phase = false;
                    oneStepMinus();
                }
                e.preventDefault()
            });
            btn_next.click(function(e){
                if (_phase) {
                    _phase = false;
                    oneStepPlus();
                }

                e.preventDefault();
            });
            function oneStepPlus () {

                if(o.vertical){
                    var step = (_holder.find(_gal_item+':last').outerHeight(true));
                    _holder.animate({
                        marginTop:step*(-1)+'px'
                    }, _speed, function(){
                        $(this).append($(this).find(_gal_item+':first'));
                        $(this).css('margin-top','0');
                        _phase = true;
                    });
                }else{
                    var step = (_holder.find(_gal_item+':last').outerWidth());
                    _holder.animate({
                        marginLeft:step*(-1)+'px'
                    }, _speed, function(){
                        $(this).append($(this).find(_gal_item+':first'));
                        $(this).css('margin-left','0');
                        _phase = true;
                    });
                }

                autoSlide();
            };
            function oneStepMinus () {
                if(o.vertical){
                    var step = (_holder.find(_gal_item+':first').outerHeight());
                    _holder.css('margin-top',-step).prepend(_holder.find(_gal_item+':last'));
                    _holder.animate({
                        marginTop:0
                    }, _speed, function(){
                        $(this).css('margin-top','0');
                        _phase = true;
                    });
                }else{
                    var step = (_holder.find(_gal_item+':first').outerWidth());
                    _holder.css('margin-left',-step).prepend(_holder.find(_gal_item+':last'));
                    _holder.animate({
                        marginLeft:0
                    }, _speed, function(){
                        $(this).css('margin-left','0');
                        _phase = true;
                    });
                }

            };
            var _timer = 0;
            function autoSlide(){
                if(!mouseover) {

                    if(_timer){
                        clearRequestTimeout(_timer);
                    }
                    _timer=requestTimeout(function(){
                        oneStepPlus()
                    },o.autoRotation);
                }

            }
            autoSlide();
            main_holder.mouseenter(function(){
                mouseover=true;
                if(_timer){
                    clearRequestTimeout(_timer);
                }
            }).mouseleave(function(){
                mouseover=false;
                if(_timer){
                    clearRequestTimeout(_timer);
                }
                autoSlide();
            });
        });
};
function initLogin () {
    var _right = $('#header').offset().left + $('#header').width() - 228,
    _top = $('#header').offset().top + 35;
    $('a.login').click(function(){
        $('#login')./*css({
            left: _right,
            top: _top
        }).*/fadeIn(200);
        return false;
    })
    $(document).click(function(e){
        if(!$(e.target).closest('.login-popup').length && !$(e.target).closest('a.login').length){
            $('.login-popup').fadeOut(200, function(){
                $('.login-popup').removeClass('error')
            });
        }
    });
    $('.login-popup .close').click(function(){
        $('.login-popup').fadeOut(200, function(){
            $('.login-popup').removeClass('error')
        });
        return false;
    })
    $('#login .red-btn input').click(function(){
        if (($('#login .input-holder input').val().length > 0) && ($('#login .input-holder input').val() != 'Логин')) {

        } else {
            $('#login').addClass('error')
        }
    })
    $('.forgot-password').click(function(){
        $('#login').fadeOut(200);
        $('#forgot-password')/*.css({
            left: _right,
            top: _top
        })*/.fadeIn(200);
        return false;
    })
}
function initLK () {
    var _right = $('#header').offset().left + $('#header').width() - 228,
    _top = $('#header').offset().top + 35;
    $('a.showLK').click(function(){
        $('#LK')./*css({
            left: _right,
            top: _top
        }).*/fadeIn(200);
        return false;
    })
    $(document).click(function(e){
        if(!$(e.target).closest('.lk-popup').length && !$(e.target).closest('a.showLK').length){
            $('.lk-popup').fadeOut(200, function(){
                $('.lk-popup').removeClass('error')
            });
        }
    });
    $('.lk-popup .close').click(function(){
        $('.lk-popup').fadeOut(200, function(){
            $('.lk-popup').removeClass('error')
        });
        return false;
    })
    $('#LK .red-btn input').click(function(){
        if (($('#LK .input-holder input').val().length > 0) && ($('#LK .input-holder input').val() != 'Логин')) {

        } else {
            $('#LK').addClass('error')
        }
    })
    $('.forgot-password').click(function(){
        $('#LK').fadeOut(200);
        $('#forgot-password')/*.css({
            left: _right,
            top: _top
        })*/.fadeIn(200);
        return false;
    })
}

//-- Подсказки при поиске
function initSuggestions() {
  $('.js-search').autocomplete({
    serviceUrl: '/suggest',
    minChars: 1,
    maxHeight: 400,
    paramName: 'text',
    formatResult: function (suggestion, currentValue) {
      return suggestion.data.html;
    },
  });
}

function initPanel () {
    $(window).scroll(function(){
        if($(window).scrollTop() > 175){
            $('.floating-menu').fadeIn(200);
        } else {
            $('.floating-menu').fadeOut(200);
        }
    })
}
/*
 * Drop in replace functions for setTimeout() & setInterval() that
 * make use of requestAnimationFrame() for performance where available
 * http://www.joelambert.co.uk
 *
 * Copyright 2011, Joe Lambert.
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

// requestAnimationFrame() shim by Paul Irish
// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
window.requestAnimFrame = (function() {
    return  window.requestAnimationFrame   ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame    ||
    window.oRequestAnimationFrame      ||
    window.msRequestAnimationFrame     ||
    function(/* function */ callback, /* DOMElement */ element){
        window.setTimeout(callback, 200 / 60);
    };
})();
/**
 * Behaves the same as setTimeout except uses requestAnimationFrame() where possible for better performance
 * @param {function} fn The callback function
 * @param {int} delay The delay in milliseconds
 */

window.requestTimeout = function(fn, delay) {
    if( !window.requestAnimationFrame      	&&
        !window.webkitRequestAnimationFrame &&
        !window.mozRequestAnimationFrame    &&
        !window.oRequestAnimationFrame      &&
        !window.msRequestAnimationFrame)
        return window.setTimeout(fn, delay);

    var start = new Date().getTime(),
    handle = new Object();

    function loop(){
        var current = new Date().getTime(),
        delta = current - start;

        delta >= delay ? fn.call() : handle.value = requestAnimFrame(loop);
    };

    handle.value = requestAnimFrame(loop);
    return handle;
};

/**
 * Behaves the same as clearInterval except uses cancelRequestAnimationFrame() where possible for better performance
 * @param {int|object} fn The callback function
 */
window.clearRequestTimeout = function(handle) {
    window.cancelAnimationFrame ? window.cancelAnimationFrame(handle.value) :
    window.webkitCancelRequestAnimationFrame ? window.webkitCancelRequestAnimationFrame(handle.value)	:
    window.mozCancelRequestAnimationFrame ? window.mozCancelRequestAnimationFrame(handle.value) :
    window.oCancelRequestAnimationFrame	? window.oCancelRequestAnimationFrame(handle.value) :
    window.msCancelRequestAnimationFrame ? msCancelRequestAnimationFrame(handle.value) :
    clearTimeout(handle);
};
$.fn.popup = function(o){
    var o = $.extend({
        "opener":".call-back a",
        "popup_holder":"#call-popup",
        "popup":".popup",
        "close_btn":".close",
        "close":function(){},
        "beforeOpen":function(){}
    },o);
    return this.each(function(){
        var container=$(this),
        opener=$(o.opener,container),
        popup_holder=$(o.popup_holder,container),
        popup=$(o.popup,popup_holder),
        close=$(o.close_btn,popup),
        bg=$('.bg',popup_holder);
        popup.css('margin',0);
        opener.click(function(e){
            o.beforeOpen.apply(this,popup_holder);
            popup_holder.fadeIn(400);
            alignPopup();
            bgResize();
            e.preventDefault();
        });
        function alignPopup(){
            if((($(window).height() / 2) - (popup.outerHeight() / 2))+ $(window).scrollTop()<0){
                popup.animate({
                    'top':0
                },400);
                return false;
            }
            if(popup.is(':animated')){
                popup.stop(true,true)
            }
            popup.animate({
                'top': (($(window).height()-popup.outerHeight())/2) + $(window).scrollTop(),
                'left': (($(window).width() - popup.outerWidth())/2) + $(window).scrollLeft()
            },400);
        }
        function bgResize(){
            var _w=$(window).width(),
            _h=$(document).height();
            bg.css({
                "height":_h,
                "width":_w+$(window).scrollLeft()
            });
        }
        $(window).resize(function(){
            if(popup_holder.is(":visible")){
                bgResize();
                alignPopup();
            }
        });
        close.add(bg).click(function(e){
            var closeEl=this;
            popup_holder.fadeOut(400,function(){
                o.close.apply(closeEl,popup_holder);
            });
            e.preventDefault();
        });
        $('body').keydown(function(e){
            if(e.keyCode=='27'){
                popup_holder.fadeOut(400);
            }
        })
    });
}
