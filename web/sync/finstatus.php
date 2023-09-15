<?php

mb_internal_encoding('UTF-8');
//$bonus = round((($order_data['total_cost'] * $persent_bonus_add) / 100));

if (mb_strtolower($newStatusId) != mb_strtolower($order->status)) {
    $template = Mailchangestatus::find_by_status($newStatusId);
    /*  if (@!$template or !$template->is_public)
      $template = Mailchangestatus::find_by_id(1); */
    if (@$template and $template->is_public) {
        Oprosnik::delete_all(array('conditions' => array('orderid' => $order_id)));
        $templateName = $template->status;
        $titlemail=$template->titlemail;
        $link = 'http://www.onona.ru/oprosnik/' . md5($order_id);
        $template = str_replace('{linkOprosnik}', '<a href="' . $link . '" style="color:white;text-decoration:none;line-height:60px;text-transform:uppercase;padding:0 30px;font-weight: bold;background: #fb0605;display: inline-block;">Оценить работу магазина</a>', $template->content);
        $template = str_replace('{orderId}', $order_id, $template);
        $template = str_replace('{name}', $order_data['full_name']['firstName'], $template);
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


        require_once __DIR__ . '/classes/PhpMailer.class.php';
        $mail = new PhpMailer();
        $mail->From = 'info@' . $SITE_NAME;
        $mail->FromName = 'Почтовый робот сайта ' . $SITE_NAME;
        $mail->CharSet = 'utf-8';
        $mail->IsHTML(true);
        $mail->Body = $template;
        //$mail->Subject = "Изменение статуса заказа.";
        $mail->Subject = $titlemail;
        if ($customer->email_address != '') {
            if (preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $customer->email_address)) {
                $mail->AddAddress($customer->email_address, $customer->first_name . ' ' . $customer->last_name);
                /*if ($MAIL_OR_SMTP == 2) {
                    $mail->SMTPDebug = false;
                    $mail->IsSMTP(); // �������� ��������� SMTP
                    $mail->Host = $SMTP_HOST; // SMTP ������
                    $mail->Port = $SMTP_PORT;
                    $mail->SMTPAuth = true; // �������� SMTP ��������������
                    $mail->Username = $SMTP_USER; // ��������� ��� �����
                    $mail->Password = $SMTP_PASS; // ������� ������ �� ��������� ����
                }*/
                $mail->Send();
                $mlog = new Mailsendlog();
                $mlog->comment = "В связи с изменением статуса заказа. <br> №" . ($order->prefix) . $order_id . "<br> Новый статус: " . $newStatusId . "<br> Шаблон: " . ($templateName) . "<br> Почта: " . ($customer->email_address);
                $mlog->save();
            }
        }
    }
}



/*
  if ((mb_strtolower($newStatusId) == "оплачен" or mb_strtolower($newStatusId) == "отмена") and mb_strtolower($order->status) != "оплачен" and mb_strtolower($order->status) != "отмена") {
  $html_mail_order_success = Settings::find_by_id(48);
  $html_mail_order_nosuccess = Settings::find_by_id(49);

  if (mb_strtolower($newStatusId) == "оплачен")
  $template = $html_mail_order_success;
  else
  $template = $html_mail_order_nosuccess;

  $template = str_replace('{linkOprosnik}', 'http://www.onona.ru/oprosnik/'.md5($order_id), $template);
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


  require_once __DIR__ . '/classes/PhpMailer.class.php';
  $mail = new PhpMailer();
  $mail->From = 'info@' . $SITE_NAME;
  $mail->FromName = 'Почтовый робот сайта ' . $SITE_NAME;
  $mail->CharSet = 'utf-8';
  $mail->IsHTML(true);
  $mail->Body = $template;
  $mail->Subject = "Спасибо за ваш заказ. Оставьте отзыв о магазине.";
  if ($customer->email_address != '') {
  $mail->AddAddress($customer->email_address, $customer->first_name . ' ' . $customer->last_name);
  if ($MAIL_OR_SMTP == 2) {
  $mail->SMTPDebug = false;
  $mail->IsSMTP(); // �������� ��������� SMTP
  $mail->Host = $SMTP_HOST; // SMTP ������
  $mail->Port = $SMTP_PORT;
  $mail->SMTPAuth = true; // �������� SMTP ��������������
  $mail->Username = $SMTP_USER; // ��������� ��� �����
  $mail->Password = $SMTP_PASS; // ������� ������ �� ��������� ����
  }
  //$mail->Send();
  }
  } */
?>
