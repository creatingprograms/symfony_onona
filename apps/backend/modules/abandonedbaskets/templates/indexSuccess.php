<div class="backend-left" style="width: 100%;">
<h1>Брошенные корзины</h1>
<p class="small">Страница <strong><?= $pageNum ?></strong> из <strong><?= $pagesCount ?></strong>.</p>
<?php
  $i=0;
  // die('<pre>'.print_r(array_keys($usersArr[0]), true).'</pre>');
  $product = isset($_GET['product']) ? $_GET['product'] : 0;
  $email = isset($_GET['email']) ? $_GET['email'] : '';
?>
<div class="backend-right" style="padding-top:0;">
  <form action="<?php echo url_for('abandonedbaskets/')?>">
    <table>
      <tbody>
        <tr>
          <td>E-mail</td>
          <td><input type="text" name="email" value="<?=$email?>" style="width: 100%;"></td>
        <tr>
          <td>Товар:</td>
          <td>
            <select name="product">
              <option value="0">Все</option>
              <? if (sizeof($productsArr)) :?>
                <? foreach ($productsArr as $key => $value) :?>
                  <option value="<?= $key ?>" <?= ($product==$key ? ' selected' : '') ?>><?= $value ?></option>
                <? endforeach ?>
              <? endif ?>
            </select>
          </td>
        </tr>
        <tr>
          <?php $dates =  new sfWidgetFormDate(); ?>
          <td>Дата</td>
          <td style="text-align: left;">С <?=$dates->render('from', $from_timestamp)?> по <?=$dates->render('to', $to_timestamp)?></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="Фильтр"></td>
      </tbody>
    </table>
  </form>
</div>
<table>
  <thead>
    <tr>
      <th>Email</th>
      <th>Корзина<br>
        <table><tr>
          <td style="width: 300px;">Товар</td>
          <td style="width: 30px;">Количество</td>
          <td style="width: 80px;">Без скидки</td>
          <td style="width: 80px;">Со скидкой</td>
        </tr></table>
      </th>
      <th>Изменен</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($usersArr as $user): ?>

    <tr>
      <td><?= $user['email_address'] ?></td>
      <td>
        <?php
          $cartArr=unserialize($user['cart']);
          $i++;
        ?>
        <table>
        <?php foreach ($cartArr as $key => $product) :?>
          <tr>
            <td style="width: 300px;">
              <?php if (isset($productsArr[$key])) : ?>
                <a href="/backend.php/product/<?= $key ?>/edit"><?=$productsArr[$key]?></a>
              <?php else : ?>
                <strong style="color: #F00;">Товар с кодом <?=$key ?> удален. У клиента будут проблемы с отображением корзины</strong>
              <?php endif ?>
            </td>
            <td style="width: 30px;"><?=$product['quantity']?> шт.</td>
            <td style="width: 80px;"><?=$product['price']?> руб.</td>
            <td style="width: 80px;"><?=$product['price_w_discount']?> руб.</td>
          </tr>
        <?php endforeach ?>
        </table>
      </td>
      <td><?=$user['updated_at']?></td>

    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p>Показаны <strong><?=$i?></strong> из <strong><?= $count ?></strong> результата(ов)</p>
<p class="small">Страница <strong><?= $pageNum ?></strong> из <strong><?= $pagesCount ?></strong>.</p>
<? if ( $pagesCount>1 ):?>
  <?php include_partial('pagination', array(
    'pageNum' => $pageNum,
    'pagesCount' => $pagesCount,
    'filter' => $filter,
  )) ?>
<? endif ?>
</div>
