<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version106 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('city', array(
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
        $this->addColumn('pickpoint', 'city_id', 'integer', '8', array(
             ));
    }

    public function down()
    {
        $this->dropTable('city');
        $this->removeColumn('pickpoint', 'city_id');
    }
}