<?php

/**
 * Category form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CategoryForm extends BaseCategoryForm {

    public function configure() {
        unset($this['countProductActions'], $this['filtersnew'], $this['minPrice'], $this['maxPrice'], $this['position'], $this['positionloveprice'], $this['category_products_list'], $this['category_catalogs_list']);

        $this->setWidget('parents_id', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => false, 'model' => $this->getRelatedModelName('Parent'), 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC'), 'add_empty'=> true)));
        if ($this->isNew) {
            $this->setWidget('img', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['img'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/catalog_icons/' . $this->getObject()->getImg(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                            ), array("style" => "float:left;"));
        }

        $this->validatorSchema['img'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/catalog_icons/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfPhotoCatalogValidatedFile',
                ));
        $this->validatorSchema['img_delete'] = new sfValidatorPass();
    }

}
