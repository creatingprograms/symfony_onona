<?php

/**
 * BaseBanners
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $href
 * @property string $alt
 * @property string $description
 * @property string $button
 * @property string $src
 * @property string $src_mobile
 * @property boolean $is_public
 * @property boolean $is_rotation
 * @property enum $class
 * @property integer $view_count
 * @property enum $position
 * 
 * @method string  getHref()        Returns the current record's "href" value
 * @method string  getAlt()         Returns the current record's "alt" value
 * @method string  getDescription() Returns the current record's "description" value
 * @method string  getButton()      Returns the current record's "button" value
 * @method string  getSrc()         Returns the current record's "src" value
 * @method string  getSrcMobile()   Returns the current record's "src_mobile" value
 * @method boolean getIsPublic()    Returns the current record's "is_public" value
 * @method boolean getIsRotation()  Returns the current record's "is_rotation" value
 * @method enum    getClass()       Returns the current record's "class" value
 * @method integer getViewCount()   Returns the current record's "view_count" value
 * @method enum    getPosition()    Returns the current record's "position" value
 * @method Banners setHref()        Sets the current record's "href" value
 * @method Banners setAlt()         Sets the current record's "alt" value
 * @method Banners setDescription() Sets the current record's "description" value
 * @method Banners setButton()      Sets the current record's "button" value
 * @method Banners setSrc()         Sets the current record's "src" value
 * @method Banners setSrcMobile()   Sets the current record's "src_mobile" value
 * @method Banners setIsPublic()    Sets the current record's "is_public" value
 * @method Banners setIsRotation()  Sets the current record's "is_rotation" value
 * @method Banners setClass()       Sets the current record's "class" value
 * @method Banners setViewCount()   Sets the current record's "view_count" value
 * @method Banners setPosition()    Sets the current record's "position" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseBanners extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('banners');
        $this->hasColumn('href', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('alt', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('button', 'string', 255, array(
             'type' => 'string',
             'default' => 'Условия акции',
             'length' => 255,
             ));
        $this->hasColumn('src', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('src_mobile', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_public', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('is_rotation', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('class', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'block-info_1',
              1 => 'block-info_2',
             ),
             ));
        $this->hasColumn('view_count', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => false,
             'length' => 8,
             ));
        $this->hasColumn('position', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Главная новый 2',
              1 => 'Главная новый 3',
              2 => 'Двойной Главная',
              3 => 'Главная 1',
              4 => 'Главная 2',
              5 => 'Категория',
              6 => 'Каталог',
              7 => 'Карточка товара',
              8 => 'Корзина',
              9 => 'catalog',
              10 => 'sex-igrushki-dlja-par',
              11 => 'sex-igrushki-dlya-muzhchin',
              12 => 'sex-igrushki-dlya-zhenschin',
              13 => 'BDSM-i-fetish',
              14 => 'intimnaya-kosmetika',
              15 => 'preparaty',
              16 => 'eroticheskoe-bele',
              17 => 'aksessuary-dlya-seksa',
              18 => 'dlya_novichkov',
              19 => 'slider_sex-igrushki-dlja-par',
              20 => 'slider_sex-igrushki-dlya-muzhchin',
              21 => 'slider_sex-igrushki-dlya-zhenschin',
              22 => 'slider_bdsm-i-fetish',
              23 => 'slider_intimnaya-kosmetika',
              24 => 'slider_preparaty',
              25 => 'slider_eroticheskoe-bele',
              26 => 'slider_aksessuary-dlya-seksa',
              27 => 'slider_dlya_novichkov',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}