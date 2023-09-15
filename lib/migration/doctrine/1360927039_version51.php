<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version51 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('product_for_back_up', array(
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
             'code' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'content' => 
             array(
              'type' => 'clob',
              'length' => '',
             ),
             'price' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'bonus' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'old_price' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'discount' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'count' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'video' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'views_count' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'votes_count' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'rating' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'is_related' => 
             array(
              'type' => 'boolean',
              'default' => '0',
              'length' => '25',
             ),
             'is_public' => 
             array(
              'type' => 'boolean',
              'default' => '1',
              'length' => '25',
             ),
             'title' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'keywords' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'description' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'parents_id' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'id1c' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'generalcategory_id' => 
             array(
              'type' => 'integer',
              'length' => '8',
             ),
             'adult' => 
             array(
              'type' => 'boolean',
              'default' => '1',
              'length' => '25',
             ),
             'endaction' => 
             array(
              'type' => 'date',
              'length' => '25',
             ),
             'step' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => '',
              1 => '1 сутки',
              2 => '2 суток',
              3 => '3 суток',
              4 => '4 суток',
              5 => '5 суток',
              ),
              'length' => '',
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
             'slug' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             ), array(
             'indexes' => 
             array(
              'product_for_back_up_position_sortable' => 
              array(
              'fields' => 
              array(
               0 => 'position',
              ),
              'type' => 'unique',
              ),
              'product_for_back_up_sluggable' => 
              array(
              'fields' => 
              array(
               0 => 'slug',
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
        $this->dropTable('product_for_back_up');
    }
}