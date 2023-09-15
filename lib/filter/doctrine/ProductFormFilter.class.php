<?php

/**
 * Product filter form.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductFormFilter extends BaseProductFormFilter {

    public function configure() {

        $this->setWidget('category_products_list', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => true, 'model' => 'Category', 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC')), array('style' => 'height:200px')));

        $this->setWidget('dop_info_products_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'DopInfo', 'query' => Doctrine_Query::create()->select('di.value, c.name as nameCategory')->from('dopinfo di')->leftJoin("di.DopInfoCategory c")->where("c.name=\"Производитель\" or c.name=\"Коллекция\" ")->orderBy("c.name"), 'method' => 'getCategoryValue'), array('style' => 'height:200px')));

        $this->setWidget('count_range', new sfWidgetFormFilterInputRange(array(
                    'from_value' => new sfWidgetFormInput(array(), array('style' => 'width:100px')),
                    'to_value' => new sfWidgetFormInput(array(), array('style' => 'width:100px')),
                    'with_empty' => false
                )));

        $this->setValidator('count_range', new sfValidatorInputRange(array(
                    'required' => false,
                    'from_value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
                    'to_value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false)))
                )));
    }

    public function addCountRangeColumnQuery($query, $field, $value) {
        $rootAlias = $query->getRootAlias();
        if (isset($value['from']) && $value['from'])
            $query->andWhere($rootAlias . ".count >= ?", $value['from']);
        if (isset($value['to']) && $value['to'])
            $query->andWhere($rootAlias . ".count <= ?", $value['to']);
    }

}
