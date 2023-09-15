<?php

class errortestTask extends sfBaseTask {

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
        $this->name = 'errortest';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [yamarket|INFO] Проверяет код ответа http сайта и чистит его кэш при ошибке
Call it with:

  [php symfony errortest|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

      $projects = [
        ['url' => 'https://onona.ru']
      ];

      if (sizeOf($projects) > 0) foreach ($projects as $project) {

         $headers = $this->getHeaders($project['url']);

         if (preg_match('/^HTTP\/1\.[01] ([0-9]{3})/', $headers, $m)) $code = $m[1];
         else $code = 0;

         // if (preg_match('/MySQL<\/b>\: Unable/i', $headers)) $code = 404;

         if ($code == '301' && preg_match('/Location: ([^\n|\r]+)/', $headers, $loc)) {
          $headers2 =  $this->getHeaders(trim($loc[1]));
          if (preg_match('/^HTTP\/1\.[01] ([0-9]{3})/', $headers2, $m)) $code = $m[1];
          else $code = 0;
         }
         // echo
          // shell_exec('ls');
        $message = shell_exec('/var/www/ononaru/data/www/bin/errortest.sh');

         // die(print_r(['code'=>$code],true));
         if ($code <> 200) {
         // if ($code == 200) {
          //Письмо для газпрома
            $strGaz = $project['url']." (код - ".($code ? $code : '').', Все пропало, онона упала'.")\n".$message;
            //mail("Yury.Osipenko@gazprombank.ru", "Уведомление о недоступности сайта", $strGaz, "Content-Type: text/plain; charset=windows-1251\nFrom: Компания ИнтерЛабс <info@interlabs.ru>");
            //mail("Alexander.Tupitsyn@gazprombank.ru", "Уведомление о недоступности сайта", $strGaz, "Content-Type: text/plain; charset=windows-1251\nFrom: Компания ИнтерЛабс <info@interlabs.ru>");
            mail("aushakov@interlabs.ru", "Уведомление о недоступности сайта", $strGaz, "Content-Type: text/plain; charset=utf-8\nFrom: Компания ИнтерЛабс <info@interlabs.ru>");
         }
      }
    }

    protected function getHeaders($url) {
         $headers = '';
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_HEADER, 1);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
         $headers = curl_exec($ch);
         curl_close($ch);
         unset($ch);
         return $headers;
      }




}
