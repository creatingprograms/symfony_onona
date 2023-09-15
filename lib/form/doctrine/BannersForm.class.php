    <?php

/**
 * Banners form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BannersForm extends BaseBannersForm
{
  public function configure()
  {
       if ($this->isNew) {
             $this->setWidget('src', new sfWidgetFormInputFile());
             $this->setWidget('src_mobile', new sfWidgetFormInputFile());
        } else {
            $this->widgetSchema['src'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/media/' . $this->getObject()->getSrc(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%delete%<br />%delete_label%<br />%file%<br />%input%</div>',
                    ));
            $this->widgetSchema['src_mobile'] = new sfWidgetFormInputFileEditable(array(
                        'label' => 'Изображение',
                        'file_src' => '/uploads/media/' . $this->getObject()->getSrcMobile(),
                        'is_image' => true,
                        'edit_mode' => !$this->isNew(),
                        'template' => '<div>%delete%<br />%delete_label%<br />%file%<br />%input%</div>',
                    ));
        }
        unset($this['view_count']);
        $this->validatorSchema['src'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/media/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfBannersValidatedFile',
                ));
        $this->validatorSchema['src_mobile'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('sf_upload_dir') . '/media/',
                    'mime_types' => 'web_images',
                    'validated_file_class' => 'sfBannersValidatedFile',
                ));
        $this->validatorSchema['src_delete'] = new sfValidatorPass();
        $this->validatorSchema['src_mobile_delete'] = new sfValidatorPass();
  }
}
