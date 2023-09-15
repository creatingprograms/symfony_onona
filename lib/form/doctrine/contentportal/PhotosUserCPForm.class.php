<?php

/**
 * PhotosUser form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhotosUserCPForm extends BasePhotosUserForm {

    protected
            $isAction = true;

    public function configure() {
        unset($this['user_id'],$this['position'], $this['created_at']);
        if ($this->isNew or $this->getObject()->getFilename() == "") {
            $this->setWidget('filename', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['filename'] = new sfWidgetFormInputFileEditable(array(
                'label' => 'Изображение',
                'file_src' => '/uploads/photouser/thumbnails/' . $this->getObject()->getFilename(),
                'is_image' => true,
                'edit_mode' => !$this->isNew(),
                'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                    ), array("style" => "float:left;"));
        }

        $this->validatorSchema['filename'] = new sfValidatorFile(array(
            'required' => false,
            'path' => sfConfig::get('sf_upload_dir') . '/photouser/',
            'mime_types' => 'web_images',
            'validated_file_class' => 'sfPhotoCatalogValidatedFile',
        ));
        $this->validatorSchema['filename_delete'] = new sfValidatorPass();

        $this->setWidget('product_id', new sfWidgetFormDoctrineChoiceSearch(array('model' => $this->getRelatedModelName('Product'), 'query' => Doctrine_Query::create()->select('*')->from('product p')->where("is_public=\"1\"" . ($this->getObject()->getProductId() != "" ? " or id=\"" . $this->getObject()->getProductId() . "\"" : '')), 'add_empty' => true)));

        if ($this->isNew) {
            unset($this['comment_manager'], $this['point'], $this['is_public']);
            $this->setWidget('manager_id', new sfWidgetFormInputHidden(array(), array('value' => sfContext::getInstance()->getUser()->getGuardUser()->getId())));
        } else {
            if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == $this->getObject()->getManagerId()) {
                unset($this['comment_manager'], $this['point'], $this['is_public'], $this['manager_id']);
                $this->setWidget('comment_manager', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCommentManager())));
                $this->setWidget('point', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPoint())));
            } elseif (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal")) {
                
                unset($this['manager_id']);
            } else {
                $this->isAction(false);
                unset($this['is_public'],$this['manager_id']);
                $this->setWidget('product_id', new sfWidgetFormPrintText(array('value' => $this->getObject()->getProduct()->getName())));
                $this->setWidget('username', new sfWidgetFormPrintText(array('value' => $this->getObject()->getUsername())));
                $this->setWidget('filename', new sfWidgetFormPrintImg(array('value' => '/uploads/photouser/thumbnails/' . $this->getObject()->getFilename())));
                $this->setWidget('name', new sfWidgetFormPrintText(array('value' => $this->getObject()->getName())));
                $this->setWidget('comment', new sfWidgetFormPrintText(array('value' => $this->getObject()->getComment())));
                $this->setWidget('comment_manager', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCommentManager())));
                $this->setWidget('point', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPoint())));
            }
        }
    }

    public function isAction($params = null) {
        if($params!==null){
            $this->isAction=$params;
        }
        return $this->isAction;
    }

}
