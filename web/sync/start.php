<?php
date_default_timezone_set('Europe/Moscow');
include 'init.php';
$rc = new ReflectionClass('CurlClient');
$sOrder = new SyncOrder( $rc->newInstanceArgs( $config['curl'] )  );

//$sOrder -> upload_orders() ;
#$sOrder -> download_orders();
//$sOrder -> call_sync();
$sOrder -> update_catalog();

