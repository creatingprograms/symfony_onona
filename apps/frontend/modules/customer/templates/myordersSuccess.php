<?
global $isTest;
$h1='Мои заказы';
slot('breadcrumbs', [
  ['text' => 'Личный кабинет', 'link'=>'/lk'],
  ['text' => $h1],
]);
slot('h1', $h1);
slot('catalog-class', '-innerWhite');
?>
<main class="wrapper">
  <div class="-innerpage">
    <div class="wrapwer">
      <div >
        <table class="lk-orders">
          <thead>
            <tr>
              <th>№ заказа</th>
              <th>Дата заказа</th>
              <th>Товаров</th>
              <th>Стоимость</th>
              <th>Статус</th>
              </tr>
          </thead>
          <tbody>
            <?php
            foreach ($orders as $order):
              $products_old = $order->getText() != '' ? unserialize($order->getText()) : '';
              $TotalSumm = 0;
              $quantity = 0;
              foreach ($products_old as $key => $productInfo):
                $TotalSumm = $TotalSumm + ($productInfo['quantity'] * (($productInfo['discount']==100 || $productInfo['price_w_discount'] > 0) ? $productInfo['price_w_discount'] : $productInfo['price']));
                $quantity = $quantity + $productInfo['quantity'];
              endforeach;
              ?>
              <tr>
                <td><a href="/customer/orderdetails/<?= $order->getId() ?>"><?= $order->getPrefix() ?><?= $order->getId() ?></a></td>
                <td><?= $order->getCreatedAt() ?></td>
                <td><?=$quantity?></td>
                <td><?=$TotalSumm - $order->getBonuspay()?> руб.</td>
                <td><?= $order->getStatus() ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
