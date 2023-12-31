<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version187 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('notification', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'user_id' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'type' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'product_id' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'dateevent' => 
             array(
              'type' => 'date',
              'length' => '25',
             ),
             'comment' => 
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
             'position' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             ), array(
             'indexes' => 
             array(
              'notification_position_sortable' => 
              array(
              'fields' => 
              array(
               0 => 'position',
              ),
              'type' => 'unique',
              ),
             ),
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
        $this->dropTable('notification');
    }
}