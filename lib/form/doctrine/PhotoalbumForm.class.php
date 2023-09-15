<?php

/**
 * Photoalbum form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhotoalbumForm extends BasePhotoalbumForm {

    protected $photoToDelete = array();

    public function configure() {

        unset($this['content'], $this['product_photoalbums_list']);
        if ($this->object->exists()) {
            $this->widgetSchema['delete'] = new sfWidgetFormInputCheckbox();
            $this->validatorSchema['delete'] = new sfValidatorPass();
        }


        /* $newPhoto = new Photo();
          $newPhoto->setPhotoalbum($this->object);
          $newPhotoForm = new PhotoForm($newPhoto);
          $this->embedForm('newPhoto', $newPhotoForm); */

        if (!$this->isNew) {
            $newPhoto = new Photo();
            $newPhoto->setPhotoalbum($this->object);
            $newPhotoForm = new PhotoForm($newPhoto);
            //$this->embedFormForEach('newPhoto', $newPhotoForm, 4);

            $relationName = "Photos";
            $relationSettings = array(
                'considerNewFormEmptyFields' => array('filename'),
                'noNewForm' => false,
                'newFormLabel' => 'Новая фотография',
                'newFormClass' => 'PhotoForm',
                'newFormClassArgs' => array(array('sf_user' => $this->getOption('sf_user'))),
                'displayEmptyRelations' => false,
                'formClass' => 'PhotoForm',
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
                'containerName' => "newPhoto",
                'addJavascript' => true,
                'useJSFramework' => "jQuery",
                'newRelationButtonLabel' => "+",
                'addByCloning' => true
            ));
            unset($subForm[$subForm->getCSRFFieldName()]);
            $relation = $this->getObject()->getTable()->getRelation($relationName);
            $newForm = $this->embeddedFormFactory($relationName, $relationSettings, $relation, 1);
            if (isset($_POST['product']['newPhotoalbums'][$this->getOption('newPhotoalbums') - 1])) {
                //echo count($_POST['tests']['new_Question'][$this->getOption('numForm') - 1]['new_Answer']);
                for ($i = 0; $i < count($_POST['product']['newPhotoalbums'][$this->getOption('numForm') - 1]['newPhoto']); $i++) {

                    $subForm->embedForm($i, $newForm);
                }
            } else {
                $subForm->embedForm(0, $newForm);
            }
            $subForm->getWidgetSchema()->setLabel('Новая фотография');

            $subForm->validatorSchema->setOption('allow_extra_fields', true);
            $subForm->validatorSchema->setOption('filter_extra_fields', false);
            $this->embedForm("newPhoto", $subForm);
            $this->embedRelation('Photos', null, array('orderBy' => array('position', 'ASC')));
        }
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

    protected function doUpdateObject($values) {
        // step 4.4
        if (count($this->photoToDelete)) {
            foreach ($this->photoToDelete as $index => $id) {
                unset($values['Photos'][$index]);
                unset($this->object['Photos'][$index]);
                PhotoTable::getInstance()->findOneById($id)->delete();
            }
        }

        parent::doUpdateObject($values);
    }

    protected function doBind(array $values) {

        //print_r(array_keys($values)); 
        // step 4.3
        if (isset($values['Photos'])) {
            foreach ($values['Photos'] as $key => $photo) {
                if (isset($photo['photodelete']) && $photo['id']) {
                    $this->photoToDelete[$key] = $photo['id'];
                }
            }
        }
        parent::doBind($values);
    }

    public function saveEmbeddedForms($con = null, $forms = null, $photoToDelete = array()) {

        if (null === $con) {
            $con = $this->getConnection();
        }
        // step 3.2
        if (null === $forms) {
            $photo = $this->getValue('newPhoto');
            $forms = $this->embeddedForms;
            if ('' === trim($photo['name'])) {
                unset($forms['newPhoto']);
            }
        }
        $i = 0;
        $photoDeleted = array();
        foreach ($photoToDelete as $photoOne) {
            $photoDeleted[] = $photoOne['id'];
        }
        foreach ($forms as $form) {

            if ($form instanceof sfFormObject) {


                if (!in_array($form->getObject()->getId(), $photoDeleted)) {
                    $form->saveEmbeddedForms($con);

                    /* print_R(array_keys($forms));
                      $i++;
                      if ($i > 3)
                      exit; */

                    if ($form->getObject()->getFilename() != "") {
                        $form->getObject()->save($con);
                    }
                }
            } else {
                $this->saveEmbeddedForms($con, $form->getEmbeddedForms(), $photoToDelete);
            }
        }
    }

}
