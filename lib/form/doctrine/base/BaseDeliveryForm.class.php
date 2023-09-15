<?php

/**
 * Delivery form base class.
 *
 * @method Delivery getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseDeliveryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'name'                    => new sfWidgetFormInputText(),
      'content'                 => new sfWidgetFormTextarea(),
      'is_public'               => new sfWidgetFormInputCheckbox(),
      'is_pvz'                  => new sfWidgetFormInputCheckbox(),
      'picture'                 => new sfWidgetFormInputText(),
      'picturehover'            => new sfWidgetFormInputText(),
      'description'             => new sfWidgetFormInputText(),
      'free_from'               => new sfWidgetFormInputText(),
      'free_from_online'        => new sfWidgetFormInputText(),
      'free_from_online_moscow' => new sfWidgetFormInputText(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
      'position'                => new sfWidgetFormInputText(),
      'delivery_payments_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Payment')),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                    => new sfValidatorString(array('max_length' => 255)),
      'content'                 => new sfValidatorString(array('required' => false)),
      'is_public'               => new sfValidatorBoolean(array('required' => false)),
      'is_pvz'                  => new sfValidatorBoolean(array('required' => false)),
      'picture'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'picturehover'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'free_from'               => new sfValidatorInteger(array('required' => false)),
      'free_from_online'        => new sfValidatorInteger(array('required' => false)),
      'free_from_online_moscow' => new sfValidatorInteger(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
      'position'                => new sfValidatorInteger(array('required' => false)),
      'delivery_payments_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Payment', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Delivery', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Delivery', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('delivery[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Delivery';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['delivery_payments_list']))
    {
      $this->setDefault('delivery_payments_list', $this->object->DeliveryPayments->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveDeliveryPaymentsList($con);

    parent::doSave($con);
  }

  public function saveDeliveryPaymentsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['delivery_payments_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->DeliveryPayments->getPrimaryKeys();
    $values = $this->getValue('delivery_payments_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('DeliveryPayments', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('DeliveryPayments', array_values($link));
    }
  }

}
