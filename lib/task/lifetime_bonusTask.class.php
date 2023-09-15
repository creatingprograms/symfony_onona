<?php

class lifetime_bonusTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "new"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'lifetime_bonus';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [lifetime_bonus|INFO] task does things.
Call it with:

  [php symfony lifetime_bonus|INFO]
EOF;
    }

    public function is_email($email) {
        //return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //echo "Valid email address.";
            return true;
        } else {
            return false;
            //echo "Invalid email address.";
        }
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if (csSettings::get("BONUS_TIME") > 0) {
            $BONUS_TIME = csSettings::get("BONUS_TIME");
        } else {
            $BONUS_TIME = 90;

            file_put_contents("/var/www/ononaru/data/www/settingsErrors.log", date("m.d.y H:i:s") . " Нету настроек в таске снятия бонусов" . "\n");
        }

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("select * from (SELECT *, MAX(created_at) as max, unix_timestamp(MAX(created_at)) as max_unix, sum(bonus) as sum FROM bonus GROUP BY user_id ORDER BY max asc) as fr WHERE sum>0 and max_unix<" . (time() - $BONUS_TIME * 24 * 60 * 60));
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $bonusLog = $result->fetchAll();

        foreach ($bonusLog as $bonus) {
            if (strtotime($bonus['max']) < (time() - $BONUS_TIME * 24 * 60 * 60)) {
                $bonusToDel = $bonus['sum'];
                if ($bonusToDel > 0) {
                    $bonusDeleteObj = new Bonus();
                    $bonusDeleteObj->set("user_id", $bonus['user_id']);
                    $bonusDeleteObj->set("bonus", 0 - $bonusToDel);
                    $bonusDeleteObj->set("comment", "Снятие средств. Истекло время жизни. Последняя операция: " . $bonus['max']);
                    $bonusDeleteObj->save();
                }
            }
        }

        if (date("j") == csSettings::get("rcon_bonus_day")) {

            $result = $q->execute("SELECT * 
                               FROM (

                                    SELECT b . * , u.first_name, u.last_name, u.email_address, MAX( b.created_at ) AS max, SUM( b.bonus ) AS sum, CEIL((" . ($BONUS_TIME * 24 * 60 * 60) . "-(UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(MAX( b.created_at ))))/" . (24 * 60 * 60) . ") as daysRemote
                                    FROM bonus AS b
                                    LEFT JOIN sf_guard_user AS u ON u.ID = b.user_id
                                    GROUP BY user_id
                                    ORDER BY max DESC
                                ) AS fr
                                WHERE sum >0
                                AND daysRemote >0");
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $bonusLog = $result->fetchAll();
            $subjectCron = csSettings::get("subject_bonus_cron");
            $PERSENT_BONUS_PAY = csSettings::get("PERSENT_BONUS_PAY");
            $html_mail_cron_bonus = csSettings::get("html_mail_cron_bonus");
            $smtp_user = csSettings::get('smtp_user');
            sfContext::createInstance($this->configuration);
            foreach ($bonusLog as $numSend => $bonus) {

                if ($this->is_email(trim($bonus['email_address']))) {
                    $message = Swift_Message::newInstance();
                    $message->setFrom($smtp_user, "OnOna.ru")
                            ->setTo(trim($bonus['email_address']))
                            ->setSubject($subjectCron)
                            ->setBody(str_replace(array("{firstname}", "{allbonus}", "{shop}", "{day}", "{perbonus}", "\r\n"), array($bonus['first_name'], $bonus['sum'], '<a href="http://onona.ru">OnOna.ru</a>', $bonus['daysRemote'], $PERSENT_BONUS_PAY, "<br />"), $html_mail_cron_bonus), 'text/html');


                    $numSent = $this->getMailer()->send($message);

                    $bonusLog2 = new BonusMailsendLog();
                    $bonusLog2->set("mail", trim($bonus['email_address']));
                    $bonusLog2->set("bonus", $bonus['sum']);
                    $bonusLog2->set("day", $bonus['daysRemote']);
                    $bonusLog2->save();
                    sleep(3);
                }
            }
        }


        if (date("j") != csSettings::get("rcon_bonus_day")) {
            $result = $q->execute("SELECT * 
                               FROM (

                                    SELECT b . * , u.first_name, u.last_name, u.email_address, MAX( b.created_at ) AS max, SUM( b.bonus ) AS sum, CEIL((" . ($BONUS_TIME * 24 * 60 * 60) . "-(UNIX_TIMESTAMP(now())-UNIX_TIMESTAMP(MAX( b.created_at ))))/" . (24 * 60 * 60) . ") as daysRemote
                                    FROM bonus AS b
                                    LEFT JOIN sf_guard_user AS u ON u.ID = b.user_id
                                    GROUP BY user_id
                                    ORDER BY max DESC
                                ) AS fr
                                WHERE sum >0
                                AND daysRemote =3");
            $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
            $bonusLog = $result->fetchAll();
            foreach ($bonusLog as $bonus) {


                if ($this->is_email(trim($bonus['email_address']))) {

                    sfContext::createInstance($this->configuration);
                    $message = Swift_Message::newInstance()
                            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                            ->setTo(trim($bonus['email_address']))
                            ->setSubject(csSettings::get("subject_bonus_cron_3_day"))
                            ->setBody(str_replace(array("{firstname}", "{allbonus}", "{shop}", "{day}", "{perbonus}", "\r\n"), array($bonus['first_name'], $bonus['sum'], '<a href="http://onona.ru">OnOna.ru</a>', $bonus['daysRemote'], csSettings::get("PERSENT_BONUS_PAY"), "<br />"), csSettings::get("html_mail_cron_bonus_3_day")), 'text/html');


                    $numSent = $this->getMailer()->send($message);
                    $bonusLog2 = new BonusMailsendLog();
                    $bonusLog2->set("mail", trim($bonus['email_address']));
                    $bonusLog2->set("bonus", $bonus['sum']);
                    $bonusLog2->set("day", $bonus['daysRemote']);
                    $bonusLog2->save();
                    sleep(3);
                }
            }
        }
    }

}
