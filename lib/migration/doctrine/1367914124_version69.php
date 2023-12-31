<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version69 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('bonus_mailsend_log', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'mail' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'bonus' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'day' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'created_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'updated_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => 'utf8_unicode_ci',
             'charset' => 'utf8',
             ));
        $this->createTable('product_action_log', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'prodid' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'manid' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'count' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'discount' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'endaction' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'step' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'created_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'updated_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => 'utf8_unicode_ci',
             'charset' => 'utf8',
             ));
    }

    public function down()
    {
        $this->dropTable('bonus_mailsend_log');
        $this->dropTable('product_action_log');
    }
}