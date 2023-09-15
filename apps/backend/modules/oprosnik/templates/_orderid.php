
  

<div class="sf_admin_form_row sf_admin_text sf_admin_form_field_orderid">
        <div>
      <label for="oprosnik_orderid">Номер заказа</label>
      <div class="content"><?php
echo $form['orderid']->getValue();
if ($form['shop']->getValue() != "Магазин") {?>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<a href="/backend.php/orders/<?=$form['orderid']->getValue()?>/edit" style="font-size: 16px; font-weight: bold;" target="_blank"> Ссылка на заказ </a><?}?></div>

          </div>
  </div>