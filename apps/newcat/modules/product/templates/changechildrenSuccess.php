<?

include_partial("product/changechildren", [
    'sf_cache_key' => $product['id'],
    'product' => $product,
    'comment' => isset($comments) ? $comments : 0,
    'photo' => $photo
        ]
);
