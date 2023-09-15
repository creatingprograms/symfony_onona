<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version25 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('catalog', array(
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
              'length' => '255',
             ),
             'page_id' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'is_public' => 
             array(
              'type' => 'boolean',
              'default' => '1',
              'length' => '25',
             ),
             'img' => 
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
              'catalog_position_sortable' => 
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
        $this->createTable('category_catalog', array(
             'category_id' => 
             array(
              'type' => 'integer',
              'primary' => '1',
              'length' => '8',
             ),
             'catalog_id' => 
             array(
              'type' => 'integer',
              'primary' => '1',
              'length' => '8',
             ),
             ), array(
             'primary' => 
             array(
              0 => 'category_id',
              1 => 'catalog_id',
             ),
             'collate' => 'utf8_unicode_ci',
             'charset' => 'utf8',
             ));
    }

    public function down()
    {
        $this->dropTable('catalog');
        $this->dropTable('category_catalog');
    }
}