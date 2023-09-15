<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?> 
<?php
echo "<b>Общее количество бонусов сейчас на счетах: </b>".$bonusAllSum[0]['sum']."<br><br>";
foreach ($bonusAll as $key => $bonus) {
    echo "<b>" . $bonus['Month'] . "." . $bonus['year'] . ":</b><br>";
    echo "Итог за месяц: " . $bonus['sum'] . "<br>";
    $bonusPrint = 0;
    foreach ($bonusPay as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }

    echo "Оплачено бонусами за заказы: " . (0 - $bonusPrint) . "<br>";
    $bonusPrint = 0;
    foreach ($bonusRegister as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Начислено за регистрацию: " . $bonusPrint. "<br>";

    $bonusPrint = 0;
    foreach ($bonusBirthday as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Начислено в день рождение: " . $bonusPrint . "<br>";

    $bonusPrint = 0;
    foreach ($bonusOrder as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Начислено за заказы: " . $bonusPrint . "<br>";

    $bonusPrint = 0;
    foreach ($bonusShop as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Начислено за покупки в магазине: " . $bonusPrint . "<br>";

    $bonusPrint = 0;
    foreach ($bonusLifetime as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Снято в связи с окончанием времени жизни: " . (0 - $bonusPrint) . "<br>";

    $bonusPrint = 0;
    foreach ($otherPlus as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Другие начисления(Это начисления которые произошли за этот месяц и не учтены в других пунктах): " . ($bonusPrint) . "<br>";

    $bonusPrint = 0;
    foreach ($otherMinus as $bonusStat) {
        if ($bonusStat['Month'] == $bonus['Month'] and $bonusStat['year'] == $bonus['year']) {
            $bonusPrint = $bonusStat['sum'];
        }
        continue;
    }
    echo "Другие списания(Это списания которые произошли за этот месяц и не учтены в других пунктах): " . ($bonusPrint) . "<br><br>";
    //print_r($bonus);
}