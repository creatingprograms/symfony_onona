function sendDetailProductPage(products, productsImpressions){
  // console.log('sendDetailProductPage');
  // console.log(products);
  // console.log(productsImpressions);
  dataLayer.push({
    'event': 'productDetail',
      'ecommerce': {
      	'impressions': productsImpressions,
      	'detail': {                           // данные о деталях товара
          'actionField': {'list': 'Apparel Gallery'},
  			  'products': products
    		}
  	  }
  });
}
function sendAddTChart(product){
  console.log('------------------------------------------\n');
  console.log('sendAddTChart');
  console.log(product);
  dataLayer.push({
    'event': 'addToCart',   // добавление товара в корзину
    'ecommerce': {
  		'currencyCode': 'RUR',   // валюта
  		'add': {
        'products': [product]
      }
    }
  });
}
function sendRemoveFromChart(product){
  console.log('------------------------------------------\n');
  console.log('sendRemoveFromChart');
  console.log(product);
  dataLayer.push({
   'event': 'removeFromCart',
	 'ecommerce': {
  		'currencyCode': 'RUR',
  		'remove': {
  			 'products': [product]
  		}
   	}
  });
}
function sendThankYou(actionField, products, promocode){
  console.log('------------------------------------------\n');
  console.log('sendThankYou');
  // console.log(actionField);
  console.log(products);
  // console.log(promocode);
  if(promocode){
    dataLayer.push({
      'event': 'dop_parametrs',
    	'dimension1': promocode // промокод
    });
  }
  dataLayer.push({
   'event': 'transaction',
  	'ecommerce': {
  		'purchase': {
  			 'actionField': actionField,
  			 'products': products
  		}
  	}
  });
}
function sendCheckout(products){
  console.log('------------------------------------------\n');
  console.log('sendCheckout(products)');
  console.log(products);
  dataLayer.push({
   'event': 'checkout',     // данные о процессе оформления покупки (чекаут)
  	'ecommerce': {
  		'checkout': {
  			'actionField': {
  				'step': 1           // шаг чекаута  (начинается с 1)
  			 },
  			'products': products
  		},
    }
  });
}
function sendCategoryView(products){
  console.log('------------------------------------------\n');
  console.log('sendCategoryView');
  console.log(products);
  dataLayer.push({
    'event': 'productView',
    'ecommerce': {
    'currencyCode': 'RUR',  // валюта.
			'impressions': products
    }
	});
}
function sendItemClick(product){
  console.log('------------------------------------------\n');
  console.log('sendItemClick');
  console.log(product);
  console.log('list:'+product.list);
  dataLayer.push({
    'event': 'productClick',
    		  'ecommerce': {
    			'click': {
    			  'actionField': {
      				'list': product.list,       // тип страницы, на которой показан товар
      				'products': [product]
    			  }
      		}
      	}
  });
}
function sendItemAddToChart(product){
  console.log('------------------------------------------\n');
  console.log('sendItemAddToChart');
  console.log(product);
  dataLayer.push({
   'event': 'addToCart',   // добавление товара в корзину
   'ecommerce': {
  		'currencyCode': 'RUR',   // валюта
  		'add': {
        'products': [product]
      }
    }
  });
}
function sendBannersImpressions(promotions){
  // console.log('sendBannersImpressions');
  // console.log(promotions);
  dataLayer.push({
    'event': 'promoView',
    	 'ecommerce': {
    		  'promoView': {
    			  'promotions': promotions
    		}
    }
  });
}
function sendBannersClick(promotions){
  // console.log('sendBannersClick');
  // console.log(promotions);
  dataLayer.push({
    'event': 'promotionClick',
    	'ecommerce': {
    		'promoClick': {
    			'promotions': promotions
    		}
    	}
    });
}
$(document).on('ready', function(){

  //Отправка просмотров баннеров
  if($(".gtm-banner").length){
    var promotions=[];
    $(".gtm-banner").each(function (index, item){
      var $item=$(item);
      if($item.hasClass('swiper-slide-duplicate')) return;
      promotions.push({
        'id': $item.data('id'),
        'name': $item.data('name'),
        'creative': $item.data('creative'),
        'position': $item.data('position'),
      });
    });
    sendBannersImpressions(promotions);
  }

  //Клик по баннеру
  $(document).on('click', ".gtm-banner", function(){
    var promotions=[];
    var $item=$(this);
    promotions.push({
      'id': $item.data('id'),
      'name': $item.data('name'),
      'creative': $item.data('creative'),
      'position': $item.data('position'),
    });
    sendBannersClick(promotions);
  });

  //кладем в корзину из детальной
  $(document).on('click', '.gtm-detail-add-to-basket', function(){
    $page=$(".gtm-detail-product-page");
    var list=$.cookie('item_list'+$page.data('id'));
    // console.log('list:'+list+'|item_list'+$page.data('id'));
    if (list===undefined) list='Детальная страница';

    var product={
      'id': $page.data('id'),        // id товара
      'name': $page.data('name'),    // название товара.
      'price': $page.data('price'),            //  цена.
      'brand': $page.find('.manufacturer-name').text(),         // название бренда.
      'category': $page.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
      'position': 0,              // позиция товара на странице, начинается с 0.
      'list': list,         // тип страницы на которой показан товар
    };

    sendItemAddToChart(product);
  });

  //кладем в корзину из списка
  $(document).on('click', '.gtm-list-add-to-basket', function(){
    var $item=$(this).closest('li').find('.gtm-list-item');
    var list=$(this).closest(".gtm-category-show").data('list');
    if(list===undefined) list=$(this).closest(".c .content").data('list'); //главная в контейнере liTip
    console.log('.gtm-list-add-to-basket list is '+list);
    $.cookie('item_list'+$item.data('id'), list, { expires: 30, path: '/' });
    var product={
      'id': $item.data('id'),        // id товара
      'name': $item.data('name'),    // название товара.
      'price': $item.data('price'),            //  цена.
      'brand': 'not set',         // название бренда.
      'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
      'position': $item.data('position'),
      'list': list,          // тип страницы на которой показан товар
      'quantity': 1                      // количество
    };
    // console.log('заполнили куку'+'item_list'+$item.data('id')+'значением '+list);
    // console.log($.cookie('item_list'+$item.data('id')));
    sendItemAddToChart(product);
  });

  //клик по товару
  $(document).on('click', '.gtm-link-item', function(){
    var $item=$(this).closest('li').find('.gtm-list-item');
    var list=$(this).closest(".gtm-category-show").data('list');
    if(list===undefined) list=$(this).closest(".c .content").data('list'); //главная в контейнере liTip
    $.cookie('item_list'+$item.data('id'), list, { expires: 30, path: '/' });
    var product={
      'id': $item.data('id'),        // id товара
      'name': $item.data('name'),    // название товара.
      'price': $item.data('price'),            //  цена.
      'brand': 'not set',         // название бренда.
      'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
      'position': $item.data('position'),
      'list': list          // тип страницы на которой показан товар
    }
    // console.log('заполнили куку'+'item_list'+$item.data('id')+'значением '+list);
    // console.log($.cookie('item_list'+$item.data('id')));
    sendItemClick(product);
  });

  //Страница категории
  if($(".gtm-category-show").length){
    $(".gtm-category-show:visible").each(function (indexList, itemsList){
      var products=[];
      var list=$(itemsList).data('list');
      $(itemsList).find(".gtm-list-item").each(function (index, item){
        var $item=$(item);
        products.push({
          'id': $item.data('id'),        // id товара
          'name': $item.data('name'),    // название товара.
          'price': $item.data('price'),            //  цена.
          'brand': 'not set',         // название бренда.
          'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
          'position': index,
          'list': list          // тип страницы на которой показан товар
        });
      });
      sendCategoryView(products);
    });
  }

  //показ скрытой вкладки
  $('.gtm-check-invisible').on('click', function(){
    var products=[];
    var $itemsList=$(this).closest('.gtm-tabset').find('.gtm-category-show:visible');
    var list=$itemsList.data('list');
    $itemsList.find(".gtm-list-item").each(function (index, item){
      var $item=$(item);
      products.push({
        'id': $item.data('id'),        // id товара
        'name': $item.data('name'),    // название товара.
        'price': $item.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': index,
        'list': list          // тип страницы на которой показан товар
      });
    });
    sendCategoryView(products);
  });

  //Детальная товара
  if($(".gtm-detail-product-page").length){
    var
      $page=$(".gtm-detail-product-page"),
      products=[],
      productsImpressions=[]
    ;
    var list=$.cookie('item_list'+$page.data('id'));
    if (list===undefined) list='Детальная страница';
    products.push({
      'id': $page.data('id'),        // id товара
      'name': $page.data('name'),    // название товара.
      'price': $page.data('price'),            //  цена.
      'brand': $page.find('.manufacturer-name').text(),         // название бренда.
      'category': $page.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
      'position': 0,              // позиция товара на странице, начинается с 0.
      'list': list,         // тип страницы на которой показан товар
    });

    //.gtm-detail-item
    $(".gtm-detail-item").each(function (index, item){
      var $item=$(item);
      productsImpressions.push({
        'id': $item.data('id'),        // id товара
        'name': $item.data('name'),    // название товара.
        'price': $item.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $item.data('index'),              // позиция товара на странице, начинается с 0.
        'list': $item.data('list'),         // тип страницы на которой показан товар
      });
    });
    sendDetailProductPage(products, productsImpressions);
  }

  //Первый шаг чекаута
  if($("#gtm-checkout-one").length){
    var products=[];
    $(".gtm-checkout-item").each(function (index, item){
      var $item=$(item).find('.count');
      var list=$.cookie('item_list'+$item.data('id'));
      if (list===undefined) list='Корзина';
      products.push({
        'id': $item.data('id'),        // id товара
        'name': $item.data('name'),    // название товара.
        'price': $item.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $item.data('position'),              // позиция товара на странице, начинается с 0.
        'list': list,         // тип страницы на которой показан товар
        'quantity': $item.find('.cartCount').text()                      // количество
      });
    });
    sendCheckout(products);
  }

  //страница Спасибо
  if($('#gtm-thank-you').length){
    var
      $div=$('#gtm-thank-you'),
      id=$div.find('#order-id').text(),
      revenue=$div.find('#order-total').text(),
      shipping=$div.find('#order-shipping').text(),
      promocode=$div.data('coupon'),
      products=[]
    ;
    var actionField={
      'id': id, // id транзакции
      'revenue': revenue,  // прибыль (общая сумма)
      'shipping': shipping    // 		стоимость доставки
    };
    $(".gtm-thankyou-item").each(function (index, item){
      var $item=$(item);
      var list=$.cookie('item_list'+$item.data('id'));
      if (list===undefined) list='Страница спасибо';
      products.push({
        'id': $item.data('id'),        // id товара
        'name': $item.data('name'),    // название товара.
        'price': $item.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $item.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $item.data('index'),              // позиция товара на странице, начинается с 0.
        'list': list,         // тип страницы на которой показан товар
        'quantity': $item.data('quant')                      // количество
      });
      var list=$.cookie('item_list'+$item.data('id'), null, { expires: 30, path: '/' });
    });
    sendThankYou(actionField, products, promocode);
  }

  //user id
  if($('#gtm-user-id').length){
    // console.log('user id');
    dataLayer.push({
      'event': 'user ID',
      'userId' : $('#gtm-user-id').data('id'),
    });
  }

  //Уменьшение количества в корзине
  $('.gtm-decrement-in-cart').on('click', function(){
    var
      $row   = $(this).closest('tr'),
      $td    = $row.find('.count'),
      id     = $td.data('id'),
      name   = $td.data('name'),
      price  = $td.data('price'),
      pos    = $td.data('position'),
      quant  = 1;
      var list=$.cookie('item_list'+id);
      if (list===undefined) list='Корзина';

      var product = {
       'id': id,        // id товара
       'name': name,    // название товара.
       'price': price,            //  цена.
       'brand': 'not set',         // название бренда.
       'category': $td.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
       'position': pos,              // позиция товара на странице, начинается с 0.
       'list': list,         // тип страницы на которой показан товар
       'quantity': quant                      // количество
     };
     // console.log( '.gtm-decrement-in-cart' );
     sendRemoveFromChart(product);
  });

  //Увеличение количества в корзине
  $('.gtm-increment-in-cart').on('click', function(){
    var
      $row   = $(this).closest('tr'),
      $td    = $row.find('.count'),
      id     = $td.data('id'),
      name   = $td.data('name'),
      price  = $td.data('price'),
      pos    = $td.data('position'),
      quant  = 1;
      var list=$.cookie('item_list'+id);
      if (list===undefined) list='Корзина';
      var product = {
       'id': id,        // id товара
       'name': name,    // название товара.
       'price': price,            //  цена.
       'brand': 'not set',         // название бренда.
       'category': $td.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
       'position': pos,              // позиция товара на странице, начинается с 0.
       'list': list,         // тип страницы на которой показан товар
       'quantity': quant                      // количество
     };
     sendAddTChart(product);
  });

  //Удаление строки из корзины
  $('.gtm-delete-from-chart').on('click', function(){
    var
      $row   = $(this).closest('tr'),
      $td    = $row.find('.count'),
      id     = $td.data('id'),
      name   = $td.data('name'),
      price  = $td.data('price'),
      pos    = $td.data('position'),
      quant  = $td.find('.cartCount').text();
      var list=$.cookie('item_list'+id);
      if (list===undefined) list='Корзина';
      var product = {
       'id': id,        // id товара
       'name': name,    // название товара.
       'price': price,            //  цена.
       'brand': 'not set',         // название бренда.
       'category': $td.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
       'position': pos,              // позиция товара на странице, начинается с 0.
       'list': list,         // тип страницы на которой показан товар
       'quantity': quant                      // количество
     };
    // console.log( '.gtm-delete-from-chart' );
    sendRemoveFromChart(product);
  });
});

/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD (Register as an anonymous module)
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// Node/CommonJS
		module.exports = factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (arguments.length > 1 && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {},
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling $.cookie().
			cookies = document.cookie ? document.cookie.split('; ') : [],
			i = 0,
			l = cookies.length;

		for (; i < l; i++) {
			var parts = cookies[i].split('='),
				name = decode(parts.shift()),
				cookie = parts.join('=');

			if (key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));
