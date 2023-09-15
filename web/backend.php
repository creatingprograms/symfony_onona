<?php
set_time_limit (60);
ini_set("max_execution_time", "60");
ini_set('session.gc_maxlifetime', 2592000);
ini_set('session.cookie_lifetime', 2592000);

$isTest = true;
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');


if(stristr($_SERVER['SCRIPT_FILENAME'], 'dev.')){
  $configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'dev', true);
  define("DEBUG_ENV", true);
}
else
  $configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'prod', false);

sfContext::createInstance($configuration)->dispatch();
