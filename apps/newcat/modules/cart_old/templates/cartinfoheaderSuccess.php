<?php if ($productsCount > 0): ?>

                                    <a href="/cart" class="arrow"></a>
                                    <a href="/cart" class="card-btn"></a>
                                    <a href="/cart"><div class="text">    <div class="num"><?= $productsCount ?> товаров</div>
    <div class="price">на <?= $totalCost ?> р.</div>
</div></a>
<?php else: ?>
                                    <a class="arrow"></a>
                                    <a class="card-btn"></a>
                                    <a><div class="text" style="padding-top: 13px;">Корзина пуста
                                        </div></a>
    
<?php endif; ?>