<?php

/**
 * Banners form base class.
 *
 * @method Banners getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseBannersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'href'        => new sfWidgetFormInputText(),
      'alt'         => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
      'button'      => new sfWidgetFormInputText(),
      'src'         => new sfWidgetFormInputText(),
      'src_mobile'  => new sfWidgetFormInputText(),
      'is_public'   => new sfWidgetFormInputCheckbox(),
      'is_rotation' => new sfWidgetFormInputCheckbox(),
      'class'       => new sfWidgetFormChoice(array('choices' => array('block-info_1' => 'block-info_1', 'block-info_2' => 'block-info_2'))),
      'view_count'  => new sfWidgetFormInputText(),
      'position'    => new sfWidgetFormChoice(array('choices' => array('Главная новый 2' => 'Главная новый 2', 'Главная новый 3' => 'Главная новый 3', 'Двойной Главная' => 'Двойной Главная', 'Главная 1' => 'Главная 1', 'Главная 2' => 'Главная 2', 'Категория' => 'Категория', 'Каталог' => 'Каталог', 'Карточка товара' => 'Карточка товара', 'Корзина' => 'Корзина', 'catalog' => 'catalog', 'sex-igrushki-dlja-par' => 'sex-igrushki-dlja-par', 'sex-igrushki-dlya-muzhchin' => 'sex-igrushki-dlya-muzhchin', 'sex-igrushki-dlya-zhenschin' => 'sex-igrushki-dlya-zhenschin', 'BDSM-i-fetish' => 'BDSM-i-fetish', 'intimnaya-kosmetika' => 'intimnaya-kosmetika', 'preparaty' => 'preparaty', 'eroticheskoe-bele' => 'eroticheskoe-bele', 'aksessuary-dlya-seksa' => 'aksessuary-dlya-seksa', 'dlya_novichkov' => 'dlya_novichkov', 'slider_sex-igrushki-dlja-par' => 'slider_sex-igrushki-dlja-par', 'slider_sex-igrushki-dlya-muzhchin' => 'slider_sex-igrushki-dlya-muzhchin', 'slider_sex-igrushki-dlya-zhenschin' => 'slider_sex-igrushki-dlya-zhenschin', 'slider_bdsm-i-fetish' => 'slider_bdsm-i-fetish', 'slider_intimnaya-kosmetika' => 'slider_intimnaya-kosmetika', 'slider_preparaty' => 'slider_preparaty', 'slider_eroticheskoe-bele' => 'slider_eroticheskoe-bele', 'slider_aksessuary-dlya-seksa' => 'slider_aksessuary-dlya-seksa', 'slider_dlya_novichkov' => 'slider_dlya_novichkov'))),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'href'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'alt'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'button'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'src'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'src_mobile'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_public'   => new sfValidatorBoolean(array('required' => false)),
      'is_rotation' => new sfValidatorBoolean(array('required' => false)),
      'class'       => new sfValidatorChoice(array('choices' => array(0 => 'block-info_1', 1 => 'block-info_2'), 'required' => false)),
      'view_count'  => new sfValidatorInteger(array('required' => false)),
      'position'    => new sfValidatorChoice(array('choices' => array(0 => 'Главная новый 2', 1 => 'Главная новый 3', 2 => 'Двойной Главная', 3 => 'Главная 1', 4 => 'Главная 2', 5 => 'Категория', 6 => 'Каталог', 7 => 'Карточка товара', 8 => 'Корзина', 9 => 'catalog', 10 => 'sex-igrushki-dlja-par', 11 => 'sex-igrushki-dlya-muzhchin', 12 => 'sex-igrushki-dlya-zhenschin', 13 => 'BDSM-i-fetish', 14 => 'intimnaya-kosmetika', 15 => 'preparaty', 16 => 'eroticheskoe-bele', 17 => 'aksessuary-dlya-seksa', 18 => 'dlya_novichkov', 19 => 'slider_sex-igrushki-dlja-par', 20 => 'slider_sex-igrushki-dlya-muzhchin', 21 => 'slider_sex-igrushki-dlya-zhenschin', 22 => 'slider_bdsm-i-fetish', 23 => 'slider_intimnaya-kosmetika', 24 => 'slider_preparaty', 25 => 'slider_eroticheskoe-bele', 26 => 'slider_aksessuary-dlya-seksa', 27 => 'slider_dlya_novichkov'), 'required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('banners[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Banners';
  }

}
