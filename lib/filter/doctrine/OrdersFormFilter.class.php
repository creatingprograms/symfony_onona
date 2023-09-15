<?php

/**
 * Orders filter form.
 *
 * @package    Magazin
 * @subpackage filter
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class OrdersFormFilter extends BaseOrdersFormFilter {

    public function configure() {   //$this->setWidget('customer_id', new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'method' => 'getSelectView')));
        $this->setWidget('customer_id', new sfWidgetFormInputText());

        $this->setValidator('customer_id', new sfValidatorString(array('required' => false)));
        $this->setWidget('status', new sfWidgetFormInputText());

        $this->setValidator('status', new sfValidatorString(array('required' => false)));

        $this->setWidget('isbonuspay', new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))));
        $this->setValidator('isbonuspay', new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))));

        $this->setWidget('perrange', new sfWidgetFormFilterInputRange(array(
            'from_value' => new sfWidgetFormInput(array(), array('style' => 'width:100px')),
            'to_value' => new sfWidgetFormInput(array(), array('style' => 'width:100px')),
            'with_empty' => false
        )));

        $this->setValidator('perrange', new sfValidatorInputRange(array(
            'required' => false,
            'from_value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
            'to_value' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false)))
        )));
    }

    public function addIsbonuspayColumnQuery($query, $field, $value) {
        $rootAlias = $query->getRootAlias();
        if (isset($value) && $value != "")
            if ($value == 1)
                $query->andWhere($rootAlias . ".bonuspay >= 1");
            else
                $query->andWhere($rootAlias . ".bonuspay = 0");
        //echo $value;
        //exit;
        /* $rootAlias = $query->getRootAlias();
          if (isset($value['from']) && $value['from'])
          $query->andWhere($rootAlias . ".count >= ?", $value['from']);
          if (isset($value['to']) && $value['to'])
          $query->andWhere($rootAlias . ".count <= ?", $value['to']); */
    }

    public function addPerrangeColumnQuery($query, $field, $value) {
        $rootAlias = $query->getRootAlias();
        /* print_r($value);
          exit; */
        if (is_array($value) && $value['from'] != "") {
            $query->andWhere("(" . $rootAlias . ".bonuspay / ( " . $rootAlias . ".firsttotalcost + " . $rootAlias . ".bonuspay )) *100 >= ?", $value['from']);
        }
        if (is_array($value) && $value['to'] != "") {
            $query->andWhere("(" . $rootAlias . ".bonuspay / ( " . $rootAlias . ".firsttotalcost + " . $rootAlias . ".bonuspay )) *100 <= ?", $value['to']);
        }
        /*  if (isset($value) && $value != "")
          if ($value == 1)
          $query->andWhere($rootAlias . ".bonuspay >= 1");
          else
          $query->andWhere($rootAlias . ".bonuspay = 0"); */
        //echo $value;
        //exit;
        /* $rootAlias = $query->getRootAlias();
          if (isset($value['from']) && $value['from'])
          $query->andWhere($rootAlias . ".count >= ?", $value['from']);
          if (isset($value['to']) && $value['to'])
          $query->andWhere($rootAlias . ".count <= ?", $value['to']); */
    }

    public function getFields() {
        $fields = parent::getFields();
        $fields['isbonuspay'] = 'custom';
        $fields['perrange'] = 'custom';
        return $fields;
    }

}
