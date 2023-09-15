<?
$products_old = unserialize($sf_user->getAttribute('products_to_cart'));
if (is_array($products_old))
    foreach ($products_old as $key => $product) {
        $arrayProdCart[] = $product['productId'];
    }
      if (in_array($id, $arrayProdCart) === true)
                        $prodInCart = true;
                    else
                        $prodInCart = false;
include_component("category", "changechildren", array('sf_cache_key' => $id."-".$prodInCart, 'id' => $id, "productsKeys"=>$_POST['productsKeys'],"prodInCart"=>$prodInCart));