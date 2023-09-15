<?php

/**
 * Shops form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ShopsForm extends BaseShopsForm
{
  public function configure()
  {
        $this->setWidget('page_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'),'query' => Doctrine_Query::create()->select('*')->from('page p')->leftJoin("p.Categorypage c")->where("c.name=\"Магазины\""), 'add_empty'=> true)));
        $this->widgetSchema['Description'] = new sfWidgetFormCKEditor();

        if ($this->isNew or $this->getObject()->getIconmetro()=="") {
            $this->setWidget('Iconmetro', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['Iconmetro'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/metro/' . $this->getObject()->getIconmetro(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }

        if ($this->isNew or $this->getObject()->getIconmetro()=="") {
            $this->setWidget('preview_image', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['preview_image'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/assets/images/' . $this->getObject()->getPreviewImage(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                      ), array("style" => "float:left; max-width:240px;"));
        }


        $this->validatorSchema['Iconmetro'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/metro/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfImagesValidatedFile',
                ));
        $this->validatorSchema['preview_image'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/assets/images/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfImagesValidatedFile',
                ));
  }
}
