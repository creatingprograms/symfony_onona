<?php
date_default_timezone_set('Europe/Moscow');
// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.

/*require_once( './4partner_connector.php' );

if (isset($_GET['r']) && $_GET['r'] === 'cmd' && $_GET['api'] === $config['curl'][3]) {
    
    check_ref();exit;
}
exit;*/
//echo $_SERVER['REMOTE_ADDR'];
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '128.69.136.205'))) {
     //die('You are not allowed to access this file. ');
}

require_once(dirname(__FILE__) . '/../config/ProjectConfiguration.class.php');

//$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
$configuration = ProjectConfiguration::getApplicationConfiguration('newcat', 'prod', true);
sfContext::createInstance($configuration)->dispatch();

