<?php

/**
 * Sliders form base class.
 *
 * @method Sliders getObject() Returns the current form's model object
 *
 * @package    test
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSlidersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'href'          => new sfWidgetFormInputText(),
      'alt'           => new sfWidgetFormInputText(),
      'file'          => new sfWidgetFormInputText(),
      'is_active'     => new sfWidgetFormInputCheckbox(),
      'src'           => new sfWidgetFormInputText(),
      'src_mobile'    => new sfWidgetFormInputText(),
      'position'      => new sfWidgetFormInputText(),
      'positionpage'  => new sfWidgetFormChoice(array('choices' => array('main_new_1' => 'main_new_1', 'main_new_2' => 'main_new_2', 'main_new_3' => 'main_new_3', 'catalog_sex-igrushki-dlja-par_1' => 'catalog_sex-igrushki-dlja-par_1', 'catalog_sex-igrushki-dlja-par_2' => 'catalog_sex-igrushki-dlja-par_2', 'catalog_sex-igrushki-dlja-par_3' => 'catalog_sex-igrushki-dlja-par_3', 'catalog_sex-igrushki-dlya-muzhchin_1' => 'catalog_sex-igrushki-dlya-muzhchin_1', 'catalog_sex-igrushki-dlya-muzhchin_2' => 'catalog_sex-igrushki-dlya-muzhchin_2', 'catalog_sex-igrushki-dlya-muzhchin_3' => 'catalog_sex-igrushki-dlya-muzhchin_3', 'catalog_sex-igrushki-dlya-zhenschin_1' => 'catalog_sex-igrushki-dlya-zhenschin_1', 'catalog_sex-igrushki-dlya-zhenschin_2' => 'catalog_sex-igrushki-dlya-zhenschin_2', 'catalog_sex-igrushki-dlya-zhenschin_3' => 'catalog_sex-igrushki-dlya-zhenschin_3', 'catalog_eroticheskoe-bele_1' => 'catalog_eroticheskoe-bele_1', 'catalog_eroticheskoe-bele_2' => 'catalog_eroticheskoe-bele_2', 'catalog_eroticheskoe-bele_3' => 'catalog_eroticheskoe-bele_3', 'catalog_intimnaya-kosmetika_1' => 'catalog_intimnaya-kosmetika_1', 'catalog_intimnaya-kosmetika_2' => 'catalog_intimnaya-kosmetika_2', 'catalog_intimnaya-kosmetika_3' => 'catalog_intimnaya-kosmetika_3', 'catalog_preparaty_1' => 'catalog_preparaty_1', 'catalog_preparaty_2' => 'catalog_preparaty_2', 'catalog_preparaty_3' => 'catalog_preparaty_3', 'catalog_bdsm-i-fetish_1' => 'catalog_bdsm-i-fetish_1', 'catalog_bdsm-i-fetish_2' => 'catalog_bdsm-i-fetish_2', 'catalog_bdsm-i-fetish_3' => 'catalog_bdsm-i-fetish_3', 'catalog_aksessuary-dlya-seksa_1' => 'catalog_aksessuary-dlya-seksa_1', 'catalog_aksessuary-dlya-seksa_2' => 'catalog_aksessuary-dlya-seksa_2', 'catalog_aksessuary-dlya-seksa_3' => 'catalog_aksessuary-dlya-seksa_3', 'catalog_dlya_novichkov_1' => 'catalog_dlya_novichkov_1', 'catalog_dlya_novichkov_2' => 'catalog_dlya_novichkov_2', 'catalog_dlya_novichkov_3' => 'catalog_dlya_novichkov_3', 'main_page' => 'main_page', 'main_page_new' => 'main_page_new', 'magazins_in_moscow' => 'magazins_in_moscow', 'sl_sexopedia' => 'sl_sexopedia', 'sl_pairs' => 'sl_pairs', 'sl_man' => 'sl_man', 'sl_woman' => 'sl_woman', 'sl_actions' => 'sl_actions', 'category_skidki_do_60_percent' => 'category_skidki_do_60_percent', 'catalog_feb14_1' => 'catalog_feb14_1', 'catalog_feb14_2' => 'catalog_feb14_2', 'catalog_feb14_3' => 'catalog_feb14_3', 'for-him' => 'for-him'))),
      'description'   => new sfWidgetFormInputText(),
      'button'        => new sfWidgetFormInputText(),
      'is_small'      => new sfWidgetFormInputCheckbox(),
      'is_onlymoscow' => new sfWidgetFormInputCheckbox(),
      'class'         => new sfWidgetFormChoice(array('choices' => array('promotion-item_cyan' => 'promotion-item_cyan', 'promotion-item_orange' => 'promotion-item_orange', 'promotion-item_blue' => 'promotion-item_blue', 'promotion-item_red' => 'promotion-item_red'))),
      'view_count'    => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'href'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'alt'           => new sfValidatorString(array('max_length' => 255)),
      'file'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_active'     => new sfValidatorBoolean(array('required' => false)),
      'src'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'src_mobile'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'position'      => new sfValidatorInteger(array('required' => false)),
      'positionpage'  => new sfValidatorChoice(array('choices' => array(0 => 'main_new_1', 1 => 'main_new_2', 2 => 'main_new_3', 3 => 'catalog_sex-igrushki-dlja-par_1', 4 => 'catalog_sex-igrushki-dlja-par_2', 5 => 'catalog_sex-igrushki-dlja-par_3', 6 => 'catalog_sex-igrushki-dlya-muzhchin_1', 7 => 'catalog_sex-igrushki-dlya-muzhchin_2', 8 => 'catalog_sex-igrushki-dlya-muzhchin_3', 9 => 'catalog_sex-igrushki-dlya-zhenschin_1', 10 => 'catalog_sex-igrushki-dlya-zhenschin_2', 11 => 'catalog_sex-igrushki-dlya-zhenschin_3', 12 => 'catalog_eroticheskoe-bele_1', 13 => 'catalog_eroticheskoe-bele_2', 14 => 'catalog_eroticheskoe-bele_3', 15 => 'catalog_intimnaya-kosmetika_1', 16 => 'catalog_intimnaya-kosmetika_2', 17 => 'catalog_intimnaya-kosmetika_3', 18 => 'catalog_preparaty_1', 19 => 'catalog_preparaty_2', 20 => 'catalog_preparaty_3', 21 => 'catalog_bdsm-i-fetish_1', 22 => 'catalog_bdsm-i-fetish_2', 23 => 'catalog_bdsm-i-fetish_3', 24 => 'catalog_aksessuary-dlya-seksa_1', 25 => 'catalog_aksessuary-dlya-seksa_2', 26 => 'catalog_aksessuary-dlya-seksa_3', 27 => 'catalog_dlya_novichkov_1', 28 => 'catalog_dlya_novichkov_2', 29 => 'catalog_dlya_novichkov_3', 30 => 'main_page', 31 => 'main_page_new', 32 => 'magazins_in_moscow', 33 => 'sl_sexopedia', 34 => 'sl_pairs', 35 => 'sl_man', 36 => 'sl_woman', 37 => 'sl_actions', 38 => 'category_skidki_do_60_percent', 39 => 'catalog_feb14_1', 40 => 'catalog_feb14_2', 41 => 'catalog_feb14_3', 42 => 'for-him'), 'required' => false)),
      'description'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'button'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_small'      => new sfValidatorBoolean(array('required' => false)),
      'is_onlymoscow' => new sfValidatorBoolean(array('required' => false)),
      'class'         => new sfValidatorChoice(array('choices' => array(0 => 'promotion-item_cyan', 1 => 'promotion-item_orange', 2 => 'promotion-item_blue', 3 => 'promotion-item_red'), 'required' => false)),
      'view_count'    => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('sliders[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Sliders';
  }

}
