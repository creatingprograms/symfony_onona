<tr>
<td><label for="qr_redirects_filters_shop_id">Магазин</label></td>
<td>
  <select name="qr_redirects_filters[shop_id]" id="qr_redirects_filters_shop_id">
    <option value=""></option>
    <?php foreach ($GLOBALS['shops'] as $shop): ?>
      <option value="<?= $shop->getId() ?>"><?= $shop->getName() ?></option>
    <?php endforeach; ?>
  </select>
</td>
</tr>
