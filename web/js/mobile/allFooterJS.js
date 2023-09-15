function getPageSize(_type){

           var xScroll, yScroll;

           if (window.innerHeight && window.scrollMaxY) {

                   xScroll = document.body.scrollWidth;

                   yScroll = window.innerHeight + window.scrollMaxY;

           } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac

                   xScroll = document.body.scrollWidth;

                   yScroll = document.body.scrollHeight;

           } else if (document.documentElement && document.documentElement.scrollHeight > document.documentElement.offsetHeight){ // Explorer 6 strict mode

                   xScroll = document.documentElement.scrollWidth;

                   yScroll = document.documentElement.scrollHeight;

           } else { // Explorer Mac...would also work in Mozilla and Safari

                   xScroll = document.body.offsetWidth;

                   yScroll = document.body.offsetHeight;

           }

           var windowWidth, windowHeight;

           if (self.innerHeight) { // all except Explorer

                   windowWidth = self.innerWidth;

                   windowHeight = self.innerHeight;

           } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode

                   windowWidth = document.documentElement.clientWidth;

                   windowHeight = document.documentElement.clientHeight;

           } else if (document.body) { // other Explorers

                   windowWidth = document.body.clientWidth;

                   windowHeight = document.body.clientHeight;

           }

           // for small pages with total height less then height of the viewport

           if(yScroll < windowHeight){
                   pageHeight = windowHeight;
           } else {
                   pageHeight = yScroll;
           }

           // for small pages with total width less then width of the viewport
           if(xScroll < windowWidth){
                   pageWidth = windowWidth;
           } else {
                   pageWidth = xScroll;
           }

           if (_type == 'pageWidth') {return pageWidth;}
           if (_type == 'pageHeight') {return pageHeight;}
           if (_type == 'windowWidth') {return windowWidth;}
           if (_type == 'windowHeight') {return windowHeight;}
           if (_type == 'array') {return [pageWidth,pageHeight,windowWidth,windowHeight];}

    }






var snapper = new Snap({
    element: document.getElementById('content'),
    disable: 'right',
    touchToDrag: false
});
var addEvent = function addEvent(element, eventName, func) {
    if (element.addEventListener) {
        return element.addEventListener(eventName, func, false);
    } else if (element.attachEvent) {
        return element.attachEvent("on" + eventName, func);
    }
};

addEvent(document.getElementById('open-left'), 'click', function () {
    $("#content").css('width',getPageSize('windowWidth')+'px');
    $("#content").css('overflow','hidden');
    $("#content").css('position','fixed');
    $("#leftMenu").css('min-height',getPageSize('windowHeight')+'px');
    snapper.open('left');
});
addEvent(document.getElementById('openSearchButton'), 'click', function () {
    snapper.open('left');
});

setInterval(function(){if($('body').attr('class')==''){to_s();};},1);
function to_s(){
    $("#content").css('width',getPageSize('windowWidth')+'px');
    $("#content").css('overflow','auto');
    $("#content").css('position','relative');
    $("#leftMenu").css('min-height',getPageSize('windowHeight')+'px');
}
/* Prevent Safari opening links when viewing as a Mobile App */
(function (a, b, c) {
    if (c in b && b[c]) {
        var d, e = a.location,
                f = /^(a|html)$/i;
        a.addEventListener("click", function (a) {
            d = a.target;
            while (!f.test(d.nodeName))
                d = d.parentNode;
            "href" in d && (d.href.indexOf("http") || ~d.href.indexOf(e.host)) && (a.preventDefault(), e.href = d.href)
        }, !1)
    }
})(document, window.navigator, "standalone");

$(document).ready(function () {
    initMap();/* Карта для страницы доставка*/
    $('#mainPage .promo #gallery-mainPage').slidesjs({
        width:760,
        height:300,
        navigation:{
            active:false,
            effect:"slide"
        },
        play: {
          active: false,
          auto: true,
          interval: 4000,
          swap: true,
          pauseOnHover: true,
          restartDelay: 2500
        }
    });

});
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
