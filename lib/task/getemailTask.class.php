<?php

class getemailTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));
        $this->addArgument
                (
                'type', // название аргумента
                sfCommandArgument::OPTIONAL, // тип аргумента
                'Тип запроса', // справка
                'all' // значение по умолчанию
        );

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'getemail';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [getemail|INFO] task does things.
Call it with:

  [php symfony getemail|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if ($arguments['type'] == "all"):
            $emails = sfGuardUserTable::getInstance()->findAll();
            foreach ($emails as $email) {
                //echo $email->getEmailAddress();
                $emailtable = $emailtable . $email->getEmailAddress() . "\r\n";
            }
            //echo 
            $file = fopen('/var/www/ononaru/data/www/email/all.txt', "w+");
            fputs($file, $emailtable, (strlen($emailtable) + 1));
            fclose($file);
        ///
        elseif ($arguments['type'] == "city"):
            $emails = sfGuardUserTable::getInstance()->createQuery()->where("city like \"%Москва%\" or city=\"москва\" or city=\"moscow\" or city like \"%Moscow%\" or city=\"Мос\" or city=\"Москв\" or city=\"мос\" or city=\"mos\" or city=\"Mos\"")->execute();

            require_once 'PHPExcel/Classes/PHPExcel.php';
            $objPHPExcel = new PHPExcel();

            /* устанавливаем метаданные */
            $objPHPExcel->getProperties()->setCreator("PHP")
                    ->setLastModifiedBy("")
                    ->setTitle("Office 2007 XLSX Москва")
                    ->setSubject("Office 2007 XLSX Москва")
                    ->setDescription("Список пользователей из Москвы")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Москва");
            $objPHPExcel->getActiveSheet()->setTitle('Москва');

            $styleArray = array(
                'font' => array(
                    'bold' => true
                )
            );

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'E-Mail')
                    ->setCellValue('B1', 'Город')
                    ->setCellValue('C1', 'Телефон');

            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);

            foreach ($emails as $key => $email) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(0, $key + 2, $email->getEmailAddress())
                        ->setCellValueByColumnAndRow(1, $key + 2, $email->getCity())
                        ->setCellValueByColumnAndRow(2, $key + 2, $email->getPhone());
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('/var/www/ononaru/data/www/email/moscow.xsl');

            
            
            $emails = sfGuardUserTable::getInstance()->createQuery()->where("city not like \"%Москва%\" and city<>\"москва\" and city<>\"moscow\" and city not like \"%Moscow%\" and city<>\"Мос\" and city<>\"Москв\" and city<>\"мос\" and city<>\"mos\" and city<>\"Mos\"")->execute();

            require_once 'PHPExcel/Classes/PHPExcel.php';
            $objPHPExcel = new PHPExcel();

            /* устанавливаем метаданные */
            $objPHPExcel->getProperties()->setCreator("PHP")
                    ->setLastModifiedBy("")
                    ->setTitle("Office 2007 XLSX Москва")
                    ->setSubject("Office 2007 XLSX Москва")
                    ->setDescription("Список пользователей из Москвы")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Москва");
            $objPHPExcel->getActiveSheet()->setTitle('Москва');

            $styleArray = array(
                'font' => array(
                    'bold' => true
                )
            );

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'E-Mail')
                    ->setCellValue('B1', 'Город')
                    ->setCellValue('C1', 'Телефон');

            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);

            foreach ($emails as $key => $email) {
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValueByColumnAndRow(0, $key + 2, $email->getEmailAddress())
                        ->setCellValueByColumnAndRow(1, $key + 2, $email->getCity())
                        ->setCellValueByColumnAndRow(2, $key + 2, $email->getPhone());
            }

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('/var/www/ononaru/data/www/email/other.xsl');

        endif;
    }

    protected function executeMoscow($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['  

        connection'])->getConnection();


        $emails = sfGuardUserTable::getInstance()->findAll();
        foreach ($emails as $email) {
            //echo $email->getEmailAddress();
            $emailtable = $emailtable . $email->getEmailAddress() . "\r\n";
        }
        //echo 
        $file = fopen('/var/www/ononaru/data/www/email.txt   ', "w+");
        fputs($file, $emailtable, (strlen($emailtable) + 1));
        fclose($file);
        // add your code here
    }

}
