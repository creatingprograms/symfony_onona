<?php

/**
 * Catalog form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CatalogForm extends BaseCatalogForm {

    public function configure() {
        unset($this['prodid_bestprice'],$this['prodid_random']);
        $this->setWidget('category_catalogs_list', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => true, 'children' => false, 'model' => 'Category', 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC'), 'where' => 'parents_id is NULL', 'add_empty' => true), array('style' => 'height:200px')));



        if (isset($this->widgetSchema['page']))
            $this->widgetSchema['page'] = new sfWidgetFormCKEditor();

        if ($this->isNew) {
            $this->setWidget('img', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['img'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/photo/thumbnails_250x250/' . $this->getObject()->getImg(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }

        $this->validatorSchema['img'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/photo/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoCatalogValidatedFile',
                ));
        if ($this->isNew) {
            $this->setWidget('img_top', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['img_top'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение вверху',
                        'file_src' => '/uploads/photo/' . $this->getObject()->getImgTop(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%delete%<br />%delete_label%<br />%file%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }
        if ($this->isNew) {
            $this->setWidget('img_bottom', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['img_bottom'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение Внизу',
                        'file_src' => '/uploads/photo/' . $this->getObject()->getImgBottom(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%delete%<br />%delete_label%<br />%file%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }

        $this->validatorSchema['img_top'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/photo/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoCatalogValidatedFile',
                ));
        $this->validatorSchema['img_bottom'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/photo/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoCatalogValidatedFile',
                ));
        $this->validatorSchema['img_delete'] = new sfValidatorPass();
    }

}
