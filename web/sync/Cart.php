<?php

class Cart {

    protected $sessionId;
    protected $products = array();
    protected $totalCost = 0;
    protected $productsCount = 0;

    public function to_array() {
        $data = array();
        $data['products'] = $this->products;
        $data['totalCost'] = $this->totalCost;
        $data['productsCount'] = $this->productsCount;
        return $data;
    }

    public function get_products() {
        return $this->products;
    }

    public function get_totalCost() {
        return $this->totalCost;
    }

    public function get_productsCount() {
        return $this->productsCount;
    }

    public function set_products($products) {
        $this->products = $products;
    }

    public function get_products_by_id() {
        $ids = array();
        if (is_array($this->products) and count($this->products) > 0)
            foreach ($this->products as $product) {
@                $ids[$product['productId']]['productOptions'] = $product['productOptions'];
                $ids[$product['productId']]['quantity'] = $product['quantity'];
                $ids[$product['productId']]['price'] = $product['price'];
                $ids[$product['productId']]['price_w_discount'] = $product['price_w_discount'];
 @               $ids[$product['productId']]['discount'] = $product['discount'];
            }
        return $ids;
    }

    public function replace_products_from_array($products) {
        $this->products = array();
        //echo '<pre>';print_r( $products );echo '</pre>';
        foreach ($products as $art => $art_info) {//echo $stockId;exit;
            $product = Products::find_all_by_productCode($art);
            $product = $product[0];
            if (!empty($product))
                $this->products[] = array(
                    'productId' => $product->id,
                    'productCode' => $art,
                    'productOptions' => array(), #$product->productoptions,
                    'quantity' => (integer) $art_info['count'],
                    'price' => $art_info['price'],
                    'price_w_discount' => $art_info['price_w_discount'],
                );
        }
    }

    public function update_totalCost($cost) {
        $this->totalCost = (int) $cost;
    }

}
