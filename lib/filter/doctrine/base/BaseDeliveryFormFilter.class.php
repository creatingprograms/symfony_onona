<?php

/**
 * Delivery filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDeliveryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'content'                 => new sfWidgetFormFilterInput(),
      'is_public'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_pvz'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'picture'                 => new sfWidgetFormFilterInput(),
      'picturehover'            => new sfWidgetFormFilterInput(),
      'description'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'free_from'               => new sfWidgetFormFilterInput(),
      'free_from_online'        => new sfWidgetFormFilterInput(),
      'free_from_online_moscow' => new sfWidgetFormFilterInput(),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'                => new sfWidgetFormFilterInput(),
      'delivery_payments_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Payment')),
    ));

    $this->setValidators(array(
      'name'                    => new sfValidatorPass(array('required' => false)),
      'content'                 => new sfValidatorPass(array('required' => false)),
      'is_public'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_pvz'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'picture'                 => new sfValidatorPass(array('required' => false)),
      'picturehover'            => new sfValidatorPass(array('required' => false)),
      'description'             => new sfValidatorPass(array('required' => false)),
      'free_from'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'free_from_online'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'free_from_online_moscow' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'delivery_payments_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Payment', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('delivery_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addDeliveryPaymentsListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.DeliveryPayment DeliveryPayment')
      ->andWhereIn('DeliveryPayment.payment_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Delivery';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'name'                    => 'Text',
      'content'                 => 'Text',
      'is_public'               => 'Boolean',
      'is_pvz'                  => 'Boolean',
      'picture'                 => 'Text',
      'picturehover'            => 'Text',
      'description'             => 'Text',
      'free_from'               => 'Number',
      'free_from_online'        => 'Number',
      'free_from_online_moscow' => 'Number',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
      'position'                => 'Number',
      'delivery_payments_list'  => 'ManyKey',
    );
  }
}
