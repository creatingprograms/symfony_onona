<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version155 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('horoscopesovm', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => '8',
              'autoincrement' => '1',
              'primary' => '1',
             ),
             'horoscope_m_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'horoscope_g_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'content' => 
             array(
              'type' => 'clob',
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
        $this->dropTable('horoscopesovm');
    }
}