<?php

/**
 * Banners filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseBannersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'href'        => new sfWidgetFormFilterInput(),
      'alt'         => new sfWidgetFormFilterInput(),
      'description' => new sfWidgetFormFilterInput(),
      'button'      => new sfWidgetFormFilterInput(),
      'src'         => new sfWidgetFormFilterInput(),
      'src_mobile'  => new sfWidgetFormFilterInput(),
      'is_public'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_rotation' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'class'       => new sfWidgetFormChoice(array('choices' => array('' => '', 'block-info_1' => 'block-info_1', 'block-info_2' => 'block-info_2'))),
      'view_count'  => new sfWidgetFormFilterInput(),
      'position'    => new sfWidgetFormChoice(array('choices' => array('' => '', 'Главная новый 2' => 'Главная новый 2', 'Главная новый 3' => 'Главная новый 3', 'Двойной Главная' => 'Двойной Главная', 'Главная 1' => 'Главная 1', 'Главная 2' => 'Главная 2', 'Категория' => 'Категория', 'Каталог' => 'Каталог', 'Карточка товара' => 'Карточка товара', 'Корзина' => 'Корзина', 'catalog' => 'catalog', 'sex-igrushki-dlja-par' => 'sex-igrushki-dlja-par', 'sex-igrushki-dlya-muzhchin' => 'sex-igrushki-dlya-muzhchin', 'sex-igrushki-dlya-zhenschin' => 'sex-igrushki-dlya-zhenschin', 'BDSM-i-fetish' => 'BDSM-i-fetish', 'intimnaya-kosmetika' => 'intimnaya-kosmetika', 'preparaty' => 'preparaty', 'eroticheskoe-bele' => 'eroticheskoe-bele', 'aksessuary-dlya-seksa' => 'aksessuary-dlya-seksa', 'dlya_novichkov' => 'dlya_novichkov', 'slider_sex-igrushki-dlja-par' => 'slider_sex-igrushki-dlja-par', 'slider_sex-igrushki-dlya-muzhchin' => 'slider_sex-igrushki-dlya-muzhchin', 'slider_sex-igrushki-dlya-zhenschin' => 'slider_sex-igrushki-dlya-zhenschin', 'slider_bdsm-i-fetish' => 'slider_bdsm-i-fetish', 'slider_intimnaya-kosmetika' => 'slider_intimnaya-kosmetika', 'slider_preparaty' => 'slider_preparaty', 'slider_eroticheskoe-bele' => 'slider_eroticheskoe-bele', 'slider_aksessuary-dlya-seksa' => 'slider_aksessuary-dlya-seksa', 'slider_dlya_novichkov' => 'slider_dlya_novichkov'))),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'href'        => new sfValidatorPass(array('required' => false)),
      'alt'         => new sfValidatorPass(array('required' => false)),
      'description' => new sfValidatorPass(array('required' => false)),
      'button'      => new sfValidatorPass(array('required' => false)),
      'src'         => new sfValidatorPass(array('required' => false)),
      'src_mobile'  => new sfValidatorPass(array('required' => false)),
      'is_public'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_rotation' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'class'       => new sfValidatorChoice(array('required' => false, 'choices' => array('block-info_1' => 'block-info_1', 'block-info_2' => 'block-info_2'))),
      'view_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'position'    => new sfValidatorChoice(array('required' => false, 'choices' => array('Главная новый 2' => 'Главная новый 2', 'Главная новый 3' => 'Главная новый 3', 'Двойной Главная' => 'Двойной Главная', 'Главная 1' => 'Главная 1', 'Главная 2' => 'Главная 2', 'Категория' => 'Категория', 'Каталог' => 'Каталог', 'Карточка товара' => 'Карточка товара', 'Корзина' => 'Корзина', 'catalog' => 'catalog', 'sex-igrushki-dlja-par' => 'sex-igrushki-dlja-par', 'sex-igrushki-dlya-muzhchin' => 'sex-igrushki-dlya-muzhchin', 'sex-igrushki-dlya-zhenschin' => 'sex-igrushki-dlya-zhenschin', 'BDSM-i-fetish' => 'BDSM-i-fetish', 'intimnaya-kosmetika' => 'intimnaya-kosmetika', 'preparaty' => 'preparaty', 'eroticheskoe-bele' => 'eroticheskoe-bele', 'aksessuary-dlya-seksa' => 'aksessuary-dlya-seksa', 'dlya_novichkov' => 'dlya_novichkov', 'slider_sex-igrushki-dlja-par' => 'slider_sex-igrushki-dlja-par', 'slider_sex-igrushki-dlya-muzhchin' => 'slider_sex-igrushki-dlya-muzhchin', 'slider_sex-igrushki-dlya-zhenschin' => 'slider_sex-igrushki-dlya-zhenschin', 'slider_bdsm-i-fetish' => 'slider_bdsm-i-fetish', 'slider_intimnaya-kosmetika' => 'slider_intimnaya-kosmetika', 'slider_preparaty' => 'slider_preparaty', 'slider_eroticheskoe-bele' => 'slider_eroticheskoe-bele', 'slider_aksessuary-dlya-seksa' => 'slider_aksessuary-dlya-seksa', 'slider_dlya_novichkov' => 'slider_dlya_novichkov'))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('banners_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Banners';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'href'        => 'Text',
      'alt'         => 'Text',
      'description' => 'Text',
      'button'      => 'Text',
      'src'         => 'Text',
      'src_mobile'  => 'Text',
      'is_public'   => 'Boolean',
      'is_rotation' => 'Boolean',
      'class'       => 'Enum',
      'view_count'  => 'Number',
      'position'    => 'Enum',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
    );
  }
}
