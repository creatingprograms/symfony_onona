<?php

mysql_connect('localhost', '1ononaru', '2xUr8hepKsKb2Lp4');
mysql_select_db('1ononaru');
$sql = '(select sf_guard_user.email_address,sf_guard_user.first_name from sf_guard_user where sf_guard_user.email_address <>"" and sf_guard_user.email_address like \'%@%\')
        UNION  (
        select senduser.mail,senduser.name from senduser where senduser.mail <>"" and senduser.mail like \'%@%\'
        )UNION  (
        select comments.mail,comments.username from comments where comments.mail <>"" and comments.mail like \'%@%\'
        )UNION  (
        select fast_order_log.mail,fast_order_log.username from fast_order_log where fast_order_log.mail <>"" and fast_order_log.mail like \'%@%\'
        )';

$stmt = mysql_query($sql);
mysql_close();

mysql_connect('localhost', 'site_intimtovar', 'd2HsbFxzbKPLbaxm');

mysql_select_db('intimtovar');
$sql = '(SELECT Email as mail
FROM SS_customers where Email like \'%@%\'
GROUP BY mail) UNION 
(SELECT customer_email as mail
FROM SS_orders  where customer_email like \'%@%\'
GROUP BY mail)';

$stmtIntim = mysql_query($sql);
mysql_close();

/* mysql_connect('176.9.74.10', 'intimcontact', 'sQriDFC57ae'); //echo mysql_error();
  mysql_select_db('intimcontact');
  $sql = 'SELECT email as mail
  FROM pro_user where Email like \'%@%\'
  GROUP BY mail'; */
mysql_connect('localhost', 'love', 'SqagB8ZA'); //echo mysql_error();
mysql_select_db('love');
$sql = 'SELECT email as mail
FROM pro_user where Email like \'%@%\'
GROUP BY mail';
$stmtIntimcont = mysql_query($sql);
mysql_close();

mysql_connect('localhost', '3sekretaru', 'UT2Rb8rET7VdfQae'); //echo mysql_error();
mysql_select_db('3sekretaru');
$sql = 'SELECT EMAIL as mail
FROM b_user where EMAIL like \'%@%\'
GROUP BY mail';

$stmt3sekreta = mysql_query($sql);
mysql_close();


if ($_POST['pass'] == "nSrW0HuZS8"):


    echo "Количество записей OnOna:" . mysql_num_rows($stmt) . " - <a href=\"/mailsdb.php?pass=4yhgrtrdsat5r&md=onona\">скачать</a> <br />";
    echo "Количество записей Intimtovar:" . mysql_num_rows($stmtIntim) . " - <a href=\"/mailsdb.php?pass=4yhgrtrdsat5r&md=intim\">скачать</a> <br />";
    echo "Количество записей Love.OnOna:" . mysql_num_rows($stmtIntimcont) . " - <a href=\"/mailsdb.php?pass=4yhgrtrdsat5r&md=intimcontact\">скачать</a> <br />";
    echo "Количество записей 3sekreta:" . mysql_num_rows($stmt3sekreta) . " - <a href=\"/mailsdb.php?pass=4yhgrtrdsat5r&md=3sekreta\">скачать</a> <br />";

elseif ($_GET['md'] == ""):
    echo '
    <form method="POST" action="/mailsdb.php">
        Пароль: <input type="text" name="pass"><input type="submit" value="OK">
    </form>
    ';
endif;

if ($_GET['md'] == "onona") {
    /* header('Cache-Control: private');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="ononaMail.txt"');
      header('Content-Transfer-Encoding: binary');
      header('Accept-Ranges: bytes');
      while ($sessionRows = mysql_fetch_array($stmt)) {
      if ($sessionRows['email_address'] != "" and isset($sessionRows['email_address']))
      echo str_replace("\r\n", "", $sessionRows['email_address']) . "\r\n";

      //$mailsOnOna.=$sessionRows['email_address'];
      } */

    header('Content-Type: text/html; charset=utf-8');
    header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', FALSE);
    header('Pragma: no-cache');
    header('Content-transfer-encoding: binary');
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"MailList.xls\"");
    //echo '"Ф.И.О.";"Адрес"'."\n";
    echo '
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="author" content="zabey" />
        <title>Demo</title>
    </head>
    <body>
';
    echo<<<HTML
<table border="1">
<tr><td>
ФИО
</td><td>
Адрес
</td></tr>
HTML;
    while ($sessionRows = mysql_fetch_array($stmt)) {
        if ($sessionRows['email_address'] != "" and isset($sessionRows['email_address'])){
            echo str_replace("\r\n", "", $sessionRows['email_address']) . "\r\n";
            $mail=str_replace("\r\n", "", $sessionRows['email_address']);
            $name=str_replace("\r\n", "", $sessionRows['first_name']);
        echo<<<HTML
<tr><td>
$mail
</td><td>
$name
</td></tr>
HTML;
        //$mailsOnOna.=$sessionRows['email_address'];
    }}

    echo<<<HTML
</table>
HTML;
}elseif ($_GET['md'] == "intim") {
    header('Cache-Control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="intimMail.txt"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    while ($sessionRows = mysql_fetch_array($stmtIntim)) {
        if ($sessionRows[0] != "" and isset($sessionRows[0]))
            echo str_replace("\r\n", "", $sessionRows[0]) . "\r\n";

        //$mailsOnOna.=$sessionRows['email_address'];
    }
}elseif ($_GET['md'] == "intimcontact") {
    header('Cache-Control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="intimcontactMail.txt"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    while ($sessionRows = mysql_fetch_array($stmtIntimcont)) {
        if ($sessionRows[0] != "" and isset($sessionRows[0]))
            echo str_replace("\r\n", "", $sessionRows[0]) . "\r\n";

        //$mailsOnOna.=$sessionRows['email_address'];
    }
}elseif ($_GET['md'] == "3sekreta") {
    header('Cache-Control: private');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="3sekretaMail.txt"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    while ($sessionRows = mysql_fetch_array($stmt3sekreta)) {
        if ($sessionRows[0] != "" and isset($sessionRows[0]))
            echo str_replace("\r\n", "", $sessionRows[0]) . "\r\n";

        //$mailsOnOna.=$sessionRows['email_address'];
    }
}
?>
