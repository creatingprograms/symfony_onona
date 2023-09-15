<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version57 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('fast_order_log', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'code' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'name' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'username' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'mail' => 
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
        
        $this->dropTable('fast_order_log');
    }
}