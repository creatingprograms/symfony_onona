<?php

/**
 * Collection form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CollectionForm extends BaseCollectionForm
{
  public function configure()
  {
    if ($this->isNew or $this->getObject()->getImage()=="") {
        $this->setWidget('image', new sfWidgetFormInputFile());
    }
    else {
        $this->widgetSchema['image'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Изображение',
                    'file_src' => '/uploads/collection/' . $this->getObject()->getImage(),
                    'is_image' => true,
                    'edit_mode' => !$this->isNew(),
                    'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                        ), array("style" => "float:left;"));
    }

    $this->validatorSchema['image'] = new sfValidatorFile(array(
        'required' => false,
        'path' => sfConfig::get('sf_upload_dir') . '/collection/',
        'mime_types' => 'web_images',
        'validated_file_class' => 'sfImagesValidatedFile',
    ));
  }
}
