<?php use_helper('I18N', 'Date') ?>

<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?> 
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?php use_stylesheet('/newdis/css/jquery-ui-1.10.3.custom.css', 'first') ?> 
<?php use_javascript('/newdis/js/jquery-ui-1.10.3.custom.js'); ?>
<?php use_javascript('/newdis/js/i18n/jquery-ui-i18n.js'); ?>
<script>$(function(){
    $.datepicker.setDefaults(
    $.extend($.datepicker.regional["ru"])
);
    $("#datepicker").datepicker();
    $("#datepicker2").datepicker();
});</script>
<form action="/backend.php/pages/managersstats/action" method="POST">

    Период:      <input  id="datepicker" name="fromDate" value="<?= @$_POST['fromDate'] ?>"> - <input  id="datepicker2" name="to" value="<?= @$_POST['to'] ?>">     <input type="submit" value="Отправить запрос">
</form><br />
<a href="/backend.php/pages/managersstats/action?period=month">Последние 30 дней</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="/backend.php/pages/managersstats/action?period=week">Последние 7 дней</a>
<br /><br />
<table><tr>
        <th>Менеджер</th>
        <th><a href="/backend.php/pages/managersstats/action?raz=manager&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу менеджера</a></th>
        <th><a href="/backend.php/pages/managersstats/action?raz=delivery&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу доставки</a></th>
        
        <th><a href="/backend.php/pages/managersstats/action?raz=product&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу товары</a></th>
        <th><a href="/backend.php/pages/managersstats/action?raz=www&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу сайт</a></th>
        
        <th><a href="/backend.php/pages/managersstats/action?raz=shop&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу магазин</a></th>
        <th><a href="/backend.php/pages/managersstats/action?raz=oprosnik&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По всему опроснику</a></th>
        <th>Заказы (менеджер/опросник)</th></tr>

    <?php
    if (count($stats) > 0)
        If (@($_GET['raz'] == "manager" or $_GET['raz'] == "") and @($_GET['sort'] == "desc" or $_GET['sort'] == "")) {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['managerBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "manager" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['managerBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        } elseIf (@($_GET['raz'] == "delivery" or $_GET['raz'] == "") and @($_GET['sort'] == "desc" or $_GET['sort'] == "")) {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['deliveryBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "delivery" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['deliveryBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        } elseIf (@($_GET['raz'] == "product" or $_GET['raz'] == "") and @($_GET['sort'] == "desc" or $_GET['sort'] == "")) {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['productBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "product" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['productBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        } elseIf (@($_GET['raz'] == "www" or $_GET['raz'] == "") and @($_GET['sort'] == "desc" or $_GET['sort'] == "")) {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['wwwBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "www" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['wwwBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        } elseIf (@($_GET['raz'] == "shop" or $_GET['raz'] == "") and @($_GET['sort'] == "desc" or $_GET['sort'] == "")) {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['shopBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "shop" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['shopBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        } elseIf ($_GET['raz'] == "oprosnik" and $_GET['sort'] == "desc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['rating'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "oprosnik" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['rating'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        }

    //print_r($stats);
    $i = 0;
    if (count($stats) > 0)
        foreach ($stats as $manager => $data) {
            $i++;
            if ($manager != "" and $manager != "Отмена (дубль, тест, шутка)" and $manager != "Панькова Ю.В." and $manager != "Витчинова Александра"
                     and $manager != "Пархоменко Елена" and $manager != "Грачева Анна" and $manager != "Михайлик Арина" and $manager != "Астапова Юлия" and $manager != "Захарова Анастасия"
                     and $manager != "Кузнецова Елена" and $manager != "Кузнецова Татьяна" and $manager != "Гриченко Анастасия") {
                echo "<tr><td>" . $manager . "</td>"
                        . "<td>" . round($data['managerBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['deliveryBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['productBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['wwwBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['shopBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['rating'] / $data['count'], 2) . "</td><td><span style=\"cursor: pointer;\" onclick=\"$('#ordersMan" . $i . "').toggle()\"><b>Показать/Скрыть (".count($data['orders']).")</b></span><div id=\"ordersMan" . $i . "\" style=\"display: none;\"><br>";
                foreach ($data['orders'] as $orderId => $bal) {
                    echo "<a href=\"/backend.php/orders/" . $orderId . "/edit\">" . $orderId . "</a> - " . $bal['managerBall'] . "/" . $bal['rating'] . "<br>";
                }

                echo "</div></td></tr>";
            }
            //echo "<b>".$manager."</b> - ".round($data['managerBall']/$data['count'], 2)." / ".round($data['rating']/$data['count'],2)."<br>";
        }
    ?>
</table>


<br /><br /><br /><br /><br />




<b>Все менеджеры:</b>
<table><tr><th>Менеджер</th>
        <th><a href="/backend.php/pages/managersstats/action?raz=manager&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу менеджера</a></th>
        <th><a href="/backend.php/pages/managersstats/action?raz=delivery&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу доставки</a></th>
        
        <th><a href="/backend.php/pages/managersstats/action?raz=product&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу товары</a></th>
        <th><a href="/backend.php/pages/managersstats/action?raz=www&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу сайт</a></th>
        
        <th><a href="/backend.php/pages/managersstats/action?raz=shop&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По разделу магазин</a></th>
        <th><a href="/backend.php/pages/managersstats/action?raz=oprosnik&sort=<? If (($_GET['sort'] == "desc" or $_GET['sort'] == "")) echo "asc"; else echo "desc"; ?>">По всему опроснику</a></th><th>Заказы (менеджер/опросник)</th></tr>

    <?php
    if (count($stats) > 0)
       /* If (@($_GET['raz'] == "manager" or $_GET['raz'] == "") and @($_GET['sort'] == "desc" or $_GET['sort'] == "")) {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['managerBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "manager" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['managerBall'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        } elseIf ($_GET['raz'] == "oprosnik" and $_GET['sort'] == "desc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['rating'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_DESC, $stats);
        } elseIf ($_GET['raz'] == "oprosnik" and $_GET['sort'] == "asc") {

            foreach ($stats as $c => $key) {
                $sort_numcie[$c] = $key['rating'] / $key['count'];
            }
            $sort_numcie2 = $sort_numcie;
            array_multisort($sort_numcie, SORT_ASC, $stats);
        }
*/
    //print_r($stats);
    $i = 0;
    if (count($stats) > 0)
        foreach ($stats as $manager => $data) {
            $i++;
            if ($manager != "" and $manager != "Отмена (дубль, тест, шутка)") {
                echo "<tr><td>" . $manager . "</td>"
                        . "<td>" . round($data['managerBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['deliveryBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['productBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['wwwBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['shopBall'] / $data['count'], 2) . "</td>"
                        . "<td>" . round($data['rating'] / $data['count'], 2) . "</td><td><span style=\"cursor: pointer;\" onclick=\"$('#ordersMan" . $i . "').toggle()\"><b>Показать/Скрыть (".count($data['orders']).")</b></span><div id=\"ordersMan" . $i . "\" style=\"display: none;\"><br>";
                foreach ($data['orders'] as $orderId => $bal) {
                    echo "<a href=\"/backend.php/orders/" . $orderId . "/edit\">" . $orderId . "</a> - " . $bal['managerBall'] . "/" . $bal['rating'] . "<br>";
                }

                echo "</div></td></tr>";
            }
            //echo "<b>".$manager."</b> - ".round($data['managerBall']/$data['count'], 2)." / ".round($data['rating']/$data['count'],2)."<br>";
        }
    ?>
</table>
