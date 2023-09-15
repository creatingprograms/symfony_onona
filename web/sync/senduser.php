<?php

$html_mail_add_product = Settings::find_by_id(41);

$SITE_NAME = "OnOna.ru";

$MAIL_OR_SMTP = 2;
$SMTP_HOST = Settings::find_by_id(1);
$SMTP_HOST = $SMTP_HOST->value;
$SMTP_PORT = Settings::find_by_id(4);
$SMTP_PORT = $SMTP_PORT->value;
$SMTP_USER = Settings::find_by_id(2);
$SMTP_USER = $SMTP_USER->value;
$SMTP_PASS = Settings::find_by_id(3);
$SMTP_PASS = $SMTP_PASS->value;


$users = ProductsUserSend::find('all', array("conditions" => "product_id = " . $item->id . " and is_send = '0'"));

foreach ($users as $user) {
    $html_mail_add_product = str_replace(
                    array('{name}', '{productname}', '{ssilkaNaTovar}', '{shop}'), array($user->name, $item->name, "http://www.onona.ru/product/" . $item->slug, $SITE_NAME), $html_mail_add_product->value);


    require_once __DIR__ . '/classes/PhpMailer.class.php';
    $mail = new PhpMailer();
    $mail->From = 'info@' . $SITE_NAME;
    $mail->FromName = 'Почтовый робот сайта ' . $SITE_NAME;
    $mail->CharSet = 'utf-8';
    $mail->IsHTML(true);
    $mail->Body = $html_mail_add_product;
    $mail->Subject = "На сайте " . $SITE_NAME . " появился ожидаемый товар.";
    if ($user->mail != '') {
        $mail->AddBCC('svs@onona.ru', 'Вадим');
        $mail->AddAddress($user->mail, $user->name);
        /*if ($MAIL_OR_SMTP == 2) {
            $mail->SMTPDebug = 0;
            $mail->IsSMTP(); // �������� ��������� SMTP
            $mail->Host = $SMTP_HOST; // SMTP ������
            $mail->Port = $SMTP_PORT;
            $mail->SMTPAuth = true; // �������� SMTP ��������������
            $mail->Username = $SMTP_USER; // ��������� ��� �����
            $mail->Password = $SMTP_PASS; // ������� ������ �� ��������� ����
        }*/
        $mail->Send();
    }

    $user->is_send = 1;
    $user->save();
    //print_r($user->name);
}
/*
  if ($item->senduser != "") {
  $senduser = unserialize($item->senduser);
  foreach ($senduser as $user) {
  $html_mail_add_product = iconv("utf-8", "Windows-1251", str_replace(
  array('{name}', '{productname}', '{ssilkaNaTovar}', '{shop}'), array($user['customer'], $item->name, "http://www.onona.ru/product/" . $item->id, $SITE_NAME), $html_mail_add_product->value));

  require_once __DIR__ . '/../classes/PhpMailer.class.php';
  $mail = new PhpMailer();
  $mail->From = 'info@' . $SITE_NAME;
  $mail->FromName = '�������� ����� ����� ' . $SITE_NAME;
  $mail->CharSet = 'windows-1251';
  $mail->IsHTML(true);
  $mail->Body = $html_mail_add_product;
  $mail->Subject = "�� ����� " . $SITE_NAME . " �������� ��������� �����";
  if ($user['email'] != '') {
  $mail->AddAddress($user['email'], $user['customer']);
  if ($MAIL_OR_SMTP == 2) {
  $mail->SMTPDebug = 0;
  $mail->IsSMTP(); // �������� ��������� SMTP
  $mail->Host = $SMTP_HOST; // SMTP ������
  $mail->Port = $SMTP_PORT;
  $mail->SMTPAuth = true; // �������� SMTP ��������������
  $mail->Username = $SMTP_USER; // ��������� ��� �����
  $mail->Password = $SMTP_PASS; // ������� ������ �� ��������� ����
  }
  $mail->Send();
  }
  }
  $item->senduser="";
  $item->save();
  } */
?>
