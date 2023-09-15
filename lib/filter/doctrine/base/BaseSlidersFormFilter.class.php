<?php

/**
 * Sliders filter form base class.
 *
 * @package    test
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSlidersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'href'          => new sfWidgetFormFilterInput(),
      'alt'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'file'          => new sfWidgetFormFilterInput(),
      'is_active'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'src'           => new sfWidgetFormFilterInput(),
      'src_mobile'    => new sfWidgetFormFilterInput(),
      'position'      => new sfWidgetFormFilterInput(),
      'positionpage'  => new sfWidgetFormChoice(array('choices' => array('' => '', 'main_new_1' => 'main_new_1', 'main_new_2' => 'main_new_2', 'main_new_3' => 'main_new_3', 'catalog_sex-igrushki-dlja-par_1' => 'catalog_sex-igrushki-dlja-par_1', 'catalog_sex-igrushki-dlja-par_2' => 'catalog_sex-igrushki-dlja-par_2', 'catalog_sex-igrushki-dlja-par_3' => 'catalog_sex-igrushki-dlja-par_3', 'catalog_sex-igrushki-dlya-muzhchin_1' => 'catalog_sex-igrushki-dlya-muzhchin_1', 'catalog_sex-igrushki-dlya-muzhchin_2' => 'catalog_sex-igrushki-dlya-muzhchin_2', 'catalog_sex-igrushki-dlya-muzhchin_3' => 'catalog_sex-igrushki-dlya-muzhchin_3', 'catalog_sex-igrushki-dlya-zhenschin_1' => 'catalog_sex-igrushki-dlya-zhenschin_1', 'catalog_sex-igrushki-dlya-zhenschin_2' => 'catalog_sex-igrushki-dlya-zhenschin_2', 'catalog_sex-igrushki-dlya-zhenschin_3' => 'catalog_sex-igrushki-dlya-zhenschin_3', 'catalog_eroticheskoe-bele_1' => 'catalog_eroticheskoe-bele_1', 'catalog_eroticheskoe-bele_2' => 'catalog_eroticheskoe-bele_2', 'catalog_eroticheskoe-bele_3' => 'catalog_eroticheskoe-bele_3', 'catalog_intimnaya-kosmetika_1' => 'catalog_intimnaya-kosmetika_1', 'catalog_intimnaya-kosmetika_2' => 'catalog_intimnaya-kosmetika_2', 'catalog_intimnaya-kosmetika_3' => 'catalog_intimnaya-kosmetika_3', 'catalog_preparaty_1' => 'catalog_preparaty_1', 'catalog_preparaty_2' => 'catalog_preparaty_2', 'catalog_preparaty_3' => 'catalog_preparaty_3', 'catalog_bdsm-i-fetish_1' => 'catalog_bdsm-i-fetish_1', 'catalog_bdsm-i-fetish_2' => 'catalog_bdsm-i-fetish_2', 'catalog_bdsm-i-fetish_3' => 'catalog_bdsm-i-fetish_3', 'catalog_aksessuary-dlya-seksa_1' => 'catalog_aksessuary-dlya-seksa_1', 'catalog_aksessuary-dlya-seksa_2' => 'catalog_aksessuary-dlya-seksa_2', 'catalog_aksessuary-dlya-seksa_3' => 'catalog_aksessuary-dlya-seksa_3', 'catalog_dlya_novichkov_1' => 'catalog_dlya_novichkov_1', 'catalog_dlya_novichkov_2' => 'catalog_dlya_novichkov_2', 'catalog_dlya_novichkov_3' => 'catalog_dlya_novichkov_3', 'main_page' => 'main_page', 'main_page_new' => 'main_page_new', 'magazins_in_moscow' => 'magazins_in_moscow', 'sl_sexopedia' => 'sl_sexopedia', 'sl_pairs' => 'sl_pairs', 'sl_man' => 'sl_man', 'sl_woman' => 'sl_woman', 'sl_actions' => 'sl_actions', 'category_skidki_do_60_percent' => 'category_skidki_do_60_percent', 'catalog_feb14_1' => 'catalog_feb14_1', 'catalog_feb14_2' => 'catalog_feb14_2', 'catalog_feb14_3' => 'catalog_feb14_3', 'for-him' => 'for-him'))),
      'description'   => new sfWidgetFormFilterInput(),
      'button'        => new sfWidgetFormFilterInput(),
      'is_small'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_onlymoscow' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'class'         => new sfWidgetFormChoice(array('choices' => array('' => '', 'promotion-item_cyan' => 'promotion-item_cyan', 'promotion-item_orange' => 'promotion-item_orange', 'promotion-item_blue' => 'promotion-item_blue', 'promotion-item_red' => 'promotion-item_red'))),
      'view_count'    => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'href'          => new sfValidatorPass(array('required' => false)),
      'alt'           => new sfValidatorPass(array('required' => false)),
      'file'          => new sfValidatorPass(array('required' => false)),
      'is_active'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'src'           => new sfValidatorPass(array('required' => false)),
      'src_mobile'    => new sfValidatorPass(array('required' => false)),
      'position'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'positionpage'  => new sfValidatorChoice(array('required' => false, 'choices' => array('main_new_1' => 'main_new_1', 'main_new_2' => 'main_new_2', 'main_new_3' => 'main_new_3', 'catalog_sex-igrushki-dlja-par_1' => 'catalog_sex-igrushki-dlja-par_1', 'catalog_sex-igrushki-dlja-par_2' => 'catalog_sex-igrushki-dlja-par_2', 'catalog_sex-igrushki-dlja-par_3' => 'catalog_sex-igrushki-dlja-par_3', 'catalog_sex-igrushki-dlya-muzhchin_1' => 'catalog_sex-igrushki-dlya-muzhchin_1', 'catalog_sex-igrushki-dlya-muzhchin_2' => 'catalog_sex-igrushki-dlya-muzhchin_2', 'catalog_sex-igrushki-dlya-muzhchin_3' => 'catalog_sex-igrushki-dlya-muzhchin_3', 'catalog_sex-igrushki-dlya-zhenschin_1' => 'catalog_sex-igrushki-dlya-zhenschin_1', 'catalog_sex-igrushki-dlya-zhenschin_2' => 'catalog_sex-igrushki-dlya-zhenschin_2', 'catalog_sex-igrushki-dlya-zhenschin_3' => 'catalog_sex-igrushki-dlya-zhenschin_3', 'catalog_eroticheskoe-bele_1' => 'catalog_eroticheskoe-bele_1', 'catalog_eroticheskoe-bele_2' => 'catalog_eroticheskoe-bele_2', 'catalog_eroticheskoe-bele_3' => 'catalog_eroticheskoe-bele_3', 'catalog_intimnaya-kosmetika_1' => 'catalog_intimnaya-kosmetika_1', 'catalog_intimnaya-kosmetika_2' => 'catalog_intimnaya-kosmetika_2', 'catalog_intimnaya-kosmetika_3' => 'catalog_intimnaya-kosmetika_3', 'catalog_preparaty_1' => 'catalog_preparaty_1', 'catalog_preparaty_2' => 'catalog_preparaty_2', 'catalog_preparaty_3' => 'catalog_preparaty_3', 'catalog_bdsm-i-fetish_1' => 'catalog_bdsm-i-fetish_1', 'catalog_bdsm-i-fetish_2' => 'catalog_bdsm-i-fetish_2', 'catalog_bdsm-i-fetish_3' => 'catalog_bdsm-i-fetish_3', 'catalog_aksessuary-dlya-seksa_1' => 'catalog_aksessuary-dlya-seksa_1', 'catalog_aksessuary-dlya-seksa_2' => 'catalog_aksessuary-dlya-seksa_2', 'catalog_aksessuary-dlya-seksa_3' => 'catalog_aksessuary-dlya-seksa_3', 'catalog_dlya_novichkov_1' => 'catalog_dlya_novichkov_1', 'catalog_dlya_novichkov_2' => 'catalog_dlya_novichkov_2', 'catalog_dlya_novichkov_3' => 'catalog_dlya_novichkov_3', 'main_page' => 'main_page', 'main_page_new' => 'main_page_new', 'magazins_in_moscow' => 'magazins_in_moscow', 'sl_sexopedia' => 'sl_sexopedia', 'sl_pairs' => 'sl_pairs', 'sl_man' => 'sl_man', 'sl_woman' => 'sl_woman', 'sl_actions' => 'sl_actions', 'category_skidki_do_60_percent' => 'category_skidki_do_60_percent', 'catalog_feb14_1' => 'catalog_feb14_1', 'catalog_feb14_2' => 'catalog_feb14_2', 'catalog_feb14_3' => 'catalog_feb14_3', 'for-him' => 'for-him'))),
      'description'   => new sfValidatorPass(array('required' => false)),
      'button'        => new sfValidatorPass(array('required' => false)),
      'is_small'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_onlymoscow' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'class'         => new sfValidatorChoice(array('required' => false, 'choices' => array('promotion-item_cyan' => 'promotion-item_cyan', 'promotion-item_orange' => 'promotion-item_orange', 'promotion-item_blue' => 'promotion-item_blue', 'promotion-item_red' => 'promotion-item_red'))),
      'view_count'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('sliders_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Sliders';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'href'          => 'Text',
      'alt'           => 'Text',
      'file'          => 'Text',
      'is_active'     => 'Boolean',
      'src'           => 'Text',
      'src_mobile'    => 'Text',
      'position'      => 'Number',
      'positionpage'  => 'Enum',
      'description'   => 'Text',
      'button'        => 'Text',
      'is_small'      => 'Boolean',
      'is_onlymoscow' => 'Boolean',
      'class'         => 'Enum',
      'view_count'    => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
