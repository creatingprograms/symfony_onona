<?php

/**
 * BaseSenduser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $product_id
 * @property string $name
 * @property string $mail
 * @property string $phone
 * @property boolean $is_send
 * @property boolean $is_manager
 * @property Product $Product
 * 
 * @method integer  getProductId()  Returns the current record's "product_id" value
 * @method string   getName()       Returns the current record's "name" value
 * @method string   getMail()       Returns the current record's "mail" value
 * @method string   getPhone()      Returns the current record's "phone" value
 * @method boolean  getIsSend()     Returns the current record's "is_send" value
 * @method boolean  getIsManager()  Returns the current record's "is_manager" value
 * @method Product  getProduct()    Returns the current record's "Product" value
 * @method Senduser setProductId()  Sets the current record's "product_id" value
 * @method Senduser setName()       Sets the current record's "name" value
 * @method Senduser setMail()       Sets the current record's "mail" value
 * @method Senduser setPhone()      Sets the current record's "phone" value
 * @method Senduser setIsSend()     Sets the current record's "is_send" value
 * @method Senduser setIsManager()  Sets the current record's "is_manager" value
 * @method Senduser setProduct()    Sets the current record's "Product" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSenduser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('senduser');
        $this->hasColumn('product_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('mail', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('phone', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_send', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('is_manager', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
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