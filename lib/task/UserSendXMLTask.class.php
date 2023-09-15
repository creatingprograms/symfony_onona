<?php

class UserSendXMLTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', "newcat"),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'UserSendXML';
        $this->briefDescription = 'Создание XML файла для выгрузки в 1С с запросами "сообщить о полступлении"';
        $this->detailedDescription = <<<EOF
The [UserSendXML|INFO] task does things.
Call it with:

  [php symfony UserSendXML|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();



        $host = "onona.ru";
        $userFTP = "prog1c";
        $passwordFTP = "ZDsY5yLxRX";


        $q = Doctrine_Manager::getInstance()->getCurrentConnection();
        $result = $q->execute("SELECT s.*,p.code as code FROM senduser as s left join product as p on p.id=s.product_id");
        $result->setFetchMode(Doctrine_Core::FETCH_ASSOC);
        $allUserSendsQuery = $result->fetchAll();

        $xmlFile = "<?xml version=\"1.0\"?><Querys>\r\n";
        foreach ($allUserSendsQuery as $query) {
            if ($query["code"] != "") {
                $xmlFile .= "<Query>\r\n";
                $xmlFile .= "<QueryID>" . ($query["id"]) . "</QueryID>\r\n";
                $xmlFile .= "<ProductID>" . ($query["product_id"]) . "</ProductID>\r\n";
                $xmlFile .= "<ProductArticle><![CDATA[" . ($query["code"]) . "]]></ProductArticle>\r\n";
                $xmlFile .= "<Name><![CDATA[" . ($query["name"]) . "]]></Name>\r\n";
                $xmlFile .= "<Mail><![CDATA[" . ($query["mail"]) . "]]></Mail>\r\n";
                $xmlFile .= "<IsSend>" . ($query["is_send"]) . "</IsSend>\r\n";
                $xmlFile .= "<CreatedAt>" . ($query["created_at"]) . "</CreatedAt>\r\n";
                $xmlFile .= "</Query>\r\n";
            }
        }
        $xmlFile.="</Querys>\r\n";
        file_put_contents("/var/www/ononaru/data/www/xml/UserSendXML.xml", $xmlFile);

        $connect = ftp_connect($host);
        $result = ftp_login($connect, $userFTP, $passwordFTP);

        ftp_put($connect, "UserSendXML.xml", "/var/www/ononaru/data/www/xml/UserSendXML.xml", FTP_ASCII);

        ftp_quit($connect);
        //echo $xmlFile;
    }

}
