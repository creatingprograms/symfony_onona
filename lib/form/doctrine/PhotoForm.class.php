<?php

/**
 * Photo form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhotoForm extends BasePhotoForm {

    public function configure() {

        unset($this['album_id']);

        if ($this->object->exists()) {
            $this->widgetSchema['photodelete'] = new sfWidgetFormInputCheckbox();
            $this->validatorSchema['photodelete'] = new sfValidatorPass();
        }
        
        if ($this->isNew) {
            $this->setWidget('filename', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['filename'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/photo/thumbnails_250x250/' . $this->getObject()->getFileName(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                    ),array("style"=>"float:left;"));
        }

        $this->validatorSchema['filename'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/photo/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoAlbumValidatedFile',
                ));
        $this->validatorSchema['filename_delete'] = new sfValidatorPass();
    }
}
