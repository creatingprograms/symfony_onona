<?php

/**
 * RussianPostCity form base class.
 *
 * @method RussianPostCity getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRussianPostCityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormInputText(),
      'slug'                => new sfWidgetFormInputText(),
      'sitemapRate'         => new sfWidgetFormInputText(),
      'content'             => new sfWidgetFormTextarea(),
      'content_mobile'      => new sfWidgetFormTextarea(),
      'content_mo'          => new sfWidgetFormTextarea(),
      'content_mo_mobile'   => new sfWidgetFormTextarea(),
      'is_public'           => new sfWidgetFormInputCheckbox(),
      'is_show_right_block' => new sfWidgetFormInputCheckbox(),
      'title'               => new sfWidgetFormInputText(),
      'keywords'            => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormInputText(),
      'tags'                => new sfWidgetFormInputText(),
      'city'                => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'position'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 255)),
      'slug'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sitemapRate'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'content'             => new sfValidatorString(array('required' => false)),
      'content_mobile'      => new sfValidatorString(array('required' => false)),
      'content_mo'          => new sfValidatorString(array('required' => false)),
      'content_mo_mobile'   => new sfValidatorString(array('required' => false)),
      'is_public'           => new sfValidatorBoolean(array('required' => false)),
      'is_show_right_block' => new sfValidatorBoolean(array('required' => false)),
      'title'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'keywords'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tags'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
      'position'            => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'RussianPostCity', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'RussianPostCity', 'column' => array('slug'))),
        new sfValidatorDoctrineUnique(array('model' => 'RussianPostCity', 'column' => array('position'))),
      ))
    );

    $this->widgetSchema->setNameFormat('russian_post_city[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RussianPostCity';
  }

}
