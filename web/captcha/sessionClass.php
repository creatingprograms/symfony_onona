<?php

ini_set('session.save_handler', 'user');
$dblocation = "localhost";
$dbname = "1ononaru";
$dbuser = "1ononaru";
$dbpasswd = "2xUr8hepKsKb2Lp4";

mysql_connect($dblocation, $dbuser, $dbpasswd);
mysql_select_db($dbname);

//echo phpinfo();exit;

function sessionOpen($path = null, $name = null) {

    return true;
}

function sessionClose() {
    // do nothing
    return true;
}

function sessionRead($id) {
    // get table/columns
    $db_table = "sessions";
    $db_data_col = "sess_data";
    $db_id_col = "sess_id";
    $db_time_col = "sess_time";

    $sql = 'SELECT ' . $db_data_col . ' FROM ' . $db_table . ' WHERE ' . $db_id_col . '="' . $id.'"';

    $stmt = mysql_query($sql);

    $sessionRows = mysql_fetch_array($stmt);
    
    

    
    if (count($sessionRows) == 2) {

        return $sessionRows[0];
    } else {
        // session does not exist, create it
         $sql = 'INSERT INTO ' . $db_table . '(' . $db_id_col . ', ' . $db_data_col . ', ' . $db_time_col . ') VALUES (' . $id . ', "", ' . time() . ')';

          mysql_query($sql); 

        return '';
    }
}

function sessionWrite($id, $data) {
    // get table/column
    $db_table = "sessions";
    $db_data_col = "sess_data";
    $db_id_col = "sess_id";
    $db_time_col = "sess_time";
    $sql = 'UPDATE ' . $db_table . ' SET ' . $db_data_col . ' = "' . $data . '", ' . $db_time_col . ' = ' . time() . ' WHERE  ' . $db_id_col . '= "' . $id . '"';
   /* $fp = fopen("/var/www/ononaru/data/www/onona.ru/captcha/counter.txt", "a");
    fwrite($fp, $sql. "\r\n");
    fclose($fp);*/
    mysql_query($sql);

    return true;
}

function sessionDestroy($id) {
    // get table/column
    $db_table = "sessions";
    $db_id_col = "sess_id";

    // delete the record associated with this id
     $sql = 'DELETE FROM ' . $db_table . ' WHERE ' . $db_id_col . '= ' . $id;

    mysql_query($sql);

    return true;
}

function sessionGC($lifetime) {
    // get table/column
    $db_table = "sessions";
    $db_time_col = "sess_time";

    // delete the record associated with this id
    $sql = 'DELETE FROM ' . $db_table . ' WHERE ' . $db_time_col . ' < ' . (time() -2592000); //$lifetime);

    mysql_query($sql);

    return true;
}

?>
