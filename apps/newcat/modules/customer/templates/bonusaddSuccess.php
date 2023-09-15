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
<table calss="noBorder" style="width:100%;" class="tableRegister">
                <tbody>
                    <tr>
  <th style="width: 110px;"><label for="name">Дата оплаты:</label></th>
  <td><input type="text" style="width: 400px" name="datePay" value="" id="datePay"></td>
</tr>
                </tbody>

                <tbody>
                    <tr>
  <th><label for="code">Укажите код:</label></th>
HERE;
            $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

    if ($errorCap)
        $html.="<span style=\" color: red;\">Ошибка. Попробуйте ещё раз.</span>";

            $html.= '
        </td>
        </tr>
        </tbody>

        </table>
        <a style = "" class = "red-btn colorWhite" href = "#" onclick = "$(\'#bonusadd-' . $key . '\').submit(); return false;"><span style = "width: 195px;">Активировать бонусы</span></a>
        </form>
';
            echo $html;
            ?>
            <div style="clear: both"></div><br/>
            Если у вас возникли вопросы по начислению бонусов, вы можете получить консультацию: <br/>
            по тел: 8 800 700 98 85<br/>
            или e-mail: info@onona.ru
            <br /><br />
            <span style="cursor: pointer; color: #c3060e;border-bottom: 2px dotted #c3060e;" onClick="$('#actBonusOnShop-<?= $key ?>').toggle()">скрыть</span>
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
