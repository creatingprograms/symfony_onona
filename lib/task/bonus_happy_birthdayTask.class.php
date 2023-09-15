<?php

class bonus_happy_birthdayTask extends sfBaseTask {

    protected function is_email($email) {
        return preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email);
    }

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
        $this->name = 'bonus_happy_birthday';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [bonus_happy_birthday|INFO] task does things.
Call it with:

  [php symfony bonus_happy_birthday|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        //echo "1";
        //exit;
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        sfProjectConfiguration::getActive()->loadHelpers('Date');




        //
        //echo csSettings::get("bonus_happy_birthday");
        //echo csSettings::get("day_bonus_happy_birthday");
        //echo csSettings::get("email_bonus_happy_birthday");
        //
        $users = sfGuardUserTable::getInstance()->createQuery()->where("MONTH(birthday) = " . date("m", (time()/* + (csSettings::get("day_bonus_happy_birthday") * 24 * 60 * 60) */)) . " and DAY(birthday)= " . date("d", (time()/* + (csSettings::get("day_bonus_happy_birthday") * 24 * 60 * 60) */)) . " and birthday<>'1970-01-01'")->execute(); //findByBirthday(date("m-d", (time()+(csSettings::get("day_bonus_happy_birthday")*24*60*60))));
//$users->getSqlQuery()
        foreach ($users as $user) {
            //if ($user->getId() == "17101" or $user->getId() == "3546") {
            //$users=  sfGuardUserTable::getInstance()->createQuery()->where("MONTH(birthday) = ".date("m", (time()+(csSettings::get("day_bonus_happy_birthday")*24*60*60)))." and DAY(birthday)= ".date("d", (time()+(csSettings::get("day_bonus_happy_birthday")*24*60*60))))->execute( );//findByBirthday(date("m-d", (time()+(csSettings::get("day_bonus_happy_birthday")*24*60*60))));
            $bonus = BonusTable::getInstance()->createQuery()->where("created_at > \"" . (date("Y", (time()/* + (csSettings::get("day_bonus_happy_birthday") * 24 * 60 * 60) */)) - 1) . "-" . date("m-d", (time()+86400/* + (csSettings::get("day_bonus_happy_birthday") * 24 * 60 * 60) */)) . "\"")->addWhere("user_id = " . $user->getId())->addWhere("comment = 'Бонус в День Рождения'")->execute();
           
            if ($bonus->count() == 0) {
                $newBonus = new Bonus();
                $newBonus->setUserId($user);
                $newBonus->setBonus(csSettings::get("bonus_happy_birthday"));
                $newBonus->setComment('Бонус в День Рождения');
                $newBonus->save();

                if ($this->is_email($user->getEmailAddress())) {
                    //$this->getMailer();
                    sfContext::createInstance($this->configuration);
                    $this->bonus = BonusTable::getInstance()->findBy('user_id', $user->getId());
                    $this->bonusCount = 0;
                    foreach ($this->bonus as $bonus) {
                        $this->bonusCount = $this->bonusCount + $bonus->getBonus();
                    }
                    $mailTemplate = MailTemplatesTable::getInstance()->findOneBySlug('happy_birthday');
                    $message = Swift_Message::newInstance()
                            ->setFrom(csSettings::get('smtp_user'), "OnOna.ru")
                            ->setTo($user->getEmailAddress())
                            ->setSubject($mailTemplate->getSubject())
                            ->setBody(str_replace(array('{firstname}', '{birthday}'), array($user->getName(), format_date($user->getBirthday(), "D")), $mailTemplate->getText()), 'text/html')
                            ->setContentType('text/html')
                    ;
                    $this->getMailer()->send($message);
                    //echo $this->getMailer()->getLogger();
                }
            }
            //echo $user->getName();
            //}
        }
        //echo $users->count();
    }

}
