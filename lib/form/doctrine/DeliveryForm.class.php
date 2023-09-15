<?php

/**
 * Delivery form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DeliveryForm extends BaseDeliveryForm
{
  public function configure()
  {
        unset($this['position']);
        if ($this->isNew or $this->getObject()->getPicture()=="") {
            $this->setWidget('picture', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['picture'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/delivery/' . $this->getObject()->getPicture(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }
        $this->validatorSchema['picture'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/delivery/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfImagesValidatedFile',
                ));

        if ($this->isNew or $this->getObject()->getPicturehover()=="") {
            $this->setWidget('picturehover', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['picturehover'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/delivery/' . $this->getObject()->getPicturehover(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }
        $this->validatorSchema['picturehover'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/delivery/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfImagesValidatedFile',
                ));
  }
}
