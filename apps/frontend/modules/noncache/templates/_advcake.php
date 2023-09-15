<?
  global $isTest;
  $advcakeType=get_slot('advcake');
  if (explode('?', $_SERVER['REQUEST_URI'])[0]=='/') $advcakeType=1;
  // $advcakeType=1;

  if($advcakeType){
    $basket=unserialize(sfContext::getInstance()->getUser()->getAttribute('products_to_cart'));
    foreach($basket as $id => $basketLine){
      $product=ProductTable::getInstance()->findOneById($id);
      $genCat=$product->getGeneralCategory();
      $productsInBasket[]=[
        'id' => $id,
        'name' => $product->getName(),
        'categoryId' => $genCat->getId(),
        'categoryName' => $genCat->getName(),
        'quantity' => $basketLine['quantity'],
        'price' => $basketLine['price_w_discount'],
      ];
    }
    ?>

    <? if (!$isTest) : ?>
      <script>
        window.advcake_data = window.advcake_data || [];
        window.advcake_data.push({
          pageType: <?= $advcakeType ?>,
          user: <?= $sf_user->isAuthenticated() ? "{email: '".md5(sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress())."'}" : 'false' ?>,
          <? if($advcakeType!=6) { // Страница "спасибо за заказ" ?>
            basketProducts: <?= json_encode($productsInBasket) ?>,
          <? } ?>
          <? if($advcakeType==6) { // Страница "спасибо за заказ" ?>
            orderInfo: <?= json_encode(get_slot('advcake_order')) ?>,
            basketProducts: <?= json_encode(get_slot('advcake_order_basket')) ?>,
          <? } ?>
          <? if($advcakeType==2) { // Детальная страница товара
            $product=get_slot('advcake_detail');
          ?>
            currentProduct: <?= json_encode($product['product'])?>,
            currentCategory: <?= json_encode($product['category'])?>,
          <? } ?>
          <? if($advcakeType==7 || $advcakeType==3) { // Страница "спасибо за заказ"
            $productsList=get_slot('advcake_list');
          ?>
              products: <?= json_encode($productsList['products']) ?>,
              <? if($advcakeType==3) {?>
                <?/*currentCategory: <?= print_r($productsList['category'], true) ?>,*/?>
                currentCategory: <?= json_encode($productsList['category']) ?>,
              <?}
          } ?>
        });
      </script>
    <? else :?>
      <?/*
      <div style=" position: fixed; top: 0; left: 0; width: 500px; height: 900px; background: #ccc; z-index: 100500;"><pre>
        pageType: <?= $advcakeType ?>,
        user: <?= $sf_user->isAuthenticated() ? "{email: '".md5(sfContext::getInstance()->getUser()->getGuardUser()->getEmailAddress())."'}" : 'false' ?>,
        <? if($advcakeType!=6) { // Страница "спасибо за заказ" ?>
          basketProducts: <?= print_r($productsInBasket, true) ?>,
        <? } ?>
        <? if($advcakeType==6) { // Страница "спасибо за заказ" ?>
          orderInfo: <?= print_r(get_slot('advcake_order'), true) ?>,
          basketProducts: <?= print_r(get_slot('advcake_order_basket'), true) ?>,
        <? } ?>
        <? if($advcakeType==2) { // Детальная страница товара
          $product=get_slot('advcake_detail');
        ?>
          currentProduct: <?= print_r($product['product'], true)?>,
          currentCategory: <?= print_r($product['category'], true)?>,
        <? } ?>
        <? if($advcakeType==7 || $advcakeType==3) { // Страница "спасибо за заказ"
          $productsList=get_slot('advcake_list');
        ?>
            products: <?= print_r($productsList['products'], true) ?>,
            <? if($advcakeType==3) {?>
              <?//currentCategory: <?= print_r($productsList['category'], true) ?>,?>
              currentCategory: <?= print_r($productsList['category'], true) ?>,
            <?}
        } ?>
      });

      </pre></div>
      */?>
    <? endif ?>
  <?}?>
