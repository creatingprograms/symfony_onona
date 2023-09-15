<?php

mb_internal_encoding('UTF-8');
//$bonus = round((($order_data['total_cost'] * $persent_bonus_add) / 100));
if ((mb_strtolower($newStatusId) == "оплачен" and mb_strtolower($order->status) != "оплачен")) {
    $html_mail_add_bonus = Settings::find_by_id(10);

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
    $persent_bonus_add = Settings::find_by_id(12);
    $persent_bonus_add = $persent_bonus_add->value;
    $persent_bonus_pay = Settings::find_by_id(14);
    $persent_bonus_pay = $persent_bonus_pay->value;
    $subject_bonus_add = Settings::find_by_id(38);
    $subject_bonus_add = $subject_bonus_add->value;


//echo $html_mail_add_bonus->value;
    $productsForBonus = $order_data['products'];
    $bonus = 0;
    /* echo '<pre>';
      print_r($productsForBonus);
      echo '</pre>'; */
    foreach ($productsForBonus as $artForBonus => $artInfoForBonus) {//echo $stockId;exit;
        $productForBonus = Products::find_all_by_code($artForBonus);
        $productForBonus = $productForBonus[0];
        if (!empty($productForBonus)) {
            if ($productForBonus->bonus != 0) {
                $bonus = $bonus + round((($artInfoForBonus['price_w_discount']>0?$artInfoForBonus['price_w_discount']:$artInfoForBonus['price']) * $artInfoForBonus['count'] * $productForBonus->bonus) / 100);
            } else {
                $bonus = $bonus + round((($artInfoForBonus['price_w_discount']>0?$artInfoForBonus['price_w_discount']:$artInfoForBonus['price']) * $artInfoForBonus['count'] * $persent_bonus_add) / 100);
            }
        }
    }


    /* $customer->bonus = $customer->bonus + $bonus;
      $customer->time = time(); */
    
    $bonusLogForOrder = BonusLog::find(array('conditions' => "comment like '%Зачисление за заказ #" . ($order->prefix) . $order_id . "%'"));
    if (!isset($bonusLogForOrder)) {
        $bo = new BonusLog();
        $bo->user_id = $customer->id;
        $bo->bonus = $bonus;
        $bo->comment = "Зачисление за заказ #" . ($order->prefix) . $order_id;
        $bo->created_at = time();
        $bo->save();

        $html_mail_add_bonus = str_replace(
                array('{firstname}', '{lastname}', '{bonus}', '{shop}', '{perbonus}'), array($customer->first_name, $customer->last_name, $bonus, $SITE_NAME, $persent_bonus_pay), $html_mail_add_bonus->value);

        require_once __DIR__ . '/classes/PhpMailer.class.php';
        $mail = new PhpMailer();
        $mail->From = 'info@' . $SITE_NAME;
        $mail->FromName = 'Почтовый робот сайта ' . $SITE_NAME;
        $mail->CharSet = 'utf-8';
        $mail->IsHTML(true);
        $mail->Body = $html_mail_add_bonus;
        $mail->Subject = $subject_bonus_add;
        if ($customer->email_address != '') {
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
        }
    }
}
?>
