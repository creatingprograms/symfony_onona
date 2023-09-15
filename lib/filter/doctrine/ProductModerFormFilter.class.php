<?php

/**
 * Product filter form.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductModerFormFilter extends BaseProductFormFilter {

    public function configure() {
        $this->setWidget('category_products_list', new sfWidgetFormInputHidden());
        $this->setWidget('dop_info_products_list', new sfWidgetFormInputHidden());
        $this->setWidget('count_range', new sfWidgetFormInputHidden());
        $this->setWidget('name', new sfWidgetFormInputHidden());
        $this->setWidget('code', new sfWidgetFormInputHidden());
        $this->setWidget('slug', new sfWidgetFormInputHidden());
        $this->setWidget('is_public', new sfWidgetFormInputHidden());
        $this->setWidget('is_related', new sfWidgetFormInputHidden());
        $this->setWidget('sync', new sfWidgetFormInputHidden());
        unset($this['count_range']);

        $q = Doctrine_Manager::getInstance()->getCurrentConnection();

        $timerProducts = sfTimerManager::getTimer('Form: Загрузка всех менеджеров');
        $choiseManager = $q->execute("SELECT sf_guard_user.id, sf_guard_user.email_address as name FROM `product` left join sf_guard_user on sf_guard_user.id=product.user where user>0 group by user")
                ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
        $this->setWidget('user', new sfWidgetFormDoctrineChoiceSearchArray(array('choises' => $choiseManager, 'add_empty' => true)));
        $timerProducts->addTime();

        
        
        //$this->setWidget('user', new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'query' => Doctrine_Query::create()->select('sfGuardUser.*')->from('sfGuardUser')->leftJoin("Product ON sfGuardUser.id = Product.user")->where(' Product.user>0')->groupBy("Product.user"), 'add_empty' => true)));
        $this->setWidget('created_at', new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)));


        /* $this->setWidget('category_products_list', new sfWidgetFormDoctrineChoiceNestedSet(array('multiple' => true, 'model' => 'Category', 'method' => 'getNameSubCategory', 'order_by' => array('position', 'ASC')), array('style' => 'height:200px')));

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
          ))); */
    }

    public function addCountRangeColumnQuery($query, $field, $value) {
        $rootAlias = $query->getRootAlias();
        if (isset($value['from']) && $value['from'])
            $query->andWhere($rootAlias . ".count >= ?", $value['from']);
        if (isset($value['to']) && $value['to'])
            $query->andWhere($rootAlias . ".count <= ?", $value['to']);
    }

}
