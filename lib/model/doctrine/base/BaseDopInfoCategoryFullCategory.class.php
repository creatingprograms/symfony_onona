<?php

/**
 * BaseDopInfoCategoryFullCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $dop_info_category_full_id
 * @property integer $dop_info_category_id
 * @property DopInfoCategoryFull $DopInfoCategoryFull
 * @property DopInfoCategory $DopInfoCategory
 * 
 * @method integer                     getDopInfoCategoryFullId()     Returns the current record's "dop_info_category_full_id" value
 * @method integer                     getDopInfoCategoryId()         Returns the current record's "dop_info_category_id" value
 * @method DopInfoCategoryFull         getDopInfoCategoryFull()       Returns the current record's "DopInfoCategoryFull" value
 * @method DopInfoCategory             getDopInfoCategory()           Returns the current record's "DopInfoCategory" value
 * @method DopInfoCategoryFullCategory setDopInfoCategoryFullId()     Sets the current record's "dop_info_category_full_id" value
 * @method DopInfoCategoryFullCategory setDopInfoCategoryId()         Sets the current record's "dop_info_category_id" value
 * @method DopInfoCategoryFullCategory setDopInfoCategoryFull()       Sets the current record's "DopInfoCategoryFull" value
 * @method DopInfoCategoryFullCategory setDopInfoCategory()           Sets the current record's "DopInfoCategory" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDopInfoCategoryFullCategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('dop_info_category_full_category');
        $this->hasColumn('dop_info_category_full_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('dop_info_category_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('DopInfoCategoryFull', array(
             'local' => 'dop_info_category_full_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('DopInfoCategory', array(
             'local' => 'dop_info_category_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}