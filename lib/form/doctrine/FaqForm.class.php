<?php

/**
 * Faq form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FaqForm extends BaseFaqForm
{
  public function configure() {
    // $this->widgetSchema['created_at']->setOption('default', time());
    unset($this['position'], $this['user'], $this['moder']);
    /*
    if ($this->isNew()) {

        $this->setWidget('user', new sfWidgetFormInputHidden(array(), array('value' => sfContext::getInstance()->getUser()->getGuardUser()->getId())));
        $this->setValidator("user", new sfValidatorString(array('required' => false)));
    }*/
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

    if ($this->isNew or $this->getObject()->getImgPreview() == "") {
        $this->setWidget('img_preview', new sfWidgetFormInputFile());
    } else {
        $this->widgetSchema['img_preview'] = new sfWidgetFormInputFileEditable(array(
            'label' => 'Изображение',
            'file_src' => '/uploads/photo/' . $this->getObject()->getImgPreview(),
            'is_image' => true,
            'edit_mode' => !$this->isNew(),
            'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                ), array("style" => "float:left;"));
    }

    $this->validatorSchema['img_preview'] = new sfValidatorFile(array(
        'required' => false,
        'path' => sfConfig::get('sf_upload_dir') . '/photo/',
        'mime_types' => 'web_images',
        'validated_file_class' => 'sfPhotoCatalogValidatedFile',
    ));
    $this->validatorSchema['img_preview_delete'] = new sfValidatorPass();

    $this->validatorSchema['img'] = new sfValidatorFile(array(
        'required' => false,
        'path' => sfConfig::get('sf_upload_dir') . '/photo/',
        'mime_types' => 'web_images',
        'validated_file_class' => 'sfPhotoCatalogValidatedFile',
    ));
    $this->validatorSchema['img_delete'] = new sfValidatorPass();


    // $timerCategory = sfTimerManager::getTimer('Form: Загрузка всех категорий');
    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
    $choiseCategory = $q->execute("SELECT id,name FROM  `faqcategory`")
            ->fetchAll(Doctrine_Core::FETCH_UNIQUE);
    $this->setWidget('faqcategory_list', new sfWidgetFormDoctrineChoiceSearchArray(array('multiple' => true, 'choises' => $choiseCategory, 'add_empty' => true)));

    // $timerCategory->addTime();

  }
}
