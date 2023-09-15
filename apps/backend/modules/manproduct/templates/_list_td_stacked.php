<td colspan="6">
  <?php echo __('%%id%% - %%name%% - %%code%% - %%slug%% - %%price%% - %%count%%', array('%%id%%' => link_to($product->getId(), 'product_manproduct_edit', $product), '%%name%%' => get_partial('manproduct/name', array('type' => 'list', 'product' => $product)), '%%code%%' => $product->getCode(), '%%slug%%' => $product->getSlug(), '%%price%%' => $product->getPrice(), '%%count%%' => $product->getCount()), 'messages') ?>
</td>
