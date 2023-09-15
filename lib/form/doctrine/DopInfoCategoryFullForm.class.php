<?php

/**
 * DopInfoCategoryFull form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DopInfoCategoryFullForm extends BaseDopInfoCategoryFullForm
{
  public function configure()
  {
        unset($this['category_full_list']);
         if ($this->isNew or $this->getObject()->getFileName()=="") {
            $this->setWidget('filename', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['filename'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/dopinfo/thumbnails/' . $this->getObject()->getFileName(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                    ),array("style"=>"float:left;"));
        }

        $this->validatorSchema['filename'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/dopinfo/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoAlbumValidatedFile',
                ));
        $this->validatorSchema['filename_delete'] = new sfValidatorPass();
  }
}
