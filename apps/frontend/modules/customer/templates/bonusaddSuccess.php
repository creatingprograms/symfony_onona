<main class="wrapper -action">
<?php
if ($error) {
    echo "Произошла ошибка. Попробуйте ещё раз.";
    if ($errorCap) {
        $bonuslog = BonusTable::getInstance()->createQuery()->where('comment like \'%Зачисление за заказ #' . $order->getPrefix() . $order->getId() . '%\'')->fetchOne();
        if (!$bonuslog) {
            $html = '<form action="/customer/bonusadd/' . $order->getId() . '
    " method="post" width="100%" id="bonusadd-' . $key . '">';
            ?>
            Мы ценим и доверяем своим покупателям, поэтому предлагаем вам не ждать автоматического зачисления, а самостоятельно активировать свои бонусы за оплаченный заказ, для этого достаточно указать дату оплаты заказа и нажать на кнопку активировать.
            <?
            $html.= <<<HERE
<table calss="noBorder" class="tableRegister">
                <tbody>
                    <tr>
  <th><label for="name">Дата оплаты:</label></th>
  <td><input type="text" name="datePay" value="" id="datePay"></td>
</tr>
                </tbody>

                <tbody>
                    <tr>
  <th><label for="code">Укажите код:</label></th>
HERE;
            $html.='<td><img src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" name="code" value="" id="code"><br>';

    if ($errorCap)
        $html.="<span >Ошибка. Попробуйте ещё раз.</span>";

            $html.= '
        </td>
        </tr>
        </tbody>

        </table>
        <a class = "red-btn colorWhite" href = "#" onclick = "$(\'#bonusadd-' . $key . '\').submit(); return false;"><span>Активировать бонусы</span></a>
        </form>
';
            echo $html;
            ?>
            <div></div><br/>
            Если у вас возникли вопросы по начислению бонусов, вы можете получить консультацию: <br/>
            по тел: 8 800 700 98 85<br/>
            или e-mail: info@onona.ru
            <br /><br />
            <span onClick="$('#actBonusOnShop-<?= $key ?>').toggle()">скрыть</span>
            <?
            //echo "Активировать ".$bonusAddUser." Бонусов за заказ #" . $orderNoBon->getPrefix() . $orderNoBon->getId() . "<br>";
        }
    }
} else {

    echo "Вам начислены бонусы.<br><br>

      <a href=\"/customer/bonus\">Вернуться в личный кабинет</a>
";
}
?>
</main>
