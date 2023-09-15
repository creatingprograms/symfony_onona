<?
  global $isTest;
  $caltatType=get_slot('caltat');
  if (explode('?', $_SERVER['REQUEST_URI'])[0]=='/') $caltatType=-1;
  if ($caltatType==2){
    $basket=unserialize(sfContext::getInstance()->getUser()->getAttribute('products_to_cart'));
    foreach($basket as $id => $basketLine){
      // $product=ProductTable::getInstance()->findOneById($id);
      $productsInBasket[]=[
        'id' => $id,
        // 'name' => $product->getName(),
        'quantity' => $basketLine['quantity'],
        'price' => $basketLine['price_w_discount'],
      ];
    }
  }
  if(!$isTest && in_array($caltatType, [0, 2, 10, 4])):?>
    <script>
      window.caltatData = {
        pageType: <?= $caltatType ?>,
        <? if($caltatType==0) :?>
        product: {
          id: <?= get_slot('caltat_product') ?> //Id товара
        },
        <? endif ?>
        <? if($caltatType==2) : //просмотр корзины ?>
        basketProducts:<?= json_encode($productsInBasket) ?>,
        <? endif ?>
        <? if($caltatType==10) : //просмотр каталога ?>
        category: {
          id: <?= get_slot('caltat_cat') ?>  //Id категории
        },
        parameters:<?= json_encode(get_slot('caltat_cat_params')) ?>,
        <? endif ?>

      };
    </script>

  <? else :?>
  <?/*
    <div style=" position: fixed; top: 0; left: 0; width: 500px; height: 900px; background: #ccc; z-index: 100500;">
    <pre>
      pageType: <?= $caltatType ?>,
      product: {
        id: <?= get_slot('caltat_product') ?> //Id товара
      },
      basketProducts:<?= json_encode($productsInBasket) ?>,
      category: {
        id: <?= get_slot('caltat_cat') ?>  //Id категории
      },
      parameters:<?= json_encode(get_slot('caltat_cat_params')) ?>,
    </pre>
    */?>
  <? endif ?>
<script>(function(){var f = function(){var s = document.createElement("script");document.getElementsByTagName("HEAD")[0].appendChild(s);s.type="text/javascript";s.async=true;s.src="https://cdn.caltat.com/api/caltatscript.aspx?id=1012054";};if (navigator.userAgent.toLowerCase().indexOf("opera")!=-1){document.addEventListener("DOMContentLoaded",f,false);}else{f();} })();</script>
