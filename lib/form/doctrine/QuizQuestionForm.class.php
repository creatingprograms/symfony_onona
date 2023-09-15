<?php

/**
 * QuizQuestion form.
 *
 * @package    symfony
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class QuizQuestionForm extends BaseQuizQuestionForm
{
  private function debug($name, $data){
    header('Content-type: text/html; charset=utf-8');
    die($name.'<pre>'.print_r($data, true).'</pre>');
  }
  protected $answerToDelete = array();

  public function configure() {
    // $this->debug('QuizQuestionForm->configure', $_POST);

      if ($this->object->exists()) {
          $this->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
          $this->validatorSchema['delete'] = new sfValidatorPass();
      }
      /*
      $this->validatorSchema['question']->setOption('required', false);
      $this->validatorSchema['number']->setOption('required', false);
      $this->validatorSchema->setOption('allow_extra_fields', true);
      $this->validatorSchema->setOption('filter_extra_fields', false);
      $this->embedRelation('QuizAnswer', null, array('orderBy' => array('position', 'ASC')));

      $this->widgetSchema['QuizAnswer']->setLabel('Ответы');
      $this->widgetSchema['question']->setLabel('Вопрос');
      $this->widgetSchema['number']->setLabel('Номер вопроса');


      $relationName = "QuizAnswer";
      $relationSettings = array(
          'considerNewFormEmptyFields' => array('answer', 'balls'),
          'noNewForm' => false,
          'newFormLabel' => 'Новый ответ',
          'newFormClass' => 'QuizQuestionAnswerForm',
          'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
          'displayEmptyRelations' => false,
          'formClass' => 'QuizQuestionAnswerForm',
          'formClassArgs' => array(array('ah_add_delete_checkbox' => true)),
          'newFormAfterExistingRelations' => true,
          'formFormatter' => null,
          'multipleNewForms' => true,
          'newFormsInitialCount' => 1,
          'newFormsContainerForm' => null, // pass BaseForm object here or we will create ahNewRelationsContainerForm
          'newRelationButtonLabel' => '+',
          'newRelationAddByCloning' => true,
          'newRelationUseJSFramework' => 'jQuery',
          'customEmbeddedFormLabelMethod' => 'getLabelTitle'
      );
      $subForm = new ahNewRelationsContainerForm(null, array(
          'containerName' => "new_Answer",
          'addJavascript' => true,
          'useJSFramework' => "jQuery",
          'newRelationButtonLabel' => "+",
          'addByCloning' => true
      ));

      unset($subForm[$subForm->getCSRFFieldName()]);

      $relation = $this->getObject()->getTable()->getRelation($relationName);
      $newForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, 1);
      */
      if ($this->isNew or $this->getObject()->getImg() == "") {
          $this->setWidget('img', new sfWidgetFormInputFile());
      } else {
          $this->widgetSchema['img'] = new sfWidgetFormInputFileEditable(array(
                      'label' => 'Изображение',
                      'file_src' => '/uploads/photo/' . $this->getObject()->getImg(),
                      'is_image' => true,
                      'edit_mode' => !$this->isNew(),
                      'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                          ), array("style" => "float:left;"));
      }

      $this->validatorSchema['img'] = new sfValidatorFile(array(
                  'required' => false,
                  'path' => sfConfig::get('sf_upload_dir') . '/photo/',
                  'mime_types' => 'web_images',
                  'validated_file_class' => 'sfPhotoCatalogValidatedFile',
              ));
      $this->validatorSchema['img_delete'] = new sfValidatorPass();
      /*
      if (isset($_POST['quiz']['new_Question'][$this->getOption('numForm') - 1])) {
          for ($i = 0; $i < count($_POST['quiz']['new_Question'][$this->getOption('numForm') - 1]['new_Answer']); $i++) {

              $subForm->embedForm($i, $newForm);
          }
      } else {
        // $this->debug('QuizQuestionForm->configure', $_POST);
          $subForm->embedForm(0, $newForm);
      }
      $subForm->getWidgetSchema()->setLabel('Новый ответ');

      $subForm->validatorSchema->setOption('allow_extra_fields', true);
      $subForm->validatorSchema->setOption('filter_extra_fields', false);
      $this->embedForm("new_Answer", $subForm);

      unset($this['quiz_id']);*/

      $this->mergePostValidator(new QuizQuestionAnswerValidatorSchema());
  }

  private function embeddedFormFactory($relationName, array $relationSettings, Doctrine_Relation $relation, $formLabel = null) {
    // $this->debug('QuizQuestionForm->embeddedFormFactory', $_POST);
      $newFormObject = $this->embeddedFormObjectFactory($relationName, $relation);
      $formClass = (null === $relationSettings['newFormClass']) ? $relation->getClass() . 'Form' : $relationSettings['newFormClass'];
      $formArgs = (null === $relationSettings['newFormClassArgs']) ? array() : $relationSettings['newFormClassArgs'];
      $r = new ReflectionClass($formClass);

      /* @var $newForm sfFormObject */
      $newForm = $r->newInstanceArgs(array_merge(array($newFormObject), $formArgs));
      $newFormIdentifiers = $newForm->getObject()->getTable()->getIdentifierColumnNames();
      // $this->debug('QuizQuestionForm->embeddedFormFactory', $newFormIdentifiers);
      foreach ($newFormIdentifiers as $primaryKey) {
          //echo $newForm;
          unset($newForm[$primaryKey]);
      }
      unset($newForm[$relation->getForeignColumnName()]);

      // FIXME/TODO: check if this even works for one-to-one
      // CORRECTION 1: Not really, it creates another record but doesn't link it to this object!
      // CORRECTION 2: No, it can't, silly! For that to work the id of the not-yet-existant related record would have to be known...
      // Think about overriding the save method and after calling parent::save($con) we should update the relations that:
      //   1. are one-to-one AND
      //   2. are LocalKey :)
      if (null !== $formLabel) {
          $newForm->getWidgetSchema()->setLabel($formLabel);
      }

      return $newForm;
  }

  private function embeddedFormObjectFactory($relationName, Doctrine_Relation $relation) {
      if (!$relation->isOneToOne()) {
          $newFormObjectClass = $relation->getClass();
          $newFormObject = new $newFormObjectClass();
          $newFormObject[$this->getRelationAliasByObject($newFormObject)] = $this->getObject();

          // $this->debug('QuizQuestionForm->embeddedFormObjectFactory $newFormObjectClass', $this->getRelationAliasByObject($newFormObject));
      } else {
        // $this->debug('QuizQuestionForm->embeddedFormObjectFactory else', $_POST);
          $newFormObject = $this->getObject()->$relationName;
      }

      return $newFormObject;
  }

  private function getRelationAliasByObject($object) {
    // $this->debug('QuizQuestionForm->getRelationAliasByObject', $_POST);
      foreach ($object->getTable()->getRelations() as $alias => $relation) {
          $class = $relation->getClass();
          if ($this->getObject() instanceof $class) {
              return $alias;
          }
      }
  }

  protected function doUpdateObject($values) {
    // $this->debug('QuizQuestionForm->doUpdateObject', $_POST);
      // step 4.4
      if (count($this->answerToDelete)) {
          foreach ($this->answerToDelete as $index => $id) {
              unset($values['Answer'][$index]);
              unset($this->object['Answer'][$index]);
              QuizQuestionAnswerTable::getInstance()->findOneById($id)->delete();
          }
      }

      parent::doUpdateObject($values);
  }

  protected function doBind(array $values) {
    // $this->debug('QuizQuestionForm->doBind', $_POST);
      // step 4.3
      if (isset($values['Answer'])) {
          foreach ($values['Answer'] as $key => $answer) {
              if (isset($answer['answerdelete']) && $answer['id']) {
                  $this->answerToDelete[$key] = $answer['id'];
              }
          }
      }
      parent::doBind($values);
  }

  public function saveEmbeddedForms($con = null, $forms = null, $answerToDelete = array()) {
    // $this->debug('QuizQuestionForm->saveEmbeddedForms', $_POST);
      if (null === $con) {
          $con = $this->getConnection();
      }
      // step 3.2
      if (null === $forms) {
          $answer = $this->getValue('new_Answer');
          $forms = $this->embeddedForms;
          if ('' === trim($answer['answer'])) {
              unset($forms['new_Answer']);
          }
      }
      $i = 0;
      $answerDeleted = array();
      foreach ($answerToDelete as $answerOne) {
          $answerDeleted[] = $answerOne['id'];
      }
      foreach ($forms as $form) {

          if ($form instanceof sfFormObject) {


              if (!in_array($form->getObject()->getId(), $answerDeleted)) {
                  $form->saveEmbeddedForms($con);
                  if ($form->getObject()->getAnswer() != "") {
                      $form->getObject()->save($con);
                  }
              }
          } else {
              $this->saveEmbeddedForms($con, $form->getEmbeddedForms(), $answerToDelete);
          }
      }
  }
}
/*
  configure
  embeddedFormFactory
  embeddedFormObjectFactory
  getRelationAliasByObject
*/
