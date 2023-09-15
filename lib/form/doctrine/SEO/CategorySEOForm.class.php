<?php

/**
 * Category form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CategorySEOForm extends BaseCategoryForm {

    public function configure() {
        $this->setWidget('parents_id', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => false, 'model' => $this->getRelatedModelName('Parent'), 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC'), 'add_empty'=> true)));
        
        unset($this['countProductActions'], $this['filtersnew'], $this['minPrice'], $this['maxPrice'], $this['position'], $this['positionloveprice'], $this['category_products_list'], $this['category_catalogs_list'],
                $this['name'], $this['slug'], $this['parents_id'], $this['is_open'], $this['is_public'], $this['adult'], $this['filters'], $this['rrproductid'], $this['lastupdaterrproductid']);

    }

}
