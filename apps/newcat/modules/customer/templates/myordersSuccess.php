<section class="wellCome">
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>
           <a href="/lk">личный кабинет</a>
        </li>
        <li>
            мои заказы
        </li>
    </ul>
    <h1 class="mobile-only">Мои заказы</h1>
    <div class="wrapwer">
        <div style="padding:5px">
            <table class="table-my-orders">
                <thead>
                  <tr>
                        <th>№ заказа</th>
                        <th>дата заказа</th>
                        <th>товаров</th>
                        <th>стоимость</th>
                        <th>статус</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($orders as $order):

                        $products_old = $order->getText() != '' ? unserialize($order->getText()) : '';
                        $TotalSumm = 0;
                        $quantity = 0;
                        foreach ($products_old as $key => $productInfo):
                            $TotalSumm = $TotalSumm + ($productInfo['quantity'] * $productInfo['price']);
                            $quantity = $quantity + $productInfo['quantity'];
                        endforeach;
                        ?>
                        <tr>
                            <td><a href="/customer/orderdetails/<?= $order->getId() ?>"><?= $order->getPrefix() ?><?= $order->getId() ?></a></td>
                            <td><?= $order->getCreatedAt() ?></td>
                            <td><?=$quantity?></td>
                            <td><?=$TotalSumm?> руб.</td>
                            <td><?= $order->getStatus() ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody></table>
        </div>
        <nav id="paginator_1" class="paginator pages">
            <ol>
            </ol>
        </nav>
    </div>
</section>
