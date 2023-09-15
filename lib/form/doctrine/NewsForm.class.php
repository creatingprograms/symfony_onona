<?php

/**
 * News form.
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class NewsForm extends BaseNewsForm
{
  public function configure()
  {
      // $this->setWidget('news_pages_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Page','query' => Doctrine_Query::create()->select('*')->from('page p')->leftJoin("p.Categorypage c")->where("c.name=\"Магазины\"")), array('style' => 'height:200px')));

      $this->widgetSchema['created_at']->setOption('default', time());

      if ($this->isNew or $this->getObject()->getPhotoUrl() == "") {
          $this->setWidget('photo_url', new sfWidgetFormInputFile());
      } else {
          $this->widgetSchema['photo_url'] = new sfWidgetFormInputFileEditable(array(
              'label' => 'Изображение',
              'file_src' => '/uploads/news/' . $this->getObject()->getPhotoUrl(),
              'is_image' => true,
              'edit_mode' => !$this->isNew(),
              'template' => '<div>%file%%fileInfo%<br />%input%</div>',
                  ), array("style" => "float:left;"));
      }

      $this->validatorSchema['photo_url'] = new sfValidatorFile(array(
          'required' => false,
          'path' => sfConfig::get('sf_upload_dir') . '/news/',
          'mime_types' => 'web_images',
          'validated_file_class' => 'sfPhotoCatalogValidatedFile',
      ));
      $this->validatorSchema['img_delete'] = new sfValidatorPass();

  }
}
