<?php

/**
 * CategoryArticle form base class.
 *
 * @method CategoryArticle getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCategoryArticleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'articlecategory_id' => new sfWidgetFormInputHidden(),
      'article_id'         => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'articlecategory_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('articlecategory_id')), 'empty_value' => $this->getObject()->get('articlecategory_id'), 'required' => false)),
      'article_id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('article_id')), 'empty_value' => $this->getObject()->get('article_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('category_article[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CategoryArticle';
  }

}
