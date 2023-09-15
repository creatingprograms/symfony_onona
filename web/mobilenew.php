<?php

if (substr_count($_SERVER['REQUEST_URI'], "sexopedia") > 0
        or substr_count($_SERVER['REQUEST_URI'], "desire") > 0
        or substr_count($_SERVER['REQUEST_URI'], "compare") > 0
        or substr_count($_SERVER['REQUEST_URI'], "tests") > 0
        or substr_count($_SERVER['REQUEST_URI'], "horoscope") > 0
        or substr_count($_SERVER['REQUEST_URI'], "pickpoint") > 0
        or substr_count($_SERVER['REQUEST_URI'], "russianpost") > 0
        or substr_count($_SERVER['REQUEST_URI'], "comments") > 0) {

    if ($_SERVER['QUERY_STRING'] != "") {
        if (substr_count($_SERVER['REQUEST_URI'], "video") > 0) {
            header('Location: https://onona.ru/?' . $_SERVER['QUERY_STRING']);exit;
        } else {
            header('Location: https://onona.ru' . $_SERVER['REQUEST_URI'] . '?' . $_SERVER['QUERY_STRING']);exit;
        }
    } else {
        if (substr_count($_SERVER['REQUEST_URI'], "video") > 0) {
            header('Location: https://onona.ru/');exit;
        } else {
            header('Location: https://onona.ru' . $_SERVER['REQUEST_URI']);exit;
        }
    }
}


if ((parse_url($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])['path']) == "m.onona.ru/sexshop") {
    //echo $row['outurl'];
    header('Location: http://m.onona.ru', TRUE, 301);
    flush();
    exit;
}

date_default_timezone_set('Europe/Moscow');
require_once( './4partner_connector.php' );
//if (isset($_GET['r']) && $_GET['r'] === 'cmd' && $_GET['api'] === $config['curl'][3]) {
check_ref();
require_once(dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('mobilenew', 'prod', false);
sfContext::createInstance($configuration)->dispatch();

