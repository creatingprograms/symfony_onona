<?php

class otherworkTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        // $this->addArguments(array(
        //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
        // ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'newcat'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'otherwork';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [otherwork|INFO] task does things.
Call it with:

  [php symfony otherwork|INFO]
EOF;
    }

    private function create_watermark($main_img_obj, $text, $font, $r = 128, $g = 128, $b = 128, $alpha_level = 70) {

        $width = imagesx($main_img_obj);
        $height = imagesy($main_img_obj);
        $angle = rad2deg(atan2(($height), ($width)));

        $c = imagecolorallocatealpha($main_img_obj, $r, $g, $b, $alpha_level);
        $size = (($width + $height) / 2) * 2 / strlen($text);
        $box = imagettfbbox($size, $angle, $font, $text);
        $x = $width / 2 - abs($box[4] - $box[0]) / 2;
        $y = $height / 2 + abs($box[5] - $box[1]) / 2;

        imagettftext($main_img_obj, $size, $angle, $x, $y, $c, $font, $text);
        return $main_img_obj;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        ini_set("max_execution_time", 1200);
        /* $users = sfGuardUserTable::getInstance()->createQuery()->fetchArray();
          foreach ($users as $user) {
          //print_r($user);exit;
          $bonus = new Bonus();
          $bonus->setUserId($user['id']);
          $bonus->setBonus(2015);
          $bonus->setComment("Подарок к Новому 2015 году!");
          $bonus->save();
          } */
        
        
        
        
   /*     $photos = PhotoTable::getInstance()->createQuery()->fetchArray();
        foreach ($photos as $photo) {
            if (file_exists("/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename'])) {
                $photofile = "/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename'];
                if (exif_imagetype($photofile) == IMAGETYPE_GIF) {
                    $image = imagecreatefromgif($photofile);
                } elseif (exif_imagetype($photofile) == IMAGETYPE_JPEG) {
                    $image = imagecreatefromjpeg($photofile);
                } elseif (exif_imagetype($photofile) == IMAGETYPE_PNG) {
                    $image = imagecreatefrompng($photofile);
                }
                if ($image) {
                    copy($photofile, "/var/www/ononaru/data/www/onona.ru/uploads/photo/original/" . $photo['filename']);

                    $info_o = @getImageSize($photofile);
                    $txt = '  OnOna.Ru  ';
                    $im = $this->create_watermark($image, $txt, "/var/www/ononaru/data/www/onona.ru/fonts/arial.ttf");
                    $out = imageCreateTrueColor($info_o[0], $info_o[1]);

                    imageCopy($out, $im, 0, 0, 0, 0, $info_o[0], $info_o[1]);
                    imagejpeg($out, "/var/www/ononaru/data/www/onona.ru/uploads/photo/" . $photo['filename']);
/*
                    if ($info_o[0] > $info_o[1])
                        $max = $info_o[0];
                    else
                        $max = $info_o[1];
                    $koef = $max / 175;
                    $out2 = imageCreateTrueColor($info_o[0] / $koef, $info_o[1] / $koef);
                    ImageCopyResampled($out2, $out, 0, 0, 0, 0, $info_o[0] / $koef, $info_o[1] / $koef, $info_o[0], $info_o[1]);
                    imagejpeg($out2, "/var/www/ononaru/data/www/onona.ru/uploads/photouser/thumbnails/" . $namePhoto . ".jpg");* /
                    ImageDestroy($image);
                    ImageDestroy($out2);
                    ImageDestroy($out);
                    exit;
                }else{
                    $errorImage[]=$photo;
                }
            } else {
                $errorFile[]=$photo;
            }
        }
        echo "errorImage:";
print_r($errorImage);
        echo "errorFile:";
print_r($errorFile);*/

        // add your code here
    }

}
