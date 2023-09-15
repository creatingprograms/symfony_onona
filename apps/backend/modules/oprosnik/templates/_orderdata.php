
<?php
if ($form['shop']->getValue() != "Магазин") {
    $order = OrdersTable::getInstance()->findOneById($form['orderid']->getValue());
    ?>

    <div class="sf_admin_form_row sf_admin_text sf_admin_form_field_orderid">
        <div>
            <label for="oprosnik_orderid">Данные заказа</label>
            <div class="content">
                <table style="margin-bottom: 0;">
                    <tr><td>Менеджер</td><td><? echo $order->getManager(); ?></td></tr>
                    <tr><td>Оценка</td><td><?= $form['rating']->getValue() ?></td></tr>
                    <tr><td>Статус заказа</td><td><? echo $order->getStatus(); ?></td></tr>
                </table>









            </div>

        </div>
    </div>
<?
}?>