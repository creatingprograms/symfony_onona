<?php
// phpinfo();
// die();
if($_SERVER['REMOTE_ADDR']=="35.228.19.173"){
  echo "IP заблокирован.";
  exit;
  }
//header("Content-Security-Policy: script-src 'self' https://onona.ru;");

  if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php')) {
include_once($_SERVER['DOCUMENT_ROOT'] . '/d-url-rewriter.php');
durRun ();
}

if (@$_GEytnT['fullVersion'] == "1") {
    SetCookie("fullVersion", true, 0, "/");
    SetCookie("fullVersionTime", time(), 0, "/");
    $_COOKIE['fullVersion'] = true;
    $_COOKIE['fullVersionTime'] = time();
}

if (@$_GET['olddis'] == "1") {
    SetCookie("olddis", true, 0, "/");
    $_COOKIE['olddis'] = true;
} elseif (@$_GET['olddis'] != "") {
    SetCookie("olddis", false, 0, "/");
    $_COOKIE['olddis'] = false;
}


$user_agent = strtolower(getenv('HTTP_USER_AGENT'));
$accept = strtolower(getenv('HTTP_ACCEPT'));

$agentIsMobile = false;



/*
 *              Редиректы
 */
$redirectConn = mysql_connect('localhost', '1ononaru', '2xUr8hepKsKb2Lp4');

mysql_select_db('1ononaru', $redirectConn);
mysql_set_charset('utf8', $redirectConn);

$res = mysql_query("SELECT * FROM `redirect` where inurl like '%" . parse_url($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])['path'] . "%'");
if(!$res) {}
else{
    while ($row = mysql_fetch_assoc($res)) {
    if (substr_count((parse_url($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])['path']), $row['inurl']) > 0) {
        // echo $row['outurl'];
        header('Location: https://onona.ru' . $row['outurl'], TRUE, 301);
        flush();
        exit;
    }
}
}
mysql_close($redirectConn);
/*
 *              Редиректы
 */






date_default_timezone_set('Europe/Moscow');
require_once( './4partner_connector.php' );

check_ref();

require_once(dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

$isNew=false;
if (isset($_GET['new_version']) && $_GET['new_version']=="Y") {
  $isNew=true;
  setcookie('use_new_version', true, time()+30*24*60*60, '/');
}
if($_COOKIE['use_new_version']==1)
  $isNew=true;
if (isset($_GET['new_version']) && $_GET['new_version']=="N") {
  $isNew=false;
  setcookie('use_new_version', false, time()-1, '/');
}

$isTest = true;
if (!$isNew) {

    $configuration = ProjectConfiguration::getApplicationConfiguration('newcat', 'prod', false);
} else {
    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', (!isset($_GET['debug']) ? 'prod' : 'dev'), false);
}

/* Старая канфигурация
if (false) {
    $configuration = ProjectConfiguration::getApplicationConfiguration('newcat', 'dev', true);
} else {
    $configuration = ProjectConfiguration::getApplicationConfiguration('newcat', 'prod', false);
}
*/

sfContext::createInstance($configuration)->dispatch();
