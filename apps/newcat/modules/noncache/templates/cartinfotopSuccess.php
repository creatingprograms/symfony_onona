<?php if ($productsCount > 0): ?>
    <span><?= $productsCount ?> товаров / <?= $totalCost ?> р.</span>
<?php else: ?>
    <span>Корзина пуста</span>
<?php endif; ?>