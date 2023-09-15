<?php

/**
 * Experts form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ExpertsForm extends BaseExpertsForm
{
  public function configure()
  {
    if ($this->isNew()) {

        $this->setWidget('user', new sfWidgetFormInputHidden(array(), array('value' => sfContext::getInstance()->getUser()->getGuardUser()->getId())));
        $this->setValidator("user", new sfValidatorString(array('required' => false)));
    }
    if ($this->isNew or $this->getObject()->getPhotoUrl() == "") {
        $this->setWidget('photo_url', new sfWidgetFormInputFile());
    } else {
        $this->widgetSchema['photo_url'] = new sfWidgetFormInputFileEditable(array(
            'label' => 'Изображение',
            'file_src' => '/uploads/photo/' . $this->getObject()->getPhotoUrl(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ), array("style" => "float:left;"));
    }

    $this->validatorSchema['photo_url'] = new sfValidatorFile(array(
        'required' => false,
        'path' => sfConfig::get('sf_upload_dir') . '/photo/',
        'mime_types' => 'web_images',
        'validated_file_class' => 'sfPhotoCatalogValidatedFile',
    ));
    $this->validatorSchema['img_delete'] = new sfValidatorPass();
  }
}
