<?php

/**
 * Payment form base class.
 *
 * @method Payment getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePaymentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'name'           => new sfWidgetFormInputText(),
      'content'        => new sfWidgetFormTextarea(),
      'is_public'      => new sfWidgetFormInputCheckbox(),
      'picture'        => new sfWidgetFormInputText(),
      'picturehover'   => new sfWidgetFormInputText(),
      'description'    => new sfWidgetFormInputText(),
      'is_online'      => new sfWidgetFormInputCheckbox(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'position'       => new sfWidgetFormInputText(),
      'deliverys_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Delivery')),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 255)),
      'content'        => new sfValidatorString(array('required' => false)),
      'is_public'      => new sfValidatorBoolean(array('required' => false)),
      'picture'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'picturehover'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_online'      => new sfValidatorBoolean(array('required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
      'position'       => new sfValidatorInteger(array('required' => false)),
      'deliverys_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Delivery', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Payment', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'Payment', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['deliverys_list']))
    {
      $this->setDefault('deliverys_list', $this->object->Deliverys->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveDeliverysList($con);

    parent::doSave($con);
  }

  public function saveDeliverysList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['deliverys_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Deliverys->getPrimaryKeys();
    $values = $this->getValue('deliverys_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Deliverys', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Deliverys', array_values($link));
    }
  }

}
