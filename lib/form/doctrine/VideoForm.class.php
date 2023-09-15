<?php

/**
 * Video form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class VideoForm extends BaseVideoForm {

    public function configure() {
        unset($this['link']);
        if ($this->object->exists()) {
            $this->widgetSchema['photodelete'] = new sfWidgetFormInputCheckbox();
            $this->validatorSchema['photodelete'] = new sfValidatorPass();
        }

        if ($this->isNew) {
            $this->setWidget('photo', new sfWidgetFormInputFile());
            $this->setWidget('videoserver', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['photo'] = new sfWidgetFormInputFileEditable(array(
                'label' => 'Изображение',
                'file_src' => '/uploads/photovideo/' . $this->getObject()->getPhoto(),
                'is_image' => true,
                'edit_mode' => !$this->isNew(),
                'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                    ), array("style" => "float:left; max-width: 400px;"));
            $this->widgetSchema['videoserver'] = new sfWidgetFormInputFileEditable(array(
                'label' => 'Изображение',
                'file_src' => '/uploads/video/' . $this->getObject()->getVideoserver(),
                'is_image' => false,
                'edit_mode' => $this->getObject()->getVideoserver(),
                /*'template' => '<div>%file%%fileInfo%<br />%input%</div>',*/
                    ));
        }

        $this->validatorSchema['photo'] = new sfValidatorFile(array(
            'required' => false,
            'path' => sfConfig::get('sf_upload_dir') . '/photovideo/',
            'mime_types' => 'web_images',
            'validated_file_class' => 'sfPhotovideoValidatedFile',
        ));
        $this->validatorSchema['photo_delete'] = new sfValidatorPass();


        $this->setValidator('videoserver', new sfValidatorFile(array(
            'required' => false,
            'path' => sfConfig::get('sf_upload_dir') . '/video/',
        )));
        $this->validatorSchema['videoserver_delete'] = new sfValidatorPass();
    }

}
