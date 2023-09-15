$(document).ready(function(){
  initGTM()
});
  var isTest = false;

  if (window.location.origin.indexOf('://dev.') + 1) isTest=true;

  function initGTM(){

    if($(".gtm-banner").length){//Отправка просмотров баннеров
      var promotions=[];
      $(".gtm-banner").each(function (index, item){
        var $item=$(item);
        if($item.parent().hasClass('swiper-slide-duplicate')) return;
        promotions.push({
          'id': $item.data('id'),
          'name': $item.data('name'),
          'creative': $item.data('creative'),
          'position': $item.data('position'),
        });
      });
      sendBannersImpressions(promotions);
    }

    $(document).on('click', ".gtm-banner", function(){//Клик по баннеру
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

    $(document).on('click', '.gtm-detail-add-to-basket', function(){ //кладем в корзину из детальной
      var $page=$(".gtm-detail-product-page");
      if(!$page.length) return;
      var list=$.cookie('item_list'+$page.data('id'));
      // console.log('list:'+list+'|item_list'+$page.data('id'));
      if (list===undefined) list='Детальная страница';

      var product={
        'id': $page.data('id'),        // id товара
        'name': $page.data('name'),    // название товара.
        'price': $page.data('price'),            //  цена.
        'brand': $page.find('.gtm-manufacturer-name').data('manufacturer'),         // название бренда.
        'category': $page.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': 0,              // позиция товара на странице, начинается с 0.
        'list': list,         // тип страницы на которой показан товар
      };

      sendItemAddToChart(product);
    });

    $(document).on('click', '.gtm-list-add-to-basket', function(){ //кладем в корзину из списка
      var $item=$(this).closest('.gtm-cat-list-item');
      var list=$(this).closest(".gtm-items-list").data('cat-name');
      // console.log('.gtm-list-add-to-basket list is '+list);
      $.cookie('item_list'+$item.data('id'), list, { expires: 30, path: '/' });
      var product={
        'id': $item.data('id'),        // id товара
        'name': $item.data('name'),    // название товара.
        'price': $item.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $item.data('cat'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $item.index(),
        'list': list,          // тип страницы на которой показан товар
        'quantity': 1                      // количество
      };
      sendItemAddToChart(product);
    });

    $(document).on('click', '.gtm-cat-list-item', function(){ //клик по товару
      var $item=$(this).closest('.gtm-cat-list-item');
      var list=$(this).closest(".gtm-items-list").data('cat-name');

      $.cookie('item_list'+$item.data('id'), list, { expires: 30, path: '/' });
      var product={
        'id': $item.data('id'),        // id товара
        'name': $item.data('name'),    // название товара.
        'price': $item.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $item.data('cat'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $item.index(),
        'list': list          // тип страницы на которой показан товар
      }
      sendItemClick(product);
    });

    if($(".gtm-items-list").length){ //Страница категории
      $(".gtm-items-list").each(function (indexList, itemsList){
        // if($(itemsList).hasClass('gtm-list-not-send')) return;
        var products=[];
        var list=$(itemsList).data('cat-name');
        $(itemsList).find(".gtm-cat-list-item").each(function (index, item){
          var $item=$(item);
          products.push({
            'id': $item.data('id'),        // id товара
            'name': $item.data('name'),    // название товара.
            'price': $item.data('price'),            //  цена.
            'brand': 'not set',         // название бренда.
            'category': $item.data('cat'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
            'position': $item.index(),
            'list': list          // тип страницы на которой показан товар
          });
        });
        sendCategoryView(products);
      });
    }

    if($(".gtm-detail-product-page").length){ //Детальная товара
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
        'brand': $page.find('.gtm-manufacturer-name').data('manufacturer'),         // название бренда.
        'category': $page.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': 0,              // позиция товара на странице, начинается с 0.
        'list': list,         // тип страницы на которой показан товар
      });

      //.gtm-detail-item
      $(".gtm-cat-list-item").each(function (index, item){
        var $item=$(item);
        productsImpressions.push({
          'id': $item.data('id'),        // id товара
          'name': $item.data('name'),    // название товара.
          'price': $item.data('price'),            //  цена.
          'brand': 'not set',         // название бренда.
          'category': $item.data('cat'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
          'position': index,              // позиция товара на странице, начинается с 0.
          'list': $item.closest('.gtm-items-list').data('cat-name'),         // тип страницы на которой показан товар
        });
      });
      sendDetailProductPage(products, productsImpressions);
    }

    if($(".gtm-checkout-one").length){ //Первый шаг чекаута
      var products=[];
      $(".gtm-checkout-item").each(function (index, item){
        var $item=$(item);
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
          'quantity': $item.find('.gtm-basket-item-count').val()                      // количество
        });
      });
      sendCheckout(products);
    }

    if($('#gtm-thank-you').length){ //страница Спасибо
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

    $('.gtm-decrement-in-cart').on('click', function(){ //Уменьшение количества в корзине
      var
        $row   = $(this).closest('.gtm-checkout-item'),
        quant  = 1;
        var list=$.cookie('item_list'+$row.data('id'));
        if (list===undefined) list='Корзина';

        var product = {
         'id': $row.data('id'),        // id товара
         'name': $row.data('name'),    // название товара.
         'price': $row.data('price'),            //  цена.
         'brand': 'not set',         // название бренда.
         'category': $row.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
         'position': $row.data('position'),              // позиция товара на странице, начинается с 0.
         'list': list,         // тип страницы на которой показан товар
         'quantity': quant                      // количество
       };
       // console.log( '.gtm-decrement-in-cart' );
       sendRemoveFromChart(product);
    });

    $('.gtm-increment-in-cart').on('click', function(){ //Увеличение количества в корзине
      var
        $row   = $(this).closest('.gtm-checkout-item'),
        quant  = 1;
      var list=$.cookie('item_list'+$row.data('id'));
      if (list===undefined) list='Корзина';
      var product = {
        'id': $row.data('id'),        // id товара
        'name': $row.data('name'),    // название товара.
        'price': $row.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $row.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $row.data('position'),              // позиция товара на странице, начинается с 0.
        'list': list,         // тип страницы на которой показан товар
        'quantity': quant                      // количество
       };
       sendAddTChart(product);
    });

    $('.gtm-delete-from-chart').on('click', function(){ //Удаление строки из корзины
      var
        $row   = $(this).closest('.gtm-checkout-item'),
        quant  = $row.find('.gtm-basket-item-count').val();
      var list=$.cookie('item_list'+$row.data('id'));
      if (list===undefined) list='Корзина';
      var product = {
        'id': $row.data('id'),        // id товара
        'name': $row.data('name'),    // название товара.
        'price': $row.data('price'),            //  цена.
        'brand': 'not set',         // название бренда.
        'category': $row.data('category'),     // категория товара, можно передавать вот таким способом 'category' : 'Игрушки/Игрушки для малышей/12 месяцев/Прыгунки и ходунки' (категорий через / может быть максимум 5).
        'position': $row.data('position'),              // позиция товара на странице, начинается с 0.
        'list': list,         // тип страницы на которой показан товар
        'quantity': quant                      // количество
      };
      console.log( '.gtm-delete-from-chart' );
      sendRemoveFromChart(product);
  });

    $('.js-tests-sort-cat').on('change', function(){//Изменение порядка сортировки
      var sort;
      var $this=$(this);
      if($this.data('sort')=='rating') sort=2;
      if($this.data('sort')=='date') sort=3;
      if($this.data('sort')=='price') {
        sort=3;
        if($this.data('direction')=='desc') sort=4;
      }
      sendSortOrder(sort);

    })

    $('.js-tests-sort').on('change', function(){//Изменение порядка сортировки
      var sort;
      var $this=$(this);
      if($this.data('sort')=='rating') sort=2;
      if($this.data('sort')=='date') sort=3;
      if($this.data('sort')=='price') {
        sort=3;
        if($this.data('direction')=='desc') sort=4;
      }
      sendSortOrder(sort);

    })

    if($('.gtm-user-id').length){//Отправка пользователю
      if(isTest){
        console.log('send user id ' + $('.gtm-user-id').data('id'));
      }
      else{
        dataLayer.push({
          'event': 'user ID',
          'userId' : $('.gtm-user-id').data('id'),
        });
      }
    }

    // console.log('init gtm fired');
  };

  $('.js-rr-send-to-basket').on('click', function(){ //Отправка дабавления в корзину retailrocket
    console.log('sent add to basket to rrApi. id is ' + $(this).data('id'));
    if(isTest){
    }
    else{
      try {
        rrApi.addToBasket($(this).data('id'));
      } catch(e) {console.log('rrApi trow exception on try to send addToBasket')}
    }
  });

  function sendSortOrder(sort){
    if(isTest){
      console.log('sendSortOrder')
      console.log(sort)
    }
    else{
      try {
        caltat.event(1008, {sorting: sort});
      } catch (e) {
        console.log('caltat sending sort exception');
      }
    }
  }

  function sendDetailProductPage(products, productsImpressions){//Отправка детальной информации о товаре
    if(isTest){
      console.log('sendDetailProductPage');
      console.log(products);
      console.log(productsImpressions);
    }
    else{
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
  }

  function sendAddTChart(product){//Положит в корзину
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendAddTChart');
      console.log(product);
    }
    else{
      try{
        caltat.event(1001, {id: product.id})
      } catch (e) {
        console.log('caltat sending addToCart exception');
      }
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
  }

  function sendRemoveFromChart(product){//Удаление из корзины
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendRemoveFromChart');
      console.log(product);
    }
    else{
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
  }

  function sendThankYou(actionField, products, promocode){//Страница спасибо
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendThankYou');
      // console.log(actionField);
      console.log(products);
      // console.log(promocode);
    }
    else{
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
  }

  function sendCheckout(products){//Первый шаг чекаута
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendCheckout(products)');
      console.log(products);
    }
    else{
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
  }

  function sendCategoryView(products){//Просмотр категории
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendCategoryView');
      console.log(products);
    }
    else{
      dataLayer.push({
        'event': 'productView',
        'ecommerce': {
        'currencyCode': 'RUR',  // валюта.
    			'impressions': products
        }
    	});
    }
  }

  function sendItemClick(product){//Клик по товару
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendItemClick');
      console.log(product);
      console.log('list:'+product.list);
    }
    else{
      try{
        caltat.event(1007, {id: product.id})
      } catch (e) {
        console.log('caltat sending productClick exception');
      }
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
  }

  function sendItemAddToChart(product){// добавление товара в корзину
    if(isTest){
      console.log('------------------------------------------\n');
      console.log('sendItemAddToChart');
      console.log(product);
    }
    else{
      try {
        console.log('sent add to basket to rrApi. id is ' + product.id)
        rrApi.addToBasket(product.id)
      } catch(e) {console.log('rrApi trow exception on try to send addToBasket')}
      dataLayer.push({
       'event': 'addToCart',   // добавление товара в корзину
       'ecommerce': {
      		'currencyCode': 'RUR',   // валюта
      		'add': {
            'products': [product]
          }
        }
      });
      try{
        caltat.event(1001, {id: product.id})
      } catch (e) {
        console.log('caltat sending addToCart exception');
      }
    }
  }

  function sendBannersImpressions(promotions){//Просмотр баннеров
    if(isTest){
      console.log('sendBannersImpressions');
      console.log(promotions);
    }
    else{
      dataLayer.push({
        'event': 'promoView',
        	 'ecommerce': {
        		  'promoView': {
        			  'promotions': promotions
        		}
        }
      });
    }
  }

  function sendBannersClick(promotions){//Клик по баннеру
    if(isTest){
      console.log('sendBannersClick');
      console.log(promotions);
    }
    else{
      dataLayer.push({
        'event': 'promotionClick',
      	'ecommerce': {
      		'promoClick': {
      			'promotions': promotions
      		}
      	}
      });
    }
  }
