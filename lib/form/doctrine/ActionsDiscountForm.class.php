<?php

/**
 * ActionsDiscount form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ActionsDiscountForm extends BaseActionsDiscountForm {

    protected $numbersToDeleteInterval = array();
    
    public function configure() {
        $this->embedRelation('Interval');
        $this->widgetSchema['Interval']->setLabel('Интервалы');
        $relationName = "Interval";
        $relationSettings = array(
            'considerNewFormEmptyFields' => array('start', 'end', 'discount'),
            'noNewForm' => true,
            'newFormLabel' => 'Новый интервал',
            'newFormClass' => 'ActionsDiscountIntervalForm',
            'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
            'displayEmptyRelations' => false,
            'formClass' => 'ActionsDiscountIntervalForm',
            'formClassArgs' => array(array('ah_add_delete_checkbox' => true)),
            'newFormAfterExistingRelations' => false,
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
            'containerName' => "new_Interval",
            'addJavascript' => true,
            'useJSFramework' => "jQuery",
            'newRelationButtonLabel' => "+",
            'addByCloning' => true
        ));

        unset($subForm[$subForm->getCSRFFieldName()]);


        $relation = $this->getObject()->getTable()->getRelation($relationName);
        $newForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, 1);
        $subForm->embedForm(0, $newForm);

        $subForm->getWidgetSchema()->setLabel('Новый интервал');
        $this->embedForm("new_Interval", $subForm);

        if ($this->isNew) {

            $this->setWidget('Interval', new sfWidgetFormInputHidden(array(), array('value' => false)));
            $this->setValidator("Interval", new sfValidatorBoolean(array('required' => false)));
        }
    }

    private function embeddedFormFactory($relationName, array $relationSettings, Doctrine_Relation $relation, $formLabel = null) {
        $newFormObject = $this->embeddedFormObjectFactory($relationName, $relation);
        $formClass = (null === $relationSettings['newFormClass']) ? $relation->getClass() . 'Form' : $relationSettings['newFormClass'];
        $formArgs = (null === $relationSettings['newFormClassArgs']) ? array() : $relationSettings['newFormClassArgs'];
        $formArgs[0]['numForm'] = $formLabel;
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







        foreach ($values['new_Interval'] as $keyNewRes => $newRes) {
            if ('' === trim($newRes['discount'])) {
                $this->validatorSchema['new_Interval'][$keyNewRes] = new sfValidatorString();
            } else {
                if (!isset($this->embeddedForms['new_Interval'][$keyNewRes])) {
                    $relationSettings = array(
                        'considerNewFormEmptyFields' => array('start', 'end', 'discount'),
                        'noNewForm' => true,
                        'newFormLabel' => 'Новый интервал',
                        'newFormClass' => 'ActionsDiscountIntervalForm',
                        'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
                        'displayEmptyRelations' => false,
                        'formClass' => 'ActionsDiscountIntervalForm',
                        'formClassArgs' => array(array('ah_add_delete_checkbox' => false)),
                        'newFormAfterExistingRelations' => false,
                        'formFormatter' => null,
                        'multipleNewForms' => true,
                        'newFormsInitialCount' => 2,
                        'newFormsContainerForm' => null, // pass BaseForm object here or we will create ahNewRelationsContainerForm
                        'newRelationButtonLabel' => '+',
                        'newRelationAddByCloning' => true,
                        'newRelationUseJSFramework' => 'jQuery',
                        'customEmbeddedFormLabelMethod' => 'getLabelTitle'
                    );
                    $relationName = "Interval";
                    // create and embed new form
                    $relation = $this->getObject()->getTable()->getRelation($relationName);
                    $addedForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, ((int) $keyNewRes) + 1);
                    $ef = $this->embeddedForms['new_Interval'];
                    $ef->embedForm($keyNewRes, $addedForm);
                    // ... and reset other stuff (symfony loses all this since container form is already embedded)
                    $this->validatorSchema['new_Interval'] = $ef->getValidatorSchema();
                    $this->widgetSchema['new_Interval'] = new sfWidgetFormSchemaDecorator($ef->getWidgetSchema(), $ef->getWidgetSchema()->getFormFormatter()->getDecoratorFormat());
                    $this->setDefault('new_Interval', $ef->getDefaults());
                }
            }
        }
        if (isset($values['Interval'])) {
            foreach ($values['Interval'] as $key => $result) {
                if (isset($result['delete']) && $result['id']) {
                    $this->numbersToDeleteInterval[$key] = $result['id'];
                }
            }
        }

        /* print_r($this->numbersToDeleteInterval);
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



        if (count($this->numbersToDeleteInterval)) {
            foreach ($this->numbersToDeleteInterval as $index => $id) {
                unset($values['Interval'][$index]);
                unset($this->object['Interval'][$index]);
                ActionsDiscountIntervalTable::getInstance()->findOneById($id)->delete();
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
            foreach ($this->getValue('new_Interval') as $keyNewRes => $newRes) {

                $result = $newRes;
                $forms = $this->embeddedForms;

                if ('' === trim($result['discount'])) {
                    unset($forms['new_Interval'][$keyNewRes]);
                }
            }
        } else {
            foreach ($this->getValue('new_Interval') as $keyNewRes => $newRes) {
                $result = $newRes;

                if ('' === trim($result['discount'])) {
                    unset($forms['new_Interval'][$keyNewRes]);
                }
            }
        }
        /* print_r($this->getValues());
          exit; */

        foreach ($forms as $formKey => $form) {

            if ($form instanceof sfFormObject) {

                    if ($form->getObject()->isNew()) {
                        $formQuestion = $form->getObject();
                        
                        if (get_class($formQuestion) == "ActionsDiscountInterval")
                            if ($formQuestion->getDiscount() != "" and !in_array($form->getObject()->getId(), $this->numbersToDeleteInterval)) {
                                $formQuestion->setActionsdiscountId($this->object);
                                $formQuestion->save($con);
                            }
                    } else {
                        //$form->saveEmbeddedForms($con, null, $this->answerToDelete);

                        $formQuestion = $form->getObject();
                        
                        if (get_class($formQuestion) == "ActionsDiscountInterval")
                            if ($formQuestion->getDiscount() != "" and !in_array($form->getObject()->getId(), $this->numbersToDeleteInterval)) {
                                //print_r($formQuestion->Answer[1]->getId());exit;
                                $formQuestion->setActionsdiscountId($this->object);
                                $formQuestion->save($con);

                                //print_r($form->getObject());exit;
                            }
                    }
            } else {
                $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
            }
        }
    }

}
