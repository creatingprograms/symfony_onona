<?php

/**
 * Quiz form.
 *
 * @package    symfony
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class QuizForm extends BaseQuizForm
{
  protected $numbersToDelete = array();
  protected $answerToDelete = array();
  protected $numbersToDeleteResult = array();

  public function configure() {
    $this->embedRelation('QuizQuestion');

    $this->widgetSchema['QuizQuestion']->setLabel('Вопросы');
    $relationName = "QuizQuestion";
    $relationSettings = array(
        'considerNewFormEmptyFields' => array('question', 'number'),
        'noNewForm' => false,
        'newFormLabel' => 'Новый вопрос',
        'newFormClass' => 'QuizQuestionForm',
        'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
        'displayEmptyRelations' => false,
        'formClass' => 'QuizQuestionForm',
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
                'containerName' => "new_Question",
                'addJavascript' => true,
                'useJSFramework' => "jQuery",
                'newRelationButtonLabel' => "+",
                'addByCloning' => true
            ));

    unset($subForm[$subForm->getCSRFFieldName()]);

    $relation = $this->getObject()->getTable()->getRelation($relationName);
    $newForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, 1);
    $subForm->embedForm(0, $newForm);

    $subForm->getWidgetSchema()->setLabel('Новый вопрос');
    $this->embedForm("new_Question", $subForm);



    $this->embedRelation('QuizResult');
    $this->widgetSchema['QuizResult']->setLabel('Результаты');
    $relationName = "QuizResult";
    $relationSettings = array(
        'considerNewFormEmptyFields' => array('balls_to', 'balls_from', 'results'),
        'noNewForm' => false,
        'newFormLabel' => 'Новый результат',
        'newFormClass' => 'QuizResultForm',
        'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
        'displayEmptyRelations' => false,
        'formClass' => 'QuizResultForm',
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
                'containerName' => "new_Result",
                'addJavascript' => true,
                'useJSFramework' => "jQuery",
                'newRelationButtonLabel' => "+",
                'addByCloning' => true
            ));

    unset($subForm[$subForm->getCSRFFieldName()]);


    $relation = $this->getObject()->getTable()->getRelation($relationName);
    $newForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, 1);
    $subForm->embedForm(0, $newForm);

    $subForm->getWidgetSchema()->setLabel('Новый результат');
    $this->embedForm("new_Result", $subForm);

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

    if ($this->isNew) {

        $this->setWidget('QuizQuestion', new sfWidgetFormInputHidden(array(), array('value' => false)));
        $this->setValidator("QuizQuestion", new sfValidatorBoolean(array('required' => false)));
        $this->setWidget('QuizQuestion', new sfWidgetFormInputHidden(array(), array('value' => false)));
        $this->setValidator("QuizQuestion", new sfValidatorBoolean(array('required' => false)));
    }


    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', false);
  }

  private function embeddedFormFactory($relationName, array $relationSettings, Doctrine_Relation $relation, $formLabel = null) {
      $newFormObject = $this->embeddedFormObjectFactory($relationName, $relation);
      $formClass = (null === $relationSettings['newFormClass']) ? $relation->getClass() . 'Form' : $relationSettings['newFormClass'];
      $formArgs = (null === $relationSettings['newFormClassArgs']) ? array() : $relationSettings['newFormClassArgs'];
      $formArgs[0]['numForm']=$formLabel;
      $r = new ReflectionClass($formClass);
      //echo $relationName;
      /* @var $newForm sfFormObject */
      //print_r($formArgs);
      $newForm = $r->newInstanceArgs(array_merge(array($newFormObject), $formArgs));
      $newFormIdentifiers = $newForm->getObject()->getTable()->getIdentifierColumnNames();

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
      } else {
          $newFormObject = $this->getObject()->$relationName;
      }

      return $newFormObject;
  }

  private function getRelationAliasByObject($object) {
      foreach ($object->getTable()->getRelations() as $alias => $relation) {
          $class = $relation->getClass();
          if ($this->getObject() instanceof $class) {
              return $alias;
          }
      }
  }

  protected function doBind(array $values) {
      // step 3.1
      //print_r(get_class($this->embeddedForms['Question'][0]['new_Answer']));exit;
      //$this->embeddedForms['new_Question'][0]['new_Answer'][1]=$this->embeddedForms['new_Question'][0]['new_Answer'][0];
      //print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));exit;

      foreach ($values['new_Question'] as $keyNewQuest => $newQuest) {
          if ('' === trim($newQuest['question'])) {
              $this->validatorSchema['new_Question'][$keyNewQuest] = new sfValidatorString();
          } else {
              if (!isset($this->embeddedForms['new_Question'][$keyNewQuest])) {
                  $relationSettings = array(
                      'considerNewFormEmptyFields' => array('question', 'number'),
                      'noNewForm' => false,
                      'newFormLabel' => 'Новый вопрос',
                      'newFormClass' => 'QuizQuestionForm',
                      'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
                      'displayEmptyRelations' => false,
                      'formClass' => 'QuizQuestionForm',
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
                  $relationName = "QuizQuestion";
                  // create and embed new form
                  $relation = $this->getObject()->getTable()->getRelation($relationName);
                  $addedForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, ((int) $keyNewQuest) + 1);
                  $ef = $this->embeddedForms['new_Question'];
                  $ef->embedForm($keyNewQuest, $addedForm);
                  // ... and reset other stuff (symfony loses all this since container form is already embedded)
                  $this->validatorSchema['new_Question'] = $ef->getValidatorSchema();
                  $this->widgetSchema['new_Question'] = new sfWidgetFormSchemaDecorator($ef->getWidgetSchema(), $ef->getWidgetSchema()->getFormFormatter()->getDecoratorFormat());
                  $this->setDefault('new_Question', $ef->getDefaults());
              }
          }
      }
      if (isset($values['Question'])) {
          foreach ($values['Question'] as $key => $question) {
              if (isset($question['delete']) && $question['id']) {
                  $this->numbersToDelete[$key] = $question['id'];
              }
              foreach ($values['Question'][$key]['new_Answer'] as $newAnswer) {
                  if (@$newAnswer['answer'] != "") {
                      $newAnswerObject = new QuizQuestionAnswer();
                      $newAnswerObject->setQuizquestionId($values['Question'][$key]['id']);
                      $newAnswerObject->setAnswer($newAnswer['answer']);
                      $newAnswerObject->setBalls($newAnswer['balls']);
                      $newAnswerObject->save();
                  }
              }

              if (isset($values['Question'][$key]['Answer'])) {
                  foreach ($values['Question'][$key]['Answer'] as $key2 => $answer) {
                      if (isset($answer['delete']) && $answer['id']) {
                          $answerDeleteArray['id'] = $answer['id'];
                          $answerDeleteArray['question_index'] = $key;
                          $this->answerToDelete[$key2] = $answerDeleteArray;
                      }
                  }
              }
          }
      }

      foreach ($values['new_Result'] as $keyNewRes => $newRes) {
          if ('' === trim($newRes['results'])) {
              $this->validatorSchema['new_Result'][$keyNewRes] = new sfValidatorString();
          } else {
              if (!isset($this->embeddedForms['new_Result'][$keyNewRes])) {
                  $relationSettings = array(
                      'considerNewFormEmptyFields' => array('balls_to', 'balls_from', 'results'),
                      'noNewForm' => false,
                      'newFormLabel' => 'Новый результат',
                      'newFormClass' => 'QuizResultForm',
                      'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
                      'displayEmptyRelations' => false,
                      'formClass' => 'QuizResultForm',
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
                  $relationName = "QuizResult";
                  // create and embed new form
                  $relation = $this->getObject()->getTable()->getRelation($relationName);
                  $addedForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, ((int) $keyNewRes) + 1);
                  $ef = $this->embeddedForms['new_Result'];
                  $ef->embedForm($keyNewRes, $addedForm);
                  // ... and reset other stuff (symfony loses all this since container form is already embedded)
                  $this->validatorSchema['new_Result'] = $ef->getValidatorSchema();
                  $this->widgetSchema['new_Result'] = new sfWidgetFormSchemaDecorator($ef->getWidgetSchema(), $ef->getWidgetSchema()->getFormFormatter()->getDecoratorFormat());
                  $this->setDefault('new_Result', $ef->getDefaults());
              }
          }
      }
      if (isset($values['Result'])) {
          foreach ($values['Result'] as $key => $result) {
              if (isset($result['delete']) && $result['id']) {
                  $this->numbersToDeleteResult[$key] = $result['id'];
              }
          }
      }

      /*print_r($this->numbersToDeleteResult);
        exit; */
      /* print_r($this->validatorSchema);
        exit; */
      /* print_r($values);
        exit; */
      parent::doBind($values);
  }

  protected function doUpdateObject($values) {
      // step 4.4
      // print_r($this->getValue('category_products_list'));exit;
      if (count($this->numbersToDelete)) {
          foreach ($this->numbersToDelete as $index => $id) {
              unset($values['Question'][$index]);
              unset($this->object['Question'][$index]);
              QuizQuestionTable::getInstance()->findOneById($id)->delete();
          }
      }

      if (count($this->answerToDelete)) {
          foreach ($this->answerToDelete as $index => $id) {
              QuizQuestionAnswerTable::getInstance()->createQuery()->delete()->where("id=?", $id['id'])->execute();
          }
      }




      if (count($this->numbersToDeleteResult)) {
          foreach ($this->numbersToDeleteResult as $index => $id) {
              unset($values['Result'][$index]);
              unset($this->object['Result'][$index]);
              QuizResultTable::getInstance()->findOneById($id)->delete();
          }
      }
      /* print_r($values);
        exit; */
      parent::doUpdateObject($values);
  }

  public function saveEmbeddedForms($con = null, $forms = null) {

      if (null === $con) {
          $con = $this->getConnection();
      }
      //$isquestion = $this->getValue('Question');
      // step 3.2
      if (null === $forms) {
          foreach ($this->getValue('new_Question') as $keyNewQuest => $newQuest) {

              $question = $newQuest;
              $forms = $this->embeddedForms;

              if ('' === trim($question['question'])) {
                  unset($forms['new_Question'][$keyNewQuest]);
              }
          }
          foreach ($this->getValue('new_Result') as $keyNewRes => $newRes) {

              $result = $newRes;
              $forms = $this->embeddedForms;

              if ('' === trim($result['results'])) {
                  unset($forms['new_Result'][$keyNewRes]);
              }
          }
      } else {

          foreach ($this->getValue('new_Question') as $keyNewQuest => $newQuest) {
              $question = $newQuest;

              if ('' === trim($question['question'])) {
                  unset($forms['new_Question'][$keyNewQuest]);
              }
          }
          foreach ($this->getValue('new_Result') as $keyNewRes => $newRes) {
              $result = $newRes;

              if ('' === trim($result['results'])) {
                  unset($forms['new_Result'][$keyNewRes]);
              }
          }
      }
      /* print_r($this->getValues());
        exit; */

      foreach ($forms as $formKey => $form) {

          if ($form instanceof sfFormObject) {
              if (!in_array($form->getObject()->getId(), $this->numbersToDelete)) {

                  if ($form->getObject()->isNew()) {
                      $formQuestion = $form->getObject();
                      if (get_class($formQuestion) != "QuizResult")
                          if ($formQuestion->getQuestion() != "") {
                              //print_r($formQuestion->Answer[1]->getId());exit;
                              $formQuestion->setQuizId($this->object);
                              $formQuestion->save($con);


                              $valuesAll = $this->getValues();
                              foreach ($valuesAll['new_Question'][$formKey]['new_Answer'] as $newAns) {
                                  //print_r($newAns);
                                  $newAnsObj = new QuizQuestionAnswer();
                                  $newAnsObj->setAnswer($newAns['answer']);
                                  $newAnsObj->setBalls($newAns['balls']);
                                  $newAnsObj->setQuizquestionId($formQuestion);
                                  $newAnsObj->save();
                                  //echo $newAnsObj->getAnswer();
                              }
                              //$form->getObject()->getQuestion() == 24351) {
                              //print_r(array_keys((array)$this->embeddedForms));
                              //echo $formKey;
                              //exit;
                          }
                      if (get_class($formQuestion) == "QuizResult")
                          if ($formQuestion->getResults() != "" and !in_array($form->getObject()->getId(), $this->numbersToDeleteResult)) {
                              $formQuestion->setQuizId($this->object);
                              $formQuestion->save($con);
                          }
                  } else {
                      $form->saveEmbeddedForms($con, null, $this->answerToDelete);

                      $formQuestion = $form->getObject();
                      if (get_class($formQuestion) != "QuizResult")
                          if ($formQuestion->getQuestion() != "") {
                              //print_r($formQuestion->Answer[1]->getId());exit;
                              $formQuestion->setQuizId($this->object);
                              $formQuestion->save($con);

                              //print_r($form->getObject());exit;
                          }
                      if (get_class($formQuestion) == "QuizResult")
                          if ($formQuestion->getResults() != "" and !in_array($form->getObject()->getId(), $this->numbersToDeleteResult)) {
                              //print_r($formQuestion->Answer[1]->getId());exit;
                              $formQuestion->setQuizId($this->object);
                              $formQuestion->save($con);

                              //print_r($form->getObject());exit;
                          }
                  }
              }
          } else {
              $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
          }
      }
  }
}
