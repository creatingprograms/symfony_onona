<?php

/**
 * Video form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class VideoCPForm extends BaseVideoForm {

    protected
            $isAction = true;

    public function configure() {
        unset($this['link'], $this['created_at']);
        if ($this->object->exists()) {
            $this->widgetSchema['photodelete'] = new sfWidgetFormInputCheckbox();
            $this->validatorSchema['photodelete'] = new sfValidatorPass();
        }

        if ($this->isNew) {
            $this->setWidget('photo', new sfWidgetFormInputFile());
            $this->setWidget('videoserver', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['photo'] = new sfWidgetFormInputFileEditable(array(
                'label' => 'Изображение',
                'file_src' => '/uploads/photovideo/' . $this->getObject()->getPhoto(),
                'is_image' => true,
                'edit_mode' => !$this->isNew(),
                'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                    ), array("style" => "float:left; max-width: 400px;"));
            $this->widgetSchema['videoserver'] = new sfWidgetFormInputFileEditable(array(
                'label' => 'Изображение',
                'file_src' => '/uploads/video/' . $this->getObject()->getVideoserver(),
                'is_image' => false,
                'edit_mode' => $this->getObject()->getVideoserver(),
                    /* 'template' => '<div>%file%%fileInfo%<br />%input%</div>', */
            ));
        }

        $this->validatorSchema['photo'] = new sfValidatorFile(array(
            'required' => false,
            'path' => sfConfig::get('sf_upload_dir') . '/photovideo/',
            'mime_types' => 'web_images',
            'validated_file_class' => 'sfPhotovideoValidatedFile',
        ));
        $this->validatorSchema['photo_delete'] = new sfValidatorPass();


        $this->setValidator('videoserver', new sfValidatorFile(array(
            'required' => false,
            'path' => sfConfig::get('sf_upload_dir') . '/video/',
        )));
        $this->validatorSchema['videoserver_delete'] = new sfValidatorPass();

        $this->setWidget('product_id', new sfWidgetFormDoctrineChoiceSearch(array('model' => $this->getRelatedModelName('Product'), 'query' => Doctrine_Query::create()->select('*')->from('product p')->where("is_public=\"1\"" . ($this->getObject()->getProductId() != "" ? " or id=\"" . $this->getObject()->getProductId() . "\"" : '')), 'add_empty' => true)));

        if ($this->isNew) {
            unset($this['comment_manager'], $this['point'], $this['is_public'],$this['is_publicmainpage']);
            $this->setWidget('manager_id', new sfWidgetFormInputHidden(array(), array('value' => sfContext::getInstance()->getUser()->getGuardUser()->getId())));
        } else {
            if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == $this->getObject()->getManagerId()) {
                unset($this['comment_manager'], $this['point'], $this['is_public'],$this['is_publicmainpage'], $this['manager_id']);
                $this->setWidget('comment_manager', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCommentManager())));
                $this->setWidget('point', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPoint())));
            } elseif (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal")) {

                unset($this['manager_id']);
            } else {
                $this->isAction(false);
                unset($this['is_public'],$this['is_publicmainpage'], $this['manager_id']);
                $this->setWidget('product_id', new sfWidgetFormPrintText(array('value' => $this->getObject()->getProduct()->getName())));
                $this->setWidget('name', new sfWidgetFormPrintText(array('value' => $this->getObject()->getName())));
                $this->setWidget('slug', new sfWidgetFormPrintText(array('value' => $this->getObject()->getSlug())));
                $this->setWidget('link', new sfWidgetFormPrintText(array('value' => $this->getObject()->getLink())));
                $this->setWidget('videoyoutube', new sfWidgetFormPrintText(array('value' => $this->getObject()->getVideoyoutube())));
                $this->setWidget('videoserver', new sfWidgetFormPrintText(array('value' => $this->getObject()->getVideoserver())));
                $this->setWidget('photo', new sfWidgetFormPrintImg(array('value' => '/uploads/photovideo/' . $this->getObject()->getPhoto())));
                $this->setWidget('content', new sfWidgetFormPrintText(array('value' => $this->getObject()->getContent())));
                $this->setWidget('tag', new sfWidgetFormPrintText(array('value' => $this->getObject()->getTag())));
                $this->setWidget('title', new sfWidgetFormPrintText(array('value' => $this->getObject()->getTitle())));
                $this->setWidget('keywords', new sfWidgetFormPrintText(array('value' => $this->getObject()->getKeywords())));
                $this->setWidget('description', new sfWidgetFormPrintText(array('value' => $this->getObject()->getDescription())));
                $this->setWidget('youtubelink', new sfWidgetFormPrintText(array('value' => $this->getObject()->getYoutubelink())));
                $this->setWidget('comment_manager', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCommentManager())));
                $this->setWidget('point', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPoint())));
            }
        }
    }

    public function isAction($params = null) {
        if ($params !== null) {
            $this->isAction = $params;
        }
        return $this->isAction;
    }

}
