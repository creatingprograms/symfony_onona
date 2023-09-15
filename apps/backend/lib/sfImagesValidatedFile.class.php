<?php
class sfImagesValidatedFile extends sfValidatedFile
{
  public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
  {
     $file_name = parent::save($file, $fileMode, $create, $dirMode);


     $img = new sfImage($this->path.$file_name);
    // $img->overlay(new sfImage(sfConfig::get('sf_upload_dir').'/overlay.png'), 'middle-center');
     $img->setQuality(100);
     $img->saveAs($this->path.$file_name);
     
     

     return $file_name;
  }
}
