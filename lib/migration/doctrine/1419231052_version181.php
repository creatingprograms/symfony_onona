<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version181 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('notification_category', array(
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
             'category_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'user_mail' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'is_enabled' => 
             array(
              'type' => 'boolean',
              'notnull' => '1',
              'default' => '1',
              'length' => '25',
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
              'notification_category_position_sortable' => 
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
        $this->dropTable('notification_category');
    }
}