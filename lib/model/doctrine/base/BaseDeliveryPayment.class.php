<?php

/**
 * BaseDeliveryPayment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $delivery_id
 * @property integer $payment_id
 * @property Delivery $Delivery
 * @property Payment $Payment
 * 
 * @method integer         getDeliveryId()  Returns the current record's "delivery_id" value
 * @method integer         getPaymentId()   Returns the current record's "payment_id" value
 * @method Delivery        getDelivery()    Returns the current record's "Delivery" value
 * @method Payment         getPayment()     Returns the current record's "Payment" value
 * @method DeliveryPayment setDeliveryId()  Sets the current record's "delivery_id" value
 * @method DeliveryPayment setPaymentId()   Sets the current record's "payment_id" value
 * @method DeliveryPayment setDelivery()    Sets the current record's "Delivery" value
 * @method DeliveryPayment setPayment()     Sets the current record's "Payment" value
 * 
 * @package    test
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDeliveryPayment extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('delivery_payment');
        $this->hasColumn('delivery_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
        $this->hasColumn('payment_id', 'integer', 8, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Delivery', array(
             'local' => 'delivery_id',
             'foreign' => 'id'));

        $this->hasOne('Payment', array(
             'local' => 'payment_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $sortable0 = new Doctrine_Template_Sortable();
        $this->actAs($timestampable0);
        $this->actAs($sortable0);
    }
}