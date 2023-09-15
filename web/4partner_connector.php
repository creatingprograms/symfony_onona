<?php

ini_set('display_errors', 0);
error_reporting(0);
include 'sync/CurlClient.php';
include 'sync/cfg/config.php';

function check_ref() {
    if ($_GET['err']) {
        //ini_set('display_errors',1);
        //ini_set('error_reporting',2039);
        //error_reporting(E_ALL);
    }
    global $config;
    $REF = False;
    $payedSource=[//Список платных источников для их дедупликации
      'gdeslon',
      'adm',
      'google',
      'yandex',
      'yandexmarket',
      'admitad',
      'advcake',
      'bluesystem'
    ];
    // if($_SERVER['REMOTE_ADDR']=='87.244.36.210') die('<pre>'.print_r(['$_GET' => $_GET, '_REQUEST' => $_REQUEST], true));
    if(isset($_GET['utm_source'])){
      if(in_array(mb_strtolower($_GET['utm_source']), $payedSource)){
        $source = mb_strtolower($_GET['utm_source']);
        if($source=='admitad') $source='adm';
        if($source=='advcake') {
          setcookie('advcake_trackid', md5($_SERVER['REQUEST_URI'].microtime()), time() + 30*24*60*60, '/', '.onona.ru');  //Запоминаем на 30 id
          setcookie('advcake_url', 'https://onona.ru'.$_SERVER['REQUEST_URI'], time() + 30*24*60*60, '/', '.onona.ru');  //Запоминаем на 30 дней url

        }
        setcookie('utm_source', $source, time() + 30*24*60*60, '/', '.onona.ru');  //Запоминаем на 30 дней платный источник
        //При повторном переходе срок продлится. При появлении другого платного источника заменится на текущий
      }
    }

    if (isset($_GET['r']) && $_GET['r'] === 'cmd' && $_GET['api'] === $config['curl'][3]) {
        ignore_user_abort(true);

        $rc = new ReflectionClass('CurlClient');
        $CurlClient = $rc->newInstanceArgs($config['curl']);
        include 'sync/init.php';
        $rc = new ReflectionClass('CurlClient');
        $sOrder = new SyncOrder($rc->newInstanceArgs($config['curl']));
        switch ($_GET['command']) {
            case 'upload_orders-' :
                $sOrder->upload_orders();
                break;
            case 'download_orders-' :
                $sOrder->download_orders();
                break;
            case 'update_catalog-' :
                $sOrder->update_catalog();
                break;
            case 'upload_order-' :
                $sOrder->upload_order((integer) $_GET['id']);
                break;
            default:
                echo 'command';
        }
        exit;
    }
    /* if ($_COOKIE['referalurl'] == "")
      setcookie('referalurl', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''), time() + 2592000, '/', '.onona.ru');
     */

    /* if (isset($_GET['r']) && (integer) $_GET['r'] !== 0) {
      $rc = new ReflectionClass('CurlClient');
      $CurlClient = $rc->newInstanceArgs($config['curl']);

      $REF = $_GET['r'];
      $PRX = $_GET['prx'];
      $UID = $_GET['uid'];
      if ((integer) $CurlClient->exec(array('api/check_ref_link', array('ref_id' => $REF)))->json_result() === 1) {
      $CurlClient->exec(array('api/fix_visit', array(
      'ref_id' => $REF,
      'ip' => $_SERVER['REMOTE_ADDR'],
      'host' => $_SERVER['HTTP_HOST'],
      'url' => $_SERVER['REQUEST_URI'],
      'ref_url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
      )))->result();
      }
      } */
    if(isset($_GET['r']) && $_GET['r']=='1819251538'){

      $_SERVER['HTTP_REFERER']='https://vk.com/sex_shop_onona';

    }
    /*
    if (isset($_GET['utm_medium']) && $_GET['utm_medium'] == "cpc") {
      if (isset($_GET['utm_source']) && $_GET['utm_source'] == "google") {
        setcookie('utm_source', 'google/cpc', time() + 30*24*60*60, '/', '.onona.ru');  //Запоминаем на 30 дней источник контекстка гугл
      }
      if (isset($_GET['utm_source']) && $_GET['utm_source'] == "yandex") {
        setcookie('utm_source', 'yandex/cpc', time() + 30*24*60*60, '/', '.onona.ru'); //Запоминаем на 30 дней источник контекстка яндекс
      }
    }*/
    if (isset($_GET['r']) && $_GET['r'] == "2764355315") {
        // setcookie('samyragon', $_GET['sa'], time() + 2592000, '/', '.onona.ru');
    }
    if (isset($_GET['wmaster'])) {
        setcookie('wmaster', $_GET['wmaster'], time() + 2592000, '/', '.onona.ru');
    }
    if(isset($_GET['r']) && ($_GET['r']=='1493006643' || $_GET['r']=='1916149974')){
      setcookie('referal', $_GET['r'], time() + 2592000, '/', '.onona.ru');
    }
    if(isset($_GET['r']) && $_GET['r']=='3933088601') {
      setcookie('referal', $_GET['r'], time() + 2592000, '/', '.onona.ru');
    }

    if (isset($_GET['r']) && isset($_COOKIE['referal']) && $_COOKIE['referal'] != $_GET['r']) {
        $REF = $_GET['r'];
        if ($_GET['r'] == "347146658") {
            setcookie('referal', $_GET['r'], time() + 7776000, '/', '.onona.ru');
            if (isset($_GET['prx'])) {
                $PRX = $_GET['prx'];
                setcookie('prx', $_GET['prx'], time() + 7776000, '/', '.onona.ru');
            }
        } else if ($_GET['r'] == "2801045062") {
            setcookie('referal', $_GET['r'], time() + 7776000, '/', '.onona.ru');
            if (isset($_GET['uid'])) {
                $UID = $_GET['uid'];
                setcookie('uidAdmitad', $_GET['uid'], time() + 7776000, '/', '.onona.ru');
            }
        } else {
            setcookie('referal', $_GET['r'], time() + 2592000, '/', '.onona.ru');
            if ($_GET['r'] == "1245533220") { // leadtrade
                //setcookie('lttracking', $_GET['lttracking'], time() + 2592000, '/', '.onona.ru');
            }
        }
    } elseif (isset($_COOKIE['referal']) && (integer) $_COOKIE['referal'] !== 0) {
        $REF = $_COOKIE['referal'];
        $PRX = $_COOKIE['prx'];
    } /* else { */
    if (isset($_GET['r']) && (integer) $_GET['r'] !== 0) {
        setcookie('referalurl', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''), time() + 2592000, '/', '.onona.ru');
        $rc = new ReflectionClass('CurlClient');
        $CurlClient = $rc->newInstanceArgs($config['curl']);

        $REF = $_GET['r'];
        $PRX = $_GET['prx'];
        $UID = $_GET['uid'];
        if ((integer) $CurlClient->exec(array('api/check_ref_link', array('ref_id' => $REF)))->json_result() === 1) {
            $CurlClient->exec(array('api/fix_visit', array(
                    'ref_id' => $REF,
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'host' => ($_SERVER['HTTPS'] == "on" ? "https://" : "") . $_SERVER['HTTP_HOST'],
                    'url' => $_SERVER['REQUEST_URI'],
                    'ref_url' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        )))->result();
            if ($REF == "347146658") {
                setcookie('referal', $REF, time() + 7776000, '/', '.onona.ru');
                //setcookie('referalurl', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''), time() + 7776000, '/', '.onona.ru');

                if (isset($REF)) {
                    setcookie('prx', $PRX, time() + 7776000, '/', '.onona.ru');
                }
            } else if ($REF == "2801045062") {
                setcookie('referal', $REF, time() + 7776000, '/', '.onona.ru');
                //setcookie('referalurl', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''), time() + 7776000, '/', '.onona.ru');
                if (isset($UID)) {
                    setcookie('uidAdmitad', $UID, time() + 7776000, '/', '.onona.ru');
                }
            } else if ($REF == "1245533220") {
                //setcookie('referal', $REF, time() + 2592000, '/', '.onona.ru');
                //setcookie('lttracking', $_GET['lttracking'], time() + 2592000, '/', '.onona.ru');
            } else {
                setcookie('referal', $REF, time() + 2592000, '/', '.onona.ru');
                //setcookie('referalurl', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''), time() + 2592000, '/', '.onona.ru');
            }
            //setcookie('referal', $REF, time() + 2592000, '/');
        }
    }
    /* } */
    if ($REF) {
        if ($REF == "347146658") {
            setcookie('referal', $REF, time() + 7776000, '/', '.onona.ru');
            if (isset($REF)) {
                setcookie('prx', $PRX, time() + 7776000, '/', '.onona.ru');
                define('PRXCITYADS', $PRX);
            }
        } else if ($REF == "2801045062") {
            setcookie('referal', $REF, time() + 7776000, '/', '.onona.ru');
            if (isset($UID)) {
                setcookie('uidAdmitad', $UID, time() + 7776000, '/', '.onona.ru');
            }
        } else {
            setcookie('referal', $REF, time() + 2592000, '/', '.onona.ru');
        }
        //setcookie('referal', $REF, time() + 2592000, '/');
        define('REFERALID', $REF);
    }
}

//check_ref();
if (defined('REFERALID')) {
//	echo '<br/>DEF:'.REFERALID;
}
