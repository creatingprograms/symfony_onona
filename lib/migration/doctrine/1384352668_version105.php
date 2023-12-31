<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version105 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('pickpoint', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'address' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'card' => 
             array(
              'type' => 'boolean',
              'default' => '0',
              'length' => '25',
             ),
             'cash' => 
             array(
              'type' => 'boolean',
              'default' => '0',
              'length' => '25',
             ),
             'citiid' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'citiname' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'citiownerid' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'countryname' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'house' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'dopid' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'indescription' => 
             array(
              'type' => 'clob',
              'length' => '',
             ),
             'indoorplace' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'latitude' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'longitude' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'metro' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'name' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'number' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'outdescription' => 
             array(
              'type' => 'clob',
              'length' => '',
             ),
             'ownerid' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'postcode' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'region' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'status' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'street' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'typetitle' => 
             array(
              'type' => 'string',
              'length' => '255',
             ),
             'worktime' => 
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
        $this->dropTable('pickpoint');
    }
}