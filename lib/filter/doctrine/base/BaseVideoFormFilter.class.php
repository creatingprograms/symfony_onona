<?php

/**
 * Video filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseVideoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'              => new sfWidgetFormFilterInput(),
      'link'              => new sfWidgetFormFilterInput(),
      'videoyoutube'      => new sfWidgetFormFilterInput(),
      'videoserver'       => new sfWidgetFormFilterInput(),
      'photo'             => new sfWidgetFormFilterInput(),
      'timing'            => new sfWidgetFormFilterInput(),
      'subname'           => new sfWidgetFormFilterInput(),
      'content'           => new sfWidgetFormFilterInput(),
      'is_public'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_publicmainpage' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_related'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'tag'               => new sfWidgetFormFilterInput(),
      'title'             => new sfWidgetFormFilterInput(),
      'keywords'          => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'youtubelink'       => new sfWidgetFormFilterInput(),
      'point'             => new sfWidgetFormFilterInput(),
      'comment_manager'   => new sfWidgetFormFilterInput(),
      'manager_id'        => new sfWidgetFormFilterInput(),
      'username'          => new sfWidgetFormFilterInput(),
      'product_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'position'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'              => new sfValidatorPass(array('required' => false)),
      'slug'              => new sfValidatorPass(array('required' => false)),
      'link'              => new sfValidatorPass(array('required' => false)),
      'videoyoutube'      => new sfValidatorPass(array('required' => false)),
      'videoserver'       => new sfValidatorPass(array('required' => false)),
      'photo'             => new sfValidatorPass(array('required' => false)),
      'timing'            => new sfValidatorPass(array('required' => false)),
      'subname'           => new sfValidatorPass(array('required' => false)),
      'content'           => new sfValidatorPass(array('required' => false)),
      'is_public'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_publicmainpage' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_related'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'tag'               => new sfValidatorPass(array('required' => false)),
      'title'             => new sfValidatorPass(array('required' => false)),
      'keywords'          => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'youtubelink'       => new sfValidatorPass(array('required' => false)),
      'point'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment_manager'   => new sfValidatorPass(array('required' => false)),
      'manager_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'username'          => new sfValidatorPass(array('required' => false)),
      'product_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Product'), 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'position'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('video_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Video';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'name'              => 'Text',
      'slug'              => 'Text',
      'link'              => 'Text',
      'videoyoutube'      => 'Text',
      'videoserver'       => 'Text',
      'photo'             => 'Text',
      'timing'            => 'Text',
      'subname'           => 'Text',
      'content'           => 'Text',
      'is_public'         => 'Boolean',
      'is_publicmainpage' => 'Boolean',
      'is_related'        => 'Boolean',
      'tag'               => 'Text',
      'title'             => 'Text',
      'keywords'          => 'Text',
      'description'       => 'Text',
      'youtubelink'       => 'Text',
      'point'             => 'Number',
      'comment_manager'   => 'Text',
      'manager_id'        => 'Number',
      'username'          => 'Text',
      'product_id'        => 'ForeignKey',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'position'          => 'Number',
    );
  }
}
