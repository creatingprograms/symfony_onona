<?php

if ($sf_request->isMethod(sfRequest::POST) and $errorCap == "" and $errorOrder=="" and $errorBonus=="") {
    echo $page->getContent();
} else {
    $html = '';

    if (@$errorOrder)
        $html.="<center><span style=\" color: red;\">Ошибка. Такой покупки не существует.</span></center>";
    if (@$errorBonus)
        $html.="<center><span style=\" color: red;\">Ошибка. По данному чеку уже начислены бонусы.</span></center>";
    
    $html.= <<<HERE
            
        <span style="font-size: 13px; color: #c3060e">Ваши бонусы не активированы...</span><br/><br/>
            Возможные причины:<br/>
- активация бонусов возможна по чеку только из оффлайн магазинов «Он и Она» в Москве,<br/>
- активация возможна только спустя 24 часа после совершения покупки,<br/>
- для активации необходимо ввести номер вашего чека + сумму вашего чека (до точки, без копеек):<br/><br/>
            
    <form action="/customer/bonusshop" method="post" width="100%" id="support">
        <img style="float: right;margin-right: 20px;" src="/images/checks.png">
<table calss="noBorder" style="width:570px;" class="tableRegister">
                <tbody>
                    <tr>
  <th style="width: 300px;"><label for="name">Номер чека*:</label></th>
  <td><input type="text" style="width: 400px" name="checknum" value="" id="checknum"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Сумма покупки:*</label></th>
  <td><input type="text" style="width: 400px" name="summ" value="" id="summ"></td>
</tr>
                </tbody>

               

                
                <tbody>
                    <tr>
  <th><label for="code">Укажите код:*</label></th>
HERE;
    $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

    if ($errorCap)
        $html.="<span style=\" color: red;\">Ошибка. Попробуйте ещё раз.</span>";
    $html.= <<<HERE
        </td>
        </tr>
        </tbody>

        </table>
       <center> * - поля, отмеченные * обязательны для заполнения.<br>
        <a style = "margin-left: 300px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 120px;">Начислить</span></a></center>
        </form><div style="clear:both;"></div><br/><br/>
            Если вы не смогли активировать свои Бонусы, пожалуйста, отправьте фото/скан чека + номер и сумму чека, которые вы указываете при активации, на e-mail: <a href="mailto:svs@onona.ru">svs@onona.ru</a> , мы обязательно вам поможем.
<br/><br/>
С уважением, команда «Он и Она»
HERE;
    echo $html;
}
?>
