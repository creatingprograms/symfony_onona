<?php

/**
 * BaseNotify
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property string $email
 * @property string $name
 * @property integer $product_id
 * @property Product $Product
 * 
 * @method integer getUserId()     Returns the current record's "user_id" value
 * @method string  getEmail()      Returns the current record's "email" value
 * @method string  getName()       Returns the current record's "name" value
 * @method integer getProductId()  Returns the current record's "product_id" value
 * @method Product getProduct()    Returns the current record's "Product" value
 * @method Notify  setUserId()     Sets the current record's "user_id" value
 * @method Notify  setEmail()      Sets the current record's "email" value
 * @method Notify  setName()       Sets the current record's "name" value
 * @method Notify  setProductId()  Sets the current record's "product_id" value
 * @method Notify  setProduct()    Sets the current record's "Product" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseNotify extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('notify');
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'primary' => true,
             'length' => 255,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('product_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Product', array(
             'local' => 'product_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}