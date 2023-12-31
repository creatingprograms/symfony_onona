<?php

/**
 * BaseCompare
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property clob $products
 * @property clob $productsinfo
 * @property enum $rule
 * @property sfGuardUser $sfGuardUser
 * @property Doctrine_Collection $Compare
 * 
 * @method integer             getUserId()       Returns the current record's "user_id" value
 * @method clob                getProducts()     Returns the current record's "products" value
 * @method clob                getProductsinfo() Returns the current record's "productsinfo" value
 * @method enum                getRule()         Returns the current record's "rule" value
 * @method sfGuardUser         getSfGuardUser()  Returns the current record's "sfGuardUser" value
 * @method Doctrine_Collection getCompare()      Returns the current record's "Compare" collection
 * @method Compare             setUserId()       Sets the current record's "user_id" value
 * @method Compare             setProducts()     Sets the current record's "products" value
 * @method Compare             setProductsinfo() Sets the current record's "productsinfo" value
 * @method Compare             setRule()         Sets the current record's "rule" value
 * @method Compare             setSfGuardUser()  Sets the current record's "sfGuardUser" value
 * @method Compare             setCompare()      Sets the current record's "Compare" collection
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCompare extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('compare');
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('products', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('productsinfo', 'clob', null, array(
             'type' => 'clob',
             ));
        $this->hasColumn('rule', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Личный',
              1 => 'Доступен со ссылкой',
              2 => 'Публичный',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('sfGuardUser', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('Comments as Compare', array(
             'local' => 'id',
             'foreign' => 'compare_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}