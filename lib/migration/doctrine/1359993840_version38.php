<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version38 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('article', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'name' => 
             array(
              'type' => 'string',
              'notnull' => '1',
              'unique' => '1',
              'length' => '255',
             ),
             'slug' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'content' => 
             array(
              'type' => 'clob',
              'length' => '',
             ),
             'is_public' => 
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
              'article_sluggable' => 
              array(
              'fields' => 
              array(
               0 => 'slug',
              ),
              'type' => 'unique',
              ),
              'article_position_sortable' => 
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
        $this->dropTable('article');
    }
}