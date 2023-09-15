<?php

mb_internal_encoding('UTF-8');
?>

<?php

foreach ($products as $product['id'] => $product) {
    echo "<li>";


    include_partial("product/productBooklet", array(
        'sf_cache_key' => $product['id'],
        'product' => $product,
        'comment' => isset($commentsAll[$product['id']]) ? $commentsAll[$product['id']] : 0,
        'photo' => $photosAll[$product['id']]
            )
    );

    echo "</li>";
}
?>