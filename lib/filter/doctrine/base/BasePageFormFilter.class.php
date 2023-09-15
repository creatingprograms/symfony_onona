<?php

/**
 * Page filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'                => new sfWidgetFormFilterInput(),
      'sitemapRate'         => new sfWidgetFormFilterInput(),
      'content'             => new sfWidgetFormFilterInput(),
      'content_mobile'      => new sfWidgetFormFilterInput(),
      'content_mo'          => new sfWidgetFormFilterInput(),
      'content_mo_mobile'   => new sfWidgetFormFilterInput(),
      'content_new_version' => new sfWidgetFormFilterInput(),
      'is_public'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_show_right_block' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'title'               => new sfWidgetFormFilterInput(),
      'keywords'            => new sfWidgetFormFilterInput(),
      'description'         => new sfWidgetFormFilterInput(),
      'tags'                => new sfWidgetFormFilterInput(),
      'class'               => new sfWidgetFormFilterInput(),
      'city_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('City'), 'add_empty' => true)),
      'views_count'         => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'            => new sfWidgetFormFilterInput(),
      'categorypage_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Categorypage')),
      'news_list'           => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'News')),
    ));

    $this->setValidators(array(
      'name'                => new sfValidatorPass(array('required' => false)),
      'slug'                => new sfValidatorPass(array('required' => false)),
      'sitemapRate'         => new sfValidatorPass(array('required' => false)),
      'content'             => new sfValidatorPass(array('required' => false)),
      'content_mobile'      => new sfValidatorPass(array('required' => false)),
      'content_mo'          => new sfValidatorPass(array('required' => false)),
      'content_mo_mobile'   => new sfValidatorPass(array('required' => false)),
      'content_new_version' => new sfValidatorPass(array('required' => false)),
      'is_public'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_show_right_block' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'title'               => new sfValidatorPass(array('required' => false)),
      'keywords'            => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'tags'                => new sfValidatorPass(array('required' => false)),
      'class'               => new sfValidatorPass(array('required' => false)),
      'city_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('City'), 'column' => 'id')),
      'views_count'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'categorypage_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Categorypage', 'required' => false)),
      'news_list'           => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'News', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('page_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addCategorypageListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.CategorypagePage CategorypagePage')
      ->andWhereIn('CategorypagePage.categorypage_id', $values)
    ;
  }

  public function addNewsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.NewsPage NewsPage')
      ->andWhereIn('NewsPage.news_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Page';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'name'                => 'Text',
      'slug'                => 'Text',
      'sitemapRate'         => 'Text',
      'content'             => 'Text',
      'content_mobile'      => 'Text',
      'content_mo'          => 'Text',
      'content_mo_mobile'   => 'Text',
      'content_new_version' => 'Text',
      'is_public'           => 'Boolean',
      'is_show_right_block' => 'Boolean',
      'title'               => 'Text',
      'keywords'            => 'Text',
      'description'         => 'Text',
      'tags'                => 'Text',
      'class'               => 'Text',
      'city_id'             => 'ForeignKey',
      'views_count'         => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'position'            => 'Number',
      'categorypage_list'   => 'ManyKey',
      'news_list'           => 'ManyKey',
    );
  }
}
