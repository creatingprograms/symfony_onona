<?php

// $agentIsMobile = false;
// $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
// $accept = strtolower(getenv('HTTP_ACCEPT'));
// $agentIsMobile = false;
if(isset($_GET['debug'])) {
  $debug=$_GET['debug'];
  setcookie('debug', $_GET['debug'], time()+30*24*60*60, '/', 'dev.onona.ru');
  // $_COOKIE['debug']=$_GET['debug'];
}
if(isset($_GET['type'])) {
  $type=$_GET['type'];
  setcookie('type', $_GET['type'], time()+30*24*60*60, '/', 'dev.onona.ru');
  // $_COOKIE['type']=$_GET['type'];
}


date_default_timezone_set('Europe/Moscow');
require_once( './4partner_connector.php' );
//if (isset($_GET['r']) && $_GET['r'] === 'cmd' && $_GET['api'] === $config['curl'][3]) {
check_ref();
//}
require_once(dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');
if(isset($_COOKIE['debug'])) $debug=$_COOKIE['debug'];
if(isset($_COOKIE['type'] )) $type= $_COOKIE['type'];

// $debug=(!isset($_COOKIE['debug']) ? 'prod' : $_COOKIE['debug']);
// $type =(!isset($_COOKIE['type'])  ? 'frontend' : $_COOKIE['type']);

$isTest = true;
$isNew=true;
switch($type){
  case 'newcat':
    $configuration = ProjectConfiguration::getApplicationConfiguration('newcat', 'prod', true);
    break;
  case 'frontend':
    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', true);
    break;
  default:
    $isNew=true;
    $configuration = ProjectConfiguration::getApplicationConfiguration('new', $debug, false);
}
/*
if (isset($_GET['old'])) {
// if (stristr($_SERVER['SCRIPT_FILENAME'], 'p702') || $_COOKIE['testnewdisdev']) {
    $configuration = ProjectConfiguration::getApplicationConfiguration('newcat', 'prod', true);
} else {
    $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', (!isset($_GET['debug']) ? 'prod' : 'dev'), false);
    // $configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
}
*/

sfContext::createInstance($configuration)->dispatch();
