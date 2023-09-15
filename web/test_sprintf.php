<?php
exit;

//echo sprintf("Test: <%s>%s\r\n", "info@onona.ru", "55");
ini_set("display_errors", 1);
error_reporting(E_ALL);


$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");

function curlPost($url, $params = null) {
    global $curl;

    if (is_array($params)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
    }
    curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($curl, CURLOPT_URL, $url);
    $result = curl_exec($curl);

    return $result;
}

function apiAuth() {

    $username = 'dropshop@onona.ru';
    $pass = '1895dropshop@onona.ru6546';
    $key = '75404b6a186819bb8e7cb6fe0cb16b5b';


    $params = [
        'username' => $username,
        'password' => $pass,
        'shopKey' => $key
    ];

    $auth = curlPost('http://lk.sexmarket.ru/sign/in?do=signInForm-submit', $params);
}

function parseProducts() {
    apiAuth();

    $response = curlPost('http://lk.sexmarket.ru/shop/api/catalog/getCategory');
    print_r(json_decode($response, true));
}

parseProducts();













exit;

$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($curl, CURLOPT_COOKIEFILE, "cookie.txt");

$data = array('sign/in?do=signInForm-submit', array(
        'password' => '1895dropshop@onona.ru6546',
        'username' => 'dropshop@onona.ru',
        'shopKey' => '9fcd6e53b81e36598458110f1029b105'));

$GET = '';
if (is_array($data)) {
    $GET = $data[0];
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data[1]);
} else {
    $GET = $data;
}
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_URL, 'http://lk.4partner.ru/' . $GET);
$result = curl_exec($curl);

$GET = 'shop/api/Orders/get/precomplete';
curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($curl, CURLOPT_URL, 'http://lk.4partner.ru/' . $GET);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
$result = curl_exec($curl);

print_r(curl_getinfo($curl, CURLINFO_HEADER_OUT));
print_r($result);

exit;
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'sync/CurlClient.php';
define('HSHOPID', 11);
$config = array(
    /* 'shop_db' => 'mysql://1ononaru:2xUr8hepKsKb2Lp4@localhost/1ononaru?charset=utf8', */
    'productsxml' => 'http://lk.4partner.ru/public/database.xml',
    'stockxml' => 'http://lk.4partner.ru/public/stock.xml',
    'curl' => array(
        'dropshop@onona.ru',
        '1895dropshop@onona.ru6546',
        'http://lk.4partner.ru',
        '9fcd6e53b81e36598458110f1029b105'
    )
);

$rc = new ReflectionClass('CurlClient');
$CurlClient = $rc->newInstanceArgs($config['curl']);
$rc = new ReflectionClass('CurlClient');
$CurlClient = $rc->newInstanceArgs($config['curl']);
print_r($CurlClient->result());
