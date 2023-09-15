<?php

require_once 'vendor/autoload.php';
require_once 'OnonaGoogleAnalitics.php';

use App\OnonaGoogleAnalitics;


$a=new OnonaGoogleAnalitics('./My First Project-22877a113540.json');
var_dump($a->getStat('ga:178119779','2019-02-14','2019-02-14'));



