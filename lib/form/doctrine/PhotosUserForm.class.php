<?php

/**
 * PhotosUser form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhotosUserForm extends BasePhotosUserForm
{
  public function configure()
  {
      unset($this['user_id']);
      if ($this->isNew or $this->getObject()->getFilename()=="") {
            $this->setWidget('filename', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['filename'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/photouser/thumbnails/' . $this->getObject()->getFilename(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }

        $this->validatorSchema['filename'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/photouser/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoCatalogValidatedFile',
                ));
        $this->validatorSchema['filename_delete'] = new sfValidatorPass();
  }
}
