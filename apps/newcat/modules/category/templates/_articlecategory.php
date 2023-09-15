<script>
    function changeButtonToGreen(id) {
        $("#buttonId_" + id).removeClass("red-btn");
        $("#buttonId_" + id).addClass("green-btn");
        $("#buttonId_" + id).html("<span>В корзине</span>");
        $("#buttonId_" + id).attr("onclick", "");
        $("#buttonId_" + id).attr("title", "Перейти в корзину");
        $(".popup-holder #buttonIdP_" + id).removeClass("red-btn");
        $(".popup-holder #buttonIdP_" + id).addClass("green-btn");
        $(".popup-holder #buttonIdP_" + id).html("<span>В корзине</span>");
        $(".popup-holder #buttonIdP_" + id).attr("onclick", "");
        $(".popup-holder #buttonIdP_" + id).attr("title", "Перейти в корзину");

        ;
        window.setTimeout('$("#buttonId_' + id + '").attr("href","/cart")', 1000);
        window.setTimeout('$(".popup-holder #buttonIdP_' + id + '").attr("href","/cart")', 1000);
        $("#buttonIdP_" + id).removeClass("red-btn");
        $("#buttonIdP_" + id).addClass("green-btn");
        $("#buttonIdP_" + id).html("<span>В корзине</span>");
        $("#buttonIdP_" + id).attr("onclick", "");
        $("#buttonIdP_" + id).attr("title", "Перейти в корзину");

        window.setTimeout('$("#buttonIdP_' + id + '").attr("href","/cart")', 1000);
    }
</script>
<?/*= '!<pre>'.print_r([
  '$categorys' => $categorys,
  '$products' => $products,
], true).'</pre>|'*/?>
  <div id="productsShow">
    <?php if (count($products) > 0): ?>
      <h3><?=end($categorys)['this_name']?></h3>
      <ul class="product item-list">
              <?php
              foreach ($products as $product['id'] => $product) {
                  echo '<li class="prodTable-' . $product['id'] . (($product['count'] == 0) ? " liProdNonCount" : "") . '">';
                  if (!isset($childrensAll[$product['id']])) {
                      $childrensAll[$product['id']] = array();
                  }

                  include_partial("product/productBooklet", array(
                      'sf_cache_key' => $product['id'],
                      'products' => $products,
                      'product' => $product,
                      'childrens' => $childrensAll[$product['id']],
                      'comment' =>  0,
                      'commentsAll' => $commentsAll,
                      'photo' => $photosAll[$product['id']],
                      'photosAll' => $photosAll,
                      'autoLoadPhoto' => false
                    )
                  );

                  echo "</li>";
              }
              ?>
          </ul>
        <br>
        <a href="/category/<?=end($categorys)['this_slug']?>">Все товары категории</a>
    <?php endif; ?>
    <div style="clear: both;"></div>

  </div>
